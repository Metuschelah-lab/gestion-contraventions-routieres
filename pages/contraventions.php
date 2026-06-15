<?php
// Gestion du paiement rapide depuis la liste
if (isset($_GET['payer'])) {
    $id = (int)$_GET['payer'];
    updateContraventionStatut($pdo, $id, 'payee');
    header("Location: ?page=contraventions&success=payee");
    exit;
}

// Récupération des listes pour les menus déroulants (véhicules et agents)
$vehicules = getAllVehicules($pdo);
$agents = getAllAgents($pdo);

$editId = $_GET['edit'] ?? 0;
$editVehicule = $editAgent = $editMontant = $editStatut = '';
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM contraventions WHERE id = ?");
    $stmt->execute([$editId]);
    $ct = $stmt->fetch();
    if ($ct) {
        $editVehicule = $ct['vehicule_id'];
        $editAgent = $ct['agent_id'];
        $editMontant = $ct['montant'];
        $editStatut = $ct['statut'];
    }
}
?>
<h2>Gestion des contraventions</h2>
<form method="post">
    <?php if ($editId): ?>
        <input type="hidden" name="id" value="<?= $editId ?>">
    <?php endif; ?>
    <select name="vehicule_id" required>
        <option value="">Choisir un véhicule</option>
        <?php foreach ($vehicules as $v): ?>
            <option value="<?= $v['id'] ?>" <?= ($editVehicule == $v['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($v['immatriculation']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="agent_id" required>
        <option value="">Choisir un agent</option>
        <?php foreach ($agents as $a): ?>
            <option value="<?= $a['id'] ?>" <?= ($editAgent == $a['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($a['nom']) ?> (<?= $a['matricule'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <input type="number" step="0.01" name="montant" placeholder="Montant" required value="<?= htmlspecialchars($editMontant) ?>">
    <select name="statut">
        <option value="impayee" <?= ($editStatut == 'impayee') ? 'selected' : '' ?>>Impayée</option>
        <option value="payee" <?= ($editStatut == 'payee') ? 'selected' : '' ?>>Payée</option>
    </select>
    <button type="submit" name="<?= $editId ? 'edit_contravention' : 'add_contravention' ?>">
        <?= $editId ? 'Modifier' : 'Ajouter' ?>
    </button>
</form>

<h3>Liste des contraventions</h3>
<table>
    <thead>
        <tr><th>ID</th><th>Date émission</th><th>Montant</th><th>Statut</th><th>Véhicule</th><th>Agent</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach (getAllContraventions($pdo) as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= $c['date_emission'] ?></td>
            <td><?= $c['montant'] ?> €</td>
            <td><?= $c['statut'] == 'payee' ? '✅ Payée' : '❌ Impayée' ?></td>
            <td>
                <?php 
                $stmt = $pdo->prepare("SELECT immatriculation FROM vehicules WHERE id = ?");
                $stmt->execute([$c['vehicule_id']]);
                $v = $stmt->fetch();
                echo $v ? htmlspecialchars($v['immatriculation']) : 'Inconnu';
                ?>
            </td>
            <td>
                <?php 
                $stmt = $pdo->prepare("SELECT nom FROM agents WHERE id = ?");
                $stmt->execute([$c['agent_id']]);
                $a = $stmt->fetch();
                echo $a ? htmlspecialchars($a['nom']) : 'Inconnu';
                ?>
            </td>
           <td>
    <a href="avis.php?id=<?= $c['id'] ?>" target="_blank">📄 Avis</a> |
    <?php if ($c['statut'] == 'impayee'): ?>
        <a href="?page=contraventions&payer=<?= $c['id'] ?>" onclick="return confirm('Marquer comme payée ?')">💰 Payer</a> |
    <?php else: ?>
        <a href="recu.php?id=<?= $c['id'] ?>" target="_blank">🧾 Reçu</a>
 |
    <?php endif; ?>
    <a href="?page=contraventions&edit=<?= $c['id'] ?>">✏️ Modifier</a> |
    <a href="?page=contraventions&action=delete&id=<?= $c['id'] ?>" onclick="return confirm('Supprimer cette contravention ?')">🗑️ Supprimer</a>
</td>
    <?php endforeach; ?>
    </tbody>
</table>