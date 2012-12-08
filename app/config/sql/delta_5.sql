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

INSERT INTO `acos` VALUES (1,NULL,NULL,NULL,'adminpage',1,2),(2,NULL,NULL,NULL,'controllers',3,634),(3,2,NULL,NULL,'Pages',4,17),(4,3,NULL,NULL,'display',5,6),(5,3,NULL,NULL,'add',7,8),(6,3,NULL,NULL,'edit',9,10),(7,3,NULL,NULL,'index',11,12),(8,3,NULL,NULL,'view',13,14),(9,3,NULL,NULL,'delete',15,16),(10,2,NULL,NULL,'Courses',18,35),(11,10,NULL,NULL,'daysLate',19,20),(12,10,NULL,NULL,'index',21,22),(13,10,NULL,NULL,'ajaxList',23,24),(14,10,NULL,NULL,'view',25,26),(15,10,NULL,NULL,'home',27,28),(16,10,NULL,NULL,'add',29,30),(17,10,NULL,NULL,'edit',31,32),(18,10,NULL,NULL,'delete',33,34),(19,2,NULL,NULL,'Departments',36,47),(20,19,NULL,NULL,'index',37,38),(21,19,NULL,NULL,'view',39,40),(22,19,NULL,NULL,'add',41,42),(23,19,NULL,NULL,'edit',43,44),(24,19,NULL,NULL,'delete',45,46),(25,2,NULL,NULL,'Emailer',48,79),(26,25,NULL,NULL,'setUpAjaxList',49,50),(27,25,NULL,NULL,'ajaxList',51,52),(28,25,NULL,NULL,'index',53,54),(29,25,NULL,NULL,'write',55,56),(30,25,NULL,NULL,'cancel',57,58),(31,25,NULL,NULL,'view',59,60),(32,25,NULL,NULL,'addRecipient',61,62),(33,25,NULL,NULL,'deleteRecipient',63,64),(34,25,NULL,NULL,'getRecipient',65,66),(35,25,NULL,NULL,'searchByUserId',67,68),(36,25,NULL,NULL,'doMerge',69,70),(37,25,NULL,NULL,'send',71,72),(38,25,NULL,NULL,'add',73,74),(39,25,NULL,NULL,'edit',75,76),(40,25,NULL,NULL,'delete',77,78),(41,2,NULL,NULL,'Emailtemplates',80,99),(42,41,NULL,NULL,'setUpAjaxList',81,82),(43,41,NULL,NULL,'ajaxList',83,84),(44,41,NULL,NULL,'index',85,86),(45,41,NULL,NULL,'add',87,88),(46,41,NULL,NULL,'edit',89,90),(47,41,NULL,NULL,'delete',91,92),(48,41,NULL,NULL,'view',93,94),(49,41,NULL,NULL,'displayTemplateContent',95,96),(50,41,NULL,NULL,'displayTemplateSubject',97,98),(51,2,NULL,NULL,'Evaltools',100,113),(52,51,NULL,NULL,'index',101,102),(53,51,NULL,NULL,'showAll',103,104),(54,51,NULL,NULL,'add',105,106),(55,51,NULL,NULL,'edit',107,108),(56,51,NULL,NULL,'view',109,110),(57,51,NULL,NULL,'delete',111,112),(58,2,NULL,NULL,'Evaluations',114,173),(59,58,NULL,NULL,'postProcess',115,116),(60,58,NULL,NULL,'setUpAjaxList',117,118),(61,58,NULL,NULL,'ajaxList',119,120),(62,58,NULL,NULL,'view',121,122),(63,58,NULL,NULL,'index',123,124),(64,58,NULL,NULL,'update',125,126),(65,58,NULL,NULL,'export',127,128),(66,58,NULL,NULL,'makeEvaluation',129,130),(67,58,NULL,NULL,'validSurveyEvalComplete',131,132),(68,58,NULL,NULL,'validRubricEvalComplete',133,134),(69,58,NULL,NULL,'completeEvaluationRubric',135,136),(70,58,NULL,NULL,'validMixevalEvalComplete',137,138),(71,58,NULL,NULL,'completeEvaluationMixeval',139,140),(72,58,NULL,NULL,'viewEvaluationResults',141,142),(73,58,NULL,NULL,'viewSurveyGroupEvaluationResults',143,144),(74,58,NULL,NULL,'studentViewEvaluationResult',145,146),(75,58,NULL,NULL,'markEventReviewed',147,148),(76,58,NULL,NULL,'markGradeRelease',149,150),(77,58,NULL,NULL,'markCommentRelease',151,152),(78,58,NULL,NULL,'changeAllCommentRelease',153,154),(79,58,NULL,NULL,'changeAllGradeRelease',155,156),(80,58,NULL,NULL,'viewGroupSubmissionDetails',157,158),(81,58,NULL,NULL,'viewSurveySummary',159,160),(82,58,NULL,NULL,'export_rubic',161,162),(83,58,NULL,NULL,'export_test',163,164),(84,58,NULL,NULL,'pre',165,166),(85,58,NULL,NULL,'add',167,168),(86,58,NULL,NULL,'edit',169,170),(87,58,NULL,NULL,'delete',171,172),(88,2,NULL,NULL,'Events',174,205),(89,88,NULL,NULL,'postProcessData',175,176),(90,88,NULL,NULL,'setUpAjaxList',177,178),(91,88,NULL,NULL,'index',179,180),(92,88,NULL,NULL,'ajaxList',181,182),(93,88,NULL,NULL,'view',183,184),(94,88,NULL,NULL,'eventTemplatesList',185,186),(95,88,NULL,NULL,'add',187,188),(96,88,NULL,NULL,'edit',189,190),(97,88,NULL,NULL,'delete',191,192),(98,88,NULL,NULL,'search',193,194),(99,88,NULL,NULL,'checkDuplicateTitle',195,196),(100,88,NULL,NULL,'viewGroups',197,198),(101,88,NULL,NULL,'editGroup',199,200),(102,88,NULL,NULL,'getAssignedGroups',201,202),(103,88,NULL,NULL,'update',203,204),(104,2,NULL,NULL,'Faculties',206,217),(105,104,NULL,NULL,'index',207,208),(106,104,NULL,NULL,'view',209,210),(107,104,NULL,NULL,'add',211,212),(108,104,NULL,NULL,'edit',213,214),(109,104,NULL,NULL,'delete',215,216),(110,2,NULL,NULL,'Framework',218,235),(111,110,NULL,NULL,'calendarDisplay',219,220),(112,110,NULL,NULL,'userInfoDisplay',221,222),(113,110,NULL,NULL,'tutIndex',223,224),(114,110,NULL,NULL,'add',225,226),(115,110,NULL,NULL,'edit',227,228),(116,110,NULL,NULL,'index',229,230),(117,110,NULL,NULL,'view',231,232),(118,110,NULL,NULL,'delete',233,234),(119,2,NULL,NULL,'Groups',236,269),(120,119,NULL,NULL,'postProcess',237,238),(121,119,NULL,NULL,'setUpAjaxList',239,240),(122,119,NULL,NULL,'index',241,242),(123,119,NULL,NULL,'ajaxList',243,244),(124,119,NULL,NULL,'goToClassList',245,246),(125,119,NULL,NULL,'view',247,248),(126,119,NULL,NULL,'add',249,250),(127,119,NULL,NULL,'edit',251,252),(128,119,NULL,NULL,'delete',253,254),(129,119,NULL,NULL,'checkDuplicateName',255,256),(130,119,NULL,NULL,'getQueryAttribute',257,258),(131,119,NULL,NULL,'import',259,260),(132,119,NULL,NULL,'addGroupByImport',261,262),(133,119,NULL,NULL,'update',263,264),(134,119,NULL,NULL,'export',265,266),(135,119,NULL,NULL,'sendGroupEmail',267,268),(136,2,NULL,NULL,'Home',270,281),(137,136,NULL,NULL,'index',271,272),(138,136,NULL,NULL,'add',273,274),(139,136,NULL,NULL,'edit',275,276),(140,136,NULL,NULL,'view',277,278),(141,136,NULL,NULL,'delete',279,280),(142,2,NULL,NULL,'Install',282,303),(143,142,NULL,NULL,'index',283,284),(144,142,NULL,NULL,'install2',285,286),(145,142,NULL,NULL,'install3',287,288),(146,142,NULL,NULL,'install4',289,290),(147,142,NULL,NULL,'install5',291,292),(148,142,NULL,NULL,'gpl',293,294),(149,142,NULL,NULL,'add',295,296),(150,142,NULL,NULL,'edit',297,298),(151,142,NULL,NULL,'view',299,300),(152,142,NULL,NULL,'delete',301,302),(153,2,NULL,NULL,'Lti',304,315),(154,153,NULL,NULL,'index',305,306),(155,153,NULL,NULL,'add',307,308),(156,153,NULL,NULL,'edit',309,310),(157,153,NULL,NULL,'view',311,312),(158,153,NULL,NULL,'delete',313,314),(159,2,NULL,NULL,'Mixevals',316,345),(160,159,NULL,NULL,'postProcess',317,318),(161,159,NULL,NULL,'setUpAjaxList',319,320),(162,159,NULL,NULL,'index',321,322),(163,159,NULL,NULL,'ajaxList',323,324),(164,159,NULL,NULL,'view',325,326),(165,159,NULL,NULL,'add',327,328),(166,159,NULL,NULL,'deleteQuestion',329,330),(167,159,NULL,NULL,'deleteDescriptor',331,332),(168,159,NULL,NULL,'edit',333,334),(169,159,NULL,NULL,'copy',335,336),(170,159,NULL,NULL,'delete',337,338),(171,159,NULL,NULL,'previewMixeval',339,340),(172,159,NULL,NULL,'renderRows',341,342),(173,159,NULL,NULL,'update',343,344),(174,2,NULL,NULL,'OauthClients',346,357),(175,174,NULL,NULL,'index',347,348),(176,174,NULL,NULL,'add',349,350),(177,174,NULL,NULL,'edit',351,352),(178,174,NULL,NULL,'delete',353,354),(179,174,NULL,NULL,'view',355,356),(180,2,NULL,NULL,'OauthTokens',358,369),(181,180,NULL,NULL,'index',359,360),(182,180,NULL,NULL,'add',361,362),(183,180,NULL,NULL,'edit',363,364),(184,180,NULL,NULL,'delete',365,366),(185,180,NULL,NULL,'view',367,368),(186,2,NULL,NULL,'Penalty',370,383),(187,186,NULL,NULL,'save',371,372),(188,186,NULL,NULL,'add',373,374),(189,186,NULL,NULL,'edit',375,376),(190,186,NULL,NULL,'index',377,378),(191,186,NULL,NULL,'view',379,380),(192,186,NULL,NULL,'delete',381,382),(193,2,NULL,NULL,'Rubrics',384,405),(194,193,NULL,NULL,'postProcess',385,386),(195,193,NULL,NULL,'setUpAjaxList',387,388),(196,193,NULL,NULL,'index',389,390),(197,193,NULL,NULL,'ajaxList',391,392),(198,193,NULL,NULL,'view',393,394),(199,193,NULL,NULL,'add',395,396),(200,193,NULL,NULL,'edit',397,398),(201,193,NULL,NULL,'copy',399,400),(202,193,NULL,NULL,'delete',401,402),(203,193,NULL,NULL,'setForm_RubricName',403,404),(204,2,NULL,NULL,'Searchs',406,433),(205,204,NULL,NULL,'update',407,408),(206,204,NULL,NULL,'index',409,410),(207,204,NULL,NULL,'searchEvaluation',411,412),(208,204,NULL,NULL,'searchResult',413,414),(209,204,NULL,NULL,'searchInstructor',415,416),(210,204,NULL,NULL,'eventBoxSearch',417,418),(211,204,NULL,NULL,'formatSearchEvaluation',419,420),(212,204,NULL,NULL,'formatSearchInstructor',421,422),(213,204,NULL,NULL,'formatSearchEvaluationResult',423,424),(214,204,NULL,NULL,'add',425,426),(215,204,NULL,NULL,'edit',427,428),(216,204,NULL,NULL,'view',429,430),(217,204,NULL,NULL,'delete',431,432),(218,2,NULL,NULL,'Simpleevaluations',434,453),(219,218,NULL,NULL,'postProcess',435,436),(220,218,NULL,NULL,'setUpAjaxList',437,438),(221,218,NULL,NULL,'index',439,440),(222,218,NULL,NULL,'ajaxList',441,442),(223,218,NULL,NULL,'view',443,444),(224,218,NULL,NULL,'add',445,446),(225,218,NULL,NULL,'edit',447,448),(226,218,NULL,NULL,'copy',449,450),(227,218,NULL,NULL,'delete',451,452),(228,2,NULL,NULL,'Surveygroups',454,485),(229,228,NULL,NULL,'postProcess',455,456),(230,228,NULL,NULL,'setUpAjaxList',457,458),(231,228,NULL,NULL,'index',459,460),(232,228,NULL,NULL,'ajaxList',461,462),(233,228,NULL,NULL,'viewresult',463,464),(234,228,NULL,NULL,'makegroups',465,466),(235,228,NULL,NULL,'makegroupssearch',467,468),(236,228,NULL,NULL,'maketmgroups',469,470),(237,228,NULL,NULL,'savegroups',471,472),(238,228,NULL,NULL,'release',473,474),(239,228,NULL,NULL,'delete',475,476),(240,228,NULL,NULL,'edit',477,478),(241,228,NULL,NULL,'changegroupset',479,480),(242,228,NULL,NULL,'add',481,482),(243,228,NULL,NULL,'view',483,484),(244,2,NULL,NULL,'Surveys',486,519),(245,244,NULL,NULL,'setUpAjaxList',487,488),(246,244,NULL,NULL,'index',489,490),(247,244,NULL,NULL,'ajaxList',491,492),(248,244,NULL,NULL,'view',493,494),(249,244,NULL,NULL,'add',495,496),(250,244,NULL,NULL,'edit',497,498),(251,244,NULL,NULL,'copy',499,500),(252,244,NULL,NULL,'delete',501,502),(253,244,NULL,NULL,'checkDuplicateName',503,504),(254,244,NULL,NULL,'releaseSurvey',505,506),(255,244,NULL,NULL,'questionsSummary',507,508),(256,244,NULL,NULL,'moveQuestion',509,510),(257,244,NULL,NULL,'removeQuestion',511,512),(258,244,NULL,NULL,'addQuestion',513,514),(259,244,NULL,NULL,'editQuestion',515,516),(260,244,NULL,NULL,'update',517,518),(261,2,NULL,NULL,'Sysparameters',520,535),(262,261,NULL,NULL,'setUpAjaxList',521,522),(263,261,NULL,NULL,'index',523,524),(264,261,NULL,NULL,'ajaxList',525,526),(265,261,NULL,NULL,'view',527,528),(266,261,NULL,NULL,'add',529,530),(267,261,NULL,NULL,'edit',531,532),(268,261,NULL,NULL,'delete',533,534),(269,2,NULL,NULL,'Upgrade',536,549),(270,269,NULL,NULL,'index',537,538),(271,269,NULL,NULL,'step2',539,540),(272,269,NULL,NULL,'add',541,542),(273,269,NULL,NULL,'edit',543,544),(274,269,NULL,NULL,'view',545,546),(275,269,NULL,NULL,'delete',547,548),(276,2,NULL,NULL,'Users',550,579),(277,276,NULL,NULL,'ajaxList',551,552),(278,276,NULL,NULL,'index',553,554),(279,276,NULL,NULL,'goToClassList',555,556),(280,276,NULL,NULL,'determineIfStudentFromThisData',557,558),(281,276,NULL,NULL,'getSimpleEntrollmentLists',559,560),(282,276,NULL,NULL,'view',561,562),(283,276,NULL,NULL,'add',563,564),(284,276,NULL,NULL,'edit',565,566),(285,276,NULL,NULL,'editProfile',567,568),(286,276,NULL,NULL,'delete',569,570),(287,276,NULL,NULL,'checkDuplicateName',571,572),(288,276,NULL,NULL,'resetPassword',573,574),(289,276,NULL,NULL,'import',575,576),(290,276,NULL,NULL,'update',577,578),(291,2,NULL,NULL,'V1',580,615),(292,291,NULL,NULL,'oauth',581,582),(293,291,NULL,NULL,'oauth_error',583,584),(294,291,NULL,NULL,'users',585,586),(295,291,NULL,NULL,'courses',587,588),(296,291,NULL,NULL,'groups',589,590),(297,291,NULL,NULL,'groupMembers',591,592),(298,291,NULL,NULL,'events',593,594),(299,291,NULL,NULL,'grades',595,596),(300,291,NULL,NULL,'departments',597,598),(301,291,NULL,NULL,'courseDepartments',599,600),(302,291,NULL,NULL,'userEvents',601,602),(303,291,NULL,NULL,'enrolment',603,604),(304,291,NULL,NULL,'add',605,606),(305,291,NULL,NULL,'edit',607,608),(306,291,NULL,NULL,'index',609,610),(307,291,NULL,NULL,'view',611,612),(308,291,NULL,NULL,'delete',613,614),(309,2,NULL,NULL,'Guard',616,633),(310,309,NULL,NULL,'Guard',617,632),(311,310,NULL,NULL,'login',618,619),(312,310,NULL,NULL,'logout',620,621),(313,310,NULL,NULL,'add',622,623),(314,310,NULL,NULL,'edit',624,625),(315,310,NULL,NULL,'index',626,627),(316,310,NULL,NULL,'view',628,629),(317,310,NULL,NULL,'delete',630,631),(318,NULL,NULL,NULL,'functions',635,698),(319,318,NULL,NULL,'user',636,663),(320,319,NULL,NULL,'superadmin',637,638),(321,319,NULL,NULL,'admin',639,640),(322,319,NULL,NULL,'instructor',641,642),(323,319,NULL,NULL,'tutor',643,644),(324,319,NULL,NULL,'student',645,646),(325,319,NULL,NULL,'import',647,648),(326,319,NULL,NULL,'password_reset',649,660),(327,326,NULL,NULL,'superadmin',650,651),(328,326,NULL,NULL,'admin',652,653),(329,326,NULL,NULL,'instructor',654,655),(330,326,NULL,NULL,'tutor',656,657),(331,326,NULL,NULL,'student',658,659),(332,319,NULL,NULL,'index',661,662),(333,318,NULL,NULL,'role',664,675),(334,333,NULL,NULL,'superadmin',665,666),(335,333,NULL,NULL,'admin',667,668),(336,333,NULL,NULL,'instructor',669,670),(337,333,NULL,NULL,'tutor',671,672),(338,333,NULL,NULL,'student',673,674),(339,318,NULL,NULL,'evaluation',676,679),(340,339,NULL,NULL,'export',677,678),(341,318,NULL,NULL,'email',680,687),(342,341,NULL,NULL,'allUsers',681,682),(343,341,NULL,NULL,'allGroups',683,684),(344,341,NULL,NULL,'allCourses',685,686),(345,318,NULL,NULL,'emailtemplate',688,689),(346,318,NULL,NULL,'viewstudentresults',690,691),(347,318,NULL,NULL,'viewemailaddresses',692,693),(348,318,NULL,NULL,'superadmin',694,695),(349,318,NULL,NULL,'onlytakeeval',696,697);

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

