/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: oboba
-- ------------------------------------------------------
-- Server version	10.6.18-MariaDB-0ubuntu0.22.04.1

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borne`
--

LOCK TABLES `borne` WRITE;
/*!40000 ALTER TABLE `borne` DISABLE KEYS */;
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
  `nom` varchar(255) NOT NULL,
  `localisation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom_projet_unique` (`nom`,`id_projet`),
  KEY `IDX_C18B73FFD63520D2` (`id_catalogue_projet_bornier`),
  KEY `IDX_C18B73FF76222944` (`id_projet`),
  CONSTRAINT `FK_C18B73FF76222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`),
  CONSTRAINT `FK_C18B73FFD63520D2` FOREIGN KEY (`id_catalogue_projet_bornier`) REFERENCES `catalogue_projet_borniers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bornier`
--

LOCK TABLES `bornier` WRITE;
/*!40000 ALTER TABLE `bornier` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cable`
--

LOCK TABLES `cable` WRITE;
/*!40000 ALTER TABLE `cable` DISABLE KEYS */;
/*!40000 ALTER TABLE `cable` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_modele_borniers`
--

LOCK TABLES `catalogue_modele_borniers` WRITE;
/*!40000 ALTER TABLE `catalogue_modele_borniers` DISABLE KEYS */;
INSERT INTO `catalogue_modele_borniers` VALUES (1,'Bornier à vis 2P',2,'2.5mm² max',0.80),(2,'Bornier à vis 4P',4,'4mm² max',1.20),(3,'Bornier à vis 6P',6,'6mm² max',1.80),(4,'Bornier à ressort 2P',2,'1.5mm² max',0.90),(5,'Bornier à ressort 4P',4,'2.5mm² max',1.40),(6,'Bornier à ressort 8P',8,'4mm² max',2.50),(7,'Bornier DIN 2P',2,'10mm² max',1.00),(8,'Bornier DIN 4P',4,'16mm² max',1.60),(9,'Bornier DIN 6P',6,'25mm² max',2.20),(10,'Bornier PCB 2P',2,'1mm² max',0.60),(11,'Bornier PCB 3P',3,'1.5mm² max',0.80),(12,'Bornier PCB 5P',5,'2.5mm² max',1.10),(13,'Bornier à levier 2P',2,'4mm² max',1.00),(14,'Bornier à levier 3P',3,'6mm² max',1.30),(15,'Bornier à levier 5P',5,'10mm² max',2.00);
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
  `nombre_conducteurs_max` int(11) NOT NULL,
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
INSERT INTO `catalogue_modele_cables` VALUES (1,'Câble unipolaire H07V-U 1.5mm²',1,0.50,'Unipolaire'),(2,'Câble unipolaire H07V-R 2.5mm²',1,0.75,'Unipolaire'),(3,'Câble unipolaire H07V-K 4.0mm²',1,1.20,'Unipolaire'),(4,'Câble multipaire 2x1.5mm²',2,1.00,'Multipaire'),(5,'Câble multipaire 3x2.5mm²',3,1.80,'Multipaire'),(6,'Câble multipaire 4x1.0mm²',4,2.10,'Multipaire'),(7,'Câble blindé CY 2x0.75mm²',2,1.50,'Blindé'),(8,'Câble blindé CY 4x1.5mm²',4,2.20,'Blindé'),(9,'Câble coaxial RG58',1,1.10,'Coaxial'),(10,'Câble coaxial RG6',1,1.40,'Coaxial'),(11,'Câble réseau UTP Cat5e',8,0.90,'Réseau'),(12,'Câble réseau STP Cat6',8,1.30,'Réseau'),(13,'Câble alimentation 3G2.5mm²',3,2.00,'Alimentation'),(14,'Câble alimentation 5G1.5mm²',5,2.50,'Alimentation'),(15,'Câble fibre optique SM 4FO',4,3.00,'Fibre');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_modele_connecteurs`
--

LOCK TABLES `catalogue_modele_connecteurs` WRITE;
/*!40000 ALTER TABLE `catalogue_modele_connecteurs` DISABLE KEYS */;
INSERT INTO `catalogue_modele_connecteurs` VALUES (1,'Connecteur DB9 Mâle',9,'Série',1.50),(2,'Connecteur DB9 Femelle',9,'Série',1.60),(3,'Connecteur DB25 Mâle',25,'Série',2.00),(4,'Connecteur DB25 Femelle',25,'Série',2.10),(5,'Connecteur RJ45 Mâle',8,'Réseau',0.80),(6,'Connecteur RJ45 Femelle',8,'Réseau',0.90),(7,'Connecteur USB-A Mâle',4,'USB',0.70),(8,'Connecteur USB-A Femelle',4,'USB',0.80),(9,'Connecteur Molex Mini-Fit 4P',4,'Molex',1.20),(10,'Connecteur Molex Mini-Fit 6P',6,'Molex',1.50),(11,'Connecteur JST PH 2P',2,'JST',0.50),(12,'Connecteur JST PH 4P',4,'JST',0.70),(13,'Connecteur XLR 3P Mâle',3,'Audio',2.00),(14,'Connecteur XLR 3P Femelle',3,'Audio',2.10),(15,'Connecteur HDMI',19,'Vidéo',2.50);
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
  `id_projet` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nombre_bornes` int(11) NOT NULL,
  `caracteristiques` varchar(255) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D1093D9B76222944` (`id_projet`),
  CONSTRAINT `FK_D1093D9B76222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_projet_borniers`
--

LOCK TABLES `catalogue_projet_borniers` WRITE;
/*!40000 ALTER TABLE `catalogue_projet_borniers` DISABLE KEYS */;
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
  `nombre_conducteurs_max` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_projet_nom` (`id_projet`,`nom`),
  KEY `IDX_FD1289CC76222944` (`id_projet`),
  CONSTRAINT `FK_FD1289CC76222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_projet_cables`
