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

INSERT INTO acos (id, parent_id, model, foreign_key, alias, lft, rght) VALUES
(1, NULL, NULL, NULL, 'adminpage', 1, 2),
(2, NULL, NULL, NULL, 'controllers', 3, 728),
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
(68, 2, NULL, NULL, 'Evaluations', 134, 207),
(69, 68, NULL, NULL, 'postProcess', 135, 136),
(70, 68, NULL, NULL, 'setUpAjaxList', 137, 138),
(71, 68, NULL, NULL, 'ajaxList', 139, 140),
(72, 68, NULL, NULL, 'view', 141, 142),
(73, 68, NULL, NULL, 'index', 143, 144),
(74, 68, NULL, NULL, 'update', 145, 146),
(75, 68, NULL, NULL, 'test', 147, 148),
(76, 68, NULL, NULL, 'export', 149, 150),
(77, 68, NULL, NULL, 'makeSimpleEvaluation', 151, 152),
(78, 68, NULL, NULL, 'validSimpleEvalComplete', 153, 154),
(79, 68, NULL, NULL, 'makeSurveyEvaluation', 155, 156),
(80, 68, NULL, NULL, 'validSurveyEvalComplete', 157, 158),
(81, 68, NULL, NULL, 'makeRubricEvaluation', 159, 160),
(82, 68, NULL, NULL, 'validRubricEvalComplete', 161, 162),
(83, 68, NULL, NULL, 'completeEvaluationRubric', 163, 164),
(84, 68, NULL, NULL, 'makeMixevalEvaluation', 165, 166),
(85, 68, NULL, NULL, 'validMixevalEvalComplete', 167, 168),
(86, 68, NULL, NULL, 'completeEvaluationMixeval', 169, 170),
(87, 68, NULL, NULL, 'viewEvaluationResults', 171, 172),
(88, 68, NULL, NULL, 'viewSurveyGroupEvaluationResults', 173, 174),
(89, 68, NULL, NULL, 'studentViewEvaluationResult', 175, 176),
(90, 68, NULL, NULL, 'markEventReviewed', 177, 178),
(91, 68, NULL, NULL, 'markGradeRelease', 179, 180),
(92, 68, NULL, NULL, 'markCommentRelease', 181, 182),
(93, 68, NULL, NULL, 'changeAllCommentRelease', 183, 184),
(94, 68, NULL, NULL, 'changeAllGradeRelease', 185, 186),
(95, 68, NULL, NULL, 'viewGroupSubmissionDetails', 187, 188),
(96, 68, NULL, NULL, 'reReleaseEvaluation', 189, 190),
(97, 68, NULL, NULL, 'viewSurveySummary', 191, 192),
(98, 68, NULL, NULL, 'export_rubic', 193, 194),
(99, 68, NULL, NULL, 'export_test', 195, 196),
(100, 68, NULL, NULL, 'pre', 197, 198),
(101, 68, NULL, NULL, 'extractModel', 199, 200),
(102, 68, NULL, NULL, 'add', 201, 202),
(103, 68, NULL, NULL, 'edit', 203, 204),
(104, 68, NULL, NULL, 'delete', 205, 206),
(105, 2, NULL, NULL, 'Events', 208, 241),
(106, 105, NULL, NULL, 'postProcessData', 209, 210),
(107, 105, NULL, NULL, 'setUpAjaxList', 211, 212),
(108, 105, NULL, NULL, 'index', 213, 214),
(109, 105, NULL, NULL, 'ajaxList', 215, 216),
(110, 105, NULL, NULL, 'view', 217, 218),
(111, 105, NULL, NULL, 'eventTemplatesList', 219, 220),
(112, 105, NULL, NULL, 'add', 221, 222),
(113, 105, NULL, NULL, 'edit', 223, 224),
(114, 105, NULL, NULL, 'delete', 225, 226),
(115, 105, NULL, NULL, 'search', 227, 228),
(116, 105, NULL, NULL, 'checkDuplicateTitle', 229, 230),
(117, 105, NULL, NULL, 'viewGroups', 231, 232),
(118, 105, NULL, NULL, 'editGroup', 233, 234),
(119, 105, NULL, NULL, 'getAssignedGroups', 235, 236),
(120, 105, NULL, NULL, 'update', 237, 238),
(121, 105, NULL, NULL, 'extractModel', 239, 240),
(122, 2, NULL, NULL, 'Faculties', 242, 255),
(123, 122, NULL, NULL, 'index', 243, 244),
(124, 122, NULL, NULL, 'view', 245, 246),
(125, 122, NULL, NULL, 'add', 247, 248),
(126, 122, NULL, NULL, 'edit', 249, 250),
(127, 122, NULL, NULL, 'delete', 251, 252),
(128, 122, NULL, NULL, 'extractModel', 253, 254),
(129, 2, NULL, NULL, 'Framework', 256, 275),
(130, 129, NULL, NULL, 'calendarDisplay', 257, 258),
(131, 129, NULL, NULL, 'userInfoDisplay', 259, 260),
(132, 129, NULL, NULL, 'tutIndex', 261, 262),
(133, 129, NULL, NULL, 'extractModel', 263, 264),
(134, 129, NULL, NULL, 'add', 265, 266),
(135, 129, NULL, NULL, 'edit', 267, 268),
(136, 129, NULL, NULL, 'index', 269, 270),
(137, 129, NULL, NULL, 'view', 271, 272),
(138, 129, NULL, NULL, 'delete', 273, 274),
(139, 2, NULL, NULL, 'Groups', 276, 311),
(140, 139, NULL, NULL, 'postProcess', 277, 278),
(141, 139, NULL, NULL, 'setUpAjaxList', 279, 280),
(142, 139, NULL, NULL, 'index', 281, 282),
(143, 139, NULL, NULL, 'ajaxList', 283, 284),
(144, 139, NULL, NULL, 'goToClassList', 285, 286),
(145, 139, NULL, NULL, 'view', 287, 288),
(146, 139, NULL, NULL, 'add', 289, 290),
(147, 139, NULL, NULL, 'edit', 291, 292),
(148, 139, NULL, NULL, 'delete', 293, 294),
(149, 139, NULL, NULL, 'checkDuplicateName', 295, 296),
(150, 139, NULL, NULL, 'getQueryAttribute', 297, 298),
(151, 139, NULL, NULL, 'import', 299, 300),
(152, 139, NULL, NULL, 'addGroupByImport', 301, 302),
(153, 139, NULL, NULL, 'update', 303, 304),
(154, 139, NULL, NULL, 'export', 305, 306),
(155, 139, NULL, NULL, 'sendGroupEmail', 307, 308),
(156, 139, NULL, NULL, 'extractModel', 309, 310),
(157, 2, NULL, NULL, 'Home', 312, 327),
(158, 157, NULL, NULL, 'index', 313, 314),
(159, 157, NULL, NULL, 'studentIndex', 315, 316),
(160, 157, NULL, NULL, 'extractModel', 317, 318),
(161, 157, NULL, NULL, 'add', 319, 320),
(162, 157, NULL, NULL, 'edit', 321, 322),
(163, 157, NULL, NULL, 'view', 323, 324),
(164, 157, NULL, NULL, 'delete', 325, 326),
(165, 2, NULL, NULL, 'Install', 328, 349),
(166, 165, NULL, NULL, 'index', 329, 330),
(167, 165, NULL, NULL, 'install2', 331, 332),
(168, 165, NULL, NULL, 'install3', 333, 334),
(169, 165, NULL, NULL, 'install4', 335, 336),
(170, 165, NULL, NULL, 'install5', 337, 338),
(171, 165, NULL, NULL, 'gpl', 339, 340),
(172, 165, NULL, NULL, 'add', 341, 342),
(173, 165, NULL, NULL, 'edit', 343, 344),
(174, 165, NULL, NULL, 'view', 345, 346),
(175, 165, NULL, NULL, 'delete', 347, 348),
(176, 2, NULL, NULL, 'Lti', 350, 363),
(177, 176, NULL, NULL, 'index', 351, 352),
(178, 176, NULL, NULL, 'extractModel', 353, 354),
(179, 176, NULL, NULL, 'add', 355, 356),
(180, 176, NULL, NULL, 'edit', 357, 358),
(181, 176, NULL, NULL, 'view', 359, 360),
(182, 176, NULL, NULL, 'delete', 361, 362),
(183, 2, NULL, NULL, 'Mixevals', 364, 395),
(184, 183, NULL, NULL, 'postProcess', 365, 366),
(185, 183, NULL, NULL, 'setUpAjaxList', 367, 368),
(186, 183, NULL, NULL, 'index', 369, 370),
(187, 183, NULL, NULL, 'ajaxList', 371, 372),
(188, 183, NULL, NULL, 'view', 373, 374),
(189, 183, NULL, NULL, 'add', 375, 376),
(190, 183, NULL, NULL, 'deleteQuestion', 377, 378),
(191, 183, NULL, NULL, 'deleteDescriptor', 379, 380),
(192, 183, NULL, NULL, 'edit', 381, 382),
(193, 183, NULL, NULL, 'copy', 383, 384),
(194, 183, NULL, NULL, 'delete', 385, 386),
(195, 183, NULL, NULL, 'previewMixeval', 387, 388),
(196, 183, NULL, NULL, 'renderRows', 389, 390),
(197, 183, NULL, NULL, 'update', 391, 392),
(198, 183, NULL, NULL, 'extractModel', 393, 394),
(199, 2, NULL, NULL, 'OauthClients', 396, 409),
(200, 199, NULL, NULL, 'index', 397, 398),
(201, 199, NULL, NULL, 'add', 399, 400),
(202, 199, NULL, NULL, 'edit', 401, 402),
(203, 199, NULL, NULL, 'delete', 403, 404),
(204, 199, NULL, NULL, 'extractModel', 405, 406),
(205, 199, NULL, NULL, 'view', 407, 408),
(206, 2, NULL, NULL, 'OauthTokens', 410, 423),
(207, 206, NULL, NULL, 'index', 411, 412),
(208, 206, NULL, NULL, 'add', 413, 414),
(209, 206, NULL, NULL, 'edit', 415, 416),
(210, 206, NULL, NULL, 'delete', 417, 418),
(211, 206, NULL, NULL, 'extractModel', 419, 420),
(212, 206, NULL, NULL, 'view', 421, 422),
(213, 2, NULL, NULL, 'Penalty', 424, 439),
(214, 213, NULL, NULL, 'save', 425, 426),
(215, 213, NULL, NULL, 'extractModel', 427, 428),
(216, 213, NULL, NULL, 'add', 429, 430),
(217, 213, NULL, NULL, 'edit', 431, 432),
(218, 213, NULL, NULL, 'index', 433, 434),
(219, 213, NULL, NULL, 'view', 435, 436),
(220, 213, NULL, NULL, 'delete', 437, 438),
(221, 2, NULL, NULL, 'Rubrics', 440, 463),
(222, 221, NULL, NULL, 'postProcess', 441, 442),
(223, 221, NULL, NULL, 'setUpAjaxList', 443, 444),
(224, 221, NULL, NULL, 'index', 445, 446),
(225, 221, NULL, NULL, 'ajaxList', 447, 448),
(226, 221, NULL, NULL, 'view', 449, 450),
(227, 221, NULL, NULL, 'add', 451, 452),
(228, 221, NULL, NULL, 'edit', 453, 454),
(229, 221, NULL, NULL, 'copy', 455, 456),
(230, 221, NULL, NULL, 'delete', 457, 458),
(231, 221, NULL, NULL, 'setForm_RubricName', 459, 460),
(232, 221, NULL, NULL, 'extractModel', 461, 462),
(233, 2, NULL, NULL, 'Searchs', 464, 493),
(234, 233, NULL, NULL, 'update', 465, 466),
(235, 233, NULL, NULL, 'index', 467, 468),
(236, 233, NULL, NULL, 'searchEvaluation', 469, 470),
(237, 233, NULL, NULL, 'searchResult', 471, 472),
(238, 233, NULL, NULL, 'searchInstructor', 473, 474),
(239, 233, NULL, NULL, 'eventBoxSearch', 475, 476),
(240, 233, NULL, NULL, 'formatSearchEvaluation', 477, 478),
(241, 233, NULL, NULL, 'formatSearchInstructor', 479, 480),
(242, 233, NULL, NULL, 'formatSearchEvaluationResult', 481, 482),
(243, 233, NULL, NULL, 'extractModel', 483, 484),
(244, 233, NULL, NULL, 'add', 485, 486),
(245, 233, NULL, NULL, 'edit', 487, 488),
(246, 233, NULL, NULL, 'view', 489, 490),
(247, 233, NULL, NULL, 'delete', 491, 492),
(248, 2, NULL, NULL, 'Simpleevaluations', 494, 515),
(249, 248, NULL, NULL, 'postProcess', 495, 496),
(250, 248, NULL, NULL, 'setUpAjaxList', 497, 498),
(251, 248, NULL, NULL, 'index', 499, 500),
(252, 248, NULL, NULL, 'ajaxList', 501, 502),
(253, 248, NULL, NULL, 'view', 503, 504),
(254, 248, NULL, NULL, 'add', 505, 506),
(255, 248, NULL, NULL, 'edit', 507, 508),
(256, 248, NULL, NULL, 'copy', 509, 510),
(257, 248, NULL, NULL, 'delete', 511, 512),
(258, 248, NULL, NULL, 'extractModel', 513, 514),
(259, 2, NULL, NULL, 'Surveygroups', 516, 549),
(260, 259, NULL, NULL, 'postProcess', 517, 518),
(261, 259, NULL, NULL, 'setUpAjaxList', 519, 520),
(262, 259, NULL, NULL, 'index', 521, 522),
(263, 259, NULL, NULL, 'ajaxList', 523, 524),
(264, 259, NULL, NULL, 'viewresult', 525, 526),
(265, 259, NULL, NULL, 'makegroups', 527, 528),
(266, 259, NULL, NULL, 'makegroupssearch', 529, 530),
(267, 259, NULL, NULL, 'maketmgroups', 531, 532),
(268, 259, NULL, NULL, 'savegroups', 533, 534),
(269, 259, NULL, NULL, 'release', 535, 536),
(270, 259, NULL, NULL, 'delete', 537, 538),
(271, 259, NULL, NULL, 'edit', 539, 540),
(272, 259, NULL, NULL, 'changegroupset', 541, 542),
(273, 259, NULL, NULL, 'extractModel', 543, 544),
(274, 259, NULL, NULL, 'add', 545, 546),
(275, 259, NULL, NULL, 'view', 547, 548),
(276, 2, NULL, NULL, 'Surveys', 550, 585),
(277, 276, NULL, NULL, 'setUpAjaxList', 551, 552),
(278, 276, NULL, NULL, 'index', 553, 554),
(279, 276, NULL, NULL, 'ajaxList', 555, 556),
(280, 276, NULL, NULL, 'view', 557, 558),
(281, 276, NULL, NULL, 'add', 559, 560),
(282, 276, NULL, NULL, 'edit', 561, 562),
(283, 276, NULL, NULL, 'copy', 563, 564),
(284, 276, NULL, NULL, 'delete', 565, 566),
(285, 276, NULL, NULL, 'checkDuplicateName', 567, 568),
(286, 276, NULL, NULL, 'releaseSurvey', 569, 570),
(287, 276, NULL, NULL, 'questionsSummary', 571, 572),
(288, 276, NULL, NULL, 'moveQuestion', 573, 574),
(289, 276, NULL, NULL, 'removeQuestion', 575, 576),
(290, 276, NULL, NULL, 'addQuestion', 577, 578),
(291, 276, NULL, NULL, 'editQuestion', 579, 580),
(292, 276, NULL, NULL, 'update', 581, 582),
(293, 276, NULL, NULL, 'extractModel', 583, 584),
(294, 2, NULL, NULL, 'Sysfunctions', 586, 605),
(295, 294, NULL, NULL, 'setUpAjaxList', 587, 588),
(296, 294, NULL, NULL, 'index', 589, 590),
(297, 294, NULL, NULL, 'ajaxList', 591, 592),
(298, 294, NULL, NULL, 'view', 593, 594),
(299, 294, NULL, NULL, 'edit', 595, 596),
(300, 294, NULL, NULL, 'delete', 597, 598),
(301, 294, NULL, NULL, 'update', 599, 600),
(302, 294, NULL, NULL, 'extractModel', 601, 602),
(303, 294, NULL, NULL, 'add', 603, 604),
(304, 2, NULL, NULL, 'Sysparameters', 606, 623),
(305, 304, NULL, NULL, 'setUpAjaxList', 607, 608),
(306, 304, NULL, NULL, 'index', 609, 610),
(307, 304, NULL, NULL, 'ajaxList', 611, 612),
(308, 304, NULL, NULL, 'view', 613, 614),
(309, 304, NULL, NULL, 'add', 615, 616),
(310, 304, NULL, NULL, 'edit', 617, 618),
(311, 304, NULL, NULL, 'delete', 619, 620),
(312, 304, NULL, NULL, 'extractModel', 621, 622),
(313, 2, NULL, NULL, 'Upgrade', 624, 641),
(314, 313, NULL, NULL, 'index', 625, 626),
(315, 313, NULL, NULL, 'step2', 627, 628),
(316, 313, NULL, NULL, 'checkPermission', 629, 630),
(317, 313, NULL, NULL, 'extractModel', 631, 632),
(318, 313, NULL, NULL, 'add', 633, 634),
(319, 313, NULL, NULL, 'edit', 635, 636),
(320, 313, NULL, NULL, 'view', 637, 638),
(321, 313, NULL, NULL, 'delete', 639, 640),
(322, 2, NULL, NULL, 'Users', 642, 681),
(323, 322, NULL, NULL, 'login', 643, 644),
(324, 322, NULL, NULL, 'logout', 645, 646),
(325, 322, NULL, NULL, 'ajaxList', 647, 648),
(326, 322, NULL, NULL, 'index', 649, 650),
(327, 322, NULL, NULL, 'goToClassList', 651, 652),
(328, 322, NULL, NULL, 'determineIfStudentFromThisData', 653, 654),
(329, 322, NULL, NULL, 'getSimpleEntrollmentLists', 655, 656),
(330, 322, NULL, NULL, 'view', 657, 658),
(331, 322, NULL, NULL, 'add', 659, 660),
(332, 322, NULL, NULL, 'edit', 661, 662),
(333, 322, NULL, NULL, 'editProfile', 663, 664),
(334, 322, NULL, NULL, 'delete', 665, 666),
(335, 322, NULL, NULL, 'checkDuplicateName', 667, 668),
(336, 322, NULL, NULL, 'resetPassword', 669, 670),
(337, 322, NULL, NULL, 'import', 671, 672),
(338, 322, NULL, NULL, 'importPreview', 673, 674),
(339, 322, NULL, NULL, 'importFile', 675, 676),
(340, 322, NULL, NULL, 'update', 677, 678),
(341, 322, NULL, NULL, 'extractModel', 679, 680),
(342, 2, NULL, NULL, 'V1', 682, 707),
(343, 342, NULL, NULL, 'oauth', 683, 684),
(344, 342, NULL, NULL, 'oauth_error', 685, 686),
(345, 342, NULL, NULL, 'users', 687, 688),
(346, 342, NULL, NULL, 'courses', 689, 690),
(347, 342, NULL, NULL, 'groups', 691, 692),
(348, 342, NULL, NULL, 'events', 693, 694),
(349, 342, NULL, NULL, 'grades', 695, 696),
(350, 342, NULL, NULL, 'add', 697, 698),
(351, 342, NULL, NULL, 'edit', 699, 700),
(352, 342, NULL, NULL, 'index', 701, 702),
(353, 342, NULL, NULL, 'view', 703, 704),
(354, 342, NULL, NULL, 'delete', 705, 706),
(355, 2, NULL, NULL, 'Guard', 708, 727),
(356, 355, NULL, NULL, 'Guard', 709, 726),
(357, 356, NULL, NULL, 'login', 710, 711),
(358, 356, NULL, NULL, 'logout', 712, 713),
(359, 356, NULL, NULL, 'extractModel', 714, 715),
(360, 356, NULL, NULL, 'add', 716, 717),
(361, 356, NULL, NULL, 'edit', 718, 719),
(362, 356, NULL, NULL, 'index', 720, 721),
(363, 356, NULL, NULL, 'view', 722, 723),
(364, 356, NULL, NULL, 'delete', 724, 725),
(365, NULL, NULL, NULL, 'functions', 729, 788),
(366, 365, NULL, NULL, 'user', 730, 757),
(367, 366, NULL, NULL, 'superadmin', 731, 732),
(368, 366, NULL, NULL, 'admin', 733, 734),
(369, 366, NULL, NULL, 'instructor', 735, 736),
(370, 366, NULL, NULL, 'tutor', 737, 738),
(371, 366, NULL, NULL, 'student', 739, 740),
(372, 366, NULL, NULL, 'import', 741, 742),
(373, 366, NULL, NULL, 'password_reset', 743, 754),
(374, 373, NULL, NULL, 'superadmin', 744, 745),
(375, 373, NULL, NULL, 'admin', 746, 747),
(376, 373, NULL, NULL, 'instructor', 748, 749),
(377, 373, NULL, NULL, 'tutor', 750, 751),
(378, 373, NULL, NULL, 'student', 752, 753),
(379, 366, NULL, NULL, 'index', 755, 756),
(380, 365, NULL, NULL, 'role', 758, 769),
(381, 380, NULL, NULL, 'superadmin', 759, 760),
(382, 380, NULL, NULL, 'admin', 761, 762),
(383, 380, NULL, NULL, 'instructor', 763, 764),
(384, 380, NULL, NULL, 'tutor', 765, 766),
(385, 380, NULL, NULL, 'student', 767, 768),
(386, 365, NULL, NULL, 'evaluation', 770, 773),
(387, 386, NULL, NULL, 'export', 771, 772),
(388, 365, NULL, NULL, 'email', 774, 781),
(389, 388, NULL, NULL, 'allUsers', 775, 776),
(390, 388, NULL, NULL, 'allGroups', 777, 778),
(391, 388, NULL, NULL, 'allCourses', 779, 780),
(392, 365, NULL, NULL, 'emailtemplate', 782, 783),
(393, 365, NULL, NULL, 'viewstudentresults', 784, 785),
(394, 365, NULL, NULL, 'viewemailaddresses', 786, 787),
(395, NULL, NULL, NULL, 'superadmin', 789, 790),
(396, NULL, NULL, NULL, 'onlytakeeval', 791, 792);



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
(2, 1, 365, '1', '1', '1', '1'),
(3, 1, 60, '1', '1', '1', '1'),
(4, 1, 248, '1', '1', '1', '1'),
(5, 1, 221, '1', '1', '1', '1'),
(6, 1, 183, '1', '1', '1', '1'),
(7, 1, 276, '1', '1', '1', '1'),
(8, 1, 32, '1', '1', '1', '1'),
(9, 1, 49, '1', '1', '1', '1'),
(10, 1, 105, '1', '1', '1', '1'),
(11, 1, 122, '1', '1', '1', '1'),
(12, 1, 25, '1', '1', '1', '1'),
(13, 1, 139, '1', '1', '1', '1'),
(14, 1, 1, '1', '1', '1', '1'),
(15, 1, 394, '-1', '-1', '-1', '-1'),
(16, 1, 395, '1', '1', '1', '1'),
(17, 2, 2, '-1', '-1', '-1', '-1'),
(18, 2, 157, '1', '1', '1', '1'),
(19, 2, 11, '1', '1', '1', '1'),
(20, 2, 25, '1', '1', '1', '1'),
(21, 2, 32, '1', '1', '1', '1'),
(22, 2, 49, '1', '1', '1', '1'),
(23, 2, 60, '1', '1', '1', '1'),
(24, 2, 105, '1', '1', '1', '1'),
(25, 2, 139, '1', '1', '1', '1'),
(26, 2, 183, '1', '1', '1', '1'),
(27, 2, 221, '1', '1', '1', '1'),
(28, 2, 248, '1', '1', '1', '1'),
(29, 2, 276, '1', '1', '1', '1'),
(30, 2, 322, '1', '1', '1', '1'),
(31, 2, 68, '1', '1', '1', '1'),
(32, 2, 365, '-1', '-1', '-1', '-1'),
(33, 2, 392, '1', '1', '1', '1'),
(34, 2, 386, '1', '1', '1', '1'),
(35, 2, 389, '1', '1', '1', '1'),
(36, 2, 366, '1', '1', '1', '1'),
(37, 2, 368, '-1', '1', '-1', '-1'),
(38, 2, 367, '-1', '-1', '-1', '-1'),
(39, 2, 1, '1', '1', '1', '1'),
(40, 2, 394, '-1', '-1', '-1', '-1'),
(41, 2, 395, '-1', '-1', '-1', '-1'),
(42, 3, 2, '-1', '-1', '-1', '-1'),
(43, 3, 157, '1', '1', '1', '1'),
(44, 3, 11, '1', '1', '1', '1'),
(45, 3, 17, '-1', '-1', '-1', '-1'),
(46, 3, 18, '-1', '-1', '-1', '-1'),
(47, 3, 32, '1', '1', '1', '1'),
(48, 3, 49, '1', '1', '1', '1'),
(49, 3, 60, '1', '1', '1', '1'),
(50, 3, 105, '1', '1', '1', '1'),
(51, 3, 139, '1', '1', '1', '1'),
(52, 3, 183, '1', '1', '1', '1'),
(53, 3, 221, '1', '1', '1', '1'),
(54, 3, 248, '1', '1', '1', '1'),
(55, 3, 276, '1', '1', '1', '1'),
(56, 3, 322, '1', '1', '1', '1'),
(57, 3, 365, '-1', '-1', '-1', '-1'),
(58, 3, 386, '1', '1', '-1', '-1'),
(59, 3, 366, '1', '1', '1', '1'),
(60, 3, 368, '-1', '-1', '-1', '-1'),
(61, 3, 367, '-1', '-1', '-1', '-1'),
(62, 3, 369, '-1', '1', '-1', '-1'),
(63, 3, 379, '-1', '-1', '-1', '-1'),
(64, 3, 394, '-1', '-1', '-1', '-1'),
(65, 3, 395, '-1', '-1', '-1', '-1'),
(66, 4, 2, '-1', '-1', '-1', '-1'),
(67, 4, 157, '1', '1', '1', '1'),
(68, 4, 11, '-1', '-1', '-1', '-1'),
(69, 4, 32, '-1', '-1', '-1', '-1'),
(70, 4, 49, '-1', '-1', '-1', '-1'),
(71, 4, 60, '-1', '-1', '-1', '-1'),
(72, 4, 105, '-1', '-1', '-1', '-1'),
(73, 4, 139, '-1', '-1', '-1', '-1'),
(74, 4, 183, '-1', '-1', '-1', '-1'),
(75, 4, 221, '-1', '-1', '-1', '-1'),
(76, 4, 248, '-1', '-1', '-1', '-1'),
(77, 4, 276, '-1', '-1', '-1', '-1'),
(78, 4, 322, '-1', '-1', '-1', '-1'),
(79, 4, 365, '-1', '-1', '-1', '-1'),
(80, 4, 394, '-1', '-1', '-1', '-1'),
(81, 4, 395, '-1', '-1', '-1', '-1'),
(82, 4, 396, '1', '1', '1', '1'),
(83, 5, 2, '-1', '-1', '-1', '-1'),
(84, 5, 157, '1', '1', '1', '1'),
(85, 5, 11, '-1', '-1', '-1', '-1'),
(86, 5, 32, '-1', '-1', '-1', '-1'),
(87, 5, 49, '-1', '-1', '-1', '-1'),
(88, 5, 60, '-1', '-1', '-1', '-1'),
(89, 5, 105, '-1', '-1', '-1', '-1'),
(90, 5, 139, '-1', '-1', '-1', '-1'),
(91, 5, 183, '-1', '-1', '-1', '-1'),
(92, 5, 221, '-1', '-1', '-1', '-1'),
(93, 5, 248, '-1', '-1', '-1', '-1'),
(94, 5, 276, '-1', '-1', '-1', '-1'),
(95, 5, 322, '-1', '-1', '-1', '-1'),
(96, 5, 365, '-1', '-1', '-1', '-1'),
(97, 5, 393, '1', '1', '1', '1'),
(98, 5, 394, '-1', '-1', '-1', '-1'),
(99, 5, 395, '-1', '-1', '-1', '-1'),
(100, 5, 396, '1', '1', '1', '1');


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

CREATE TABLE `oauth_clients` (
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

CREATE TABLE `oauth_nonces` (
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

CREATE TABLE `oauth_tokens` (
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

CREATE TABLE `penalties` (
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


