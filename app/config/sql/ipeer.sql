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

INSERT INTO acos (id, parent_id, model, foreign_key, alias, lft, rght) VALUES
(1, NULL, NULL, NULL, 'adminpage', 1, 2),
(2, NULL, NULL, NULL, 'controllers', 3, 646),
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
(58, 2, NULL, NULL, 'Evaluations', 114, 181),
(59, 58, NULL, NULL, 'postProcess', 115, 116),
(60, 58, NULL, NULL, 'setUpAjaxList', 117, 118),
(61, 58, NULL, NULL, 'ajaxList', 119, 120),
(62, 58, NULL, NULL, 'view', 121, 122),
(63, 58, NULL, NULL, 'index', 123, 124),
(64, 58, NULL, NULL, 'update', 125, 126),
(65, 58, NULL, NULL, 'export', 127, 128),
(66, 58, NULL, NULL, 'makeSimpleEvaluation', 129, 130),
(67, 58, NULL, NULL, 'validSimpleEvalComplete', 131, 132),
(68, 58, NULL, NULL, 'makeSurveyEvaluation', 133, 134),
(69, 58, NULL, NULL, 'validSurveyEvalComplete', 135, 136),
(70, 58, NULL, NULL, 'makeRubricEvaluation', 137, 138),
(71, 58, NULL, NULL, 'validRubricEvalComplete', 139, 140),
(72, 58, NULL, NULL, 'completeEvaluationRubric', 141, 142),
(73, 58, NULL, NULL, 'makeMixevalEvaluation', 143, 144),
(74, 58, NULL, NULL, 'validMixevalEvalComplete', 145, 146),
(75, 58, NULL, NULL, 'completeEvaluationMixeval', 147, 148),
(76, 58, NULL, NULL, 'viewEvaluationResults', 149, 150),
(77, 58, NULL, NULL, 'viewSurveyGroupEvaluationResults', 151, 152),
(78, 58, NULL, NULL, 'studentViewEvaluationResult', 153, 154),
(79, 58, NULL, NULL, 'markEventReviewed', 155, 156),
(80, 58, NULL, NULL, 'markGradeRelease', 157, 158),
(81, 58, NULL, NULL, 'markCommentRelease', 159, 160),
(82, 58, NULL, NULL, 'changeAllCommentRelease', 161, 162),
(83, 58, NULL, NULL, 'changeAllGradeRelease', 163, 164),
(84, 58, NULL, NULL, 'viewGroupSubmissionDetails', 165, 166),
(85, 58, NULL, NULL, 'viewSurveySummary', 167, 168),
(86, 58, NULL, NULL, 'export_rubic', 169, 170),
(87, 58, NULL, NULL, 'export_test', 171, 172),
(88, 58, NULL, NULL, 'pre', 173, 174),
(89, 58, NULL, NULL, 'add', 175, 176),
(90, 58, NULL, NULL, 'edit', 177, 178),
(91, 58, NULL, NULL, 'delete', 179, 180),
(92, 2, NULL, NULL, 'Events', 182, 213),
(93, 92, NULL, NULL, 'postProcessData', 183, 184),
(94, 92, NULL, NULL, 'setUpAjaxList', 185, 186),
(95, 92, NULL, NULL, 'index', 187, 188),
(96, 92, NULL, NULL, 'ajaxList', 189, 190),
(97, 92, NULL, NULL, 'view', 191, 192),
(98, 92, NULL, NULL, 'eventTemplatesList', 193, 194),
(99, 92, NULL, NULL, 'add', 195, 196),
(100, 92, NULL, NULL, 'edit', 197, 198),
(101, 92, NULL, NULL, 'delete', 199, 200),
(102, 92, NULL, NULL, 'search', 201, 202),
(103, 92, NULL, NULL, 'checkDuplicateTitle', 203, 204),
(104, 92, NULL, NULL, 'viewGroups', 205, 206),
(105, 92, NULL, NULL, 'editGroup', 207, 208),
(106, 92, NULL, NULL, 'getAssignedGroups', 209, 210),
(107, 92, NULL, NULL, 'update', 211, 212),
(108, 2, NULL, NULL, 'Faculties', 214, 225),
(109, 108, NULL, NULL, 'index', 215, 216),
(110, 108, NULL, NULL, 'view', 217, 218),
(111, 108, NULL, NULL, 'add', 219, 220),
(112, 108, NULL, NULL, 'edit', 221, 222),
(113, 108, NULL, NULL, 'delete', 223, 224),
(114, 2, NULL, NULL, 'Framework', 226, 243),
(115, 114, NULL, NULL, 'calendarDisplay', 227, 228),
(116, 114, NULL, NULL, 'userInfoDisplay', 229, 230),
(117, 114, NULL, NULL, 'tutIndex', 231, 232),
(118, 114, NULL, NULL, 'add', 233, 234),
(119, 114, NULL, NULL, 'edit', 235, 236),
(120, 114, NULL, NULL, 'index', 237, 238),
(121, 114, NULL, NULL, 'view', 239, 240),
(122, 114, NULL, NULL, 'delete', 241, 242),
(123, 2, NULL, NULL, 'Groups', 244, 277),
(124, 123, NULL, NULL, 'postProcess', 245, 246),
(125, 123, NULL, NULL, 'setUpAjaxList', 247, 248),
(126, 123, NULL, NULL, 'index', 249, 250),
(127, 123, NULL, NULL, 'ajaxList', 251, 252),
(128, 123, NULL, NULL, 'goToClassList', 253, 254),
(129, 123, NULL, NULL, 'view', 255, 256),
(130, 123, NULL, NULL, 'add', 257, 258),
(131, 123, NULL, NULL, 'edit', 259, 260),
(132, 123, NULL, NULL, 'delete', 261, 262),
(133, 123, NULL, NULL, 'checkDuplicateName', 263, 264),
(134, 123, NULL, NULL, 'getQueryAttribute', 265, 266),
(135, 123, NULL, NULL, 'import', 267, 268),
(136, 123, NULL, NULL, 'addGroupByImport', 269, 270),
(137, 123, NULL, NULL, 'update', 271, 272),
(138, 123, NULL, NULL, 'export', 273, 274),
(139, 123, NULL, NULL, 'sendGroupEmail', 275, 276),
(140, 2, NULL, NULL, 'Home', 278, 291),
(141, 140, NULL, NULL, 'index', 279, 280),
(142, 140, NULL, NULL, 'studentIndex', 281, 282),
(143, 140, NULL, NULL, 'add', 283, 284),
(144, 140, NULL, NULL, 'edit', 285, 286),
(145, 140, NULL, NULL, 'view', 287, 288),
(146, 140, NULL, NULL, 'delete', 289, 290),
(147, 2, NULL, NULL, 'Install', 292, 313),
(148, 147, NULL, NULL, 'index', 293, 294),
(149, 147, NULL, NULL, 'install2', 295, 296),
(150, 147, NULL, NULL, 'install3', 297, 298),
(151, 147, NULL, NULL, 'install4', 299, 300),
(152, 147, NULL, NULL, 'install5', 301, 302),
(153, 147, NULL, NULL, 'gpl', 303, 304),
(154, 147, NULL, NULL, 'add', 305, 306),
(155, 147, NULL, NULL, 'edit', 307, 308),
(156, 147, NULL, NULL, 'view', 309, 310),
(157, 147, NULL, NULL, 'delete', 311, 312),
(158, 2, NULL, NULL, 'Lti', 314, 325),
(159, 158, NULL, NULL, 'index', 315, 316),
(160, 158, NULL, NULL, 'add', 317, 318),
(161, 158, NULL, NULL, 'edit', 319, 320),
(162, 158, NULL, NULL, 'view', 321, 322),
(163, 158, NULL, NULL, 'delete', 323, 324),
(164, 2, NULL, NULL, 'Mixevals', 326, 355),
(165, 164, NULL, NULL, 'postProcess', 327, 328),
(166, 164, NULL, NULL, 'setUpAjaxList', 329, 330),
(167, 164, NULL, NULL, 'index', 331, 332),
(168, 164, NULL, NULL, 'ajaxList', 333, 334),
(169, 164, NULL, NULL, 'view', 335, 336),
(170, 164, NULL, NULL, 'add', 337, 338),
(171, 164, NULL, NULL, 'deleteQuestion', 339, 340),
(172, 164, NULL, NULL, 'deleteDescriptor', 341, 342),
(173, 164, NULL, NULL, 'edit', 343, 344),
(174, 164, NULL, NULL, 'copy', 345, 346),
(175, 164, NULL, NULL, 'delete', 347, 348),
(176, 164, NULL, NULL, 'previewMixeval', 349, 350),
(177, 164, NULL, NULL, 'renderRows', 351, 352),
(178, 164, NULL, NULL, 'update', 353, 354),
(179, 2, NULL, NULL, 'OauthClients', 356, 367),
(180, 179, NULL, NULL, 'index', 357, 358),
(181, 179, NULL, NULL, 'add', 359, 360),
(182, 179, NULL, NULL, 'edit', 361, 362),
(183, 179, NULL, NULL, 'delete', 363, 364),
(184, 179, NULL, NULL, 'view', 365, 366),
(185, 2, NULL, NULL, 'OauthTokens', 368, 379),
(186, 185, NULL, NULL, 'index', 369, 370),
(187, 185, NULL, NULL, 'add', 371, 372),
(188, 185, NULL, NULL, 'edit', 373, 374),
(189, 185, NULL, NULL, 'delete', 375, 376),
(190, 185, NULL, NULL, 'view', 377, 378),
(191, 2, NULL, NULL, 'Penalty', 380, 393),
(192, 191, NULL, NULL, 'save', 381, 382),
(193, 191, NULL, NULL, 'add', 383, 384),
(194, 191, NULL, NULL, 'edit', 385, 386),
(195, 191, NULL, NULL, 'index', 387, 388),
(196, 191, NULL, NULL, 'view', 389, 390),
(197, 191, NULL, NULL, 'delete', 391, 392),
(198, 2, NULL, NULL, 'Rubrics', 394, 415),
(199, 198, NULL, NULL, 'postProcess', 395, 396),
(200, 198, NULL, NULL, 'setUpAjaxList', 397, 398),
(201, 198, NULL, NULL, 'index', 399, 400),
(202, 198, NULL, NULL, 'ajaxList', 401, 402),
(203, 198, NULL, NULL, 'view', 403, 404),
(204, 198, NULL, NULL, 'add', 405, 406),
(205, 198, NULL, NULL, 'edit', 407, 408),
(206, 198, NULL, NULL, 'copy', 409, 410),
(207, 198, NULL, NULL, 'delete', 411, 412),
(208, 198, NULL, NULL, 'setForm_RubricName', 413, 414),
(209, 2, NULL, NULL, 'Searchs', 416, 443),
(210, 209, NULL, NULL, 'update', 417, 418),
(211, 209, NULL, NULL, 'index', 419, 420),
(212, 209, NULL, NULL, 'searchEvaluation', 421, 422),
(213, 209, NULL, NULL, 'searchResult', 423, 424),
(214, 209, NULL, NULL, 'searchInstructor', 425, 426),
(215, 209, NULL, NULL, 'eventBoxSearch', 427, 428),
(216, 209, NULL, NULL, 'formatSearchEvaluation', 429, 430),
(217, 209, NULL, NULL, 'formatSearchInstructor', 431, 432),
(218, 209, NULL, NULL, 'formatSearchEvaluationResult', 433, 434),
(219, 209, NULL, NULL, 'add', 435, 436),
(220, 209, NULL, NULL, 'edit', 437, 438),
(221, 209, NULL, NULL, 'view', 439, 440),
(222, 209, NULL, NULL, 'delete', 441, 442),
(223, 2, NULL, NULL, 'Simpleevaluations', 444, 463),
(224, 223, NULL, NULL, 'postProcess', 445, 446),
(225, 223, NULL, NULL, 'setUpAjaxList', 447, 448),
(226, 223, NULL, NULL, 'index', 449, 450),
(227, 223, NULL, NULL, 'ajaxList', 451, 452),
(228, 223, NULL, NULL, 'view', 453, 454),
(229, 223, NULL, NULL, 'add', 455, 456),
(230, 223, NULL, NULL, 'edit', 457, 458),
(231, 223, NULL, NULL, 'copy', 459, 460),
(232, 223, NULL, NULL, 'delete', 461, 462),
(233, 2, NULL, NULL, 'Surveygroups', 464, 495),
(234, 233, NULL, NULL, 'postProcess', 465, 466),
(235, 233, NULL, NULL, 'setUpAjaxList', 467, 468),
(236, 233, NULL, NULL, 'index', 469, 470),
(237, 233, NULL, NULL, 'ajaxList', 471, 472),
(238, 233, NULL, NULL, 'viewresult', 473, 474),
(239, 233, NULL, NULL, 'makegroups', 475, 476),
(240, 233, NULL, NULL, 'makegroupssearch', 477, 478),
(241, 233, NULL, NULL, 'maketmgroups', 479, 480),
(242, 233, NULL, NULL, 'savegroups', 481, 482),
(243, 233, NULL, NULL, 'release', 483, 484),
(244, 233, NULL, NULL, 'delete', 485, 486),
(245, 233, NULL, NULL, 'edit', 487, 488),
(246, 233, NULL, NULL, 'changegroupset', 489, 490),
(247, 233, NULL, NULL, 'add', 491, 492),
(248, 233, NULL, NULL, 'view', 493, 494),
(249, 2, NULL, NULL, 'Surveys', 496, 529),
(250, 249, NULL, NULL, 'setUpAjaxList', 497, 498),
(251, 249, NULL, NULL, 'index', 499, 500),
(252, 249, NULL, NULL, 'ajaxList', 501, 502),
(253, 249, NULL, NULL, 'view', 503, 504),
(254, 249, NULL, NULL, 'add', 505, 506),
(255, 249, NULL, NULL, 'edit', 507, 508),
(256, 249, NULL, NULL, 'copy', 509, 510),
(257, 249, NULL, NULL, 'delete', 511, 512),
(258, 249, NULL, NULL, 'checkDuplicateName', 513, 514),
(259, 249, NULL, NULL, 'releaseSurvey', 515, 516),
(260, 249, NULL, NULL, 'questionsSummary', 517, 518),
(261, 249, NULL, NULL, 'moveQuestion', 519, 520),
(262, 249, NULL, NULL, 'removeQuestion', 521, 522),
(263, 249, NULL, NULL, 'addQuestion', 523, 524),
(264, 249, NULL, NULL, 'editQuestion', 525, 526),
(265, 249, NULL, NULL, 'update', 527, 528),
(266, 2, NULL, NULL, 'Sysparameters', 530, 545),
(267, 266, NULL, NULL, 'setUpAjaxList', 531, 532),
(268, 266, NULL, NULL, 'index', 533, 534),
(269, 266, NULL, NULL, 'ajaxList', 535, 536),
(270, 266, NULL, NULL, 'view', 537, 538),
(271, 266, NULL, NULL, 'add', 539, 540),
(272, 266, NULL, NULL, 'edit', 541, 542),
(273, 266, NULL, NULL, 'delete', 543, 544),
(274, 2, NULL, NULL, 'Upgrade', 546, 561),
(275, 274, NULL, NULL, 'index', 547, 548),
(276, 274, NULL, NULL, 'step2', 549, 550),
(277, 274, NULL, NULL, 'checkPermission', 551, 552),
(278, 274, NULL, NULL, 'add', 553, 554),
(279, 274, NULL, NULL, 'edit', 555, 556),
(280, 274, NULL, NULL, 'view', 557, 558),
(281, 274, NULL, NULL, 'delete', 559, 560),
(282, 2, NULL, NULL, 'Users', 562, 591),
(283, 282, NULL, NULL, 'ajaxList', 563, 564),
(284, 282, NULL, NULL, 'index', 565, 566),
(285, 282, NULL, NULL, 'goToClassList', 567, 568),
(286, 282, NULL, NULL, 'determineIfStudentFromThisData', 569, 570),
(287, 282, NULL, NULL, 'getSimpleEntrollmentLists', 571, 572),
(288, 282, NULL, NULL, 'view', 573, 574),
(289, 282, NULL, NULL, 'add', 575, 576),
(290, 282, NULL, NULL, 'edit', 577, 578),
(291, 282, NULL, NULL, 'editProfile', 579, 580),
(292, 282, NULL, NULL, 'delete', 581, 582),
(293, 282, NULL, NULL, 'checkDuplicateName', 583, 584),
(294, 282, NULL, NULL, 'resetPassword', 585, 586),
(295, 282, NULL, NULL, 'import', 587, 588),
(296, 282, NULL, NULL, 'update', 589, 590),
(297, 2, NULL, NULL, 'V1', 592, 627),
(298, 297, NULL, NULL, 'oauth', 593, 594),
(299, 297, NULL, NULL, 'oauth_error', 595, 596),
(300, 297, NULL, NULL, 'users', 597, 598),
(301, 297, NULL, NULL, 'courses', 599, 600),
(302, 297, NULL, NULL, 'groups', 601, 602),
(303, 297, NULL, NULL, 'groupMembers', 603, 604),
(304, 297, NULL, NULL, 'events', 605, 606),
(305, 297, NULL, NULL, 'grades', 607, 608),
(306, 297, NULL, NULL, 'departments', 609, 610),
(307, 297, NULL, NULL, 'courseDepartments', 611, 612),
(308, 297, NULL, NULL, 'userEvents', 613, 614),
(309, 297, NULL, NULL, 'enrolment', 615, 616),
(310, 297, NULL, NULL, 'add', 617, 618),
(311, 297, NULL, NULL, 'edit', 619, 620),
(312, 297, NULL, NULL, 'index', 621, 622),
(313, 297, NULL, NULL, 'view', 623, 624),
(314, 297, NULL, NULL, 'delete', 625, 626),
(315, 2, NULL, NULL, 'Guard', 628, 645),
(316, 315, NULL, NULL, 'Guard', 629, 644),
(317, 316, NULL, NULL, 'login', 630, 631),
(318, 316, NULL, NULL, 'logout', 632, 633),
(319, 316, NULL, NULL, 'add', 634, 635),
(320, 316, NULL, NULL, 'edit', 636, 637),
(321, 316, NULL, NULL, 'index', 638, 639),
(322, 316, NULL, NULL, 'view', 640, 641),
(323, 316, NULL, NULL, 'delete', 642, 643),
(324, NULL, NULL, NULL, 'functions', 647, 710),
(325, 324, NULL, NULL, 'user', 648, 675),
(326, 325, NULL, NULL, 'superadmin', 649, 650),
(327, 325, NULL, NULL, 'admin', 651, 652),
(328, 325, NULL, NULL, 'instructor', 653, 654),
(329, 325, NULL, NULL, 'tutor', 655, 656),
(330, 325, NULL, NULL, 'student', 657, 658),
(331, 325, NULL, NULL, 'import', 659, 660),
(332, 325, NULL, NULL, 'password_reset', 661, 672),
(333, 332, NULL, NULL, 'superadmin', 662, 663),
(334, 332, NULL, NULL, 'admin', 664, 665),
(335, 332, NULL, NULL, 'instructor', 666, 667),
(336, 332, NULL, NULL, 'tutor', 668, 669),
(337, 332, NULL, NULL, 'student', 670, 671),
(338, 325, NULL, NULL, 'index', 673, 674),
(339, 324, NULL, NULL, 'role', 676, 687),
(340, 339, NULL, NULL, 'superadmin', 677, 678),
(341, 339, NULL, NULL, 'admin', 679, 680),
(342, 339, NULL, NULL, 'instructor', 681, 682),
(343, 339, NULL, NULL, 'tutor', 683, 684),
(344, 339, NULL, NULL, 'student', 685, 686),
(345, 324, NULL, NULL, 'evaluation', 688, 691),
(346, 345, NULL, NULL, 'export', 689, 690),
(347, 324, NULL, NULL, 'email', 692, 699),
(348, 347, NULL, NULL, 'allUsers', 693, 694),
(349, 347, NULL, NULL, 'allGroups', 695, 696),
(350, 347, NULL, NULL, 'allCourses', 697, 698),
(351, 324, NULL, NULL, 'emailtemplate', 700, 701),
(352, 324, NULL, NULL, 'viewstudentresults', 702, 703),
(353, 324, NULL, NULL, 'viewemailaddresses', 704, 705),
(354, 324, NULL, NULL, 'superadmin', 706, 707),
(355, 324, NULL, NULL, 'onlytakeeval', 708, 709);





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

