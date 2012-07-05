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
(1, NULL, NULL, NULL, 'adminpage', 1, 2),
(2, NULL, NULL, NULL, 'controllers', 3, 684),
(3, 2, NULL, NULL, 'Pages', 4, 19),
(4, 3, NULL, NULL, 'display', 5, 6),
(5, 3, NULL, NULL, 'extractModel', 7, 8),
(6, 3, NULL, NULL, 'add', 9, 10),
(7, 3, NULL, NULL, 'edit', 11, 12),
(8, 3, NULL, NULL, 'index', 13, 14),
(9, 3, NULL, NULL, 'view', 15, 16),
(10, 3, NULL, NULL, 'delete', 17, 18),
(11, 2, NULL, NULL, 'Evaltools', 20, 35),
(12, 11, NULL, NULL, 'index', 21, 22),
(13, 11, NULL, NULL, 'showAll', 23, 24),
(14, 11, NULL, NULL, 'extractModel', 25, 26),
(15, 11, NULL, NULL, 'add', 27, 28),
(16, 11, NULL, NULL, 'edit', 29, 30),
(17, 11, NULL, NULL, 'view', 31, 32),
(18, 11, NULL, NULL, 'delete', 33, 34),
(19, 2, NULL, NULL, 'Sysfunctions', 36, 55),
(20, 19, NULL, NULL, 'setUpAjaxList', 37, 38),
(21, 19, NULL, NULL, 'index', 39, 40),
(22, 19, NULL, NULL, 'ajaxList', 41, 42),
(23, 19, NULL, NULL, 'view', 43, 44),
(24, 19, NULL, NULL, 'edit', 45, 46),
(25, 19, NULL, NULL, 'delete', 47, 48),
(26, 19, NULL, NULL, 'update', 49, 50),
(27, 19, NULL, NULL, 'extractModel', 51, 52),
(28, 19, NULL, NULL, 'add', 53, 54),
(29, 2, NULL, NULL, 'Emailer', 56, 89),
(30, 29, NULL, NULL, 'setUpAjaxList', 57, 58),
(31, 29, NULL, NULL, 'ajaxList', 59, 60),
(32, 29, NULL, NULL, 'index', 61, 62),
(33, 29, NULL, NULL, 'write', 63, 64),
(34, 29, NULL, NULL, 'cancel', 65, 66),
(35, 29, NULL, NULL, 'view', 67, 68),
(36, 29, NULL, NULL, 'addRecipient', 69, 70),
(37, 29, NULL, NULL, 'deleteRecipient', 71, 72),
(38, 29, NULL, NULL, 'getRecipient', 73, 74),
(39, 29, NULL, NULL, 'searchByUserId', 75, 76),
(40, 29, NULL, NULL, 'doMerge', 77, 78),
(41, 29, NULL, NULL, 'send', 79, 80),
(42, 29, NULL, NULL, 'extractModel', 81, 82),
(43, 29, NULL, NULL, 'add', 83, 84),
(44, 29, NULL, NULL, 'edit', 85, 86),
(45, 29, NULL, NULL, 'delete', 87, 88),
(46, 2, NULL, NULL, 'Users', 90, 135),
(47, 46, NULL, NULL, 'login', 91, 92),
(48, 46, NULL, NULL, 'logout', 93, 94),
(49, 46, NULL, NULL, 'ajaxList', 95, 96),
(50, 46, NULL, NULL, 'index', 97, 98),
(51, 46, NULL, NULL, 'goToClassList', 99, 100),
(52, 46, NULL, NULL, 'determineIfStudentFromThisData', 101, 102),
(53, 46, NULL, NULL, 'getSimpleEntrollmentLists', 103, 104),
(54, 46, NULL, NULL, 'processEnrollmentListsPostBack', 105, 106),
(55, 46, NULL, NULL, 'view', 107, 108),
(56, 46, NULL, NULL, 'add', 109, 110),
(57, 46, NULL, NULL, 'edit', 111, 112),
(58, 46, NULL, NULL, 'editProfile', 113, 114),
(59, 46, NULL, NULL, 'delete', 115, 116),
(60, 46, NULL, NULL, 'drop', 117, 118),
(61, 46, NULL, NULL, 'checkDuplicateName', 119, 120),
(62, 46, NULL, NULL, 'resetPassword', 121, 122),
(63, 46, NULL, NULL, 'import', 123, 124),
(64, 46, NULL, NULL, 'importPreview', 125, 126),
(65, 46, NULL, NULL, 'importFile', 127, 128),
(66, 46, NULL, NULL, 'update', 129, 130),
(67, 46, NULL, NULL, 'nonRegisteredCourses', 131, 132),
(68, 46, NULL, NULL, 'extractModel', 133, 134),
(69, 2, NULL, NULL, 'Mixevals', 136, 167),
(70, 69, NULL, NULL, 'postProcess', 137, 138),
(71, 69, NULL, NULL, 'setUpAjaxList', 139, 140),
(72, 69, NULL, NULL, 'index', 141, 142),
(73, 69, NULL, NULL, 'ajaxList', 143, 144),
(74, 69, NULL, NULL, 'view', 145, 146),
(75, 69, NULL, NULL, 'add', 147, 148),
(76, 69, NULL, NULL, 'deleteQuestion', 149, 150),
(77, 69, NULL, NULL, 'deleteDescriptor', 151, 152),
(78, 69, NULL, NULL, 'edit', 153, 154),
(79, 69, NULL, NULL, 'copy', 155, 156),
(80, 69, NULL, NULL, 'delete', 157, 158),
(81, 69, NULL, NULL, 'previewMixeval', 159, 160),
(82, 69, NULL, NULL, 'renderRows', 161, 162),
(83, 69, NULL, NULL, 'update', 163, 164),
(84, 69, NULL, NULL, 'extractModel', 165, 166),
(85, 2, NULL, NULL, 'Simpleevaluations', 168, 189),
(86, 85, NULL, NULL, 'postProcess', 169, 170),
(87, 85, NULL, NULL, 'setUpAjaxList', 171, 172),
(88, 85, NULL, NULL, 'index', 173, 174),
(89, 85, NULL, NULL, 'ajaxList', 175, 176),
(90, 85, NULL, NULL, 'view', 177, 178),
(91, 85, NULL, NULL, 'add', 179, 180),
(92, 85, NULL, NULL, 'edit', 181, 182),
(93, 85, NULL, NULL, 'copy', 183, 184),
(94, 85, NULL, NULL, 'delete', 185, 186),
(95, 85, NULL, NULL, 'extractModel', 187, 188),
(96, 2, NULL, NULL, 'Penalty', 190, 205),
(97, 96, NULL, NULL, 'save', 191, 192),
(98, 96, NULL, NULL, 'extractModel', 193, 194),
(99, 96, NULL, NULL, 'add', 195, 196),
(100, 96, NULL, NULL, 'edit', 197, 198),
(101, 96, NULL, NULL, 'index', 199, 200),
(102, 96, NULL, NULL, 'view', 201, 202),
(103, 96, NULL, NULL, 'delete', 203, 204),
(104, 2, NULL, NULL, 'Home', 206, 221),
(105, 104, NULL, NULL, 'index', 207, 208),
(106, 104, NULL, NULL, 'studentIndex', 209, 210),
(107, 104, NULL, NULL, 'extractModel', 211, 212),
(108, 104, NULL, NULL, 'add', 213, 214),
(109, 104, NULL, NULL, 'edit', 215, 216),
(110, 104, NULL, NULL, 'view', 217, 218),
(111, 104, NULL, NULL, 'delete', 219, 220),
(112, 2, NULL, NULL, 'Surveys', 222, 257),
(113, 112, NULL, NULL, 'setUpAjaxList', 223, 224),
(114, 112, NULL, NULL, 'index', 225, 226),
(115, 112, NULL, NULL, 'ajaxList', 227, 228),
(116, 112, NULL, NULL, 'view', 229, 230),
(117, 112, NULL, NULL, 'add', 231, 232),
(118, 112, NULL, NULL, 'edit', 233, 234),
(119, 112, NULL, NULL, 'copy', 235, 236),
(120, 112, NULL, NULL, 'delete', 237, 238),
(121, 112, NULL, NULL, 'checkDuplicateName', 239, 240),
(122, 112, NULL, NULL, 'releaseSurvey', 241, 242),
(123, 112, NULL, NULL, 'questionsSummary', 243, 244),
(124, 112, NULL, NULL, 'moveQuestion', 245, 246),
(125, 112, NULL, NULL, 'removeQuestion', 247, 248),
(126, 112, NULL, NULL, 'addQuestion', 249, 250),
(127, 112, NULL, NULL, 'editQuestion', 251, 252),
(128, 112, NULL, NULL, 'update', 253, 254),
(129, 112, NULL, NULL, 'extractModel', 255, 256),
(130, 2, NULL, NULL, 'Evaluations', 258, 333),
(131, 130, NULL, NULL, 'postProcess', 259, 260),
(132, 130, NULL, NULL, 'setUpAjaxList', 261, 262),
(133, 130, NULL, NULL, 'ajaxList', 263, 264),
(134, 130, NULL, NULL, 'view', 265, 266),
(135, 130, NULL, NULL, 'index', 267, 268),
(136, 130, NULL, NULL, 'search', 269, 270),
(137, 130, NULL, NULL, 'update', 271, 272),
(138, 130, NULL, NULL, 'test', 273, 274),
(139, 130, NULL, NULL, 'export', 275, 276),
(140, 130, NULL, NULL, 'makeSimpleEvaluation', 277, 278),
(141, 130, NULL, NULL, 'validSimpleEvalComplete', 279, 280),
(142, 130, NULL, NULL, 'makeSurveyEvaluation', 281, 282),
(143, 130, NULL, NULL, 'validSurveyEvalComplete', 283, 284),
(144, 130, NULL, NULL, 'makeRubricEvaluation', 285, 286),
(145, 130, NULL, NULL, 'validRubricEvalComplete', 287, 288),
(146, 130, NULL, NULL, 'completeEvaluationRubric', 289, 290),
(147, 130, NULL, NULL, 'makeMixevalEvaluation', 291, 292),
(148, 130, NULL, NULL, 'validMixevalEvalComplete', 293, 294),
(149, 130, NULL, NULL, 'completeEvaluationMixeval', 295, 296),
(150, 130, NULL, NULL, 'viewEvaluationResults', 297, 298),
(151, 130, NULL, NULL, 'viewSurveyGroupEvaluationResults', 299, 300),
(152, 130, NULL, NULL, 'studentViewEvaluationResult', 301, 302),
(153, 130, NULL, NULL, 'markEventReviewed', 303, 304),
(154, 130, NULL, NULL, 'markGradeRelease', 305, 306),
(155, 130, NULL, NULL, 'markCommentRelease', 307, 308),
(156, 130, NULL, NULL, 'changeAllCommentRelease', 309, 310),
(157, 130, NULL, NULL, 'changeAllGradeRelease', 311, 312),
(158, 130, NULL, NULL, 'viewGroupSubmissionDetails', 313, 314),
(159, 130, NULL, NULL, 'reReleaseEvaluation', 315, 316),
(160, 130, NULL, NULL, 'viewSurveySummary', 317, 318),
(161, 130, NULL, NULL, 'export_rubic', 319, 320),
(162, 130, NULL, NULL, 'export_test', 321, 322),
(163, 130, NULL, NULL, 'pre', 323, 324),
(164, 130, NULL, NULL, 'extractModel', 325, 326),
(165, 130, NULL, NULL, 'add', 327, 328),
(166, 130, NULL, NULL, 'edit', 329, 330),
(167, 130, NULL, NULL, 'delete', 331, 332),
(168, 2, NULL, NULL, 'Rubrics', 334, 357),
(169, 168, NULL, NULL, 'postProcess', 335, 336),
(170, 168, NULL, NULL, 'setUpAjaxList', 337, 338),
(171, 168, NULL, NULL, 'index', 339, 340),
(172, 168, NULL, NULL, 'ajaxList', 341, 342),
(173, 168, NULL, NULL, 'view', 343, 344),
(174, 168, NULL, NULL, 'add', 345, 346),
(175, 168, NULL, NULL, 'edit', 347, 348),
(176, 168, NULL, NULL, 'copy', 349, 350),
(177, 168, NULL, NULL, 'delete', 351, 352),
(178, 168, NULL, NULL, 'setForm_RubricName', 353, 354),
(179, 168, NULL, NULL, 'extractModel', 355, 356),
(180, 2, NULL, NULL, 'Emailtemplates', 358, 379),
(181, 180, NULL, NULL, 'setUpAjaxList', 359, 360),
(182, 180, NULL, NULL, 'ajaxList', 361, 362),
(183, 180, NULL, NULL, 'index', 363, 364),
(184, 180, NULL, NULL, 'add', 365, 366),
(185, 180, NULL, NULL, 'edit', 367, 368),
(186, 180, NULL, NULL, 'delete', 369, 370),
(187, 180, NULL, NULL, 'view', 371, 372),
(188, 180, NULL, NULL, 'displayTemplateContent', 373, 374),
(189, 180, NULL, NULL, 'displayTemplateSubject', 375, 376),
(190, 180, NULL, NULL, 'extractModel', 377, 378),
(191, 2, NULL, NULL, 'Faculties', 380, 393),
(192, 191, NULL, NULL, 'index', 381, 382),
(193, 191, NULL, NULL, 'view', 383, 384),
(194, 191, NULL, NULL, 'add', 385, 386),
(195, 191, NULL, NULL, 'edit', 387, 388),
(196, 191, NULL, NULL, 'delete', 389, 390),
(197, 191, NULL, NULL, 'extractModel', 391, 392),
(198, 2, NULL, NULL, 'Departments', 394, 407),
(199, 198, NULL, NULL, 'index', 395, 396),
(200, 198, NULL, NULL, 'view', 397, 398),
(201, 198, NULL, NULL, 'add', 399, 400),
(202, 198, NULL, NULL, 'edit', 401, 402),
(203, 198, NULL, NULL, 'delete', 403, 404),
(204, 198, NULL, NULL, 'extractModel', 405, 406),
(205, 2, NULL, NULL, 'Sysparameters', 408, 425),
(206, 205, NULL, NULL, 'setUpAjaxList', 409, 410),
(207, 205, NULL, NULL, 'index', 411, 412),
(208, 205, NULL, NULL, 'ajaxList', 413, 414),
(209, 205, NULL, NULL, 'view', 415, 416),
(210, 205, NULL, NULL, 'add', 417, 418),
(211, 205, NULL, NULL, 'edit', 419, 420),
(212, 205, NULL, NULL, 'delete', 421, 422),
(213, 205, NULL, NULL, 'extractModel', 423, 424),
(214, 2, NULL, NULL, 'Install', 426, 447),
(215, 214, NULL, NULL, 'index', 427, 428),
(216, 214, NULL, NULL, 'install2', 429, 430),
(217, 214, NULL, NULL, 'install3', 431, 432),
(218, 214, NULL, NULL, 'install4', 433, 434),
(219, 214, NULL, NULL, 'install5', 435, 436),
(220, 214, NULL, NULL, 'gpl', 437, 438),
(221, 214, NULL, NULL, 'add', 439, 440),
(222, 214, NULL, NULL, 'edit', 441, 442),
(223, 214, NULL, NULL, 'view', 443, 444),
(224, 214, NULL, NULL, 'delete', 445, 446),
(225, 2, NULL, NULL, 'Searchs', 448, 477),
(226, 225, NULL, NULL, 'update', 449, 450),
(227, 225, NULL, NULL, 'index', 451, 452),
(228, 225, NULL, NULL, 'searchEvaluation', 453, 454),
(229, 225, NULL, NULL, 'searchResult', 455, 456),
(230, 225, NULL, NULL, 'searchInstructor', 457, 458),
(231, 225, NULL, NULL, 'eventBoxSearch', 459, 460),
(232, 225, NULL, NULL, 'formatSearchEvaluation', 461, 462),
(233, 225, NULL, NULL, 'formatSearchInstructor', 463, 464),
(234, 225, NULL, NULL, 'formatSearchEvaluationResult', 465, 466),
(235, 225, NULL, NULL, 'extractModel', 467, 468),
(236, 225, NULL, NULL, 'add', 469, 470),
(237, 225, NULL, NULL, 'edit', 471, 472),
(238, 225, NULL, NULL, 'view', 473, 474),
(239, 225, NULL, NULL, 'delete', 475, 476),
(240, 2, NULL, NULL, 'Groups', 478, 513),
(241, 240, NULL, NULL, 'postProcess', 479, 480),
(242, 240, NULL, NULL, 'setUpAjaxList', 481, 482),
(243, 240, NULL, NULL, 'index', 483, 484),
(244, 240, NULL, NULL, 'ajaxList', 485, 486),
(245, 240, NULL, NULL, 'goToClassList', 487, 488),
(246, 240, NULL, NULL, 'view', 489, 490),
(247, 240, NULL, NULL, 'add', 491, 492),
(248, 240, NULL, NULL, 'edit', 493, 494),
(249, 240, NULL, NULL, 'delete', 495, 496),
(250, 240, NULL, NULL, 'checkDuplicateName', 497, 498),
(251, 240, NULL, NULL, 'getQueryAttribute', 499, 500),
(252, 240, NULL, NULL, 'import', 501, 502),
(253, 240, NULL, NULL, 'addGroupByImport', 503, 504),
(254, 240, NULL, NULL, 'update', 505, 506),
(255, 240, NULL, NULL, 'export', 507, 508),
(256, 240, NULL, NULL, 'sendGroupEmail', 509, 510),
(257, 240, NULL, NULL, 'extractModel', 511, 512),
(258, 2, NULL, NULL, 'Courses', 514, 541),
(259, 258, NULL, NULL, 'daysLate', 515, 516),
(260, 258, NULL, NULL, 'index', 517, 518),
(261, 258, NULL, NULL, 'ajaxList', 519, 520),
(262, 258, NULL, NULL, 'view', 521, 522),
(263, 258, NULL, NULL, 'home', 523, 524),
(264, 258, NULL, NULL, 'add', 525, 526),
(265, 258, NULL, NULL, 'edit', 527, 528),
(266, 258, NULL, NULL, 'delete', 529, 530),
(267, 258, NULL, NULL, 'addInstructor', 531, 532),
(268, 258, NULL, NULL, 'deleteInstructor', 533, 534),
(269, 258, NULL, NULL, 'checkDuplicateName', 535, 536),
(270, 258, NULL, NULL, 'update', 537, 538),
(271, 258, NULL, NULL, 'extractModel', 539, 540),
(272, 2, NULL, NULL, 'Upgrade', 542, 559),
(273, 272, NULL, NULL, 'index', 543, 544),
(274, 272, NULL, NULL, 'step2', 545, 546),
(275, 272, NULL, NULL, 'checkPermission', 547, 548),
(276, 272, NULL, NULL, 'extractModel', 549, 550),
(277, 272, NULL, NULL, 'add', 551, 552),
(278, 272, NULL, NULL, 'edit', 553, 554),
(279, 272, NULL, NULL, 'view', 555, 556),
(280, 272, NULL, NULL, 'delete', 557, 558),
(281, 2, NULL, NULL, 'Surveygroups', 560, 593),
(282, 281, NULL, NULL, 'postProcess', 561, 562),
(283, 281, NULL, NULL, 'setUpAjaxList', 563, 564),
(284, 281, NULL, NULL, 'index', 565, 566),
(285, 281, NULL, NULL, 'ajaxList', 567, 568),
(286, 281, NULL, NULL, 'viewresult', 569, 570),
(287, 281, NULL, NULL, 'makegroups', 571, 572),
(288, 281, NULL, NULL, 'makegroupssearch', 573, 574),
(289, 281, NULL, NULL, 'maketmgroups', 575, 576),
(290, 281, NULL, NULL, 'savegroups', 577, 578),
(291, 281, NULL, NULL, 'release', 579, 580),
(292, 281, NULL, NULL, 'delete', 581, 582),
(293, 281, NULL, NULL, 'edit', 583, 584),
(294, 281, NULL, NULL, 'changegroupset', 585, 586),
(295, 281, NULL, NULL, 'extractModel', 587, 588),
(296, 281, NULL, NULL, 'add', 589, 590),
(297, 281, NULL, NULL, 'view', 591, 592),
(298, 2, NULL, NULL, 'Events', 594, 629),
(299, 298, NULL, NULL, 'postProcessData', 595, 596),
(300, 298, NULL, NULL, 'setUpAjaxList', 597, 598),
(301, 298, NULL, NULL, 'index', 599, 600),
(302, 298, NULL, NULL, 'ajaxList', 601, 602),
(303, 298, NULL, NULL, 'view', 603, 604),
(304, 298, NULL, NULL, 'eventTemplatesList', 605, 606),
(305, 298, NULL, NULL, 'add', 607, 608),
(306, 298, NULL, NULL, 'edit', 609, 610),
(307, 298, NULL, NULL, 'editOld', 611, 612),
(308, 298, NULL, NULL, 'delete', 613, 614),
(309, 298, NULL, NULL, 'search', 615, 616),
(310, 298, NULL, NULL, 'checkDuplicateTitle', 617, 618),
(311, 298, NULL, NULL, 'viewGroups', 619, 620),
(312, 298, NULL, NULL, 'editGroup', 621, 622),
(313, 298, NULL, NULL, 'getAssignedGroups', 623, 624),
(314, 298, NULL, NULL, 'update', 625, 626),
(315, 298, NULL, NULL, 'extractModel', 627, 628),
(316, 2, NULL, NULL, 'Framework', 630, 649),
(317, 316, NULL, NULL, 'calendarDisplay', 631, 632),
(318, 316, NULL, NULL, 'userInfoDisplay', 633, 634),
(319, 316, NULL, NULL, 'tutIndex', 635, 636),
(320, 316, NULL, NULL, 'extractModel', 637, 638),
(321, 316, NULL, NULL, 'add', 639, 640),
(322, 316, NULL, NULL, 'edit', 641, 642),
(323, 316, NULL, NULL, 'index', 643, 644),
(324, 316, NULL, NULL, 'view', 645, 646),
(325, 316, NULL, NULL, 'delete', 647, 648),
(326, 2, NULL, NULL, 'Lti', 650, 663),
(327, 326, NULL, NULL, 'index', 651, 652),
(328, 326, NULL, NULL, 'extractModel', 653, 654),
(329, 326, NULL, NULL, 'add', 655, 656),
(330, 326, NULL, NULL, 'edit', 657, 658),
(331, 326, NULL, NULL, 'view', 659, 660),
(332, 326, NULL, NULL, 'delete', 661, 662),
(333, 2, NULL, NULL, 'Guard', 664, 683),
(334, 333, NULL, NULL, 'Guard', 665, 682),
(335, 334, NULL, NULL, 'login', 666, 667),
(336, 334, NULL, NULL, 'logout', 668, 669),
(337, 334, NULL, NULL, 'extractModel', 670, 671),
(338, 334, NULL, NULL, 'add', 672, 673),
(339, 334, NULL, NULL, 'edit', 674, 675),
(340, 334, NULL, NULL, 'index', 676, 677),
(341, 334, NULL, NULL, 'view', 678, 679),
(342, 334, NULL, NULL, 'delete', 680, 681),
(343, NULL, NULL, NULL, 'functions', 685, 736),
(344, 343, NULL, NULL, 'user', 686, 711),
(345, 344, NULL, NULL, 'superadmin', 687, 688),
(346, 344, NULL, NULL, 'admin', 689, 690),
(347, 344, NULL, NULL, 'instructor', 691, 692),
(348, 344, NULL, NULL, 'tutor', 693, 694),
(349, 344, NULL, NULL, 'student', 695, 696),
(350, 344, NULL, NULL, 'import', 697, 698),
(351, 344, NULL, NULL, 'password_reset', 699, 710),
(352, 351, NULL, NULL, 'superadmin', 700, 701),
(353, 351, NULL, NULL, 'admin', 702, 703),
(354, 351, NULL, NULL, 'instructor', 704, 705),
(355, 351, NULL, NULL, 'tutor', 706, 707),
(356, 351, NULL, NULL, 'student', 708, 709),
(357, 343, NULL, NULL, 'role', 712, 723),
(358, 357, NULL, NULL, 'superadmin', 713, 714),
(359, 357, NULL, NULL, 'admin', 715, 716),
(360, 357, NULL, NULL, 'instructor', 717, 718),
(361, 357, NULL, NULL, 'tutor', 719, 720),
(362, 357, NULL, NULL, 'student', 721, 722),
(363, 343, NULL, NULL, 'evaluation', 724, 725),
(364, 343, NULL, NULL, 'email', 726, 733),
(365, 364, NULL, NULL, 'allUsers', 727, 728),
(366, 364, NULL, NULL, 'allGroups', 729, 730),
(367, 364, NULL, NULL, 'allCourses', 731, 732),
(368, 343, NULL, NULL, 'emailtemplate', 734, 735);

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
(2, 1, 343, '1', '1', '1', '1'),
(3, 1, 11, '1', '1', '1', '1'),
(4, 1, 85, '1', '1', '1', '1'),
(5, 1, 168, '1', '1', '1', '1'),
(6, 1, 69, '1', '1', '1', '1'),
(7, 1, 112, '1', '1', '1', '1'),
(8, 1, 29, '1', '1', '1', '1'),
(9, 1, 180, '1', '1', '1', '1'),
(10, 1, 191, '1', '1', '1', '1'),
(11, 1, 198, '1', '1', '1', '1'),
(12, 1, 1, '1', '1', '1', '1'),
(13, 2, 2, '-1', '-1', '-1', '-1'),
(14, 2, 104, '1', '1', '1', '1'),
(15, 2, 258, '1', '1', '1', '1'),
(16, 2, 46, '1', '1', '1', '1'),
(17, 2, 343, '-1', '-1', '-1', '-1'),
(18, 2, 344, '1', '1', '1', '1'),
(19, 2, 346, '-1', '1', '-1', '-1'),
(20, 2, 345, '-1', '-1', '-1', '-1'),
(21, 2, 11, '1', '1', '1', '1'),
(22, 2, 363, '1', '1', '1', '1'),
(23, 2, 85, '1', '1', '1', '1'),
(24, 2, 168, '1', '1', '1', '1'),
(25, 2, 69, '1', '1', '1', '1'),
(26, 2, 112, '1', '1', '1', '1'),
(27, 2, 29, '1', '1', '1', '1'),
(28, 2, 364, '1', '1', '1', '1'),
(29, 2, 180, '1', '1', '1', '1'),
(30, 2, 368, '1', '1', '1', '1'),
(31, 2, 198, '1', '1', '1', '1'),
(32, 2, 1, '1', '1', '1', '1'),
(33, 3, 2, '-1', '-1', '-1', '-1'),
(34, 3, 104, '1', '1', '1', '1'),
(35, 3, 258, '1', '1', '1', '1'),
(36, 3, 264, '-1', '-1', '-1', '-1'),
(37, 3, 265, '-1', '-1', '-1', '-1'),
(38, 3, 46, '1', '1', '1', '1'),
(39, 3, 343, '-1', '-1', '-1', '-1'),
(40, 3, 344, '1', '1', '1', '1'),
(41, 3, 346, '-1', '-1', '-1', '-1'),
(42, 3, 345, '-1', '-1', '-1', '-1'),
(43, 3, 347, '-1', '1', '-1', '-1'),
(44, 3, 11, '1', '1', '1', '1'),
(45, 3, 363, '1', '1', '-1', '-1'),
(46, 3, 85, '1', '1', '1', '1'),
(47, 3, 168, '1', '1', '1', '1'),
(48, 3, 69, '1', '1', '1', '1'),
(49, 3, 112, '1', '1', '1', '1'),
(50, 3, 29, '1', '1', '1', '1'),
(51, 3, 364, '1', '1', '1', '1'),
(52, 3, 365, '-1', '-1', '-1', '-1'),
(53, 3, 366, '-1', '-1', '-1', '-1'),
(54, 3, 367, '-1', '-1', '-1', '-1'),
(55, 3, 180, '1', '1', '1', '1'),
(56, 5, 2, '-1', '-1', '-1', '-1'),
(57, 5, 104, '1', '1', '1', '1'),
(58, 5, 258, '-1', '-1', '-1', '-1'),
(59, 5, 46, '-1', '-1', '-1', '-1'),
(60, 5, 343, '-1', '-1', '-1', '-1'),
(61, 5, 11, '-1', '-1', '-1', '-1'),
(62, 5, 85, '-1', '-1', '-1', '-1'),
(63, 5, 168, '-1', '-1', '-1', '-1'),
(64, 5, 69, '-1', '-1', '-1', '-1'),
(65, 5, 112, '-1', '-1', '-1', '-1'),
(66, 5, 29, '-1', '-1', '-1', '-1'),
(67, 5, 180, '-1', '-1', '-1', '-1');

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
(3, 1, 1);

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

