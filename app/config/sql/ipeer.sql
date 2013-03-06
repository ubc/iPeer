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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=350 ;

--
-- Dumping data for table `acos`
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

INSERT INTO acos (id, parent_id, model, foreign_key, alias, lft, rght) VALUES
(1, NULL, NULL, NULL, 'adminpage', 1, 2),
(2, NULL, NULL, NULL, 'controllers', 3, 576),
(3, 2, NULL, NULL, 'Pages', 4, 17),
(4, 3, NULL, NULL, 'display', 5, 6),
(5, 3, NULL, NULL, 'add', 7, 8),
(6, 3, NULL, NULL, 'edit', 9, 10),
(7, 3, NULL, NULL, 'index', 11, 12),
(8, 3, NULL, NULL, 'view', 13, 14),
(9, 3, NULL, NULL, 'delete', 15, 16),
(10, 2, NULL, NULL, 'Courses', 18, 41),
(11, 10, NULL, NULL, 'daysLate', 19, 20),
(12, 10, NULL, NULL, 'index', 21, 22),
(13, 10, NULL, NULL, 'ajaxList', 23, 24),
(14, 10, NULL, NULL, 'view', 25, 26),
(15, 10, NULL, NULL, 'home', 27, 28),
(16, 10, NULL, NULL, 'add', 29, 30),
(17, 10, NULL, NULL, 'edit', 31, 32),
(18, 10, NULL, NULL, 'delete', 33, 34),
(19, 10, NULL, NULL, 'move', 35, 36),
(20, 10, NULL, NULL, 'ajax_options', 37, 38),
(21, 10, NULL, NULL, 'import', 39, 40),
(22, 2, NULL, NULL, 'Departments', 42, 53),
(23, 22, NULL, NULL, 'index', 43, 44),
(24, 22, NULL, NULL, 'view', 45, 46),
(25, 22, NULL, NULL, 'add', 47, 48),
(26, 22, NULL, NULL, 'edit', 49, 50),
(27, 22, NULL, NULL, 'delete', 51, 52),
(28, 2, NULL, NULL, 'Emailer', 54, 81),
(29, 28, NULL, NULL, 'setUpAjaxList', 55, 56),
(30, 28, NULL, NULL, 'ajaxList', 57, 58),
(31, 28, NULL, NULL, 'index', 59, 60),
(32, 28, NULL, NULL, 'write', 61, 62),
(33, 28, NULL, NULL, 'cancel', 63, 64),
(34, 28, NULL, NULL, 'view', 65, 66),
(35, 28, NULL, NULL, 'addRecipient', 67, 68),
(36, 28, NULL, NULL, 'deleteRecipient', 69, 70),
(37, 28, NULL, NULL, 'getRecipient', 71, 72),
(38, 28, NULL, NULL, 'searchByUserId', 73, 74),
(39, 28, NULL, NULL, 'add', 75, 76),
(40, 28, NULL, NULL, 'edit', 77, 78),
(41, 28, NULL, NULL, 'delete', 79, 80),
(42, 2, NULL, NULL, 'Emailtemplates', 82, 101),
(43, 42, NULL, NULL, 'setUpAjaxList', 83, 84),
(44, 42, NULL, NULL, 'ajaxList', 85, 86),
(45, 42, NULL, NULL, 'index', 87, 88),
(46, 42, NULL, NULL, 'add', 89, 90),
(47, 42, NULL, NULL, 'edit', 91, 92),
(48, 42, NULL, NULL, 'delete', 93, 94),
(49, 42, NULL, NULL, 'view', 95, 96),
(50, 42, NULL, NULL, 'displayTemplateContent', 97, 98),
(51, 42, NULL, NULL, 'displayTemplateSubject', 99, 100),
(52, 2, NULL, NULL, 'Evaltools', 102, 113),
(53, 52, NULL, NULL, 'index', 103, 104),
(54, 52, NULL, NULL, 'add', 105, 106),
(55, 52, NULL, NULL, 'edit', 107, 108),
(56, 52, NULL, NULL, 'view', 109, 110),
(57, 52, NULL, NULL, 'delete', 111, 112),
(58, 2, NULL, NULL, 'Evaluations', 114, 161),
(59, 58, NULL, NULL, 'setUpAjaxList', 115, 116),
(60, 58, NULL, NULL, 'ajaxList', 117, 118),
(61, 58, NULL, NULL, 'view', 119, 120),
(62, 58, NULL, NULL, 'index', 121, 122),
(63, 58, NULL, NULL, 'export', 123, 124),
(64, 58, NULL, NULL, 'makeEvaluation', 125, 126),
(65, 58, NULL, NULL, 'validRubricEvalComplete', 127, 128),
(66, 58, NULL, NULL, 'completeEvaluationRubric', 129, 130),
(67, 58, NULL, NULL, 'validMixevalEvalComplete', 131, 132),
(68, 58, NULL, NULL, 'viewEvaluationResults', 133, 134),
(69, 58, NULL, NULL, 'studentViewEvaluationResult', 135, 136),
(70, 58, NULL, NULL, 'markEventReviewed', 137, 138),
(71, 58, NULL, NULL, 'markGradeRelease', 139, 140),
(72, 58, NULL, NULL, 'markCommentRelease', 141, 142),
(73, 58, NULL, NULL, 'changeAllCommentRelease', 143, 144),
(74, 58, NULL, NULL, 'changeAllGradeRelease', 145, 146),
(75, 58, NULL, NULL, 'viewGroupSubmissionDetails', 147, 148),
(76, 58, NULL, NULL, 'viewSurveySummary', 149, 150),
(77, 58, NULL, NULL, 'export_rubic', 151, 152),
(78, 58, NULL, NULL, 'export_test', 153, 154),
(79, 58, NULL, NULL, 'add', 155, 156),
(80, 58, NULL, NULL, 'edit', 157, 158),
(81, 58, NULL, NULL, 'delete', 159, 160),
(82, 2, NULL, NULL, 'Events', 162, 181),
(83, 82, NULL, NULL, 'postProcessData', 163, 164),
(84, 82, NULL, NULL, 'setUpAjaxList', 165, 166),
(85, 82, NULL, NULL, 'index', 167, 168),
(86, 82, NULL, NULL, 'ajaxList', 169, 170),
(87, 82, NULL, NULL, 'view', 171, 172),
(88, 82, NULL, NULL, 'add', 173, 174),
(89, 82, NULL, NULL, 'edit', 175, 176),
(90, 82, NULL, NULL, 'delete', 177, 178),
(91, 82, NULL, NULL, 'checkDuplicateName', 179, 180),
(92, 2, NULL, NULL, 'Faculties', 182, 193),
(93, 92, NULL, NULL, 'index', 183, 184),
(94, 92, NULL, NULL, 'view', 185, 186),
(95, 92, NULL, NULL, 'add', 187, 188),
(96, 92, NULL, NULL, 'edit', 189, 190),
(97, 92, NULL, NULL, 'delete', 191, 192),
(98, 2, NULL, NULL, 'Framework', 194, 209),
(99, 98, NULL, NULL, 'calendarDisplay', 195, 196),
(100, 98, NULL, NULL, 'tutIndex', 197, 198),
(101, 98, NULL, NULL, 'add', 199, 200),
(102, 98, NULL, NULL, 'edit', 201, 202),
(103, 98, NULL, NULL, 'index', 203, 204),
(104, 98, NULL, NULL, 'view', 205, 206),
(105, 98, NULL, NULL, 'delete', 207, 208),
(106, 2, NULL, NULL, 'Groups', 210, 229),
(107, 106, NULL, NULL, 'setUpAjaxList', 211, 212),
(108, 106, NULL, NULL, 'index', 213, 214),
(109, 106, NULL, NULL, 'ajaxList', 215, 216),
(110, 106, NULL, NULL, 'view', 217, 218),
(111, 106, NULL, NULL, 'add', 219, 220),
(112, 106, NULL, NULL, 'edit', 221, 222),
(113, 106, NULL, NULL, 'delete', 223, 224),
(114, 106, NULL, NULL, 'import', 225, 226),
(115, 106, NULL, NULL, 'export', 227, 228),
(116, 2, NULL, NULL, 'Home', 230, 241),
(117, 116, NULL, NULL, 'index', 231, 232),
(118, 116, NULL, NULL, 'add', 233, 234),
(119, 116, NULL, NULL, 'edit', 235, 236),
(120, 116, NULL, NULL, 'view', 237, 238),
(121, 116, NULL, NULL, 'delete', 239, 240),
(122, 2, NULL, NULL, 'Install', 242, 263),
(123, 122, NULL, NULL, 'index', 243, 244),
(124, 122, NULL, NULL, 'install2', 245, 246),
(125, 122, NULL, NULL, 'install3', 247, 248),
(126, 122, NULL, NULL, 'install4', 249, 250),
(127, 122, NULL, NULL, 'install5', 251, 252),
(128, 122, NULL, NULL, 'gpl', 253, 254),
(129, 122, NULL, NULL, 'add', 255, 256),
(130, 122, NULL, NULL, 'edit', 257, 258),
(131, 122, NULL, NULL, 'view', 259, 260),
(132, 122, NULL, NULL, 'delete', 261, 262),
(133, 2, NULL, NULL, 'Lti', 264, 275),
(134, 133, NULL, NULL, 'index', 265, 266),
(135, 133, NULL, NULL, 'add', 267, 268),
(136, 133, NULL, NULL, 'edit', 269, 270),
(137, 133, NULL, NULL, 'view', 271, 272),
(138, 133, NULL, NULL, 'delete', 273, 274),
(139, 2, NULL, NULL, 'Mixevals', 276, 293),
(140, 139, NULL, NULL, 'setUpAjaxList', 277, 278),
(141, 139, NULL, NULL, 'index', 279, 280),
(142, 139, NULL, NULL, 'ajaxList', 281, 282),
(143, 139, NULL, NULL, 'view', 283, 284),
(144, 139, NULL, NULL, 'add', 285, 286),
(145, 139, NULL, NULL, 'edit', 287, 288),
(146, 139, NULL, NULL, 'copy', 289, 290),
(147, 139, NULL, NULL, 'delete', 291, 292),
(148, 2, NULL, NULL, 'Oauthclients', 294, 305),
(149, 148, NULL, NULL, 'index', 295, 296),
(150, 148, NULL, NULL, 'add', 297, 298),
(151, 148, NULL, NULL, 'edit', 299, 300),
(152, 148, NULL, NULL, 'delete', 301, 302),
(153, 148, NULL, NULL, 'view', 303, 304),
(154, 2, NULL, NULL, 'Oauthtokens', 306, 317),
(155, 154, NULL, NULL, 'index', 307, 308),
(156, 154, NULL, NULL, 'add', 309, 310),
(157, 154, NULL, NULL, 'edit', 311, 312),
(158, 154, NULL, NULL, 'delete', 313, 314),
(159, 154, NULL, NULL, 'view', 315, 316),
(160, 2, NULL, NULL, 'Penalty', 318, 331),
(161, 160, NULL, NULL, 'save', 319, 320),
(162, 160, NULL, NULL, 'add', 321, 322),
(163, 160, NULL, NULL, 'edit', 323, 324),
(164, 160, NULL, NULL, 'index', 325, 326),
(165, 160, NULL, NULL, 'view', 327, 328),
(166, 160, NULL, NULL, 'delete', 329, 330),
(167, 2, NULL, NULL, 'Rubrics', 332, 351),
(168, 167, NULL, NULL, 'postProcess', 333, 334),
(169, 167, NULL, NULL, 'setUpAjaxList', 335, 336),
(170, 167, NULL, NULL, 'index', 337, 338),
(171, 167, NULL, NULL, 'ajaxList', 339, 340),
(172, 167, NULL, NULL, 'view', 341, 342),
(173, 167, NULL, NULL, 'add', 343, 344),
(174, 167, NULL, NULL, 'edit', 345, 346),
(175, 167, NULL, NULL, 'copy', 347, 348),
(176, 167, NULL, NULL, 'delete', 349, 350),
(177, 2, NULL, NULL, 'Searchs', 352, 379),
(178, 177, NULL, NULL, 'update', 353, 354),
(179, 177, NULL, NULL, 'index', 355, 356),
(180, 177, NULL, NULL, 'searchEvaluation', 357, 358),
(181, 177, NULL, NULL, 'searchResult', 359, 360),
(182, 177, NULL, NULL, 'searchInstructor', 361, 362),
(183, 177, NULL, NULL, 'eventBoxSearch', 363, 364),
(184, 177, NULL, NULL, 'formatSearchEvaluation', 365, 366),
(185, 177, NULL, NULL, 'formatSearchInstructor', 367, 368),
(186, 177, NULL, NULL, 'formatSearchEvaluationResult', 369, 370),
(187, 177, NULL, NULL, 'add', 371, 372),
(188, 177, NULL, NULL, 'edit', 373, 374),
(189, 177, NULL, NULL, 'view', 375, 376),
(190, 177, NULL, NULL, 'delete', 377, 378),
(191, 2, NULL, NULL, 'Simpleevaluations', 380, 399),
(192, 191, NULL, NULL, 'postProcess', 381, 382),
(193, 191, NULL, NULL, 'setUpAjaxList', 383, 384),
(194, 191, NULL, NULL, 'index', 385, 386),
(195, 191, NULL, NULL, 'ajaxList', 387, 388),
(196, 191, NULL, NULL, 'view', 389, 390),
(197, 191, NULL, NULL, 'add', 391, 392),
(198, 191, NULL, NULL, 'edit', 393, 394),
(199, 191, NULL, NULL, 'copy', 395, 396),
(200, 191, NULL, NULL, 'delete', 397, 398),
(201, 2, NULL, NULL, 'Surveygroups', 400, 429),
(202, 201, NULL, NULL, 'postProcess', 401, 402),
(203, 201, NULL, NULL, 'setUpAjaxList', 403, 404),
(204, 201, NULL, NULL, 'index', 405, 406),
(205, 201, NULL, NULL, 'ajaxList', 407, 408),
(206, 201, NULL, NULL, 'makegroups', 409, 410),
(207, 201, NULL, NULL, 'makegroupssearch', 411, 412),
(208, 201, NULL, NULL, 'maketmgroups', 413, 414),
(209, 201, NULL, NULL, 'savegroups', 415, 416),
(210, 201, NULL, NULL, 'release', 417, 418),
(211, 201, NULL, NULL, 'delete', 419, 420),
(212, 201, NULL, NULL, 'edit', 421, 422),
(213, 201, NULL, NULL, 'changegroupset', 423, 424),
(214, 201, NULL, NULL, 'add', 425, 426),
(215, 201, NULL, NULL, 'view', 427, 428),
(216, 2, NULL, NULL, 'Surveys', 430, 457),
(217, 216, NULL, NULL, 'setUpAjaxList', 431, 432),
(218, 216, NULL, NULL, 'index', 433, 434),
(219, 216, NULL, NULL, 'ajaxList', 435, 436),
(220, 216, NULL, NULL, 'view', 437, 438),
(221, 216, NULL, NULL, 'add', 439, 440),
(222, 216, NULL, NULL, 'edit', 441, 442),
(223, 216, NULL, NULL, 'copy', 443, 444),
(224, 216, NULL, NULL, 'delete', 445, 446),
(225, 216, NULL, NULL, 'questionsSummary', 447, 448),
(226, 216, NULL, NULL, 'moveQuestion', 449, 450),
(227, 216, NULL, NULL, 'removeQuestion', 451, 452),
(228, 216, NULL, NULL, 'addQuestion', 453, 454),
(229, 216, NULL, NULL, 'editQuestion', 455, 456),
(230, 2, NULL, NULL, 'Sysparameters', 458, 473),
(231, 230, NULL, NULL, 'setUpAjaxList', 459, 460),
(232, 230, NULL, NULL, 'index', 461, 462),
(233, 230, NULL, NULL, 'ajaxList', 463, 464),
(234, 230, NULL, NULL, 'view', 465, 466),
(235, 230, NULL, NULL, 'add', 467, 468),
(236, 230, NULL, NULL, 'edit', 469, 470),
(237, 230, NULL, NULL, 'delete', 471, 472),
(238, 2, NULL, NULL, 'Upgrade', 474, 487),
(239, 238, NULL, NULL, 'index', 475, 476),
(240, 238, NULL, NULL, 'step2', 477, 478),
(241, 238, NULL, NULL, 'add', 479, 480),
(242, 238, NULL, NULL, 'edit', 481, 482),
(243, 238, NULL, NULL, 'view', 483, 484),
(244, 238, NULL, NULL, 'delete', 485, 486),
(245, 2, NULL, NULL, 'Users', 488, 521),
(246, 245, NULL, NULL, 'ajaxList', 489, 490),
(247, 245, NULL, NULL, 'index', 491, 492),
(248, 245, NULL, NULL, 'goToClassList', 493, 494),
(249, 245, NULL, NULL, 'determineIfStudentFromThisData', 495, 496),
(250, 245, NULL, NULL, 'view', 497, 498),
(251, 245, NULL, NULL, 'add', 499, 500),
(252, 245, NULL, NULL, 'enrol', 501, 502),
(253, 245, NULL, NULL, 'edit', 503, 504),
(254, 245, NULL, NULL, 'editProfile', 505, 506),
(255, 245, NULL, NULL, 'delete', 507, 508),
(256, 245, NULL, NULL, 'checkDuplicateName', 509, 510),
(257, 245, NULL, NULL, 'resetPassword', 511, 512),
(258, 245, NULL, NULL, 'import', 513, 514),
(259, 245, NULL, NULL, 'merge', 515, 516),
(260, 245, NULL, NULL, 'ajax_merge', 517, 518),
(261, 245, NULL, NULL, 'update', 519, 520),
(262, 2, NULL, NULL, 'V1', 522, 557),
(263, 262, NULL, NULL, 'oauth', 523, 524),
(264, 262, NULL, NULL, 'oauth_error', 525, 526),
(265, 262, NULL, NULL, 'users', 527, 528),
(266, 262, NULL, NULL, 'courses', 529, 530),
(267, 262, NULL, NULL, 'groups', 531, 532),
(268, 262, NULL, NULL, 'groupMembers', 533, 534),
(269, 262, NULL, NULL, 'events', 535, 536),
(270, 262, NULL, NULL, 'grades', 537, 538),
(271, 262, NULL, NULL, 'departments', 539, 540),
(272, 262, NULL, NULL, 'courseDepartments', 541, 542),
(273, 262, NULL, NULL, 'userEvents', 543, 544),
(274, 262, NULL, NULL, 'enrolment', 545, 546),
(275, 262, NULL, NULL, 'add', 547, 548),
(276, 262, NULL, NULL, 'edit', 549, 550),
(277, 262, NULL, NULL, 'index', 551, 552),
(278, 262, NULL, NULL, 'view', 553, 554),
(279, 262, NULL, NULL, 'delete', 555, 556),
(280, 2, NULL, NULL, 'Guard', 558, 575),
(281, 280, NULL, NULL, 'Guard', 559, 574),
(282, 281, NULL, NULL, 'login', 560, 561),
(283, 281, NULL, NULL, 'logout', 562, 563),
(284, 281, NULL, NULL, 'add', 564, 565),
(285, 281, NULL, NULL, 'edit', 566, 567),
(286, 281, NULL, NULL, 'index', 568, 569),
(287, 281, NULL, NULL, 'view', 570, 571),
(288, 281, NULL, NULL, 'delete', 572, 573),
(289, NULL, NULL, NULL, 'functions', 577, 640),
(290, 289, NULL, NULL, 'user', 578, 605),
(291, 290, NULL, NULL, 'superadmin', 579, 580),
(292, 290, NULL, NULL, 'admin', 581, 582),
(293, 290, NULL, NULL, 'instructor', 583, 584),
(294, 290, NULL, NULL, 'tutor', 585, 586),
(295, 290, NULL, NULL, 'student', 587, 588),
(296, 290, NULL, NULL, 'import', 589, 590),
(297, 290, NULL, NULL, 'password_reset', 591, 602),
(298, 297, NULL, NULL, 'superadmin', 592, 593),
(299, 297, NULL, NULL, 'admin', 594, 595),
(300, 297, NULL, NULL, 'instructor', 596, 597),
(301, 297, NULL, NULL, 'tutor', 598, 599),
(302, 297, NULL, NULL, 'student', 600, 601),
(303, 290, NULL, NULL, 'index', 603, 604),
(304, 289, NULL, NULL, 'role', 606, 617),
(305, 304, NULL, NULL, 'superadmin', 607, 608),
(306, 304, NULL, NULL, 'admin', 609, 610),
(307, 304, NULL, NULL, 'instructor', 611, 612),
(308, 304, NULL, NULL, 'tutor', 613, 614),
(309, 304, NULL, NULL, 'student', 615, 616),
(310, 289, NULL, NULL, 'evaluation', 618, 619),
(311, 289, NULL, NULL, 'email', 620, 627),
(312, 311, NULL, NULL, 'allUsers', 621, 622),
(313, 311, NULL, NULL, 'allGroups', 623, 624),
(314, 311, NULL, NULL, 'allCourses', 625, 626),
(315, 289, NULL, NULL, 'emailtemplate', 628, 629),
(316, 289, NULL, NULL, 'viewstudentresults', 630, 631),
(317, 289, NULL, NULL, 'viewemailaddresses', 632, 633),
(318, 289, NULL, NULL, 'superadmin', 634, 635),
(319, 289, NULL, NULL, 'coursemanager', 636, 637),
(320, 289, NULL, NULL, 'viewusername', 638, 639);

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

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


