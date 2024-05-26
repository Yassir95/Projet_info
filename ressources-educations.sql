-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 26 mai 2024 à 10:09
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ressources-educations`
--

-- --------------------------------------------------------

--
-- Structure de la table `publications`
--

CREATE TABLE `publications` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `auteur` int(11) NOT NULL,
  `description` text,
  `affiche` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `motsCles` text,
  `nivEducation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `publications`
--

INSERT INTO `publications` (`id`, `titre`, `auteur`, `description`, `affiche`, `document`, `categorie`, `motsCles`, `nivEducation`) VALUES
(8, 'Joker', 3, 'Script officiel du film \"Le Joker\" , realiser par  Todd Phillips .', 'uploads/image.jpg', 'uploads/joker_new_final.pdf', 'Film / Cinéma', 'Suspens / Drama', 'Lycée'),
(9, 'Cours sur le CSS', 3, 'Cours complet sur comment bien utiliser le CSS .', 'uploads/css.png', 'uploads/11-CSS.pdf', 'Informatique / CSS ', 'Info', 'Lycée'),
(13, 'Cours Maths Lycée', 3, 'Cours complet sur sur les mathematiques niveau lycée .', 'uploads/616s4MqWKzL._AC_UF1000,1000_QL80_.jpg', 'uploads/lycee.pdf', 'Mathématiques', '', 'Lycée');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auteur` (`auteur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
