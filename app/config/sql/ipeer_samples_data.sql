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

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, NULL, NULL, 'controllers', 1, 656),
(2, 1, NULL, NULL, 'Pages', 2, 17),
(3, 2, NULL, NULL, 'display', 3, 4),
(4, 2, NULL, NULL, 'extractModel', 5, 6),
(5, 2, NULL, NULL, 'add', 7, 8),
(6, 2, NULL, NULL, 'edit', 9, 10),
(7, 2, NULL, NULL, 'index', 11, 12),
(8, 2, NULL, NULL, 'view', 13, 14),
(9, 2, NULL, NULL, 'delete', 15, 16),
(10, 1, NULL, NULL, 'Evaltools', 18, 33),
(11, 10, NULL, NULL, 'index', 19, 20),
(12, 10, NULL, NULL, 'showAll', 21, 22),
(13, 10, NULL, NULL, 'extractModel', 23, 24),
(14, 10, NULL, NULL, 'add', 25, 26),
(15, 10, NULL, NULL, 'edit', 27, 28),
(16, 10, NULL, NULL, 'view', 29, 30),
(17, 10, NULL, NULL, 'delete', 31, 32),
(18, 1, NULL, NULL, 'Sysfunctions', 34, 53),
(19, 18, NULL, NULL, 'setUpAjaxList', 35, 36),
(20, 18, NULL, NULL, 'index', 37, 38),
(21, 18, NULL, NULL, 'ajaxList', 39, 40),
(22, 18, NULL, NULL, 'view', 41, 42),
(23, 18, NULL, NULL, 'edit', 43, 44),
(24, 18, NULL, NULL, 'delete', 45, 46),
(25, 18, NULL, NULL, 'update', 47, 48),
(26, 18, NULL, NULL, 'extractModel', 49, 50),
(27, 18, NULL, NULL, 'add', 51, 52),
(28, 1, NULL, NULL, 'Emailer', 54, 87),
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
(39, 28, NULL, NULL, 'doMerge', 75, 76),
(40, 28, NULL, NULL, 'send', 77, 78),
(41, 28, NULL, NULL, 'extractModel', 79, 80),
(42, 28, NULL, NULL, 'add', 81, 82),
(43, 28, NULL, NULL, 'edit', 83, 84),
(44, 28, NULL, NULL, 'delete', 85, 86),
(45, 1, NULL, NULL, 'Users', 88, 133),
(46, 45, NULL, NULL, 'login', 89, 90),
(47, 45, NULL, NULL, 'logout', 91, 92),
(48, 45, NULL, NULL, 'ajaxList', 93, 94),
(49, 45, NULL, NULL, 'index', 95, 96),
(50, 45, NULL, NULL, 'goToClassList', 97, 98),
(51, 45, NULL, NULL, 'determineIfStudentFromThisData', 99, 100),
(52, 45, NULL, NULL, 'getSimpleEntrollmentLists', 101, 102),
(53, 45, NULL, NULL, 'processEnrollmentListsPostBack', 103, 104),
(54, 45, NULL, NULL, 'view', 105, 106),
(55, 45, NULL, NULL, 'add', 107, 108),
(56, 45, NULL, NULL, 'edit', 109, 110),
(57, 45, NULL, NULL, 'editProfile', 111, 112),
(58, 45, NULL, NULL, 'delete', 113, 114),
(59, 45, NULL, NULL, 'drop', 115, 116),
(60, 45, NULL, NULL, 'checkDuplicateName', 117, 118),
(61, 45, NULL, NULL, 'resetPassword', 119, 120),
(62, 45, NULL, NULL, 'import', 121, 122),
(63, 45, NULL, NULL, 'importPreview', 123, 124),
(64, 45, NULL, NULL, 'importFile', 125, 126),
(65, 45, NULL, NULL, 'update', 127, 128),
(66, 45, NULL, NULL, 'nonRegisteredCourses', 129, 130),
(67, 45, NULL, NULL, 'extractModel', 131, 132),
(68, 1, NULL, NULL, 'Mixevals', 134, 165),
(69, 68, NULL, NULL, 'postProcess', 135, 136),
(70, 68, NULL, NULL, 'setUpAjaxList', 137, 138),
(71, 68, NULL, NULL, 'index', 139, 140),
(72, 68, NULL, NULL, 'ajaxList', 141, 142),
(73, 68, NULL, NULL, 'view', 143, 144),
(74, 68, NULL, NULL, 'add', 145, 146),
(75, 68, NULL, NULL, 'deleteQuestion', 147, 148),
(76, 68, NULL, NULL, 'deleteDescriptor', 149, 150),
(77, 68, NULL, NULL, 'edit', 151, 152),
(78, 68, NULL, NULL, 'copy', 153, 154),
(79, 68, NULL, NULL, 'delete', 155, 156),
(80, 68, NULL, NULL, 'previewMixeval', 157, 158),
(81, 68, NULL, NULL, 'renderRows', 159, 160),
(82, 68, NULL, NULL, 'update', 161, 162),
(83, 68, NULL, NULL, 'extractModel', 163, 164),
(84, 1, NULL, NULL, 'Simpleevaluations', 166, 187),
(85, 84, NULL, NULL, 'postProcess', 167, 168),
(86, 84, NULL, NULL, 'setUpAjaxList', 169, 170),
(87, 84, NULL, NULL, 'index', 171, 172),
(88, 84, NULL, NULL, 'ajaxList', 173, 174),
(89, 84, NULL, NULL, 'view', 175, 176),
(90, 84, NULL, NULL, 'add', 177, 178),
(91, 84, NULL, NULL, 'edit', 179, 180),
(92, 84, NULL, NULL, 'copy', 181, 182),
(93, 84, NULL, NULL, 'delete', 183, 184),
(94, 84, NULL, NULL, 'extractModel', 185, 186),
(95, 1, NULL, NULL, 'Penalty', 188, 203),
(96, 95, NULL, NULL, 'save', 189, 190),
(97, 95, NULL, NULL, 'extractModel', 191, 192),
(98, 95, NULL, NULL, 'add', 193, 194),
(99, 95, NULL, NULL, 'edit', 195, 196),
(100, 95, NULL, NULL, 'index', 197, 198),
(101, 95, NULL, NULL, 'view', 199, 200),
(102, 95, NULL, NULL, 'delete', 201, 202),
(103, 1, NULL, NULL, 'Home', 204, 217),
(104, 103, NULL, NULL, 'index', 205, 206),
(105, 103, NULL, NULL, 'extractModel', 207, 208),
(106, 103, NULL, NULL, 'add', 209, 210),
(107, 103, NULL, NULL, 'edit', 211, 212),
(108, 103, NULL, NULL, 'view', 213, 214),
(109, 103, NULL, NULL, 'delete', 215, 216),
(110, 1, NULL, NULL, 'Surveys', 218, 253),
(111, 110, NULL, NULL, 'setUpAjaxList', 219, 220),
(112, 110, NULL, NULL, 'index', 221, 222),
(113, 110, NULL, NULL, 'ajaxList', 223, 224),
(114, 110, NULL, NULL, 'view', 225, 226),
(115, 110, NULL, NULL, 'add', 227, 228),
(116, 110, NULL, NULL, 'edit', 229, 230),
(117, 110, NULL, NULL, 'copy', 231, 232),
(118, 110, NULL, NULL, 'delete', 233, 234),
(119, 110, NULL, NULL, 'checkDuplicateName', 235, 236),
(120, 110, NULL, NULL, 'releaseSurvey', 237, 238),
(121, 110, NULL, NULL, 'questionsSummary', 239, 240),
(122, 110, NULL, NULL, 'moveQuestion', 241, 242),
(123, 110, NULL, NULL, 'removeQuestion', 243, 244),
(124, 110, NULL, NULL, 'addQuestion', 245, 246),
(125, 110, NULL, NULL, 'editQuestion', 247, 248),
(126, 110, NULL, NULL, 'update', 249, 250),
(127, 110, NULL, NULL, 'extractModel', 251, 252),
(128, 1, NULL, NULL, 'Evaluations', 254, 329),
(129, 128, NULL, NULL, 'postProcess', 255, 256),
(130, 128, NULL, NULL, 'setUpAjaxList', 257, 258),
(131, 128, NULL, NULL, 'ajaxList', 259, 260),
(132, 128, NULL, NULL, 'view', 261, 262),
(133, 128, NULL, NULL, 'index', 263, 264),
(134, 128, NULL, NULL, 'search', 265, 266),
(135, 128, NULL, NULL, 'update', 267, 268),
(136, 128, NULL, NULL, 'test', 269, 270),
(137, 128, NULL, NULL, 'export', 271, 272),
(138, 128, NULL, NULL, 'makeSimpleEvaluation', 273, 274),
(139, 128, NULL, NULL, 'validSimpleEvalComplete', 275, 276),
(140, 128, NULL, NULL, 'makeSurveyEvaluation', 277, 278),
(141, 128, NULL, NULL, 'validSurveyEvalComplete', 279, 280),
(142, 128, NULL, NULL, 'makeRubricEvaluation', 281, 282),
(143, 128, NULL, NULL, 'validRubricEvalComplete', 283, 284),
(144, 128, NULL, NULL, 'completeEvaluationRubric', 285, 286),
(145, 128, NULL, NULL, 'makeMixevalEvaluation', 287, 288),
(146, 128, NULL, NULL, 'validMixevalEvalComplete', 289, 290),
(147, 128, NULL, NULL, 'completeEvaluationMixeval', 291, 292),
(148, 128, NULL, NULL, 'viewEvaluationResults', 293, 294),
(149, 128, NULL, NULL, 'viewSurveyGroupEvaluationResults', 295, 296),
(150, 128, NULL, NULL, 'studentViewEvaluationResult', 297, 298),
(151, 128, NULL, NULL, 'markEventReviewed', 299, 300),
(152, 128, NULL, NULL, 'markGradeRelease', 301, 302),
(153, 128, NULL, NULL, 'markCommentRelease', 303, 304),
(154, 128, NULL, NULL, 'changeAllCommentRelease', 305, 306),
(155, 128, NULL, NULL, 'changeAllGradeRelease', 307, 308),
(156, 128, NULL, NULL, 'viewGroupSubmissionDetails', 309, 310),
(157, 128, NULL, NULL, 'reReleaseEvaluation', 311, 312),
(158, 128, NULL, NULL, 'viewSurveySummary', 313, 314),
(159, 128, NULL, NULL, 'export_rubic', 315, 316),
(160, 128, NULL, NULL, 'export_test', 317, 318),
(161, 128, NULL, NULL, 'pre', 319, 320),
(162, 128, NULL, NULL, 'extractModel', 321, 322),
(163, 128, NULL, NULL, 'add', 323, 324),
(164, 128, NULL, NULL, 'edit', 325, 326),
(165, 128, NULL, NULL, 'delete', 327, 328),
(166, 1, NULL, NULL, 'Rubrics', 330, 353),
(167, 166, NULL, NULL, 'postProcess', 331, 332),
(168, 166, NULL, NULL, 'setUpAjaxList', 333, 334),
(169, 166, NULL, NULL, 'index', 335, 336),
(170, 166, NULL, NULL, 'ajaxList', 337, 338),
(171, 166, NULL, NULL, 'view', 339, 340),
(172, 166, NULL, NULL, 'add', 341, 342),
(173, 166, NULL, NULL, 'edit', 343, 344),
(174, 166, NULL, NULL, 'copy', 345, 346),
(175, 166, NULL, NULL, 'delete', 347, 348),
(176, 166, NULL, NULL, 'setForm_RubricName', 349, 350),
(177, 166, NULL, NULL, 'extractModel', 351, 352),
(178, 1, NULL, NULL, 'Emailtemplates', 354, 375),
(179, 178, NULL, NULL, 'setUpAjaxList', 355, 356),
(180, 178, NULL, NULL, 'ajaxList', 357, 358),
(181, 178, NULL, NULL, 'index', 359, 360),
(182, 178, NULL, NULL, 'add', 361, 362),
(183, 178, NULL, NULL, 'edit', 363, 364),
(184, 178, NULL, NULL, 'delete', 365, 366),
(185, 178, NULL, NULL, 'view', 367, 368),
(186, 178, NULL, NULL, 'displayTemplateContent', 369, 370),
(187, 178, NULL, NULL, 'displayTemplateSubject', 371, 372),
(188, 178, NULL, NULL, 'extractModel', 373, 374),
(189, 1, NULL, NULL, 'Sysparameters', 376, 393),
(190, 189, NULL, NULL, 'setUpAjaxList', 377, 378),
(191, 189, NULL, NULL, 'index', 379, 380),
(192, 189, NULL, NULL, 'ajaxList', 381, 382),
(193, 189, NULL, NULL, 'view', 383, 384),
(194, 189, NULL, NULL, 'add', 385, 386),
(195, 189, NULL, NULL, 'edit', 387, 388),
(196, 189, NULL, NULL, 'delete', 389, 390),
(197, 189, NULL, NULL, 'extractModel', 391, 392),
(198, 1, NULL, NULL, 'Install', 394, 415),
(199, 198, NULL, NULL, 'index', 395, 396),
(200, 198, NULL, NULL, 'install2', 397, 398),
(201, 198, NULL, NULL, 'install3', 399, 400),
(202, 198, NULL, NULL, 'install4', 401, 402),
(203, 198, NULL, NULL, 'install5', 403, 404),
(204, 198, NULL, NULL, 'gpl', 405, 406),
(205, 198, NULL, NULL, 'add', 407, 408),
(206, 198, NULL, NULL, 'edit', 409, 410),
(207, 198, NULL, NULL, 'view', 411, 412),
(208, 198, NULL, NULL, 'delete', 413, 414),
(209, 1, NULL, NULL, 'Searchs', 416, 445),
(210, 209, NULL, NULL, 'update', 417, 418),
(211, 209, NULL, NULL, 'index', 419, 420),
(212, 209, NULL, NULL, 'searchEvaluation', 421, 422),
(213, 209, NULL, NULL, 'searchResult', 423, 424),
(214, 209, NULL, NULL, 'searchInstructor', 425, 426),
(215, 209, NULL, NULL, 'eventBoxSearch', 427, 428),
(216, 209, NULL, NULL, 'formatSearchEvaluation', 429, 430),
(217, 209, NULL, NULL, 'formatSearchInstructor', 431, 432),
(218, 209, NULL, NULL, 'formatSearchEvaluationResult', 433, 434),
(219, 209, NULL, NULL, 'extractModel', 435, 436),
(220, 209, NULL, NULL, 'add', 437, 438),
(221, 209, NULL, NULL, 'edit', 439, 440),
(222, 209, NULL, NULL, 'view', 441, 442),
(223, 209, NULL, NULL, 'delete', 443, 444),
(224, 1, NULL, NULL, 'Groups', 446, 481),
(225, 224, NULL, NULL, 'postProcess', 447, 448),
(226, 224, NULL, NULL, 'setUpAjaxList', 449, 450),
(227, 224, NULL, NULL, 'index', 451, 452),
(228, 224, NULL, NULL, 'ajaxList', 453, 454),
(229, 224, NULL, NULL, 'goToClassList', 455, 456),
(230, 224, NULL, NULL, 'view', 457, 458),
(231, 224, NULL, NULL, 'add', 459, 460),
(232, 224, NULL, NULL, 'edit', 461, 462),
(233, 224, NULL, NULL, 'delete', 463, 464),
(234, 224, NULL, NULL, 'checkDuplicateName', 465, 466),
(235, 224, NULL, NULL, 'getQueryAttribute', 467, 468),
(236, 224, NULL, NULL, 'import', 469, 470),
(237, 224, NULL, NULL, 'addGroupByImport', 471, 472),
(238, 224, NULL, NULL, 'update', 473, 474),
(239, 224, NULL, NULL, 'export', 475, 476),
(240, 224, NULL, NULL, 'sendGroupEmail', 477, 478),
(241, 224, NULL, NULL, 'extractModel', 479, 480),
(242, 1, NULL, NULL, 'Courses', 482, 509),
(243, 242, NULL, NULL, 'daysLate', 483, 484),
(244, 242, NULL, NULL, 'index', 485, 486),
(245, 242, NULL, NULL, 'ajaxList', 487, 488),
(246, 242, NULL, NULL, 'view', 489, 490),
(247, 242, NULL, NULL, 'home', 491, 492),
(248, 242, NULL, NULL, 'add', 493, 494),
(249, 242, NULL, NULL, 'edit', 495, 496),
(250, 242, NULL, NULL, 'delete', 497, 498),
(251, 242, NULL, NULL, 'addInstructor', 499, 500),
(252, 242, NULL, NULL, 'deleteInstructor', 501, 502),
(253, 242, NULL, NULL, 'checkDuplicateName', 503, 504),
(254, 242, NULL, NULL, 'update', 505, 506),
(255, 242, NULL, NULL, 'extractModel', 507, 508),
(256, 1, NULL, NULL, 'Upgrade', 510, 527),
(257, 256, NULL, NULL, 'index', 511, 512),
(258, 256, NULL, NULL, 'step2', 513, 514),
(259, 256, NULL, NULL, 'checkPermission', 515, 516),
(260, 256, NULL, NULL, 'extractModel', 517, 518),
(261, 256, NULL, NULL, 'add', 519, 520),
(262, 256, NULL, NULL, 'edit', 521, 522),
(263, 256, NULL, NULL, 'view', 523, 524),
(264, 256, NULL, NULL, 'delete', 525, 526),
(265, 1, NULL, NULL, 'Surveygroups', 528, 563),
(266, 265, NULL, NULL, 'postProcess', 529, 530),
(267, 265, NULL, NULL, 'setUpAjaxList', 531, 532),
(268, 265, NULL, NULL, 'index', 533, 534),
(269, 265, NULL, NULL, 'ajaxList', 535, 536),
(270, 265, NULL, NULL, 'viewresult', 537, 538),
(271, 265, NULL, NULL, 'viewresultsearch', 539, 540),
(272, 265, NULL, NULL, 'makegroups', 541, 542),
(273, 265, NULL, NULL, 'makegroupssearch', 543, 544),
(274, 265, NULL, NULL, 'maketmgroups', 545, 546),
(275, 265, NULL, NULL, 'savegroups', 547, 548),
(276, 265, NULL, NULL, 'release', 549, 550),
(277, 265, NULL, NULL, 'delete', 551, 552),
(278, 265, NULL, NULL, 'edit', 553, 554),
(279, 265, NULL, NULL, 'changegroupset', 555, 556),
(280, 265, NULL, NULL, 'extractModel', 557, 558),
(281, 265, NULL, NULL, 'add', 559, 560),
(282, 265, NULL, NULL, 'view', 561, 562),
(283, 1, NULL, NULL, 'Events', 564, 601),
(284, 283, NULL, NULL, 'postProcessData', 565, 566),
(285, 283, NULL, NULL, 'setUpAjaxList', 567, 568),
(286, 283, NULL, NULL, 'index', 569, 570),
(287, 283, NULL, NULL, 'ajaxList', 571, 572),
(288, 283, NULL, NULL, 'goToClassList', 573, 574),
(289, 283, NULL, NULL, 'view', 575, 576),
(290, 283, NULL, NULL, 'eventTemplatesList', 577, 578),
(291, 283, NULL, NULL, 'add', 579, 580),
(292, 283, NULL, NULL, 'edit', 581, 582),
(293, 283, NULL, NULL, 'editOld', 583, 584),
(294, 283, NULL, NULL, 'delete', 585, 586),
(295, 283, NULL, NULL, 'search', 587, 588),
(296, 283, NULL, NULL, 'checkDuplicateTitle', 589, 590),
(297, 283, NULL, NULL, 'viewGroups', 591, 592),
(298, 283, NULL, NULL, 'editGroup', 593, 594),
(299, 283, NULL, NULL, 'getAssignedGroups', 595, 596),
(300, 283, NULL, NULL, 'update', 597, 598),
(301, 283, NULL, NULL, 'extractModel', 599, 600),
(302, 1, NULL, NULL, 'Framework', 602, 621),
(303, 302, NULL, NULL, 'calendarDisplay', 603, 604),
(304, 302, NULL, NULL, 'userInfoDisplay', 605, 606),
(305, 302, NULL, NULL, 'tutIndex', 607, 608),
(306, 302, NULL, NULL, 'extractModel', 609, 610),
(307, 302, NULL, NULL, 'add', 611, 612),
(308, 302, NULL, NULL, 'edit', 613, 614),
(309, 302, NULL, NULL, 'index', 615, 616),
(310, 302, NULL, NULL, 'view', 617, 618),
(311, 302, NULL, NULL, 'delete', 619, 620),
(312, 1, NULL, NULL, 'Lti', 622, 635),
(313, 312, NULL, NULL, 'index', 623, 624),
(314, 312, NULL, NULL, 'extractModel', 625, 626),
(315, 312, NULL, NULL, 'add', 627, 628),
(316, 312, NULL, NULL, 'edit', 629, 630),
(317, 312, NULL, NULL, 'view', 631, 632),
(318, 312, NULL, NULL, 'delete', 633, 634),
(319, 1, NULL, NULL, 'Guard', 636, 655),
(320, 319, NULL, NULL, 'Guard', 637, 654),
(321, 320, NULL, NULL, 'login', 638, 639),
(322, 320, NULL, NULL, 'logout', 640, 641),
(323, 320, NULL, NULL, 'extractModel', 642, 643),
(324, 320, NULL, NULL, 'add', 644, 645),
(325, 320, NULL, NULL, 'edit', 646, 647),
(326, 320, NULL, NULL, 'index', 648, 649),
(327, 320, NULL, NULL, 'view', 650, 651),
(328, 320, NULL, NULL, 'delete', 652, 653),
(329, NULL, NULL, NULL, 'functions', 657, 696),
(330, 329, NULL, NULL, 'user', 658, 683),
(331, 330, NULL, NULL, 'superadmin', 659, 660),
(332, 330, NULL, NULL, 'admin', 661, 662),
(333, 330, NULL, NULL, 'instructor', 663, 664),
(334, 330, NULL, NULL, 'tutor', 665, 666),
(335, 330, NULL, NULL, 'student', 667, 668),
(336, 330, NULL, NULL, 'import', 669, 670),
(337, 330, NULL, NULL, 'password_reset', 671, 682),
(338, 337, NULL, NULL, 'superadmin', 672, 673),
(339, 337, NULL, NULL, 'admin', 674, 675),
(340, 337, NULL, NULL, 'instructor', 676, 677),
(341, 337, NULL, NULL, 'tutor', 678, 679),
(342, 337, NULL, NULL, 'student', 680, 681),
(343, 329, NULL, NULL, 'role', 684, 695),
(344, 343, NULL, NULL, 'superadmin', 685, 686),
(345, 343, NULL, NULL, 'admin', 687, 688),
(346, 343, NULL, NULL, 'instructor', 689, 690),
(347, 343, NULL, NULL, 'tutor', 691, 692),
(348, 343, NULL, NULL, 'student', 693, 694);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 1, '1', '1', '1', '1'),
(2, 1, 329, '1', '1', '1', '1'),
(3, 2, 1, '-1', '-1', '-1', '-1'),
(4, 2, 103, '1', '1', '1', '1'),
(5, 2, 242, '1', '1', '1', '1'),
(6, 2, 45, '1', '1', '1', '1'),
(7, 2, 329, '-1', '-1', '-1', '-1'),
(8, 2, 330, '1', '1', '1', '1'),
(9, 2, 332, '-1', '-1', '-1', '-1'),
(10, 2, 331, '-1', '-1', '-1', '-1'),
(11, 3, 1, '-1', '-1', '-1', '-1'),
(12, 3, 103, '1', '1', '1', '1'),
(13, 3, 242, '1', '1', '1', '1'),
(14, 3, 45, '1', '1', '1', '1'),
(15, 3, 329, '-1', '-1', '-1', '-1'),
(16, 3, 330, '1', '1', '1', '1'),
(17, 3, 332, '-1', '-1', '-1', '-1'),
(18, 3, 331, '-1', '-1', '-1', '-1'),
(19, 3, 333, '-1', '-1', '-1', '-1'),
(20, 4, 1, '-1', '-1', '-1', '-1'),
(21, 4, 103, '1', '1', '1', '1'),
(22, 4, 242, '1', '1', '1', '1'),
(23, 4, 45, '-1', '-1', '-1', '-1'),
(24, 4, 329, '-1', '-1', '-1', '-1');

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
  `self_enroll` enum('on','off') default 'off',
  `password` varchar(25) default NULL,
  `record_status` enum('A','I') NOT NULL default 'A',
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
INSERT INTO `courses` VALUES (2, 'APSC 201', 'Technical Communication', 'http://www.apsc.ubc.ca', 'off', NULL, 'A', 1, '2006-06-20 14:15:38', 1, '2006-06-20 14:39:31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` enum('A','I','S','T') DEFAULT 'S',
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
  `created` date NOT NULL DEFAULT '0000-00-00',
  `updater_id` int(11) DEFAULT NULL,
  `updated` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `description`, `subject`, `content`, `availability`, `creator_id`, `created`, `updater_id`, `updated`) VALUES
