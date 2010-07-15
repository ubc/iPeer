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

-- 
-- Dumping data for table `courses`
-- 

INSERT INTO `courses` VALUES (1, 'MECH 328', 'Mechanical Engineering Design Project', 'http://www.mech.ubc.ca', 'off', NULL, 'A', 1, '2006-06-20 14:14:45', NULL, '2006-06-20 14:14:45', 0);
INSERT INTO `courses` VALUES (2, 'APSC 201', 'Technical Communication', 'http://www.apsc.ubc.ca', 'off', NULL, 'A', 1, '2006-06-20 14:15:38', 1, '2006-06-20 14:39:31', 0);

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

-- 
-- Dumping data for table `evaluation_mixeval_details`
-- 


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

-- 
-- Dumping data for table `evaluation_mixevals`
-- 


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

-- 
-- Dumping data for table `evaluation_rubric_details`
-- 


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

-- 
-- Dumping data for table `evaluation_rubrics`
-- 


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

-- 
-- Dumping data for table `evaluation_simples`
-- 


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

-- 
-- Dumping data for table `evaluation_submissions`
-- 


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

-- 
-- Dumping data for table `events`
-- 

INSERT INTO `events` VALUES (1, 'Term 1 Evaluation', 1, '', 1, 1, '0', 0, '2006-07-02 16:34:43', '2006-06-16 16:34:49', '2006-07-02 16:34:53', 'A', 0, '2006-06-20 16:27:33', NULL, '2006-06-21 08:51:20');
INSERT INTO `events` VALUES (2, 'Term Report Evaluation', 1, '', 2, 1, '0', 0, '2006-06-08 08:59:29', '2006-06-06 08:59:35', '2006-07-02 08:59:41', 'A', 0, '2006-06-21 08:52:20', NULL, '2006-06-21 08:54:25');
INSERT INTO `events` VALUES (3, 'Project Evaluation', 1, '', 4, 1, '0', 0, '2006-07-02 09:00:28', '2006-06-07 09:00:35', '2006-07-02 09:00:39', 'A', 0, '2006-06-21 08:53:14', NULL, '2006-06-21 09:07:26');

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
CREATE TABLE `groups_members` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

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

-- 
-- Dumping data for table `mixevals`
-- 

INSERT INTO `mixevals` VALUES (1, 'Project Evaluation', 1, 'on', 3, 1, 5, 1, 'public', 1, '2006-06-21 09:06:47', 1, '2006-06-21 09:08:49');

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

-- 
-- Dumping data for table `mixevals_question_descs`
-- 

