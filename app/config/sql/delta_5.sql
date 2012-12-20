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

INSERT INTO `acos` VALUES (1,NULL,NULL,NULL,'adminpage',1,2),(2,NULL,NULL,NULL,'controllers',3,580),(3,2,NULL,NULL,'Pages',4,17),(4,3,NULL,NULL,'display',5,6),(5,3,NULL,NULL,'add',7,8),(6,3,NULL,NULL,'edit',9,10),(7,3,NULL,NULL,'index',11,12),(8,3,NULL,NULL,'view',13,14),(9,3,NULL,NULL,'delete',15,16),(10,2,NULL,NULL,'Courses',18,35),(11,10,NULL,NULL,'daysLate',19,20),(12,10,NULL,NULL,'index',21,22),(13,10,NULL,NULL,'ajaxList',23,24),(14,10,NULL,NULL,'view',25,26),(15,10,NULL,NULL,'home',27,28),(16,10,NULL,NULL,'add',29,30),(17,10,NULL,NULL,'edit',31,32),(18,10,NULL,NULL,'delete',33,34),(19,2,NULL,NULL,'Departments',36,47),(20,19,NULL,NULL,'index',37,38),(21,19,NULL,NULL,'view',39,40),(22,19,NULL,NULL,'add',41,42),(23,19,NULL,NULL,'edit',43,44),(24,19,NULL,NULL,'delete',45,46),(25,2,NULL,NULL,'Emailer',48,75),(26,25,NULL,NULL,'setUpAjaxList',49,50),(27,25,NULL,NULL,'ajaxList',51,52),(28,25,NULL,NULL,'index',53,54),(29,25,NULL,NULL,'write',55,56),(30,25,NULL,NULL,'cancel',57,58),(31,25,NULL,NULL,'view',59,60),(32,25,NULL,NULL,'addRecipient',61,62),(33,25,NULL,NULL,'deleteRecipient',63,64),(34,25,NULL,NULL,'getRecipient',65,66),(35,25,NULL,NULL,'searchByUserId',67,68),(36,25,NULL,NULL,'add',69,70),(37,25,NULL,NULL,'edit',71,72),(38,25,NULL,NULL,'delete',73,74),(39,2,NULL,NULL,'Emailtemplates',76,95),(40,39,NULL,NULL,'setUpAjaxList',77,78),(41,39,NULL,NULL,'ajaxList',79,80),(42,39,NULL,NULL,'index',81,82),(43,39,NULL,NULL,'add',83,84),(44,39,NULL,NULL,'edit',85,86),(45,39,NULL,NULL,'delete',87,88),(46,39,NULL,NULL,'view',89,90),(47,39,NULL,NULL,'displayTemplateContent',91,92),(48,39,NULL,NULL,'displayTemplateSubject',93,94),(49,2,NULL,NULL,'Evaltools',96,109),(50,49,NULL,NULL,'index',97,98),(51,49,NULL,NULL,'showAll',99,100),(52,49,NULL,NULL,'add',101,102),(53,49,NULL,NULL,'edit',103,104),(54,49,NULL,NULL,'view',105,106),(55,49,NULL,NULL,'delete',107,108),(56,2,NULL,NULL,'Evaluations',110,165),(57,56,NULL,NULL,'postProcess',111,112),(58,56,NULL,NULL,'setUpAjaxList',113,114),(59,56,NULL,NULL,'ajaxList',115,116),(60,56,NULL,NULL,'view',117,118),(61,56,NULL,NULL,'index',119,120),(62,56,NULL,NULL,'export',121,122),(63,56,NULL,NULL,'makeEvaluation',123,124),(64,56,NULL,NULL,'validSurveyEvalComplete',125,126),(65,56,NULL,NULL,'validRubricEvalComplete',127,128),(66,56,NULL,NULL,'completeEvaluationRubric',129,130),(67,56,NULL,NULL,'validMixevalEvalComplete',131,132),(68,56,NULL,NULL,'completeEvaluationMixeval',133,134),(69,56,NULL,NULL,'viewEvaluationResults',135,136),(70,56,NULL,NULL,'viewSurveyGroupEvaluationResults',137,138),(71,56,NULL,NULL,'studentViewEvaluationResult',139,140),(72,56,NULL,NULL,'markEventReviewed',141,142),(73,56,NULL,NULL,'markGradeRelease',143,144),(74,56,NULL,NULL,'markCommentRelease',145,146),(75,56,NULL,NULL,'changeAllCommentRelease',147,148),(76,56,NULL,NULL,'changeAllGradeRelease',149,150),(77,56,NULL,NULL,'viewGroupSubmissionDetails',151,152),(78,56,NULL,NULL,'viewSurveySummary',153,154),(79,56,NULL,NULL,'export_rubic',155,156),(80,56,NULL,NULL,'export_test',157,158),(81,56,NULL,NULL,'add',159,160),(82,56,NULL,NULL,'edit',161,162),(83,56,NULL,NULL,'delete',163,164),(84,2,NULL,NULL,'Events',166,183),(85,84,NULL,NULL,'postProcessData',167,168),(86,84,NULL,NULL,'setUpAjaxList',169,170),(87,84,NULL,NULL,'index',171,172),(88,84,NULL,NULL,'ajaxList',173,174),(89,84,NULL,NULL,'view',175,176),(90,84,NULL,NULL,'add',177,178),(91,84,NULL,NULL,'edit',179,180),(92,84,NULL,NULL,'delete',181,182),(93,2,NULL,NULL,'Faculties',184,195),(94,93,NULL,NULL,'index',185,186),(95,93,NULL,NULL,'view',187,188),(96,93,NULL,NULL,'add',189,190),(97,93,NULL,NULL,'edit',191,192),(98,93,NULL,NULL,'delete',193,194),(99,2,NULL,NULL,'Framework',196,213),(100,99,NULL,NULL,'calendarDisplay',197,198),(101,99,NULL,NULL,'userInfoDisplay',199,200),(102,99,NULL,NULL,'tutIndex',201,202),(103,99,NULL,NULL,'add',203,204),(104,99,NULL,NULL,'edit',205,206),(105,99,NULL,NULL,'index',207,208),(106,99,NULL,NULL,'view',209,210),(107,99,NULL,NULL,'delete',211,212),(108,2,NULL,NULL,'Groups',214,233),(109,108,NULL,NULL,'setUpAjaxList',215,216),(110,108,NULL,NULL,'index',217,218),(111,108,NULL,NULL,'ajaxList',219,220),(112,108,NULL,NULL,'view',221,222),(113,108,NULL,NULL,'add',223,224),(114,108,NULL,NULL,'edit',225,226),(115,108,NULL,NULL,'delete',227,228),(116,108,NULL,NULL,'import',229,230),(117,108,NULL,NULL,'export',231,232),(118,2,NULL,NULL,'Home',234,245),(119,118,NULL,NULL,'index',235,236),(120,118,NULL,NULL,'add',237,238),(121,118,NULL,NULL,'edit',239,240),(122,118,NULL,NULL,'view',241,242),(123,118,NULL,NULL,'delete',243,244),(124,2,NULL,NULL,'Install',246,267),(125,124,NULL,NULL,'index',247,248),(126,124,NULL,NULL,'install2',249,250),(127,124,NULL,NULL,'install3',251,252),(128,124,NULL,NULL,'install4',253,254),(129,124,NULL,NULL,'install5',255,256),(130,124,NULL,NULL,'gpl',257,258),(131,124,NULL,NULL,'add',259,260),(132,124,NULL,NULL,'edit',261,262),(133,124,NULL,NULL,'view',263,264),(134,124,NULL,NULL,'delete',265,266),(135,2,NULL,NULL,'Lti',268,279),(136,135,NULL,NULL,'index',269,270),(137,135,NULL,NULL,'add',271,272),(138,135,NULL,NULL,'edit',273,274),(139,135,NULL,NULL,'view',275,276),(140,135,NULL,NULL,'delete',277,278),(141,2,NULL,NULL,'Mixevals',280,301),(142,141,NULL,NULL,'setUpAjaxList',281,282),(143,141,NULL,NULL,'index',283,284),(144,141,NULL,NULL,'ajaxList',285,286),(145,141,NULL,NULL,'view',287,288),(146,141,NULL,NULL,'add',289,290),(147,141,NULL,NULL,'deleteQuestion',291,292),(148,141,NULL,NULL,'deleteDescriptor',293,294),(149,141,NULL,NULL,'edit',295,296),(150,141,NULL,NULL,'copy',297,298),(151,141,NULL,NULL,'delete',299,300),(152,2,NULL,NULL,'Oauthclients',302,313),(153,152,NULL,NULL,'index',303,304),(154,152,NULL,NULL,'add',305,306),(155,152,NULL,NULL,'edit',307,308),(156,152,NULL,NULL,'delete',309,310),(157,152,NULL,NULL,'view',311,312),(158,2,NULL,NULL,'Oauthtokens',314,325),(159,158,NULL,NULL,'index',315,316),(160,158,NULL,NULL,'add',317,318),(161,158,NULL,NULL,'edit',319,320),(162,158,NULL,NULL,'delete',321,322),(163,158,NULL,NULL,'view',323,324),(164,2,NULL,NULL,'Penalty',326,339),(165,164,NULL,NULL,'save',327,328),(166,164,NULL,NULL,'add',329,330),(167,164,NULL,NULL,'edit',331,332),(168,164,NULL,NULL,'index',333,334),(169,164,NULL,NULL,'view',335,336),(170,164,NULL,NULL,'delete',337,338),(171,2,NULL,NULL,'Rubrics',340,359),(172,171,NULL,NULL,'postProcess',341,342),(173,171,NULL,NULL,'setUpAjaxList',343,344),(174,171,NULL,NULL,'index',345,346),(175,171,NULL,NULL,'ajaxList',347,348),(176,171,NULL,NULL,'view',349,350),(177,171,NULL,NULL,'add',351,352),(178,171,NULL,NULL,'edit',353,354),(179,171,NULL,NULL,'copy',355,356),(180,171,NULL,NULL,'delete',357,358),(181,2,NULL,NULL,'Searchs',360,387),(182,181,NULL,NULL,'update',361,362),(183,181,NULL,NULL,'index',363,364),(184,181,NULL,NULL,'searchEvaluation',365,366),(185,181,NULL,NULL,'searchResult',367,368),(186,181,NULL,NULL,'searchInstructor',369,370),(187,181,NULL,NULL,'eventBoxSearch',371,372),(188,181,NULL,NULL,'formatSearchEvaluation',373,374),(189,181,NULL,NULL,'formatSearchInstructor',375,376),(190,181,NULL,NULL,'formatSearchEvaluationResult',377,378),(191,181,NULL,NULL,'add',379,380),(192,181,NULL,NULL,'edit',381,382),(193,181,NULL,NULL,'view',383,384),(194,181,NULL,NULL,'delete',385,386),(195,2,NULL,NULL,'Simpleevaluations',388,407),(196,195,NULL,NULL,'postProcess',389,390),(197,195,NULL,NULL,'setUpAjaxList',391,392),(198,195,NULL,NULL,'index',393,394),(199,195,NULL,NULL,'ajaxList',395,396),(200,195,NULL,NULL,'view',397,398),(201,195,NULL,NULL,'add',399,400),(202,195,NULL,NULL,'edit',401,402),(203,195,NULL,NULL,'copy',403,404),(204,195,NULL,NULL,'delete',405,406),(205,2,NULL,NULL,'Surveygroups',408,439),(206,205,NULL,NULL,'postProcess',409,410),(207,205,NULL,NULL,'setUpAjaxList',411,412),(208,205,NULL,NULL,'index',413,414),(209,205,NULL,NULL,'ajaxList',415,416),(210,205,NULL,NULL,'viewresult',417,418),(211,205,NULL,NULL,'makegroups',419,420),(212,205,NULL,NULL,'makegroupssearch',421,422),(213,205,NULL,NULL,'maketmgroups',423,424),(214,205,NULL,NULL,'savegroups',425,426),(215,205,NULL,NULL,'release',427,428),(216,205,NULL,NULL,'delete',429,430),(217,205,NULL,NULL,'edit',431,432),(218,205,NULL,NULL,'changegroupset',433,434),(219,205,NULL,NULL,'add',435,436),(220,205,NULL,NULL,'view',437,438),(221,2,NULL,NULL,'Surveys',440,467),(222,221,NULL,NULL,'setUpAjaxList',441,442),(223,221,NULL,NULL,'index',443,444),(224,221,NULL,NULL,'ajaxList',445,446),(225,221,NULL,NULL,'view',447,448),(226,221,NULL,NULL,'add',449,450),(227,221,NULL,NULL,'edit',451,452),(228,221,NULL,NULL,'copy',453,454),(229,221,NULL,NULL,'delete',455,456),(230,221,NULL,NULL,'questionsSummary',457,458),(231,221,NULL,NULL,'moveQuestion',459,460),(232,221,NULL,NULL,'removeQuestion',461,462),(233,221,NULL,NULL,'addQuestion',463,464),(234,221,NULL,NULL,'editQuestion',465,466),(235,2,NULL,NULL,'Sysparameters',468,483),(236,235,NULL,NULL,'setUpAjaxList',469,470),(237,235,NULL,NULL,'index',471,472),(238,235,NULL,NULL,'ajaxList',473,474),(239,235,NULL,NULL,'view',475,476),(240,235,NULL,NULL,'add',477,478),(241,235,NULL,NULL,'edit',479,480),(242,235,NULL,NULL,'delete',481,482),(243,2,NULL,NULL,'Upgrade',484,497),(244,243,NULL,NULL,'index',485,486),(245,243,NULL,NULL,'step2',487,488),(246,243,NULL,NULL,'add',489,490),(247,243,NULL,NULL,'edit',491,492),(248,243,NULL,NULL,'view',493,494),(249,243,NULL,NULL,'delete',495,496),(250,2,NULL,NULL,'Users',498,525),(251,250,NULL,NULL,'ajaxList',499,500),(252,250,NULL,NULL,'index',501,502),(253,250,NULL,NULL,'goToClassList',503,504),(254,250,NULL,NULL,'determineIfStudentFromThisData',505,506),(255,250,NULL,NULL,'view',507,508),(256,250,NULL,NULL,'add',509,510),(257,250,NULL,NULL,'edit',511,512),(258,250,NULL,NULL,'editProfile',513,514),(259,250,NULL,NULL,'delete',515,516),(260,250,NULL,NULL,'checkDuplicateName',517,518),(261,250,NULL,NULL,'resetPassword',519,520),(262,250,NULL,NULL,'import',521,522),(263,250,NULL,NULL,'update',523,524),(264,2,NULL,NULL,'V1',526,561),(265,264,NULL,NULL,'oauth',527,528),(266,264,NULL,NULL,'oauth_error',529,530),(267,264,NULL,NULL,'users',531,532),(268,264,NULL,NULL,'courses',533,534),(269,264,NULL,NULL,'groups',535,536),(270,264,NULL,NULL,'groupMembers',537,538),(271,264,NULL,NULL,'events',539,540),(272,264,NULL,NULL,'grades',541,542),(273,264,NULL,NULL,'departments',543,544),(274,264,NULL,NULL,'courseDepartments',545,546),(275,264,NULL,NULL,'userEvents',547,548),(276,264,NULL,NULL,'enrolment',549,550),(277,264,NULL,NULL,'add',551,552),(278,264,NULL,NULL,'edit',553,554),(279,264,NULL,NULL,'index',555,556),(280,264,NULL,NULL,'view',557,558),(281,264,NULL,NULL,'delete',559,560),(282,2,NULL,NULL,'Guard',562,579),(283,282,NULL,NULL,'Guard',563,578),(284,283,NULL,NULL,'login',564,565),(285,283,NULL,NULL,'logout',566,567),(286,283,NULL,NULL,'add',568,569),(287,283,NULL,NULL,'edit',570,571),(288,283,NULL,NULL,'index',572,573),(289,283,NULL,NULL,'view',574,575),(290,283,NULL,NULL,'delete',576,577),(291,NULL,NULL,NULL,'functions',581,646),(292,291,NULL,NULL,'user',582,609),(293,292,NULL,NULL,'superadmin',583,584),(294,292,NULL,NULL,'admin',585,586),(295,292,NULL,NULL,'instructor',587,588),(296,292,NULL,NULL,'tutor',589,590),(297,292,NULL,NULL,'student',591,592),(298,292,NULL,NULL,'import',593,594),(299,292,NULL,NULL,'password_reset',595,606),(300,299,NULL,NULL,'superadmin',596,597),(301,299,NULL,NULL,'admin',598,599),(302,299,NULL,NULL,'instructor',600,601),(303,299,NULL,NULL,'tutor',602,603),(304,299,NULL,NULL,'student',604,605),(305,292,NULL,NULL,'index',607,608),(306,291,NULL,NULL,'role',610,621),(307,306,NULL,NULL,'superadmin',611,612),(308,306,NULL,NULL,'admin',613,614),(309,306,NULL,NULL,'instructor',615,616),(310,306,NULL,NULL,'tutor',617,618),(311,306,NULL,NULL,'student',619,620),(312,291,NULL,NULL,'evaluation',622,625),(313,312,NULL,NULL,'export',623,624),(314,291,NULL,NULL,'email',626,633),(315,314,NULL,NULL,'allUsers',627,628),(316,314,NULL,NULL,'allGroups',629,630),(317,314,NULL,NULL,'allCourses',631,632),(318,291,NULL,NULL,'emailtemplate',634,635),(319,291,NULL,NULL,'viewstudentresults',636,637),(320,291,NULL,NULL,'viewemailaddresses',638,639),(321,291,NULL,NULL,'superadmin',640,641),(322,291,NULL,NULL,'coursemanager',642,643),(323,291,NULL,NULL,'viewusername',644,645);

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