(1, 'Email template example', 'This is an email template example', 'Email Template', 'Hello, {{{USERNAME}}}',1, 0, '0000-00-00', NULL, NULL);


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

INSERT INTO `events` VALUES (1, 'Term 1 Evaluation', 1, '', 1, 1, '0', 0, '2006-07-02 16:34:43', '2006-06-16 16:34:49', '2006-07-22 16:34:53', '2006-07-04 16:34:43', '2006-07-30 16:34:43', 'A', 0, '2006-06-20 16:27:33', NULL, '2006-06-21 08:51:20');
INSERT INTO `events` VALUES (2, 'Term Report Evaluation', 1, '', 2, 1, '0', 0, '2006-06-08 08:59:29', '2006-06-06 08:59:35', '2006-07-02 08:59:41', '2006-06-09 08:59:29', '2006-07-08 08:59:29', 'A', 0, '2006-06-21 08:52:20', NULL, '2006-06-21 08:54:25');
INSERT INTO `events` VALUES (3, 'Project Evaluation', 1, '', 4, 1, '0', 0, '2006-07-02 09:00:28', '2006-06-07 09:00:35', '2006-07-09 09:00:39', '2006-07-04 09:00:28', '2006-07-12 09:00:28', 'A', 0, '2006-06-21 08:53:14', NULL, '2006-06-21 09:07:26');