INSERT INTO aros_acos (id, aro_id, aco_id, _create, _read, _update, _delete) VALUES
(1, 1, 2, '1', '1', '1', '1'),
(2, 1, 289, '1', '1', '1', '1'),
(3, 1, 1, '1', '1', '1', '1'),
(4, 2, 2, '-1', '-1', '-1', '-1'),
(5, 2, 116, '1', '1', '1', '1'),
(6, 2, 10, '1', '1', '1', '1'),
(7, 2, 22, '1', '1', '1', '1'),
(8, 2, 28, '1', '1', '1', '1'),
(9, 2, 42, '1', '1', '1', '1'),
(10, 2, 52, '1', '1', '1', '1'),
(11, 2, 58, '1', '1', '1', '1'),
(12, 2, 82, '1', '1', '1', '1'),
(13, 2, 106, '1', '1', '1', '1'),
(14, 2, 139, '1', '1', '1', '1'),
(15, 2, 167, '1', '1', '1', '1'),
(16, 2, 191, '1', '1', '1', '1'),
(17, 2, 216, '1', '1', '1', '1'),
(18, 2, 201, '1', '1', '1', '1'),
(19, 2, 245, '1', '1', '1', '1'),
(20, 2, 283, '1', '1', '1', '1'),
(21, 2, 150, '1', '1', '1', '1'),
(22, 2, 152, '1', '1', '1', '1'),
(23, 2, 156, '1', '1', '1', '1'),
(24, 2, 158, '1', '1', '1', '1'),
(25, 2, 289, '-1', '-1', '-1', '-1'),
(26, 2, 315, '1', '1', '1', '1'),
(27, 2, 310, '1', '1', '1', '1'),
(28, 2, 312, '1', '1', '1', '1'),
(29, 2, 290, '1', '1', '1', '1'),
(30, 2, 292, '1', '1', '1', '-1'),
(31, 2, 291, '-1', '-1', '-1', '-1'),
(32, 2, 317, '1', '1', '1', '1'),
(33, 2, 320, '1', '1', '1', '1'),
(34, 2, 319, '1', '1', '1', '1'),
(35, 2, 318, '-1', '-1', '-1', '-1'),
(36, 3, 2, '-1', '-1', '-1', '-1'),
(37, 3, 116, '1', '1', '1', '1'),
(38, 3, 10, '1', '1', '1', '1'),
(39, 3, 28, '1', '1', '1', '1'),
(40, 3, 42, '1', '1', '1', '1'),
(41, 3, 52, '1', '1', '1', '1'),
(42, 3, 58, '1', '1', '1', '1'),
(43, 3, 82, '1', '1', '1', '1'),
(44, 3, 106, '1', '1', '1', '1'),
(45, 3, 139, '1', '1', '1', '1'),
(46, 3, 167, '1', '1', '1', '1'),
(47, 3, 191, '1', '1', '1', '1'),
(48, 3, 216, '1', '1', '1', '1'),
(49, 3, 201, '1', '1', '1', '1'),
(50, 3, 245, '1', '1', '1', '1'),
(51, 3, 283, '1', '1', '1', '1'),
(52, 3, 150, '1', '1', '1', '1'),
(53, 3, 152, '1', '1', '1', '1'),
(54, 3, 156, '1', '1', '1', '1'),
(55, 3, 158, '1', '1', '1', '1'),
(56, 3, 259, '-1', '-1', '-1', '-1'),
(57, 3, 289, '-1', '-1', '-1', '-1'),
(58, 3, 310, '1', '1', '-1', '-1'),
(59, 3, 290, '1', '1', '1', '1'),
(60, 3, 292, '-1', '-1', '-1', '-1'),
(61, 3, 291, '-1', '-1', '-1', '-1'),
(62, 3, 293, '-1', '1', '-1', '-1'),
(63, 3, 303, '-1', '-1', '-1', '-1'),
(64, 3, 317, '-1', '-1', '-1', '-1'),
(65, 3, 318, '-1', '-1', '-1', '-1'),
(66, 3, 319, '1', '1', '1', '1'),
(67, 4, 2, '-1', '-1', '-1', '-1'),
(68, 4, 116, '1', '1', '1', '1'),
(69, 4, 10, '-1', '-1', '-1', '-1'),
(70, 4, 28, '-1', '-1', '-1', '-1'),
(71, 4, 42, '-1', '-1', '-1', '-1'),
(72, 4, 52, '-1', '-1', '-1', '-1'),
(73, 4, 82, '-1', '-1', '-1', '-1'),
(74, 4, 106, '-1', '-1', '-1', '-1'),
(75, 4, 139, '-1', '-1', '-1', '-1'),
(76, 4, 167, '-1', '-1', '-1', '-1'),
(77, 4, 191, '-1', '-1', '-1', '-1'),
(78, 4, 216, '-1', '-1', '-1', '-1'),
(79, 4, 201, '-1', '-1', '-1', '-1'),
(80, 4, 245, '-1', '-1', '-1', '-1'),
(81, 4, 283, '1', '1', '1', '1'),
(82, 4, 64, '1', '1', '1', '1'),
(83, 4, 69, '1', '1', '1', '1'),
(84, 4, 66, '1', '1', '1', '1'),
(85, 4, 254, '1', '1', '1', '1'),
(86, 4, 289, '-1', '-1', '-1', '-1'),
(87, 4, 317, '-1', '-1', '-1', '-1'),
(88, 4, 318, '-1', '-1', '-1', '-1'),
(89, 5, 2, '-1', '-1', '-1', '-1'),
(90, 5, 116, '1', '1', '1', '1'),
(91, 5, 10, '-1', '-1', '-1', '-1'),
(92, 5, 28, '-1', '-1', '-1', '-1'),
(93, 5, 42, '-1', '-1', '-1', '-1'),
(94, 5, 52, '-1', '-1', '-1', '-1'),
(95, 5, 82, '-1', '-1', '-1', '-1'),
(96, 5, 106, '-1', '-1', '-1', '-1'),
(97, 5, 139, '-1', '-1', '-1', '-1'),
(98, 5, 167, '-1', '-1', '-1', '-1'),
(99, 5, 191, '-1', '-1', '-1', '-1'),
(100, 5, 216, '-1', '-1', '-1', '-1'),
(101, 5, 201, '-1', '-1', '-1', '-1'),
(102, 5, 245, '-1', '-1', '-1', '-1'),
(103, 5, 283, '1', '1', '1', '1'),
(104, 5, 64, '1', '1', '1', '1'),
(105, 5, 69, '1', '1', '1', '1'),
(106, 5, 66, '1', '1', '1', '1'),
(107, 5, 254, '1', '1', '1', '1'),
(108, 5, 150, '1', '1', '1', '1'),
(109, 5, 152, '1', '1', '1', '1'),
(110, 5, 156, '1', '1', '1', '1'),
(111, 5, 158, '1', '1', '1', '1'),
(112, 5, 289, '-1', '-1', '-1', '-1'),
(113, 5, 316, '1', '1', '1', '1'),
(114, 5, 317, '-1', '-1', '-1', '-1'),
(115, 5, 318, '-1', '-1', '-1', '-1');

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
  `created` datetime,
  `updater_id` int(11) default NULL,
  `modified` datetime,
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
  `created` datetime,
  `modified` datetime,
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `updated` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
  `date_submitted` datetime,
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
(3, 'SURVEY', 'surveys', '', 1, 'A', 0, '0000-00-00 00:00:00', NULL, NULL),
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
  `results` int(11) NOT NULL DEFAULT '0',
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
  UNIQUE KEY `group_user` (`group_id`,`user_id`),
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
  `availability` varchar(10) NOT NULL DEFAULT 'public',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Table structure for table `mixeval_question_types`
