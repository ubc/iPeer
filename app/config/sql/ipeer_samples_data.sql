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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=365 ;

--
-- Dumping data for table `acos`
--

(1, NULL, NULL, NULL, 'adminpage', 1, 2),
(2, NULL, NULL, NULL, 'controllers', 3, 634),
(3, 2, NULL, NULL, 'Pages', 4, 17),
(4, 3, NULL, NULL, 'display', 5, 6),
(5, 3, NULL, NULL, 'add', 7, 8),
(6, 3, NULL, NULL, 'edit', 9, 10),
(7, 3, NULL, NULL, 'index', 11, 12),
(8, 3, NULL, NULL, 'view', 13, 14),
(9, 3, NULL, NULL, 'delete', 15, 16),
(10, 2, NULL, NULL, 'Courses', 18, 35),
(11, 10, NULL, NULL, 'daysLate', 19, 20),
(12, 10, NULL, NULL, 'index', 21, 22),
(13, 10, NULL, NULL, 'ajaxList', 23, 24),
(14, 10, NULL, NULL, 'view', 25, 26),
(15, 10, NULL, NULL, 'home', 27, 28),
(16, 10, NULL, NULL, 'add', 29, 30),
(17, 10, NULL, NULL, 'edit', 31, 32),
(18, 10, NULL, NULL, 'delete', 33, 34),
(19, 2, NULL, NULL, 'Departments', 36, 47),
(20, 19, NULL, NULL, 'index', 37, 38),
(21, 19, NULL, NULL, 'view', 39, 40),
(22, 19, NULL, NULL, 'add', 41, 42),
(23, 19, NULL, NULL, 'edit', 43, 44),
(24, 19, NULL, NULL, 'delete', 45, 46),
(25, 2, NULL, NULL, 'Emailer', 48, 79),
(26, 25, NULL, NULL, 'setUpAjaxList', 49, 50),
(27, 25, NULL, NULL, 'ajaxList', 51, 52),
(28, 25, NULL, NULL, 'index', 53, 54),
(29, 25, NULL, NULL, 'write', 55, 56),
(30, 25, NULL, NULL, 'cancel', 57, 58),
(31, 25, NULL, NULL, 'view', 59, 60),
(32, 25, NULL, NULL, 'addRecipient', 61, 62),
(33, 25, NULL, NULL, 'deleteRecipient', 63, 64),
(34, 25, NULL, NULL, 'getRecipient', 65, 66),
(35, 25, NULL, NULL, 'searchByUserId', 67, 68),
(36, 25, NULL, NULL, 'doMerge', 69, 70),
(37, 25, NULL, NULL, 'send', 71, 72),
(38, 25, NULL, NULL, 'add', 73, 74),
(39, 25, NULL, NULL, 'edit', 75, 76),
(40, 25, NULL, NULL, 'delete', 77, 78),
(41, 2, NULL, NULL, 'Emailtemplates', 80, 99),
(42, 41, NULL, NULL, 'setUpAjaxList', 81, 82),
(43, 41, NULL, NULL, 'ajaxList', 83, 84),
(44, 41, NULL, NULL, 'index', 85, 86),
(45, 41, NULL, NULL, 'add', 87, 88),
(46, 41, NULL, NULL, 'edit', 89, 90),
(47, 41, NULL, NULL, 'delete', 91, 92),
(48, 41, NULL, NULL, 'view', 93, 94),
(49, 41, NULL, NULL, 'displayTemplateContent', 95, 96),
(50, 41, NULL, NULL, 'displayTemplateSubject', 97, 98),
(51, 2, NULL, NULL, 'Evaltools', 100, 113),
(52, 51, NULL, NULL, 'index', 101, 102),
(53, 51, NULL, NULL, 'showAll', 103, 104),
(54, 51, NULL, NULL, 'add', 105, 106),
(55, 51, NULL, NULL, 'edit', 107, 108),
(56, 51, NULL, NULL, 'view', 109, 110),
(57, 51, NULL, NULL, 'delete', 111, 112),
(58, 2, NULL, NULL, 'Evaluations', 114, 173),
(59, 58, NULL, NULL, 'postProcess', 115, 116),
(60, 58, NULL, NULL, 'setUpAjaxList', 117, 118),
(61, 58, NULL, NULL, 'ajaxList', 119, 120),
(62, 58, NULL, NULL, 'view', 121, 122),
(63, 58, NULL, NULL, 'index', 123, 124),
(64, 58, NULL, NULL, 'update', 125, 126),
(65, 58, NULL, NULL, 'export', 127, 128),
(66, 58, NULL, NULL, 'makeEvaluation', 129, 130),
(67, 58, NULL, NULL, 'validSurveyEvalComplete', 131, 132),
(68, 58, NULL, NULL, 'validRubricEvalComplete', 133, 134),
(69, 58, NULL, NULL, 'completeEvaluationRubric', 135, 136),
(70, 58, NULL, NULL, 'validMixevalEvalComplete', 137, 138),
(71, 58, NULL, NULL, 'completeEvaluationMixeval', 139, 140),
(72, 58, NULL, NULL, 'viewEvaluationResults', 141, 142),
(73, 58, NULL, NULL, 'viewSurveyGroupEvaluationResults', 143, 144),
(74, 58, NULL, NULL, 'studentViewEvaluationResult', 145, 146),
(75, 58, NULL, NULL, 'markEventReviewed', 147, 148),
(76, 58, NULL, NULL, 'markGradeRelease', 149, 150),
(77, 58, NULL, NULL, 'markCommentRelease', 151, 152),
(78, 58, NULL, NULL, 'changeAllCommentRelease', 153, 154),
(79, 58, NULL, NULL, 'changeAllGradeRelease', 155, 156),
(80, 58, NULL, NULL, 'viewGroupSubmissionDetails', 157, 158),
(81, 58, NULL, NULL, 'viewSurveySummary', 159, 160),
(82, 58, NULL, NULL, 'export_rubic', 161, 162),
(83, 58, NULL, NULL, 'export_test', 163, 164),
(84, 58, NULL, NULL, 'pre', 165, 166),
(85, 58, NULL, NULL, 'add', 167, 168),
(86, 58, NULL, NULL, 'edit', 169, 170),
(87, 58, NULL, NULL, 'delete', 171, 172),
(88, 2, NULL, NULL, 'Events', 174, 205),
(89, 88, NULL, NULL, 'postProcessData', 175, 176),
(90, 88, NULL, NULL, 'setUpAjaxList', 177, 178),
(91, 88, NULL, NULL, 'index', 179, 180),
(92, 88, NULL, NULL, 'ajaxList', 181, 182),
(93, 88, NULL, NULL, 'view', 183, 184),
(94, 88, NULL, NULL, 'eventTemplatesList', 185, 186),
(95, 88, NULL, NULL, 'add', 187, 188),
(96, 88, NULL, NULL, 'edit', 189, 190),
(97, 88, NULL, NULL, 'delete', 191, 192),
(98, 88, NULL, NULL, 'search', 193, 194),
(99, 88, NULL, NULL, 'checkDuplicateTitle', 195, 196),
(100, 88, NULL, NULL, 'viewGroups', 197, 198),
(101, 88, NULL, NULL, 'editGroup', 199, 200),
(102, 88, NULL, NULL, 'getAssignedGroups', 201, 202),
(103, 88, NULL, NULL, 'update', 203, 204),
(104, 2, NULL, NULL, 'Faculties', 206, 217),
(105, 104, NULL, NULL, 'index', 207, 208),
(106, 104, NULL, NULL, 'view', 209, 210),
(107, 104, NULL, NULL, 'add', 211, 212),
(108, 104, NULL, NULL, 'edit', 213, 214),
(109, 104, NULL, NULL, 'delete', 215, 216),
(110, 2, NULL, NULL, 'Framework', 218, 235),
(111, 110, NULL, NULL, 'calendarDisplay', 219, 220),
(112, 110, NULL, NULL, 'userInfoDisplay', 221, 222),
(113, 110, NULL, NULL, 'tutIndex', 223, 224),
(114, 110, NULL, NULL, 'add', 225, 226),
(115, 110, NULL, NULL, 'edit', 227, 228),
(116, 110, NULL, NULL, 'index', 229, 230),
(117, 110, NULL, NULL, 'view', 231, 232),
(118, 110, NULL, NULL, 'delete', 233, 234),
(119, 2, NULL, NULL, 'Groups', 236, 269),
(120, 119, NULL, NULL, 'postProcess', 237, 238),
(121, 119, NULL, NULL, 'setUpAjaxList', 239, 240),
(122, 119, NULL, NULL, 'index', 241, 242),
(123, 119, NULL, NULL, 'ajaxList', 243, 244),
(124, 119, NULL, NULL, 'goToClassList', 245, 246),
(125, 119, NULL, NULL, 'view', 247, 248),
(126, 119, NULL, NULL, 'add', 249, 250),
(127, 119, NULL, NULL, 'edit', 251, 252),
(128, 119, NULL, NULL, 'delete', 253, 254),
(129, 119, NULL, NULL, 'checkDuplicateName', 255, 256),
(130, 119, NULL, NULL, 'getQueryAttribute', 257, 258),
(131, 119, NULL, NULL, 'import', 259, 260),
(132, 119, NULL, NULL, 'addGroupByImport', 261, 262),
(133, 119, NULL, NULL, 'update', 263, 264),
(134, 119, NULL, NULL, 'export', 265, 266),
(135, 119, NULL, NULL, 'sendGroupEmail', 267, 268),
(136, 2, NULL, NULL, 'Home', 270, 281),
(137, 136, NULL, NULL, 'index', 271, 272),
(138, 136, NULL, NULL, 'add', 273, 274),
(139, 136, NULL, NULL, 'edit', 275, 276),
(140, 136, NULL, NULL, 'view', 277, 278),
(141, 136, NULL, NULL, 'delete', 279, 280),
(142, 2, NULL, NULL, 'Install', 282, 303),
(143, 142, NULL, NULL, 'index', 283, 284),
(144, 142, NULL, NULL, 'install2', 285, 286),
(145, 142, NULL, NULL, 'install3', 287, 288),
(146, 142, NULL, NULL, 'install4', 289, 290),
(147, 142, NULL, NULL, 'install5', 291, 292),
(148, 142, NULL, NULL, 'gpl', 293, 294),
(149, 142, NULL, NULL, 'add', 295, 296),
(150, 142, NULL, NULL, 'edit', 297, 298),
(151, 142, NULL, NULL, 'view', 299, 300),
(152, 142, NULL, NULL, 'delete', 301, 302),
(153, 2, NULL, NULL, 'Lti', 304, 315),
(154, 153, NULL, NULL, 'index', 305, 306),
(155, 153, NULL, NULL, 'add', 307, 308),
(156, 153, NULL, NULL, 'edit', 309, 310),
(157, 153, NULL, NULL, 'view', 311, 312),
(158, 153, NULL, NULL, 'delete', 313, 314),
(159, 2, NULL, NULL, 'Mixevals', 316, 345),
(160, 159, NULL, NULL, 'postProcess', 317, 318),
(161, 159, NULL, NULL, 'setUpAjaxList', 319, 320),
(162, 159, NULL, NULL, 'index', 321, 322),
(163, 159, NULL, NULL, 'ajaxList', 323, 324),
(164, 159, NULL, NULL, 'view', 325, 326),
(165, 159, NULL, NULL, 'add', 327, 328),
(166, 159, NULL, NULL, 'deleteQuestion', 329, 330),
(167, 159, NULL, NULL, 'deleteDescriptor', 331, 332),
(168, 159, NULL, NULL, 'edit', 333, 334),
(169, 159, NULL, NULL, 'copy', 335, 336),
(170, 159, NULL, NULL, 'delete', 337, 338),
(171, 159, NULL, NULL, 'previewMixeval', 339, 340),
(172, 159, NULL, NULL, 'renderRows', 341, 342),
(173, 159, NULL, NULL, 'update', 343, 344),
(174, 2, NULL, NULL, 'Oauthclients', 346, 357),
(175, 174, NULL, NULL, 'index', 347, 348),
(176, 174, NULL, NULL, 'add', 349, 350),
(177, 174, NULL, NULL, 'edit', 351, 352),
(178, 174, NULL, NULL, 'delete', 353, 354),
(179, 174, NULL, NULL, 'view', 355, 356),
(180, 2, NULL, NULL, 'Oauthtokens', 358, 369),
(181, 180, NULL, NULL, 'index', 359, 360),
(182, 180, NULL, NULL, 'add', 361, 362),
(183, 180, NULL, NULL, 'edit', 363, 364),
(184, 180, NULL, NULL, 'delete', 365, 366),
(185, 180, NULL, NULL, 'view', 367, 368),
(186, 2, NULL, NULL, 'Penalty', 370, 383),
(187, 186, NULL, NULL, 'save', 371, 372),
(188, 186, NULL, NULL, 'add', 373, 374),
(189, 186, NULL, NULL, 'edit', 375, 376),
(190, 186, NULL, NULL, 'index', 377, 378),
(191, 186, NULL, NULL, 'view', 379, 380),
(192, 186, NULL, NULL, 'delete', 381, 382),
(193, 2, NULL, NULL, 'Rubrics', 384, 405),
(194, 193, NULL, NULL, 'postProcess', 385, 386),
(195, 193, NULL, NULL, 'setUpAjaxList', 387, 388),
(196, 193, NULL, NULL, 'index', 389, 390),
(197, 193, NULL, NULL, 'ajaxList', 391, 392),
(198, 193, NULL, NULL, 'view', 393, 394),
(199, 193, NULL, NULL, 'add', 395, 396),
(200, 193, NULL, NULL, 'edit', 397, 398),
(201, 193, NULL, NULL, 'copy', 399, 400),
(202, 193, NULL, NULL, 'delete', 401, 402),
(203, 193, NULL, NULL, 'setForm_RubricName', 403, 404),
(204, 2, NULL, NULL, 'Searchs', 406, 433),
(205, 204, NULL, NULL, 'update', 407, 408),
(206, 204, NULL, NULL, 'index', 409, 410),
(207, 204, NULL, NULL, 'searchEvaluation', 411, 412),
(208, 204, NULL, NULL, 'searchResult', 413, 414),
(209, 204, NULL, NULL, 'searchInstructor', 415, 416),
(210, 204, NULL, NULL, 'eventBoxSearch', 417, 418),
(211, 204, NULL, NULL, 'formatSearchEvaluation', 419, 420),
(212, 204, NULL, NULL, 'formatSearchInstructor', 421, 422),
(213, 204, NULL, NULL, 'formatSearchEvaluationResult', 423, 424),
(214, 204, NULL, NULL, 'add', 425, 426),
(215, 204, NULL, NULL, 'edit', 427, 428),
(216, 204, NULL, NULL, 'view', 429, 430),
(217, 204, NULL, NULL, 'delete', 431, 432),
(218, 2, NULL, NULL, 'Simpleevaluations', 434, 453),
(219, 218, NULL, NULL, 'postProcess', 435, 436),
(220, 218, NULL, NULL, 'setUpAjaxList', 437, 438),
(221, 218, NULL, NULL, 'index', 439, 440),
(222, 218, NULL, NULL, 'ajaxList', 441, 442),
(223, 218, NULL, NULL, 'view', 443, 444),
(224, 218, NULL, NULL, 'add', 445, 446),
(225, 218, NULL, NULL, 'edit', 447, 448),
(226, 218, NULL, NULL, 'copy', 449, 450),
(227, 218, NULL, NULL, 'delete', 451, 452),
(228, 2, NULL, NULL, 'Surveygroups', 454, 485),
(229, 228, NULL, NULL, 'postProcess', 455, 456),
(230, 228, NULL, NULL, 'setUpAjaxList', 457, 458),
(231, 228, NULL, NULL, 'index', 459, 460),
(232, 228, NULL, NULL, 'ajaxList', 461, 462),
(233, 228, NULL, NULL, 'viewresult', 463, 464),
(234, 228, NULL, NULL, 'makegroups', 465, 466),
(235, 228, NULL, NULL, 'makegroupssearch', 467, 468),
(236, 228, NULL, NULL, 'maketmgroups', 469, 470),
(237, 228, NULL, NULL, 'savegroups', 471, 472),
(238, 228, NULL, NULL, 'release', 473, 474),
(239, 228, NULL, NULL, 'delete', 475, 476),
(240, 228, NULL, NULL, 'edit', 477, 478),
(241, 228, NULL, NULL, 'changegroupset', 479, 480),
(242, 228, NULL, NULL, 'add', 481, 482),
(243, 228, NULL, NULL, 'view', 483, 484),
(244, 2, NULL, NULL, 'Surveys', 486, 519),
(245, 244, NULL, NULL, 'setUpAjaxList', 487, 488),
(246, 244, NULL, NULL, 'index', 489, 490),
(247, 244, NULL, NULL, 'ajaxList', 491, 492),
(248, 244, NULL, NULL, 'view', 493, 494),
(249, 244, NULL, NULL, 'add', 495, 496),
(250, 244, NULL, NULL, 'edit', 497, 498),
(251, 244, NULL, NULL, 'copy', 499, 500),
(252, 244, NULL, NULL, 'delete', 501, 502),
(253, 244, NULL, NULL, 'checkDuplicateName', 503, 504),
(254, 244, NULL, NULL, 'releaseSurvey', 505, 506),
(255, 244, NULL, NULL, 'questionsSummary', 507, 508),
(256, 244, NULL, NULL, 'moveQuestion', 509, 510),
(257, 244, NULL, NULL, 'removeQuestion', 511, 512),
(258, 244, NULL, NULL, 'addQuestion', 513, 514),
(259, 244, NULL, NULL, 'editQuestion', 515, 516),
(260, 244, NULL, NULL, 'update', 517, 518),
(261, 2, NULL, NULL, 'Sysparameters', 520, 535),
(262, 261, NULL, NULL, 'setUpAjaxList', 521, 522),
(263, 261, NULL, NULL, 'index', 523, 524),
(264, 261, NULL, NULL, 'ajaxList', 525, 526),
(265, 261, NULL, NULL, 'view', 527, 528),
(266, 261, NULL, NULL, 'add', 529, 530),
(267, 261, NULL, NULL, 'edit', 531, 532),
(268, 261, NULL, NULL, 'delete', 533, 534),
(269, 2, NULL, NULL, 'Upgrade', 536, 549),
(270, 269, NULL, NULL, 'index', 537, 538),
(271, 269, NULL, NULL, 'step2', 539, 540),
(272, 269, NULL, NULL, 'add', 541, 542),
(273, 269, NULL, NULL, 'edit', 543, 544),
(274, 269, NULL, NULL, 'view', 545, 546),
(275, 269, NULL, NULL, 'delete', 547, 548),
(276, 2, NULL, NULL, 'Users', 550, 579),
(277, 276, NULL, NULL, 'ajaxList', 551, 552),
(278, 276, NULL, NULL, 'index', 553, 554),
(279, 276, NULL, NULL, 'goToClassList', 555, 556),
(280, 276, NULL, NULL, 'determineIfStudentFromThisData', 557, 558),
(281, 276, NULL, NULL, 'getSimpleEntrollmentLists', 559, 560),
(282, 276, NULL, NULL, 'view', 561, 562),
(283, 276, NULL, NULL, 'add', 563, 564),
(284, 276, NULL, NULL, 'edit', 565, 566),
(285, 276, NULL, NULL, 'editProfile', 567, 568),
(286, 276, NULL, NULL, 'delete', 569, 570),
(287, 276, NULL, NULL, 'checkDuplicateName', 571, 572),
(288, 276, NULL, NULL, 'resetPassword', 573, 574),
(289, 276, NULL, NULL, 'import', 575, 576),
(290, 276, NULL, NULL, 'update', 577, 578),
(291, 2, NULL, NULL, 'V1', 580, 615),
(292, 291, NULL, NULL, 'oauth', 581, 582),
(293, 291, NULL, NULL, 'oauth_error', 583, 584),
(294, 291, NULL, NULL, 'users', 585, 586),
(295, 291, NULL, NULL, 'courses', 587, 588),
(296, 291, NULL, NULL, 'groups', 589, 590),
(297, 291, NULL, NULL, 'groupMembers', 591, 592),
(298, 291, NULL, NULL, 'events', 593, 594),
(299, 291, NULL, NULL, 'grades', 595, 596),
(300, 291, NULL, NULL, 'departments', 597, 598),
(301, 291, NULL, NULL, 'courseDepartments', 599, 600),
(302, 291, NULL, NULL, 'userEvents', 601, 602),
(303, 291, NULL, NULL, 'enrolment', 603, 604),
(304, 291, NULL, NULL, 'add', 605, 606),
(305, 291, NULL, NULL, 'edit', 607, 608),
(306, 291, NULL, NULL, 'index', 609, 610),
(307, 291, NULL, NULL, 'view', 611, 612),
(308, 291, NULL, NULL, 'delete', 613, 614),
(309, 2, NULL, NULL, 'Guard', 616, 633),
(310, 309, NULL, NULL, 'Guard', 617, 632),
(311, 310, NULL, NULL, 'login', 618, 619),
(312, 310, NULL, NULL, 'logout', 620, 621),
(313, 310, NULL, NULL, 'add', 622, 623),
(314, 310, NULL, NULL, 'edit', 624, 625),
(315, 310, NULL, NULL, 'index', 626, 627),
(316, 310, NULL, NULL, 'view', 628, 629),
(317, 310, NULL, NULL, 'delete', 630, 631),
(318, NULL, NULL, NULL, 'functions', 635, 698),
(319, 318, NULL, NULL, 'user', 636, 663),
(320, 319, NULL, NULL, 'superadmin', 637, 638),
(321, 319, NULL, NULL, 'admin', 639, 640),
(322, 319, NULL, NULL, 'instructor', 641, 642),
(323, 319, NULL, NULL, 'tutor', 643, 644),
(324, 319, NULL, NULL, 'student', 645, 646),
(325, 319, NULL, NULL, 'import', 647, 648),
(326, 319, NULL, NULL, 'password_reset', 649, 660),
(327, 326, NULL, NULL, 'superadmin', 650, 651),
(328, 326, NULL, NULL, 'admin', 652, 653),
(329, 326, NULL, NULL, 'instructor', 654, 655),
(330, 326, NULL, NULL, 'tutor', 656, 657),
(331, 326, NULL, NULL, 'student', 658, 659),
(332, 319, NULL, NULL, 'index', 661, 662),
(333, 318, NULL, NULL, 'role', 664, 675),
(334, 333, NULL, NULL, 'superadmin', 665, 666),
(335, 333, NULL, NULL, 'admin', 667, 668),
(336, 333, NULL, NULL, 'instructor', 669, 670),
(337, 333, NULL, NULL, 'tutor', 671, 672),
(338, 333, NULL, NULL, 'student', 673, 674),
(339, 318, NULL, NULL, 'evaluation', 676, 679),
(340, 339, NULL, NULL, 'export', 677, 678),
(341, 318, NULL, NULL, 'email', 680, 687),
(342, 341, NULL, NULL, 'allUsers', 681, 682),
(343, 341, NULL, NULL, 'allGroups', 683, 684),
(344, 341, NULL, NULL, 'allCourses', 685, 686),
(345, 318, NULL, NULL, 'emailtemplate', 688, 689),
(346, 318, NULL, NULL, 'viewstudentresults', 690, 691),
(347, 318, NULL, NULL, 'viewemailaddresses', 692, 693),
(348, 318, NULL, NULL, 'superadmin', 694, 695),
(349, 318, NULL, NULL, 'onlytakeeval', 696, 697);


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

