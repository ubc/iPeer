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
(2, NULL, NULL, NULL, 'controllers', 3, 578),
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
(58, 2, NULL, NULL, 'Evaluations', 114, 163),
(59, 58, NULL, NULL, 'setUpAjaxList', 115, 116),
(60, 58, NULL, NULL, 'ajaxList', 117, 118),
(61, 58, NULL, NULL, 'view', 119, 120),
(62, 58, NULL, NULL, 'index', 121, 122),
(63, 58, NULL, NULL, 'export', 123, 124),
(64, 58, NULL, NULL, 'makeEvaluation', 125, 126),
(65, 58, NULL, NULL, 'validRubricEvalComplete', 127, 128),
(66, 58, NULL, NULL, 'completeEvaluationRubric', 129, 130),
(67, 58, NULL, NULL, 'validMixevalEvalComplete', 131, 132),
(68, 58, NULL, NULL, 'completeEvaluationMixeval', 133, 134),
(69, 58, NULL, NULL, 'viewEvaluationResults', 135, 136),
(70, 58, NULL, NULL, 'studentViewEvaluationResult', 137, 138),
(71, 58, NULL, NULL, 'markEventReviewed', 139, 140),
(72, 58, NULL, NULL, 'markGradeRelease', 141, 142),
(73, 58, NULL, NULL, 'markCommentRelease', 143, 144),
(74, 58, NULL, NULL, 'changeAllCommentRelease', 145, 146),
(75, 58, NULL, NULL, 'changeAllGradeRelease', 147, 148),
(76, 58, NULL, NULL, 'viewGroupSubmissionDetails', 149, 150),
(77, 58, NULL, NULL, 'viewSurveySummary', 151, 152),
(78, 58, NULL, NULL, 'export_rubic', 153, 154),
(79, 58, NULL, NULL, 'export_test', 155, 156),
(80, 58, NULL, NULL, 'add', 157, 158),
(81, 58, NULL, NULL, 'edit', 159, 160),
(82, 58, NULL, NULL, 'delete', 161, 162),
(83, 2, NULL, NULL, 'Events', 164, 183),
(84, 83, NULL, NULL, 'postProcessData', 165, 166),
(85, 83, NULL, NULL, 'setUpAjaxList', 167, 168),
(86, 83, NULL, NULL, 'index', 169, 170),
(87, 83, NULL, NULL, 'ajaxList', 171, 172),
(88, 83, NULL, NULL, 'view', 173, 174),
(89, 83, NULL, NULL, 'add', 175, 176),
(90, 83, NULL, NULL, 'edit', 177, 178),
(91, 83, NULL, NULL, 'delete', 179, 180),
(92, 83, NULL, NULL, 'checkDuplicateName', 181, 182),
(93, 2, NULL, NULL, 'Faculties', 184, 195),
(94, 93, NULL, NULL, 'index', 185, 186),
(95, 93, NULL, NULL, 'view', 187, 188),
(96, 93, NULL, NULL, 'add', 189, 190),
(97, 93, NULL, NULL, 'edit', 191, 192),
(98, 93, NULL, NULL, 'delete', 193, 194),
(99, 2, NULL, NULL, 'Framework', 196, 211),
(100, 99, NULL, NULL, 'calendarDisplay', 197, 198),
(101, 99, NULL, NULL, 'tutIndex', 199, 200),
(102, 99, NULL, NULL, 'add', 201, 202),
(103, 99, NULL, NULL, 'edit', 203, 204),
(104, 99, NULL, NULL, 'index', 205, 206),
(105, 99, NULL, NULL, 'view', 207, 208),
(106, 99, NULL, NULL, 'delete', 209, 210),
(107, 2, NULL, NULL, 'Groups', 212, 231),
(108, 107, NULL, NULL, 'setUpAjaxList', 213, 214),
(109, 107, NULL, NULL, 'index', 215, 216),
(110, 107, NULL, NULL, 'ajaxList', 217, 218),
(111, 107, NULL, NULL, 'view', 219, 220),
(112, 107, NULL, NULL, 'add', 221, 222),
(113, 107, NULL, NULL, 'edit', 223, 224),
(114, 107, NULL, NULL, 'delete', 225, 226),
(115, 107, NULL, NULL, 'import', 227, 228),
(116, 107, NULL, NULL, 'export', 229, 230),
(117, 2, NULL, NULL, 'Home', 232, 243),
(118, 117, NULL, NULL, 'index', 233, 234),
(119, 117, NULL, NULL, 'add', 235, 236),
(120, 117, NULL, NULL, 'edit', 237, 238),
(121, 117, NULL, NULL, 'view', 239, 240),
(122, 117, NULL, NULL, 'delete', 241, 242),
(123, 2, NULL, NULL, 'Install', 244, 265),
(124, 123, NULL, NULL, 'index', 245, 246),
(125, 123, NULL, NULL, 'install2', 247, 248),
(126, 123, NULL, NULL, 'install3', 249, 250),
(127, 123, NULL, NULL, 'install4', 251, 252),
(128, 123, NULL, NULL, 'install5', 253, 254),
(129, 123, NULL, NULL, 'gpl', 255, 256),
(130, 123, NULL, NULL, 'add', 257, 258),
(131, 123, NULL, NULL, 'edit', 259, 260),
(132, 123, NULL, NULL, 'view', 261, 262),
(133, 123, NULL, NULL, 'delete', 263, 264),
(134, 2, NULL, NULL, 'Lti', 266, 277),
(135, 134, NULL, NULL, 'index', 267, 268),
(136, 134, NULL, NULL, 'add', 269, 270),
(137, 134, NULL, NULL, 'edit', 271, 272),
(138, 134, NULL, NULL, 'view', 273, 274),
(139, 134, NULL, NULL, 'delete', 275, 276),
(140, 2, NULL, NULL, 'Mixevals', 278, 299),
(141, 140, NULL, NULL, 'setUpAjaxList', 279, 280),
(142, 140, NULL, NULL, 'index', 281, 282),
(143, 140, NULL, NULL, 'ajaxList', 283, 284),
(144, 140, NULL, NULL, 'view', 285, 286),
(145, 140, NULL, NULL, 'add', 287, 288),
(146, 140, NULL, NULL, 'deleteQuestion', 289, 290),
(147, 140, NULL, NULL, 'deleteDescriptor', 291, 292),
(148, 140, NULL, NULL, 'edit', 293, 294),
(149, 140, NULL, NULL, 'copy', 295, 296),
(150, 140, NULL, NULL, 'delete', 297, 298),
(151, 2, NULL, NULL, 'Oauthclients', 300, 311),
(152, 151, NULL, NULL, 'index', 301, 302),
(153, 151, NULL, NULL, 'add', 303, 304),
(154, 151, NULL, NULL, 'edit', 305, 306),
(155, 151, NULL, NULL, 'delete', 307, 308),
(156, 151, NULL, NULL, 'view', 309, 310),
(157, 2, NULL, NULL, 'Oauthtokens', 312, 323),
(158, 157, NULL, NULL, 'index', 313, 314),
(159, 157, NULL, NULL, 'add', 315, 316),
(160, 157, NULL, NULL, 'edit', 317, 318),
(161, 157, NULL, NULL, 'delete', 319, 320),
(162, 157, NULL, NULL, 'view', 321, 322),
(163, 2, NULL, NULL, 'Penalty', 324, 337),
(164, 163, NULL, NULL, 'save', 325, 326),
(165, 163, NULL, NULL, 'add', 327, 328),
(166, 163, NULL, NULL, 'edit', 329, 330),
(167, 163, NULL, NULL, 'index', 331, 332),
(168, 163, NULL, NULL, 'view', 333, 334),
(169, 163, NULL, NULL, 'delete', 335, 336),
(170, 2, NULL, NULL, 'Rubrics', 338, 357),
(171, 170, NULL, NULL, 'postProcess', 339, 340),
(172, 170, NULL, NULL, 'setUpAjaxList', 341, 342),
(173, 170, NULL, NULL, 'index', 343, 344),
(174, 170, NULL, NULL, 'ajaxList', 345, 346),
(175, 170, NULL, NULL, 'view', 347, 348),
(176, 170, NULL, NULL, 'add', 349, 350),
(177, 170, NULL, NULL, 'edit', 351, 352),
(178, 170, NULL, NULL, 'copy', 353, 354),
(179, 170, NULL, NULL, 'delete', 355, 356),
(180, 2, NULL, NULL, 'Searchs', 358, 385),
(181, 180, NULL, NULL, 'update', 359, 360),
(182, 180, NULL, NULL, 'index', 361, 362),
(183, 180, NULL, NULL, 'searchEvaluation', 363, 364),
(184, 180, NULL, NULL, 'searchResult', 365, 366),
(185, 180, NULL, NULL, 'searchInstructor', 367, 368),
(186, 180, NULL, NULL, 'eventBoxSearch', 369, 370),
(187, 180, NULL, NULL, 'formatSearchEvaluation', 371, 372),
(188, 180, NULL, NULL, 'formatSearchInstructor', 373, 374),
(189, 180, NULL, NULL, 'formatSearchEvaluationResult', 375, 376),
(190, 180, NULL, NULL, 'add', 377, 378),
(191, 180, NULL, NULL, 'edit', 379, 380),
(192, 180, NULL, NULL, 'view', 381, 382),
(193, 180, NULL, NULL, 'delete', 383, 384),
(194, 2, NULL, NULL, 'Simpleevaluations', 386, 405),
(195, 194, NULL, NULL, 'postProcess', 387, 388),
(196, 194, NULL, NULL, 'setUpAjaxList', 389, 390),
(197, 194, NULL, NULL, 'index', 391, 392),
(198, 194, NULL, NULL, 'ajaxList', 393, 394),
(199, 194, NULL, NULL, 'view', 395, 396),
(200, 194, NULL, NULL, 'add', 397, 398),
(201, 194, NULL, NULL, 'edit', 399, 400),
(202, 194, NULL, NULL, 'copy', 401, 402),
(203, 194, NULL, NULL, 'delete', 403, 404),
(204, 2, NULL, NULL, 'Surveygroups', 406, 435),
(205, 204, NULL, NULL, 'postProcess', 407, 408),
(206, 204, NULL, NULL, 'setUpAjaxList', 409, 410),
(207, 204, NULL, NULL, 'index', 411, 412),
(208, 204, NULL, NULL, 'ajaxList', 413, 414),
(209, 204, NULL, NULL, 'makegroups', 415, 416),
(210, 204, NULL, NULL, 'makegroupssearch', 417, 418),
(211, 204, NULL, NULL, 'maketmgroups', 419, 420),
(212, 204, NULL, NULL, 'savegroups', 421, 422),
(213, 204, NULL, NULL, 'release', 423, 424),
(214, 204, NULL, NULL, 'delete', 425, 426),
(215, 204, NULL, NULL, 'edit', 427, 428),
(216, 204, NULL, NULL, 'changegroupset', 429, 430),
(217, 204, NULL, NULL, 'add', 431, 432),
(218, 204, NULL, NULL, 'view', 433, 434),
(219, 2, NULL, NULL, 'Surveys', 436, 463),
(220, 219, NULL, NULL, 'setUpAjaxList', 437, 438),
(221, 219, NULL, NULL, 'index', 439, 440),
(222, 219, NULL, NULL, 'ajaxList', 441, 442),
(223, 219, NULL, NULL, 'view', 443, 444),
(224, 219, NULL, NULL, 'add', 445, 446),
(225, 219, NULL, NULL, 'edit', 447, 448),
(226, 219, NULL, NULL, 'copy', 449, 450),
(227, 219, NULL, NULL, 'delete', 451, 452),
(228, 219, NULL, NULL, 'questionsSummary', 453, 454),
(229, 219, NULL, NULL, 'moveQuestion', 455, 456),
(230, 219, NULL, NULL, 'removeQuestion', 457, 458),
(231, 219, NULL, NULL, 'addQuestion', 459, 460),
(232, 219, NULL, NULL, 'editQuestion', 461, 462),
(233, 2, NULL, NULL, 'Sysparameters', 464, 479),
(234, 233, NULL, NULL, 'setUpAjaxList', 465, 466),
(235, 233, NULL, NULL, 'index', 467, 468),
(236, 233, NULL, NULL, 'ajaxList', 469, 470),
(237, 233, NULL, NULL, 'view', 471, 472),
(238, 233, NULL, NULL, 'add', 473, 474),
(239, 233, NULL, NULL, 'edit', 475, 476),
(240, 233, NULL, NULL, 'delete', 477, 478),
(241, 2, NULL, NULL, 'Upgrade', 480, 493),
(242, 241, NULL, NULL, 'index', 481, 482),
(243, 241, NULL, NULL, 'step2', 483, 484),
(244, 241, NULL, NULL, 'add', 485, 486),
(245, 241, NULL, NULL, 'edit', 487, 488),
(246, 241, NULL, NULL, 'view', 489, 490),
(247, 241, NULL, NULL, 'delete', 491, 492),
(248, 2, NULL, NULL, 'Users', 494, 523),
(249, 248, NULL, NULL, 'ajaxList', 495, 496),
(250, 248, NULL, NULL, 'index', 497, 498),
(251, 248, NULL, NULL, 'goToClassList', 499, 500),
(252, 248, NULL, NULL, 'determineIfStudentFromThisData', 501, 502),
(253, 248, NULL, NULL, 'view', 503, 504),
(254, 248, NULL, NULL, 'add', 505, 506),
(255, 248, NULL, NULL, 'enrol', 507, 508),
(256, 248, NULL, NULL, 'edit', 509, 510),
(257, 248, NULL, NULL, 'editProfile', 511, 512),
(258, 248, NULL, NULL, 'delete', 513, 514),
(259, 248, NULL, NULL, 'checkDuplicateName', 515, 516),
(260, 248, NULL, NULL, 'resetPassword', 517, 518),
(261, 248, NULL, NULL, 'import', 519, 520),
(262, 248, NULL, NULL, 'update', 521, 522),
(263, 2, NULL, NULL, 'V1', 524, 559),
(264, 263, NULL, NULL, 'oauth', 525, 526),
(265, 263, NULL, NULL, 'oauth_error', 527, 528),
(266, 263, NULL, NULL, 'users', 529, 530),
(267, 263, NULL, NULL, 'courses', 531, 532),
(268, 263, NULL, NULL, 'groups', 533, 534),
(269, 263, NULL, NULL, 'groupMembers', 535, 536),
(270, 263, NULL, NULL, 'events', 537, 538),
(271, 263, NULL, NULL, 'grades', 539, 540),
(272, 263, NULL, NULL, 'departments', 541, 542),
(273, 263, NULL, NULL, 'courseDepartments', 543, 544),
(274, 263, NULL, NULL, 'userEvents', 545, 546),
(275, 263, NULL, NULL, 'enrolment', 547, 548),
(276, 263, NULL, NULL, 'add', 549, 550),
(277, 263, NULL, NULL, 'edit', 551, 552),
(278, 263, NULL, NULL, 'index', 553, 554),
(279, 263, NULL, NULL, 'view', 555, 556),
(280, 263, NULL, NULL, 'delete', 557, 558),
(281, 2, NULL, NULL, 'Guard', 560, 577),
(282, 281, NULL, NULL, 'Guard', 561, 576),
(283, 282, NULL, NULL, 'login', 562, 563),
(284, 282, NULL, NULL, 'logout', 564, 565),
(285, 282, NULL, NULL, 'add', 566, 567),
(286, 282, NULL, NULL, 'edit', 568, 569),
(287, 282, NULL, NULL, 'index', 570, 571),
(288, 282, NULL, NULL, 'view', 572, 573),
(289, 282, NULL, NULL, 'delete', 574, 575),
(290, NULL, NULL, NULL, 'functions', 579, 642),
(291, 290, NULL, NULL, 'user', 580, 607),
(292, 291, NULL, NULL, 'superadmin', 581, 582),
(293, 291, NULL, NULL, 'admin', 583, 584),
(294, 291, NULL, NULL, 'instructor', 585, 586),
(295, 291, NULL, NULL, 'tutor', 587, 588),
(296, 291, NULL, NULL, 'student', 589, 590),
(297, 291, NULL, NULL, 'import', 591, 592),
(298, 291, NULL, NULL, 'password_reset', 593, 604),
(299, 298, NULL, NULL, 'superadmin', 594, 595),
(300, 298, NULL, NULL, 'admin', 596, 597),
(301, 298, NULL, NULL, 'instructor', 598, 599),
(302, 298, NULL, NULL, 'tutor', 600, 601),
(303, 298, NULL, NULL, 'student', 602, 603),
(304, 291, NULL, NULL, 'index', 605, 606),
(305, 290, NULL, NULL, 'role', 608, 619),
(306, 305, NULL, NULL, 'superadmin', 609, 610),
(307, 305, NULL, NULL, 'admin', 611, 612),
(308, 305, NULL, NULL, 'instructor', 613, 614),
(309, 305, NULL, NULL, 'tutor', 615, 616),
(310, 305, NULL, NULL, 'student', 617, 618),
(311, 290, NULL, NULL, 'evaluation', 620, 621),
(312, 290, NULL, NULL, 'email', 622, 629),
(313, 312, NULL, NULL, 'allUsers', 623, 624),
(314, 312, NULL, NULL, 'allGroups', 625, 626),
(315, 312, NULL, NULL, 'allCourses', 627, 628),
(316, 290, NULL, NULL, 'emailtemplate', 630, 631),
(317, 290, NULL, NULL, 'viewstudentresults', 632, 633),
(318, 290, NULL, NULL, 'viewemailaddresses', 634, 635),
(319, 290, NULL, NULL, 'superadmin', 636, 637),
(320, 290, NULL, NULL, 'coursemanager', 638, 639),
(321, 290, NULL, NULL, 'viewusername', 640, 641);



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
(2, 1, 290, '1', '1', '1', '1'),
(3, 1, 1, '1', '1', '1', '1'),
(4, 2, 2, '-1', '-1', '-1', '-1'),
(5, 2, 117, '1', '1', '1', '1'),
(6, 2, 10, '1', '1', '1', '1'),
(7, 2, 22, '1', '1', '1', '1'),
(8, 2, 28, '1', '1', '1', '1'),
(9, 2, 42, '1', '1', '1', '1'),
(10, 2, 52, '1', '1', '1', '1'),
(11, 2, 58, '1', '1', '1', '1'),
(12, 2, 83, '1', '1', '1', '1'),
(13, 2, 107, '1', '1', '1', '1'),
(14, 2, 140, '1', '1', '1', '1'),
(15, 2, 170, '1', '1', '1', '1'),
(16, 2, 194, '1', '1', '1', '1'),
(17, 2, 219, '1', '1', '1', '1'),
(18, 2, 204, '1', '1', '1', '1'),
(19, 2, 248, '1', '1', '1', '1'),
(20, 2, 284, '1', '1', '1', '1'),
(21, 2, 153, '1', '1', '1', '1'),
(22, 2, 155, '1', '1', '1', '1'),
(23, 2, 159, '1', '1', '1', '1'),
(24, 2, 161, '1', '1', '1', '1'),
(25, 2, 290, '-1', '-1', '-1', '-1'),
(26, 2, 316, '1', '1', '1', '1'),
(27, 2, 311, '1', '1', '1', '1'),
(28, 2, 313, '1', '1', '1', '1'),
(29, 2, 291, '1', '1', '1', '1'),
(30, 2, 293, '1', '1', '1', '-1'),
(31, 2, 292, '-1', '-1', '-1', '-1'),
(32, 2, 318, '1', '1', '1', '1'),
(33, 2, 321, '1', '1', '1', '1'),
(34, 2, 320, '1', '1', '1', '1'),
(35, 2, 319, '-1', '-1', '-1', '-1'),
(36, 3, 2, '-1', '-1', '-1', '-1'),
(37, 3, 117, '1', '1', '1', '1'),
(38, 3, 10, '1', '1', '1', '1'),
(39, 3, 28, '1', '1', '1', '1'),
(40, 3, 42, '1', '1', '1', '1'),
(41, 3, 52, '1', '1', '1', '1'),
(42, 3, 58, '1', '1', '1', '1'),
(43, 3, 83, '1', '1', '1', '1'),
(44, 3, 107, '1', '1', '1', '1'),
(45, 3, 140, '1', '1', '1', '1'),
(46, 3, 170, '1', '1', '1', '1'),
(47, 3, 194, '1', '1', '1', '1'),
(48, 3, 219, '1', '1', '1', '1'),
(49, 3, 204, '1', '1', '1', '1'),
(50, 3, 248, '1', '1', '1', '1'),
(51, 3, 284, '1', '1', '1', '1'),
(52, 3, 153, '1', '1', '1', '1'),
(53, 3, 155, '1', '1', '1', '1'),
(54, 3, 159, '1', '1', '1', '1'),
(55, 3, 161, '1', '1', '1', '1'),
(56, 3, 290, '-1', '-1', '-1', '-1'),
(57, 3, 311, '1', '1', '-1', '-1'),
(58, 3, 291, '1', '1', '1', '1'),
(59, 3, 293, '-1', '-1', '-1', '-1'),
(60, 3, 292, '-1', '-1', '-1', '-1'),
(61, 3, 294, '-1', '1', '-1', '-1'),
(62, 3, 304, '-1', '-1', '-1', '-1'),
(63, 3, 318, '-1', '-1', '-1', '-1'),
(64, 3, 319, '-1', '-1', '-1', '-1'),
(65, 3, 320, '1', '1', '1', '1'),
(66, 4, 2, '-1', '-1', '-1', '-1'),
(67, 4, 117, '1', '1', '1', '1'),
(68, 4, 10, '-1', '-1', '-1', '-1'),
(69, 4, 28, '-1', '-1', '-1', '-1'),
(70, 4, 42, '-1', '-1', '-1', '-1'),
(71, 4, 52, '-1', '-1', '-1', '-1'),
(72, 4, 83, '-1', '-1', '-1', '-1'),
(73, 4, 107, '-1', '-1', '-1', '-1'),
(74, 4, 140, '-1', '-1', '-1', '-1'),
(75, 4, 170, '-1', '-1', '-1', '-1'),
(76, 4, 194, '-1', '-1', '-1', '-1'),
(77, 4, 219, '-1', '-1', '-1', '-1'),
(78, 4, 204, '-1', '-1', '-1', '-1'),
(79, 4, 248, '-1', '-1', '-1', '-1'),
(80, 4, 284, '1', '1', '1', '1'),
(81, 4, 64, '1', '1', '1', '1'),
(82, 4, 70, '1', '1', '1', '1'),
(83, 4, 66, '1', '1', '1', '1'),
(84, 4, 68, '1', '1', '1', '1'),
(85, 4, 257, '1', '1', '1', '1'),
(86, 4, 290, '-1', '-1', '-1', '-1'),
(87, 4, 318, '-1', '-1', '-1', '-1'),
(88, 4, 319, '-1', '-1', '-1', '-1'),
(89, 5, 2, '-1', '-1', '-1', '-1'),
(90, 5, 117, '1', '1', '1', '1'),
(91, 5, 10, '-1', '-1', '-1', '-1'),
(92, 5, 28, '-1', '-1', '-1', '-1'),
(93, 5, 42, '-1', '-1', '-1', '-1'),
(94, 5, 52, '-1', '-1', '-1', '-1'),
(95, 5, 83, '-1', '-1', '-1', '-1'),
(96, 5, 107, '-1', '-1', '-1', '-1'),
(97, 5, 140, '-1', '-1', '-1', '-1'),
(98, 5, 170, '-1', '-1', '-1', '-1'),
(99, 5, 194, '-1', '-1', '-1', '-1'),
(100, 5, 219, '-1', '-1', '-1', '-1'),
(101, 5, 204, '-1', '-1', '-1', '-1'),
(102, 5, 248, '-1', '-1', '-1', '-1'),
(103, 5, 284, '1', '1', '1', '1'),
(104, 5, 64, '1', '1', '1', '1'),
(105, 5, 70, '1', '1', '1', '1'),
(106, 5, 66, '1', '1', '1', '1'),
(107, 5, 68, '1', '1', '1', '1'),
(108, 5, 257, '1', '1', '1', '1'),
(109, 5, 153, '1', '1', '1', '1'),
(110, 5, 155, '1', '1', '1', '1'),
(111, 5, 159, '1', '1', '1', '1'),
(112, 5, 161, '1', '1', '1', '1'),
(113, 5, 290, '-1', '-1', '-1', '-1'),
(114, 5, 317, '1', '1', '1', '1'),
(115, 5, 318, '-1', '-1', '-1', '-1'),
(116, 5, 319, '-1', '-1', '-1', '-1');
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
  `comment` text,
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
  `comment` text,
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
  `self_eval` varchar(11) NOT NULL DEFAULT '',
  `com_req` int(11) NOT NULL DEFAULT '0',
  `auto_release` int(11) NOT NULL DEFAULT '0',
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
-- Table structure for table `mixeval_question_descs`
--