--

LOCK TABLES `catalogue_projet_cables` WRITE;
/*!40000 ALTER TABLE `catalogue_projet_cables` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogue_projet_connecteurs`
--

LOCK TABLES `catalogue_projet_connecteurs` WRITE;
/*!40000 ALTER TABLE `catalogue_projet_connecteurs` DISABLE KEYS */;
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
  CONSTRAINT `FK_23677143523B2018` FOREIGN KEY (`id_wire_signal`) REFERENCES `wire_signal` (`id`),
  CONSTRAINT `FK_236771439A92C5B6` FOREIGN KEY (`id_borne_source`) REFERENCES `borne` (`id`),
  CONSTRAINT `FK_23677143C944263C` FOREIGN KEY (`id_borne_destination`) REFERENCES `borne` (`id`),
  CONSTRAINT `FK_23677143CA6C85E4` FOREIGN KEY (`id_cable`) REFERENCES `cable` (`id`),
  CONSTRAINT `FK_23677143F27EF5F4` FOREIGN KEY (`id_contact_source`) REFERENCES `contact` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conducteur`
--

LOCK TABLES `conducteur` WRITE;
/*!40000 ALTER TABLE `conducteur` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `connecteur`
--

LOCK TABLES `connecteur` WRITE;
/*!40000 ALTER TABLE `connecteur` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipement`
--

LOCK TABLES `equipement` WRITE;
/*!40000 ALTER TABLE `equipement` DISABLE KEYS */;
/*!40000 ALTER TABLE `equipement` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projet`
--

LOCK TABLES `projet` WRITE;
/*!40000 ALTER TABLE `projet` DISABLE KEYS */;
INSERT INTO `projet` VALUES (1,'Test','2025-03-23 13:48:46','2025-03-23 13:48:46');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projet_utilisateur`
--

LOCK TABLES `projet_utilisateur` WRITE;
/*!40000 ALTER TABLE `projet_utilisateur` DISABLE KEYS */;
INSERT INTO `projet_utilisateur` VALUES (1,1,3,'proprietaire');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (3,'Marie Curie','marie.curie@example.com','$2y$13$qaMPeImeONSn.DCpWUWj4e5N1UWg/ccl3.zZdcwfAT1n2VnqZLGOO','[\"ROLE_ADMIN\"]'),(4,'Homer Simpson','h.simpson@example.com','$2y$13$r6qJrZ4z/hT2.NfI0/7PQ.Mwt9d4x4DIA9onOTiaFEdYHsmyJeLrO','[]'),(5,'Paul Martin','paul.martin@example.com','$2y$13$VblUEAtNukaQlkhspNR4pewn6aMeh/k0IH7ibwLOb8v3Taa.l4XUi','[\"ROLE_ADMIN\"]'),(6,'jean dupont','jean.dupont@example.com','$2y$13$Kdoz39gn0GtZsLcq1oMtyuXhRK2QoW5I2BUjmCiM/upnqVbUGjztO','[]');
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
  CONSTRAINT `FK_740C95F576222944` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wire_signal`
--

LOCK TABLES `wire_signal` WRITE;
/*!40000 ALTER TABLE `wire_signal` DISABLE KEYS */;
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

-- Dump completed on 2025-03-23 18:34:54
