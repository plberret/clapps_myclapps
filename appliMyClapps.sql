-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 15 Octobre 2012 à 10:13
-- Version du serveur: 5.1.44
-- Version de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `appliMyClapps`
--

-- --------------------------------------------------------

--
-- Structure de la table `mc_favorite`
--

CREATE TABLE IF NOT EXISTS `mc_favorite` (
  `id_project` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  KEY `ID_project` (`id_project`),
  KEY `ID_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `mc_favorite`
--

INSERT INTO `mc_favorite` (`id_project`, `id_user`) VALUES
(3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `mc_profile`
--

CREATE TABLE IF NOT EXISTS `mc_profile` (
  `id_profile` int(10) NOT NULL AUTO_INCREMENT,
  `id_project` int(10) NOT NULL,
  `person` varchar(250) NOT NULL,
  `domain` int(1) NOT NULL,
  `current_state` int(1) NOT NULL,
  PRIMARY KEY (`id_profile`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `mc_profile`
--

INSERT INTO `mc_profile` (`id_profile`, `id_project`, `person`, `domain`, `current_state`) VALUES
(1, 1, 'Un acteur blond', 1, 1),
(2, 1, 'Une maquilleuse', 2, 1),
(3, 1, 'Un acteur roux', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `mc_project`
--

CREATE TABLE IF NOT EXISTS `mc_project` (
  `id_project` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `id_creator` int(10) NOT NULL,
  `current_state` int(1) NOT NULL DEFAULT '1',
  `create_date` date NOT NULL,
  PRIMARY KEY (`id_project`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Contenu de la table `mc_project`
--

INSERT INTO `mc_project` (`id_project`, `title`, `description`, `id_creator`, `current_state`, `create_date`) VALUES
(1, 'Mon projet 1', 'description de mon projet ', 1, 1, '0000-00-00'),
(2, 'Prochain d''un autre 1', 'description du projet', 2, 1, '0000-00-00'),
(3, 'Prochain d''un autre 2', 'description', 2, 1, '0000-00-00'),
(4, 'Nouveau films 18', 'Description 18', 2, 0, '0000-00-00'),
(5, 'Nouveau films 21', 'Description 21', 2, 2, '0000-00-00'),
(6, 'test', 'desc', 25, 1, '2012-10-14'),
(7, 'ezfezfez', 'ezfezfezfezf', 1, 1, '2012-10-14'),
(8, 'PLPLPL', 'PLPLPLPLPLPL', 1, 1, '2012-10-14'),
(9, '', '', 1, 1, '2012-10-14'),
(10, '', '', 1, 1, '2012-10-14'),
(11, '', '', 1, 1, '2012-10-14'),
(12, '', '', 1, 1, '2012-10-14'),
(13, '', '', 1, 1, '2012-10-14'),
(14, '', '', 1, 1, '2012-10-14'),
(15, '', '', 1, 1, '2012-10-14'),
(16, '', '', 1, 1, '2012-10-14'),
(17, 'aze', '', 1, 1, '2012-10-14'),
(18, 'aaaa', '', 1, 1, '2012-10-14'),
(19, 'aze', '', 1, 1, '2012-10-14'),
(20, 'aaa', '', 1, 1, '2012-10-14'),
(21, 'eeee', '', 1, 1, '2012-10-14'),
(22, 'eeee', '', 1, 1, '2012-10-14'),
(23, 'eeee', '', 1, 1, '2012-10-14'),
(24, 'eeee', '', 1, 1, '2012-10-14'),
(25, 'eeee', '', 1, 1, '2012-10-14');

-- --------------------------------------------------------

--
-- Structure de la table `mc_user`
--

CREATE TABLE IF NOT EXISTS `mc_user` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `user_fb` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `mc_user`
--

INSERT INTO `mc_user` (`id_user`, `user_fb`) VALUES
(1, 'AAAAAAAA'),
(2, 'BBBBBBBB');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `mc_favorite`
--
ALTER TABLE `mc_favorite`
  ADD CONSTRAINT `mc_favorite_ibfk_1` FOREIGN KEY (`ID_project`) REFERENCES `mc_project` (`ID_project`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `mc_favorite_ibfk_2` FOREIGN KEY (`ID_user`) REFERENCES `mc_user` (`ID_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;