--

DROP TABLE IF EXISTS `mixeval_question_types`;
CREATE TABLE IF NOT EXISTS `mixeval_question_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `mixeval_question_types`
--

INSERT INTO `mixeval_question_types` (`id`, `type`) VALUES
(1, 'Likert'),
(2, 'Paragraph'),
(3, 'Sentence');

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
  `multiplier` int(11) NOT NULL DEFAULT '0',
  `scale_level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`mixeval_question_type_id`) REFERENCES `mixeval_question_types` 
	(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`mixeval_id`) REFERENCES `mixevals` 
	(`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

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
  `created` datetime,
  `updated` datetime,
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
  `created` datetime,
  `modified` datetime,
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
  `created` datetime,
  `modified` datetime,
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
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
  `event_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL DEFAULT '0',
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
('database.version', '6', 'I', 'database version', 'A', 0, NOW(), NULL, NOW()),
('email.port', '25', 'S', 'port number for email smtp option', 'A', '0', NOW(), NULL , NOW()),
('email.host', 'localhost', 'S', 'host address for email smtp option', 'A', '0', NOW(), NULL , NOW()),
('email.username', '', 'S', 'username for email smtp option', 'A', '0', NOW(), NULL , NOW()),
('email.password', '', 'S', 'password for email smtp option', 'A', '0', NOW(), NULL , NOW()),
('display.contact_info', 'noreply@ipeer.ctlt.ubc.ca', 'S', 'Contact Info', 'A', 0, NOW(), 0, NOW()),
('display.login.header', '', 'S', 'Login Info Header', 'A', 0, NOW(), 0, NOW()),
('display.login.footer', '', 'S', 'Login Info Footer', 'A', 0, NOW(), 0, NOW());

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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
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
  `created` datetime,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime,
  PRIMARY KEY (`id`),
  UNIQUE no_duplicates (`course_id`,`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


SET foreign_key_checks = 1;
