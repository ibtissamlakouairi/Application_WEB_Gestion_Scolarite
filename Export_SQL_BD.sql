-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 22 déc. 2025 à 21:35
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
-- Base de données : `db_scolarite`
--

-- --------------------------------------------------------

--
-- Structure de la table `coefficient`
--

CREATE TABLE `coefficient` (
  `id` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `coefficient` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`id`, `nom`, `prenom`, `email`) VALUES
(2, 'Professeur1', 'test', 'Professeur1.test@univ.com'),
(4, 'Professeur2', 'test', 'Professeur2.test@univ.com'),
(9, 'Professeur3', 'test', 'Professeur3.test@univ.com'),
(10, 'professeur4', 'test', 'enseignant4@ucd.com'),
(12, 'Professeur5', 'test', 'Professeur5.test@univ.com'),
(13, 'Professeur6', 'test', 'Professeur6.test@univ.com'),
(14, 'Professeur7', 'test', 'Professeur7.test@univ.com');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `nom`, `prenom`, `email`) VALUES
(5, 'ouarrak', 'mouad', 'mouad@gmail.com'),
(12, 'LAKOUAIRI', 'Ibtissam', 'ibtissam@gmail.com'),
(13, 'student2', 'FPSB', 'student2FPSB@gmail.com'),
(14, 'student3', 'FPSB', 'student3FPSB@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant_module`
--

CREATE TABLE `etudiant_module` (
  `id_etudiant` int(11) NOT NULL,
  `id_module` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant_module`
--

INSERT INTO `etudiant_module` (`id_etudiant`, `id_module`) VALUES
(5, 13),
(5, 14),
(5, 15),
(5, 18),
(5, 19),
(12, 13),
(12, 14),
(12, 15),
(12, 16),
(12, 17),
(12, 18),
(12, 19),
(13, 13),
(13, 14),
(13, 15),
(13, 16),
(13, 17),
(13, 18),
(13, 19),
(14, 13),
(14, 14),
(14, 15),
(14, 16),
(14, 17),
(14, 18),
(14, 19);

-- --------------------------------------------------------

--
-- Structure de la table `mentions`
--

CREATE TABLE `mentions` (
  `id` int(11) NOT NULL,
  `min_val` float DEFAULT NULL,
  `max_val` float DEFAULT NULL,
  `libelle` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mentions`
--

INSERT INTO `mentions` (`id`, `min_val`, `max_val`, `libelle`) VALUES
(1, 0, 9.99, 'Ajourné'),
(2, 10, 11.99, 'Passable'),
(3, 12, 13.99, 'Assez Bien'),
(4, 14, 15.99, 'Bien'),
(5, 16, 20, 'Très Bien');

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `intitule` varchar(100) DEFAULT NULL,
  `id_enseignant` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id`, `code`, `intitule`, `id_enseignant`) VALUES
(13, '32', 'Anglais', 12),
(14, '112', 'Intelligence Artificial', 10),
(15, '134', 'JAVA', 13),
(16, '972043', 'Programmation WEB3', 12),
(17, '972034', 'ORACLE DB', 13),
(18, '1234', 'BIG DATA', 14),
(19, '3211', 'Droit & T.D', 14);

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `note_cc` float DEFAULT NULL CHECK (`note_cc` between 0 and 20),
  `note_exam` float DEFAULT NULL CHECK (`note_exam` between 0 and 20),
  `moyenne_module` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`id`, `id_etudiant`, `id_module`, `note_cc`, `note_exam`, `moyenne_module`) VALUES
(12, 12, 19, 16, 19, 17),
(14, 12, 15, 19, 20, 19),
(15, 12, 14, 18, 19, 18),
(16, 12, 13, 15, 16, 15),
(17, 5, 15, 17, 16, 16),
(20, 5, 14, 15, 17, 16),
(21, 5, 13, 15, 15, 15),
(22, 5, 16, 14, 15, 14.6),
(23, 5, 18, 17, 18.5, 17),
(24, 5, 19, 14.5, 17, 16),
(25, 5, 17, 19, 19, 19),
(26, 12, 18, 20, 20, 20),
(27, 12, 16, 17, 16, 16),
(28, 14, 19, 11.99, 15, 13.796),
(29, 13, 13, 14, 13.5, 13),
(30, 13, 18, 16.5, 15, 15),
(31, 13, 19, 16, 17, 16),
(32, 13, 14, 16, 17.9, 17),
(33, 13, 15, 16, 15.5, 15),
(34, 13, 17, 17, 17, 17),
(35, 13, 16, 16, 16, 16),
(46, 12, 17, 18, 17.5, 17.7);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `username`, `password`) VALUES
(1, 'admin', '81dc9bdb52d04dc20036dbd8313ed055');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `coefficient`
--
ALTER TABLE `coefficient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_module` (`id_module`);

--
-- Index pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etudiant_module`
--
ALTER TABLE `etudiant_module`
  ADD PRIMARY KEY (`id_etudiant`,`id_module`),
  ADD KEY `id_module` (`id_module`);

--
-- Index pour la table `mentions`
--
ALTER TABLE `mentions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_enseignant` (`id_enseignant`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_module` (`id_module`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `coefficient`
--
ALTER TABLE `coefficient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `enseignant`
--
ALTER TABLE `enseignant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `mentions`
--
ALTER TABLE `mentions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `coefficient`
--
ALTER TABLE `coefficient`
  ADD CONSTRAINT `coefficient_ibfk_1` FOREIGN KEY (`id_module`) REFERENCES `module` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etudiant_module`
--
ALTER TABLE `etudiant_module`
  ADD CONSTRAINT `etudiant_module_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `etudiant_module_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `module` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `module_ibfk_1` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignant` (`id`);

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `module` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
