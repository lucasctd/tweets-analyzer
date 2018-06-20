-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: tweets
-- ------------------------------------------------------
-- Server version	5.5.5-10.2.15-MariaDB-10.2.15+maria~xenial-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary view structure for view `V_TOP5_HASHTAGS_PER_USER`
--

DROP TABLE IF EXISTS `V_TOP5_HASHTAGS_PER_USER`;
/*!50001 DROP VIEW IF EXISTS `V_TOP5_HASHTAGS_PER_USER`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `V_TOP5_HASHTAGS_PER_USER` AS SELECT 
 1 AS `screen_name`,
 1 AS `name`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `V_HASHTAGS_SECUNDARIAS`
--

DROP TABLE IF EXISTS `V_HASHTAGS_SECUNDARIAS`;
/*!50001 DROP VIEW IF EXISTS `V_HASHTAGS_SECUNDARIAS`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `V_HASHTAGS_SECUNDARIAS` AS SELECT 
 1 AS `name`,
 1 AS `text`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `V_QUANTIDADE_TWEETS_CANDIDATO`
--

DROP TABLE IF EXISTS `V_QUANTIDADE_TWEETS_CANDIDATO`;
/*!50001 DROP VIEW IF EXISTS `V_QUANTIDADE_TWEETS_CANDIDATO`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `V_QUANTIDADE_TWEETS_CANDIDATO` AS SELECT 
 1 AS `GERALDO_ALCKMIN`,
 1 AS `JAIR_BOLSONARO`,
 1 AS `MANUELA_DAVILA`,
 1 AS `MARINA_SILVA`,
 1 AS `CIRO_GOMES`,
 1 AS `JOAO_AMOEDO`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `V_LOCALIZACAO_TWEETS`
--

DROP TABLE IF EXISTS `V_LOCALIZACAO_TWEETS`;
/*!50001 DROP VIEW IF EXISTS `V_LOCALIZACAO_TWEETS`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `V_LOCALIZACAO_TWEETS` AS SELECT 
 1 AS `LATITUDE`,
 1 AS `LONGITUDE`,
 1 AS `tweet`,
 1 AS `LOCAL`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `V_TOP5_USERS`
--

DROP TABLE IF EXISTS `V_TOP5_USERS`;
/*!50001 DROP VIEW IF EXISTS `V_TOP5_USERS`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `V_TOP5_USERS` AS SELECT 
 1 AS `screen_name`,
 1 AS `tweet`,
 1 AS `qtd`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `V_TOP5_HASHTAGS_PER_USER`
--

/*!50001 DROP VIEW IF EXISTS `V_TOP5_HASHTAGS_PER_USER`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`homestead`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `V_TOP5_HASHTAGS_PER_USER` AS select `towner`.`screen_name` AS `screen_name`,`hu`.`name` AS `name` from ((`tweet` `tw` join `tweet_owner` `towner` on(`tw`.`owner_id` = `towner`.`id`)) join `hashtag_username` `hu` on(`tw`.`id` = `hu`.`tweet_id` and `hu`.`primary` = 1)) group by `towner`.`screen_name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `V_HASHTAGS_SECUNDARIAS`
--

/*!50001 DROP VIEW IF EXISTS `V_HASHTAGS_SECUNDARIAS`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`homestead`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `V_HASHTAGS_SECUNDARIAS` AS select `hu`.`name` AS `name`,`tw`.`text` AS `text` from (`hashtag_username` `hu` join `tweet` `tw` on(`hu`.`tweet_id` = `tw`.`id`)) where `hu`.`primary` = '0' */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `V_QUANTIDADE_TWEETS_CANDIDATO`
--

/*!50001 DROP VIEW IF EXISTS `V_QUANTIDADE_TWEETS_CANDIDATO`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`homestead`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `V_QUANTIDADE_TWEETS_CANDIDATO` AS select (select count(`tw`.`id`) from (`tweet` `tw` join `hashtag_username` `hu` on(`tw`.`id` = `hu`.`tweet_id`)) where `hu`.`name` in ('#alckmin','#geraldoalckmin','#alckmin2018','#alckminpresidente','#geraldoalckminpresidente')) AS `GERALDO_ALCKMIN`,(select count(`tw`.`id`) from (`tweet` `tw` join `hashtag_username` `hu` on(`tw`.`id` = `hu`.`tweet_id`)) where `hu`.`name` in ('#jairbolsonaro','#bolsonaro','#bolsonaro2018','#jairbolsonaropresidente','#bolsonaropresidente')) AS `JAIR_BOLSONARO`,(select count(`tw`.`id`) from (`tweet` `tw` join `hashtag_username` `hu` on(`tw`.`id` = `hu`.`tweet_id`)) where `hu`.`name` in ('#manueladavila','#manueladavila2018','#manuela2018','#manuela2018','#manuelapresidente')) AS `MANUELA_DAVILA`,(select count(`tw`.`id`) from (`tweet` `tw` join `hashtag_username` `hu` on(`tw`.`id` = `hu`.`tweet_id`)) where `hu`.`name` in ('#marina2018','#marinasilva','#marinasilva2018','#marinapresidente','#marinasilvapresidente')) AS `MARINA_SILVA`,(select count(`tw`.`id`) from (`tweet` `tw` join `hashtag_username` `hu` on(`tw`.`id` = `hu`.`tweet_id`)) where `hu`.`name` in ('#cirogomes2018','#ciro2018','#cirogomes','#ciropresidente','#cirogomespresidente')) AS `CIRO_GOMES`,(select count(`tw`.`id`) from (`tweet` `tw` join `hashtag_username` `hu` on(`tw`.`id` = `hu`.`tweet_id`)) where `hu`.`name` in ('#joaoamoedo','#joaoamoedo2018','#amoedo2018','#joaoamoedopresidente','#amoedopresidente')) AS `JOAO_AMOEDO` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `V_LOCALIZACAO_TWEETS`
--

/*!50001 DROP VIEW IF EXISTS `V_LOCALIZACAO_TWEETS`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`homestead`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `V_LOCALIZACAO_TWEETS` AS select case when `city`.`latitude` is not null then `city`.`latitude` else `stat`.`latitude` end AS `LATITUDE`,case when `city`.`longitude` is not null then `city`.`longitude` else `stat`.`longitude` end AS `LONGITUDE`,`tw`.`text` AS `tweet`,case when `city`.`latitude` is not null then `city`.`nome` else `stat`.`nome` end AS `LOCAL` from (((`tweet_owner` `tw_owner` join `tweet` `tw` on(`tw_owner`.`id` = `tw`.`owner_id`)) left join `city` on(`tw_owner`.`city_id` = `city`.`codigo`)) left join `br_state` `stat` on(`tw_owner`.`br_state_id` = `stat`.`codigo`)) where `city`.`latitude` is not null and `city`.`longitude` is not null or `stat`.`latitude` is not null and `stat`.`longitude` is not null */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `V_TOP5_USERS`
--

/*!50001 DROP VIEW IF EXISTS `V_TOP5_USERS`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`homestead`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `V_TOP5_USERS` AS select `towner`.`screen_name` AS `screen_name`,`tw`.`text` AS `tweet`,count(`tw`.`id`) AS `qtd` from (`tweet` `tw` join `tweet_owner` `towner` on(`tw`.`owner_id` = `towner`.`id`)) group by `towner`.`screen_name` order by count(`tw`.`id`) desc limit 5 */;
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

-- Dump completed on 2018-06-11 21:04:36
