SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE  TABLE IF NOT EXISTS `acos` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `parent_id` INT(10) NULL DEFAULT NULL ,
  `model` VARCHAR(255) NULL DEFAULT NULL ,
  `foreign_key` INT(10) NULL DEFAULT NULL ,
  `alias` VARCHAR(255) NULL DEFAULT NULL ,
  `lft` INT(10) NULL DEFAULT NULL ,
  `rght` INT(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `parent_id` (`parent_id` ASC) ,
  INDEX `alias` (`alias` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
AUTO_INCREMENT=350
COLLATE = utf8_general_ci;

INSERT INTO `acos` VALUES (1,NULL,NULL,NULL,'adminpage',1,2),(2,NULL,NULL,NULL,'controllers',3,622),(3,2,NULL,NULL,'Pages',4,17),(4,3,NULL,NULL,'display',5,6),(5,3,NULL,NULL,'add',7,8),(6,3,NULL,NULL,'edit',9,10),(7,3,NULL,NULL,'index',11,12),(8,3,NULL,NULL,'view',13,14),(9,3,NULL,NULL,'delete',15,16),(10,2,NULL,NULL,'Courses',18,35),(11,10,NULL,NULL,'daysLate',19,20),(12,10,NULL,NULL,'index',21,22),(13,10,NULL,NULL,'ajaxList',23,24),(14,10,NULL,NULL,'view',25,26),(15,10,NULL,NULL,'home',27,28),(16,10,NULL,NULL,'add',29,30),(17,10,NULL,NULL,'edit',31,32),(18,10,NULL,NULL,'delete',33,34),(19,2,NULL,NULL,'Departments',36,47),(20,19,NULL,NULL,'index',37,38),(21,19,NULL,NULL,'view',39,40),(22,19,NULL,NULL,'add',41,42),(23,19,NULL,NULL,'edit',43,44),(24,19,NULL,NULL,'delete',45,46),(25,2,NULL,NULL,'Emailer',48,79),(26,25,NULL,NULL,'setUpAjaxList',49,50),(27,25,NULL,NULL,'ajaxList',51,52),(28,25,NULL,NULL,'index',53,54),(29,25,NULL,NULL,'write',55,56),(30,25,NULL,NULL,'cancel',57,58),(31,25,NULL,NULL,'view',59,60),(32,25,NULL,NULL,'addRecipient',61,62),(33,25,NULL,NULL,'deleteRecipient',63,64),(34,25,NULL,NULL,'getRecipient',65,66),(35,25,NULL,NULL,'searchByUserId',67,68),(36,25,NULL,NULL,'doMerge',69,70),(37,25,NULL,NULL,'send',71,72),(38,25,NULL,NULL,'add',73,74),(39,25,NULL,NULL,'edit',75,76),(40,25,NULL,NULL,'delete',77,78),(41,2,NULL,NULL,'Emailtemplates',80,99),(42,41,NULL,NULL,'setUpAjaxList',81,82),(43,41,NULL,NULL,'ajaxList',83,84),(44,41,NULL,NULL,'index',85,86),(45,41,NULL,NULL,'add',87,88),(46,41,NULL,NULL,'edit',89,90),(47,41,NULL,NULL,'delete',91,92),(48,41,NULL,NULL,'view',93,94),(49,41,NULL,NULL,'displayTemplateContent',95,96),(50,41,NULL,NULL,'displayTemplateSubject',97,98),(51,2,NULL,NULL,'Evaltools',100,113),(52,51,NULL,NULL,'index',101,102),(53,51,NULL,NULL,'showAll',103,104),(54,51,NULL,NULL,'add',105,106),(55,51,NULL,NULL,'edit',107,108),(56,51,NULL,NULL,'view',109,110),(57,51,NULL,NULL,'delete',111,112),(58,2,NULL,NULL,'Evaluations',114,173),(59,58,NULL,NULL,'postProcess',115,116),(60,58,NULL,NULL,'setUpAjaxList',117,118),(61,58,NULL,NULL,'ajaxList',119,120),(62,58,NULL,NULL,'view',121,122),(63,58,NULL,NULL,'index',123,124),(64,58,NULL,NULL,'update',125,126),(65,58,NULL,NULL,'export',127,128),(66,58,NULL,NULL,'makeEvaluation',129,130),(67,58,NULL,NULL,'validSurveyEvalComplete',131,132),(68,58,NULL,NULL,'validRubricEvalComplete',133,134),(69,58,NULL,NULL,'completeEvaluationRubric',135,136),(70,58,NULL,NULL,'validMixevalEvalComplete',137,138),(71,58,NULL,NULL,'completeEvaluationMixeval',139,140),(72,58,NULL,NULL,'viewEvaluationResults',141,142),(73,58,NULL,NULL,'viewSurveyGroupEvaluationResults',143,144),(74,58,NULL,NULL,'studentViewEvaluationResult',145,146),(75,58,NULL,NULL,'markEventReviewed',147,148),(76,58,NULL,NULL,'markGradeRelease',149,150),(77,58,NULL,NULL,'markCommentRelease',151,152),(78,58,NULL,NULL,'changeAllCommentRelease',153,154),(79,58,NULL,NULL,'changeAllGradeRelease',155,156),(80,58,NULL,NULL,'viewGroupSubmissionDetails',157,158),(81,58,NULL,NULL,'viewSurveySummary',159,160),(82,58,NULL,NULL,'export_rubic',161,162),(83,58,NULL,NULL,'export_test',163,164),(84,58,NULL,NULL,'pre',165,166),(85,58,NULL,NULL,'add',167,168),(86,58,NULL,NULL,'edit',169,170),(87,58,NULL,NULL,'delete',171,172),(88,2,NULL,NULL,'Events',174,201),(89,88,NULL,NULL,'postProcessData',175,176),(90,88,NULL,NULL,'setUpAjaxList',177,178),(91,88,NULL,NULL,'index',179,180),(92,88,NULL,NULL,'ajaxList',181,182),(93,88,NULL,NULL,'view',183,184),(94,88,NULL,NULL,'eventTemplatesList',185,186),(95,88,NULL,NULL,'add',187,188),(96,88,NULL,NULL,'edit',189,190),(97,88,NULL,NULL,'delete',191,192),(98,88,NULL,NULL,'checkDuplicateTitle',193,194),(99,88,NULL,NULL,'viewGroups',195,196),(100,88,NULL,NULL,'editGroup',197,198),(101,88,NULL,NULL,'getAssignedGroups',199,200),(102,2,NULL,NULL,'Faculties',202,213),(103,102,NULL,NULL,'index',203,204),(104,102,NULL,NULL,'view',205,206),(105,102,NULL,NULL,'add',207,208),(106,102,NULL,NULL,'edit',209,210),(107,102,NULL,NULL,'delete',211,212),(108,2,NULL,NULL,'Framework',214,231),(109,108,NULL,NULL,'calendarDisplay',215,216),(110,108,NULL,NULL,'userInfoDisplay',217,218),(111,108,NULL,NULL,'tutIndex',219,220),(112,108,NULL,NULL,'add',221,222),(113,108,NULL,NULL,'edit',223,224),(114,108,NULL,NULL,'index',225,226),(115,108,NULL,NULL,'view',227,228),(116,108,NULL,NULL,'delete',229,230),(117,2,NULL,NULL,'Groups',232,259),(118,117,NULL,NULL,'postProcess',233,234),(119,117,NULL,NULL,'setUpAjaxList',235,236),(120,117,NULL,NULL,'index',237,238),(121,117,NULL,NULL,'ajaxList',239,240),(122,117,NULL,NULL,'goToClassList',241,242),(123,117,NULL,NULL,'view',243,244),(124,117,NULL,NULL,'add',245,246),(125,117,NULL,NULL,'edit',247,248),(126,117,NULL,NULL,'delete',249,250),(127,117,NULL,NULL,'import',251,252),(128,117,NULL,NULL,'addGroupByImport',253,254),(129,117,NULL,NULL,'export',255,256),(130,117,NULL,NULL,'sendGroupEmail',257,258),(131,2,NULL,NULL,'Home',260,271),(132,131,NULL,NULL,'index',261,262),(133,131,NULL,NULL,'add',263,264),(134,131,NULL,NULL,'edit',265,266),(135,131,NULL,NULL,'view',267,268),(136,131,NULL,NULL,'delete',269,270),(137,2,NULL,NULL,'Install',272,293),(138,137,NULL,NULL,'index',273,274),(139,137,NULL,NULL,'install2',275,276),(140,137,NULL,NULL,'install3',277,278),(141,137,NULL,NULL,'install4',279,280),(142,137,NULL,NULL,'install5',281,282),(143,137,NULL,NULL,'gpl',283,284),(144,137,NULL,NULL,'add',285,286),(145,137,NULL,NULL,'edit',287,288),(146,137,NULL,NULL,'view',289,290),(147,137,NULL,NULL,'delete',291,292),(148,2,NULL,NULL,'Lti',294,305),(149,148,NULL,NULL,'index',295,296),(150,148,NULL,NULL,'add',297,298),(151,148,NULL,NULL,'edit',299,300),(152,148,NULL,NULL,'view',301,302),(153,148,NULL,NULL,'delete',303,304),(154,2,NULL,NULL,'Mixevals',306,335),(155,154,NULL,NULL,'postProcess',307,308),(156,154,NULL,NULL,'setUpAjaxList',309,310),(157,154,NULL,NULL,'index',311,312),(158,154,NULL,NULL,'ajaxList',313,314),(159,154,NULL,NULL,'view',315,316),(160,154,NULL,NULL,'add',317,318),(161,154,NULL,NULL,'deleteQuestion',319,320),(162,154,NULL,NULL,'deleteDescriptor',321,322),(163,154,NULL,NULL,'edit',323,324),(164,154,NULL,NULL,'copy',325,326),(165,154,NULL,NULL,'delete',327,328),(166,154,NULL,NULL,'previewMixeval',329,330),(167,154,NULL,NULL,'renderRows',331,332),(168,154,NULL,NULL,'update',333,334),(169,2,NULL,NULL,'Oauthclients',336,347),(170,169,NULL,NULL,'index',337,338),(171,169,NULL,NULL,'add',339,340),(172,169,NULL,NULL,'edit',341,342),(173,169,NULL,NULL,'delete',343,344),(174,169,NULL,NULL,'view',345,346),(175,2,NULL,NULL,'Oauthtokens',348,359),(176,175,NULL,NULL,'index',349,350),(177,175,NULL,NULL,'add',351,352),(178,175,NULL,NULL,'edit',353,354),(179,175,NULL,NULL,'delete',355,356),(180,175,NULL,NULL,'view',357,358),(181,2,NULL,NULL,'Penalty',360,373),(182,181,NULL,NULL,'save',361,362),(183,181,NULL,NULL,'add',363,364),(184,181,NULL,NULL,'edit',365,366),(185,181,NULL,NULL,'index',367,368),(186,181,NULL,NULL,'view',369,370),(187,181,NULL,NULL,'delete',371,372),(188,2,NULL,NULL,'Rubrics',374,395),(189,188,NULL,NULL,'postProcess',375,376),(190,188,NULL,NULL,'setUpAjaxList',377,378),(191,188,NULL,NULL,'index',379,380),(192,188,NULL,NULL,'ajaxList',381,382),(193,188,NULL,NULL,'view',383,384),(194,188,NULL,NULL,'add',385,386),(195,188,NULL,NULL,'edit',387,388),(196,188,NULL,NULL,'copy',389,390),(197,188,NULL,NULL,'delete',391,392),(198,188,NULL,NULL,'setForm_RubricName',393,394),(199,2,NULL,NULL,'Searchs',396,423),(200,199,NULL,NULL,'update',397,398),(201,199,NULL,NULL,'index',399,400),(202,199,NULL,NULL,'searchEvaluation',401,402),(203,199,NULL,NULL,'searchResult',403,404),(204,199,NULL,NULL,'searchInstructor',405,406),(205,199,NULL,NULL,'eventBoxSearch',407,408),(206,199,NULL,NULL,'formatSearchEvaluation',409,410),(207,199,NULL,NULL,'formatSearchInstructor',411,412),(208,199,NULL,NULL,'formatSearchEvaluationResult',413,414),(209,199,NULL,NULL,'add',415,416),(210,199,NULL,NULL,'edit',417,418),(211,199,NULL,NULL,'view',419,420),(212,199,NULL,NULL,'delete',421,422),(213,2,NULL,NULL,'Simpleevaluations',424,443),(214,213,NULL,NULL,'postProcess',425,426),(215,213,NULL,NULL,'setUpAjaxList',427,428),(216,213,NULL,NULL,'index',429,430),(217,213,NULL,NULL,'ajaxList',431,432),(218,213,NULL,NULL,'view',433,434),(219,213,NULL,NULL,'add',435,436),(220,213,NULL,NULL,'edit',437,438),(221,213,NULL,NULL,'copy',439,440),(222,213,NULL,NULL,'delete',441,442),(223,2,NULL,NULL,'Surveygroups',444,475),(224,223,NULL,NULL,'postProcess',445,446),(225,223,NULL,NULL,'setUpAjaxList',447,448),(226,223,NULL,NULL,'index',449,450),(227,223,NULL,NULL,'ajaxList',451,452),(228,223,NULL,NULL,'viewresult',453,454),(229,223,NULL,NULL,'makegroups',455,456),(230,223,NULL,NULL,'makegroupssearch',457,458),(231,223,NULL,NULL,'maketmgroups',459,460),(232,223,NULL,NULL,'savegroups',461,462),(233,223,NULL,NULL,'release',463,464),(234,223,NULL,NULL,'delete',465,466),(235,223,NULL,NULL,'edit',467,468),(236,223,NULL,NULL,'changegroupset',469,470),(237,223,NULL,NULL,'add',471,472),(238,223,NULL,NULL,'view',473,474),(239,2,NULL,NULL,'Surveys',476,509),(240,239,NULL,NULL,'setUpAjaxList',477,478),(241,239,NULL,NULL,'index',479,480),(242,239,NULL,NULL,'ajaxList',481,482),(243,239,NULL,NULL,'view',483,484),(244,239,NULL,NULL,'add',485,486),(245,239,NULL,NULL,'edit',487,488),(246,239,NULL,NULL,'copy',489,490),(247,239,NULL,NULL,'delete',491,492),(248,239,NULL,NULL,'checkDuplicateName',493,494),(249,239,NULL,NULL,'releaseSurvey',495,496),(250,239,NULL,NULL,'questionsSummary',497,498),(251,239,NULL,NULL,'moveQuestion',499,500),(252,239,NULL,NULL,'removeQuestion',501,502),(253,239,NULL,NULL,'addQuestion',503,504),(254,239,NULL,NULL,'editQuestion',505,506),(255,239,NULL,NULL,'update',507,508),(256,2,NULL,NULL,'Sysparameters',510,525),(257,256,NULL,NULL,'setUpAjaxList',511,512),(258,256,NULL,NULL,'index',513,514),(259,256,NULL,NULL,'ajaxList',515,516),(260,256,NULL,NULL,'view',517,518),(261,256,NULL,NULL,'add',519,520),(262,256,NULL,NULL,'edit',521,522),(263,256,NULL,NULL,'delete',523,524),(264,2,NULL,NULL,'Upgrade',526,539),(265,264,NULL,NULL,'index',527,528),(266,264,NULL,NULL,'step2',529,530),(267,264,NULL,NULL,'add',531,532),(268,264,NULL,NULL,'edit',533,534),(269,264,NULL,NULL,'view',535,536),(270,264,NULL,NULL,'delete',537,538),(271,2,NULL,NULL,'Users',540,567),(272,271,NULL,NULL,'ajaxList',541,542),(273,271,NULL,NULL,'index',543,544),(274,271,NULL,NULL,'goToClassList',545,546),(275,271,NULL,NULL,'determineIfStudentFromThisData',547,548),(276,271,NULL,NULL,'view',549,550),(277,271,NULL,NULL,'add',551,552),(278,271,NULL,NULL,'edit',553,554),(279,271,NULL,NULL,'editProfile',555,556),(280,271,NULL,NULL,'delete',557,558),(281,271,NULL,NULL,'checkDuplicateName',559,560),(282,271,NULL,NULL,'resetPassword',561,562),(283,271,NULL,NULL,'import',563,564),(284,271,NULL,NULL,'update',565,566),(285,2,NULL,NULL,'V1',568,603),(286,285,NULL,NULL,'oauth',569,570),(287,285,NULL,NULL,'oauth_error',571,572),(288,285,NULL,NULL,'users',573,574),(289,285,NULL,NULL,'courses',575,576),(290,285,NULL,NULL,'groups',577,578),(291,285,NULL,NULL,'groupMembers',579,580),(292,285,NULL,NULL,'events',581,582),(293,285,NULL,NULL,'grades',583,584),(294,285,NULL,NULL,'departments',585,586),(295,285,NULL,NULL,'courseDepartments',587,588),(296,285,NULL,NULL,'userEvents',589,590),(297,285,NULL,NULL,'enrolment',591,592),(298,285,NULL,NULL,'add',593,594),(299,285,NULL,NULL,'edit',595,596),(300,285,NULL,NULL,'index',597,598),(301,285,NULL,NULL,'view',599,600),(302,285,NULL,NULL,'delete',601,602),(303,2,NULL,NULL,'Guard',604,621),(304,303,NULL,NULL,'Guard',605,620),(305,304,NULL,NULL,'login',606,607),(306,304,NULL,NULL,'logout',608,609),(307,304,NULL,NULL,'add',610,611),(308,304,NULL,NULL,'edit',612,613),(309,304,NULL,NULL,'index',614,615),(310,304,NULL,NULL,'view',616,617),(311,304,NULL,NULL,'delete',618,619),(312,NULL,NULL,NULL,'functions',623,686),(313,312,NULL,NULL,'user',624,651),(314,313,NULL,NULL,'superadmin',625,626),(315,313,NULL,NULL,'admin',627,628),(316,313,NULL,NULL,'instructor',629,630),(317,313,NULL,NULL,'tutor',631,632),(318,313,NULL,NULL,'student',633,634),(319,313,NULL,NULL,'import',635,636),(320,313,NULL,NULL,'password_reset',637,648),(321,320,NULL,NULL,'superadmin',638,639),(322,320,NULL,NULL,'admin',640,641),(323,320,NULL,NULL,'instructor',642,643),(324,320,NULL,NULL,'tutor',644,645),(325,320,NULL,NULL,'student',646,647),(326,313,NULL,NULL,'index',649,650),(327,312,NULL,NULL,'role',652,663),(328,327,NULL,NULL,'superadmin',653,654),(329,327,NULL,NULL,'admin',655,656),(330,327,NULL,NULL,'instructor',657,658),(331,327,NULL,NULL,'tutor',659,660),(332,327,NULL,NULL,'student',661,662),(333,312,NULL,NULL,'evaluation',664,667),(334,333,NULL,NULL,'export',665,666),(335,312,NULL,NULL,'email',668,675),(336,335,NULL,NULL,'allUsers',669,670),(337,335,NULL,NULL,'allGroups',671,672),(338,335,NULL,NULL,'allCourses',673,674),(339,312,NULL,NULL,'emailtemplate',676,677),(340,312,NULL,NULL,'viewstudentresults',678,679),(341,312,NULL,NULL,'viewemailaddresses',680,681),(342,312,NULL,NULL,'superadmin',682,683),(343,312,NULL,NULL,'coursemanager',684,685);

CREATE  TABLE IF NOT EXISTS `aros` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `parent_id` INT(10) NULL DEFAULT NULL ,
  `model` VARCHAR(255) NULL DEFAULT NULL ,
  `foreign_key` INT(10) NULL DEFAULT NULL ,
  `alias` VARCHAR(255) NULL DEFAULT NULL ,
  `lft` INT(10) NULL DEFAULT NULL ,
  `rght` INT(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
AUTO_INCREMENT=5
COLLATE = utf8_general_ci;

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Role', 1, NULL, 1, 2),
(2, NULL, 'Role', 2, NULL, 3, 4),
(3, NULL, 'Role', 3, NULL, 5, 6),
(4, NULL, 'Role', 4, NULL, 7, 8),
(5, NULL, 'Role', 5, NULL, 9, 10);

CREATE  TABLE IF NOT EXISTS `aros_acos` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `aro_id` INT(10) NOT NULL ,
  `aco_id` INT(10) NOT NULL ,
  `_create` VARCHAR(2) NOT NULL DEFAULT '0' ,
  `_read` VARCHAR(2) NOT NULL DEFAULT '0' ,
  `_update` VARCHAR(2) NOT NULL DEFAULT '0' ,
  `_delete` VARCHAR(2) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `ARO_ACO_KEY` (`aro_id` ASC, `aco_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
AUTO_INCREMENT=60
COLLATE = utf8_general_ci;

INSERT INTO `aros_acos` VALUES (1,1,2,'1','1','1','1'),(2,1,312,'1','1','1','1'),(3,1,51,'1','1','1','1'),(4,1,213,'1','1','1','1'),(5,1,188,'1','1','1','1'),(6,1,154,'1','1','1','1'),(7,1,239,'1','1','1','1'),(8,1,223,'1','1','1','1'),(9,1,25,'1','1','1','1'),(10,1,41,'1','1','1','1'),(11,1,88,'1','1','1','1'),(12,1,102,'1','1','1','1'),(13,1,19,'1','1','1','1'),(14,1,117,'1','1','1','1'),(15,1,1,'1','1','1','1'),(16,1,341,'1','1','1','1'),(17,1,342,'1','1','1','1'),(18,2,2,'-1','-1','-1','-1'),(19,2,131,'1','1','1','1'),(20,2,10,'1','1','1','1'),(21,2,19,'1','1','1','1'),(22,2,25,'1','1','1','1'),(23,2,41,'1','1','1','1'),(24,2,51,'1','1','1','1'),(25,2,58,'1','1','1','1'),(26,2,88,'1','1','1','1'),(27,2,117,'1','1','1','1'),(28,2,154,'1','1','1','1'),(29,2,188,'1','1','1','1'),(30,2,213,'1','1','1','1'),(31,2,239,'1','1','1','1'),(32,2,223,'1','1','1','1'),(33,2,271,'1','1','1','1'),(34,2,306,'1','1','1','1'),(35,2,171,'1','1','1','1'),(36,2,173,'1','1','1','1'),(37,2,177,'1','1','1','1'),(38,2,179,'1','1','1','1'),(39,2,312,'-1','-1','-1','-1'),(40,2,339,'1','1','1','1'),(41,2,333,'1','1','1','1'),(42,2,336,'1','1','1','1'),(43,2,313,'1','1','1','1'),(44,2,315,'-1','1','-1','-1'),(45,2,314,'-1','-1','-1','-1'),(46,2,1,'1','1','1','1'),(47,2,341,'1','1','1','1'),(48,2,343,'1','1','1','1'),(49,2,342,'-1','-1','-1','-1'),(50,3,2,'-1','-1','-1','-1'),(51,3,131,'1','1','1','1'),(52,3,10,'1','1','1','1'),(53,3,16,'-1','-1','-1','-1'),(54,3,17,'-1','-1','-1','-1'),(55,3,25,'1','1','1','1'),(56,3,41,'1','1','1','1'),(57,3,51,'1','1','1','1'),(58,3,58,'1','1','1','1'),(59,3,88,'1','1','1','1'),(60,3,117,'1','1','1','1'),(61,3,154,'1','1','1','1'),(62,3,188,'1','1','1','1'),(63,3,213,'1','1','1','1'),(64,3,239,'1','1','1','1'),(65,3,223,'1','1','1','1'),(66,3,271,'1','1','1','1'),(67,3,306,'1','1','1','1'),(68,3,171,'1','1','1','1'),(69,3,173,'1','1','1','1'),(70,3,177,'1','1','1','1'),(71,3,179,'1','1','1','1'),(72,3,312,'-1','-1','-1','-1'),(73,3,333,'1','1','-1','-1'),(74,3,313,'1','1','1','1'),(75,3,315,'-1','-1','-1','-1'),(76,3,314,'-1','-1','-1','-1'),(77,3,316,'-1','1','-1','-1'),(78,3,326,'-1','-1','-1','-1'),(79,3,341,'-1','-1','-1','-1'),(80,3,342,'-1','-1','-1','-1'),(81,3,343,'1','1','1','1'),(82,4,2,'-1','-1','-1','-1'),(83,4,131,'1','1','1','1'),(84,4,10,'-1','-1','-1','-1'),(85,4,25,'-1','-1','-1','-1'),(86,4,41,'-1','-1','-1','-1'),(87,4,51,'-1','-1','-1','-1'),(88,4,88,'-1','-1','-1','-1'),(89,4,117,'-1','-1','-1','-1'),(90,4,154,'-1','-1','-1','-1'),(91,4,188,'-1','-1','-1','-1'),(92,4,213,'-1','-1','-1','-1'),(93,4,239,'-1','-1','-1','-1'),(94,4,223,'-1','-1','-1','-1'),(95,4,271,'-1','-1','-1','-1'),(96,4,306,'1','1','1','1'),(97,4,66,'1','1','1','1'),(98,4,74,'1','1','1','1'),(99,4,69,'1','1','1','1'),(100,4,71,'1','1','1','1'),(101,4,279,'1','1','1','1'),(102,4,171,'1','1','1','1'),(103,4,173,'1','1','1','1'),(104,4,177,'1','1','1','1'),(105,4,179,'1','1','1','1'),(106,4,312,'-1','-1','-1','-1'),(107,4,341,'-1','-1','-1','-1'),(108,4,342,'-1','-1','-1','-1'),(109,5,2,'-1','-1','-1','-1'),(110,5,131,'1','1','1','1'),(111,5,10,'-1','-1','-1','-1'),(112,5,25,'-1','-1','-1','-1'),(113,5,41,'-1','-1','-1','-1'),(114,5,51,'-1','-1','-1','-1'),(115,5,88,'-1','-1','-1','-1'),(116,5,117,'-1','-1','-1','-1'),(117,5,154,'-1','-1','-1','-1'),(118,5,188,'-1','-1','-1','-1'),(119,5,213,'-1','-1','-1','-1'),(120,5,239,'-1','-1','-1','-1'),(121,5,223,'-1','-1','-1','-1'),(122,5,271,'-1','-1','-1','-1'),(123,5,306,'1','1','1','1'),(124,5,66,'1','1','1','1'),(125,5,74,'1','1','1','1'),(126,5,69,'1','1','1','1'),(127,5,71,'1','1','1','1'),(128,5,279,'1','1','1','1'),(129,5,171,'1','1','1','1'),(130,5,173,'1','1','1','1'),(131,5,177,'1','1','1','1'),(132,5,179,'1','1','1','1'),(133,5,312,'-1','-1','-1','-1'),(134,5,340,'1','1','1','1'),(135,5,341,'-1','-1','-1','-1'),(136,5,342,'-1','-1','-1','-1');

CREATE  TABLE IF NOT EXISTS `course_departments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `course_id` INT(11) NOT NULL ,
  `department_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `course_id` (`course_id` ASC) ,
  INDEX `department_id` (`department_id` ASC) ,
  CONSTRAINT `course_departments_ibfk_1`
    FOREIGN KEY (`course_id` )
    REFERENCES `courses` (`id` )
    ON DELETE CASCADE,
  CONSTRAINT `course_departments_ibfk_2`
    FOREIGN KEY (`department_id` )
    REFERENCES `departments` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

INSERT INTO `course_departments` (course_id, department_id) (SELECT id, 1 FROM `courses`);

ALTER TABLE `courses` CHARACTER SET = utf8 
, CHANGE COLUMN `self_enroll` `self_enroll` VARCHAR(3) NULL DEFAULT 'off'  
, CHANGE COLUMN `record_status` `record_status` VARCHAR(1) NOT NULL DEFAULT 'A'
, CHANGE COLUMN `created` `created` DATETIME NULL DEFAULT NULL
, CHANGE COLUMN `modified` `modified` DATETIME NULL DEFAULT NULL;

CREATE  TABLE IF NOT EXISTS `faculties` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(80) NOT NULL ,
  `creator_id` INT(11) NOT NULL DEFAULT '0' ,
  `created` DATETIME NULL DEFAULT NULL,
  `updater_id` INT(11) NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
INSERT INTO faculties VALUES (NULL, 'default', 1, NOW(), 1, NOW());

CREATE  TABLE IF NOT EXISTS `departments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(80) NOT NULL ,
  `faculty_id` INT(11) NOT NULL ,
  `creator_id` INT(11) NOT NULL DEFAULT '0' ,
  `created` DATETIME NULL DEFAULT NULL ,
  `updater_id` INT(11) NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) ,
  INDEX `faculty_id` (`faculty_id` ASC) ,
  CONSTRAINT `departments_ibfk_1`
    FOREIGN KEY (`faculty_id` )
    REFERENCES `faculties` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
INSERT INTO departments VALUES (NULL, 'default', 1, 1, NOW(), 1, NOW());

CREATE  TABLE IF NOT EXISTS `email_merges` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `key` VARCHAR(80) NOT NULL ,
  `value` VARCHAR(80) NOT NULL ,
  `table_name` VARCHAR(80) NOT NULL ,
  `field_name` VARCHAR(80) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
AUTO_INCREMENT=5
COLLATE = utf8_general_ci;

INSERT INTO `email_merges` (`id`, `key`, `value`, `table_name`, `field_name`, `created`, `modified`) VALUES
(1, 'Username', '{{{USERNAME}}}', 'User', 'username', NOW(), NOW()),
(2, 'First Name', '{{{FIRSTNAME}}}', 'User', 'first_name', NOW(), NOW()),
(3, 'Last Name', '{{{LASTNAME}}}', 'User', 'last_name', NOW(), NOW()),
(4, 'Email Address', '{{{Email}}}', 'User', 'email', NOW(), NOW());

CREATE  TABLE IF NOT EXISTS `email_schedules` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `subject` VARCHAR(80) NOT NULL ,
  `content` TEXT NOT NULL ,
  `date` DATETIME NULL DEFAULT NULL,
  `from` VARCHAR(80) NOT NULL ,
  `to` VARCHAR(600) NOT NULL ,
  `course_id` INT(11) NULL DEFAULT NULL ,
  `event_id` INT(11) NULL DEFAULT NULL ,
  `grp_id` INT(11) NULL DEFAULT NULL ,
  `sent` TINYINT(1) NOT NULL DEFAULT '0' ,
  `creator_id` INT(11) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `email_templates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(80) NOT NULL ,
  `description` TEXT NOT NULL ,
  `subject` VARCHAR(80) NOT NULL ,
  `content` TEXT NOT NULL ,
  `availability` TINYINT(4) NOT NULL ,
  `creator_id` INT(11) NOT NULL DEFAULT '0' ,
  `created` DATETIME NULL DEFAULT NULL,
  `updater_id` INT(11) NULL DEFAULT NULL ,
  `updated` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

INSERT INTO `email_templates` (`id`, `name`, `description`, `subject`, `content`, `availability`, `creator_id`, `created`, `updater_id`, `updated`) VALUES
(1, 'Submission Confirmation', 'template for submission confirmation', 'iPeer: Evaluation Submission Confirmation', 'Hi {{{FIRSTNAME}}}, \nYour evaluation has been submitted successfully. Thank you for your feedback!\n\n iPeer',1, 1, NOW(), NULL, NULL);

ALTER TABLE  `evaluation_mixeval_details` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL ;
ALTER TABLE  `evaluation_mixeval_details` CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `evaluation_mixeval_details` CHARACTER SET = utf8 ;

ALTER TABLE  `evaluation_mixevals` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL ;
ALTER TABLE  `evaluation_mixevals` CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `evaluation_mixevals` CHARACTER SET = utf8 ;

ALTER TABLE  `evaluation_rubric_details` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL ;
ALTER TABLE  `evaluation_rubric_details` CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `evaluation_rubric_details` CHARACTER SET = utf8 ;

ALTER TABLE  `evaluation_rubrics` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL ;
ALTER TABLE  `evaluation_rubrics` CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `evaluation_rubrics` CHARACTER SET = utf8 ;

ALTER TABLE  `evaluation_simples` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL, CHANGE  `date_submitted`  `date_submitted` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `evaluation_simples` CHARACTER SET = utf8 ;

ALTER TABLE  `evaluation_submissions` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `evaluation_submissions` CHARACTER SET = utf8 ;

UPDATE `event_template_types` SET `created`=NOW(), `modified` = NOW() WHERE 1;
ALTER TABLE  `event_template_types` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `event_template_types` CHARACTER SET = utf8 ;

ALTER TABLE  `events` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `events` CHARACTER SET = utf8 , ADD COLUMN `result_release_date_begin` DATETIME NULL DEFAULT NULL  AFTER `release_date_end` , ADD COLUMN `result_release_date_end` DATETIME NULL DEFAULT NULL  AFTER `result_release_date_begin` ;


ALTER TABLE  `group_events` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `group_events` CHARACTER SET = utf8 , CHANGE COLUMN `marked` `marked` VARCHAR(20) NOT NULL DEFAULT 'not reviewed'  ;

ALTER TABLE  `groups` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `groups` CHARACTER SET = utf8 , CHANGE COLUMN `record_status` `record_status` VARCHAR(1) NOT NULL DEFAULT 'A'  ;

ALTER TABLE `groups_members` CHARACTER SET = utf8 
, ADD INDEX `group_id` (`group_id` ASC) ;

ALTER TABLE `mixevals` CHARACTER SET = utf8 
, CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL
, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL
, DROP COLUMN `total_marks` 
, CHANGE COLUMN `zero_mark` `zero_mark` TINYINT(1) NOT NULL DEFAULT '0'  
, CHANGE COLUMN `availability` `availability` VARCHAR(10) NOT NULL DEFAULT 'public'  ;
UPDATE mixevals SET zero_mark=0 where zero_mark=2;

-- change mixeval question description question number to question_id
UPDATE mixevals_question_descs as qd, mixevals_questions as q set qd.question_num = q.id where qd.mixeval_id = q.mixeval_id AND qd.question_num = q.question_num;
ALTER TABLE `mixevals_question_descs` CHARACTER SET = utf8 
, DROP COLUMN `mixeval_id` 
, CHANGE `question_num` `question_id` INT( 11 ) NOT NULL DEFAULT '0'
, ADD INDEX `question_id` (`question_id` ASC)
, ADD FOREIGN KEY ( `question_id` ) REFERENCES `mixevals_questions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;

ALTER TABLE `mixevals_questions` CHARACTER SET = utf8 , CHANGE COLUMN `required` `required` TINYINT(1) NOT NULL DEFAULT '1'  ;

CREATE  TABLE IF NOT EXISTS `oauth_clients` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `key` VARCHAR(255) NOT NULL ,
  `secret` VARCHAR(255) NOT NULL ,
  `comment` TEXT NULL DEFAULT NULL ,
  `enabled` TINYINT(1) NULL DEFAULT '1' ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `oauth_clients_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `oauth_nonces` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `nonce` VARCHAR(255) NOT NULL ,
  `expires` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `oauth_tokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `key` VARCHAR(255) NOT NULL ,
  `secret` VARCHAR(255) NOT NULL ,
  `expires` DATE NOT NULL ,
  `comment` TEXT NULL DEFAULT NULL ,
  `enabled` TINYINT(1) NULL DEFAULT '1' ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `oauth_tokens_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `penalties` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `event_id` INT(11) NULL DEFAULT NULL ,
  `days_late` INT(11) NOT NULL ,
  `percent_penalty` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `event_id` (`event_id` ASC) ,
  CONSTRAINT `penalties_ibfk_1`
    FOREIGN KEY (`event_id` )
    REFERENCES `events` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE  `personalizes` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `updated`  `updated` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `personalizes` CHARACTER SET = utf8 ;

ALTER TABLE `questions` CHARACTER SET = utf8 , ENGINE = InnoDB , CHANGE COLUMN `type` `type` VARCHAR(1) NULL DEFAULT NULL  , CHANGE COLUMN `master` `master` VARCHAR(3) NOT NULL DEFAULT 'yes'  ;

ALTER TABLE `responses` CHARACTER SET = utf8 ;

CREATE  TABLE IF NOT EXISTS `roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
AUTO_INCREMENT=5
COLLATE = utf8_general_ci;

INSERT INTO `roles` (`id`, `name`, `created`, `modified`) VALUES
(1, 'superadmin', '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(2, 'admin', '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(3, 'instructor', '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(4, 'tutor', '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 'student', '2010-10-27 16:17:29', '2010-10-27 16:17:29');

CREATE  TABLE IF NOT EXISTS `roles_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `role_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
AUTO_INCREMENT=1
COLLATE = utf8_general_ci;

INSERT INTO `roles_users` (`role_id`, `user_id`, `created`, `modified`) VALUES
(1, 1, NOW(), NOW());

ALTER TABLE  `rubrics` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `rubrics` CHARACTER SET = utf8 
, DROP COLUMN `total_marks` 
, CHANGE COLUMN `zero_mark` `zero_mark` TINYINT(1) NOT NULL DEFAULT '0'  
, CHANGE COLUMN `availability` `availability` VARCHAR(10) NOT NULL DEFAULT 'public'  
, CHANGE COLUMN `template` `template` VARCHAR(20) NOT NULL DEFAULT 'horizontal'  ;
UPDATE rubrics SET zero_mark=0 where zero_mark=2;

ALTER TABLE `rubrics_criteria_comments` CHARACTER SET = utf8 
, ADD COLUMN `criteria_id` INT(11) NULL  AFTER `id` 
, ADD COLUMN `rubrics_loms_id` INT(11) NOT NULL  AFTER `criteria_id` 
, ADD INDEX `criteria_id` (`criteria_id` ASC) 
, ADD INDEX `rubrics_loms_id` (`rubrics_loms_id` ASC)
, ADD FOREIGN KEY ( `rubrics_loms_id` ) REFERENCES  `rubrics_loms` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
, ADD FOREIGN KEY ( `criteria_id` ) REFERENCES `rubrics_criterias` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;

-- change rubrics_criteria_comments to associate with rubrics_criterias by criteria id
UPDATE rubrics_criteria_comments as rcc set criteria_id = (SELECT id from rubrics_criterias as rc WHERE rc.rubric_id = rcc.rubric_id AND rc.criteria_num = rcc.criteria_num);

-- change Criteria comments lom_num to LOM ids
UPDATE rubrics_criteria_comments as rcc, rubrics_criterias as rc, rubrics as r, rubrics_loms as rl set rcc.lom_num=rl.id where rcc.criteria_id = rc.id AND rc.rubric_id = r.id  AND rl.rubric_id = r.id AND rl.lom_num = rcc.lom_num;

ALTER TABLE `rubrics_criteria_comments` DROP COLUMN `lom_num` 
, DROP COLUMN `criteria_num` 
, DROP COLUMN `rubric_id`; 

ALTER TABLE `rubrics_criterias` CHARACTER SET = utf8 , CHANGE COLUMN `criteria_num` `criteria_num` INT(11) NOT NULL DEFAULT '999'  ;

ALTER TABLE `rubrics_loms` CHARACTER SET = utf8 , CHANGE COLUMN `lom_num` `lom_num` INT(11) NOT NULL DEFAULT '999'  ;

ALTER TABLE  `simple_evaluations` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `simple_evaluations` CHARACTER SET = utf8 , ADD COLUMN `availability` VARCHAR(10) NOT NULL DEFAULT 'public'  AFTER `record_status` ;

ALTER TABLE `survey_group_members` CHARACTER SET = utf8 , CHANGE COLUMN `group_set_id` `group_set_id` INT(11) NULL DEFAULT '0'  ;

ALTER TABLE `survey_group_sets` CHARACTER SET = utf8 ;

ALTER TABLE `survey_groups` CHARACTER SET = utf8 ;

ALTER TABLE `survey_inputs` CHARACTER SET = utf8 , ADD COLUMN `chkbx_id` INT(11) NULL DEFAULT NULL  AFTER `sub_id` ;

ALTER TABLE `survey_questions` CHARACTER SET = utf8 , CHANGE COLUMN `number` `number` INT(11) NOT NULL DEFAULT '9999'  ;

ALTER TABLE  `surveys` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `surveys` CHARACTER SET = utf8 , CHANGE COLUMN `name` `name` VARCHAR(255) NOT NULL  ;

ALTER TABLE  `sys_parameters` CHARACTER SET = utf8,  CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
INSERT INTO `sys_parameters` VALUES (NULL, 'system.version', '2.9.9', 'S', 'System version', 'A', 1, NOW(), 1, NOW());

ALTER TABLE  `user_courses` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `user_courses` CHARACTER SET = utf8 , CHANGE COLUMN `access_right` `access_right` VARCHAR(1) NOT NULL DEFAULT 'O'  , CHANGE COLUMN `record_status` `record_status` VARCHAR(1) NOT NULL DEFAULT 'A'  , 
  ADD CONSTRAINT `user_courses_ibfk_1`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE CASCADE, 
  ADD CONSTRAINT `user_courses_ibfk_2`
  FOREIGN KEY (`course_id` )
  REFERENCES `courses` (`id` )
  ON DELETE CASCADE
, ADD UNIQUE INDEX `no_duplicates` (`course_id` ASC, `user_id` ASC) 
, ADD INDEX `user_id` (`user_id` ASC) ;

ALTER TABLE  `user_enrols` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `user_enrols` CHARACTER SET = utf8 , CHANGE COLUMN `record_status` `record_status` VARCHAR(1) NOT NULL DEFAULT 'A'  , 
  ADD CONSTRAINT `user_enrols_ibfk_1`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE CASCADE, 
  ADD CONSTRAINT `user_enrols_ibfk_2`
  FOREIGN KEY (`course_id` )
  REFERENCES `courses` (`id` )
  ON DELETE CASCADE
, ADD UNIQUE INDEX `no_duplicates` (`course_id` ASC, `user_id` ASC) ;

CREATE  TABLE IF NOT EXISTS `user_faculties` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `faculty_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `faculty_id` (`faculty_id` ASC) ,
  CONSTRAINT `user_faculties_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE,
  CONSTRAINT `user_faculties_ibfk_2`
    FOREIGN KEY (`faculty_id` )
    REFERENCES `faculties` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
INSERT INTO `user_faculties` (user_id, faculty_id) (SELECT id, 1 FROM `users` WHERE role in ('A', 'I'));

CREATE  TABLE IF NOT EXISTS `user_tutors` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL DEFAULT '0' ,
  `course_id` INT(11) NOT NULL DEFAULT '0' ,
  `creator_id` INT(11) NOT NULL DEFAULT '0' ,
  `created` DATETIME NULL DEFAULT NULL ,
  `updater_id` INT(11) NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `no_duplicates` (`course_id` ASC, `user_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `user_tutors_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE,
  CONSTRAINT `user_tutors_ibfk_2`
    FOREIGN KEY (`course_id` )
    REFERENCES `courses` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

INSERT INTO roles_users (role_id, user_id, created, modified) (SELECT 2, id, NOW(), NOW() FROM  `users` WHERE  `role` =  'A' AND id != 1);
INSERT INTO roles_users (role_id, user_id, created, modified) (SELECT 3, id, NOW(), NOW() FROM  `users` WHERE  `role` =  'I');
INSERT INTO roles_users (role_id, user_id, created, modified) (SELECT 5, id, NOW(), NOW() FROM  `users` WHERE  `role` =  'S');
UPDATE `users` SET `created`=NOW() WHERE `created` = '0000-00-00 00:00:00';
ALTER TABLE `users` CHARACTER SET = utf8 
, CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL
, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL 
, DROP COLUMN `role` 
, ADD COLUMN `lti_id` INT(11) NULL DEFAULT NULL  AFTER `modified` 
, ADD UNIQUE INDEX `lti_id` (`lti_id` ASC) ;

DROP TABLE IF EXISTS `sys_functions` ;

-- some house clean up
DELETE FROM `group_events` WHERE `group_id` = 0;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
