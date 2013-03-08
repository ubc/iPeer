SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- Update datetime for all tables

-- Previous iPeer versions sometimes use '0000-00-00 00:00:00' for datetime
-- fields. Unfortunately, newer MySQL versions now rejects it as an invalid
-- date, so we'll just replace the all 0 datetimes with some fake but valid
-- datetime
UPDATE courses SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE courses SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE users SET last_login = '2000-01-01 00:00:00' WHERE last_login = '0000-00-00 00:00:00';
UPDATE users SET last_logout = '2000-01-01 00:00:00' WHERE last_logout = '0000-00-00 00:00:00';
UPDATE users SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE users SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE faculties SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE faculties SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE departments SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE departments SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE email_merges SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE email_merges SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE email_schedules SET `date` = '2000-01-01 00:00:00' WHERE `date` = '0000-00-00 00:00:00';
UPDATE email_schedules SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE email_templates SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE email_templates SET updated = '2000-01-01 00:00:00' WHERE updated = '0000-00-00 00:00:00';
UPDATE evaluation_mixevals SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE evaluation_mixevals SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE evaluation_mixeval_details SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE evaluation_mixeval_details SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE evaluation_rubric_details SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE evaluation_rubric_details SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE evaluation_rubrics SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE evaluation_rubrics SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE evaluation_simples SET date_submitted = '2000-01-01 00:00:00' WHERE date_submitted = '0000-00-00 00:00:00';
UPDATE evaluation_simples SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE evaluation_simples SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE evaluation_submissions SET date_submitted = '2000-01-01 00:00:00' WHERE date_submitted = '0000-00-00 00:00:00';
UPDATE evaluation_submissions SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE evaluation_submissions SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE event_template_types SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE event_template_types SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE events SET due_date = '2000-01-01 00:00:00' WHERE due_date = '0000-00-00 00:00:00';
UPDATE events SET release_date_begin = '2000-01-01 00:00:00' WHERE release_date_begin = '0000-00-00 00:00:00';
UPDATE events SET release_date_end = '2000-01-01 00:00:00' WHERE release_date_end = '0000-00-00 00:00:00';
UPDATE events SET result_release_date_begin = '2000-01-01 00:00:00' WHERE result_release_date_begin = '0000-00-00 00:00:00';
UPDATE events SET result_release_date_end = '2000-01-01 00:00:00' WHERE result_release_date_end = '0000-00-00 00:00:00';
UPDATE events SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE events SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE group_events SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE group_events SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE groups SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE groups SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE mixevals SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE mixevals SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE personalizes SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE personalizes SET updated = '2000-01-01 00:00:00' WHERE updated = '0000-00-00 00:00:00';
UPDATE roles SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE roles SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE roles_users SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE roles_users SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE rubrics SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE rubrics SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE simple_evaluations SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE simple_evaluations SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE surveys SET due_date = '2000-01-01 00:00:00' WHERE due_date = '0000-00-00 00:00:00';
UPDATE surveys SET release_date_begin = '2000-01-01 00:00:00' WHERE release_date_begin = '0000-00-00 00:00:00';
UPDATE surveys SET release_date_end = '2000-01-01 00:00:00' WHERE release_date_end = '0000-00-00 00:00:00';
UPDATE surveys SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE surveys SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE sys_parameters SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE sys_parameters SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE user_courses SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE user_courses SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE user_enrols SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE user_enrols SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';
UPDATE user_tutors SET created = '2000-01-01 00:00:00' WHERE created = '0000-00-00 00:00:00';
UPDATE user_tutors SET modified = '2000-01-01 00:00:00' WHERE modified = '0000-00-00 00:00:00';

-- Batch these updates so that they happen simulantaneously for each
-- table to get rid of the annoying effect where, e.g.: the statement fixing
-- the created column will complain about the modified column being incorrect,
-- even though the very next statement fixes the modified column.
ALTER TABLE courses MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE users MODIFY created datetime, MODIFY modified datetime, MODIFY last_login datetime, MODIFY last_logout datetime;
ALTER TABLE faculties MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE departments MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE email_merges MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE email_schedules MODIFY `date` datetime, MODIFY created datetime;
ALTER TABLE email_templates MODIFY created datetime, MODIFY updated datetime;
ALTER TABLE evaluation_mixevals MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE evaluation_mixeval_details MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE evaluation_rubric_details MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE evaluation_rubrics MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE evaluation_simples MODIFY date_submitted datetime, MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE evaluation_submissions MODIFY created datetime, MODIFY modified datetime, MODIFY date_submitted datetime;
ALTER TABLE event_template_types MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE events MODIFY created datetime, MODIFY modified datetime, MODIFY due_date datetime, MODIFY release_date_begin datetime, MODIFY release_date_end datetime, MODIFY result_release_date_begin datetime, MODIFY result_release_date_end datetime;
ALTER TABLE group_events MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE groups MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE mixevals MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE personalizes MODIFY created datetime, MODIFY updated datetime;
ALTER TABLE roles MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE roles_users MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE rubrics MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE simple_evaluations MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE surveys MODIFY due_date datetime, MODIFY release_date_begin datetime, MODIFY release_date_end datetime, MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE sys_parameters MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE user_courses MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE user_enrols MODIFY created datetime, MODIFY modified datetime;
ALTER TABLE user_tutors MODIFY created datetime, MODIFY modified datetime;

-- Missed one table's char set conversion during the last upgrade
-- Do this here or MySQL will complain about invalid dates.
ALTER TABLE sys_parameters CHARACTER SET utf8;

-- Convert all old table columns to utf8
-- While the iPeer 3.0 upgrader did update the table character set to utf8, it
-- turns out that there's an extra step necessary to finish the utf8
-- conversion: Pre-existing columns in the table have to be manually converted
-- to utf8 individually.
-- Have to convert to binary and then the right column data-type due to some
-- MySQL estorica in character set conversion.
-- More info: http://codex.wordpress.org/Converting_Database_Character_Sets

