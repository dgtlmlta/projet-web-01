-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: stampee.cerberus    Database: stampee
-- ------------------------------------------------------
-- Server version	5.5.5-10.3.29-MariaDB-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auction`
--

DROP TABLE IF EXISTS `auction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timeStart` datetime NOT NULL,
  `timeEnd` datetime NOT NULL,
  `startPrice` decimal(10,2) NOT NULL,
  `timeCreated` datetime NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `stampId` int(10) unsigned NOT NULL,
  `sellerId` int(10) unsigned NOT NULL,
  `winnerId` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auction_FK_stamp` (`stampId`),
  KEY `auction_FK_seller` (`sellerId`),
  KEY `auction_FK_winner` (`winnerId`),
  CONSTRAINT `auction_FK_seller` FOREIGN KEY (`sellerId`) REFERENCES `user` (`id`),
  CONSTRAINT `auction_FK_stamp` FOREIGN KEY (`stampId`) REFERENCES `stamp` (`id`),
  CONSTRAINT `auction_FK_winner` FOREIGN KEY (`winnerId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auction`
--

LOCK TABLES `auction` WRITE;
/*!40000 ALTER TABLE `auction` DISABLE KEYS */;
INSERT INTO `auction` VALUES (2,'2021-10-20 00:00:00','2021-10-23 00:00:00',1.00,'2021-07-01 07:44:33',1,15,15,NULL),(3,'2021-10-20 00:00:00','2021-10-23 00:00:00',1.00,'2021-07-01 07:51:27',1,16,15,NULL),(4,'2021-10-20 00:00:00','2021-10-23 00:00:00',1.00,'2021-07-01 08:11:44',1,17,15,NULL),(5,'2021-10-20 00:00:00','2021-10-23 00:00:00',1.00,'2021-07-01 08:18:06',1,18,16,NULL);
/*!40000 ALTER TABLE `auction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centeringCondition`
--

DROP TABLE IF EXISTS `centeringCondition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centeringCondition` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `description` tinytext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centeringCondition`
--

LOCK TABLES `centeringCondition` WRITE;
/*!40000 ALTER TABLE `centeringCondition` DISABLE KEYS */;
INSERT INTO `centeringCondition` VALUES (1,'A','Le design du timbre touche à une ou plusieurs bordure(s) et est affecté par la perforation.'),(2,'F','Le design du timbre est près des bordure, mais n\'est pas coupé par la perforation.\r\n'),(3,'F/VF','Le design du timbre est presque centré de gauche ainsi que de haut en bas.'),(4,'VF','Le design du timbre est presque centré de gauche à droite et parfaitement centré de haut en bas.'),(5,'XF','Le design du timbre est presque centré de haut en bas et parfaitement centré de gauche à droite.'),(6,'S','Le design du timbre est parfaitement centré.');
/*!40000 ALTER TABLE `centeringCondition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gumCondition`
--

DROP TABLE IF EXISTS `gumCondition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gumCondition` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gumCondition`
--

LOCK TABLES `gumCondition` WRITE;
/*!40000 ALTER TABLE `gumCondition` DISABLE KEYS */;
INSERT INTO `gumCondition` VALUES (1,'H','Le timbre a déjà eu une charnière.'),(2,'LH','Le timbre a déjà eu une charnière, mais la marque laissée au verso du timbre demeure légère.'),(3,'HH','Le timbre a déjà eu une charnière et la marque laissée au verso du timbre est visible et proéminente.'),(4,'HR','Le timbre a déjà eu une charnière et une partie de cette charniè est toujours présente au verso.'),(5,'DG','La gomme au verson a été endommagée par autre chose qu\'une charnière (ex.: empreintes digitales).'),(6,'NG','Le timbre est inutilisé et n\'a pas de gomme.'),(7,'RG','Le verso du timbre à reç une nouvelle gomme. Il arrive parfois que de vieux timbres furent re-gommés.'),(8,'MNH','Il s\'agit de la condition originale du timbre (et de la gomme) tel qu\'elle était lors de son achat au bureau de poste.');
/*!40000 ALTER TABLE `gumCondition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` tinyint(2) NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` tinytext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (22,'membre','Utilisateur enregistré auprès du portail. Peut vendre et acheter des timbres sur la plateforme.'),(88,'administrateur','Gestionnaire des comptes utilisateurs, des enchères et des timbres. Peut ajouter, modifier et supprimer ceux-ci.');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stamp`
--

DROP TABLE IF EXISTS `stamp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stamp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `year` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `denomination` varchar(20) NOT NULL,
  `color` varchar(45) NOT NULL,
  `isCertified` tinyint(1) NOT NULL,
  `country` varchar(40) NOT NULL,
  `conditionId` tinyint(3) unsigned NOT NULL,
  `gumId` tinyint(3) unsigned NOT NULL,
  `centeringId` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stamp`
--

LOCK TABLES `stamp` WRITE;
/*!40000 ALTER TABLE `stamp` DISABLE KEYS */;
INSERT INTO `stamp` VALUES (15,'Canada #158 1929 50c Dark Blue Bluenose, mint never hinged','Quite fresh and perfectly centered, extremely fine. A beautiful example of this lovely and popular engraved stamp. Accompanied by a 2020 Greene Foundation certificate.',1950,20,20,'50 cents','Bleu',1,'Canada',1,4,2),(16,'Lot 78 - AUSTRIA fine','Accession unsurf paper IMPERF PAIR,Michel 141x U,NHM,2',1978,20,20,'12 cents','Bleu',1,'Autriche',2,7,3),(17,'Canada #158 1929 50c Dark Blue Bluenose, mint never hinged','Quite fresh and perfectly centered, extremely fine. A beautiful example of this lovely and popular engraved stamp. Accompanied by a 2020 Greene Foundation certificate.',1855,20,20,'50 cents','Bleu',0,'Canada',1,5,3),(18,'Luxembourg 1962 Early Issue Fine Mint Hinged 1.50F. NW-134574','LUXEMBOURG - OFFICIAL 1891 12½c MISPLACED OVPT,Mi 48var,NHM,signed Demuth BPP',1910,120,130,'30 euros','Jaune',1,'Luxembourg',1,8,6);
/*!40000 ALTER TABLE `stamp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stampCondition`
--

DROP TABLE IF EXISTS `stampCondition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stampCondition` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stampCondition`
--

LOCK TABLES `stampCondition` WRITE;
/*!40000 ALTER TABLE `stampCondition` DISABLE KEYS */;
INSERT INTO `stampCondition` VALUES (1,'Parfait','Sans faute.'),(2,'VMF','Très peu de fautes.'),(3,'MF','Peu de fautes.'),(4,'F','A des fautes.'),(5,'MAF','A des fautes majeures.');
/*!40000 ALTER TABLE `stampCondition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stampImage`
--

DROP TABLE IF EXISTS `stampImage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stampImage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(200) NOT NULL,
  `timeAdded` datetime NOT NULL,
  `title` varchar(100) NOT NULL,
  `isMainImage` tinyint(1) NOT NULL,
  `stampId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `image_FK` (`stampId`),
  CONSTRAINT `image_FK` FOREIGN KEY (`stampId`) REFERENCES `stamp` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stampImage`
--

LOCK TABLES `stampImage` WRITE;
/*!40000 ALTER TABLE `stampImage` DISABLE KEYS */;
INSERT INTO `stampImage` VALUES (5,'/assets/img/stamps-db/15.jpg','2021-07-01 07:44:33','Canada #158 1929 50c Dark Blue Bluenose, mint never hinged',1,15),(6,'/assets/img/stamps-db/16.jpg','2021-07-01 07:51:27','Lot 78 - AUSTRIA fine',1,16),(7,'/assets/img/stamps-db/17.jpg','2021-07-01 08:11:44','Canada #158 1929 50c Dark Blue Bluenose, mint never hinged',1,17),(8,'/assets/img/stamps-db/18.jpg','2021-07-01 08:18:06','Luxembourg 1962 Early Issue Fine Mint Hinged 1.50F. NW-134574',1,18);
/*!40000 ALTER TABLE `stampImage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` char(60) NOT NULL,
  `newPassword` char(60) DEFAULT NULL,
  `dateCreated` datetime NOT NULL,
  `roleId` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_UQ` (`username`),
  KEY `user_FK` (`roleId`),
  CONSTRAINT `user_FK` FOREIGN KEY (`roleId`) REFERENCES `role` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (14,'Soundwave','D','soundwave@decepti.con','soundwave@decepti.con','$2y$10$EO3X4jtTSJYGnLMdCxrw7ea8ijGwWPoFF6EjXd1pyNklspKEhk/BO',NULL,'2021-07-01 05:52:41',88),(15,'Omega','Supreme','omega@auto.bot','omega@auto.bot','$2y$10$1UXxtdfBlryG66RJTPRDBOxuH02.E2/7KUmc6smU26o/WJUltL8Oe',NULL,'2021-07-01 05:53:19',22),(16,'Bumble','Bee','bumblebee@auto.bot','bumblebee@auto.bot','$2y$10$VMVyO4iiy8Zhct0JCEPideSMoS39bYmq1hKbtDV2ak1FHk41m2f3m',NULL,'2021-07-01 05:54:04',22);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'stampee'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-07-01 11:08:34
