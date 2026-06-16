<?php
if ($_SESSION['user_role'] != 'admin') {
    header("Location: agent_dashboard.php");
    exit;
}

// Ajout d'un agent + création automatique du compte utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_agent'])) {
    $nom = trim($_POST['nom']);
    $matricule = trim($_POST['matricule']);
    $identifiant = trim($_POST['identifiant']);
    $mot_de_passe = md5($_POST['mot_de_passe']);

    $erreur = '';

    // Vérifier si l'identifiant existe déjà dans utilisateurs
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE identifiant = ?");
    $check->execute([$identifiant]);
    if ($check->fetch()) {
        $erreur = "L'identifiant '$identifiant' est déjà utilisé.";
    }

    // Vérifier si le nom existe déjà dans agents
    $check2 = $pdo->prepare("SELECT id FROM agents WHERE nom = ?");
    $check2->execute([$nom]);
    if ($check2->fetch()) {
        $erreur .= " Un agent avec le nom '$nom' existe déjà.";
    }

    if (empty($erreur)) {
        // 1. Ajouter l'agent
        $stmt = $pdo->prepare("INSERT INTO agents (nom, matricule) VALUES (?, ?)");
        if ($stmt->execute([$nom, $matricule])) {
            $agentId = $pdo->lastInsertId();

            // 2. Ajouter le compte utilisateur
            $stmt2 = $pdo->prepare("INSERT INTO utilisateurs (identifiant, mot_de_passe, role, nom_complet) VALUES (?, ?, 'agent', ?)");
            if ($stmt2->execute([$identifiant, $mot_de_passe, $nom])) {
                header("Location: index.php?page=agents&success=1");
                exit;
            } else {
                // En cas d'échec, on supprime l'agent pour rester cohérent
                $pdo->prepare("DELETE FROM agents WHERE id = ?")->execute([$agentId]);
                $erreur = "Échec de la création du compte utilisateur. Vérifiez les champs.";
            }
        } else {
            $erreur = "Échec de l'ajout de l'agent (matricule peut-être en double).";
        }
    }

    if (!empty($erreur)) {
        echo "<p style='color:red; font-weight:bold; background:#fdd; padding:10px; border-radius:5px;'>❌ $erreur</p>";
    }
}

// Suppression d'un agent (supprime aussi son compte utilisateur associé)
if (isset($_GET['delete'])) {
    $agent = $pdo->prepare("SELECT nom FROM agents WHERE id = ?");
    $agent->execute([(int)$_GET['delete']]);
    $nom = $agent->fetchColumn();
    if ($nom) {
        $pdo->prepare("DELETE FROM utilisateurs WHERE nom_complet = ?")->execute([$nom]);
    }
    $pdo->prepare("DELETE FROM agents WHERE id = ?")->execute([(int)$_GET['delete']]);
    header("Location: index.php?page=agents&success=1");
    exit;
}

$agents = $pdo->query("SELECT * FROM agents ORDER BY id")->fetchAll();
?>
<h2>Gestion des agents</h2>

<!-- Formulaire d'ajout -->
<form method="post" style="margin-bottom:30px;">
    <input type="text" name="nom" placeholder="Nom complet (ex: Jean)" required>
    <input type="text" name="matricule" placeholder="Matricule (ex: A005)" required>
    <input type="text" name="identifiant" placeholder="Identifiant de connexion (ex: jeanAgent)" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <button type="submit" name="add_agent">Ajouter l'agent</button>
</form>

<!-- Liste des agents -->
<h3>Liste des agents</h3>
<table>
    <thead>
        <tr><th>ID</th><th>Nom</th><th>Matricule</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach($agents as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= htmlspecialchars($a['nom']) ?></td>
            <td><?= htmlspecialchars($a['matricule']) ?></td>
            <td>
                <a href="?page=agents&delete=<?= $a['id'] ?>" onclick="return confirm('Supprimer cet agent et son compte ?')">🗑️ Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>