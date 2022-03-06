-- MariaDB dump 10.19  Distrib 10.5.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: crm
-- ------------------------------------------------------
-- Server version	10.5.12-MariaDB-1:10.5.12+maria~focal

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
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filters`
--

DROP TABLE IF EXISTS `filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `filter` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`filter`)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filters`
--

LOCK TABLES `filters` WRITE;
/*!40000 ALTER TABLE `filters` DISABLE KEYS */;
/*!40000 ALTER TABLE `filters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history_operation`
--

DROP TABLE IF EXISTS `history_operation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history_operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `volume` decimal(13,2) DEFAULT NULL,
  `price` decimal(13,2) DEFAULT NULL,
  `total` decimal(13,2) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `is_debt` int(11) DEFAULT NULL,
  `confirmed_data` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history_operation`
--

LOCK TABLES `history_operation` WRITE;
/*!40000 ALTER TABLE `history_operation` DISABLE KEYS */;
/*!40000 ALTER TABLE `history_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icons`
--

DROP TABLE IF EXISTS `icons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1608 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icons`
--

LOCK TABLES `icons` WRITE;
/*!40000 ALTER TABLE `icons` DISABLE KEYS */;
INSERT INTO `icons` VALUES (1,'fab','500px'),(2,'fab','accessible-icon'),(3,'fab','accusoft'),(4,'fab','acquisitions-incorporated'),(5,'fas','address-book'),(6,'far','address-book'),(7,'fas','address-card'),(8,'far','address-card'),(9,'fas','adjust'),(10,'fab','adn'),(11,'fab','adversal'),(12,'fab','affiliatetheme'),(13,'fas','air-freshener'),(14,'fab','airbnb'),(15,'fab','algolia'),(16,'fas','align-center'),(17,'fas','align-justify'),(18,'fas','align-left'),(19,'fas','align-right'),(20,'fab','alipay'),(21,'fas','allergies'),(22,'fab','amazon'),(23,'fab','amazon-pay'),(24,'fas','ambulance'),(25,'fas','american-sign-language-interpreting'),(26,'fab','amilia'),(27,'fas','anchor'),(28,'fab','android'),(29,'fab','angellist'),(30,'fas','angle-double-down'),(31,'fas','angle-double-left'),(32,'fas','angle-double-right'),(33,'fas','angle-double-up'),(34,'fas','angle-down'),(35,'fas','angle-left'),(36,'fas','angle-right'),(37,'fas','angle-up'),(38,'fas','angry'),(39,'far','angry'),(40,'fab','angrycreative'),(41,'fab','angular'),(42,'fas','ankh'),(43,'fab','app-store'),(44,'fab','app-store-ios'),(45,'fab','apper'),(46,'fab','apple'),(47,'fas','apple-alt'),(48,'fab','apple-pay'),(49,'fas','archive'),(50,'fas','archway'),(51,'fas','arrow-alt-circle-down'),(52,'far','arrow-alt-circle-down'),(53,'fas','arrow-alt-circle-left'),(54,'far','arrow-alt-circle-left'),(55,'fas','arrow-alt-circle-right'),(56,'far','arrow-alt-circle-right'),(57,'fas','arrow-alt-circle-up'),(58,'far','arrow-alt-circle-up'),(59,'fas','arrow-circle-down'),(60,'fas','arrow-circle-left'),(61,'fas','arrow-circle-right'),(62,'fas','arrow-circle-up'),(63,'fas','arrow-down'),(64,'fas','arrow-left'),(65,'fas','arrow-right'),(66,'fas','arrow-up'),(67,'fas','arrows-alt'),(68,'fas','arrows-alt-h'),(69,'fas','arrows-alt-v'),(70,'fab','artstation'),(71,'fas','assistive-listening-systems'),(72,'fas','asterisk'),(73,'fab','asymmetrik'),(74,'fas','at'),(75,'fas','atlas'),(76,'fab','atlassian'),(77,'fas','atom'),(78,'fab','audible'),(79,'fas','audio-description'),(80,'fab','autoprefixer'),(81,'fab','avianex'),(82,'fab','aviato'),(83,'fas','award'),(84,'fab','aws'),(85,'fas','baby'),(86,'fas','baby-carriage'),(87,'fas','backspace'),(88,'fas','backward'),(89,'fas','bacon'),(90,'fas','bacteria'),(91,'fas','bacterium'),(92,'fas','bahai'),(93,'fas','balance-scale'),(94,'fas','balance-scale-left'),(95,'fas','balance-scale-right'),(96,'fas','ban'),(97,'fas','band-aid'),(98,'fab','bandcamp'),(99,'fas','barcode'),(100,'fas','bars'),(101,'fas','baseball-ball'),(102,'fas','basketball-ball'),(103,'fas','bath'),(104,'fas','battery-empty'),(105,'fas','battery-full'),(106,'fas','battery-half'),(107,'fas','battery-quarter'),(108,'fas','battery-three-quarters'),(109,'fab','battle-net'),(110,'fas','bed'),(111,'fas','beer'),(112,'fab','behance'),(113,'fab','behance-square'),(114,'fas','bell'),(115,'far','bell'),(116,'fas','bell-slash'),(117,'far','bell-slash'),(118,'fas','bezier-curve'),(119,'fas','bible'),(120,'fas','bicycle'),(121,'fas','biking'),(122,'fab','bimobject'),(123,'fas','binoculars'),(124,'fas','biohazard'),(125,'fas','birthday-cake'),(126,'fab','bitbucket'),(127,'fab','bitcoin'),(128,'fab','bity'),(129,'fab','black-tie'),(130,'fab','blackberry'),(131,'fas','blender'),(132,'fas','blender-phone'),(133,'fas','blind'),(134,'fas','blog'),(135,'fab','blogger'),(136,'fab','blogger-b'),(137,'fab','bluetooth'),(138,'fab','bluetooth-b'),(139,'fas','bold'),(140,'fas','bolt'),(141,'fas','bomb'),(142,'fas','bone'),(143,'fas','bong'),(144,'fas','book'),(145,'fas','book-dead'),(146,'fas','book-medical'),(147,'fas','book-open'),(148,'fas','book-reader'),(149,'fas','bookmark'),(150,'far','bookmark'),(151,'fab','bootstrap'),(152,'fas','border-all'),(153,'fas','border-none'),(154,'fas','border-style'),(155,'fas','bowling-ball'),(156,'fas','box'),(157,'fas','box-open'),(158,'fas','box-tissue'),(159,'fas','boxes'),(160,'fas','braille'),(161,'fas','brain'),(162,'fas','bread-slice'),(163,'fas','briefcase'),(164,'fas','briefcase-medical'),(165,'fas','broadcast-tower'),(166,'fas','broom'),(167,'fas','brush'),(168,'fab','btc'),(169,'fab','buffer'),(170,'fas','bug'),(171,'fas','building'),(172,'far','building'),(173,'fas','bullhorn'),(174,'fas','bullseye'),(175,'fas','burn'),(176,'fab','buromobelexperte'),(177,'fas','bus'),(178,'fas','bus-alt'),(179,'fas','business-time'),(180,'fab','buy-n-large'),(181,'fab','buysellads'),(182,'fas','calculator'),(183,'fas','calendar'),(184,'far','calendar'),(185,'fas','calendar-alt'),(186,'far','calendar-alt'),(187,'fas','calendar-check'),(188,'far','calendar-check'),(189,'fas','calendar-day'),(190,'fas','calendar-minus'),(191,'far','calendar-minus'),(192,'fas','calendar-plus'),(193,'far','calendar-plus'),(194,'fas','calendar-times'),(195,'far','calendar-times'),(196,'fas','calendar-week'),(197,'fas','camera'),(198,'fas','camera-retro'),(199,'fas','campground'),(200,'fab','canadian-maple-leaf'),(201,'fas','candy-cane'),(202,'fas','cannabis'),(203,'fas','capsules'),(204,'fas','car'),(205,'fas','car-alt'),(206,'fas','car-battery'),(207,'fas','car-crash'),(208,'fas','car-side'),(209,'fas','caravan'),(210,'fas','caret-down'),(211,'fas','caret-left'),(212,'fas','caret-right'),(213,'fas','caret-square-down'),(214,'far','caret-square-down'),(215,'fas','caret-square-left'),(216,'far','caret-square-left'),(217,'fas','caret-square-right'),(218,'far','caret-square-right'),(219,'fas','caret-square-up'),(220,'far','caret-square-up'),(221,'fas','caret-up'),(222,'fas','carrot'),(223,'fas','cart-arrow-down'),(224,'fas','cart-plus'),(225,'fas','cash-register'),(226,'fas','cat'),(227,'fab','cc-amazon-pay'),(228,'fab','cc-amex'),(229,'fab','cc-apple-pay'),(230,'fab','cc-diners-club'),(231,'fab','cc-discover'),(232,'fab','cc-jcb'),(233,'fab','cc-mastercard'),(234,'fab','cc-paypal'),(235,'fab','cc-stripe'),(236,'fab','cc-visa'),(237,'fab','centercode'),(238,'fab','centos'),(239,'fas','certificate'),(240,'fas','chair'),(241,'fas','chalkboard'),(242,'fas','chalkboard-teacher'),(243,'fas','charging-station'),(244,'fas','chart-area'),(245,'fas','chart-bar'),(246,'far','chart-bar'),(247,'fas','chart-line'),(248,'fas','chart-pie'),(249,'fas','check'),(250,'fas','check-circle'),(251,'far','check-circle'),(252,'fas','check-double'),(253,'fas','check-square'),(254,'far','check-square'),(255,'fas','cheese'),(256,'fas','chess'),(257,'fas','chess-bishop'),(258,'fas','chess-board'),(259,'fas','chess-king'),(260,'fas','chess-knight'),(261,'fas','chess-pawn'),(262,'fas','chess-queen'),(263,'fas','chess-rook'),(264,'fas','chevron-circle-down'),(265,'fas','chevron-circle-left'),(266,'fas','chevron-circle-right'),(267,'fas','chevron-circle-up'),(268,'fas','chevron-down'),(269,'fas','chevron-left'),(270,'fas','chevron-right'),(271,'fas','chevron-up'),(272,'fas','child'),(273,'fab','chrome'),(274,'fab','chromecast'),(275,'fas','church'),(276,'fas','circle'),(277,'far','circle'),(278,'fas','circle-notch'),(279,'fas','city'),(280,'fas','clinic-medical'),(281,'fas','clipboard'),(282,'far','clipboard'),(283,'fas','clipboard-check'),(284,'fas','clipboard-list'),(285,'fas','clock'),(286,'far','clock'),(287,'fas','clone'),(288,'far','clone'),(289,'fas','closed-captioning'),(290,'far','closed-captioning'),(291,'fas','cloud'),(292,'fas','cloud-download-alt'),(293,'fas','cloud-meatball'),(294,'fas','cloud-moon'),(295,'fas','cloud-moon-rain'),(296,'fas','cloud-rain'),(297,'fas','cloud-showers-heavy'),(298,'fas','cloud-sun'),(299,'fas','cloud-sun-rain'),(300,'fas','cloud-upload-alt'),(301,'fab','cloudflare'),(302,'fab','cloudscale'),(303,'fab','cloudsmith'),(304,'fab','cloudversify'),(305,'fas','cocktail'),(306,'fas','code'),(307,'fas','code-branch'),(308,'fab','codepen'),(309,'fab','codiepie'),(310,'fas','coffee'),(311,'fas','cog'),(312,'fas','cogs'),(313,'fas','coins'),(314,'fas','columns'),(315,'fas','comment'),(316,'far','comment'),(317,'fas','comment-alt'),(318,'far','comment-alt'),(319,'fas','comment-dollar'),(320,'fas','comment-dots'),(321,'far','comment-dots'),(322,'fas','comment-medical'),(323,'fas','comment-slash'),(324,'fas','comments'),(325,'far','comments'),(326,'fas','comments-dollar'),(327,'fas','compact-disc'),(328,'fas','compass'),(329,'far','compass'),(330,'fas','compress'),(331,'fas','compress-alt'),(332,'fas','compress-arrows-alt'),(333,'fas','concierge-bell'),(334,'fab','confluence'),(335,'fab','connectdevelop'),(336,'fab','contao'),(337,'fas','cookie'),(338,'fas','cookie-bite'),(339,'fas','copy'),(340,'far','copy'),(341,'fas','copyright'),(342,'far','copyright'),(343,'fab','cotton-bureau'),(344,'fas','couch'),(345,'fab','cpanel'),(346,'fab','creative-commons'),(347,'fab','creative-commons-by'),(348,'fab','creative-commons-nc'),(349,'fab','creative-commons-nc-eu'),(350,'fab','creative-commons-nc-jp'),(351,'fab','creative-commons-nd'),(352,'fab','creative-commons-pd'),(353,'fab','creative-commons-pd-alt'),(354,'fab','creative-commons-remix'),(355,'fab','creative-commons-sa'),(356,'fab','creative-commons-sampling'),(357,'fab','creative-commons-sampling-plus'),(358,'fab','creative-commons-share'),(359,'fab','creative-commons-zero'),(360,'fas','credit-card'),(361,'far','credit-card'),(362,'fab','critical-role'),(363,'fas','crop'),(364,'fas','crop-alt'),(365,'fas','cross'),(366,'fas','crosshairs'),(367,'fas','crow'),(368,'fas','crown'),(369,'fas','crutch'),(370,'fab','css3'),(371,'fab','css3-alt'),(372,'fas','cube'),(373,'fas','cubes'),(374,'fas','cut'),(375,'fab','cuttlefish'),(376,'fab','d-and-d'),(377,'fab','d-and-d-beyond'),(378,'fab','dailymotion'),(379,'fab','dashcube'),(380,'fas','database'),(381,'fas','deaf'),(382,'fab','deezer'),(383,'fab','delicious'),(384,'fas','democrat'),(385,'fab','deploydog'),(386,'fab','deskpro'),(387,'fas','desktop'),(388,'fab','dev'),(389,'fab','deviantart'),(390,'fas','dharmachakra'),(391,'fab','dhl'),(392,'fas','diagnoses'),(393,'fab','diaspora'),(394,'fas','dice'),(395,'fas','dice-d20'),(396,'fas','dice-d6'),(397,'fas','dice-five'),(398,'fas','dice-four'),(399,'fas','dice-one'),(400,'fas','dice-six'),(401,'fas','dice-three'),(402,'fas','dice-two'),(403,'fab','digg'),(404,'fab','digital-ocean'),(405,'fas','digital-tachograph'),(406,'fas','directions'),(407,'fab','discord'),(408,'fab','discourse'),(409,'fas','disease'),(410,'fas','divide'),(411,'fas','dizzy'),(412,'far','dizzy'),(413,'fas','dna'),(414,'fab','dochub'),(415,'fab','docker'),(416,'fas','dog'),(417,'fas','dollar-sign'),(418,'fas','dolly'),(419,'fas','dolly-flatbed'),(420,'fas','donate'),(421,'fas','door-closed'),(422,'fas','door-open'),(423,'fas','dot-circle'),(424,'far','dot-circle'),(425,'fas','dove'),(426,'fas','download'),(427,'fab','draft2digital'),(428,'fas','drafting-compass'),(429,'fas','dragon'),(430,'fas','draw-polygon'),(431,'fab','dribbble'),(432,'fab','dribbble-square'),(433,'fab','dropbox'),(434,'fas','drum'),(435,'fas','drum-steelpan'),(436,'fas','drumstick-bite'),(437,'fab','drupal'),(438,'fas','dumbbell'),(439,'fas','dumpster'),(440,'fas','dumpster-fire'),(441,'fas','dungeon'),(442,'fab','dyalog'),(443,'fab','earlybirds'),(444,'fab','ebay'),(445,'fab','edge'),(446,'fab','edge-legacy'),(447,'fas','edit'),(448,'far','edit'),(449,'fas','egg'),(450,'fas','eject'),(451,'fab','elementor'),(452,'fas','ellipsis-h'),(453,'fas','ellipsis-v'),(454,'fab','ello'),(455,'fab','ember'),(456,'fab','empire'),(457,'fas','envelope'),(458,'far','envelope'),(459,'fas','envelope-open'),(460,'far','envelope-open'),(461,'fas','envelope-open-text'),(462,'fas','envelope-square'),(463,'fab','envira'),(464,'fas','equals'),(465,'fas','eraser'),(466,'fab','erlang'),(467,'fab','ethereum'),(468,'fas','ethernet'),(469,'fab','etsy'),(470,'fas','euro-sign'),(471,'fab','evernote'),(472,'fas','exchange-alt'),(473,'fas','exclamation'),(474,'fas','exclamation-circle'),(475,'fas','exclamation-triangle'),(476,'fas','expand'),(477,'fas','expand-alt'),(478,'fas','expand-arrows-alt'),(479,'fab','expeditedssl'),(480,'fas','external-link-alt'),(481,'fas','external-link-square-alt'),(482,'fas','eye'),(483,'far','eye'),(484,'fas','eye-dropper'),(485,'fas','eye-slash'),(486,'far','eye-slash'),(487,'fab','facebook'),(488,'fab','facebook-f'),(489,'fab','facebook-messenger'),(490,'fab','facebook-square'),(491,'fas','fan'),(492,'fab','fantasy-flight-games'),(493,'fas','fast-backward'),(494,'fas','fast-forward'),(495,'fas','faucet'),(496,'fas','fax'),(497,'fas','feather'),(498,'fas','feather-alt'),(499,'fab','fedex'),(500,'fab','fedora'),(501,'fas','female'),(502,'fas','fighter-jet'),(503,'fab','figma'),(504,'fas','file'),(505,'far','file'),(506,'fas','file-alt'),(507,'far','file-alt'),(508,'fas','file-archive'),(509,'far','file-archive'),(510,'fas','file-audio'),(511,'far','file-audio'),(512,'fas','file-code'),(513,'far','file-code'),(514,'fas','file-contract'),(515,'fas','file-csv'),(516,'fas','file-download'),(517,'fas','file-excel'),(518,'far','file-excel'),(519,'fas','file-export'),(520,'fas','file-image'),(521,'far','file-image'),(522,'fas','file-import'),(523,'fas','file-invoice'),(524,'fas','file-invoice-dollar'),(525,'fas','file-medical'),(526,'fas','file-medical-alt'),(527,'fas','file-pdf'),(528,'far','file-pdf'),(529,'fas','file-powerpoint'),(530,'far','file-powerpoint'),(531,'fas','file-prescription'),(532,'fas','file-signature'),(533,'fas','file-upload'),(534,'fas','file-video'),(535,'far','file-video'),(536,'fas','file-word'),(537,'far','file-word'),(538,'fas','fill'),(539,'fas','fill-drip'),(540,'fas','film'),(541,'fas','filter'),(542,'fas','fingerprint'),(543,'fas','fire'),(544,'fas','fire-alt'),(545,'fas','fire-extinguisher'),(546,'fab','firefox'),(547,'fab','firefox-browser'),(548,'fas','first-aid'),(549,'fab','first-order'),(550,'fab','first-order-alt'),(551,'fab','firstdraft'),(552,'fas','fish'),(553,'fas','fist-raised'),(554,'fas','flag'),(555,'far','flag'),(556,'fas','flag-checkered'),(557,'fas','flag-usa'),(558,'fas','flask'),(559,'fab','flickr'),(560,'fab','flipboard'),(561,'fas','flushed'),(562,'far','flushed'),(563,'fab','fly'),(564,'fas','folder'),(565,'far','folder'),(566,'fas','folder-minus'),(567,'fas','folder-open'),(568,'far','folder-open'),(569,'fas','folder-plus'),(570,'fas','font'),(571,'fab','font-awesome'),(572,'fab','font-awesome-alt'),(573,'fab','font-awesome-flag'),(574,'fab','fonticons'),(575,'fab','fonticons-fi'),(576,'fas','football-ball'),(577,'fab','fort-awesome'),(578,'fab','fort-awesome-alt'),(579,'fab','forumbee'),(580,'fas','forward'),(581,'fab','foursquare'),(582,'fab','free-code-camp'),(583,'fab','freebsd'),(584,'fas','frog'),(585,'fas','frown'),(586,'far','frown'),(587,'fas','frown-open'),(588,'far','frown-open'),(589,'fab','fulcrum'),(590,'fas','funnel-dollar'),(591,'fas','futbol'),(592,'far','futbol'),(593,'fab','galactic-republic'),(594,'fab','galactic-senate'),(595,'fas','gamepad'),(596,'fas','gas-pump'),(597,'fas','gavel'),(598,'fas','gem'),(599,'far','gem'),(600,'fas','genderless'),(601,'fab','get-pocket'),(602,'fab','gg'),(603,'fab','gg-circle'),(604,'fas','ghost'),(605,'fas','gift'),(606,'fas','gifts'),(607,'fab','git'),(608,'fab','git-alt'),(609,'fab','git-square'),(610,'fab','github'),(611,'fab','github-alt'),(612,'fab','github-square'),(613,'fab','gitkraken'),(614,'fab','gitlab'),(615,'fab','gitter'),(616,'fas','glass-cheers'),(617,'fas','glass-martini'),(618,'fas','glass-martini-alt'),(619,'fas','glass-whiskey'),(620,'fas','glasses'),(621,'fab','glide'),(622,'fab','glide-g'),(623,'fas','globe'),(624,'fas','globe-africa'),(625,'fas','globe-americas'),(626,'fas','globe-asia'),(627,'fas','globe-europe'),(628,'fab','gofore'),(629,'fas','golf-ball'),(630,'fab','goodreads'),(631,'fab','goodreads-g'),(632,'fab','google'),(633,'fab','google-drive'),(634,'fab','google-pay'),(635,'fab','google-play'),(636,'fab','google-plus'),(637,'fab','google-plus-g'),(638,'fab','google-plus-square'),(639,'fab','google-wallet'),(640,'fas','gopuram'),(641,'fas','graduation-cap'),(642,'fab','gratipay'),(643,'fab','grav'),(644,'fas','greater-than'),(645,'fas','greater-than-equal'),(646,'fas','grimace'),(647,'far','grimace'),(648,'fas','grin'),(649,'far','grin'),(650,'fas','grin-alt'),(651,'far','grin-alt'),(652,'fas','grin-beam'),(653,'far','grin-beam'),(654,'fas','grin-beam-sweat'),(655,'far','grin-beam-sweat'),(656,'fas','grin-hearts'),(657,'far','grin-hearts'),(658,'fas','grin-squint'),(659,'far','grin-squint'),(660,'fas','grin-squint-tears'),(661,'far','grin-squint-tears'),(662,'fas','grin-stars'),(663,'far','grin-stars'),(664,'fas','grin-tears'),(665,'far','grin-tears'),(666,'fas','grin-tongue'),(667,'far','grin-tongue'),(668,'fas','grin-tongue-squint'),(669,'far','grin-tongue-squint'),(670,'fas','grin-tongue-wink'),(671,'far','grin-tongue-wink'),(672,'fas','grin-wink'),(673,'far','grin-wink'),(674,'fas','grip-horizontal'),(675,'fas','grip-lines'),(676,'fas','grip-lines-vertical'),(677,'fas','grip-vertical'),(678,'fab','gripfire'),(679,'fab','grunt'),(680,'fab','guilded'),(681,'fas','guitar'),(682,'fab','gulp'),(683,'fas','h-square'),(684,'fab','hacker-news'),(685,'fab','hacker-news-square'),(686,'fab','hackerrank'),(687,'fas','hamburger'),(688,'fas','hammer'),(689,'fas','hamsa'),(690,'fas','hand-holding'),(691,'fas','hand-holding-heart'),(692,'fas','hand-holding-medical'),(693,'fas','hand-holding-usd'),(694,'fas','hand-holding-water'),(695,'fas','hand-lizard'),(696,'far','hand-lizard'),(697,'fas','hand-middle-finger'),(698,'fas','hand-paper'),(699,'far','hand-paper'),(700,'fas','hand-peace'),(701,'far','hand-peace'),(702,'fas','hand-point-down'),(703,'far','hand-point-down'),(704,'fas','hand-point-left'),(705,'far','hand-point-left'),(706,'fas','hand-point-right'),(707,'far','hand-point-right'),(708,'fas','hand-point-up'),(709,'far','hand-point-up'),(710,'fas','hand-pointer'),(711,'far','hand-pointer'),(712,'fas','hand-rock'),(713,'far','hand-rock'),(714,'fas','hand-scissors'),(715,'far','hand-scissors'),(716,'fas','hand-sparkles'),(717,'fas','hand-spock'),(718,'far','hand-spock'),(719,'fas','hands'),(720,'fas','hands-helping'),(721,'fas','hands-wash'),(722,'fas','handshake'),(723,'far','handshake'),(724,'fas','handshake-alt-slash'),(725,'fas','handshake-slash'),(726,'fas','hanukiah'),(727,'fas','hard-hat'),(728,'fas','hashtag'),(729,'fas','hat-cowboy'),(730,'fas','hat-cowboy-side'),(731,'fas','hat-wizard'),(732,'fas','hdd'),(733,'far','hdd'),(734,'fas','head-side-cough'),(735,'fas','head-side-cough-slash'),(736,'fas','head-side-mask'),(737,'fas','head-side-virus'),(738,'fas','heading'),(739,'fas','headphones'),(740,'fas','headphones-alt'),(741,'fas','headset'),(742,'fas','heart'),(743,'far','heart'),(744,'fas','heart-broken'),(745,'fas','heartbeat'),(746,'fas','helicopter'),(747,'fas','highlighter'),(748,'fas','hiking'),(749,'fas','hippo'),(750,'fab','hips'),(751,'fab','hire-a-helper'),(752,'fas','history'),(753,'fab','hive'),(754,'fas','hockey-puck'),(755,'fas','holly-berry'),(756,'fas','home'),(757,'fab','hooli'),(758,'fab','hornbill'),(759,'fas','horse'),(760,'fas','horse-head'),(761,'fas','hospital'),(762,'far','hospital'),(763,'fas','hospital-alt'),(764,'fas','hospital-symbol'),(765,'fas','hospital-user'),(766,'fas','hot-tub'),(767,'fas','hotdog'),(768,'fas','hotel'),(769,'fab','hotjar'),(770,'fas','hourglass'),(771,'far','hourglass'),(772,'fas','hourglass-end'),(773,'fas','hourglass-half'),(774,'fas','hourglass-start'),(775,'fas','house-damage'),(776,'fas','house-user'),(777,'fab','houzz'),(778,'fas','hryvnia'),(779,'fab','html5'),(780,'fab','hubspot'),(781,'fas','i-cursor'),(782,'fas','ice-cream'),(783,'fas','icicles'),(784,'fas','icons'),(785,'fas','id-badge'),(786,'far','id-badge'),(787,'fas','id-card'),(788,'far','id-card'),(789,'fas','id-card-alt'),(790,'fab','ideal'),(791,'fas','igloo'),(792,'fas','image'),(793,'far','image'),(794,'fas','images'),(795,'far','images'),(796,'fab','imdb'),(797,'fas','inbox'),(798,'fas','indent'),(799,'fas','industry'),(800,'fas','infinity'),(801,'fas','info'),(802,'fas','info-circle'),(803,'fab','innosoft'),(804,'fab','instagram'),(805,'fab','instagram-square'),(806,'fab','instalod'),(807,'fab','intercom'),(808,'fab','internet-explorer'),(809,'fab','invision'),(810,'fab','ioxhost'),(811,'fas','italic'),(812,'fab','itch-io'),(813,'fab','itunes'),(814,'fab','itunes-note'),(815,'fab','java'),(816,'fas','jedi'),(817,'fab','jedi-order'),(818,'fab','jenkins'),(819,'fab','jira'),(820,'fab','joget'),(821,'fas','joint'),(822,'fab','joomla'),(823,'fas','journal-whills'),(824,'fab','js'),(825,'fab','js-square'),(826,'fab','jsfiddle'),(827,'fas','kaaba'),(828,'fab','kaggle'),(829,'fas','key'),(830,'fab','keybase'),(831,'fas','keyboard'),(832,'far','keyboard'),(833,'fab','keycdn'),(834,'fas','khanda'),(835,'fab','kickstarter'),(836,'fab','kickstarter-k'),(837,'fas','kiss'),(838,'far','kiss'),(839,'fas','kiss-beam'),(840,'far','kiss-beam'),(841,'fas','kiss-wink-heart'),(842,'far','kiss-wink-heart'),(843,'fas','kiwi-bird'),(844,'fab','korvue'),(845,'fas','landmark'),(846,'fas','language'),(847,'fas','laptop'),(848,'fas','laptop-code'),(849,'fas','laptop-house'),(850,'fas','laptop-medical'),(851,'fab','laravel'),(852,'fab','lastfm'),(853,'fab','lastfm-square'),(854,'fas','laugh'),(855,'far','laugh'),(856,'fas','laugh-beam'),(857,'far','laugh-beam'),(858,'fas','laugh-squint'),(859,'far','laugh-squint'),(860,'fas','laugh-wink'),(861,'far','laugh-wink'),(862,'fas','layer-group'),(863,'fas','leaf'),(864,'fab','leanpub'),(865,'fas','lemon'),(866,'far','lemon'),(867,'fab','less'),(868,'fas','less-than'),(869,'fas','less-than-equal'),(870,'fas','level-down-alt'),(871,'fas','level-up-alt'),(872,'fas','life-ring'),(873,'far','life-ring'),(874,'fas','lightbulb'),(875,'far','lightbulb'),(876,'fab','line'),(877,'fas','link'),(878,'fab','linkedin'),(879,'fab','linkedin-in'),(880,'fab','linode'),(881,'fab','linux'),(882,'fas','lira-sign'),(883,'fas','list'),(884,'fas','list-alt'),(885,'far','list-alt'),(886,'fas','list-ol'),(887,'fas','list-ul'),(888,'fas','location-arrow'),(889,'fas','lock'),(890,'fas','lock-open'),(891,'fas','long-arrow-alt-down'),(892,'fas','long-arrow-alt-left'),(893,'fas','long-arrow-alt-right'),(894,'fas','long-arrow-alt-up'),(895,'fas','low-vision'),(896,'fas','luggage-cart'),(897,'fas','lungs'),(898,'fas','lungs-virus'),(899,'fab','lyft'),(900,'fab','magento'),(901,'fas','magic'),(902,'fas','magnet'),(903,'fas','mail-bulk'),(904,'fab','mailchimp'),(905,'fas','male'),(906,'fab','mandalorian'),(907,'fas','map'),(908,'far','map'),(909,'fas','map-marked'),(910,'fas','map-marked-alt'),(911,'fas','map-marker'),(912,'fas','map-marker-alt'),(913,'fas','map-pin'),(914,'fas','map-signs'),(915,'fab','markdown'),(916,'fas','marker'),(917,'fas','mars'),(918,'fas','mars-double'),(919,'fas','mars-stroke'),(920,'fas','mars-stroke-h'),(921,'fas','mars-stroke-v'),(922,'fas','mask'),(923,'fab','mastodon'),(924,'fab','maxcdn'),(925,'fab','mdb'),(926,'fas','medal'),(927,'fab','medapps'),(928,'fab','medium'),(929,'fab','medium-m'),(930,'fas','medkit'),(931,'fab','medrt'),(932,'fab','meetup'),(933,'fab','megaport'),(934,'fas','meh'),(935,'far','meh'),(936,'fas','meh-blank'),(937,'far','meh-blank'),(938,'fas','meh-rolling-eyes'),(939,'far','meh-rolling-eyes'),(940,'fas','memory'),(941,'fab','mendeley'),(942,'fas','menorah'),(943,'fas','mercury'),(944,'fas','meteor'),(945,'fab','microblog'),(946,'fas','microchip'),(947,'fas','microphone'),(948,'fas','microphone-alt'),(949,'fas','microphone-alt-slash'),(950,'fas','microphone-slash'),(951,'fas','microscope'),(952,'fab','microsoft'),(953,'fas','minus'),(954,'fas','minus-circle'),(955,'fas','minus-square'),(956,'far','minus-square'),(957,'fas','mitten'),(958,'fab','mix'),(959,'fab','mixcloud'),(960,'fab','mixer'),(961,'fab','mizuni'),(962,'fas','mobile'),(963,'fas','mobile-alt'),(964,'fab','modx'),(965,'fab','monero'),(966,'fas','money-bill'),(967,'fas','money-bill-alt'),(968,'far','money-bill-alt'),(969,'fas','money-bill-wave'),(970,'fas','money-bill-wave-alt'),(971,'fas','money-check'),(972,'fas','money-check-alt'),(973,'fas','monument'),(974,'fas','moon'),(975,'far','moon'),(976,'fas','mortar-pestle'),(977,'fas','mosque'),(978,'fas','motorcycle'),(979,'fas','mountain'),(980,'fas','mouse'),(981,'fas','mouse-pointer'),(982,'fas','mug-hot'),(983,'fas','music'),(984,'fab','napster'),(985,'fab','neos'),(986,'fas','network-wired'),(987,'fas','neuter'),(988,'fas','newspaper'),(989,'far','newspaper'),(990,'fab','nimblr'),(991,'fab','node'),(992,'fab','node-js'),(993,'fas','not-equal'),(994,'fas','notes-medical'),(995,'fab','npm'),(996,'fab','ns8'),(997,'fab','nutritionix'),(998,'fas','object-group'),(999,'far','object-group'),(1000,'fas','object-ungroup'),(1001,'far','object-ungroup'),(1002,'fab','octopus-deploy'),(1003,'fab','odnoklassniki'),(1004,'fab','odnoklassniki-square'),(1005,'fas','oil-can'),(1006,'fab','old-republic'),(1007,'fas','om'),(1008,'fab','opencart'),(1009,'fab','openid'),(1010,'fab','opera'),(1011,'fab','optin-monster'),(1012,'fab','orcid'),(1013,'fab','osi'),(1014,'fas','otter'),(1015,'fas','outdent'),(1016,'fab','page4'),(1017,'fab','pagelines'),(1018,'fas','pager'),(1019,'fas','paint-brush'),(1020,'fas','paint-roller'),(1021,'fas','palette'),(1022,'fab','palfed'),(1023,'fas','pallet'),(1024,'fas','paper-plane'),(1025,'far','paper-plane'),(1026,'fas','paperclip'),(1027,'fas','parachute-box'),(1028,'fas','paragraph'),(1029,'fas','parking'),(1030,'fas','passport'),(1031,'fas','pastafarianism'),(1032,'fas','paste'),(1033,'fab','patreon'),(1034,'fas','pause'),(1035,'fas','pause-circle'),(1036,'far','pause-circle'),(1037,'fas','paw'),(1038,'fab','paypal'),(1039,'fas','peace'),(1040,'fas','pen'),(1041,'fas','pen-alt'),(1042,'fas','pen-fancy'),(1043,'fas','pen-nib'),(1044,'fas','pen-square'),(1045,'fas','pencil-alt'),(1046,'fas','pencil-ruler'),(1047,'fab','penny-arcade'),(1048,'fas','people-arrows'),(1049,'fas','people-carry'),(1050,'fas','pepper-hot'),(1051,'fab','perbyte'),(1052,'fas','percent'),(1053,'fas','percentage'),(1054,'fab','periscope'),(1055,'fas','person-booth'),(1056,'fab','phabricator'),(1057,'fab','phoenix-framework'),(1058,'fab','phoenix-squadron'),(1059,'fas','phone'),(1060,'fas','phone-alt'),(1061,'fas','phone-slash'),(1062,'fas','phone-square'),(1063,'fas','phone-square-alt'),(1064,'fas','phone-volume'),(1065,'fas','photo-video'),(1066,'fab','php'),(1067,'fab','pied-piper'),(1068,'fab','pied-piper-alt'),(1069,'fab','pied-piper-hat'),(1070,'fab','pied-piper-pp'),(1071,'fab','pied-piper-square'),(1072,'fas','piggy-bank'),(1073,'fas','pills'),(1074,'fab','pinterest'),(1075,'fab','pinterest-p'),(1076,'fab','pinterest-square'),(1077,'fas','pizza-slice'),(1078,'fas','place-of-worship'),(1079,'fas','plane'),(1080,'fas','plane-arrival'),(1081,'fas','plane-departure'),(1082,'fas','plane-slash'),(1083,'fas','play'),(1084,'fas','play-circle'),(1085,'far','play-circle'),(1086,'fab','playstation'),(1087,'fas','plug'),(1088,'fas','plus'),(1089,'fas','plus-circle'),(1090,'fas','plus-square'),(1091,'far','plus-square'),(1092,'fas','podcast'),(1093,'fas','poll'),(1094,'fas','poll-h'),(1095,'fas','poo'),(1096,'fas','poo-storm'),(1097,'fas','poop'),(1098,'fas','portrait'),(1099,'fas','pound-sign'),(1100,'fas','power-off'),(1101,'fas','pray'),(1102,'fas','praying-hands'),(1103,'fas','prescription'),(1104,'fas','prescription-bottle'),(1105,'fas','prescription-bottle-alt'),(1106,'fas','print'),(1107,'fas','procedures'),(1108,'fab','product-hunt'),(1109,'fas','project-diagram'),(1110,'fas','pump-medical'),(1111,'fas','pump-soap'),(1112,'fab','pushed'),(1113,'fas','puzzle-piece'),(1114,'fab','python'),(1115,'fab','qq'),(1116,'fas','qrcode'),(1117,'fas','question'),(1118,'fas','question-circle'),(1119,'far','question-circle'),(1120,'fas','quidditch'),(1121,'fab','quinscape'),(1122,'fab','quora'),(1123,'fas','quote-left'),(1124,'fas','quote-right'),(1125,'fas','quran'),(1126,'fab','r-project'),(1127,'fas','radiation'),(1128,'fas','radiation-alt'),(1129,'fas','rainbow'),(1130,'fas','random'),(1131,'fab','raspberry-pi'),(1132,'fab','ravelry'),(1133,'fab','react'),(1134,'fab','reacteurope'),(1135,'fab','readme'),(1136,'fab','rebel'),(1137,'fas','receipt'),(1138,'fas','record-vinyl'),(1139,'fas','recycle'),(1140,'fab','red-river'),(1141,'fab','reddit'),(1142,'fab','reddit-alien'),(1143,'fab','reddit-square'),(1144,'fab','redhat'),(1145,'fas','redo'),(1146,'fas','redo-alt'),(1147,'fas','registered'),(1148,'far','registered'),(1149,'fas','remove-format'),(1150,'fab','renren'),(1151,'fas','reply'),(1152,'fas','reply-all'),(1153,'fab','replyd'),(1154,'fas','republican'),(1155,'fab','researchgate'),(1156,'fab','resolving'),(1157,'fas','restroom'),(1158,'fas','retweet'),(1159,'fab','rev'),(1160,'fas','ribbon'),(1161,'fas','ring'),(1162,'fas','road'),(1163,'fas','robot'),(1164,'fas','rocket'),(1165,'fab','rocketchat'),(1166,'fab','rockrms'),(1167,'fas','route'),(1168,'fas','rss'),(1169,'fas','rss-square'),(1170,'fas','ruble-sign'),(1171,'fas','ruler'),(1172,'fas','ruler-combined'),(1173,'fas','ruler-horizontal'),(1174,'fas','ruler-vertical'),(1175,'fas','running'),(1176,'fas','rupee-sign'),(1177,'fab','rust'),(1178,'fas','sad-cry'),(1179,'far','sad-cry'),(1180,'fas','sad-tear'),(1181,'far','sad-tear'),(1182,'fab','safari'),(1183,'fab','salesforce'),(1184,'fab','sass'),(1185,'fas','satellite'),(1186,'fas','satellite-dish'),(1187,'fas','save'),(1188,'far','save'),(1189,'fab','schlix'),(1190,'fas','school'),(1191,'fas','screwdriver'),(1192,'fab','scribd'),(1193,'fas','scroll'),(1194,'fas','sd-card'),(1195,'fas','search'),(1196,'fas','search-dollar'),(1197,'fas','search-location'),(1198,'fas','search-minus'),(1199,'fas','search-plus'),(1200,'fab','searchengin'),(1201,'fas','seedling'),(1202,'fab','sellcast'),(1203,'fab','sellsy'),(1204,'fas','server'),(1205,'fab','servicestack'),(1206,'fas','shapes'),(1207,'fas','share'),(1208,'fas','share-alt'),(1209,'fas','share-alt-square'),(1210,'fas','share-square'),(1211,'far','share-square'),(1212,'fas','shekel-sign'),(1213,'fas','shield-alt'),(1214,'fas','shield-virus'),(1215,'fas','ship'),(1216,'fas','shipping-fast'),(1217,'fab','shirtsinbulk'),(1218,'fas','shoe-prints'),(1219,'fab','shopify'),(1220,'fas','shopping-bag'),(1221,'fas','shopping-basket'),(1222,'fas','shopping-cart'),(1223,'fab','shopware'),(1224,'fas','shower'),(1225,'fas','shuttle-van'),(1226,'fas','sign'),(1227,'fas','sign-in-alt'),(1228,'fas','sign-language'),(1229,'fas','sign-out-alt'),(1230,'fas','signal'),(1231,'fas','signature'),(1232,'fas','sim-card'),(1233,'fab','simplybuilt'),(1234,'fas','sink'),(1235,'fab','sistrix'),(1236,'fas','sitemap'),(1237,'fab','sith'),(1238,'fas','skating'),(1239,'fab','sketch'),(1240,'fas','skiing'),(1241,'fas','skiing-nordic'),(1242,'fas','skull'),(1243,'fas','skull-crossbones'),(1244,'fab','skyatlas'),(1245,'fab','skype'),(1246,'fab','slack'),(1247,'fab','slack-hash'),(1248,'fas','slash'),(1249,'fas','sleigh'),(1250,'fas','sliders-h'),(1251,'fab','slideshare'),(1252,'fas','smile'),(1253,'far','smile'),(1254,'fas','smile-beam'),(1255,'far','smile-beam'),(1256,'fas','smile-wink'),(1257,'far','smile-wink'),(1258,'fas','smog'),(1259,'fas','smoking'),(1260,'fas','smoking-ban'),(1261,'fas','sms'),(1262,'fab','snapchat'),(1263,'fab','snapchat-ghost'),(1264,'fab','snapchat-square'),(1265,'fas','snowboarding'),(1266,'fas','snowflake'),(1267,'far','snowflake'),(1268,'fas','snowman'),(1269,'fas','snowplow'),(1270,'fas','soap'),(1271,'fas','socks'),(1272,'fas','solar-panel'),(1273,'fas','sort'),(1274,'fas','sort-alpha-down'),(1275,'fas','sort-alpha-down-alt'),(1276,'fas','sort-alpha-up'),(1277,'fas','sort-alpha-up-alt'),(1278,'fas','sort-amount-down'),(1279,'fas','sort-amount-down-alt'),(1280,'fas','sort-amount-up'),(1281,'fas','sort-amount-up-alt'),(1282,'fas','sort-down'),(1283,'fas','sort-numeric-down'),(1284,'fas','sort-numeric-down-alt'),(1285,'fas','sort-numeric-up'),(1286,'fas','sort-numeric-up-alt'),(1287,'fas','sort-up'),(1288,'fab','soundcloud'),(1289,'fab','sourcetree'),(1290,'fas','spa'),(1291,'fas','space-shuttle'),(1292,'fab','speakap'),(1293,'fab','speaker-deck'),(1294,'fas','spell-check'),(1295,'fas','spider'),(1296,'fas','spinner'),(1297,'fas','splotch'),(1298,'fab','spotify'),(1299,'fas','spray-can'),(1300,'fas','square'),(1301,'far','square'),(1302,'fas','square-full'),(1303,'fas','square-root-alt'),(1304,'fab','squarespace'),(1305,'fab','stack-exchange'),(1306,'fab','stack-overflow'),(1307,'fab','stackpath'),(1308,'fas','stamp'),(1309,'fas','star'),(1310,'far','star'),(1311,'fas','star-and-crescent'),(1312,'fas','star-half'),(1313,'far','star-half'),(1314,'fas','star-half-alt'),(1315,'fas','star-of-david'),(1316,'fas','star-of-life'),(1317,'fab','staylinked'),(1318,'fab','steam'),(1319,'fab','steam-square'),(1320,'fab','steam-symbol'),(1321,'fas','step-backward'),(1322,'fas','step-forward'),(1323,'fas','stethoscope'),(1324,'fab','sticker-mule'),(1325,'fas','sticky-note'),(1326,'far','sticky-note'),(1327,'fas','stop'),(1328,'fas','stop-circle'),(1329,'far','stop-circle'),(1330,'fas','stopwatch'),(1331,'fas','stopwatch-20'),(1332,'fas','store'),(1333,'fas','store-alt'),(1334,'fas','store-alt-slash'),(1335,'fas','store-slash'),(1336,'fab','strava'),(1337,'fas','stream'),(1338,'fas','street-view'),(1339,'fas','strikethrough'),(1340,'fab','stripe'),(1341,'fab','stripe-s'),(1342,'fas','stroopwafel'),(1343,'fab','studiovinari'),(1344,'fab','stumbleupon'),(1345,'fab','stumbleupon-circle'),(1346,'fas','subscript'),(1347,'fas','subway'),(1348,'fas','suitcase'),(1349,'fas','suitcase-rolling'),(1350,'fas','sun'),(1351,'far','sun'),(1352,'fab','superpowers'),(1353,'fas','superscript'),(1354,'fab','supple'),(1355,'fas','surprise'),(1356,'far','surprise'),(1357,'fab','suse'),(1358,'fas','swatchbook'),(1359,'fab','swift'),(1360,'fas','swimmer'),(1361,'fas','swimming-pool'),(1362,'fab','symfony'),(1363,'fas','synagogue'),(1364,'fas','sync'),(1365,'fas','sync-alt'),(1366,'fas','syringe'),(1367,'fas','table'),(1368,'fas','table-tennis'),(1369,'fas','tablet'),(1370,'fas','tablet-alt'),(1371,'fas','tablets'),(1372,'fas','tachometer-alt'),(1373,'fas','tag'),(1374,'fas','tags'),(1375,'fas','tape'),(1376,'fas','tasks'),(1377,'fas','taxi'),(1378,'fab','teamspeak'),(1379,'fas','teeth'),(1380,'fas','teeth-open'),(1381,'fab','telegram'),(1382,'fab','telegram-plane'),(1383,'fas','temperature-high'),(1384,'fas','temperature-low'),(1385,'fab','tencent-weibo'),(1386,'fas','tenge'),(1387,'fas','terminal'),(1388,'fas','text-height'),(1389,'fas','text-width'),(1390,'fas','th'),(1391,'fas','th-large'),(1392,'fas','th-list'),(1393,'fab','the-red-yeti'),(1394,'fas','theater-masks'),(1395,'fab','themeco'),(1396,'fab','themeisle'),(1397,'fas','thermometer'),(1398,'fas','thermometer-empty'),(1399,'fas','thermometer-full'),(1400,'fas','thermometer-half'),(1401,'fas','thermometer-quarter'),(1402,'fas','thermometer-three-quarters'),(1403,'fab','think-peaks'),(1404,'fas','thumbs-down'),(1405,'far','thumbs-down'),(1406,'fas','thumbs-up'),(1407,'far','thumbs-up'),(1408,'fas','thumbtack'),(1409,'fas','ticket-alt'),(1410,'fab','tiktok'),(1411,'fas','times'),(1412,'fas','times-circle'),(1413,'far','times-circle'),(1414,'fas','tint'),(1415,'fas','tint-slash'),(1416,'fas','tired'),(1417,'far','tired'),(1418,'fas','toggle-off'),(1419,'fas','toggle-on'),(1420,'fas','toilet'),(1421,'fas','toilet-paper'),(1422,'fas','toilet-paper-slash'),(1423,'fas','toolbox'),(1424,'fas','tools'),(1425,'fas','tooth'),(1426,'fas','torah'),(1427,'fas','torii-gate'),(1428,'fas','tractor'),(1429,'fab','trade-federation'),(1430,'fas','trademark'),(1431,'fas','traffic-light'),(1432,'fas','trailer'),(1433,'fas','train'),(1434,'fas','tram'),(1435,'fas','transgender'),(1436,'fas','transgender-alt'),(1437,'fas','trash'),(1438,'fas','trash-alt'),(1439,'far','trash-alt'),(1440,'fas','trash-restore'),(1441,'fas','trash-restore-alt'),(1442,'fas','tree'),(1443,'fab','trello'),(1444,'fas','trophy'),(1445,'fas','truck'),(1446,'fas','truck-loading'),(1447,'fas','truck-monster'),(1448,'fas','truck-moving'),(1449,'fas','truck-pickup'),(1450,'fas','tshirt'),(1451,'fas','tty'),(1452,'fab','tumblr'),(1453,'fab','tumblr-square'),(1454,'fas','tv'),(1455,'fab','twitch'),(1456,'fab','twitter'),(1457,'fab','twitter-square'),(1458,'fab','typo3'),(1459,'fab','uber'),(1460,'fab','ubuntu'),(1461,'fab','uikit'),(1462,'fab','umbraco'),(1463,'fas','umbrella'),(1464,'fas','umbrella-beach'),(1465,'fab','uncharted'),(1466,'fas','underline'),(1467,'fas','undo'),(1468,'fas','undo-alt'),(1469,'fab','uniregistry'),(1470,'fab','unity'),(1471,'fas','universal-access'),(1472,'fas','university'),(1473,'fas','unlink'),(1474,'fas','unlock'),(1475,'fas','unlock-alt'),(1476,'fab','unsplash'),(1477,'fab','untappd'),(1478,'fas','upload'),(1479,'fab','ups'),(1480,'fab','usb'),(1481,'fas','user'),(1482,'far','user'),(1483,'fas','user-alt'),(1484,'fas','user-alt-slash'),(1485,'fas','user-astronaut'),(1486,'fas','user-check'),(1487,'fas','user-circle'),(1488,'far','user-circle'),(1489,'fas','user-clock'),(1490,'fas','user-cog'),(1491,'fas','user-edit'),(1492,'fas','user-friends'),(1493,'fas','user-graduate'),(1494,'fas','user-injured'),(1495,'fas','user-lock'),(1496,'fas','user-md'),(1497,'fas','user-minus'),(1498,'fas','user-ninja'),(1499,'fas','user-nurse'),(1500,'fas','user-plus'),(1501,'fas','user-secret'),(1502,'fas','user-shield'),(1503,'fas','user-slash'),(1504,'fas','user-tag'),(1505,'fas','user-tie'),(1506,'fas','user-times'),(1507,'fas','users'),(1508,'fas','users-cog'),(1509,'fas','users-slash'),(1510,'fab','usps'),(1511,'fab','ussunnah'),(1512,'fas','utensil-spoon'),(1513,'fas','utensils'),(1514,'fab','vaadin'),(1515,'fas','vector-square'),(1516,'fas','venus'),(1517,'fas','venus-double'),(1518,'fas','venus-mars'),(1519,'fas','vest'),(1520,'fas','vest-patches'),(1521,'fab','viacoin'),(1522,'fab','viadeo'),(1523,'fab','viadeo-square'),(1524,'fas','vial'),(1525,'fas','vials'),(1526,'fab','viber'),(1527,'fas','video'),(1528,'fas','video-slash'),(1529,'fas','vihara'),(1530,'fab','vimeo'),(1531,'fab','vimeo-square'),(1532,'fab','vimeo-v'),(1533,'fab','vine'),(1534,'fas','virus'),(1535,'fas','virus-slash'),(1536,'fas','viruses'),(1537,'fab','vk'),(1538,'fab','vnv'),(1539,'fas','voicemail'),(1540,'fas','volleyball-ball'),(1541,'fas','volume-down'),(1542,'fas','volume-mute'),(1543,'fas','volume-off'),(1544,'fas','volume-up'),(1545,'fas','vote-yea'),(1546,'fas','vr-cardboard'),(1547,'fab','vuejs'),(1548,'fas','walking'),(1549,'fas','wallet'),(1550,'fas','warehouse'),(1551,'fab','watchman-monitoring'),(1552,'fas','water'),(1553,'fas','wave-square'),(1554,'fab','waze'),(1555,'fab','weebly'),(1556,'fab','weibo'),(1557,'fas','weight'),(1558,'fas','weight-hanging'),(1559,'fab','weixin'),(1560,'fab','whatsapp'),(1561,'fab','whatsapp-square'),(1562,'fas','wheelchair'),(1563,'fab','whmcs'),(1564,'fas','wifi'),(1565,'fab','wikipedia-w'),(1566,'fas','wind'),(1567,'fas','window-close'),(1568,'far','window-close'),(1569,'fas','window-maximize'),(1570,'far','window-maximize'),(1571,'fas','window-minimize'),(1572,'far','window-minimize'),(1573,'fas','window-restore'),(1574,'far','window-restore'),(1575,'fab','windows'),(1576,'fas','wine-bottle'),(1577,'fas','wine-glass'),(1578,'fas','wine-glass-alt'),(1579,'fab','wix'),(1580,'fab','wizards-of-the-coast'),(1581,'fab','wodu'),(1582,'fab','wolf-pack-battalion'),(1583,'fas','won-sign'),(1584,'fab','wordpress'),(1585,'fab','wordpress-simple'),(1586,'fab','wpbeginner'),(1587,'fab','wpexplorer'),(1588,'fab','wpforms'),(1589,'fab','wpressr'),(1590,'fas','wrench'),(1591,'fas','x-ray'),(1592,'fab','xbox'),(1593,'fab','xing'),(1594,'fab','xing-square'),(1595,'fab','y-combinator'),(1596,'fab','yahoo'),(1597,'fab','yammer'),(1598,'fab','yandex'),(1599,'fab','yandex-international'),(1600,'fab','yarn'),(1601,'fab','yelp'),(1602,'fas','yen-sign'),(1603,'fas','yin-yang'),(1604,'fab','yoast'),(1605,'fab','youtube'),(1606,'fab','youtube-square'),(1607,'fab','zhihu');
/*!40000 ALTER TABLE `icons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `legal_entities`
--

