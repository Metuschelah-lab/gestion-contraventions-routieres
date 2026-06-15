<?php
require_once 'functions.php';
if (!isset($_GET['id'])) die("Aucune contravention spécifiée.");
$id = (int)$_GET['id'];
$contravention = getContraventionDetails($pdo, $id);
if (!$contravention || $contravention['statut'] != 'payee') die("Reçu disponible uniquement pour les contraventions payées.");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Reçu n°<?= $id ?></title>
<style>
body { font-family: 'Courier New', monospace; margin: 20px; }
.recu { width: 700px; margin: auto; border: 1px solid #ccc; padding: 20px; background: #f9f9f9; }
h2 { text-align: center; color: green; }
.montant { font-size: 1.3em; font-weight: bold; }
@media print { button { display: none; } }
</style>
</head>
<body>
<div class="recu">
    <h2>QUITTANCE DE PAIEMENT</h2>
    <p>Nous soussignés, l'Agent verbalisateur, attestons avoir reçu le paiement de l'amende afférente à la contravention n° <?= $id ?>.</p>
    <p><strong>Montant réglé :</strong> <?= number_format($contravention['montant'],2) ?> €</p>
    <p><strong>Date du paiement :</strong> <?= date('d/m/Y H:i:s') ?></p>
    <p><strong>Véhicule :</strong> <?= htmlspecialchars($contravention['immatriculation']) ?></p>
    <p><strong>Conducteur :</strong> <?= htmlspecialchars($contravention['cond_nom'] ?? 'Non précisé') ?></p>
    <p>Cachet et signature : _________________</p>
    <p style="text-align:center;">Ce reçu vous est délivré pour valoir acquit.</p>
</div>
<button onclick="window.print()">Imprimer</button>
</body>
</html>