INSERT INTO `aros_acos` VALUES (1,1,2,'1','1','1','1'),(2,1,318,'1','1','1','1'),(3,1,51,'1','1','1','1'),(4,1,218,'1','1','1','1'),(5,1,193,'1','1','1','1'),(6,1,159,'1','1','1','1'),(7,1,244,'1','1','1','1'),(8,1,228,'1','1','1','1'),(9,1,25,'1','1','1','1'),(10,1,41,'1','1','1','1'),(11,1,88,'1','1','1','1'),(12,1,104,'1','1','1','1'),(13,1,19,'1','1','1','1'),(14,1,119,'1','1','1','1'),(15,1,1,'1','1','1','1'),(16,1,347,'1','1','1','1'),(17,1,348,'1','1','1','1'),(18,2,2,'-1','-1','-1','-1'),(19,2,136,'1','1','1','1'),(20,2,10,'1','1','1','1'),(21,2,19,'1','1','1','1'),(22,2,25,'1','1','1','1'),(23,2,41,'1','1','1','1'),(24,2,51,'1','1','1','1'),(25,2,88,'1','1','1','1'),(26,2,119,'1','1','1','1'),(27,2,159,'1','1','1','1'),(28,2,193,'1','1','1','1'),(29,2,218,'1','1','1','1'),(30,2,244,'1','1','1','1'),(31,2,228,'1','1','1','1'),(32,2,276,'1','1','1','1'),(33,2,58,'1','1','1','1'),(34,2,312,'1','1','1','1'),(35,2,318,'-1','-1','-1','-1'),(36,2,345,'1','1','1','1'),(37,2,339,'1','1','1','1'),(38,2,342,'1','1','1','1'),(39,2,319,'1','1','1','1'),(40,2,321,'-1','1','-1','-1'),(41,2,320,'-1','-1','-1','-1'),(42,2,1,'1','1','1','1'),(43,2,347,'1','1','1','1'),(44,2,348,'-1','-1','-1','-1'),(45,3,2,'-1','-1','-1','-1'),(46,3,136,'1','1','1','1'),(47,3,10,'1','1','1','1'),(48,3,16,'-1','-1','-1','-1'),(49,3,17,'-1','-1','-1','-1'),(50,3,25,'1','1','1','1'),(51,3,41,'1','1','1','1'),(52,3,51,'1','1','1','1'),(53,3,88,'1','1','1','1'),(54,3,119,'1','1','1','1'),(55,3,159,'1','1','1','1'),(56,3,193,'1','1','1','1'),(57,3,218,'1','1','1','1'),(58,3,244,'1','1','1','1'),(59,3,228,'1','1','1','1'),(60,3,276,'1','1','1','1'),(61,3,312,'1','1','1','1'),(62,3,318,'-1','-1','-1','-1'),(63,3,339,'1','1','-1','-1'),(64,3,319,'1','1','1','1'),(65,3,321,'-1','-1','-1','-1'),(66,3,320,'-1','-1','-1','-1'),(67,3,322,'-1','1','-1','-1'),(68,3,332,'-1','-1','-1','-1'),(69,3,347,'-1','-1','-1','-1'),(70,3,348,'-1','-1','-1','-1'),(71,3,349,'-1','-1','-1','-1'),(72,4,2,'-1','-1','-1','-1'),(73,4,136,'1','1','1','1'),(74,4,10,'-1','-1','-1','-1'),(75,4,25,'-1','-1','-1','-1'),(76,4,41,'-1','-1','-1','-1'),(77,4,51,'-1','-1','-1','-1'),(78,4,88,'-1','-1','-1','-1'),(79,4,119,'-1','-1','-1','-1'),(80,4,159,'-1','-1','-1','-1'),(81,4,193,'-1','-1','-1','-1'),(82,4,218,'-1','-1','-1','-1'),(83,4,244,'-1','-1','-1','-1'),(84,4,228,'-1','-1','-1','-1'),(85,4,276,'-1','-1','-1','-1'),(86,4,312,'1','1','1','1'),(87,4,66,'1','1','1','1'),(88,4,74,'1','1','1','1'),(89,4,69,'1','1','1','1'),(90,4,71,'1','1','1','1'),(91,4,318,'-1','-1','-1','-1'),(92,4,347,'-1','-1','-1','-1'),(93,4,348,'-1','-1','-1','-1'),(94,4,349,'1','1','1','1'),(95,5,2,'-1','-1','-1','-1'),(96,5,136,'1','1','1','1'),(97,5,10,'-1','-1','-1','-1'),(98,5,25,'-1','-1','-1','-1'),(99,5,41,'-1','-1','-1','-1'),(100,5,51,'-1','-1','-1','-1'),(101,5,88,'-1','-1','-1','-1'),(102,5,119,'-1','-1','-1','-1'),(103,5,159,'-1','-1','-1','-1'),(104,5,193,'-1','-1','-1','-1'),(105,5,218,'-1','-1','-1','-1'),(106,5,244,'-1','-1','-1','-1'),(107,5,228,'-1','-1','-1','-1'),(108,5,276,'-1','-1','-1','-1'),(109,5,312,'1','1','1','1'),(110,5,66,'1','1','1','1'),(111,5,74,'1','1','1','1'),(112,5,69,'1','1','1','1'),(113,5,71,'1','1','1','1'),(114,5,318,'-1','-1','-1','-1'),(115,5,346,'1','1','1','1'),(116,5,347,'-1','-1','-1','-1'),(117,5,348,'-1','-1','-1','-1'),(118,5,349,'1','1','1','1');

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