DROP TABLE IF EXISTS `legal_entities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `legal_entities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `legal_entities_type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `is_archive` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legal_entities`
--

LOCK TABLES `legal_entities` WRITE;
/*!40000 ALTER TABLE `legal_entities` DISABLE KEYS */;
/*!40000 ALTER TABLE `legal_entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `legal_entities_types`
--

DROP TABLE IF EXISTS `legal_entities_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `legal_entities_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legal_entities_types`
--

LOCK TABLES `legal_entities_types` WRITE;
/*!40000 ALTER TABLE `legal_entities_types` DISABLE KEYS */;
INSERT INTO `legal_entities_types` VALUES (1,'ООО','Общество с ограниченной ответственностью'),(2,'ОАО','Открытое акционерное общество'),(3,'ЗАО','Закрытое акционерное общество'),(4,'ПАО','Публичное акционерное общество'),(5,'АО','Акционерное общество'),(6,'ИП','Индивидуальный предприниматель');
/*!40000 ALTER TABLE `legal_entities_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material_types`
--

DROP TABLE IF EXISTS `material_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `units_measurement_volume_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material_types`
--

LOCK TABLES `material_types` WRITE;
/*!40000 ALTER TABLE `material_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `material_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1638192565),('m210918_182339_init',1638192565),('m210919_090259_login',1638192565),('m210921_202003_user_admin',1638192566),('m210921_202934_is_admin',1638192566),('m211004_164118_files',1638192566),('m211005_165743_timezone',1638192566),('m211007_191940_material_types',1638192566),('m211008_200051_materials',1638192566),('m211011_182431_legal_entities_types',1638192566),('m211012_153221_legal_entities',1638192566),('m211013_154908_vendors',1638192568),('m211014_175831_objects',1638192568),('m211016_113934_payments',1638192568),('m211023_191115_remove_methods',1638192568),('m211024_110337_is_archive',1638192568),('m211025_173517_history_operation',1638192568),('m211029_175309_units_measurement_volume',1638192568),('m211103_100524_files',1638192568),('m211105_123433_settings',1638192568),('m211105_161922_docs',1638192568),('m211124_191025_reports',1638192568);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objects`
--

DROP TABLE IF EXISTS `objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_archive` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects`
--

LOCK TABLES `objects` WRITE;
/*!40000 ALTER TABLE `objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT NULL,
  `legal_entity_id` int(11) DEFAULT NULL,
  `amount` decimal(13,2) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rights`
--

DROP TABLE IF EXISTS `rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `is_admin` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rights`
--

LOCK TABLES `rights` WRITE;
/*!40000 ALTER TABLE `rights` DISABLE KEYS */;
INSERT INTO `rights` VALUES (1,1,1);
/*!40000 ALTER TABLE `rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'debt',0);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timezone`
--

DROP TABLE IF EXISTS `timezone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timezone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timezone_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=349 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timezone`
--

LOCK TABLES `timezone` WRITE;
/*!40000 ALTER TABLE `timezone` DISABLE KEYS */;
INSERT INTO `timezone` VALUES (1,'Europe/Moscow'),(2,'Africa/Accra'),(3,'Europe/Gibraltar'),(4,'America/Godthab'),(5,'America/Danmarkshavn'),(6,'America/Scoresbysund'),(7,'Africa/Bissau'),(8,'America/Guyana'),(9,'Asia/Hong_Kong'),(10,'America/Tegucigalpa'),(11,'America/Port-au-Prince'),(12,'America/Argentina/Buenos_Aires'),(13,'America/Argentina/Cordoba'),(14,'America/Argentina/Salta'),(15,'America/Argentina/Jujuy'),(16,'America/Argentina/Tucuman'),(17,'America/Argentina/Catamarca'),(18,'America/Argentina/La_Rioja'),(19,'America/Argentina/San_Juan'),(20,'America/Argentina/Mendoza'),(21,'America/Argentina/San_Luis'),(22,'America/Argentina/Rio_Gallegos'),(23,'America/Argentina/Ushuaia'),(24,'America/Bahia_Banderas'),(25,'Asia/Kuala_Lumpur'),(26,'Asia/Kuching'),(27,'Africa/Maputo'),(28,'Africa/Windhoek'),(29,'America/Campo_Grande'),(30,'America/Cuiaba'),(31,'America/Santarem'),(32,'America/Porto_Velho'),(33,'America/Boa_Vista'),(34,'America/Curacao'),(35,'Indian/Christmas'),(36,'Asia/Nicosia'),(37,'Asia/Famagusta'),(38,'Europe/Prague'),(39,'Europe/Berlin'),(40,'America/Indiana/Knox'),(41,'America/Menominee'),(42,'America/North_Dakota/Center'),(43,'America/Indiana/Petersburg'),(44,'America/Indiana/Vevay'),(45,'America/Chicago'),(46,'America/Indiana/Tell_City'),(47,'America/Indiana/Vincennes'),(48,'America/Indiana/Winamac'),(49,'America/Indiana/Marengo'),(50,'America/Inuvik'),(51,'America/Creston'),(52,'America/Dawson_Creek'),(53,'America/Fort_Nelson'),(54,'America/Vancouver'),(55,'America/Kentucky/Louisville'),(56,'America/Kentucky/Monticello'),(57,'America/Indiana/Indianapolis'),(58,'America/Lima'),(59,'Pacific/Tahiti'),(60,'Pacific/Marquesas'),(61,'Pacific/Gambier'),(62,'Pacific/Port_Moresby'),(63,'America/Manaus'),(64,'America/Eirunepe'),(65,'America/Rio_Branco'),(66,'America/Nassau'),(67,'Asia/Thimphu'),(68,'America/Mazatlan'),(69,'America/Chihuahua'),(70,'America/Ojinaga'),(71,'America/Hermosillo'),(72,'America/Tijuana'),(73,'America/Metlakatla'),(74,'America/Yakutat'),(75,'America/Nome'),(76,'America/Adak'),(77,'Pacific/Honolulu'),(78,'America/Mexico_City'),(79,'America/Cancun'),(80,'America/Merida'),(81,'America/Monterrey'),(82,'America/Matamoros'),(83,'America/Moncton'),(84,'America/Goose_Bay'),(85,'America/Blanc-Sablon'),(86,'America/Toronto'),(87,'America/Nipigon'),(88,'America/Montevideo'),(89,'Asia/Samarkand'),(90,'Asia/Tashkent'),(91,'America/Caracas'),(92,'Asia/Ho_Chi_Minh'),(93,'America/North_Dakota/New_Salem'),(94,'America/North_Dakota/Beulah'),(95,'America/Denver'),(96,'America/Boise'),(97,'America/Phoenix'),(98,'America/Los_Angeles'),(99,'America/Anchorage'),(100,'America/Juneau'),(101,'America/Sitka'),(102,'America/Rainy_River'),(103,'America/Resolute'),(104,'America/Rankin_Inlet'),(105,'America/Regina'),(106,'America/Recife'),(107,'America/Araguaina'),(108,'America/Maceio'),(109,'America/Bahia'),(110,'America/Sao_Paulo'),(111,'America/Swift_Current'),(112,'America/Edmonton'),(113,'America/Cambridge_Bay'),(114,'America/Yellowknife'),(115,'America/Thule'),(116,'Europe/Athens'),(117,'Atlantic/South_Georgia'),(118,'America/Guatemala'),(119,'Pacific/Guam'),(120,'America/Thunder_Bay'),(121,'America/Iqaluit'),(122,'America/Pangnirtung'),(123,'America/Atikokan'),(124,'America/Winnipeg'),(125,'America/Whitehorse'),(126,'America/Dawson'),(127,'Indian/Cocos'),(128,'Europe/Zurich'),(129,'Africa/Abidjan'),(130,'Antarctica/Davis'),(131,'Antarctica/DumontDUrville'),(132,'Antarctica/Mawson'),(133,'Antarctica/Palmer'),(134,'Antarctica/Rothera'),(135,'Antarctica/Syowa'),(136,'Antarctica/Troll'),(137,'Antarctica/Vostok'),(138,'Asia/Almaty'),(139,'Asia/Qyzylorda'),(140,'Asia/Qostanay'),(141,'Asia/Aqtobe'),(142,'Asia/Aqtau'),(143,'Asia/Atyrau'),(144,'Asia/Damascus'),(145,'America/Grand_Turk'),(146,'Africa/Ndjamena'),(147,'Indian/Kerguelen'),(148,'Asia/Bangkok'),(149,'Asia/Dushanbe'),(150,'Pacific/Fakaofo'),(151,'Asia/Dili'),(152,'Asia/Ashgabat'),(153,'Africa/Tunis'),(154,'Pacific/Tongatapu'),(155,'Asia/Jerusalem'),(156,'Asia/Kolkata'),(157,'Indian/Chagos'),(158,'Asia/Baghdad'),(159,'Asia/Tehran'),(160,'Atlantic/Reykjavik'),(161,'Asia/Kathmandu'),(162,'Pacific/Nauru'),(163,'Pacific/Niue'),(164,'Pacific/Auckland'),(165,'Pacific/Chatham'),(166,'America/Panama'),(167,'Asia/Krasnoyarsk'),(168,'Asia/Irkutsk'),(169,'Asia/Chita'),(170,'Asia/Yakutsk'),(171,'Asia/Khandyga'),(172,'Asia/Vladivostok'),(173,'Asia/Macau'),(174,'America/Martinique'),(175,'Europe/Malta'),(176,'Indian/Mauritius'),(177,'Indian/Maldives'),(178,'Asia/Oral'),(179,'Asia/Beirut'),(180,'Asia/Colombo'),(181,'Africa/Monrovia'),(182,'Europe/Vilnius'),(183,'Europe/Luxembourg'),(184,'Asia/Riyadh'),(185,'Pacific/Guadalcanal'),(186,'Indian/Mahe'),(187,'Africa/Khartoum'),(188,'Europe/Stockholm'),(189,'Asia/Singapore'),(190,'America/Paramaribo'),(191,'Africa/Juba'),(192,'Africa/Sao_Tome'),(193,'America/El_Salvador'),(194,'Asia/Urumqi'),(195,'America/Bogota'),(196,'America/Costa_Rica'),(197,'America/Havana'),(198,'Atlantic/Cape_Verde'),(199,'Asia/Ust-Nera'),(200,'Asia/Magadan'),(201,'Asia/Sakhalin'),(202,'Asia/Srednekolymsk'),(203,'Asia/Kamchatka'),(204,'Asia/Anadyr'),(205,'Asia/Yekaterinburg'),(206,'Asia/Omsk'),(207,'Asia/Novosibirsk'),(208,'Asia/Barnaul'),(209,'Asia/Tomsk'),(210,'Asia/Novokuznetsk'),(211,'Atlantic/Azores'),(212,'Pacific/Palau'),(213,'America/Asuncion'),(214,'Asia/Qatar'),(215,'Indian/Reunion'),(216,'Europe/Bucharest'),(217,'Atlantic/Bermuda'),(218,'Asia/Brunei'),(219,'America/La_Paz'),(220,'America/Noronha'),(221,'America/Belem'),(222,'America/Fortaleza'),(223,'Australia/Brisbane'),(224,'Australia/Lindeman'),(225,'Australia/Adelaide'),(226,'Australia/Darwin'),(227,'Australia/Perth'),(228,'Australia/Currie'),(229,'Australia/Melbourne'),(230,'Australia/Sydney'),(231,'Australia/Broken_Hill'),(232,'Australia/Eucla'),(233,'Asia/Baku'),(234,'America/Barbados'),(235,'Asia/Dhaka'),(236,'Europe/Brussels'),(237,'Europe/Sofia'),(238,'Europe/Astrakhan'),(239,'Europe/Volgograd'),(240,'Europe/Saratov'),(241,'Europe/Ulyanovsk'),(242,'Europe/Samara'),(243,'Europe/Belgrade'),(244,'Europe/Kaliningrad'),(245,'Europe/Simferopol'),(246,'Europe/Kirov'),(247,'Europe/Budapest'),(248,'Asia/Jakarta'),(249,'Asia/Pontianak'),(250,'Asia/Makassar'),(251,'Asia/Jayapura'),(252,'Europe/Dublin'),(253,'Europe/Copenhagen'),(254,'America/Santo_Domingo'),(255,'Africa/Algiers'),(256,'America/Guayaquil'),(257,'Pacific/Galapagos'),(258,'Europe/Helsinki'),(259,'Pacific/Fiji'),(260,'Atlantic/Stanley'),(261,'Pacific/Chuuk'),(262,'Pacific/Pohnpei'),(263,'Europe/Istanbul'),(264,'America/Port_of_Spain'),(265,'Pacific/Funafuti'),(266,'Asia/Taipei'),(267,'Europe/Kiev'),(268,'Europe/Minsk'),(269,'America/Belize'),(270,'America/St_Johns'),(271,'America/Halifax'),(272,'America/Glace_Bay'),(273,'Europe/Riga'),(274,'Africa/Tripoli'),(275,'Africa/Casablanca'),(276,'Europe/Monaco'),(277,'Europe/Chisinau'),(278,'Europe/Rome'),(279,'America/Jamaica'),(280,'Asia/Amman'),(281,'Asia/Tokyo'),(282,'Africa/Nairobi'),(283,'Asia/Bishkek'),(284,'Europe/Tallinn'),(285,'Africa/Cairo'),(286,'Africa/El_Aaiun'),(287,'Europe/Madrid'),(288,'Africa/Ceuta'),(289,'Atlantic/Canary'),(290,'Europe/Uzhgorod'),(291,'Europe/Zaporozhye'),(292,'Pacific/Wake'),(293,'America/New_York'),(294,'America/Detroit'),(295,'Pacific/Bougainville'),(296,'Asia/Manila'),(297,'Asia/Karachi'),(298,'Europe/Warsaw'),(299,'America/Miquelon'),(300,'Pacific/Efate'),(301,'Pacific/Wallis'),(302,'Pacific/Apia'),(303,'Africa/Johannesburg'),(304,'Pacific/Kosrae'),(305,'Atlantic/Faroe'),(306,'Europe/Paris'),(307,'Europe/London'),(308,'Asia/Tbilisi'),(309,'America/Cayenne'),(310,'Pacific/Majuro'),(311,'Pacific/Kwajalein'),(312,'Asia/Yangon'),(313,'Asia/Ulaanbaatar'),(314,'Asia/Hovd'),(315,'Asia/Choibalsan'),(316,'Pacific/Noumea'),(317,'Pacific/Norfolk'),(318,'Africa/Lagos'),(319,'America/Managua'),(320,'Europe/Amsterdam'),(321,'Europe/Oslo'),(322,'Pacific/Pago_Pago'),(323,'Europe/Vienna'),(324,'Australia/Lord_Howe'),(325,'Antarctica/Macquarie'),(326,'Australia/Hobart'),(327,'Pacific/Pitcairn'),(328,'America/Puerto_Rico'),(329,'Asia/Gaza'),(330,'Asia/Hebron'),(331,'Europe/Lisbon'),(332,'Atlantic/Madeira'),(333,'Pacific/Rarotonga'),(334,'America/Santiago'),(335,'America/Punta_Arenas'),(336,'Pacific/Easter'),(337,'Asia/Shanghai'),(338,'Pacific/Tarawa'),(339,'Pacific/Enderbury'),(340,'Pacific/Kiritimati'),(341,'Asia/Pyongyang'),(342,'Asia/Seoul'),(343,'Europe/Andorra'),(344,'Asia/Dubai'),(345,'Asia/Kabul'),(346,'Europe/Tirane'),(347,'Asia/Yerevan'),(348,'Antarctica/Casey');
/*!40000 ALTER TABLE `timezone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_measurement_volume`
--

DROP TABLE IF EXISTS `units_measurement_volume`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_measurement_volume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_measurement_volume`
--

LOCK TABLES `units_measurement_volume` WRITE;
/*!40000 ALTER TABLE `units_measurement_volume` DISABLE KEYS */;
INSERT INTO `units_measurement_volume` VALUES (1,'Метр'),(2,'Километр'),(3,'Дюйм'),(4,'Квадратный километр'),(5,'Ар'),(6,'Гектар'),(7,'Квадратный метр'),(8,'Квадратный дециметр'),(9,'Квадратный сантиметр'),(10,'Квадратный дюйм'),(11,'Кубический метр'),(12,'Кубический дециметр'),(13,'Гектолитр'),(14,'Тонна'),(15,'Килограмм'),(16,'Грамм'),(17,'Килловат-час'),(18,'Килловат');
/*!40000 ALTER TABLE `units_measurement_volume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_timezone`
--

DROP TABLE IF EXISTS `user_timezone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_timezone` (
  `timezone_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_timezone`
--

LOCK TABLES `user_timezone` WRITE;
/*!40000 ALTER TABLE `user_timezone` DISABLE KEYS */;
INSERT INTO `user_timezone` VALUES (1,1);
/*!40000 ALTER TABLE `user_timezone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `last_login_at` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@it-paradise.ru','$2y$13$.CwZ5aOFHq3PqEmn4yNzd.8HLU/bnk03ZT.RJlPDev4h6Y84oRHY6',1638192565,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon_id` int(11) DEFAULT NULL,
  `is_archive` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-29 14:10:27
