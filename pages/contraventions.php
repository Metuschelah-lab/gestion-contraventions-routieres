<?php
// Vérification : si un agent essaie d'accéder directement, on le vire
if ($_SESSION['user_role'] != 'admin') {
    header("Location: agent_dashboard.php");
    exit;
}

// Récupération de toutes les contraventions avec le matricule de l'agent
$stmt = $pdo->query("
    SELECT c.*, 
           a.matricule as agent_matricule
    FROM contraventions c
    LEFT JOIN agents a ON c.agent_id = a.id
    ORDER BY c.id DESC
");
$contraventions = $stmt->fetchAll();
?>
<h2>Liste complète des contraventions</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Montant</th>
            <th>Statut</th>
            <th>Véhicule</th>
            <th>Agent</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($contraventions as $c): ?>
        <tr>
            <td>#<?= $c['id'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($c['date_emission'])) ?></td>
            <td><?= number_format($c['montant'], 2) ?> $</td>
            <td><?= $c['statut'] == 'payee' ? '✅ Payée' : '❌ Impayée' ?></td>
            <td>
                <?php
                // Si la plaque est renseignée (PV agent), on l'affiche
                if (!empty($c['plaque'])) {
                    echo htmlspecialchars($c['plaque']);
                } elseif (!empty($c['vehicule_id'])) {
                    // Sinon, on cherche l'immatriculation du véhicule
                    $stmtV = $pdo->prepare("SELECT immatriculation FROM vehicules WHERE id = ?");
                    $stmtV->execute([$c['vehicule_id']]);
                    $v = $stmtV->fetch();
                    echo $v ? htmlspecialchars($v['immatriculation']) : '—';
                } else {
                    echo '—';
                }
                ?>
            </td>
            <td>
                <?php
                // Si l'agent a un matricule (table agents), on l'affiche
                if (!empty($c['agent_matricule'])) {
                    echo htmlspecialchars($c['agent_matricule']);
                } elseif (!empty($c['agent_id'])) {
                    // Sinon on cherche son matricule
                    $stmtA = $pdo->prepare("SELECT matricule FROM agents WHERE id = ?");
                    $stmtA->execute([$c['agent_id']]);
                    $a = $stmtA->fetch();
                    echo $a ? htmlspecialchars($a['matricule']) : 'Agent inconnu';
                } else {
                    // Cas d'un agent terrain qui n'est pas dans la table agents (ancien système)
                    echo 'Officier Police Judiciaire';
                }
                ?>
            </td>
            <td>
                <?php if ($c['statut'] == 'impayee'): ?>
                    <a href="?page=contraventions&payer=<?= $c['id'] ?>" onclick="return confirm('Marquer comme payée ?')">💰 Payer</a> |
                <?php endif; ?>
                <a href="?page=contraventions&edit=<?= $c['id'] ?>">✏️ Modifier</a> |
                <a href="?page=contraventions&action=delete&id=<?= $c['id'] ?>" onclick="return confirm('Supprimer cette contravention ?')">🗑️ Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>