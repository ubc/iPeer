-- MySQL dump 10.16  Distrib 10.1.44-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ipeer
-- ------------------------------------------------------
-- Server version	10.1.44-MariaDB-1~bionic

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
-- Table structure for table `acos`
--

DROP TABLE IF EXISTS `acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=338 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acos`
--

LOCK TABLES `acos` WRITE;
/*!40000 ALTER TABLE `acos` DISABLE KEYS */;
INSERT INTO `acos` VALUES (1,NULL,NULL,NULL,'adminpage',1,2),(2,NULL,NULL,NULL,'controllers',3,598),(3,2,NULL,NULL,'Pages',4,17),(4,3,NULL,NULL,'display',5,6),(5,3,NULL,NULL,'add',7,8),(6,3,NULL,NULL,'edit',9,10),(7,3,NULL,NULL,'index',11,12),(8,3,NULL,NULL,'view',13,14),(9,3,NULL,NULL,'delete',15,16),(10,2,NULL,NULL,'Accesses',18,29),(11,10,NULL,NULL,'view',19,20),(12,10,NULL,NULL,'edit',21,22),(13,10,NULL,NULL,'add',23,24),(14,10,NULL,NULL,'index',25,26),(15,10,NULL,NULL,'delete',27,28),(16,2,NULL,NULL,'Courses',30,53),(17,16,NULL,NULL,'daysLate',31,32),(18,16,NULL,NULL,'index',33,34),(19,16,NULL,NULL,'ajaxList',35,36),(20,16,NULL,NULL,'view',37,38),(21,16,NULL,NULL,'home',39,40),(22,16,NULL,NULL,'add',41,42),(23,16,NULL,NULL,'edit',43,44),(24,16,NULL,NULL,'delete',45,46),(25,16,NULL,NULL,'move',47,48),(26,16,NULL,NULL,'ajax_options',49,50),(27,16,NULL,NULL,'import',51,52),(28,2,NULL,NULL,'Departments',54,65),(29,28,NULL,NULL,'index',55,56),(30,28,NULL,NULL,'view',57,58),(31,28,NULL,NULL,'add',59,60),(32,28,NULL,NULL,'edit',61,62),(33,28,NULL,NULL,'delete',63,64),(34,2,NULL,NULL,'Emailer',66,93),(35,34,NULL,NULL,'setUpAjaxList',67,68),(36,34,NULL,NULL,'ajaxList',69,70),(37,34,NULL,NULL,'index',71,72),(38,34,NULL,NULL,'write',73,74),(39,34,NULL,NULL,'cancel',75,76),(40,34,NULL,NULL,'view',77,78),(41,34,NULL,NULL,'addRecipient',79,80),(42,34,NULL,NULL,'deleteRecipient',81,82),(43,34,NULL,NULL,'getRecipient',83,84),(44,34,NULL,NULL,'searchByUserId',85,86),(45,34,NULL,NULL,'add',87,88),(46,34,NULL,NULL,'edit',89,90),(47,34,NULL,NULL,'delete',91,92),(48,2,NULL,NULL,'Emailtemplates',94,113),(49,48,NULL,NULL,'setUpAjaxList',95,96),(50,48,NULL,NULL,'ajaxList',97,98),(51,48,NULL,NULL,'index',99,100),(52,48,NULL,NULL,'add',101,102),(53,48,NULL,NULL,'edit',103,104),(54,48,NULL,NULL,'delete',105,106),(55,48,NULL,NULL,'view',107,108),(56,48,NULL,NULL,'displayTemplateContent',109,110),(57,48,NULL,NULL,'displayTemplateSubject',111,112),(58,2,NULL,NULL,'Evaltools',114,125),(59,58,NULL,NULL,'index',115,116),(60,58,NULL,NULL,'add',117,118),(61,58,NULL,NULL,'edit',119,120),(62,58,NULL,NULL,'view',121,122),(63,58,NULL,NULL,'delete',123,124),(64,2,NULL,NULL,'Evaluations',126,165),(65,64,NULL,NULL,'setUpAjaxList',127,128),(66,64,NULL,NULL,'ajaxList',129,130),(67,64,NULL,NULL,'view',131,132),(68,64,NULL,NULL,'index',133,134),(69,64,NULL,NULL,'export',135,136),(70,64,NULL,NULL,'makeEvaluation',137,138),(71,64,NULL,NULL,'completeEvaluationRubric',139,140),(72,64,NULL,NULL,'viewEvaluationResults',141,142),(73,64,NULL,NULL,'studentViewEvaluationResult',143,144),(74,64,NULL,NULL,'markEventReviewed',145,146),(75,64,NULL,NULL,'markGradeRelease',147,148),(76,64,NULL,NULL,'markCommentRelease',149,150),(77,64,NULL,NULL,'changeAllCommentRelease',151,152),(78,64,NULL,NULL,'changeAllGradeRelease',153,154),(79,64,NULL,NULL,'viewGroupSubmissionDetails',155,156),(80,64,NULL,NULL,'viewSurveySummary',157,158),(81,64,NULL,NULL,'add',159,160),(82,64,NULL,NULL,'edit',161,162),(83,64,NULL,NULL,'delete',163,164),(84,2,NULL,NULL,'Events',166,193),(85,84,NULL,NULL,'postProcessData',167,168),(86,84,NULL,NULL,'setUpAjaxList',169,170),(87,84,NULL,NULL,'index',171,172),(88,84,NULL,NULL,'ajaxList',173,174),(89,84,NULL,NULL,'view',175,176),(90,84,NULL,NULL,'add',177,178),(91,84,NULL,NULL,'setSchedule',179,180),(92,84,NULL,NULL,'getGroupMembers',181,182),(93,84,NULL,NULL,'edit',183,184),(94,84,NULL,NULL,'checkIfChanged',185,186),(95,84,NULL,NULL,'calculateFrequency',187,188),(96,84,NULL,NULL,'delete',189,190),(97,84,NULL,NULL,'checkDuplicateName',191,192),(98,2,NULL,NULL,'Faculties',194,205),(99,98,NULL,NULL,'index',195,196),(100,98,NULL,NULL,'view',197,198),(101,98,NULL,NULL,'add',199,200),(102,98,NULL,NULL,'edit',201,202),(103,98,NULL,NULL,'delete',203,204),(104,2,NULL,NULL,'Framework',206,221),(105,104,NULL,NULL,'calendarDisplay',207,208),(106,104,NULL,NULL,'tutIndex',209,210),(107,104,NULL,NULL,'add',211,212),(108,104,NULL,NULL,'edit',213,214),(109,104,NULL,NULL,'index',215,216),(110,104,NULL,NULL,'view',217,218),(111,104,NULL,NULL,'delete',219,220),(112,2,NULL,NULL,'Groups',222,241),(113,112,NULL,NULL,'setUpAjaxList',223,224),(114,112,NULL,NULL,'index',225,226),(115,112,NULL,NULL,'ajaxList',227,228),(116,112,NULL,NULL,'view',229,230),(117,112,NULL,NULL,'add',231,232),(118,112,NULL,NULL,'edit',233,234),(119,112,NULL,NULL,'delete',235,236),(120,112,NULL,NULL,'import',237,238),(121,112,NULL,NULL,'export',239,240),(122,2,NULL,NULL,'Home',242,253),(123,122,NULL,NULL,'index',243,244),(124,122,NULL,NULL,'add',245,246),(125,122,NULL,NULL,'edit',247,248),(126,122,NULL,NULL,'view',249,250),(127,122,NULL,NULL,'delete',251,252),(128,2,NULL,NULL,'Install',254,275),(129,128,NULL,NULL,'index',255,256),(130,128,NULL,NULL,'install2',257,258),(131,128,NULL,NULL,'install3',259,260),(132,128,NULL,NULL,'install4',261,262),(133,128,NULL,NULL,'install5',263,264),(134,128,NULL,NULL,'gpl',265,266),(135,128,NULL,NULL,'add',267,268),(136,128,NULL,NULL,'edit',269,270),(137,128,NULL,NULL,'view',271,272),(138,128,NULL,NULL,'delete',273,274),(139,2,NULL,NULL,'Lti',276,287),(140,139,NULL,NULL,'index',277,278),(141,139,NULL,NULL,'add',279,280),(142,139,NULL,NULL,'edit',281,282),(143,139,NULL,NULL,'view',283,284),(144,139,NULL,NULL,'delete',285,286),(145,2,NULL,NULL,'Mixevals',288,305),(146,145,NULL,NULL,'setUpAjaxList',289,290),(147,145,NULL,NULL,'index',291,292),(148,145,NULL,NULL,'ajaxList',293,294),(149,145,NULL,NULL,'view',295,296),(150,145,NULL,NULL,'add',297,298),(151,145,NULL,NULL,'edit',299,300),(152,145,NULL,NULL,'copy',301,302),(153,145,NULL,NULL,'delete',303,304),(154,2,NULL,NULL,'Oauthclients',306,317),(155,154,NULL,NULL,'index',307,308),(156,154,NULL,NULL,'add',309,310),(157,154,NULL,NULL,'edit',311,312),(158,154,NULL,NULL,'delete',313,314),(159,154,NULL,NULL,'view',315,316),(160,2,NULL,NULL,'Oauthtokens',318,329),(161,160,NULL,NULL,'index',319,320),(162,160,NULL,NULL,'add',321,322),(163,160,NULL,NULL,'edit',323,324),(164,160,NULL,NULL,'delete',325,326),(165,160,NULL,NULL,'view',327,328),(166,2,NULL,NULL,'Penalty',330,343),(167,166,NULL,NULL,'save',331,332),(168,166,NULL,NULL,'add',333,334),(169,166,NULL,NULL,'edit',335,336),(170,166,NULL,NULL,'index',337,338),(171,166,NULL,NULL,'view',339,340),(172,166,NULL,NULL,'delete',341,342),(173,2,NULL,NULL,'Rubrics',344,363),(174,173,NULL,NULL,'postProcess',345,346),(175,173,NULL,NULL,'setUpAjaxList',347,348),(176,173,NULL,NULL,'index',349,350),(177,173,NULL,NULL,'ajaxList',351,352),(178,173,NULL,NULL,'view',353,354),(179,173,NULL,NULL,'add',355,356),(180,173,NULL,NULL,'edit',357,358),(181,173,NULL,NULL,'copy',359,360),(182,173,NULL,NULL,'delete',361,362),(183,2,NULL,NULL,'Searchs',364,391),(184,183,NULL,NULL,'update',365,366),(185,183,NULL,NULL,'index',367,368),(186,183,NULL,NULL,'searchEvaluation',369,370),(187,183,NULL,NULL,'searchResult',371,372),(188,183,NULL,NULL,'searchInstructor',373,374),(189,183,NULL,NULL,'eventBoxSearch',375,376),(190,183,NULL,NULL,'formatSearchEvaluation',377,378),(191,183,NULL,NULL,'formatSearchInstructor',379,380),(192,183,NULL,NULL,'formatSearchEvaluationResult',381,382),(193,183,NULL,NULL,'add',383,384),(194,183,NULL,NULL,'edit',385,386),(195,183,NULL,NULL,'view',387,388),(196,183,NULL,NULL,'delete',389,390),(197,2,NULL,NULL,'Simpleevaluations',392,411),(198,197,NULL,NULL,'postProcess',393,394),(199,197,NULL,NULL,'setUpAjaxList',395,396),(200,197,NULL,NULL,'index',397,398),(201,197,NULL,NULL,'ajaxList',399,400),(202,197,NULL,NULL,'view',401,402),(203,197,NULL,NULL,'add',403,404),(204,197,NULL,NULL,'edit',405,406),(205,197,NULL,NULL,'copy',407,408),(206,197,NULL,NULL,'delete',409,410),(207,2,NULL,NULL,'Surveygroups',412,443),(208,207,NULL,NULL,'postProcess',413,414),(209,207,NULL,NULL,'setUpAjaxList',415,416),(210,207,NULL,NULL,'index',417,418),(211,207,NULL,NULL,'ajaxList',419,420),(212,207,NULL,NULL,'makegroups',421,422),(213,207,NULL,NULL,'makegroupssearch',423,424),(214,207,NULL,NULL,'maketmgroups',425,426),(215,207,NULL,NULL,'savegroups',427,428),(216,207,NULL,NULL,'release',429,430),(217,207,NULL,NULL,'delete',431,432),(218,207,NULL,NULL,'edit',433,434),(219,207,NULL,NULL,'changegroupset',435,436),(220,207,NULL,NULL,'export',437,438),(221,207,NULL,NULL,'add',439,440),(222,207,NULL,NULL,'view',441,442),(223,2,NULL,NULL,'Surveys',444,473),(224,223,NULL,NULL,'setUpAjaxList',445,446),(225,223,NULL,NULL,'index',447,448),(226,223,NULL,NULL,'ajaxList',449,450),(227,223,NULL,NULL,'view',451,452),(228,223,NULL,NULL,'add',453,454),(229,223,NULL,NULL,'edit',455,456),(230,223,NULL,NULL,'copy',457,458),(231,223,NULL,NULL,'delete',459,460),(232,223,NULL,NULL,'questionsSummary',461,462),(233,223,NULL,NULL,'moveQuestion',463,464),(234,223,NULL,NULL,'removeQuestion',465,466),(235,223,NULL,NULL,'addQuestion',467,468),(236,223,NULL,NULL,'editQuestion',469,470),(237,223,NULL,NULL,'surveyAccess',471,472),(238,2,NULL,NULL,'Sysparameters',474,489),(239,238,NULL,NULL,'setUpAjaxList',475,476),(240,238,NULL,NULL,'index',477,478),(241,238,NULL,NULL,'ajaxList',479,480),(242,238,NULL,NULL,'view',481,482),(243,238,NULL,NULL,'add',483,484),(244,238,NULL,NULL,'edit',485,486),(245,238,NULL,NULL,'delete',487,488),(246,2,NULL,NULL,'Upgrade',490,503),(247,246,NULL,NULL,'index',491,492),(248,246,NULL,NULL,'step2',493,494),(249,246,NULL,NULL,'add',495,496),(250,246,NULL,NULL,'edit',497,498),(251,246,NULL,NULL,'view',499,500),(252,246,NULL,NULL,'delete',501,502),(253,2,NULL,NULL,'Users',504,543),(254,253,NULL,NULL,'ajaxList',505,506),(255,253,NULL,NULL,'index',507,508),(256,253,NULL,NULL,'goToClassList',509,510),(257,253,NULL,NULL,'determineIfStudentFromThisData',511,512),(258,253,NULL,NULL,'view',513,514),(259,253,NULL,NULL,'add',515,516),(260,253,NULL,NULL,'enrol',517,518),(261,253,NULL,NULL,'readd',519,520),(262,253,NULL,NULL,'edit',521,522),(263,253,NULL,NULL,'editProfile',523,524),(264,253,NULL,NULL,'delete',525,526),(265,253,NULL,NULL,'checkDuplicateName',527,528),(266,253,NULL,NULL,'resetPassword',529,530),(267,253,NULL,NULL,'resetPasswordWithoutEmail',531,532),(268,253,NULL,NULL,'import',533,534),(269,253,NULL,NULL,'merge',535,536),(270,253,NULL,NULL,'ajax_merge',537,538),(271,253,NULL,NULL,'update',539,540),(272,253,NULL,NULL,'showEvents',541,542),(273,2,NULL,NULL,'V1',544,579),(274,273,NULL,NULL,'oauth',545,546),(275,273,NULL,NULL,'oauth_error',547,548),(276,273,NULL,NULL,'users',549,550),(277,273,NULL,NULL,'courses',551,552),(278,273,NULL,NULL,'groups',553,554),(279,273,NULL,NULL,'groupMembers',555,556),(280,273,NULL,NULL,'events',557,558),(281,273,NULL,NULL,'grades',559,560),(282,273,NULL,NULL,'departments',561,562),(283,273,NULL,NULL,'courseDepartments',563,564),(284,273,NULL,NULL,'userEvents',565,566),(285,273,NULL,NULL,'enrolment',567,568),(286,273,NULL,NULL,'add',569,570),(287,273,NULL,NULL,'edit',571,572),(288,273,NULL,NULL,'index',573,574),(289,273,NULL,NULL,'view',575,576),(290,273,NULL,NULL,'delete',577,578),(291,2,NULL,NULL,'Guard',580,597),(292,291,NULL,NULL,'Guard',581,596),(293,292,NULL,NULL,'login',582,583),(294,292,NULL,NULL,'logout',584,585),(295,292,NULL,NULL,'add',586,587),(296,292,NULL,NULL,'edit',588,589),(297,292,NULL,NULL,'index',590,591),(298,292,NULL,NULL,'view',592,593),(299,292,NULL,NULL,'delete',594,595),(300,NULL,NULL,NULL,'functions',599,664),(301,300,NULL,NULL,'user',600,627),(302,301,NULL,NULL,'superadmin',601,602),(303,301,NULL,NULL,'admin',603,604),(304,301,NULL,NULL,'instructor',605,606),(305,301,NULL,NULL,'tutor',607,608),(306,301,NULL,NULL,'student',609,610),(307,301,NULL,NULL,'import',611,612),(308,301,NULL,NULL,'password_reset',613,624),(309,308,NULL,NULL,'superadmin',614,615),(310,308,NULL,NULL,'admin',616,617),(311,308,NULL,NULL,'instructor',618,619),(312,308,NULL,NULL,'tutor',620,621),(313,308,NULL,NULL,'student',622,623),(314,301,NULL,NULL,'index',625,626),(315,300,NULL,NULL,'role',628,639),(316,315,NULL,NULL,'superadmin',629,630),(317,315,NULL,NULL,'admin',631,632),(318,315,NULL,NULL,'instructor',633,634),(319,315,NULL,NULL,'tutor',635,636),(320,315,NULL,NULL,'student',637,638),(321,300,NULL,NULL,'evaluation',640,641),(322,300,NULL,NULL,'email',642,649),(323,322,NULL,NULL,'allUsers',643,644),(324,322,NULL,NULL,'allGroups',645,646),(325,322,NULL,NULL,'allCourses',647,648),(326,300,NULL,NULL,'emailtemplate',650,651),(327,300,NULL,NULL,'viewstudentresults',652,653),(328,300,NULL,NULL,'viewemailaddresses',654,655),(329,300,NULL,NULL,'superadmin',656,657),(330,300,NULL,NULL,'coursemanager',658,659),(331,300,NULL,NULL,'viewusername',660,661),(332,300,NULL,NULL,'submitstudenteval',662,663),(333,84,NULL,NULL,'export',193,194),(334,84,NULL,NULL,'import',195,196),(335,16,NULL,NULL,'syncCanvasEnrollment',NULL,NULL),(336,112,NULL,NULL,'syncCanvas',NULL,NULL),(337,64,NULL,NULL,'exportCanvas',NULL,NULL);
/*!40000 ALTER TABLE `acos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aros`
--

DROP TABLE IF EXISTS `aros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aros`
--

LOCK TABLES `aros` WRITE;
/*!40000 ALTER TABLE `aros` DISABLE KEYS */;
INSERT INTO `aros` VALUES (1,NULL,'Role',1,NULL,1,2),(2,NULL,'Role',2,NULL,3,4),(3,NULL,'Role',3,NULL,5,6),(4,NULL,'Role',4,NULL,7,8),(5,NULL,'Role',5,NULL,9,10);
/*!40000 ALTER TABLE `aros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aros_acos`
--

LOCK TABLES `aros_acos` WRITE;
/*!40000 ALTER TABLE `aros_acos` DISABLE KEYS */;
INSERT INTO `aros_acos` VALUES (1,1,2,'1','1','1','1'),(2,1,300,'1','1','1','1'),(3,1,1,'1','1','1','1'),(4,2,2,'-1','-1','-1','-1'),(5,2,122,'1','1','1','1'),(6,2,16,'1','1','1','1'),(7,2,28,'1','1','1','1'),(8,2,31,'-1','-1','-1','-1'),(9,2,30,'-1','-1','-1','-1'),(10,2,33,'-1','-1','-1','-1'),(11,2,32,'-1','-1','-1','-1'),(12,2,29,'-1','-1','-1','-1'),(13,2,34,'1','1','1','1'),(14,2,48,'1','1','1','1'),(15,2,58,'1','1','1','1'),(16,2,64,'1','1','1','1'),(17,2,84,'1','1','1','1'),(18,2,112,'1','1','1','1'),(19,2,145,'1','1','1','1'),(20,2,173,'1','1','1','1'),(21,2,197,'1','1','1','1'),(22,2,223,'1','1','1','1'),(23,2,207,'1','1','1','1'),(24,2,253,'1','1','1','1'),(25,2,267,'-1','-1','-1','-1'),(26,2,294,'1','1','1','1'),(27,2,300,'-1','-1','-1','-1'),(28,2,326,'1','1','1','1'),(29,2,321,'1','1','1','1'),(30,2,323,'1','1','1','1'),(31,2,301,'1','1','1','1'),(32,2,303,'1','1','1','-1'),(33,2,302,'-1','-1','-1','-1'),(34,2,328,'1','1','1','1'),(35,2,331,'1','1','1','1'),(36,2,330,'1','1','1','1'),(37,2,329,'-1','-1','-1','-1'),(38,2,332,'1','1','1','1'),(39,3,2,'-1','-1','-1','-1'),(40,3,122,'1','1','1','1'),(41,3,16,'1','1','1','1'),(42,3,34,'1','1','1','1'),(43,3,48,'1','1','1','1'),(44,3,58,'1','1','1','1'),(45,3,64,'1','1','1','1'),(46,3,84,'1','1','1','1'),(47,3,112,'1','1','1','1'),(48,3,145,'1','1','1','1'),(49,3,173,'1','1','1','1'),(50,3,197,'1','1','1','1'),(51,3,223,'1','1','1','1'),(52,3,207,'1','1','1','1'),(53,3,253,'1','1','1','1'),(54,3,294,'1','1','1','1'),(55,3,156,'1','1','1','1'),(56,3,158,'1','1','1','1'),(57,3,162,'1','1','1','1'),(58,3,164,'1','1','1','1'),(59,3,269,'-1','-1','-1','-1'),(60,3,272,'1','1','1','1'),(61,3,267,'-1','-1','-1','-1'),(62,3,300,'-1','-1','-1','-1'),(63,3,321,'1','1','-1','-1'),(64,3,301,'1','1','1','1'),(65,3,303,'-1','-1','-1','-1'),(66,3,302,'-1','-1','-1','-1'),(67,3,304,'-1','1','-1','-1'),(68,3,314,'-1','-1','-1','-1'),(69,3,328,'-1','-1','-1','-1'),(70,3,329,'-1','-1','-1','-1'),(71,3,330,'1','1','1','1'),(72,3,332,'-1','-1','-1','-1'),(73,4,2,'-1','-1','-1','-1'),(74,4,122,'1','1','1','1'),(75,4,16,'-1','-1','-1','-1'),(76,4,34,'-1','-1','-1','-1'),(77,4,48,'-1','-1','-1','-1'),(78,4,58,'-1','-1','-1','-1'),(79,4,84,'-1','-1','-1','-1'),(80,4,112,'-1','-1','-1','-1'),(81,4,145,'-1','-1','-1','-1'),(82,4,173,'-1','-1','-1','-1'),(83,4,197,'-1','-1','-1','-1'),(84,4,223,'-1','-1','-1','-1'),(85,4,207,'-1','-1','-1','-1'),(86,4,253,'-1','-1','-1','-1'),(87,4,294,'1','1','1','1'),(88,4,70,'1','1','1','1'),(89,4,73,'1','1','1','1'),(90,4,71,'1','1','1','1'),(91,4,263,'1','1','1','1'),(92,4,300,'-1','-1','-1','-1'),(93,4,328,'-1','-1','-1','-1'),(94,4,329,'-1','-1','-1','-1'),(95,5,2,'-1','-1','-1','-1'),(96,5,122,'1','1','1','1'),(97,5,16,'-1','-1','-1','-1'),(98,5,34,'-1','-1','-1','-1'),(99,5,48,'-1','-1','-1','-1'),(100,5,58,'-1','-1','-1','-1'),(101,5,84,'-1','-1','-1','-1'),(102,5,112,'-1','-1','-1','-1'),(103,5,145,'-1','-1','-1','-1'),(104,5,173,'-1','-1','-1','-1'),(105,5,197,'-1','-1','-1','-1'),(106,5,223,'-1','-1','-1','-1'),(107,5,207,'-1','-1','-1','-1'),(108,5,253,'-1','-1','-1','-1'),(109,5,294,'1','1','1','1'),(110,5,70,'1','1','1','1'),(111,5,73,'1','1','1','1'),(112,5,71,'1','1','1','1'),(113,5,263,'1','1','1','1'),(114,5,156,'1','1','1','1'),(115,5,158,'1','1','1','1'),(116,5,162,'1','1','1','1'),(117,5,164,'1','1','1','1'),(118,5,300,'-1','-1','-1','-1'),(119,5,327,'1','1','1','1'),(120,5,328,'-1','-1','-1','-1'),(121,5,329,'-1','-1','-1','-1');
/*!40000 ALTER TABLE `aros_acos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cake_sessions`
--

DROP TABLE IF EXISTS `cake_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cake_sessions` (
  `id` varchar(255) NOT NULL,
  `data` text,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cake_sessions`
--

LOCK TABLES `cake_sessions` WRITE;
/*!40000 ALTER TABLE `cake_sessions` DISABLE KEYS */;
INSERT INTO `cake_sessions` VALUES ('a8945189d0d8c824dc2b16c6c0692558','Config|a:3:{s:9:\"userAgent\";s:32:\"e61182efa419bd1b193ec7eaf82da8af\";s:4:\"time\";i:1582752805;s:7:\"timeout\";i:10;}data_setup_option|s:1:\"A\";Message|a:0:{}Auth|a:1:{s:4:\"User\";a:20:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:4:\"root\";s:10:\"first_name\";s:5:\"Super\";s:9:\"last_name\";s:5:\"Admin\";s:10:\"student_no\";N;s:5:\"title\";N;s:5:\"email\";s:0:\"\";s:10:\"last_login\";N;s:11:\"last_logout\";N;s:13:\"last_accessed\";N;s:13:\"record_status\";s:1:\"A\";s:10:\"creator_id\";s:1:\"1\";s:7:\"created\";s:19:\"2020-02-26 18:32:35\";s:10:\"updater_id\";N;s:8:\"modified\";s:19:\"2020-02-26 10:32:37\";s:6:\"lti_id\";N;s:9:\"full_name\";s:11:\"Super Admin\";s:25:\"student_no_with_full_name\";s:11:\"Super Admin\";s:7:\"creator\";s:11:\"Super Admin\";s:7:\"updater\";N;}}ipeerSession|a:4:{s:5:\"Roles\";a:1:{i:1;s:10:\"superadmin\";}s:12:\"IsInstructor\";b:0;s:16:\"IsStudentOrTutor\";b:0;s:11:\"Permissions\";a:337:{s:9:\"adminpage\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:11:\"controllers\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:17:\"controllers/pages\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/pages/display\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/pages/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"controllers/pages/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/pages/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"controllers/pages/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/pages/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"controllers/accesses\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/accesses/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/accesses/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/accesses/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/accesses/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/accesses/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/courses\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/courses/dayslate\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/courses/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/courses/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/courses/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/courses/home\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/courses/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/courses/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/courses/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/courses/move\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/courses/ajax_options\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/courses/import\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:40:\"controllers/courses/synccanvasenrollment\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/departments\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/departments/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/departments/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/departments/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/departments/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/departments/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/emailer\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:33:\"controllers/emailer/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/emailer/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/emailer/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/emailer/write\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/emailer/cancel\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/emailer/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/emailer/addrecipient\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:35:\"controllers/emailer/deleterecipient\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/emailer/getrecipient\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/emailer/searchbyuserid\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/emailer/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/emailer/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/emailer/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/emailtemplates\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:40:\"controllers/emailtemplates/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:35:\"controllers/emailtemplates/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/emailtemplates/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/emailtemplates/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/emailtemplates/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:33:\"controllers/emailtemplates/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/emailtemplates/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:49:\"controllers/emailtemplates/displaytemplatecontent\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:49:\"controllers/emailtemplates/displaytemplatesubject\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/evaltools\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/evaltools/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/evaltools/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/evaltools/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/evaltools/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/evaltools/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/evaluations\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:37:\"controllers/evaluations/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/evaluations/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/evaluations/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/evaluations/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/evaluations/export\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:38:\"controllers/evaluations/makeevaluation\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:48:\"controllers/evaluations/completeevaluationrubric\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:45:\"controllers/evaluations/viewevaluationresults\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:51:\"controllers/evaluations/studentviewevaluationresult\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:41:\"controllers/evaluations/markeventreviewed\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:40:\"controllers/evaluations/markgraderelease\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:42:\"controllers/evaluations/markcommentrelease\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:47:\"controllers/evaluations/changeallcommentrelease\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:45:\"controllers/evaluations/changeallgraderelease\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:50:\"controllers/evaluations/viewgroupsubmissiondetails\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:41:\"controllers/evaluations/viewsurveysummary\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/evaluations/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/evaluations/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/evaluations/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:36:\"controllers/evaluations/exportcanvas\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:18:\"controllers/events\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/events/postprocessdata\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/events/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/events/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/events/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/events/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"controllers/events/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/events/setschedule\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/events/getgroupmembers\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/events/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:33:\"controllers/events/checkifchanged\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:37:\"controllers/events/calculatefrequency\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/events/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:37:\"controllers/events/checkduplicatename\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/events/export\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/events/import\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/faculties\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/faculties/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/faculties/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/faculties/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/faculties/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/faculties/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/framework\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:37:\"controllers/framework/calendardisplay\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/framework/tutindex\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/framework/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/framework/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/framework/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/framework/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/framework/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:18:\"controllers/groups\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/groups/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/groups/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/groups/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/groups/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"controllers/groups/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/groups/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/groups/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/groups/import\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/groups/export\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/groups/synccanvas\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:16:\"controllers/home\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"controllers/home/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"controllers/home/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/home/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/home/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/home/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/install\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/install/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/install/install2\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/install/install3\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/install/install4\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/install/install5\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/install/gpl\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/install/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/install/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/install/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/install/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:15:\"controllers/lti\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/lti/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/lti/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"controllers/lti/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"controllers/lti/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"controllers/lti/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"controllers/mixevals\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/mixevals/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/mixevals/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/mixevals/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/mixevals/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/mixevals/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/mixevals/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/mixevals/copy\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/mixevals/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/oauthclients\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/oauthclients/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/oauthclients/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/oauthclients/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/oauthclients/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/oauthclients/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/oauthtokens\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/oauthtokens/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/oauthtokens/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/oauthtokens/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/oauthtokens/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/oauthtokens/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/penalty\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/penalty/save\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/penalty/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/penalty/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/penalty/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/penalty/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/penalty/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/rubrics\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/rubrics/postprocess\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:33:\"controllers/rubrics/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/rubrics/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/rubrics/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/rubrics/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/rubrics/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/rubrics/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/rubrics/copy\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/rubrics/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/searchs\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/searchs/update\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/searchs/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:36:\"controllers/searchs/searchevaluation\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/searchs/searchresult\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:36:\"controllers/searchs/searchinstructor\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/searchs/eventboxsearch\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:42:\"controllers/searchs/formatsearchevaluation\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:42:\"controllers/searchs/formatsearchinstructor\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:48:\"controllers/searchs/formatsearchevaluationresult\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/searchs/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/searchs/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/searchs/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/searchs/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/simpleevaluations\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:41:\"controllers/simpleevaluations/postprocess\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:43:\"controllers/simpleevaluations/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:35:\"controllers/simpleevaluations/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:38:\"controllers/simpleevaluations/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/simpleevaluations/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:33:\"controllers/simpleevaluations/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/simpleevaluations/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/simpleevaluations/copy\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:36:\"controllers/simpleevaluations/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/surveygroups\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:36:\"controllers/surveygroups/postprocess\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:38:\"controllers/surveygroups/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/surveygroups/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:33:\"controllers/surveygroups/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:35:\"controllers/surveygroups/makegroups\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:41:\"controllers/surveygroups/makegroupssearch\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:37:\"controllers/surveygroups/maketmgroups\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:35:\"controllers/surveygroups/savegroups\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/surveygroups/release\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/surveygroups/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/surveygroups/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:39:\"controllers/surveygroups/changegroupset\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/surveygroups/export\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/surveygroups/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/surveygroups/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/surveys\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:33:\"controllers/surveys/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/surveys/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/surveys/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/surveys/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/surveys/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/surveys/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/surveys/copy\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/surveys/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:36:\"controllers/surveys/questionssummary\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/surveys/movequestion\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/surveys/removequestion\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/surveys/addquestion\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/surveys/editquestion\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/surveys/surveyaccess\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/sysparameters\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:39:\"controllers/sysparameters/setupajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/sysparameters/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:34:\"controllers/sysparameters/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/sysparameters/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/sysparameters/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/sysparameters/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/sysparameters/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/upgrade\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/upgrade/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/upgrade/step2\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/upgrade/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/upgrade/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/upgrade/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/upgrade/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:17:\"controllers/users\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/users/ajaxlist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/users/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/users/gotoclasslist\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:48:\"controllers/users/determineifstudentfromthisdata\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"controllers/users/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/users/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/users/enrol\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/users/readd\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"controllers/users/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/users/editprofile\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/users/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:36:\"controllers/users/checkduplicatename\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:31:\"controllers/users/resetpassword\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:43:\"controllers/users/resetpasswordwithoutemail\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/users/import\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/users/merge\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/users/ajax_merge\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/users/update\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/users/showevents\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:14:\"controllers/v1\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"controllers/v1/oauth\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/v1/oauth_error\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"controllers/v1/users\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"controllers/v1/courses\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/v1/groups\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/v1/groupmembers\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/v1/events\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/v1/grades\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"controllers/v1/departments\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:32:\"controllers/v1/coursedepartments\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"controllers/v1/userevents\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"controllers/v1/enrolment\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:18:\"controllers/v1/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/v1/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"controllers/v1/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:19:\"controllers/v1/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"controllers/v1/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:17:\"controllers/guard\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"controllers/guard/guard\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/guard/guard/login\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/guard/guard/logout\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"controllers/guard/guard/add\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/guard/guard/edit\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"controllers/guard/guard/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"controllers/guard/guard/view\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:30:\"controllers/guard/guard/delete\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:9:\"functions\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:14:\"functions/user\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"functions/user/superadmin\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"functions/user/admin\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"functions/user/instructor\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"functions/user/tutor\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"functions/user/student\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:21:\"functions/user/import\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:29:\"functions/user/password_reset\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:40:\"functions/user/password_reset/superadmin\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:35:\"functions/user/password_reset/admin\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:40:\"functions/user/password_reset/instructor\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:35:\"functions/user/password_reset/tutor\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:37:\"functions/user/password_reset/student\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"functions/user/index\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:14:\"functions/role\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"functions/role/superadmin\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"functions/role/admin\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"functions/role/instructor\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"functions/role/tutor\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"functions/role/student\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"functions/evaluation\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:15:\"functions/email\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:24:\"functions/email/allusers\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:25:\"functions/email/allgroups\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:26:\"functions/email/allcourses\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"functions/emailtemplate\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"functions/viewstudentresults\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:28:\"functions/viewemailaddresses\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:20:\"functions/superadmin\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:23:\"functions/coursemanager\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:22:\"functions/viewusername\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}s:27:\"functions/submitstudenteval\";a:4:{i:0;s:6:\"create\";i:1;s:4:\"read\";i:2;s:6:\"update\";i:3;s:6:\"delete\";}}}',1582752806);
/*!40000 ALTER TABLE `cake_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_departments`
--

DROP TABLE IF EXISTS `course_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `course_departments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_departments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_departments`
--

LOCK TABLES `course_departments` WRITE;
/*!40000 ALTER TABLE `course_departments` DISABLE KEYS */;
INSERT INTO `course_departments` VALUES (1,2,2),(2,1,1),(3,3,3),(4,3,2),(5,4,3);
/*!40000 ALTER TABLE `course_departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(80) DEFAULT NULL,
  `homepage` varchar(100) DEFAULT NULL,
  `self_enroll` varchar(3) DEFAULT 'off',
  `password` varchar(25) DEFAULT NULL,
  `record_status` varchar(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `canvas_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course` (`course`),
  KEY `canvas_id` (`canvas_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'MECH 328','Mechanical Engineering Design Project','http://www.mech.ubc.ca','off',NULL,'A',1,'2006-06-20 14:14:45',NULL,'2006-06-20 14:14:45',NULL),(2,'APSC 201','Technical Communication','http://www.apsc.ubc.ca','off',NULL,'A',1,'2006-06-20 14:15:38',NULL,'2006-06-20 14:39:31',NULL),(3,'CPSC 101','Connecting with Computer Science','http://www.ugrad.cs.ubc.ca/~cs101/','off',NULL,'I',1,'2006-06-20 00:00:00',NULL,NULL,NULL),(4,'CPSC 404','Advanced Software Engineering','http://www.ugrad.cs.ubc.ca/~cs404/','off',NULL,'A',1,'2014-12-15 00:00:00',NULL,'2014-12-15 00:00:00',NULL);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'MECH',1,0,'2020-02-26 18:32:35',NULL,'2012-05-23 11:30:41'),(2,'APSC',1,0,'2020-02-26 18:32:35',NULL,'2012-05-23 11:30:57'),(3,'CPSC',2,0,'2020-02-26 18:32:35',NULL,'2012-05-23 11:31:07');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_merges`
--

DROP TABLE IF EXISTS `email_merges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_merges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `value` varchar(80) NOT NULL,
  `table_name` varchar(80) NOT NULL,
  `field_name` varchar(80) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_merges`
--

LOCK TABLES `email_merges` WRITE;
/*!40000 ALTER TABLE `email_merges` DISABLE KEYS */;
INSERT INTO `email_merges` VALUES (1,'Username','{{{USERNAME}}}','User','username','2020-02-26 18:32:35','2020-02-26 18:32:35'),(2,'First Name','{{{FIRSTNAME}}}','User','first_name','2020-02-26 18:32:35','2020-02-26 18:32:35'),(3,'Last Name','{{{LASTNAME}}}','User','last_name','2020-02-26 18:32:35','2020-02-26 18:32:35'),(4,'Email Address','{{{Email}}}','User','email','2020-02-26 18:32:35','2020-02-26 18:32:35'),(5,'Course Name','{{{COURSENAME}}}','Course','course','2020-02-26 18:32:35','2020-02-26 18:32:35'),(6,'Event Title','{{{EVENTTITLE}}}','Event','title','2020-02-26 18:32:35','2020-02-26 18:32:35'),(7,'Event Due Date','{{{DUEDATE}}}','Event','due_date','2020-02-26 18:32:35','2020-02-26 18:32:35'),(8,'Event Close Date','{{{CLOSEDATE}}}','Event','release_date_end','2020-02-26 18:32:35','2020-02-26 18:32:35');
/*!40000 ALTER TABLE `email_merges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_schedules`
--

DROP TABLE IF EXISTS `email_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `date` datetime DEFAULT NULL,
  `from` varchar(80) NOT NULL,
  `to` text NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `grp_id` int(11) DEFAULT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `creator_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_schedules`
--

LOCK TABLES `email_schedules` WRITE;
/*!40000 ALTER TABLE `email_schedules` DISABLE KEYS */;
INSERT INTO `email_schedules` VALUES (1,'Email Template','Hello, {{{FIRSTNAME}}}','2021-07-18 16:52:31','1','5;6;7;13;15;17;19;21;26;28;31;32;33',NULL,NULL,NULL,0,1,'2012-07-16 16:52:50'),(2,'Email Template','Hello, {{{USERNAME}}}','2011-07-18 16:52:31','1','5;6;7;13;15;17;19;21;26;28;31;32;33',NULL,NULL,NULL,0,1,'2010-07-16 16:57:50'),(3,'Email Template','Hi, {{{USERNAME}}}','2011-07-18 17:52:31','1','5;6;7;13;15;17;19;21;26;28;31;32;33',NULL,NULL,NULL,1,1,'2010-07-16 16:57:50');
/*!40000 ALTER TABLE `email_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `description` text NOT NULL,
  `subject` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `availability` tinyint(4) NOT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
INSERT INTO `email_templates` VALUES (2,'Email template example','This is an email template example','Email Template','Hello, {{{USERNAME}}}',1,1,'2020-02-26 18:32:35',NULL,NULL),(3,'Email template example2','email template ex2','Email Template2','Hello, {{{FIRSTNAME}}}',1,2,'2020-02-26 18:32:35',NULL,NULL),(4,'Email template example3','email temp example3','Email Template3','Hello,',1,3,'2020-02-26 18:32:35',NULL,NULL),(5,'Evaluation Reminder Template','evaluation reminder template','iPeer Evaluation Reminder','Hello {{{FIRSTNAME}}},\n\nA evaluation for {{{COURSENAME}}} is made available to you in iPeer, which has yet to be completed.\n\nName: {{{EVENTTITLE}}}\nDue Date: {{{DUEDATE}}}\nClose Date: {{{CLOSEDATE}}}\n\nThank You',1,1,'2020-02-26 18:32:35',NULL,NULL),(6,'MECH 328 Evaluation Reminder Template','MECH 328 evaluation reminder template','MECH 328 - iPeer Evaluation Reminder','Hello {{{FIRSTNAME}}},\n\nA evaluation for {{{COURSENAME}}} is made available to you in iPeer, which has yet to be completed.\n\nName: {{{EVENTTITLE}}}\nDue Date: {{{DUEDATE}}}\nClose Date: {{{CLOSEDATE}}}\n\nThank You',1,1,'2020-02-26 18:32:35',NULL,NULL),(7,'MECH 328 Survey Reminder Template','MECH 328 survey reminder template','MECH 328 - iPeer Survey Reminder','Hello {{{FIRSTNAME}}},\n\nA evaluation for {{{COURSENAME}}} is made available to you in iPeer, which has yet to be completed.\n\nName: {{{EVENTTITLE}}}\nDue Date: {{{DUEDATE}}}\nClose Date: {{{CLOSEDATE}}}\n\nThank You',1,1,'2020-02-26 18:32:35',NULL,NULL);
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_mixeval_details`
--

DROP TABLE IF EXISTS `evaluation_mixeval_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_mixeval_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluation_mixeval_id` int(11) NOT NULL DEFAULT '0',
  `question_number` int(11) NOT NULL DEFAULT '0',
  `question_comment` text,
  `selected_lom` int(11) NOT NULL DEFAULT '0',
  `grade` double(12,2) NOT NULL DEFAULT '0.00',
  `comment_release` int(1) NOT NULL DEFAULT '0',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evaluation_mixeval_id` (`evaluation_mixeval_id`,`question_number`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation_mixeval_details`
--

LOCK TABLES `evaluation_mixeval_details` WRITE;
/*!40000 ALTER TABLE `evaluation_mixeval_details` DISABLE KEYS */;
INSERT INTO `evaluation_mixeval_details` VALUES (1,1,1,NULL,5,1.00,0,'A',7,'2012-07-13 10:38:20',7,'2012-07-13 10:38:20'),(2,1,2,NULL,5,1.00,0,'A',7,'2012-07-13 10:38:20',7,'2012-07-13 10:38:20'),(3,1,3,NULL,5,1.00,0,'A',7,'2012-07-13 10:38:20',7,'2012-07-13 10:38:20'),(4,1,4,'work very efficiently',0,0.00,0,'A',7,'2012-07-13 10:38:20',7,'2012-07-13 10:38:20'),(5,1,5,'Contributed his part',0,0.00,0,'A',7,'2012-07-13 10:38:20',7,'2012-07-13 10:38:20'),(6,1,6,'very easy to work with',0,0.00,0,'A',7,'2012-07-13 10:38:20',7,'2012-07-13 10:38:20'),(7,2,1,NULL,4,0.80,0,'A',7,'2012-07-13 10:39:28',7,'2012-07-13 10:39:28'),(8,2,2,NULL,4,0.80,0,'A',7,'2012-07-13 10:39:28',7,'2012-07-13 10:39:28'),(9,2,3,NULL,4,0.80,0,'A',7,'2012-07-13 10:39:28',7,'2012-07-13 10:39:28'),(10,2,4,'Yes',0,0.00,0,'A',7,'2012-07-13 10:39:28',7,'2012-07-13 10:39:28'),(11,2,5,'He contributed in all parts of the project.',0,0.00,0,'A',7,'2012-07-13 10:39:28',7,'2012-07-13 10:39:28'),(12,2,6,'He is very easy to communicate with.',0,0.00,0,'A',7,'2012-07-13 10:39:28',7,'2012-07-13 10:39:28'),(13,3,1,NULL,5,1.00,0,'A',31,'2012-07-13 10:42:49',31,'2012-07-13 10:42:49'),(14,3,2,NULL,5,1.00,0,'A',31,'2012-07-13 10:42:49',31,'2012-07-13 10:42:49'),(15,3,3,NULL,5,1.00,0,'A',31,'2012-07-13 10:42:49',31,'2012-07-13 10:42:49'),(16,3,4,'does great work',0,0.00,0,'A',31,'2012-07-13 10:42:49',31,'2012-07-13 10:42:49'),(17,3,5,'willing to do their part',0,0.00,0,'A',31,'2012-07-13 10:42:49',31,'2012-07-13 10:42:49'),(18,3,6,'absolutely easy to work with',0,0.00,0,'A',31,'2012-07-13 10:42:49',31,'2012-07-13 10:42:49'),(19,4,1,NULL,4,0.80,0,'A',31,'2012-07-13 10:43:59',31,'2012-07-13 10:43:59'),(20,4,2,NULL,4,0.80,0,'A',31,'2012-07-13 10:43:59',31,'2012-07-13 10:43:59'),(21,4,3,NULL,5,1.00,0,'A',31,'2012-07-13 10:43:59',31,'2012-07-13 10:43:59'),(22,4,4,'produce efficient work',0,0.00,0,'A',31,'2012-07-13 10:43:59',31,'2012-07-13 10:43:59'),(23,4,5,'definitely',0,0.00,0,'A',31,'2012-07-13 10:43:59',31,'2012-07-13 10:43:59'),(24,4,6,'very easy to get along with',0,0.00,0,'A',31,'2012-07-13 10:43:59',31,'2012-07-13 10:43:59'),(25,5,1,NULL,4,0.80,0,'A',5,'2012-07-13 15:19:26',5,'2012-07-13 15:19:26'),(26,5,2,NULL,4,0.80,0,'A',5,'2012-07-13 15:19:26',5,'2012-07-13 15:19:26'),(27,5,3,NULL,4,0.80,0,'A',5,'2012-07-13 15:19:26',5,'2012-07-13 15:19:26'),(28,5,4,'Yes',0,0.00,0,'A',5,'2012-07-13 15:19:26',5,'2012-07-13 15:19:26'),(29,5,5,'Yes',0,0.00,0,'A',5,'2012-07-13 15:19:26',5,'2012-07-13 15:19:26'),(30,5,6,'Yes',0,0.00,0,'A',5,'2012-07-13 15:19:26',5,'2012-07-13 15:19:26');
/*!40000 ALTER TABLE `evaluation_mixeval_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_mixevals`
--

DROP TABLE IF EXISTS `evaluation_mixevals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_mixevals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluator` int(11) NOT NULL DEFAULT '0',
  `evaluatee` int(11) NOT NULL DEFAULT '0',
  `score` double(12,2) NOT NULL DEFAULT '0.00',
  `comment_release` int(1) NOT NULL DEFAULT '0',
  `grade_release` int(1) NOT NULL DEFAULT '0',
  `grp_event_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluator` (`evaluator`),
  KEY `evaluatee` (`evaluatee`),
  KEY `grp_event_id` (`grp_event_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation_mixevals`
--

LOCK TABLES `evaluation_mixevals` WRITE;
/*!40000 ALTER TABLE `evaluation_mixevals` DISABLE KEYS */;
INSERT INTO `evaluation_mixevals` VALUES (1,7,5,3.00,0,0,5,3,'A',7,'2012-07-13 10:38:20',7,'2012-07-13 10:38:20'),(2,7,6,2.40,0,0,5,3,'A',7,'2012-07-13 10:39:28',7,'2012-07-13 10:39:28'),(3,31,32,3.00,0,0,6,3,'A',31,'2012-07-13 10:42:49',31,'2012-07-13 10:42:49'),(4,31,33,2.60,0,0,6,3,'A',31,'2012-07-13 10:43:59',31,'2012-07-13 10:43:59'),(5,5,6,2.40,0,0,5,3,'A',5,'2012-07-13 15:19:26',5,'2012-07-13 15:19:26');
/*!40000 ALTER TABLE `evaluation_mixevals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_rubric_details`
--

DROP TABLE IF EXISTS `evaluation_rubric_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_rubric_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluation_rubric_id` int(11) NOT NULL DEFAULT '0',
  `criteria_number` int(11) NOT NULL DEFAULT '0',
  `criteria_comment` text,
  `selected_lom` int(11) NOT NULL DEFAULT '0',
  `grade` double(12,2) NOT NULL DEFAULT '0.00',
  `comment_release` int(1) NOT NULL DEFAULT '0',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evaluation_rubric_id` (`evaluation_rubric_id`,`criteria_number`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation_rubric_details`
--

LOCK TABLES `evaluation_rubric_details` WRITE;
/*!40000 ALTER TABLE `evaluation_rubric_details` DISABLE KEYS */;
INSERT INTO `evaluation_rubric_details` VALUES (1,1,1,'always on time',5,5.00,0,'A',31,'2012-07-13 10:26:47',31,'2012-07-13 10:26:47'),(2,1,2,'willing to do their part',5,5.00,0,'A',31,'2012-07-13 10:26:47',31,'2012-07-13 10:26:47'),(3,1,3,'everything was done a day early',5,5.00,0,'A',31,'2012-07-13 10:26:47',31,'2012-07-13 10:26:47'),(4,2,1,'attended most meetings',4,4.00,0,'A',31,'2012-07-13 10:29:15',31,'2012-07-13 10:29:15'),(5,2,2,'very co-operative',5,5.00,0,'A',31,'2012-07-13 10:29:15',31,'2012-07-13 10:29:15'),(6,2,3,'finished all his work on time',5,5.00,0,'A',31,'2012-07-13 10:29:15',31,'2012-07-13 10:29:15'),(7,3,1,'Yes',5,5.00,0,'A',7,'2012-07-13 10:30:29',7,'2012-07-13 10:30:29'),(8,3,2,'Absolutely',4,4.00,0,'A',7,'2012-07-13 10:30:29',7,'2012-07-13 10:30:29'),(9,3,3,'Definitely',5,5.00,0,'A',7,'2012-07-13 10:30:29',7,'2012-07-13 10:30:29'),(10,4,1,'attended all of our team meetings',5,5.00,0,'A',7,'2012-07-13 10:31:19',7,'2012-07-13 10:31:19'),(11,4,2,'very helpful in all parts of the project',5,5.00,0,'A',7,'2012-07-13 10:31:19',7,'2012-07-13 10:31:19'),(12,4,3,'Yes',5,5.00,0,'A',7,'2012-07-13 10:31:19',7,'2012-07-13 10:31:19');
/*!40000 ALTER TABLE `evaluation_rubric_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_rubrics`
--

DROP TABLE IF EXISTS `evaluation_rubrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_rubrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluator` int(11) NOT NULL DEFAULT '0',
  `evaluatee` int(11) NOT NULL DEFAULT '0',
  `comment` text,
  `score` double(12,2) NOT NULL DEFAULT '0.00',
  `comment_release` int(1) NOT NULL DEFAULT '0',
  `grade_release` int(1) NOT NULL DEFAULT '0',
  `grp_event_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `rubric_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `evaluator` (`evaluator`),
  KEY `evaluatee` (`evaluatee`),
  KEY `grp_event_id` (`grp_event_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation_rubrics`
--

LOCK TABLES `evaluation_rubrics` WRITE;
/*!40000 ALTER TABLE `evaluation_rubrics` DISABLE KEYS */;
INSERT INTO `evaluation_rubrics` VALUES (1,31,32,'We work well together.',15.00,0,0,4,2,'A',31,'2012-07-13 10:26:47',31,'2012-07-13 10:26:47',1),(2,31,33,'He did a great job.',14.00,1,1,4,2,'A',31,'2012-07-13 10:29:14',31,'2012-07-13 10:29:15',1),(3,7,5,'Good group member.',14.00,0,0,3,2,'A',7,'2012-07-13 10:30:29',7,'2012-07-13 10:30:29',1),(4,7,6,'Good job.',15.00,0,0,3,2,'A',7,'2012-07-13 10:31:19',7,'2012-07-13 10:31:19',1);
/*!40000 ALTER TABLE `evaluation_rubrics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_simples`
--

DROP TABLE IF EXISTS `evaluation_simples`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_simples` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluator` int(11) NOT NULL DEFAULT '0',
  `evaluatee` int(11) NOT NULL DEFAULT '0',
  `score` int(5) NOT NULL DEFAULT '0',
  `comment` text,
  `release_status` int(1) NOT NULL DEFAULT '0',
  `grp_event_id` int(11) NOT NULL DEFAULT '0',
  `event_id` bigint(11) NOT NULL DEFAULT '0',
  `date_submitted` datetime DEFAULT NULL,
  `grade_release` int(1) DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluator` (`evaluator`),
  KEY `evaluatee` (`evaluatee`),
  KEY `grp_event_id` (`grp_event_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation_simples`
--

LOCK TABLES `evaluation_simples` WRITE;
/*!40000 ALTER TABLE `evaluation_simples` DISABLE KEYS */;
INSERT INTO `evaluation_simples` VALUES (1,7,5,95,'very hard working',0,1,1,'2012-07-13 10:21:57',0,'A',7,'2012-07-13 10:21:57',7,'2012-07-13 10:21:57'),(2,7,6,105,'did a decent job',0,1,1,'2012-07-13 10:21:57',0,'A',7,'2012-07-13 10:21:57',7,'2012-07-13 10:21:57'),(3,31,32,125,'very good job',0,2,1,'2012-07-13 10:23:11',0,'A',31,'2012-07-13 10:23:11',31,'2012-07-13 10:23:11'),(4,31,33,75,'he participated',0,2,1,'2012-07-13 10:23:11',0,'A',31,'2012-07-13 10:23:11',31,'2012-07-13 10:23:11'),(6,5,7,105,'',0,7,6,'2012-11-21 12:24:51',0,'A',5,'2012-11-21 12:24:51',5,'2012-11-21 12:24:51'),(7,5,6,95,'',0,7,6,'2012-11-21 12:24:51',0,'A',5,'2012-11-21 12:24:51',5,'2012-11-21 12:24:51'),(8,5,7,105,'',0,10,8,'2012-11-21 12:24:51',0,'A',5,'2012-11-21 12:24:51',5,'2012-11-21 12:24:51'),(9,5,6,95,'',0,10,8,'2012-11-21 12:24:51',0,'A',5,'2012-11-21 12:24:51',5,'2012-11-21 12:24:51');
/*!40000 ALTER TABLE `evaluation_simples` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_submissions`
--

DROP TABLE IF EXISTS `evaluation_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evaluation_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) NOT NULL DEFAULT '0',
  `grp_event_id` int(11) DEFAULT NULL,
  `submitter_id` int(11) NOT NULL DEFAULT '0',
  `submitted` tinyint(1) NOT NULL DEFAULT '0',
  `date_submitted` datetime DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grp_event_id` (`grp_event_id`,`submitter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation_submissions`
--

LOCK TABLES `evaluation_submissions` WRITE;
/*!40000 ALTER TABLE `evaluation_submissions` DISABLE KEYS */;
INSERT INTO `evaluation_submissions` VALUES (1,1,1,7,1,'2012-07-13 10:21:57','A',7,'2012-07-13 10:21:57',7,'2012-07-13 10:21:57'),(2,2,3,7,1,'2012-07-13 11:04:11','A',7,'2012-07-13 11:04:11',7,'2012-07-13 11:04:11'),(3,3,5,7,1,'2012-07-13 11:04:23','A',7,'2012-07-13 11:04:23',7,'2012-07-13 11:04:23'),(4,1,2,31,1,'2012-07-13 10:23:11','A',31,'2012-07-13 10:23:11',31,'2012-07-13 10:23:11'),(5,2,4,31,1,'2012-07-13 11:04:11','A',31,'2012-07-13 11:04:11',31,'2012-07-13 11:04:11'),(6,3,6,31,1,'2012-07-13 11:06:23','A',31,'2012-07-13 11:06:23',31,'2012-07-13 11:06:23'),(7,4,NULL,7,1,'2012-07-13 11:23:31','A',7,'2012-07-13 11:23:31',7,'2012-07-13 11:23:31'),(8,4,NULL,31,1,'2012-07-13 11:24:09','A',31,'2012-07-13 11:24:09',31,'2012-07-13 11:24:09'),(9,5,NULL,17,1,'2012-07-17 10:10:10','A',17,'2012-07-17 10:10:10',17,'2012-07-17 10:10:10'),(10,6,7,5,1,'2012-11-21 12:24:51','A',5,'2012-11-21 12:24:51',5,'2012-11-21 12:24:51'),(11,8,10,5,1,'2012-11-21 12:24:51','A',5,'2012-11-21 12:24:51',5,'2012-11-21 12:24:51');
/*!40000 ALTER TABLE `evaluation_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_template_types`
--

DROP TABLE IF EXISTS `event_template_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_template_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL DEFAULT '',
  `table_name` varchar(50) NOT NULL DEFAULT '',
  `model_name` varchar(80) NOT NULL DEFAULT '',
  `display_for_selection` tinyint(1) NOT NULL DEFAULT '1',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`),
  UNIQUE KEY `table_name` (`table_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_template_types`
--

LOCK TABLES `event_template_types` WRITE;
/*!40000 ALTER TABLE `event_template_types` DISABLE KEYS */;
INSERT INTO `event_template_types` VALUES (1,'SIMPLE','simple_evaluations','SimpleEvaluation',1,'A',0,'2020-02-26 18:32:35',NULL,NULL),(2,'RUBRIC','rubrics','Rubric',1,'A',0,'2020-02-26 18:32:35',NULL,NULL),(3,'SURVEY','surveys','',1,'A',0,'2020-02-26 18:32:35',NULL,NULL),(4,'MIX EVALUATION','mixevals','Mixeval',1,'A',0,'2006-04-03 11:51:02',0,'2006-04-06 15:31:48');
/*!40000 ALTER TABLE `event_template_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `course_id` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `event_template_type_id` int(20) NOT NULL DEFAULT '0',
  `template_id` int(11) NOT NULL DEFAULT '2',
  `self_eval` varchar(11) NOT NULL DEFAULT '0',
  `com_req` int(11) NOT NULL DEFAULT '0',
  `auto_release` int(11) NOT NULL DEFAULT '0',
  `enable_details` int(11) NOT NULL DEFAULT '1',
  `due_date` datetime DEFAULT NULL,
  `release_date_begin` datetime DEFAULT NULL,
  `release_date_end` datetime DEFAULT NULL,
  `result_release_date_begin` datetime DEFAULT NULL,
  `result_release_date_end` datetime DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `canvas_assignment_id` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'Term 1 Evaluation',1,'',1,1,'0',0,0,1,'2021-07-02 16:34:43','2011-06-16 16:34:49','2023-07-22 16:34:53','2024-07-04 16:34:43','2024-07-30 16:34:43','A',1,'2006-06-20 16:27:33',1,'2006-06-21 08:51:20',NULL),(2,'Term Report Evaluation',1,'',2,1,'0',0,0,1,'2021-06-08 08:59:29','2011-06-06 08:59:35','2023-07-02 08:59:41','2024-06-09 08:59:29','2024-07-08 08:59:29','A',1,'2006-06-21 08:52:20',1,'2006-06-21 08:54:25',NULL),(3,'Project Evaluation',1,'',4,1,'0',0,0,1,'2021-07-02 09:00:28','2011-06-07 09:00:35','2023-07-09 09:00:39','2023-07-04 09:00:28','2024-07-12 09:00:28','A',1,'2006-06-21 08:53:14',1,'2006-06-21 09:07:26',NULL),(4,'Team Creation Survey',1,NULL,3,1,'1',1,0,1,'2021-07-31 11:20:00','2012-07-01 11:20:00','2021-12-31 11:20:00',NULL,NULL,'A',2,'2012-07-13 11:18:56',2,'2012-07-13 11:18:56',NULL),(5,'Survey, all Q types',1,NULL,3,2,'1',1,0,1,'2021-07-31 11:20:00','2012-07-01 11:20:00','2021-12-31 11:20:00',NULL,NULL,'A',1,'2012-07-13 11:18:56',1,'2012-07-13 11:18:56',NULL),(6,'simple evaluation 2',1,'2nd simple evaluation',1,1,'0',0,0,1,'2012-11-28 00:00:00','2012-11-20 00:00:00','2022-11-29 00:00:00','2022-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(7,'simple evaluation 3',1,'3rd simple evaluation for testing overdue event',1,1,'0',0,0,1,'2012-11-28 00:00:00','2012-11-20 00:00:00','2012-11-29 00:00:00','2022-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(8,'simple evaluation 4',1,'result released with submission',1,1,'0',0,0,1,'2012-11-28 00:00:00','2012-11-20 00:00:00','2012-11-29 00:00:00','2012-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(9,'simple evaluation 5',1,'result released with no submission',1,1,'0',0,0,1,'2012-11-28 00:00:00','2012-11-20 00:00:00','2012-11-29 00:00:00','2012-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(10,'simple evaluation 6',1,'result released with no submission',1,1,'0',0,0,1,'2022-07-31 11:20:00','2021-07-31 11:20:00','2022-07-31 11:20:00','2022-07-31 11:20:00','2023-07-31 11:20:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(11,'timezone test A',1,'before DST',1,1,'0',0,0,1,'2013-02-14 00:00:00','2012-11-20 00:00:00','2022-11-29 00:00:00','2022-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(12,'timezone test B',1,'transition to DST',1,1,'0',0,0,1,'2013-03-10 02:00:00','2012-11-20 00:00:00','2022-11-29 00:00:00','2022-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(13,'timezone test C',1,'during DST',1,1,'0',0,0,1,'2013-06-12 00:00:00','2012-11-20 00:00:00','2022-11-29 00:00:00','2022-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(14,'timezone test D',1,'transition from DST',1,1,'0',0,0,1,'2013-11-03 02:00:00','2012-11-20 00:00:00','2022-11-29 00:00:00','2022-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(15,'timezone test E',1,'after DST',1,1,'0',0,0,1,'2013-11-04 00:00:00','2012-11-20 00:00:00','2022-11-29 00:00:00','2022-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(16,'timezone test B1',1,'missed time from transition to DST',1,1,'0',0,0,1,'2013-03-10 02:30:00','2012-11-20 00:00:00','2022-11-29 00:00:00','2022-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL),(17,'timezone test D1',1,'overlapped time from transition from DST',1,1,'0',0,0,1,'2013-11-03 01:30:00','2012-11-20 00:00:00','2022-11-29 00:00:00','2022-11-30 00:00:00','2022-12-12 00:00:00','A',1,'2012-11-21 12:23:13',1,'2012-11-21 12:23:13',NULL);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculties`
--

DROP TABLE IF EXISTS `faculties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faculties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculties`
--

LOCK TABLES `faculties` WRITE;
/*!40000 ALTER TABLE `faculties` DISABLE KEYS */;
INSERT INTO `faculties` VALUES (1,'Applied Science',0,'2020-02-26 18:32:35',NULL,'2012-05-23 11:29:58'),(2,'Science',0,'2020-02-26 18:32:35',NULL,'2012-05-23 11:30:05');
/*!40000 ALTER TABLE `faculties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_events`
--

DROP TABLE IF EXISTS `group_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `marked` varchar(20) NOT NULL DEFAULT 'not reviewed',
  `grade` double(12,2) DEFAULT NULL,
  `grade_release_status` varchar(20) NOT NULL DEFAULT 'None',
  `comment_release_status` varchar(20) NOT NULL DEFAULT 'None',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`event_id`,`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_events`
--

LOCK TABLES `group_events` WRITE;
/*!40000 ALTER TABLE `group_events` DISABLE KEYS */;
INSERT INTO `group_events` VALUES (1,1,1,'not reviewed',NULL,'None','None','A',0,'2006-06-20 16:27:33',NULL,'2006-06-20 16:27:33'),(2,2,1,'not reviewed',NULL,'None','None','A',0,'2006-06-21 08:50:22',NULL,'2006-06-21 08:50:22'),(3,1,2,'not reviewed',NULL,'None','None','A',0,'2006-06-21 08:52:20',NULL,'2006-06-21 08:52:20'),(4,2,2,'not reviewed',NULL,'None','None','A',0,'2006-06-21 08:52:20',NULL,'2006-06-21 08:52:20'),(5,1,3,'not reviewed',NULL,'None','None','A',0,'2006-06-21 08:53:23',NULL,'2006-06-21 08:53:23'),(6,2,3,'not reviewed',NULL,'None','None','A',0,'2006-06-21 08:53:23',NULL,'2006-06-21 08:53:23'),(7,1,6,'not reviewed',NULL,'None','None','A',0,'2020-02-26 18:32:35',NULL,NULL),(8,2,6,'not reviewed',NULL,'None','None','A',0,'2020-02-26 18:32:35',NULL,NULL),(9,1,7,'not reviewed',NULL,'None','None','A',0,'2020-02-26 18:32:35',NULL,NULL),(10,1,8,'not reviewed',NULL,'None','None','A',0,'2020-02-26 18:32:35',NULL,NULL),(11,1,9,'not reviewed',NULL,'None','None','A',0,'2020-02-26 18:32:35',NULL,NULL),(12,1,10,'not reviewed',NULL,'None','None','A',0,'2020-02-26 18:32:35',NULL,NULL);
/*!40000 ALTER TABLE `group_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_num` int(4) NOT NULL DEFAULT '0',
  `group_name` varchar(80) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `record_status` varchar(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,1,'Reapers',1,'A',0,'2006-06-20 16:23:40',NULL,'2006-06-20 16:23:40'),(2,2,'Lazy Engineers',1,'A',0,'2006-06-21 08:47:04',NULL,'2006-06-21 08:49:53');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups_members`
--

DROP TABLE IF EXISTS `groups_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_user` (`group_id`,`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups_members`
--

LOCK TABLES `groups_members` WRITE;
/*!40000 ALTER TABLE `groups_members` DISABLE KEYS */;
INSERT INTO `groups_members` VALUES (1,1,5),(2,1,6),(3,1,7),(4,1,35),(8,2,7),(5,2,31),(6,2,32),(7,2,33);
/*!40000 ALTER TABLE `groups_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `handler` text NOT NULL,
  `queue` varchar(255) NOT NULL DEFAULT 'default',
  `attempts` int(10) unsigned NOT NULL DEFAULT '0',
  `run_at` datetime DEFAULT NULL,
  `locked_at` datetime DEFAULT NULL,
  `locked_by` varchar(255) DEFAULT NULL,
  `failed_at` datetime DEFAULT NULL,
  `error` text,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lti_platform_deployments`
--

DROP TABLE IF EXISTS `lti_platform_deployments`;
CREATE TABLE `lti_platform_deployments` (
  `iss` varchar(255) NOT NULL,
  `deployment` varchar(64) NOT NULL COMMENT 'Platform deployment ID hash. https://purl.imsglobal.org/spec/lti/claim/deployment_id',
  KEY `iss` (`iss`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lti_platform_deployments`
--

LOCK TABLES `lti_platform_deployments` WRITE;
INSERT INTO `lti_platform_deployments` VALUES
('https://lti-ri.imsglobal.org', '1'),
('https://canvas.instructure.com', '1:4dde05e8ca1973bcca9bffc13e1548820eee93a3'),
('https://canvas.instructure.com', '2:f97330a96452fc363a34e0ef6d8d0d3e9e1007d2'),
('https://canvas.instructure.com', '3:d3a2504bba5184799a38f141e8df2335cfa8206d');
UNLOCK TABLES;

--
-- Table structure for table `lti_tool_registrations`
--

DROP TABLE IF EXISTS `lti_tool_registrations`;
CREATE TABLE `lti_tool_registrations` (
  `iss` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `auth_login_url` varchar(255) NOT NULL,
  `auth_token_url` varchar(255) NOT NULL,
  `key_set_url` varchar(255) NOT NULL,
  `private_key_file` varchar(255) NOT NULL,
  PRIMARY KEY `iss` (`iss`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lti_tool_registrations`
--

LOCK TABLES `lti_tool_registrations` WRITE;
INSERT INTO `lti_tool_registrations` VALUES
(
    'https://lti-ri.imsglobal.org',
    'ipeer-lti13-001',
    'https://lti-ri.imsglobal.org/platforms/652/authorizations/new',
    'https://lti-ri.imsglobal.org/platforms/652/access_tokens',
    'https://lti-ri.imsglobal.org/platforms/652/platform_keys/654.json',
    'app/config/lti13/tool.private.key'
),
(
    'https://canvas.instructure.com',
    '10000000000001',
    'http://canvas.docker/api/lti/authorize_redirect',
    'http://canvas.docker/login/oauth2/token',
    'http://canvas.docker/api/lti/security/jwks',
    'app/config/lti13/tool.private.key'
);
UNLOCK TABLES;

--
-- Table structure for table `mixeval_question_descs`
--

DROP TABLE IF EXISTS `mixeval_question_descs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mixeval_question_descs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL DEFAULT '0',
  `scale_level` int(11) NOT NULL DEFAULT '0',
  `descriptor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `mixeval_question_descs_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `mixeval_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mixeval_question_descs`
--

LOCK TABLES `mixeval_question_descs` WRITE;
/*!40000 ALTER TABLE `mixeval_question_descs` DISABLE KEYS */;
INSERT INTO `mixeval_question_descs` VALUES (1,1,1,'Lowest'),(2,1,2,NULL),(3,1,3,'Middle'),(4,1,4,NULL),(5,1,5,'Highest'),(6,2,1,'Lowest'),(7,2,2,NULL),(8,2,3,'Middle'),(9,2,4,NULL),(10,2,5,'Highest'),(11,3,1,'Lowest'),(12,3,2,NULL),(13,3,3,'Middle'),(14,3,4,NULL),(15,3,5,'Highest');
/*!40000 ALTER TABLE `mixeval_question_descs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mixeval_question_types`
--

DROP TABLE IF EXISTS `mixeval_question_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mixeval_question_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mixeval_question_types`
--

LOCK TABLES `mixeval_question_types` WRITE;
/*!40000 ALTER TABLE `mixeval_question_types` DISABLE KEYS */;
INSERT INTO `mixeval_question_types` VALUES (1,'Likert'),(2,'Paragraph'),(3,'Sentence'),(4,'ScoreDropdown');
/*!40000 ALTER TABLE `mixeval_question_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mixeval_questions`
--

DROP TABLE IF EXISTS `mixeval_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mixeval_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mixeval_id` int(11) NOT NULL DEFAULT '0',
  `question_num` int(11) NOT NULL DEFAULT '0',
  `title` text,
  `instructions` text,
  `mixeval_question_type_id` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '1',
  `self_eval` tinyint(1) NOT NULL DEFAULT '0',
  `multiplier` int(11) NOT NULL DEFAULT '0',
  `scale_level` int(11) NOT NULL DEFAULT '0',
  `show_marks` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mixeval_question_type_id` (`mixeval_question_type_id`),
  KEY `mixeval_id` (`mixeval_id`),
  CONSTRAINT `mixeval_questions_ibfk_1` FOREIGN KEY (`mixeval_question_type_id`) REFERENCES `mixeval_question_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mixeval_questions_ibfk_2` FOREIGN KEY (`mixeval_id`) REFERENCES `mixevals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mixeval_questions`
--

LOCK TABLES `mixeval_questions` WRITE;
/*!40000 ALTER TABLE `mixeval_questions` DISABLE KEYS */;
INSERT INTO `mixeval_questions` VALUES (1,1,1,'Participated in Team Meetings','Please rate performance.',1,1,0,1,5,1),(2,1,2,'Was Helpful and co-operative',NULL,1,1,0,1,5,0),(3,1,3,'Submitted work on time',NULL,1,1,0,1,5,1),(4,1,4,'Produced efficient work?',NULL,3,1,0,0,5,0),(5,1,5,'Contributed?','Please give a paragraph answer.',2,1,0,0,5,0),(6,1,6,'Easy to work with?',NULL,3,0,0,0,5,0);
/*!40000 ALTER TABLE `mixeval_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mixevals`
--

DROP TABLE IF EXISTS `mixevals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mixevals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `zero_mark` tinyint(1) NOT NULL DEFAULT '0',
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mixevals`
--

LOCK TABLES `mixevals` WRITE;
/*!40000 ALTER TABLE `mixevals` DISABLE KEYS */;
INSERT INTO `mixevals` VALUES (1,'Default Mix Evaluation',0,'public',1,'2006-09-12 13:34:30',1,'2006-09-12 13:47:57');
/*!40000 ALTER TABLE `mixevals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `comment` text,
  `enabled` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `oauth_clients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES (1,1,'i//dt5l+kFYk/','M++SgeLVtL//locYEjkb/aLg2Q/','',1);
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_nonces`
--

DROP TABLE IF EXISTS `oauth_nonces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_nonces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nonce` varchar(255) NOT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_nonces`
--

LOCK TABLES `oauth_nonces` WRITE;
/*!40000 ALTER TABLE `oauth_nonces` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_nonces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_tokens`
--

DROP TABLE IF EXISTS `oauth_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `expires` date NOT NULL,
  `comment` text,
  `enabled` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `oauth_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_tokens`
--

LOCK TABLES `oauth_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_tokens` DISABLE KEYS */;
INSERT INTO `oauth_tokens` VALUES (1,1,'//qu+Bfa/gr+C//p8//','//Bgc3Ql+QQR/O+PEi6sJZG//','2032-08-13','',1);
/*!40000 ALTER TABLE `oauth_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penalties`
--

DROP TABLE IF EXISTS `penalties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `penalties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `days_late` int(11) NOT NULL,
  `percent_penalty` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `penalties_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penalties`
--

LOCK TABLES `penalties` WRITE;
/*!40000 ALTER TABLE `penalties` DISABLE KEYS */;
INSERT INTO `penalties` VALUES (1,1,1,20),(2,1,2,40),(3,1,3,60),(4,1,4,100),(5,2,1,15),(6,2,2,30),(7,2,3,45),(8,2,4,60),(9,6,1,5),(10,6,2,10);
/*!40000 ALTER TABLE `penalties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personalizes`
--

DROP TABLE IF EXISTS `personalizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personalizes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `attribute_code` varchar(80) DEFAULT NULL,
  `attribute_value` varchar(80) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`attribute_code`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personalizes`
--

LOCK TABLES `personalizes` WRITE;
/*!40000 ALTER TABLE `personalizes` DISABLE KEYS */;
INSERT INTO `personalizes` VALUES (1,1,'Course.SubMenu.EvalEvents.Show','true',NULL,'2006-04-03 09:08:36'),(2,1,'Course.SubMenu.SimpleEvals.Show','true',NULL,'2006-04-03 09:08:42'),(3,1,'Course.SubMenu.Student.Show','true',NULL,'2006-04-03 09:08:47'),(4,1,'Course.SubMenu.Group.Show','none',NULL,'2006-04-03 09:08:50'),(5,1,'Course.SubMenu.EvalResults.Show','true',NULL,'2006-04-03 09:10:04'),(6,1,'Course.SubMenu.Rubric.Show','true',NULL,'2006-04-04 14:40:46'),(7,1,'Course.SubMenu.TeamMaker.Show','true',NULL,'2006-05-03 14:26:26'),(8,1,'Mixeval.ListMenu.Limit.Show','10',NULL,'2006-06-21 09:06:47'),(9,1,'Course.ListMenu.Limit.Show','10','2006-06-21 09:06:52','2006-06-21 09:06:52'),(10,1,'Event.ListMenu.Limit.Show','10','2006-06-21 09:07:06','2006-06-21 09:07:06'),(11,1,'Survey.ListMenu.Limit.Show','10','2006-06-21 09:43:13','2006-06-21 09:43:13'),(12,1,'SurveyGroupSet.List.Limit.Show','10','2006-06-21 12:22:19','2006-06-21 12:22:19'),(13,1,'User.ListMenu.Limit.Show','10','2006-06-21 15:11:00','2006-06-21 15:11:00'),(14,1,'SimpleEval.ListMenu.Limit.Show','10','2006-06-21 15:12:56','2006-06-21 15:12:56'),(15,1,'Search.ListMenu.Limit.Show','10','2006-06-21 15:16:35','2006-06-21 15:16:35'),(16,1,'SysParam.ListMenu.Limit.Show','10','2006-06-21 15:16:37','2006-06-21 15:16:37');
/*!40000 ALTER TABLE `personalizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prompt` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(1) DEFAULT NULL,
  `master` varchar(3) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,'What was your GPA last term?','M','no'),(2,'Do you own a laptop?','M','no'),(3,'Testing out MC','M','no'),(4,'Testing out checkboxes','C','no'),(5,'Testing out single line answers','S','no'),(6,'Testing out multi-line long answers','L','no');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responses`
--

DROP TABLE IF EXISTS `responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL DEFAULT '0',
  `response` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responses`
--

LOCK TABLES `responses` WRITE;
/*!40000 ALTER TABLE `responses` DISABLE KEYS */;
INSERT INTO `responses` VALUES (1,1,'4+'),(2,1,'3-4'),(3,1,'2-3'),(4,1,'<2'),(5,2,'yes'),(6,2,'no'),(7,3,'A'),(8,3,'B'),(9,3,'C'),(10,3,'D'),(11,4,'choose me'),(12,4,'no, me'),(13,4,'me please');
/*!40000 ALTER TABLE `responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'superadmin','2010-10-27 16:17:29','2010-10-27 16:17:29'),(2,'admin','2010-10-27 16:17:29','2010-10-27 16:17:29'),(3,'instructor','2010-10-27 16:17:29','2010-10-27 16:17:29'),(4,'tutor','2010-10-27 16:17:29','2010-10-27 16:17:29'),(5,'student','2010-10-27 16:17:29','2010-10-27 16:17:29');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles_users`
--

DROP TABLE IF EXISTS `roles_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles_users`
--

LOCK TABLES `roles_users` WRITE;
/*!40000 ALTER TABLE `roles_users` DISABLE KEYS */;
INSERT INTO `roles_users` VALUES (1,1,1,'2020-02-26 18:32:36','2020-02-26 10:32:37'),(2,3,2,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(3,3,3,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(4,3,4,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(5,5,5,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(6,5,6,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(7,5,7,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(8,5,8,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(9,5,9,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(10,5,10,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(11,5,11,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(12,5,12,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(13,5,13,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(14,5,14,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(15,5,15,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(16,5,16,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(17,5,17,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(18,5,18,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(19,5,19,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(20,5,20,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(21,5,21,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(22,5,22,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(23,5,23,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(24,5,24,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(25,5,25,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(26,5,26,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(27,5,27,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(28,5,28,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(29,5,29,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(30,5,30,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(31,5,31,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(32,5,32,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(33,5,33,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(34,2,34,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(35,4,35,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(36,4,36,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(37,4,37,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(38,2,38,'2010-10-27 16:17:29','2010-10-27 16:17:29'),(39,2,39,'2014-12-15 00:00:00','2014-12-15 00:00:00'),(40,2,40,'2014-12-15 00:00:00','2014-12-15 00:00:00');
/*!40000 ALTER TABLE `roles_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rubrics`
--

DROP TABLE IF EXISTS `rubrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rubrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `zero_mark` tinyint(1) NOT NULL DEFAULT '0',
  `lom_max` int(11) DEFAULT NULL,
  `criteria` int(11) DEFAULT NULL,
  `view_mode` varchar(10) NOT NULL DEFAULT 'student',
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `template` varchar(20) NOT NULL DEFAULT 'horizontal',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rubrics`
--

LOCK TABLES `rubrics` WRITE;
/*!40000 ALTER TABLE `rubrics` DISABLE KEYS */;
INSERT INTO `rubrics` VALUES (1,'Term Report Evaluation',0,5,3,'student','public','horizontal',1,'2006-06-20 15:21:50',NULL,'2006-06-20 15:21:50');
/*!40000 ALTER TABLE `rubrics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rubrics_criteria_comments`
--

DROP TABLE IF EXISTS `rubrics_criteria_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rubrics_criteria_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `criteria_id` int(11) NOT NULL,
  `rubrics_loms_id` int(11) NOT NULL,
  `criteria_comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `criteria_id` (`criteria_id`),
  KEY `rubrics_loms_id` (`rubrics_loms_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rubrics_criteria_comments`
--

LOCK TABLES `rubrics_criteria_comments` WRITE;
/*!40000 ALTER TABLE `rubrics_criteria_comments` DISABLE KEYS */;
INSERT INTO `rubrics_criteria_comments` VALUES (1,1,1,'No participation.'),(2,1,2,'Little participation.'),(3,1,3,'Some participation.'),(4,1,4,'Good participation.'),(5,1,5,'Great participation.'),(6,2,1,NULL),(7,2,2,NULL),(8,2,3,NULL),(9,2,4,NULL),(10,2,5,NULL),(11,3,1,NULL),(12,3,2,NULL),(13,3,3,NULL),(14,3,4,NULL),(15,3,5,NULL);
/*!40000 ALTER TABLE `rubrics_criteria_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rubrics_criterias`
--

DROP TABLE IF EXISTS `rubrics_criterias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rubrics_criterias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubric_id` int(11) NOT NULL DEFAULT '0',
  `criteria_num` int(11) NOT NULL DEFAULT '999',
  `criteria` varchar(255) DEFAULT NULL,
  `multiplier` int(11) NOT NULL DEFAULT '0',
  `show_marks` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rubrics_criterias`
--

LOCK TABLES `rubrics_criterias` WRITE;
/*!40000 ALTER TABLE `rubrics_criterias` DISABLE KEYS */;
INSERT INTO `rubrics_criterias` VALUES (1,1,1,'Participated in Team Meetings',5,0),(2,1,2,'Was Helpful and Co-operative',5,1),(3,1,3,'Submitted Work on Time',5,1);
/*!40000 ALTER TABLE `rubrics_criterias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rubrics_loms`
--

DROP TABLE IF EXISTS `rubrics_loms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rubrics_loms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubric_id` int(11) NOT NULL DEFAULT '0',
  `lom_num` int(11) NOT NULL DEFAULT '999',
  `lom_comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rubrics_loms`
--

LOCK TABLES `rubrics_loms` WRITE;
/*!40000 ALTER TABLE `rubrics_loms` DISABLE KEYS */;
INSERT INTO `rubrics_loms` VALUES (1,1,1,'Poor'),(2,1,2,'Below Average'),(3,1,3,'Average'),(4,1,4,'Above Average'),(5,1,5,'Excellent');
/*!40000 ALTER TABLE `rubrics_loms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simple_evaluations`
--

DROP TABLE IF EXISTS `simple_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `simple_evaluations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `point_per_member` int(10) NOT NULL DEFAULT '0',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simple_evaluations`
--

LOCK TABLES `simple_evaluations` WRITE;
/*!40000 ALTER TABLE `simple_evaluations` DISABLE KEYS */;
INSERT INTO `simple_evaluations` VALUES (1,'Module 1 Project Evaluation','',100,'A','public',1,'2006-06-20 15:17:47',NULL,'2006-06-20 15:17:47');
/*!40000 ALTER TABLE `simple_evaluations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_group_members`
--

DROP TABLE IF EXISTS `survey_group_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_group_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_set_id` int(11) DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_group_members`
--

LOCK TABLES `survey_group_members` WRITE;
/*!40000 ALTER TABLE `survey_group_members` DISABLE KEYS */;
INSERT INTO `survey_group_members` VALUES (25,3,5,17),(26,3,5,21),(27,3,5,15),(28,3,5,19),(29,3,6,6),(30,3,6,32),(31,3,6,26),(32,3,6,13),(33,3,7,7),(34,3,7,28),(35,3,7,5),(36,3,7,33);
/*!40000 ALTER TABLE `survey_group_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_group_sets`
--

DROP TABLE IF EXISTS `survey_group_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_group_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL DEFAULT '0',
  `set_description` text NOT NULL,
  `num_groups` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `released` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_group_sets`
--

LOCK TABLES `survey_group_sets` WRITE;
/*!40000 ALTER TABLE `survey_group_sets` DISABLE KEYS */;
INSERT INTO `survey_group_sets` VALUES (3,4,'test groupset',3,1150923956,0);
/*!40000 ALTER TABLE `survey_group_sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_groups`
--

DROP TABLE IF EXISTS `survey_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_set_id` int(11) NOT NULL DEFAULT '0',
  `group_number` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_groups`
--

LOCK TABLES `survey_groups` WRITE;
/*!40000 ALTER TABLE `survey_groups` DISABLE KEYS */;
INSERT INTO `survey_groups` VALUES (5,3,1),(6,3,2),(7,3,3);
/*!40000 ALTER TABLE `survey_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_inputs`
--

DROP TABLE IF EXISTS `survey_inputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_inputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL DEFAULT '0',
  `response_text` text,
  `response_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_inputs`
--

LOCK TABLES `survey_inputs` WRITE;
/*!40000 ALTER TABLE `survey_inputs` DISABLE KEYS */;
INSERT INTO `survey_inputs` VALUES (1,4,7,1,'4+',1),(2,4,7,2,'yes',5),(3,4,31,1,'3-4',2),(4,4,31,2,'no',6),(5,5,17,3,'B',8),(6,5,17,4,'choose me',11),(7,5,17,4,'no, me',12),(8,5,17,5,'single line rah rah',0),(9,5,17,6,'long answer what what',0);
/*!40000 ALTER TABLE `survey_inputs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_questions`
--

DROP TABLE IF EXISTS `survey_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL DEFAULT '0',
  `number` int(11) NOT NULL DEFAULT '9999',
  `question_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_questions`
--

LOCK TABLES `survey_questions` WRITE;
/*!40000 ALTER TABLE `survey_questions` DISABLE KEYS */;
INSERT INTO `survey_questions` VALUES (1,1,1,1),(2,1,2,2),(3,2,1,3),(4,2,2,4),(5,2,3,5),(6,2,4,6);
/*!40000 ALTER TABLE `survey_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `surveys`
--

DROP TABLE IF EXISTS `surveys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `due_date` datetime DEFAULT NULL,
  `release_date_begin` datetime DEFAULT NULL,
  `release_date_end` datetime DEFAULT NULL,
  `released` tinyint(1) NOT NULL DEFAULT '0',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `surveys`
--

LOCK TABLES `surveys` WRITE;
/*!40000 ALTER TABLE `surveys` DISABLE KEYS */;
INSERT INTO `surveys` VALUES (1,'Team Creation Survey','public','2012-07-31 11:20:00','2012-07-01 11:20:00','2013-12-31 11:20:00',0,2,'2012-07-13 11:18:56',2,'2012-07-13 11:18:56'),(2,'Survey, all Q types','public','2012-07-31 11:20:00','2012-07-01 11:20:00','2013-12-31 11:20:00',0,1,'2012-07-13 11:18:56',1,'2012-07-13 11:18:56');
/*!40000 ALTER TABLE `surveys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_parameters`
--

DROP TABLE IF EXISTS `sys_parameters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameter_code` varchar(80) NOT NULL,
  `parameter_value` text,
  `parameter_type` char(1) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parameter_code` (`parameter_code`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_parameters`
--

LOCK TABLES `sys_parameters` WRITE;
/*!40000 ALTER TABLE `sys_parameters` DISABLE KEYS */;
INSERT INTO `sys_parameters` VALUES (1,'system.super_admin','root','S',NULL,'A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 10:32:37'),(2,'system.admin_email','','S',NULL,'A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 10:32:37'),(3,'display.date_format','D, M j, Y g:i a','S','date format preference','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(4,'system.version','3.4.4','S',NULL,'A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(5,'database.version','16','I','database version','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(6,'email.port','','S','port number for email smtp option','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 10:32:37'),(7,'email.host','','S','host address for email smtp option','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 10:32:37'),(8,'email.username','','S','username for email smtp option','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 10:32:37'),(9,'email.password','','S','password for email smtp option','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 10:32:37'),(10,'display.contact_info','noreply@ipeer.ctlt.ubc.ca','S','Contact Info','A',0,'2020-02-26 18:32:36',0,'2020-02-26 18:32:36'),(11,'display.login.header','','S','Login Info Header','A',0,'2020-02-26 18:32:36',0,'2020-02-26 18:32:36'),(12,'display.login.footer','','S','Login Info Footer','A',0,'2020-02-26 18:32:36',0,'2020-02-26 18:32:36'),(13,'system.absolute_url','http://localhost:8080/','S','base url to iPeer','A',0,'2020-02-26 18:32:36',0,'2020-02-26 10:32:37'),(14,'google_analytics.tracking_id','','S','tracking id for Google Analytics','A',0,'2020-02-26 18:32:36',0,'2020-02-26 18:32:36'),(15,'google_analytics.domain','','S','domain name for Google Analytics','A',0,'2020-02-26 18:32:36',0,'2020-02-26 18:32:36'),(16,'banner.custom_logo','','S','custom logo that appears on the left side of the banner','A',0,'2020-02-26 18:32:36',0,'2020-02-26 18:32:36'),(17,'system.timezone','UTC','S','timezone','A',0,'2020-02-26 18:32:36',0,'2020-02-26 10:32:37'),(18,'system.student_number','true','B','allow students to change their student number','A',0,'2020-02-26 18:32:36',0,'2020-02-26 18:32:36'),(19,'course.creation.instructions','','S','Display course creation instructions','A',0,'2020-02-26 18:32:36',0,'2020-02-26 18:32:36'),(20,'email.reminder_enabled','true','B','Enable email reminder feature','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(21,'system.canvas_enabled','true','B','Enable Canvas integration','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(22,'system.canvas_baseurl','http://docker-canvas_app_1:80','S','Base URL for Canvas API','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(23,'system.canvas_baseurl_ext','http://docker-canvas_app_1','S','External Base URL for Canvas API (if not set, will default to canvas_baseurl)','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(24,'system.canvas_user_key','integration_id','S','Key used to map a Canvas user to iPeer username','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(25,'system.canvas_client_id','','S','Canvas Oauth Client ID','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(26,'system.canvas_client_secret','','S','Canvas Oauth Client Secret','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(27,'system.canvas_force_login','false','B','Force the user to enter their Canvas credentials when connecting for the first time','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(28,'system.canvas_api_timeout','10','I','Canvas API call timeout in seconds','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(29,'system.canvas_api_default_per_page','500','I','Default number of items to retrieve per Canvas API call','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(30,'system.canvas_api_max_retrieve_all','10000','I','Max number of item to retrieve when auto-looping Canvas API pagination to retrieve all records','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(31,'system.canvas_api_max_call','20','I','Max number of API calls when auto-looping Canvas API pagination to retrieve all records','A',0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36');
/*!40000 ALTER TABLE `sys_parameters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_courses`
--

DROP TABLE IF EXISTS `user_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `course_id` int(11) NOT NULL DEFAULT '0',
  `access_right` varchar(1) NOT NULL DEFAULT 'O',
  `record_status` varchar(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_duplicates` (`course_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_courses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_courses`
--

LOCK TABLES `user_courses` WRITE;
/*!40000 ALTER TABLE `user_courses` DISABLE KEYS */;
INSERT INTO `user_courses` VALUES (1,2,1,'A','A',0,'2006-06-20 14:14:45',NULL,'2006-06-20 14:14:45'),(2,3,2,'A','A',0,'2006-06-20 14:39:31',NULL,'2006-06-20 14:39:31'),(3,4,2,'A','A',0,'2006-06-20 14:39:31',NULL,'2006-06-20 14:39:31'),(4,4,3,'A','A',0,'2006-06-20 14:39:31',NULL,'2006-06-20 14:39:31'),(5,39,4,'A','A',0,'2014-12-16 14:39:31',NULL,'2014-12-16 14:39:31'),(6,40,2,'A','A',0,'2014-12-16 14:39:31',NULL,'2014-12-16 14:39:31');
/*!40000 ALTER TABLE `user_courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_enrols`
--

DROP TABLE IF EXISTS `user_enrols`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_enrols` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `record_status` varchar(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_duplicates` (`course_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_enrols_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_enrols_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_enrols`
--

LOCK TABLES `user_enrols` WRITE;
/*!40000 ALTER TABLE `user_enrols` DISABLE KEYS */;
INSERT INTO `user_enrols` VALUES (1,1,5,'A',1,'2006-06-20 14:19:18',NULL,'2006-06-20 14:19:18'),(2,1,6,'A',1,'2006-06-20 14:26:59',NULL,'2006-06-20 14:26:59'),(3,1,7,'A',1,'2006-06-20 14:27:24',NULL,'2006-06-20 14:27:24'),(4,2,9,'A',1,'2006-06-20 14:28:08',NULL,'2006-06-20 14:28:08'),(5,2,10,'A',1,'2006-06-20 14:28:29',NULL,'2006-06-20 14:28:29'),(6,2,11,'A',1,'2006-06-20 14:28:49',NULL,'2006-06-20 14:28:49'),(7,2,12,'A',1,'2006-06-20 14:29:07',NULL,'2006-06-20 14:29:07'),(8,1,13,'A',1,'2006-06-20 14:31:17',NULL,'2006-06-20 14:31:17'),(9,1,15,'A',1,'2006-06-20 15:00:35',NULL,'2006-06-20 15:00:35'),(10,2,16,'A',1,'2006-06-20 15:01:09',NULL,'2006-06-20 15:01:09'),(11,1,17,'A',1,'2006-06-20 15:01:24',NULL,'2006-06-20 15:01:24'),(12,2,18,'A',1,'2006-06-20 15:01:52',NULL,'2006-06-20 15:01:52'),(13,1,19,'A',1,'2006-06-20 15:02:20',NULL,'2006-06-20 15:02:20'),(14,2,20,'A',1,'2006-06-20 15:03:27',NULL,'2006-06-20 15:03:27'),(15,1,21,'A',1,'2006-06-20 15:03:47',NULL,'2006-06-20 15:03:47'),(16,2,22,'A',1,'2006-06-20 15:04:22',NULL,'2006-06-20 15:04:22'),(17,2,23,'A',1,'2006-06-20 15:05:55',NULL,'2006-06-20 15:05:55'),(18,2,24,'A',1,'2006-06-20 15:06:20',NULL,'2006-06-20 15:06:20'),(19,2,25,'A',1,'2006-06-20 15:06:46',NULL,'2006-06-20 15:06:46'),(20,1,26,'A',1,'2006-06-20 15:07:01',NULL,'2006-06-20 15:07:01'),(21,2,27,'A',1,'2006-06-20 15:07:37',NULL,'2006-06-20 15:07:37'),(22,1,28,'A',1,'2006-06-20 15:08:04',NULL,'2006-06-20 15:08:04'),(23,2,29,'A',1,'2006-06-20 15:08:31',NULL,'2006-06-20 15:08:31'),(24,2,30,'A',1,'2006-06-20 15:08:47',NULL,'2006-06-20 15:08:47'),(25,1,31,'A',1,'2006-06-20 15:10:16',NULL,'2006-06-20 15:10:16'),(26,1,32,'A',1,'2006-06-20 15:10:32',NULL,'2006-06-20 15:10:32'),(27,1,33,'A',1,'2006-06-21 08:44:09',NULL,'2006-06-21 08:44:09'),(28,2,7,'A',1,'2006-06-21 08:44:09',NULL,'2006-06-21 08:44:09'),(29,3,33,'A',1,'2006-06-21 08:44:09',NULL,'2006-06-21 08:44:09'),(30,3,8,'A',1,'2006-06-21 08:44:09',NULL,'2006-06-21 08:44:09');
/*!40000 ALTER TABLE `user_enrols` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_faculties`
--

DROP TABLE IF EXISTS `user_faculties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_faculties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `user_faculties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_faculties_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_faculties`
--

LOCK TABLES `user_faculties` WRITE;
/*!40000 ALTER TABLE `user_faculties` DISABLE KEYS */;
INSERT INTO `user_faculties` VALUES (1,2,1),(2,3,1),(3,3,2),(4,4,1),(5,34,1),(6,38,2),(7,39,1),(8,40,1);
/*!40000 ALTER TABLE `user_faculties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_oauths`
--

DROP TABLE IF EXISTS `user_oauths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_oauths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `provider` varchar(255) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_duplicates` (`user_id`,`provider`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_oauths_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_oauths`
--

LOCK TABLES `user_oauths` WRITE;
/*!40000 ALTER TABLE `user_oauths` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_oauths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_tutors`
--

DROP TABLE IF EXISTS `user_tutors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tutors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `course_id` int(11) NOT NULL DEFAULT '0',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_duplicates` (`course_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_tutors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_tutors_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tutors`
--

LOCK TABLES `user_tutors` WRITE;
/*!40000 ALTER TABLE `user_tutors` DISABLE KEYS */;
INSERT INTO `user_tutors` VALUES (1,35,1,0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(2,36,1,0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(3,37,2,0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36'),(4,37,3,0,'2020-02-26 18:32:36',NULL,'2020-02-26 18:32:36');
/*!40000 ALTER TABLE `user_tutors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL DEFAULT '',
  `first_name` varchar(80) DEFAULT '',
  `last_name` varchar(80) DEFAULT '',
  `student_no` varchar(30) DEFAULT '',
  `title` varchar(80) DEFAULT '',
  `email` varchar(254) DEFAULT '',
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `last_accessed` varchar(10) DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `lti_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `lti_id` (`lti_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'root','5f4dcc3b5aa765d61d8327deb882cf99','Super','Admin',NULL,NULL,'',NULL,NULL,NULL,'A',1,'2020-02-26 18:32:35',NULL,'2020-02-26 10:32:37','f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd'),(2,'instructor1','b17c3f638781ecd22648b509e138c00f','Instructor','1',NULL,'Instructor','instructor1@email',NULL,NULL,NULL,'A',1,'2006-06-19 16:25:24',NULL,'2006-06-19 16:25:24',NULL),(3,'instructor2','b17c3f638781ecd22648b509e138c00f','Instructor','2',NULL,'Professor','',NULL,NULL,NULL,'A',1,'2006-06-20 14:17:02',NULL,'2006-06-20 14:17:02',NULL),(4,'instructor3','b17c3f638781ecd22648b509e138c00f','Instructor','3',NULL,'Assistant Professor','',NULL,NULL,NULL,'A',1,'2006-06-20 14:17:53',NULL,'2006-06-20 14:17:53',NULL),(5,'redshirt0001','b17c3f638781ecd22648b509e138c00f','Ed','Student','65498451',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:19:18',NULL,'2006-06-20 14:19:18',NULL),(6,'redshirt0002','b17c3f638781ecd22648b509e138c00f','Alex','Student','65468188',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:26:59',NULL,'2006-06-20 14:26:59',NULL),(7,'redshirt0003','b17c3f638781ecd22648b509e138c00f','Matt','Student','98985481',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:27:24',NULL,'2006-06-20 14:27:24',NULL),(8,'redshirt0004','b17c3f638781ecd22648b509e138c00f','Chris','Student','16585158',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:27:43',NULL,'2006-06-20 14:27:43',NULL),(9,'redshirt0005','b17c3f638781ecd22648b509e138c00f','Johnny','Student','81121651',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:28:08',NULL,'2006-06-20 14:28:08',NULL),(10,'redshirt0006','b17c3f638781ecd22648b509e138c00f','Travis','Student','87800283',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:28:29',NULL,'2006-06-20 14:28:29',NULL),(11,'redshirt0007','b17c3f638781ecd22648b509e138c00f','Kelly','Student','68541180',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:28:49',NULL,'2006-06-20 14:28:49',NULL),(12,'redshirt0008','b17c3f638781ecd22648b509e138c00f','Peter','Student','48451389',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:29:07',NULL,'2006-06-20 14:29:07',NULL),(13,'redshirt0009','b17c3f638781ecd22648b509e138c00f','Damien','Student','84188465',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:31:17',NULL,'2006-06-20 14:31:17',NULL),(14,'redshirt0010','b17c3f638781ecd22648b509e138c00f','Hajar','Student','27701036',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 14:47:34',NULL,'2006-06-20 14:47:34',NULL),(15,'redshirt0011','b17c3f638781ecd22648b509e138c00f','Jennifer','Student','48877031',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:00:35',NULL,'2006-06-20 15:00:35',NULL),(16,'redshirt0012','b17c3f638781ecd22648b509e138c00f','Chad','Student','25731063',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:01:09',NULL,'2006-06-20 15:01:09',NULL),(17,'redshirt0013','b17c3f638781ecd22648b509e138c00f','Edna','Student','37116036',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:01:24',NULL,'2006-06-20 15:01:24',NULL),(18,'redshirt0014','b17c3f638781ecd22648b509e138c00f','Denny','Student','76035030',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:01:52',NULL,'2006-06-20 15:01:52',NULL),(19,'redshirt0015','b17c3f638781ecd22648b509e138c00f','Jonathan','Student','90938044',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:02:20',NULL,'2006-06-20 15:02:20',NULL),(20,'redshirt0016','b17c3f638781ecd22648b509e138c00f','Soroush','Student','88505045',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:03:27',NULL,'2006-06-20 15:03:27',NULL),(21,'redshirt0017','b17c3f638781ecd22648b509e138c00f','Nicole','Student','22784037',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:03:47',NULL,'2006-06-20 15:03:47',NULL),(22,'redshirt0018','b17c3f638781ecd22648b509e138c00f','Vivian','Student','37048022',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:04:22',NULL,'2006-06-20 15:04:22',NULL),(23,'redshirt0019','b17c3f638781ecd22648b509e138c00f','Trevor','Student','89947048',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:05:55',NULL,'2006-06-20 15:05:55',NULL),(24,'redshirt0020','b17c3f638781ecd22648b509e138c00f','Michael','Student','39823059',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:06:20',NULL,'2006-06-20 15:06:20',NULL),(25,'redshirt0021','b17c3f638781ecd22648b509e138c00f','Steven','Student','35644039',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:06:46',NULL,'2006-06-20 15:06:46',NULL),(26,'redshirt0022','b17c3f638781ecd22648b509e138c00f','Bill','Student','19524032',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:07:01',NULL,'2006-06-20 15:07:01',NULL),(27,'redshirt0023','b17c3f638781ecd22648b509e138c00f','Van Hong','Student','40289059',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:07:37',NULL,'2006-06-20 15:07:37',NULL),(28,'redshirt0024','b17c3f638781ecd22648b509e138c00f','Michael','Student','38058020',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:08:04',NULL,'2006-06-20 15:08:04',NULL),(29,'redshirt0025','b17c3f638781ecd22648b509e138c00f','Jonathan','Student','38861035',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:08:31',NULL,'2006-06-20 15:08:31',NULL),(30,'redshirt0026','b17c3f638781ecd22648b509e138c00f','Geoff','Student','27879030',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:08:47',NULL,'2006-06-20 15:08:47',NULL),(31,'redshirt0027','b17c3f638781ecd22648b509e138c00f','Hui','Student','10186039',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:10:16',NULL,'2006-06-20 15:10:16',NULL),(32,'redshirt0028','b17c3f638781ecd22648b509e138c00f','Bowinn','Student','19803030',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-20 15:10:32',NULL,'2006-06-20 15:10:32',NULL),(33,'redshirt0029','b17c3f638781ecd22648b509e138c00f','Joe','Student','51516498',NULL,'',NULL,NULL,NULL,'A',1,'2006-06-21 08:44:09',33,'2006-06-21 08:45:00',NULL),(34,'admin1','b17c3f638781ecd22648b509e138c00f','','','','','',NULL,NULL,NULL,'A',1,'2012-05-25 15:48:08',1,'2012-05-25 15:48:08',NULL),(35,'tutor1','b17c3f638781ecd22648b509e138c00f','Tutor','1','','','',NULL,NULL,NULL,'A',1,'2012-05-25 15:48:08',1,'2012-05-25 15:48:08',NULL),(36,'tutor2','b17c3f638781ecd22648b509e138c00f','Tutor','2','','','',NULL,NULL,NULL,'A',1,'2012-05-25 15:48:08',1,'2012-05-25 15:48:08',NULL),(37,'tutor3','b17c3f638781ecd22648b509e138c00f','Tutor','3','','','',NULL,NULL,NULL,'A',1,'2012-05-25 15:48:08',1,'2012-05-25 15:48:08',NULL),(38,'admin2','b17c3f638781ecd22648b509e138c00f','','','','','',NULL,NULL,NULL,'A',1,'2012-05-25 15:48:08',1,'2012-05-25 15:48:08',NULL),(39,'admin3','b17c3f638781ecd22648b509e138c00f','','','','','',NULL,NULL,NULL,'A',1,'2014-12-15 00:00:00',1,'2014-12-16 00:00:00',NULL),(40,'admin4','b17c3f638781ecd22648b509e138c00f','','','','','',NULL,NULL,NULL,'A',1,'2014-12-15 00:00:00',1,'2014-12-16 00:00:00',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-26 18:34:02