INSERT INTO `roles_users` (`id`, `role_id`, `user_id`, `created`, `modified`) VALUES
(1, 3, 2, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(2, 3, 3, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(3, 3, 4, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(4, 5, 5, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(5, 5, 6, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(6, 5, 7, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(7, 5, 8, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(8, 5, 9, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(9, 5, 10, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(10, 5, 11, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(11, 5, 12, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(12, 5, 13, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(13, 5, 14, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(14, 5, 15, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(15, 5, 16, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(17, 5, 17, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(18, 5, 18, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(19, 5, 19, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(20, 5, 20, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(21, 5, 21, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(22, 5, 22, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(23, 5, 23, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(24, 5, 24, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(25, 5, 25, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(27, 5, 26, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(28, 5, 27, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(29, 5, 28, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(30, 5, 29, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(31, 5, 30, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(32, 5, 31, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(33, 5, 32, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(34, 5, 33, '2010-10-27 16:17:29', '2010-10-27 16:17:29'),
(35, 2, 34, '2010-10-27 16:17:29', '2010-10-27 16:17:29');

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

INSERT INTO `users` (`id`, `role`, `username`, `password`, `first_name`, `last_name`, `student_no`, `title`, `email`, `last_login`, `last_logout`, `last_accessed`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`, `lti_id`) VALUES
(2, 'I', 'instructor1', '6f40a1a25eec7d325310dea310949005', 'Instructor', '1', NULL, 'Instructor', '', NULL, NULL, NULL, 'A', 1, '2006-06-19 16:25:24', NULL, '2006-06-19 16:25:24', NULL),
(3, 'I', 'instructor2', '6f40a1a25eec7d325310dea310949005', 'Instructor', '2', NULL, 'Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:02', NULL, '2006-06-20 14:17:02', NULL),
(4, 'I', 'instructor3', '6f40a1a25eec7d325310dea310949005', 'Instructor', '3', NULL, 'Assistant Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:53', NULL, '2006-06-20 14:17:53', NULL),
(5, 'S', '65498451', '6f40a1a25eec7d325310dea310949005', 'Ed', 'Student', '65498451', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:19:18', NULL, '2006-06-20 14:19:18', NULL),
(6, 'S', '65468188', '6f40a1a25eec7d325310dea310949005', 'Alex', 'Student', '65468188', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:26:59', NULL, '2006-06-20 14:26:59', NULL),
(7, 'S', '98985481', '6f40a1a25eec7d325310dea310949005', 'Matt', 'Student', '98985481', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:24', NULL, '2006-06-20 14:27:24', NULL),
(8, 'S', '16585158', '6f40a1a25eec7d325310dea310949005', 'Chris', 'Student', '16585158', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:43', NULL, '2006-06-20 14:27:43', NULL),
(9, 'S', '81121651', '6f40a1a25eec7d325310dea310949005', 'Johnny', 'Student', '81121651', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:08', NULL, '2006-06-20 14:28:08', NULL),
(10, 'S', '87800283', '6f40a1a25eec7d325310dea310949005', 'Travis', 'Student', '87800283', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:29', NULL, '2006-06-20 14:28:29', NULL),
(11, 'S', '68541180', '6f40a1a25eec7d325310dea310949005', 'Kelly', 'Student', '68541180', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:49', NULL, '2006-06-20 14:28:49', NULL),
(12, 'S', '48451389', '6f40a1a25eec7d325310dea310949005', 'Peter', 'Student', '48451389', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:29:07', NULL, '2006-06-20 14:29:07', NULL),
(13, 'S', '84188465', '6f40a1a25eec7d325310dea310949005', 'Damien', 'Student', '84188465', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:31:17', NULL, '2006-06-20 14:31:17', NULL),
(14, 'S', '27701036', '6f40a1a25eec7d325310dea310949005', 'Hajar', 'Student', '27701036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:47:34', NULL, '2006-06-20 14:47:34', NULL),
(15, 'S', '48877031', '6f40a1a25eec7d325310dea310949005', 'Jennifer', 'Student', '48877031', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:00:35', NULL, '2006-06-20 15:00:35', NULL),
(16, 'S', '25731063', '6f40a1a25eec7d325310dea310949005', 'Chad', 'Student', '25731063', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:09', NULL, '2006-06-20 15:01:09', NULL),
(17, 'S', '37116036', '6f40a1a25eec7d325310dea310949005', 'Edna', 'Student', '37116036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:24', NULL, '2006-06-20 15:01:24', NULL),
(18, 'S', '76035030', '6f40a1a25eec7d325310dea310949005', 'Denny', 'Student', '76035030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:52', NULL, '2006-06-20 15:01:52', NULL),
(19, 'S', '90938044', '6f40a1a25eec7d325310dea310949005', 'Jonathan', 'Student', '90938044', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:02:20', NULL, '2006-06-20 15:02:20', NULL),
(20, 'S', '88505045', '6f40a1a25eec7d325310dea310949005', 'Soroush', 'Student', '88505045', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:27', NULL, '2006-06-20 15:03:27', NULL),
(21, 'S', '22784037', '6f40a1a25eec7d325310dea310949005', 'Nicole', 'Student', '22784037', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:47', NULL, '2006-06-20 15:03:47', NULL),
(22, 'S', '37048022', '6f40a1a25eec7d325310dea310949005', 'Vivian', 'Student', '37048022', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:04:22', NULL, '2006-06-20 15:04:22', NULL),
(23, 'S', '89947048', '6f40a1a25eec7d325310dea310949005', 'Trevor', 'Student', '89947048', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:05:55', NULL, '2006-06-20 15:05:55', NULL),
(24, 'S', '39823059', '6f40a1a25eec7d325310dea310949005', 'Michael', 'Student', '39823059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:20', NULL, '2006-06-20 15:06:20', NULL),
(25, 'S', '35644039', '6f40a1a25eec7d325310dea310949005', 'Steven', 'Student', '35644039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:46', NULL, '2006-06-20 15:06:46', NULL),
(26, 'S', '19524032', '6f40a1a25eec7d325310dea310949005', 'Bill', 'Student', '19524032', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:01', NULL, '2006-06-20 15:07:01', NULL),
(27, 'S', '40289059', '6f40a1a25eec7d325310dea310949005', 'Van Hong', 'Student', '40289059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:37', NULL, '2006-06-20 15:07:37', NULL),
(28, 'S', '38058020', '6f40a1a25eec7d325310dea310949005', 'Michael', 'Student', '38058020', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:04', NULL, '2006-06-20 15:08:04', NULL),
(29, 'S', '38861035', '6f40a1a25eec7d325310dea310949005', 'Jonathan', 'Student', '38861035', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:31', NULL, '2006-06-20 15:08:31', NULL),
(30, 'S', '27879030', '6f40a1a25eec7d325310dea310949005', 'Geoff', 'Student', '27879030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:47', NULL, '2006-06-20 15:08:47', NULL),
(31, 'S', '10186039', '6f40a1a25eec7d325310dea310949005', 'Hui', 'Student', '10186039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:16', NULL, '2006-06-20 15:10:16', NULL),
(32, 'S', '19803030', '6f40a1a25eec7d325310dea310949005', 'Bowinn', 'Student', '19803030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:32', NULL, '2006-06-20 15:10:32', NULL),
(33, 'S', '51516498', '6f40a1a25eec7d325310dea310949005', 'Joe', 'Student', '51516498', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-21 08:44:09', 33, '2006-06-21 08:45:00', NULL),
(34, 'S', 'admin1', '6f40a1a25eec7d325310dea310949005', 'admin1', '', '', '', '', NULL, NULL, NULL, 'A', 1, '2012-05-25 15:48:08', 1, '2012-05-25 15:48:08', NULL);

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
(1, 34, 1);

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
