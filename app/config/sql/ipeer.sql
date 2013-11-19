--
-- iPeer Database
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acos`
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

INSERT INTO acos (id, parent_id, model, foreign_key, alias, lft, rght) VALUES
(1,NULL,NULL,NULL,'adminpage',1,2),
(2,NULL,NULL,NULL,'controllers',3,594),
(3,2,NULL,NULL,'Pages',4,17),
(4,3,NULL,NULL,'display',5,6),
(5,3,NULL,NULL,'add',7,8),
(6,3,NULL,NULL,'edit',9,10),
(7,3,NULL,NULL,'index',11,12),
(8,3,NULL,NULL,'view',13,14),
(9,3,NULL,NULL,'delete',15,16),
(10,2,NULL,NULL,'Searchs',18,45),
(11,10,NULL,NULL,'update',19,20),
(12,10,NULL,NULL,'index',21,22),
(13,10,NULL,NULL,'searchEvaluation',23,24),
(14,10,NULL,NULL,'searchResult',25,26),
(15,10,NULL,NULL,'searchInstructor',27,28),
(16,10,NULL,NULL,'eventBoxSearch',29,30),
(17,10,NULL,NULL,'formatSearchEvaluation',31,32),
(18,10,NULL,NULL,'formatSearchInstructor',33,34),
(19,10,NULL,NULL,'formatSearchEvaluationResult',35,36),
(20,10,NULL,NULL,'add',37,38),
(21,10,NULL,NULL,'edit',39,40),
(22,10,NULL,NULL,'view',41,42),
(23,10,NULL,NULL,'delete',43,44),
(24,2,NULL,NULL,'Users',46,81),
(25,24,NULL,NULL,'ajaxList',47,48),
(26,24,NULL,NULL,'index',49,50),
(27,24,NULL,NULL,'goToClassList',51,52),
(28,24,NULL,NULL,'determineIfStudentFromThisData',53,54),
(29,24,NULL,NULL,'view',55,56),
(30,24,NULL,NULL,'add',57,58),
(31,24,NULL,NULL,'enrol',59,60),
(32,24,NULL,NULL,'edit',61,62),
(33,24,NULL,NULL,'editProfile',63,64),
(34,24,NULL,NULL,'delete',65,66),
(35,24,NULL,NULL,'checkDuplicateName',67,68),
(36,24,NULL,NULL,'resetPassword',69,70),
(37,24,NULL,NULL,'import',71,72),
(38,24,NULL,NULL,'merge',73,74),
(39,24,NULL,NULL,'ajax_merge',75,76),
(40,24,NULL,NULL,'update',77,78),
(41,24,NULL,NULL,'showEvents',79,80),
(42,2,NULL,NULL,'Oauthclients',82,93),
(43,42,NULL,NULL,'index',83,84),
(44,42,NULL,NULL,'add',85,86),
(45,42,NULL,NULL,'edit',87,88),
(46,42,NULL,NULL,'delete',89,90),
(47,42,NULL,NULL,'view',91,92),
(48,2,NULL,NULL,'Framework',94,109),
(49,48,NULL,NULL,'calendarDisplay',95,96),
(50,48,NULL,NULL,'tutIndex',97,98),
(51,48,NULL,NULL,'add',99,100),
(52,48,NULL,NULL,'edit',101,102),
(53,48,NULL,NULL,'index',103,104),
(54,48,NULL,NULL,'view',105,106),
(55,48,NULL,NULL,'delete',107,108),
(56,2,NULL,NULL,'Home',110,121),
(57,56,NULL,NULL,'index',111,112),
(58,56,NULL,NULL,'add',113,114),
(59,56,NULL,NULL,'edit',115,116),
(60,56,NULL,NULL,'view',117,118),
(61,56,NULL,NULL,'delete',119,120),
(62,2,NULL,NULL,'Accesses',122,133),
(63,62,NULL,NULL,'view',123,124),
(64,62,NULL,NULL,'edit',125,126),
(65,62,NULL,NULL,'add',127,128),
(66,62,NULL,NULL,'index',129,130),
(67,62,NULL,NULL,'delete',131,132),
(68,2,NULL,NULL,'Surveygroups',134,165),
(69,68,NULL,NULL,'postProcess',135,136),
(70,68,NULL,NULL,'setUpAjaxList',137,138),
(71,68,NULL,NULL,'index',139,140),
(72,68,NULL,NULL,'ajaxList',141,142),
(73,68,NULL,NULL,'makegroups',143,144),
(74,68,NULL,NULL,'makegroupssearch',145,146),
(75,68,NULL,NULL,'maketmgroups',147,148),
(76,68,NULL,NULL,'savegroups',149,150),
(77,68,NULL,NULL,'release',151,152),
(78,68,NULL,NULL,'delete',153,154),
(79,68,NULL,NULL,'edit',155,156),
(80,68,NULL,NULL,'changegroupset',157,158),
(81,68,NULL,NULL,'export',159,160),
(82,68,NULL,NULL,'add',161,162),
(83,68,NULL,NULL,'view',163,164),
(84,2,NULL,NULL,'Upgrade',166,179),
(85,84,NULL,NULL,'index',167,168),
(86,84,NULL,NULL,'step2',169,170),
(87,84,NULL,NULL,'add',171,172),
(88,84,NULL,NULL,'edit',173,174),
(89,84,NULL,NULL,'view',175,176),
(90,84,NULL,NULL,'delete',177,178),
(91,2,NULL,NULL,'Evaluations',180,219),
(92,91,NULL,NULL,'setUpAjaxList',181,182),
(93,91,NULL,NULL,'ajaxList',183,184),
(94,91,NULL,NULL,'view',185,186),
(95,91,NULL,NULL,'index',187,188),
(96,91,NULL,NULL,'export',189,190),
(97,91,NULL,NULL,'makeEvaluation',191,192),
(98,91,NULL,NULL,'completeEvaluationRubric',193,194),
(99,91,NULL,NULL,'viewEvaluationResults',195,196),
(100,91,NULL,NULL,'studentViewEvaluationResult',197,198),
(101,91,NULL,NULL,'markEventReviewed',199,200),
(102,91,NULL,NULL,'markGradeRelease',201,202),
(103,91,NULL,NULL,'markCommentRelease',203,204),
(104,91,NULL,NULL,'changeAllCommentRelease',205,206),
(105,91,NULL,NULL,'changeAllGradeRelease',207,208),
(106,91,NULL,NULL,'viewGroupSubmissionDetails',209,210),
(107,91,NULL,NULL,'viewSurveySummary',211,212),
(108,91,NULL,NULL,'add',213,214),
(109,91,NULL,NULL,'edit',215,216),
(110,91,NULL,NULL,'delete',217,218),
(111,2,NULL,NULL,'Oauthtokens',220,231),
(112,111,NULL,NULL,'index',221,222),
(113,111,NULL,NULL,'add',223,224),
(114,111,NULL,NULL,'edit',225,226),
(115,111,NULL,NULL,'delete',227,228),
(116,111,NULL,NULL,'view',229,230),
(117,2,NULL,NULL,'Evaltools',232,243),
(118,117,NULL,NULL,'index',233,234),
(119,117,NULL,NULL,'add',235,236),
(120,117,NULL,NULL,'edit',237,238),
(121,117,NULL,NULL,'view',239,240),
(122,117,NULL,NULL,'delete',241,242),
(123,2,NULL,NULL,'Penalty',244,257),
(124,123,NULL,NULL,'save',245,246),
(125,123,NULL,NULL,'add',247,248),
(126,123,NULL,NULL,'edit',249,250),
(127,123,NULL,NULL,'index',251,252),
(128,123,NULL,NULL,'view',253,254),
(129,123,NULL,NULL,'delete',255,256),
(130,2,NULL,NULL,'Groups',258,277),
(131,130,NULL,NULL,'setUpAjaxList',259,260),
(132,130,NULL,NULL,'index',261,262),
(133,130,NULL,NULL,'ajaxList',263,264),
(134,130,NULL,NULL,'view',265,266),
(135,130,NULL,NULL,'add',267,268),
(136,130,NULL,NULL,'edit',269,270),
(137,130,NULL,NULL,'delete',271,272),
(138,130,NULL,NULL,'import',273,274),
(139,130,NULL,NULL,'export',275,276),
(140,2,NULL,NULL,'Faculties',278,289),
(141,140,NULL,NULL,'index',279,280),
(142,140,NULL,NULL,'view',281,282),
(143,140,NULL,NULL,'add',283,284),
(144,140,NULL,NULL,'edit',285,286),
(145,140,NULL,NULL,'delete',287,288),
(146,2,NULL,NULL,'Rubrics',290,309),
(147,146,NULL,NULL,'postProcess',291,292),
(148,146,NULL,NULL,'setUpAjaxList',293,294),
(149,146,NULL,NULL,'index',295,296),
(150,146,NULL,NULL,'ajaxList',297,298),
(151,146,NULL,NULL,'view',299,300),
(152,146,NULL,NULL,'add',301,302),
(153,146,NULL,NULL,'edit',303,304),
(154,146,NULL,NULL,'copy',305,306),
(155,146,NULL,NULL,'delete',307,308),
(156,2,NULL,NULL,'Events',310,337),
(157,156,NULL,NULL,'postProcessData',311,312),
(158,156,NULL,NULL,'setUpAjaxList',313,314),
(159,156,NULL,NULL,'index',315,316),
(160,156,NULL,NULL,'ajaxList',317,318),
(161,156,NULL,NULL,'view',319,320),
(162,156,NULL,NULL,'add',321,322),
(163,156,NULL,NULL,'setSchedule',323,324),
(164,156,NULL,NULL,'getGroupMembers',325,326),
(165,156,NULL,NULL,'edit',327,328),
(166,156,NULL,NULL,'checkIfChanged',329,330),
(167,156,NULL,NULL,'calculateFrequency',331,332),
(168,156,NULL,NULL,'delete',333,334),
(169,156,NULL,NULL,'checkDuplicateName',335,336),
(170,2,NULL,NULL,'Simpleevaluations',338,357),
(171,170,NULL,NULL,'postProcess',339,340),
(172,170,NULL,NULL,'setUpAjaxList',341,342),
(173,170,NULL,NULL,'index',343,344),
(174,170,NULL,NULL,'ajaxList',345,346),
(175,170,NULL,NULL,'view',347,348),
(176,170,NULL,NULL,'add',349,350),
(177,170,NULL,NULL,'edit',351,352),
(178,170,NULL,NULL,'copy',353,354),
(179,170,NULL,NULL,'delete',355,356),
(180,2,NULL,NULL,'V1',358,393),
(181,180,NULL,NULL,'oauth',359,360),
(182,180,NULL,NULL,'oauth_error',361,362),
(183,180,NULL,NULL,'users',363,364),
(184,180,NULL,NULL,'courses',365,366),
(185,180,NULL,NULL,'groups',367,368),
(186,180,NULL,NULL,'groupMembers',369,370),
(187,180,NULL,NULL,'events',371,372),
(188,180,NULL,NULL,'grades',373,374),
(189,180,NULL,NULL,'departments',375,376),
(190,180,NULL,NULL,'courseDepartments',377,378),
(191,180,NULL,NULL,'userEvents',379,380),
(192,180,NULL,NULL,'enrolment',381,382),
(193,180,NULL,NULL,'add',383,384),
(194,180,NULL,NULL,'edit',385,386),
(195,180,NULL,NULL,'index',387,388),
(196,180,NULL,NULL,'view',389,390),
(197,180,NULL,NULL,'delete',391,392),
(198,2,NULL,NULL,'Departments',394,405),
(199,198,NULL,NULL,'index',395,396),
(200,198,NULL,NULL,'view',397,398),
(201,198,NULL,NULL,'add',399,400),
(202,198,NULL,NULL,'edit',401,402),
(203,198,NULL,NULL,'delete',403,404),
(204,2,NULL,NULL,'Sysparameters',406,421),
(205,204,NULL,NULL,'setUpAjaxList',407,408),
(206,204,NULL,NULL,'index',409,410),
(207,204,NULL,NULL,'ajaxList',411,412),
(208,204,NULL,NULL,'view',413,414),
(209,204,NULL,NULL,'add',415,416),
(210,204,NULL,NULL,'edit',417,418),
(211,204,NULL,NULL,'delete',419,420),
(212,2,NULL,NULL,'Mixevals',422,439),
(213,212,NULL,NULL,'setUpAjaxList',423,424),
(214,212,NULL,NULL,'index',425,426),
(215,212,NULL,NULL,'ajaxList',427,428),
(216,212,NULL,NULL,'view',429,430),
(217,212,NULL,NULL,'add',431,432),
(218,212,NULL,NULL,'edit',433,434),
(219,212,NULL,NULL,'copy',435,436),
(220,212,NULL,NULL,'delete',437,438),
(221,2,NULL,NULL,'Emailtemplates',440,459),
(222,221,NULL,NULL,'setUpAjaxList',441,442),
(223,221,NULL,NULL,'ajaxList',443,444),
(224,221,NULL,NULL,'index',445,446),
(225,221,NULL,NULL,'add',447,448),
(226,221,NULL,NULL,'edit',449,450),
(227,221,NULL,NULL,'delete',451,452),
(228,221,NULL,NULL,'view',453,454),
(229,221,NULL,NULL,'displayTemplateContent',455,456),
(230,221,NULL,NULL,'displayTemplateSubject',457,458),
(231,2,NULL,NULL,'Install',460,481),
(232,231,NULL,NULL,'index',461,462),
(233,231,NULL,NULL,'install2',463,464),
(234,231,NULL,NULL,'install3',465,466),
(235,231,NULL,NULL,'install4',467,468),
(236,231,NULL,NULL,'install5',469,470),
(237,231,NULL,NULL,'gpl',471,472),
(238,231,NULL,NULL,'add',473,474),
(239,231,NULL,NULL,'edit',475,476),
(240,231,NULL,NULL,'view',477,478),
(241,231,NULL,NULL,'delete',479,480),
(242,2,NULL,NULL,'Courses',482,505),
(243,242,NULL,NULL,'daysLate',483,484),
(244,242,NULL,NULL,'index',485,486),
(245,242,NULL,NULL,'ajaxList',487,488),
(246,242,NULL,NULL,'view',489,490),
(247,242,NULL,NULL,'home',491,492),
(248,242,NULL,NULL,'add',493,494),
(249,242,NULL,NULL,'edit',495,496),
(250,242,NULL,NULL,'delete',497,498),
(251,242,NULL,NULL,'move',499,500),
(252,242,NULL,NULL,'ajax_options',501,502),
(253,242,NULL,NULL,'import',503,504),
(254,2,NULL,NULL,'Surveys',506,535),
(255,254,NULL,NULL,'setUpAjaxList',507,508),
(256,254,NULL,NULL,'index',509,510),
(257,254,NULL,NULL,'ajaxList',511,512),
(258,254,NULL,NULL,'view',513,514),
(259,254,NULL,NULL,'add',515,516),
(260,254,NULL,NULL,'edit',517,518),
(261,254,NULL,NULL,'copy',519,520),
(262,254,NULL,NULL,'delete',521,522),
(263,254,NULL,NULL,'questionsSummary',523,524),
(264,254,NULL,NULL,'moveQuestion',525,526),
(265,254,NULL,NULL,'removeQuestion',527,528),
(266,254,NULL,NULL,'addQuestion',529,530),
(267,254,NULL,NULL,'editQuestion',531,532),
(268,254,NULL,NULL,'surveyAccess',533,534),
(269,2,NULL,NULL,'Lti',536,547),
(270,269,NULL,NULL,'index',537,538),
(271,269,NULL,NULL,'add',539,540),
(272,269,NULL,NULL,'edit',541,542),
(273,269,NULL,NULL,'view',543,544),
(274,269,NULL,NULL,'delete',545,546),
(275,2,NULL,NULL,'Emailer',548,575),
(276,275,NULL,NULL,'setUpAjaxList',549,550),
(277,275,NULL,NULL,'ajaxList',551,552),
(278,275,NULL,NULL,'index',553,554),
(279,275,NULL,NULL,'write',555,556),
(280,275,NULL,NULL,'cancel',557,558),
(281,275,NULL,NULL,'view',559,560),
(282,275,NULL,NULL,'addRecipient',561,562),
(283,275,NULL,NULL,'deleteRecipient',563,564),
(284,275,NULL,NULL,'getRecipient',565,566),
(285,275,NULL,NULL,'searchByUserId',567,568),
(286,275,NULL,NULL,'add',569,570),
(287,275,NULL,NULL,'edit',571,572),
(288,275,NULL,NULL,'delete',573,574),
(289,2,NULL,NULL,'Guard',576,593),
(290,289,NULL,NULL,'Guard',577,592),
(291,290,NULL,NULL,'login',578,579),
(292,290,NULL,NULL,'logout',580,581),
(293,290,NULL,NULL,'add',582,583),
(294,290,NULL,NULL,'edit',584,585),
(295,290,NULL,NULL,'index',586,587),
(296,290,NULL,NULL,'view',588,589),
(297,290,NULL,NULL,'delete',590,591),
(298,NULL,NULL,NULL,'functions',595,658),
(299,298,NULL,NULL,'user',596,623),
(300,299,NULL,NULL,'superadmin',597,598),
(301,299,NULL,NULL,'admin',599,600),
(302,299,NULL,NULL,'instructor',601,602),
(303,299,NULL,NULL,'tutor',603,604),
(304,299,NULL,NULL,'student',605,606),
(305,299,NULL,NULL,'import',607,608),
(306,299,NULL,NULL,'password_reset',609,620),
(307,306,NULL,NULL,'superadmin',610,611),
(308,306,NULL,NULL,'admin',612,613),
(309,306,NULL,NULL,'instructor',614,615),
(310,306,NULL,NULL,'tutor',616,617),
(311,306,NULL,NULL,'student',618,619),
(312,299,NULL,NULL,'index',621,622),
(313,298,NULL,NULL,'role',624,635),
(314,313,NULL,NULL,'superadmin',625,626),
(315,313,NULL,NULL,'admin',627,628),
(316,313,NULL,NULL,'instructor',629,630),
(317,313,NULL,NULL,'tutor',631,632),
(318,313,NULL,NULL,'student',633,634),
(319,298,NULL,NULL,'evaluation',636,637),
(320,298,NULL,NULL,'email',638,645),
(321,320,NULL,NULL,'allUsers',639,640),
(322,320,NULL,NULL,'allGroups',641,642),
(323,320,NULL,NULL,'allCourses',643,644),
(324,298,NULL,NULL,'emailtemplate',646,647),
(325,298,NULL,NULL,'viewstudentresults',648,649),
(326,298,NULL,NULL,'viewemailaddresses',650,651),
(327,298,NULL,NULL,'superadmin',652,653),
(328,298,NULL,NULL,'coursemanager',654,655),
(329,298,NULL,NULL,'viewusername',656,657);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aros_acos`
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


