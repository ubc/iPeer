-- phpMyAdmin SQL Dump
-- version 2.6.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 27, 2006 at 10:51 AM
-- Server version: 4.1.11
-- PHP Version: 4.3.10-16
--
-- Database: `ipeer2`
--

SET foreign_key_checks = 0;

-- --------------------------------------------------------

--
-- Table structure for table `acos`
--

DROP TABLE IF EXISTS `acos`;
CREATE TABLE IF NOT EXISTS `acos` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=365 ;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` VALUES (1,NULL,NULL,NULL,'adminpage',1,2),(2,NULL,NULL,NULL,'controllers',3,644),(3,2,NULL,NULL,'Pages',4,17),(4,3,NULL,NULL,'display',5,6),(5,3,NULL,NULL,'add',7,8),(6,3,NULL,NULL,'edit',9,10),(7,3,NULL,NULL,'index',11,12),(8,3,NULL,NULL,'view',13,14),(9,3,NULL,NULL,'delete',15,16),(10,2,NULL,NULL,'Courses',18,35),(11,10,NULL,NULL,'daysLate',19,20),(12,10,NULL,NULL,'index',21,22),(13,10,NULL,NULL,'ajaxList',23,24),(14,10,NULL,NULL,'view',25,26),(15,10,NULL,NULL,'home',27,28),(16,10,NULL,NULL,'add',29,30),(17,10,NULL,NULL,'edit',31,32),(18,10,NULL,NULL,'delete',33,34),(19,2,NULL,NULL,'Departments',36,47),(20,19,NULL,NULL,'index',37,38),(21,19,NULL,NULL,'view',39,40),(22,19,NULL,NULL,'add',41,42),(23,19,NULL,NULL,'edit',43,44),(24,19,NULL,NULL,'delete',45,46),(25,2,NULL,NULL,'Emailer',48,79),(26,25,NULL,NULL,'setUpAjaxList',49,50),(27,25,NULL,NULL,'ajaxList',51,52),(28,25,NULL,NULL,'index',53,54),(29,25,NULL,NULL,'write',55,56),(30,25,NULL,NULL,'cancel',57,58),(31,25,NULL,NULL,'view',59,60),(32,25,NULL,NULL,'addRecipient',61,62),(33,25,NULL,NULL,'deleteRecipient',63,64),(34,25,NULL,NULL,'getRecipient',65,66),(35,25,NULL,NULL,'searchByUserId',67,68),(36,25,NULL,NULL,'doMerge',69,70),(37,25,NULL,NULL,'send',71,72),(38,25,NULL,NULL,'add',73,74),(39,25,NULL,NULL,'edit',75,76),(40,25,NULL,NULL,'delete',77,78),(41,2,NULL,NULL,'Emailtemplates',80,99),(42,41,NULL,NULL,'setUpAjaxList',81,82),(43,41,NULL,NULL,'ajaxList',83,84),(44,41,NULL,NULL,'index',85,86),(45,41,NULL,NULL,'add',87,88),(46,41,NULL,NULL,'edit',89,90),(47,41,NULL,NULL,'delete',91,92),(48,41,NULL,NULL,'view',93,94),(49,41,NULL,NULL,'displayTemplateContent',95,96),(50,41,NULL,NULL,'displayTemplateSubject',97,98),(51,2,NULL,NULL,'Evaltools',100,113),(52,51,NULL,NULL,'index',101,102),(53,51,NULL,NULL,'showAll',103,104),(54,51,NULL,NULL,'add',105,106),(55,51,NULL,NULL,'edit',107,108),(56,51,NULL,NULL,'view',109,110),(57,51,NULL,NULL,'delete',111,112),(58,2,NULL,NULL,'Evaluations',114,181),(59,58,NULL,NULL,'postProcess',115,116),(60,58,NULL,NULL,'setUpAjaxList',117,118),(61,58,NULL,NULL,'ajaxList',119,120),(62,58,NULL,NULL,'view',121,122),(63,58,NULL,NULL,'index',123,124),(64,58,NULL,NULL,'update',125,126),(65,58,NULL,NULL,'export',127,128),(66,58,NULL,NULL,'makeEvaluation',129,130),(67,58,NULL,NULL,'makeSimpleEvaluation',131,132),(68,58,NULL,NULL,'makeSurveyEvaluation',133,134),(69,58,NULL,NULL,'validSurveyEvalComplete',135,136),(70,58,NULL,NULL,'makeRubricEvaluation',137,138),(71,58,NULL,NULL,'validRubricEvalComplete',139,140),(72,58,NULL,NULL,'completeEvaluationRubric',141,142),(73,58,NULL,NULL,'makeMixevalEvaluation',143,144),(74,58,NULL,NULL,'validMixevalEvalComplete',145,146),(75,58,NULL,NULL,'completeEvaluationMixeval',147,148),(76,58,NULL,NULL,'viewEvaluationResults',149,150),(77,58,NULL,NULL,'viewSurveyGroupEvaluationResults',151,152),(78,58,NULL,NULL,'studentViewEvaluationResult',153,154),(79,58,NULL,NULL,'markEventReviewed',155,156),(80,58,NULL,NULL,'markGradeRelease',157,158),(81,58,NULL,NULL,'markCommentRelease',159,160),(82,58,NULL,NULL,'changeAllCommentRelease',161,162),(83,58,NULL,NULL,'changeAllGradeRelease',163,164),(84,58,NULL,NULL,'viewGroupSubmissionDetails',165,166),(85,58,NULL,NULL,'viewSurveySummary',167,168),(86,58,NULL,NULL,'export_rubic',169,170),(87,58,NULL,NULL,'export_test',171,172),(88,58,NULL,NULL,'pre',173,174),(89,58,NULL,NULL,'add',175,176),(90,58,NULL,NULL,'edit',177,178),(91,58,NULL,NULL,'delete',179,180),(92,2,NULL,NULL,'Events',182,213),(93,92,NULL,NULL,'postProcessData',183,184),(94,92,NULL,NULL,'setUpAjaxList',185,186),(95,92,NULL,NULL,'index',187,188),(96,92,NULL,NULL,'ajaxList',189,190),(97,92,NULL,NULL,'view',191,192),(98,92,NULL,NULL,'eventTemplatesList',193,194),(99,92,NULL,NULL,'add',195,196),(100,92,NULL,NULL,'edit',197,198),(101,92,NULL,NULL,'delete',199,200),(102,92,NULL,NULL,'search',201,202),(103,92,NULL,NULL,'checkDuplicateTitle',203,204),(104,92,NULL,NULL,'viewGroups',205,206),(105,92,NULL,NULL,'editGroup',207,208),(106,92,NULL,NULL,'getAssignedGroups',209,210),(107,92,NULL,NULL,'update',211,212),(108,2,NULL,NULL,'Faculties',214,225),(109,108,NULL,NULL,'index',215,216),(110,108,NULL,NULL,'view',217,218),(111,108,NULL,NULL,'add',219,220),(112,108,NULL,NULL,'edit',221,222),(113,108,NULL,NULL,'delete',223,224),(114,2,NULL,NULL,'Framework',226,243),(115,114,NULL,NULL,'calendarDisplay',227,228),(116,114,NULL,NULL,'userInfoDisplay',229,230),(117,114,NULL,NULL,'tutIndex',231,232),(118,114,NULL,NULL,'add',233,234),(119,114,NULL,NULL,'edit',235,236),(120,114,NULL,NULL,'index',237,238),(121,114,NULL,NULL,'view',239,240),(122,114,NULL,NULL,'delete',241,242),(123,2,NULL,NULL,'Groups',244,277),(124,123,NULL,NULL,'postProcess',245,246),(125,123,NULL,NULL,'setUpAjaxList',247,248),(126,123,NULL,NULL,'index',249,250),(127,123,NULL,NULL,'ajaxList',251,252),(128,123,NULL,NULL,'goToClassList',253,254),(129,123,NULL,NULL,'view',255,256),(130,123,NULL,NULL,'add',257,258),(131,123,NULL,NULL,'edit',259,260),(132,123,NULL,NULL,'delete',261,262),(133,123,NULL,NULL,'checkDuplicateName',263,264),(134,123,NULL,NULL,'getQueryAttribute',265,266),(135,123,NULL,NULL,'import',267,268),(136,123,NULL,NULL,'addGroupByImport',269,270),(137,123,NULL,NULL,'update',271,272),(138,123,NULL,NULL,'export',273,274),(139,123,NULL,NULL,'sendGroupEmail',275,276),(140,2,NULL,NULL,'Home',278,289),(141,140,NULL,NULL,'index',279,280),(142,140,NULL,NULL,'add',281,282),(143,140,NULL,NULL,'edit',283,284),(144,140,NULL,NULL,'view',285,286),(145,140,NULL,NULL,'delete',287,288),(146,2,NULL,NULL,'Install',290,311),(147,146,NULL,NULL,'index',291,292),(148,146,NULL,NULL,'install2',293,294),(149,146,NULL,NULL,'install3',295,296),(150,146,NULL,NULL,'install4',297,298),(151,146,NULL,NULL,'install5',299,300),(152,146,NULL,NULL,'gpl',301,302),(153,146,NULL,NULL,'add',303,304),(154,146,NULL,NULL,'edit',305,306),(155,146,NULL,NULL,'view',307,308),(156,146,NULL,NULL,'delete',309,310),(157,2,NULL,NULL,'Lti',312,323),(158,157,NULL,NULL,'index',313,314),(159,157,NULL,NULL,'add',315,316),(160,157,NULL,NULL,'edit',317,318),(161,157,NULL,NULL,'view',319,320),(162,157,NULL,NULL,'delete',321,322),(163,2,NULL,NULL,'Mixevals',324,353),(164,163,NULL,NULL,'postProcess',325,326),(165,163,NULL,NULL,'setUpAjaxList',327,328),(166,163,NULL,NULL,'index',329,330),(167,163,NULL,NULL,'ajaxList',331,332),(168,163,NULL,NULL,'view',333,334),(169,163,NULL,NULL,'add',335,336),(170,163,NULL,NULL,'deleteQuestion',337,338),(171,163,NULL,NULL,'deleteDescriptor',339,340),(172,163,NULL,NULL,'edit',341,342),(173,163,NULL,NULL,'copy',343,344),(174,163,NULL,NULL,'delete',345,346),(175,163,NULL,NULL,'previewMixeval',347,348),(176,163,NULL,NULL,'renderRows',349,350),(177,163,NULL,NULL,'update',351,352),(178,2,NULL,NULL,'OauthClients',354,365),(179,178,NULL,NULL,'index',355,356),(180,178,NULL,NULL,'add',357,358),(181,178,NULL,NULL,'edit',359,360),(182,178,NULL,NULL,'delete',361,362),(183,178,NULL,NULL,'view',363,364),(184,2,NULL,NULL,'OauthTokens',366,377),(185,184,NULL,NULL,'index',367,368),(186,184,NULL,NULL,'add',369,370),(187,184,NULL,NULL,'edit',371,372),(188,184,NULL,NULL,'delete',373,374),(189,184,NULL,NULL,'view',375,376),(190,2,NULL,NULL,'Penalty',378,391),(191,190,NULL,NULL,'save',379,380),(192,190,NULL,NULL,'add',381,382),(193,190,NULL,NULL,'edit',383,384),(194,190,NULL,NULL,'index',385,386),(195,190,NULL,NULL,'view',387,388),(196,190,NULL,NULL,'delete',389,390),(197,2,NULL,NULL,'Rubrics',392,413),(198,197,NULL,NULL,'postProcess',393,394),(199,197,NULL,NULL,'setUpAjaxList',395,396),(200,197,NULL,NULL,'index',397,398),(201,197,NULL,NULL,'ajaxList',399,400),(202,197,NULL,NULL,'view',401,402),(203,197,NULL,NULL,'add',403,404),(204,197,NULL,NULL,'edit',405,406),(205,197,NULL,NULL,'copy',407,408),(206,197,NULL,NULL,'delete',409,410),(207,197,NULL,NULL,'setForm_RubricName',411,412),(208,2,NULL,NULL,'Searchs',414,441),(209,208,NULL,NULL,'update',415,416),(210,208,NULL,NULL,'index',417,418),(211,208,NULL,NULL,'searchEvaluation',419,420),(212,208,NULL,NULL,'searchResult',421,422),(213,208,NULL,NULL,'searchInstructor',423,424),(214,208,NULL,NULL,'eventBoxSearch',425,426),(215,208,NULL,NULL,'formatSearchEvaluation',427,428),(216,208,NULL,NULL,'formatSearchInstructor',429,430),(217,208,NULL,NULL,'formatSearchEvaluationResult',431,432),(218,208,NULL,NULL,'add',433,434),(219,208,NULL,NULL,'edit',435,436),(220,208,NULL,NULL,'view',437,438),(221,208,NULL,NULL,'delete',439,440),(222,2,NULL,NULL,'Simpleevaluations',442,461),(223,222,NULL,NULL,'postProcess',443,444),(224,222,NULL,NULL,'setUpAjaxList',445,446),(225,222,NULL,NULL,'index',447,448),(226,222,NULL,NULL,'ajaxList',449,450),(227,222,NULL,NULL,'view',451,452),(228,222,NULL,NULL,'add',453,454),(229,222,NULL,NULL,'edit',455,456),(230,222,NULL,NULL,'copy',457,458),(231,222,NULL,NULL,'delete',459,460),(232,2,NULL,NULL,'Surveygroups',462,493),(233,232,NULL,NULL,'postProcess',463,464),(234,232,NULL,NULL,'setUpAjaxList',465,466),(235,232,NULL,NULL,'index',467,468),(236,232,NULL,NULL,'ajaxList',469,470),(237,232,NULL,NULL,'viewresult',471,472),(238,232,NULL,NULL,'makegroups',473,474),(239,232,NULL,NULL,'makegroupssearch',475,476),(240,232,NULL,NULL,'maketmgroups',477,478),(241,232,NULL,NULL,'savegroups',479,480),(242,232,NULL,NULL,'release',481,482),(243,232,NULL,NULL,'delete',483,484),(244,232,NULL,NULL,'edit',485,486),(245,232,NULL,NULL,'changegroupset',487,488),(246,232,NULL,NULL,'add',489,490),(247,232,NULL,NULL,'view',491,492),(248,2,NULL,NULL,'Surveys',494,527),(249,248,NULL,NULL,'setUpAjaxList',495,496),(250,248,NULL,NULL,'index',497,498),(251,248,NULL,NULL,'ajaxList',499,500),(252,248,NULL,NULL,'view',501,502),(253,248,NULL,NULL,'add',503,504),(254,248,NULL,NULL,'edit',505,506),(255,248,NULL,NULL,'copy',507,508),(256,248,NULL,NULL,'delete',509,510),(257,248,NULL,NULL,'checkDuplicateName',511,512),(258,248,NULL,NULL,'releaseSurvey',513,514),(259,248,NULL,NULL,'questionsSummary',515,516),(260,248,NULL,NULL,'moveQuestion',517,518),(261,248,NULL,NULL,'removeQuestion',519,520),(262,248,NULL,NULL,'addQuestion',521,522),(263,248,NULL,NULL,'editQuestion',523,524),(264,248,NULL,NULL,'update',525,526),(265,2,NULL,NULL,'Sysparameters',528,543),(266,265,NULL,NULL,'setUpAjaxList',529,530),(267,265,NULL,NULL,'index',531,532),(268,265,NULL,NULL,'ajaxList',533,534),(269,265,NULL,NULL,'view',535,536),(270,265,NULL,NULL,'add',537,538),(271,265,NULL,NULL,'edit',539,540),(272,265,NULL,NULL,'delete',541,542),(273,2,NULL,NULL,'Upgrade',544,559),(274,273,NULL,NULL,'index',545,546),(275,273,NULL,NULL,'step2',547,548),(276,273,NULL,NULL,'checkPermission',549,550),(277,273,NULL,NULL,'add',551,552),(278,273,NULL,NULL,'edit',553,554),(279,273,NULL,NULL,'view',555,556),(280,273,NULL,NULL,'delete',557,558),(281,2,NULL,NULL,'Users',560,589),(282,281,NULL,NULL,'ajaxList',561,562),(283,281,NULL,NULL,'index',563,564),(284,281,NULL,NULL,'goToClassList',565,566),(285,281,NULL,NULL,'determineIfStudentFromThisData',567,568),(286,281,NULL,NULL,'getSimpleEntrollmentLists',569,570),(287,281,NULL,NULL,'view',571,572),(288,281,NULL,NULL,'add',573,574),(289,281,NULL,NULL,'edit',575,576),(290,281,NULL,NULL,'editProfile',577,578),(291,281,NULL,NULL,'delete',579,580),(292,281,NULL,NULL,'checkDuplicateName',581,582),(293,281,NULL,NULL,'resetPassword',583,584),(294,281,NULL,NULL,'import',585,586),(295,281,NULL,NULL,'update',587,588),(296,2,NULL,NULL,'V1',590,625),(297,296,NULL,NULL,'oauth',591,592),(298,296,NULL,NULL,'oauth_error',593,594),(299,296,NULL,NULL,'users',595,596),(300,296,NULL,NULL,'courses',597,598),(301,296,NULL,NULL,'groups',599,600),(302,296,NULL,NULL,'groupMembers',601,602),(303,296,NULL,NULL,'events',603,604),(304,296,NULL,NULL,'grades',605,606),(305,296,NULL,NULL,'departments',607,608),(306,296,NULL,NULL,'courseDepartments',609,610),(307,296,NULL,NULL,'userEvents',611,612),(308,296,NULL,NULL,'enrolment',613,614),(309,296,NULL,NULL,'add',615,616),(310,296,NULL,NULL,'edit',617,618),(311,296,NULL,NULL,'index',619,620),(312,296,NULL,NULL,'view',621,622),(313,296,NULL,NULL,'delete',623,624),(314,2,NULL,NULL,'Guard',626,643),(315,314,NULL,NULL,'Guard',627,642),(316,315,NULL,NULL,'login',628,629),(317,315,NULL,NULL,'logout',630,631),(318,315,NULL,NULL,'add',632,633),(319,315,NULL,NULL,'edit',634,635),(320,315,NULL,NULL,'index',636,637),(321,315,NULL,NULL,'view',638,639),(322,315,NULL,NULL,'delete',640,641),(323,NULL,NULL,NULL,'functions',645,708),(324,323,NULL,NULL,'user',646,673),(325,324,NULL,NULL,'superadmin',647,648),(326,324,NULL,NULL,'admin',649,650),(327,324,NULL,NULL,'instructor',651,652),(328,324,NULL,NULL,'tutor',653,654),(329,324,NULL,NULL,'student',655,656),(330,324,NULL,NULL,'import',657,658),(331,324,NULL,NULL,'password_reset',659,670),(332,331,NULL,NULL,'superadmin',660,661),(333,331,NULL,NULL,'admin',662,663),(334,331,NULL,NULL,'instructor',664,665),(335,331,NULL,NULL,'tutor',666,667),(336,331,NULL,NULL,'student',668,669),(337,324,NULL,NULL,'index',671,672),(338,323,NULL,NULL,'role',674,685),(339,338,NULL,NULL,'superadmin',675,676),(340,338,NULL,NULL,'admin',677,678),(341,338,NULL,NULL,'instructor',679,680),(342,338,NULL,NULL,'tutor',681,682),(343,338,NULL,NULL,'student',683,684),(344,323,NULL,NULL,'evaluation',686,689),(345,344,NULL,NULL,'export',687,688),(346,323,NULL,NULL,'email',690,697),(347,346,NULL,NULL,'allUsers',691,692),(348,346,NULL,NULL,'allGroups',693,694),(349,346,NULL,NULL,'allCourses',695,696),(350,323,NULL,NULL,'emailtemplate',698,699),(351,323,NULL,NULL,'viewstudentresults',700,701),(352,323,NULL,NULL,'viewemailaddresses',702,703),(353,323,NULL,NULL,'superadmin',704,705),(354,323,NULL,NULL,'onlytakeeval',706,707);

-- --------------------------------------------------------

--
-- Table structure for table `aros`
--

DROP TABLE IF EXISTS `aros`;
CREATE TABLE IF NOT EXISTS `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `aros`
--

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Role', 1, NULL, 1, 2),
(2, NULL, 'Role', 2, NULL, 3, 4),
(3, NULL, 'Role', 3, NULL, 5, 6),
(4, NULL, 'Role', 4, NULL, 7, 8),
(5, NULL, 'Role', 5, NULL, 9, 10);