-- --------------------------------------------------------

--
-- Table structure for table `group_events`
--

DROP TABLE IF EXISTS `group_events`;
CREATE TABLE IF NOT EXISTS `group_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `marked` enum('not reviewed','reviewed','to review') NOT NULL DEFAULT 'not reviewed',
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
INSERT INTO `group_events` VALUES (7, 0, 2, 'not reviewed', NULL, 'None', 'None', 'A', 0, '2006-06-21 08:54:25', NULL, '2006-06-21 08:54:25');
INSERT INTO `group_events` VALUES (8, 0, 3, 'not reviewed', NULL, 'None', 'None', 'A', 0, '2006-06-21 09:07:26', NULL, '2006-06-21 09:07:26');

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
  `record_status` enum('A','I') NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` VALUES (1, 0, 'Reapers', 1, 'A', 0, '2006-06-20 16:23:40', NULL, '2006-06-20 16:23:40');
INSERT INTO `groups` VALUES (2, 1, 'Lazy Engineers', 1, 'A', 0, '2006-06-21 08:47:04', NULL, '2006-06-21 08:49:53');

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
INSERT INTO `groups_members` VALUES (2, 1, 7);
INSERT INTO `groups_members` VALUES (3, 1, 6);
INSERT INTO `groups_members` VALUES (7, 2, 33);
INSERT INTO `groups_members` VALUES (8, 2, 31);
INSERT INTO `groups_members` VALUES (9, 2, 32);

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
  `availability` enum('public','private') NOT NULL DEFAULT 'public',
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
(46, 1, 1, 'Lowest'),
(47, 1, 2, NULL),
(48, 1, 3, 'Middle'),
(49, 1, 4, NULL),
(50, 1, 5, 'Highest'),
(51, 1, 1, 'Lowest'),
(52, 1, 2, NULL),
(53, 1, 3, 'Middle'),
(54, 1, 4, NULL),
(55, 1, 5, 'Highest'),
(56, 1, 1, 'Lowest'),
(57, 1, 2, NULL),
(58, 1, 3, 'Middle'),
(59, 1, 4, NULL),
(60, 1, 5, 'Highest');

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
(19, 1, 1, 'Participated in Team Meetings', NULL, 'S', 1, 1, 5, NULL),
(20, 1, 2, 'Was Helpful and co-operative', NULL, 'S', 1, 1, 5, NULL),
(21, 1, 3, 'Submitted work on time', NULL, 'S', 1, 1, 5, NULL),
(22, 1, 4, 'Produced efficient work?', NULL, 'T', 1, 0, 5, 'S'),
(23, 1, 5, 'Contributed?', NULL, 'T', 1, 0, 5, 'L'),
(24, 1, 6, 'Easy to work with?', NULL, 'T', 0, 0, 5, 'S');

