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

INSERT INTO `acos` VALUES (1,NULL,NULL,NULL,'adminpage',1,2),(2,NULL,NULL,NULL,'controllers',3,588),(3,2,NULL,NULL,'Pages',4,17),(4,3,NULL,NULL,'display',5,6),(5,3,NULL,NULL,'add',7,8),(6,3,NULL,NULL,'edit',9,10),(7,3,NULL,NULL,'index',11,12),(8,3,NULL,NULL,'view',13,14),(9,3,NULL,NULL,'delete',15,16),(10,2,NULL,NULL,'Courses',18,35),(11,10,NULL,NULL,'daysLate',19,20),(12,10,NULL,NULL,'index',21,22),(13,10,NULL,NULL,'ajaxList',23,24),(14,10,NULL,NULL,'view',25,26),(15,10,NULL,NULL,'home',27,28),(16,10,NULL,NULL,'add',29,30),(17,10,NULL,NULL,'edit',31,32),(18,10,NULL,NULL,'delete',33,34),(19,2,NULL,NULL,'Departments',36,47),(20,19,NULL,NULL,'index',37,38),(21,19,NULL,NULL,'view',39,40),(22,19,NULL,NULL,'add',41,42),(23,19,NULL,NULL,'edit',43,44),(24,19,NULL,NULL,'delete',45,46),(25,2,NULL,NULL,'Emailer',48,79),(26,25,NULL,NULL,'setUpAjaxList',49,50),(27,25,NULL,NULL,'ajaxList',51,52),(28,25,NULL,NULL,'index',53,54),(29,25,NULL,NULL,'write',55,56),(30,25,NULL,NULL,'cancel',57,58),(31,25,NULL,NULL,'view',59,60),(32,25,NULL,NULL,'addRecipient',61,62),(33,25,NULL,NULL,'deleteRecipient',63,64),(34,25,NULL,NULL,'getRecipient',65,66),(35,25,NULL,NULL,'searchByUserId',67,68),(36,25,NULL,NULL,'doMerge',69,70),(37,25,NULL,NULL,'send',71,72),(38,25,NULL,NULL,'add',73,74),(39,25,NULL,NULL,'edit',75,76),(40,25,NULL,NULL,'delete',77,78),(41,2,NULL,NULL,'Emailtemplates',80,99),(42,41,NULL,NULL,'setUpAjaxList',81,82),(43,41,NULL,NULL,'ajaxList',83,84),(44,41,NULL,NULL,'index',85,86),(45,41,NULL,NULL,'add',87,88),(46,41,NULL,NULL,'edit',89,90),(47,41,NULL,NULL,'delete',91,92),(48,41,NULL,NULL,'view',93,94),(49,41,NULL,NULL,'displayTemplateContent',95,96),(50,41,NULL,NULL,'displayTemplateSubject',97,98),(51,2,NULL,NULL,'Evaltools',100,113),(52,51,NULL,NULL,'index',101,102),(53,51,NULL,NULL,'showAll',103,104),(54,51,NULL,NULL,'add',105,106),(55,51,NULL,NULL,'edit',107,108),(56,51,NULL,NULL,'view',109,110),(57,51,NULL,NULL,'delete',111,112),(58,2,NULL,NULL,'Evaluations',114,171),(59,58,NULL,NULL,'postProcess',115,116),(60,58,NULL,NULL,'setUpAjaxList',117,118),(61,58,NULL,NULL,'ajaxList',119,120),(62,58,NULL,NULL,'view',121,122),(63,58,NULL,NULL,'index',123,124),(64,58,NULL,NULL,'export',125,126),(65,58,NULL,NULL,'makeEvaluation',127,128),(66,58,NULL,NULL,'validSurveyEvalComplete',129,130),(67,58,NULL,NULL,'validRubricEvalComplete',131,132),(68,58,NULL,NULL,'completeEvaluationRubric',133,134),(69,58,NULL,NULL,'validMixevalEvalComplete',135,136),(70,58,NULL,NULL,'completeEvaluationMixeval',137,138),(71,58,NULL,NULL,'viewEvaluationResults',139,140),(72,58,NULL,NULL,'viewSurveyGroupEvaluationResults',141,142),(73,58,NULL,NULL,'studentViewEvaluationResult',143,144),(74,58,NULL,NULL,'markEventReviewed',145,146),(75,58,NULL,NULL,'markGradeRelease',147,148),(76,58,NULL,NULL,'markCommentRelease',149,150),(77,58,NULL,NULL,'changeAllCommentRelease',151,152),(78,58,NULL,NULL,'changeAllGradeRelease',153,154),(79,58,NULL,NULL,'viewGroupSubmissionDetails',155,156),(80,58,NULL,NULL,'viewSurveySummary',157,158),(81,58,NULL,NULL,'export_rubic',159,160),(82,58,NULL,NULL,'export_test',161,162),(83,58,NULL,NULL,'pre',163,164),(84,58,NULL,NULL,'add',165,166),(85,58,NULL,NULL,'edit',167,168),(86,58,NULL,NULL,'delete',169,170),(87,2,NULL,NULL,'Events',172,189),(88,87,NULL,NULL,'postProcessData',173,174),(89,87,NULL,NULL,'setUpAjaxList',175,176),(90,87,NULL,NULL,'index',177,178),(91,87,NULL,NULL,'ajaxList',179,180),(92,87,NULL,NULL,'view',181,182),(93,87,NULL,NULL,'add',183,184),(94,87,NULL,NULL,'edit',185,186),(95,87,NULL,NULL,'delete',187,188),(96,2,NULL,NULL,'Faculties',190,201),(97,96,NULL,NULL,'index',191,192),(98,96,NULL,NULL,'view',193,194),(99,96,NULL,NULL,'add',195,196),(100,96,NULL,NULL,'edit',197,198),(101,96,NULL,NULL,'delete',199,200),(102,2,NULL,NULL,'Framework',202,219),(103,102,NULL,NULL,'calendarDisplay',203,204),(104,102,NULL,NULL,'userInfoDisplay',205,206),(105,102,NULL,NULL,'tutIndex',207,208),(106,102,NULL,NULL,'add',209,210),(107,102,NULL,NULL,'edit',211,212),(108,102,NULL,NULL,'index',213,214),(109,102,NULL,NULL,'view',215,216),(110,102,NULL,NULL,'delete',217,218),(111,2,NULL,NULL,'Groups',220,239),(112,111,NULL,NULL,'setUpAjaxList',221,222),(113,111,NULL,NULL,'index',223,224),(114,111,NULL,NULL,'ajaxList',225,226),(115,111,NULL,NULL,'view',227,228),(116,111,NULL,NULL,'add',229,230),(117,111,NULL,NULL,'edit',231,232),(118,111,NULL,NULL,'delete',233,234),(119,111,NULL,NULL,'import',235,236),(120,111,NULL,NULL,'export',237,238),(121,2,NULL,NULL,'Home',240,251),(122,121,NULL,NULL,'index',241,242),(123,121,NULL,NULL,'add',243,244),(124,121,NULL,NULL,'edit',245,246),(125,121,NULL,NULL,'view',247,248),(126,121,NULL,NULL,'delete',249,250),(127,2,NULL,NULL,'Install',252,273),(128,127,NULL,NULL,'index',253,254),(129,127,NULL,NULL,'install2',255,256),(130,127,NULL,NULL,'install3',257,258),(131,127,NULL,NULL,'install4',259,260),(132,127,NULL,NULL,'install5',261,262),(133,127,NULL,NULL,'gpl',263,264),(134,127,NULL,NULL,'add',265,266),(135,127,NULL,NULL,'edit',267,268),(136,127,NULL,NULL,'view',269,270),(137,127,NULL,NULL,'delete',271,272),(138,2,NULL,NULL,'Lti',274,285),(139,138,NULL,NULL,'index',275,276),(140,138,NULL,NULL,'add',277,278),(141,138,NULL,NULL,'edit',279,280),(142,138,NULL,NULL,'view',281,282),(143,138,NULL,NULL,'delete',283,284),(144,2,NULL,NULL,'Mixevals',286,307),(145,144,NULL,NULL,'setUpAjaxList',287,288),(146,144,NULL,NULL,'index',289,290),(147,144,NULL,NULL,'ajaxList',291,292),(148,144,NULL,NULL,'view',293,294),(149,144,NULL,NULL,'add',295,296),(150,144,NULL,NULL,'deleteQuestion',297,298),(151,144,NULL,NULL,'deleteDescriptor',299,300),(152,144,NULL,NULL,'edit',301,302),(153,144,NULL,NULL,'copy',303,304),(154,144,NULL,NULL,'delete',305,306),(155,2,NULL,NULL,'Oauthclients',308,319),(156,155,NULL,NULL,'index',309,310),(157,155,NULL,NULL,'add',311,312),(158,155,NULL,NULL,'edit',313,314),(159,155,NULL,NULL,'delete',315,316),(160,155,NULL,NULL,'view',317,318),(161,2,NULL,NULL,'Oauthtokens',320,331),(162,161,NULL,NULL,'index',321,322),(163,161,NULL,NULL,'add',323,324),(164,161,NULL,NULL,'edit',325,326),(165,161,NULL,NULL,'delete',327,328),(166,161,NULL,NULL,'view',329,330),(167,2,NULL,NULL,'Penalty',332,345),(168,167,NULL,NULL,'save',333,334),(169,167,NULL,NULL,'add',335,336),(170,167,NULL,NULL,'edit',337,338),(171,167,NULL,NULL,'index',339,340),(172,167,NULL,NULL,'view',341,342),(173,167,NULL,NULL,'delete',343,344),(174,2,NULL,NULL,'Rubrics',346,365),(175,174,NULL,NULL,'postProcess',347,348),(176,174,NULL,NULL,'setUpAjaxList',349,350),(177,174,NULL,NULL,'index',351,352),(178,174,NULL,NULL,'ajaxList',353,354),(179,174,NULL,NULL,'view',355,356),(180,174,NULL,NULL,'add',357,358),(181,174,NULL,NULL,'edit',359,360),(182,174,NULL,NULL,'copy',361,362),(183,174,NULL,NULL,'delete',363,364),(184,2,NULL,NULL,'Searchs',366,393),(185,184,NULL,NULL,'update',367,368),(186,184,NULL,NULL,'index',369,370),(187,184,NULL,NULL,'searchEvaluation',371,372),(188,184,NULL,NULL,'searchResult',373,374),(189,184,NULL,NULL,'searchInstructor',375,376),(190,184,NULL,NULL,'eventBoxSearch',377,378),(191,184,NULL,NULL,'formatSearchEvaluation',379,380),(192,184,NULL,NULL,'formatSearchInstructor',381,382),(193,184,NULL,NULL,'formatSearchEvaluationResult',383,384),(194,184,NULL,NULL,'add',385,386),(195,184,NULL,NULL,'edit',387,388),(196,184,NULL,NULL,'view',389,390),(197,184,NULL,NULL,'delete',391,392),(198,2,NULL,NULL,'Simpleevaluations',394,413),(199,198,NULL,NULL,'postProcess',395,396),(200,198,NULL,NULL,'setUpAjaxList',397,398),(201,198,NULL,NULL,'index',399,400),(202,198,NULL,NULL,'ajaxList',401,402),(203,198,NULL,NULL,'view',403,404),(204,198,NULL,NULL,'add',405,406),(205,198,NULL,NULL,'edit',407,408),(206,198,NULL,NULL,'copy',409,410),(207,198,NULL,NULL,'delete',411,412),(208,2,NULL,NULL,'Surveygroups',414,445),(209,208,NULL,NULL,'postProcess',415,416),(210,208,NULL,NULL,'setUpAjaxList',417,418),(211,208,NULL,NULL,'index',419,420),(212,208,NULL,NULL,'ajaxList',421,422),(213,208,NULL,NULL,'viewresult',423,424),(214,208,NULL,NULL,'makegroups',425,426),(215,208,NULL,NULL,'makegroupssearch',427,428),(216,208,NULL,NULL,'maketmgroups',429,430),(217,208,NULL,NULL,'savegroups',431,432),(218,208,NULL,NULL,'release',433,434),(219,208,NULL,NULL,'delete',435,436),(220,208,NULL,NULL,'edit',437,438),(221,208,NULL,NULL,'changegroupset',439,440),(222,208,NULL,NULL,'add',441,442),(223,208,NULL,NULL,'view',443,444),(224,2,NULL,NULL,'Surveys',446,475),(225,224,NULL,NULL,'setUpAjaxList',447,448),(226,224,NULL,NULL,'index',449,450),(227,224,NULL,NULL,'ajaxList',451,452),(228,224,NULL,NULL,'view',453,454),(229,224,NULL,NULL,'add',455,456),(230,224,NULL,NULL,'edit',457,458),(231,224,NULL,NULL,'copy',459,460),(232,224,NULL,NULL,'delete',461,462),(233,224,NULL,NULL,'releaseSurvey',463,464),(234,224,NULL,NULL,'questionsSummary',465,466),(235,224,NULL,NULL,'moveQuestion',467,468),(236,224,NULL,NULL,'removeQuestion',469,470),(237,224,NULL,NULL,'addQuestion',471,472),(238,224,NULL,NULL,'editQuestion',473,474),(239,2,NULL,NULL,'Sysparameters',476,491),(240,239,NULL,NULL,'setUpAjaxList',477,478),(241,239,NULL,NULL,'index',479,480),(242,239,NULL,NULL,'ajaxList',481,482),(243,239,NULL,NULL,'view',483,484),(244,239,NULL,NULL,'add',485,486),(245,239,NULL,NULL,'edit',487,488),(246,239,NULL,NULL,'delete',489,490),(247,2,NULL,NULL,'Upgrade',492,505),(248,247,NULL,NULL,'index',493,494),(249,247,NULL,NULL,'step2',495,496),(250,247,NULL,NULL,'add',497,498),(251,247,NULL,NULL,'edit',499,500),(252,247,NULL,NULL,'view',501,502),(253,247,NULL,NULL,'delete',503,504),(254,2,NULL,NULL,'Users',506,533),(255,254,NULL,NULL,'ajaxList',507,508),(256,254,NULL,NULL,'index',509,510),(257,254,NULL,NULL,'goToClassList',511,512),(258,254,NULL,NULL,'determineIfStudentFromThisData',513,514),(259,254,NULL,NULL,'view',515,516),(260,254,NULL,NULL,'add',517,518),(261,254,NULL,NULL,'edit',519,520),(262,254,NULL,NULL,'editProfile',521,522),(263,254,NULL,NULL,'delete',523,524),(264,254,NULL,NULL,'checkDuplicateName',525,526),(265,254,NULL,NULL,'resetPassword',527,528),(266,254,NULL,NULL,'import',529,530),(267,254,NULL,NULL,'update',531,532),(268,2,NULL,NULL,'V1',534,569),(269,268,NULL,NULL,'oauth',535,536),(270,268,NULL,NULL,'oauth_error',537,538),(271,268,NULL,NULL,'users',539,540),(272,268,NULL,NULL,'courses',541,542),(273,268,NULL,NULL,'groups',543,544),(274,268,NULL,NULL,'groupMembers',545,546),(275,268,NULL,NULL,'events',547,548),(276,268,NULL,NULL,'grades',549,550),(277,268,NULL,NULL,'departments',551,552),(278,268,NULL,NULL,'courseDepartments',553,554),(279,268,NULL,NULL,'userEvents',555,556),(280,268,NULL,NULL,'enrolment',557,558),(281,268,NULL,NULL,'add',559,560),(282,268,NULL,NULL,'edit',561,562),(283,268,NULL,NULL,'index',563,564),(284,268,NULL,NULL,'view',565,566),(285,268,NULL,NULL,'delete',567,568),(286,2,NULL,NULL,'Guard',570,587),(287,286,NULL,NULL,'Guard',571,586),(288,287,NULL,NULL,'login',572,573),(289,287,NULL,NULL,'logout',574,575),(290,287,NULL,NULL,'add',576,577),(291,287,NULL,NULL,'edit',578,579),(292,287,NULL,NULL,'index',580,581),(293,287,NULL,NULL,'view',582,583),(294,287,NULL,NULL,'delete',584,585),(295,NULL,NULL,NULL,'functions',589,654),(296,295,NULL,NULL,'user',590,617),(297,296,NULL,NULL,'superadmin',591,592),(298,296,NULL,NULL,'admin',593,594),(299,296,NULL,NULL,'instructor',595,596),(300,296,NULL,NULL,'tutor',597,598),(301,296,NULL,NULL,'student',599,600),(302,296,NULL,NULL,'import',601,602),(303,296,NULL,NULL,'password_reset',603,614),(304,303,NULL,NULL,'superadmin',604,605),(305,303,NULL,NULL,'admin',606,607),(306,303,NULL,NULL,'instructor',608,609),(307,303,NULL,NULL,'tutor',610,611),(308,303,NULL,NULL,'student',612,613),(309,296,NULL,NULL,'index',615,616),(310,295,NULL,NULL,'role',618,629),(311,310,NULL,NULL,'superadmin',619,620),(312,310,NULL,NULL,'admin',621,622),(313,310,NULL,NULL,'instructor',623,624),(314,310,NULL,NULL,'tutor',625,626),(315,310,NULL,NULL,'student',627,628),(316,295,NULL,NULL,'evaluation',630,633),(317,316,NULL,NULL,'export',631,632),(318,295,NULL,NULL,'email',634,641),(319,318,NULL,NULL,'allUsers',635,636),(320,318,NULL,NULL,'allGroups',637,638),(321,318,NULL,NULL,'allCourses',639,640),(322,295,NULL,NULL,'emailtemplate',642,643),(323,295,NULL,NULL,'viewstudentresults',644,645),(324,295,NULL,NULL,'viewemailaddresses',646,647),(325,295,NULL,NULL,'superadmin',648,649),(326,295,NULL,NULL,'coursemanager',650,651),(327,295,NULL,NULL,'viewusername',652,653);

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

