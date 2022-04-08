-- MySQL dump 10.19  Distrib 10.3.34-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: 2600
-- ------------------------------------------------------
-- Server version	10.3.34-MariaDB-0+deb10u1

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
-- Table structure for table `Addendums`
--

DROP TABLE IF EXISTS `Addendums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Addendums` (
  `AddendumsID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `ArticlesID` int(11) NOT NULL COMMENT 'Link to article.',
  `Notes` varchar(2048) NOT NULL COMMENT 'Addendum notes and/or comments.',
  PRIMARY KEY (`AddendumsID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Article addendums (addenda) if any.';
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `AuthorsID` int(11) NOT NULL DEFAULT 1 COMMENT 'Link to author.',
  `Synopsis` varchar(2048) NOT NULL COMMENT 'Article synopsis.',
  `Notes` varchar(1024) DEFAULT NULL COMMENT 'Article notes and/or comments.',
  `Code` char(1) NOT NULL DEFAULT 'N' COMMENT 'Article code flag, C=on 2600 web site, A=in article text, N=none, S=See links.',
  `Page` int(11) DEFAULT NULL COMMENT 'Article page number in issue.',
  `PageTitle` varchar(128) DEFAULT NULL COMMENT 'Article title (from page - often different from index).',
  `Pages` decimal(5,3) NOT NULL DEFAULT 1.000 COMMENT 'Number of pages in article (percentage for less than one page).',
  `PageIn2600Book` int(11) NOT NULL DEFAULT 0 COMMENT 'Zero if article is not in Best of 2600 book, otherwise the page number.',
  PRIMARY KEY (`ArticlesID`),
  UNIQUE KEY `ArticlesID_IssuesID` (`IssuesID`,`Title`)
) ENGINE=InnoDB AUTO_INCREMENT=3106 DEFAULT CHARSET=latin1 COMMENT='Article information (issue, title, author etc).';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Authors`
--

DROP TABLE IF EXISTS `Authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Authors` (
  `AuthorsID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `NomDePlume` varchar(64) NOT NULL COMMENT 'Author nom de plume (pen name) from article.',
  `Name` varchar(64) DEFAULT NULL COMMENT 'Author name if available.',
  `Email` varchar(128) DEFAULT NULL COMMENT 'Author email address if given.',
  `Notes` varchar(2048) DEFAULT NULL COMMENT 'Author notes and/or comments.',
  PRIMARY KEY (`AuthorsID`),
  UNIQUE KEY `NomDePlume` (`NomDePlume`)
) ENGINE=InnoDB AUTO_INCREMENT=1439 DEFAULT CHARSET=latin1 COMMENT='Author information (name, email etc).';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Chapters`
--

DROP TABLE IF EXISTS `Chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Chapters` (
  `ChaptersID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `SectionsID` int(11) NOT NULL COMMENT 'Link to Sections.',
  `Number` int(11) NOT NULL COMMENT 'Best of 2600 book chapter number.',
  `Name` varchar(64) NOT NULL COMMENT 'Best of 2600 book chapter name.',
  `Page` int(11) NOT NULL COMMENT 'Best of 2600 book page number.',
  PRIMARY KEY (`ChaptersID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COMMENT='Best of 2600 book chapters.';
/*!40101 SET character_set_client = @saved_cs_client */;

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

--
-- Table structure for table `KeywordXref`
--

DROP TABLE IF EXISTS `KeywordXref`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `KeywordXref` (
  `KeywordXrefID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `KeywordsID` int(11) NOT NULL COMMENT 'Link to keyword.',
  `ArticlesID` int(11) NOT NULL COMMENT 'Link to article.',
  PRIMARY KEY (`KeywordXrefID`)
) ENGINE=InnoDB AUTO_INCREMENT=3059 DEFAULT CHARSET=latin1 COMMENT='Keyword to Article cross reference.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Keywords`
--

