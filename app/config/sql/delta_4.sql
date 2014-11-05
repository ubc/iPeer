CREATE INDEX user_id_index on user_enrols(user_id);
ALTER TABLE `personalizes` ADD INDEX ( `user_id` , `attribute_code` );

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

INSERT INTO `acos` VALUES (1,NULL,NULL,NULL,'adminpage',1,2),(2,NULL,NULL,NULL,'controllers',3,574),(3,2,NULL,NULL,'Pages',4,17),(4,3,NULL,NULL,'display',5,6),(5,3,NULL,NULL,'add',7,8),(6,3,NULL,NULL,'edit',9,10),(7,3,NULL,NULL,'index',11,12),(8,3,NULL,NULL,'view',13,14),(9,3,NULL,NULL,'delete',15,16),(10,2,NULL,NULL,'Courses',18,35),(11,10,NULL,NULL,'daysLate',19,20),(12,10,NULL,NULL,'index',21,22),(13,10,NULL,NULL,'ajaxList',23,24),(14,10,NULL,NULL,'view',25,26),(15,10,NULL,NULL,'home',27,28),(16,10,NULL,NULL,'add',29,30),(17,10,NULL,NULL,'edit',31,32),(18,10,NULL,NULL,'delete',33,34),(19,2,NULL,NULL,'Departments',36,47),(20,19,NULL,NULL,'index',37,38),(21,19,NULL,NULL,'view',39,40),(22,19,NULL,NULL,'add',41,42),(23,19,NULL,NULL,'edit',43,44),(24,19,NULL,NULL,'delete',45,46),(25,2,NULL,NULL,'Emailer',48,75),(26,25,NULL,NULL,'setUpAjaxList',49,50),(27,25,NULL,NULL,'ajaxList',51,52),(28,25,NULL,NULL,'index',53,54),(29,25,NULL,NULL,'write',55,56),(30,25,NULL,NULL,'cancel',57,58),(31,25,NULL,NULL,'view',59,60),(32,25,NULL,NULL,'addRecipient',61,62),(33,25,NULL,NULL,'deleteRecipient',63,64),(34,25,NULL,NULL,'getRecipient',65,66),(35,25,NULL,NULL,'searchByUserId',67,68),(36,25,NULL,NULL,'add',69,70),(37,25,NULL,NULL,'edit',71,72),(38,25,NULL,NULL,'delete',73,74),(39,2,NULL,NULL,'Emailtemplates',76,95),(40,39,NULL,NULL,'setUpAjaxList',77,78),(41,39,NULL,NULL,'ajaxList',79,80),(42,39,NULL,NULL,'index',81,82),(43,39,NULL,NULL,'add',83,84),(44,39,NULL,NULL,'edit',85,86),(45,39,NULL,NULL,'delete',87,88),(46,39,NULL,NULL,'view',89,90),(47,39,NULL,NULL,'displayTemplateContent',91,92),(48,39,NULL,NULL,'displayTemplateSubject',93,94),(49,2,NULL,NULL,'Evaltools',96,109),(50,49,NULL,NULL,'index',97,98),(51,49,NULL,NULL,'showAll',99,100),(52,49,NULL,NULL,'add',101,102),(53,49,NULL,NULL,'edit',103,104),(54,49,NULL,NULL,'view',105,106),(55,49,NULL,NULL,'delete',107,108),(56,2,NULL,NULL,'Evaluations',110,161),(57,56,NULL,NULL,'setUpAjaxList',111,112),(58,56,NULL,NULL,'ajaxList',113,114),(59,56,NULL,NULL,'view',115,116),(60,56,NULL,NULL,'index',117,118),(61,56,NULL,NULL,'export',119,120),(62,56,NULL,NULL,'makeEvaluation',121,122),(63,56,NULL,NULL,'validSurveyEvalComplete',123,124),(64,56,NULL,NULL,'validRubricEvalComplete',125,126),(65,56,NULL,NULL,'completeEvaluationRubric',127,128),(66,56,NULL,NULL,'validMixevalEvalComplete',129,130),(67,56,NULL,NULL,'completeEvaluationMixeval',131,132),(68,56,NULL,NULL,'viewEvaluationResults',133,134),(69,56,NULL,NULL,'studentViewEvaluationResult',135,136),(70,56,NULL,NULL,'markEventReviewed',137,138),(71,56,NULL,NULL,'markGradeRelease',139,140),(72,56,NULL,NULL,'markCommentRelease',141,142),(73,56,NULL,NULL,'changeAllCommentRelease',143,144),(74,56,NULL,NULL,'changeAllGradeRelease',145,146),(75,56,NULL,NULL,'viewGroupSubmissionDetails',147,148),(76,56,NULL,NULL,'viewSurveySummary',149,150),(77,56,NULL,NULL,'export_rubic',151,152),(78,56,NULL,NULL,'export_test',153,154),(79,56,NULL,NULL,'add',155,156),(80,56,NULL,NULL,'edit',157,158),(81,56,NULL,NULL,'delete',159,160),(82,2,NULL,NULL,'Events',162,179),(83,82,NULL,NULL,'postProcessData',163,164),(84,82,NULL,NULL,'setUpAjaxList',165,166),(85,82,NULL,NULL,'index',167,168),(86,82,NULL,NULL,'ajaxList',169,170),(87,82,NULL,NULL,'view',171,172),(88,82,NULL,NULL,'add',173,174),(89,82,NULL,NULL,'edit',175,176),(90,82,NULL,NULL,'delete',177,178),(91,2,NULL,NULL,'Faculties',180,191),(92,91,NULL,NULL,'index',181,182),(93,91,NULL,NULL,'view',183,184),(94,91,NULL,NULL,'add',185,186),(95,91,NULL,NULL,'edit',187,188),(96,91,NULL,NULL,'delete',189,190),(97,2,NULL,NULL,'Framework',192,209),(98,97,NULL,NULL,'calendarDisplay',193,194),(99,97,NULL,NULL,'userInfoDisplay',195,196),(100,97,NULL,NULL,'tutIndex',197,198),(101,97,NULL,NULL,'add',199,200),(102,97,NULL,NULL,'edit',201,202),(103,97,NULL,NULL,'index',203,204),(104,97,NULL,NULL,'view',205,206),(105,97,NULL,NULL,'delete',207,208),(106,2,NULL,NULL,'Groups',210,229),(107,106,NULL,NULL,'setUpAjaxList',211,212),(108,106,NULL,NULL,'index',213,214),(109,106,NULL,NULL,'ajaxList',215,216),(110,106,NULL,NULL,'view',217,218),(111,106,NULL,NULL,'add',219,220),(112,106,NULL,NULL,'edit',221,222),(113,106,NULL,NULL,'delete',223,224),(114,106,NULL,NULL,'import',225,226),(115,106,NULL,NULL,'export',227,228),(116,2,NULL,NULL,'Home',230,241),(117,116,NULL,NULL,'index',231,232),(118,116,NULL,NULL,'add',233,234),(119,116,NULL,NULL,'edit',235,236),(120,116,NULL,NULL,'view',237,238),(121,116,NULL,NULL,'delete',239,240),(122,2,NULL,NULL,'Install',242,263),(123,122,NULL,NULL,'index',243,244),(124,122,NULL,NULL,'install2',245,246),(125,122,NULL,NULL,'install3',247,248),(126,122,NULL,NULL,'install4',249,250),(127,122,NULL,NULL,'install5',251,252),(128,122,NULL,NULL,'gpl',253,254),(129,122,NULL,NULL,'add',255,256),(130,122,NULL,NULL,'edit',257,258),(131,122,NULL,NULL,'view',259,260),(132,122,NULL,NULL,'delete',261,262),(133,2,NULL,NULL,'Lti',264,275),(134,133,NULL,NULL,'index',265,266),(135,133,NULL,NULL,'add',267,268),(136,133,NULL,NULL,'edit',269,270),(137,133,NULL,NULL,'view',271,272),(138,133,NULL,NULL,'delete',273,274),(139,2,NULL,NULL,'Mixevals',276,297),(140,139,NULL,NULL,'setUpAjaxList',277,278),(141,139,NULL,NULL,'index',279,280),(142,139,NULL,NULL,'ajaxList',281,282),(143,139,NULL,NULL,'view',283,284),(144,139,NULL,NULL,'add',285,286),(145,139,NULL,NULL,'deleteQuestion',287,288),(146,139,NULL,NULL,'deleteDescriptor',289,290),(147,139,NULL,NULL,'edit',291,292),(148,139,NULL,NULL,'copy',293,294),(149,139,NULL,NULL,'delete',295,296),(150,2,NULL,NULL,'Oauthclients',298,309),(151,150,NULL,NULL,'index',299,300),(152,150,NULL,NULL,'add',301,302),(153,150,NULL,NULL,'edit',303,304),(154,150,NULL,NULL,'delete',305,306),(155,150,NULL,NULL,'view',307,308),(156,2,NULL,NULL,'Oauthtokens',310,321),(157,156,NULL,NULL,'index',311,312),(158,156,NULL,NULL,'add',313,314),(159,156,NULL,NULL,'edit',315,316),(160,156,NULL,NULL,'delete',317,318),(161,156,NULL,NULL,'view',319,320),(162,2,NULL,NULL,'Penalty',322,335),(163,162,NULL,NULL,'save',323,324),(164,162,NULL,NULL,'add',325,326),(165,162,NULL,NULL,'edit',327,328),(166,162,NULL,NULL,'index',329,330),(167,162,NULL,NULL,'view',331,332),(168,162,NULL,NULL,'delete',333,334),(169,2,NULL,NULL,'Rubrics',336,355),(170,169,NULL,NULL,'postProcess',337,338),(171,169,NULL,NULL,'setUpAjaxList',339,340),(172,169,NULL,NULL,'index',341,342),(173,169,NULL,NULL,'ajaxList',343,344),(174,169,NULL,NULL,'view',345,346),(175,169,NULL,NULL,'add',347,348),(176,169,NULL,NULL,'edit',349,350),(177,169,NULL,NULL,'copy',351,352),(178,169,NULL,NULL,'delete',353,354),(179,2,NULL,NULL,'Searchs',356,383),(180,179,NULL,NULL,'update',357,358),(181,179,NULL,NULL,'index',359,360),(182,179,NULL,NULL,'searchEvaluation',361,362),(183,179,NULL,NULL,'searchResult',363,364),(184,179,NULL,NULL,'searchInstructor',365,366),(185,179,NULL,NULL,'eventBoxSearch',367,368),(186,179,NULL,NULL,'formatSearchEvaluation',369,370),(187,179,NULL,NULL,'formatSearchInstructor',371,372),(188,179,NULL,NULL,'formatSearchEvaluationResult',373,374),(189,179,NULL,NULL,'add',375,376),(190,179,NULL,NULL,'edit',377,378),(191,179,NULL,NULL,'view',379,380),(192,179,NULL,NULL,'delete',381,382),(193,2,NULL,NULL,'Simpleevaluations',384,403),(194,193,NULL,NULL,'postProcess',385,386),(195,193,NULL,NULL,'setUpAjaxList',387,388),(196,193,NULL,NULL,'index',389,390),(197,193,NULL,NULL,'ajaxList',391,392),(198,193,NULL,NULL,'view',393,394),(199,193,NULL,NULL,'add',395,396),(200,193,NULL,NULL,'edit',397,398),(201,193,NULL,NULL,'copy',399,400),(202,193,NULL,NULL,'delete',401,402),(203,2,NULL,NULL,'Surveygroups',404,433),(204,203,NULL,NULL,'postProcess',405,406),(205,203,NULL,NULL,'setUpAjaxList',407,408),(206,203,NULL,NULL,'index',409,410),(207,203,NULL,NULL,'ajaxList',411,412),(208,203,NULL,NULL,'makegroups',413,414),(209,203,NULL,NULL,'makegroupssearch',415,416),(210,203,NULL,NULL,'maketmgroups',417,418),(211,203,NULL,NULL,'savegroups',419,420),(212,203,NULL,NULL,'release',421,422),(213,203,NULL,NULL,'delete',423,424),(214,203,NULL,NULL,'edit',425,426),(215,203,NULL,NULL,'changegroupset',427,428),(216,203,NULL,NULL,'add',429,430),(217,203,NULL,NULL,'view',431,432),(218,2,NULL,NULL,'Surveys',434,461),(219,218,NULL,NULL,'setUpAjaxList',435,436),(220,218,NULL,NULL,'index',437,438),(221,218,NULL,NULL,'ajaxList',439,440),(222,218,NULL,NULL,'view',441,442),(223,218,NULL,NULL,'add',443,444),(224,218,NULL,NULL,'edit',445,446),(225,218,NULL,NULL,'copy',447,448),(226,218,NULL,NULL,'delete',449,450),(227,218,NULL,NULL,'questionsSummary',451,452),(228,218,NULL,NULL,'moveQuestion',453,454),(229,218,NULL,NULL,'removeQuestion',455,456),(230,218,NULL,NULL,'addQuestion',457,458),(231,218,NULL,NULL,'editQuestion',459,460),(232,2,NULL,NULL,'Sysparameters',462,477),(233,232,NULL,NULL,'setUpAjaxList',463,464),(234,232,NULL,NULL,'index',465,466),(235,232,NULL,NULL,'ajaxList',467,468),(236,232,NULL,NULL,'view',469,470),(237,232,NULL,NULL,'add',471,472),(238,232,NULL,NULL,'edit',473,474),(239,232,NULL,NULL,'delete',475,476),(240,2,NULL,NULL,'Upgrade',478,491),(241,240,NULL,NULL,'index',479,480),(242,240,NULL,NULL,'step2',481,482),(243,240,NULL,NULL,'add',483,484),(244,240,NULL,NULL,'edit',485,486),(245,240,NULL,NULL,'view',487,488),(246,240,NULL,NULL,'delete',489,490),(247,2,NULL,NULL,'Users',492,519),(248,247,NULL,NULL,'ajaxList',493,494),(249,247,NULL,NULL,'index',495,496),(250,247,NULL,NULL,'goToClassList',497,498),(251,247,NULL,NULL,'determineIfStudentFromThisData',499,500),(252,247,NULL,NULL,'view',501,502),(253,247,NULL,NULL,'add',503,504),(254,247,NULL,NULL,'edit',505,506),(255,247,NULL,NULL,'editProfile',507,508),(256,247,NULL,NULL,'delete',509,510),(257,247,NULL,NULL,'checkDuplicateName',511,512),(258,247,NULL,NULL,'resetPassword',513,514),(259,247,NULL,NULL,'import',515,516),(260,247,NULL,NULL,'update',517,518),(261,2,NULL,NULL,'V1',520,555),(262,261,NULL,NULL,'oauth',521,522),(263,261,NULL,NULL,'oauth_error',523,524),(264,261,NULL,NULL,'users',525,526),(265,261,NULL,NULL,'courses',527,528),(266,261,NULL,NULL,'groups',529,530),(267,261,NULL,NULL,'groupMembers',531,532),(268,261,NULL,NULL,'events',533,534),(269,261,NULL,NULL,'grades',535,536),(270,261,NULL,NULL,'departments',537,538),(271,261,NULL,NULL,'courseDepartments',539,540),(272,261,NULL,NULL,'userEvents',541,542),(273,261,NULL,NULL,'enrolment',543,544),(274,261,NULL,NULL,'add',545,546),(275,261,NULL,NULL,'edit',547,548),(276,261,NULL,NULL,'index',549,550),(277,261,NULL,NULL,'view',551,552),(278,261,NULL,NULL,'delete',553,554),(279,2,NULL,NULL,'Guard',556,573),(280,279,NULL,NULL,'Guard',557,572),(281,280,NULL,NULL,'login',558,559),(282,280,NULL,NULL,'logout',560,561),(283,280,NULL,NULL,'add',562,563),(284,280,NULL,NULL,'edit',564,565),(285,280,NULL,NULL,'index',566,567),(286,280,NULL,NULL,'view',568,569),(287,280,NULL,NULL,'delete',570,571),(288,NULL,NULL,NULL,'functions',575,638),(289,288,NULL,NULL,'user',576,603),(290,289,NULL,NULL,'superadmin',577,578),(291,289,NULL,NULL,'admin',579,580),(292,289,NULL,NULL,'instructor',581,582),(293,289,NULL,NULL,'tutor',583,584),(294,289,NULL,NULL,'student',585,586),(295,289,NULL,NULL,'import',587,588),(296,289,NULL,NULL,'password_reset',589,600),(297,296,NULL,NULL,'superadmin',590,591),(298,296,NULL,NULL,'admin',592,593),(299,296,NULL,NULL,'instructor',594,595),(300,296,NULL,NULL,'tutor',596,597),(301,296,NULL,NULL,'student',598,599),(302,289,NULL,NULL,'index',601,602),(303,288,NULL,NULL,'role',604,615),(304,303,NULL,NULL,'superadmin',605,606),(305,303,NULL,NULL,'admin',607,608),(306,303,NULL,NULL,'instructor',609,610),(307,303,NULL,NULL,'tutor',611,612),(308,303,NULL,NULL,'student',613,614),(309,288,NULL,NULL,'evaluation',616,617),(310,288,NULL,NULL,'email',618,625),(311,310,NULL,NULL,'allUsers',619,620),(312,310,NULL,NULL,'allGroups',621,622),(313,310,NULL,NULL,'allCourses',623,624),(314,288,NULL,NULL,'emailtemplate',626,627),(315,288,NULL,NULL,'viewstudentresults',628,629),(316,288,NULL,NULL,'viewemailaddresses',630,631),(317,288,NULL,NULL,'superadmin',632,633),(318,288,NULL,NULL,'coursemanager',634,635),(319,288,NULL,NULL,'viewusername',636,637);

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

