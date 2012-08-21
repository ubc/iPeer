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
(2, NULL, NULL, NULL, 'controllers', 3, 726),
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
(68, 2, NULL, NULL, 'Evaluations', 134, 205),
(69, 68, NULL, NULL, 'postProcess', 135, 136),
(70, 68, NULL, NULL, 'setUpAjaxList', 137, 138),
(71, 68, NULL, NULL, 'ajaxList', 139, 140),
(72, 68, NULL, NULL, 'view', 141, 142),
(73, 68, NULL, NULL, 'index', 143, 144),
(74, 68, NULL, NULL, 'update', 145, 146),
(75, 68, NULL, NULL, 'export', 147, 148),
(76, 68, NULL, NULL, 'makeSimpleEvaluation', 149, 150),
(77, 68, NULL, NULL, 'validSimpleEvalComplete', 151, 152),
(78, 68, NULL, NULL, 'makeSurveyEvaluation', 153, 154),
(79, 68, NULL, NULL, 'validSurveyEvalComplete', 155, 156),
(80, 68, NULL, NULL, 'makeRubricEvaluation', 157, 158),
(81, 68, NULL, NULL, 'validRubricEvalComplete', 159, 160),
(82, 68, NULL, NULL, 'completeEvaluationRubric', 161, 162),
(83, 68, NULL, NULL, 'makeMixevalEvaluation', 163, 164),
(84, 68, NULL, NULL, 'validMixevalEvalComplete', 165, 166),
(85, 68, NULL, NULL, 'completeEvaluationMixeval', 167, 168),
(86, 68, NULL, NULL, 'viewEvaluationResults', 169, 170),
(87, 68, NULL, NULL, 'viewSurveyGroupEvaluationResults', 171, 172),
(88, 68, NULL, NULL, 'studentViewEvaluationResult', 173, 174),
(89, 68, NULL, NULL, 'markEventReviewed', 175, 176),
(90, 68, NULL, NULL, 'markGradeRelease', 177, 178),
(91, 68, NULL, NULL, 'markCommentRelease', 179, 180),
(92, 68, NULL, NULL, 'changeAllCommentRelease', 181, 182),
(93, 68, NULL, NULL, 'changeAllGradeRelease', 183, 184),
(94, 68, NULL, NULL, 'viewGroupSubmissionDetails', 185, 186),
(95, 68, NULL, NULL, 'reReleaseEvaluation', 187, 188),
(96, 68, NULL, NULL, 'viewSurveySummary', 189, 190),
(97, 68, NULL, NULL, 'export_rubic', 191, 192),
(98, 68, NULL, NULL, 'export_test', 193, 194),
(99, 68, NULL, NULL, 'pre', 195, 196),
(100, 68, NULL, NULL, 'extractModel', 197, 198),
(101, 68, NULL, NULL, 'add', 199, 200),
(102, 68, NULL, NULL, 'edit', 201, 202),
(103, 68, NULL, NULL, 'delete', 203, 204),
(104, 2, NULL, NULL, 'Events', 206, 239),
(105, 104, NULL, NULL, 'postProcessData', 207, 208),
(106, 104, NULL, NULL, 'setUpAjaxList', 209, 210),
(107, 104, NULL, NULL, 'index', 211, 212),
(108, 104, NULL, NULL, 'ajaxList', 213, 214),
(109, 104, NULL, NULL, 'view', 215, 216),
(110, 104, NULL, NULL, 'eventTemplatesList', 217, 218),
(111, 104, NULL, NULL, 'add', 219, 220),
(112, 104, NULL, NULL, 'edit', 221, 222),
(113, 104, NULL, NULL, 'delete', 223, 224),
(114, 104, NULL, NULL, 'search', 225, 226),
(115, 104, NULL, NULL, 'checkDuplicateTitle', 227, 228),
(116, 104, NULL, NULL, 'viewGroups', 229, 230),
(117, 104, NULL, NULL, 'editGroup', 231, 232),
(118, 104, NULL, NULL, 'getAssignedGroups', 233, 234),
(119, 104, NULL, NULL, 'update', 235, 236),
(120, 104, NULL, NULL, 'extractModel', 237, 238),
(121, 2, NULL, NULL, 'Faculties', 240, 253),
(122, 121, NULL, NULL, 'index', 241, 242),
(123, 121, NULL, NULL, 'view', 243, 244),
(124, 121, NULL, NULL, 'add', 245, 246),
(125, 121, NULL, NULL, 'edit', 247, 248),
(126, 121, NULL, NULL, 'delete', 249, 250),
(127, 121, NULL, NULL, 'extractModel', 251, 252),
(128, 2, NULL, NULL, 'Framework', 254, 273),
(129, 128, NULL, NULL, 'calendarDisplay', 255, 256),
(130, 128, NULL, NULL, 'userInfoDisplay', 257, 258),
(131, 128, NULL, NULL, 'tutIndex', 259, 260),
(132, 128, NULL, NULL, 'extractModel', 261, 262),
(133, 128, NULL, NULL, 'add', 263, 264),
(134, 128, NULL, NULL, 'edit', 265, 266),
(135, 128, NULL, NULL, 'index', 267, 268),
(136, 128, NULL, NULL, 'view', 269, 270),
(137, 128, NULL, NULL, 'delete', 271, 272),
(138, 2, NULL, NULL, 'Groups', 274, 309),
(139, 138, NULL, NULL, 'postProcess', 275, 276),
(140, 138, NULL, NULL, 'setUpAjaxList', 277, 278),
(141, 138, NULL, NULL, 'index', 279, 280),
(142, 138, NULL, NULL, 'ajaxList', 281, 282),
(143, 138, NULL, NULL, 'goToClassList', 283, 284),
(144, 138, NULL, NULL, 'view', 285, 286),
(145, 138, NULL, NULL, 'add', 287, 288),
(146, 138, NULL, NULL, 'edit', 289, 290),
(147, 138, NULL, NULL, 'delete', 291, 292),
(148, 138, NULL, NULL, 'checkDuplicateName', 293, 294),
(149, 138, NULL, NULL, 'getQueryAttribute', 295, 296),
(150, 138, NULL, NULL, 'import', 297, 298),
(151, 138, NULL, NULL, 'addGroupByImport', 299, 300),
(152, 138, NULL, NULL, 'update', 301, 302),
(153, 138, NULL, NULL, 'export', 303, 304),
(154, 138, NULL, NULL, 'sendGroupEmail', 305, 306),
(155, 138, NULL, NULL, 'extractModel', 307, 308),
(156, 2, NULL, NULL, 'Home', 310, 325),
(157, 156, NULL, NULL, 'index', 311, 312),
(158, 156, NULL, NULL, 'studentIndex', 313, 314),
(159, 156, NULL, NULL, 'extractModel', 315, 316),
(160, 156, NULL, NULL, 'add', 317, 318),
(161, 156, NULL, NULL, 'edit', 319, 320),
(162, 156, NULL, NULL, 'view', 321, 322),
(163, 156, NULL, NULL, 'delete', 323, 324),
(164, 2, NULL, NULL, 'Install', 326, 347),
(165, 164, NULL, NULL, 'index', 327, 328),
(166, 164, NULL, NULL, 'install2', 329, 330),
(167, 164, NULL, NULL, 'install3', 331, 332),
(168, 164, NULL, NULL, 'install4', 333, 334),
(169, 164, NULL, NULL, 'install5', 335, 336),
(170, 164, NULL, NULL, 'gpl', 337, 338),
(171, 164, NULL, NULL, 'add', 339, 340),
(172, 164, NULL, NULL, 'edit', 341, 342),
(173, 164, NULL, NULL, 'view', 343, 344),
(174, 164, NULL, NULL, 'delete', 345, 346),
(175, 2, NULL, NULL, 'Lti', 348, 361),
(176, 175, NULL, NULL, 'index', 349, 350),
(177, 175, NULL, NULL, 'extractModel', 351, 352),
(178, 175, NULL, NULL, 'add', 353, 354),
(179, 175, NULL, NULL, 'edit', 355, 356),
(180, 175, NULL, NULL, 'view', 357, 358),
(181, 175, NULL, NULL, 'delete', 359, 360),
(182, 2, NULL, NULL, 'Mixevals', 362, 393),
(183, 182, NULL, NULL, 'postProcess', 363, 364),
(184, 182, NULL, NULL, 'setUpAjaxList', 365, 366),
(185, 182, NULL, NULL, 'index', 367, 368),
(186, 182, NULL, NULL, 'ajaxList', 369, 370),
(187, 182, NULL, NULL, 'view', 371, 372),
(188, 182, NULL, NULL, 'add', 373, 374),
(189, 182, NULL, NULL, 'deleteQuestion', 375, 376),
(190, 182, NULL, NULL, 'deleteDescriptor', 377, 378),
(191, 182, NULL, NULL, 'edit', 379, 380),
(192, 182, NULL, NULL, 'copy', 381, 382),
(193, 182, NULL, NULL, 'delete', 383, 384),
(194, 182, NULL, NULL, 'previewMixeval', 385, 386),
(195, 182, NULL, NULL, 'renderRows', 387, 388),
(196, 182, NULL, NULL, 'update', 389, 390),
(197, 182, NULL, NULL, 'extractModel', 391, 392),
(198, 2, NULL, NULL, 'OauthClients', 394, 407),
(199, 198, NULL, NULL, 'index', 395, 396),
(200, 198, NULL, NULL, 'add', 397, 398),
(201, 198, NULL, NULL, 'edit', 399, 400),
(202, 198, NULL, NULL, 'delete', 401, 402),
(203, 198, NULL, NULL, 'extractModel', 403, 404),
(204, 198, NULL, NULL, 'view', 405, 406),
(205, 2, NULL, NULL, 'OauthTokens', 408, 421),
(206, 205, NULL, NULL, 'index', 409, 410),
(207, 205, NULL, NULL, 'add', 411, 412),
(208, 205, NULL, NULL, 'edit', 413, 414),
(209, 205, NULL, NULL, 'delete', 415, 416),
(210, 205, NULL, NULL, 'extractModel', 417, 418),
(211, 205, NULL, NULL, 'view', 419, 420),
(212, 2, NULL, NULL, 'Penalty', 422, 437),
(213, 212, NULL, NULL, 'save', 423, 424),
(214, 212, NULL, NULL, 'extractModel', 425, 426),
(215, 212, NULL, NULL, 'add', 427, 428),
(216, 212, NULL, NULL, 'edit', 429, 430),
(217, 212, NULL, NULL, 'index', 431, 432),
(218, 212, NULL, NULL, 'view', 433, 434),
(219, 212, NULL, NULL, 'delete', 435, 436),
(220, 2, NULL, NULL, 'Rubrics', 438, 461),
(221, 220, NULL, NULL, 'postProcess', 439, 440),
(222, 220, NULL, NULL, 'setUpAjaxList', 441, 442),
(223, 220, NULL, NULL, 'index', 443, 444),
(224, 220, NULL, NULL, 'ajaxList', 445, 446),
(225, 220, NULL, NULL, 'view', 447, 448),
(226, 220, NULL, NULL, 'add', 449, 450),
(227, 220, NULL, NULL, 'edit', 451, 452),
(228, 220, NULL, NULL, 'copy', 453, 454),
(229, 220, NULL, NULL, 'delete', 455, 456),
(230, 220, NULL, NULL, 'setForm_RubricName', 457, 458),
(231, 220, NULL, NULL, 'extractModel', 459, 460),
(232, 2, NULL, NULL, 'Searchs', 462, 491),
(233, 232, NULL, NULL, 'update', 463, 464),
(234, 232, NULL, NULL, 'index', 465, 466),
(235, 232, NULL, NULL, 'searchEvaluation', 467, 468),
(236, 232, NULL, NULL, 'searchResult', 469, 470),
(237, 232, NULL, NULL, 'searchInstructor', 471, 472),
(238, 232, NULL, NULL, 'eventBoxSearch', 473, 474),
(239, 232, NULL, NULL, 'formatSearchEvaluation', 475, 476),
(240, 232, NULL, NULL, 'formatSearchInstructor', 477, 478),
(241, 232, NULL, NULL, 'formatSearchEvaluationResult', 479, 480),
(242, 232, NULL, NULL, 'extractModel', 481, 482),
(243, 232, NULL, NULL, 'add', 483, 484),
(244, 232, NULL, NULL, 'edit', 485, 486),
(245, 232, NULL, NULL, 'view', 487, 488),
(246, 232, NULL, NULL, 'delete', 489, 490),
(247, 2, NULL, NULL, 'Simpleevaluations', 492, 513),
(248, 247, NULL, NULL, 'postProcess', 493, 494),
(249, 247, NULL, NULL, 'setUpAjaxList', 495, 496),
(250, 247, NULL, NULL, 'index', 497, 498),
(251, 247, NULL, NULL, 'ajaxList', 499, 500),
(252, 247, NULL, NULL, 'view', 501, 502),
(253, 247, NULL, NULL, 'add', 503, 504),
(254, 247, NULL, NULL, 'edit', 505, 506),
(255, 247, NULL, NULL, 'copy', 507, 508),
(256, 247, NULL, NULL, 'delete', 509, 510),
(257, 247, NULL, NULL, 'extractModel', 511, 512),
(258, 2, NULL, NULL, 'Surveygroups', 514, 547),
(259, 258, NULL, NULL, 'postProcess', 515, 516),
(260, 258, NULL, NULL, 'setUpAjaxList', 517, 518),
(261, 258, NULL, NULL, 'index', 519, 520),
(262, 258, NULL, NULL, 'ajaxList', 521, 522),
(263, 258, NULL, NULL, 'viewresult', 523, 524),
(264, 258, NULL, NULL, 'makegroups', 525, 526),
(265, 258, NULL, NULL, 'makegroupssearch', 527, 528),
(266, 258, NULL, NULL, 'maketmgroups', 529, 530),
(267, 258, NULL, NULL, 'savegroups', 531, 532),
(268, 258, NULL, NULL, 'release', 533, 534),
(269, 258, NULL, NULL, 'delete', 535, 536),
(270, 258, NULL, NULL, 'edit', 537, 538),
(271, 258, NULL, NULL, 'changegroupset', 539, 540),
(272, 258, NULL, NULL, 'extractModel', 541, 542),
(273, 258, NULL, NULL, 'add', 543, 544),
(274, 258, NULL, NULL, 'view', 545, 546),
(275, 2, NULL, NULL, 'Surveys', 548, 583),
(276, 275, NULL, NULL, 'setUpAjaxList', 549, 550),
(277, 275, NULL, NULL, 'index', 551, 552),
(278, 275, NULL, NULL, 'ajaxList', 553, 554),
(279, 275, NULL, NULL, 'view', 555, 556),
(280, 275, NULL, NULL, 'add', 557, 558),
(281, 275, NULL, NULL, 'edit', 559, 560),
(282, 275, NULL, NULL, 'copy', 561, 562),
(283, 275, NULL, NULL, 'delete', 563, 564),
(284, 275, NULL, NULL, 'checkDuplicateName', 565, 566),
(285, 275, NULL, NULL, 'releaseSurvey', 567, 568),
(286, 275, NULL, NULL, 'questionsSummary', 569, 570),
(287, 275, NULL, NULL, 'moveQuestion', 571, 572),
(288, 275, NULL, NULL, 'removeQuestion', 573, 574),
(289, 275, NULL, NULL, 'addQuestion', 575, 576),
(290, 275, NULL, NULL, 'editQuestion', 577, 578),
(291, 275, NULL, NULL, 'update', 579, 580),
(292, 275, NULL, NULL, 'extractModel', 581, 582),
(293, 2, NULL, NULL, 'Sysfunctions', 584, 603),
(294, 293, NULL, NULL, 'setUpAjaxList', 585, 586),
(295, 293, NULL, NULL, 'index', 587, 588),
(296, 293, NULL, NULL, 'ajaxList', 589, 590),
(297, 293, NULL, NULL, 'view', 591, 592),
(298, 293, NULL, NULL, 'edit', 593, 594),
(299, 293, NULL, NULL, 'delete', 595, 596),
(300, 293, NULL, NULL, 'update', 597, 598),
(301, 293, NULL, NULL, 'extractModel', 599, 600),
(302, 293, NULL, NULL, 'add', 601, 602),
(303, 2, NULL, NULL, 'Sysparameters', 604, 621),
(304, 303, NULL, NULL, 'setUpAjaxList', 605, 606),
(305, 303, NULL, NULL, 'index', 607, 608),
(306, 303, NULL, NULL, 'ajaxList', 609, 610),
(307, 303, NULL, NULL, 'view', 611, 612),
(308, 303, NULL, NULL, 'add', 613, 614),
(309, 303, NULL, NULL, 'edit', 615, 616),
(310, 303, NULL, NULL, 'delete', 617, 618),
(311, 303, NULL, NULL, 'extractModel', 619, 620),
(312, 2, NULL, NULL, 'Upgrade', 622, 639),
(313, 312, NULL, NULL, 'index', 623, 624),
(314, 312, NULL, NULL, 'step2', 625, 626),
(315, 312, NULL, NULL, 'checkPermission', 627, 628),
(316, 312, NULL, NULL, 'extractModel', 629, 630),
(317, 312, NULL, NULL, 'add', 631, 632),
(318, 312, NULL, NULL, 'edit', 633, 634),
(319, 312, NULL, NULL, 'view', 635, 636),
(320, 312, NULL, NULL, 'delete', 637, 638),
(321, 2, NULL, NULL, 'Users', 640, 679),
(322, 321, NULL, NULL, 'login', 641, 642),
(323, 321, NULL, NULL, 'logout', 643, 644),
(324, 321, NULL, NULL, 'ajaxList', 645, 646),
(325, 321, NULL, NULL, 'index', 647, 648),
(326, 321, NULL, NULL, 'goToClassList', 649, 650),
(327, 321, NULL, NULL, 'determineIfStudentFromThisData', 651, 652),
(328, 321, NULL, NULL, 'getSimpleEntrollmentLists', 653, 654),
(329, 321, NULL, NULL, 'view', 655, 656),
(330, 321, NULL, NULL, 'add', 657, 658),
(331, 321, NULL, NULL, 'edit', 659, 660),
(332, 321, NULL, NULL, 'editProfile', 661, 662),
(333, 321, NULL, NULL, 'delete', 663, 664),
(334, 321, NULL, NULL, 'checkDuplicateName', 665, 666),
(335, 321, NULL, NULL, 'resetPassword', 667, 668),
(336, 321, NULL, NULL, 'import', 669, 670),
(337, 321, NULL, NULL, 'importPreview', 671, 672),
(338, 321, NULL, NULL, 'importFile', 673, 674),
(339, 321, NULL, NULL, 'update', 675, 676),
(340, 321, NULL, NULL, 'extractModel', 677, 678),
(341, 2, NULL, NULL, 'V1', 680, 705),
(342, 341, NULL, NULL, 'oauth', 681, 682),
(343, 341, NULL, NULL, 'oauth_error', 683, 684),
(344, 341, NULL, NULL, 'users', 685, 686),
(345, 341, NULL, NULL, 'courses', 687, 688),
(346, 341, NULL, NULL, 'groups', 689, 690),
(347, 341, NULL, NULL, 'events', 691, 692),
(348, 341, NULL, NULL, 'grades', 693, 694),
(349, 341, NULL, NULL, 'add', 695, 696),
(350, 341, NULL, NULL, 'edit', 697, 698),
(351, 341, NULL, NULL, 'index', 699, 700),
(352, 341, NULL, NULL, 'view', 701, 702),
(353, 341, NULL, NULL, 'delete', 703, 704),
(354, 2, NULL, NULL, 'Guard', 706, 725),
(355, 354, NULL, NULL, 'Guard', 707, 724),
(356, 355, NULL, NULL, 'login', 708, 709),
(357, 355, NULL, NULL, 'logout', 710, 711),
(358, 355, NULL, NULL, 'extractModel', 712, 713),
(359, 355, NULL, NULL, 'add', 714, 715),
(360, 355, NULL, NULL, 'edit', 716, 717),
(361, 355, NULL, NULL, 'index', 718, 719),
(362, 355, NULL, NULL, 'view', 720, 721),
(363, 355, NULL, NULL, 'delete', 722, 723),
(364, NULL, NULL, NULL, 'functions', 727, 790),
(365, 364, NULL, NULL, 'user', 728, 755),
(366, 365, NULL, NULL, 'superadmin', 729, 730),
(367, 365, NULL, NULL, 'admin', 731, 732),
(368, 365, NULL, NULL, 'instructor', 733, 734),
(369, 365, NULL, NULL, 'tutor', 735, 736),
(370, 365, NULL, NULL, 'student', 737, 738),
(371, 365, NULL, NULL, 'import', 739, 740),
(372, 365, NULL, NULL, 'password_reset', 741, 752),
(373, 372, NULL, NULL, 'superadmin', 742, 743),
(374, 372, NULL, NULL, 'admin', 744, 745),
(375, 372, NULL, NULL, 'instructor', 746, 747),
(376, 372, NULL, NULL, 'tutor', 748, 749),
(377, 372, NULL, NULL, 'student', 750, 751),
(378, 365, NULL, NULL, 'index', 753, 754),
(379, 364, NULL, NULL, 'role', 756, 767),
(380, 379, NULL, NULL, 'superadmin', 757, 758),
(381, 379, NULL, NULL, 'admin', 759, 760),
(382, 379, NULL, NULL, 'instructor', 761, 762),
(383, 379, NULL, NULL, 'tutor', 763, 764),
(384, 379, NULL, NULL, 'student', 765, 766),
(385, 364, NULL, NULL, 'evaluation', 768, 771),
(386, 385, NULL, NULL, 'export', 769, 770),
(387, 364, NULL, NULL, 'email', 772, 779),
(388, 387, NULL, NULL, 'allUsers', 773, 774),
(389, 387, NULL, NULL, 'allGroups', 775, 776),
(390, 387, NULL, NULL, 'allCourses', 777, 778),
(391, 364, NULL, NULL, 'emailtemplate', 780, 781),
(392, 364, NULL, NULL, 'viewstudentresults', 782, 783),
(393, 364, NULL, NULL, 'viewemailaddresses', 784, 785),
(394, 364, NULL, NULL, 'superadmin', 786, 787),
(395, 364, NULL, NULL, 'onlytakeeval', 788, 789);



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
(2, 1, 364, '1', '1', '1', '1'),
(3, 1, 60, '1', '1', '1', '1'),
(4, 1, 247, '1', '1', '1', '1'),
(5, 1, 220, '1', '1', '1', '1'),
(6, 1, 182, '1', '1', '1', '1'),
(7, 1, 275, '1', '1', '1', '1'),
(8, 1, 32, '1', '1', '1', '1'),
(9, 1, 49, '1', '1', '1', '1'),
(10, 1, 104, '1', '1', '1', '1'),
(11, 1, 121, '1', '1', '1', '1'),
(12, 1, 25, '1', '1', '1', '1'),
(13, 1, 138, '1', '1', '1', '1'),
(14, 1, 1, '1', '1', '1', '1'),
(15, 1, 393, '-1', '-1', '-1', '-1'),
(16, 1, 394, '1', '1', '1', '1'),
(17, 2, 2, '-1', '-1', '-1', '-1'),
(18, 2, 156, '1', '1', '1', '1'),
(19, 2, 11, '1', '1', '1', '1'),
(20, 2, 25, '1', '1', '1', '1'),
(21, 2, 32, '1', '1', '1', '1'),
(22, 2, 49, '1', '1', '1', '1'),
(23, 2, 60, '1', '1', '1', '1'),
(24, 2, 104, '1', '1', '1', '1'),
(25, 2, 138, '1', '1', '1', '1'),
(26, 2, 182, '1', '1', '1', '1'),
(27, 2, 220, '1', '1', '1', '1'),
(28, 2, 247, '1', '1', '1', '1'),
(29, 2, 275, '1', '1', '1', '1'),
(30, 2, 321, '1', '1', '1', '1'),
(31, 2, 68, '1', '1', '1', '1'),
(32, 2, 364, '-1', '-1', '-1', '-1'),
(33, 2, 391, '1', '1', '1', '1'),
(34, 2, 385, '1', '1', '1', '1'),
(35, 2, 388, '1', '1', '1', '1'),
(36, 2, 365, '1', '1', '1', '1'),
(37, 2, 367, '-1', '1', '-1', '-1'),
(38, 2, 366, '-1', '-1', '-1', '-1'),
(39, 2, 1, '1', '1', '1', '1'),
(40, 2, 393, '-1', '-1', '-1', '-1'),
(41, 2, 394, '-1', '-1', '-1', '-1'),
(42, 3, 2, '-1', '-1', '-1', '-1'),
(43, 3, 156, '1', '1', '1', '1'),
(44, 3, 11, '1', '1', '1', '1'),
(45, 3, 17, '-1', '-1', '-1', '-1'),
(46, 3, 18, '-1', '-1', '-1', '-1'),
(47, 3, 32, '1', '1', '1', '1'),
(48, 3, 49, '1', '1', '1', '1'),
(49, 3, 60, '1', '1', '1', '1'),
(50, 3, 104, '1', '1', '1', '1'),
(51, 3, 138, '1', '1', '1', '1'),
(52, 3, 182, '1', '1', '1', '1'),
(53, 3, 220, '1', '1', '1', '1'),
(54, 3, 247, '1', '1', '1', '1'),
(55, 3, 275, '1', '1', '1', '1'),
(56, 3, 321, '1', '1', '1', '1'),
(57, 3, 364, '-1', '-1', '-1', '-1'),
(58, 3, 385, '1', '1', '-1', '-1'),
(59, 3, 365, '1', '1', '1', '1'),
(60, 3, 367, '-1', '-1', '-1', '-1'),
(61, 3, 366, '-1', '-1', '-1', '-1'),
(62, 3, 368, '-1', '1', '-1', '-1'),
(63, 3, 378, '-1', '-1', '-1', '-1'),
(64, 3, 393, '-1', '-1', '-1', '-1'),
(65, 3, 394, '-1', '-1', '-1', '-1'),
(66, 3, 395, '-1', '-1', '-1', '-1'),
(67, 4, 2, '-1', '-1', '-1', '-1'),
(68, 4, 156, '1', '1', '1', '1'),
(69, 4, 11, '-1', '-1', '-1', '-1'),
(70, 4, 32, '-1', '-1', '-1', '-1'),
(71, 4, 49, '-1', '-1', '-1', '-1'),
(72, 4, 60, '-1', '-1', '-1', '-1'),
(73, 4, 104, '-1', '-1', '-1', '-1'),
(74, 4, 138, '-1', '-1', '-1', '-1'),
(75, 4, 182, '-1', '-1', '-1', '-1'),
(76, 4, 220, '-1', '-1', '-1', '-1'),
(77, 4, 247, '-1', '-1', '-1', '-1'),
(78, 4, 275, '-1', '-1', '-1', '-1'),
(79, 4, 321, '-1', '-1', '-1', '-1'),
(80, 4, 364, '-1', '-1', '-1', '-1'),
(81, 4, 393, '-1', '-1', '-1', '-1'),
(82, 4, 394, '-1', '-1', '-1', '-1'),
(83, 4, 395, '1', '1', '1', '1'),
(84, 5, 2, '-1', '-1', '-1', '-1'),
(85, 5, 156, '1', '1', '1', '1'),
(86, 5, 11, '-1', '-1', '-1', '-1'),
(87, 5, 32, '-1', '-1', '-1', '-1'),
(88, 5, 49, '-1', '-1', '-1', '-1'),
(89, 5, 60, '-1', '-1', '-1', '-1'),
(90, 5, 104, '-1', '-1', '-1', '-1'),
(91, 5, 138, '-1', '-1', '-1', '-1'),
(92, 5, 182, '-1', '-1', '-1', '-1'),
(93, 5, 220, '-1', '-1', '-1', '-1'),
(94, 5, 247, '-1', '-1', '-1', '-1'),
(95, 5, 275, '-1', '-1', '-1', '-1'),
(96, 5, 321, '-1', '-1', '-1', '-1'),
(97, 5, 364, '-1', '-1', '-1', '-1'),
(98, 5, 392, '1', '1', '1', '1'),
(99, 5, 393, '-1', '-1', '-1', '-1'),
(100, 5, 394, '-1', '-1', '-1', '-1'),
(101, 5, 395, '1', '1', '1', '1');


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


