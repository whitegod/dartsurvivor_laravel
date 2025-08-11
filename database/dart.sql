-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 3.128.168.188    Database: dart
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `first_name` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_name` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `address1` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `address2` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `zip` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `fullname` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,1,'active','Super','Admin','admin@dart.com','999-999-9999','7 Dark street','house 29','Indian','IN','54435','2025-05-12 12:46:09','2019-10-31 14:20:51',NULL,'Super Admin');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `author` (
  `id` int NOT NULL,
  `author_name` char(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `scope` varchar(500) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author`
--

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` VALUES (1,'Carol Hughes','5309 Aerosmith Rd.','',''),(2,'Damien Weyas	','9998 State Park St.','',''),(3,'Sweet Caroline','7 Ulysses S. Grant Rd.','',''),(4,'Damien Weyas	','7 Ulysses S. Grant Rd.','','');
/*!40000 ALTER TABLE `author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caseworker`
--

DROP TABLE IF EXISTS `caseworker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `caseworker` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caseworker`
--

LOCK TABLES `caseworker` WRITE;
/*!40000 ALTER TABLE `caseworker` DISABLE KEYS */;
INSERT INTO `caseworker` VALUES (4,'snow','white','1234214'),(5,'Cristiano','Ronaldo','383745675'),(6,'PPP','NNN','555-444-1212'),(7,'SSS','NNN','555-444-1212'),(8,'KKK','CCC','555-444-1212'),(9,'KKK','WWW','555-444-1212');
/*!40000 ALTER TABLE `caseworker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotel`
--

DROP TABLE IF EXISTS `hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` char(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `author` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel`
--