DROP TABLE IF EXISTS `Keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Keywords` (
  `KeywordsID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `Keyword` varchar(32) NOT NULL COMMENT 'Keyword for searching.',
  `Notes` varchar(2048) DEFAULT NULL COMMENT 'Keyword notes and/or comments.',
  `WikipediaLink` varchar(256) DEFAULT NULL COMMENT 'Wikipedia.org link url.',
  PRIMARY KEY (`KeywordsID`),
  UNIQUE KEY `Keyword` (`Keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=latin1 COMMENT='Keywords for article searching.';
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`wepp`@`localhost`*/ /*!50003 TRIGGER trg_Links_Updated BEFORE UPDATE ON `Links`
FOR EACH ROW SET
NEW.Updated = (CASE WHEN NEW.Link = NEW.OriginalLink THEN 'N' ELSE 'Y' END) */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Sections`
--

DROP TABLE IF EXISTS `Sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sections` (
  `SectionsID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `Number` int(11) NOT NULL COMMENT 'Best of 2600 book section number.',
  `Name` varchar(64) NOT NULL COMMENT 'Best of 2600 book section name.',
  PRIMARY KEY (`SectionsID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Best of 2600 book sections.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TableEditor_HardcodedLookups`
--

DROP TABLE IF EXISTS `TableEditor_HardcodedLookups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TableEditor_HardcodedLookups` (
  `HardcodedLookupsID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `LookupNumber` int(11) NOT NULL COMMENT 'Lookup number to group hardcoded records by.',
  `HLID` varchar(64) NOT NULL COMMENT 'Hardcoded lookup ID.',
  `HLValue` varchar(64) NOT NULL COMMENT 'Hardcoded lookup Value.',
  `HLSort` int(11) NOT NULL COMMENT 'Hardcoded lookup sort value.',
  PRIMARY KEY (`HardcodedLookupsID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COMMENT='Hardcoded lookup id/values information.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TableEditor_Lookups`
--

DROP TABLE IF EXISTS `TableEditor_Lookups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TableEditor_Lookups` (
  `LookupsID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `TablesID` int(11) NOT NULL COMMENT 'Record ID of TableEditor_Tables table.',
  `ColumnName` varchar(64) NOT NULL COMMENT 'Column name for lookup.',
  `LookupTableName` varchar(64) DEFAULT NULL COMMENT 'Table name to use for lookup id/values.',
  `LookupColumnID` varchar(64) DEFAULT NULL COMMENT 'Column name of lookup ID.',
  `LookupColumnValue` varchar(64) DEFAULT NULL COMMENT 'Column name of lookup Value.',
  `LookupColumnWhere` varchar(512) DEFAULT NULL COMMENT 'Table lookup Where section of SQL statement.',
  `LookupColumnOrderBy` varchar(512) DEFAULT NULL COMMENT 'Table lookup up Order By section of SQL statement.',
  `LookupNumber` int(11) DEFAULT NULL COMMENT 'Lookup number in TableEditor_LookupValues for hardcoded lookup records.',
  PRIMARY KEY (`LookupsID`),
  UNIQUE KEY `TablesID_ColumnName` (`TablesID`,`ColumnName`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COMMENT='Per table/column information.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TableEditor_Tables`
--

DROP TABLE IF EXISTS `TableEditor_Tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TableEditor_Tables` (
  `TablesID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Automatic unique record id.',
  `TableName` varchar(64) NOT NULL COMMENT 'Table name that this record is for.',
  `OrderBy` varchar(512) DEFAULT NULL COMMENT 'Order By section of SQL select statement.',
  `NumberPerPage` int(11) NOT NULL DEFAULT 20 COMMENT 'Number of records to display per page.',
  `WhereFilter` varchar(512) DEFAULT NULL COMMENT 'Where section of SQL select statement.',
  `TableEditorTable` tinyint(1) DEFAULT NULL COMMENT 'True=TableEditor table, False=not a TableEditor table.',
  PRIMARY KEY (`TablesID`),
  UNIQUE KEY `TableName` (`TableName`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='Per table information.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `vw_ArticleInfo`
--

DROP TABLE IF EXISTS `vw_ArticleInfo`;
/*!50001 DROP VIEW IF EXISTS `vw_ArticleInfo`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_ArticleInfo` (
  `ArticlesID` tinyint NOT NULL,
  `IssuesID` tinyint NOT NULL,
  `Title` tinyint NOT NULL,
  `AuthorsID` tinyint NOT NULL,
  `Synopsis` tinyint NOT NULL,
  `ArticleNotes` tinyint NOT NULL,
  `Code` tinyint NOT NULL,
  `Page` tinyint NOT NULL,
  `Issue` tinyint NOT NULL,
  `Period` tinyint NOT NULL,
  `IssueYear` tinyint NOT NULL,
  `IssueNotes` tinyint NOT NULL,
  `CoverLink` tinyint NOT NULL,
  `BackCoverLink` tinyint NOT NULL,
  `Quarter` tinyint NOT NULL,
  `Volume` tinyint NOT NULL,
  `Quote` tinyint NOT NULL,
  `NomDePlume` tinyint NOT NULL,
  `Name` tinyint NOT NULL,
  `Email` tinyint NOT NULL,
  `AuthorNotes` tinyint NOT NULL,
  `Pages` tinyint NOT NULL,
  `PageTitle` tinyint NOT NULL,
  `PageIn2600Book` tinyint NOT NULL,
  `ArticlePages` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_ArticleKeywords`
--

DROP TABLE IF EXISTS `vw_ArticleKeywords`;
/*!50001 DROP VIEW IF EXISTS `vw_ArticleKeywords`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_ArticleKeywords` (
  `ArticlesID` tinyint NOT NULL,
  `KeywordsID` tinyint NOT NULL,
  `Keyword` tinyint NOT NULL,
  `Notes` tinyint NOT NULL,
  `WikipediaLink` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_ArticlesList`
--

DROP TABLE IF EXISTS `vw_ArticlesList`;
/*!50001 DROP VIEW IF EXISTS `vw_ArticlesList`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_ArticlesList` (
  `ArticlesID` tinyint NOT NULL,
  `Article` tinyint NOT NULL,
  `IssueYear` tinyint NOT NULL,
  `Quarter` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_Issues`
--

DROP TABLE IF EXISTS `vw_Issues`;
/*!50001 DROP VIEW IF EXISTS `vw_Issues`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_Issues` (
  `IssuesID` tinyint NOT NULL,
  `Issue` tinyint NOT NULL,
  `Period` tinyint NOT NULL,
  `IssueYear` tinyint NOT NULL,
  `Notes` tinyint NOT NULL,
  `CoverLink` tinyint NOT NULL,
  `BackCoverLink` tinyint NOT NULL,
  `Quarter` tinyint NOT NULL,
  `Volume` tinyint NOT NULL,
  `Quote` tinyint NOT NULL,
  `Pages` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_Search`
--

DROP TABLE IF EXISTS `vw_Search`;
/*!50001 DROP VIEW IF EXISTS `vw_Search`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_Search` (
  `IssuesID` tinyint NOT NULL,
  `IssueYear` tinyint NOT NULL,
  `Quarter` tinyint NOT NULL,
  `Period` tinyint NOT NULL,
  `ArticlesID` tinyint NOT NULL,
  `Title` tinyint NOT NULL,
  `KeywordsID` tinyint NOT NULL,
  `Keyword` tinyint NOT NULL,
  `AuthorsID` tinyint NOT NULL,
  `NomDePlume` tinyint NOT NULL,
  `Synopsis` tinyint NOT NULL,
  `Issue` tinyint NOT NULL,
  `Code` tinyint NOT NULL,
  `PageTitle` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database '2600'
--

--
-- Final view structure for view `vw_ArticleInfo`
--

/*!50001 DROP TABLE IF EXISTS `vw_ArticleInfo`*/;
/*!50001 DROP VIEW IF EXISTS `vw_ArticleInfo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`wepp`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_ArticleInfo` AS select `a`.`ArticlesID` AS `ArticlesID`,`a`.`IssuesID` AS `IssuesID`,`a`.`Title` AS `Title`,`a`.`AuthorsID` AS `AuthorsID`,`a`.`Synopsis` AS `Synopsis`,`a`.`Notes` AS `ArticleNotes`,`a`.`Code` AS `Code`,`a`.`Page` AS `Page`,concat(`i`.`Period`,`i`.`IssueYear`) AS `Issue`,`i`.`Period` AS `Period`,`i`.`IssueYear` AS `IssueYear`,`i`.`Notes` AS `IssueNotes`,`i`.`CoverLink` AS `CoverLink`,`i`.`BackCoverLink` AS `BackCoverLink`,`i`.`Quarter` AS `Quarter`,`i`.`Volume` AS `Volume`,`i`.`Quote` AS `Quote`,`au`.`NomDePlume` AS `NomDePlume`,`au`.`Name` AS `Name`,`au`.`Email` AS `Email`,`au`.`Notes` AS `AuthorNotes`,`i`.`Pages` AS `Pages`,`a`.`PageTitle` AS `PageTitle`,`a`.`PageIn2600Book` AS `PageIn2600Book`,`a`.`Pages` AS `ArticlePages` from ((`Articles` `a` join `Issues` `i`) join `Authors` `au`) where `a`.`IssuesID` = `i`.`IssuesID` and `a`.`AuthorsID` = `au`.`AuthorsID` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_ArticleKeywords`
--

/*!50001 DROP TABLE IF EXISTS `vw_ArticleKeywords`*/;
/*!50001 DROP VIEW IF EXISTS `vw_ArticleKeywords`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`wepp`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_ArticleKeywords` AS select `a`.`ArticlesID` AS `ArticlesID`,`k`.`KeywordsID` AS `KeywordsID`,`k`.`Keyword` AS `Keyword`,`k`.`Notes` AS `Notes`,`k`.`WikipediaLink` AS `WikipediaLink` from ((`Articles` `a` join `KeywordXref` `kx` on(`a`.`ArticlesID` = `kx`.`ArticlesID`)) join `Keywords` `k` on(`kx`.`KeywordsID` = `k`.`KeywordsID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_ArticlesList`
--

/*!50001 DROP TABLE IF EXISTS `vw_ArticlesList`*/;
/*!50001 DROP VIEW IF EXISTS `vw_ArticlesList`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`wepp`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_ArticlesList` AS select `a`.`ArticlesID` AS `ArticlesID`,concat(`i`.`Period`,`i`.`IssueYear`,'-',`a`.`Title`) AS `Article`,`i`.`IssueYear` AS `IssueYear`,`i`.`Quarter` AS `Quarter` from (`Articles` `a` left join `Issues` `i` on(`a`.`IssuesID` = `i`.`IssuesID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_Issues`
--

/*!50001 DROP TABLE IF EXISTS `vw_Issues`*/;
/*!50001 DROP VIEW IF EXISTS `vw_Issues`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`wepp`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_Issues` AS select `Issues`.`IssuesID` AS `IssuesID`,concat(`Issues`.`Period`,`Issues`.`IssueYear`) AS `Issue`,`Issues`.`Period` AS `Period`,`Issues`.`IssueYear` AS `IssueYear`,`Issues`.`Notes` AS `Notes`,`Issues`.`CoverLink` AS `CoverLink`,`Issues`.`BackCoverLink` AS `BackCoverLink`,`Issues`.`Quarter` AS `Quarter`,`Issues`.`Volume` AS `Volume`,`Issues`.`Quote` AS `Quote`,`Issues`.`Pages` AS `Pages` from `Issues` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_Search`
--

/*!50001 DROP TABLE IF EXISTS `vw_Search`*/;
/*!50001 DROP VIEW IF EXISTS `vw_Search`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`wepp`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_Search` AS select `i`.`IssuesID` AS `IssuesID`,`i`.`IssueYear` AS `IssueYear`,`i`.`Quarter` AS `Quarter`,`i`.`Period` AS `Period`,`a`.`ArticlesID` AS `ArticlesID`,`a`.`Title` AS `Title`,`k`.`KeywordsID` AS `KeywordsID`,`k`.`Keyword` AS `Keyword`,`au`.`AuthorsID` AS `AuthorsID`,`au`.`NomDePlume` AS `NomDePlume`,`a`.`Synopsis` AS `Synopsis`,concat(`i`.`IssueYear`,' ',`i`.`Period`) AS `Issue`,`a`.`Code` AS `Code`,`a`.`PageTitle` AS `PageTitle` from ((((`Articles` `a` left join `KeywordXref` `kx` on(`a`.`ArticlesID` = `kx`.`ArticlesID`)) left join `Keywords` `k` on(`kx`.`KeywordsID` = `k`.`KeywordsID`)) left join `Authors` `au` on(`a`.`AuthorsID` = `au`.`AuthorsID`)) join `Issues` `i` on(`i`.`IssuesID` = `a`.`IssuesID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-07 23:30:14
