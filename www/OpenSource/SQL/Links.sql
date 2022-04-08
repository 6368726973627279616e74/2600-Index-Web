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
-- Table structure for table `Links`
--

DROP TABLE IF EXISTS `Links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Links` (
  `LinksID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `ArticlesID` int(11) NOT NULL COMMENT 'Link to article.',
  `Link` varchar(256) NOT NULL COMMENT 'Link url.',
  `Description` varchar(64) DEFAULT NULL COMMENT 'Short description for display.',
  `Notes` varchar(2048) DEFAULT NULL COMMENT 'Link notes and/or comments.',
  `Status` char(3) NOT NULL DEFAULT '?' COMMENT 'Links status, Ok, Err or ?=unknown.',
  `Validated` datetime DEFAULT NULL COMMENT 'Date/time stamp link was last validated.',
  `Updated` char(1) NOT NULL DEFAULT 'N' COMMENT 'Link updated? Y=yes, N=no.',
  `LocalCopy` char(1) NOT NULL DEFAULT 'N' COMMENT 'Local copy? Y=yes, N=no.',
  `LocalCopyDate` datetime DEFAULT NULL COMMENT 'Date/time stamp local copy was made using wget.',
  `Type` char(1) NOT NULL DEFAULT 'O' COMMENT 'Link type, O=original article, A=addendum, I=added when indexing.',
  `OriginalLink` varchar(256) NOT NULL COMMENT 'Original link url.',
  `ArchiveLink` varchar(256) DEFAULT NULL COMMENT 'Archive.org link url.',
  `CurlOutput` varchar(4096) DEFAULT NULL COMMENT 'Curl command output from validation.',
  PRIMARY KEY (`LinksID`)
) ENGINE=InnoDB AUTO_INCREMENT=2876 DEFAULT CHARSET=latin1 COMMENT='Article links if any.';
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-18 15:10:41
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_Links_Updated BEFORE UPDATE ON `Links`
FOR EACH ROW SET
NEW.Updated = (CASE WHEN NEW.Link = NEW.OriginalLink THEN 'N' ELSE 'Y' END) */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