-- --------------------------------------------------------

--
-- Table structure for table `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
CREATE TABLE IF NOT EXISTS `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `aros_acos`
--

INSERT INTO `aros_acos` VALUES (1,1,2,'1','1','1','1'),(2,1,323,'1','1','1','1'),(3,1,51,'1','1','1','1'),(4,1,222,'1','1','1','1'),(5,1,197,'1','1','1','1'),(6,1,163,'1','1','1','1'),(7,1,248,'1','1','1','1'),(8,1,25,'1','1','1','1'),(9,1,41,'1','1','1','1'),(10,1,92,'1','1','1','1'),(11,1,108,'1','1','1','1'),(12,1,19,'1','1','1','1'),(13,1,123,'1','1','1','1'),(14,1,1,'1','1','1','1'),(15,1,352,'1','1','1','1'),(16,1,353,'1','1','1','1'),(17,2,2,'-1','-1','-1','-1'),(18,2,140,'1','1','1','1'),(19,2,10,'1','1','1','1'),(20,2,19,'1','1','1','1'),(21,2,25,'1','1','1','1'),(22,2,41,'1','1','1','1'),(23,2,51,'1','1','1','1'),(24,2,92,'1','1','1','1'),(25,2,123,'1','1','1','1'),(26,2,163,'1','1','1','1'),(27,2,197,'1','1','1','1'),(28,2,222,'1','1','1','1'),(29,2,248,'1','1','1','1'),(30,2,281,'1','1','1','1'),(31,2,58,'1','1','1','1'),(32,2,317,'1','1','1','1'),(33,2,323,'-1','-1','-1','-1'),(34,2,350,'1','1','1','1'),(35,2,344,'1','1','1','1'),(36,2,347,'1','1','1','1'),(37,2,324,'1','1','1','1'),(38,2,326,'-1','1','-1','-1'),(39,2,325,'-1','-1','-1','-1'),(40,2,1,'1','1','1','1'),(41,2,352,'1','1','1','1'),(42,2,353,'-1','-1','-1','-1'),(43,3,2,'-1','-1','-1','-1'),(44,3,140,'1','1','1','1'),(45,3,10,'1','1','1','1'),(46,3,16,'-1','-1','-1','-1'),(47,3,17,'-1','-1','-1','-1'),(48,3,25,'1','1','1','1'),(49,3,41,'1','1','1','1'),(50,3,51,'1','1','1','1'),(51,3,92,'1','1','1','1'),(52,3,123,'1','1','1','1'),(53,3,163,'1','1','1','1'),(54,3,197,'1','1','1','1'),(55,3,222,'1','1','1','1'),(56,3,248,'1','1','1','1'),(57,3,281,'1','1','1','1'),(58,3,317,'1','1','1','1'),(59,3,323,'-1','-1','-1','-1'),(60,3,344,'1','1','-1','-1'),(61,3,324,'1','1','1','1'),(62,3,326,'-1','-1','-1','-1'),(63,3,325,'-1','-1','-1','-1'),(64,3,327,'-1','1','-1','-1'),(65,3,337,'-1','-1','-1','-1'),(66,3,352,'-1','-1','-1','-1'),(67,3,353,'-1','-1','-1','-1'),(68,3,354,'-1','-1','-1','-1'),(69,4,2,'-1','-1','-1','-1'),(70,4,140,'1','1','1','1'),(71,4,10,'-1','-1','-1','-1'),(72,4,25,'-1','-1','-1','-1'),(73,4,41,'-1','-1','-1','-1'),(74,4,51,'-1','-1','-1','-1'),(75,4,92,'-1','-1','-1','-1'),(76,4,123,'-1','-1','-1','-1'),(77,4,163,'-1','-1','-1','-1'),(78,4,197,'-1','-1','-1','-1'),(79,4,222,'-1','-1','-1','-1'),(80,4,248,'-1','-1','-1','-1'),(81,4,281,'-1','-1','-1','-1'),(82,4,317,'1','1','1','1'),(83,4,66,'1','1','1','1'),(84,4,67,'1','1','1','1'),(85,4,70,'1','1','1','1'),(86,4,73,'1','1','1','1'),(87,4,68,'1','1','1','1'),(88,4,78,'1','1','1','1'),(89,4,72,'1','1','1','1'),(90,4,75,'1','1','1','1'),(91,4,323,'-1','-1','-1','-1'),(92,4,352,'-1','-1','-1','-1'),(93,4,353,'-1','-1','-1','-1'),(94,4,354,'1','1','1','1'),(95,5,2,'-1','-1','-1','-1'),(96,5,140,'1','1','1','1'),(97,5,10,'-1','-1','-1','-1'),(98,5,25,'-1','-1','-1','-1'),(99,5,41,'-1','-1','-1','-1'),(100,5,51,'-1','-1','-1','-1'),(101,5,92,'-1','-1','-1','-1'),(102,5,123,'-1','-1','-1','-1'),(103,5,163,'-1','-1','-1','-1'),(104,5,197,'-1','-1','-1','-1'),(105,5,222,'-1','-1','-1','-1'),(106,5,248,'-1','-1','-1','-1'),(107,5,281,'-1','-1','-1','-1'),(108,5,317,'1','1','1','1'),(109,5,66,'1','1','1','1'),(110,5,67,'1','1','1','1'),(111,5,70,'1','1','1','1'),(112,5,73,'1','1','1','1'),(113,5,68,'1','1','1','1'),(114,5,78,'1','1','1','1'),(115,5,72,'1','1','1','1'),(116,5,75,'1','1','1','1'),(117,5,323,'-1','-1','-1','-1'),(118,5,351,'1','1','1','1'),(119,5,352,'-1','-1','-1','-1'),(120,5,353,'-1','-1','-1','-1'),(121,5,354,'1','1','1','1');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` int(11) NOT NULL auto_increment,
  `course` varchar(80) NOT NULL default '',
  `title` varchar(80) default NULL,
  `homepage` varchar(100) default NULL,
  `self_enroll` varchar(3) default 'off',
  `password` varchar(25) default NULL,
  `record_status` varchar(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  `instructor_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `course` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL DEFAULT '',
  `password` varchar(80) NOT NULL DEFAULT '',
  `first_name` varchar(80) DEFAULT NULL,
  `last_name` varchar(80) DEFAULT NULL,
  `student_no` varchar(30) DEFAULT NULL,
  `title` varchar(80) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `last_accessed` varchar(10) DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `lti_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE (`lti_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `student_no`, `title`, `email`, `last_login`, `last_logout`, `last_accessed`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`, `lti_id`) VALUES
(1, 'root', '', 'Super', 'Admin', NULL, NULL, '', NULL, NULL, NULL, 'A', 1, NOW(), NULL, NOW(), NULL);


-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

DROP TABLE IF EXISTS `faculties`;
CREATE TABLE IF NOT EXISTS `faculties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `faculty_id` int NOT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `course_departments`
--