(1, 1, 2, '1', '1', '1', '1'),
(2, 1, 318, '1', '1', '1', '1'),
(3, 1, 51, '1', '1', '1', '1'),
(4, 1, 218, '1', '1', '1', '1'),
(5, 1, 193, '1', '1', '1', '1'),
(6, 1, 159, '1', '1', '1', '1'),
(7, 1, 244, '1', '1', '1', '1'),
(8, 1, 25, '1', '1', '1', '1'),
(9, 1, 41, '1', '1', '1', '1'),
(10, 1, 88, '1', '1', '1', '1'),
(11, 1, 104, '1', '1', '1', '1'),
(12, 1, 19, '1', '1', '1', '1'),
(13, 1, 119, '1', '1', '1', '1'),
(14, 1, 1, '1', '1', '1', '1'),
(15, 1, 347, '1', '1', '1', '1'),
(16, 1, 348, '1', '1', '1', '1'),
(17, 2, 2, '-1', '-1', '-1', '-1'),
(18, 2, 136, '1', '1', '1', '1'),
(19, 2, 10, '1', '1', '1', '1'),
(20, 2, 19, '1', '1', '1', '1'),
(21, 2, 25, '1', '1', '1', '1'),
(22, 2, 41, '1', '1', '1', '1'),
(23, 2, 51, '1', '1', '1', '1'),
(24, 2, 88, '1', '1', '1', '1'),
(25, 2, 119, '1', '1', '1', '1'),
(26, 2, 159, '1', '1', '1', '1'),
(27, 2, 193, '1', '1', '1', '1'),
(28, 2, 218, '1', '1', '1', '1'),
(29, 2, 244, '1', '1', '1', '1'),
(30, 2, 228, '1', '1', '1', '1'),
(31, 2, 276, '1', '1', '1', '1'),
(32, 2, 58, '1', '1', '1', '1'),
(33, 2, 312, '1', '1', '1', '1'),
(34, 2, 176, '1', '1', '1', '1'),
(35, 2, 178, '1', '1', '1', '1'),
(36, 2, 182, '1', '1', '1', '1'),
(37, 2, 184, '1', '1', '1', '1'),
(38, 2, 318, '-1', '-1', '-1', '-1'),
(39, 2, 345, '1', '1', '1', '1'),
(40, 2, 339, '1', '1', '1', '1'),
(41, 2, 342, '1', '1', '1', '1'),
(42, 2, 319, '1', '1', '1', '1'),
(43, 2, 321, '-1', '1', '-1', '-1'),
(44, 2, 320, '-1', '-1', '-1', '-1'),
(45, 2, 1, '1', '1', '1', '1'),
(46, 2, 347, '1', '1', '1', '1'),
(47, 2, 348, '-1', '-1', '-1', '-1'),
(48, 3, 2, '-1', '-1', '-1', '-1'),
(49, 3, 136, '1', '1', '1', '1'),
(50, 3, 10, '1', '1', '1', '1'),
(51, 3, 16, '-1', '-1', '-1', '-1'),
(52, 3, 17, '-1', '-1', '-1', '-1'),
(53, 3, 25, '1', '1', '1', '1'),
(54, 3, 41, '1', '1', '1', '1'),
(55, 3, 51, '1', '1', '1', '1'),
(56, 3, 88, '1', '1', '1', '1'),
(57, 3, 119, '1', '1', '1', '1'),
(58, 3, 159, '1', '1', '1', '1'),
(59, 3, 193, '1', '1', '1', '1'),
(60, 3, 218, '1', '1', '1', '1'),
(61, 3, 244, '1', '1', '1', '1'),
(62, 3, 228, '1', '1', '1', '1'),
(63, 3, 276, '1', '1', '1', '1'),
(64, 3, 312, '1', '1', '1', '1'),
(65, 3, 176, '1', '1', '1', '1'),
(66, 3, 178, '1', '1', '1', '1'),
(67, 3, 182, '1', '1', '1', '1'),
(68, 3, 184, '1', '1', '1', '1'),
(69, 3, 318, '-1', '-1', '-1', '-1'),
(70, 3, 339, '1', '1', '-1', '-1'),
(71, 3, 319, '1', '1', '1', '1'),
(72, 3, 321, '-1', '-1', '-1', '-1'),
(73, 3, 320, '-1', '-1', '-1', '-1'),
(74, 3, 322, '-1', '1', '-1', '-1'),
(75, 3, 332, '-1', '-1', '-1', '-1'),
(76, 3, 347, '-1', '-1', '-1', '-1'),
(77, 3, 348, '-1', '-1', '-1', '-1'),
(78, 3, 349, '-1', '-1', '-1', '-1'),
(79, 4, 2, '-1', '-1', '-1', '-1'),
(80, 4, 136, '1', '1', '1', '1'),
(81, 4, 10, '-1', '-1', '-1', '-1'),
(82, 4, 25, '-1', '-1', '-1', '-1'),
(83, 4, 41, '-1', '-1', '-1', '-1'),
(84, 4, 51, '-1', '-1', '-1', '-1'),
(85, 4, 88, '-1', '-1', '-1', '-1'),
(86, 4, 119, '-1', '-1', '-1', '-1'),
(87, 4, 159, '-1', '-1', '-1', '-1'),
(88, 4, 193, '-1', '-1', '-1', '-1'),
(89, 4, 218, '-1', '-1', '-1', '-1'),
(90, 4, 244, '-1', '-1', '-1', '-1'),
(91, 4, 276, '-1', '-1', '-1', '-1'),
(92, 4, 312, '1', '1', '1', '1'),
(93, 4, 66, '1', '1', '1', '1'),
(94, 4, 74, '1', '1', '1', '1'),
(95, 4, 69, '1', '1', '1', '1'),
(96, 4, 71, '1', '1', '1', '1'),
(97, 4, 285, '1', '1', '1', '1'),
(98, 4, 176, '1', '1', '1', '1'),
(99, 4, 178, '1', '1', '1', '1'),
(100, 4, 182, '1', '1', '1', '1'),
(101, 4, 184, '1', '1', '1', '1'),
(102, 4, 318, '-1', '-1', '-1', '-1'),
(103, 4, 347, '-1', '-1', '-1', '-1'),
(104, 4, 348, '-1', '-1', '-1', '-1'),
(105, 4, 349, '1', '1', '1', '1'),
(106, 5, 2, '-1', '-1', '-1', '-1'),
(107, 5, 136, '1', '1', '1', '1'),
(108, 5, 10, '-1', '-1', '-1', '-1'),
(109, 5, 25, '-1', '-1', '-1', '-1'),
(110, 5, 41, '-1', '-1', '-1', '-1'),
(111, 5, 51, '-1', '-1', '-1', '-1'),
(112, 5, 88, '-1', '-1', '-1', '-1'),
(113, 5, 119, '-1', '-1', '-1', '-1'),
(114, 5, 159, '-1', '-1', '-1', '-1'),
(115, 5, 193, '-1', '-1', '-1', '-1'),
(116, 5, 218, '-1', '-1', '-1', '-1'),
(117, 5, 244, '-1', '-1', '-1', '-1'),
(118, 5, 276, '-1', '-1', '-1', '-1'),
(119, 5, 312, '1', '1', '1', '1'),
(120, 5, 66, '1', '1', '1', '1'),
(121, 5, 74, '1', '1', '1', '1'),
(122, 5, 69, '1', '1', '1', '1'),
(123, 5, 71, '1', '1', '1', '1'),
(124, 5, 285, '1', '1', '1', '1'),
(125, 5, 176, '1', '1', '1', '1'),
(126, 5, 178, '1', '1', '1', '1'),
(127, 5, 182, '1', '1', '1', '1'),
(128, 5, 184, '1', '1', '1', '1'),
(129, 5, 318, '-1', '-1', '-1', '-1'),
(130, 5, 346, '1', '1', '1', '1'),
(131, 5, 347, '-1', '-1', '-1', '-1'),
(132, 5, 348, '-1', '-1', '-1', '-1'),
(133, 5, 349, '1', '1', '1', '1');

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

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` VALUES (1, 'MECH 328', 'Mechanical Engineering Design Project', 'http://www.mech.ubc.ca', 'off', NULL, 'A', 1, '2006-06-20 14:14:45', NULL, '2006-06-20 14:14:45', 0);
INSERT INTO `courses` VALUES (2, 'APSC 201', 'Technical Communication', 'http://www.apsc.ubc.ca', 'off', NULL, 'A', 1, '2006-06-20 14:15:38', NULL, '2006-06-20 14:39:31', 0);
INSERT INTO `courses` VALUES (3, 'CPSC 101', 'Connecting with Computer Science', 'http://www.ugrad.cs.ubc.ca/~cs101/', 'off', NULL, 'I', 0, '2006-06-20 00:00:00', NULL, NULL, 0);
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
(1, 'root', 'b17c3f638781ecd22648b509e138c00f', 'Super', 'Admin', NULL, NULL, '', NULL, NULL, NULL, 'A', 1, NOW(), NULL, NOW(), NULL),
(2, 'instructor1', 'b17c3f638781ecd22648b509e138c00f', 'Instructor', '1', NULL, 'Instructor', 'instructor1@email', NULL, NULL, NULL, 'A', 1, '2006-06-19 16:25:24', NULL, '2006-06-19 16:25:24', NULL),
(3, 'instructor2', 'b17c3f638781ecd22648b509e138c00f', 'Instructor', '2', NULL, 'Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:02', NULL, '2006-06-20 14:17:02', NULL),
(4, 'instructor3', 'b17c3f638781ecd22648b509e138c00f', 'Instructor', '3', NULL, 'Assistant Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:53', NULL, '2006-06-20 14:17:53', NULL),
(5, '65498451', 'b17c3f638781ecd22648b509e138c00f', 'Ed', 'Student', '65498451', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:19:18', NULL, '2006-06-20 14:19:18', NULL),
(6, '65468188', 'b17c3f638781ecd22648b509e138c00f', 'Alex', 'Student', '65468188', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:26:59', NULL, '2006-06-20 14:26:59', NULL),
(7, '98985481', 'b17c3f638781ecd22648b509e138c00f', 'Matt', 'Student', '98985481', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:24', NULL, '2006-06-20 14:27:24', NULL),
(8, '16585158', 'b17c3f638781ecd22648b509e138c00f', 'Chris', 'Student', '16585158', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:43', NULL, '2006-06-20 14:27:43', NULL),
(9, '81121651', 'b17c3f638781ecd22648b509e138c00f', 'Johnny', 'Student', '81121651', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:08', NULL, '2006-06-20 14:28:08', NULL),
(10, '87800283', 'b17c3f638781ecd22648b509e138c00f', 'Travis', 'Student', '87800283', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:29', NULL, '2006-06-20 14:28:29', NULL),
(11, '68541180', 'b17c3f638781ecd22648b509e138c00f', 'Kelly', 'Student', '68541180', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:49', NULL, '2006-06-20 14:28:49', NULL),
(12, '48451389', 'b17c3f638781ecd22648b509e138c00f', 'Peter', 'Student', '48451389', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:29:07', NULL, '2006-06-20 14:29:07', NULL),
(13, '84188465', 'b17c3f638781ecd22648b509e138c00f', 'Damien', 'Student', '84188465', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:31:17', NULL, '2006-06-20 14:31:17', NULL),
(14, '27701036', 'b17c3f638781ecd22648b509e138c00f', 'Hajar', 'Student', '27701036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:47:34', NULL, '2006-06-20 14:47:34', NULL),
(15, '48877031', 'b17c3f638781ecd22648b509e138c00f', 'Jennifer', 'Student', '48877031', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:00:35', NULL, '2006-06-20 15:00:35', NULL),
(16, '25731063', 'b17c3f638781ecd22648b509e138c00f', 'Chad', 'Student', '25731063', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:09', NULL, '2006-06-20 15:01:09', NULL),
(17, '37116036', 'b17c3f638781ecd22648b509e138c00f', 'Edna', 'Student', '37116036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:24', NULL, '2006-06-20 15:01:24', NULL),
(18, '76035030', 'b17c3f638781ecd22648b509e138c00f', 'Denny', 'Student', '76035030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:52', NULL, '2006-06-20 15:01:52', NULL),
(19, '90938044', 'b17c3f638781ecd22648b509e138c00f', 'Jonathan', 'Student', '90938044', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:02:20', NULL, '2006-06-20 15:02:20', NULL),
(20, '88505045', 'b17c3f638781ecd22648b509e138c00f', 'Soroush', 'Student', '88505045', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:27', NULL, '2006-06-20 15:03:27', NULL),
(21, '22784037', 'b17c3f638781ecd22648b509e138c00f', 'Nicole', 'Student', '22784037', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:47', NULL, '2006-06-20 15:03:47', NULL),
(22, '37048022', 'b17c3f638781ecd22648b509e138c00f', 'Vivian', 'Student', '37048022', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:04:22', NULL, '2006-06-20 15:04:22', NULL),
(23, '89947048', 'b17c3f638781ecd22648b509e138c00f', 'Trevor', 'Student', '89947048', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:05:55', NULL, '2006-06-20 15:05:55', NULL),
(24, '39823059', 'b17c3f638781ecd22648b509e138c00f', 'Michael', 'Student', '39823059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:20', NULL, '2006-06-20 15:06:20', NULL),
(25, '35644039', 'b17c3f638781ecd22648b509e138c00f', 'Steven', 'Student', '35644039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:46', NULL, '2006-06-20 15:06:46', NULL),
(26, '19524032', 'b17c3f638781ecd22648b509e138c00f', 'Bill', 'Student', '19524032', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:01', NULL, '2006-06-20 15:07:01', NULL),
(27, '40289059', 'b17c3f638781ecd22648b509e138c00f', 'Van Hong', 'Student', '40289059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:37', NULL, '2006-06-20 15:07:37', NULL),
(28, '38058020', 'b17c3f638781ecd22648b509e138c00f', 'Michael', 'Student', '38058020', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:04', NULL, '2006-06-20 15:08:04', NULL),
(29, '38861035', 'b17c3f638781ecd22648b509e138c00f', 'Jonathan', 'Student', '38861035', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:31', NULL, '2006-06-20 15:08:31', NULL),
(30, '27879030', 'b17c3f638781ecd22648b509e138c00f', 'Geoff', 'Student', '27879030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:47', NULL, '2006-06-20 15:08:47', NULL),
(31, '10186039', 'b17c3f638781ecd22648b509e138c00f', 'Hui', 'Student', '10186039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:16', NULL, '2006-06-20 15:10:16', NULL),
(32, '19803030', 'b17c3f638781ecd22648b509e138c00f', 'Bowinn', 'Student', '19803030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:32', NULL, '2006-06-20 15:10:32', NULL),
(33, '51516498', 'b17c3f638781ecd22648b509e138c00f', 'Joe', 'Student', '51516498', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-21 08:44:09', 33, '2006-06-21 08:45:00', NULL),
(34, 'admin1', 'b17c3f638781ecd22648b509e138c00f', '', '', '', '', '', NULL, NULL, NULL, 'A', 1, '2012-05-25 15:48:08', 1, '2012-05-25 15:48:08', NULL),
(35, 'tutor1', 'b17c3f638781ecd22648b509e138c00f', 'Tutor', '1', '', '', '', NULL, NULL, NULL, 'A', 1, '2012-05-25 15:48:08', 1, '2012-05-25 15:48:08', NULL),
(36, 'tutor2', 'b17c3f638781ecd22648b509e138c00f', 'Tutor', '2', '', '', '', NULL, NULL, NULL, 'A', 1, '2012-05-25 15:48:08', 1, '2012-05-25 15:48:08', NULL),
(37, 'tutor3', 'b17c3f638781ecd22648b509e138c00f', 'Tutor', '3', '', '', '', NULL, NULL, NULL, 'A', 1, '2012-05-25 15:48:08', 1, '2012-05-25 15:48:08', NULL);

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

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`id`, `name`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
(1, 'Applied Science', 0, '0000-00-00 00:00:00', NULL, '2012-05-23 11:29:58'),
(2, 'Science', 0, '0000-00-00 00:00:00', NULL, '2012-05-23 11:30:05');

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

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `faculty_id`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
(1, 'MECH', 1, 0, '0000-00-00 00:00:00', NULL, '2012-05-23 11:30:41'),
(2, 'APSC', 1, 0, '0000-00-00 00:00:00', NULL, '2012-05-23 11:30:57'),
(3, 'CPSC', 2, 0, '0000-00-00 00:00:00', NULL, '2012-05-23 11:31:07');

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

--
-- Dumping data for table `course_departments`
--

INSERT INTO `course_departments` (`id`, `course_id`, `department_id`) VALUES
(1, 2, 2),
(2, 1, 1),
(3, 3, 3);

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

--
-- Dumping data for table `email_schedules`
--

INSERT INTO `email_schedules` (`id`, `subject`, `content`, `date`, `from`, `to`, `course_id`, `event_id`, `grp_id`, `sent`, `creator_id`, `created`) VALUES
(1, 'Email Template', 'Hello, {{{FIRSTNAME}}}', '2013-07-18 16:52:31', '1', '5;6;7;13;15;17;19;21;26;28;31;32;33', NULL, NULL, NULL, 0, 1, '2012-07-16 16:52:50'),
(2, 'Email Template', 'Hello, {{{USERNAME}}}', '2011-07-18 16:52:31', '1', '5;6;7;13;15;17;19;21;26;28;31;32;33', NULL, NULL, NULL, 0, 1, '2010-07-16 16:57:50'),
(3, 'Email Template', 'Hi, {{{USERNAME}}}', '2011-07-18 17:52:31', '1', '5;6;7;13;15;17;19;21;26;28;31;32;33', NULL, NULL, NULL, 1, 1, '2010-07-16 16:57:50');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `description`, `subject`, `content`, `availability`, `creator_id`, `created`, `updater_id`, `updated`) VALUES
(1, 'Email template example', 'This is an email template example', 'Email Template', 'Hello, {{{USERNAME}}}',1, 1, '0000-00-00', NULL, NULL),
(2, 'Email template example2', 'email template ex2', 'Email Template2', 'Hello, {{{FIRSTNAME}}}',1, 2, '0000-00-00', NULL, NULL),
(3, 'Email template example3', 'email temp example3', 'Email Template3', 'Hello,',1, 3, '0000-00-00', NULL, NULL);


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

--
-- Dumping data for table `evaluation_mixeval_details`
--

INSERT INTO evaluation_mixeval_details (id, evaluation_mixeval_id, question_number, question_comment, selected_lom, grade, record_status, creator_id, created, updater_id, modified) VALUES
(1, 1, 0, NULL, 5, 1.00, 'A', 7, '2012-07-13 10:38:20', 7, '2012-07-13 10:38:20'),
(2, 1, 1, NULL, 5, 1.00, 'A', 7, '2012-07-13 10:38:20', 7, '2012-07-13 10:38:20'),
(3, 1, 2, NULL, 5, 1.00, 'A', 7, '2012-07-13 10:38:20', 7, '2012-07-13 10:38:20'),
(4, 1, 3, 'work very efficiently', 0, 0.00, 'A', 7, '2012-07-13 10:38:20', 7, '2012-07-13 10:38:20'),
(5, 1, 4, 'Contributed his part', 0, 0.00, 'A', 7, '2012-07-13 10:38:20', 7, '2012-07-13 10:38:20'),
(6, 1, 5, 'very easy to work with', 0, 0.00, 'A', 7, '2012-07-13 10:38:20', 7, '2012-07-13 10:38:20'),
(7, 2, 0, NULL, 4, 0.80, 'A', 7, '2012-07-13 10:39:28', 7, '2012-07-13 10:39:28'),
(8, 2, 1, NULL, 4, 0.80, 'A', 7, '2012-07-13 10:39:28', 7, '2012-07-13 10:39:28'),
(9, 2, 2, NULL, 4, 0.80, 'A', 7, '2012-07-13 10:39:28', 7, '2012-07-13 10:39:28'),
(10, 2, 3, 'Yes', 0, 0.00, 'A', 7, '2012-07-13 10:39:28', 7, '2012-07-13 10:39:28'),
(11, 2, 4, 'He contributed in all parts of the project.', 0, 0.00, 'A', 7, '2012-07-13 10:39:28', 7, '2012-07-13 10:39:28'),
(12, 2, 5, 'He is very easy to communicate with.', 0, 0.00, 'A', 7, '2012-07-13 10:39:28', 7, '2012-07-13 10:39:28'),
(13, 3, 0, NULL, 5, 1.00, 'A', 31, '2012-07-13 10:42:49', 31, '2012-07-13 10:42:49'),
(14, 3, 1, NULL, 5, 1.00, 'A', 31, '2012-07-13 10:42:49', 31, '2012-07-13 10:42:49'),
(15, 3, 2, NULL, 5, 1.00, 'A', 31, '2012-07-13 10:42:49', 31, '2012-07-13 10:42:49'),
(16, 3, 3, 'does great work', 0, 0.00, 'A', 31, '2012-07-13 10:42:49', 31, '2012-07-13 10:42:49'),
(17, 3, 4, 'willing to do their part', 0, 0.00, 'A', 31, '2012-07-13 10:42:49', 31, '2012-07-13 10:42:49'),
(18, 3, 5, 'absolutely easy to work with', 0, 0.00, 'A', 31, '2012-07-13 10:42:49', 31, '2012-07-13 10:42:49'),
(19, 4, 0, NULL, 4, 0.80, 'A', 31, '2012-07-13 10:43:59', 31, '2012-07-13 10:43:59'),
(20, 4, 1, NULL, 4, 0.80, 'A', 31, '2012-07-13 10:43:59', 31, '2012-07-13 10:43:59'),
(21, 4, 2, NULL, 5, 1.00, 'A', 31, '2012-07-13 10:43:59', 31, '2012-07-13 10:43:59'),
(22, 4, 3, 'produce efficient work', 0, 0.00, 'A', 31, '2012-07-13 10:43:59', 31, '2012-07-13 10:43:59'),
(23, 4, 4, 'definitely', 0, 0.00, 'A', 31, '2012-07-13 10:43:59', 31, '2012-07-13 10:43:59'),
(24, 4, 5, 'very easy to get along with', 0, 0.00, 'A', 31, '2012-07-13 10:43:59', 31, '2012-07-13 10:43:59'),
(25, 5, 0, NULL, 4, 0.80, 'A', 5, '2012-07-13 15:19:26', 5, '2012-07-13 15:19:26'),
(26, 5, 1, NULL, 4, 0.80, 'A', 5, '2012-07-13 15:19:26', 5, '2012-07-13 15:19:26'),
(27, 5, 2, NULL, 4, 0.80, 'A', 5, '2012-07-13 15:19:26', 5, '2012-07-13 15:19:26'),
(28, 5, 3, 'Yes', 0, 0.00, 'A', 5, '2012-07-13 15:19:26', 5, '2012-07-13 15:19:26'),
(29, 5, 4, 'Yes', 0, 0.00, 'A', 5, '2012-07-13 15:19:26', 5, '2012-07-13 15:19:26'),
(30, 5, 5, 'Yes', 0, 0.00, 'A', 5, '2012-07-13 15:19:26', 5, '2012-07-13 15:19:26');

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

--
-- Dumping data for table `evaluation_mixevals`
--

INSERT INTO evaluation_mixevals (id, evaluator, evaluatee, score, comment_release, grade_release, grp_event_id, event_id, record_status, creator_id, created, updater_id, modified) VALUES
(1, 7, 5, 3.00, 0, 0, 5, 3, 'A', 7, '2012-07-13 10:38:20', 7, '2012-07-13 10:38:20'),
(2, 7, 6, 2.40, 0, 0, 5, 3, 'A', 7, '2012-07-13 10:39:28', 7, '2012-07-13 10:39:28'),
(3, 31, 32, 3.00, 0, 0, 6, 3, 'A', 31, '2012-07-13 10:42:49', 31, '2012-07-13 10:42:49'),
(4, 31, 33, 2.60, 0, 0, 6, 3, 'A', 31, '2012-07-13 10:43:59', 31, '2012-07-13 10:43:59'),
(5, 5, 6, 2.40, 0, 0, 5, 3, 'A', 5, '2012-07-13 15:19:26', 5, '2012-07-13 15:19:26');

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

--
-- Dumping data for table `evaluation_rubric_details`
--

INSERT INTO evaluation_rubric_details (id, evaluation_rubric_id, criteria_number, criteria_comment, selected_lom, grade, record_status, creator_id, created, updater_id, modified) VALUES
(1, 1, 1, 'always on time', 5, 5.00, 'A', 31, '2012-07-13 10:26:47', 31, '2012-07-13 10:26:47'),
(2, 1, 2, 'willing to do their part', 5, 5.00, 'A', 31, '2012-07-13 10:26:47', 31, '2012-07-13 10:26:47'),
(3, 1, 3, 'everything was done a day early', 5, 5.00, 'A', 31, '2012-07-13 10:26:47', 31, '2012-07-13 10:26:47'),
(4, 2, 1, 'attended most meetings', 4, 4.00, 'A', 31, '2012-07-13 10:29:15', 31, '2012-07-13 10:29:15'),
(5, 2, 2, 'very co-operative', 5, 5.00, 'A', 31, '2012-07-13 10:29:15', 31, '2012-07-13 10:29:15'),
(6, 2, 3, 'finished all his work on time', 5, 5.00, 'A', 31, '2012-07-13 10:29:15', 31, '2012-07-13 10:29:15'),
(7, 3, 1, 'Yes', 5, 5.00, 'A', 7, '2012-07-13 10:30:29', 7, '2012-07-13 10:30:29'),
(8, 3, 2, 'Absolutely', 4, 4.00, 'A', 7, '2012-07-13 10:30:29', 7, '2012-07-13 10:30:29'),
(9, 3, 3, 'Definitely', 5, 5.00, 'A', 7, '2012-07-13 10:30:29', 7, '2012-07-13 10:30:29'),
(10, 4, 1, 'attended all of our team meetings', 5, 5.00, 'A', 7, '2012-07-13 10:31:19', 7, '2012-07-13 10:31:19'),
(11, 4, 2, 'very helpful in all parts of the project', 5, 5.00, 'A', 7, '2012-07-13 10:31:19', 7, '2012-07-13 10:31:19'),
(12, 4, 3, 'Yes', 5, 5.00, 'A', 7, '2012-07-13 10:31:19', 7, '2012-07-13 10:31:19');

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

--
-- Dumping data for table `evaluation_rubrics`
--

INSERT INTO evaluation_rubrics (id, evaluator, evaluatee, general_comment, score, comment_release, grade_release, grp_event_id, event_id, record_status, creator_id, created, updater_id, modified, rubric_id) VALUES
(1, 31, 32, 'We work well together.', 15.00, 0, 0, 4, 2, 'A', 31, '2012-07-13 10:26:47', 31, '2012-07-13 10:26:47', 1),
(2, 31, 33, 'He did a great job.', 14.00, 0, 0, 4, 2, 'A', 31, '2012-07-13 10:29:14', 31, '2012-07-13 10:29:15', 1),
(3, 7, 5, 'Good group member.', 14.00, 0, 0, 3, 2, 'A', 7, '2012-07-13 10:30:29', 7, '2012-07-13 10:30:29', 1),
(4, 7, 6, 'Good job.', 15.00, 0, 0, 3, 2, 'A', 7, '2012-07-13 10:31:19', 7, '2012-07-13 10:31:19', 1);

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

--
-- Dumping data for table `evaluation_simples`
--

INSERT INTO evaluation_simples (id, evaluator, evaluatee, score, eval_comment, release_status, grp_event_id, event_id, date_submitted, grade_release, record_status, creator_id, created, updater_id, modified) VALUES
(1, 7, 5, 95, 'very hard working', 0, 1, 1, '2012-07-13 10:21:57', 0, 'A', 7, '2012-07-13 10:21:57', 7, '2012-07-13 10:21:57'),
(2, 7, 6, 105, 'did a decent job', 0, 1, 1, '2012-07-13 10:21:57', 0, 'A', 7, '2012-07-13 10:21:57', 7, '2012-07-13 10:21:57'),
(3, 31, 32, 125, 'very good job', 0, 2, 1, '2012-07-13 10:23:11', 0, 'A', 31, '2012-07-13 10:23:11', 31, '2012-07-13 10:23:11'),
(4, 31, 33, 75, 'he participated', 0, 2, 1, '2012-07-13 10:23:11', 0, 'A', 31, '2012-07-13 10:23:11', 31, '2012-07-13 10:23:11'),
(6, 5, 7, 105, '', 0, 7, 6, '2012-11-21 12:24:51', 0, 'A', 5, '2012-11-21 12:24:51', 5, '2012-11-21 12:24:51'),
(7, 5, 6, 95, '', 0, 7, 6, '2012-11-21 12:24:51', 0, 'A', 5, '2012-11-21 12:24:51', 5, '2012-11-21 12:24:51');



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

--
-- Dumping data for table `evaluation_submissions`
--

INSERT INTO evaluation_submissions (id, event_id, grp_event_id, submitter_id, submitted, date_submitted, record_status, creator_id, created, updater_id, modified) VALUES
(1, 1, 1, 7, 1, '2012-07-13 10:21:57', 'A', 7, '2012-07-13 10:21:57', 7, '2012-07-13 10:21:57'),
(2, 2, 3, 7, 1, '2012-07-13 11:04:11', 'A', 7, '2012-07-13 11:04:11', 7, '2012-07-13 11:04:11'),
(3, 3, 5, 7, 1, '2012-07-13 11:04:23', 'A', 7, '2012-07-13 11:04:23', 7, '2012-07-13 11:04:23'),
(4, 1, 2, 31, 1, '2012-07-13 10:23:11', 'A', 31, '2012-07-13 10:23:11', 31, '2012-07-13 10:23:11'),
(5, 2, 4, 31, 1, '2012-07-13 11:04:11', 'A', 31, '2012-07-13 11:04:11', 31, '2012-07-13 11:04:11'),
(6, 3, 6, 31, 1, '2012-07-13 11:06:23', 'A', 31, '2012-07-13 11:06:23', 31, '2012-07-13 11:06:23'),
(7, 4, NULL, 7, 1, '2012-07-13 11:23:31', 'A', 7, '2012-07-13 11:23:31', 7, '2012-07-13 11:23:31'),
(8, 4, NULL, 31, 1, '2012-07-13 11:24:09', 'A', 31, '2012-07-13 11:24:09', 31, '2012-07-13 11:24:09'),
(9, 5, NULL, 17, 1, '2012-07-17 10:10:10', 'A', 17, '2012-07-17 10:10:10', 17, '2012-07-17 10:10:10'),
(10, 6, 7, 5, 1, '2012-11-21 12:24:51', 'A', 5, '2012-11-21 12:24:51', 5, '2012-11-21 12:24:51');


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
(3, 'SURVEY', 'surveys', '', 0, 'A', 0, '0000-00-00 00:00:00', NULL, NULL),
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

--
-- Dumping data for table `events`
--

INSERT INTO events (id, title, course_id, description, event_template_type_id, template_id, self_eval, com_req, due_date, release_date_begin, release_date_end, result_release_date_begin, result_release_date_end, record_status, creator_id, created, updater_id, modified) VALUES
(1, 'Term 1 Evaluation', 1, '', 1, 1, '0', 0, '2013-07-02 16:34:43', '2011-06-16 16:34:49', '2023-07-22 16:34:53', '2024-07-04 16:34:43', '2024-07-30 16:34:43', 'A', 1, '2006-06-20 16:27:33', 1, '2006-06-21 08:51:20'),
(2, 'Term Report Evaluation', 1, '', 2, 1, '0', 0, '2013-06-08 08:59:29', '2011-06-06 08:59:35', '2023-07-02 08:59:41', '2024-06-09 08:59:29', '2024-07-08 08:59:29', 'A', 1, '2006-06-21 08:52:20', 1, '2006-06-21 08:54:25'),
(3, 'Project Evaluation', 1, '', 4, 1, '0', 0, '2013-07-02 09:00:28', '2011-06-07 09:00:35', '2023-07-09 09:00:39', '2023-07-04 09:00:28', '2024-07-12 09:00:28', 'A', 1, '2006-06-21 08:53:14', 1, '2006-06-21 09:07:26'),
(4, 'Team Creation Survey', 1, NULL, 3, 1, '1', 1, '2013-07-31 11:20:00', '2012-07-01 11:20:00', '2013-12-31 11:20:00', NULL, NULL, 'A', 2, '2012-07-13 11:18:56', 2, '2012-07-13 11:18:56'),
(5, 'Survey, all Q types', 1, NULL, 3, 2, '1', 1, '2013-07-31 11:20:00', '2012-07-01 11:20:00', '2013-12-31 11:20:00', NULL, NULL, 'A', 1, '2012-07-13 11:18:56', 1, '2012-07-13 11:18:56'),
(6, 'simple evaluation 2', 1, '2nd simple evaluation', 1, 1, '0', 0, '2012-11-28 00:00:00', '2012-11-20 00:00:00', '2022-11-29 00:00:00', '2022-11-30 00:00:00', '2022-12-12 00:00:00', 'A', 1, '2012-11-21 12:23:13', 1, '2012-11-21 12:23:13');

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

--
-- Dumping data for table `group_events`
--

INSERT INTO `group_events` VALUES (1, 1, 1, 'not reviewed', NULL, 'None', 'None', 'A', 0, '2006-06-20 16:27:33', NULL, '2006-06-20 16:27:33');
INSERT INTO `group_events` VALUES (2, 2, 1, 'not reviewed', NULL, 'None', 'None', 'A', 0, '2006-06-21 08:50:22', NULL, '2006-06-21 08:50:22');
INSERT INTO `group_events` VALUES (3, 1, 2, 'not reviewed', NULL, 'None', 'None', 'A', 0, '2006-06-21 08:52:20', NULL, '2006-06-21 08:52:20');
INSERT INTO `group_events` VALUES (4, 2, 2, 'not reviewed', NULL, 'None', 'None', 'A', 0, '2006-06-21 08:52:20', NULL, '2006-06-21 08:52:20');
INSERT INTO `group_events` VALUES (5, 1, 3, 'not reviewed', NULL, 'None', 'None', 'A', 0, '2006-06-21 08:53:23', NULL, '2006-06-21 08:53:23');
INSERT INTO `group_events` VALUES (6, 2, 3, 'not reviewed', NULL, 'None', 'None', 'A', 0, '2006-06-21 08:53:23', NULL, '2006-06-21 08:53:23');
INSERT INTO `group_events` VALUES (7, 1, 6, 'not reviewed', NULL, 'None', 'None', 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `group_events` VALUES (8, 2, 6, 'not reviewed', NULL, 'None', 'None', 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
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

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` VALUES (1, 1, 'Reapers', 1, 'A', 0, '2006-06-20 16:23:40', NULL, '2006-06-20 16:23:40');
INSERT INTO `groups` VALUES (2, 2, 'Lazy Engineers', 1, 'A', 0, '2006-06-21 08:47:04', NULL, '2006-06-21 08:49:53');

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

