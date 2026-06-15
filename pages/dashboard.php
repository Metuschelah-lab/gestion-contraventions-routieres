<div class="dashboard">
    <div class="card">
        <h3><?= countContraventionsByStatut($pdo, 'impayee') ?></h3>
        <p>Contraventions impayées</p>
    </div>
    <div class="card">
        <h3><?= countContraventionsByStatut($pdo, 'payee') ?></h3>
        <p>Contraventions payées</p>
    </div>
    <div class="card">
        <h3><?= totalMontantImpaye($pdo) ?> €</h3>
        <p>Montant total dû</p>
    </div>
</div>
<h2>Dernières contraventions</h2>
<table>
    <thead>
        <tr><th>ID</th><th>Date</th><th>Montant</th><th>Statut</th><th>Véhicule</th><th>Agent</th></tr>
    </thead>
    <tbody>
    <?php 
    $stmt = $pdo->query("SELECT * FROM contraventions ORDER BY id DESC LIMIT 5");
    while($row = $stmt->fetch()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['date_emission'] ?></td>
            <td><?= $row['montant'] ?> €</td>
            <td><?= $row['statut'] ?></td>
            <td><?= $row['vehicule_id'] ?></td>
            <td><?= $row['agent_id'] ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>