INSERT INTO `aros_acos` VALUES (1,1,2,'1','1','1','1'),(2,1,288,'1','1','1','1'),(3,1,1,'1','1','1','1'),(4,2,2,'-1','-1','-1','-1'),(5,2,116,'1','1','1','1'),(6,2,10,'1','1','1','1'),(7,2,19,'1','1','1','1'),(8,2,25,'1','1','1','1'),(9,2,39,'1','1','1','1'),(10,2,49,'1','1','1','1'),(11,2,56,'1','1','1','1'),(12,2,82,'1','1','1','1'),(13,2,106,'1','1','1','1'),(14,2,139,'1','1','1','1'),(15,2,169,'1','1','1','1'),(16,2,193,'1','1','1','1'),(17,2,218,'1','1','1','1'),(18,2,203,'1','1','1','1'),(19,2,247,'1','1','1','1'),(20,2,282,'1','1','1','1'),(21,2,152,'1','1','1','1'),(22,2,154,'1','1','1','1'),(23,2,158,'1','1','1','1'),(24,2,160,'1','1','1','1'),(25,2,288,'-1','-1','-1','-1'),(26,2,314,'1','1','1','1'),(27,2,309,'1','1','1','1'),(28,2,311,'1','1','1','1'),(29,2,289,'1','1','1','1'),(30,2,291,'1','1','1','-1'),(31,2,290,'-1','-1','-1','-1'),(32,2,316,'1','1','1','1'),(33,2,319,'1','1','1','1'),(34,2,318,'1','1','1','1'),(35,2,317,'-1','-1','-1','-1'),(36,3,2,'-1','-1','-1','-1'),(37,3,116,'1','1','1','1'),(38,3,10,'1','1','1','1'),(39,3,25,'1','1','1','1'),(40,3,39,'1','1','1','1'),(41,3,49,'1','1','1','1'),(42,3,56,'1','1','1','1'),(43,3,82,'1','1','1','1'),(44,3,106,'1','1','1','1'),(45,3,139,'1','1','1','1'),(46,3,169,'1','1','1','1'),(47,3,193,'1','1','1','1'),(48,3,218,'1','1','1','1'),(49,3,203,'1','1','1','1'),(50,3,247,'1','1','1','1'),(51,3,282,'1','1','1','1'),(52,3,152,'1','1','1','1'),(53,3,154,'1','1','1','1'),(54,3,158,'1','1','1','1'),(55,3,160,'1','1','1','1'),(56,3,288,'-1','-1','-1','-1'),(57,3,309,'1','1','-1','-1'),(58,3,289,'1','1','1','1'),(59,3,291,'-1','-1','-1','-1'),(60,3,290,'-1','-1','-1','-1'),(61,3,292,'-1','1','-1','-1'),(62,3,302,'-1','-1','-1','-1'),(63,3,316,'-1','-1','-1','-1'),(64,3,317,'-1','-1','-1','-1'),(65,3,318,'1','1','1','1'),(66,4,2,'-1','-1','-1','-1'),(67,4,116,'1','1','1','1'),(68,4,10,'-1','-1','-1','-1'),(69,4,25,'-1','-1','-1','-1'),(70,4,39,'-1','-1','-1','-1'),(71,4,49,'-1','-1','-1','-1'),(72,4,82,'-1','-1','-1','-1'),(73,4,106,'-1','-1','-1','-1'),(74,4,139,'-1','-1','-1','-1'),(75,4,169,'-1','-1','-1','-1'),(76,4,193,'-1','-1','-1','-1'),(77,4,218,'-1','-1','-1','-1'),(78,4,203,'-1','-1','-1','-1'),(79,4,247,'-1','-1','-1','-1'),(80,4,282,'1','1','1','1'),(81,4,62,'1','1','1','1'),(82,4,69,'1','1','1','1'),(83,4,65,'1','1','1','1'),(84,4,67,'1','1','1','1'),(85,4,255,'1','1','1','1'),(86,4,288,'-1','-1','-1','-1'),(87,4,316,'-1','-1','-1','-1'),(88,4,317,'-1','-1','-1','-1'),(89,5,2,'-1','-1','-1','-1'),(90,5,116,'1','1','1','1'),(91,5,10,'-1','-1','-1','-1'),(92,5,25,'-1','-1','-1','-1'),(93,5,39,'-1','-1','-1','-1'),(94,5,49,'-1','-1','-1','-1'),(95,5,82,'-1','-1','-1','-1'),(96,5,106,'-1','-1','-1','-1'),(97,5,139,'-1','-1','-1','-1'),(98,5,169,'-1','-1','-1','-1'),(99,5,193,'-1','-1','-1','-1'),(100,5,218,'-1','-1','-1','-1'),(101,5,203,'-1','-1','-1','-1'),(102,5,247,'-1','-1','-1','-1'),(103,5,282,'1','1','1','1'),(104,5,62,'1','1','1','1'),(105,5,69,'1','1','1','1'),(106,5,65,'1','1','1','1'),(107,5,67,'1','1','1','1'),(108,5,255,'1','1','1','1'),(109,5,152,'1','1','1','1'),(110,5,154,'1','1','1','1'),(111,5,158,'1','1','1','1'),(112,5,160,'1','1','1','1'),(113,5,288,'-1','-1','-1','-1'),(114,5,315,'1','1','1','1'),(115,5,316,'-1','-1','-1','-1'),(116,5,317,'-1','-1','-1','-1');

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
UPDATE `event_template_types` SET  `display_for_selection` =  '1' WHERE  `event_template_types`.`id` =3;

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
ALTER TABLE `survey_inputs` CHANGE  `survey_id`  `event_id` INT( 11 ) NOT NULL DEFAULT  '0';

