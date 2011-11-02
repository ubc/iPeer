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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=365 ;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, NULL, NULL, 'controllers', 1, 694),
(2, 1, NULL, NULL, 'Pages', 2, 21),
(3, 2, NULL, NULL, 'display', 3, 4),
(4, 2, NULL, NULL, 'checkDatabaseVersion', 5, 6),
(5, 2, NULL, NULL, 'extractModel', 7, 8),
(6, 2, NULL, NULL, 'checkAccess', 9, 10),
(7, 2, NULL, NULL, 'add', 11, 12),
(8, 2, NULL, NULL, 'edit', 13, 14),
(9, 2, NULL, NULL, 'index', 15, 16),
(10, 2, NULL, NULL, 'view', 17, 18),
(11, 2, NULL, NULL, 'delete', 19, 20),
(12, 1, NULL, NULL, 'Bakers', 22, 39),
(13, 12, NULL, NULL, 'index', 23, 24),
(14, 12, NULL, NULL, 'checkDatabaseVersion', 25, 26),
(15, 12, NULL, NULL, 'extractModel', 27, 28),
(16, 12, NULL, NULL, 'checkAccess', 29, 30),
(17, 12, NULL, NULL, 'add', 31, 32),
(18, 12, NULL, NULL, 'edit', 33, 34),
(19, 12, NULL, NULL, 'view', 35, 36),
(20, 12, NULL, NULL, 'delete', 37, 38),
(21, 1, NULL, NULL, 'Courses', 40, 79),
(22, 21, NULL, NULL, 'setUpAjaxList', 41, 42),
(23, 21, NULL, NULL, 'index', 43, 44),
(24, 21, NULL, NULL, 'ajaxList', 45, 46),
(25, 21, NULL, NULL, 'view', 47, 48),
(26, 21, NULL, NULL, 'home', 49, 50),
(27, 21, NULL, NULL, 'add', 51, 52),
(28, 21, NULL, NULL, 'edit', 53, 54),
(29, 21, NULL, NULL, 'delete', 55, 56),
(30, 21, NULL, NULL, 'deleteInstructor', 57, 58),
(31, 21, NULL, NULL, 'adddelrow', 59, 60),
(32, 21, NULL, NULL, 'adddelinstructor', 61, 62),
(33, 21, NULL, NULL, 'allUsers', 63, 64),
(34, 21, NULL, NULL, 'allInstructors', 65, 66),
(35, 21, NULL, NULL, 'uniqueInstructors', 67, 68),
(36, 21, NULL, NULL, 'checkDuplicateName', 69, 70),
(37, 21, NULL, NULL, 'update', 71, 72),
(38, 21, NULL, NULL, 'checkDatabaseVersion', 73, 74),
(39, 21, NULL, NULL, 'extractModel', 75, 76),
(40, 21, NULL, NULL, 'checkAccess', 77, 78),
(41, 1, NULL, NULL, 'Evaltools', 80, 99),
(42, 41, NULL, NULL, 'index', 81, 82),
(43, 41, NULL, NULL, 'showAll', 83, 84),
(44, 41, NULL, NULL, 'checkDatabaseVersion', 85, 86),
(45, 41, NULL, NULL, 'extractModel', 87, 88),
(46, 41, NULL, NULL, 'checkAccess', 89, 90),
(47, 41, NULL, NULL, 'add', 91, 92),
(48, 41, NULL, NULL, 'edit', 93, 94),
(49, 41, NULL, NULL, 'view', 95, 96),
(50, 41, NULL, NULL, 'delete', 97, 98),
(51, 1, NULL, NULL, 'Evaluations', 100, 179),
(52, 51, NULL, NULL, 'postProcess', 101, 102),
(53, 51, NULL, NULL, 'setUpAjaxList', 103, 104),
(54, 51, NULL, NULL, 'ajaxList', 105, 106),
(55, 51, NULL, NULL, 'view', 107, 108),
(56, 51, NULL, NULL, 'index', 109, 110),
(57, 51, NULL, NULL, 'search', 111, 112),
(58, 51, NULL, NULL, 'update', 113, 114),
(59, 51, NULL, NULL, 'test', 115, 116),
(60, 51, NULL, NULL, 'export', 117, 118),
(61, 51, NULL, NULL, 'makeSimpleEvaluation', 119, 120),
(62, 51, NULL, NULL, 'validSimpleEvalComplete', 121, 122),
(63, 51, NULL, NULL, 'makeSurveyEvaluation', 123, 124),
(64, 51, NULL, NULL, 'validSurveyEvalComplete', 125, 126),
(65, 51, NULL, NULL, 'makeRubricEvaluation', 127, 128),
(66, 51, NULL, NULL, 'validRubricEvalComplete', 129, 130),
(67, 51, NULL, NULL, 'completeEvaluationRubric', 131, 132),
(68, 51, NULL, NULL, 'makeMixevalEvaluation', 133, 134),
(69, 51, NULL, NULL, 'validMixevalEvalComplete', 135, 136),
(70, 51, NULL, NULL, 'completeEvaluationMixeval', 137, 138),
(71, 51, NULL, NULL, 'viewEvaluationResults', 139, 140),
(72, 51, NULL, NULL, 'viewSurveyGroupEvaluationResults', 141, 142),
(73, 51, NULL, NULL, 'studentViewEvaluationResult', 143, 144),
(74, 51, NULL, NULL, 'markEventReviewed', 145, 146),
(75, 51, NULL, NULL, 'markGradeRelease', 147, 148),
(76, 51, NULL, NULL, 'markCommentRelease', 149, 150),
(77, 51, NULL, NULL, 'changeAllCommentRelease', 151, 152),
(78, 51, NULL, NULL, 'changeAllGradeRelease', 153, 154),
(79, 51, NULL, NULL, 'viewGroupSubmissionDetails', 155, 156),
(80, 51, NULL, NULL, 'reReleaseEvaluation', 157, 158),
(81, 51, NULL, NULL, 'viewSurveySummary', 159, 160),
(82, 51, NULL, NULL, 'export_rubic', 161, 162),
(83, 51, NULL, NULL, 'export_test', 163, 164),
(84, 51, NULL, NULL, 'pre', 165, 166),
(85, 51, NULL, NULL, 'checkDatabaseVersion', 167, 168),
(86, 51, NULL, NULL, 'extractModel', 169, 170),
(87, 51, NULL, NULL, 'checkAccess', 171, 172),
(88, 51, NULL, NULL, 'add', 173, 174),
(89, 51, NULL, NULL, 'edit', 175, 176),
(90, 51, NULL, NULL, 'delete', 177, 178),
(91, 1, NULL, NULL, 'Events', 180, 221),
(92, 91, NULL, NULL, 'postProcessData', 181, 182),
(93, 91, NULL, NULL, 'setUpAjaxList', 183, 184),
(94, 91, NULL, NULL, 'index', 185, 186),
(95, 91, NULL, NULL, 'ajaxList', 187, 188),
(96, 91, NULL, NULL, 'goToClassList', 189, 190),
(97, 91, NULL, NULL, 'view', 191, 192),
(98, 91, NULL, NULL, 'eventTemplatesList', 193, 194),
(99, 91, NULL, NULL, 'add', 195, 196),
(100, 91, NULL, NULL, 'edit', 197, 198),
(101, 91, NULL, NULL, 'editOld', 199, 200),
(102, 91, NULL, NULL, 'delete', 201, 202),
(103, 91, NULL, NULL, 'search', 203, 204),
(104, 91, NULL, NULL, 'checkDuplicateTitle', 205, 206),
(105, 91, NULL, NULL, 'viewGroups', 207, 208),
(106, 91, NULL, NULL, 'editGroup', 209, 210),
(107, 91, NULL, NULL, 'getAssignedGroups', 211, 212),
(108, 91, NULL, NULL, 'update', 213, 214),
(109, 91, NULL, NULL, 'checkDatabaseVersion', 215, 216),
(110, 91, NULL, NULL, 'extractModel', 217, 218),
(111, 91, NULL, NULL, 'checkAccess', 219, 220),
(112, 1, NULL, NULL, 'Framework', 222, 245),
(113, 112, NULL, NULL, 'calendarDisplay', 223, 224),
(114, 112, NULL, NULL, 'userInfoDisplay', 225, 226),
(115, 112, NULL, NULL, 'tutIndex', 227, 228),
(116, 112, NULL, NULL, 'checkDatabaseVersion', 229, 230),
(117, 112, NULL, NULL, 'extractModel', 231, 232),
(118, 112, NULL, NULL, 'checkAccess', 233, 234),
(119, 112, NULL, NULL, 'add', 235, 236),
(120, 112, NULL, NULL, 'edit', 237, 238),
(121, 112, NULL, NULL, 'index', 239, 240),
(122, 112, NULL, NULL, 'view', 241, 242),
(123, 112, NULL, NULL, 'delete', 243, 244),
(124, 1, NULL, NULL, 'Groups', 246, 285),
(125, 124, NULL, NULL, 'postProcess', 247, 248),
(126, 124, NULL, NULL, 'setUpAjaxList', 249, 250),
(127, 124, NULL, NULL, 'index', 251, 252),
(128, 124, NULL, NULL, 'ajaxList', 253, 254),
(129, 124, NULL, NULL, 'goToClassList', 255, 256),
(130, 124, NULL, NULL, 'view', 257, 258),
(131, 124, NULL, NULL, 'add', 259, 260),
(132, 124, NULL, NULL, 'edit', 261, 262),
(133, 124, NULL, NULL, 'delete', 263, 264),
(134, 124, NULL, NULL, 'checkDuplicateName', 265, 266),
(135, 124, NULL, NULL, 'getQueryAttribute', 267, 268),
(136, 124, NULL, NULL, 'import', 269, 270),
(137, 124, NULL, NULL, 'addGroupByImport', 271, 272),
(138, 124, NULL, NULL, 'update', 273, 274),
(139, 124, NULL, NULL, 'getFilteredStudent', 275, 276),
(140, 124, NULL, NULL, 'sendGroupEmail', 277, 278),
(141, 124, NULL, NULL, 'checkDatabaseVersion', 279, 280),
(142, 124, NULL, NULL, 'extractModel', 281, 282),
(143, 124, NULL, NULL, 'checkAccess', 283, 284),
(144, 1, NULL, NULL, 'Home', 286, 317),
(145, 144, NULL, NULL, 'createAro', 287, 288),
(146, 144, NULL, NULL, 'createPermissions', 289, 290),
(147, 144, NULL, NULL, 'createAcos', 291, 292),
(148, 144, NULL, NULL, 'index', 293, 294),
(149, 144, NULL, NULL, 'preparePeerEvals', 295, 296),
(150, 144, NULL, NULL, 'getEvaluation', 297, 298),
(151, 144, NULL, NULL, 'getSurveyEvaluation', 299, 300),
(152, 144, NULL, NULL, 'formatCourseList', 301, 302),
(153, 144, NULL, NULL, 'checkDatabaseVersion', 303, 304),
(154, 144, NULL, NULL, 'extractModel', 305, 306),
(155, 144, NULL, NULL, 'checkAccess', 307, 308),
(156, 144, NULL, NULL, 'add', 309, 310),
(157, 144, NULL, NULL, 'edit', 311, 312),
(158, 144, NULL, NULL, 'view', 313, 314),
(159, 144, NULL, NULL, 'delete', 315, 316),
(160, 1, NULL, NULL, 'Install', 318, 341),
(162, 160, NULL, NULL, 'index', 321, 322),
(163, 160, NULL, NULL, 'install2', 323, 324),
(164, 160, NULL, NULL, 'install3', 325, 326),
(165, 160, NULL, NULL, 'install4', 327, 328),
(166, 160, NULL, NULL, 'gpl', 329, 330),
(167, 160, NULL, NULL, 'manualdoc', 331, 332),
(168, 160, NULL, NULL, 'add', 333, 334),
(169, 160, NULL, NULL, 'edit', 335, 336),
(170, 160, NULL, NULL, 'view', 337, 338),
(171, 160, NULL, NULL, 'delete', 339, 340),
(172, 1, NULL, NULL, 'Loginout', 342, 371),
(173, 172, NULL, NULL, 'login', 343, 344),
(174, 172, NULL, NULL, 'loginByDefault', 345, 346),
(175, 172, NULL, NULL, 'loginByCWL', 347, 348),
(176, 172, NULL, NULL, 'clearSession', 349, 350),
(177, 172, NULL, NULL, 'logout', 351, 352),
(178, 172, NULL, NULL, 'forgot', 353, 354),
(179, 172, NULL, NULL, 'checkDatabaseVersion', 355, 356),
(180, 172, NULL, NULL, 'extractModel', 357, 358),
(181, 172, NULL, NULL, 'checkAccess', 359, 360),
(182, 172, NULL, NULL, 'add', 361, 362),
(183, 172, NULL, NULL, 'edit', 363, 364),
(184, 172, NULL, NULL, 'index', 365, 366),
(185, 172, NULL, NULL, 'view', 367, 368),
(186, 172, NULL, NULL, 'delete', 369, 370),
(187, 1, NULL, NULL, 'Mixevals', 372, 405),
(188, 187, NULL, NULL, 'postProcess', 373, 374),
(189, 187, NULL, NULL, 'setUpAjaxList', 375, 376),
(190, 187, NULL, NULL, 'index', 377, 378),
(191, 187, NULL, NULL, 'ajaxList', 379, 380),
(192, 187, NULL, NULL, 'view', 381, 382),
(193, 187, NULL, NULL, 'add', 383, 384),
(194, 187, NULL, NULL, 'edit', 385, 386),
(195, 187, NULL, NULL, 'copy', 387, 388),
(196, 187, NULL, NULL, 'delete', 389, 390),
(197, 187, NULL, NULL, 'previewMixeval', 391, 392),
(198, 187, NULL, NULL, 'renderRows', 393, 394),
(199, 187, NULL, NULL, 'printUserName', 395, 396),
(200, 187, NULL, NULL, 'update', 397, 398),
(201, 187, NULL, NULL, 'checkDatabaseVersion', 399, 400),
(202, 187, NULL, NULL, 'extractModel', 401, 402),
(203, 187, NULL, NULL, 'checkAccess', 403, 404),
(204, 1, NULL, NULL, 'Rubrics', 406, 437),
(205, 204, NULL, NULL, 'postProcess', 407, 408),
(206, 204, NULL, NULL, 'setUpAjaxList', 409, 410),
(207, 204, NULL, NULL, 'index', 411, 412),
(208, 204, NULL, NULL, 'ajaxList', 413, 414),
(209, 204, NULL, NULL, 'view', 415, 416),
(210, 204, NULL, NULL, 'add', 417, 418),
(211, 204, NULL, NULL, 'edit', 419, 420),
(212, 204, NULL, NULL, 'copy', 421, 422),
(213, 204, NULL, NULL, 'delete', 423, 424),
(214, 204, NULL, NULL, 'previewRubric', 425, 426),
(215, 204, NULL, NULL, 'renderRows', 427, 428),
(216, 204, NULL, NULL, 'update', 429, 430),
(217, 204, NULL, NULL, 'checkDatabaseVersion', 431, 432),
(218, 204, NULL, NULL, 'extractModel', 433, 434),
(219, 204, NULL, NULL, 'checkAccess', 435, 436),
(220, 1, NULL, NULL, 'Searchs', 438, 467),
(221, 220, NULL, NULL, 'index', 439, 440),
(222, 220, NULL, NULL, 'searchEvaluation', 441, 442),
(223, 220, NULL, NULL, 'searchResult', 443, 444),
(224, 220, NULL, NULL, 'searchInstructor', 445, 446),
(225, 220, NULL, NULL, 'display', 447, 448),
(226, 220, NULL, NULL, 'update', 449, 450),
(227, 220, NULL, NULL, 'eventBoxSearch', 451, 452),
(228, 220, NULL, NULL, 'checkDatabaseVersion', 453, 454),
(229, 220, NULL, NULL, 'extractModel', 455, 456),
(230, 220, NULL, NULL, 'checkAccess', 457, 458),
(231, 220, NULL, NULL, 'add', 459, 460),
(232, 220, NULL, NULL, 'edit', 461, 462),
(233, 220, NULL, NULL, 'view', 463, 464),
(234, 220, NULL, NULL, 'delete', 465, 466),
(235, 1, NULL, NULL, 'Simpleevaluations', 468, 497),
(236, 235, NULL, NULL, 'postProcess', 469, 470),
(237, 235, NULL, NULL, 'setUpAjaxList', 471, 472),
(238, 235, NULL, NULL, 'index', 473, 474),
(239, 235, NULL, NULL, 'ajaxList', 475, 476),
(240, 235, NULL, NULL, 'view', 477, 478),
(241, 235, NULL, NULL, 'add', 479, 480),
(242, 235, NULL, NULL, 'edit', 481, 482),
(243, 235, NULL, NULL, 'copy', 483, 484),
(244, 235, NULL, NULL, 'delete', 485, 486),
(245, 235, NULL, NULL, 'checkDuplicateTitle', 487, 488),
(246, 235, NULL, NULL, 'update', 489, 490),
(247, 235, NULL, NULL, 'checkDatabaseVersion', 491, 492),
(248, 235, NULL, NULL, 'extractModel', 493, 494),
(249, 235, NULL, NULL, 'checkAccess', 495, 496),
(250, 1, NULL, NULL, 'Surveygroups', 498, 539),
(251, 250, NULL, NULL, 'index', 499, 500),
(252, 250, NULL, NULL, 'listgroupssearch', 501, 502),
(253, 250, NULL, NULL, 'viewresult', 503, 504),
(254, 250, NULL, NULL, 'viewresultsearch', 505, 506),
(255, 250, NULL, NULL, 'makegroups', 507, 508),
(256, 250, NULL, NULL, 'makegroupssearch', 509, 510),
(257, 250, NULL, NULL, 'maketmgroups', 511, 512),
(258, 250, NULL, NULL, 'savegroups', 513, 514),
(259, 250, NULL, NULL, 'releasesurveygroupset', 515, 516),
(260, 250, NULL, NULL, 'deletesurveygroupset', 517, 518),
(261, 250, NULL, NULL, 'editgroupset', 519, 520),
(262, 250, NULL, NULL, 'changegroupset', 521, 522),
(263, 250, NULL, NULL, 'update', 523, 524),
(264, 250, NULL, NULL, 'checkDatabaseVersion', 525, 526),
(265, 250, NULL, NULL, 'extractModel', 527, 528),
(266, 250, NULL, NULL, 'checkAccess', 529, 530),
(267, 250, NULL, NULL, 'add', 531, 532),
(268, 250, NULL, NULL, 'edit', 533, 534),
(269, 250, NULL, NULL, 'view', 535, 536),
(270, 250, NULL, NULL, 'delete', 537, 538),
(271, 1, NULL, NULL, 'Surveys', 540, 581),
(272, 271, NULL, NULL, 'postProcess', 541, 542),
(273, 271, NULL, NULL, 'setUpAjaxList', 543, 544),
(274, 271, NULL, NULL, 'index', 545, 546),
(275, 271, NULL, NULL, 'ajaxList', 547, 548),
(276, 271, NULL, NULL, 'view', 549, 550),
(277, 271, NULL, NULL, 'questionssummary', 551, 552),
(278, 271, NULL, NULL, 'removequestion', 553, 554),
(279, 271, NULL, NULL, 'add', 555, 556),
(280, 271, NULL, NULL, 'addquestion', 557, 558),
(281, 271, NULL, NULL, 'edit', 559, 560),
(282, 271, NULL, NULL, 'copy', 561, 562),
(283, 271, NULL, NULL, 'editquestion', 563, 564),
(284, 271, NULL, NULL, 'delete', 565, 566),
(285, 271, NULL, NULL, 'adddelquestion', 567, 568),
(286, 271, NULL, NULL, 'checkDuplicateName', 569, 570),
(287, 271, NULL, NULL, 'releaseSurvey', 571, 572),
(288, 271, NULL, NULL, 'update', 573, 574),
(289, 271, NULL, NULL, 'checkDatabaseVersion', 575, 576),
(290, 271, NULL, NULL, 'extractModel', 577, 578),
(291, 271, NULL, NULL, 'checkAccess', 579, 580),
(292, 1, NULL, NULL, 'Sysfunctions', 582, 605),
(293, 292, NULL, NULL, 'setUpAjaxList', 583, 584),
(294, 292, NULL, NULL, 'index', 585, 586),
(295, 292, NULL, NULL, 'ajaxList', 587, 588),
(296, 292, NULL, NULL, 'view', 589, 590),
(297, 292, NULL, NULL, 'edit', 591, 592),
(298, 292, NULL, NULL, 'delete', 593, 594),
(299, 292, NULL, NULL, 'update', 595, 596),
(300, 292, NULL, NULL, 'checkDatabaseVersion', 597, 598),
(301, 292, NULL, NULL, 'extractModel', 599, 600),
(302, 292, NULL, NULL, 'checkAccess', 601, 602),
(303, 292, NULL, NULL, 'add', 603, 604),
(304, 1, NULL, NULL, 'Sysparameters', 606, 627),
(305, 304, NULL, NULL, 'setUpAjaxList', 607, 608),
(306, 304, NULL, NULL, 'index', 609, 610),
(307, 304, NULL, NULL, 'ajaxList', 611, 612),
(308, 304, NULL, NULL, 'view', 613, 614),
(309, 304, NULL, NULL, 'add', 615, 616),
(310, 304, NULL, NULL, 'edit', 617, 618),
(311, 304, NULL, NULL, 'delete', 619, 620),
(312, 304, NULL, NULL, 'checkDatabaseVersion', 621, 622),
(313, 304, NULL, NULL, 'extractModel', 623, 624),
(314, 304, NULL, NULL, 'checkAccess', 625, 626),
(315, 1, NULL, NULL, 'Upgrade', 628, 645),
(316, 315, NULL, NULL, 'preExecute', 629, 630),
(317, 315, NULL, NULL, 'index', 631, 632),
(318, 315, NULL, NULL, 'step2', 633, 634),
(319, 315, NULL, NULL, 'checkPermission', 635, 636),
(320, 315, NULL, NULL, 'add', 637, 638),
(321, 315, NULL, NULL, 'edit', 639, 640),
(322, 315, NULL, NULL, 'view', 641, 642),
(323, 315, NULL, NULL, 'delete', 643, 644),
(324, 1, NULL, NULL, 'Users', 646, 693),
(325, 324, NULL, NULL, 'login', 647, 648),
(326, 324, NULL, NULL, 'logout', 649, 650),
(327, 324, NULL, NULL, 'setUpAjaxList', 651, 652),
(328, 324, NULL, NULL, 'ajaxList', 653, 654),
(329, 324, NULL, NULL, 'index', 655, 656),
(330, 324, NULL, NULL, 'goToClassList', 657, 658),
(331, 324, NULL, NULL, 'view', 659, 660),
(332, 324, NULL, NULL, 'add', 661, 662),
(333, 324, NULL, NULL, 'edit', 663, 664),
(334, 324, NULL, NULL, 'editProfile', 665, 666),
(335, 324, NULL, NULL, 'delete', 667, 668),
(336, 324, NULL, NULL, 'drop', 669, 670),
(337, 324, NULL, NULL, 'checkDuplicateName', 671, 672),
(338, 324, NULL, NULL, 'resetPassword', 673, 674),
(339, 324, NULL, NULL, 'import', 675, 676),
(340, 324, NULL, NULL, 'addUserByImport', 677, 678),
(341, 324, NULL, NULL, 'getQueryAttribute', 679, 680),
(342, 324, NULL, NULL, 'update', 681, 682),
(343, 324, NULL, NULL, 'sendEmail', 683, 684),
(344, 324, NULL, NULL, 'nonRegisteredCourses', 685, 686),
(345, 324, NULL, NULL, 'checkDatabaseVersion', 687, 688),
(346, 324, NULL, NULL, 'extractModel', 689, 690),
(347, 324, NULL, NULL, 'checkAccess', 691, 692),
(348, NULL, NULL, NULL, 'functions', 695, 728),
(349, 348, NULL, NULL, 'user', 696, 717),
(350, 349, NULL, NULL, 'superadmin', 697, 698),
(351, 349, NULL, NULL, 'admin', 699, 700),
(352, 349, NULL, NULL, 'instructor', 701, 702),
(353, 349, NULL, NULL, 'student', 703, 704),
(354, 349, NULL, NULL, 'import', 705, 706),
(355, 349, NULL, NULL, 'password_reset', 707, 716),
(356, 355, NULL, NULL, 'superadmin', 708, 709),
(357, 355, NULL, NULL, 'admin', 710, 711),
(358, 355, NULL, NULL, 'instructor', 712, 713),
(359, 355, NULL, NULL, 'student', 714, 715),
(360, 348, NULL, NULL, 'role', 718, 727),
(361, 360, NULL, NULL, 'superadmin', 719, 720),
(362, 360, NULL, NULL, 'admin', 721, 722),
(363, 360, NULL, NULL, 'instructor', 723, 724),
(364, 360, NULL, NULL, 'student', 725, 726);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `aros`
--

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Role', 1, NULL, 1, 2),
(2, NULL, 'Role', 2, NULL, 3, 4),
(3, NULL, 'Role', 3, NULL, 5, 6),
(4, NULL, 'Role', 4, NULL, 7, 8);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 1, '1', '1', '1', '1'),
(2, 1, 348, '1', '1', '1', '1'),
(3, 2, 1, '-1', '-1', '-1', '-1'),
(4, 2, 144, '1', '1', '1', '1'),
(5, 2, 21, '1', '1', '1', '1'),
(6, 2, 324, '1', '1', '1', '1'),
(7, 2, 348, '-1', '-1', '-1', '-1'),
(8, 2, 349, '1', '1', '1', '1'),
(9, 2, 351, '-1', '-1', '-1', '-1'),
(10, 2, 350, '-1', '-1', '-1', '-1'),
(11, 3, 1, '-1', '-1', '-1', '-1'),
(12, 3, 144, '1', '1', '1', '1'),
(13, 3, 21, '1', '1', '1', '1'),
(14, 3, 324, '1', '1', '1', '1'),
(15, 3, 348, '-1', '-1', '-1', '-1'),
(16, 3, 349, '1', '1', '1', '1'),
(17, 3, 351, '-1', '-1', '-1', '-1'),
(18, 3, 350, '-1', '-1', '-1', '-1'),
(19, 3, 352, '-1', '-1', '-1', '-1'),
(20, 4, 1, '-1', '-1', '-1', '-1'),
(21, 4, 144, '1', '1', '1', '1'),
(22, 4, 21, '1', '1', '1', '1'),
(23, 4, 324, '-1', '-1', '-1', '-1'),
(24, 4, 348, '-1', '-1', '-1', '-1');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

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
  FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,  
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `sys_parameters`
--

