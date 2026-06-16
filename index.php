<?php
require_once 'functions.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Si l'utilisateur est un agent, on le redirige vers son espace dédié
if ($_SESSION['user_role'] == 'agent') {
    header("Location: agent_dashboard.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Traitement des formulaires (admin uniquement)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Agents
    if (isset($_POST['add_agent'])) {
        addAgent($pdo, $_POST['nom'], $_POST['matricule']);
        header("Location: index.php?page=agents&success=1");
        exit;
    }
    if (isset($_POST['edit_agent'])) {
        updateAgent($pdo, $_POST['id'], $_POST['nom'], $_POST['matricule']);
        header("Location: index.php?page=agents&success=1");
        exit;
    }
    // Véhicules
    if (isset($_POST['add_vehicule'])) {
        addVehicule($pdo, $_POST['immatriculation'], $_POST['conducteur_id'] ?: null);
        header("Location: index.php?page=vehicules&success=1");
        exit;
    }
    if (isset($_POST['edit_vehicule'])) {
        updateVehicule($pdo, $_POST['id'], $_POST['immatriculation'], $_POST['conducteur_id'] ?: null);
        header("Location: index.php?page=vehicules&success=1");
        exit;
    }
    // Conducteurs
    if (isset($_POST['add_conducteur'])) {
        addConducteur($pdo, $_POST['nom'], $_POST['prenom'], $_POST['num_permis']);
        header("Location: index.php?page=conducteurs&success=1");
        exit;
    }
    if (isset($_POST['edit_conducteur'])) {
        updateConducteur($pdo, $_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['num_permis']);
        header("Location: index.php?page=conducteurs&success=1");
        exit;
    }
    // Contraventions (admin)
    if (isset($_POST['add_contravention'])) {
        addContravention($pdo, $_POST['vehicule_id'], $_POST['agent_id'], $_POST['montant'], $_POST['statut']);
        $newId = $pdo->lastInsertId();
        header("Location: avis.php?id=" . $newId);
        exit;
    }
    if (isset($_POST['edit_contravention'])) {
        $stmt = $pdo->prepare("UPDATE contraventions SET montant=?, statut=?, vehicule_id=?, agent_id=? WHERE id=?");
        $stmt->execute([$_POST['montant'], $_POST['statut'], $_POST['vehicule_id'], $_POST['agent_id'], $_POST['id']]);
        header("Location: index.php?page=contraventions&success=1");
        exit;
    }
}

// Suppression simple via GET
if ($action === 'delete' && $id) {
    if ($page == 'agents') deleteAgent($pdo, $id);
    if ($page == 'vehicules') deleteVehicule($pdo, $id);
    if ($page == 'conducteurs') deleteConducteur($pdo, $id);
    if ($page == 'contraventions') deleteContravention($pdo, $id);
    header("Location: index.php?page=$page");
    exit;
}

// Gestion du paiement
if (isset($_GET['payer'])) {
    $id = (int)$_GET['payer'];
    updateContraventionStatut($pdo, $id, 'payee');
    header("Location: recu.php?id=" . $id);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestion des contraventions - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>🚔 Système de gestion des contraventions routières</h1>
    <div style="text-align:right; margin-bottom:10px;">
        Bienvenue, <strong><?= htmlspecialchars($_SESSION['user_nom']) ?></strong> 
        (<?= $_SESSION['user_role'] ?>) 
        - <a href="logout.php">Déconnexion</a>
    </div>
    <nav>
        <ul>
            <li><a href="?page=dashboard" <?= $page=='dashboard'?'class="active"':'' ?>>Tableau de bord</a></li>
            <?php if ($_SESSION['user_role'] == 'admin'): ?>
                <li><a href="?page=agents" <?= $page=='agents'?'class="active"':'' ?>>Agents</a></li>
                <li><a href="?page=vehicules" <?= $page=='vehicules'?'class="active"':'' ?>>Véhicules</a></li>
                <li><a href="?page=conducteurs" <?= $page=='conducteurs'?'class="active"':'' ?>>Conducteurs</a></li>
                <li><a href="?page=tests" <?= $page=='tests'?'class="active"':'' ?>>Tests unitaires</a></li>
            <?php endif; ?>
            <li><a href="?page=contraventions" <?= $page=='contraventions'?'class="active"':'' ?>>Contraventions</a></li>
        </ul>
    </nav>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert success">Opération réussie !</div>
    <?php endif; ?>

    <?php
    switch($page) {
        case 'dashboard':
            include 'pages/dashboard.php';
            break;
        case 'agents':
            include 'pages/agents.php';
            break;
        case 'vehicules':
            include 'pages/vehicules.php';
            break;
        case 'conducteurs':
            include 'pages/conducteurs.php';
            break;
        case 'contraventions':
            include 'pages/contraventions.php';
            break;
        case 'tests':
            runUnitTests($pdo);
            break;
        case 'avis':
            include 'pages/avis.php';
            break;
        case 'recu':
            include 'pages/recu.php';
            break;
        default:
            echo "<p>Page introuvable.</p>";
    }
    ?>
</div>
</body>
</html>