INSERT INTO `aros_acos` VALUES (1,1,2,'1','1','1','1'),(2,1,291,'1','1','1','1'),(3,1,1,'1','1','1','1'),(4,2,2,'-1','-1','-1','-1'),(5,2,118,'1','1','1','1'),(6,2,10,'1','1','1','1'),(7,2,19,'1','1','1','1'),(8,2,25,'1','1','1','1'),(9,2,39,'1','1','1','1'),(10,2,49,'1','1','1','1'),(11,2,56,'1','1','1','1'),(12,2,84,'1','1','1','1'),(13,2,108,'1','1','1','1'),(14,2,141,'1','1','1','1'),(15,2,171,'1','1','1','1'),(16,2,195,'1','1','1','1'),(17,2,221,'1','1','1','1'),(18,2,205,'1','1','1','1'),(19,2,250,'1','1','1','1'),(20,2,285,'1','1','1','1'),(21,2,154,'1','1','1','1'),(22,2,156,'1','1','1','1'),(23,2,160,'1','1','1','1'),(24,2,162,'1','1','1','1'),(25,2,291,'-1','-1','-1','-1'),(26,2,318,'1','1','1','1'),(27,2,312,'1','1','1','1'),(28,2,315,'1','1','1','1'),(29,2,292,'1','1','1','1'),(30,2,294,'-1','1','-1','-1'),(31,2,293,'-1','-1','-1','-1'),(32,2,1,'1','1','1','1'),(33,2,320,'1','1','1','1'),(34,2,323,'1','1','1','1'),(35,2,322,'1','1','1','1'),(36,2,321,'-1','-1','-1','-1'),(37,3,2,'-1','-1','-1','-1'),(38,3,118,'1','1','1','1'),(39,3,10,'1','1','1','1'),(40,3,25,'1','1','1','1'),(41,3,39,'1','1','1','1'),(42,3,49,'1','1','1','1'),(43,3,56,'1','1','1','1'),(44,3,84,'1','1','1','1'),(45,3,108,'1','1','1','1'),(46,3,141,'1','1','1','1'),(47,3,171,'1','1','1','1'),(48,3,195,'1','1','1','1'),(49,3,221,'1','1','1','1'),(50,3,205,'1','1','1','1'),(51,3,250,'1','1','1','1'),(52,3,285,'1','1','1','1'),(53,3,154,'1','1','1','1'),(54,3,156,'1','1','1','1'),(55,3,160,'1','1','1','1'),(56,3,162,'1','1','1','1'),(57,3,291,'-1','-1','-1','-1'),(58,3,312,'1','1','-1','-1'),(59,3,292,'1','1','1','1'),(60,3,294,'-1','-1','-1','-1'),(61,3,293,'-1','-1','-1','-1'),(62,3,295,'-1','1','-1','-1'),(63,3,305,'-1','-1','-1','-1'),(64,3,320,'-1','-1','-1','-1'),(65,3,321,'-1','-1','-1','-1'),(66,3,322,'1','1','1','1'),(67,4,2,'-1','-1','-1','-1'),(68,4,118,'1','1','1','1'),(69,4,10,'-1','-1','-1','-1'),(70,4,25,'-1','-1','-1','-1'),(71,4,39,'-1','-1','-1','-1'),(72,4,49,'-1','-1','-1','-1'),(73,4,84,'-1','-1','-1','-1'),(74,4,108,'-1','-1','-1','-1'),(75,4,141,'-1','-1','-1','-1'),(76,4,171,'-1','-1','-1','-1'),(77,4,195,'-1','-1','-1','-1'),(78,4,221,'-1','-1','-1','-1'),(79,4,205,'-1','-1','-1','-1'),(80,4,250,'-1','-1','-1','-1'),(81,4,285,'1','1','1','1'),(82,4,63,'1','1','1','1'),(83,4,71,'1','1','1','1'),(84,4,66,'1','1','1','1'),(85,4,68,'1','1','1','1'),(86,4,258,'1','1','1','1'),(87,4,154,'1','1','1','1'),(88,4,156,'1','1','1','1'),(89,4,160,'1','1','1','1'),(90,4,162,'1','1','1','1'),(91,4,291,'-1','-1','-1','-1'),(92,4,320,'-1','-1','-1','-1'),(93,4,321,'-1','-1','-1','-1'),(94,5,2,'-1','-1','-1','-1'),(95,5,118,'1','1','1','1'),(96,5,10,'-1','-1','-1','-1'),(97,5,25,'-1','-1','-1','-1'),(98,5,39,'-1','-1','-1','-1'),(99,5,49,'-1','-1','-1','-1'),(100,5,84,'-1','-1','-1','-1'),(101,5,108,'-1','-1','-1','-1'),(102,5,141,'-1','-1','-1','-1'),(103,5,171,'-1','-1','-1','-1'),(104,5,195,'-1','-1','-1','-1'),(105,5,221,'-1','-1','-1','-1'),(106,5,205,'-1','-1','-1','-1'),(107,5,250,'-1','-1','-1','-1'),(108,5,285,'1','1','1','1'),(109,5,63,'1','1','1','1'),(110,5,71,'1','1','1','1'),(111,5,66,'1','1','1','1'),(112,5,68,'1','1','1','1'),(113,5,258,'1','1','1','1'),(114,5,154,'1','1','1','1'),(115,5,156,'1','1','1','1'),(116,5,160,'1','1','1','1'),(117,5,162,'1','1','1','1'),(118,5,291,'-1','-1','-1','-1'),(119,5,319,'1','1','1','1'),(120,5,320,'-1','-1','-1','-1'),(121,5,321,'-1','-1','-1','-1');

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