DROP TABLE IF EXISTS `course_departments`;
CREATE TABLE `course_departments` (
  `id` int NOT NULL auto_increment,
  `course_id` int NOT NULL,
  `department_id` int NOT NULL,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_merges`
--

DROP TABLE IF EXISTS `email_merges`;
CREATE TABLE IF NOT EXISTS `email_merges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `value` varchar(80) NOT NULL,
  `table_name` varchar(80) NOT NULL,
  `field_name` varchar(80) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `email_merges`
--

INSERT INTO `email_merges` (`id`, `key`, `value`, `table_name`, `field_name`, `created`, `modified`) VALUES
(1, 'Username', '{{{USERNAME}}}', 'User', 'username', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'First Name', '{{{FIRSTNAME}}}', 'User', 'first_name', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Last Name', '{{{LASTNAME}}}', 'User', 'last_name', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Email Address', '{{{Email}}}', 'User', 'email', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `email_schedules`
--

DROP TABLE IF EXISTS `email_schedules`;
CREATE TABLE IF NOT EXISTS `email_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `from` varchar(80) NOT NULL,
  `to` varchar(600) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `grp_id` int(11) DEFAULT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `creator_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `description` text NOT NULL,
  `subject` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `availability` tinyint(4) NOT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00',
  `updater_id` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_mixeval_details`
--

DROP TABLE IF EXISTS `evaluation_mixeval_details`;
CREATE TABLE IF NOT EXISTS `evaluation_mixeval_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluation_mixeval_id` int(11) NOT NULL DEFAULT '0',
  `question_number` int(11) NOT NULL DEFAULT '0',
  `question_comment` text,
  `selected_lom` int(11) NOT NULL DEFAULT '0',
  `grade` double(12,2) NOT NULL DEFAULT '0.00',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evaluation_mixeval_id` (`evaluation_mixeval_id`,`question_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_mixevals`
--

DROP TABLE IF EXISTS `evaluation_mixevals`;
CREATE TABLE IF NOT EXISTS `evaluation_mixevals` (
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
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `evaluation_rubric_details`
--

DROP TABLE IF EXISTS `evaluation_rubric_details`;
CREATE TABLE IF NOT EXISTS `evaluation_rubric_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluation_rubric_id` int(11) NOT NULL DEFAULT '0',
  `criteria_number` int(11) NOT NULL DEFAULT '0',
  `criteria_comment` varchar(255) DEFAULT NULL,
  `selected_lom` int(11) NOT NULL DEFAULT '0',
  `grade` double(12,2) NOT NULL DEFAULT '0.00',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evaluation_rubric_id` (`evaluation_rubric_id`,`criteria_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_rubrics`
--

DROP TABLE IF EXISTS `evaluation_rubrics`;
CREATE TABLE IF NOT EXISTS `evaluation_rubrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluator` int(11) NOT NULL DEFAULT '0',
  `evaluatee` int(11) NOT NULL DEFAULT '0',
  `general_comment` text,
  `score` double(12,2) NOT NULL DEFAULT '0.00',
  `comment_release` int(1) NOT NULL DEFAULT '0',
  `grade_release` int(1) NOT NULL DEFAULT '0',
  `grp_event_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `rubric_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_simples`
--

DROP TABLE IF EXISTS `evaluation_simples`;
CREATE TABLE IF NOT EXISTS `evaluation_simples` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evaluator` int(11) NOT NULL DEFAULT '0',
  `evaluatee` int(11) NOT NULL DEFAULT '0',
  `score` int(5) NOT NULL DEFAULT '0',
  `eval_comment` text,
  `release_status` int(1) NOT NULL DEFAULT '0',
  `grp_event_id` int(11) NOT NULL DEFAULT '0',
  `event_id` bigint(11) NOT NULL DEFAULT '0',
  `date_submitted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `grade_release` int(1) DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_submissions`
--

DROP TABLE IF EXISTS `evaluation_submissions`;
CREATE TABLE IF NOT EXISTS `evaluation_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) NOT NULL DEFAULT '0',
  `grp_event_id` int(11) DEFAULT NULL,
  `submitter_id` int(11) NOT NULL DEFAULT '0',
  `submitted` tinyint(1) NOT NULL DEFAULT '0',
  `date_submitted` datetime DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grp_event_id` (`grp_event_id`,`submitter_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_template_types`
--

DROP TABLE IF EXISTS `event_template_types`;
CREATE TABLE IF NOT EXISTS `event_template_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL DEFAULT '',
  `table_name` varchar(50) NOT NULL DEFAULT '',
  `model_name` varchar(80) NOT NULL DEFAULT '',
  `display_for_selection` tinyint(1) NOT NULL DEFAULT '1',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`),
  UNIQUE KEY `table_name` (`table_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `event_template_types`
--

INSERT INTO `event_template_types` (`id`, `type_name`, `table_name`, `model_name`, `display_for_selection`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
(1, 'SIMPLE', 'simple_evaluations', 'SimpleEvaluation', 1, 'A', 0, '0000-00-00 00:00:00', NULL, NULL),
(2, 'RUBRIC', 'rubrics', 'Rubric', 1, 'A', 0, '0000-00-00 00:00:00', NULL, NULL),
(3, 'SURVEY', 'surveys', '', 0, 'A', 0, '0000-00-00 00:00:00', NULL, NULL),
(4, 'MIX EVALUATION', 'mixevals', 'Mixeval', 1, 'A', 0, '2006-04-03 11:51:02', 0, '2006-04-06 15:31:48');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `course_id` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `event_template_type_id` int(20) NOT NULL DEFAULT '0',
  `template_id` int(11) NOT NULL DEFAULT '2',
  `self_eval` varchar(11) NOT NULL DEFAULT '',
  `com_req` int(11) NOT NULL DEFAULT '0',
  `due_date` datetime DEFAULT NULL,
  `release_date_begin` datetime DEFAULT NULL,
  `release_date_end` datetime DEFAULT NULL,
  `result_release_date_begin` datetime DEFAULT NULL,
  `result_release_date_end` datetime DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_events`
--

DROP TABLE IF EXISTS `group_events`;
CREATE TABLE IF NOT EXISTS `group_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `marked` varchar(20) NOT NULL DEFAULT 'not reviewed',
  `grade` double(12,2) DEFAULT NULL,
  `grade_release_status` varchar(20) NOT NULL DEFAULT 'None',
  `comment_release_status` varchar(20) NOT NULL DEFAULT 'None',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`event_id`,`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_num` int(4) NOT NULL DEFAULT '0',
  `group_name` varchar(80) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `record_status` varchar(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups_members`
--

DROP TABLE IF EXISTS `groups_members`;
CREATE TABLE IF NOT EXISTS `groups_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mixevals`
--

DROP TABLE IF EXISTS `mixevals`;
CREATE TABLE IF NOT EXISTS `mixevals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `zero_mark` tinyint(1) NOT NULL DEFAULT '0',
  `total_question` int(11) NOT NULL DEFAULT '0',
  `lickert_question_max` int(11) NOT NULL DEFAULT '0',
  `scale_max` int(11) NOT NULL DEFAULT '0',
  `prefill_question_max` int(11) DEFAULT NULL,
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mixevals`
--

INSERT INTO `mixevals` (`id`, `name`, `zero_mark`, `total_question`, `lickert_question_max`, `scale_max`, `prefill_question_max`, `availability`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
(1, 'Default Mix Evalution', 0, 7, 3, 5, 3, 'public', 1, '2006-09-12 13:34:30', 1, '2006-09-12 13:47:57');

-- --------------------------------------------------------

--
-- Table structure for table `mixevals_question_descs`
--

DROP TABLE IF EXISTS `mixevals_question_descs`;
CREATE TABLE IF NOT EXISTS `mixevals_question_descs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL DEFAULT '0',
  `scale_level` int(11) NOT NULL DEFAULT '0',
  `descriptor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_num` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `mixevals_question_descs`
--

INSERT INTO `mixevals_question_descs` (`id`, `question_id`, `scale_level`, `descriptor`) VALUES
(1, 1, 1, 'Lowest'),
(2, 1, 2, NULL),
(3, 1, 3, 'Middle'),
(4, 1, 4, NULL),
(5, 1, 5, 'Highest'),
(6, 2, 1, 'Lowest'),
(7, 2, 2, NULL),
(8, 2, 3, 'Middle'),
(9, 2, 4, NULL),
(10, 2, 5, 'Highest'),
(11, 3, 1, 'Lowest'),
(12, 3, 2, NULL),
(13, 3, 3, 'Middle'),
(14, 3, 4, NULL),
(15, 3, 5, 'Highest');

-- --------------------------------------------------------

--
-- Table structure for table `mixevals_questions`
--

DROP TABLE IF EXISTS `mixevals_questions`;
CREATE TABLE IF NOT EXISTS `mixevals_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mixeval_id` int(11) NOT NULL DEFAULT '0',
  `question_num` int(11) NOT NULL DEFAULT '0',
  `title` text,
  `instructions` text,
  `question_type` char(1) DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '1',
  `multiplier` int(11) NOT NULL DEFAULT '0',
  `scale_level` int(11) NOT NULL DEFAULT '0',
  `response_type` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;


-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
-- This stores OAuth client credentials (AKA: consumer key and secret).
-- Client credentails are used to identify client software.
--
DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `comment` text DEFAULT '',
  `enabled` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_nonces`
-- Stores nonces to defeat replay attacks.
--
DROP TABLE IF EXISTS `oauth_nonces`;
CREATE TABLE IF NOT EXISTS `oauth_nonces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nonce` varchar(255) NOT NULL,
  `expires` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `oauth_tokens`
-- This stores OAuth token credentials (AKA: access token and secret).
-- Token credentials are used to identify a user.
--

DROP TABLE IF EXISTS `oauth_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `expires` date NOT NULL,
  `comment` text DEFAULT '',
  `enabled` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `penalties`
--

DROP TABLE IF EXISTS `penalties`;
CREATE TABLE IF NOT EXISTS `penalties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `days_late` int(11) NOT NULL,
  `percent_penalty` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `personalizes`
--

DROP TABLE IF EXISTS `personalizes`;
CREATE TABLE IF NOT EXISTS `personalizes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `attribute_code` varchar(80) DEFAULT NULL,
  `attribute_value` varchar(80) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`attribute_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prompt` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(1) DEFAULT NULL,
  `master` varchar(3) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

DROP TABLE IF EXISTS `responses`;
CREATE TABLE IF NOT EXISTS `responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL DEFAULT '0',
  `response` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created`, `modified`) VALUES
(1, 'superadmin', '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(2, 'admin', '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(3, 'instructor', '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(4, 'tutor', '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 'student', '2010-10-27 16:17:29', '2010-10-27 16:17:29');

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

DROP TABLE IF EXISTS `roles_users`;
CREATE TABLE IF NOT EXISTS `roles_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `roles_users`
--

INSERT INTO `roles_users` (`role_id`, `user_id`, `created`, `modified`) VALUES
(1, 1, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `rubrics`
--

DROP TABLE IF EXISTS `rubrics`;
CREATE TABLE IF NOT EXISTS `rubrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `zero_mark` tinyint(1) NOT NULL DEFAULT '0',
  `lom_max` int(11) DEFAULT NULL,
  `criteria` int(11) DEFAULT NULL,
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `template` varchar(20) NOT NULL DEFAULT 'horizontal',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rubrics_criteria_comments`
--

DROP TABLE IF EXISTS `rubrics_criteria_comments`;
CREATE TABLE IF NOT EXISTS `rubrics_criteria_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `criteria_id` int(11) NOT NULL,
  `rubrics_loms_id` int(11) NOT NULL,
  `criteria_comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `criteria_id` (`criteria_id`),
  KEY `rubrics_loms_id` (`rubrics_loms_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rubrics_criterias`
--

DROP TABLE IF EXISTS `rubrics_criterias`;
CREATE TABLE IF NOT EXISTS `rubrics_criterias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubric_id` int(11) NOT NULL DEFAULT '0',
  `criteria_num` int(11) NOT NULL DEFAULT '999',
  `criteria` varchar(255) DEFAULT NULL,
  `multiplier` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rubrics_loms`
--

DROP TABLE IF EXISTS `rubrics_loms`;
CREATE TABLE IF NOT EXISTS `rubrics_loms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rubric_id` int(11) NOT NULL DEFAULT '0',
  `lom_num` int(11) NOT NULL DEFAULT '999',
  `lom_comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `simple_evaluations`
--

DROP TABLE IF EXISTS `simple_evaluations`;
CREATE TABLE IF NOT EXISTS `simple_evaluations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `point_per_member` int(10) NOT NULL DEFAULT '0',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_group_members`
--

DROP TABLE IF EXISTS `survey_group_members`;
CREATE TABLE IF NOT EXISTS `survey_group_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_set_id` int(11) DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_group_sets`
--

DROP TABLE IF EXISTS `survey_group_sets`;
CREATE TABLE IF NOT EXISTS `survey_group_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL DEFAULT '0',
  `set_description` text NOT NULL,
  `num_groups` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `released` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_groups`
--

DROP TABLE IF EXISTS `survey_groups`;
CREATE TABLE IF NOT EXISTS `survey_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_set_id` int(11) NOT NULL DEFAULT '0',
  `group_number` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_inputs`
--

DROP TABLE IF EXISTS `survey_inputs`;
CREATE TABLE IF NOT EXISTS `survey_inputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL DEFAULT '0',
  `sub_id` int(11) DEFAULT NULL,
  `chkbx_id` int(11) DEFAULT NULL,
  `response_text` text,
  `response_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions`
--

DROP TABLE IF EXISTS `survey_questions`;
CREATE TABLE IF NOT EXISTS `survey_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL DEFAULT '0',
  `number` int(11) NOT NULL DEFAULT '9999',
  `question_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

DROP TABLE IF EXISTS `surveys`;
CREATE TABLE IF NOT EXISTS `surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `release_date_begin` datetime DEFAULT NULL,
  `release_date_end` datetime DEFAULT NULL,
  `released` tinyint(1) NOT NULL DEFAULT '0',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sys_parameters`
--

DROP TABLE IF EXISTS `sys_parameters`;
CREATE TABLE IF NOT EXISTS `sys_parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameter_code` varchar(80) NOT NULL DEFAULT '',
  `parameter_value` text,
  `parameter_type` char(1) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parameter_code` (`parameter_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `sys_parameters`
--

INSERT INTO `sys_parameters` (`id`, `parameter_code`, `parameter_value`, `parameter_type`, `description`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
(1, 'system.soft_delete_enable', 'true', 'B', 'Whether soft deletion of records is enabled.', 'A', 0, NOW(), 0, NOW()),
(2, 'system.debug_mode', '0', 'I', 'Debug Mode of the system', 'A', 0, NOW(), NULL, NOW()),
(3, 'system.debug_verbosity', '1', 'I', NULL, 'A', 0, NOW(), NULL, NOW()),
(6, 'system.super_admin', 'root', 'S', NULL, 'A', 0, NOW(), NULL, NOW()),
(7, 'system.upload_dir', 'uploads/', 'S', NULL, 'A', 0, NOW(), NULL, NOW()),
(9, 'display.page_title', 'iPeer v2 with TeamMaker', 'S', 'Page title show in HTML.', 'A', 0, NOW(), 0, NOW()),
(10, 'display.logo_file', 'LayoutLogoDefault.gif', 'S', 'Logo image name.', 'A', 0, NOW(), 0, NOW()),
(11, 'display.login_logo_file', 'LayoutLoginLogoDefault.gif', 'S', 'Login Image File Name.', 'A', 0, NOW(), 0, NOW()),
(13, 'custom.login_control', 'ipeer', 'S', 'The login control for iPeer: ipeer; CWL: UBC_CWL', 'A', 0, NOW(), NULL, NOW()),
(14, 'custom.login_page_pathname', 'custom_ubc_cwl_login', 'S', 'The file pathname for the custom login page; CWL:custom_ubc_cwl_login', 'A', 0, NOW(), NULL, NOW()),
(15, 'system.admin_email', 'Please enter the iPeer administrator\\''s email address.', 'S', NULL, 'A', 0, NOW(), NULL, NOW()),
(16, 'system.password_reset_mail', 'Dear <user>,<br> Your password has been reset to <newpassword>. Please use this to log in from now on. <br><br>iPeer Administrator', 'S', NULL, 'A', 0, NOW(), NULL, NOW()),
(17, 'system.password_reset_emailsubject', 'iPeer Password Reset Notification', 'S', NULL, 'A', 0, NOW(), NULL, NOW()),
(18, 'display.date_format', 'D, M j, Y g:i a', 'S', 'date format preference', 'A', 0, NOW(), NULL, NOW()),
(20, 'database.version', '4', 'I', 'database version', 'A', 0, NOW(), NULL, NOW()),
(21, 'email.port', '465', 'S', 'port number for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(22, 'email.host', 'email_host', 'S', 'host address for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(23 , 'email.username', 'your_email_address', 'S', 'username for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(24 , 'email.password', 'your_email_password', 'S', 'password for email smtp option', 'A', '0', NOW(), NULL , NOW());

-- --------------------------------------------------------

--
-- Table structure for table `user_courses`
--

DROP TABLE IF EXISTS `user_courses`;
CREATE TABLE IF NOT EXISTS `user_courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `course_id` int(11) NOT NULL DEFAULT '0',
  `access_right` varchar(1) NOT NULL DEFAULT 'O',
  `record_status` varchar(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE no_duplicates (`course_id`,`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_enrols`
--

DROP TABLE IF EXISTS `user_enrols`;
CREATE TABLE IF NOT EXISTS `user_enrols` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `record_status` varchar(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE no_duplicates (`course_id`,`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_faculties`
--

DROP TABLE IF EXISTS `user_faculties`;
CREATE TABLE `user_faculties` (
  `id` int NOT NULL auto_increment,
  `user_id` int NOT NULL,
  `faculty_id` int NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_tutors`
--

DROP TABLE IF EXISTS `user_tutors`;
CREATE TABLE IF NOT EXISTS `user_tutors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `course_id` int(11) NOT NULL DEFAULT '0',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE no_duplicates (`course_id`,`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


SET foreign_key_checks = 1;