ALTER TABLE courses CHANGE course course VARBINARY(80);
ALTER TABLE courses CHANGE course course VARCHAR(80) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE courses CHANGE title title VARBINARY(80);
ALTER TABLE courses CHANGE title title VARCHAR(80) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE courses CHANGE homepage homepage VARBINARY(100);
ALTER TABLE courses CHANGE homepage homepage VARCHAR(100) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE courses CHANGE password password VARBINARY(25);
ALTER TABLE courses CHANGE password password VARCHAR(25) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE evaluation_mixevals CHANGE record_status record_status BINARY(1);
ALTER TABLE evaluation_mixevals CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE evaluation_mixeval_details CHANGE question_comment question_comment BLOB;
ALTER TABLE evaluation_mixeval_details CHANGE question_comment question_comment TEXT CHARACTER SET utf8;
ALTER TABLE evaluation_mixeval_details CHANGE record_status record_status BINARY(1);
ALTER TABLE evaluation_mixeval_details CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE evaluation_rubrics CHANGE record_status record_status BINARY(1);
ALTER TABLE evaluation_rubrics CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE evaluation_rubric_details CHANGE criteria_comment criteria_comment VARBINARY(255);
ALTER TABLE evaluation_rubric_details CHANGE criteria_comment criteria_comment VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE evaluation_rubric_details CHANGE record_status record_status BINARY(1);
ALTER TABLE evaluation_rubric_details CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE evaluation_simples CHANGE record_status record_status BINARY(1);
ALTER TABLE evaluation_simples CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE evaluation_submissions CHANGE record_status record_status BINARY(1);
ALTER TABLE evaluation_submissions CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE events CHANGE title title VARBINARY(255);
ALTER TABLE events CHANGE title title VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE events CHANGE description description VARBINARY(255);
ALTER TABLE events CHANGE description description TEXT CHARACTER SET utf8;
ALTER TABLE events CHANGE self_eval self_eval VARBINARY(11);
ALTER TABLE events CHANGE self_eval self_eval VARCHAR(11) CHARACTER SET utf8 NOT NULL DEFAULT '0';
ALTER TABLE events CHANGE record_status record_status BINARY(1);
ALTER TABLE events CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE event_template_types CHANGE type_name type_name VARBINARY(50);
ALTER TABLE event_template_types CHANGE type_name type_name VARCHAR(50) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE event_template_types CHANGE table_name table_name VARBINARY(50);
ALTER TABLE event_template_types CHANGE table_name table_name VARCHAR(50) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE event_template_types CHANGE model_name model_name VARBINARY(80);
ALTER TABLE event_template_types CHANGE model_name model_name VARCHAR(80) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE event_template_types CHANGE record_status record_status BINARY(1);
ALTER TABLE event_template_types CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE groups CHANGE group_name group_name VARBINARY(80);
ALTER TABLE groups CHANGE group_name group_name VARCHAR(80) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE group_events CHANGE grade_release_status grade_release_status VARBINARY(20);
ALTER TABLE group_events CHANGE grade_release_status grade_release_status VARCHAR(20) CHARACTER SET utf8 NOT NULL DEFAULT 'None';
ALTER TABLE group_events CHANGE comment_release_status comment_release_status VARBINARY(20);
ALTER TABLE group_events CHANGE comment_release_status comment_release_status VARCHAR(20) CHARACTER SET utf8 NOT NULL DEFAULT 'None';
ALTER TABLE group_events CHANGE record_status record_status BINARY(1);
ALTER TABLE group_events CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE mixevals CHANGE name name VARBINARY(80);
ALTER TABLE mixevals CHANGE name name VARCHAR(80) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE mixevals_questions CHANGE title title BLOB;
ALTER TABLE mixevals_questions CHANGE title title TEXT CHARACTER SET utf8;
ALTER TABLE mixevals_questions CHANGE instructions instructions BLOB;
ALTER TABLE mixevals_questions CHANGE instructions instructions TEXT CHARACTER SET utf8;
ALTER TABLE mixevals_question_descs CHANGE descriptor descriptor VARBINARY(255);
ALTER TABLE mixevals_question_descs CHANGE descriptor descriptor VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE personalizes CHANGE attribute_code attribute_code VARBINARY(80);
ALTER TABLE personalizes CHANGE attribute_code attribute_code VARCHAR(80) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE personalizes CHANGE attribute_value attribute_value VARBINARY(80);
ALTER TABLE personalizes CHANGE attribute_value attribute_value VARCHAR(80) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE questions CHANGE prompt prompt VARBINARY(255);
ALTER TABLE questions CHANGE prompt prompt VARCHAR(255) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE responses CHANGE response response BLOB;
ALTER TABLE responses CHANGE response response TEXT CHARACTER SET utf8 NOT NULL;
ALTER TABLE rubrics CHANGE name name VARBINARY(80);
ALTER TABLE rubrics CHANGE name name VARCHAR(80) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE rubrics_criterias CHANGE criteria criteria VARBINARY(255);
ALTER TABLE rubrics_criterias CHANGE criteria criteria VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE rubrics_criteria_comments CHANGE criteria_comment criteria_comment VARBINARY(255);
ALTER TABLE rubrics_criteria_comments CHANGE criteria_comment criteria_comment VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE rubrics_loms CHANGE lom_comment lom_comment VARBINARY(255);
ALTER TABLE rubrics_loms CHANGE lom_comment lom_comment VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE simple_evaluations CHANGE name name VARBINARY(50);
ALTER TABLE simple_evaluations CHANGE name name VARCHAR(50) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE simple_evaluations CHANGE description description BLOB;
ALTER TABLE simple_evaluations CHANGE description description TEXT CHARACTER SET utf8 NOT NULL;
ALTER TABLE simple_evaluations CHANGE record_status record_status BINARY(1);
ALTER TABLE simple_evaluations CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE survey_group_sets CHANGE set_description set_description BLOB;
ALTER TABLE survey_group_sets CHANGE set_description set_description TEXT CHARACTER SET utf8 NOT NULL;
ALTER TABLE survey_inputs CHANGE response_text response_text BLOB;
ALTER TABLE survey_inputs CHANGE response_text response_text TEXT CHARACTER SET utf8;
ALTER TABLE users CHANGE username username VARBINARY(80);
ALTER TABLE users CHANGE username username VARCHAR(80) CHARACTER SET utf8 NOT NULL; 
ALTER TABLE users CHANGE password password VARBINARY(80);
ALTER TABLE users CHANGE password password VARCHAR(80) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE users CHANGE first_name first_name VARBINARY(80);
ALTER TABLE users CHANGE first_name first_name VARCHAR(80) CHARACTER SET utf8 DEFAULT '';
ALTER TABLE users CHANGE last_name last_name VARBINARY(80);
ALTER TABLE users CHANGE last_name last_name VARCHAR(80) CHARACTER SET utf8 DEFAULT '';
ALTER TABLE users CHANGE student_no student_no VARBINARY(30);
ALTER TABLE users CHANGE student_no student_no VARCHAR(30) CHARACTER SET utf8 DEFAULT '';
ALTER TABLE users CHANGE title title VARBINARY(80);
ALTER TABLE users CHANGE title title VARCHAR(80) CHARACTER SET utf8 DEFAULT '';
ALTER TABLE users CHANGE email email VARBINARY(80);
-- note increase in character limit for emails
ALTER TABLE users CHANGE email email VARCHAR(254) CHARACTER SET utf8 DEFAULT '';
ALTER TABLE users CHANGE last_accessed last_accessed VARBINARY(10);
ALTER TABLE users CHANGE last_accessed last_accessed VARCHAR(10) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE users CHANGE record_status record_status BINARY(1);
ALTER TABLE users CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';
ALTER TABLE sys_parameters CHANGE parameter_code parameter_code VARBINARY(80);
ALTER TABLE sys_parameters CHANGE parameter_code parameter_code VARCHAR(80) CHARACTER SET utf8 NOT NULL;
ALTER TABLE sys_parameters CHANGE parameter_value parameter_value BLOB;
ALTER TABLE sys_parameters CHANGE parameter_value parameter_value TEXT CHARACTER SET utf8;
ALTER TABLE sys_parameters CHANGE parameter_type parameter_type BINARY(1);
ALTER TABLE sys_parameters CHANGE parameter_type parameter_type CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE sys_parameters CHANGE description description VARBINARY(255);
ALTER TABLE sys_parameters CHANGE description description VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL;
ALTER TABLE sys_parameters CHANGE record_status record_status BINARY(1);
ALTER TABLE sys_parameters CHANGE record_status record_status CHAR(1) CHARACTER SET utf8 NOT NULL DEFAULT 'A';