INSERT INTO `sys_parameters` (`id`, `parameter_code`, `parameter_value`, `parameter_type`, `description`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
(1, 'system.soft_delete_enable', 'true', 'B', 'Whether soft deletion of records is enabled.', 'A', 0, '2010-07-27 16:42:17', 0, '2010-07-27 16:42:17'),
(2, 'system.debug_mode', '0', 'I', 'Debug Mode of the system', 'A', 0, '2010-07-27 16:42:17', NULL, '2011-08-09 21:22:59'),
(3, 'system.debug_verbosity', '1', 'I', NULL, 'A', 0, '2010-07-27 16:42:17', NULL, '2010-07-27 16:42:17'),
(4, 'system.absolute_url', 'http://', 'S', '', 'A', 0, '2010-07-27 16:42:17', NULL, '2011-08-09 21:22:59'),
(5, 'system.domain', 'test', 'S', '', 'A', 0, '2010-07-27 16:42:17', NULL, '2011-08-09 21:22:59'),
(6, 'system.super_admin', 'root', 'S', NULL, 'A', 0, '2010-07-27 16:42:17', NULL, '2011-08-09 21:22:59'),
(7, 'system.upload_dir', 'uploads/', 'S', NULL, 'A', 0, '2010-07-27 16:42:17', NULL, '2010-07-27 16:42:17'),
(8, 'display.contact_info', 'Please enter your custom contact info. HTML tabs are acceptable.', 'S', NULL, 'A', 0, '2010-07-27 16:42:17', NULL, '2011-08-09 21:22:59'),
(9, 'display.page_title', 'iPeer v2 with TeamMaker', 'S', 'Page title show in HTML.', 'A', 0, '2010-07-27 16:42:17', 0, '2010-07-27 16:42:17'),
(10, 'display.logo_file', 'LayoutLogoDefault.gif', 'S', 'Logo image name.', 'A', 0, '2010-07-27 16:42:17', 0, '2010-07-27 16:42:17'),
(11, 'display.login_logo_file', 'LayoutLoginLogoDefault.gif', 'S', 'Login Image File Name.', 'A', 0, '2010-07-27 16:42:17', 0, '2010-07-27 16:42:17'),
(12, 'display.login_text', '<a href=''http://www.ubc.ca'' target=''_blank''>UBC</a>', 'S', 'Login Image File Name.', 'A', 0, '2010-07-27 16:42:17', 0, '2011-08-09 21:22:59'),
(13, 'custom.login_control', 'ipeer', 'S', 'The login control for iPeer: ipeer; CWL: UBC_CWL', 'A', 0, '2010-07-27 16:42:17', NULL, '2010-10-08 15:48:12'),
(14, 'custom.login_page_pathname', 'custom_ubc_cwl_login', 'S', 'The file pathname for the custom login page; CWL:custom_ubc_cwl_login', 'A', 0, '2010-07-27 16:42:17', NULL, '2010-07-27 16:42:17'),
(15, 'system.admin_email', 'Please enter the iPeer administrator\\''s email address.', 'S', NULL, 'A', 0, '2010-07-27 16:42:17', NULL, '2011-08-09 21:22:59'),
(16, 'system.password_reset_mail', 'Dear <user>,<br> Your password has been reset to <newpassword>. Please use this to log in from now on. <br><br>iPeer Administrator', 'S', NULL, 'A', 0, '2010-07-27 16:42:17', NULL, '2010-07-27 16:42:17'),
(17, 'system.password_reset_emailsubject', 'iPeer Password Reset Notification', 'S', NULL, 'A', 0, '2010-07-27 16:42:17', NULL, '2011-08-09 21:22:59'),
(18, 'display.date_format', 'D, M j, Y g:i a', 'S', 'date format preference', 'A', 0, '2010-07-27 16:42:17', NULL, '2010-07-27 16:42:17'),
(20, 'database.version', '4', 'I', 'database version', 'A', 0, '2010-07-27 16:42:18', NULL, '2010-10-13 15:19:27'),
(21, 'email.port', '465', 'S', 'port number for email smtp option', 'A', '0', '2011-07-18 14:23:26', NULL , '2011-07-18 14:23:26'),
(22, 'email.host', 'email_host', 'S', 'host address for email smtp option', 'A', '0', '2011-07-18 14:23:26', NULL , '2011-07-18 14:23:26'),
(23 , 'email.username', 'your_email_address', 'S', 'username for email smtp option', 'A', '0', '2011-07-18 14:23:26', NULL , '2011-07-18 14:23:26'),
(24 , 'email.password', 'your_email_password', 'S', 'password for email smtp option', 'A', '0', '2011-07-18 14:23:26', NULL , '2011-07-18 14:23:26');


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

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

INSERT INTO `users` VALUES (2, 'I', 'poscar', '22bf1cbd965e66775fd973a30dcc4431', 'Peter', 'Oscar', NULL, 'Instructor', '', NULL, NULL, NULL, 'A', 1, '2006-06-19 16:25:24', NULL, '2006-06-19 16:25:24', NULL);
INSERT INTO `users` VALUES (3, 'I', 'paulmcbeth', '36d59a7ca1e0bbad18ea4f185d698e6b', 'McBeth', 'Paul', NULL, 'Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:02', NULL, '2006-06-20 14:17:02', NULL);
INSERT INTO `users` VALUES (4, 'I', 'emilylai', 'bebfc322b580a86124307f387454e968', 'Emily', 'Lai', NULL, 'Assistant Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:53', NULL, '2006-06-20 14:17:53', NULL);
INSERT INTO `users` VALUES (5, 'S', '65498451', '78c3c751d765acd1d9e0db2613c30598', 'Ed', 'Fidler', '65498451', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:19:18', NULL, '2006-06-20 14:19:18', NULL);
INSERT INTO `users` VALUES (6, 'S', '65468188', '123181add2761dcde157ee8acad0671f', 'Alex', 'Ng', '65468188', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:26:59', NULL, '2006-06-20 14:26:59', NULL);
INSERT INTO `users` VALUES (7, 'S', '98985481', 'da0ae9740be304173241f6d22bda88fc', 'Matt', 'Harper', '98985481', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:24', NULL, '2006-06-20 14:27:24', NULL);
INSERT INTO `users` VALUES (8, 'S', '16585158', '3482e702575e6539ebaf26d26c32d6a9', 'Chris', 'Leikermoser', '16585158', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:43', NULL, '2006-06-20 14:27:43', NULL);
INSERT INTO `users` VALUES (9, 'S', '81121651', '3e3006de9165ca68a1474d15b36ca61a', 'Johnny', 'Oshika', '81121651', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:08', NULL, '2006-06-20 14:28:08', NULL);
INSERT INTO `users` VALUES (10, 'S', '87800283', '033aa9b0a34277be46185e5f7897766d', 'Travis', 'Penno', '87800283', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:29', NULL, '2006-06-20 14:28:29', NULL);
INSERT INTO `users` VALUES (11, 'S', '68541180', '41ed4245fd127b23f644abdea8c983e8', 'Kelly', 'Sall', '68541180', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:49', NULL, '2006-06-20 14:28:49', NULL);
INSERT INTO `users` VALUES (12, 'S', '48451389', 'f953ddcfb7a7d0727731fea5443f1612', 'Peter', 'So', '48451389', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:29:07', NULL, '2006-06-20 14:29:07', NULL);
INSERT INTO `users` VALUES (13, 'S', '84188465', 'a2d928445b8ce0768ca60866db7ec4d8', 'Damien', 'Clapa', '84188465', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:31:17', NULL, '2006-06-20 14:31:17', NULL);
INSERT INTO `users` VALUES (14, 'S', '27701036', '9a8d62f8f051085faa1993801e359c26', 'Hajar', 'Abdollahi', '27701036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:47:34', NULL, '2006-06-20 14:47:34', NULL);
INSERT INTO `users` VALUES (15, 'S', '48877031', 'e9512f326fa60069295d55402c934ae9', 'Jennifer', 'Alloway', '48877031', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:00:35', NULL, '2006-06-20 15:00:35', NULL);
INSERT INTO `users` VALUES (16, 'S', '25731063', 'd809ef1ff75140842ebcea2eeb326a68', 'Chad', 'Amiel', '25731063', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:09', NULL, '2006-06-20 15:01:09', NULL);
INSERT INTO `users` VALUES (17, 'S', '37116036', '92473bbae508045b37b065d3059ef2fe', 'Edna', 'Ang', '37116036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:24', NULL, '2006-06-20 15:01:24', NULL);
INSERT INTO `users` VALUES (18, 'S', '76035030', 'a802acb2ff0fa49f0ab7220a548a90f2', 'Denny', 'Anggabrata', '76035030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:52', NULL, '2006-06-20 15:01:52', NULL);
INSERT INTO `users` VALUES (19, 'S', '90938044', 'd99df20a80253a2977db58e7d1e9e1a6', 'Jonathan', 'Appleton', '90938044', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:02:20', NULL, '2006-06-20 15:02:20', NULL);
INSERT INTO `users` VALUES (20, 'S', '88505045', '79fc2a62a52fb95a884336182760ca47', 'Soroush', 'Babaeian', '88505045', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:27', NULL, '2006-06-20 15:03:27', NULL);
INSERT INTO `users` VALUES (21, 'S', '22784037', '7bd663524c1c742e005e583c354a181c', 'Nicole', 'Babuick', '22784037', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:47', NULL, '2006-06-20 15:03:47', NULL);
INSERT INTO `users` VALUES (22, 'S', '37048022', 'a5ac7f4b57cfec2117292f65421fa8fd', 'Vivian', 'Baik', '37048022', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:04:22', NULL, '2006-06-20 15:04:22', NULL);
INSERT INTO `users` VALUES (23, 'S', '89947048', '44920ee1b7466dd14b3f05b9c230c765', 'Trevor', 'Bruce', '89947048', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:05:55', NULL, '2006-06-20 15:05:55', NULL);
INSERT INTO `users` VALUES (24, 'S', '39823059', '772a66f511a1ee98049c226581544006', 'Michael', 'Canning', '39823059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:20', NULL, '2006-06-20 15:06:20', NULL);
INSERT INTO `users` VALUES (25, 'S', '35644039', '333e126eb7eb77eedefbb9ac24c5e5bb', 'Steven', 'Catania', '35644039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:46', NULL, '2006-06-20 15:06:46', NULL);
INSERT INTO `users` VALUES (26, 'S', '19524032', 'e661d6bdab29994109b189363142977a', 'Bill', 'Chan', '19524032', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:01', NULL, '2006-06-20 15:07:01', NULL);
INSERT INTO `users` VALUES (27, 'S', '40289059', '455ca8f6b1ef46e1793308614581bf48', 'Van Hong', 'Dao', '40289059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:37', NULL, '2006-06-20 15:07:37', NULL);
INSERT INTO `users` VALUES (28, 'S', '38058020', '0800bd74b17181bd9461f02a84569b96', 'Michael', 'Davis', '38058020', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:04', NULL, '2006-06-20 15:08:04', NULL);
INSERT INTO `users` VALUES (29, 'S', '38861035', '4da8d3aa955eac6544d29ccdd30776f1', 'Jonathan', 'Funk', '38861035', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:31', NULL, '2006-06-20 15:08:31', NULL);
INSERT INTO `users` VALUES (30, 'S', '27879030', '46a7c75feecfdf87ce0b9a1796d7f369', 'Geoff', 'Howe', '27879030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:47', NULL, '2006-06-20 15:08:47', NULL);
INSERT INTO `users` VALUES (31, 'S', '10186039', '84c94d12e8b8629c4d4e37879bc7043b', 'Hui', 'Jing', '10186039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:16', NULL, '2006-06-20 15:10:16', NULL);
INSERT INTO `users` VALUES (32, 'S', '19803030', '8328f0ce00854e307cfeda1119e73d26', 'Bowinn', 'Ma', '19803030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:32', NULL, '2006-06-20 15:10:32', NULL);
INSERT INTO `users` VALUES (33, 'S', '51516498', '25cef707046231c80a9d4546744879db', 'Joe', 'Deon', '51516498', NULL, 'joe.deon@ubc.ca', NULL, NULL, NULL, 'A', 1, '2006-06-21 08:44:09', 33, '2006-06-21 08:45:00', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Constraints for table `user_grade_penalties`
--
ALTER TABLE `user_grade_penalties`
  ADD CONSTRAINT `fk_penalties` FOREIGN KEY (`penalty_id`) REFERENCES `penalties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
