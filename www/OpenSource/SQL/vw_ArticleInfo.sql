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
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_ArticleInfo` AS select `a`.`ArticlesID` AS `ArticlesID`,`a`.`IssuesID` AS `IssuesID`,`a`.`Title` AS `Title`,`a`.`AuthorsID` AS `AuthorsID`,`a`.`Synopsis` AS `Synopsis`,`a`.`Notes` AS `ArticleNotes`,`a`.`Code` AS `Code`,`a`.`Page` AS `Page`,concat(`i`.`Period`,`i`.`IssueYear`) AS `Issue`,`i`.`Period` AS `Period`,`i`.`IssueYear` AS `IssueYear`,`i`.`Notes` AS `IssueNotes`,`i`.`CoverLink` AS `CoverLink`,`i`.`BackCoverLink` AS `BackCoverLink`,`i`.`Quarter` AS `Quarter`,`i`.`Volume` AS `Volume`,`i`.`Quote` AS `Quote`,`au`.`NomDePlume` AS `NomDePlume`,`au`.`Name` AS `Name`,`au`.`Email` AS `Email`,`au`.`Notes` AS `AuthorNotes`,`i`.`Pages` AS `Pages`,`a`.`PageTitle` AS `PageTitle`,`a`.`PageIn2600Book` AS `PageIn2600Book`,`a`.`Pages` AS `ArticlePages` from ((`Articles` `a` join `Issues` `i`) join `Authors` `au`) where ((`a`.`IssuesID` = `i`.`IssuesID`) and (`a`.`AuthorsID` = `au`.`AuthorsID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-18 15:10:41