DROP TABLE IF EXISTS `mixeval_question_descs`;
CREATE TABLE IF NOT EXISTS `mixeval_question_descs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL DEFAULT '0',
  `scale_level` int(11) NOT NULL DEFAULT '0',
  `descriptor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_num` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `mixeval_question_descs`
--

INSERT INTO `mixeval_question_descs` (`id`, `question_id`, `scale_level`, `descriptor`) VALUES
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
-- Table structure for table `mixeval_questions`
--

DROP TABLE IF EXISTS `mixeval_questions`;
CREATE TABLE IF NOT EXISTS `mixeval_questions` (
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
  `course_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `availability` varchar(10) NOT NULL DEFAULT 'public',
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
(21, 'email.port', '25', 'S', 'port number for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(22, 'email.host', 'localhost', 'S', 'host address for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(23, 'email.username', '', 'S', 'username for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(24, 'email.password', '', 'S', 'password for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(25, 'display.contact_info', 'noreply@ipeer.ctlt.ubc.ca', 'S', 'Contact Info', 'A', 0, NOW(), 0, NOW()),
(26, 'display.login.header', '', 'S', 'Login Info Header', 'A', 0, NOW(), 0, NOW()),
(27, 'display.login.footer', '', 'S', 'Login Info Footer', 'A', 0, NOW(), 0, NOW()),
(28, 'display.vocabulary.department', 'Department', 'S', 'Department vocabulary', 'A', 0, NOW(), 0, NOW());

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
