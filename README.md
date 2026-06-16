#  Système de gestion des contraventions routières

## Description
Application web complète pour la gestion des contraventions routières.  
Développée dans le cadre de l’examen de **Génie Logiciel** (L3 – LIAGE / L4 – LSI).

Elle propose deux espaces distincts :
- **Agent** : émettre des PV, consulter ses propres PV, générer l’avis de contravention.
- **Administrateur** : superviser l’ensemble du système (statistiques, gestion des agents, consultation des véhicules/conducteurs, gestion des contraventions, tests unitaires).

## Fonctionnalités principales
- **Authentification** : deux rôles (Admin / Agent) avec des interfaces dédiées.
- **Espace Agent** :
  - Émission d’un procès-verbal (plaque, propriétaire, infraction, montant, etc.).
  - Synchronisation automatique des véhicules et conducteurs dans les tables dédiées.
  - Consultation de ses propres PV émis.
  - Génération d’un **avis de contravention** imprimable.
- **Espace Admin** :
  - Tableau de bord avec statistiques globales (nombre de contraventions, impayées, montant total dû).
  - Gestion des agents (ajout / suppression) avec création automatique du compte de connexion.
  - Consultation des véhicules et conducteurs (synchronisés automatiquement).
  - Gestion complète des contraventions (payer, modifier, supprimer).
  - Génération du **reçu de paiement** (quittance).
- **Tests unitaires intégrés** : 2 tests automatisés (ajout agent + ajout contravention).
- **Interface épurée** : onglets, responsive, adaptée à chaque rôle.

## Technologies utilisées
- **PHP 8.2** (procédural avec fonctions métier)
- **MySQL (MariaDB)** – obligatoire selon le sujet
- **HTML5 / CSS3** (design personnalisé)
- **XAMPP** (serveur local)

## Installation
1. Copier le dossier `contraventions` dans `C:\xampp\htdocs\`
2. Démarrer **Apache** et **MySQL** dans XAMPP (Apache sur le port 8080)
3. Importer le fichier `database.sql` dans phpMyAdmin (base `contraventions`)
4. Accéder à l’application : `http://localhost:8080/contraventions/`

## Diagrammes UML
Les diagrammes sont dans le dossier `diagrammes/` :

- [Cas d’utilisation](diagrammes/Diagramme%20du%20cas%20d’utilisation.png)
- [Classes](diagrammes/Diagramme%20de%20classe.png)
- [Activité](diagrammes/Diagramme%20d’activité.png)
- [Séquence](diagrammes/Diagramme%20de%20sequence.png)
- [Déploiement](diagrammes/Diagramme%20de%20déploiement.png)

## Captures d’écran
<img width="1366" height="768" alt="Capture d’écran 2026-06-16 181219" src="https://github.com/user-attachments/assets/a39ca5f5-5b23-4113-b77b-62f5d8f0ffcb" />
<img width="1366" height="768" alt="Capture d’écran 2026-06-16 181311" src="https://github.com/user-attachments/assets/47c117a0-f73c-4c7f-bd52-8e8a11a9b20b" />
<img width="1366" height="768" alt="Capture d’écran 2026-06-16 181106" src="https://github.com/user-attachments/assets/5eba3d1f-19cc-443e-aa79-bcf768f7e48f" />
<img width="1366" height="768" alt="Capture d’écran 2026-06-16 181242" src="https://github.com/user-attachments/assets/47a280bf-953f-4ac8-ad75-ffb096ab91c7" />
<img width="1366" height="768" alt="Capture d’écran 2026-06-16 181045" src="https://github.com/user-attachments/assets/106f863a-01a8-4d26-bab7-6f1cf4e93359" />



## Tests unitaires
Accéder à `http://localhost:8080/contraventions/index.php?page=tests`  
Résultat attendu : **2 tests passés sur 2** (ajout agent + ajout contravention).

## Auteurs
- KIZYELE KABASELE METUSCHELAH
- MONGI JACQUES BRIGANCE
- DIMANDJA LUNYENGU JOSEPH
- ZAGABE AGANZE ESTHER
