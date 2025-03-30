/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.8-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: oboba
-- ------------------------------------------------------
-- Server version	10.11.8-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `borne`
--

DROP TABLE IF EXISTS `borne`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `borne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_bornier` int(11) NOT NULL,
  `identification` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D7465BA51F16DA8F` (`id_bornier`),
  CONSTRAINT `FK_D7465BA51F16DA8F` FOREIGN KEY (`id_bornier`) REFERENCES `bornier` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borne`
--

LOCK TABLES `borne` WRITE;
/*!40000 ALTER TABLE `borne` DISABLE KEYS */;
INSERT INTO `borne` VALUES
(1,1,'Borne 1'),
(2,1,'Borne 2'),
(3,2,'Borne 1'),
(4,2,'Borne 2'),
(5,3,'Borne 1'),
(6,4,'Borne 1');
/*!40000 ALTER TABLE `borne` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bornier`
--

DROP TABLE IF EXISTS `bornier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bornier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_catalogue_projet_bornier` int(11) NOT NULL,
  `id_projet` int(11) NOT NULL,
  `id_localisation` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_projet_unique` (`nom`,`id_projet`),
  KEY `IDX_C18B73FFD63520D2` (`id_catalogue_projet_bornier`),
  KEY `IDX_C18B73FF76222944` (`id_projet`),
  KEY `IDX_C18B73FF9C12BBFD` (`id_localisation`),
  CONSTRAINT `FK_C18B73FF76222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`),
  CONSTRAINT `FK_C18B73FF9C12BBFD` FOREIGN KEY (`id_localisation`) REFERENCES `localisation` (`id`),
  CONSTRAINT `FK_C18B73FFD63520D2` FOREIGN KEY (`id_catalogue_projet_bornier`) REFERENCES `catalogue_projet_borniers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bornier`
--

LOCK TABLES `bornier` WRITE;
/*!40000 ALTER TABLE `bornier` DISABLE KEYS */;
INSERT INTO `bornier` VALUES
(1,1,1,1,'Bornier A1'),
(2,2,1,2,'Bornier B1'),
(3,3,2,3,'Bornier C2'),
(4,4,2,4,'Bornier D2');
/*!40000 ALTER TABLE `bornier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cable`
--

DROP TABLE IF EXISTS `cable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_projet` int(11) DEFAULT NULL,
  `id_catalogue_projet_cable` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `longueur` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_24E9C4D476222944` (`id_projet`),
  KEY `IDX_24E9C4D47FCB71D8` (`id_catalogue_projet_cable`),
  CONSTRAINT `FK_24E9C4D476222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`),
  CONSTRAINT `FK_24E9C4D47FCB71D8` FOREIGN KEY (`id_catalogue_projet_cable`) REFERENCES `catalogue_projet_cables` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cable`
--

LOCK TABLES `cable` WRITE;
/*!40000 ALTER TABLE `cable` DISABLE KEYS */;
INSERT INTO `cable` VALUES
(1,1,1,'Câble A1',5),
(2,1,2,'Câble B1',10),
(3,2,3,'Câble C2',15),
(4,2,4,'Câble D2',20),
(5,1,8,'Câble test',NULL),
(6,1,8,'câble beta test',NULL);
/*!40000 ALTER TABLE `cable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogue_borne`
--

DROP TABLE IF EXISTS `catalogue_borne`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogue_borne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalogue_modele_borniers_id` int(11) DEFAULT NULL,
  `catalogue_projet_borniers_id` int(11) DEFAULT NULL,
  `attribut` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CF5A29149540A349` (`catalogue_modele_borniers_id`),
  KEY `IDX_CF5A2914843362EA` (`catalogue_projet_borniers_id`),
  CONSTRAINT `FK_CF5A2914843362EA` FOREIGN KEY (`catalogue_projet_borniers_id`) REFERENCES `catalogue_projet_borniers` (`id`),
  CONSTRAINT `FK_CF5A29149540A349` FOREIGN KEY (`catalogue_modele_borniers_id`) REFERENCES `catalogue_modele_borniers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_borne`
--

LOCK TABLES `catalogue_borne` WRITE;
/*!40000 ALTER TABLE `catalogue_borne` DISABLE KEYS */;
INSERT INTO `catalogue_borne` VALUES
(33,NULL,NULL,'43'),
(34,NULL,5,'1'),
(35,NULL,5,'2'),
(36,NULL,5,'3'),
(37,NULL,5,'4'),
(38,NULL,NULL,NULL),
(42,13,NULL,'1'),
(43,13,NULL,'2'),
(44,13,NULL,'3'),
(45,13,NULL,'4'),
(46,13,NULL,'5'),
(47,13,NULL,'6'),
(48,13,NULL,'7'),
(49,13,NULL,'8'),
(50,14,NULL,'1'),
(51,14,NULL,'2'),
(52,14,NULL,'3'),
(53,14,NULL,'4'),
(54,NULL,9,'1'),
(55,NULL,9,'2'),
(56,NULL,9,'3'),
(57,NULL,9,'4');
/*!40000 ALTER TABLE `catalogue_borne` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogue_conducteur`
--

DROP TABLE IF EXISTS `catalogue_conducteur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogue_conducteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalogue_modele_cables_id` int(11) DEFAULT NULL,
  `catalogue_projet_cables_id` int(11) DEFAULT NULL,
  `attribut` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_58D1FABD2A94A665` (`catalogue_modele_cables_id`),
  KEY `IDX_58D1FABDE70CF8A3` (`catalogue_projet_cables_id`),
  CONSTRAINT `FK_58D1FABD2A94A665` FOREIGN KEY (`catalogue_modele_cables_id`) REFERENCES `catalogue_modele_cables` (`id`),
  CONSTRAINT `FK_58D1FABDE70CF8A3` FOREIGN KEY (`catalogue_projet_cables_id`) REFERENCES `catalogue_projet_cables` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_conducteur`
--

LOCK TABLES `catalogue_conducteur` WRITE;
/*!40000 ALTER TABLE `catalogue_conducteur` DISABLE KEYS */;
INSERT INTO `catalogue_conducteur` VALUES
(39,11,NULL,'1A'),
(40,11,NULL,'1B'),
(41,11,NULL,'2A'),
(42,11,NULL,'2B'),
(43,11,NULL,'3A'),
(44,11,NULL,'3B'),
(45,11,NULL,'4A'),
(46,11,NULL,'4B'),
(47,11,NULL,'5A'),
(48,11,NULL,'5B'),
(49,11,NULL,'6A'),
(50,11,NULL,'6B'),
(51,11,NULL,'7A'),
(52,11,NULL,'7B'),
(53,11,NULL,'8A'),
(54,11,NULL,'8B'),
(55,11,NULL,'9A'),
(56,11,NULL,'9B'),
(57,11,NULL,'10A'),
(58,11,NULL,'10B'),
(59,11,NULL,'11A'),
(60,11,NULL,'11B'),
(61,11,NULL,'12A'),
(62,11,NULL,'12B'),
(63,11,NULL,'13A'),
(64,11,NULL,'13B'),
(65,11,NULL,'14A'),
(66,11,NULL,'14B'),
(67,11,NULL,'15A'),
(68,11,NULL,'15B'),
(69,11,NULL,'16A'),
(70,11,NULL,'16B'),
(71,11,NULL,'17A'),
(72,11,NULL,'17B'),
(73,11,NULL,'18A'),
(74,11,NULL,'18B'),
(75,12,NULL,'1'),
(76,12,NULL,'2'),
(77,13,NULL,'1'),
(78,13,NULL,'2'),
(79,14,NULL,'1'),
(80,14,NULL,'2'),
(81,NULL,14,'1A'),
(82,NULL,14,'1B'),
(83,NULL,14,'2A'),
(84,NULL,14,'2B'),
(85,NULL,14,'3A'),
(86,NULL,14,'3B'),
(87,NULL,14,'4A'),
(88,NULL,14,'4B'),
(89,NULL,14,'5A'),
(90,NULL,14,'5B'),
(91,NULL,14,'6A'),
(92,NULL,14,'6B'),
(93,NULL,14,'7A'),
(94,NULL,14,'7B'),
(95,NULL,14,'8A'),
(96,NULL,14,'8B'),
(97,NULL,14,'9A'),
(98,NULL,14,'9B'),
(99,NULL,14,'10A'),
(100,NULL,14,'10B'),
(101,NULL,14,'11A'),
(102,NULL,14,'11B'),
(103,NULL,14,'12A'),
(104,NULL,14,'12B'),
(105,NULL,14,'13A'),
(106,NULL,14,'13B'),
(107,NULL,14,'14A'),
(108,NULL,14,'14B'),
(109,NULL,14,'15A'),
(110,NULL,14,'15B'),
(111,NULL,14,'16A'),
(112,NULL,14,'16B'),
(113,NULL,14,'17A'),
(114,NULL,14,'17B'),
(115,NULL,14,'18A'),
(116,NULL,14,'18B'),
(131,NULL,5,'1'),
(132,NULL,5,'2'),
(133,15,NULL,'1'),
(134,15,NULL,'2'),
(135,15,NULL,'3'),
(136,15,NULL,'4');
/*!40000 ALTER TABLE `catalogue_conducteur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogue_modele_borniers`
--

DROP TABLE IF EXISTS `catalogue_modele_borniers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogue_modele_borniers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `nombre_bornes` int(11) NOT NULL,
  `caracteristiques` varchar(255) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_NOM` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_modele_borniers`
--

LOCK TABLES `catalogue_modele_borniers` WRITE;
/*!40000 ALTER TABLE `catalogue_modele_borniers` DISABLE KEYS */;
INSERT INTO `catalogue_modele_borniers` VALUES
(13,'Bornier à vis sur rail DIN',8,'Section de fil : 0,14 à 4 mm²',0.00),
(14,'Bornier à connexion rapide (Push-In)',4,'Section de fil : 0,25 à 2,5 mm²',0.00);
/*!40000 ALTER TABLE `catalogue_modele_borniers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogue_modele_cables`
--

DROP TABLE IF EXISTS `catalogue_modele_cables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogue_modele_cables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `nb_conducteurs` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5216F85D6C6E55B5` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_modele_cables`
--

LOCK TABLES `catalogue_modele_cables` WRITE;
/*!40000 ALTER TABLE `catalogue_modele_cables` DISABLE KEYS */;
INSERT INTO `catalogue_modele_cables` VALUES
(1,'Câble unipolaire H07V-U 1.5mm²',1,0.50,'Unipolaire'),
(2,'Câble multipaire 2x1.5mm²',2,1.00,'Multipaire'),
(3,'Câble blindé CY 2x0.75mm²',2,1.50,'Blindé'),
(4,'Câble réseau UTP Cat5e',8,0.90,'Réseau'),
(5,'Câble coaxial RG58',1,1.10,'Coaxial'),
(7,'Câble multipaire 12x1.5mm²',12,0.00,'multipaires'),
(9,'Test débile',40,0.00,'debilos company'),
(11,'débilos',36,0.00,'debil company'),
(12,'X',2,0.00,'X'),
(13,'Y',2,0.00,'Y'),
(14,'Z',2,0.00,'Z'),
(15,'Câble test2',4,0.00,'XXX');
/*!40000 ALTER TABLE `catalogue_modele_cables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogue_modele_connecteurs`
--

DROP TABLE IF EXISTS `catalogue_modele_connecteurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogue_modele_connecteurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `nombre_contacts` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_NOM` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_modele_connecteurs`
--

LOCK TABLES `catalogue_modele_connecteurs` WRITE;
/*!40000 ALTER TABLE `catalogue_modele_connecteurs` DISABLE KEYS */;
INSERT INTO `catalogue_modele_connecteurs` VALUES
(1,'Connecteur DB9 Mâle',9,'Série',1.50),
(2,'Connecteur RJ45 Mâle',8,'Réseau',0.80),
(3,'Connecteur USB-A Mâle',4,'USB',0.70),
(4,'Connecteur XLR 3P Mâle',3,'Audio',2.00),
(5,'Connecteur Molex Mini-Fit 4P',4,'Molex',1.20);
/*!40000 ALTER TABLE `catalogue_modele_connecteurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogue_projet_borniers`
--

DROP TABLE IF EXISTS `catalogue_projet_borniers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogue_projet_borniers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projet_id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nombre_bornes` int(11) NOT NULL,
  `caracteristiques` varchar(255) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D1093D9BC18272` (`projet_id`),
  CONSTRAINT `FK_D1093D9BC18272` FOREIGN KEY (`projet_id`) REFERENCES `projet` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_projet_borniers`
--

LOCK TABLES `catalogue_projet_borniers` WRITE;
/*!40000 ALTER TABLE `catalogue_projet_borniers` DISABLE KEYS */;
INSERT INTO `catalogue_projet_borniers` VALUES
(1,1,'Bornier à vis 2P - ref XXX',2,'2.5mm² max',0.80),
(2,1,'Bornier à ressort 4P',4,'2.5mm² max',1.40),
(3,2,'Bornier DIN 2P',2,'10mm² max',1.00),
(4,2,'Bornier PCB 3P',3,'1.5mm² max',0.80),
(5,1,'Bornier à vis 4P',4,'4mm² max',1.20),
(6,1,'Bornier à ressort 2P',2,'1.5mm² max',0.90),
(7,1,'Bornier DIN 2P',2,'10mm² max',1.00),
(8,1,'Bornier PCB 3P',3,'1.5mm² max',0.80),
(9,1,'EEEEE',4,NULL,0.00);
/*!40000 ALTER TABLE `catalogue_projet_borniers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogue_projet_cables`
--

DROP TABLE IF EXISTS `catalogue_projet_cables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogue_projet_cables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_projet` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nb_conducteurs` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_projet_nom` (`id_projet`,`nom`),
  KEY `IDX_FD1289CC76222944` (`id_projet`),
  CONSTRAINT `FK_FD1289CC76222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_projet_cables`
--

LOCK TABLES `catalogue_projet_cables` WRITE;
/*!40000 ALTER TABLE `catalogue_projet_cables` DISABLE KEYS */;
INSERT INTO `catalogue_projet_cables` VALUES
(1,1,'Câble unipolaire H07V-U 1.5mm²',1,0.50,'Unipolaire'),
(2,1,'Câble multipaire 2x1.5mm²',2,1.00,'Multipaire'),
(3,2,'Câble blindé CY 2x0.75mm²',2,1.50,'Blindé'),
(4,2,'Câble réseau UTP Cat5e',8,0.90,'Réseau'),
(5,1,'Blindé CY 2x0.75mm²',2,1.50,'Blindé'),
(6,1,'Câble réseau UTP Cat5e',8,0.90,'Réseau'),
(7,1,'Câble coaxial RG58',1,1.10,'Coaxial'),
(8,1,'Câble multipaire 12x1.5mm²',12,0.00,'multipaires'),
(13,1,'Test débile',40,0.00,'debilos company'),
(14,1,'débilos',36,0.00,'debil company');
/*!40000 ALTER TABLE `catalogue_projet_cables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogue_projet_connecteurs`
--

DROP TABLE IF EXISTS `catalogue_projet_connecteurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogue_projet_connecteurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_projet` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nombre_contacts` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FBBDF5AE76222944` (`id_projet`),
  CONSTRAINT `FK_FBBDF5AE76222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_projet_connecteurs`
--

LOCK TABLES `catalogue_projet_connecteurs` WRITE;
/*!40000 ALTER TABLE `catalogue_projet_connecteurs` DISABLE KEYS */;
INSERT INTO `catalogue_projet_connecteurs` VALUES
(1,1,'Connecteur DB9 Mâle',9,'Série',1.50),
(2,1,'Connecteur RJ45 Mâle',8,'Réseau',0.80),
(3,2,'Connecteur USB-A Mâle',4,'USB',0.70),
(4,2,'Connecteur XLR 3P Mâle',3,'Audio',2.00),
(5,1,'Connecteur USB-A Mâle',4,'USB',0.70),
(6,1,'Connecteur XLR 3P Mâle',3,'Audio',2.00),
(7,1,'Connecteur Molex Mini-Fit 4P',4,'Molex',1.20);
/*!40000 ALTER TABLE `catalogue_projet_connecteurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conducteur`
--

DROP TABLE IF EXISTS `conducteur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conducteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cable` int(11) NOT NULL,
  `id_borne_source` int(11) DEFAULT NULL,
  `id_borne_destination` int(11) DEFAULT NULL,
  `id_contact_source` int(11) DEFAULT NULL,
  `id_contact_destination` int(11) DEFAULT NULL,
  `id_wire_signal` int(11) DEFAULT NULL,
  `attribut` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_23677143CA6C85E4` (`id_cable`),
  KEY `IDX_236771439A92C5B6` (`id_borne_source`),
  KEY `IDX_23677143C944263C` (`id_borne_destination`),
  KEY `IDX_23677143F27EF5F4` (`id_contact_source`),
  KEY `IDX_2367714316801DDF` (`id_contact_destination`),
  KEY `IDX_23677143DF73FC55` (`id_wire_signal`),
  CONSTRAINT `FK_2367714316801DDF` FOREIGN KEY (`id_contact_destination`) REFERENCES `contact` (`id`),
  CONSTRAINT `FK_236771439A92C5B6` FOREIGN KEY (`id_borne_source`) REFERENCES `borne` (`id`),
  CONSTRAINT `FK_23677143C944263C` FOREIGN KEY (`id_borne_destination`) REFERENCES `borne` (`id`),
  CONSTRAINT `FK_23677143CA6C85E4` FOREIGN KEY (`id_cable`) REFERENCES `cable` (`id`),
  CONSTRAINT `FK_23677143DF73FC55` FOREIGN KEY (`id_wire_signal`) REFERENCES `wire_signal` (`id`),
  CONSTRAINT `FK_23677143F27EF5F4` FOREIGN KEY (`id_contact_source`) REFERENCES `contact` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conducteur`
--

LOCK TABLES `conducteur` WRITE;
/*!40000 ALTER TABLE `conducteur` DISABLE KEYS */;
INSERT INTO `conducteur` VALUES
(1,1,1,NULL,NULL,1,1,'Conducteur 1'),
(2,2,3,4,NULL,NULL,2,'Conducteur 1'),
(3,3,5,NULL,4,NULL,3,'Conducteur 1'),
(4,5,NULL,NULL,NULL,NULL,NULL,'1'),
(5,5,NULL,NULL,NULL,NULL,NULL,'2'),
(6,5,NULL,NULL,NULL,NULL,NULL,'3'),
(7,5,NULL,NULL,NULL,NULL,NULL,'4'),
(8,5,NULL,NULL,NULL,NULL,NULL,'5'),
(9,5,NULL,NULL,NULL,NULL,NULL,'6'),
(10,5,NULL,NULL,NULL,NULL,NULL,'7'),
(11,5,NULL,NULL,NULL,NULL,NULL,'8'),
(12,5,NULL,NULL,NULL,NULL,NULL,'9'),
(13,5,NULL,NULL,NULL,NULL,NULL,'10'),
(14,5,NULL,NULL,NULL,NULL,NULL,'11'),
(15,5,NULL,NULL,NULL,NULL,NULL,'12'),
(16,6,NULL,NULL,NULL,NULL,NULL,'1'),
(17,6,NULL,NULL,NULL,NULL,NULL,'2'),
(18,6,NULL,NULL,NULL,NULL,NULL,'3'),
(19,6,NULL,NULL,NULL,NULL,NULL,'4'),
(20,6,NULL,NULL,NULL,NULL,NULL,'5'),
(21,6,NULL,NULL,NULL,NULL,NULL,'6'),
(22,6,NULL,NULL,NULL,NULL,NULL,'7'),
(23,6,NULL,NULL,NULL,NULL,NULL,'8'),
(24,6,NULL,NULL,NULL,NULL,NULL,'9'),
(25,6,NULL,NULL,NULL,NULL,NULL,'10'),
(26,6,NULL,NULL,NULL,NULL,NULL,'11'),
(27,6,NULL,NULL,NULL,NULL,NULL,'12');
/*!40000 ALTER TABLE `conducteur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `connecteur`
--

DROP TABLE IF EXISTS `connecteur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `connecteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_projet` int(11) DEFAULT NULL,
  `id_catalogue_projet_connecteur` int(11) DEFAULT NULL,
  `id_equipement` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_84C12C9676222944` (`id_projet`),
  KEY `IDX_84C12C9691B692E` (`id_catalogue_projet_connecteur`),
  KEY `IDX_84C12C961D3E4624` (`id_equipement`),
  CONSTRAINT `FK_84C12C961D3E4624` FOREIGN KEY (`id_equipement`) REFERENCES `equipement` (`id`),
  CONSTRAINT `FK_84C12C9676222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`),
  CONSTRAINT `FK_84C12C9691B692E` FOREIGN KEY (`id_catalogue_projet_connecteur`) REFERENCES `catalogue_projet_connecteurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `connecteur`
--

LOCK TABLES `connecteur` WRITE;
/*!40000 ALTER TABLE `connecteur` DISABLE KEYS */;
INSERT INTO `connecteur` VALUES
(1,1,1,1,'Connecteur DB9 S1'),
(2,1,2,NULL,'Connecteur RJ45 C1'),
(3,2,3,2,'Connecteur USB SW1'),
(4,2,4,NULL,'Connecteur XLR A1');
/*!40000 ALTER TABLE `connecteur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_connecteur` int(11) NOT NULL,
  `identifiant` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C62E638214BAC41` (`id_connecteur`),
  CONSTRAINT `FK_4C62E638214BAC41` FOREIGN KEY (`id_connecteur`) REFERENCES `connecteur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES
(1,1,'Pin 1','Mâle'),
(2,1,'Pin 2','Mâle'),
(3,2,'Pin 1','Mâle'),
(4,3,'Pin 1','Mâle'),
(5,4,'Pin 1','Mâle');
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20250327181706','2025-03-27 18:17:45',17171),
('DoctrineMigrations\\Version20250329194048','2025-03-29 19:41:38',2375),
('DoctrineMigrations\\Version20250329202544','2025-03-29 20:26:05',291),
('DoctrineMigrations\\Version20250330132907','2025-03-30 13:29:35',10419),
('DoctrineMigrations\\Version20250330133250','2025-03-30 13:33:00',2308);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipement`
--

DROP TABLE IF EXISTS `equipement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_projet` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `reference` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B8B4C6F376222944` (`id_projet`),
  CONSTRAINT `FK_B8B4C6F376222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipement`
--

LOCK TABLES `equipement` WRITE;
/*!40000 ALTER TABLE `equipement` DISABLE KEYS */;
INSERT INTO `equipement` VALUES
(1,1,'Serveur principal','SRV-001'),
(2,2,'Switch réseau','SWT-002');
/*!40000 ALTER TABLE `equipement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `localisation`
--

DROP TABLE IF EXISTS `localisation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `localisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_projet` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `x` double DEFAULT NULL,
  `y` double DEFAULT NULL,
  `z` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_unique` (`nom`),
  KEY `IDX_BFD3CE8F76222944` (`id_projet`),
  CONSTRAINT `FK_BFD3CE8F76222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `localisation`
--

LOCK TABLES `localisation` WRITE;
/*!40000 ALTER TABLE `localisation` DISABLE KEYS */;
INSERT INTO `localisation` VALUES
(1,1,'Armoire 1 - Rangée 1',10.5,20,NULL),
(2,1,'Salle serveur',NULL,NULL,NULL),
(3,2,'Zone A - Étage 1',15,25,0),
(4,2,'Zone B - Sous-sol',15,30,-2.5),
(6,1,'Labo',12.5,2,23);
/*!40000 ALTER TABLE `localisation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projet`
--

DROP TABLE IF EXISTS `projet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `date_heure_creation` datetime NOT NULL,
  `date_heure_derniere_modification` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projet`
--

LOCK TABLES `projet` WRITE;
/*!40000 ALTER TABLE `projet` DISABLE KEYS */;
INSERT INTO `projet` VALUES
(1,'Projet Alpha','2025-03-27 10:00:00','2025-03-30 11:37:01'),
(2,'Projet Beta','2025-03-27 11:00:00','2025-03-27 11:00:00');
/*!40000 ALTER TABLE `projet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projet_utilisateur`
--

DROP TABLE IF EXISTS `projet_utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projet_utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projet_id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C626378DC18272` (`projet_id`),
  KEY `IDX_C626378DFB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_C626378DC18272` FOREIGN KEY (`projet_id`) REFERENCES `projet` (`id`),
  CONSTRAINT `FK_C626378DFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projet_utilisateur`
--

LOCK TABLES `projet_utilisateur` WRITE;
/*!40000 ALTER TABLE `projet_utilisateur` DISABLE KEYS */;
INSERT INTO `projet_utilisateur` VALUES
(3,1,2,'proprietaire'),
(4,1,4,'lecteur'),
(6,2,3,'proprietaire'),
(7,2,2,'concepteur'),
(8,2,4,'lecteur'),
(9,1,3,'concepteur');
/*!40000 ALTER TABLE `projet_utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1D1C63B3E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES
(2,'Marie Curie','marie.curie@example.com','$2y$13$UOYEjXjQrtI0GpgEszV/8O.oWlQJuiloRj7UPqKWHTj9mCJ781jIa','[\"ROLE_ADMIN\"]'),
(3,'Paul Martin','paul.martin@example.com','$2y$13$TG.jjDbsT1iPoJIl8xqNJ.teP536.8DD36cS6f.cJ/K2kh4p7EAd2','[]'),
(4,'Alfred','alfred@example.com','$2y$13$qXyfeqba5DPOoo6.tMREl.MLHWw9Tx5GKi9gHxRqlwW83wCyypByO','[]');
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wire_signal`
--

DROP TABLE IF EXISTS `wire_signal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wire_signal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_projet` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `details` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C76131A26C6E55B5` (`nom`),
  KEY `IDX_C76131A276222944` (`id_projet`),
  CONSTRAINT `FK_C76131A276222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wire_signal`
--

LOCK TABLES `wire_signal` WRITE;
/*!40000 ALTER TABLE `wire_signal` DISABLE KEYS */;
INSERT INTO `wire_signal` VALUES
(1,1,'Signal Alim','Power','24V DC'),
(2,1,'Signal Data','Data','Ethernet'),
(3,2,'Signal Audio','Audio','Mono');
/*!40000 ALTER TABLE `wire_signal` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-30 17:26:00
