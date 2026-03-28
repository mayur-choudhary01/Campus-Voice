-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: campusvoice
-- ------------------------------------------------------
-- Server version	8.0.43

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin123');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analytics`
--

DROP TABLE IF EXISTS `analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analytics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `total_complaints` int DEFAULT '0',
  `resolved` int DEFAULT '0',
  `in_progress` int DEFAULT '0',
  `satisfaction_rate` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics`
--

LOCK TABLES `analytics` WRITE;
/*!40000 ALTER TABLE `analytics` DISABLE KEYS */;
INSERT INTO `analytics` VALUES (1,450,380,45,95);
/*!40000 ALTER TABLE `analytics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaints` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text,
  `faculty_remarks` text,
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `assigned_to` int DEFAULT NULL,
  `status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_anonymous` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaints`
--

LOCK TABLES `complaints` WRITE;
/*!40000 ALTER TABLE `complaints` DISABLE KEYS */;
INSERT INTO `complaints` VALUES (1,101,'Rahul Verma',NULL,'CS','BCA 2nd Year','Fan not working','Infrastructure','Room No. 202 ka fan awaz kar raha hai.','Room No. 202 The fan has been fixed','High',4,'Resolved','2026-03-20 18:58:50',0),(2,1,'Student',NULL,'FCA','BCA 2nd Year',' exam form','General',' exam form issue ','','High',4,'Resolved','2026-03-22 06:02:53',0),(3,1,'Student',NULL,'FCA','BCA 2nd Year','Internet issue','Infrastructure','Internet issue','','Medium',4,'Resolved','2026-03-22 09:43:13',0),(4,1,'Student',NULL,'IMCA','BCA 2nd Year','Lab fan ','Infrastructure','Fan is not working properly ',NULL,'Low',NULL,'Resolved','2026-03-23 05:50:00',0),(5,1,'Student',NULL,'FCA','BCA 2nd Year','\"Projector not working in Room 302\"','Other','Projecter is not working properly','','High',4,'In Progress','2026-03-23 06:19:37',0),(6,1,'Student',NULL,'FCA','BCA 2nd Year','Ac is not available ','Other','Respected Class Coordinator,\r\n\r\nI would like to bring to your kind attention that our classroom becomes extremely hot during lecture hours. Due to this, it becomes very difficult for students to concentrate properly on studies.\r\n\r\nThe ventilation in the classroom is not sufficient, and fans are not working effectively. This causes discomfort and affects our learning environment.\r\n\r\nI kindly request you to please look into this issue and take necessary steps, such as improving ventilation or add Ac , so that students can attend classes comfortably.\r\n\r\nThank you for your understanding.\r\n','Y','Low',4,'Resolved','2026-03-23 06:20:00',0),(7,1,'Student',NULL,'FCA','BCA 2nd Year','\"Projector not working in Room 302\"','Other','Projecter is not working properly','','High',4,'In Progress','2026-03-23 06:22:15',0),(8,1,'Student',NULL,'FCA','BCA 2nd Year','\"Projector not working in Room 302\"','Other','Projecter is not working properly',NULL,'High',NULL,'Pending','2026-03-23 06:22:57',0),(9,1,'Student',NULL,'FCA','BCA 2nd Year','Concern Regarding Teaching Method','Behavior','Respected  Madam,\r\nI would like to express concern about the teaching method in [all subjects]. Many students are finding it difficult to understand the concepts.\r\nWe kindly request you to take necessary steps to improve the learning experience.\r\nThank you.\r\nYours sincerely,\r\n[XYZ]',NULL,'Medium',NULL,'Pending','2026-03-23 07:05:32',0),(10,1,'Student',NULL,'FCA','BCA 2nd Year','Lab','Infrastructure','Lab ',NULL,'Medium',NULL,'Pending','2026-03-23 07:06:57',0),(11,1,'Student',NULL,'FCA','BCA 2nd Year','PROJECTER PROBLEM','Infrastructure','FNDJKF','','High',5,'In Progress','2026-03-23 07:15:18',0),(12,1,'mayur',NULL,'FCA','BCA 1st Year','Fees problem','Other','Weldwe',NULL,'High',NULL,'Pending','2026-03-23 07:39:40',0),(13,1,'mayur',NULL,'FCA','BCA 2nd Year','Complaint regarding  irregular class','Academic','Lecture are not being conducted properly attending our studies. kindly take  necessary action',NULL,'Medium',NULL,'Pending','2026-03-24 06:14:19',0),(14,1,'mayur',NULL,'FCA','BCA 2nd Year','Internet issue','Infrastructure','Fgfh','','Medium',4,'In Progress','2026-03-24 08:37:42',0);
/*!40000 ALTER TABLE `complaints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculty`
--

DROP TABLE IF EXISTS `faculty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faculty` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `assigned_class` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'default_user.png',
  `icon_class` varchar(100) DEFAULT 'fa-user',
  `icon_color` varchar(50) DEFAULT 'text-primary',
  `role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty`
--

LOCK TABLES `faculty` WRITE;
/*!40000 ALTER TABLE `faculty` DISABLE KEYS */;
INSERT INTO `faculty` VALUES (1,'Dr. Rajesh Gupta',NULL,NULL,'Head of BCA/MCA','BCA',NULL,'../images/hod.png','fa-laptop-code','text-primary','HOD'),(2,'Prof. Anita Sharma',NULL,NULL,'Head of B.Tech CS','CS',NULL,'default_user.png','fa-user-gear','text-warning','HOD'),(3,'Dr. Vikram Singh',NULL,NULL,'Head of Management','Management',NULL,'default_user.png','images/hod','text-success','HOD'),(4,'Dr. Geeta santosh ','hod@college.com','123456','Head of Department','FCA','','1774249482_IMG_20251103_134819.jpg','fa-user','text-primary','HOD'),(5,'Harshita sharma','cood@college.com','123456','cood@college.com','FCA','BCA 2nd Year','default_user.jpeg','fa-user','text-primary','Coordinator');
/*!40000 ALTER TABLE `faculty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` int NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footer_settings`
--

DROP TABLE IF EXISTS `footer_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `footer_settings` (
  `id` int NOT NULL,
  `about_text` text,
  `address` text,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `insta_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `copyright_text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_settings`
--

LOCK TABLES `footer_settings` WRITE;
/*!40000 ALTER TABLE `footer_settings` DISABLE KEYS */;
INSERT INTO `footer_settings` VALUES (1,'Smart digital bridge for Acropolis Students.','Indore, MP','info@acropolis.in','+91 00000000','#','#','All Rights Reserved.');
/*!40000 ALTER TABLE `footer_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hero_settings`
--

DROP TABLE IF EXISTS `hero_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hero_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `badge_text` varchar(255) DEFAULT NULL,
  `main_title_part1` varchar(255) DEFAULT NULL,
  `main_title_blue` varchar(255) DEFAULT NULL,
  `hero_description` text,
  `stat1_val` varchar(50) DEFAULT NULL,
  `stat1_label` varchar(50) DEFAULT NULL,
  `stat2_val` varchar(50) DEFAULT NULL,
  `stat2_label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_settings`
--

LOCK TABLES `hero_settings` WRITE;
/*!40000 ALTER TABLE `hero_settings` DISABLE KEYS */;
INSERT INTO `hero_settings` VALUES (1,'Welcome Back','Your Voice','Campus Resolved.','Smart digital bridge between Acropolis Students and HODs.','24/7','Support','100%','Secure');
/*!40000 ALTER TABLE `hero_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` enum('urgent','update','event','general') DEFAULT 'general',
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notices`
--

LOCK TABLES `notices` WRITE;
/*!40000 ALTER TABLE `notices` DISABLE KEYS */;
INSERT INTO `notices` VALUES (1,'Mid-Sem Exam Dates','BCA 2nd Year mid-semester exams are starting from 25th March.','urgent','2026-03-19 20:17:49'),(4,'Category: Tech Fest / Hackathon ?','Title: Tech-Sprint 2026\r\n\r\nDetail: College mein 2 days ka coding competition ho raha hai. Winners ko exciting prizes aur certificates milenge.\r\n\r\nVenue: Computer Lab 1\r\n\r\nDate: 5th April\r\n\r\nBy: Technical Club','urgent','2026-03-24 02:11:36'),(5,'Lost & Found ?','Title: Lost Item\r\n\r\nDetail: Library ke paas ek black color ka HP laptop adapter mila hai. Jiska bhi ho, wo apna ID card dikhakar reception se collect kar sakta hai.\r\n\r\nBy: College Reception','urgent','2026-03-24 02:21:44');
/*!40000 ALTER TABLE `notices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'fa-route','Dept.-wise HOD Routing','Auto-routing complaint ko seedhe sahi Department ke HOD tak pahunchati hai.'),(2,'fa-comments','Direct Communication','HOD ke saath seedhi batchit aur feedback dene ki suvidha.');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `year` varchar(20) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'mayur','mayur.dx24@acropolis.in','mayur@123',NULL,NULL,NULL,NULL),(2,'','','','','','','');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-28 17:55:28