-- Database structure changes

ALTER TABLE courses DROP instructor_id;

ALTER TABLE email_schedules MODIFY `to` text NOT NULL;

ALTER TABLE events ADD `auto_release` int(11) NOT NULL DEFAULT 0;
ALTER TABLE events ADD `enable_details` int(11) NOT NULL DEFAULT '1';

ALTER TABLE groups_members ADD UNIQUE KEY `group_user` (`group_id`, `user_id`);

ALTER TABLE mixevals DROP total_question;
ALTER TABLE mixevals DROP lickert_question_max;
ALTER TABLE mixevals DROP scale_max;
ALTER TABLE mixevals DROP prefill_question_max;

CREATE TABLE IF NOT EXISTS `mixeval_question_types` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`type` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `mixeval_question_types` (`id`, `type`) VALUES
(1, 'Likert'),
(2, 'Paragraph'),
(3, 'Sentence');

ALTER TABLE mixevals_questions RENAME mixeval_questions;
ALTER TABLE mixeval_questions ADD mixeval_question_type_id int(11) NOT NULL;
ALTER TABLE mixeval_questions ADD FOREIGN KEY (`mixeval_question_type_id`) 
	REFERENCES `mixeval_question_types` (`id`) ON DELETE CASCADE;
ALTER TABLE mixeval_questions ADD FOREIGN KEY (`mixeval_id`) REFERENCES 
	`mixevals` (`id`) ON DELETE CASCADE;
UPDATE mixeval_questions SET mixeval_question_type_id = 1 WHERE question_type = 'S';
UPDATE mixeval_questions SET mixeval_question_type_id = 2 WHERE question_type = 'T' AND response_type = 'L';
UPDATE mixeval_questions SET mixeval_question_type_id = 3 WHERE question_type = 'T' AND response_type = 'S';
ALTER TABLE mixeval_questions DROP question_type;
ALTER TABLE mixeval_questions DROP response_type;

ALTER TABLE mixevals_question_descs RENAME mixeval_question_descs;
ALTER TABLE mixeval_question_descs ADD FOREIGN KEY (`question_id`) REFERENCES 
	`mixeval_questions` (`id`) ON DELETE CASCADE;

ALTER TABLE survey_inputs DROP sub_id;
ALTER TABLE survey_inputs DROP chkbx_id;

ALTER TABLE surveys DROP course_id;
ALTER TABLE surveys DROP user_id;

