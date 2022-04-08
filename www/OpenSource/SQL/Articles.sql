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
-- Table structure for table `Articles`
--

DROP TABLE IF EXISTS `Articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Articles` (
  `ArticlesID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `IssuesID` int(11) NOT NULL COMMENT 'Link to issue.',
  `Title` varchar(128) NOT NULL COMMENT 'Article title (from 2600 index - often different from page).',
  `AuthorsID` int(11) NOT NULL DEFAULT '1' COMMENT 'Link to author.',
  `Synopsis` varchar(2048) NOT NULL COMMENT 'Article synopsis.',
  `Notes` varchar(1024) DEFAULT NULL COMMENT 'Article notes and/or comments.',
  `Code` char(1) NOT NULL DEFAULT 'N' COMMENT 'Article code flag, C=on 2600 web site, A=in article text, N=none, S=See links.',
  `Page` int(11) DEFAULT NULL COMMENT 'Article page number in issue.',
  `PageTitle` varchar(128) DEFAULT NULL COMMENT 'Article title (from page - often different from index).',
  `Pages` decimal(5,3) NOT NULL DEFAULT '1.000' COMMENT 'Number of pages in article (percentage for less than one page).',
  `PageIn2600Book` int(11) NOT NULL DEFAULT '0' COMMENT 'Zero if article is not in Best of 2600 book, otherwise the page number.',
  PRIMARY KEY (`ArticlesID`),
  UNIQUE KEY `ArticlesID_IssuesID` (`IssuesID`,`Title`)
) ENGINE=InnoDB AUTO_INCREMENT=3106 DEFAULT CHARSET=latin1 COMMENT='Article information (issue, title, author etc).';
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-18 15:10:41
