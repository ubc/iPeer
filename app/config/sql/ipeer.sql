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
(1, NULL, NULL, NULL, 'adminpage', 1, 2),
(2, NULL, NULL, NULL, 'controllers', 3, 598),
(3, 2, NULL, NULL, 'Pages', 4, 17),
(4, 3, NULL, NULL, 'display', 5, 6),
(5, 3, NULL, NULL, 'add', 7, 8),
(6, 3, NULL, NULL, 'edit', 9, 10),
(7, 3, NULL, NULL, 'index', 11, 12),
(8, 3, NULL, NULL, 'view', 13, 14),
(9, 3, NULL, NULL, 'delete', 15, 16),
(10, 2, NULL, NULL, 'Accesses', 18, 29),
(11, 10, NULL, NULL, 'view', 19, 20),
(12, 10, NULL, NULL, 'edit', 21, 22),
(13, 10, NULL, NULL, 'add', 23, 24),
(14, 10, NULL, NULL, 'index', 25, 26),
(15, 10, NULL, NULL, 'delete', 27, 28),
(16, 2, NULL, NULL, 'Courses', 30, 53),
(17, 16, NULL, NULL, 'daysLate', 31, 32),
(18, 16, NULL, NULL, 'index', 33, 34),
(19, 16, NULL, NULL, 'ajaxList', 35, 36),
(20, 16, NULL, NULL, 'view', 37, 38),
(21, 16, NULL, NULL, 'home', 39, 40),
(22, 16, NULL, NULL, 'add', 41, 42),
(23, 16, NULL, NULL, 'edit', 43, 44),
(24, 16, NULL, NULL, 'delete', 45, 46),
(25, 16, NULL, NULL, 'move', 47, 48),
(26, 16, NULL, NULL, 'ajax_options', 49, 50),
(27, 16, NULL, NULL, 'import', 51, 52),
(28, 2, NULL, NULL, 'Departments', 54, 65),
(29, 28, NULL, NULL, 'index', 55, 56),
(30, 28, NULL, NULL, 'view', 57, 58),
(31, 28, NULL, NULL, 'add', 59, 60),
(32, 28, NULL, NULL, 'edit', 61, 62),
(33, 28, NULL, NULL, 'delete', 63, 64),
(34, 2, NULL, NULL, 'Emailer', 66, 93),
(35, 34, NULL, NULL, 'setUpAjaxList', 67, 68),
(36, 34, NULL, NULL, 'ajaxList', 69, 70),
(37, 34, NULL, NULL, 'index', 71, 72),
(38, 34, NULL, NULL, 'write', 73, 74),
(39, 34, NULL, NULL, 'cancel', 75, 76),
(40, 34, NULL, NULL, 'view', 77, 78),
(41, 34, NULL, NULL, 'addRecipient', 79, 80),
(42, 34, NULL, NULL, 'deleteRecipient', 81, 82),
(43, 34, NULL, NULL, 'getRecipient', 83, 84),
(44, 34, NULL, NULL, 'searchByUserId', 85, 86),
(45, 34, NULL, NULL, 'add', 87, 88),
(46, 34, NULL, NULL, 'edit', 89, 90),
(47, 34, NULL, NULL, 'delete', 91, 92),
(48, 2, NULL, NULL, 'Emailtemplates', 94, 113),
(49, 48, NULL, NULL, 'setUpAjaxList', 95, 96),
(50, 48, NULL, NULL, 'ajaxList', 97, 98),
(51, 48, NULL, NULL, 'index', 99, 100),
(52, 48, NULL, NULL, 'add', 101, 102),
(53, 48, NULL, NULL, 'edit', 103, 104),
(54, 48, NULL, NULL, 'delete', 105, 106),
(55, 48, NULL, NULL, 'view', 107, 108),
(56, 48, NULL, NULL, 'displayTemplateContent', 109, 110),
(57, 48, NULL, NULL, 'displayTemplateSubject', 111, 112),
(58, 2, NULL, NULL, 'Evaltools', 114, 125),
(59, 58, NULL, NULL, 'index', 115, 116),
(60, 58, NULL, NULL, 'add', 117, 118),
(61, 58, NULL, NULL, 'edit', 119, 120),
(62, 58, NULL, NULL, 'view', 121, 122),
(63, 58, NULL, NULL, 'delete', 123, 124),
(64, 2, NULL, NULL, 'Evaluations', 126, 173),
(65, 64, NULL, NULL, 'setUpAjaxList', 127, 128),
(66, 64, NULL, NULL, 'ajaxList', 129, 130),
(67, 64, NULL, NULL, 'view', 131, 132),
(68, 64, NULL, NULL, 'index', 133, 134),
(69, 64, NULL, NULL, 'export', 135, 136),
(70, 64, NULL, NULL, 'makeEvaluation', 137, 138),
(71, 64, NULL, NULL, 'validRubricEvalComplete', 139, 140),
(72, 64, NULL, NULL, 'completeEvaluationRubric', 141, 142),
(73, 64, NULL, NULL, 'validMixevalEvalComplete', 143, 144),
(74, 64, NULL, NULL, 'viewEvaluationResults', 145, 146),
(75, 64, NULL, NULL, 'studentViewEvaluationResult', 147, 148),
(76, 64, NULL, NULL, 'markEventReviewed', 149, 150),
(77, 64, NULL, NULL, 'markGradeRelease', 151, 152),
(78, 64, NULL, NULL, 'markCommentRelease', 153, 154),
(79, 64, NULL, NULL, 'changeAllCommentRelease', 155, 156),
(80, 64, NULL, NULL, 'changeAllGradeRelease', 157, 158),
(81, 64, NULL, NULL, 'viewGroupSubmissionDetails', 159, 160),
(82, 64, NULL, NULL, 'viewSurveySummary', 161, 162),
(83, 64, NULL, NULL, 'export_rubic', 163, 164),
(84, 64, NULL, NULL, 'export_test', 165, 166),
(85, 64, NULL, NULL, 'add', 167, 168),
(86, 64, NULL, NULL, 'edit', 169, 170),
(87, 64, NULL, NULL, 'delete', 171, 172),
(88, 2, NULL, NULL, 'Events', 174, 203),
(89, 88, NULL, NULL, 'postProcessData', 175, 176),
(90, 88, NULL, NULL, 'setUpAjaxList', 177, 178),
(91, 88, NULL, NULL, 'index', 179, 180),
(92, 88, NULL, NULL, 'ajaxList', 181, 182),
(93, 88, NULL, NULL, 'view', 183, 184),
(94, 88, NULL, NULL, 'add', 185, 186),
(95, 88, NULL, NULL, 'setSchedule', 187, 188),
(96, 88, NULL, NULL, 'getGroupMembers', 189, 190),
(97, 88, NULL, NULL, 'edit', 191, 192),
(98, 88, NULL, NULL, 'checkIfChanged', 193, 194),
(99, 88, NULL, NULL, 'calculateFrequency', 195, 196),
(100, 88, NULL, NULL, 'modifySchedule', 197, 198),
(101, 88, NULL, NULL, 'delete', 199, 200),
(102, 88, NULL, NULL, 'checkDuplicateName', 201, 202),
(103, 2, NULL, NULL, 'Faculties', 204, 215),
(104, 103, NULL, NULL, 'index', 205, 206),
(105, 103, NULL, NULL, 'view', 207, 208),
(106, 103, NULL, NULL, 'add', 209, 210),
(107, 103, NULL, NULL, 'edit', 211, 212),
(108, 103, NULL, NULL, 'delete', 213, 214),
(109, 2, NULL, NULL, 'Framework', 216, 231),
(110, 109, NULL, NULL, 'calendarDisplay', 217, 218),
(111, 109, NULL, NULL, 'tutIndex', 219, 220),
(112, 109, NULL, NULL, 'add', 221, 222),
(113, 109, NULL, NULL, 'edit', 223, 224),
(114, 109, NULL, NULL, 'index', 225, 226),
(115, 109, NULL, NULL, 'view', 227, 228),
(116, 109, NULL, NULL, 'delete', 229, 230),
(117, 2, NULL, NULL, 'Groups', 232, 251),
(118, 117, NULL, NULL, 'setUpAjaxList', 233, 234),
(119, 117, NULL, NULL, 'index', 235, 236),
(120, 117, NULL, NULL, 'ajaxList', 237, 238),
(121, 117, NULL, NULL, 'view', 239, 240),
(122, 117, NULL, NULL, 'add', 241, 242),
(123, 117, NULL, NULL, 'edit', 243, 244),
(124, 117, NULL, NULL, 'delete', 245, 246),
(125, 117, NULL, NULL, 'import', 247, 248),
(126, 117, NULL, NULL, 'export', 249, 250),
(127, 2, NULL, NULL, 'Home', 252, 263),
(128, 127, NULL, NULL, 'index', 253, 254),
(129, 127, NULL, NULL, 'add', 255, 256),
(130, 127, NULL, NULL, 'edit', 257, 258),
(131, 127, NULL, NULL, 'view', 259, 260),
(132, 127, NULL, NULL, 'delete', 261, 262),
(133, 2, NULL, NULL, 'Install', 264, 285),
(134, 133, NULL, NULL, 'index', 265, 266),
(135, 133, NULL, NULL, 'install2', 267, 268),
(136, 133, NULL, NULL, 'install3', 269, 270),
(137, 133, NULL, NULL, 'install4', 271, 272),
(138, 133, NULL, NULL, 'install5', 273, 274),
(139, 133, NULL, NULL, 'gpl', 275, 276),
(140, 133, NULL, NULL, 'add', 277, 278),
(141, 133, NULL, NULL, 'edit', 279, 280),
(142, 133, NULL, NULL, 'view', 281, 282),
(143, 133, NULL, NULL, 'delete', 283, 284),
(144, 2, NULL, NULL, 'Lti', 286, 297),
(145, 144, NULL, NULL, 'index', 287, 288),
(146, 144, NULL, NULL, 'add', 289, 290),
(147, 144, NULL, NULL, 'edit', 291, 292),
(148, 144, NULL, NULL, 'view', 293, 294),
(149, 144, NULL, NULL, 'delete', 295, 296),
(150, 2, NULL, NULL, 'Mixevals', 298, 315),
(151, 150, NULL, NULL, 'setUpAjaxList', 299, 300),
(152, 150, NULL, NULL, 'index', 301, 302),
(153, 150, NULL, NULL, 'ajaxList', 303, 304),
(154, 150, NULL, NULL, 'view', 305, 306),
(155, 150, NULL, NULL, 'add', 307, 308),
(156, 150, NULL, NULL, 'edit', 309, 310),
(157, 150, NULL, NULL, 'copy', 311, 312),
(158, 150, NULL, NULL, 'delete', 313, 314),
(159, 2, NULL, NULL, 'Oauthclients', 316, 327),
(160, 159, NULL, NULL, 'index', 317, 318),
(161, 159, NULL, NULL, 'add', 319, 320),
(162, 159, NULL, NULL, 'edit', 321, 322),
(163, 159, NULL, NULL, 'delete', 323, 324),
(164, 159, NULL, NULL, 'view', 325, 326),
(165, 2, NULL, NULL, 'Oauthtokens', 328, 339),
(166, 165, NULL, NULL, 'index', 329, 330),
(167, 165, NULL, NULL, 'add', 331, 332),
(168, 165, NULL, NULL, 'edit', 333, 334),
(169, 165, NULL, NULL, 'delete', 335, 336),
(170, 165, NULL, NULL, 'view', 337, 338),
(171, 2, NULL, NULL, 'Penalty', 340, 353),
(172, 171, NULL, NULL, 'save', 341, 342),
(173, 171, NULL, NULL, 'add', 343, 344),
(174, 171, NULL, NULL, 'edit', 345, 346),
(175, 171, NULL, NULL, 'index', 347, 348),
(176, 171, NULL, NULL, 'view', 349, 350),
(177, 171, NULL, NULL, 'delete', 351, 352),
(178, 2, NULL, NULL, 'Rubrics', 354, 373),
(179, 178, NULL, NULL, 'postProcess', 355, 356),
(180, 178, NULL, NULL, 'setUpAjaxList', 357, 358),
(181, 178, NULL, NULL, 'index', 359, 360),
(182, 178, NULL, NULL, 'ajaxList', 361, 362),
(183, 178, NULL, NULL, 'view', 363, 364),
(184, 178, NULL, NULL, 'add', 365, 366),
(185, 178, NULL, NULL, 'edit', 367, 368),
(186, 178, NULL, NULL, 'copy', 369, 370),
(187, 178, NULL, NULL, 'delete', 371, 372),
(188, 2, NULL, NULL, 'Searchs', 374, 401),
(189, 188, NULL, NULL, 'update', 375, 376),
(190, 188, NULL, NULL, 'index', 377, 378),
(191, 188, NULL, NULL, 'searchEvaluation', 379, 380),
(192, 188, NULL, NULL, 'searchResult', 381, 382),
(193, 188, NULL, NULL, 'searchInstructor', 383, 384),
(194, 188, NULL, NULL, 'eventBoxSearch', 385, 386),
(195, 188, NULL, NULL, 'formatSearchEvaluation', 387, 388),
(196, 188, NULL, NULL, 'formatSearchInstructor', 389, 390),
(197, 188, NULL, NULL, 'formatSearchEvaluationResult', 391, 392),
(198, 188, NULL, NULL, 'add', 393, 394),
(199, 188, NULL, NULL, 'edit', 395, 396),
(200, 188, NULL, NULL, 'view', 397, 398),
(201, 188, NULL, NULL, 'delete', 399, 400),
(202, 2, NULL, NULL, 'Simpleevaluations', 402, 421),
(203, 202, NULL, NULL, 'postProcess', 403, 404),
(204, 202, NULL, NULL, 'setUpAjaxList', 405, 406),
(205, 202, NULL, NULL, 'index', 407, 408),
(206, 202, NULL, NULL, 'ajaxList', 409, 410),
(207, 202, NULL, NULL, 'view', 411, 412),
(208, 202, NULL, NULL, 'add', 413, 414),
(209, 202, NULL, NULL, 'edit', 415, 416),
(210, 202, NULL, NULL, 'copy', 417, 418),
(211, 202, NULL, NULL, 'delete', 419, 420),
(212, 2, NULL, NULL, 'Surveygroups', 422, 451),
(213, 212, NULL, NULL, 'postProcess', 423, 424),
(214, 212, NULL, NULL, 'setUpAjaxList', 425, 426),
(215, 212, NULL, NULL, 'index', 427, 428),
(216, 212, NULL, NULL, 'ajaxList', 429, 430),
(217, 212, NULL, NULL, 'makegroups', 431, 432),
(218, 212, NULL, NULL, 'makegroupssearch', 433, 434),
(219, 212, NULL, NULL, 'maketmgroups', 435, 436),
(220, 212, NULL, NULL, 'savegroups', 437, 438),
(221, 212, NULL, NULL, 'release', 439, 440),
(222, 212, NULL, NULL, 'delete', 441, 442),
(223, 212, NULL, NULL, 'edit', 443, 444),
(224, 212, NULL, NULL, 'changegroupset', 445, 446),
(225, 212, NULL, NULL, 'add', 447, 448),
(226, 212, NULL, NULL, 'view', 449, 450),
(227, 2, NULL, NULL, 'Surveys', 452, 479),
(228, 227, NULL, NULL, 'setUpAjaxList', 453, 454),
(229, 227, NULL, NULL, 'index', 455, 456),
(230, 227, NULL, NULL, 'ajaxList', 457, 458),
(231, 227, NULL, NULL, 'view', 459, 460),
(232, 227, NULL, NULL, 'add', 461, 462),
(233, 227, NULL, NULL, 'edit', 463, 464),
(234, 227, NULL, NULL, 'copy', 465, 466),
(235, 227, NULL, NULL, 'delete', 467, 468),
(236, 227, NULL, NULL, 'questionsSummary', 469, 470),
(237, 227, NULL, NULL, 'moveQuestion', 471, 472),
(238, 227, NULL, NULL, 'removeQuestion', 473, 474),
(239, 227, NULL, NULL, 'addQuestion', 475, 476),
(240, 227, NULL, NULL, 'editQuestion', 477, 478),
(241, 2, NULL, NULL, 'Sysparameters', 480, 495),
(242, 241, NULL, NULL, 'setUpAjaxList', 481, 482),
(243, 241, NULL, NULL, 'index', 483, 484),
(244, 241, NULL, NULL, 'ajaxList', 485, 486),
(245, 241, NULL, NULL, 'view', 487, 488),
(246, 241, NULL, NULL, 'add', 489, 490),
(247, 241, NULL, NULL, 'edit', 491, 492),
(248, 241, NULL, NULL, 'delete', 493, 494),
(249, 2, NULL, NULL, 'Upgrade', 496, 509),
(250, 249, NULL, NULL, 'index', 497, 498),
(251, 249, NULL, NULL, 'step2', 499, 500),
(252, 249, NULL, NULL, 'add', 501, 502),
(253, 249, NULL, NULL, 'edit', 503, 504),
(254, 249, NULL, NULL, 'view', 505, 506),
(255, 249, NULL, NULL, 'delete', 507, 508),
(256, 2, NULL, NULL, 'Users', 510, 543),
(257, 256, NULL, NULL, 'ajaxList', 511, 512),
(258, 256, NULL, NULL, 'index', 513, 514),
(259, 256, NULL, NULL, 'goToClassList', 515, 516),
(260, 256, NULL, NULL, 'determineIfStudentFromThisData', 517, 518),
(261, 256, NULL, NULL, 'view', 519, 520),
(262, 256, NULL, NULL, 'add', 521, 522),
(263, 256, NULL, NULL, 'enrol', 523, 524),
(264, 256, NULL, NULL, 'edit', 525, 526),
(265, 256, NULL, NULL, 'editProfile', 527, 528),
(266, 256, NULL, NULL, 'delete', 529, 530),
(267, 256, NULL, NULL, 'checkDuplicateName', 531, 532),
(268, 256, NULL, NULL, 'resetPassword', 533, 534),
(269, 256, NULL, NULL, 'import', 535, 536),
(270, 256, NULL, NULL, 'merge', 537, 538),
(271, 256, NULL, NULL, 'ajax_merge', 539, 540),
(272, 256, NULL, NULL, 'update', 541, 542),
(273, 2, NULL, NULL, 'V1', 544, 579),
(274, 273, NULL, NULL, 'oauth', 545, 546),
(275, 273, NULL, NULL, 'oauth_error', 547, 548),
(276, 273, NULL, NULL, 'users', 549, 550),
(277, 273, NULL, NULL, 'courses', 551, 552),
(278, 273, NULL, NULL, 'groups', 553, 554),
(279, 273, NULL, NULL, 'groupMembers', 555, 556),
(280, 273, NULL, NULL, 'events', 557, 558),
(281, 273, NULL, NULL, 'grades', 559, 560),
(282, 273, NULL, NULL, 'departments', 561, 562),
(283, 273, NULL, NULL, 'courseDepartments', 563, 564),
(284, 273, NULL, NULL, 'userEvents', 565, 566),
(285, 273, NULL, NULL, 'enrolment', 567, 568),
(286, 273, NULL, NULL, 'add', 569, 570),
(287, 273, NULL, NULL, 'edit', 571, 572),
(288, 273, NULL, NULL, 'index', 573, 574),
(289, 273, NULL, NULL, 'view', 575, 576),
(290, 273, NULL, NULL, 'delete', 577, 578),
(291, 2, NULL, NULL, 'Guard', 580, 597),
(292, 291, NULL, NULL, 'Guard', 581, 596),
(293, 292, NULL, NULL, 'login', 582, 583),
(294, 292, NULL, NULL, 'logout', 584, 585),
(295, 292, NULL, NULL, 'add', 586, 587),
(296, 292, NULL, NULL, 'edit', 588, 589),
(297, 292, NULL, NULL, 'index', 590, 591),
(298, 292, NULL, NULL, 'view', 592, 593),
(299, 292, NULL, NULL, 'delete', 594, 595),
(300, NULL, NULL, NULL, 'functions', 599, 662),
(301, 300, NULL, NULL, 'user', 600, 627),
(302, 301, NULL, NULL, 'superadmin', 601, 602),
(303, 301, NULL, NULL, 'admin', 603, 604),
(304, 301, NULL, NULL, 'instructor', 605, 606),
(305, 301, NULL, NULL, 'tutor', 607, 608),
(306, 301, NULL, NULL, 'student', 609, 610),
(307, 301, NULL, NULL, 'import', 611, 612),
(308, 301, NULL, NULL, 'password_reset', 613, 624),
(309, 308, NULL, NULL, 'superadmin', 614, 615),
(310, 308, NULL, NULL, 'admin', 616, 617),
(311, 308, NULL, NULL, 'instructor', 618, 619),
(312, 308, NULL, NULL, 'tutor', 620, 621),
(313, 308, NULL, NULL, 'student', 622, 623),
(314, 301, NULL, NULL, 'index', 625, 626),
(315, 300, NULL, NULL, 'role', 628, 639),
(316, 315, NULL, NULL, 'superadmin', 629, 630),
(317, 315, NULL, NULL, 'admin', 631, 632),
(318, 315, NULL, NULL, 'instructor', 633, 634),
(319, 315, NULL, NULL, 'tutor', 635, 636),
(320, 315, NULL, NULL, 'student', 637, 638),
(321, 300, NULL, NULL, 'evaluation', 640, 641),
(322, 300, NULL, NULL, 'email', 642, 649),
(323, 322, NULL, NULL, 'allUsers', 643, 644),
(324, 322, NULL, NULL, 'allGroups', 645, 646),
(325, 322, NULL, NULL, 'allCourses', 647, 648),
(326, 300, NULL, NULL, 'emailtemplate', 650, 651),
(327, 300, NULL, NULL, 'viewstudentresults', 652, 653),
(328, 300, NULL, NULL, 'viewemailaddresses', 654, 655),
(329, 300, NULL, NULL, 'superadmin', 656, 657),
(330, 300, NULL, NULL, 'coursemanager', 658, 659),
(331, 300, NULL, NULL, 'viewusername', 660, 661);

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
(1, 1, 2, '1', '1', '1', '1'),
(2, 1, 300, '1', '1', '1', '1'),
(3, 1, 1, '1', '1', '1', '1'),
(4, 2, 2, '-1', '-1', '-1', '-1'),
(5, 2, 127, '1', '1', '1', '1'),
(6, 2, 16, '1', '1', '1', '1'),
(7, 2, 28, '1', '1', '1', '1'),
(8, 2, 34, '1', '1', '1', '1'),
(9, 2, 48, '1', '1', '1', '1'),
(10, 2, 58, '1', '1', '1', '1'),
(11, 2, 64, '1', '1', '1', '1'),
(12, 2, 88, '1', '1', '1', '1'),
(13, 2, 117, '1', '1', '1', '1'),
(14, 2, 150, '1', '1', '1', '1'),
(15, 2, 178, '1', '1', '1', '1'),
(16, 2, 202, '1', '1', '1', '1'),
(17, 2, 227, '1', '1', '1', '1'),
(18, 2, 212, '1', '1', '1', '1'),
(19, 2, 256, '1', '1', '1', '1'),
(20, 2, 294, '1', '1', '1', '1'),
(21, 2, 161, '1', '1', '1', '1'),
(22, 2, 163, '1', '1', '1', '1'),
(23, 2, 167, '1', '1', '1', '1'),
(24, 2, 169, '1', '1', '1', '1'),
(25, 2, 300, '-1', '-1', '-1', '-1'),
(26, 2, 326, '1', '1', '1', '1'),
(27, 2, 321, '1', '1', '1', '1'),
(28, 2, 323, '1', '1', '1', '1'),
(29, 2, 301, '1', '1', '1', '1'),
(30, 2, 303, '1', '1', '1', '-1'),
(31, 2, 302, '-1', '-1', '-1', '-1'),
(32, 2, 328, '1', '1', '1', '1'),
(33, 2, 331, '1', '1', '1', '1'),
(34, 2, 330, '1', '1', '1', '1'),
(35, 2, 329, '-1', '-1', '-1', '-1'),
(36, 3, 2, '-1', '-1', '-1', '-1'),
(37, 3, 127, '1', '1', '1', '1'),
(38, 3, 16, '1', '1', '1', '1'),
(39, 3, 34, '1', '1', '1', '1'),
(40, 3, 48, '1', '1', '1', '1'),
(41, 3, 58, '1', '1', '1', '1'),
(42, 3, 64, '1', '1', '1', '1'),
(43, 3, 88, '1', '1', '1', '1'),
(44, 3, 117, '1', '1', '1', '1'),
(45, 3, 150, '1', '1', '1', '1'),
(46, 3, 178, '1', '1', '1', '1'),
(47, 3, 202, '1', '1', '1', '1'),
(48, 3, 227, '1', '1', '1', '1'),
(49, 3, 212, '1', '1', '1', '1'),
(50, 3, 256, '1', '1', '1', '1'),
(51, 3, 294, '1', '1', '1', '1'),
(52, 3, 161, '1', '1', '1', '1'),
(53, 3, 163, '1', '1', '1', '1'),
(54, 3, 167, '1', '1', '1', '1'),
(55, 3, 169, '1', '1', '1', '1'),
(56, 3, 270, '-1', '-1', '-1', '-1'),
(57, 3, 300, '-1', '-1', '-1', '-1'),
(58, 3, 321, '1', '1', '-1', '-1'),
(59, 3, 301, '1', '1', '1', '1'),
(60, 3, 303, '-1', '-1', '-1', '-1'),
(61, 3, 302, '-1', '-1', '-1', '-1'),
(62, 3, 304, '-1', '1', '-1', '-1'),
(63, 3, 314, '-1', '-1', '-1', '-1'),
(64, 3, 328, '-1', '-1', '-1', '-1'),
(65, 3, 329, '-1', '-1', '-1', '-1'),
(66, 3, 330, '1', '1', '1', '1'),
(67, 4, 2, '-1', '-1', '-1', '-1'),
(68, 4, 127, '1', '1', '1', '1'),
(69, 4, 16, '-1', '-1', '-1', '-1'),
(70, 4, 34, '-1', '-1', '-1', '-1'),
(71, 4, 48, '-1', '-1', '-1', '-1'),
(72, 4, 58, '-1', '-1', '-1', '-1'),
(73, 4, 88, '-1', '-1', '-1', '-1'),
(74, 4, 117, '-1', '-1', '-1', '-1'),
(75, 4, 150, '-1', '-1', '-1', '-1'),
(76, 4, 178, '-1', '-1', '-1', '-1'),
(77, 4, 202, '-1', '-1', '-1', '-1'),
(78, 4, 227, '-1', '-1', '-1', '-1'),
(79, 4, 212, '-1', '-1', '-1', '-1'),
(80, 4, 256, '-1', '-1', '-1', '-1'),
(81, 4, 294, '1', '1', '1', '1'),
(82, 4, 70, '1', '1', '1', '1'),
(83, 4, 75, '1', '1', '1', '1'),
(84, 4, 72, '1', '1', '1', '1'),
(85, 4, 265, '1', '1', '1', '1'),
(86, 4, 300, '-1', '-1', '-1', '-1'),
(87, 4, 328, '-1', '-1', '-1', '-1'),
(88, 4, 329, '-1', '-1', '-1', '-1'),
(89, 5, 2, '-1', '-1', '-1', '-1'),
(90, 5, 127, '1', '1', '1', '1'),
(91, 5, 16, '-1', '-1', '-1', '-1'),
(92, 5, 34, '-1', '-1', '-1', '-1'),
(93, 5, 48, '-1', '-1', '-1', '-1'),
(94, 5, 58, '-1', '-1', '-1', '-1'),
(95, 5, 88, '-1', '-1', '-1', '-1'),
(96, 5, 117, '-1', '-1', '-1', '-1'),
(97, 5, 150, '-1', '-1', '-1', '-1'),
(98, 5, 178, '-1', '-1', '-1', '-1'),
(99, 5, 202, '-1', '-1', '-1', '-1'),
(100, 5, 227, '-1', '-1', '-1', '-1'),
(101, 5, 212, '-1', '-1', '-1', '-1'),
(102, 5, 256, '-1', '-1', '-1', '-1'),
(103, 5, 294, '1', '1', '1', '1'),
(104, 5, 70, '1', '1', '1', '1'),
(105, 5, 75, '1', '1', '1', '1'),
(106, 5, 72, '1', '1', '1', '1'),
(107, 5, 265, '1', '1', '1', '1'),
(108, 5, 161, '1', '1', '1', '1'),
(109, 5, 163, '1', '1', '1', '1'),
(110, 5, 167, '1', '1', '1', '1'),
(111, 5, 169, '1', '1', '1', '1'),
(112, 5, 300, '-1', '-1', '-1', '-1'),
(113, 5, 327, '1', '1', '1', '1'),
(114, 5, 328, '-1', '-1', '-1', '-1'),
(115, 5, 329, '-1', '-1', '-1', '-1');

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
