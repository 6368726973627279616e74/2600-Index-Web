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
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_Search` AS select `i`.`IssuesID` AS `IssuesID`,`i`.`IssueYear` AS `IssueYear`,`i`.`Quarter` AS `Quarter`,`i`.`Period` AS `Period`,`a`.`ArticlesID` AS `ArticlesID`,`a`.`Title` AS `Title`,`k`.`KeywordsID` AS `KeywordsID`,`k`.`Keyword` AS `Keyword`,`au`.`AuthorsID` AS `AuthorsID`,`au`.`NomDePlume` AS `NomDePlume`,`a`.`Synopsis` AS `Synopsis`,concat(`i`.`IssueYear`,' ',`i`.`Period`) AS `Issue`,`a`.`Code` AS `Code`,`a`.`PageTitle` AS `PageTitle` from ((((`Articles` `a` left join `KeywordXref` `kx` on((`a`.`ArticlesID` = `kx`.`ArticlesID`))) left join `Keywords` `k` on((`kx`.`KeywordsID` = `k`.`KeywordsID`))) left join `Authors` `au` on((`a`.`AuthorsID` = `au`.`AuthorsID`))) join `Issues` `i` on((`i`.`IssuesID` = `a`.`IssuesID`))) */;
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
