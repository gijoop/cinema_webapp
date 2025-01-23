/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: cinema
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
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Akcja'),(3,'Dramat'),(6,'Fantasy'),(4,'Horror'),(2,'Komedia'),(5,'Sci-Fi'),(7,'Thriller');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language`
--

LOCK TABLES `language` WRITE;
/*!40000 ALTER TABLE `language` DISABLE KEYS */;
INSERT INTO `language` VALUES (1,'Dubbing PL'),(3,'Lektor PL'),(4,'Napisy EN'),(2,'Napisy PL');
/*!40000 ALTER TABLE `language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movie`
--

DROP TABLE IF EXISTS `movie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `release_date` date DEFAULT NULL,
  `poster_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `movie_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movie`
--

LOCK TABLES `movie` WRITE;
/*!40000 ALTER TABLE `movie` DISABLE KEYS */;
INSERT INTO `movie` VALUES (3,'Top Gun: Maverick','Po ponad 30 latach w służbie amerykańskiej marynarki wojennej Pete \"Maverick\" Mitchell (Tom Cruise) jest tam, gdzie powinien być - na szczycie. Jest mistrzowskim pilotem, testującym najnowocześniejsze maszyny. ',131,1,'2022-05-27','1737574376_top_gun.jpg'),(4,'Mission: Impossible - The Final Reckoning','Ten film nie ma jeszcze zarysu fabuły.',0,1,'2025-05-23','1737576465_mission_impossible.png'),(5,'SEPTEMBER 5','\"September 5\" ukazuje kulisy kluczowego momentu w historii przekazu medialnego, który na zawsze zmienił sposób nadawania wiadomości na żywo. Akcja filmu rozgrywa się podczas Letnich Igrzysk Olimpijskich w Monachium w 1972 roku i podąża śladem amerykańskiego zespołu transmisyjnego, który błyskawicznie przestawił się z relacjonowania wydarzeń sportowych na rejestrację porwania izraelskich sportowców.',91,1,'2025-02-28','1737576873_sept.png'),(6,'Kapitan Ameryka: Nowy wspaniały świat','W nowym filmie Marvel Studios \"Kapitan Ameryka: Nowy wspaniały świat\" w rolę Kapitana wciela się Anthony Mackie. Sam Wilson alias Falcon, którego Mackie gra w MCU, oficjalnie przyjął tytuł Kapitana Ameryki w finale serialu \"Falcon i Zimowy żołnierz\". Po spotkaniu z nowo wybranym prezydentem USA, Thaddeusem Rossem (marvelowski debiut Harrisona Forda), Sam trafia w sam środek międzynarodowego kryzysu.',118,1,'2025-02-14','1737576986_captain-america-brave-new-world-poster.jpg'),(7,'Mroczny Rycerz','W nowym filmie Batman podejmuje szeroko zakrojoną walkę z przestępczością. Z pomocą porucznika Jima Gordona i prokuratora okręgowego Harveya Denta zabiera się za rozpracowywanie istniejących organizacji przestępczych nękających mieszkańców miasta. Współpraca przynosi efekty, ale bohaterowie wkrótce padną ofiarą chaosu, który rozpęta rosnący w siłę genialny przestępca, znany przerażonym mieszkańcom Gotham jako Joker.',152,1,'2008-08-08','1737577127_asd.jpg');
/*!40000 ALTER TABLE `movie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  `num_seats` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room`
--

LOCK TABLES `room` WRITE;
/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` VALUES (1,101,50),(2,102,50),(3,103,80),(4,104,80);
/*!40000 ALTER TABLE `room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `showing`
--

DROP TABLE IF EXISTS `showing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `showing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movie_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `time` time NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `movie_id` (`movie_id`),
  KEY `room_id` (`room_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `showing_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`),
  CONSTRAINT `showing_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`),
  CONSTRAINT `showing_ibfk_3` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `showing`
--

LOCK TABLES `showing` WRITE;
/*!40000 ALTER TABLE `showing` DISABLE KEYS */;
INSERT INTO `showing` VALUES (1,3,3,1,'22:00:00','2025-01-22'),(2,7,1,2,'23:00:00','2025-01-22');
/*!40000 ALTER TABLE `showing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `showing_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `seat_number` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `showing_id` (`showing_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`showing_id`) REFERENCES `showing` (`id`),
  CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
INSERT INTO `ticket` VALUES (1,1,1,45),(2,1,1,46);
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `password` char(255) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(60) NOT NULL,
  `creation_date` date NOT NULL DEFAULT curdate(),
  `role` enum('EMPLOYEE','CUSTOMER') NOT NULL DEFAULT 'CUSTOMER',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'luidku','$2y$10$M3eIAxQMZbjhTmafcaYFhuEvfqQ28Z4q2m0XMzom.fpPjaeH9JXY.','Łukasz','Kuś','luidku@gmail.com','2025-01-22','EMPLOYEE');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'cinema'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_upcoming_movies` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_upcoming_movies`()
BEGIN
    SELECT *
    FROM movie
    WHERE movie.release_date > CURDATE()
    ORDER BY movie.release_date
    LIMIT 3;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-22 21:43:07
