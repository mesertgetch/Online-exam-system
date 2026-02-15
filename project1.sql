-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: project1
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Create and use the database
--
CREATE DATABASE IF NOT EXISTS `project1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `project1`;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `email` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `role` varchar(10) DEFAULT 'admin',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` (`email`, `password`, `role`) VALUES ('head@gmail.com','head','head'),('teacher1@gmail.com','teacher1','admin');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `answer`
--

DROP TABLE IF EXISTS `answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answer` (
  `qid` text NOT NULL,
  `ansid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answer`
--

LOCK TABLES `answer` WRITE;
/*!40000 ALTER TABLE `answer` DISABLE KEYS */;
INSERT INTO `answer` (`qid`, `ansid`) VALUES ('698f1018154ff','698f101815bf2'),('698f1018182dd','698f1018188f8'),('698f10181b1cd','698f10181b72a'),('698f10181cf6c','698f10181dc8e'),('698f17940e825','698f17940f1f7'),('698f1794123dd','698f179412b53'),('698f17cfadb10','698f17cfaef23'),('698f17cfb2469','698f17cfb2e7f'),('698f18145d9c0','698f18145e8a7'),('698f181461cc0','698f181462477'),('698f1888bd0ed','698f1888bdad9'),('698f1888c12a4','698f1888c25c1'),('698f199962e8d','698f19996362d'),('698f19996659a','698f199966ba0'),('698f199969cd8','698f19996ab9c'),('698f1a0c6f274','698f1a0c6f956'),('698f1a0c71dba','698f1a0c72350'),('698f1a0c746e7','698f1a0c75501'),('698f1eaab8cf2','698f1eaab9993'),('698f1eaabea1d','Match Colors'),('698f1eaabfdd1','match_check_pairs'),('698f218f3264f','698f218f32d9f'),('698f218f36d27','9'),('698f218f379c1','match_check_pairs'),('698f21d4d7a7a','698f21d4dba99'),('698f21d4dd7d0','3'),('6990c3f4ebb02','6990c3f4ec584'),('6990c3f4eebf5','4'),('6990c3f4ef71f','match_check_pairs'),('6990c3f4f2f49','6990c3f4f35cb');
/*!40000 ALTER TABLE `answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `dept_id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(100) NOT NULL,
  `year_labels` text NOT NULL,
  PRIMARY KEY (`dept_id`),
  UNIQUE KEY `dept_name` (`dept_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` (`dept_id`, `dept_name`, `year_labels`) VALUES (3,'Software Enginerring','1st, 2nd, 3rd, 4th, 5th');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` text NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `feedback` varchar(500) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history` (
  `email` varchar(50) NOT NULL,
  `eid` text NOT NULL,
  `score` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `sahi` int(11) NOT NULL,
  `wrong` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
INSERT INTO `history` (`email`, `eid`, `score`, `level`, `sahi`, `wrong`, `date`) VALUES ('head@gmail.com','698f0f702fa5e',0,4,1,1,'2026-02-13 16:23:45'),('mesi@ecusta.edu.et','6990c376e2446',4,4,4,0,'2026-02-15 19:54:53'),('mesi@ecusta.edu.et','698f0f702fa5e',0,4,0,0,'2026-02-15 19:57:10');
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `options` (
  `qid` varchar(50) NOT NULL,
  `option` varchar(5000) NOT NULL,
  `optionid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` (`qid`, `option`, `optionid`) VALUES ('698f1018154ff','A progmraming ','698f101815beb'),('698f1018154ff','The Tes','698f101815bf1'),('698f1018154ff','print','698f101815bf2'),('698f1018154ff','Ass','698f101815bf3'),('698f1018182dd','what this','698f1018188f4'),('698f1018182dd','Whay not this','698f1018188f7'),('698f1018182dd','This is rt','698f1018188f8'),('698f1018182dd','nAA','698f1018188f9'),('698f10181b1cd','tHES 1','698f10181b726'),('698f10181b1cd','test 2','698f10181b729'),('698f10181b1cd','test 3','698f10181b72a'),('698f10181b1cd','tes4','698f10181b72b'),('698f10181cf6c','ALWya1','698f10181dc8a'),('698f10181cf6c','alw2','698f10181dc8e'),('698f10181cf6c','try c','698f10181dc8f'),('698f10181cf6c','chod','698f10181dc90'),('698f17940e825','1','698f17940f1ee'),('698f17940e825','2','698f17940f1f7'),('698f17940e825','3','698f17940f1f8'),('698f17940e825','4','698f17940f1f9'),('698f1794123dd','2','698f179412b49'),('698f1794123dd','3','698f179412b52'),('698f1794123dd','4','698f179412b53'),('698f1794123dd','5','698f179412b54'),('698f17cfadb10','1','698f17cfaef19'),('698f17cfadb10','2','698f17cfaef23'),('698f17cfadb10','3','698f17cfaef26'),('698f17cfadb10','4','698f17cfaef27'),('698f17cfb2469','2','698f17cfb2e71'),('698f17cfb2469','3','698f17cfb2e7e'),('698f17cfb2469','4','698f17cfb2e7f'),('698f17cfb2469','5','698f17cfb2e81'),('698f18145d9c0','1','698f18145e89c'),('698f18145d9c0','2','698f18145e8a7'),('698f18145d9c0','3','698f18145e8a8'),('698f18145d9c0','4','698f18145e8a9'),('698f181461cc0','2','698f18146246d'),('698f181461cc0','3','698f181462476'),('698f181461cc0','4','698f181462477'),('698f181461cc0','5','698f181462478'),('698f1888bd0ed','1','698f1888bdacc'),('698f1888bd0ed','2','698f1888bdad9'),('698f1888bd0ed','3','698f1888bdada'),('698f1888bd0ed','4','698f1888bdadb'),('698f1888c12a4','2','698f1888c25b5'),('698f1888c12a4','3','698f1888c25bf'),('698f1888c12a4','4','698f1888c25c1'),('698f1888c12a4','5','698f1888c25c2'),('698f199962e8d','2','698f19996362d'),('698f199962e8d','3','698f199963633'),('698f199962e8d','4','698f199963634'),('698f199962e8d','5','698f199963635'),('698f19996659a','2','698f199966b9a'),('698f19996659a','3','698f199966b9f'),('698f19996659a','4','698f199966ba0'),('698f19996659a','5','698f199966ba1'),('698f199969cd8','2','698f19996ab94'),('698f199969cd8','4','698f19996ab9a'),('698f199969cd8','3','698f19996ab9b'),('698f199969cd8','6','698f19996ab9c'),('698f1a0c6f274','1','698f1a0c6f952'),('698f1a0c6f274','2','698f1a0c6f956'),('698f1a0c6f274','3','698f1a0c6f957'),('698f1a0c6f274','4','698f1a0c6f958'),('698f1a0c71dba','2','698f1a0c7234b'),('698f1a0c71dba','3','698f1a0c7234f'),('698f1a0c71dba','4','698f1a0c72350'),('698f1a0c71dba','5','698f1a0c72351'),('698f1a0c746e7','3','698f1a0c754f7'),('698f1a0c746e7','4','698f1a0c754ff'),('698f1a0c746e7','5','698f1a0c75500'),('698f1a0c746e7','6','698f1a0c75501'),('698f1eaab8cf2','3','698f1eaab9989'),('698f1eaab8cf2','4','698f1eaab9993'),('698f1eaab8cf2','5','698f1eaab9994'),('698f1eaab8cf2','6','698f1eaab9995'),('698f1eaabfdd1','Blue','Sky'),('698f1eaabfdd1','Green','Grass'),('698f1eaabfdd1','',''),('698f1eaabfdd1','',''),('698f218f3264f','2','698f218f32d9f'),('698f218f3264f','3','698f218f32da6'),('698f218f3264f','4','698f218f32da7'),('698f218f3264f','5','698f218f32da8'),('698f218f379c1','1+1','2'),('698f218f379c1','2+2','4'),('698f218f379c1','3+3','6'),('698f218f379c1','4+4','8'),('698f21d4d7a7a','2','698f21d4dba99'),('698f21d4d7a7a','3','698f21d4dbaa0'),('698f21d4d7a7a','4','698f21d4dbaa1'),('698f21d4d7a7a','5','698f21d4dbaa2'),('6990c3f4ebb02','2','6990c3f4ec584'),('6990c3f4ebb02','3','6990c3f4ec589'),('6990c3f4ebb02','4','6990c3f4ec58a'),('6990c3f4ebb02','5','6990c3f4ec58b'),('6990c3f4ef71f','1+!','2'),('6990c3f4ef71f','2+2','4'),('6990c3f4ef71f','3+3','6'),('6990c3f4ef71f','4+4','8'),('6990c3f4f2f49','5','6990c3f4f35c3'),('6990c3f4f2f49','7','6990c3f4f35ca'),('6990c3f4f2f49','6','6990c3f4f35cb'),('6990c3f4f2f49','8','6990c3f4f35cc');
/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `eid` text NOT NULL,
  `qid` text NOT NULL,
  `qns` text NOT NULL,
  `choice` int(10) NOT NULL,
  `sn` int(11) NOT NULL,
  `question_type` varchar(20) DEFAULT 'mcq'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` (`eid`, `qid`, `qns`, `choice`, `sn`, `question_type`) VALUES ('698f0f702fa5e','698f1018154ff','What is Python',4,1,'mcq'),('698f0f702fa5e','698f1018182dd','test2',4,2,'mcq'),('698f0f702fa5e','698f10181b1cd','tHIS IS 3',4,3,'mcq'),('698f0f702fa5e','698f10181cf6c','The followin is try4',4,4,'mcq'),('698f17814df08','698f17940e825','What is 1+1?',4,1,'mcq'),('698f17814df08','698f1794123dd','What is 2+2?',4,2,'mcq'),('698f17c77ece4','698f17cfadb10','What is 1+1?',4,1,'mcq'),('698f17c77ece4','698f17cfb2469','What is 2+2?',4,2,'mcq'),('698f18078af32','698f18145d9c0','What is 1+1?',4,1,'mcq'),('698f18078af32','698f181461cc0','What is 2+2?',4,2,'mcq'),('698f187f66b46','698f1888bd0ed','What is 1+1?',4,1,'mcq'),('698f187f66b46','698f1888c12a4','What is 2+2?',4,2,'mcq'),('698f195adb1f7','698f199962e8d','What 1+1',4,1,'mcq'),('698f195adb1f7','698f19996659a','what is 2+2',4,2,'mcq'),('698f195adb1f7','698f199969cd8','what is 3+3',4,3,'mcq'),('698f19cadc0ad','698f1a0c6f274','1+1',4,1,'mcq'),('698f19cadc0ad','698f1a0c71dba','2+2',4,2,'mcq'),('698f19cadc0ad','698f1a0c746e7','3+3',4,3,'mcq'),('698f1dfdf21dd','698f1eaab8cf2','2+2?',4,1,''),('698f1dfdf21dd','698f1eaabea1d','Paris',4,2,'short'),('698f1dfdf21dd','698f1eaabfdd1','Match Colors',4,3,'match'),('698f211d93d1a','698f218f3264f','1+1',4,1,'mcq'),('698f211d93d1a','698f218f36d27','2+3+4',4,2,'short'),('698f211d93d1a','698f218f379c1','Match the question',4,3,'match'),('698f21ad6be11','698f21d4d7a7a','1+1',4,1,'mcq'),('698f21ad6be11','698f21d4dd7d0','2+2',4,2,'short'),('6990c376e2446','6990c3f4ebb02','1+1',4,1,'mcq'),('6990c376e2446','6990c3f4eebf5','2+2',4,2,'short'),('6990c376e2446','6990c3f4ef71f','Match the correct one',4,3,'match'),('6990c376e2446','6990c3f4f2f49','3+3',4,5,'mcq');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quiz` (
  `eid` text NOT NULL,
  `title` varchar(100) NOT NULL,
  `sahi` int(11) NOT NULL,
  `wrong` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `intro` text NOT NULL,
  `tag` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(50) DEFAULT NULL,
  `access_code` varchar(50) DEFAULT NULL,
  `target_dept` varchar(100) DEFAULT NULL,
  `target_year` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz`
--

LOCK TABLES `quiz` WRITE;
/*!40000 ALTER TABLE `quiz` DISABLE KEYS */;
INSERT INTO `quiz` (`eid`, `title`, `sahi`, `wrong`, `total`, `time`, `intro`, `tag`, `date`, `email`, `access_code`, `target_dept`, `target_year`, `status`) VALUES ('698f0f702fa5e','Ict-pythone',1,1,4,10,'Please Anserw all correctly','#Midterms','2026-02-15 19:55:40','head@gmail.com',NULL,NULL,NULL,'active'),('6990c376e2446','Medical Pathology',1,1,4,50,'Read and Answer the following','#Finals','2026-02-15 19:53:29','head@gmail.com','2728','Software Enginerring','','active');
/*!40000 ALTER TABLE `quiz` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rank`
--

DROP TABLE IF EXISTS `rank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rank` (
  `email` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rank`
--

LOCK TABLES `rank` WRITE;
/*!40000 ALTER TABLE `rank` DISABLE KEYS */;
INSERT INTO `rank` (`email`, `score`, `time`) VALUES ('head@gmail.com',0,'2026-02-15 19:53:33'),('teacher1@gmail.com',0,'2026-02-15 10:56:41'),('mesi@ecusta.edu.et',4,'2026-02-15 19:57:10');
/*!40000 ALTER TABLE `rank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `name` varchar(50) NOT NULL,
  `gender` varchar(5) NOT NULL,
  `college` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mob` bigint(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `year` varchar(50) DEFAULT '1st Year',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`name`, `gender`, `college`, `email`, `mob`, `password`, `year`) VALUES ('Messeret Getachew','M','Software Enginerring','mesi@ecusta.edu.et',940225343,'e10adc3949ba59abbe56e057f20f883e','1st');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-15 23:03:29
