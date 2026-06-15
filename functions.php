<?php
require_once 'config.php';

// ----- Agents -----
function getAllAgents($pdo) {
    $stmt = $pdo->query("SELECT * FROM agents ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addAgent($pdo, $nom, $matricule) {
    $stmt = $pdo->prepare("INSERT INTO agents (nom, matricule) VALUES (?, ?)");
    return $stmt->execute([$nom, $matricule]);
}

function updateAgent($pdo, $id, $nom, $matricule) {
    $stmt = $pdo->prepare("UPDATE agents SET nom=?, matricule=? WHERE id=?");
    return $stmt->execute([$nom, $matricule, $id]);
}

function deleteAgent($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM agents WHERE id=?");
    return $stmt->execute([$id]);
}

// ----- Véhicules -----
function getAllVehicules($pdo) {
    $stmt = $pdo->query("SELECT * FROM vehicules ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addVehicule($pdo, $immatriculation, $conducteur_id = null) {
    $stmt = $pdo->prepare("INSERT INTO vehicules (immatriculation, conducteur_id) VALUES (?, ?)");
    return $stmt->execute([$immatriculation, $conducteur_id]);
}

function updateVehicule($pdo, $id, $immatriculation, $conducteur_id) {
    $stmt = $pdo->prepare("UPDATE vehicules SET immatriculation=?, conducteur_id=? WHERE id=?");
    return $stmt->execute([$immatriculation, $conducteur_id, $id]);
}

function deleteVehicule($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM vehicules WHERE id=?");
    return $stmt->execute([$id]);
}

// ----- Conducteurs -----
function getAllConducteurs($pdo) {
    $stmt = $pdo->query("SELECT * FROM conducteurs ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addConducteur($pdo, $nom, $prenom, $num_permis) {
    $stmt = $pdo->prepare("INSERT INTO conducteurs (nom, prenom, num_permis) VALUES (?, ?, ?)");
    return $stmt->execute([$nom, $prenom, $num_permis]);
}

function updateConducteur($pdo, $id, $nom, $prenom, $num_permis) {
    $stmt = $pdo->prepare("UPDATE conducteurs SET nom=?, prenom=?, num_permis=? WHERE id=?");
    return $stmt->execute([$nom, $prenom, $num_permis, $id]);
}

function deleteConducteur($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM conducteurs WHERE id=?");
    return $stmt->execute([$id]);
}

// ----- Contraventions -----
function getAllContraventions($pdo) {
    $stmt = $pdo->query("SELECT * FROM contraventions ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addContravention($pdo, $vehicule_id, $agent_id, $montant, $statut = 'impayee') {
    $date = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO contraventions (date_emission, montant, statut, vehicule_id, agent_id) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$date, $montant, $statut, $vehicule_id, $agent_id]);
}

function updateContraventionStatut($pdo, $id, $statut) {
    $stmt = $pdo->prepare("UPDATE contraventions SET statut=? WHERE id=?");
    return $stmt->execute([$statut, $id]);
}

function deleteContravention($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM contraventions WHERE id=?");
    return $stmt->execute([$id]);
}

// ----- Statistiques pour le tableau de bord -----
function countContraventionsByStatut($pdo, $statut) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM contraventions WHERE statut=?");
    $stmt->execute([$statut]);
    return $stmt->fetchColumn();
}

function totalMontantImpaye($pdo) {
    $stmt = $pdo->query("SELECT SUM(montant) FROM contraventions WHERE statut='impayee'");
    return $stmt->fetchColumn() ?: 0;
}

function getContraventionDetails($pdo, $id) {
    $sql = "SELECT c.*, 
                   v.immatriculation, v.conducteur_id,
                   cond.nom as cond_nom, cond.prenom as cond_prenom, cond.num_permis,
                   a.nom as agent_nom, a.matricule as agent_matricule
            FROM contraventions c
            LEFT JOIN vehicules v ON c.vehicule_id = v.id
            LEFT JOIN conducteurs cond ON v.conducteur_id = cond.id
            LEFT JOIN agents a ON c.agent_id = a.id
            WHERE c.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ----- TESTS UNITAIRES (à exécuter via ?test=1) -----
function runUnitTests($pdo) {
    echo "<h2>Tests unitaires</h2>";
    $ok = 0;
    $total = 0;
    
    // Test ajout agent
    $total++;
    $testNom = "TestAgent_" . time();
    if (addAgent($pdo, $testNom, "TEST01")) {
        $agentId = $pdo->lastInsertId();
        $agent = $pdo->query("SELECT * FROM agents WHERE id=$agentId")->fetch();
        if ($agent['nom'] == $testNom) $ok++; else echo "Échec ajout agent<br>";
        deleteAgent($pdo, $agentId);
    } else { echo "Échec insertion agent<br>"; }
    
    // Test ajout contravention
    $total++;
    $vehiculeId = 1; // suppose que la table vehicules contient un id=1
    $agentId = 1;
    if (addContravention($pdo, $vehiculeId, $agentId, 100)) {
        $contraventionId = $pdo->lastInsertId();
        $c = $pdo->query("SELECT * FROM contraventions WHERE id=$contraventionId")->fetch();
        if ($c['montant'] == 100 && $c['statut'] == 'impayee') $ok++; else echo "Échec ajout contravention<br>";
        deleteContravention($pdo, $contraventionId);
    } else { echo "Échec insertion contravention<br>"; }
    
    echo "<p style='background: #eef; padding:10px;'>Résultat : $ok / $total tests passés.</p>";
}
?>