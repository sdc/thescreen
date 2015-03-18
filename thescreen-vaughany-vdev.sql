
/*!40000 DROP DATABASE IF EXISTS `thescreen`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `thescreen` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `thescreen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aprilfools` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `fact` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `aprilfools` VALUES (1,'Meerkats like the colour purple more than any other colour.',0,1426702913,1426702913),(2,'Meerkats are the only quadruped mammals who make and sell their own cheese.',0,1426702913,1426702913),(3,'Meerkats have an odd number of teeth.',0,1426702913,1426702913),(4,'Meerkats have ears but choose not to wear them.',0,1426702913,1426702913),(5,'Meerkat fur is a natural source of nylon.',0,1426702913,1426702913),(6,'Meerkats produce more methane than cows.',0,1426702913,1426702913),(7,'Meerkats provide car insurance on behalf of weasels.',0,1426702913,1426702913),(8,'The \'We Buy Any Car .com\' dance routine and club smash-hit was choreographed by meerkats.',0,1426702913,1426702913),(9,'Meerkats live in all parts of the Calamari Desert in Botswana.',0,1426702913,1426702913),(10,'Meerkats hold the world record for the longest manned paper aeroplane flight.',0,1426702913,1426702913),(11,'Meerkats have individual cries for \'taxi\', \'hold the door, please\' and \'Yellow army!\'.',0,1426702913,1426702913),(12,'A meerkat scored the winning goal in the 1970 World Cup.',0,1426702913,1426702913),(13,'Aleksandr Orlov from comparethemeerkat.com lives in a riverside mansion in Stoke Gabriel.',0,1426702913,1426702913),(14,'If you see a meerkat go by, and then another identical meerkat goes by, it\'s a glitch in the Matrix.',0,1426702913,1426702913),(15,'Meerkats collect things.',0,1426702913,1426702913),(16,'Meerkats collect irrational numbers.',0,1426702913,1426702913),(17,'Meerkats know the locations of all the weapons of mass destruction.',0,1426702913,1426702913),(18,'In the 2014 budget, there was a 10% increase in duty on meerkats.',0,1426702913,1426702913),(19,'South Devon College owns five and a half meerkats.',0,1426702913,1426702913),(20,'Meerkats have already found the Higgs Boson in the LHC under Paignton Zoo, but are not telling.',0,1426702913,1426702913),(21,'Baby Oleg\'s aspiration is to work for confused.com. Alexander is not happy about this.',0,1426702913,1426702913),(22,'Type \"do a barrel roll\" into Google.',0,1426702913,1426702913),(23,'Meerkats are bioluminescent, but only on their birthdays.',0,1426702913,1426702913),(24,'Meerkats despise poor spelling, grammar and punctuation, more than expensive car insurance.',0,1426702913,1426702913),(25,'Compare The Meerkat has more computational power than the NSA, but use it for good, not evil.',0,1426702913,1426702913);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `item` (`item`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `config` VALUES (1,'page','1',1426702913,1426702913),(2,'status','2',1426702913,1426702962),(3,'refresh','300',1426702913,1426702913),(4,'rssfeed','http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml',1426702913,1426702913),(5,'showstopper','error!',1426702913,1426702913),(6,'specific_fig','aaa-random.png',1426702913,1426702913),(7,'changes','no',1426702913,1426702965),(8,'installed','2015-03-18T18:21:53+00:00',1426702913,1426702913),(9,'admin_password','1317dfa6a0c51245a1fbd37c6de9819ac469d2e5f71f70a42eec6c6181a30fa7',1426702945,1426702945);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `start` date NOT NULL,
  `text` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `events` VALUES (1,'2015-03-18','Disaster recovery testing.',0,1426702913,1426702913),(2,'2015-03-19','Disaster!.',0,1426702913,1426702913),(3,'2015-03-20','Recovery.',0,1426702913,1426702913),(4,'2015-03-21','Recovered disaster celebration.',0,1426702913,1426702913),(5,'2015-03-22','Hangover.',1,1426702913,1426702913);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `factoids` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fact` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `factoids` VALUES (1,'Default Factoid - edit or delete to say something witty or informative.',0,1426702913,1426702913),(2,'10 PRINT \'Second Factoid\' / 20 GOTO 10 / RUN.',0,1426702913,1426702913),(3,'Third, hidden Factoid. Will not appear unless un-hidden.',1,1426702913,1426702913);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `help` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `help` VALUES (1,'pagetype','Page Type','All page types are equal, but some page types are more equal than others.',1426702913,1426702913),(2,'status','Current Status','If there\'s a problem, change this and it will display on the main page.',1426702913,1426702913),(3,'events','Events','Help with events.',1426702913,1426702913),(4,'showstopper','Showstopper','Help with Showstopper.',1426702913,1426702913),(5,'rssfeed','RSS Feed','Help with RSS feed.',1426702913,1426702913),(6,'rssfeedpreset','RSS Feed Presets','Help with the RSS feed presets.',1426702913,1426702913),(7,'factoids','Factoids','Help with factoids.',1426702913,1426702913),(8,'person','Specific Person','Help with choosing a person.',1426702913,1426702913),(9,'refresh','Refresh','Help with the refresh rate setting.',1426702913,1426702913),(10,'stats','Stats','Help with stats (deprecated).',1426702913,1426702913),(11,'logs','Logs','Logs help (really?).',1426702913,1426702913);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `data` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `log` VALUES (1,'2015-03-03 16:47:53','site_installed','2015-03-18 18:21:55'),(2,'2015-03-18 18:22:25','set_config|admin_password|1317dfa6a0c51245a1fbd37c6de9819ac469d2e5f71f70a42eec6c6181a30fa7','2015-03-18 18:22:25'),(3,'2015-03-18 18:22:27','manage','2015-03-18 18:22:27'),(4,'2015-03-18 18:22:30','manage','2015-03-18 18:22:30'),(5,'2015-03-18 18:22:42','set_config|status|2','2015-03-18 18:22:42'),(6,'2015-03-18 18:22:42','set_config|changes|yes','2015-03-18 18:22:42'),(7,'2015-03-18 18:22:42','manage','2015-03-18 18:22:43'),(8,'2015-03-18 18:22:45','set_config|changes|no','2015-03-18 18:22:45'),(9,'2015-03-18 18:22:45','pageload_1','2015-03-18 18:22:45'),(10,'2015-03-18 18:22:45','img|rnd|fig-kev.png','2015-03-18 18:22:45'),(11,'2015-03-18 18:27:43','manage','2015-03-18 18:27:43'),(12,'2015-03-18 18:27:46','pageload_1','2015-03-18 18:27:46'),(13,'2015-03-18 18:27:46','img|rnd|fig-leigh.png','2015-03-18 18:27:46'),(14,'2015-03-18 18:32:43','manage','2015-03-18 18:32:43'),(15,'2015-03-18 18:32:47','pageload_1','2015-03-18 18:32:47'),(16,'2015-03-18 18:32:47','img|rnd|fig-paul-alt.png','2015-03-18 18:32:47'),(17,'2015-03-18 18:37:44','manage','2015-03-18 18:37:44'),(18,'2015-03-18 18:37:48','pageload_1','2015-03-18 18:37:48'),(19,'2015-03-18 18:37:48','img|rnd|fig-kelly.png','2015-03-18 18:37:48'),(20,'2015-03-18 18:42:44','manage','2015-03-18 18:42:44'),(21,'2015-03-18 18:42:48','pageload_1','2015-03-18 18:42:48'),(22,'2015-03-18 18:42:48','img|rnd|fig-kelly.png','2015-03-18 18:42:48'),(23,'2015-03-18 18:47:45','manage','2015-03-18 18:47:45'),(24,'2015-03-18 18:47:49','pageload_1','2015-03-18 18:47:49'),(25,'2015-03-18 18:47:49','img|rnd|fig-jeff.png','2015-03-18 18:47:49'),(26,'2015-03-18 18:52:46','manage','2015-03-18 18:52:46'),(27,'2015-03-18 18:52:50','pageload_1','2015-03-18 18:52:50'),(28,'2015-03-18 18:52:50','img|rnd|fig-kev.png','2015-03-18 18:52:50'),(29,'2015-03-18 18:57:46','manage','2015-03-18 18:57:46'),(30,'2015-03-18 18:57:51','pageload_1','2015-03-18 18:57:51'),(31,'2015-03-18 18:57:51','img|rnd|fig-mark.png','2015-03-18 18:57:51'),(32,'2015-03-18 19:02:47','manage','2015-03-18 19:02:47'),(33,'2015-03-18 19:02:52','pageload_1','2015-03-18 19:02:52'),(34,'2015-03-18 19:02:52','img|rnd|fig-kelly.png','2015-03-18 19:02:52'),(35,'2015-03-18 19:07:48','manage','2015-03-18 19:07:48'),(36,'2015-03-18 19:07:52','pageload_1','2015-03-18 19:07:52'),(37,'2015-03-18 19:07:52','img|rnd|fig-kelly.png','2015-03-18 19:07:53'),(38,'2015-03-18 19:12:48','manage','2015-03-18 19:12:48'),(39,'2015-03-18 19:12:53','pageload_1','2015-03-18 19:12:53'),(40,'2015-03-18 19:12:53','img|rnd|fig-paul-alt.png','2015-03-18 19:12:53'),(41,'2015-03-18 19:17:49','manage','2015-03-18 19:17:49'),(42,'2015-03-18 19:17:54','pageload_1','2015-03-18 19:17:54'),(43,'2015-03-18 19:17:54','img|rnd|fig-jeff.png','2015-03-18 19:17:54'),(44,'2015-03-18 19:22:49','manage','2015-03-18 19:22:49'),(45,'2015-03-18 19:22:56','pageload_1','2015-03-18 19:22:56'),(46,'2015-03-18 19:22:56','img|rnd|fig-leigh.png','2015-03-18 19:22:56'),(47,'2015-03-18 19:27:50','manage','2015-03-18 19:27:50'),(48,'2015-03-18 19:27:56','pageload_1','2015-03-18 19:27:56'),(49,'2015-03-18 19:27:56','img|rnd|fig-kev.png','2015-03-18 19:27:56'),(50,'2015-03-18 19:32:51','manage','2015-03-18 19:32:51'),(51,'2015-03-18 19:32:57','pageload_1','2015-03-18 19:32:57'),(52,'2015-03-18 19:32:57','img|rnd|fig-dave.png','2015-03-18 19:32:57'),(53,'2015-03-18 19:37:52','manage','2015-03-18 19:37:52'),(54,'2015-03-18 19:37:58','pageload_1','2015-03-18 19:37:58'),(55,'2015-03-18 19:37:58','img|rnd|fig-jeff.png','2015-03-18 19:37:58'),(56,'2015-03-18 19:42:53','manage','2015-03-18 19:42:53'),(57,'2015-03-18 19:42:59','pageload_1','2015-03-18 19:42:59'),(58,'2015-03-18 19:42:59','img|rnd|fig-kelly.png','2015-03-18 19:42:59'),(59,'2015-03-18 19:47:54','manage','2015-03-18 19:47:54'),(60,'2015-03-18 19:48:00','pageload_1','2015-03-18 19:48:00'),(61,'2015-03-18 19:48:00','img|rnd|fig-chris.png','2015-03-18 19:48:00'),(62,'2015-03-18 19:52:54','manage','2015-03-18 19:52:54'),(63,'2015-03-18 19:53:01','pageload_1','2015-03-18 19:53:01'),(64,'2015-03-18 19:53:01','img|rnd|fig-kelly.png','2015-03-18 19:53:01'),(65,'2015-03-18 19:57:55','manage','2015-03-18 19:57:55'),(66,'2015-03-18 19:58:01','pageload_1','2015-03-18 19:58:01'),(67,'2015-03-18 19:58:01','img|rnd|fig-kev.png','2015-03-18 19:58:01'),(68,'2015-03-18 20:02:55','manage','2015-03-18 20:02:55'),(69,'2015-03-18 20:03:02','pageload_1','2015-03-18 20:03:02'),(70,'2015-03-18 20:03:02','img|rnd|fig-paul-alt.png','2015-03-18 20:03:02'),(71,'2015-03-18 20:08:03','pageload_1','2015-03-18 20:08:03'),(72,'2015-03-18 20:08:03','img|rnd|fig-jeff.png','2015-03-18 20:08:03'),(73,'2015-03-18 20:08:05','manage','2015-03-18 20:08:05'),(74,'2015-03-18 20:13:04','pageload_1','2015-03-18 20:13:04'),(75,'2015-03-18 20:13:04','img|rnd|fig-leigh.png','2015-03-18 20:13:04'),(76,'2015-03-18 20:13:05','manage','2015-03-18 20:13:05'),(77,'2015-03-18 20:18:05','pageload_1','2015-03-18 20:18:05'),(78,'2015-03-18 20:18:05','img|rnd|fig-brian.png','2015-03-18 20:18:05'),(79,'2015-03-18 20:18:05','manage','2015-03-18 20:18:05'),(80,'2015-03-18 20:23:06','pageload_1','2015-03-18 20:23:06'),(81,'2015-03-18 20:23:06','manage','2015-03-18 20:23:06'),(82,'2015-03-18 20:23:06','img|rnd|fig-leigh.png','2015-03-18 20:23:06'),(83,'2015-03-18 20:28:07','manage','2015-03-18 20:28:07'),(84,'2015-03-18 20:28:07','pageload_1','2015-03-18 20:28:07'),(85,'2015-03-18 20:28:07','img|rnd|fig-bobby.png','2015-03-18 20:28:07'),(86,'2015-03-18 20:33:07','manage','2015-03-18 20:33:07'),(87,'2015-03-18 20:33:08','pageload_1','2015-03-18 20:33:08'),(88,'2015-03-18 20:33:08','img|rnd|fig-tim.png','2015-03-18 20:33:08'),(89,'2015-03-18 20:38:08','manage','2015-03-18 20:38:08'),(90,'2015-03-18 20:38:09','pageload_1','2015-03-18 20:38:09'),(91,'2015-03-18 20:38:09','img|rnd|fig-jeff.png','2015-03-18 20:38:09'),(92,'2015-03-18 20:43:08','manage','2015-03-18 20:43:08'),(93,'2015-03-18 20:43:09','pageload_1','2015-03-18 20:43:09'),(94,'2015-03-18 20:43:09','img|rnd|fig-jeff.png','2015-03-18 20:43:10'),(95,'2015-03-18 20:48:09','manage','2015-03-18 20:48:09'),(96,'2015-03-18 20:48:10','pageload_1','2015-03-18 20:48:10'),(97,'2015-03-18 20:48:10','img|rnd|fig-jeff.png','2015-03-18 20:48:10'),(98,'2015-03-18 20:53:10','manage','2015-03-18 20:53:10'),(99,'2015-03-18 20:53:11','pageload_1','2015-03-18 20:53:11'),(100,'2015-03-18 20:53:11','img|rnd|fig-tim.png','2015-03-18 20:53:11'),(101,'2015-03-18 20:58:10','manage','2015-03-18 20:58:10'),(102,'2015-03-18 20:58:12','pageload_1','2015-03-18 20:58:12'),(103,'2015-03-18 20:58:12','img|rnd|fig-jo.png','2015-03-18 20:58:12'),(104,'2015-03-18 21:03:11','manage','2015-03-18 21:03:11'),(105,'2015-03-18 21:03:13','pageload_1','2015-03-18 21:03:13'),(106,'2015-03-18 21:03:13','img|rnd|fig-brian.png','2015-03-18 21:03:13'),(107,'2015-03-18 21:08:11','manage','2015-03-18 21:08:11'),(108,'2015-03-18 21:08:13','pageload_1','2015-03-18 21:08:14'),(109,'2015-03-18 21:08:13','img|rnd|fig-kev.png','2015-03-18 21:08:14'),(110,'2015-03-18 21:13:12','manage','2015-03-18 21:13:12'),(111,'2015-03-18 21:13:14','pageload_1','2015-03-18 21:13:14'),(112,'2015-03-18 21:13:14','img|rnd|fig-brian.png','2015-03-18 21:13:14'),(113,'2015-03-18 21:18:12','manage','2015-03-18 21:18:12'),(114,'2015-03-18 21:18:15','pageload_1','2015-03-18 21:18:15'),(115,'2015-03-18 21:18:15','img|rnd|fig-chris.png','2015-03-18 21:18:15'),(116,'2015-03-18 21:23:13','manage','2015-03-18 21:23:13'),(117,'2015-03-18 21:23:16','pageload_1','2015-03-18 21:23:16'),(118,'2015-03-18 21:23:16','img|rnd|fig-tim.png','2015-03-18 21:23:16'),(119,'2015-03-18 21:28:13','manage','2015-03-18 21:28:13'),(120,'2015-03-18 21:28:17','pageload_1','2015-03-18 21:28:17'),(121,'2015-03-18 21:28:17','img|rnd|fig-tim.png','2015-03-18 21:28:17'),(122,'2015-03-18 21:33:14','manage','2015-03-18 21:33:14'),(123,'2015-03-18 21:33:18','pageload_1','2015-03-18 21:33:18'),(124,'2015-03-18 21:33:18','img|rnd|fig-chris.png','2015-03-18 21:33:18');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `background` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `refresh` smallint(4) unsigned NOT NULL DEFAULT '0',
  `priority` tinyint(2) unsigned NOT NULL,
  `defaultpage` tinyint(1) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `pages` VALUES (1,'standard','Standard','The standard page used 99% of the time.','standard.png',0,1,1,1426702913,1426702913),(2,'meeting3pmfriday','Friday 3pm Meeting','We\'re attending our regular Friday 3pm meeting and will be back about 4pm.','meeting_fri3pm.png',60,2,0,1426702913,1426702913),(3,'helpdeskclosed','Helpdesk Closed','The Technician has popped out and will be back shortly.','helpdeskclosed.png',60,3,0,1426702913,1426702913),(4,'showstopper','Showstopper!','For big messages!','showstopper.jpg',60,4,0,1426702913,1426702913),(5,'christmasparty','Christmas Party','We\'re having fun at our Christmas lunch. Back soon.','xmasparty.png',60,5,0,1426702913,1426702913),(6,'byebyejoy','Bye bye Joy!','Joy\'s leaving! :(','byejoy.png',60,6,0,1426702913,1426702913),(7,'meeting','General Meeting','We\'re away at a meeting and will be back soon.','meeting_general.png',15,7,0,1426702913,1426702913),(8,'training','Training','Generic \'training\' screen.','training.png',15,9,0,1426702913,1426702913),(9,'christmas','Christmas','Default Christmas template.','xmas.png',300,10,0,1426702913,1426702913),(10,'communityday2015','Community Day 2015','Community day 2015 flyer.','events.png',120,20,0,1426702913,1426702913),(11,'communityday2014','Community Day 2014','Community day 2014 poster.','communityday.png',120,20,0,1426702913,1426702913);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `priority` tinyint(2) unsigned NOT NULL,
  `defaultstatus` tinyint(1) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `status` VALUES (1,'ok','Okay','Everything is awesome.','green',1,1,1426702913,1426702913),(2,'minorproblem','Problems','We\'re experiencing problems with the server, which we are looking in to at the moment','amber',10,0,1426702913,1426702913),(3,'epicfail','Epic fail','Something has gone horribly, terribly wrong. It may even involve a zombie apocalypse.','red',20,0,1426702913,1426702913),(4,'maintenance','Planned maintenance','We are performing planned maintenance on some systems.<br><br>There may be brief periods of interruption.','blue',50,0,1426702913,1426702913),(5,'xmas','Merry Christmas!','Merry Christmas and Happy New Year!<br><br>(Everything is awesome, by the way.)','christmas',99,0,1426702913,1426702913);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `status_types` VALUES (1,'green','Green (OK)',1426702913,1426702913),(2,'amber','Amber (Alert)',1426702913,1426702913),(3,'red','Red (Warning)',1426702913,1426702913),(4,'blue','Blue (Information)',1426702913,1426702913),(5,'christmas','Christmas',1426702913,1426702913);
