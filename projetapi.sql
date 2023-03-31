-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 31 mars 2023 à 21:10
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetapi`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(4) NOT NULL AUTO_INCREMENT,
  `publication` datetime NOT NULL DEFAULT current_timestamp(),
  `contenu` varchar(100) NOT NULL DEFAULT '',
  `login` varchar(50) NOT NULL,
  PRIMARY KEY (`id_article`),
  KEY `fk_article_login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `publication`, `contenu`, `login`) VALUES
(10, '2023-03-30 11:11:35', 'PT', 'AlexM'),
(11, '2023-03-30 13:35:14', 'ceci est un article', 'AlexM'),
(23, '2023-03-30 23:49:19', 'norwegia', 'Remi'),
(24, '2023-03-31 20:13:18', 'updated contenu', 'username'),
(26, '2023-03-31 23:06:26', 'Bonjour Brice, sachez que ce projet fut un plaisir. Bien cordialement. :)', 'AlexM'),
(27, '2023-03-31 23:09:23', 'Félicitation Mr Broisin :)', 'AlexM');

-- --------------------------------------------------------

--
-- Structure de la table `consulte`
--

DROP TABLE IF EXISTS `consulte`;
CREATE TABLE IF NOT EXISTS `consulte` (
  `login` varchar(50) NOT NULL,
  `id_article` int(11) NOT NULL,
  `vote` tinyint(1) NOT NULL,
  PRIMARY KEY (`login`,`id_article`),
  KEY `consulte_ibfk_1` (`id_article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `consulte`
--

INSERT INTO `consulte` (`login`, `id_article`, `vote`) VALUES
('AlexM', 10, 0),
('AlexM', 11, 1),
('username', 24, 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `login` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'publisher',
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`login`, `password`, `role`) VALUES
('AlexandreCalmet', '59f393df9726dad190b17bdcdd7c0ba1c3f13809399152293fb6df2e6d6f0afb', 'Publisher'),
('AlexC', '90d75375bc8ff0f50034e9f9d482fdd3ea733affa3c6ae9856ae832bcb77d817', 'Moderator'),
('AlexM', '0c3b4cb743abdf8e9cdfaab49d2a672ddda18d809806917b9b482c2a021d9676', 'Moderator'),
('Azorez', '90d75375bc8ff0f50034e9f9d482fdd3ea733affa3c6ae9856ae832bcb77d817', 'Publisher'),
('ElonMusk', 'a006acaba8d5676a6cb249c175c047cf8cc779a0ec491a712b018f8d84bb97b9', 'Publisher'),
('Gaia', '59f393df9726dad190b17bdcdd7c0ba1c3f13809399152293fb6df2e6d6f0afb', 'Publisher'),
('N0x', '59f393df9726dad190b17bdcdd7c0ba1c3f13809399152293fb6df2e6d6f0afb', 'Publisher'),
('Oui', '203ac0c737449dea211fa17bb09617c659cf7031672a6a07530852bf9d5860d6', 'Publisher'),
('Remi', '59f393df9726dad190b17bdcdd7c0ba1c3f13809399152293fb6df2e6d6f0afb', 'Publisher'),
('Rupugod', '8056daf1ae590dd8b62d8357d0a03ff662fd8b23fd453fa2da60f5835c403f62', 'Publisher'),
('username', '73eceb49dca9ae5c2c7a08df8edad975b7df0805e9b8a658401b70c0d8b1bec5', 'Publisher'),
('username2', '73eceb49dca9ae5c2c7a08df8edad975b7df0805e9b8a658401b70c0d8b1bec5', 'Publisher'),
('username3', '73eceb49dca9ae5c2c7a08df8edad975b7df0805e9b8a658401b70c0d8b1bec5', 'Publisher');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_login` FOREIGN KEY (`login`) REFERENCES `utilisateur` (`login`);

--
-- Contraintes pour la table `consulte`
--
ALTER TABLE `consulte`
  ADD CONSTRAINT `consulte_ibfk_1` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE CASCADE,
  ADD CONSTRAINT `consulte_ibfk_2` FOREIGN KEY (`login`) REFERENCES `utilisateur` (`login`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
