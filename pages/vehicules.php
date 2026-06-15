<?php
// Récupération des données pour l'édition
$editId = $_GET['edit'] ?? 0;
$editImmat = $editConducteur = '';
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
    $stmt->execute([$editId]);
    $vehicule = $stmt->fetch();
    if ($vehicule) {
        $editImmat = $vehicule['immatriculation'];
        $editConducteur = $vehicule['conducteur_id'];
    }
}
?>
<h2>Gestion des véhicules</h2>
<form method="post">
    <?php if ($editId): ?>
        <input type="hidden" name="id" value="<?= $editId ?>">
    <?php endif; ?>
    <input type="text" name="immatriculation" placeholder="Immatriculation (ex: AB-123-CD)" required value="<?= htmlspecialchars($editImmat) ?>">
    <select name="conducteur_id">
        <option value="">Sans conducteur</option>
        <?php 
        $conducteurs = getAllConducteurs($pdo);
        foreach ($conducteurs as $cond): ?>
            <option value="<?= $cond['id'] ?>" <?= ($editConducteur == $cond['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cond['nom'] . ' ' . $cond['prenom']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="<?= $editId ? 'edit_vehicule' : 'add_vehicule' ?>">
        <?= $editId ? 'Modifier' : 'Ajouter' ?>
    </button>
</form>

<h3>Liste des véhicules</h3>
<table>
    <thead>
        <tr><th>ID</th><th>Immatriculation</th><th>Conducteur</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach (getAllVehicules($pdo) as $veh): ?>
        <?php 
        // Récupérer le nom du conducteur associé (si existant)
        $nomConducteur = '';
        if ($veh['conducteur_id']) {
            $stmt = $pdo->prepare("SELECT nom, prenom FROM conducteurs WHERE id = ?");
            $stmt->execute([$veh['conducteur_id']]);
            $cond = $stmt->fetch();
            if ($cond) $nomConducteur = $cond['nom'] . ' ' . $cond['prenom'];
        }
        ?>
        <tr>
            <td><?= $veh['id'] ?></td>
            <td><?= htmlspecialchars($veh['immatriculation']) ?></td>
            <td><?= htmlspecialchars($nomConducteur) ?: 'Aucun' ?></td>
            <td>
                <a href="?page=vehicules&edit=<?= $veh['id'] ?>">Modifier</a> |
                <a href="?page=vehicules&action=delete&id=<?= $veh['id'] ?>" onclick="return confirm('Supprimer ce véhicule ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>