<?php
require_once 'functions.php';
if (!isset($_GET['id'])) die("Aucune contravention spécifiée.");
$id = (int)$_GET['id'];
$contravention = getContraventionDetails($pdo, $id);
if (!$contravention) die("Contravention introuvable.");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Avis n°<?= $id ?></title>
<style>
body { font-family: 'Courier New', monospace; margin: 20px; }
.ticket { width: 800px; margin: auto; border: 2px solid #000; padding: 20px; }
h1 { text-align: center; color: red; }
.montant { font-size: 1.5em; font-weight: bold; }
@media print { button { display: none; } }
</style>
</head>
<body>
<div class="ticket">
    <h1>AVIS DE CONTRAVENTION</h1>
    <p><strong>N° PV :</strong> <?= $contravention['id'] ?></p>
    <p><strong>Date :</strong> <?= $contravention['date_emission'] ?></p>
    <p><strong>Véhicule :</strong> <?= htmlspecialchars($contravention['immatriculation']) ?></p>
    <p><strong>Conducteur :</strong> <?= htmlspecialchars($contravention['cond_nom'] ?? 'Non renseigné') ?></p>
    <p><strong>Agent :</strong> <?= htmlspecialchars($contravention['agent_nom']) ?></p>
    <p class="montant"><strong>Montant :</strong> <?= number_format($contravention['montant'],2) ?> €</p>
    <p><strong>Statut :</strong> <?= $contravention['statut'] ?></p>
</div>
<button onclick="window.print()">Imprimer</button>
</body>
</html>