LOCK TABLES `hotel` WRITE;
/*!40000 ALTER TABLE `hotel` DISABLE KEYS */;
INSERT INTO `hotel` VALUES (5,'Baymont (Madisonville)','123 Main Street','555-555-1212','2025-07-24','2025-07-28','Dart Admin',NULL),(6,'Candlewood Suites (Paducah)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(7,'Comfort Suites (Hopkinsville)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(8,'Comfort Suites (Owensboro)','123 Main Street','555--555-1212','2025-07-24','2025-07-25','Dart Admin',NULL),(9,'Days Inn (Central City)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(10,'Holiday Inn (Henderson)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(11,'Morgantown Inn (Morgantown)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(12,'Roadway Inn (Owensboro)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(13,'Stratton Inn (Princeton)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(14,'Sleep Inn (Oak Grove)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(15,'Springhill Suites (Murray)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(16,'Wingate Inn (Bowling Green)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(17,'Best Western (Lawrenceburg)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(18,'Candlewood Suites (Lexington)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(19,'Capital Plaza (Frankfort)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(20,'Days Inn (Frankfort)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(21,'Economy Suites (Muldraugh)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(22,'Extended Stay (Louisville)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(23,'Greenfield Inn (Elizabethtown)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(24,'HI Y Inn (Owenton)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(25,'Microtel (Florence)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(26,'Motel 6 (Shepherdsville)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(27,'Quality Inn (Carrollton)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(28,'Quality Inn (Harrodsburg)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(29,'Red Roof Inn (Danville)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(30,'Red Roof Inn (Louisville)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(31,'American Elite Inn','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(32,'Baymont Inn ( London)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(33,'Comfort Inn (Somerset)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(34,'Days Inn (Madison)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(35,'Red Roof Inn (London)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(36,'Rodeway Inn (Harlan)','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL);
/*!40000 ALTER TABLE `hotel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lodge_unit`
--

DROP TABLE IF EXISTS `lodge_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lodge_unit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `unit_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `unit_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `survivor_id` int DEFAULT NULL,
  `statepark_id` int NOT NULL,
  `li_date` date DEFAULT NULL,
  `lo_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lodge_unit`
--

LOCK TABLES `lodge_unit` WRITE;
/*!40000 ALTER TABLE `lodge_unit` DISABLE KEYS */;
INSERT INTO `lodge_unit` VALUES (9,'cabin','301',NULL,4,NULL,NULL),(10,'cabin','302',NULL,4,NULL,NULL),(11,'cabin','304',NULL,4,NULL,NULL),(12,'cabin','305',NULL,4,NULL,NULL),(13,'cabin','306',NULL,4,NULL,NULL),(14,'cabin','307',NULL,4,NULL,NULL),(15,'cabin','308',NULL,4,NULL,NULL),(16,'cabin','401',NULL,4,NULL,NULL),(17,'cabin','406',NULL,4,NULL,NULL),(18,'cabin','407',NULL,4,NULL,NULL),(19,'cabin','303',NULL,4,NULL,NULL),(20,'cabin','402',NULL,4,NULL,NULL),(21,'lot','C108',NULL,4,NULL,NULL),(22,'lot','B32',6,4,NULL,NULL),(23,'lot','B33',NULL,4,NULL,NULL),(24,'lot','B34',NULL,4,NULL,NULL),(25,'lot','B35',NULL,4,NULL,NULL),(26,'lot','B36',NULL,4,NULL,NULL),(27,'lot','B37',8,4,NULL,NULL),(28,'lot','B38',NULL,4,NULL,NULL),(29,'lot','B39',9,4,NULL,NULL),(30,'lot','B40',10,4,NULL,NULL),(31,'lot','B41',NULL,4,NULL,NULL),(32,'lot','B42',11,4,NULL,NULL),(33,'lot','B43',12,4,'2025-04-19',NULL),(34,'lot','B44',13,4,NULL,NULL),(35,'lot','B45',14,4,NULL,NULL),(36,'lot','B45',NULL,4,NULL,NULL),(37,'lot','B46',15,4,NULL,NULL),(38,'lot','B47',NULL,4,NULL,NULL),(39,'lot','B48',NULL,4,NULL,NULL),(40,'lot','B49',NULL,4,NULL,NULL),(41,'lot','B50',NULL,4,NULL,NULL),(42,'lot','B51',NULL,4,NULL,NULL),(43,'lot','B52',NULL,4,NULL,NULL),(44,'lot','B53',NULL,4,NULL,NULL),(45,'lot','B54',NULL,4,NULL,NULL),(46,'lot','B55',NULL,4,NULL,NULL),(47,'lot','B56',NULL,4,NULL,NULL),(48,'lot','B57',NULL,4,NULL,NULL),(49,'lot','B58',NULL,4,NULL,NULL),(50,'lot','B59',NULL,4,NULL,NULL),(51,'lot','B60',NULL,4,NULL,NULL),(52,'lot','B61',NULL,4,NULL,NULL),(53,'lot','B62',NULL,4,NULL,NULL),(54,'lot','B63',NULL,4,NULL,NULL),(55,'lot','B64',NULL,4,NULL,NULL),(56,'lot','B65',NULL,4,NULL,NULL),(57,'lot','C66',NULL,4,NULL,NULL),(58,'lot','C67',NULL,4,NULL,NULL),(59,'lot','C68',NULL,4,NULL,NULL),(60,'lot','C69',NULL,4,NULL,NULL),(61,'lot','C70',NULL,4,NULL,NULL),(62,'lot','C72',NULL,4,NULL,NULL),(63,'lot','C74',NULL,4,NULL,NULL),(64,'lot','C73',NULL,4,NULL,NULL),(65,'lot','C75',NULL,4,NULL,NULL),(66,'lot','C76',NULL,4,NULL,NULL),(67,'lot','C77',NULL,4,NULL,NULL),(68,'lot','C78',NULL,4,NULL,NULL),(69,'lot','C79',NULL,4,NULL,NULL),(70,'lot','C80',NULL,4,NULL,NULL),(71,'lot','C81',NULL,4,NULL,NULL),(72,'lot','C83',NULL,4,NULL,NULL),(73,'lot','C82',NULL,4,NULL,NULL),(74,'lot','C84',NULL,4,NULL,NULL),(75,'lot','C85',NULL,4,NULL,NULL),(76,'lot','SV01',NULL,5,NULL,NULL),(77,'lot','SV02',NULL,5,NULL,NULL),(78,'lot','SV03',NULL,5,NULL,NULL),(79,'lot','SV04',NULL,5,NULL,NULL),(80,'lot','SV05',NULL,5,NULL,NULL),(81,'lot','SV06',NULL,5,NULL,NULL),(82,'lot','SV07',NULL,5,NULL,NULL),(83,'lot','SV08',NULL,5,NULL,NULL),(84,'lot','SV09',NULL,5,NULL,NULL),(85,'lot','SV10',NULL,5,NULL,NULL),(86,'lot','SV11',NULL,5,NULL,NULL),(87,'lot','SV12',NULL,5,NULL,NULL),(88,'lot','SV13',NULL,5,NULL,NULL),(89,'lot','SV14',NULL,5,NULL,NULL),(90,'lot','SV15',NULL,5,NULL,NULL),(91,'lot','SV16',NULL,5,NULL,NULL),(92,'lot','SV17',NULL,5,NULL,NULL),(93,'lot','SV18',NULL,5,NULL,NULL),(94,'lot','SV19',NULL,5,NULL,NULL),(95,'lot','SV20',NULL,5,NULL,NULL),(96,'lot','SV21',NULL,5,NULL,NULL),(97,'cabin','503',NULL,9,NULL,NULL),(98,'cabin','514',NULL,9,NULL,NULL),(99,'cabin','511',NULL,8,NULL,NULL),(100,'cabin','518',NULL,8,NULL,NULL),(101,'cabin','532',NULL,8,NULL,NULL),(102,'lot','89',NULL,8,NULL,NULL),(103,'lot','90',NULL,8,NULL,NULL),(104,'lot','91',NULL,8,NULL,NULL),(105,'lot','92',NULL,8,NULL,NULL),(106,'lot','93',NULL,8,NULL,NULL),(107,'lot','94',NULL,8,NULL,NULL),(108,'lot','95',NULL,8,NULL,NULL),(109,'lot','96',NULL,8,NULL,NULL),(110,'lot','97',NULL,8,NULL,NULL),(111,'lot','98',NULL,8,NULL,NULL),(112,'lot','99',NULL,8,NULL,NULL),(113,'lot','99',NULL,8,NULL,NULL),(114,'lot','100',NULL,8,NULL,NULL),(115,'lot','103',NULL,8,NULL,NULL),(116,'lot','105',NULL,8,NULL,NULL),(117,'lot','107',NULL,8,NULL,NULL),(118,'lot','108',NULL,8,NULL,NULL),(119,'lot','110',NULL,8,NULL,NULL),(120,'lot','A11',NULL,10,NULL,NULL),(121,'lot','A12',NULL,10,NULL,NULL),(122,'lot','C07',NULL,10,NULL,NULL),(123,'lot','A01',NULL,6,NULL,NULL),(124,'lot','A02',NULL,6,NULL,NULL),(125,'lot','A03',NULL,6,NULL,NULL),(126,'lot','A04',NULL,6,NULL,NULL),(127,'lot','A07',NULL,6,NULL,NULL),(128,'lot','A06',NULL,6,NULL,NULL),(129,'lot','A08',NULL,6,NULL,NULL),(130,'lot','A09',NULL,6,NULL,NULL),(131,'lot','A10',NULL,6,NULL,NULL),(132,'lot','A11',NULL,6,NULL,NULL),(133,'lot','A12',NULL,6,NULL,NULL),(134,'lot','A13',NULL,6,NULL,NULL),(135,'lot','A14',NULL,6,NULL,NULL),(136,'lot','A15',NULL,6,NULL,NULL),(137,'lot','A15',NULL,6,NULL,NULL),(138,'lot','A16',NULL,6,NULL,NULL),(139,'lot','A12',NULL,6,NULL,NULL),(140,'lot','A17',NULL,6,NULL,NULL),(141,'lot','A18',NULL,6,NULL,NULL),(142,'lot','A19',NULL,6,NULL,NULL),(143,'lot','A20',NULL,6,NULL,NULL),(144,'lot','A21',NULL,6,NULL,NULL),(145,'lot','A22',NULL,6,NULL,NULL),(146,'lot','B01',NULL,6,NULL,NULL),(147,'lot','B02',NULL,6,NULL,NULL),(148,'lot','B03',NULL,6,NULL,NULL),(149,'lot','B04',NULL,6,NULL,NULL),(150,'lot','B05',NULL,6,NULL,NULL),(151,'lot','B06',NULL,6,NULL,NULL),(152,'lot','B07',NULL,6,NULL,NULL),(153,'lot','B08',NULL,6,NULL,NULL),(154,'lot','B09',NULL,6,NULL,NULL),(155,'lot','B10',NULL,6,NULL,NULL),(156,'lot','B11',NULL,6,NULL,NULL),(157,'lot','B15',NULL,6,NULL,NULL),(158,'lot','B16',NULL,6,NULL,NULL),(159,'lot','B17',NULL,6,NULL,NULL),(160,'lot','B18',NULL,6,NULL,NULL),(161,'lot','B05',NULL,7,NULL,NULL),(162,'lot','B06',NULL,7,NULL,NULL),(163,'lot','B07',NULL,7,NULL,NULL),(164,'lot','B08',NULL,7,NULL,NULL),(165,'lot','B09',NULL,7,NULL,NULL),(166,'lot','B10',NULL,7,NULL,NULL),(167,'lot','C02',NULL,7,NULL,NULL),(168,'lot','C03',NULL,7,NULL,NULL),(169,'lot','C04',NULL,7,NULL,NULL),(170,'lot','C05',NULL,7,NULL,NULL),(171,'lot','A2',NULL,11,NULL,NULL),(172,'lot','A3',NULL,11,NULL,NULL),(173,'lot','A4',NULL,11,NULL,NULL),(174,'lot','A5',NULL,11,NULL,NULL),(175,'lot','A6',NULL,11,NULL,NULL),(176,'lot','A7',NULL,11,NULL,NULL),(177,'lot','A8',NULL,11,NULL,NULL),(178,'lot','A9',NULL,11,NULL,NULL),(179,'lot','A10',NULL,11,NULL,NULL),(180,'lot','A11',NULL,11,NULL,NULL),(181,'lot','A12',NULL,11,NULL,NULL),(182,'lot','A13',NULL,11,NULL,NULL),(183,'lot','A14',NULL,11,NULL,NULL),(184,'lot','A15',NULL,11,NULL,NULL),(185,'lot','A16',NULL,11,NULL,NULL),(186,'lot','A17',NULL,11,NULL,NULL),(187,'lot','A18',NULL,11,NULL,NULL),(188,'lot','B2',NULL,11,NULL,NULL),(189,'lot','B4',NULL,11,NULL,NULL),(190,'lot','B5',NULL,11,NULL,NULL),(191,'lot','B6',NULL,11,NULL,NULL),(192,'lot','B7',NULL,11,NULL,NULL),(193,'lot','B8',NULL,11,NULL,NULL),(194,'lot','B9',NULL,11,NULL,NULL),(195,'lot','B9',NULL,11,NULL,NULL),(196,'lot','B10',NULL,11,NULL,NULL),(197,'lot','B10',NULL,11,NULL,NULL),(198,'lot','B11',NULL,11,NULL,NULL),(199,'lot','B11',NULL,11,NULL,NULL),(200,'lot','B12',NULL,11,NULL,NULL),(201,'lot','B13',NULL,11,NULL,NULL),(202,'lot','B13A',NULL,11,NULL,NULL),(203,'lot','B14',NULL,11,NULL,NULL),(204,'lot','B15',NULL,11,NULL,NULL),(205,'cabin','133',NULL,12,NULL,NULL),(206,'lot','C88',NULL,4,NULL,NULL),(207,'lot','C89',NULL,4,NULL,NULL),(208,'lot','C90',NULL,4,NULL,NULL),(209,'lot','C100',NULL,4,NULL,NULL),(210,'lot','C101',NULL,4,NULL,NULL),(211,'lot','C102',NULL,4,NULL,NULL),(212,'lot','C103',NULL,4,NULL,NULL),(213,'lot','C104',NULL,4,NULL,NULL),(214,'lot','C105',NULL,4,NULL,NULL),(215,'lot','C107',NULL,4,NULL,NULL),(216,'lot','C108',NULL,4,NULL,NULL),(217,'lot','C109',NULL,4,NULL,NULL),(218,'lot','C110',NULL,4,NULL,NULL),(219,'lot','C111',NULL,4,NULL,NULL),(220,'lot','C112',NULL,4,NULL,NULL),(221,'lot','C113',NULL,4,NULL,NULL),(222,'lot','C114',NULL,4,NULL,NULL),(223,'lot','C115',NULL,4,NULL,NULL),(224,'lot','C116',NULL,4,NULL,NULL),(225,'lot','C117',NULL,4,NULL,NULL),(226,'lot','C118',NULL,4,NULL,NULL),(227,'lot','C119',NULL,4,NULL,NULL),(228,'lot','C120',NULL,4,NULL,NULL),(229,'lot','C121',NULL,4,NULL,NULL),(230,'lot','C65',NULL,4,NULL,NULL),(231,'lot','C71',NULL,4,NULL,NULL);
/*!40000 ALTER TABLE `lodge_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL,
  `migration` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_05_15_094256_update_ttus_table',1),(2,'2025_05_15_102431_create_survivors_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privatesite`
--

DROP TABLE IF EXISTS `privatesite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `privatesite` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` char(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ttu_id` int DEFAULT NULL,
  `damage_assessment` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ehp` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ehp_notes` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dow_lat` float DEFAULT NULL,
  `dow_long` float DEFAULT NULL,
  `dow_response` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kytc` tinyint(1) DEFAULT NULL,
  `pow` tinyint(1) DEFAULT NULL,
  `h2o` tinyint(1) DEFAULT NULL,
  `sew` tinyint(1) DEFAULT NULL,
  `own` tinyint(1) DEFAULT NULL,
  `res` tinyint(1) DEFAULT NULL,
  `zon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `author` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privatesite`
--

LOCK TABLES `privatesite` WRITE;
/*!40000 ALTER TABLE `privatesite` DISABLE KEYS */;
INSERT INTO `privatesite` VALUES (3,'Dave\'s ranch','12354 Federal Way','(777) 654-0101',1,NULL,NULL,'something interesting',NULL,NULL,NULL,NULL,1,0,1,0,0,NULL,'2025-07-02','2025-07-14',NULL);
/*!40000 ALTER TABLE `privatesite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int unsigned NOT NULL,
  `user_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1478 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (1,1,1,'2018-11-01 22:22:00','2018-11-01 22:23:00'),(1476,1,24,'2025-08-04 15:52:54','2025-08-04 15:52:54'),(1477,1,25,'2025-08-05 15:27:41','2025-08-05 15:27:41');
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'2018-11-01 09:30:00','2018-11-01 09:30:00','Admin','Admin'),(2,'2018-11-01 11:30:00','2018-11-01 11:30:00','User','User');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_num` char(50) COLLATE utf8mb4_general_ci NOT NULL,
  `survivor_id` int DEFAULT NULL,
  `hotel_id` int NOT NULL,
  `li_date` date DEFAULT NULL,
  `lo_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room`
--

LOCK TABLES `room` WRITE;
/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` VALUES (9,'102',NULL,5,NULL,NULL),(10,'123',NULL,5,NULL,NULL),(11,'205',NULL,5,NULL,NULL),(12,'132',NULL,6,NULL,NULL),(13,'311',NULL,6,NULL,NULL),(14,'317',NULL,6,NULL,NULL),(15,'320',NULL,6,NULL,NULL),(16,'323',NULL,6,NULL,NULL),(17,'409',NULL,6,NULL,NULL),(18,'125',NULL,6,NULL,NULL),(19,'422',NULL,6,NULL,NULL),(20,'320',NULL,7,NULL,NULL),(21,'322',NULL,7,NULL,NULL),(22,'121',NULL,8,NULL,NULL),(23,'208',1,8,NULL,NULL),(24,'223',NULL,8,NULL,NULL),(25,'306',NULL,8,NULL,NULL),(26,'324',NULL,8,NULL,NULL),(27,'120',NULL,9,NULL,NULL),(28,'152',NULL,9,NULL,NULL),(29,'213',NULL,9,NULL,NULL),(30,'209',NULL,9,NULL,NULL),(31,'101',NULL,10,NULL,NULL),(32,'107',NULL,10,NULL,NULL),(33,'211',NULL,10,NULL,NULL),(34,'215',NULL,10,NULL,NULL),(35,'217',NULL,10,NULL,NULL),(36,'217',NULL,10,NULL,NULL),(37,'216',NULL,10,NULL,NULL),(38,'219',NULL,10,NULL,NULL),(39,'221',NULL,10,NULL,NULL),(40,'223',NULL,10,NULL,NULL),(41,'224',NULL,10,NULL,NULL),(42,'225',NULL,10,NULL,NULL),(43,'226',NULL,10,NULL,NULL),(44,'216',NULL,11,NULL,NULL),(45,'102',NULL,12,NULL,NULL),(46,'142',NULL,12,NULL,NULL),(47,'110',NULL,13,NULL,NULL),(48,'214',NULL,14,NULL,NULL),(49,'306',NULL,14,NULL,NULL),(50,'309',NULL,14,NULL,NULL),(51,'320',NULL,14,NULL,NULL),(52,'218',NULL,15,NULL,NULL),(53,'124',NULL,16,NULL,NULL),(54,'405',NULL,16,NULL,NULL),(55,'418',NULL,16,NULL,NULL),(56,'418',NULL,16,NULL,NULL),(57,'419',NULL,16,NULL,NULL),(58,'420',NULL,16,NULL,NULL),(59,'104',NULL,17,NULL,NULL),(60,'110',NULL,17,NULL,NULL),(61,'130',NULL,17,NULL,NULL),(62,'230',NULL,17,NULL,NULL),(63,'116',NULL,18,NULL,NULL),(64,'210',NULL,18,NULL,NULL),(65,'330',NULL,18,NULL,NULL),(66,'300',NULL,19,NULL,NULL),(67,'301',NULL,19,NULL,NULL),(68,'302',NULL,19,NULL,NULL),(69,'304',NULL,19,NULL,NULL),(70,'306',NULL,19,NULL,NULL),(71,'307',NULL,19,NULL,NULL),(72,'308',NULL,19,NULL,NULL),(73,'310',NULL,19,NULL,NULL),(74,'312',NULL,19,NULL,NULL),(75,'311',NULL,19,NULL,NULL),(76,'314',NULL,19,NULL,NULL),(77,'314',NULL,19,NULL,NULL),(78,'318',NULL,19,NULL,NULL),(79,'324',NULL,19,NULL,NULL),(80,'326',NULL,19,NULL,NULL),(81,'327',NULL,19,NULL,NULL),(82,'328',NULL,19,NULL,NULL),(83,'330',NULL,19,NULL,NULL),(84,'405',NULL,19,NULL,NULL),(85,'518',NULL,19,NULL,NULL),(86,'520',NULL,19,NULL,NULL),(87,'530',NULL,19,NULL,NULL),(88,'703',NULL,19,NULL,NULL),(89,'717',NULL,19,NULL,NULL),(90,'149',NULL,20,NULL,NULL),(91,'259',NULL,20,NULL,NULL),(92,'14',NULL,21,NULL,NULL),(93,'26',NULL,21,NULL,NULL),(94,'27',NULL,21,NULL,NULL),(95,'28',NULL,21,NULL,NULL),(96,'29',NULL,21,NULL,NULL),(97,'232',NULL,22,NULL,NULL),(98,'120',NULL,23,NULL,NULL),(99,'121',NULL,23,NULL,NULL),(100,'122',NULL,23,NULL,NULL),(101,'123',NULL,23,NULL,NULL),(102,'124',NULL,23,NULL,NULL),(103,'126',NULL,23,NULL,NULL),(104,'126',NULL,23,NULL,NULL),(105,'133',NULL,23,NULL,NULL),(106,'148',NULL,23,NULL,NULL),(107,'136',NULL,23,NULL,NULL),(108,'150',NULL,23,NULL,NULL),(109,'149',NULL,23,NULL,NULL),(110,'278',NULL,23,NULL,NULL),(111,'279',NULL,23,NULL,NULL),(112,'0',NULL,24,NULL,NULL),(113,'3',NULL,24,NULL,NULL),(114,'6',NULL,24,NULL,NULL),(115,'8',NULL,24,NULL,NULL),(116,'124',NULL,25,NULL,NULL),(117,'104',NULL,26,NULL,NULL),(118,'135',NULL,27,NULL,NULL),(119,'137',NULL,27,NULL,NULL),(120,'111',NULL,28,NULL,NULL),(121,'202',NULL,28,NULL,NULL),(122,'203',NULL,28,NULL,NULL),(123,'115',NULL,29,NULL,NULL),(124,'137',NULL,30,NULL,NULL),(125,'105',NULL,31,NULL,NULL),(126,'128',NULL,31,NULL,NULL),(127,'141',NULL,31,NULL,NULL),(128,'147',NULL,31,NULL,NULL),(129,'148',NULL,31,NULL,NULL),(130,'119',NULL,32,NULL,NULL),(131,'204',NULL,33,NULL,NULL),(132,'215',NULL,33,NULL,NULL),(133,'322',NULL,33,NULL,NULL),(134,'130',NULL,34,NULL,NULL),(135,'107',NULL,35,NULL,NULL),(136,'122',NULL,35,NULL,NULL),(137,'127',NULL,35,NULL,NULL),(138,'131',NULL,35,NULL,NULL),(139,'132',NULL,35,NULL,NULL),(140,'23',NULL,36,NULL,NULL),(141,'24',NULL,36,NULL,NULL);
/*!40000 ALTER TABLE `room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statepark`
--

DROP TABLE IF EXISTS `statepark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statepark` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `author` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statepark`
--

LOCK TABLES `statepark` WRITE;
/*!40000 ALTER TABLE `statepark` DISABLE KEYS */;
INSERT INTO `statepark` VALUES (4,'Jenny Wiley State Park','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(5,'Shelby Valley','124 Main Street','555-444-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(6,'Perry Park','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(7,'Crocketsville','9508 State Hwy 315, Booneville, KY 4131','555-555-1212','2025-07-24','2025-08-01','Dart Admin',NULL),(8,'General Butler State Park','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(9,'Pennyrile State Park','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(10,'Mine Made Park','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(11,'Levi Jackson Park','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL),(12,'Natural Bridge State Park','123 Main Street','555-555-1212','2025-07-24','2025-07-24','Dart Admin',NULL);
/*!40000 ALTER TABLE `statepark` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survivor`
--

DROP TABLE IF EXISTS `survivor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `survivor` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fema_id` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fname` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lname` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` char(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` char(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zip` char(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `county` char(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `primary_phone` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `secondary_phone` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hh_size` int DEFAULT NULL,
  `group0_2` int DEFAULT NULL,
  `group3_6` int DEFAULT NULL,
  `group7_12` int DEFAULT NULL,
  `group13_17` int DEFAULT NULL,
  `group18_21` int DEFAULT NULL,
  `group22_65` int DEFAULT NULL,
  `group65plus` int DEFAULT NULL,
  `pets` int DEFAULT NULL,
  `email` char(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tpm` date DEFAULT NULL,
  `li_date` date DEFAULT NULL,
  `own_rent` tinyint(1) DEFAULT NULL,
  `author` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `opt_out` tinyint DEFAULT NULL,
  `opt_out_reason` char(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `caseworker_id` int DEFAULT NULL,
  `notes` varchar(3000) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survivor`
--

LOCK TABLES `survivor` WRITE;
/*!40000 ALTER TABLE `survivor` DISABLE KEYS */;
INSERT INTO `survivor` VALUES (1,'364783292634','test','dfsd','att',NULL,NULL,NULL,NULL,'(890) 903-5768','(890) 903-2344',4,2,NULL,NULL,NULL,2,NULL,NULL,2,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\"]',0,'N/A',NULL,NULL,'2025-05-26 15:29:38','2025-08-08 12:27:31'),(2,'343256765','dd','fdge','address 2',NULL,'CA','12345',NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,2,NULL,2,NULL,'2025-05-12',NULL,0,'Dart Admin','TTU',0,'N/A',NULL,NULL,'2025-05-26 15:43:18','2025-07-28 10:56:24'),(3,'343256243','adsa','fewfw',NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,NULL,NULL,3,NULL,NULL,1,1,1,NULL,'2025-06-25',NULL,0,'Dart Admin','TTU',0,'Personal',NULL,NULL,'2025-05-26 15:43:36','2025-07-28 10:55:31'),(4,'Mdj304827','victor','tester','add4',NULL,NULL,NULL,NULL,'(890) 903-1212',NULL,6,1,NULL,NULL,1,2,NULL,2,2,NULL,'2025-06-26',NULL,0,'Dart Admin','Hotel',0,'N/A',NULL,NULL,'2025-06-17 19:25:40','2025-07-11 17:00:36'),(5,'616543927','TTT','MMM',NULL,NULL,NULL,NULL,'Pike','555-555-1212','555-555-1212',2,NULL,NULL,1,NULL,NULL,1,NULL,2,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',6,'JWSP B32','2025-07-28 08:00:58','2025-08-10 12:56:52'),(6,'638820799','MMM','TTTT',NULL,NULL,NULL,NULL,'Floyd','555-555-1212','555-555-1212',2,NULL,NULL,NULL,NULL,NULL,2,NULL,0,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',6,'B35/36\r\nJWSP','2025-07-28 08:57:56','2025-08-08 12:30:51'),(7,'465678','Dave','Fakename','1234 Huckleberry Dr.','Frankfurt','TX','19191','Burr','2138675309',NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,0,'yy@tt.net',NULL,NULL,0,'Dart Admin','TTU',0,'N/A',6,NULL,'2025-07-28 11:05:49','2025-07-28 11:06:35'),(8,'638587862','LLL','JJJJ',NULL,NULL,NULL,NULL,'Floyd','555-555-1212',NULL,2,NULL,NULL,NULL,NULL,NULL,2,NULL,1,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',NULL,NULL,'2025-08-10 20:02:14','2025-08-10 21:39:48'),(9,'638587617','JJJ','AAA',NULL,NULL,NULL,NULL,'Floyd','555-555-1212',NULL,4,NULL,2,NULL,NULL,NULL,2,NULL,2,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',NULL,NULL,'2025-08-10 20:06:09','2025-08-10 21:40:06'),(10,'620549568','BBB','CCC',NULL,NULL,NULL,NULL,'Pike','555-555-1212',NULL,5,NULL,1,2,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',NULL,NULL,'2025-08-10 21:29:08','2025-08-10 21:40:22'),(11,'638544498','KKK','LLL',NULL,NULL,NULL,NULL,'Pike','555-555-1212',NULL,6,1,1,2,NULL,NULL,2,NULL,2,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',NULL,NULL,'2025-08-10 21:31:59','2025-08-10 21:40:42'),(12,'638585543','BBB','RRR',NULL,NULL,NULL,NULL,'Pike','555-555-1212',NULL,1,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',NULL,NULL,'2025-08-10 21:35:01','2025-08-10 21:41:02'),(13,'638617829','PPP','CCC',NULL,NULL,NULL,NULL,'Floyd','555-555-1212',NULL,7,NULL,NULL,2,1,NULL,4,NULL,NULL,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',NULL,NULL,'2025-08-10 21:37:42','2025-08-10 21:41:19'),(14,'638543504','SSS','YYY',NULL,NULL,NULL,NULL,'Pike','555-555-1212',NULL,3,NULL,NULL,NULL,NULL,NULL,3,NULL,2,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',NULL,NULL,'2025-08-10 21:42:39','2025-08-10 21:43:26'),(15,'638592009','LLL','CCC',NULL,NULL,NULL,NULL,NULL,'555-555-1212',NULL,2,NULL,NULL,NULL,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,0,'Dart Admin','[\"TTU\",\"State Park\"]',0,'N/A',NULL,NULL,'2025-08-10 21:45:55','2025-08-10 21:46:32');
/*!40000 ALTER TABLE `survivor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transfer`
--

DROP TABLE IF EXISTS `transfer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transfer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ttu_id` int DEFAULT NULL,
  `donated` tinyint NOT NULL,
  `recipient_type` tinyint NOT NULL,
  `donation_agency` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `donation_category` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `auction` tinyint NOT NULL,
  `sold_at_auction_price` float DEFAULT NULL,
  `recipient` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transfer`
--

LOCK TABLES `transfer` WRITE;
/*!40000 ALTER TABLE `transfer` DISABLE KEYS */;
INSERT INTO `transfer` VALUES (1,2,1,0,'asd','cat234',0,NULL,NULL);
/*!40000 ALTER TABLE `transfer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ttu`
--

DROP TABLE IF EXISTS `ttu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ttu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `survivor_id` int DEFAULT NULL,
  `vin` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location_type` char(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `location` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manufacturer` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `brand` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `model` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `year` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title_manufacturer` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title_brand` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title_model` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `has_title` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit_loc` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `county` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `imei` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `li_date` date DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `status` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_beds` int DEFAULT NULL,
  `disposition` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `transpo_agency` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_general_ci,
  `comments` text COLLATE utf8mb4_general_ci,
  `fdec` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lo` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `lo_date` date DEFAULT NULL,
  `est_lo_date` date DEFAULT NULL,
  `has_200sqft` tinyint(1) DEFAULT '0',
  `has_propanefire` tinyint(1) DEFAULT '0',
  `has_tv` tinyint(1) DEFAULT '0',
  `has_hydraul` tinyint(1) DEFAULT '0',
  `has_steps` tinyint(1) DEFAULT '0',
  `has_teardrop` tinyint(1) DEFAULT '0',
  `has_foldwalls` tinyint(1) DEFAULT '0',
  `has_extkitchen` tinyint(1) DEFAULT '0',
  `is_onsite` tinyint(1) DEFAULT '0',
  `is_occupied` tinyint(1) DEFAULT '0',
  `is_winterized` tinyint(1) DEFAULT '0',
  `is_deblocked` tinyint(1) DEFAULT '0',
  `is_cleaned` tinyint(1) DEFAULT '0',
  `is_gps_removed` tinyint(1) DEFAULT '0',
  `is_being_donated` tinyint(1) DEFAULT '0',
  `is_sold_at_auction` tinyint(1) DEFAULT '0',
  `author` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_num` char(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `purchase_origin` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ttu`
--

LOCK TABLES `ttu` WRITE;
/*!40000 ALTER TABLE `ttu` DISABLE KEYS */;
INSERT INTO `ttu` VALUES (1,NULL,'S0VM9145645','statepark',NULL,'Forest River','Apex Ultra Lite','D23','2023',NULL,NULL,NULL,'Yes',NULL,'Brexit County',NULL,'2024-02-28',NULL,NULL,3,'Available',NULL,NULL,NULL,NULL,'NO',NULL,NULL,0,0,0,1,1,1,0,0,0,0,1,0,1,1,0,0,'Damien Weyas	','2025-05-12 16:19:11','2025-08-01 09:41:18','A11',2),(2,NULL,'3SD2CXSBXSM046291','hotel','king\'s hotel','Forest River',NULL,NULL,NULL,NULL,NULL,NULL,'Yes',NULL,NULL,NULL,NULL,NULL,NULL,4,'Awaiting Signatures','fedex',NULL,NULL,NULL,'',NULL,NULL,0,0,1,0,1,0,0,0,0,0,0,0,1,0,1,0,'Damien Weyas	','2025-05-27 15:35:51','2025-08-01 11:42:36',NULL,NULL),(3,3,'3SD2CXBNMSM043391','hotel','queen\'s hotel','Forest River',NULL,NULL,NULL,NULL,NULL,NULL,'Yes','13',NULL,NULL,'2025-06-18',NULL,'Transferred to Auction (#007bff)',3,'Available',NULL,NULL,NULL,NULL,'1','2025-07-12','2025-06-14',0,0,1,1,0,0,0,0,0,0,0,0,1,0,0,0,'Damien Weyas	','2025-06-01 16:19:32','2025-07-12 18:44:04',NULL,NULL),(4,3,'MSV2937294','statepark','statepark1',NULL,NULL,NULL,'2023',NULL,NULL,NULL,'Yes','123',NULL,NULL,NULL,NULL,'Transferred to City/County/State Entity (#800080)',5,NULL,NULL,'A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs. This is because paragraphs show a reader where the subdivisions of an essay begin and end, and thus help the reader see the organization of the essay and grasp its main points.\r\n\r\nParagraphs can contain many different kinds of information. A paragraph could contain a series of brief examples or a single long illustration of a general point. It might describe a place, character, or process; narrate a series of events; compare or contrast two or more things; classify items into categories; or describe causes and effects. Regardless of the kind of information they contain, all paragraphs share certain characteristics. One of the most important of these is a topic sentence.','A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs. This is because paragraphs show a reader where the subdivisions of an essay begin and end, and thus help the reader see the organization of the essay and grasp its main points.\r\n\r\nParagraphs can contain many different kinds of information. A paragraph could contain a series of brief examples or a single long illustration of a general point. It might describe a place, character, or process; narrate a series of events; compare or contrast two or more things; classify items into categories; or describe causes and effects. Regardless of the kind of information they contain, all paragraphs share certain characteristics. One of the most important of these is a topic sentence.',NULL,'NO',NULL,NULL,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'Damien Weyas	','2025-06-25 16:19:44','2025-08-03 06:57:55',NULL,NULL),(5,6,'5SFEB3026NE512288','statepark','Jenny Wiley State Park','Heartland','Trail Runner','261JM','2022',NULL,NULL,NULL,'No',NULL,'Floyd','015910002797622','2025-03-27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-24 23:31:32','2025-08-08 12:30:51','B32',NULL),(6,NULL,'5SFEB3024NE510410','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002834532',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 19:57:29','2025-07-29 08:53:14','B33',NULL),(7,NULL,'5SFEB3020NE514022','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002834516',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 19:59:38','2025-07-29 09:15:48','B34',NULL),(8,NULL,'5SFEB3021NE513994','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002809807',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:01:17','2025-07-29 09:16:02','B35',NULL),(9,NULL,'5SFEB3024NE512287','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002806076',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:02:39','2025-07-29 09:16:15','B36',NULL),(10,8,'5ZT2AVNB3PB936236','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,NULL,'015910000353733','2025-03-13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:08:11','2025-08-10 21:39:48','B37',NULL),(11,2,'573TT302XN8819848','statepark','Jenny Wiley State Park','Grand Design','Transcend',NULL,'2022',NULL,NULL,NULL,'No',NULL,'Floyd','015910002816794',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:17:03','2025-07-29 09:16:47','B38',NULL),(12,9,'4EZTU2520N5121224','statepark','Jenny Wiley State Park','KZRV','Sportsmen',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002256470','2025-03-13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:18:13','2025-08-10 21:40:06','B39',NULL),(13,10,'4X4TSMC28N7427883','statepark','Jenny Wiley State Park','Forest River','Salem',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910000321805','2025-06-19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-07-25 20:20:34','2025-08-10 21:40:22','B40',NULL),(14,NULL,'5ZT2AVNBXPB936265','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,NULL,'015910000347099',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:21:17','2025-07-29 09:17:35','B41',NULL),(15,11,'4YDTKDN29NJ971120','statepark','Jenny Wiley State Park','Kodiak',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002848367','2025-06-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:22:53','2025-08-10 21:40:42','B42',NULL),(16,12,'5ZT2CKRB6NF003598','statepark','Jenny Wiley State Park','Forest River','Cherokee',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002797697',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:24:06','2025-08-10 21:41:02','B43',NULL),(17,NULL,'1UJBJOBT5NIT90642','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910000359623',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-07-25 20:25:04','2025-08-04 09:53:37','B44',NULL),(18,NULL,'5SFEB3024NE507428','statepark','Levi Jackson Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002764359',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:26:50','2025-07-29 09:15:27','A2',NULL),(19,7,'5SFEB3023NE508845','statepark','Levi Jackson Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002796210',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:28:34','2025-07-29 09:15:12','A3',NULL),(20,NULL,'5SFEB3028NE512289','statepark','Levi Jackson Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002802190',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-25 20:29:20','2025-07-29 09:14:58','A4',NULL),(21,NULL,'5SFEB3024PE517962','statepark','Levi Jackson Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002801663',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:19:54','2025-07-29 09:14:36','A5',NULL),(22,NULL,'5SFEB3025PE517937','statepark','Levi Jackson Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910000334212',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:20:51','2025-07-29 09:12:31','A6',NULL),(23,NULL,'1NL1G3020S1176170','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002311101',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:22:03','2025-07-29 09:11:20','A7',NULL),(24,NULL,'1NL1G2723S6021373','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002796228',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:23:03','2025-07-29 09:10:04','A8',NULL),(25,NULL,'1NL1G3024S1176169','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002805268',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:24:04','2025-07-29 09:09:24','A9',NULL),(26,NULL,'1NL1G2724S6021365','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002796327',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:25:10','2025-07-29 09:08:16','A10',NULL),(27,NULL,'1NL1G2720S6021363','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002300856',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:26:11','2025-07-29 09:05:45','A11',NULL),(28,NULL,'1NL1G2729S6021362','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002796475',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:27:09','2025-07-29 09:03:15','A12',NULL),(29,NULL,'1NL1G272XS6021368','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002764432',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:27:57','2025-07-29 09:02:39','A13',NULL),(30,NULL,'1NL1G2726S6021366','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002834581',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:28:49','2025-07-29 09:01:15','A14',NULL),(31,NULL,'1NL1G2721S6021372','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002734674',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:29:44','2025-07-29 09:00:46','A15',NULL),(32,NULL,'1NL1G2727T6022768','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2026',NULL,NULL,NULL,'No',NULL,'Laurel','015910002833690',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:30:25','2025-07-29 09:00:01','A16',NULL),(33,NULL,'1NL1G2728S6021370','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002835646',NULL,NULL,NULL,NULL,NULL,NULL,'KAM purchase DO 095-2500033136',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:31:32','2025-07-29 08:59:23','A17',NULL),(34,NULL,'1NL1G3022S1176168','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002802695',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-26 16:32:53','2025-07-29 08:58:04','A18',NULL),(35,14,'5ZT2AVSB5PB936100','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000323629',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-07-27 07:37:17','2025-08-10 21:43:26','B45',NULL),(36,NULL,'5ZT2AVSB0PB936103','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000343643',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-07-27 07:38:43','2025-08-04 09:52:41','B46',NULL),(37,NULL,'5SFEB3021PE517952','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000338270',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-27 07:39:31','2025-07-29 08:55:24','B47',NULL),(38,NULL,'5SFEB3029PE517939','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000323694',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-27 07:39:55','2025-07-29 08:54:57','B48',NULL),(39,NULL,'5SFEB3024NE514024','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000362072',NULL,NULL,NULL,NULL,NULL,NULL,'B49',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-27 07:42:41','2025-07-29 08:01:17','B49',NULL),(40,NULL,'1UJBCOBN9P17V0266','statepark','Jenny Wiley State Park','Jayco','Jay Flight','264BH','2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000320641',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 07:57:26','2025-07-29 08:53:37','B50',NULL),(41,NULL,'5ZT2AVRB4NB934664','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000354137',NULL,NULL,NULL,NULL,NULL,NULL,'B51',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 08:04:06','2025-07-29 08:06:17','B51',NULL),(42,NULL,'5ZT2SSRB3PE020838','statepark','Jenny Wiley State Park','Forest River','Shasta Oasis',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002797838',NULL,NULL,NULL,NULL,NULL,NULL,'B52',NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 08:08:30','2025-07-29 08:11:17','B52',NULL),(43,NULL,'5ZT2SSRB9PE020830','statepark','Jenny Wiley State Park','Forest River','Shasta Oasis',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002768672',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:21:09','2025-07-29 09:21:39','B53',NULL),(44,NULL,'5ZT2AVNB6PB936229','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000319791',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:23:09','2025-07-29 09:23:48','B54',NULL),(45,NULL,'5ZT2CWRB0NJ131730','statepark','Jenny Wiley State Park','Forest River','Clipper',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:25:30','2025-07-29 09:25:49','B55',NULL),(46,NULL,'5ZT2AVTB1PB935430','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000322167',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:28:22','2025-07-29 09:28:52','B56',NULL),(47,NULL,'5SFEB3025NE513965','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002809898',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-07-29 09:31:31','2025-07-29 09:33:14','B58',NULL),(48,NULL,'5ZT2WDFC4PG203747','statepark','Jenny Wiley State Park','Forest River','Wildwood',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000319403',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:35:13','2025-07-29 09:35:41','B59',NULL),(49,NULL,'5ZT2AVNB8PB936264','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000345259',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:37:34','2025-07-29 09:38:23','B60',NULL),(50,NULL,'5SFEB3025PE517954','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002807249',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:40:24','2025-07-29 09:40:59','B61',NULL),(51,NULL,'5SFEB3027NE513966','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002848268',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:42:31','2025-07-29 09:42:56','B62',NULL),(52,NULL,'5SFEB302XNE514013','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002311762',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:44:18','2025-07-29 09:44:57','B63',NULL),(53,NULL,'5SFEB3020PE517957','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002809708',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:47:01','2025-07-29 09:47:23','B64',NULL),(54,NULL,'1NL1G3022S1176140','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002806092',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:52:30','2025-07-29 09:53:09','B2',NULL),(55,NULL,'1NL1G3022S1176171','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002738279',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-07-29 09:54:19','2025-08-01 14:25:58','B4',NULL),(56,NULL,'1NL1G272SX6021371','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002796368',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:56:33','2025-07-29 09:57:20','B5',NULL),(57,NULL,'1NL1G2722S6021364','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002802422',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 09:58:54','2025-07-29 09:59:14','B6',NULL),(58,NULL,'1NL1G3029S1176166','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002833914',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 10:03:50','2025-07-29 10:07:09','B7',NULL),(59,NULL,'1NL1G3024S1176155','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002765355',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 10:23:58','2025-07-29 10:24:49','B8',3),(60,NULL,'1NL1G3027S1176165','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002765355',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 10:28:24','2025-07-29 10:30:21','B9',3),(61,NULL,'1NL1G2721S6021369','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002796293',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 10:31:36','2025-08-02 16:48:54','B10',3),(62,NULL,'1NL1G2725S6021374','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Laurel','015910002802430',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-07-29 10:33:19','2025-07-29 10:50:15','B11',NULL),(63,NULL,'1NL1G3025S1176164','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Laurel','015910002738667',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:24:33','2025-08-01 09:26:32','B12',NULL),(64,NULL,'1NL1G3020S1176167','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002848961',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:27:37','2025-08-01 09:28:03','B13',NULL),(65,NULL,'1NL1G3024S1176172','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002321407',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:28:51','2025-08-01 09:29:12','B13A',NULL),(66,NULL,'1NL1G3026S1176173','statepark','Levi Jackson Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,NULL,'015910002256546',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:30:09','2025-08-01 09:30:33','B14',NULL),(67,NULL,'4EZTU2829N5071032','statepark','Perry Park','KZRV','Sportsmen',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000321433',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:36:51','2025-08-01 10:03:30','A01',5),(68,NULL,'5RXPB33XN1490386','statepark','Perry Park',NULL,'Land Roamer',NULL,'2021',NULL,NULL,NULL,'Yes',NULL,NULL,'015910000338635',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:38:27','2025-08-01 14:32:41','A03',5),(69,NULL,'5RXPB3622M1476220','statepark','Perry Park','Twilight','Signature',NULL,'2021',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000368376',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:40:03','2025-08-01 14:33:11','A04',5),(70,NULL,'5SFEB302XNE508843','statepark','Perry Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000368210',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:42:23','2025-08-01 09:55:02','A06',4),(71,NULL,'5SFEB3022NE512272','statepark','Perry Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000340672',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:51:11','2025-08-01 10:02:32','A07',4),(72,NULL,'1UJBJ0BN6N17V0549','statepark','Perry Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000322266',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:52:54','2025-08-01 14:32:17','A08',5),(73,NULL,'52T2CKRB1NF001516','statepark','Perry Park','CAM','Superline',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000333933',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 09:59:37','2025-08-01 10:02:02','A09',5),(74,NULL,'4EZTU252XN5010583','statepark','Perry Park','KZRV','Sportsmen',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,NULL,'015910000338031',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 10:21:53','2025-08-01 10:22:15','A10',5),(75,NULL,'1UJBJ0BPXN1JJ0163','statepark','Perry Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000325962',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 10:23:50','2025-08-01 10:24:10','A11',5),(76,NULL,'1UJBJ0BN8N17V1878','statepark','Perry Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000345937',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 10:25:14','2025-08-01 10:25:39','A12',5),(77,NULL,'1UJBJ0BNXN17V1994','statepark','Perry Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000342678',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 10:44:17','2025-08-01 10:44:40','A13',5),(78,NULL,'1NL1G2720N6009590','statepark','Perry Park','Gulfstream','Ameri-Lite',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000342876',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 10:55:39','2025-08-01 10:56:05','A14',5),(79,NULL,'4X4TCKB22MX153873','statepark','Perry Park','Forest River','Cherokee',NULL,'2021',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000357569',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 10:56:57','2025-08-01 10:57:12','A15',5),(80,NULL,'5ZT2ARXB3NX025070','statepark','Perry Park','Forest River','Aurora',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000323645',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 10:58:18','2025-08-01 10:58:41','A17',5),(81,NULL,'5ZT2DEPB5N9008677','statepark','Perry Park','Forest River','Della Terra',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000367261',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 10:59:56','2025-08-01 11:00:12','A18',5),(82,NULL,'5ZT2AVRB8NB931637','statepark','Perry Park','Forest River','Avenger',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000322803',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 11:02:19','2025-08-01 11:02:41','A19',5),(83,NULL,'4YDTSGL27N3101908','statepark','Perry Park','Springdale',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000332547',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 11:03:52','2025-08-01 11:04:06','A20',5),(84,NULL,'5ZT2ARXB5NX023823','statepark','Perry Park','Forest River','Aurora',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000344245',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 11:05:07','2025-08-01 11:05:30','A21',5),(85,NULL,'4YDT1922XH7207041','statepark','Perry Park','Keystone',NULL,NULL,'2017',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000343262',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 11:06:43','2025-08-01 11:07:07','A22',5),(86,NULL,'5ZT2AVNB3PB936298','statepark','Perry Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Perry',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 11:21:35','2025-08-01 11:21:51','B01',6),(87,NULL,'5SFEB3023NE512281','statepark','Perry Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000363963',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 11:26:38','2025-08-01 11:27:02','B02',4),(88,NULL,'1SABU0BNP1BF5165','statepark','Perry Park',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Perry','015910000319197',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 13:55:20','2025-08-01 13:57:13','B03',6),(89,NULL,'5ZT2SSRBXPE020917','statepark','Perry Park','Forest River','Shasta Oasis',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000355035',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 13:56:31','2025-08-01 13:56:58','B04',6),(90,NULL,'1UJBJ0BNXN17V1798','statepark','Perry Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000321706',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 13:58:20','2025-08-01 13:58:36','B05',6),(91,NULL,'58TBH0BN2N15F3168','statepark','Perry Park','Highland Ridge',NULL,NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Perry','015910000346943',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 13:59:38','2025-08-01 13:59:56','B06',6),(92,NULL,'58TBH0BR7N3EK3059','statepark','Perry Park','Highland Ridge',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000338890',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 14:01:07','2025-08-01 14:01:22','B07',6),(93,NULL,'5ZT2WDRB9PG203908','statepark','Perry Park','Forest River','Wildwood',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000323199',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 14:02:16','2025-08-01 14:02:47','B08',6),(94,NULL,'5ZT2AVNB9PB936189','statepark','Perry Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000345648',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:04:07','2025-08-01 14:04:24','B09',6),(95,NULL,'5ZT2AVNB8PB936250','statepark','Perry Park','Forest River','Avenger',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Perry','015910000319346',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 14:05:01','2025-08-01 14:05:16','B10',6),(96,NULL,'5ZT2TRMB7PB524016','statepark','Perry Park','Forest River','Avenger',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Perry','015910000353543',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:06:26','2025-08-01 14:06:42','B11',6),(97,NULL,'5SFEB3029NE513967','statepark','Perry Park','Heartland','Trail Runner',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Perry','015910000323330',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 14:08:05','2025-08-01 14:08:32','B16',4),(98,NULL,'5SFEB3028NE501082','statepark','Perry Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000321656',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:09:23','2025-08-01 14:09:43','B17',4),(99,NULL,'5SFEB3021NE508830','statepark','Perry Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Perry','015910000323744',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 14:10:33','2025-08-01 14:10:49','B17',4),(100,NULL,'58TB40BP6N14L3617','statepark','Crocketsville','Olympia',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002768680',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:14:28','2025-08-01 14:14:47','B05',7),(101,NULL,'58TB40BP2N14L3727','statepark','Crocketsville','Olympia',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002852161',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:17:24','2025-08-01 14:17:40','B06',7),(102,NULL,'58TB40BP6N14L3732','statepark','Crocketsville','Olympia',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002835653',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 14:19:18','2025-08-01 14:19:41','B07',7),(103,NULL,'58TB40BP3N14L3736','statepark','Crocketsville','Olympia',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002802653',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 14:21:15','2025-08-01 14:21:35','B08',7),(104,NULL,'5SFEB3021PE518499','statepark','Crocketsville','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002801010',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:22:07','2025-08-01 14:31:00','B09',7),(105,NULL,'5SFEB3023NE513978','statepark','Crocketsville','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002796871',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:23:45','2025-08-01 14:24:01','B10',7),(106,NULL,'5SFEB3024NE514007','statepark','Crocketsville','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002802810',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:24:51','2025-08-01 14:25:07','C02',7),(107,NULL,'5SFEB3025NE501069','statepark','Crocketsville','Heartland','Trail Runner',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002796087',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:27:32','2025-08-01 14:28:02','C03',7),(108,NULL,'1UJBJ0BRXN17S0523','statepark','Crocketsville','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002810482',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:29:00','2025-08-01 14:29:17','C04',7),(109,NULL,'5SFEB3027NE501073','statepark','Crocketsville','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Owsley','015910002802612',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:30:08','2025-08-01 14:30:29','C05',7),(110,NULL,'1NL1G3025N1151298','statepark','Shelby Valley','Gulfstream',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002806571',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 14:42:58','2025-08-01 14:43:18','SV01',8),(111,NULL,'1UJBJ0BS9N14A0785','statepark','Shelby Valley','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002797663',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 14:44:30','2025-08-01 14:45:05','SV02',8),(112,NULL,'573TT3027N8819645','statepark','Shelby Valley','Grand Design','Transcend',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002801291',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 15:25:01','2025-08-01 15:25:24','SV03',8),(113,NULL,'4X4TCKC22MK076840','statepark','Shelby Valley','Forest River','Cherokee',NULL,'2021',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002764333',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 15:30:22','2025-08-01 15:31:25','SV04',8),(114,NULL,'4YDTPPM21NK411719','statepark','Shelby Valley','Passport',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002765751',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 15:47:47','2025-08-01 15:48:06','SV05',8),(115,NULL,'54CTM1V22M6062004','statepark','Shelby Valley','Winnebago','Minnie',NULL,'2021',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002797952',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-01 15:49:20','2025-08-01 15:49:57','SV06',8),(116,NULL,'4x4TCKD27NK079054','statepark','Shelby Valley','Forest River','Cherokee',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','0159100028002174',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 15:57:11','2025-08-01 15:57:33','SV07',8),(117,NULL,'5ZT2TRNB7NB520821','statepark','Shelby Valley','Forest River','Tracer',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002802794',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 15:58:24','2025-08-01 15:58:58','SV08',8),(118,NULL,'5ZT2CKRB7NF001200','statepark','Shelby Valley','Forest River','Cherokee',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002849100',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-01 17:16:24','2025-08-01 18:25:00','SV09',8),(119,NULL,'5ZT2DEPB6M9007407','statepark','Shelby Valley','Forest River','Della Terra',NULL,'2021',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002463332',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 11:26:32','2025-08-02 11:28:13','SV10',8),(120,NULL,'1NL1G2725N6009360','statepark','Shelby Valley','Gulfstream','Ameri-Lite',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002791252',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 11:29:49','2025-08-02 11:30:13','SV11',8),(121,NULL,'5SFEB302XNE505246','statepark','Shelby Valley','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002806928',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 11:31:27','2025-08-02 11:31:52','SV12',8),(122,NULL,'4X4TCKB24MX153874','statepark','Shelby Valley','Forest River','Cherokee',NULL,'2021',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002797846',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 11:33:13','2025-08-02 11:33:49','SV13',8),(123,NULL,'1UJBJ0BN4N17R0077','statepark','Shelby Valley','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002463381',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 11:35:03','2025-08-02 11:35:49','SV14',8),(124,NULL,'4YDTH1L21N7241733','statepark','Shelby Valley','Keystone','Hideout',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002463282',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 11:37:34','2025-08-02 11:44:05','SV15',8),(125,NULL,'5SFPB2923NE480194','statepark','Shelby Valley','Heartland','Pioneer',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002827643',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 11:45:22','2025-08-02 11:46:24','SV16',8),(126,NULL,'1UJBJ0BN8N17V1945','statepark','Shelby Valley','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002741539',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 11:47:41','2025-08-02 11:49:52','SV17',8),(127,NULL,'4YDTH1M20N7241186','statepark','Shelby Valley','Hideout',NULL,NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002802455',NULL,NULL,'Transferred to City/County/State Entity (#800080)',NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 11:58:56','2025-08-02 11:59:30','SV18',8),(128,NULL,'1NL1G3026S1175833','statepark','Shelby Valley','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002311788',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 12:02:10','2025-08-02 12:02:35','SV19',8),(129,NULL,'1NL1G3026S1175329','statepark','Shelby Valley','Gulfstream','Ameri-Lite',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Pike','015910002312042',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 12:03:54','2025-08-02 12:04:35','SV20',8),(130,NULL,'1NL1G3026S1175834','statepark','Shelby Valley','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Pike','015910002768813',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 12:05:34','2025-08-02 12:05:59','SV21',8),(131,NULL,'1NL1G2724S6021298','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'No',NULL,'Floyd','015910002796772',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 12:09:22','2025-08-02 12:10:02','C65',3),(132,NULL,'1NL1G2729S1174949','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002807199',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:10:31','2025-08-02 15:10:51','C66',3),(133,NULL,'1NL1G3021S1175304','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002797770',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:11:45','2025-08-02 15:12:04','C67',3),(134,NULL,'1NL1G3023S1175305','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000323678',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:14:11','2025-08-02 15:14:31','C68',3),(135,NULL,'1NL1G3029S1175308','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000323942',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:15:48','2025-08-02 15:16:03','C69',3),(136,NULL,'5SFEB3024NE513973','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002311317',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:17:13','2025-08-02 15:47:04','C70',4),(137,NULL,'5SFEB302XNE513976','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002798000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:18:10','2025-08-02 15:47:23','C71',4),(138,NULL,'1NL1G2722S1174954','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000318587',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:20:21','2025-08-02 15:20:46','C75',3),(139,NULL,'1NL1G2725S1174950','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002796202',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:21:41','2025-08-02 15:22:20','C76',3),(140,NULL,'1NL1G2726S6018998','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002809666',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:23:06','2025-08-02 15:23:21','C77',3),(141,NULL,'1NL1G2720S1174953','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002802703',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:24:19','2025-08-02 15:24:35','C78',3),(142,NULL,'1NL1G2729S1174952','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002256462',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:29:24','2025-08-02 15:39:18','C79',3),(143,NULL,'1NL1G3024S1175832','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002809393',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:42:01','2025-08-02 15:42:20','C80',3),(144,NULL,'1NL1G302XS1175835','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002816067',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:44:28','2025-08-02 15:44:47','C81',3),(145,NULL,'1UJBJOBM9N17N0424','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-08-02 15:45:46','2025-08-04 09:54:33','C82',9),(146,NULL,'1UJBJ0BN0N17V1681','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:48:54','2025-08-02 15:49:17','C84',9),(147,NULL,'1UJBJ0BN5N17V0476','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:50:13','2025-08-02 15:50:30','C85',9),(148,NULL,'1NL1G2726N6010310','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:51:21','2025-08-02 15:51:46','C86',9),(149,NULL,'5ZT2CKRB3N2006269','statepark','Jenny Wiley State Park','Forest River','Cherokee',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-08-02 15:53:07','2025-08-04 09:55:41','C88',9),(150,NULL,'1UJBJOBPXN75W019','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-08-02 15:54:23','2025-08-04 09:55:57','C89',9),(151,NULL,'5SFEB302XNE488111','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 15:55:33','2025-08-02 15:55:49','C90',9),(152,NULL,'1UJBJOBM3N17N0368','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-08-02 16:03:48','2025-08-04 09:56:40','C100',9),(153,NULL,'1UJBJ0BMXN17N0593','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-08-02 16:05:17','2025-08-04 09:56:57','C101',9),(154,NULL,'1NL1G2729N6010320','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:06:20','2025-08-02 16:06:39','C102',9),(155,NULL,'1NL1G2727N6010249','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:07:56','2025-08-02 16:08:13','C103',9),(156,NULL,'1SABS0BP6N1BL5172','statepark','Jenny Wiley State Park','Autumn Ridge',NULL,'268BHS','2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,'1','2025-08-02 16:10:03','2025-08-04 09:57:20','C104',9),(157,NULL,'4YDT27021M5373525','statepark','Jenny Wiley State Park','Keystone',NULL,NULL,'2021',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:11:00','2025-08-02 16:11:19','C105',9),(158,NULL,'5ZT2CKRB8NF001514','statepark','Jenny Wiley State Park','Forest River','Cherokee',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-02 16:12:03','2025-08-02 16:12:24','C107',9),(159,NULL,'1UJBJ0BMXN17N0643','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,'1','2025-08-02 16:13:13','2025-08-04 09:58:41','C108',9),(160,NULL,'5ZT2AVSB0PB936067','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000321243',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-08-02 16:14:47','2025-08-04 09:59:32','C109',6),(161,NULL,'5ZT2AVRB2NB932945','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000324965',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-08-02 16:17:14','2025-08-04 09:59:59','C110',6),(162,NULL,'1UJBJ0BN2N17V0824','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002314055',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:18:11','2025-08-02 16:18:29','C111',6),(163,NULL,'4EZJU2522N5121225','statepark','Jenny Wiley State Park','KZRV','Sportsmen',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002321530',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:19:34','2025-08-02 16:19:51','C112',6),(164,NULL,'5ZT2AVNB6PB936246','statepark','Jenny Wiley State Park','Forest River','Avenger',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000319262',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:20:51','2025-08-02 16:21:06','C113',6),(165,NULL,'1UJBJ0BN6N17V1619','statepark','Jenny Wiley State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000335474',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:22:25','2025-08-02 16:22:43','C114',6),(166,NULL,'1NL1G2725S6021309','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002797879',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-02 16:23:25','2025-08-02 16:23:47','C115',3),(167,NULL,'1NL1G2722S6019016','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002801408',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:24:31','2025-08-02 16:24:48','C116',3),(168,NULL,'1NL1G2727S1174951','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002311374',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-02 16:25:24','2025-08-02 16:25:43','C117',3),(169,NULL,'1NL1G302XS1175303','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','01591000331606',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:26:40','2025-08-02 16:26:59','C118',3),(170,NULL,'1NL1G3027S1175307','statepark','Jenny Wiley State Park','Gulfstream','Ameri-Lite',NULL,'2025',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910000354863',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:27:39','2025-08-02 16:27:59','C119',3),(171,NULL,'5SFEB3027PE517955','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002797820',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:28:48','2025-08-02 16:29:13','C120',4),(172,NULL,'5SFEB3028PE517933','statepark','Jenny Wiley State Park','Heartland','Trail Runner',NULL,'2023',NULL,NULL,NULL,'Yes',NULL,'Floyd','015910002308537',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-02 16:30:09','2025-08-02 16:30:25','C120',4),(173,NULL,'5SFEB3026NE503106','statepark','General Butler State Park','Heartland','Trail Runner',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-02 16:32:52','2025-08-02 16:33:07','89',9),(174,NULL,'4EZTU252XN5090712','statepark','General Butler State Park','KZRV','Sportsmen',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','NA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-02 16:33:58','2025-08-02 16:34:16','90',9),(175,NULL,'1SAB50BP3N1BL5114','statepark','General Butler State Park','Starcraft','Autumn Ridge',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Carroll','15910000343379',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:35:06','2025-08-02 16:35:22','91',9),(176,NULL,'1UJBJOBM7N1790633','statepark','General Butler State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','15910000339807',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,'1','2025-08-02 16:36:48','2025-08-02 16:37:06','92',9),(177,NULL,'5ZT2CAUB2NX024394','statepark','General Butler State Park','Forest River','Catalina',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','015910000361074',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:38:10','2025-08-02 16:38:27','93',9),(178,NULL,'1NL1G2729N6010298','statepark','General Butler State Park','Gulfstream','Ameri-Lite',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','015910000338510',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:39:09','2025-08-02 16:39:25','94',9),(179,NULL,'4X4TCKY24NK079474','statepark','General Butler State Park','Forest River','Cherokee',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','15910000347941',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:40:13','2025-08-02 16:40:37','95',9),(180,NULL,'4YDTH1M20N7240989','statepark','General Butler State Park','Hideout',NULL,NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Carroll','015910000345457',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:41:26','2025-08-02 16:41:42','96',9),(181,NULL,'4YDT26B2XM8924670','statepark','General Butler State Park','Keystone','Aspen Trail',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Carroll','15910000343213',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-02 16:42:36','2025-08-02 16:42:54','97',9),(182,NULL,'4YDTH1M2XN7243219','statepark','General Butler State Park','Forest River','Cherokee',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','15910002801259',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-03 06:35:23','2025-08-03 06:35:50','98',9),(183,NULL,'5ZT2AVSB1NB932087','statepark','General Butler State Park','Forest River','Avenger',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Carroll','015910000320708',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-03 06:37:04','2025-08-03 06:37:26','99',9),(184,NULL,'4X4TCKE22NX156057','statepark','General Butler State Park','Forest River','Cherokee',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','015910000339864',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-03 06:38:57','2025-08-03 06:39:44','100',9),(185,NULL,'1UJBJ0BMXN17N0884','statepark','General Butler State Park','Jayco','Jay Flight',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','15910000319841',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-03 06:40:47','2025-08-03 06:41:15','103',9),(186,NULL,'5SFPB3523NE490283','statepark','General Butler State Park','Heartland','Prowler',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','015910002796525',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-03 06:42:14','2025-08-03 06:42:34','105',9),(187,NULL,'5ZT2CKRB8NF001156','statepark','General Butler State Park','Forest River','Grey Wolf',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','15910002810532',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-03 06:44:03','2025-08-03 06:44:28','107',9),(188,NULL,'4YDTH1N26N7243040','statepark','General Butler State Park','Keystone','Hideout',NULL,NULL,NULL,NULL,NULL,'Yes',NULL,'Carroll','015910002835216',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,'1','2025-08-03 06:45:58','2025-08-03 06:46:19','108',9),(189,NULL,'5ZT2CKRB1NF000799','statepark','General Butler State Park','Forest River','Cherokee',NULL,'2022',NULL,NULL,NULL,'Yes',NULL,'Carroll','015910002801218',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'NO',NULL,NULL,1,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,'1','2025-08-03 06:47:43','2025-08-04 09:54:46','110',9);
/*!40000 ALTER TABLE `ttu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ttulocation`
--

DROP TABLE IF EXISTS `ttulocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ttulocation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ttu_id` int DEFAULT NULL,
  `loc_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `loc_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `loc_phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ttulocation`
--

LOCK TABLES `ttulocation` WRITE;
/*!40000 ALTER TABLE `ttulocation` DISABLE KEYS */;
INSERT INTO `ttulocation` VALUES (1,NULL,'Brexit County State Park of Florida','4831 Wetstone Dr.','(123) 456 - 7890'),(2,NULL,'Ramada Inn','9930 Divebar Ln.','(123) 458 - 2894');
/*!40000 ALTER TABLE `ttulocation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `token` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `admin_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payroll_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `worker_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `contact_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `account_manager_id` varchar(25) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_token` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `verified` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Dart Admin','admin@dart.com','000',1,'','$2y$10$6E8oWaxqgaMrETR/IYtHEegfRejR9fVqg0f/OfISMJFtqyybQpQYu','UxXA9jonddOYTFDKPxkAQ6vgoDRBWZ7kTHiUMc4xSAxJfatxeUKY5EZBmWdp',NULL,NULL,NULL,NULL,NULL,'2017-10-13 22:59:32','2019-03-10 13:14:56',NULL,NULL),(24,'Mark','PMARKLAMBERT@GMAIL.COM',NULL,1,NULL,'$2y$12$dSWxkbe6iZIU/Tr.9At1fumLqQJ.eISNxsXX1aSX3HCAOzSSiHEh6',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-04 15:52:54','2025-08-04 15:52:54',NULL,NULL),(25,'Joe','joe@gfcenterprizesllc.com',NULL,1,NULL,'$2y$12$1lRlVfptv01hBIsmOpCgTOkwz3PVoj8p314xp0pJ0Yv.mS09tZ/Fu',NULL,NULL,NULL,NULL,NULL,NULL,'2025-08-05 15:27:41','2025-08-05 15:27:41',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
INSERT INTO `vendor` VALUES (1,'Opel'),(2,'Ford'),(3,'KYEM'),(4,'FL Phase 2'),(5,'Perry Co.'),(6,'KYEM Surplus'),(7,'FL Phase 3'),(8,'Pike Co'),(9,'LA Phase 2');
/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-11 11:48:04
