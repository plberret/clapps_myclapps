# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.9)
# Database: appliMyClapps
# Generation Time: 2012-10-23 15:26:59 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table mc_favorite
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_favorite`;

CREATE TABLE `mc_favorite` (
  `id_project` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  KEY `ID_project` (`id_project`),
  KEY `ID_user` (`id_user`),
  CONSTRAINT `mc_favorite_ibfk_1` FOREIGN KEY (`id_project`) REFERENCES `mc_project` (`id_project`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `mc_favorite_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `mc_users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `mc_favorite` WRITE;
/*!40000 ALTER TABLE `mc_favorite` DISABLE KEYS */;

INSERT INTO `mc_favorite` (`id_project`, `id_user`)
VALUES
	(3,1);

/*!40000 ALTER TABLE `mc_favorite` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_profile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_profile`;

CREATE TABLE `mc_profile` (
  `id_profile` int(10) NOT NULL AUTO_INCREMENT,
  `id_project` int(10) NOT NULL,
  `person` varchar(250) NOT NULL,
  `domain` int(1) NOT NULL,
  `current_state` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `mc_profile` WRITE;
/*!40000 ALTER TABLE `mc_profile` DISABLE KEYS */;

INSERT INTO `mc_profile` (`id_profile`, `id_project`, `person`, `domain`, `current_state`)
VALUES
	(1,1,'Un acteur blond',1,1),
	(2,1,'Une maquilleuse',2,1),
	(3,1,'Un acteur roux',1,0),
	(9,30,'Acteur 1',1,1),
	(10,30,'Tecos 1',2,1),
	(11,30,'Acteur 2',1,1),
	(12,30,'Tecos 2',2,1),
	(13,31,'Costumier',2,1),
	(14,32,'TEst',2,1);

/*!40000 ALTER TABLE `mc_profile` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_project
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_project`;

CREATE TABLE `mc_project` (
  `id_project` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `id_creator` int(10) NOT NULL,
  `current_state` int(1) NOT NULL DEFAULT '1',
  `create_date` date NOT NULL,
  PRIMARY KEY (`id_project`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `mc_project` WRITE;
/*!40000 ALTER TABLE `mc_project` DISABLE KEYS */;

INSERT INTO `mc_project` (`id_project`, `title`, `description`, `id_creator`, `current_state`, `create_date`)
VALUES
	(1,'Mon projet 1','description de mon projet ',1,1,'0000-00-00'),
	(2,'Prochain d\'un autre 1','description du projet',2,1,'0000-00-00'),
	(3,'Prochain d\'un autre 2','description',2,1,'0000-00-00'),
	(4,'Nouveau films 18','Description 18',2,0,'0000-00-00'),
	(5,'Nouveau films 21','Description 21',2,2,'0000-00-00'),
	(30,'MyTest','',1,1,'2012-10-21'),
	(31,'Court métrage pornographique','Un super film pornographique animalier',1,1,'2012-10-22'),
	(32,'TEst','Test',1,1,'2012-10-22');

/*!40000 ALTER TABLE `mc_project` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_users`;

CREATE TABLE `mc_users` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `user_fb` varchar(100) CHARACTER SET latin1 NOT NULL,
  `img_url` varchar(100) DEFAULT '',
  `name` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `mc_users` WRITE;
/*!40000 ALTER TABLE `mc_users` DISABLE KEYS */;

INSERT INTO `mc_users` (`id_user`, `user_fb`, `img_url`, `name`)
VALUES
	(1,'AAAAAAAA','./images/img_test.jpg','Pierre-Loic'),
	(2,'BBBBBBBB','./images/img_test.jpg','Léonard');

/*!40000 ALTER TABLE `mc_users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