--
-- Dumping data for table `groups_members`
--

INSERT INTO `groups_members` VALUES (1, 1, 5);
INSERT INTO `groups_members` VALUES (2, 1, 6);
INSERT INTO `groups_members` VALUES (3, 1, 7);
INSERT INTO `groups_members` VALUES (4, 1, 35);
INSERT INTO `groups_members` VALUES (5, 2, 31);
INSERT INTO `groups_members` VALUES (6, 2, 32);
INSERT INTO `groups_members` VALUES (7, 2, 33);
INSERT INTO `groups_members` VALUES (8, 2, 36);

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

--
-- Dumping data for table `mixevals_questions`
--

INSERT INTO `mixevals_questions` (`id`, `mixeval_id`, `question_num`, `title`, `instructions`, `question_type`, `required`, `multiplier`, `scale_level`, `response_type`) VALUES
(1, 1, 1, 'Participated in Team Meetings', NULL, 'S', 1, 1, 5, NULL),
(2, 1, 2, 'Was Helpful and co-operative', NULL, 'S', 1, 1, 5, NULL),
(3, 1, 3, 'Submitted work on time', NULL, 'S', 1, 1, 5, NULL),
(4, 1, 4, 'Produced efficient work?', NULL, 'T', 1, 0, 5, 'S'),
(5, 1, 5, 'Contributed?', NULL, 'T', 1, 0, 5, 'L'),
(6, 1, 6, 'Easy to work with?', NULL, 'T', 0, 0, 5, 'S');

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

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `key`, `secret`, `comment`, `enabled`) VALUES
(1, 1, 'i//dt5l+kFYk/', 'M++SgeLVtL//locYEjkb/aLg2Q/', '', 1);

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