DELETE FROM sys_parameters WHERE parameter_code = 'system.domain';
DELETE FROM sys_parameters WHERE parameter_code = 'system.upload_dir';
DELETE FROM sys_parameters WHERE parameter_code = 'system.password_reset_mail';
DELETE FROM sys_parameters WHERE parameter_code = 'system.password_reset_emailsubject';
DELETE FROM sys_parameters WHERE parameter_code = 'system.soft_delete_enable';
DELETE FROM sys_parameters WHERE parameter_code = 'system.debug_mode';
DELETE FROM sys_parameters WHERE parameter_code = 'system.debug_verbosity';
DELETE FROM sys_parameters WHERE parameter_code = 'system.absolute_url';
DELETE FROM sys_parameters WHERE parameter_code = 'display.page_title';
DELETE FROM sys_parameters WHERE parameter_code = 'display.logo_file';
DELETE FROM sys_parameters WHERE parameter_code = 'display.login_logo_file';
DELETE FROM sys_parameters WHERE parameter_code = 'display.login_text';
DELETE FROM sys_parameters WHERE parameter_code = 'display.vocabulary.department';
DELETE FROM sys_parameters WHERE parameter_code = 'custom.login_control';
DELETE FROM sys_parameters WHERE parameter_code = 'custom.login_page_pathname';

ALTER TABLE rubrics_criteria_comments modify criteria_id int(11) NOT NULL;