-- --------------------------------------------------------

--
-- Table structure for table `penalties`
--

CREATE TABLE `penalties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `days_late` int(11) NOT NULL,
  `percent_penalty` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_events` (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Constraints for table `penalties`
--
ALTER TABLE `penalties`
  ADD CONSTRAINT `fk_events` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

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
  `type` enum('M','C','S','L') DEFAULT NULL,
  `master` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` VALUES (1, 'What was your GPA last term?', 'M', 'no');
INSERT INTO `questions` VALUES (2, 'Do you own a laptop?', 'M', 'no');

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
INSERT INTO `responses` VALUES (4, 1, '< 2');
INSERT INTO `responses` VALUES (5, 2, 'yes');
INSERT INTO `responses` VALUES (6, 2, 'no');

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
(4, 'student', '2010-10-27 16:17:29', '2010-10-27 16:17:29');

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

INSERT INTO `roles_users` (`id`, `role_id`, `user_id`, `created`, `modified`) VALUES
(1, 3, 2, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(2, 3, 3, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(3, 3, 4, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(4, 4, 5, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 4, 6, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(6, 4, 7, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(7, 4, 8, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(8, 4, 9, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(9, 4, 10, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(10, 4, 11, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(11, 4, 12, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(12, 4, 13, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(13, 4, 14, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(14, 4, 15, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(15, 4, 16, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(17, 4, 17, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(18, 4, 18, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(19, 4, 19, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(20, 4, 20, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(21, 4, 21, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(22, 4, 22, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(23, 4, 23, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(24, 4, 24, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(25, 4, 25, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(27, 4, 26, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(28, 4, 27, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(29, 4, 28, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(30, 4, 29, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(31, 4, 30, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(32, 4, 31, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(33, 4, 32, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(34, 4, 33, '2010-10-27 16:17:29', '2010-10-27 16:17:29');

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
  `availability` enum('public','private') NOT NULL DEFAULT 'public',
  `template` enum('horizontal','vertical') NOT NULL DEFAULT 'horizontal',
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

INSERT INTO `rubrics_criteria_comments` VALUES (1, 1, 1, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (2, 1, 2, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (3, 1, 3, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (4, 1, 4, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (5, 1, 5, NULL);
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
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `simple_evaluations`
--

INSERT INTO `simple_evaluations` VALUES (1, 'Module 1 Project Evaluation', '', 100, 'A', 1, '2006-06-20 15:17:47', NULL, '2006-06-20 15:17:47');

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
  `response_text` text,
  `response_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `survey_inputs`
--


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

INSERT INTO `survey_questions` VALUES (1, 1, 1, 1);
INSERT INTO `survey_questions` VALUES (2, 1, 2, 2);

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

INSERT INTO `surveys` VALUES (1, 1, 1, 'Team Creation Survey', '2006-07-01 15:31:08', '2006-06-01 15:31:17', '2006-07-02 15:31:21', 0, 0, '2006-06-20 15:23:59', NULL, '2006-06-21 09:43:24');

-- --------------------------------------------------------

--
-- Table structure for table `sys_functions`
--

DROP TABLE IF EXISTS `sys_functions`;
CREATE TABLE IF NOT EXISTS `sys_functions` (
  `id` int(11) NOT NULL DEFAULT '0',
  `function_code` varchar(80) NOT NULL DEFAULT '',
  `function_name` varchar(200) NOT NULL DEFAULT '',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `controller_name` varchar(80) NOT NULL DEFAULT '',
  `url_link` varchar(80) NOT NULL DEFAULT '',
  `permission_type` varchar(10) NOT NULL DEFAULT 'A',
  `record_status` char(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_functions`
--

INSERT INTO `sys_functions` (`id`, `function_code`, `function_name`, `parent_id`, `controller_name`, `url_link`, `permission_type`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
(100, 'SYS_FUNC', 'System Functions', 0, 'sysfunctions', 'sysfunctions/index/', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(101, 'SYS_PARA', 'System Parameters', 0, 'sysparameters', 'sysparameters/index/', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(102, 'SYS_BAKER', 'System Codes Generator', 0, 'bakers', 'bakers/index/', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(200, 'HOME', 'Home', 0, 'home', 'home/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(201, 'HOME', 'Home', 0, 'home', 'home/index/', 'S', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(300, 'USERS', 'Users', 0, 'users', 'users/index/', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(301, 'USERS', 'Students', 0, 'users', 'users/index/', 'IS', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(302, 'USR_PROFILE', 'Profile', 0, 'users', 'users/editProfile/', 'S', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(303, 'USR_RECORD', 'User Record', 1000, 'users', 'users/add/S', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(304, 'USR_INST_MGT', 'Instruction Record Management', 1000, 'users', 'users/add/I', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(305, 'USR_ADMIN_MGT', 'Admin Record Management', 1000, 'users', 'users/add/A', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(306, 'USR_PROFILE', 'Profile Edit', 1000, 'users', 'users/add/A', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(400, 'COURSE', 'Courses', 0, 'courses', 'courses/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(401, 'COURSE_RECORD', 'Course Record', 1000, 'courses', 'courses/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(500, 'GROUP', 'Groups', 0, 'groups', 'groups/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, NULL),
(501, 'GROUP_RECORD', 'Group Record', 1000, 'groups', 'groups/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, NULL),
(600, 'RUBRIC', 'Rubrics', 0, 'rubrics', 'rubrics/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-03-27 11:05:57'),
(601, 'RUBRIC_RECORD', 'Rubric Record', 1000, 'rubrics', 'rubrics/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-03-27 11:07:44'),
(700, 'SIMPLE_EVAL', 'Simple Evaluations', 0, 'simpleevaluations', 'simpleevaluations/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(800, 'EVENT', 'Events Management', 0, 'events', 'events/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(900, 'PERSONALIZES', 'Personalizes', 0, 'personalizes', 'personalizes/index/', 'AIS', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00'),
(1000, 'FRAMEWORK', 'Framework Controller', 0, 'framework', 'framework/index/', 'ASI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-07 08:40:29'),
(1100, 'SURVEY', 'Team Maker', 0, 'surveys', 'surveys/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-06 09:18:04'),
(1101, 'SURVEY_RECORD', 'Survey Record', 1000, 'surveys', 'surveys/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-06 09:16:19'),
(1200, 'EVALUATION', 'Evaluation', 0, 'evaluations', 'evaluations/index/', 'ASI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-07 08:40:29'),
(1300, 'EVAL_TOOL', 'Evaluation Tools', 0, 'evaltools', 'evaltools/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-07 08:40:29'),
(1400, 'ADV_SEARCH', 'Advanced Search', 0, 'searchs', 'searchs/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-07 08:40:29'),
(1500, 'MIX_EVAL', 'Mixed Evaluations', 0, 'mixevals', 'mixevals/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-03-27 11:05:57'),
(1501, 'MIX_EVAL_RECORD', 'Mixed Evaluations Record', 1000, 'mixevals', 'mixevals/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-03-27 11:07:44'),
(1600, 'SURVEY_GROUP', 'Survey Group', 0, 'surveygroups', 'surveygroups/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-06 09:18:04'),
(1601, 'SURVEY_GROUP_RECORD', 'Survey Group Record', 1000, 'surveygroups', 'surveygroups/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-06 09:16:19'),
(1700, 'EMAIL_TEMPLATE', 'Email Templates', 0, 'emailtemplates', 'emailtemplates/index', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, NULL),
(1800, 'EMAIL', 'Emails ', 0, 'emailer', 'emailer/index', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, NULL);

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
(4, 'system.absolute_url', 'http://', 'S', '', 'A', 0, NOW(), NULL, NOW()),
(5, 'system.domain', 'test', 'S', '', 'A', 0, NOW(), NULL, NOW()),
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
  `access_right` enum('O','A','R') NOT NULL DEFAULT 'O',
  `record_status` enum('A','I') NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_courses`
--

INSERT INTO `user_courses` VALUES (1, 1, 1, 'A', 'A', 0, '2006-06-20 14:14:45', NULL, '2006-06-20 14:14:45');
INSERT INTO `user_courses` VALUES (2, 2, 1, 'A', 'A', 0, '2006-06-20 14:14:45', NULL, '2006-06-20 14:14:45');
INSERT INTO `user_courses` VALUES (3, 1, 2, 'A', 'A', 0, '2006-06-20 14:15:38', NULL, '2006-06-20 14:15:38');
INSERT INTO `user_courses` VALUES (4, 3, 2, 'A', 'A', 0, '2006-06-20 14:39:31', NULL, '2006-06-20 14:39:31');
INSERT INTO `user_courses` VALUES (5, 4, 2, 'A', 'A', 0, '2006-06-20 14:39:31', NULL, '2006-06-20 14:39:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_enrols`
--

DROP TABLE IF EXISTS `user_enrols`;
CREATE TABLE IF NOT EXISTS `user_enrols` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `record_status` enum('A','I') NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_id` (`course_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_index` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_enrols`
--

INSERT INTO `user_enrols` VALUES (1, 1, 5, 'A', 0, '2006-06-20 14:19:18', NULL, '2006-06-20 14:19:18');
INSERT INTO `user_enrols` VALUES (2, 1, 6, 'A', 0, '2006-06-20 14:26:59', NULL, '2006-06-20 14:26:59');
INSERT INTO `user_enrols` VALUES (3, 1, 7, 'A', 0, '2006-06-20 14:27:24', NULL, '2006-06-20 14:27:24');
INSERT INTO `user_enrols` VALUES (4, -1, 8, 'A', 0, '2006-06-20 14:27:43', NULL, '2006-06-20 14:27:43');
INSERT INTO `user_enrols` VALUES (5, 2, 9, 'A', 0, '2006-06-20 14:28:08', NULL, '2006-06-20 14:28:08');
INSERT INTO `user_enrols` VALUES (6, 2, 10, 'A', 0, '2006-06-20 14:28:29', NULL, '2006-06-20 14:28:29');
INSERT INTO `user_enrols` VALUES (7, 2, 11, 'A', 0, '2006-06-20 14:28:49', NULL, '2006-06-20 14:28:49');
INSERT INTO `user_enrols` VALUES (8, 2, 12, 'A', 0, '2006-06-20 14:29:07', NULL, '2006-06-20 14:29:07');
INSERT INTO `user_enrols` VALUES (9, 1, 13, 'A', 0, '2006-06-20 14:31:17', NULL, '2006-06-20 14:31:17');
INSERT INTO `user_enrols` VALUES (10, -1, 14, 'A', 0, '2006-06-20 14:47:34', NULL, '2006-06-20 14:47:34');
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
INSERT INTO `user_enrols` VALUES (27, 2, 31, 'A', 0, '2006-06-20 15:10:16', NULL, '2006-06-20 15:10:16');
INSERT INTO `user_enrols` VALUES (28, 1, 32, 'A', 0, '2006-06-20 15:10:32', NULL, '2006-06-20 15:10:32');
INSERT INTO `user_enrols` VALUES (29, 1, 33, 'A', 0, '2006-06-21 08:44:09', NULL, '2006-06-21 08:44:09');

-- --------------------------------------------------------

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES (2, 'I', 'instructor1', '6f40a1a25eec7d325310dea310949005', 'Instructor', '1', NULL, 'Instructor', '', NULL, NULL, NULL, 'A', 1, '2006-06-19 16:25:24', NULL, '2006-06-19 16:25:24', NULL);
INSERT INTO `users` VALUES (3, 'I', 'instructor2', '6f40a1a25eec7d325310dea310949005', 'Instructor', '2', NULL, 'Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:02', NULL, '2006-06-20 14:17:02', NULL);
INSERT INTO `users` VALUES (4, 'I', 'instructor3', '6f40a1a25eec7d325310dea310949005', 'Instructor', '3', NULL, 'Assistant Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:53', NULL, '2006-06-20 14:17:53', NULL);
INSERT INTO `users` VALUES (5, 'S', '65498451', '6f40a1a25eec7d325310dea310949005', 'Ed', 'Student', '65498451', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:19:18', NULL, '2006-06-20 14:19:18', NULL);
INSERT INTO `users` VALUES (6, 'S', '65468188', '6f40a1a25eec7d325310dea310949005', 'Alex', 'Student', '65468188', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:26:59', NULL, '2006-06-20 14:26:59', NULL);
INSERT INTO `users` VALUES (7, 'S', '98985481', '6f40a1a25eec7d325310dea310949005', 'Matt', 'Student', '98985481', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:24', NULL, '2006-06-20 14:27:24', NULL);
INSERT INTO `users` VALUES (8, 'S', '16585158', '6f40a1a25eec7d325310dea310949005', 'Chris', 'Student', '16585158', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:43', NULL, '2006-06-20 14:27:43', NULL);
INSERT INTO `users` VALUES (9, 'S', '81121651', '6f40a1a25eec7d325310dea310949005', 'Johnny', 'Student', '81121651', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:08', NULL, '2006-06-20 14:28:08', NULL);
INSERT INTO `users` VALUES (10, 'S', '87800283', '6f40a1a25eec7d325310dea310949005', 'Travis', 'Student', '87800283', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:29', NULL, '2006-06-20 14:28:29', NULL);
INSERT INTO `users` VALUES (11, 'S', '68541180', '6f40a1a25eec7d325310dea310949005', 'Kelly', 'Student', '68541180', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:49', NULL, '2006-06-20 14:28:49', NULL);
INSERT INTO `users` VALUES (12, 'S', '48451389', '6f40a1a25eec7d325310dea310949005', 'Peter', 'Student', '48451389', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:29:07', NULL, '2006-06-20 14:29:07', NULL);
INSERT INTO `users` VALUES (13, 'S', '84188465', '6f40a1a25eec7d325310dea310949005', 'Damien', 'Student', '84188465', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:31:17', NULL, '2006-06-20 14:31:17', NULL);
INSERT INTO `users` VALUES (14, 'S', '27701036', '6f40a1a25eec7d325310dea310949005', 'Hajar', 'Student', '27701036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:47:34', NULL, '2006-06-20 14:47:34', NULL);
INSERT INTO `users` VALUES (15, 'S', '48877031', '6f40a1a25eec7d325310dea310949005', 'Jennifer', 'Student', '48877031', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:00:35', NULL, '2006-06-20 15:00:35', NULL);
INSERT INTO `users` VALUES (16, 'S', '25731063', '6f40a1a25eec7d325310dea310949005', 'Chad', 'Student', '25731063', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:09', NULL, '2006-06-20 15:01:09', NULL);
INSERT INTO `users` VALUES (17, 'S', '37116036', '6f40a1a25eec7d325310dea310949005', 'Edna', 'Student', '37116036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:24', NULL, '2006-06-20 15:01:24', NULL);
INSERT INTO `users` VALUES (18, 'S', '76035030', '6f40a1a25eec7d325310dea310949005', 'Denny', 'Student', '76035030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:52', NULL, '2006-06-20 15:01:52', NULL);
INSERT INTO `users` VALUES (19, 'S', '90938044', '6f40a1a25eec7d325310dea310949005', 'Jonathan', 'Student', '90938044', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:02:20', NULL, '2006-06-20 15:02:20', NULL);
INSERT INTO `users` VALUES (20, 'S', '88505045', '6f40a1a25eec7d325310dea310949005', 'Soroush', 'Student', '88505045', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:27', NULL, '2006-06-20 15:03:27', NULL);
INSERT INTO `users` VALUES (21, 'S', '22784037', '6f40a1a25eec7d325310dea310949005', 'Nicole', 'Student', '22784037', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:47', NULL, '2006-06-20 15:03:47', NULL);
INSERT INTO `users` VALUES (22, 'S', '37048022', '6f40a1a25eec7d325310dea310949005', 'Vivian', 'Student', '37048022', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:04:22', NULL, '2006-06-20 15:04:22', NULL);
INSERT INTO `users` VALUES (23, 'S', '89947048', '6f40a1a25eec7d325310dea310949005', 'Trevor', 'Student', '89947048', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:05:55', NULL, '2006-06-20 15:05:55', NULL);
INSERT INTO `users` VALUES (24, 'S', '39823059', '6f40a1a25eec7d325310dea310949005', 'Michael', 'Student', '39823059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:20', NULL, '2006-06-20 15:06:20', NULL);
INSERT INTO `users` VALUES (25, 'S', '35644039', '6f40a1a25eec7d325310dea310949005', 'Steven', 'Student', '35644039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:46', NULL, '2006-06-20 15:06:46', NULL);
INSERT INTO `users` VALUES (26, 'S', '19524032', '6f40a1a25eec7d325310dea310949005', 'Bill', 'Student', '19524032', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:01', NULL, '2006-06-20 15:07:01', NULL);
INSERT INTO `users` VALUES (27, 'S', '40289059', '6f40a1a25eec7d325310dea310949005', 'Van Hong', 'Student', '40289059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:37', NULL, '2006-06-20 15:07:37', NULL);
INSERT INTO `users` VALUES (28, 'S', '38058020', '6f40a1a25eec7d325310dea310949005', 'Michael', 'Student', '38058020', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:04', NULL, '2006-06-20 15:08:04', NULL);
INSERT INTO `users` VALUES (29, 'S', '38861035', '6f40a1a25eec7d325310dea310949005', 'Jonathan', 'Student', '38861035', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:31', NULL, '2006-06-20 15:08:31', NULL);
INSERT INTO `users` VALUES (30, 'S', '27879030', '6f40a1a25eec7d325310dea310949005', 'Geoff', 'Student', '27879030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:47', NULL, '2006-06-20 15:08:47', NULL);
INSERT INTO `users` VALUES (31, 'S', '10186039', '6f40a1a25eec7d325310dea310949005', 'Hui', 'Student', '10186039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:16', NULL, '2006-06-20 15:10:16', NULL);
INSERT INTO `users` VALUES (32, 'S', '19803030', '6f40a1a25eec7d325310dea310949005', 'Bowinn', 'Student', '19803030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:32', NULL, '2006-06-20 15:10:32', NULL);
INSERT INTO `users` VALUES (33, 'S', '51516498', '6f40a1a25eec7d325310dea310949005', 'Joe', 'Student', '51516498', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-21 08:44:09', 33, '2006-06-21 08:45:00', NULL);

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
-- Table structure for table `user_grade_penalties`
--

CREATE TABLE `user_grade_penalties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penalty_id` int(11) DEFAULT NULL,
  `grp_event_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_penalties` (`penalty_id`),
  KEY `fk_users` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Constraints for table `user_grade_penalties`
--
ALTER TABLE `user_grade_penalties`
  ADD CONSTRAINT `fk_penalties` FOREIGN KEY (`penalty_id`) REFERENCES `penalties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
