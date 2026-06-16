<?php
session_start();
require_once 'config.php';

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'];
    $mdp = md5($_POST['mot_de_passe']); // Pour l'exemple, mais en vrai utilisez password_hash()
    
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE identifiant = ? AND mot_de_passe = ?");
    $stmt->execute([$identifiant, $mdp]);
    $user = $stmt->fetch();
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom_complet'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_identifiant'] = $user['identifiant'];

        // AJOUT POUR AGENT : on stocke son ID provenant de la table `agents`
        if ($user['role'] == 'agent') {
            $stmtAgent = $pdo->prepare("SELECT id FROM agents WHERE nom = ?");
            $stmtAgent->execute([$user['nom_complet']]);
            $agentId = $stmtAgent->fetchColumn();
            $_SESSION['user_agent_id'] = $agentId ?: 0;
        }

        header("Location: index.php");
        exit;
    } else {
        $erreur = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion - Gestion contraventions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width:400px; margin-top:100px;">
        <h2>🔐 Connexion</h2>
        <?php if($erreur): ?>
            <div class="alert error"><?= $erreur ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Identifiant</label>
            <input type="text" name="identifiant" placeholder="admin17-08 ou agent1100" required>
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" placeholder="Votre mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>