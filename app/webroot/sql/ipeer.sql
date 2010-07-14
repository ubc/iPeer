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

-- --------------------------------------------------------

-- 
-- Table structure for table `evaluation_mixeval_details`
-- 

DROP TABLE IF EXISTS `evaluation_mixeval_details`;
CREATE TABLE `evaluation_mixeval_details` (
  `id` int(11) NOT NULL auto_increment,
  `evaluation_mixeval_id` int(11) NOT NULL default '0',
  `question_number` int(11) NOT NULL default '0',
  `question_comment` varchar(255) default NULL,
  `selected_lom` int(11) NOT NULL default '0',
  `grade` double(12,2) NOT NULL default '0.00',
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `evaluation_mixeval_id` (`evaluation_mixeval_id`,`question_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `evaluation_mixevals`
-- 

DROP TABLE IF EXISTS `evaluation_mixevals`;
CREATE TABLE `evaluation_mixevals` (
  `id` int(11) NOT NULL auto_increment,
  `evaluator` int(11) NOT NULL default '0',
  `evaluatee` int(11) NOT NULL default '0',
  `score` double(12,2) NOT NULL default '0.00',
  `comment_release` int(1) NOT NULL default '0',
  `grade_release` int(1) NOT NULL default '0',
  `grp_event_id` int(11) NOT NULL default '0',
  `event_id` int(11) NOT NULL default '0',
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `evaluation_rubric_details`
-- 

DROP TABLE IF EXISTS `evaluation_rubric_details`;
CREATE TABLE `evaluation_rubric_details` (
  `id` int(11) NOT NULL auto_increment,
  `evaluation_rubric_id` int(11) NOT NULL default '0',
  `criteria_number` int(11) NOT NULL default '0',
  `criteria_comment` varchar(255) default NULL,
  `selected_lom` int(11) NOT NULL default '0',
  `grade` double(12,2) NOT NULL default '0.00',
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `evaluation_rubric_id` (`evaluation_rubric_id`,`criteria_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `evaluation_rubrics`
-- 

DROP TABLE IF EXISTS `evaluation_rubrics`;
CREATE TABLE `evaluation_rubrics` (
  `id` int(11) NOT NULL auto_increment,
  `evaluator` int(11) NOT NULL default '0',
  `evaluatee` int(11) NOT NULL default '0',
  `general_comment` text,
  `score` double(12,2) NOT NULL default '0.00',
  `comment_release` int(1) NOT NULL default '0',
  `grade_release` int(1) NOT NULL default '0',
  `grp_event_id` int(11) NOT NULL default '0',
  `event_id` int(11) NOT NULL default '0',
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `evaluation_simples`
-- 

DROP TABLE IF EXISTS `evaluation_simples`;
CREATE TABLE `evaluation_simples` (
  `id` int(11) NOT NULL auto_increment,
  `evaluator` int(11) NOT NULL default '0',
  `evaluatee` int(11) NOT NULL default '0',
  `score` int(5) NOT NULL default '0',
  `eval_comment` text,
  `release_status` int(1) NOT NULL default '0',
  `grp_event_id` int(11) NOT NULL default '0',
  `event_id` bigint(11) NOT NULL default '0',
  `date_submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  `grade_release` int(1) default NULL,
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `evaluation_submissions`
-- 

DROP TABLE IF EXISTS `evaluation_submissions`;
CREATE TABLE `evaluation_submissions` (
  `id` int(11) NOT NULL auto_increment,
  `event_id` bigint(20) NOT NULL default '0',
  `grp_event_id` int(11) NOT NULL default '0',
  `submitter_id` int(11) NOT NULL default '0',
  `submitted` tinyint(1) NOT NULL default '0',
  `date_submitted` datetime default NULL,
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `grp_event_id` (`grp_event_id`,`submitter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `event_template_types`
-- 

DROP TABLE IF EXISTS `event_template_types`;
CREATE TABLE `event_template_types` (
  `id` int(11) NOT NULL auto_increment,
  `type_name` varchar(50) NOT NULL default '',
  `table_name` varchar(50) NOT NULL default '',
  `model_name` varchar(80) NOT NULL default '',
  `display_for_selection` tinyint(1) NOT NULL default '1',
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `type_name` (`type_name`),
  UNIQUE KEY `table_name` (`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `event_template_types`
-- 

INSERT INTO `event_template_types` VALUES (1, 'SIMPLE', 'simple_evaluations', 'SimpleEvaluation', 1, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `event_template_types` VALUES (2, 'RUBRIC', 'rubrics', 'Rubric', 1, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `event_template_types` VALUES (3, 'SURVEY', 'surveys', '', 0, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `event_template_types` VALUES (4, 'MIX EVALUATION', 'mixevals', 'Mixeval', 1, 'A', 0, '2006-04-03 11:51:02', 0, '2006-04-06 15:31:48');

-- --------------------------------------------------------

-- 
-- Table structure for table `events`
-- 

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `course_id` int(11) NOT NULL default '0',
  `description` varchar(255) default NULL,
  `event_template_type_id` int(20) NOT NULL default '0',
  `template_id` int(11) NOT NULL default '2',
  `self_eval` varchar(11) NOT NULL default '',
  `com_req` int(11) NOT NULL default '0',
  `due_date` datetime default NULL,
  `release_date_begin` datetime default NULL,
  `release_date_end` datetime default NULL,
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `group_events`
-- 

DROP TABLE IF EXISTS `group_events`;
CREATE TABLE `group_events` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `event_id` int(11) NOT NULL default '0',
  `marked` enum('not reviewed','reviewed','to review') NOT NULL default 'not reviewed',
  `grade` double(12,2) default NULL,
  `grade_release_status` varchar(20) NOT NULL default 'None',
  `comment_release_status` varchar(20) NOT NULL default 'None',
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `group_id` (`event_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `groups`
-- 

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL auto_increment,
  `group_num` int(4) NOT NULL default '0',
  `group_name` varchar(80) default NULL,
  `course_id` int(11) default NULL,
  `record_status` enum('A','I') NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `groups_members`
-- 

DROP TABLE IF EXISTS `groups_members`;
CREATE TABLE `groups_members` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `mixevals`
-- 

DROP TABLE IF EXISTS `mixevals`;
CREATE TABLE `mixevals` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  `total_marks` int(11) default NULL,
  `zero_mark` enum('on','off') NOT NULL default 'off',
  `total_question` int(11) NOT NULL default '0',
  `lickert_question_max` int(11) NOT NULL default '0',
  `scale_max` int(11) NOT NULL default '0',
  `prefill_question_max` int(11) default NULL,
  `availability` enum('public','private') NOT NULL default 'public',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `mixevals` (`id`, `name`, `total_marks`, `total_question`, `lickert_question_max`, `scale_max`, `prefill_question_max`, `availability`, `creator_id`, `created`, `updater_id`, `modified`) VALUES 
(1, 'Default Mix Evalution', 3, 7, 3, 5, 3, 'public', 1, '2006-09-12 13:34:30', 1, '2006-09-12 13:47:57');

-- --------------------------------------------------------

-- 
-- Table structure for table `mixevals_question_descs`
-- 

DROP TABLE IF EXISTS `mixevals_question_descs`;
CREATE TABLE `mixevals_question_descs` (
  `id` int(11) NOT NULL auto_increment,
  `mixeval_id` int(11) NOT NULL default '0',
  `question_num` int(11) NOT NULL default '0',
  `scale_level` int(11) NOT NULL default '0',
  `descriptor` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `mixevals_question_descs` (`id`, `mixeval_id`, `question_num`, `scale_level`, `descriptor`) VALUES 
(46, 1, 1, 1, 'Lowest'),
(47, 1, 1, 2, NULL),
(48, 1, 1, 3, 'Middle'),
(49, 1, 1, 4, NULL),
(50, 1, 1, 5, 'Highest'),
(51, 1, 2, 1, 'Lowest'),
(52, 1, 2, 2, NULL),
(53, 1, 2, 3, 'Middle'),
(54, 1, 2, 4, NULL),
(55, 1, 2, 5, 'Highest'),
(56, 1, 3, 1, 'Lowest'),
(57, 1, 3, 2, NULL),
(58, 1, 3, 3, 'Middle'),
(59, 1, 3, 4, NULL),
(60, 1, 3, 5, 'Highest');

-- --------------------------------------------------------

-- 
-- Table structure for table `mixevals_questions`
-- 

DROP TABLE IF EXISTS `mixevals_questions`;
CREATE TABLE `mixevals_questions` (
  `id` int(11) NOT NULL auto_increment,
  `mixeval_id` int(11) NOT NULL default '0',
  `question_num` int(11) NOT NULL default '0',
  `title` text,
  `instructions` text,
  `question_type` char(1) default NULL,
  `required` int(1) NOT NULL default '1',
  `multiplier` int(11) NOT NULL default '0',
  `scale_level` int(11) NOT NULL default '0',
  `response_type` char(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

INSERT INTO `mixevals_questions` (`id`, `mixeval_id`, `question_num`, `title`, `instructions`, `question_type`, `required`, `multiplier`, `scale_level`, `response_type`) VALUES 
(19, 1, 1, 'Participated in Team Meetings', NULL, 'S', 1, 1, 5, NULL),
(20, 1, 2, 'Was Helpful and co-operative', NULL, 'S', 1, 1, 5, NULL),
(21, 1, 3, 'Submitted work on time', NULL, 'S', 1, 1, 5, NULL),
(22, 1, 4, 'Produced efficient work?', NULL, 'T', 1, 0, 5, 'S'),
(23, 1, 5, 'Contributed?', NULL, 'T', 1, 0, 5, 'L'),
(24, 1, 6, 'Easy to work with?', NULL, 'T', 0, 0, 5, 'S');

-- --------------------------------------------------------

-- 
-- Table structure for table `personalizes`
-- 

DROP TABLE IF EXISTS `personalizes`;
CREATE TABLE `personalizes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `attribute_code` varchar(80) default NULL,
  `attribute_value` varchar(80) default NULL,
  `created` datetime default NULL,
  `updated` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `questions`
-- 

DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL auto_increment,
  `prompt` varchar(255) NOT NULL default '',
  `type` enum('M','C','S','L') default NULL,
  `master` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `responses`
-- 

DROP TABLE IF EXISTS `responses`;
CREATE TABLE `responses` (
  `id` int(11) NOT NULL auto_increment,
  `question_id` int(11) NOT NULL default '0',
  `response` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `rubrics`
-- 

DROP TABLE IF EXISTS `rubrics`;
CREATE TABLE `rubrics` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  `total_marks` int(11) default NULL,
  `zero_mark` enum('on','off') NOT NULL default 'off',
  `lom_max` int(11) default NULL,
  `criteria` int(11) default NULL,
  `availability` enum('public','private') NOT NULL default 'public',
  `template` enum('horizontal','vertical') NOT NULL default 'horizontal',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `rubrics_criteria_comments`
-- 

DROP TABLE IF EXISTS `rubrics_criteria_comments`;
CREATE TABLE `rubrics_criteria_comments` (
  `id` int(11) NOT NULL auto_increment,
  `rubric_id` int(11) NOT NULL default '0',
  `criteria_num` int(11) NOT NULL default '0',
  `lom_num` int(11) NOT NULL default '0',
  `criteria_comment` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `rubrics_criterias`
-- 

DROP TABLE IF EXISTS `rubrics_criterias`;
CREATE TABLE `rubrics_criterias` (
  `id` int(11) NOT NULL auto_increment,
  `rubric_id` int(11) NOT NULL default '0',
  `criteria_num` int(11) NOT NULL default '0',
  `criteria` varchar(255) default NULL,
  `multiplier` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `rubrics_loms`
-- 

DROP TABLE IF EXISTS `rubrics_loms`;
CREATE TABLE `rubrics_loms` (
  `id` int(11) NOT NULL auto_increment,
  `rubric_id` int(11) NOT NULL default '0',
  `lom_num` int(11) NOT NULL default '0',
  `lom_comment` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `simple_evaluations`
-- 

DROP TABLE IF EXISTS `simple_evaluations`;
CREATE TABLE `simple_evaluations` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` text NOT NULL,
  `point_per_member` int(10) NOT NULL default '0',
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `survey_group_members`
-- 

DROP TABLE IF EXISTS `survey_group_members`;
CREATE TABLE `survey_group_members` (
  `id` int(11) NOT NULL auto_increment,
  `group_set_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `survey_group_sets`
-- 

DROP TABLE IF EXISTS `survey_group_sets`;
CREATE TABLE `survey_group_sets` (
  `id` int(11) NOT NULL auto_increment,
  `survey_id` int(11) NOT NULL default '0',
  `set_description` text NOT NULL,
  `num_groups` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `released` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `survey_groups`
-- 

DROP TABLE IF EXISTS `survey_groups`;
CREATE TABLE `survey_groups` (
  `id` int(11) NOT NULL auto_increment,
  `group_set_id` int(11) NOT NULL default '0',
  `group_number` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `survey_inputs`
-- 

DROP TABLE IF EXISTS `survey_inputs`;
CREATE TABLE `survey_inputs` (
  `id` int(11) NOT NULL auto_increment,
  `survey_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `question_id` int(11) NOT NULL default '0',
  `sub_id` int(11) default NULL,
  `response_text` text,
  `response_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `survey_questions`
-- 

DROP TABLE IF EXISTS `survey_questions`;
CREATE TABLE `survey_questions` (
  `id` int(11) NOT NULL auto_increment,
  `survey_id` int(11) NOT NULL default '0',
  `number` int(11) NOT NULL default '0',
  `question_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `question_id` (`question_id`),
  KEY `survey_id` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `surveys`
-- 

DROP TABLE IF EXISTS `surveys`;
CREATE TABLE `surveys` (
  `id` int(11) NOT NULL auto_increment,
  `course_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '1',
  `name` text NOT NULL,
  `due_date` datetime default NULL,
  `release_date_begin` datetime default NULL,
  `release_date_end` datetime default NULL,
  `released` tinyint(1) NOT NULL default '0',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `sys_functions`
-- 

DROP TABLE IF EXISTS `sys_functions`;
CREATE TABLE `sys_functions` (
  `id` int(11) NOT NULL default '0',
  `function_code` varchar(80) NOT NULL default '',
  `function_name` varchar(200) NOT NULL default '',
  `parent_id` int(11) NOT NULL default '0',
  `controller_name` varchar(80) NOT NULL default '',
  `url_link` varchar(80) NOT NULL default '',
  `permission_type` varchar(10) NOT NULL default 'A',
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `sys_functions`
-- 

INSERT INTO `sys_functions` VALUES (100, 'SYS_FUNC', 'System Functions', 0, 'sysfunctions', 'sysfunctions/index/', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (101, 'SYS_PARA', 'System Parameters', 0, 'sysparameters', 'sysparameters/index/', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (102, 'SYS_BAKER', 'System Codes Generator', 0, 'bakers', 'bakers/index/', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (200, 'HOME', 'Home', 0, 'home', 'home/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (201, 'HOME', 'Home', 0, 'home', 'home/index/', 'S', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (300, 'USR', 'Users', 0, 'users', 'users/index/', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (301, 'USR', 'Students', 0, 'users', 'users/index/', 'IS', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (302, 'USR_PROFILE', 'Profile', 0, 'users', 'users/editProfile/', 'S', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (303, 'USR_RECORD', 'User Record', 1000, 'users', 'users/add/S', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (304, 'USR_INST_MGT', 'Instruction Record Management', 1000, 'users', 'users/add/I', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (305, 'USR_ADMIN_MGT', 'Admin Record Management', 1000, 'users', 'users/add/A', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (306, 'USR_PROFILE', 'Profile Edit', 1000, 'users', 'users/add/A', 'A', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (400, 'COURSE', 'Courses', 0, 'courses', 'courses/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (401, 'COURSE_RECORD', 'Course Record', 1000, 'courses', 'courses/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (500, 'GROUP', 'Groups', 0, 'groups', 'groups/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_functions` VALUES (501, 'GROUP_RECORD', 'Group Record', 1000, 'groups', 'groups/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_functions` VALUES (600, 'RUBRIC', 'Rubrics', 0, 'rubrics', 'rubrics/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-03-27 11:05:57');
INSERT INTO `sys_functions` VALUES (601, 'RUBRIC_RECORD', 'Rubric Record', 1000, 'rubrics', 'rubrics/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-03-27 11:07:44');
INSERT INTO `sys_functions` VALUES (700, 'SIMPLE_EVAL', 'Simple Evaluations', 0, 'simpleevaluations', 'simpleevaluations/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (800, 'EVENT', 'Events Management', 0, 'events', 'events/index/', 'AI', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (900, 'PERSONALIZES', 'Personalizes', 0, 'personalizes', 'personalizes/index/', 'AIS', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_functions` VALUES (1000, 'FRAMEWORK', 'Framework Controller', 0, 'framework', 'framework/index/', 'ASI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-07 08:40:29');
INSERT INTO `sys_functions` VALUES (1100, 'SURVEY', 'Team Maker', 0, 'surveys', 'surveys/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-06 09:18:04');
INSERT INTO `sys_functions` VALUES (1101, 'SURVEY_RECORD', 'Survey Record', 1000, 'surveys', 'surveys/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-06 09:16:19');
INSERT INTO `sys_functions` VALUES (1200, 'EVALUATION', 'Evaluation', 0, 'evaluations', 'evaluations/index/', 'ASI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-07 08:40:29');
INSERT INTO `sys_functions` VALUES (1300, 'EVAL_TOOL', 'Evaluation Tools', 0, 'evaltools', 'evaltools/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-07 08:40:29');
INSERT INTO `sys_functions` VALUES (1400, 'ADV_SEARCH', 'Advanced Search', 0, 'searchs', 'searchs/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-07 08:40:29');
INSERT INTO `sys_functions` VALUES (1500, 'MIX_EVAL', 'Mixed Evaluations', 0, 'mixevals', 'mixevals/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-03-27 11:05:57');
INSERT INTO `sys_functions` VALUES (1501, 'MIX_EVAL_RECORD', 'Mixed Evaluations Record', 1000, 'mixevals', 'mixevals/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-03-27 11:07:44');
INSERT INTO `sys_functions` VALUES (1600, 'SURVEY_GROUP', 'Survey Group', 0, 'surveygroups', 'surveygroups/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-06 09:18:04');
INSERT INTO `sys_functions` VALUES (1601, 'SURVEY_GROUP_RECORD', 'Survey Group Record', 1000, 'surveygroups', 'surveygroups/index/', 'AI', 'A', 0, '0000-00-00 00:00:00', NULL, '2006-04-06 09:16:19');

-- --------------------------------------------------------

-- 
-- Table structure for table `sys_parameters`
-- 

DROP TABLE IF EXISTS `sys_parameters`;
CREATE TABLE `sys_parameters` (
  `id` int(11) NOT NULL auto_increment,
  `parameter_code` varchar(80) NOT NULL default '',
  `parameter_value` text,
  `parameter_type` char(1) NOT NULL default '',
  `description` varchar(255) default NULL,
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `parameter_code` (`parameter_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- 
-- Dumping data for table `sys_parameters`
-- 

INSERT INTO `sys_parameters` VALUES (1, 'system.soft_delete_enable', 'true', 'B', 'Whether soft deletion of records is enabled.', 'A', 0, '2006-03-01 00:00:00', 0, '2006-03-01 00:00:00');
INSERT INTO `sys_parameters` VALUES (2, 'system.debug_mode', '0', 'I', 'Debug Mode of the system', 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (3, 'system.debug_verbosity', '1', 'I', NULL, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (4, 'system.absolute_url', 'http://', 'S', NULL, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (5, 'system.domain', '', 'S', NULL, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (6, 'system.super_admin', 'root', 'S', NULL, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (7, 'system.upload_dir', 'uploads/', 'S', NULL, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (8, 'display.contact_info', 'Please enter your custom contact info. HTML tabs are acceptable.', 'S', NULL, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (9, 'display.page_title', 'iPeer v2 with TeamMaker', 'S', 'Page title show in HTML.', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_parameters` VALUES (10, 'display.logo_file', 'LayoutLogoDefault.gif', 'S', 'Logo image name.', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_parameters` VALUES (11, 'display.login_logo_file', 'LayoutLoginLogoDefault.gif', 'S', 'Login Image File Name.', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_parameters` VALUES (12, 'display.login_text', '<a href=''http://www.apsc.ubc.ca'' target=''_blank''>UBC - Faculty of Applied Science</a>', 'S', 'Login Image File Name.', 'A', 0, '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00');
INSERT INTO `sys_parameters` VALUES (13, 'custom.login_control', 'ipeer', 'S', 'The login control for iPeer: ipeer; CWL: UBC_CWL', 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (14, 'custom.login_page_pathname', 'custom_ubc_cwl_login', 'S', 'The file pathname for the custom login page; CWL:custom_ubc_cwl_login', 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (15, 'system.admin_email', 'ipeer@apsc.ubc.ca', 'S', NULL, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` VALUES (16, 'system.password_reset_mail', 'Dear <user>,<br> Your password has been reset to <newpassword>. Please use this to log in from now on. <br><br>iPeer Administrator', 'S', NULL, 'A', 0, '0000-00-00 00:00:00', NULL, NULL); 
INSERT INTO `sys_parameters` VALUES (17, 'system.password_reset_emailsubject', 'iPeer Password Reset', 'S', NULL, 'A', 0, '0000-00-00 00:00:00', NULL, NULL);
INSERT INTO `sys_parameters` ( `id` , `parameter_code` , `parameter_value` , `parameter_type` , `description` , `record_status` , `creator_id` , `created` , `updater_id` , `modified` )
VALUES (18 , 'display.date_format', 'D, M j, Y g:i a', 'S', 'date format preference', 'A', '0', '0000-00-00 00:00:00', NULL , NULL); 

-- --------------------------------------------------------

-- 
-- Table structure for table `user_courses`
-- 

DROP TABLE IF EXISTS `user_courses`;
CREATE TABLE `user_courses` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `course_id` int(11) NOT NULL default '0',
  `access_right` enum('O','A','R') NOT NULL default 'O',
  `record_status` enum('A','I') NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `user_enrols`
-- 

DROP TABLE IF EXISTS `user_enrols`;
CREATE TABLE `user_enrols` (
  `id` int(11) NOT NULL auto_increment,
  `course_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `record_status` enum('A','I') NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `course_id` (`course_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `role` enum('A','I','S','T') NOT NULL default 'S',
  `username` varchar(80) NOT NULL default '',
  `password` varchar(80) NOT NULL default '',
  `first_name` varchar(80) default NULL,
  `last_name` varchar(80) default NULL,
  `student_no` varchar(30) default NULL,
  `title` varchar(80) default NULL,
  `email` varchar(80) default NULL,
  `last_login` datetime default NULL,
  `last_logout` datetime default NULL,
  `last_accessed` varchar(10) default NULL,
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;