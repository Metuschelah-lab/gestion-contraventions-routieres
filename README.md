# Système de gestion des contraventions routières

## Description
Application web complète pour la gestion des contraventions routières.  
Développée dans le cadre de l’examen de **Génie Logiciel** (Licence 3 – LIAGE / Licence 4 – LSI), année 2025/2026.

## Fonctionnalités principales
- **CRUD complet** : Agents, Véhicules, Conducteurs, Contraventions  
- **Émission d’une contravention** avec génération d’un **avis imprimable**  
- **Paiement d’une contravention** avec génération d’un **reçu de paiement** (quittance)  
- **Tableau de bord** : statistiques (nombre d’impayés, montant total dû)  
- **Tests unitaires intégrés** (page dédiée)  
- **Interface épurée** avec onglets, responsive  

## Technologies utilisées
- **PHP 8.2** (procédural avec fonctions métier)  
- **MySQL (MariaDB)** – obligatoire selon le sujet  
- **HTML5 / CSS3** (design personnalisé)

## Installation
1. Copier le dossier `contraventions` dans `C:\xampp\htdocs\`
2. Démarrer Apache et MySQL (port 8080 pour Apache)
3. Créer une base `contraventions` dans phpMyAdmin et exécuter le script SQL ci-dessous
4. Accéder à `http://localhost:8080/contraventions/index.php`

## Diagrammes UML
Les diagrammes sont dans le dossier diagrammes/ :
Cas d’utilisation
Classes
Activité
Séquence
Déploiement

## Captures d’écran
<img width="1366" height="644" alt="Avis de contravention" src="https://github.com/user-attachments/assets/764e9960-1758-4a4c-8993-3108c734361c" />
<img width="1366" height="645" alt="Recu de contraventions" src="https://github.com/user-attachments/assets/c57c8351-f6c8-4400-a307-28a2956d6c7f" />
<img width="1366" height="639" alt="Liste de contraventions" src="https://github.com/user-attachments/assets/f92d9dd8-1e1b-4d55-bc8c-26ed340be262" />
<img width="1366" height="643" alt="Acceuil" src="https://github.com/user-attachments/assets/d9ba3400-0b56-4aaa-b261-7a5126cfc7ab" />

## Tests unitaires
Rendez-vous sur http://localhost:8080/contraventions/index.php?page=tests
Résultat attendu : 2 tests passés sur 2.

## Auteurs
KIZYELE KABASELE METUSCHELAH
DIMANDJA LUNYENGU JOSEPH
ZAGABE AGANZE ESTHER
MONGI JACQUES BRIGANCE

Cours : Examen de Génie Logiciel - L3 LIAGE / L4 LSI
## Script SQL (à exécuter dans phpMyAdmin)
```sql
CREATE TABLE conducteurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    num_permis VARCHAR(20)
);

CREATE TABLE vehicules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    immatriculation VARCHAR(20),
    conducteur_id INT
);

CREATE TABLE agents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    matricule VARCHAR(20)
);

CREATE TABLE contraventions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date_emission DATETIME,
    montant DECIMAL(10,2),
    statut VARCHAR(20) DEFAULT 'impayee',
    vehicule_id INT,
    agent_id INT
);