INSERT INTO aros_acos (id, aro_id, aco_id, _create, _read, _update, _delete) VALUES
(1, 1, 2, '1', '1', '1', '1'),
(2, 1, 324, '1', '1', '1', '1'),
(3, 1, 51, '1', '1', '1', '1'),
(4, 1, 223, '1', '1', '1', '1'),
(5, 1, 198, '1', '1', '1', '1'),
(6, 1, 164, '1', '1', '1', '1'),
(7, 1, 249, '1', '1', '1', '1'),
(8, 1, 25, '1', '1', '1', '1'),
(9, 1, 41, '1', '1', '1', '1'),
(10, 1, 92, '1', '1', '1', '1'),
(11, 1, 108, '1', '1', '1', '1'),
(12, 1, 19, '1', '1', '1', '1'),
(13, 1, 123, '1', '1', '1', '1'),
(14, 1, 1, '1', '1', '1', '1'),
(15, 1, 353, '1', '1', '1', '1'),
(16, 1, 354, '1', '1', '1', '1'),
(17, 2, 2, '-1', '-1', '-1', '-1'),
(18, 2, 140, '1', '1', '1', '1'),
(19, 2, 10, '1', '1', '1', '1'),
(20, 2, 19, '1', '1', '1', '1'),
(21, 2, 25, '1', '1', '1', '1'),
(22, 2, 41, '1', '1', '1', '1'),
(23, 2, 51, '1', '1', '1', '1'),
(24, 2, 92, '1', '1', '1', '1'),
(25, 2, 123, '1', '1', '1', '1'),
(26, 2, 164, '1', '1', '1', '1'),
(27, 2, 198, '1', '1', '1', '1'),
(28, 2, 223, '1', '1', '1', '1'),
(29, 2, 249, '1', '1', '1', '1'),
(30, 2, 282, '1', '1', '1', '1'),
(31, 2, 58, '1', '1', '1', '1'),
(32, 2, 318, '1', '1', '1', '1'),
(33, 2, 324, '-1', '-1', '-1', '-1'),
(34, 2, 351, '1', '1', '1', '1'),
(35, 2, 345, '1', '1', '1', '1'),
(36, 2, 348, '1', '1', '1', '1'),
(37, 2, 325, '1', '1', '1', '1'),
(38, 2, 327, '-1', '1', '-1', '-1'),
(39, 2, 326, '-1', '-1', '-1', '-1'),
(40, 2, 1, '1', '1', '1', '1'),
(41, 2, 353, '1', '1', '1', '1'),
(42, 2, 354, '-1', '-1', '-1', '-1'),
(43, 3, 2, '-1', '-1', '-1', '-1'),
(44, 3, 140, '1', '1', '1', '1'),
(45, 3, 10, '1', '1', '1', '1'),
(46, 3, 16, '-1', '-1', '-1', '-1'),
(47, 3, 17, '-1', '-1', '-1', '-1'),
(48, 3, 25, '1', '1', '1', '1'),
(49, 3, 41, '1', '1', '1', '1'),
(50, 3, 51, '1', '1', '1', '1'),
(51, 3, 92, '1', '1', '1', '1'),
(52, 3, 123, '1', '1', '1', '1'),
(53, 3, 164, '1', '1', '1', '1'),
(54, 3, 198, '1', '1', '1', '1'),
(55, 3, 223, '1', '1', '1', '1'),
(56, 3, 249, '1', '1', '1', '1'),
(57, 3, 282, '1', '1', '1', '1'),
(58, 3, 318, '1', '1', '1', '1'),
(59, 3, 324, '-1', '-1', '-1', '-1'),
(60, 3, 345, '1', '1', '-1', '-1'),
(61, 3, 325, '1', '1', '1', '1'),
(62, 3, 327, '-1', '-1', '-1', '-1'),
(63, 3, 326, '-1', '-1', '-1', '-1'),
(64, 3, 328, '-1', '1', '-1', '-1'),
(65, 3, 338, '-1', '-1', '-1', '-1'),
(66, 3, 353, '-1', '-1', '-1', '-1'),
(67, 3, 354, '-1', '-1', '-1', '-1'),
(68, 3, 355, '-1', '-1', '-1', '-1'),
(69, 4, 2, '-1', '-1', '-1', '-1'),
(70, 4, 140, '1', '1', '1', '1'),
(71, 4, 10, '-1', '-1', '-1', '-1'),
(72, 4, 25, '-1', '-1', '-1', '-1'),
(73, 4, 41, '-1', '-1', '-1', '-1'),
(74, 4, 51, '-1', '-1', '-1', '-1'),
(75, 4, 92, '-1', '-1', '-1', '-1'),
(76, 4, 123, '-1', '-1', '-1', '-1'),
(77, 4, 164, '-1', '-1', '-1', '-1'),
(78, 4, 198, '-1', '-1', '-1', '-1'),
(79, 4, 223, '-1', '-1', '-1', '-1'),
(80, 4, 249, '-1', '-1', '-1', '-1'),
(81, 4, 282, '-1', '-1', '-1', '-1'),
(82, 4, 318, '1', '1', '1', '1'),
(83, 4, 66, '1', '1', '1', '1'),
(84, 4, 70, '1', '1', '1', '1'),
(85, 4, 73, '1', '1', '1', '1'),
(86, 4, 68, '1', '1', '1', '1'),
(87, 4, 78, '1', '1', '1', '1'),
(88, 4, 324, '-1', '-1', '-1', '-1'),
(89, 4, 353, '-1', '-1', '-1', '-1'),
(90, 4, 354, '-1', '-1', '-1', '-1'),
(91, 4, 355, '1', '1', '1', '1'),
(92, 5, 2, '-1', '-1', '-1', '-1'),
(93, 5, 140, '1', '1', '1', '1'),
(94, 5, 10, '-1', '-1', '-1', '-1'),
(95, 5, 25, '-1', '-1', '-1', '-1'),
(96, 5, 41, '-1', '-1', '-1', '-1'),
(97, 5, 51, '-1', '-1', '-1', '-1'),
(98, 5, 92, '-1', '-1', '-1', '-1'),
(99, 5, 123, '-1', '-1', '-1', '-1'),
(100, 5, 164, '-1', '-1', '-1', '-1'),
(101, 5, 198, '-1', '-1', '-1', '-1'),
(102, 5, 223, '-1', '-1', '-1', '-1'),
(103, 5, 249, '-1', '-1', '-1', '-1'),
(104, 5, 282, '-1', '-1', '-1', '-1'),
(105, 5, 318, '1', '1', '1', '1'),
(106, 5, 66, '1', '1', '1', '1'),
(107, 5, 70, '1', '1', '1', '1'),
(108, 5, 73, '1', '1', '1', '1'),
(109, 5, 68, '1', '1', '1', '1'),
(110, 5, 78, '1', '1', '1', '1'),
(111, 5, 324, '-1', '-1', '-1', '-1'),
(112, 5, 352, '1', '1', '1', '1'),
(113, 5, 353, '-1', '-1', '-1', '-1'),
(114, 5, 354, '-1', '-1', '-1', '-1'),
(115, 5, 355, '1', '1', '1', '1');




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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

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
  `survey_id` int(11) NOT NULL DEFAULT '0',
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
