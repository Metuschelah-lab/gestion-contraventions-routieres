<?php
// Statistiques globales pour l'admin
$totalContraventions = $pdo->query("SELECT COUNT(*) FROM contraventions")->fetchColumn();
$impayees = $pdo->query("SELECT COUNT(*) FROM contraventions WHERE statut = 'impayee'")->fetchColumn();
$totalDu = $pdo->query("SELECT SUM(montant) FROM contraventions WHERE statut = 'impayee'")->fetchColumn() ?: 0;

$totalAgents = $pdo->query("SELECT COUNT(*) FROM agents")->fetchColumn();
$totalVehicules = $pdo->query("SELECT COUNT(*) FROM vehicules")->fetchColumn();
$totalConducteurs = $pdo->query("SELECT COUNT(*) FROM conducteurs")->fetchColumn();

// Dernières contraventions
$stmt = $pdo->query("SELECT c.*, a.nom as agent_nom, v.immatriculation 
                     FROM contraventions c
                     LEFT JOIN agents a ON c.agent_id = a.id
                     LEFT JOIN vehicules v ON c.vehicule_id = v.id
                     ORDER BY c.id DESC LIMIT 5");
$recentes = $stmt->fetchAll();
?>
<div class="dashboard">
    <div class="card"><h3><?= $totalContraventions ?></h3><p>Total contraventions</p></div>
    <div class="card"><h3><?= $impayees ?></h3><p>Impayées</p></div>
    <div class="card"><h3><?= number_format($totalDu, 2) ?> $</h3><p>Montant total dû</p></div>
</div>

<div style="display: flex; gap: 20px; margin-bottom: 30px;">
    <div class="card" style="flex:1;"><h3><?= $totalAgents ?></h3><p>Agents enregistrés</p></div>
    <div class="card" style="flex:1;"><h3><?= $totalVehicules ?></h3><p>Véhicules enregistrés</p></div>
    <div class="card" style="flex:1;"><h3><?= $totalConducteurs ?></h3><p>Conducteurs enregistrés</p></div>
</div>

<h3>Dernières contraventions</h3>
<table>
    <thead>
        <tr><th>ID</th><th>Date</th><th>Véhicule</th><th>Montant</th><th>Statut</th><th>Agent</th></tr>
    </thead>
    <tbody>
    <?php if (empty($recentes)): ?>
        <tr><td colspan="6">Aucune contravention pour le moment.</td></tr>
    <?php else: ?>
        <?php foreach($recentes as $c): ?>
        <tr>
            <td>#<?= $c['id'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($c['date_emission'])) ?></td>
            <td><?= htmlspecialchars($c['immatriculation'] ?? '?') ?></td>
            <td><?= number_format($c['montant'], 2) ?> $S</td>
            <td><?= $c['statut'] == 'payee' ? '✅ Payée' : '❌ Impayée' ?></td>
            <td><?= htmlspecialchars($c['agent_nom'] ?? '?') ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>