ALTER TABLE `survey_questions` CHARACTER SET = utf8 , CHANGE COLUMN `number` `number` INT(11) NOT NULL DEFAULT '9999'  ;

ALTER TABLE `surveys` CHANGE  `created`  `created` DATETIME NULL DEFAULT NULL, CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `surveys` CHARACTER SET = utf8 , CHANGE COLUMN `name` `name` VARCHAR(255) NOT NULL  ;
ALTER TABLE `surveys` ADD  `availability` VARCHAR( 10 ) NOT NULL DEFAULT  'public' AFTER  `name`;

INSERT INTO `sys_parameters` VALUES (NULL, 'system.version', '3.0.0', 'S', 'System version', 'A', 1, NOW(), 1, NOW()),
(NULL, 'display.login.header', '', 'S', 'Login Info Header', 'A', 0, NOW(), 0, NOW()),
(NULL, 'display.login.footer', '', 'S', 'Login Info Footer', 'A', 0, NOW(), 0, NOW()),
(NULL, 'display.vocabulary.department', 'Department', 'S', 'Department vocabulary', 'A', 0, NOW(), 0, NOW());

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

ALTER TABLE  `evaluation_rubrics` CHANGE  `general_comment`  `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE  `evaluation_simples` CHANGE  `eval_comment`  `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

DROP TABLE IF EXISTS `sys_functions` ;

-- some house clean up
DELETE FROM `group_events` WHERE `group_id` = 0;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
