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

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, NULL, NULL, 'adminpage', 1, 2),
(2, NULL, NULL, NULL, 'controllers', 3, 574),
(3, 2, NULL, NULL, 'Pages', 4, 17),
(4, 3, NULL, NULL, 'display', 5, 6),
(5, 3, NULL, NULL, 'add', 7, 8),
(6, 3, NULL, NULL, 'edit', 9, 10),
(7, 3, NULL, NULL, 'index', 11, 12),
(8, 3, NULL, NULL, 'view', 13, 14),
(9, 3, NULL, NULL, 'delete', 15, 16),
(10, 2, NULL, NULL, 'Evaltools', 18, 31),
(11, 10, NULL, NULL, 'index', 19, 20),
(12, 10, NULL, NULL, 'showAll', 21, 22),
(13, 10, NULL, NULL, 'add', 23, 24),
(14, 10, NULL, NULL, 'edit', 25, 26),
(15, 10, NULL, NULL, 'view', 27, 28),
(16, 10, NULL, NULL, 'delete', 29, 30),
(17, 2, NULL, NULL, 'Emailer', 32, 59),
(18, 17, NULL, NULL, 'setUpAjaxList', 33, 34),
(19, 17, NULL, NULL, 'ajaxList', 35, 36),
(20, 17, NULL, NULL, 'index', 37, 38),
(21, 17, NULL, NULL, 'write', 39, 40),
(22, 17, NULL, NULL, 'cancel', 41, 42),
(23, 17, NULL, NULL, 'view', 43, 44),
(24, 17, NULL, NULL, 'addRecipient', 45, 46),
(25, 17, NULL, NULL, 'deleteRecipient', 47, 48),
(26, 17, NULL, NULL, 'getRecipient', 49, 50),
(27, 17, NULL, NULL, 'searchByUserId', 51, 52),
(28, 17, NULL, NULL, 'add', 53, 54),
(29, 17, NULL, NULL, 'edit', 55, 56),
(30, 17, NULL, NULL, 'delete', 57, 58),
(31, 2, NULL, NULL, 'Users', 60, 87),
(32, 31, NULL, NULL, 'ajaxList', 61, 62),
(33, 31, NULL, NULL, 'index', 63, 64),
(34, 31, NULL, NULL, 'goToClassList', 65, 66),
(35, 31, NULL, NULL, 'determineIfStudentFromThisData', 67, 68),
(36, 31, NULL, NULL, 'view', 69, 70),
(37, 31, NULL, NULL, 'add', 71, 72),
(38, 31, NULL, NULL, 'edit', 73, 74),
(39, 31, NULL, NULL, 'editProfile', 75, 76),
(40, 31, NULL, NULL, 'delete', 77, 78),
(41, 31, NULL, NULL, 'checkDuplicateName', 79, 80),
(42, 31, NULL, NULL, 'resetPassword', 81, 82),
(43, 31, NULL, NULL, 'import', 83, 84),
(44, 31, NULL, NULL, 'update', 85, 86),
(45, 2, NULL, NULL, 'Mixevals', 88, 109),
(46, 45, NULL, NULL, 'setUpAjaxList', 89, 90),
(47, 45, NULL, NULL, 'index', 91, 92),
(48, 45, NULL, NULL, 'ajaxList', 93, 94),
(49, 45, NULL, NULL, 'view', 95, 96),
(50, 45, NULL, NULL, 'add', 97, 98),
(51, 45, NULL, NULL, 'deleteQuestion', 99, 100),
(52, 45, NULL, NULL, 'deleteDescriptor', 101, 102),
(53, 45, NULL, NULL, 'edit', 103, 104),
(54, 45, NULL, NULL, 'copy', 105, 106),
(55, 45, NULL, NULL, 'delete', 107, 108),
(56, 2, NULL, NULL, 'V1', 110, 145),
(57, 56, NULL, NULL, 'oauth', 111, 112),
(58, 56, NULL, NULL, 'oauth_error', 113, 114),
(59, 56, NULL, NULL, 'users', 115, 116),
(60, 56, NULL, NULL, 'courses', 117, 118),
(61, 56, NULL, NULL, 'groups', 119, 120),
(62, 56, NULL, NULL, 'groupMembers', 121, 122),
(63, 56, NULL, NULL, 'events', 123, 124),
(64, 56, NULL, NULL, 'grades', 125, 126),
(65, 56, NULL, NULL, 'departments', 127, 128),
(66, 56, NULL, NULL, 'courseDepartments', 129, 130),
(67, 56, NULL, NULL, 'userEvents', 131, 132),
(68, 56, NULL, NULL, 'enrolment', 133, 134),
(69, 56, NULL, NULL, 'add', 135, 136),
(70, 56, NULL, NULL, 'edit', 137, 138),
(71, 56, NULL, NULL, 'index', 139, 140),
(72, 56, NULL, NULL, 'view', 141, 142),
(73, 56, NULL, NULL, 'delete', 143, 144),
(74, 2, NULL, NULL, 'Simpleevaluations', 146, 165),
(75, 74, NULL, NULL, 'postProcess', 147, 148),
(76, 74, NULL, NULL, 'setUpAjaxList', 149, 150),
(77, 74, NULL, NULL, 'index', 151, 152),
(78, 74, NULL, NULL, 'ajaxList', 153, 154),
(79, 74, NULL, NULL, 'view', 155, 156),
(80, 74, NULL, NULL, 'add', 157, 158),
(81, 74, NULL, NULL, 'edit', 159, 160),
(82, 74, NULL, NULL, 'copy', 161, 162),
(83, 74, NULL, NULL, 'delete', 163, 164),
(84, 2, NULL, NULL, 'Penalty', 166, 179),
(85, 84, NULL, NULL, 'save', 167, 168),
(86, 84, NULL, NULL, 'add', 169, 170),
(87, 84, NULL, NULL, 'edit', 171, 172),
(88, 84, NULL, NULL, 'index', 173, 174),
(89, 84, NULL, NULL, 'view', 175, 176),
(90, 84, NULL, NULL, 'delete', 177, 178),
(91, 2, NULL, NULL, 'Home', 180, 191),
(92, 91, NULL, NULL, 'index', 181, 182),
(93, 91, NULL, NULL, 'add', 183, 184),
(94, 91, NULL, NULL, 'edit', 185, 186),
(95, 91, NULL, NULL, 'view', 187, 188),
(96, 91, NULL, NULL, 'delete', 189, 190),
(97, 2, NULL, NULL, 'Oauthclients', 192, 203),
(98, 97, NULL, NULL, 'index', 193, 194),
(99, 97, NULL, NULL, 'add', 195, 196),
(100, 97, NULL, NULL, 'edit', 197, 198),
(101, 97, NULL, NULL, 'delete', 199, 200),
(102, 97, NULL, NULL, 'view', 201, 202),
(103, 2, NULL, NULL, 'Surveys', 204, 231),
(104, 103, NULL, NULL, 'setUpAjaxList', 205, 206),
(105, 103, NULL, NULL, 'index', 207, 208),
(106, 103, NULL, NULL, 'ajaxList', 209, 210),
(107, 103, NULL, NULL, 'view', 211, 212),
(108, 103, NULL, NULL, 'add', 213, 214),
(109, 103, NULL, NULL, 'edit', 215, 216),
(110, 103, NULL, NULL, 'copy', 217, 218),
(111, 103, NULL, NULL, 'delete', 219, 220),
(112, 103, NULL, NULL, 'questionsSummary', 221, 222),
(113, 103, NULL, NULL, 'moveQuestion', 223, 224),
(114, 103, NULL, NULL, 'removeQuestion', 225, 226),
(115, 103, NULL, NULL, 'addQuestion', 227, 228),
(116, 103, NULL, NULL, 'editQuestion', 229, 230),
(117, 2, NULL, NULL, 'Evaluations', 232, 283),
(118, 117, NULL, NULL, 'setUpAjaxList', 233, 234),
(119, 117, NULL, NULL, 'ajaxList', 235, 236),
(120, 117, NULL, NULL, 'view', 237, 238),
(121, 117, NULL, NULL, 'index', 239, 240),
(122, 117, NULL, NULL, 'export', 241, 242),
(123, 117, NULL, NULL, 'makeEvaluation', 243, 244),
(124, 117, NULL, NULL, 'validSurveyEvalComplete', 245, 246),
(125, 117, NULL, NULL, 'validRubricEvalComplete', 247, 248),
(126, 117, NULL, NULL, 'completeEvaluationRubric', 249, 250),
(127, 117, NULL, NULL, 'validMixevalEvalComplete', 251, 252),
(128, 117, NULL, NULL, 'completeEvaluationMixeval', 253, 254),
(129, 117, NULL, NULL, 'viewEvaluationResults', 255, 256),
(130, 117, NULL, NULL, 'studentViewEvaluationResult', 257, 258),
(131, 117, NULL, NULL, 'markEventReviewed', 259, 260),
(132, 117, NULL, NULL, 'markGradeRelease', 261, 262),
(133, 117, NULL, NULL, 'markCommentRelease', 263, 264),
(134, 117, NULL, NULL, 'changeAllCommentRelease', 265, 266),
(135, 117, NULL, NULL, 'changeAllGradeRelease', 267, 268),
(136, 117, NULL, NULL, 'viewGroupSubmissionDetails', 269, 270),
(137, 117, NULL, NULL, 'viewSurveySummary', 271, 272),
(138, 117, NULL, NULL, 'export_rubic', 273, 274),
(139, 117, NULL, NULL, 'export_test', 275, 276),
(140, 117, NULL, NULL, 'add', 277, 278),
(141, 117, NULL, NULL, 'edit', 279, 280),
(142, 117, NULL, NULL, 'delete', 281, 282),
(143, 2, NULL, NULL, 'Rubrics', 284, 303),
(144, 143, NULL, NULL, 'postProcess', 285, 286),
(145, 143, NULL, NULL, 'setUpAjaxList', 287, 288),
(146, 143, NULL, NULL, 'index', 289, 290),
(147, 143, NULL, NULL, 'ajaxList', 291, 292),
(148, 143, NULL, NULL, 'view', 293, 294),
(149, 143, NULL, NULL, 'add', 295, 296),
(150, 143, NULL, NULL, 'edit', 297, 298),
(151, 143, NULL, NULL, 'copy', 299, 300),
(152, 143, NULL, NULL, 'delete', 301, 302),
(153, 2, NULL, NULL, 'Emailtemplates', 304, 323),
(154, 153, NULL, NULL, 'setUpAjaxList', 305, 306),
(155, 153, NULL, NULL, 'ajaxList', 307, 308),
(156, 153, NULL, NULL, 'index', 309, 310),
(157, 153, NULL, NULL, 'add', 311, 312),
(158, 153, NULL, NULL, 'edit', 313, 314),
(159, 153, NULL, NULL, 'delete', 315, 316),
(160, 153, NULL, NULL, 'view', 317, 318),
(161, 153, NULL, NULL, 'displayTemplateContent', 319, 320),
(162, 153, NULL, NULL, 'displayTemplateSubject', 321, 322),
(163, 2, NULL, NULL, 'Faculties', 324, 335),
(164, 163, NULL, NULL, 'index', 325, 326),
(165, 163, NULL, NULL, 'view', 327, 328),
(166, 163, NULL, NULL, 'add', 329, 330),
(167, 163, NULL, NULL, 'edit', 331, 332),
(168, 163, NULL, NULL, 'delete', 333, 334),
(169, 2, NULL, NULL, 'Departments', 336, 347),
(170, 169, NULL, NULL, 'index', 337, 338),
(171, 169, NULL, NULL, 'view', 339, 340),
(172, 169, NULL, NULL, 'add', 341, 342),
(173, 169, NULL, NULL, 'edit', 343, 344),
(174, 169, NULL, NULL, 'delete', 345, 346),
(175, 2, NULL, NULL, 'Sysparameters', 348, 363),
(176, 175, NULL, NULL, 'setUpAjaxList', 349, 350),
(177, 175, NULL, NULL, 'index', 351, 352),
(178, 175, NULL, NULL, 'ajaxList', 353, 354),
(179, 175, NULL, NULL, 'view', 355, 356),
(180, 175, NULL, NULL, 'add', 357, 358),
(181, 175, NULL, NULL, 'edit', 359, 360),
(182, 175, NULL, NULL, 'delete', 361, 362),
(183, 2, NULL, NULL, 'Install', 364, 385),
(184, 183, NULL, NULL, 'index', 365, 366),
(185, 183, NULL, NULL, 'install2', 367, 368),
(186, 183, NULL, NULL, 'install3', 369, 370),
(187, 183, NULL, NULL, 'install4', 371, 372),
(188, 183, NULL, NULL, 'install5', 373, 374),
(189, 183, NULL, NULL, 'gpl', 375, 376),
(190, 183, NULL, NULL, 'add', 377, 378),
(191, 183, NULL, NULL, 'edit', 379, 380),
(192, 183, NULL, NULL, 'view', 381, 382),
(193, 183, NULL, NULL, 'delete', 383, 384),
(194, 2, NULL, NULL, 'Searchs', 386, 413),
(195, 194, NULL, NULL, 'update', 387, 388),
(196, 194, NULL, NULL, 'index', 389, 390),
(197, 194, NULL, NULL, 'searchEvaluation', 391, 392),
(198, 194, NULL, NULL, 'searchResult', 393, 394),
(199, 194, NULL, NULL, 'searchInstructor', 395, 396),
(200, 194, NULL, NULL, 'eventBoxSearch', 397, 398),
(201, 194, NULL, NULL, 'formatSearchEvaluation', 399, 400),
(202, 194, NULL, NULL, 'formatSearchInstructor', 401, 402),
(203, 194, NULL, NULL, 'formatSearchEvaluationResult', 403, 404),
(204, 194, NULL, NULL, 'add', 405, 406),
(205, 194, NULL, NULL, 'edit', 407, 408),
(206, 194, NULL, NULL, 'view', 409, 410),
(207, 194, NULL, NULL, 'delete', 411, 412),
(208, 2, NULL, NULL, 'Groups', 414, 433),
(209, 208, NULL, NULL, 'setUpAjaxList', 415, 416),
(210, 208, NULL, NULL, 'index', 417, 418),
(211, 208, NULL, NULL, 'ajaxList', 419, 420),
(212, 208, NULL, NULL, 'view', 421, 422),
(213, 208, NULL, NULL, 'add', 423, 424),
(214, 208, NULL, NULL, 'edit', 425, 426),
(215, 208, NULL, NULL, 'delete', 427, 428),
(216, 208, NULL, NULL, 'import', 429, 430),
(217, 208, NULL, NULL, 'export', 431, 432),
(218, 2, NULL, NULL, 'Oauthtokens', 434, 445),
(219, 218, NULL, NULL, 'index', 435, 436),
(220, 218, NULL, NULL, 'add', 437, 438),
(221, 218, NULL, NULL, 'edit', 439, 440),
(222, 218, NULL, NULL, 'delete', 441, 442),
(223, 218, NULL, NULL, 'view', 443, 444),
(224, 2, NULL, NULL, 'Courses', 446, 463),
(225, 224, NULL, NULL, 'daysLate', 447, 448),
(226, 224, NULL, NULL, 'index', 449, 450),
(227, 224, NULL, NULL, 'ajaxList', 451, 452),
(228, 224, NULL, NULL, 'view', 453, 454),
(229, 224, NULL, NULL, 'home', 455, 456),
(230, 224, NULL, NULL, 'add', 457, 458),
(231, 224, NULL, NULL, 'edit', 459, 460),
(232, 224, NULL, NULL, 'delete', 461, 462),
(233, 2, NULL, NULL, 'Upgrade', 464, 477),
(234, 233, NULL, NULL, 'index', 465, 466),
(235, 233, NULL, NULL, 'step2', 467, 468),
(236, 233, NULL, NULL, 'add', 469, 470),
(237, 233, NULL, NULL, 'edit', 471, 472),
(238, 233, NULL, NULL, 'view', 473, 474),
(239, 233, NULL, NULL, 'delete', 475, 476),
(240, 2, NULL, NULL, 'Surveygroups', 478, 507),
(241, 240, NULL, NULL, 'postProcess', 479, 480),
(242, 240, NULL, NULL, 'setUpAjaxList', 481, 482),
(243, 240, NULL, NULL, 'index', 483, 484),
(244, 240, NULL, NULL, 'ajaxList', 485, 486),
(245, 240, NULL, NULL, 'makegroups', 487, 488),
(246, 240, NULL, NULL, 'makegroupssearch', 489, 490),
(247, 240, NULL, NULL, 'maketmgroups', 491, 492),
(248, 240, NULL, NULL, 'savegroups', 493, 494),
(249, 240, NULL, NULL, 'release', 495, 496),
(250, 240, NULL, NULL, 'delete', 497, 498),
(251, 240, NULL, NULL, 'edit', 499, 500),
(252, 240, NULL, NULL, 'changegroupset', 501, 502),
(253, 240, NULL, NULL, 'add', 503, 504),
(254, 240, NULL, NULL, 'view', 505, 506),
(255, 2, NULL, NULL, 'Events', 508, 525),
(256, 255, NULL, NULL, 'postProcessData', 509, 510),
(257, 255, NULL, NULL, 'setUpAjaxList', 511, 512),
(258, 255, NULL, NULL, 'index', 513, 514),
(259, 255, NULL, NULL, 'ajaxList', 515, 516),
(260, 255, NULL, NULL, 'view', 517, 518),
(261, 255, NULL, NULL, 'add', 519, 520),
(262, 255, NULL, NULL, 'edit', 521, 522),
(263, 255, NULL, NULL, 'delete', 523, 524),
(264, 2, NULL, NULL, 'Framework', 526, 543),
(265, 264, NULL, NULL, 'calendarDisplay', 527, 528),
(266, 264, NULL, NULL, 'userInfoDisplay', 529, 530),
(267, 264, NULL, NULL, 'tutIndex', 531, 532),
(268, 264, NULL, NULL, 'add', 533, 534),
(269, 264, NULL, NULL, 'edit', 535, 536),
(270, 264, NULL, NULL, 'index', 537, 538),
(271, 264, NULL, NULL, 'view', 539, 540),
(272, 264, NULL, NULL, 'delete', 541, 542),
(273, 2, NULL, NULL, 'Lti', 544, 555),
(274, 273, NULL, NULL, 'index', 545, 546),
(275, 273, NULL, NULL, 'add', 547, 548),
(276, 273, NULL, NULL, 'edit', 549, 550),
(277, 273, NULL, NULL, 'view', 551, 552),
(278, 273, NULL, NULL, 'delete', 553, 554),
(279, 2, NULL, NULL, 'Guard', 556, 573),
(280, 279, NULL, NULL, 'Guard', 557, 572),
(281, 280, NULL, NULL, 'login', 558, 559),
(282, 280, NULL, NULL, 'logout', 560, 561),
(283, 280, NULL, NULL, 'add', 562, 563),
(284, 280, NULL, NULL, 'edit', 564, 565),
(285, 280, NULL, NULL, 'index', 566, 567),
(286, 280, NULL, NULL, 'view', 568, 569),
(287, 280, NULL, NULL, 'delete', 570, 571),
(288, NULL, NULL, NULL, 'functions', 575, 640),
(289, 288, NULL, NULL, 'user', 576, 603),
(290, 289, NULL, NULL, 'superadmin', 577, 578),
(291, 289, NULL, NULL, 'admin', 579, 580),
(292, 289, NULL, NULL, 'instructor', 581, 582),
(293, 289, NULL, NULL, 'tutor', 583, 584),
(294, 289, NULL, NULL, 'student', 585, 586),
(295, 289, NULL, NULL, 'import', 587, 588),
(296, 289, NULL, NULL, 'password_reset', 589, 600),
(297, 296, NULL, NULL, 'superadmin', 590, 591),
(298, 296, NULL, NULL, 'admin', 592, 593),
(299, 296, NULL, NULL, 'instructor', 594, 595),
(300, 296, NULL, NULL, 'tutor', 596, 597),
(301, 296, NULL, NULL, 'student', 598, 599),
(302, 289, NULL, NULL, 'index', 601, 602),
(303, 288, NULL, NULL, 'role', 604, 615),
(304, 303, NULL, NULL, 'superadmin', 605, 606),
(305, 303, NULL, NULL, 'admin', 607, 608),
(306, 303, NULL, NULL, 'instructor', 609, 610),
(307, 303, NULL, NULL, 'tutor', 611, 612),
(308, 303, NULL, NULL, 'student', 613, 614),
(309, 288, NULL, NULL, 'evaluation', 616, 619),
(310, 309, NULL, NULL, 'export', 617, 618),
(311, 288, NULL, NULL, 'email', 620, 627),
(312, 311, NULL, NULL, 'allUsers', 621, 622),
(313, 311, NULL, NULL, 'allGroups', 623, 624),
(314, 311, NULL, NULL, 'allCourses', 625, 626),
(315, 288, NULL, NULL, 'emailtemplate', 628, 629),
(316, 288, NULL, NULL, 'viewstudentresults', 630, 631),
(317, 288, NULL, NULL, 'viewemailaddresses', 632, 633),
(318, 288, NULL, NULL, 'superadmin', 634, 635),
(319, 288, NULL, NULL, 'coursemanager', 636, 637),
(320, 288, NULL, NULL, 'viewusername', 638, 639);

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

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 2, '1', '1', '1', '1'),
(2, 1, 288, '1', '1', '1', '1'),
(3, 1, 1, '1', '1', '1', '1'),
(4, 2, 2, '-1', '-1', '-1', '-1'),
(5, 2, 91, '1', '1', '1', '1'),
(6, 2, 224, '1', '1', '1', '1'),
(7, 2, 169, '1', '1', '1', '1'),
(8, 2, 17, '1', '1', '1', '1'),
(9, 2, 153, '1', '1', '1', '1'),
(10, 2, 10, '1', '1', '1', '1'),
(11, 2, 117, '1', '1', '1', '1'),
(12, 2, 255, '1', '1', '1', '1'),
(13, 2, 208, '1', '1', '1', '1'),
(14, 2, 45, '1', '1', '1', '1'),
(15, 2, 143, '1', '1', '1', '1'),
(16, 2, 74, '1', '1', '1', '1'),
(17, 2, 103, '1', '1', '1', '1'),
(18, 2, 240, '1', '1', '1', '1'),
(19, 2, 31, '1', '1', '1', '1'),
(20, 2, 282, '1', '1', '1', '1'),
(21, 2, 99, '1', '1', '1', '1'),
(22, 2, 101, '1', '1', '1', '1'),
(23, 2, 220, '1', '1', '1', '1'),
(24, 2, 222, '1', '1', '1', '1'),
(25, 2, 288, '-1', '-1', '-1', '-1'),
(26, 2, 315, '1', '1', '1', '1'),
(27, 2, 309, '1', '1', '1', '1'),
(28, 2, 312, '1', '1', '1', '1'),
(29, 2, 289, '1', '1', '1', '1'),
(30, 2, 291, '-1', '1', '-1', '-1'),
(31, 2, 290, '-1', '-1', '-1', '-1'),
(32, 2, 1, '1', '1', '1', '1'),
(33, 2, 317, '1', '1', '1', '1'),
(34, 2, 320, '1', '1', '1', '1'),
(35, 2, 319, '1', '1', '1', '1'),
(36, 2, 318, '-1', '-1', '-1', '-1'),
(37, 3, 2, '-1', '-1', '-1', '-1'),
(38, 3, 91, '1', '1', '1', '1'),
(39, 3, 224, '1', '1', '1', '1'),
(40, 3, 17, '1', '1', '1', '1'),
(41, 3, 153, '1', '1', '1', '1'),
(42, 3, 10, '1', '1', '1', '1'),
(43, 3, 117, '1', '1', '1', '1'),
(44, 3, 255, '1', '1', '1', '1'),
(45, 3, 208, '1', '1', '1', '1'),
(46, 3, 45, '1', '1', '1', '1'),
(47, 3, 143, '1', '1', '1', '1'),
(48, 3, 74, '1', '1', '1', '1'),
(49, 3, 103, '1', '1', '1', '1'),
(50, 3, 240, '1', '1', '1', '1'),
(51, 3, 31, '1', '1', '1', '1'),
(52, 3, 282, '1', '1', '1', '1'),
(53, 3, 99, '1', '1', '1', '1'),
(54, 3, 101, '1', '1', '1', '1'),
(55, 3, 220, '1', '1', '1', '1'),
(56, 3, 222, '1', '1', '1', '1'),
(57, 3, 288, '-1', '-1', '-1', '-1'),
(58, 3, 309, '1', '1', '-1', '-1'),
(59, 3, 289, '1', '1', '1', '1'),
(60, 3, 291, '-1', '-1', '-1', '-1'),
(61, 3, 290, '-1', '-1', '-1', '-1'),
(62, 3, 292, '-1', '1', '-1', '-1'),
(63, 3, 302, '-1', '-1', '-1', '-1'),
(64, 3, 317, '-1', '-1', '-1', '-1'),
(65, 3, 318, '-1', '-1', '-1', '-1'),
(66, 3, 319, '1', '1', '1', '1'),
(67, 4, 2, '-1', '-1', '-1', '-1'),
(68, 4, 91, '1', '1', '1', '1'),
(69, 4, 224, '-1', '-1', '-1', '-1'),
(70, 4, 17, '-1', '-1', '-1', '-1'),
(71, 4, 153, '-1', '-1', '-1', '-1'),
(72, 4, 10, '-1', '-1', '-1', '-1'),
(73, 4, 255, '-1', '-1', '-1', '-1'),
(74, 4, 208, '-1', '-1', '-1', '-1'),
(75, 4, 45, '-1', '-1', '-1', '-1'),
(76, 4, 143, '-1', '-1', '-1', '-1'),
(77, 4, 74, '-1', '-1', '-1', '-1'),
(78, 4, 103, '-1', '-1', '-1', '-1'),
(79, 4, 240, '-1', '-1', '-1', '-1'),
(80, 4, 31, '-1', '-1', '-1', '-1'),
(81, 4, 282, '1', '1', '1', '1'),
(82, 4, 123, '1', '1', '1', '1'),
(83, 4, 130, '1', '1', '1', '1'),
(84, 4, 126, '1', '1', '1', '1'),
(85, 4, 128, '1', '1', '1', '1'),
(86, 4, 39, '1', '1', '1', '1'),
(87, 4, 288, '-1', '-1', '-1', '-1'),
(88, 4, 317, '-1', '-1', '-1', '-1'),
(89, 4, 318, '-1', '-1', '-1', '-1'),
(90, 5, 2, '-1', '-1', '-1', '-1'),
(91, 5, 91, '1', '1', '1', '1'),
(92, 5, 224, '-1', '-1', '-1', '-1'),
(93, 5, 17, '-1', '-1', '-1', '-1'),
(94, 5, 153, '-1', '-1', '-1', '-1'),
(95, 5, 10, '-1', '-1', '-1', '-1'),
(96, 5, 255, '-1', '-1', '-1', '-1'),
(97, 5, 208, '-1', '-1', '-1', '-1'),
(98, 5, 45, '-1', '-1', '-1', '-1'),
(99, 5, 143, '-1', '-1', '-1', '-1'),
(100, 5, 74, '-1', '-1', '-1', '-1'),
(101, 5, 103, '-1', '-1', '-1', '-1'),
(102, 5, 240, '-1', '-1', '-1', '-1'),
(103, 5, 31, '-1', '-1', '-1', '-1'),
(104, 5, 282, '1', '1', '1', '1'),
(105, 5, 123, '1', '1', '1', '1'),
(106, 5, 130, '1', '1', '1', '1'),
(107, 5, 126, '1', '1', '1', '1'),
(108, 5, 128, '1', '1', '1', '1'),
(109, 5, 39, '1', '1', '1', '1'),
(110, 5, 99, '1', '1', '1', '1'),
(111, 5, 101, '1', '1', '1', '1'),
(112, 5, 220, '1', '1', '1', '1'),
(113, 5, 222, '1', '1', '1', '1'),
(114, 5, 288, '-1', '-1', '-1', '-1'),
(115, 5, 316, '1', '1', '1', '1'),
(116, 5, 317, '-1', '-1', '-1', '-1'),
(117, 5, 318, '-1', '-1', '-1', '-1');

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
(27, 'display.login.footer', '', 'S', 'Login Info Footer', 'A', 0, NOW(), 0, NOW());

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
