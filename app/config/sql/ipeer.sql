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

INSERT INTO `acos` (id, parent_id, model, foreign_key, alias, lft, rght) VALUES
(1, NULL, NULL, NULL, 'adminpage', 1, 2),
(2, NULL, NULL, NULL, 'controllers', 3, 696),
(3, 2, NULL, NULL, 'Pages', 4, 19),
(4, 3, NULL, NULL, 'display', 5, 6),
(5, 3, NULL, NULL, 'extractModel', 7, 8),
(6, 3, NULL, NULL, 'add', 9, 10),
(7, 3, NULL, NULL, 'edit', 11, 12),
(8, 3, NULL, NULL, 'index', 13, 14),
(9, 3, NULL, NULL, 'view', 15, 16),
(10, 3, NULL, NULL, 'delete', 17, 18),
(11, 2, NULL, NULL, 'Courses', 20, 47),
(12, 11, NULL, NULL, 'daysLate', 21, 22),
(13, 11, NULL, NULL, 'index', 23, 24),
(14, 11, NULL, NULL, 'ajaxList', 25, 26),
(15, 11, NULL, NULL, 'view', 27, 28),
(16, 11, NULL, NULL, 'home', 29, 30),
(17, 11, NULL, NULL, 'add', 31, 32),
(18, 11, NULL, NULL, 'edit', 33, 34),
(19, 11, NULL, NULL, 'delete', 35, 36),
(20, 11, NULL, NULL, 'addInstructor', 37, 38),
(21, 11, NULL, NULL, 'deleteInstructor', 39, 40),
(22, 11, NULL, NULL, 'checkDuplicateName', 41, 42),
(23, 11, NULL, NULL, 'update', 43, 44),
(24, 11, NULL, NULL, 'extractModel', 45, 46),
(25, 2, NULL, NULL, 'Departments', 48, 61),
(26, 25, NULL, NULL, 'index', 49, 50),
(27, 25, NULL, NULL, 'view', 51, 52),
(28, 25, NULL, NULL, 'add', 53, 54),
(29, 25, NULL, NULL, 'edit', 55, 56),
(30, 25, NULL, NULL, 'delete', 57, 58),
(31, 25, NULL, NULL, 'extractModel', 59, 60),
(32, 2, NULL, NULL, 'Emailer', 62, 95),
(33, 32, NULL, NULL, 'setUpAjaxList', 63, 64),
(34, 32, NULL, NULL, 'ajaxList', 65, 66),
(35, 32, NULL, NULL, 'index', 67, 68),
(36, 32, NULL, NULL, 'write', 69, 70),
(37, 32, NULL, NULL, 'cancel', 71, 72),
(38, 32, NULL, NULL, 'view', 73, 74),
(39, 32, NULL, NULL, 'addRecipient', 75, 76),
(40, 32, NULL, NULL, 'deleteRecipient', 77, 78),
(41, 32, NULL, NULL, 'getRecipient', 79, 80),
(42, 32, NULL, NULL, 'searchByUserId', 81, 82),
(43, 32, NULL, NULL, 'doMerge', 83, 84),
(44, 32, NULL, NULL, 'send', 85, 86),
(45, 32, NULL, NULL, 'extractModel', 87, 88),
(46, 32, NULL, NULL, 'add', 89, 90),
(47, 32, NULL, NULL, 'edit', 91, 92),
(48, 32, NULL, NULL, 'delete', 93, 94),
(49, 2, NULL, NULL, 'Emailtemplates', 96, 117),
(50, 49, NULL, NULL, 'setUpAjaxList', 97, 98),
(51, 49, NULL, NULL, 'ajaxList', 99, 100),
(52, 49, NULL, NULL, 'index', 101, 102),
(53, 49, NULL, NULL, 'add', 103, 104),
(54, 49, NULL, NULL, 'edit', 105, 106),
(55, 49, NULL, NULL, 'delete', 107, 108),
(56, 49, NULL, NULL, 'view', 109, 110),
(57, 49, NULL, NULL, 'displayTemplateContent', 111, 112),
(58, 49, NULL, NULL, 'displayTemplateSubject', 113, 114),
(59, 49, NULL, NULL, 'extractModel', 115, 116),
(60, 2, NULL, NULL, 'Evaltools', 118, 133),
(61, 60, NULL, NULL, 'index', 119, 120),
(62, 60, NULL, NULL, 'showAll', 121, 122),
(63, 60, NULL, NULL, 'extractModel', 123, 124),
(64, 60, NULL, NULL, 'add', 125, 126),
(65, 60, NULL, NULL, 'edit', 127, 128),
(66, 60, NULL, NULL, 'view', 129, 130),
(67, 60, NULL, NULL, 'delete', 131, 132),
(68, 2, NULL, NULL, 'Evaluations', 134, 209),
(69, 68, NULL, NULL, 'postProcess', 135, 136),
(70, 68, NULL, NULL, 'setUpAjaxList', 137, 138),
(71, 68, NULL, NULL, 'ajaxList', 139, 140),
(72, 68, NULL, NULL, 'view', 141, 142),
(73, 68, NULL, NULL, 'index', 143, 144),
(74, 68, NULL, NULL, 'search', 145, 146),
(75, 68, NULL, NULL, 'update', 147, 148),
(76, 68, NULL, NULL, 'test', 149, 150),
(77, 68, NULL, NULL, 'export', 151, 152),
(78, 68, NULL, NULL, 'makeSimpleEvaluation', 153, 154),
(79, 68, NULL, NULL, 'validSimpleEvalComplete', 155, 156),
(80, 68, NULL, NULL, 'makeSurveyEvaluation', 157, 158),
(81, 68, NULL, NULL, 'validSurveyEvalComplete', 159, 160),
(82, 68, NULL, NULL, 'makeRubricEvaluation', 161, 162),
(83, 68, NULL, NULL, 'validRubricEvalComplete', 163, 164),
(84, 68, NULL, NULL, 'completeEvaluationRubric', 165, 166),
(85, 68, NULL, NULL, 'makeMixevalEvaluation', 167, 168),
(86, 68, NULL, NULL, 'validMixevalEvalComplete', 169, 170),
(87, 68, NULL, NULL, 'completeEvaluationMixeval', 171, 172),
(88, 68, NULL, NULL, 'viewEvaluationResults', 173, 174),
(89, 68, NULL, NULL, 'viewSurveyGroupEvaluationResults', 175, 176),
(90, 68, NULL, NULL, 'studentViewEvaluationResult', 177, 178),
(91, 68, NULL, NULL, 'markEventReviewed', 179, 180),
(92, 68, NULL, NULL, 'markGradeRelease', 181, 182),
(93, 68, NULL, NULL, 'markCommentRelease', 183, 184),
(94, 68, NULL, NULL, 'changeAllCommentRelease', 185, 186),
(95, 68, NULL, NULL, 'changeAllGradeRelease', 187, 188),
(96, 68, NULL, NULL, 'viewGroupSubmissionDetails', 189, 190),
(97, 68, NULL, NULL, 'reReleaseEvaluation', 191, 192),
(98, 68, NULL, NULL, 'viewSurveySummary', 193, 194),
(99, 68, NULL, NULL, 'export_rubic', 195, 196),
(100, 68, NULL, NULL, 'export_test', 197, 198),
(101, 68, NULL, NULL, 'pre', 199, 200),
(102, 68, NULL, NULL, 'extractModel', 201, 202),
(103, 68, NULL, NULL, 'add', 203, 204),
(104, 68, NULL, NULL, 'edit', 205, 206),
(105, 68, NULL, NULL, 'delete', 207, 208),
(106, 2, NULL, NULL, 'Events', 210, 243),
(107, 106, NULL, NULL, 'postProcessData', 211, 212),
(108, 106, NULL, NULL, 'setUpAjaxList', 213, 214),
(109, 106, NULL, NULL, 'index', 215, 216),
(110, 106, NULL, NULL, 'ajaxList', 217, 218),
(111, 106, NULL, NULL, 'view', 219, 220),
(112, 106, NULL, NULL, 'eventTemplatesList', 221, 222),
(113, 106, NULL, NULL, 'add', 223, 224),
(114, 106, NULL, NULL, 'edit', 225, 226),
(115, 106, NULL, NULL, 'delete', 227, 228),
(116, 106, NULL, NULL, 'search', 229, 230),
(117, 106, NULL, NULL, 'checkDuplicateTitle', 231, 232),
(118, 106, NULL, NULL, 'viewGroups', 233, 234),
(119, 106, NULL, NULL, 'editGroup', 235, 236),
(120, 106, NULL, NULL, 'getAssignedGroups', 237, 238),
(121, 106, NULL, NULL, 'update', 239, 240),
(122, 106, NULL, NULL, 'extractModel', 241, 242),
(123, 2, NULL, NULL, 'Faculties', 244, 257),
(124, 123, NULL, NULL, 'index', 245, 246),
(125, 123, NULL, NULL, 'view', 247, 248),
(126, 123, NULL, NULL, 'add', 249, 250),
(127, 123, NULL, NULL, 'edit', 251, 252),
(128, 123, NULL, NULL, 'delete', 253, 254),
(129, 123, NULL, NULL, 'extractModel', 255, 256),
(130, 2, NULL, NULL, 'Framework', 258, 277),
(131, 130, NULL, NULL, 'calendarDisplay', 259, 260),
(132, 130, NULL, NULL, 'userInfoDisplay', 261, 262),
(133, 130, NULL, NULL, 'tutIndex', 263, 264),
(134, 130, NULL, NULL, 'extractModel', 265, 266),
(135, 130, NULL, NULL, 'add', 267, 268),
(136, 130, NULL, NULL, 'edit', 269, 270),
(137, 130, NULL, NULL, 'index', 271, 272),
(138, 130, NULL, NULL, 'view', 273, 274),
(139, 130, NULL, NULL, 'delete', 275, 276),
(140, 2, NULL, NULL, 'Groups', 278, 313),
(141, 140, NULL, NULL, 'postProcess', 279, 280),
(142, 140, NULL, NULL, 'setUpAjaxList', 281, 282),
(143, 140, NULL, NULL, 'index', 283, 284),
(144, 140, NULL, NULL, 'ajaxList', 285, 286),
(145, 140, NULL, NULL, 'goToClassList', 287, 288),
(146, 140, NULL, NULL, 'view', 289, 290),
(147, 140, NULL, NULL, 'add', 291, 292),
(148, 140, NULL, NULL, 'edit', 293, 294),
(149, 140, NULL, NULL, 'delete', 295, 296),
(150, 140, NULL, NULL, 'checkDuplicateName', 297, 298),
(151, 140, NULL, NULL, 'getQueryAttribute', 299, 300),
(152, 140, NULL, NULL, 'import', 301, 302),
(153, 140, NULL, NULL, 'addGroupByImport', 303, 304),
(154, 140, NULL, NULL, 'update', 305, 306),
(155, 140, NULL, NULL, 'export', 307, 308),
(156, 140, NULL, NULL, 'sendGroupEmail', 309, 310),
(157, 140, NULL, NULL, 'extractModel', 311, 312),
(158, 2, NULL, NULL, 'Home', 314, 329),
(159, 158, NULL, NULL, 'index', 315, 316),
(160, 158, NULL, NULL, 'studentIndex', 317, 318),
(161, 158, NULL, NULL, 'extractModel', 319, 320),
(162, 158, NULL, NULL, 'add', 321, 322),
(163, 158, NULL, NULL, 'edit', 323, 324),
(164, 158, NULL, NULL, 'view', 325, 326),
(165, 158, NULL, NULL, 'delete', 327, 328),
(166, 2, NULL, NULL, 'Install', 330, 351),
(167, 166, NULL, NULL, 'index', 331, 332),
(168, 166, NULL, NULL, 'install2', 333, 334),
(169, 166, NULL, NULL, 'install3', 335, 336),
(170, 166, NULL, NULL, 'install4', 337, 338),
(171, 166, NULL, NULL, 'install5', 339, 340),
(172, 166, NULL, NULL, 'gpl', 341, 342),
(173, 166, NULL, NULL, 'add', 343, 344),
(174, 166, NULL, NULL, 'edit', 345, 346),
(175, 166, NULL, NULL, 'view', 347, 348),
(176, 166, NULL, NULL, 'delete', 349, 350),
(177, 2, NULL, NULL, 'Lti', 352, 365),
(178, 177, NULL, NULL, 'index', 353, 354),
(179, 177, NULL, NULL, 'extractModel', 355, 356),
(180, 177, NULL, NULL, 'add', 357, 358),
(181, 177, NULL, NULL, 'edit', 359, 360),
(182, 177, NULL, NULL, 'view', 361, 362),
(183, 177, NULL, NULL, 'delete', 363, 364),
(184, 2, NULL, NULL, 'Mixevals', 366, 397),
(185, 184, NULL, NULL, 'postProcess', 367, 368),
(186, 184, NULL, NULL, 'setUpAjaxList', 369, 370),
(187, 184, NULL, NULL, 'index', 371, 372),
(188, 184, NULL, NULL, 'ajaxList', 373, 374),
(189, 184, NULL, NULL, 'view', 375, 376),
(190, 184, NULL, NULL, 'add', 377, 378),
(191, 184, NULL, NULL, 'deleteQuestion', 379, 380),
(192, 184, NULL, NULL, 'deleteDescriptor', 381, 382),
(193, 184, NULL, NULL, 'edit', 383, 384),
(194, 184, NULL, NULL, 'copy', 385, 386),
(195, 184, NULL, NULL, 'delete', 387, 388),
(196, 184, NULL, NULL, 'previewMixeval', 389, 390),
(197, 184, NULL, NULL, 'renderRows', 391, 392),
(198, 184, NULL, NULL, 'update', 393, 394),
(199, 184, NULL, NULL, 'extractModel', 395, 396),
(200, 2, NULL, NULL, 'Penalty', 398, 413),
(201, 200, NULL, NULL, 'save', 399, 400),
(202, 200, NULL, NULL, 'extractModel', 401, 402),
(203, 200, NULL, NULL, 'add', 403, 404),
(204, 200, NULL, NULL, 'edit', 405, 406),
(205, 200, NULL, NULL, 'index', 407, 408),
(206, 200, NULL, NULL, 'view', 409, 410),
(207, 200, NULL, NULL, 'delete', 411, 412),
(208, 2, NULL, NULL, 'Rubrics', 414, 437),
(209, 208, NULL, NULL, 'postProcess', 415, 416),
(210, 208, NULL, NULL, 'setUpAjaxList', 417, 418),
(211, 208, NULL, NULL, 'index', 419, 420),
(212, 208, NULL, NULL, 'ajaxList', 421, 422),
(213, 208, NULL, NULL, 'view', 423, 424),
(214, 208, NULL, NULL, 'add', 425, 426),
(215, 208, NULL, NULL, 'edit', 427, 428),
(216, 208, NULL, NULL, 'copy', 429, 430),
(217, 208, NULL, NULL, 'delete', 431, 432),
(218, 208, NULL, NULL, 'setForm_RubricName', 433, 434),
(219, 208, NULL, NULL, 'extractModel', 435, 436),
(220, 2, NULL, NULL, 'Searchs', 438, 467),
(221, 220, NULL, NULL, 'update', 439, 440),
(222, 220, NULL, NULL, 'index', 441, 442),
(223, 220, NULL, NULL, 'searchEvaluation', 443, 444),
(224, 220, NULL, NULL, 'searchResult', 445, 446),
(225, 220, NULL, NULL, 'searchInstructor', 447, 448),
(226, 220, NULL, NULL, 'eventBoxSearch', 449, 450),
(227, 220, NULL, NULL, 'formatSearchEvaluation', 451, 452),
(228, 220, NULL, NULL, 'formatSearchInstructor', 453, 454),
(229, 220, NULL, NULL, 'formatSearchEvaluationResult', 455, 456),
(230, 220, NULL, NULL, 'extractModel', 457, 458),
(231, 220, NULL, NULL, 'add', 459, 460),
(232, 220, NULL, NULL, 'edit', 461, 462),
(233, 220, NULL, NULL, 'view', 463, 464),
(234, 220, NULL, NULL, 'delete', 465, 466),
(235, 2, NULL, NULL, 'Simpleevaluations', 468, 489),
(236, 235, NULL, NULL, 'postProcess', 469, 470),
(237, 235, NULL, NULL, 'setUpAjaxList', 471, 472),
(238, 235, NULL, NULL, 'index', 473, 474),
(239, 235, NULL, NULL, 'ajaxList', 475, 476),
(240, 235, NULL, NULL, 'view', 477, 478),
(241, 235, NULL, NULL, 'add', 479, 480),
(242, 235, NULL, NULL, 'edit', 481, 482),
(243, 235, NULL, NULL, 'copy', 483, 484),
(244, 235, NULL, NULL, 'delete', 485, 486),
(245, 235, NULL, NULL, 'extractModel', 487, 488),
(246, 2, NULL, NULL, 'Surveygroups', 490, 523),
(247, 246, NULL, NULL, 'postProcess', 491, 492),
(248, 246, NULL, NULL, 'setUpAjaxList', 493, 494),
(249, 246, NULL, NULL, 'index', 495, 496),
(250, 246, NULL, NULL, 'ajaxList', 497, 498),
(251, 246, NULL, NULL, 'viewresult', 499, 500),
(252, 246, NULL, NULL, 'makegroups', 501, 502),
(253, 246, NULL, NULL, 'makegroupssearch', 503, 504),
(254, 246, NULL, NULL, 'maketmgroups', 505, 506),
(255, 246, NULL, NULL, 'savegroups', 507, 508),
(256, 246, NULL, NULL, 'release', 509, 510),
(257, 246, NULL, NULL, 'delete', 511, 512),
(258, 246, NULL, NULL, 'edit', 513, 514),
(259, 246, NULL, NULL, 'changegroupset', 515, 516),
(260, 246, NULL, NULL, 'extractModel', 517, 518),
(261, 246, NULL, NULL, 'add', 519, 520),
(262, 246, NULL, NULL, 'view', 521, 522),
(263, 2, NULL, NULL, 'Surveys', 524, 559),
(264, 263, NULL, NULL, 'setUpAjaxList', 525, 526),
(265, 263, NULL, NULL, 'index', 527, 528),
(266, 263, NULL, NULL, 'ajaxList', 529, 530),
(267, 263, NULL, NULL, 'view', 531, 532),
(268, 263, NULL, NULL, 'add', 533, 534),
(269, 263, NULL, NULL, 'edit', 535, 536),
(270, 263, NULL, NULL, 'copy', 537, 538),
(271, 263, NULL, NULL, 'delete', 539, 540),
(272, 263, NULL, NULL, 'checkDuplicateName', 541, 542),
(273, 263, NULL, NULL, 'releaseSurvey', 543, 544),
(274, 263, NULL, NULL, 'questionsSummary', 545, 546),
(275, 263, NULL, NULL, 'moveQuestion', 547, 548),
(276, 263, NULL, NULL, 'removeQuestion', 549, 550),
(277, 263, NULL, NULL, 'addQuestion', 551, 552),
(278, 263, NULL, NULL, 'editQuestion', 553, 554),
(279, 263, NULL, NULL, 'update', 555, 556),
(280, 263, NULL, NULL, 'extractModel', 557, 558),
(281, 2, NULL, NULL, 'Sysfunctions', 560, 579),
(282, 281, NULL, NULL, 'setUpAjaxList', 561, 562),
(283, 281, NULL, NULL, 'index', 563, 564),
(284, 281, NULL, NULL, 'ajaxList', 565, 566),
(285, 281, NULL, NULL, 'view', 567, 568),
(286, 281, NULL, NULL, 'edit', 569, 570),
(287, 281, NULL, NULL, 'delete', 571, 572),
(288, 281, NULL, NULL, 'update', 573, 574),
(289, 281, NULL, NULL, 'extractModel', 575, 576),
(290, 281, NULL, NULL, 'add', 577, 578),
(291, 2, NULL, NULL, 'Sysparameters', 580, 597),
(292, 291, NULL, NULL, 'setUpAjaxList', 581, 582),
(293, 291, NULL, NULL, 'index', 583, 584),
(294, 291, NULL, NULL, 'ajaxList', 585, 586),
(295, 291, NULL, NULL, 'view', 587, 588),
(296, 291, NULL, NULL, 'add', 589, 590),
(297, 291, NULL, NULL, 'edit', 591, 592),
(298, 291, NULL, NULL, 'delete', 593, 594),
(299, 291, NULL, NULL, 'extractModel', 595, 596),
(300, 2, NULL, NULL, 'Upgrade', 598, 615),
(301, 300, NULL, NULL, 'index', 599, 600),
(302, 300, NULL, NULL, 'step2', 601, 602),
(303, 300, NULL, NULL, 'checkPermission', 603, 604),
(304, 300, NULL, NULL, 'extractModel', 605, 606),
(305, 300, NULL, NULL, 'add', 607, 608),
(306, 300, NULL, NULL, 'edit', 609, 610),
(307, 300, NULL, NULL, 'view', 611, 612),
(308, 300, NULL, NULL, 'delete', 613, 614),
(309, 2, NULL, NULL, 'Users', 616, 655),
(310, 309, NULL, NULL, 'login', 617, 618),
(311, 309, NULL, NULL, 'logout', 619, 620),
(312, 309, NULL, NULL, 'ajaxList', 621, 622),
(313, 309, NULL, NULL, 'index', 623, 624),
(314, 309, NULL, NULL, 'goToClassList', 625, 626),
(315, 309, NULL, NULL, 'determineIfStudentFromThisData', 627, 628),
(316, 309, NULL, NULL, 'getSimpleEntrollmentLists', 629, 630),
(317, 309, NULL, NULL, 'view', 631, 632),
(318, 309, NULL, NULL, 'add', 633, 634),
(319, 309, NULL, NULL, 'edit', 635, 636),
(320, 309, NULL, NULL, 'editProfile', 637, 638),
(321, 309, NULL, NULL, 'delete', 639, 640),
(322, 309, NULL, NULL, 'checkDuplicateName', 641, 642),
(323, 309, NULL, NULL, 'resetPassword', 643, 644),
(324, 309, NULL, NULL, 'import', 645, 646),
(325, 309, NULL, NULL, 'importPreview', 647, 648),
(326, 309, NULL, NULL, 'importFile', 649, 650),
(327, 309, NULL, NULL, 'update', 651, 652),
(328, 309, NULL, NULL, 'extractModel', 653, 654),
(329, 2, NULL, NULL, 'V1', 656, 675),
(330, 329, NULL, NULL, 'users', 657, 658),
(331, 329, NULL, NULL, 'courses', 659, 660),
(332, 329, NULL, NULL, 'groups', 661, 662),
(333, 329, NULL, NULL, 'grades', 663, 664),
(334, 329, NULL, NULL, 'add', 665, 666),
(335, 329, NULL, NULL, 'edit', 667, 668),
(336, 329, NULL, NULL, 'index', 669, 670),
(337, 329, NULL, NULL, 'view', 671, 672),
(338, 329, NULL, NULL, 'delete', 673, 674),
(339, 2, NULL, NULL, 'Guard', 676, 695),
(340, 339, NULL, NULL, 'Guard', 677, 694),
(341, 340, NULL, NULL, 'login', 678, 679),
(342, 340, NULL, NULL, 'logout', 680, 681),
(343, 340, NULL, NULL, 'extractModel', 682, 683),
(344, 340, NULL, NULL, 'add', 684, 685),
(345, 340, NULL, NULL, 'edit', 686, 687),
(346, 340, NULL, NULL, 'index', 688, 689),
(347, 340, NULL, NULL, 'view', 690, 691),
(348, 340, NULL, NULL, 'delete', 692, 693),
(349, NULL, NULL, NULL, 'functions', 697, 752),
(350, 349, NULL, NULL, 'user', 698, 723),
(351, 350, NULL, NULL, 'superadmin', 699, 700),
(352, 350, NULL, NULL, 'admin', 701, 702),
(353, 350, NULL, NULL, 'instructor', 703, 704),
(354, 350, NULL, NULL, 'tutor', 705, 706),
(355, 350, NULL, NULL, 'student', 707, 708),
(356, 350, NULL, NULL, 'import', 709, 710),
(357, 350, NULL, NULL, 'password_reset', 711, 722),
(358, 357, NULL, NULL, 'superadmin', 712, 713),
(359, 357, NULL, NULL, 'admin', 714, 715),
(360, 357, NULL, NULL, 'instructor', 716, 717),
(361, 357, NULL, NULL, 'tutor', 718, 719),
(362, 357, NULL, NULL, 'student', 720, 721),
(363, 349, NULL, NULL, 'role', 724, 735),
(364, 363, NULL, NULL, 'superadmin', 725, 726),
(365, 363, NULL, NULL, 'admin', 727, 728),
(366, 363, NULL, NULL, 'instructor', 729, 730),
(367, 363, NULL, NULL, 'tutor', 731, 732),
(368, 363, NULL, NULL, 'student', 733, 734),
(369, 349, NULL, NULL, 'evaluation', 736, 739),
(370, 369, NULL, NULL, 'export', 737, 738),
(371, 349, NULL, NULL, 'email', 740, 747),
(372, 371, NULL, NULL, 'allUsers', 741, 742),
(373, 371, NULL, NULL, 'allGroups', 743, 744),
(374, 371, NULL, NULL, 'allCourses', 745, 746),
(375, 349, NULL, NULL, 'emailtemplate', 748, 749),
(376, 349, NULL, NULL, 'viewstudentresults', 750, 751);

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
(2, 1, 349, '1', '1', '1', '1'),
(3, 1, 60, '1', '1', '1', '1'),
(4, 1, 235, '1', '1', '1', '1'),
(5, 1, 208, '1', '1', '1', '1'),
(6, 1, 184, '1', '1', '1', '1'),
(7, 1, 263, '1', '1', '1', '1'),
(8, 1, 32, '1', '1', '1', '1'),
(9, 1, 49, '1', '1', '1', '1'),
(10, 1, 106, '1', '1', '1', '1'),
(11, 1, 123, '1', '1', '1', '1'),
(12, 1, 25, '1', '1', '1', '1'),
(13, 1, 140, '1', '1', '1', '1'),
(14, 1, 1, '1', '1', '1', '1'),
(15, 2, 2, '-1', '-1', '-1', '-1'),
(16, 2, 158, '1', '1', '1', '1'),
(17, 2, 11, '1', '1', '1', '1'),
(18, 2, 25, '1', '1', '1', '1'),
(19, 2, 32, '1', '1', '1', '1'),
(20, 2, 49, '1', '1', '1', '1'),
(21, 2, 60, '1', '1', '1', '1'),
(22, 2, 106, '1', '1', '1', '1'),
(23, 2, 140, '1', '1', '1', '1'),
(24, 2, 184, '1', '1', '1', '1'),
(25, 2, 208, '1', '1', '1', '1'),
(26, 2, 235, '1', '1', '1', '1'),
(27, 2, 263, '1', '1', '1', '1'),
(28, 2, 309, '1', '1', '1', '1'),
(29, 2, 349, '-1', '-1', '-1', '-1'),
(30, 2, 371, '1', '1', '1', '1'),
(31, 2, 375, '1', '1', '1', '1'),
(32, 2, 369, '1', '1', '1', '1'),
(33, 2, 350, '1', '1', '1', '1'),
(34, 2, 352, '-1', '1', '-1', '-1'),
(35, 2, 351, '-1', '-1', '-1', '-1'),
(36, 2, 1, '1', '1', '1', '1'),
(37, 3, 2, '-1', '-1', '-1', '-1'),
(38, 3, 158, '1', '1', '1', '1'),
(39, 3, 11, '1', '1', '1', '1'),
(40, 3, 17, '-1', '-1', '-1', '-1'),
(41, 3, 18, '-1', '-1', '-1', '-1'),
(42, 3, 32, '1', '1', '1', '1'),
(43, 3, 49, '1', '1', '1', '1'),
(44, 3, 60, '1', '1', '1', '1'),
(45, 3, 106, '1', '1', '1', '1'),
(46, 3, 140, '1', '1', '1', '1'),
(47, 3, 184, '1', '1', '1', '1'),
(48, 3, 208, '1', '1', '1', '1'),
(49, 3, 235, '1', '1', '1', '1'),
(50, 3, 263, '1', '1', '1', '1'),
(51, 3, 309, '1', '1', '1', '1'),
(52, 3, 349, '-1', '-1', '-1', '-1'),
(53, 3, 371, '1', '1', '1', '1'),
(54, 3, 372, '-1', '-1', '-1', '-1'),
(55, 3, 373, '-1', '-1', '-1', '-1'),
(56, 3, 374, '-1', '-1', '-1', '-1'),
(57, 3, 369, '1', '1', '-1', '-1'),
(58, 3, 350, '1', '1', '1', '1'),
(59, 3, 352, '-1', '-1', '-1', '-1'),
(60, 3, 351, '-1', '-1', '-1', '-1'),
(61, 3, 353, '-1', '1', '-1', '-1'),
(62, 4, 2, '-1', '-1', '-1', '-1'),
(63, 4, 158, '1', '1', '1', '1'),
(64, 4, 11, '-1', '-1', '-1', '-1'),
(65, 4, 32, '-1', '-1', '-1', '-1'),
(66, 4, 49, '-1', '-1', '-1', '-1'),
(67, 4, 60, '-1', '-1', '-1', '-1'),
(68, 4, 106, '-1', '-1', '-1', '-1'),
(69, 4, 140, '-1', '-1', '-1', '-1'),
(70, 4, 184, '-1', '-1', '-1', '-1'),
(71, 4, 208, '-1', '-1', '-1', '-1'),
(72, 4, 235, '-1', '-1', '-1', '-1'),
(73, 4, 263, '-1', '-1', '-1', '-1'),
(74, 4, 309, '-1', '-1', '-1', '-1'),
(75, 4, 349, '-1', '-1', '-1', '-1'),
(76, 5, 2, '-1', '-1', '-1', '-1'),
(77, 5, 158, '1', '1', '1', '1'),
(78, 5, 11, '-1', '-1', '-1', '-1'),
(79, 5, 32, '-1', '-1', '-1', '-1'),
(80, 5, 49, '-1', '-1', '-1', '-1'),
(81, 5, 60, '-1', '-1', '-1', '-1'),
(82, 5, 106, '-1', '-1', '-1', '-1'),
(83, 5, 140, '-1', '-1', '-1', '-1'),
(84, 5, 184, '-1', '-1', '-1', '-1'),
(85, 5, 208, '-1', '-1', '-1', '-1'),
(86, 5, 235, '-1', '-1', '-1', '-1'),
(87, 5, 263, '-1', '-1', '-1', '-1'),
(88, 5, 309, '-1', '-1', '-1', '-1'),
(89, 5, 349, '-1', '-1', '-1', '-1'),
(90, 5, 376, '1', '1', '1', '1');

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
  `access_right` varchar(1) NOT NULL DEFAULT 'O',
  `record_status` varchar(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  UNIQUE KEY `course_id` (`course_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_index` (`user_id`)
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

