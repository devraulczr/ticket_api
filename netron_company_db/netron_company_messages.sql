CREATE DATABASE  IF NOT EXISTS `netron_company` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `netron_company`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: netron_company
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(300) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shipping_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ticket_id` (`ticket_id`),
  KEY `fk_user_id` (`user_id`),
  CONSTRAINT `fk_ticket_id` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (60,'a',8,1,'2025-03-20 23:43:23'),(145,'a',8,1,'2025-03-22 10:31:22'),(146,'a',8,1,'2025-03-22 10:31:22'),(147,'a',8,1,'2025-03-22 10:31:22'),(148,'a',8,1,'2025-03-22 10:31:22'),(149,'a',8,1,'2025-03-22 10:31:22'),(150,'a',8,1,'2025-03-22 10:31:23'),(151,'daw',8,1,'2025-03-22 10:31:24'),(152,'a',8,1,'2025-03-22 10:31:24'),(153,'wdawd',8,1,'2025-03-22 10:31:25'),(154,'aw',8,1,'2025-03-22 10:31:25'),(155,'aw',8,1,'2025-03-22 10:31:25'),(156,'dawd',8,1,'2025-03-22 10:31:26'),(157,'awd',8,1,'2025-03-22 10:31:26'),(158,'bfgg',8,1,'2025-03-22 10:31:28'),(159,'bg',8,1,'2025-03-22 10:31:28'),(160,'a',8,1,'2025-03-26 19:43:23'),(161,'a',8,1,'2025-03-26 19:44:16'),(162,'ababa',8,1,'2025-03-26 19:47:14'),(163,'a',8,1,'2025-03-26 19:47:16'),(164,'b',8,1,'2025-03-26 19:47:19'),(165,'teste',8,1,'2025-03-26 20:11:39'),(166,'a',9,2,'2025-03-26 20:12:09'),(167,'ababa',9,2,'2025-03-26 20:12:12'),(168,'teste',9,1,'2025-03-26 20:13:12'),(169,'a',9,2,'2025-03-26 20:13:59'),(170,'testte',9,1,'2025-03-26 20:14:10'),(171,'b',9,1,'2025-03-26 20:14:30'),(172,'teste',9,1,'2025-03-26 20:14:33'),(173,'aaaa',9,2,'2025-03-26 20:14:36'),(174,'aaaa',9,1,'2025-03-28 22:50:26');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-01 19:47:11