INSERT INTO aros_acos (id, aro_id, aco_id, _create, _read, _update, _delete) VALUES
(1,1,2,'1','1','1','1'),
(2,1,298,'1','1','1','1'),
(3,1,1,'1','1','1','1'),
(4,2,2,'-1','-1','-1','-1'),
(5,2,56,'1','1','1','1'),
(6,2,242,'1','1','1','1'),
(7,2,198,'1','1','1','1'),
(8,2,201,'-1','-1','-1','-1'),
(9,2,200,'-1','-1','-1','-1'),
(10,2,203,'-1','-1','-1','-1'),
(11,2,202,'-1','-1','-1','-1'),
(12,2,199,'-1','-1','-1','-1'),
(13,2,275,'1','1','1','1'),
(14,2,221,'1','1','1','1'),
(15,2,117,'1','1','1','1'),
(16,2,91,'1','1','1','1'),
(17,2,156,'1','1','1','1'),
(18,2,130,'1','1','1','1'),
(19,2,212,'1','1','1','1'),
(20,2,146,'1','1','1','1'),
(21,2,170,'1','1','1','1'),
(22,2,254,'1','1','1','1'),
(23,2,68,'1','1','1','1'),
(24,2,24,'1','1','1','1'),
(25,2,292,'1','1','1','1'),
(26,2,298,'-1','-1','-1','-1'),
(27,2,324,'1','1','1','1'),
(28,2,319,'1','1','1','1'),
(29,2,321,'1','1','1','1'),
(30,2,299,'1','1','1','1'),
(31,2,301,'1','1','1','-1'),
(32,2,300,'-1','-1','-1','-1'),
(33,2,326,'1','1','1','1'),
(34,2,329,'1','1','1','1'),
(35,2,328,'1','1','1','1'),
(36,2,327,'-1','-1','-1','-1'),
(37,3,2,'-1','-1','-1','-1'),
(38,3,56,'1','1','1','1'),
(39,3,242,'1','1','1','1'),
(40,3,275,'1','1','1','1'),
(41,3,221,'1','1','1','1'),
(42,3,117,'1','1','1','1'),
(43,3,91,'1','1','1','1'),
(44,3,156,'1','1','1','1'),
(45,3,130,'1','1','1','1'),
(46,3,212,'1','1','1','1'),
(47,3,146,'1','1','1','1'),
(48,3,170,'1','1','1','1'),
(49,3,254,'1','1','1','1'),
(50,3,68,'1','1','1','1'),
(51,3,24,'1','1','1','1'),
(52,3,292,'1','1','1','1'),
(53,3,44,'1','1','1','1'),
(54,3,46,'1','1','1','1'),
(55,3,113,'1','1','1','1'),
(56,3,115,'1','1','1','1'),
(57,3,38,'-1','-1','-1','-1'),
(58,3,41,'1','1','1','1'),
(59,3,298,'-1','-1','-1','-1'),
(60,3,319,'1','1','-1','-1'),
(61,3,299,'1','1','1','1'),
(62,3,301,'-1','-1','-1','-1'),
(63,3,300,'-1','-1','-1','-1'),
(64,3,302,'-1','1','-1','-1'),
(65,3,312,'-1','-1','-1','-1'),
(66,3,326,'-1','-1','-1','-1'),
(67,3,327,'-1','-1','-1','-1'),
(68,3,328,'1','1','1','1'),
(69,4,2,'-1','-1','-1','-1'),
(70,4,56,'1','1','1','1'),
(71,4,242,'-1','-1','-1','-1'),
(72,4,275,'-1','-1','-1','-1'),
(73,4,221,'-1','-1','-1','-1'),
(74,4,117,'-1','-1','-1','-1'),
(75,4,156,'-1','-1','-1','-1'),
(76,4,130,'-1','-1','-1','-1'),
(77,4,212,'-1','-1','-1','-1'),
(78,4,146,'-1','-1','-1','-1'),
(79,4,170,'-1','-1','-1','-1'),
(80,4,254,'-1','-1','-1','-1'),
(81,4,68,'-1','-1','-1','-1'),
(82,4,24,'-1','-1','-1','-1'),
(83,4,292,'1','1','1','1'),
(84,4,97,'1','1','1','1'),
(85,4,100,'1','1','1','1'),
(86,4,98,'1','1','1','1'),
(87,4,33,'1','1','1','1'),
(88,4,298,'-1','-1','-1','-1'),
(89,4,326,'-1','-1','-1','-1'),
(90,4,327,'-1','-1','-1','-1'),
(91,5,2,'-1','-1','-1','-1'),
(92,5,56,'1','1','1','1'),
(93,5,242,'-1','-1','-1','-1'),
(94,5,275,'-1','-1','-1','-1'),
(95,5,221,'-1','-1','-1','-1'),
(96,5,117,'-1','-1','-1','-1'),
(97,5,156,'-1','-1','-1','-1'),
(98,5,130,'-1','-1','-1','-1'),
(99,5,212,'-1','-1','-1','-1'),
(100,5,146,'-1','-1','-1','-1'),
(101,5,170,'-1','-1','-1','-1'),
(102,5,254,'-1','-1','-1','-1'),
(103,5,68,'-1','-1','-1','-1'),
(104,5,24,'-1','-1','-1','-1'),
(105,5,292,'1','1','1','1'),
(106,5,97,'1','1','1','1'),
(107,5,100,'1','1','1','1'),
(108,5,98,'1','1','1','1'),
(109,5,33,'1','1','1','1'),
(110,5,44,'1','1','1','1'),
(111,5,46,'1','1','1','1'),
(112,5,113,'1','1','1','1'),
(113,5,115,'1','1','1','1'),
(114,5,298,'-1','-1','-1','-1'),
(115,5,325,'1','1','1','1'),
(116,5,326,'-1','-1','-1','-1'),
(117,5,327,'-1','-1','-1','-1');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(80) NOT NULL default '',
  `title` varchar(80) default NULL,
  `homepage` varchar(100) default NULL,
  `self_enroll` varchar(3) default 'off',
  `password` varchar(25) default NULL,
  `record_status` varchar(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime,
  `updater_id` int(11) default NULL,
  `modified` datetime,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `course` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL DEFAULT '',
  `first_name` varchar(80) DEFAULT '',
  `last_name` varchar(80) DEFAULT '',
  `student_no` varchar(30) DEFAULT '',
  `title` varchar(80) DEFAULT '',
-- The maximum length for an email address is 254 chars by RFC3696 errata
  `email` varchar(254) DEFAULT '',
  `last_login` datetime,
  `last_logout` datetime,
  `last_accessed` varchar(10) DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  `lti_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE (`lti_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `first_name`, `last_name`, `student_no`, `title`, `email`, `last_login`, `last_logout`, `last_accessed`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`, `lti_id`) VALUES
('root', '', 'Super', 'Admin', NULL, NULL, '', NULL, NULL, NULL, 'A', 1, NOW(), NULL, NOW(), NULL);


-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

DROP TABLE IF EXISTS `faculties`;
CREATE TABLE IF NOT EXISTS `faculties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `course_departments`
--

DROP TABLE IF EXISTS `course_departments`;
CREATE TABLE `course_departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `department_id` int NOT NULL,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_merges`
--

INSERT INTO `email_merges` (`id`, `key`, `value`, `table_name`, `field_name`, `created`, `modified`) VALUES
(1, 'Username', '{{{USERNAME}}}', 'User', 'username', NOW(), NOW()),
(2, 'First Name', '{{{FIRSTNAME}}}', 'User', 'first_name', NOW(), NOW()),
(3, 'Last Name', '{{{LASTNAME}}}', 'User', 'last_name', NOW(), NOW()),
(4, 'Email Address', '{{{Email}}}', 'User', 'email', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `email_schedules`
--

DROP TABLE IF EXISTS `email_schedules`;
CREATE TABLE IF NOT EXISTS `email_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `date` datetime,
  `from` varchar(80) NOT NULL,
  `to` text NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `grp_id` int(11) DEFAULT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `creator_id` int(11) NOT NULL,
  `created` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `updated` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `email_templates` (`id`, `name`, `description`, `subject`, `content`, `availability`, `creator_id`, `created`, `updater_id`, `updated`) VALUES
(1, 'Submission Confirmation', 'template for submission confirmation', 'iPeer: Evaluation Submission Confirmation', 'Hi {{{FIRSTNAME}}}, \nYour evaluation has been submitted successfully. Thank you for your feedback!\n\n iPeer',1, 1, NOW(), NULL, NULL);

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evaluation_mixeval_id` (`evaluation_mixeval_id`,`question_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evaluation_rubric_id` (`evaluation_rubric_id`,`criteria_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_rubrics`
--

DROP TABLE IF EXISTS `evaluation_rubrics`;
CREATE TABLE IF NOT EXISTS `evaluation_rubrics` (
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  `rubric_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `comment` text,
  `release_status` int(1) NOT NULL DEFAULT '0',
  `grp_event_id` int(11) NOT NULL DEFAULT '0',
  `event_id` bigint(11) NOT NULL DEFAULT '0',
  `date_submitted` datetime,
  `grade_release` int(1) DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `date_submitted` datetime,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grp_event_id` (`grp_event_id`,`submitter_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`),
  UNIQUE KEY `table_name` (`table_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event_template_types`
--

INSERT INTO `event_template_types` (`id`, `type_name`, `table_name`, `model_name`, `display_for_selection`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
(1, 'SIMPLE', 'simple_evaluations', 'SimpleEvaluation', 1, 'A', 0, NOW(), NULL, NULL),
(2, 'RUBRIC', 'rubrics', 'Rubric', 1, 'A', 0, NOW(), NULL, NULL),
(3, 'SURVEY', 'surveys', '', 1, 'A', 0, NOW(), NULL, NULL),
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
  `self_eval` varchar(11) NOT NULL DEFAULT '0',
  `com_req` int(11) NOT NULL DEFAULT '0',
  `auto_release` int(11) NOT NULL DEFAULT '0',
  `enable_details` int(11) NOT NULL DEFAULT '1',
  `due_date` datetime,
  `release_date_begin` datetime,
  `release_date_end` datetime,
  `result_release_date_begin` datetime,
  `result_release_date_end` datetime,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`event_id`,`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  UNIQUE KEY `group_user` (`group_id`,`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mixevals`
--

DROP TABLE IF EXISTS `mixevals`;
CREATE TABLE IF NOT EXISTS `mixevals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `zero_mark` tinyint(1) NOT NULL DEFAULT '0',
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `mixeval_question_types`
--

DROP TABLE IF EXISTS `mixeval_question_types`;
CREATE TABLE IF NOT EXISTS `mixeval_question_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mixeval_question_types`
--

INSERT INTO `mixeval_question_types` (`id`, `type`) VALUES
(1, 'Likert'),
(2, 'Paragraph'),
(3, 'Sentence'),
(4, 'ScoreDropdown');

-- --------------------------------------------------------

--
-- Table structure for table `mixeval_questions`
--

DROP TABLE IF EXISTS `mixeval_questions`;
CREATE TABLE IF NOT EXISTS `mixeval_questions` (
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
  `show_marks` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`mixeval_question_type_id`) REFERENCES `mixeval_question_types` 
	(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`mixeval_id`) REFERENCES `mixevals` 
	(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mixeval_question_descs`
--

DROP TABLE IF EXISTS `mixeval_question_descs`;
CREATE TABLE IF NOT EXISTS `mixeval_question_descs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL DEFAULT '0',
  `scale_level` int(11) NOT NULL DEFAULT '0',
  `descriptor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`question_id`) REFERENCES `mixeval_questions` 
	(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updated` datetime,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`attribute_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` datetime,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `show_marks` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `survey_inputs`
--

DROP TABLE IF EXISTS `survey_inputs`;
CREATE TABLE IF NOT EXISTS `survey_inputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL DEFAULT '0',
  `response_text` text,
  `response_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

DROP TABLE IF EXISTS `surveys`;
CREATE TABLE IF NOT EXISTS `surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `due_date` datetime,
  `release_date_begin` datetime,
  `release_date_end` datetime,
  `released` tinyint(1) NOT NULL DEFAULT '0',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sys_parameters`
--

DROP TABLE IF EXISTS `sys_parameters`;
CREATE TABLE IF NOT EXISTS `sys_parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameter_code` varchar(80) NOT NULL,
  `parameter_value` text,
  `parameter_type` char(1) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parameter_code` (`parameter_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_parameters`
--

INSERT INTO `sys_parameters` (`parameter_code`, `parameter_value`, `parameter_type`, `description`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
('system.super_admin', 'root', 'S', NULL, 'A', 0, NOW(), NULL, NOW()),
('system.admin_email', 'Please enter the iPeer administrator\\''s email address.', 'S', NULL, 'A', 0, NOW(), NULL, NOW()),
('display.date_format', 'D, M j, Y g:i a', 'S', 'date format preference', 'A', 0, NOW(), NULL, NOW()),
('system.version', '3.1.0', 'S', NULL, 'A', 0, NOW(), NULL, NOW()),
('database.version', '7', 'I', 'database version', 'A', 0, NOW(), NULL, NOW()),
('email.port', '25', 'S', 'port number for email smtp option', 'A', '0', NOW(), NULL , NOW()),
('email.host', 'localhost', 'S', 'host address for email smtp option', 'A', '0', NOW(), NULL , NOW()),
('email.username', '', 'S', 'username for email smtp option', 'A', '0', NOW(), NULL , NOW()),
('email.password', '', 'S', 'password for email smtp option', 'A', '0', NOW(), NULL , NOW()),
('display.contact_info', 'noreply@ipeer.ctlt.ubc.ca', 'S', 'Contact Info', 'A', 0, NOW(), 0, NOW()),
('display.login.header', '', 'S', 'Login Info Header', 'A', 0, NOW(), 0, NOW()),
('display.login.footer', '', 'S', 'Login Info Footer', 'A', 0, NOW(), 0, NOW()),
('system.absolute_url', '', 'S', 'base url to iPeer', 'A', 0, NOW(), 0, NOW()),
('google_analytics.tracking_id', '', 'S', 'tracking id for Google Analytics', 'A', 0, NOW(), 0, NOW()),
('google_analytics.domain', '', 'S', 'domain name for Google Analytics', 'A', 0, NOW(), 0, NOW()),
('banner.custom_logo', '', 'S', 'custom logo that appears on the left side of the banner', 'A', 0, NOW(), 0, NOW()),
('system.timezone', '', 'S', 'timezone', 'A', 0, NOW(), 0, NOW());

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE no_duplicates (`course_id`,`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE no_duplicates (`course_id`,`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_faculties`
--

DROP TABLE IF EXISTS `user_faculties`;
CREATE TABLE `user_faculties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `faculty_id` int NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE no_duplicates (`course_id`,`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


SET foreign_key_checks = 1;
