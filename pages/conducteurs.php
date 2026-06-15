<?php
$editId = $_GET['edit'] ?? 0;
$editNom = $editPrenom = $editPermis = '';
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM conducteurs WHERE id = ?");
    $stmt->execute([$editId]);
    $cond = $stmt->fetch();
    if ($cond) {
        $editNom = $cond['nom'];
        $editPrenom = $cond['prenom'];
        $editPermis = $cond['num_permis'];
    }
}
?>
<h2>Gestion des conducteurs</h2>
<form method="post">
    <?php if ($editId): ?>
        <input type="hidden" name="id" value="<?= $editId ?>">
    <?php endif; ?>
    <input type="text" name="nom" placeholder="Nom" required value="<?= htmlspecialchars($editNom) ?>">
    <input type="text" name="prenom" placeholder="Prénom" required value="<?= htmlspecialchars($editPrenom) ?>">
    <input type="text" name="num_permis" placeholder="Numéro de permis" required value="<?= htmlspecialchars($editPermis) ?>">
    <button type="submit" name="<?= $editId ? 'edit_conducteur' : 'add_conducteur' ?>">
        <?= $editId ? 'Modifier' : 'Ajouter' ?>
    </button>
</form>

<h3>Liste des conducteurs</h3>
<table>
    <thead>
        <tr><th>ID</th><th>Nom</th><th>Prénom</th><th>N° permis</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach (getAllConducteurs($pdo) as $cond): ?>
        <tr>
            <td><?= $cond['id'] ?></td>
            <td><?= htmlspecialchars($cond['nom']) ?></td>
            <td><?= htmlspecialchars($cond['prenom']) ?></td>
            <td><?= htmlspecialchars($cond['num_permis']) ?></td>
            <td>
                <a href="?page=conducteurs&edit=<?= $cond['id'] ?>">Modifier</a> |
                <a href="?page=conducteurs&action=delete&id=<?= $cond['id'] ?>" onclick="return confirm('Supprimer ce conducteur ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>