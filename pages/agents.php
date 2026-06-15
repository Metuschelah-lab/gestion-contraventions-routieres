<?php
$editNom = $editMatricule = '';
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM agents WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
    if ($edit) {
        $editNom = $edit['nom'];
        $editMatricule = $edit['matricule'];
    }
}
?>
<h2>Gestion des agents</h2>
<form method="post">
    <input type="hidden" name="id" value="<?= $_GET['edit'] ?? '' ?>">
    <input type="text" name="nom" placeholder="Nom" required value="<?= $editNom ?? '' ?>">
    <input type="text" name="matricule" placeholder="Matricule" required value="<?= $editMatricule ?? '' ?>">
    <button type="submit" name="<?= isset($_GET['edit']) ? 'edit_agent' : 'add_agent' ?>">
        <?= isset($_GET['edit']) ? 'Modifier' : 'Ajouter' ?>
    </button>
</form>
<table>
    <thead><tr><th>ID</th><th>Nom</th><th>Matricule</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach(getAllAgents($pdo) as $agent): ?>
        <tr>
            <td><?= $agent['id'] ?></td>
            <td><?= htmlspecialchars($agent['nom']) ?></td>
            <td><?= htmlspecialchars($agent['matricule']) ?></td>
            <td>
                <a href="?page=agents&edit=<?= $agent['id'] ?>">Modifier</a> |
                <a href="?page=agents&action=delete&id=<?= $agent['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>