-- Should be fine to completely recreate the ACL completely from scratch

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acos`
--

INSERT INTO acos (id, parent_id, model, foreign_key, alias, lft, rght) VALUES
(1, NULL, NULL, NULL, 'adminpage', 1, 2),
(2, NULL, NULL, NULL, 'controllers', 3, 576),
(3, 2, NULL, NULL, 'Pages', 4, 17),
(4, 3, NULL, NULL, 'display', 5, 6),
(5, 3, NULL, NULL, 'add', 7, 8),
(6, 3, NULL, NULL, 'edit', 9, 10),
(7, 3, NULL, NULL, 'index', 11, 12),
(8, 3, NULL, NULL, 'view', 13, 14),
(9, 3, NULL, NULL, 'delete', 15, 16),
(10, 2, NULL, NULL, 'Courses', 18, 41),
(11, 10, NULL, NULL, 'daysLate', 19, 20),
(12, 10, NULL, NULL, 'index', 21, 22),
(13, 10, NULL, NULL, 'ajaxList', 23, 24),
(14, 10, NULL, NULL, 'view', 25, 26),
(15, 10, NULL, NULL, 'home', 27, 28),
(16, 10, NULL, NULL, 'add', 29, 30),
(17, 10, NULL, NULL, 'edit', 31, 32),
(18, 10, NULL, NULL, 'delete', 33, 34),
(19, 10, NULL, NULL, 'move', 35, 36),
(20, 10, NULL, NULL, 'ajax_options', 37, 38),
(21, 10, NULL, NULL, 'import', 39, 40),
(22, 2, NULL, NULL, 'Departments', 42, 53),
(23, 22, NULL, NULL, 'index', 43, 44),
(24, 22, NULL, NULL, 'view', 45, 46),
(25, 22, NULL, NULL, 'add', 47, 48),
(26, 22, NULL, NULL, 'edit', 49, 50),
(27, 22, NULL, NULL, 'delete', 51, 52),
(28, 2, NULL, NULL, 'Emailer', 54, 81),
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
(39, 28, NULL, NULL, 'add', 75, 76),
(40, 28, NULL, NULL, 'edit', 77, 78),
(41, 28, NULL, NULL, 'delete', 79, 80),
(42, 2, NULL, NULL, 'Emailtemplates', 82, 101),
(43, 42, NULL, NULL, 'setUpAjaxList', 83, 84),
(44, 42, NULL, NULL, 'ajaxList', 85, 86),
(45, 42, NULL, NULL, 'index', 87, 88),
(46, 42, NULL, NULL, 'add', 89, 90),
(47, 42, NULL, NULL, 'edit', 91, 92),
(48, 42, NULL, NULL, 'delete', 93, 94),
(49, 42, NULL, NULL, 'view', 95, 96),
(50, 42, NULL, NULL, 'displayTemplateContent', 97, 98),
(51, 42, NULL, NULL, 'displayTemplateSubject', 99, 100),
(52, 2, NULL, NULL, 'Evaltools', 102, 113),
(53, 52, NULL, NULL, 'index', 103, 104),
(54, 52, NULL, NULL, 'add', 105, 106),
(55, 52, NULL, NULL, 'edit', 107, 108),
(56, 52, NULL, NULL, 'view', 109, 110),
(57, 52, NULL, NULL, 'delete', 111, 112),
(58, 2, NULL, NULL, 'Evaluations', 114, 161),
(59, 58, NULL, NULL, 'setUpAjaxList', 115, 116),
(60, 58, NULL, NULL, 'ajaxList', 117, 118),
(61, 58, NULL, NULL, 'view', 119, 120),
(62, 58, NULL, NULL, 'index', 121, 122),
(63, 58, NULL, NULL, 'export', 123, 124),
(64, 58, NULL, NULL, 'makeEvaluation', 125, 126),
(65, 58, NULL, NULL, 'validRubricEvalComplete', 127, 128),
(66, 58, NULL, NULL, 'completeEvaluationRubric', 129, 130),
(67, 58, NULL, NULL, 'validMixevalEvalComplete', 131, 132),
(68, 58, NULL, NULL, 'viewEvaluationResults', 133, 134),
(69, 58, NULL, NULL, 'studentViewEvaluationResult', 135, 136),
(70, 58, NULL, NULL, 'markEventReviewed', 137, 138),
(71, 58, NULL, NULL, 'markGradeRelease', 139, 140),
(72, 58, NULL, NULL, 'markCommentRelease', 141, 142),
(73, 58, NULL, NULL, 'changeAllCommentRelease', 143, 144),
(74, 58, NULL, NULL, 'changeAllGradeRelease', 145, 146),
(75, 58, NULL, NULL, 'viewGroupSubmissionDetails', 147, 148),
(76, 58, NULL, NULL, 'viewSurveySummary', 149, 150),
(77, 58, NULL, NULL, 'export_rubic', 151, 152),
(78, 58, NULL, NULL, 'export_test', 153, 154),
(79, 58, NULL, NULL, 'add', 155, 156),
(80, 58, NULL, NULL, 'edit', 157, 158),
(81, 58, NULL, NULL, 'delete', 159, 160),
(82, 2, NULL, NULL, 'Events', 162, 181),
(83, 82, NULL, NULL, 'postProcessData', 163, 164),
(84, 82, NULL, NULL, 'setUpAjaxList', 165, 166),
(85, 82, NULL, NULL, 'index', 167, 168),
(86, 82, NULL, NULL, 'ajaxList', 169, 170),
(87, 82, NULL, NULL, 'view', 171, 172),
(88, 82, NULL, NULL, 'add', 173, 174),
(89, 82, NULL, NULL, 'edit', 175, 176),
(90, 82, NULL, NULL, 'delete', 177, 178),
(91, 82, NULL, NULL, 'checkDuplicateName', 179, 180),
(92, 2, NULL, NULL, 'Faculties', 182, 193),
(93, 92, NULL, NULL, 'index', 183, 184),
(94, 92, NULL, NULL, 'view', 185, 186),
(95, 92, NULL, NULL, 'add', 187, 188),
(96, 92, NULL, NULL, 'edit', 189, 190),
(97, 92, NULL, NULL, 'delete', 191, 192),
(98, 2, NULL, NULL, 'Framework', 194, 209),
(99, 98, NULL, NULL, 'calendarDisplay', 195, 196),
(100, 98, NULL, NULL, 'tutIndex', 197, 198),
(101, 98, NULL, NULL, 'add', 199, 200),
(102, 98, NULL, NULL, 'edit', 201, 202),
(103, 98, NULL, NULL, 'index', 203, 204),
(104, 98, NULL, NULL, 'view', 205, 206),
(105, 98, NULL, NULL, 'delete', 207, 208),
(106, 2, NULL, NULL, 'Groups', 210, 229),
(107, 106, NULL, NULL, 'setUpAjaxList', 211, 212),
(108, 106, NULL, NULL, 'index', 213, 214),
(109, 106, NULL, NULL, 'ajaxList', 215, 216),
(110, 106, NULL, NULL, 'view', 217, 218),
(111, 106, NULL, NULL, 'add', 219, 220),
(112, 106, NULL, NULL, 'edit', 221, 222),
(113, 106, NULL, NULL, 'delete', 223, 224),
(114, 106, NULL, NULL, 'import', 225, 226),
(115, 106, NULL, NULL, 'export', 227, 228),
(116, 2, NULL, NULL, 'Home', 230, 241),
(117, 116, NULL, NULL, 'index', 231, 232),
(118, 116, NULL, NULL, 'add', 233, 234),
(119, 116, NULL, NULL, 'edit', 235, 236),
(120, 116, NULL, NULL, 'view', 237, 238),
(121, 116, NULL, NULL, 'delete', 239, 240),
(122, 2, NULL, NULL, 'Install', 242, 263),
(123, 122, NULL, NULL, 'index', 243, 244),
(124, 122, NULL, NULL, 'install2', 245, 246),
(125, 122, NULL, NULL, 'install3', 247, 248),
(126, 122, NULL, NULL, 'install4', 249, 250),
(127, 122, NULL, NULL, 'install5', 251, 252),
(128, 122, NULL, NULL, 'gpl', 253, 254),
(129, 122, NULL, NULL, 'add', 255, 256),
(130, 122, NULL, NULL, 'edit', 257, 258),
(131, 122, NULL, NULL, 'view', 259, 260),
(132, 122, NULL, NULL, 'delete', 261, 262),
(133, 2, NULL, NULL, 'Lti', 264, 275),
(134, 133, NULL, NULL, 'index', 265, 266),
(135, 133, NULL, NULL, 'add', 267, 268),
(136, 133, NULL, NULL, 'edit', 269, 270),
(137, 133, NULL, NULL, 'view', 271, 272),
(138, 133, NULL, NULL, 'delete', 273, 274),
(139, 2, NULL, NULL, 'Mixevals', 276, 293),
(140, 139, NULL, NULL, 'setUpAjaxList', 277, 278),
(141, 139, NULL, NULL, 'index', 279, 280),
(142, 139, NULL, NULL, 'ajaxList', 281, 282),
(143, 139, NULL, NULL, 'view', 283, 284),
(144, 139, NULL, NULL, 'add', 285, 286),
(145, 139, NULL, NULL, 'edit', 287, 288),
(146, 139, NULL, NULL, 'copy', 289, 290),
(147, 139, NULL, NULL, 'delete', 291, 292),
(148, 2, NULL, NULL, 'Oauthclients', 294, 305),
(149, 148, NULL, NULL, 'index', 295, 296),
(150, 148, NULL, NULL, 'add', 297, 298),
(151, 148, NULL, NULL, 'edit', 299, 300),
(152, 148, NULL, NULL, 'delete', 301, 302),
(153, 148, NULL, NULL, 'view', 303, 304),
(154, 2, NULL, NULL, 'Oauthtokens', 306, 317),
(155, 154, NULL, NULL, 'index', 307, 308),
(156, 154, NULL, NULL, 'add', 309, 310),
(157, 154, NULL, NULL, 'edit', 311, 312),
(158, 154, NULL, NULL, 'delete', 313, 314),
(159, 154, NULL, NULL, 'view', 315, 316),
(160, 2, NULL, NULL, 'Penalty', 318, 331),
(161, 160, NULL, NULL, 'save', 319, 320),
(162, 160, NULL, NULL, 'add', 321, 322),
(163, 160, NULL, NULL, 'edit', 323, 324),
(164, 160, NULL, NULL, 'index', 325, 326),
(165, 160, NULL, NULL, 'view', 327, 328),
(166, 160, NULL, NULL, 'delete', 329, 330),
(167, 2, NULL, NULL, 'Rubrics', 332, 351),
(168, 167, NULL, NULL, 'postProcess', 333, 334),
(169, 167, NULL, NULL, 'setUpAjaxList', 335, 336),
(170, 167, NULL, NULL, 'index', 337, 338),
(171, 167, NULL, NULL, 'ajaxList', 339, 340),
(172, 167, NULL, NULL, 'view', 341, 342),
(173, 167, NULL, NULL, 'add', 343, 344),
(174, 167, NULL, NULL, 'edit', 345, 346),
(175, 167, NULL, NULL, 'copy', 347, 348),
(176, 167, NULL, NULL, 'delete', 349, 350),
(177, 2, NULL, NULL, 'Searchs', 352, 379),
(178, 177, NULL, NULL, 'update', 353, 354),
(179, 177, NULL, NULL, 'index', 355, 356),
(180, 177, NULL, NULL, 'searchEvaluation', 357, 358),
(181, 177, NULL, NULL, 'searchResult', 359, 360),
(182, 177, NULL, NULL, 'searchInstructor', 361, 362),
(183, 177, NULL, NULL, 'eventBoxSearch', 363, 364),
(184, 177, NULL, NULL, 'formatSearchEvaluation', 365, 366),
(185, 177, NULL, NULL, 'formatSearchInstructor', 367, 368),
(186, 177, NULL, NULL, 'formatSearchEvaluationResult', 369, 370),
(187, 177, NULL, NULL, 'add', 371, 372),
(188, 177, NULL, NULL, 'edit', 373, 374),
(189, 177, NULL, NULL, 'view', 375, 376),
(190, 177, NULL, NULL, 'delete', 377, 378),
(191, 2, NULL, NULL, 'Simpleevaluations', 380, 399),
(192, 191, NULL, NULL, 'postProcess', 381, 382),
(193, 191, NULL, NULL, 'setUpAjaxList', 383, 384),
(194, 191, NULL, NULL, 'index', 385, 386),
(195, 191, NULL, NULL, 'ajaxList', 387, 388),
(196, 191, NULL, NULL, 'view', 389, 390),
(197, 191, NULL, NULL, 'add', 391, 392),
(198, 191, NULL, NULL, 'edit', 393, 394),
(199, 191, NULL, NULL, 'copy', 395, 396),
(200, 191, NULL, NULL, 'delete', 397, 398),
(201, 2, NULL, NULL, 'Surveygroups', 400, 429),
(202, 201, NULL, NULL, 'postProcess', 401, 402),
(203, 201, NULL, NULL, 'setUpAjaxList', 403, 404),
(204, 201, NULL, NULL, 'index', 405, 406),
(205, 201, NULL, NULL, 'ajaxList', 407, 408),
(206, 201, NULL, NULL, 'makegroups', 409, 410),
(207, 201, NULL, NULL, 'makegroupssearch', 411, 412),
(208, 201, NULL, NULL, 'maketmgroups', 413, 414),
(209, 201, NULL, NULL, 'savegroups', 415, 416),
(210, 201, NULL, NULL, 'release', 417, 418),
(211, 201, NULL, NULL, 'delete', 419, 420),
(212, 201, NULL, NULL, 'edit', 421, 422),
(213, 201, NULL, NULL, 'changegroupset', 423, 424),
(214, 201, NULL, NULL, 'add', 425, 426),
(215, 201, NULL, NULL, 'view', 427, 428),
(216, 2, NULL, NULL, 'Surveys', 430, 457),
(217, 216, NULL, NULL, 'setUpAjaxList', 431, 432),
(218, 216, NULL, NULL, 'index', 433, 434),
(219, 216, NULL, NULL, 'ajaxList', 435, 436),
(220, 216, NULL, NULL, 'view', 437, 438),
(221, 216, NULL, NULL, 'add', 439, 440),
(222, 216, NULL, NULL, 'edit', 441, 442),
(223, 216, NULL, NULL, 'copy', 443, 444),
(224, 216, NULL, NULL, 'delete', 445, 446),
(225, 216, NULL, NULL, 'questionsSummary', 447, 448),
(226, 216, NULL, NULL, 'moveQuestion', 449, 450),
(227, 216, NULL, NULL, 'removeQuestion', 451, 452),
(228, 216, NULL, NULL, 'addQuestion', 453, 454),
(229, 216, NULL, NULL, 'editQuestion', 455, 456),
(230, 2, NULL, NULL, 'Sysparameters', 458, 473),
(231, 230, NULL, NULL, 'setUpAjaxList', 459, 460),
(232, 230, NULL, NULL, 'index', 461, 462),
(233, 230, NULL, NULL, 'ajaxList', 463, 464),
(234, 230, NULL, NULL, 'view', 465, 466),
(235, 230, NULL, NULL, 'add', 467, 468),
(236, 230, NULL, NULL, 'edit', 469, 470),
(237, 230, NULL, NULL, 'delete', 471, 472),
(238, 2, NULL, NULL, 'Upgrade', 474, 487),
(239, 238, NULL, NULL, 'index', 475, 476),
(240, 238, NULL, NULL, 'step2', 477, 478),
(241, 238, NULL, NULL, 'add', 479, 480),
(242, 238, NULL, NULL, 'edit', 481, 482),
(243, 238, NULL, NULL, 'view', 483, 484),
(244, 238, NULL, NULL, 'delete', 485, 486),
(245, 2, NULL, NULL, 'Users', 488, 521),
(246, 245, NULL, NULL, 'ajaxList', 489, 490),
(247, 245, NULL, NULL, 'index', 491, 492),
(248, 245, NULL, NULL, 'goToClassList', 493, 494),
(249, 245, NULL, NULL, 'determineIfStudentFromThisData', 495, 496),
(250, 245, NULL, NULL, 'view', 497, 498),
(251, 245, NULL, NULL, 'add', 499, 500),
(252, 245, NULL, NULL, 'enrol', 501, 502),
(253, 245, NULL, NULL, 'edit', 503, 504),
(254, 245, NULL, NULL, 'editProfile', 505, 506),
(255, 245, NULL, NULL, 'delete', 507, 508),
(256, 245, NULL, NULL, 'checkDuplicateName', 509, 510),
(257, 245, NULL, NULL, 'resetPassword', 511, 512),
(258, 245, NULL, NULL, 'import', 513, 514),
(259, 245, NULL, NULL, 'merge', 515, 516),
(260, 245, NULL, NULL, 'ajax_merge', 517, 518),
(261, 245, NULL, NULL, 'update', 519, 520),
(262, 2, NULL, NULL, 'V1', 522, 557),
(263, 262, NULL, NULL, 'oauth', 523, 524),
(264, 262, NULL, NULL, 'oauth_error', 525, 526),
(265, 262, NULL, NULL, 'users', 527, 528),
(266, 262, NULL, NULL, 'courses', 529, 530),
(267, 262, NULL, NULL, 'groups', 531, 532),
(268, 262, NULL, NULL, 'groupMembers', 533, 534),
(269, 262, NULL, NULL, 'events', 535, 536),
(270, 262, NULL, NULL, 'grades', 537, 538),
(271, 262, NULL, NULL, 'departments', 539, 540),
(272, 262, NULL, NULL, 'courseDepartments', 541, 542),
(273, 262, NULL, NULL, 'userEvents', 543, 544),
(274, 262, NULL, NULL, 'enrolment', 545, 546),
(275, 262, NULL, NULL, 'add', 547, 548),
(276, 262, NULL, NULL, 'edit', 549, 550),
(277, 262, NULL, NULL, 'index', 551, 552),
(278, 262, NULL, NULL, 'view', 553, 554),
(279, 262, NULL, NULL, 'delete', 555, 556),
(280, 2, NULL, NULL, 'Guard', 558, 575),
(281, 280, NULL, NULL, 'Guard', 559, 574),
(282, 281, NULL, NULL, 'login', 560, 561),
(283, 281, NULL, NULL, 'logout', 562, 563),
(284, 281, NULL, NULL, 'add', 564, 565),
(285, 281, NULL, NULL, 'edit', 566, 567),
(286, 281, NULL, NULL, 'index', 568, 569),
(287, 281, NULL, NULL, 'view', 570, 571),
(288, 281, NULL, NULL, 'delete', 572, 573),
(289, NULL, NULL, NULL, 'functions', 577, 640),
(290, 289, NULL, NULL, 'user', 578, 605),
(291, 290, NULL, NULL, 'superadmin', 579, 580),
(292, 290, NULL, NULL, 'admin', 581, 582),
(293, 290, NULL, NULL, 'instructor', 583, 584),
(294, 290, NULL, NULL, 'tutor', 585, 586),
(295, 290, NULL, NULL, 'student', 587, 588),
(296, 290, NULL, NULL, 'import', 589, 590),
(297, 290, NULL, NULL, 'password_reset', 591, 602),
(298, 297, NULL, NULL, 'superadmin', 592, 593),
(299, 297, NULL, NULL, 'admin', 594, 595),
(300, 297, NULL, NULL, 'instructor', 596, 597),
(301, 297, NULL, NULL, 'tutor', 598, 599),
(302, 297, NULL, NULL, 'student', 600, 601),
(303, 290, NULL, NULL, 'index', 603, 604),
(304, 289, NULL, NULL, 'role', 606, 617),
(305, 304, NULL, NULL, 'superadmin', 607, 608),
(306, 304, NULL, NULL, 'admin', 609, 610),
(307, 304, NULL, NULL, 'instructor', 611, 612),
(308, 304, NULL, NULL, 'tutor', 613, 614),
(309, 304, NULL, NULL, 'student', 615, 616),
(310, 289, NULL, NULL, 'evaluation', 618, 619),
(311, 289, NULL, NULL, 'email', 620, 627),
(312, 311, NULL, NULL, 'allUsers', 621, 622),
(313, 311, NULL, NULL, 'allGroups', 623, 624),
(314, 311, NULL, NULL, 'allCourses', 625, 626),
(315, 289, NULL, NULL, 'emailtemplate', 628, 629),
(316, 289, NULL, NULL, 'viewstudentresults', 630, 631),
(317, 289, NULL, NULL, 'viewemailaddresses', 632, 633),
(318, 289, NULL, NULL, 'superadmin', 634, 635),
(319, 289, NULL, NULL, 'coursemanager', 636, 637),
(320, 289, NULL, NULL, 'viewusername', 638, 639);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Role', 1, NULL, 1, 2),
(2, NULL, 'Role', 2, NULL, 3, 4),
(3, NULL, 'Role', 3, NULL, 5, 6),
(4, NULL, 'Role', 4, NULL, 7, 8),
(5, NULL, 'Role', 5, NULL, 9, 10);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO aros_acos (id, aro_id, aco_id, _create, _read, _update, _delete) VALUES
(1, 1, 2, '1', '1', '1', '1'),
(2, 1, 289, '1', '1', '1', '1'),
(3, 1, 1, '1', '1', '1', '1'),
(4, 2, 2, '-1', '-1', '-1', '-1'),
(5, 2, 116, '1', '1', '1', '1'),
(6, 2, 10, '1', '1', '1', '1'),
(7, 2, 22, '1', '1', '1', '1'),
(8, 2, 28, '1', '1', '1', '1'),
(9, 2, 42, '1', '1', '1', '1'),
(10, 2, 52, '1', '1', '1', '1'),
(11, 2, 58, '1', '1', '1', '1'),
(12, 2, 82, '1', '1', '1', '1'),
(13, 2, 106, '1', '1', '1', '1'),
(14, 2, 139, '1', '1', '1', '1'),
(15, 2, 167, '1', '1', '1', '1'),
(16, 2, 191, '1', '1', '1', '1'),
(17, 2, 216, '1', '1', '1', '1'),
(18, 2, 201, '1', '1', '1', '1'),
(19, 2, 245, '1', '1', '1', '1'),
(20, 2, 283, '1', '1', '1', '1'),
(21, 2, 150, '1', '1', '1', '1'),
(22, 2, 152, '1', '1', '1', '1'),
(23, 2, 156, '1', '1', '1', '1'),
(24, 2, 158, '1', '1', '1', '1'),
(25, 2, 289, '-1', '-1', '-1', '-1'),
(26, 2, 315, '1', '1', '1', '1'),
(27, 2, 310, '1', '1', '1', '1'),
(28, 2, 312, '1', '1', '1', '1'),
(29, 2, 290, '1', '1', '1', '1'),
(30, 2, 292, '1', '1', '1', '-1'),
(31, 2, 291, '-1', '-1', '-1', '-1'),
(32, 2, 317, '1', '1', '1', '1'),
(33, 2, 320, '1', '1', '1', '1'),
(34, 2, 319, '1', '1', '1', '1'),
(35, 2, 318, '-1', '-1', '-1', '-1'),
(36, 3, 2, '-1', '-1', '-1', '-1'),
(37, 3, 116, '1', '1', '1', '1'),
(38, 3, 10, '1', '1', '1', '1'),
(39, 3, 28, '1', '1', '1', '1'),
(40, 3, 42, '1', '1', '1', '1'),
(41, 3, 52, '1', '1', '1', '1'),
(42, 3, 58, '1', '1', '1', '1'),
(43, 3, 82, '1', '1', '1', '1'),
(44, 3, 106, '1', '1', '1', '1'),
(45, 3, 139, '1', '1', '1', '1'),
(46, 3, 167, '1', '1', '1', '1'),
(47, 3, 191, '1', '1', '1', '1'),
(48, 3, 216, '1', '1', '1', '1'),
(49, 3, 201, '1', '1', '1', '1'),
(50, 3, 245, '1', '1', '1', '1'),
(51, 3, 283, '1', '1', '1', '1'),
(52, 3, 150, '1', '1', '1', '1'),
(53, 3, 152, '1', '1', '1', '1'),
(54, 3, 156, '1', '1', '1', '1'),
(55, 3, 158, '1', '1', '1', '1'),
(56, 3, 259, '-1', '-1', '-1', '-1'),
(57, 3, 289, '-1', '-1', '-1', '-1'),
(58, 3, 310, '1', '1', '-1', '-1'),
(59, 3, 290, '1', '1', '1', '1'),
(60, 3, 292, '-1', '-1', '-1', '-1'),
(61, 3, 291, '-1', '-1', '-1', '-1'),
(62, 3, 293, '-1', '1', '-1', '-1'),
(63, 3, 303, '-1', '-1', '-1', '-1'),
(64, 3, 317, '-1', '-1', '-1', '-1'),
(65, 3, 318, '-1', '-1', '-1', '-1'),
(66, 3, 319, '1', '1', '1', '1'),
(67, 4, 2, '-1', '-1', '-1', '-1'),
(68, 4, 116, '1', '1', '1', '1'),
(69, 4, 10, '-1', '-1', '-1', '-1'),
(70, 4, 28, '-1', '-1', '-1', '-1'),
(71, 4, 42, '-1', '-1', '-1', '-1'),
(72, 4, 52, '-1', '-1', '-1', '-1'),
(73, 4, 82, '-1', '-1', '-1', '-1'),
(74, 4, 106, '-1', '-1', '-1', '-1'),
(75, 4, 139, '-1', '-1', '-1', '-1'),
(76, 4, 167, '-1', '-1', '-1', '-1'),
(77, 4, 191, '-1', '-1', '-1', '-1'),
(78, 4, 216, '-1', '-1', '-1', '-1'),
(79, 4, 201, '-1', '-1', '-1', '-1'),
(80, 4, 245, '-1', '-1', '-1', '-1'),
(81, 4, 283, '1', '1', '1', '1'),
(82, 4, 64, '1', '1', '1', '1'),
(83, 4, 69, '1', '1', '1', '1'),
(84, 4, 66, '1', '1', '1', '1'),
(85, 4, 254, '1', '1', '1', '1'),
(86, 4, 289, '-1', '-1', '-1', '-1'),
(87, 4, 317, '-1', '-1', '-1', '-1'),
(88, 4, 318, '-1', '-1', '-1', '-1'),
(89, 5, 2, '-1', '-1', '-1', '-1'),
(90, 5, 116, '1', '1', '1', '1'),
(91, 5, 10, '-1', '-1', '-1', '-1'),
(92, 5, 28, '-1', '-1', '-1', '-1'),
(93, 5, 42, '-1', '-1', '-1', '-1'),
(94, 5, 52, '-1', '-1', '-1', '-1'),
(95, 5, 82, '-1', '-1', '-1', '-1'),
(96, 5, 106, '-1', '-1', '-1', '-1'),
(97, 5, 139, '-1', '-1', '-1', '-1'),
(98, 5, 167, '-1', '-1', '-1', '-1'),
(99, 5, 191, '-1', '-1', '-1', '-1'),
(100, 5, 216, '-1', '-1', '-1', '-1'),
(101, 5, 201, '-1', '-1', '-1', '-1'),
(102, 5, 245, '-1', '-1', '-1', '-1'),
(103, 5, 283, '1', '1', '1', '1'),
(104, 5, 64, '1', '1', '1', '1'),
(105, 5, 69, '1', '1', '1', '1'),
(106, 5, 66, '1', '1', '1', '1'),
(107, 5, 254, '1', '1', '1', '1'),
(108, 5, 150, '1', '1', '1', '1'),
(109, 5, 152, '1', '1', '1', '1'),
(110, 5, 156, '1', '1', '1', '1'),
(111, 5, 158, '1', '1', '1', '1'),
(112, 5, 289, '-1', '-1', '-1', '-1'),
(113, 5, 316, '1', '1', '1', '1'),
(114, 5, 317, '-1', '-1', '-1', '-1'),
(115, 5, 318, '-1', '-1', '-1', '-1');

-- --------------------------------------------------------

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
