-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: 2600
-- ------------------------------------------------------
-- Server version	5.5.47-0+deb8u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Issues`
--

DROP TABLE IF EXISTS `Issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Issues` (
  `IssuesID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `Period` varchar(9) NOT NULL COMMENT 'Issue period - Spring, Summer, Autumn, Winter or January thru December.',
  `IssueYear` int(11) NOT NULL COMMENT 'Four digit issue year.',
  `Notes` varchar(2048) DEFAULT NULL COMMENT 'Issue notes and/or comments.',
  `CoverLink` varchar(256) NOT NULL COMMENT 'Link to cover image.',
  `BackCoverLink` varchar(256) NOT NULL COMMENT 'Link to back cover image.',
  `Quarter` int(11) NOT NULL COMMENT 'Issue numeric quarter, for sorting',
  `Volume` int(11) NOT NULL COMMENT 'Issue volume number.',
  `Quote` varchar(2048) DEFAULT NULL COMMENT 'Issue quote (from Staff page).',
  `Pages` int(11) NOT NULL COMMENT 'Number of pages in issue.',
  PRIMARY KEY (`IssuesID`),
  UNIQUE KEY `Period_IssueYear` (`Period`,`IssueYear`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=latin1 COMMENT='Issue information (cover, period, notes etc).';
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-18 15:10:41
