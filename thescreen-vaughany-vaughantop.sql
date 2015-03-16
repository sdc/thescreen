
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
INSERT INTO `aprilfools` VALUES (1,'Meerkats like the colour purple more than any other colour.',0,1426546563,1426546563),(2,'Meerkats are the only quadruped mammals who make and sell their own cheese.',0,1426546563,1426546563),(3,'Meerkats have an odd number of teeth.',0,1426546563,1426546563),(4,'Meerkats have ears but choose not to wear them.',0,1426546563,1426546563),(5,'Meerkat fur is a natural source of nylon.',0,1426546563,1426546563),(6,'Meerkats produce more methane than cows.',0,1426546563,1426546563),(7,'Meerkats provide car insurance on behalf of weasels.',0,1426546563,1426546563),(8,'The \'We Buy Any Car .com\' dance routine and club smash-hit was choreographed by meerkats.',0,1426546563,1426546563),(9,'Meerkats live in all parts of the Calamari Desert in Botswana.',0,1426546563,1426546563),(10,'Meerkats hold the world record for the longest manned paper aeroplane flight.',0,1426546563,1426546563),(11,'Meerkats have individual cries for \'taxi\', \'hold the door, please\' and \'Yellow army!\'.',0,1426546563,1426546563),(12,'A meerkat scored the winning goal in the 1970 World Cup.',0,1426546563,1426546563),(13,'Aleksandr Orlov from comparethemeerkat.com lives in a riverside mansion in Stoke Gabriel.',0,1426546563,1426546563),(14,'If you see a meerkat go by, and then another identical meerkat goes by, it\'s a glitch in the Matrix.',0,1426546563,1426546563),(15,'Meerkats collect things.',0,1426546563,1426546563),(16,'Meerkats collect irrational numbers.',0,1426546563,1426546563),(17,'Meerkats know the locations of all the weapons of mass destruction.',0,1426546563,1426546563),(18,'In the 2014 budget, there was a 10% increase in duty on meerkats.',0,1426546563,1426546563),(19,'South Devon College owns five and a half meerkats.',0,1426546563,1426546563),(20,'Meerkats have already found the Higgs Boson in the LHC under Paignton Zoo, but are not telling.',0,1426546563,1426546563),(21,'Baby Oleg\'s aspiration is to work for confused.com. Alexander is not happy about this.',0,1426546563,1426546563),(22,'Type \"do a barrel roll\" into Google.',0,1426546563,1426546563),(23,'Meerkats are bioluminescent, but only on their birthdays.',0,1426546563,1426546563),(24,'Meerkats despise poor spelling, grammar and punctuation, more than expensive car insurance.',0,1426546563,1426546563),(25,'Compare The Meerkat has more computational power than the NSA, but use it for good, not evil.',0,1426546563,1426546563);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `config` VALUES (1,'page','1',1426546563,1426546563),(2,'status','1',1426546563,1426546563),(3,'refresh','300',1426546563,1426546563),(4,'rssfeed','http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml',1426546563,1426546563),(5,'showstopper','error!',1426546563,1426546563),(6,'specific_fig','aaa-random.png',1426546563,1426546563),(7,'changes','no',1426546563,1426546563),(8,'admin_password','1317dfa6a0c51245a1fbd37c6de9819ac469d2e5f71f70a42eec6c6181a30fa7',1426546577,1426546577);
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
INSERT INTO `events` VALUES (1,'2015-03-16','Disaster recovery testing.',0,1426546563,1426546563),(2,'2015-03-17','Disaster!.',0,1426546563,1426546563),(3,'2015-03-18','Recovery.',0,1426546563,1426546563),(4,'2015-03-19','Recovered disaster celebration.',0,1426546563,1426546563),(5,'2015-03-20','Hangover.',1,1426546563,1426546563);
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
INSERT INTO `factoids` VALUES (1,'Default Factoid - edit or delete to say something witty or informative.',0,1426546563,1426546563),(2,'10 PRINT \'Second Factoid\' / 20 GOTO 10 / RUN.',0,1426546563,1426546563),(3,'Third, hidden Factoid. Will not appear unless un-hidden.',1,1426546563,1426546563);
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
INSERT INTO `help` VALUES (1,'pagetype','Page Type','<p>All page types are equal, but some page types are more equal than others.</p>',1426546563,1426546602),(2,'status','Current Status','<p>If there\'s a problem, change this and it will display on the main page.</p>',1426546563,1426546688),(3,'events','Events','<p>Help with events.</p>',1426546563,1426546708),(4,'showstopper','Showstopper','<p>Help with Showstopper.</p>',1426546563,1426546729),(5,'rssfeed','RSS Feed','<p>Help with RSS feed.</p>',1426546563,1426546744),(6,'rssfeedpreset','RSS Feed Presets','<p>Help with the RSS feed presets.</p>',1426546563,1426546765),(7,'factoids','Factoids','<p>Help with factoids.</p>',1426546563,1426546783),(8,'person','Specific Person','<p>Help with choosing a person.</p>',1426546563,1426546801),(9,'refresh','Refresh','<p>Help with the refresh rate setting.</p>',1426546563,1426546832),(10,'stats','Stats','<p>Help with stats (<i>deprecated</i>).</p>',1426546563,1426546852),(11,'logs','Logs','<p>Logs help (really?).</p>',1426546563,1426546870);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `data` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `log` VALUES (1,'2015-03-03 16:47:53','site_installed','2015-03-16 22:56:09'),(2,'2015-03-16 22:56:17','set_config|admin_password|1317dfa6a0c51245a1fbd37c6de9819ac469d2e5f71f70a42eec6c6181a30fa7','2015-03-16 22:56:17'),(3,'2015-03-16 22:56:22','manage','2015-03-16 22:56:22'),(4,'2015-03-16 22:56:29','help','2015-03-16 22:56:29'),(5,'2015-03-16 22:56:42','edit_help|1','2015-03-16 22:56:42'),(6,'2015-03-16 22:56:42','manage','2015-03-16 22:56:42'),(7,'2015-03-16 22:57:20','manage','2015-03-16 22:57:20'),(8,'2015-03-16 22:57:58','help','2015-03-16 22:57:58'),(9,'2015-03-16 22:58:08','edit_help|2','2015-03-16 22:58:08'),(10,'2015-03-16 22:58:08','manage','2015-03-16 22:58:08'),(11,'2015-03-16 22:58:21','help','2015-03-16 22:58:21'),(12,'2015-03-16 22:58:28','edit_help|3','2015-03-16 22:58:28'),(13,'2015-03-16 22:58:28','manage','2015-03-16 22:58:28'),(14,'2015-03-16 22:58:42','help','2015-03-16 22:58:42'),(15,'2015-03-16 22:58:49','edit_help|4','2015-03-16 22:58:49'),(16,'2015-03-16 22:58:49','manage','2015-03-16 22:58:49'),(17,'2015-03-16 22:58:56','help','2015-03-16 22:58:56'),(18,'2015-03-16 22:59:04','edit_help|5','2015-03-16 22:59:04'),(19,'2015-03-16 22:59:04','manage','2015-03-16 22:59:04'),(20,'2015-03-16 22:59:15','help','2015-03-16 22:59:15'),(21,'2015-03-16 22:59:25','edit_help|6','2015-03-16 22:59:25'),(22,'2015-03-16 22:59:25','manage','2015-03-16 22:59:25'),(23,'2015-03-16 22:59:36','help','2015-03-16 22:59:36'),(24,'2015-03-16 22:59:43','edit_help|7','2015-03-16 22:59:43'),(25,'2015-03-16 22:59:43','manage','2015-03-16 22:59:43'),(26,'2015-03-16 22:59:51','help','2015-03-16 22:59:51'),(27,'2015-03-16 23:00:01','edit_help|8','2015-03-16 23:00:01'),(28,'2015-03-16 23:00:01','manage','2015-03-16 23:00:01'),(29,'2015-03-16 23:00:23','help','2015-03-16 23:00:23'),(30,'2015-03-16 23:00:32','edit_help|9','2015-03-16 23:00:32'),(31,'2015-03-16 23:00:32','manage','2015-03-16 23:00:32'),(32,'2015-03-16 23:00:39','help','2015-03-16 23:00:39'),(33,'2015-03-16 23:00:52','edit_help|10','2015-03-16 23:00:52'),(34,'2015-03-16 23:00:53','manage','2015-03-16 23:00:53'),(35,'2015-03-16 23:01:02','help','2015-03-16 23:01:02'),(36,'2015-03-16 23:01:10','edit_help|11','2015-03-16 23:01:10'),(37,'2015-03-16 23:01:11','manage','2015-03-16 23:01:11');
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
INSERT INTO `pages` VALUES (1,'standard','Standard','The standard page used 99% of the time.','standard.png',0,1,1,1426546563,1426546563),(2,'meeting3pmfriday','Friday 3pm Meeting','We\'re attending our regular Friday 3pm meeting and will be back about 4pm.','meeting_fri3pm.png',60,2,0,1426546563,1426546563),(3,'helpdeskclosed','Helpdesk Closed','The Technician has popped out and will be back shortly.','helpdeskclosed.png',60,3,0,1426546563,1426546563),(4,'showstopper','Showstopper!','For big messages!','showstopper.jpg',60,4,0,1426546563,1426546563),(5,'christmasparty','Christmas Party','We\'re having fun at our Christmas lunch. Back soon.','xmasparty.png',60,5,0,1426546563,1426546563),(6,'byebyejoy','Bye bye Joy!','Joy\'s leaving! :(','byejoy.png',60,6,0,1426546563,1426546563),(7,'meeting','General Meeting','We\'re away at a meeting and will be back soon.','meeting_general.png',15,7,0,1426546563,1426546563),(8,'training','Training','Generic \'training\' screen.','training.png',15,9,0,1426546563,1426546563),(9,'christmas','Christmas','Default Christmas template.','xmas.png',300,10,0,1426546563,1426546563),(10,'communityday2015','Community Day 2015','Community day 2015 flyer.','events.png',120,20,0,1426546563,1426546563),(11,'communityday2014','Community Day 2014','Community day 2014 poster.','communityday.png',120,20,0,1426546563,1426546563);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `priority` tinyint(2) unsigned NOT NULL,
  `defaultstatus` tinyint(1) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `status` VALUES (1,'ok','Okay','Everything is awesome.','green',1,1,1426546563,1426546563),(2,'minorproblem','Problems','We\'re experiencing problems with the server, which we are looking in to at the moment','amber',10,0,1426546563,1426546563),(3,'epicfail','Epic fail','Something has gone horribly, terribly wrong. It may even involve a zombie apocalypse.','red',20,0,1426546563,1426546563),(4,'maintenance','Planned maintenance','We are performing planned maintenance on some systems.<br><br>There may be brief periods of interruption.','blue',50,0,1426546563,1426546563),(5,'xmas','Merry Christmas!','Merry Christmas and Happy New Year!<br><br>(Everything is awesome, by the way.)','christmas',99,0,1426546563,1426546563);
