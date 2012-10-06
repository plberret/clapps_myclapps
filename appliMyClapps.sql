-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Sam 06 Octobre 2012 à 15:16
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
  `ID_project` int(10) NOT NULL,
  `ID_user` int(10) NOT NULL,
  KEY `ID_project` (`ID_project`),
  KEY `ID_user` (`ID_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `mc_favorite`
--

INSERT INTO `mc_favorite` (`ID_project`, `ID_user`) VALUES
(3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `mc_profile`
--

CREATE TABLE IF NOT EXISTS `mc_profile` (
  `ID_profile` int(10) NOT NULL AUTO_INCREMENT,
  `ID_project` int(10) NOT NULL,
  `Person` varchar(250) NOT NULL,
  `Domain` int(1) NOT NULL,
  `Current_state` int(1) NOT NULL,
  PRIMARY KEY (`ID_profile`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `mc_profile`
--

INSERT INTO `mc_profile` (`ID_profile`, `ID_project`, `Person`, `Domain`, `Current_state`) VALUES
(1, 1, 'Un acteur blond', 1, 1),
(2, 1, 'Une maquilleuse', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `mc_project`
--

CREATE TABLE IF NOT EXISTS `mc_project` (
  `ID_project` int(10) NOT NULL AUTO_INCREMENT,
  `Title` varchar(250) CHARACTER SET latin1 NOT NULL,
  `Description` text CHARACTER SET latin1 NOT NULL,
  `ID_creator` int(10) NOT NULL,
  `Current_state` int(1) NOT NULL,
  PRIMARY KEY (`ID_project`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `mc_project`
--

INSERT INTO `mc_project` (`ID_project`, `Title`, `Description`, `ID_creator`, `Current_state`) VALUES
(1, 'Mon projet 1', 'description de mon projet ', 1, 1),
(2, 'Prochain d''un autre 1', 'description du projet', 2, 1),
(3, 'Prochain d''un autre 2', 'description', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `mc_user`
--

CREATE TABLE IF NOT EXISTS `mc_user` (
  `ID_user` int(10) NOT NULL AUTO_INCREMENT,
  `User_fb` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ID_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `mc_user`
--

INSERT INTO `mc_user` (`ID_user`, `User_fb`) VALUES
(1, 'AAAAAAAA');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `mc_favorite`
--
ALTER TABLE `mc_favorite`
  ADD CONSTRAINT `mc_favorite_ibfk_1` FOREIGN KEY (`ID_project`) REFERENCES `mc_project` (`ID_project`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `mc_favorite_ibfk_2` FOREIGN KEY (`ID_user`) REFERENCES `mc_user` (`ID_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;