INSERT INTO `aros_acos` VALUES (1,1,2,'1','1','1','1'),(2,1,295,'1','1','1','1'),(3,1,1,'1','1','1','1'),(4,2,2,'-1','-1','-1','-1'),(5,2,121,'1','1','1','1'),(6,2,10,'1','1','1','1'),(7,2,19,'1','1','1','1'),(8,2,25,'1','1','1','1'),(9,2,41,'1','1','1','1'),(10,2,51,'1','1','1','1'),(11,2,58,'1','1','1','1'),(12,2,87,'1','1','1','1'),(13,2,111,'1','1','1','1'),(14,2,144,'1','1','1','1'),(15,2,174,'1','1','1','1'),(16,2,198,'1','1','1','1'),(17,2,224,'1','1','1','1'),(18,2,208,'1','1','1','1'),(19,2,254,'1','1','1','1'),(20,2,289,'1','1','1','1'),(21,2,157,'1','1','1','1'),(22,2,159,'1','1','1','1'),(23,2,163,'1','1','1','1'),(24,2,165,'1','1','1','1'),(25,2,295,'-1','-1','-1','-1'),(26,2,322,'1','1','1','1'),(27,2,316,'1','1','1','1'),(28,2,319,'1','1','1','1'),(29,2,296,'1','1','1','1'),(30,2,298,'-1','1','-1','-1'),(31,2,297,'-1','-1','-1','-1'),(32,2,1,'1','1','1','1'),(33,2,324,'1','1','1','1'),(34,2,327,'1','1','1','1'),(35,2,326,'1','1','1','1'),(36,2,325,'-1','-1','-1','-1'),(37,3,2,'-1','-1','-1','-1'),(38,3,121,'1','1','1','1'),(39,3,10,'1','1','1','1'),(40,3,16,'-1','-1','-1','-1'),(41,3,17,'-1','-1','-1','-1'),(42,3,25,'1','1','1','1'),(43,3,41,'1','1','1','1'),(44,3,51,'1','1','1','1'),(45,3,58,'1','1','1','1'),(46,3,87,'1','1','1','1'),(47,3,111,'1','1','1','1'),(48,3,144,'1','1','1','1'),(49,3,174,'1','1','1','1'),(50,3,198,'1','1','1','1'),(51,3,224,'1','1','1','1'),(52,3,208,'1','1','1','1'),(53,3,254,'1','1','1','1'),(54,3,289,'1','1','1','1'),(55,3,157,'1','1','1','1'),(56,3,159,'1','1','1','1'),(57,3,163,'1','1','1','1'),(58,3,165,'1','1','1','1'),(59,3,295,'-1','-1','-1','-1'),(60,3,316,'1','1','-1','-1'),(61,3,296,'1','1','1','1'),(62,3,298,'-1','-1','-1','-1'),(63,3,297,'-1','-1','-1','-1'),(64,3,299,'-1','1','-1','-1'),(65,3,309,'-1','-1','-1','-1'),(66,3,324,'-1','-1','-1','-1'),(67,3,325,'-1','-1','-1','-1'),(68,3,326,'1','1','1','1'),(69,4,2,'-1','-1','-1','-1'),(70,4,121,'1','1','1','1'),(71,4,10,'-1','-1','-1','-1'),(72,4,25,'-1','-1','-1','-1'),(73,4,41,'-1','-1','-1','-1'),(74,4,51,'-1','-1','-1','-1'),(75,4,87,'-1','-1','-1','-1'),(76,4,111,'-1','-1','-1','-1'),(77,4,144,'-1','-1','-1','-1'),(78,4,174,'-1','-1','-1','-1'),(79,4,198,'-1','-1','-1','-1'),(80,4,224,'-1','-1','-1','-1'),(81,4,208,'-1','-1','-1','-1'),(82,4,254,'-1','-1','-1','-1'),(83,4,289,'1','1','1','1'),(84,4,65,'1','1','1','1'),(85,4,73,'1','1','1','1'),(86,4,68,'1','1','1','1'),(87,4,70,'1','1','1','1'),(88,4,262,'1','1','1','1'),(89,4,157,'1','1','1','1'),(90,4,159,'1','1','1','1'),(91,4,163,'1','1','1','1'),(92,4,165,'1','1','1','1'),(93,4,295,'-1','-1','-1','-1'),(94,4,324,'-1','-1','-1','-1'),(95,4,325,'-1','-1','-1','-1'),(96,5,2,'-1','-1','-1','-1'),(97,5,121,'1','1','1','1'),(98,5,10,'-1','-1','-1','-1'),(99,5,25,'-1','-1','-1','-1'),(100,5,41,'-1','-1','-1','-1'),(101,5,51,'-1','-1','-1','-1'),(102,5,87,'-1','-1','-1','-1'),(103,5,111,'-1','-1','-1','-1'),(104,5,144,'-1','-1','-1','-1'),(105,5,174,'-1','-1','-1','-1'),(106,5,198,'-1','-1','-1','-1'),(107,5,224,'-1','-1','-1','-1'),(108,5,208,'-1','-1','-1','-1'),(109,5,254,'-1','-1','-1','-1'),(110,5,289,'1','1','1','1'),(111,5,65,'1','1','1','1'),(112,5,73,'1','1','1','1'),(113,5,68,'1','1','1','1'),(114,5,70,'1','1','1','1'),(115,5,262,'1','1','1','1'),(116,5,157,'1','1','1','1'),(117,5,159,'1','1','1','1'),(118,5,163,'1','1','1','1'),(119,5,165,'1','1','1','1'),(120,5,295,'-1','-1','-1','-1'),(121,5,323,'1','1','1','1'),(122,5,324,'-1','-1','-1','-1'),(123,5,325,'-1','-1','-1','-1');

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
INSERT INTO `sys_parameters` VALUES (NULL, 'system.version', '3.0.0', 'S', 'System version', 'A', 1, NOW(), 1, NOW()),
(NULL, 'display.login.header', '', 'S', 'Login Info Header', 'A', 0, NOW(), 0, NOW()),
(NULL, 'display.login.footer', '', 'S', 'Login Info Footer', 'A', 0, NOW(), 0, NOW());

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
