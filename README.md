#  Système de gestion des contraventions routières

## Description
Application web complète pour la gestion des contraventions routières.  
Développée dans le cadre de l’examen de **Génie Logiciel** (Licence 3 – LIAGE / Licence 4 – LSI), année 2025/2026.

## Fonctionnalités principales
-  **CRUD complet** : Agents, Véhicules, Conducteurs, Contraventions
-  **Émission d’une contravention** avec génération d’un **avis imprimable**
-  **Paiement d’une contravention** avec génération d’un **reçu de paiement** (quittance)
-  **Tableau de bord** : statistiques (nombre d’impayés, montant total dû)
-  **Tests unitaires** intégrés (page dédiée)
-  **Interface épurée** avec onglets, responsive

## Technologies utilisées
- **PHP 8.2** (procédural avec fonctions métier)
- **MySQL (MariaDB)** – obligatoire selon le sujet
- **HTML5 / CSS3** (design personnalisé)
- **XAMPP** (serveur local : Apache + MySQL)

## Installation et exécution
1. **Télécharger** ou **cloner** ce dépôt.
2. Placer le dossier `contraventions` dans `C:\xampp\htdocs\`.
3. Lancer **XAMPP** et démarrer **Apache** (port 8080) et **MySQL** (port 3306).
4. Importer la base de données :
   - Ouvrir phpMyAdmin (`http://localhost:8080/phpmyadmin`)
   - Créer une base nommée `contraventions`
   - Exécuter le script SQL suivant (copier-coller dans l'onglet SQL) :

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
5. Accéder à l’application :  
   `http://localhost:8080/contraventions/index.php`

## Captures d’écran
<img width="1366" height="645" alt="Recu de contraventions" src="https://github.com/user-attachments/assets/07fb0071-29aa-43c4-9a46-bf3d813d243d" />
<img width="1366" height="644" alt="Avis de contravention" src="https://github.com/user-attachments/assets/1a51f869-29bd-4e92-9d74-64b9ae3ab580" />
<img width="1366" height="639" alt="Liste de contraventions" src="https://github.com/user-attachments/assets/d13ef434-5235-452f-a5d3-d73329710aed" />
<img width="1366" height="643" alt="Acceuil" src="https://github.com/user-attachments/assets/8922c522-a12a-4fb6-8296-083b7730e137" />


## Diagrammes UML (5 diagrammes)
Les diagrammes sont dans le dossier `diagrammes/`. Cliquez sur chaque lien pour les visualiser.

- [Diagramme des cas d’utilisation](diagrammes/Diagramme%20du%20cas%20d’utilisation.png)
- [Diagramme de classes](diagrammes/Diagramme%20de%20classe.png)
- [Diagramme d’activité](diagrammes/Diagramme%20d’activité.png)
- [Diagramme de séquence](diagrammes/Diagramme%20de%20sequence.png)
- [Diagramme de déploiement](diagrammes/Diagramme%20de%20déploiement.png)


## Tests unitaires
- Rendez-vous sur la page **Tests unitaires** via l’onglet dédié dans l’application.
- URL directe : `http://localhost:8080/contraventions/index.php?page=tests`
- Résultat attendu : **2 tests passés sur 2** (ajout agent + ajout contravention).

## Auteur(s)
- **Nom** : KIZYELE KABASELE METUSCHELAH;DIMANDJA  LUNYENGU JOSEPH;ZAGABE AGANZE ESTHER;MONGI JACQUES BRIGANCE.
- **Année universitaire** : 2025/2026
- **Cours** : Examen de Génie Logiciel – L3 LIAGE / L4 LSI

## Statut du projet
 **Terminé** – toutes les fonctionnalités demandées par le sujet sont implémentées.