INSERT INTO `mixevals_question_descs` VALUES (1, 1, 1, 1, 'Lowest');
INSERT INTO `mixevals_question_descs` VALUES (2, 1, 1, 2, '');
INSERT INTO `mixevals_question_descs` VALUES (3, 1, 1, 3, 'Middle');
INSERT INTO `mixevals_question_descs` VALUES (4, 1, 1, 4, '');
INSERT INTO `mixevals_question_descs` VALUES (5, 1, 1, 5, 'Highest');
INSERT INTO `mixevals_question_descs` VALUES (6, 1, 1, 1, 'Lowest');
INSERT INTO `mixevals_question_descs` VALUES (7, 1, 1, 2, '');
INSERT INTO `mixevals_question_descs` VALUES (8, 1, 1, 3, 'Middle');
INSERT INTO `mixevals_question_descs` VALUES (9, 1, 1, 4, '');
INSERT INTO `mixevals_question_descs` VALUES (10, 1, 1, 5, 'Highest');

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
INSERT INTO `personalizes` VALUES (14, 1, 'Mixeval.ListMenu.Limit.Show', '10', NULL, '2006-06-21 09:06:47');
INSERT INTO `personalizes` VALUES (15, 1, 'Course.ListMenu.Limit.Show', '10', '2006-06-21 09:06:52', '2006-06-21 09:06:52');
INSERT INTO `personalizes` VALUES (16, 1, 'Event.ListMenu.Limit.Show', '10', '2006-06-21 09:07:06', '2006-06-21 09:07:06');
INSERT INTO `personalizes` VALUES (17, 1, 'Survey.ListMenu.Limit.Show', '10', '2006-06-21 09:43:13', '2006-06-21 09:43:13');
INSERT INTO `personalizes` VALUES (18, 1, 'SurveyGroupSet.List.Limit.Show', '10', '2006-06-21 12:22:19', '2006-06-21 12:22:19');
INSERT INTO `personalizes` VALUES (19, 1, 'User.ListMenu.Limit.Show', '10', '2006-06-21 15:11:00', '2006-06-21 15:11:00');
INSERT INTO `personalizes` VALUES (20, 1, 'SimpleEval.ListMenu.Limit.Show', '10', '2006-06-21 15:12:56', '2006-06-21 15:12:56');
INSERT INTO `personalizes` VALUES (21, 1, 'Search.ListMenu.Limit.Show', '10', '2006-06-21 15:16:35', '2006-06-21 15:16:35');
INSERT INTO `personalizes` VALUES (22, 1, 'SysParam.ListMenu.Limit.Show', '10', '2006-06-21 15:16:37', '2006-06-21 15:16:37');

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
CREATE TABLE `responses` (
  `id` int(11) NOT NULL auto_increment,
  `question_id` int(11) NOT NULL default '0',
  `response` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

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

-- 
-- Dumping data for table `rubrics`
-- 

INSERT INTO `rubrics` VALUES (1, 'Term Report Evaluation', 15, 'off', 5, 3, 'public', 'horizontal', 1, '2006-06-20 15:21:50', NULL, '2006-06-20 15:21:50');

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

-- 
-- Dumping data for table `rubrics_criteria_comments`
-- 

INSERT INTO `rubrics_criteria_comments` VALUES (1, 1, 1, 1, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (2, 1, 1, 2, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (3, 1, 1, 3, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (4, 1, 1, 4, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (5, 1, 1, 5, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (6, 1, 2, 1, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (7, 1, 2, 2, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (8, 1, 2, 3, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (9, 1, 2, 4, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (10, 1, 2, 5, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (11, 1, 3, 1, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (12, 1, 3, 2, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (13, 1, 3, 3, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (14, 1, 3, 4, NULL);
INSERT INTO `rubrics_criteria_comments` VALUES (15, 1, 3, 5, NULL);

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
CREATE TABLE `rubrics_loms` (
  `id` int(11) NOT NULL auto_increment,
  `rubric_id` int(11) NOT NULL default '0',
  `lom_num` int(11) NOT NULL default '0',
  `lom_comment` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

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
CREATE TABLE `simple_evaluations` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` text NOT NULL,
  `point_per_member` int(10) NOT NULL default '0',
  `point_low_limit` int(10) NOT NULL default '0',
  `point_high_limit` int(10) NOT NULL default '0',
  `record_status` char(1) NOT NULL default 'A',
  `creator_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updater_id` int(11) default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `simple_evaluations`
-- 

INSERT INTO `simple_evaluations` VALUES (1, 'Module 1 Project Evaluation', '', 100, 50, 200, 'A', 1, '2006-06-20 15:17:47', NULL, '2006-06-20 15:17:47');

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

-- 
-- Dumping data for table `survey_group_sets`
-- 

INSERT INTO `survey_group_sets` VALUES (3, 1, 'test groupset', 3, 1150923956, 0);

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

-- 
-- Dumping data for table `survey_inputs`
-- 


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

-- 
-- Dumping data for table `surveys`
-- 

INSERT INTO `surveys` VALUES (1, 1, 1, 'Team Creation Survey', '2006-07-01 15:31:08', '2006-06-01 15:31:17', '2006-07-02 15:31:21', 0, 0, '2006-06-20 15:23:59', NULL, '2006-06-21 09:43:24');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (1, 'A', 'root', 'eda2575e2d2d21385336d974ddd83eec', 'Super', 'Admin', NULL, 'Administrator', 'cis-dev1@apsc.ubc.ca', NULL, NULL, NULL, 'A', 0, '0000-00-00 00:00:00', NULL, '2006-05-24 16:02:59');
INSERT INTO `users` VALUES (2, 'I', 'poscar', '22bf1cbd965e66775fd973a30dcc4431', 'Peter', 'Oscar', NULL, 'Instructor', '', NULL, NULL, NULL, 'A', 1, '2006-06-19 16:25:24', NULL, '2006-06-19 16:25:24');
INSERT INTO `users` VALUES (3, 'I', 'paulmcbeth', '36d59a7ca1e0bbad18ea4f185d698e6b', 'McBeth', 'Paul', NULL, 'Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:02', NULL, '2006-06-20 14:17:02');
INSERT INTO `users` VALUES (4, 'I', 'emilylai', 'bebfc322b580a86124307f387454e968', 'Emily', 'Lai', NULL, 'Assistant Professor', '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:17:53', NULL, '2006-06-20 14:17:53');
INSERT INTO `users` VALUES (5, 'S', '65498451', '78c3c751d765acd1d9e0db2613c30598', 'Ed', 'Fidler', '65498451', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:19:18', NULL, '2006-06-20 14:19:18');
INSERT INTO `users` VALUES (6, 'S', '65468188', '123181add2761dcde157ee8acad0671f', 'Alex', 'Ng', '65468188', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:26:59', NULL, '2006-06-20 14:26:59');
INSERT INTO `users` VALUES (7, 'S', '98985481', 'da0ae9740be304173241f6d22bda88fc', 'Matt', 'Harper', '98985481', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:24', NULL, '2006-06-20 14:27:24');
INSERT INTO `users` VALUES (8, 'S', '16585158', '3482e702575e6539ebaf26d26c32d6a9', 'Chris', 'Leikermoser', '16585158', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:27:43', NULL, '2006-06-20 14:27:43');
INSERT INTO `users` VALUES (9, 'S', '81121651', '3e3006de9165ca68a1474d15b36ca61a', 'Johnny', 'Oshika', '81121651', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:08', NULL, '2006-06-20 14:28:08');
INSERT INTO `users` VALUES (10, 'S', '87800283', '033aa9b0a34277be46185e5f7897766d', 'Travis', 'Penno', '87800283', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:29', NULL, '2006-06-20 14:28:29');
INSERT INTO `users` VALUES (11, 'S', '68541180', '41ed4245fd127b23f644abdea8c983e8', 'Kelly', 'Sall', '68541180', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:28:49', NULL, '2006-06-20 14:28:49');
INSERT INTO `users` VALUES (12, 'S', '48451389', 'f953ddcfb7a7d0727731fea5443f1612', 'Peter', 'So', '48451389', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:29:07', NULL, '2006-06-20 14:29:07');
INSERT INTO `users` VALUES (13, 'S', '84188465', 'a2d928445b8ce0768ca60866db7ec4d8', 'Damien', 'Clapa', '84188465', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:31:17', NULL, '2006-06-20 14:31:17');
INSERT INTO `users` VALUES (14, 'S', '27701036', '9a8d62f8f051085faa1993801e359c26', 'Hajar', 'Abdollahi', '27701036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 14:47:34', NULL, '2006-06-20 14:47:34');
INSERT INTO `users` VALUES (15, 'S', '48877031', 'e9512f326fa60069295d55402c934ae9', 'Jennifer', 'Alloway', '48877031', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:00:35', NULL, '2006-06-20 15:00:35');
INSERT INTO `users` VALUES (16, 'S', '25731063', 'd809ef1ff75140842ebcea2eeb326a68', 'Chad', 'Amiel', '25731063', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:09', NULL, '2006-06-20 15:01:09');
INSERT INTO `users` VALUES (17, 'S', '37116036', '92473bbae508045b37b065d3059ef2fe', 'Edna', 'Ang', '37116036', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:24', NULL, '2006-06-20 15:01:24');
INSERT INTO `users` VALUES (18, 'S', '76035030', 'a802acb2ff0fa49f0ab7220a548a90f2', 'Denny', 'Anggabrata', '76035030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:01:52', NULL, '2006-06-20 15:01:52');
INSERT INTO `users` VALUES (19, 'S', '90938044', 'd99df20a80253a2977db58e7d1e9e1a6', 'Jonathan', 'Appleton', '90938044', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:02:20', NULL, '2006-06-20 15:02:20');
INSERT INTO `users` VALUES (20, 'S', '88505045', '79fc2a62a52fb95a884336182760ca47', 'Soroush', 'Babaeian', '88505045', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:27', NULL, '2006-06-20 15:03:27');
INSERT INTO `users` VALUES (21, 'S', '22784037', '7bd663524c1c742e005e583c354a181c', 'Nicole', 'Babuick', '22784037', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:03:47', NULL, '2006-06-20 15:03:47');
INSERT INTO `users` VALUES (22, 'S', '37048022', 'a5ac7f4b57cfec2117292f65421fa8fd', 'Vivian', 'Baik', '37048022', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:04:22', NULL, '2006-06-20 15:04:22');
INSERT INTO `users` VALUES (23, 'S', '89947048', '44920ee1b7466dd14b3f05b9c230c765', 'Trevor', 'Bruce', '89947048', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:05:55', NULL, '2006-06-20 15:05:55');
INSERT INTO `users` VALUES (24, 'S', '39823059', '772a66f511a1ee98049c226581544006', 'Michael', 'Canning', '39823059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:20', NULL, '2006-06-20 15:06:20');
INSERT INTO `users` VALUES (25, 'S', '35644039', '333e126eb7eb77eedefbb9ac24c5e5bb', 'Steven', 'Catania', '35644039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:06:46', NULL, '2006-06-20 15:06:46');
INSERT INTO `users` VALUES (26, 'S', '19524032', 'e661d6bdab29994109b189363142977a', 'Bill', 'Chan', '19524032', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:01', NULL, '2006-06-20 15:07:01');
INSERT INTO `users` VALUES (27, 'S', '40289059', '455ca8f6b1ef46e1793308614581bf48', 'Van Hong', 'Dao', '40289059', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:07:37', NULL, '2006-06-20 15:07:37');
INSERT INTO `users` VALUES (28, 'S', '38058020', '0800bd74b17181bd9461f02a84569b96', 'Michael', 'Davis', '38058020', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:04', NULL, '2006-06-20 15:08:04');
INSERT INTO `users` VALUES (29, 'S', '38861035', '4da8d3aa955eac6544d29ccdd30776f1', 'Jonathan', 'Funk', '38861035', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:31', NULL, '2006-06-20 15:08:31');
INSERT INTO `users` VALUES (30, 'S', '27879030', '46a7c75feecfdf87ce0b9a1796d7f369', 'Geoff', 'Howe', '27879030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:08:47', NULL, '2006-06-20 15:08:47');
INSERT INTO `users` VALUES (31, 'S', '10186039', '84c94d12e8b8629c4d4e37879bc7043b', 'Hui', 'Jing', '10186039', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:16', NULL, '2006-06-20 15:10:16');
INSERT INTO `users` VALUES (32, 'S', '19803030', '8328f0ce00854e307cfeda1119e73d26', 'Bowinn', 'Ma', '19803030', NULL, '', NULL, NULL, NULL, 'A', 1, '2006-06-20 15:10:32', NULL, '2006-06-20 15:10:32');
INSERT INTO `users` VALUES (33, 'S', '51516498', '25cef707046231c80a9d4546744879db', 'Joe', 'Deon', '51516498', NULL, 'joe.deon@ubc.ca', NULL, NULL, NULL, 'A', 1, '2006-06-21 08:44:09', 33, '2006-06-21 08:45:00');
