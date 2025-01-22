-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 22 jan. 2025 à 14:07
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id_article` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `date_publication` date NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_article`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id_article`, `titre`, `contenu`, `date_publication`, `image`) VALUES
(1, 'teste', 'test', '2025-01-15', 'uploads/1736945632_singe2.jpg'),
(2, 'dmd1', 'ffff', '2025-01-15', 'uploads/1736952884_singe3.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'Web'),
(2, 'Mobile'),
(3, 'Interaction'),
(4, 'Voiture');

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id_contact` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_contact`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `contacts`
--

INSERT INTO `contacts` (`id_contact`, `email`, `message`, `date_envoi`) VALUES
(1, 'lennaik@test.fr', 'teste', '2025-01-15 15:11:04');

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

DROP TABLE IF EXISTS `projets`;
CREATE TABLE IF NOT EXISTS `projets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `annee` int DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id`, `titre`, `description`, `annee`, `image_url`, `date_creation`) VALUES
(2, 'PROJET 1', 'Web / Mobile', 2018, 'assets/images/projects/App-Screens-Perspective-MockUp-full.jpg', '2025-01-09 13:27:03'),
(3, 'PROJET 4', 'Web', 2021, 'assets/images/projects/sample_4.jpg', '2025-01-09 13:27:03'),
(4, 'PROJET 7', 'Mobile', 2020, 'assets/images/projects/sample_5.jpg', '2025-01-09 13:27:03'),
(5, 'PROJET 2', 'Interaction', 2018, 'assets/images/projects/sample_2.jpg', '2025-01-09 13:27:03'),
(6, 'PROJET 5', 'Mobile / Interaction', 2022, 'assets/images/projects/sample_7.jpg', '2025-01-09 13:27:03'),
(7, 'PROJET 8', 'Web / Mobile / Interaction', 2022, 'assets/images/projects/Rectangle 405.jpg', '2025-01-09 13:27:03'),
(8, 'PROJET 3', 'Web', 2018, 'assets/images/projects/sample_6.jpg', '2025-01-09 13:27:03'),
(9, 'PROJET 6', 'Interaction', 2018, 'assets/images/projects/sample_1.jpg', '2025-01-09 13:27:03'),
(10, 'PROJET 9', 'Interaction', 2019, 'assets/images/projects/cover_photo.jpg', '2025-01-09 13:27:03'),
(11, 'Projet 11', 'Le projet 11', 2025, 'assets/images/singe.jpg', '2025-01-10 13:13:21'),
(16, 'demande accepter', 'accepte stp', 24444, 'uploads/1736947021_singe.jpg', '2025-01-14 23:00:00'),
(17, 'dmd2', 'dmd2', 2025, 'uploads/1736947744_singe4.jpg', '2025-01-14 23:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `projets_categories`
--

DROP TABLE IF EXISTS `projets_categories`;
CREATE TABLE IF NOT EXISTS `projets_categories` (
  `projet_id` int NOT NULL,
  `categorie_id` int NOT NULL,
  PRIMARY KEY (`projet_id`,`categorie_id`),
  KEY `categorie_id` (`categorie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projets_categories`
--

INSERT INTO `projets_categories` (`projet_id`, `categorie_id`) VALUES
(2, 1),
(3, 2),
(4, 3),
(5, 2),
(5, 3),
(6, 1),
(6, 2),
(6, 3),
(7, 1),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 4),
(13, 1),
(13, 2),
(14, 1),
(14, 3),
(15, 1),
(15, 2),
(15, 4),
(16, 3),
(17, 1),
(17, 2),
(18, 4);

-- --------------------------------------------------------

--
-- Structure de la table `suggestion`
--

DROP TABLE IF EXISTS `suggestion`;
CREATE TABLE IF NOT EXISTS `suggestion` (
  `id_suggestion` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `annee` int NOT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_suggestion`)
) ENGINE=MyISAM AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `suggestion`
--

INSERT INTO `suggestion` (`id_suggestion`, `titre`, `description`, `annee`, `image_url`, `date_creation`) VALUES
(155, 'singe intectuelle', 'un singe peut reflechire mais il peut ausssi ne pas reflechire... à mediter', 2005, 'uploads/1736950609_singe4.jpg', '2025-01-15 00:00:00'),
(152, 'dmd2', 'dmd2', 2025, 'uploads/1736947744_singe4.jpg', '2025-01-15 00:00:00'),
(153, 'demande 3', 'test1', 2024, 'uploads/1736947955_singe2.jpg', '2025-01-15 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `temoignages`
--

DROP TABLE IF EXISTS `temoignages`;
CREATE TABLE IF NOT EXISTS `temoignages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_auteur` varchar(255) NOT NULL,
  `entreprise_auteur` varchar(255) DEFAULT NULL,
  `texte` text NOT NULL,
  `note` tinyint(1) DEFAULT NULL,
  `chemin_image` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','valide','rejete') DEFAULT 'en_attente',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `temoignages`
--

INSERT INTO `temoignages` (`id`, `nom_auteur`, `entreprise_auteur`, `texte`, `note`, `chemin_image`, `statut`, `date_creation`) VALUES
(2, 'Lennaik Desir', 'Mcdonald', 'super site', 5, 'uploads/1737127870_Capture d\'écran 2024-12-19 095802.png', 'valide', '2025-01-16 23:00:00'),
(5, 'Jamal Hamad', 'Saint Paul', 'ce site est vraiment incroyable', 5, 'uploads/1737550700_morgan-charriere-veut-se-faire-connaitre-ufc-on-espnplus-98-photo___ID_2024-04-06-10-34-45_6992.jpg', 'valide', '2025-01-21 23:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(100) NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(50) DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `date_creation`, `role`) VALUES
(2, 'desir', 'lennaik', 'lennaik@test.com', '$2y$10$mmJ9PvkbhTRBYBm6l1bPgen4zFX3E9jeHO22ywwJeGT1ejsBfKTya', '2025-01-15 12:44:33', 'admin'),
(3, 'test', 'user', 'user1@test.fr', '$2y$10$ncqo9ufUk/E.5vU5JmL3FuJAdbuarv..zNw7Yq4NsMOaXHwFebplO', '2025-01-15 13:13:39', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
