<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'agent') {
    header("Location: login.php");
    exit;
}
require_once 'config.php';

$agentId = $_SESSION['user_agent_id'] ?? 0;

// Récupération du matricule
$stmtMat = $pdo->prepare("SELECT matricule FROM agents WHERE id = ?");
$stmtMat->execute([$agentId]);
$matricule = $stmtMat->fetchColumn();
$matricule = $matricule ?: 'Officier Police Judiciaire';

// Traitement du formulaire (synchronisation toujours active, mais message simplifié)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plaque'])) {
    $date = date('Y-m-d H:i:s');

    // Gérer le conducteur
    $stmt = $pdo->prepare("SELECT id FROM conducteurs WHERE nom = ? AND telephone = ?");
    $stmt->execute([$_POST['proprietaire'], $_POST['telephone']]);
    $conducteurId = $stmt->fetchColumn();
    if (!$conducteurId) {
        $stmt = $pdo->prepare("INSERT INTO conducteurs (nom, prenom, telephone, adresse, num_permis) VALUES (?, '', ?, ?, '')");
        $stmt->execute([$_POST['proprietaire'], $_POST['telephone'], $_POST['adresse']]);
        $conducteurId = $pdo->lastInsertId();
    }

    // Gérer le véhicule
    $stmt = $pdo->prepare("SELECT id FROM vehicules WHERE immatriculation = ?");
    $stmt->execute([$_POST['plaque']]);
    $vehiculeId = $stmt->fetchColumn();
    if (!$vehiculeId) {
        $stmt = $pdo->prepare("INSERT INTO vehicules (immatriculation, marque, conducteur_id) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['plaque'], $_POST['marque'], $conducteurId]);
        $vehiculeId = $pdo->lastInsertId();
    } else {
        $stmt = $pdo->prepare("UPDATE vehicules SET conducteur_id = ? WHERE id = ?");
        $stmt->execute([$conducteurId, $vehiculeId]);
    }

    // Insérer la contravention
    $stmt = $pdo->prepare("INSERT INTO contraventions (date_emission, montant, statut, agent_id, vehicule_id, plaque, marque, proprietaire, telephone, adresse, infraction_texte)
                            VALUES (?, ?, 'impayee', ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $date,
        $_POST['montant'],
        $agentId,
        $vehiculeId,
        $_POST['plaque'],
        $_POST['marque'],
        $_POST['proprietaire'],
        $_POST['telephone'],
        $_POST['adresse'],
        $_POST['infraction_texte']
    ]);

    $success = "✅ PV enregistré avec succès !";
}

// Récupération des PV de l'agent
$stmt = $pdo->prepare("SELECT * FROM contraventions WHERE agent_id = ? ORDER BY id DESC");
$stmt->execute([$agentId]);
$contraventions = $stmt->fetchAll();
$nbPv = count($contraventions);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Espace Agent - PV</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f4f4f4; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .deconnexion { float: right; margin-bottom: 20px; }
        .deconnexion a { background: #e74c3c; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
        .deconnexion a:hover { background: #c0392b; }
        form input, form textarea { width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ddd; border-radius: 5px; }
        form button { background: #2c3e50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #2c3e50; color: white; }
        .success { background: #d4edda; padding: 10px; border-radius: 5px; color: #155724; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="deconnexion">
        <span>👤 <?= htmlspecialchars($_SESSION['user_nom']) ?> (Agent) - Matricule : <?= htmlspecialchars($matricule) ?></span>
        <a href="logout.php">Déconnexion</a>
    </div>

    <h2>Ravi de vous revoir, Officier Police Judiciaire !</h2>

    <?php if (isset($success)): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <h3>Dresser un Procès-Verbal</h3>
    <form method="post">
        <input type="text" name="plaque" placeholder="Plaque d'immatriculation (ex: 1234AB/22)" required>
        <input type="text" name="marque" placeholder="Marque & Modèle (ex: Toyota Hilux)">
        <input type="text" name="proprietaire" placeholder="Nom du Propriétaire" required>
        <input type="text" name="telephone" placeholder="Téléphone (ex: +243...)" required>
        <input type="text" name="adresse" placeholder="Adresse (Quartier, Avenue, N°)" required>
        <textarea name="infraction_texte" placeholder="Description de l'infraction (ex: Dépassement non autorisé)" required></textarea>
        <input type="number" step="0.01" name="montant" placeholder="Montant de l'amende ($)" required>
        <button type="submit">Enregistrer et Émettre le PV</button>
    </form>

    <h3>Registre des infractions enregistrées</h3>
    <table>
        <thead>
            <tr><th>ID</th><th>Date</th><th>Plaque</th><th>Infraction</th><th>Amende</th><th>Statut</th><th>Agent</th><th>Avis</th></tr>
        </thead>
        <tbody>
        <?php if ($nbPv == 0): ?>
            <tr><td colspan="8" style="text-align:center;">Aucun PV émis pour le moment.</td></tr>
        <?php else: ?>
            <?php foreach($contraventions as $c): ?>
            <tr>
                <td>#<?= $c['id'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($c['date_emission'])) ?></td>
                <td><?= htmlspecialchars($c['plaque']) ?></td>
                <td><?= htmlspecialchars($c['infraction_texte']) ?></td>
                <td><?= number_format($c['montant'],2) ?> $</td>
                <td><?= $c['statut'] == 'payee' ? 'Payée' : 'Émis' ?></td>
                <td><?= htmlspecialchars($matricule) ?></td>
                <td><a href="avis.php?id=<?= $c['id'] ?>" target="_blank">📄 Avis</a></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <p><strong><?= $nbPv ?> PV émis</strong></p>
</div>
</body>
</html>