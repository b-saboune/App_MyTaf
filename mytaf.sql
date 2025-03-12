-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 11 mars 2025 à 23:09
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mytaf`
--

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

DROP TABLE IF EXISTS `projet`;
CREATE TABLE IF NOT EXISTS `projet` (
  `idprojet` int NOT NULL AUTO_INCREMENT,
  `nom_projet` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `date_debut` varchar(45) DEFAULT NULL,
  `date_fin` varchar(45) DEFAULT NULL,
  `statut` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idprojet`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`idprojet`, `nom_projet`, `description`, `date_debut`, `date_fin`, `statut`) VALUES
(1, '1er projet', 'edition', '2025-02-23', '2025-02-26', 'en cours'),
(2, '2ème prjt', 'pour l\'etude du plan', '2025-02-23', '2025-02-28', 'en cours'),
(3, 'GRD', 'FR', '2025-02-23', '2025-02-23', 'terminé'),
(4, '3ème projet', 'abcd', '2025-02-23', '2025-02-26', 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `rapport`
--

DROP TABLE IF EXISTS `rapport`;
CREATE TABLE IF NOT EXISTS `rapport` (
  `idrapport` int NOT NULL AUTO_INCREMENT,
  `date_creation` varchar(45) DEFAULT NULL,
  `contenu` varchar(45) DEFAULT NULL,
  `projet_id` int NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idrapport`),
  KEY `fk_rapport_projet1_idx` (`projet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `rapport`
--

INSERT INTO `rapport` (`idrapport`, `date_creation`, `contenu`, `projet_id`, `file_path`) VALUES
(1, '2025-02-20 12:32:42', 'redaction de memoire', 1, '../uploads/Abou_fadoul.docx');

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

DROP TABLE IF EXISTS `taches`;
CREATE TABLE IF NOT EXISTS `taches` (
  `idtaches` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `statut` varchar(45) DEFAULT NULL,
  `date_limite` varchar(45) DEFAULT NULL,
  `Utilisateurs_id` int NOT NULL,
  `projet_id` int NOT NULL,
  PRIMARY KEY (`idtaches`),
  KEY `fk_taches_Utilisateurs_idx` (`Utilisateurs_id`),
  KEY `fk_taches_projet1_idx` (`projet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `taches`
--

INSERT INTO `taches` (`idtaches`, `titre`, `description`, `statut`, `date_limite`, `Utilisateurs_id`, `projet_id`) VALUES
(1, 'Laver et repasser', 'les outils', 'En cours', '2025-02-22', 4, 1),
(2, 'Laver', 'dedier', 'En cours', '2025-02-23', 5, 1),
(3, 'react', 'jvs', 'En cours', '2025-02-23', 5, 1),
(4, 'Aller au marché', 'achete des produits', 'En cours', '2025-02-23', 5, 1),
(5, 'Repasser', 'à midi', 'En cours', '2025-02-15', 5, 2);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `idUtilisateurs` int NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `role` enum('admin','member') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`idUtilisateurs`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`idUtilisateurs`, `username`, `email`, `password`, `role`) VALUES
(3, 'taha', 't@gmail.com', '$2y$10$SlljkaAbeWOI35FZ3LPEDuyJx3thbISo/QNS3ou1jioa8N9T4ygJe', 'admin'),
(4, 'Brahim', 'brhm@gmail.com', '$2y$10$rXLJd89OIWdX81LwbMRJeOdKXLPx2vzJ.ioPdDKkIpvkdPdwRY7oK', 'member'),
(5, 'abdel', 'abdel@gmail.com', '$2y$10$2cAMnWYs6tvqnctlWomU/uBeuqo2M.RmgPH7y8BvATQT2pUfhgZEK', 'admin'),
(6, 'jean', 'jean@gmail.com', '$2y$10$pk9xZf0.hZa2PxdJ58yzWuWloH/aDzmjPYrfWOiOeB6CQ.qDv6Jke', 'member'),
(7, 'Pala', 'pl@gmail.com', '$2y$10$TvR5IqWaHVtEjWiA0JUKg./YRVcuzRIiRfi/CTuq56lNlN9Co/SDa', 'member'),
(8, 'deku', 'deku@gmail.com', '$2y$10$.lEMqPlx027oQeNvJtS..Od86QgjdDDoCyRSXd4Bxxm9zfpiqXZae', 'member');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `rapport`
--
ALTER TABLE `rapport`
  ADD CONSTRAINT `fk_rapport_projet1` FOREIGN KEY (`projet_id`) REFERENCES `projet` (`idprojet`);

--
-- Contraintes pour la table `taches`
--
ALTER TABLE `taches`
  ADD CONSTRAINT `fk_taches_projet1` FOREIGN KEY (`projet_id`) REFERENCES `projet` (`idprojet`),
  ADD CONSTRAINT `fk_taches_Utilisateurs` FOREIGN KEY (`Utilisateurs_id`) REFERENCES `users` (`idUtilisateurs`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
