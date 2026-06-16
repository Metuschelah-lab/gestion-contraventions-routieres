-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 16 juin 2026 à 23:42
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `contraventions`
--

-- --------------------------------------------------------

--
-- Structure de la table `agents`
--

CREATE TABLE `agents` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `matricule` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `agents`
--

INSERT INTO `agents` (`id`, `nom`, `matricule`) VALUES
(1, 'Dupont', 'A001'),
(13, 'Peter', 'A002'),
(14, 'Thierry', 'A003'),
(15, 'Moise', 'A004'),
(17, 'Jean', '005'),
(18, 'Marie', 'A006');

-- --------------------------------------------------------

--
-- Structure de la table `conducteurs`
--

CREATE TABLE `conducteurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `num_permis` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `conducteurs`
--

INSERT INTO `conducteurs` (`id`, `nom`, `prenom`, `telephone`, `adresse`, `num_permis`) VALUES
(4, 'Germain', '', '+24309987633', 'Goma ville', '');

-- --------------------------------------------------------

--
-- Structure de la table `contraventions`
--

CREATE TABLE `contraventions` (
  `id` int(11) NOT NULL,
  `date_emission` datetime DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `statut` varchar(20) DEFAULT 'impayee',
  `vehicule_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `plaque` varchar(20) DEFAULT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `proprietaire` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `infraction_texte` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `contraventions`
--

INSERT INTO `contraventions` (`id`, `date_emission`, `montant`, `statut`, `vehicule_id`, `agent_id`, `plaque`, `marque`, `proprietaire`, `telephone`, `adresse`, `infraction_texte`) VALUES
(21, '2026-06-16 18:09:53', 120.00, 'payee', 5, 17, '123AA', 'AudIRS3', 'Germain', '+24309987633', 'Goma ville', 'Exces');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `identifiant` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','agent') NOT NULL,
  `nom_complet` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `identifiant`, `mot_de_passe`, `role`, `nom_complet`) VALUES
(1, 'admin17-08', '0192023a7bbd73250516f069df18b500', 'admin', 'Administrateur Principal'),
(2, 'agent1100', '2ec199f1e2de31576869a57488e919ad', 'agent', 'Dupont'),
(3, 'peterAgent', 'e3e7f312a36e128c29a42352bb4ff8d7', 'agent', 'Peter'),
(4, 'thierryAgent', 'a5d0102e6262b33db942206851ab7377', 'agent', 'Thierry'),
(5, 'moiseAgent', 'a85165fd8e8d5ea8472e1c15e7ccbb78', 'agent', 'Moise'),
(6, 'jeanAgent', 'a2faca5f819b9b2778e78abb889671ed', 'agent', 'Jean');

-- --------------------------------------------------------

--
-- Structure de la table `vehicules`
--

CREATE TABLE `vehicules` (
  `id` int(11) NOT NULL,
  `immatriculation` varchar(20) DEFAULT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `conducteur_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `vehicules`
--

INSERT INTO `vehicules` (`id`, `immatriculation`, `marque`, `conducteur_id`) VALUES
(5, '123AA', 'AudIRS3', 4);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `conducteurs`
--
ALTER TABLE `conducteurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contraventions`
--
ALTER TABLE `contraventions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identifiant` (`identifiant`);

--
-- Index pour la table `vehicules`
--
ALTER TABLE `vehicules`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `conducteurs`
--
ALTER TABLE `conducteurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `contraventions`
--
ALTER TABLE `contraventions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `vehicules`
--
ALTER TABLE `vehicules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
