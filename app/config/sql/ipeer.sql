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
(2, NULL, NULL, NULL, 'controllers', 3, 582),
(3, 2, NULL, NULL, 'Pages', 4, 17),
(4, 3, NULL, NULL, 'display', 5, 6),
(5, 3, NULL, NULL, 'add', 7, 8),
(6, 3, NULL, NULL, 'edit', 9, 10),
(7, 3, NULL, NULL, 'index', 11, 12),
(8, 3, NULL, NULL, 'view', 13, 14),
(9, 3, NULL, NULL, 'delete', 15, 16),
(10, 2, NULL, NULL, 'Courses', 18, 45),
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
(21, 10, NULL, NULL, 'submitters', 39, 40),
(22, 10, NULL, NULL, 'destCourse', 41, 42),
(23, 10, NULL, NULL, 'destSurvey', 43, 44),
(24, 2, NULL, NULL, 'Departments', 46, 57),
(25, 24, NULL, NULL, 'index', 47, 48),
(26, 24, NULL, NULL, 'view', 49, 50),
(27, 24, NULL, NULL, 'add', 51, 52),
(28, 24, NULL, NULL, 'edit', 53, 54),
(29, 24, NULL, NULL, 'delete', 55, 56),
(30, 2, NULL, NULL, 'Emailer', 58, 85),
(31, 30, NULL, NULL, 'setUpAjaxList', 59, 60),
(32, 30, NULL, NULL, 'ajaxList', 61, 62),
(33, 30, NULL, NULL, 'index', 63, 64),
(34, 30, NULL, NULL, 'write', 65, 66),
(35, 30, NULL, NULL, 'cancel', 67, 68),
(36, 30, NULL, NULL, 'view', 69, 70),
(37, 30, NULL, NULL, 'addRecipient', 71, 72),
(38, 30, NULL, NULL, 'deleteRecipient', 73, 74),
(39, 30, NULL, NULL, 'getRecipient', 75, 76),
(40, 30, NULL, NULL, 'searchByUserId', 77, 78),
(41, 30, NULL, NULL, 'add', 79, 80),
(42, 30, NULL, NULL, 'edit', 81, 82),
(43, 30, NULL, NULL, 'delete', 83, 84),
(44, 2, NULL, NULL, 'Emailtemplates', 86, 105),
(45, 44, NULL, NULL, 'setUpAjaxList', 87, 88),
(46, 44, NULL, NULL, 'ajaxList', 89, 90),
(47, 44, NULL, NULL, 'index', 91, 92),
(48, 44, NULL, NULL, 'add', 93, 94),
(49, 44, NULL, NULL, 'edit', 95, 96),
(50, 44, NULL, NULL, 'delete', 97, 98),
(51, 44, NULL, NULL, 'view', 99, 100),
(52, 44, NULL, NULL, 'displayTemplateContent', 101, 102),
(53, 44, NULL, NULL, 'displayTemplateSubject', 103, 104),
(54, 2, NULL, NULL, 'Evaltools', 106, 117),
(55, 54, NULL, NULL, 'index', 107, 108),
(56, 54, NULL, NULL, 'add', 109, 110),
(57, 54, NULL, NULL, 'edit', 111, 112),
(58, 54, NULL, NULL, 'view', 113, 114),
(59, 54, NULL, NULL, 'delete', 115, 116),
(60, 2, NULL, NULL, 'Evaluations', 118, 167),
(61, 60, NULL, NULL, 'setUpAjaxList', 119, 120),
(62, 60, NULL, NULL, 'ajaxList', 121, 122),
(63, 60, NULL, NULL, 'view', 123, 124),
(64, 60, NULL, NULL, 'index', 125, 126),
(65, 60, NULL, NULL, 'export', 127, 128),
(66, 60, NULL, NULL, 'makeEvaluation', 129, 130),
(67, 60, NULL, NULL, 'validRubricEvalComplete', 131, 132),
(68, 60, NULL, NULL, 'completeEvaluationRubric', 133, 134),
(69, 60, NULL, NULL, 'validMixevalEvalComplete', 135, 136),
(70, 60, NULL, NULL, 'completeEvaluationMixeval', 137, 138),
(71, 60, NULL, NULL, 'viewEvaluationResults', 139, 140),
(72, 60, NULL, NULL, 'studentViewEvaluationResult', 141, 142),
(73, 60, NULL, NULL, 'markEventReviewed', 143, 144),
(74, 60, NULL, NULL, 'markGradeRelease', 145, 146),
(75, 60, NULL, NULL, 'markCommentRelease', 147, 148),
(76, 60, NULL, NULL, 'changeAllCommentRelease', 149, 150),
(77, 60, NULL, NULL, 'changeAllGradeRelease', 151, 152),
(78, 60, NULL, NULL, 'viewGroupSubmissionDetails', 153, 154),
(79, 60, NULL, NULL, 'viewSurveySummary', 155, 156),
(80, 60, NULL, NULL, 'export_rubic', 157, 158),
(81, 60, NULL, NULL, 'export_test', 159, 160),
(82, 60, NULL, NULL, 'add', 161, 162),
(83, 60, NULL, NULL, 'edit', 163, 164),
(84, 60, NULL, NULL, 'delete', 165, 166),
(85, 2, NULL, NULL, 'Events', 168, 187),
(86, 85, NULL, NULL, 'postProcessData', 169, 170),
(87, 85, NULL, NULL, 'setUpAjaxList', 171, 172),
(88, 85, NULL, NULL, 'index', 173, 174),
(89, 85, NULL, NULL, 'ajaxList', 175, 176),
(90, 85, NULL, NULL, 'view', 177, 178),
(91, 85, NULL, NULL, 'add', 179, 180),
(92, 85, NULL, NULL, 'edit', 181, 182),
(93, 85, NULL, NULL, 'delete', 183, 184),
(94, 85, NULL, NULL, 'checkDuplicateName', 185, 186),
(95, 2, NULL, NULL, 'Faculties', 188, 199),
(96, 95, NULL, NULL, 'index', 189, 190),
(97, 95, NULL, NULL, 'view', 191, 192),
(98, 95, NULL, NULL, 'add', 193, 194),
(99, 95, NULL, NULL, 'edit', 195, 196),
(100, 95, NULL, NULL, 'delete', 197, 198),
(101, 2, NULL, NULL, 'Framework', 200, 215),
(102, 101, NULL, NULL, 'calendarDisplay', 201, 202),
(103, 101, NULL, NULL, 'tutIndex', 203, 204),
(104, 101, NULL, NULL, 'add', 205, 206),
(105, 101, NULL, NULL, 'edit', 207, 208),
(106, 101, NULL, NULL, 'index', 209, 210),
(107, 101, NULL, NULL, 'view', 211, 212),
(108, 101, NULL, NULL, 'delete', 213, 214),
(109, 2, NULL, NULL, 'Groups', 216, 235),
(110, 109, NULL, NULL, 'setUpAjaxList', 217, 218),
(111, 109, NULL, NULL, 'index', 219, 220),
(112, 109, NULL, NULL, 'ajaxList', 221, 222),
(113, 109, NULL, NULL, 'view', 223, 224),
(114, 109, NULL, NULL, 'add', 225, 226),
(115, 109, NULL, NULL, 'edit', 227, 228),
(116, 109, NULL, NULL, 'delete', 229, 230),
(117, 109, NULL, NULL, 'import', 231, 232),
(118, 109, NULL, NULL, 'export', 233, 234),
(119, 2, NULL, NULL, 'Home', 236, 247),
(120, 119, NULL, NULL, 'index', 237, 238),
(121, 119, NULL, NULL, 'add', 239, 240),
(122, 119, NULL, NULL, 'edit', 241, 242),
(123, 119, NULL, NULL, 'view', 243, 244),
(124, 119, NULL, NULL, 'delete', 245, 246),
(125, 2, NULL, NULL, 'Install', 248, 269),
(126, 125, NULL, NULL, 'index', 249, 250),
(127, 125, NULL, NULL, 'install2', 251, 252),
(128, 125, NULL, NULL, 'install3', 253, 254),
(129, 125, NULL, NULL, 'install4', 255, 256),
(130, 125, NULL, NULL, 'install5', 257, 258),
(131, 125, NULL, NULL, 'gpl', 259, 260),
(132, 125, NULL, NULL, 'add', 261, 262),
(133, 125, NULL, NULL, 'edit', 263, 264),
(134, 125, NULL, NULL, 'view', 265, 266),
(135, 125, NULL, NULL, 'delete', 267, 268),
(136, 2, NULL, NULL, 'Lti', 270, 281),
(137, 136, NULL, NULL, 'index', 271, 272),
(138, 136, NULL, NULL, 'add', 273, 274),
(139, 136, NULL, NULL, 'edit', 275, 276),
(140, 136, NULL, NULL, 'view', 277, 278),
(141, 136, NULL, NULL, 'delete', 279, 280),
(142, 2, NULL, NULL, 'Mixevals', 282, 303),
(143, 142, NULL, NULL, 'setUpAjaxList', 283, 284),
(144, 142, NULL, NULL, 'index', 285, 286),
(145, 142, NULL, NULL, 'ajaxList', 287, 288),
(146, 142, NULL, NULL, 'view', 289, 290),
(147, 142, NULL, NULL, 'add', 291, 292),
(148, 142, NULL, NULL, 'deleteQuestion', 293, 294),
(149, 142, NULL, NULL, 'deleteDescriptor', 295, 296),
(150, 142, NULL, NULL, 'edit', 297, 298),
(151, 142, NULL, NULL, 'copy', 299, 300),
(152, 142, NULL, NULL, 'delete', 301, 302),
(153, 2, NULL, NULL, 'Oauthclients', 304, 315),
(154, 153, NULL, NULL, 'index', 305, 306),
(155, 153, NULL, NULL, 'add', 307, 308),
(156, 153, NULL, NULL, 'edit', 309, 310),
(157, 153, NULL, NULL, 'delete', 311, 312),
(158, 153, NULL, NULL, 'view', 313, 314),
(159, 2, NULL, NULL, 'Oauthtokens', 316, 327),
(160, 159, NULL, NULL, 'index', 317, 318),
(161, 159, NULL, NULL, 'add', 319, 320),
(162, 159, NULL, NULL, 'edit', 321, 322),
(163, 159, NULL, NULL, 'delete', 323, 324),
(164, 159, NULL, NULL, 'view', 325, 326),
(165, 2, NULL, NULL, 'Penalty', 328, 341),
(166, 165, NULL, NULL, 'save', 329, 330),
(167, 165, NULL, NULL, 'add', 331, 332),
(168, 165, NULL, NULL, 'edit', 333, 334),
(169, 165, NULL, NULL, 'index', 335, 336),
(170, 165, NULL, NULL, 'view', 337, 338),
(171, 165, NULL, NULL, 'delete', 339, 340),
(172, 2, NULL, NULL, 'Rubrics', 342, 361),
(173, 172, NULL, NULL, 'postProcess', 343, 344),
(174, 172, NULL, NULL, 'setUpAjaxList', 345, 346),
(175, 172, NULL, NULL, 'index', 347, 348),
(176, 172, NULL, NULL, 'ajaxList', 349, 350),
(177, 172, NULL, NULL, 'view', 351, 352),
(178, 172, NULL, NULL, 'add', 353, 354),
(179, 172, NULL, NULL, 'edit', 355, 356),
(180, 172, NULL, NULL, 'copy', 357, 358),
(181, 172, NULL, NULL, 'delete', 359, 360),
(182, 2, NULL, NULL, 'Searchs', 362, 389),
(183, 182, NULL, NULL, 'update', 363, 364),
(184, 182, NULL, NULL, 'index', 365, 366),
(185, 182, NULL, NULL, 'searchEvaluation', 367, 368),
(186, 182, NULL, NULL, 'searchResult', 369, 370),
(187, 182, NULL, NULL, 'searchInstructor', 371, 372),
(188, 182, NULL, NULL, 'eventBoxSearch', 373, 374),
(189, 182, NULL, NULL, 'formatSearchEvaluation', 375, 376),
(190, 182, NULL, NULL, 'formatSearchInstructor', 377, 378),
(191, 182, NULL, NULL, 'formatSearchEvaluationResult', 379, 380),
(192, 182, NULL, NULL, 'add', 381, 382),
(193, 182, NULL, NULL, 'edit', 383, 384),
(194, 182, NULL, NULL, 'view', 385, 386),
(195, 182, NULL, NULL, 'delete', 387, 388),
(196, 2, NULL, NULL, 'Simpleevaluations', 390, 409),
(197, 196, NULL, NULL, 'postProcess', 391, 392),
(198, 196, NULL, NULL, 'setUpAjaxList', 393, 394),
(199, 196, NULL, NULL, 'index', 395, 396),
(200, 196, NULL, NULL, 'ajaxList', 397, 398),
(201, 196, NULL, NULL, 'view', 399, 400),
(202, 196, NULL, NULL, 'add', 401, 402),
(203, 196, NULL, NULL, 'edit', 403, 404),
(204, 196, NULL, NULL, 'copy', 405, 406),
(205, 196, NULL, NULL, 'delete', 407, 408),
(206, 2, NULL, NULL, 'Surveygroups', 410, 439),
(207, 206, NULL, NULL, 'postProcess', 411, 412),
(208, 206, NULL, NULL, 'setUpAjaxList', 413, 414),
(209, 206, NULL, NULL, 'index', 415, 416),
(210, 206, NULL, NULL, 'ajaxList', 417, 418),
(211, 206, NULL, NULL, 'makegroups', 419, 420),
(212, 206, NULL, NULL, 'makegroupssearch', 421, 422),
(213, 206, NULL, NULL, 'maketmgroups', 423, 424),
(214, 206, NULL, NULL, 'savegroups', 425, 426),
(215, 206, NULL, NULL, 'release', 427, 428),
(216, 206, NULL, NULL, 'delete', 429, 430),
(217, 206, NULL, NULL, 'edit', 431, 432),
(218, 206, NULL, NULL, 'changegroupset', 433, 434),
(219, 206, NULL, NULL, 'add', 435, 436),
(220, 206, NULL, NULL, 'view', 437, 438),
(221, 2, NULL, NULL, 'Surveys', 440, 467),
(222, 221, NULL, NULL, 'setUpAjaxList', 441, 442),
(223, 221, NULL, NULL, 'index', 443, 444),
(224, 221, NULL, NULL, 'ajaxList', 445, 446),
(225, 221, NULL, NULL, 'view', 447, 448),
(226, 221, NULL, NULL, 'add', 449, 450),
(227, 221, NULL, NULL, 'edit', 451, 452),
(228, 221, NULL, NULL, 'copy', 453, 454),
(229, 221, NULL, NULL, 'delete', 455, 456),
(230, 221, NULL, NULL, 'questionsSummary', 457, 458),
(231, 221, NULL, NULL, 'moveQuestion', 459, 460),
(232, 221, NULL, NULL, 'removeQuestion', 461, 462),
(233, 221, NULL, NULL, 'addQuestion', 463, 464),
(234, 221, NULL, NULL, 'editQuestion', 465, 466),
(235, 2, NULL, NULL, 'Sysparameters', 468, 483),
(236, 235, NULL, NULL, 'setUpAjaxList', 469, 470),
(237, 235, NULL, NULL, 'index', 471, 472),
(238, 235, NULL, NULL, 'ajaxList', 473, 474),
(239, 235, NULL, NULL, 'view', 475, 476),
(240, 235, NULL, NULL, 'add', 477, 478),
(241, 235, NULL, NULL, 'edit', 479, 480),
(242, 235, NULL, NULL, 'delete', 481, 482),
(243, 2, NULL, NULL, 'Upgrade', 484, 497),
(244, 243, NULL, NULL, 'index', 485, 486),
(245, 243, NULL, NULL, 'step2', 487, 488),
(246, 243, NULL, NULL, 'add', 489, 490),
(247, 243, NULL, NULL, 'edit', 491, 492),
(248, 243, NULL, NULL, 'view', 493, 494),
(249, 243, NULL, NULL, 'delete', 495, 496),
(250, 2, NULL, NULL, 'Users', 498, 527),
(251, 250, NULL, NULL, 'ajaxList', 499, 500),
(252, 250, NULL, NULL, 'index', 501, 502),
(253, 250, NULL, NULL, 'goToClassList', 503, 504),
(254, 250, NULL, NULL, 'determineIfStudentFromThisData', 505, 506),
(255, 250, NULL, NULL, 'view', 507, 508),
(256, 250, NULL, NULL, 'add', 509, 510),
(257, 250, NULL, NULL, 'enrol', 511, 512),
(258, 250, NULL, NULL, 'edit', 513, 514),
(259, 250, NULL, NULL, 'editProfile', 515, 516),
(260, 250, NULL, NULL, 'delete', 517, 518),
(261, 250, NULL, NULL, 'checkDuplicateName', 519, 520),
(262, 250, NULL, NULL, 'resetPassword', 521, 522),
(263, 250, NULL, NULL, 'import', 523, 524),
(264, 250, NULL, NULL, 'update', 525, 526),
(265, 2, NULL, NULL, 'V1', 528, 563),
(266, 265, NULL, NULL, 'oauth', 529, 530),
(267, 265, NULL, NULL, 'oauth_error', 531, 532),
(268, 265, NULL, NULL, 'users', 533, 534),
(269, 265, NULL, NULL, 'courses', 535, 536),
(270, 265, NULL, NULL, 'groups', 537, 538),
(271, 265, NULL, NULL, 'groupMembers', 539, 540),
(272, 265, NULL, NULL, 'events', 541, 542),
(273, 265, NULL, NULL, 'grades', 543, 544),
(274, 265, NULL, NULL, 'departments', 545, 546),
(275, 265, NULL, NULL, 'courseDepartments', 547, 548),
(276, 265, NULL, NULL, 'userEvents', 549, 550),
(277, 265, NULL, NULL, 'enrolment', 551, 552),
(278, 265, NULL, NULL, 'add', 553, 554),
(279, 265, NULL, NULL, 'edit', 555, 556),
(280, 265, NULL, NULL, 'index', 557, 558),
(281, 265, NULL, NULL, 'view', 559, 560),
(282, 265, NULL, NULL, 'delete', 561, 562),
(283, 2, NULL, NULL, 'Guard', 564, 581),
(284, 283, NULL, NULL, 'Guard', 565, 580),
(285, 284, NULL, NULL, 'login', 566, 567),
(286, 284, NULL, NULL, 'logout', 568, 569),
(287, 284, NULL, NULL, 'add', 570, 571),
(288, 284, NULL, NULL, 'edit', 572, 573),
(289, 284, NULL, NULL, 'index', 574, 575),
(290, 284, NULL, NULL, 'view', 576, 577),
(291, 284, NULL, NULL, 'delete', 578, 579),
(292, NULL, NULL, NULL, 'functions', 583, 646),
(293, 292, NULL, NULL, 'user', 584, 611),
(294, 293, NULL, NULL, 'superadmin', 585, 586),
(295, 293, NULL, NULL, 'admin', 587, 588),
(296, 293, NULL, NULL, 'instructor', 589, 590),
(297, 293, NULL, NULL, 'tutor', 591, 592),
(298, 293, NULL, NULL, 'student', 593, 594),
(299, 293, NULL, NULL, 'import', 595, 596),
(300, 293, NULL, NULL, 'password_reset', 597, 608),
(301, 300, NULL, NULL, 'superadmin', 598, 599),
(302, 300, NULL, NULL, 'admin', 600, 601),
(303, 300, NULL, NULL, 'instructor', 602, 603),
(304, 300, NULL, NULL, 'tutor', 604, 605),
(305, 300, NULL, NULL, 'student', 606, 607),
(306, 293, NULL, NULL, 'index', 609, 610),
(307, 292, NULL, NULL, 'role', 612, 623),
(308, 307, NULL, NULL, 'superadmin', 613, 614),
(309, 307, NULL, NULL, 'admin', 615, 616),
(310, 307, NULL, NULL, 'instructor', 617, 618),
(311, 307, NULL, NULL, 'tutor', 619, 620),
(312, 307, NULL, NULL, 'student', 621, 622),
(313, 292, NULL, NULL, 'evaluation', 624, 625),
(314, 292, NULL, NULL, 'email', 626, 633),
(315, 314, NULL, NULL, 'allUsers', 627, 628),
(316, 314, NULL, NULL, 'allGroups', 629, 630),
(317, 314, NULL, NULL, 'allCourses', 631, 632),
(318, 292, NULL, NULL, 'emailtemplate', 634, 635),
(319, 292, NULL, NULL, 'viewstudentresults', 636, 637),
(320, 292, NULL, NULL, 'viewemailaddresses', 638, 639),
(321, 292, NULL, NULL, 'superadmin', 640, 641),
(322, 292, NULL, NULL, 'coursemanager', 642, 643),
(323, 292, NULL, NULL, 'viewusername', 644, 645);


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
(2, 1, 292, '1', '1', '1', '1'),
(3, 1, 1, '1', '1', '1', '1'),
(4, 2, 2, '-1', '-1', '-1', '-1'),
(5, 2, 119, '1', '1', '1', '1'),
(6, 2, 10, '1', '1', '1', '1'),
(7, 2, 24, '1', '1', '1', '1'),
(8, 2, 30, '1', '1', '1', '1'),
(9, 2, 44, '1', '1', '1', '1'),
(10, 2, 54, '1', '1', '1', '1'),
(11, 2, 60, '1', '1', '1', '1'),
(12, 2, 85, '1', '1', '1', '1'),
(13, 2, 109, '1', '1', '1', '1'),
(14, 2, 142, '1', '1', '1', '1'),
(15, 2, 172, '1', '1', '1', '1'),
(16, 2, 196, '1', '1', '1', '1'),
(17, 2, 221, '1', '1', '1', '1'),
(18, 2, 206, '1', '1', '1', '1'),
(19, 2, 250, '1', '1', '1', '1'),
(20, 2, 286, '1', '1', '1', '1'),
(21, 2, 155, '1', '1', '1', '1'),
(22, 2, 157, '1', '1', '1', '1'),
(23, 2, 161, '1', '1', '1', '1'),
(24, 2, 163, '1', '1', '1', '1'),
(25, 2, 292, '-1', '-1', '-1', '-1'),
(26, 2, 318, '1', '1', '1', '1'),
(27, 2, 313, '1', '1', '1', '1'),
(28, 2, 315, '1', '1', '1', '1'),
(29, 2, 293, '1', '1', '1', '1'),
(30, 2, 295, '1', '1', '1', '-1'),
(31, 2, 294, '-1', '-1', '-1', '-1'),
(32, 2, 320, '1', '1', '1', '1'),
(33, 2, 323, '1', '1', '1', '1'),
(34, 2, 322, '1', '1', '1', '1'),
(35, 2, 321, '-1', '-1', '-1', '-1'),
(36, 3, 2, '-1', '-1', '-1', '-1'),
(37, 3, 119, '1', '1', '1', '1'),
(38, 3, 10, '1', '1', '1', '1'),
(39, 3, 30, '1', '1', '1', '1'),
(40, 3, 44, '1', '1', '1', '1'),
(41, 3, 54, '1', '1', '1', '1'),
(42, 3, 60, '1', '1', '1', '1'),
(43, 3, 85, '1', '1', '1', '1'),
(44, 3, 109, '1', '1', '1', '1'),
(45, 3, 142, '1', '1', '1', '1'),
(46, 3, 172, '1', '1', '1', '1'),
(47, 3, 196, '1', '1', '1', '1'),
(48, 3, 221, '1', '1', '1', '1'),
(49, 3, 206, '1', '1', '1', '1'),
(50, 3, 250, '1', '1', '1', '1'),
(51, 3, 286, '1', '1', '1', '1'),
(52, 3, 155, '1', '1', '1', '1'),
(53, 3, 157, '1', '1', '1', '1'),
(54, 3, 161, '1', '1', '1', '1'),
(55, 3, 163, '1', '1', '1', '1'),
(56, 3, 292, '-1', '-1', '-1', '-1'),
(57, 3, 313, '1', '1', '-1', '-1'),
(58, 3, 293, '1', '1', '1', '1'),
(59, 3, 295, '-1', '-1', '-1', '-1'),
(60, 3, 294, '-1', '-1', '-1', '-1'),
(61, 3, 296, '-1', '1', '-1', '-1'),
(62, 3, 306, '-1', '-1', '-1', '-1'),
(63, 3, 320, '-1', '-1', '-1', '-1'),
(64, 3, 321, '-1', '-1', '-1', '-1'),
(65, 3, 322, '1', '1', '1', '1'),
(66, 4, 2, '-1', '-1', '-1', '-1'),
(67, 4, 119, '1', '1', '1', '1'),
(68, 4, 10, '-1', '-1', '-1', '-1'),
(69, 4, 30, '-1', '-1', '-1', '-1'),
(70, 4, 44, '-1', '-1', '-1', '-1'),
(71, 4, 54, '-1', '-1', '-1', '-1'),
(72, 4, 85, '-1', '-1', '-1', '-1'),
(73, 4, 109, '-1', '-1', '-1', '-1'),
(74, 4, 142, '-1', '-1', '-1', '-1'),
(75, 4, 172, '-1', '-1', '-1', '-1'),
(76, 4, 196, '-1', '-1', '-1', '-1'),
(77, 4, 221, '-1', '-1', '-1', '-1'),
(78, 4, 206, '-1', '-1', '-1', '-1'),
(79, 4, 250, '-1', '-1', '-1', '-1'),
(80, 4, 286, '1', '1', '1', '1'),
(81, 4, 66, '1', '1', '1', '1'),
(82, 4, 72, '1', '1', '1', '1'),
(83, 4, 68, '1', '1', '1', '1'),
(84, 4, 70, '1', '1', '1', '1'),
(85, 4, 259, '1', '1', '1', '1'),
(86, 4, 292, '-1', '-1', '-1', '-1'),
(87, 4, 320, '-1', '-1', '-1', '-1'),
(88, 4, 321, '-1', '-1', '-1', '-1'),
(89, 5, 2, '-1', '-1', '-1', '-1'),
(90, 5, 119, '1', '1', '1', '1'),
(91, 5, 10, '-1', '-1', '-1', '-1'),
(92, 5, 30, '-1', '-1', '-1', '-1'),
(93, 5, 44, '-1', '-1', '-1', '-1'),
(94, 5, 54, '-1', '-1', '-1', '-1'),
(95, 5, 85, '-1', '-1', '-1', '-1'),
(96, 5, 109, '-1', '-1', '-1', '-1'),
(97, 5, 142, '-1', '-1', '-1', '-1'),
(98, 5, 172, '-1', '-1', '-1', '-1'),
(99, 5, 196, '-1', '-1', '-1', '-1'),
(100, 5, 221, '-1', '-1', '-1', '-1'),
(101, 5, 206, '-1', '-1', '-1', '-1'),
(102, 5, 250, '-1', '-1', '-1', '-1'),
(103, 5, 286, '1', '1', '1', '1'),
(104, 5, 66, '1', '1', '1', '1'),
(105, 5, 72, '1', '1', '1', '1'),
(106, 5, 68, '1', '1', '1', '1'),
(107, 5, 70, '1', '1', '1', '1'),
(108, 5, 259, '1', '1', '1', '1'),
(109, 5, 155, '1', '1', '1', '1'),
(110, 5, 157, '1', '1', '1', '1'),
(111, 5, 161, '1', '1', '1', '1'),
(112, 5, 163, '1', '1', '1', '1'),
(113, 5, 292, '-1', '-1', '-1', '-1'),
(114, 5, 319, '1', '1', '1', '1'),
(115, 5, 320, '-1', '-1', '-1', '-1'),
(116, 5, 321, '-1', '-1', '-1', '-1');

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