--
-- Dumping data for table `oauth_tokens`
--

INSERT INTO `oauth_tokens` (`id`, `user_id`, `key`, `secret`, `expires`, `comment`, `enabled`) VALUES
(1, 1, '//qu+Bfa/gr+C//p8//', '//Bgc3Ql+QQR/O+PEi6sJZG//', '2032-08-13', '', 1);
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

--
-- Dumping data for table `penalties`
--

INSERT INTO penalties (id, event_id, days_late, percent_penalty) VALUES
(1, 1, 1, 20),
(2, 1, 2, 40),
(3, 1, 3, 60),
(4, 1, 4, 100),
(5, 2, 1, 15),
(6, 2, 2, 30),
(7, 2, 3, 45),
(8, 2, 4, 60),
(9, 6, 1, 5),
(10, 6, 2, 10);

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

--
-- Dumping data for table `personalizes`
--

INSERT INTO `personalizes` VALUES (1, 1, 'Course.SubMenu.EvalEvents.Show', 'true', NULL, '2006-04-03 09:08:36');
INSERT INTO `personalizes` VALUES (2, 1, 'Course.SubMenu.SimpleEvals.Show', 'true', NULL, '2006-04-03 09:08:42');
INSERT INTO `personalizes` VALUES (3, 1, 'Course.SubMenu.Student.Show', 'true', NULL, '2006-04-03 09:08:47');
INSERT INTO `personalizes` VALUES (4, 1, 'Course.SubMenu.Group.Show', 'none', NULL, '2006-04-03 09:08:50');
INSERT INTO `personalizes` VALUES (5, 1, 'Course.SubMenu.EvalResults.Show', 'true', NULL, '2006-04-03 09:10:04');
INSERT INTO `personalizes` VALUES (6, 1, 'Course.SubMenu.Rubric.Show', 'true', NULL, '2006-04-04 14:40:46');
INSERT INTO `personalizes` VALUES (7, 1, 'Course.SubMenu.TeamMaker.Show', 'true', NULL, '2006-05-03 14:26:26');
INSERT INTO `personalizes` VALUES (8, 1, 'Mixeval.ListMenu.Limit.Show', '10', NULL, '2006-06-21 09:06:47');
INSERT INTO `personalizes` VALUES (9, 1, 'Course.ListMenu.Limit.Show', '10', '2006-06-21 09:06:52', '2006-06-21 09:06:52');
INSERT INTO `personalizes` VALUES (10, 1, 'Event.ListMenu.Limit.Show', '10', '2006-06-21 09:07:06', '2006-06-21 09:07:06');
INSERT INTO `personalizes` VALUES (11, 1, 'Survey.ListMenu.Limit.Show', '10', '2006-06-21 09:43:13', '2006-06-21 09:43:13');
INSERT INTO `personalizes` VALUES (12, 1, 'SurveyGroupSet.List.Limit.Show', '10', '2006-06-21 12:22:19', '2006-06-21 12:22:19');
INSERT INTO `personalizes` VALUES (13, 1, 'User.ListMenu.Limit.Show', '10', '2006-06-21 15:11:00', '2006-06-21 15:11:00');
INSERT INTO `personalizes` VALUES (14, 1, 'SimpleEval.ListMenu.Limit.Show', '10', '2006-06-21 15:12:56', '2006-06-21 15:12:56');
INSERT INTO `personalizes` VALUES (15, 1, 'Search.ListMenu.Limit.Show', '10', '2006-06-21 15:16:35', '2006-06-21 15:16:35');
INSERT INTO `personalizes` VALUES (16, 1, 'SysParam.ListMenu.Limit.Show', '10', '2006-06-21 15:16:37', '2006-06-21 15:16:37');

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

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` VALUES (1, 'What was your GPA last term?', 'M', 'no');
INSERT INTO `questions` VALUES (2, 'Do you own a laptop?', 'M', 'no');
INSERT INTO `questions` VALUES (3, 'Testing out MC', 'M', 'no');
INSERT INTO `questions` VALUES (4, 'Testing out checkboxes', 'C', 'no');
INSERT INTO `questions` VALUES (5, 'Testing out single line answers', 'S', 'no');
INSERT INTO `questions` VALUES (6, 'Testing out multi-line long answers', 'L', 'no');

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

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` VALUES (1, 1, '4+');
INSERT INTO `responses` VALUES (2, 1, '3-4');
INSERT INTO `responses` VALUES (3, 1, '2-3');
INSERT INTO `responses` VALUES (4, 1, '<2');
INSERT INTO `responses` VALUES (5, 2, 'yes');
INSERT INTO `responses` VALUES (6, 2, 'no');
INSERT INTO `responses` VALUES (7, 3, 'A');
INSERT INTO `responses` VALUES (8, 3, 'B');
INSERT INTO `responses` VALUES (9, 3, 'C');
INSERT INTO `responses` VALUES (10, 3, 'D');
INSERT INTO `responses` VALUES (11, 4, 'choose me');
INSERT INTO `responses` VALUES (12, 4, 'no, me');
INSERT INTO `responses` VALUES (13, 4, 'me please');

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
(1, 1, NOW(), NOW()),
(3, 2, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(3, 3, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(3, 4, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 5, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 6, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 7, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 8, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 9, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 10, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 11, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 12, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 13, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 14, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 15, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 16, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 17, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 18, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 19, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 20, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 21, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 22, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 23, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 24, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 25, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 26, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 27, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 28, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 29, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 30, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 31, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 32, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 33, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(2, 34, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(4, 35, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(4, 36, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(4, 37, '2010-10-27 16:17:29', '2010-10-27 16:17:29');

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

--
-- Dumping data for table `rubrics`
--

INSERT INTO `rubrics` VALUES (1, 'Term Report Evaluation', 0, 5, 3, 'public', 'horizontal', 1, '2006-06-20 15:21:50', NULL, '2006-06-20 15:21:50');

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

--
-- Dumping data for table `rubrics_criteria_comments`
--

INSERT INTO `rubrics_criteria_comments` VALUES (1, 1, 1, "No participation.");
INSERT INTO `rubrics_criteria_comments` VALUES (2, 1, 2, "Little participation.");
INSERT INTO `rubrics_criteria_comments` VALUES (3, 1, 3, "Some participation.");
INSERT INTO `rubrics_criteria_comments` VALUES (4, 1, 4, "Good participation.");
INSERT INTO `rubrics_criteria_comments` VALUES (5, 1, 5, "Great participation.");
INSERT INTO `rubrics_criteria_comments` VALUES (6, 2, 1, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (7, 2, 2, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (8, 2, 3, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (9, 2, 4, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (10, 2, 5, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (11, 3, 1, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (12, 3, 2, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (13, 3, 3, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (14, 3, 4, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (15, 3, 5, NULL);

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

--
-- Dumping data for table `rubrics_criterias`
--

INSERT INTO `rubrics_criterias` VALUES (1, 1, 1, 'Participated in Team Meetings', 5);
INSERT INTO `rubrics_criterias` VALUES (2, 1, 2, 'Was Helpful and Co-operative', 5);
INSERT INTO `rubrics_criterias` VALUES (3, 1, 3, 'Submitted Work on Time', 5);

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

--
-- Dumping data for table `rubrics_loms`
--

INSERT INTO `rubrics_loms` VALUES (1, 1, 1, 'Poor');
INSERT INTO `rubrics_loms` VALUES (2, 1, 2, 'Below Average');
INSERT INTO `rubrics_loms` VALUES (3, 1, 3, 'Average');
INSERT INTO `rubrics_loms` VALUES (4, 1, 4, 'Above Average');
INSERT INTO `rubrics_loms` VALUES (5, 1, 5, 'Excellent');

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

--
-- Dumping data for table `simple_evaluations`
--

INSERT INTO `simple_evaluations` VALUES (1, 'Module 1 Project Evaluation', '', 100, 'A', 'public', 1, '2006-06-20 15:17:47', NULL, '2006-06-20 15:17:47');

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

--
-- Dumping data for table `survey_group_members`
--

INSERT INTO `survey_group_members` VALUES (25, 3, 5, 17);
INSERT INTO `survey_group_members` VALUES (26, 3, 5, 21);
INSERT INTO `survey_group_members` VALUES (27, 3, 5, 15);
INSERT INTO `survey_group_members` VALUES (28, 3, 5, 19);
INSERT INTO `survey_group_members` VALUES (29, 3, 6, 6);
INSERT INTO `survey_group_members` VALUES (30, 3, 6, 32);
INSERT INTO `survey_group_members` VALUES (31, 3, 6, 26);
INSERT INTO `survey_group_members` VALUES (32, 3, 6, 13);
INSERT INTO `survey_group_members` VALUES (33, 3, 7, 7);
INSERT INTO `survey_group_members` VALUES (34, 3, 7, 28);
INSERT INTO `survey_group_members` VALUES (35, 3, 7, 5);
INSERT INTO `survey_group_members` VALUES (36, 3, 7, 33);

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

--
-- Dumping data for table `survey_group_sets`
--

INSERT INTO `survey_group_sets` VALUES (3, 1, 'test groupset', 3, 1150923956, 0);

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

--
-- Dumping data for table `survey_groups`
--

INSERT INTO `survey_groups` VALUES (5, 3, 1);
INSERT INTO `survey_groups` VALUES (6, 3, 2);
INSERT INTO `survey_groups` VALUES (7, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `survey_inputs`
--

DROP TABLE IF EXISTS `survey_inputs`;
CREATE TABLE IF NOT EXISTS `survey_inputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL DEFAULT '0',
  `sub_id` int(11) DEFAULT NULL,
  `chkbx_id` int(11) DEFAULT NULL,
  `response_text` text,
  `response_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `survey_inputs`
--

INSERT INTO survey_inputs (id, survey_id, user_id, question_id, sub_id, chkbx_id, response_text, response_id) VALUES
(1, 1, 7, 1, NULL, NULL, '4+', 1),
(2, 1, 7, 2, NULL, NULL, 'yes', 5),
(3, 1, 31, 1, NULL, NULL, '3-4', 2),
(4, 1, 31, 2, NULL, NULL, 'no', 6),
(5, 2, 17, 3, NULL, NULL, 'B', 8),
(6, 2, 17, 4, NULL, 0, 'choose me', 11),
(7, 2, 17, 4, NULL, 1, 'no, me', 12),
(8, 2, 17, 5, NULL, NULL, 'single line rah rah', 0),
(9, 2, 17, 6, NULL, NULL, 'long answer what what', 0);

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

--
-- Dumping data for table `survey_questions`
--

INSERT INTO survey_questions (id, survey_id, number, question_id) VALUES
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 2, 1, 3),
(4, 2, 2, 4),
(5, 2, 3, 5),
(6, 2, 4, 6);

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

--
-- Dumping data for table `surveys`
--

INSERT INTO surveys (id, course_id, user_id, name, due_date, release_date_begin, release_date_end, released, creator_id, created, updater_id, modified) VALUES
(1, 1, 1, 'Team Creation Survey', '2012-07-31 11:20:00', '2012-07-01 11:20:00', '2013-12-31 11:20:00', 0, 2, '2012-07-13 11:18:56', 2, '2012-07-13 11:18:56'),
(2, 1, 1, 'Survey, all Q types', '2012-07-31 11:20:00', '2012-07-01 11:20:00', '2013-12-31 11:20:00', 0, 1, '2012-07-13 11:18:56', 1, '2012-07-13 11:18:56');
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
(21, 'email.port', '465', 'S', 'port number for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(22, 'email.host', 'email_host', 'S', 'host address for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(23 , 'email.username', 'your_email_address', 'S', 'username for email smtp option', 'A', '0', NOW(), NULL , NOW()),
(24 , 'email.password', 'your_email_password', 'S', 'password for email smtp option', 'A', '0', NOW(), NULL , NOW());

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

--
-- Dumping data for table `user_courses`
--

INSERT INTO `user_courses` VALUES (1, 2, 1, 'A', 'A', 0, '2006-06-20 14:14:45', NULL, '2006-06-20 14:14:45');
INSERT INTO `user_courses` VALUES (2, 3, 2, 'A', 'A', 0, '2006-06-20 14:39:31', NULL, '2006-06-20 14:39:31');
INSERT INTO `user_courses` VALUES (3, 4, 2, 'A', 'A', 0, '2006-06-20 14:39:31', NULL, '2006-06-20 14:39:31');
INSERT INTO `user_courses` VALUES (4, 4, 3, 'A', 'A', 0, '2006-06-20 14:39:31', NULL, '2006-06-20 14:39:31');

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

--
-- Dumping data for table `user_enrols`
--

INSERT INTO `user_enrols` VALUES (1, 1, 5, 'A', 0, '2006-06-20 14:19:18', NULL, '2006-06-20 14:19:18');
INSERT INTO `user_enrols` VALUES (2, 1, 6, 'A', 0, '2006-06-20 14:26:59', NULL, '2006-06-20 14:26:59');
INSERT INTO `user_enrols` VALUES (3, 1, 7, 'A', 0, '2006-06-20 14:27:24', NULL, '2006-06-20 14:27:24');
INSERT INTO `user_enrols` VALUES (5, 2, 9, 'A', 0, '2006-06-20 14:28:08', NULL, '2006-06-20 14:28:08');
INSERT INTO `user_enrols` VALUES (6, 2, 10, 'A', 0, '2006-06-20 14:28:29', NULL, '2006-06-20 14:28:29');
INSERT INTO `user_enrols` VALUES (7, 2, 11, 'A', 0, '2006-06-20 14:28:49', NULL, '2006-06-20 14:28:49');
INSERT INTO `user_enrols` VALUES (8, 2, 12, 'A', 0, '2006-06-20 14:29:07', NULL, '2006-06-20 14:29:07');
INSERT INTO `user_enrols` VALUES (9, 1, 13, 'A', 0, '2006-06-20 14:31:17', NULL, '2006-06-20 14:31:17');
INSERT INTO `user_enrols` VALUES (11, 1, 15, 'A', 0, '2006-06-20 15:00:35', NULL, '2006-06-20 15:00:35');
INSERT INTO `user_enrols` VALUES (12, 2, 16, 'A', 0, '2006-06-20 15:01:09', NULL, '2006-06-20 15:01:09');
INSERT INTO `user_enrols` VALUES (13, 1, 17, 'A', 0, '2006-06-20 15:01:24', NULL, '2006-06-20 15:01:24');
INSERT INTO `user_enrols` VALUES (14, 2, 18, 'A', 0, '2006-06-20 15:01:52', NULL, '2006-06-20 15:01:52');
INSERT INTO `user_enrols` VALUES (15, 1, 19, 'A', 0, '2006-06-20 15:02:20', NULL, '2006-06-20 15:02:20');
INSERT INTO `user_enrols` VALUES (16, 2, 20, 'A', 0, '2006-06-20 15:03:27', NULL, '2006-06-20 15:03:27');
INSERT INTO `user_enrols` VALUES (17, 1, 21, 'A', 0, '2006-06-20 15:03:47', NULL, '2006-06-20 15:03:47');
INSERT INTO `user_enrols` VALUES (18, 2, 22, 'A', 0, '2006-06-20 15:04:22', NULL, '2006-06-20 15:04:22');
INSERT INTO `user_enrols` VALUES (19, 2, 23, 'A', 0, '2006-06-20 15:05:55', NULL, '2006-06-20 15:05:55');
INSERT INTO `user_enrols` VALUES (20, 2, 24, 'A', 0, '2006-06-20 15:06:20', NULL, '2006-06-20 15:06:20');
INSERT INTO `user_enrols` VALUES (21, 2, 25, 'A', 0, '2006-06-20 15:06:46', NULL, '2006-06-20 15:06:46');
INSERT INTO `user_enrols` VALUES (22, 1, 26, 'A', 0, '2006-06-20 15:07:01', NULL, '2006-06-20 15:07:01');
INSERT INTO `user_enrols` VALUES (23, 2, 27, 'A', 0, '2006-06-20 15:07:37', NULL, '2006-06-20 15:07:37');
INSERT INTO `user_enrols` VALUES (24, 1, 28, 'A', 0, '2006-06-20 15:08:04', NULL, '2006-06-20 15:08:04');
INSERT INTO `user_enrols` VALUES (25, 2, 29, 'A', 0, '2006-06-20 15:08:31', NULL, '2006-06-20 15:08:31');
INSERT INTO `user_enrols` VALUES (26, 2, 30, 'A', 0, '2006-06-20 15:08:47', NULL, '2006-06-20 15:08:47');
INSERT INTO `user_enrols` VALUES (27, 1, 31, 'A', 0, '2006-06-20 15:10:16', NULL, '2006-06-20 15:10:16');
INSERT INTO `user_enrols` VALUES (28, 1, 32, 'A', 0, '2006-06-20 15:10:32', NULL, '2006-06-20 15:10:32');
INSERT INTO `user_enrols` VALUES (29, 1, 33, 'A', 0, '2006-06-21 08:44:09', NULL, '2006-06-21 08:44:09');
INSERT INTO `user_enrols` VALUES (30, 2, 7, 'A', 0, '2006-06-21 08:44:09', NULL, '2006-06-21 08:44:09');
INSERT INTO `user_enrols` VALUES (31, 3, 33, 'A', 0, '2006-06-21 08:44:09', NULL, '2006-06-21 08:44:09');
INSERT INTO `user_enrols` VALUES (32, 3, 8, 'A', 0, '2006-06-21 08:44:09', NULL, '2006-06-21 08:44:09');

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

--
-- Dumping data for table `user_faculties`
--

INSERT INTO `user_faculties` (`id`, `user_id`, `faculty_id`) VALUES
(1, 34, 1),
(2, 1, 1),
(3, 1, 2);

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

--
-- Dumping data for table `user_tutors`
--

INSERT INTO user_tutors (id, user_id, course_id, creator_id, created, updater_id, modified) VALUES
(1, 35, 1, 0, '0000-00-00 00:00:00', NULL, '2012-07-13 09:45:57'),
(2, 36, 1, 0, '0000-00-00 00:00:00', NULL, '2012-07-13 09:48:16'),
(3, 37, 2, 0, '0000-00-00 00:00:00', NULL, '2012-07-13 09:48:24'),
(4, 37, 3, 0, '0000-00-00 00:00:00', NULL, '2012-07-13 09:48:24');

SET foreign_key_checks = 1;
