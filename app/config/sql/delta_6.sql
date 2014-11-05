SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

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

-- clean up some wired characters, the conversion below doesn't like them
UPDATE users SET first_name = TRIM(REPLACE(first_name, "ï¿½", ' ')) WHERE  `first_name` LIKE  '%ï¿½%';
UPDATE users SET last_name = TRIM(REPLACE(last_name, "ï¿½", ' ')) WHERE  `last_name` LIKE  '%ï¿½%';
UPDATE users SET first_name = TRIM(REPLACE(first_name, concat(0x89,0xF7,0xBC), ' ')) WHERE  `first_name` LIKE  concat('%',0x89,0xF7,0xBC,'%');
UPDATE users SET last_name = TRIM(REPLACE(last_name, concat(0x89,0xF7,0xBC), ' ')) WHERE  `last_name` LIKE  concat('%',0x89,0xF7,0xBC,'%');
UPDATE users SET first_name = TRIM(REPLACE(first_name, 0xa0, ' ')) WHERE  `first_name` LIKE  concat('%',0xa0,'%');
UPDATE users SET last_name = TRIM(REPLACE(last_name, 0xa0, ' ')) WHERE  `last_name` LIKE  concat('%',0xa0,'%');
UPDATE users SET first_name = TRIM(REPLACE(first_name, 0xf8, ' ')) WHERE  `first_name` LIKE  concat('%',0xf8,'%');
UPDATE users SET last_name = TRIM(REPLACE(last_name, 0xf8, ' ')) WHERE  `last_name` LIKE  concat('%',0xf8,'%');
UPDATE users SET first_name = TRIM(REPLACE(first_name, 0x81, ' ')) WHERE  `first_name` LIKE  concat('%',0x81,'%');
UPDATE users SET last_name = TRIM(REPLACE(last_name, 0x81, ' ')) WHERE  `last_name` LIKE  concat('%',0x81,'%');
UPDATE users SET first_name = TRIM(REPLACE(first_name, 0xff, ' ')) WHERE  `first_name` LIKE  concat('%',0xff,'%');
UPDATE users SET last_name = TRIM(REPLACE(last_name, 0xff, ' ')) WHERE  `last_name` LIKE  concat('%',0xff,'%');
UPDATE users SET first_name = TRIM(REPLACE(first_name, 0xe5, '')) WHERE  `first_name` LIKE  concat('%',0xe5,'%');
UPDATE users SET last_name = TRIM(REPLACE(last_name, 0xe5, '')) WHERE  `last_name` LIKE  concat('%',0xe5,'%');
UPDATE users SET first_name = TRIM(REPLACE(first_name, 0xe6, '')) WHERE  `first_name` LIKE  concat('%',0xe6,'%');
UPDATE users SET last_name = TRIM(REPLACE(last_name, 0xe6, '')) WHERE  `last_name` LIKE  concat('%',0xe6,'%');
UPDATE users SET first_name = TRIM(REPLACE(first_name, 0xe9, '')) WHERE  `first_name` LIKE  concat('%',0xe9,'%');
UPDATE users SET last_name = TRIM(REPLACE(last_name, 0xe9, '')) WHERE  `last_name` LIKE  concat('%',0xe9,'%');

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
ALTER TABLE events CHANGE description description BLOB;
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

DROP TABLE IF EXISTS `mixeval_question_types`;
CREATE TABLE IF NOT EXISTS `mixeval_question_types` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`type` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `mixeval_question_types` (`id`, `type`) VALUES
(1, 'Likert'),
(2, 'Paragraph'),
(3, 'Sentence'),
(4, 'ScoreDropdown');

ALTER TABLE mixevals_questions RENAME mixeval_questions;
ALTER TABLE mixeval_questions ADD mixeval_question_type_id int(11) NOT NULL;
ALTER TABLE mixeval_questions ADD self_eval tinyint(1) NOT NULL DEFAULT '0';
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

INSERT INTO `sys_parameters` (`parameter_code`, `parameter_value`, `parameter_type`, `description`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
('system.absolute_url', '', 'S', 'base url to iPeer', 'A', 0, NOW(), 0, NOW()),
('google_analytics.tracking_id', '', 'S', 'tracking id for Google Analytics', 'A', 0, NOW(), 0, NOW()),
('google_analytics.domain', '', 'S', 'domain name for Google Analytics', 'A', 0, NOW(), 0, NOW()),
('banner.custom_logo', '', 'S', 'custom logo that appears on the left side of the banner', 'A', 0, NOW(), 0, NOW()),
('system.timezone', '', 'S', 'timezone', 'A', 0, NOW(), 0, NOW());

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
(2, NULL, NULL, NULL, 'controllers', 3, 592),
(3, 2, NULL, NULL, 'Pages', 4, 17),
(4, 3, NULL, NULL, 'display', 5, 6),
(5, 3, NULL, NULL, 'add', 7, 8),
(6, 3, NULL, NULL, 'edit', 9, 10),
(7, 3, NULL, NULL, 'index', 11, 12),
(8, 3, NULL, NULL, 'view', 13, 14),
(9, 3, NULL, NULL, 'delete', 15, 16),
(10, 2, NULL, NULL, 'Accesses', 18, 29),
(11, 10, NULL, NULL, 'view', 19, 20),
(12, 10, NULL, NULL, 'edit', 21, 22),
(13, 10, NULL, NULL, 'add', 23, 24),
(14, 10, NULL, NULL, 'index', 25, 26),
(15, 10, NULL, NULL, 'delete', 27, 28),
(16, 2, NULL, NULL, 'Courses', 30, 53),
(17, 16, NULL, NULL, 'daysLate', 31, 32),
(18, 16, NULL, NULL, 'index', 33, 34),
(19, 16, NULL, NULL, 'ajaxList', 35, 36),
(20, 16, NULL, NULL, 'view', 37, 38),
(21, 16, NULL, NULL, 'home', 39, 40),
(22, 16, NULL, NULL, 'add', 41, 42),
(23, 16, NULL, NULL, 'edit', 43, 44),
(24, 16, NULL, NULL, 'delete', 45, 46),
(25, 16, NULL, NULL, 'move', 47, 48),
(26, 16, NULL, NULL, 'ajax_options', 49, 50),
(27, 16, NULL, NULL, 'import', 51, 52),
(28, 2, NULL, NULL, 'Departments', 54, 65),
(29, 28, NULL, NULL, 'index', 55, 56),
(30, 28, NULL, NULL, 'view', 57, 58),
(31, 28, NULL, NULL, 'add', 59, 60),
(32, 28, NULL, NULL, 'edit', 61, 62),
(33, 28, NULL, NULL, 'delete', 63, 64),
(34, 2, NULL, NULL, 'Emailer', 66, 93),
(35, 34, NULL, NULL, 'setUpAjaxList', 67, 68),
(36, 34, NULL, NULL, 'ajaxList', 69, 70),
(37, 34, NULL, NULL, 'index', 71, 72),
(38, 34, NULL, NULL, 'write', 73, 74),
(39, 34, NULL, NULL, 'cancel', 75, 76),
(40, 34, NULL, NULL, 'view', 77, 78),
(41, 34, NULL, NULL, 'addRecipient', 79, 80),
(42, 34, NULL, NULL, 'deleteRecipient', 81, 82),
(43, 34, NULL, NULL, 'getRecipient', 83, 84),
(44, 34, NULL, NULL, 'searchByUserId', 85, 86),
(45, 34, NULL, NULL, 'add', 87, 88),
(46, 34, NULL, NULL, 'edit', 89, 90),
(47, 34, NULL, NULL, 'delete', 91, 92),
(48, 2, NULL, NULL, 'Emailtemplates', 94, 113),
(49, 48, NULL, NULL, 'setUpAjaxList', 95, 96),
(50, 48, NULL, NULL, 'ajaxList', 97, 98),
(51, 48, NULL, NULL, 'index', 99, 100),
(52, 48, NULL, NULL, 'add', 101, 102),
(53, 48, NULL, NULL, 'edit', 103, 104),
(54, 48, NULL, NULL, 'delete', 105, 106),
(55, 48, NULL, NULL, 'view', 107, 108),
(56, 48, NULL, NULL, 'displayTemplateContent', 109, 110),
(57, 48, NULL, NULL, 'displayTemplateSubject', 111, 112),
(58, 2, NULL, NULL, 'Evaltools', 114, 125),
(59, 58, NULL, NULL, 'index', 115, 116),
(60, 58, NULL, NULL, 'add', 117, 118),
(61, 58, NULL, NULL, 'edit', 119, 120),
(62, 58, NULL, NULL, 'view', 121, 122),
(63, 58, NULL, NULL, 'delete', 123, 124),
(64, 2, NULL, NULL, 'Evaluations', 126, 165),
(65, 64, NULL, NULL, 'setUpAjaxList', 127, 128),
(66, 64, NULL, NULL, 'ajaxList', 129, 130),
(67, 64, NULL, NULL, 'view', 131, 132),
(68, 64, NULL, NULL, 'index', 133, 134),
(69, 64, NULL, NULL, 'export', 135, 136),
(70, 64, NULL, NULL, 'makeEvaluation', 137, 138),
(71, 64, NULL, NULL, 'completeEvaluationRubric', 139, 140),
(72, 64, NULL, NULL, 'viewEvaluationResults', 141, 142),
(73, 64, NULL, NULL, 'studentViewEvaluationResult', 143, 144),
(74, 64, NULL, NULL, 'markEventReviewed', 145, 146),
(75, 64, NULL, NULL, 'markGradeRelease', 147, 148),
(76, 64, NULL, NULL, 'markCommentRelease', 149, 150),
(77, 64, NULL, NULL, 'changeAllCommentRelease', 151, 152),
(78, 64, NULL, NULL, 'changeAllGradeRelease', 153, 154),
(79, 64, NULL, NULL, 'viewGroupSubmissionDetails', 155, 156),
(80, 64, NULL, NULL, 'viewSurveySummary', 157, 158),
(81, 64, NULL, NULL, 'add', 159, 160),
(82, 64, NULL, NULL, 'edit', 161, 162),
(83, 64, NULL, NULL, 'delete', 163, 164),
(84, 2, NULL, NULL, 'Events', 166, 193),
(85, 84, NULL, NULL, 'postProcessData', 167, 168),
(86, 84, NULL, NULL, 'setUpAjaxList', 169, 170),
(87, 84, NULL, NULL, 'index', 171, 172),
(88, 84, NULL, NULL, 'ajaxList', 173, 174),
(89, 84, NULL, NULL, 'view', 175, 176),
(90, 84, NULL, NULL, 'add', 177, 178),
(91, 84, NULL, NULL, 'setSchedule', 179, 180),
(92, 84, NULL, NULL, 'getGroupMembers', 181, 182),
(93, 84, NULL, NULL, 'edit', 183, 184),
(94, 84, NULL, NULL, 'checkIfChanged', 185, 186),
(95, 84, NULL, NULL, 'calculateFrequency', 187, 188),
(96, 84, NULL, NULL, 'delete', 189, 190),
(97, 84, NULL, NULL, 'checkDuplicateName', 191, 192),
(98, 2, NULL, NULL, 'Faculties', 194, 205),
(99, 98, NULL, NULL, 'index', 195, 196),
(100, 98, NULL, NULL, 'view', 197, 198),
(101, 98, NULL, NULL, 'add', 199, 200),
(102, 98, NULL, NULL, 'edit', 201, 202),
(103, 98, NULL, NULL, 'delete', 203, 204),
(104, 2, NULL, NULL, 'Framework', 206, 221),
(105, 104, NULL, NULL, 'calendarDisplay', 207, 208),
(106, 104, NULL, NULL, 'tutIndex', 209, 210),
(107, 104, NULL, NULL, 'add', 211, 212),
(108, 104, NULL, NULL, 'edit', 213, 214),
(109, 104, NULL, NULL, 'index', 215, 216),
(110, 104, NULL, NULL, 'view', 217, 218),
(111, 104, NULL, NULL, 'delete', 219, 220),
(112, 2, NULL, NULL, 'Groups', 222, 241),
(113, 112, NULL, NULL, 'setUpAjaxList', 223, 224),
(114, 112, NULL, NULL, 'index', 225, 226),
(115, 112, NULL, NULL, 'ajaxList', 227, 228),
(116, 112, NULL, NULL, 'view', 229, 230),
(117, 112, NULL, NULL, 'add', 231, 232),
(118, 112, NULL, NULL, 'edit', 233, 234),
(119, 112, NULL, NULL, 'delete', 235, 236),
(120, 112, NULL, NULL, 'import', 237, 238),
(121, 112, NULL, NULL, 'export', 239, 240),
(122, 2, NULL, NULL, 'Home', 242, 253),
(123, 122, NULL, NULL, 'index', 243, 244),
(124, 122, NULL, NULL, 'add', 245, 246),
(125, 122, NULL, NULL, 'edit', 247, 248),
(126, 122, NULL, NULL, 'view', 249, 250),
(127, 122, NULL, NULL, 'delete', 251, 252),
(128, 2, NULL, NULL, 'Install', 254, 275),
(129, 128, NULL, NULL, 'index', 255, 256),
(130, 128, NULL, NULL, 'install2', 257, 258),
(131, 128, NULL, NULL, 'install3', 259, 260),
(132, 128, NULL, NULL, 'install4', 261, 262),
(133, 128, NULL, NULL, 'install5', 263, 264),
(134, 128, NULL, NULL, 'gpl', 265, 266),
(135, 128, NULL, NULL, 'add', 267, 268),
(136, 128, NULL, NULL, 'edit', 269, 270),
(137, 128, NULL, NULL, 'view', 271, 272),
(138, 128, NULL, NULL, 'delete', 273, 274),
(139, 2, NULL, NULL, 'Lti', 276, 287),
(140, 139, NULL, NULL, 'index', 277, 278),
(141, 139, NULL, NULL, 'add', 279, 280),
(142, 139, NULL, NULL, 'edit', 281, 282),
(143, 139, NULL, NULL, 'view', 283, 284),
(144, 139, NULL, NULL, 'delete', 285, 286),
(145, 2, NULL, NULL, 'Mixevals', 288, 305),
(146, 145, NULL, NULL, 'setUpAjaxList', 289, 290),
(147, 145, NULL, NULL, 'index', 291, 292),
(148, 145, NULL, NULL, 'ajaxList', 293, 294),
(149, 145, NULL, NULL, 'view', 295, 296),
(150, 145, NULL, NULL, 'add', 297, 298),
(151, 145, NULL, NULL, 'edit', 299, 300),
(152, 145, NULL, NULL, 'copy', 301, 302),
(153, 145, NULL, NULL, 'delete', 303, 304),
(154, 2, NULL, NULL, 'Oauthclients', 306, 317),
(155, 154, NULL, NULL, 'index', 307, 308),
(156, 154, NULL, NULL, 'add', 309, 310),
(157, 154, NULL, NULL, 'edit', 311, 312),
(158, 154, NULL, NULL, 'delete', 313, 314),
(159, 154, NULL, NULL, 'view', 315, 316),
(160, 2, NULL, NULL, 'Oauthtokens', 318, 329),
(161, 160, NULL, NULL, 'index', 319, 320),
(162, 160, NULL, NULL, 'add', 321, 322),
(163, 160, NULL, NULL, 'edit', 323, 324),
(164, 160, NULL, NULL, 'delete', 325, 326),
(165, 160, NULL, NULL, 'view', 327, 328),
(166, 2, NULL, NULL, 'Penalty', 330, 343),
(167, 166, NULL, NULL, 'save', 331, 332),
(168, 166, NULL, NULL, 'add', 333, 334),
(169, 166, NULL, NULL, 'edit', 335, 336),
(170, 166, NULL, NULL, 'index', 337, 338),
(171, 166, NULL, NULL, 'view', 339, 340),
(172, 166, NULL, NULL, 'delete', 341, 342),
(173, 2, NULL, NULL, 'Rubrics', 344, 363),
(174, 173, NULL, NULL, 'postProcess', 345, 346),
(175, 173, NULL, NULL, 'setUpAjaxList', 347, 348),
(176, 173, NULL, NULL, 'index', 349, 350),
(177, 173, NULL, NULL, 'ajaxList', 351, 352),
(178, 173, NULL, NULL, 'view', 353, 354),
(179, 173, NULL, NULL, 'add', 355, 356),
(180, 173, NULL, NULL, 'edit', 357, 358),
(181, 173, NULL, NULL, 'copy', 359, 360),
(182, 173, NULL, NULL, 'delete', 361, 362),
(183, 2, NULL, NULL, 'Searchs', 364, 391),
(184, 183, NULL, NULL, 'update', 365, 366),
(185, 183, NULL, NULL, 'index', 367, 368),
(186, 183, NULL, NULL, 'searchEvaluation', 369, 370),
(187, 183, NULL, NULL, 'searchResult', 371, 372),
(188, 183, NULL, NULL, 'searchInstructor', 373, 374),
(189, 183, NULL, NULL, 'eventBoxSearch', 375, 376),
(190, 183, NULL, NULL, 'formatSearchEvaluation', 377, 378),
(191, 183, NULL, NULL, 'formatSearchInstructor', 379, 380),
(192, 183, NULL, NULL, 'formatSearchEvaluationResult', 381, 382),
(193, 183, NULL, NULL, 'add', 383, 384),
(194, 183, NULL, NULL, 'edit', 385, 386),
(195, 183, NULL, NULL, 'view', 387, 388),
(196, 183, NULL, NULL, 'delete', 389, 390),
(197, 2, NULL, NULL, 'Simpleevaluations', 392, 411),
(198, 197, NULL, NULL, 'postProcess', 393, 394),
(199, 197, NULL, NULL, 'setUpAjaxList', 395, 396),
(200, 197, NULL, NULL, 'index', 397, 398),
(201, 197, NULL, NULL, 'ajaxList', 399, 400),
(202, 197, NULL, NULL, 'view', 401, 402),
(203, 197, NULL, NULL, 'add', 403, 404),
(204, 197, NULL, NULL, 'edit', 405, 406),
(205, 197, NULL, NULL, 'copy', 407, 408),
(206, 197, NULL, NULL, 'delete', 409, 410),
(207, 2, NULL, NULL, 'Surveygroups', 412, 443),
(208, 207, NULL, NULL, 'postProcess', 413, 414),
(209, 207, NULL, NULL, 'setUpAjaxList', 415, 416),
(210, 207, NULL, NULL, 'index', 417, 418),
(211, 207, NULL, NULL, 'ajaxList', 419, 420),
(212, 207, NULL, NULL, 'makegroups', 421, 422),
(213, 207, NULL, NULL, 'makegroupssearch', 423, 424),
(214, 207, NULL, NULL, 'maketmgroups', 425, 426),
(215, 207, NULL, NULL, 'savegroups', 427, 428),
(216, 207, NULL, NULL, 'export', 429, 430),
(217, 207, NULL, NULL, 'release', 431, 432),
(218, 207, NULL, NULL, 'delete', 433, 434),
(219, 207, NULL, NULL, 'edit', 435, 436),
(220, 207, NULL, NULL, 'changegroupset', 437, 438),
(221, 207, NULL, NULL, 'add', 439, 440),
(222, 207, NULL, NULL, 'view', 441, 442),
(223, 2, NULL, NULL, 'Surveys', 444, 473),
(224, 223, NULL, NULL, 'setUpAjaxList', 445, 446),
(225, 223, NULL, NULL, 'index', 447, 448),
(226, 223, NULL, NULL, 'ajaxList', 449, 450),
(227, 223, NULL, NULL, 'view', 451, 452),
(228, 223, NULL, NULL, 'add', 453, 454),
(229, 223, NULL, NULL, 'edit', 455, 456),
(230, 223, NULL, NULL, 'copy', 457, 458),
(231, 223, NULL, NULL, 'delete', 459, 460),
(232, 223, NULL, NULL, 'questionsSummary', 461, 462),
(233, 223, NULL, NULL, 'moveQuestion', 463, 464),
(234, 223, NULL, NULL, 'removeQuestion', 465, 466),
(235, 223, NULL, NULL, 'addQuestion', 467, 468),
(236, 223, NULL, NULL, 'editQuestion', 469, 470),
(237, 223, NULL, NULL, 'surveyAccess', 471, 472),
(238, 2, NULL, NULL, 'Sysparameters', 474, 489),
(239, 238, NULL, NULL, 'setUpAjaxList', 475, 476),
(240, 238, NULL, NULL, 'index', 477, 478),
(241, 238, NULL, NULL, 'ajaxList', 479, 480),
(242, 238, NULL, NULL, 'view', 481, 482),
(243, 238, NULL, NULL, 'add', 483, 484),
(244, 238, NULL, NULL, 'edit', 485, 486),
(245, 238, NULL, NULL, 'delete', 487, 488),
(246, 2, NULL, NULL, 'Upgrade', 490, 503),
(247, 246, NULL, NULL, 'index', 491, 492),
(248, 246, NULL, NULL, 'step2', 493, 494),
(249, 246, NULL, NULL, 'add', 495, 496),
(250, 246, NULL, NULL, 'edit', 497, 498),
(251, 246, NULL, NULL, 'view', 499, 500),
(252, 246, NULL, NULL, 'delete', 501, 502),
(253, 2, NULL, NULL, 'Users', 504, 537),
(254, 253, NULL, NULL, 'ajaxList', 505, 506),
(255, 253, NULL, NULL, 'index', 507, 508),
(256, 253, NULL, NULL, 'goToClassList', 509, 510),
(257, 253, NULL, NULL, 'determineIfStudentFromThisData', 511, 512),
(258, 253, NULL, NULL, 'view', 513, 514),
(259, 253, NULL, NULL, 'add', 515, 516),
(260, 253, NULL, NULL, 'enrol', 517, 518),
(261, 253, NULL, NULL, 'edit', 519, 520),
(262, 253, NULL, NULL, 'editProfile', 521, 522),
(263, 253, NULL, NULL, 'delete', 523, 524),
(264, 253, NULL, NULL, 'checkDuplicateName', 525, 526),
(265, 253, NULL, NULL, 'resetPassword', 527, 528),
(266, 253, NULL, NULL, 'import', 529, 530),
(267, 253, NULL, NULL, 'merge', 531, 532),
(268, 253, NULL, NULL, 'ajax_merge', 533, 534),
(269, 253, NULL, NULL, 'update', 535, 536),
(270, 2, NULL, NULL, 'V1', 538, 573),
(271, 270, NULL, NULL, 'oauth', 539, 540),
(272, 270, NULL, NULL, 'oauth_error', 541, 542),
(273, 270, NULL, NULL, 'users', 543, 544),
(274, 270, NULL, NULL, 'courses', 545, 546),
(275, 270, NULL, NULL, 'groups', 547, 548),
(276, 270, NULL, NULL, 'groupMembers', 549, 550),
(277, 270, NULL, NULL, 'events', 551, 552),
(278, 270, NULL, NULL, 'grades', 553, 554),
(279, 270, NULL, NULL, 'departments', 555, 556),
(280, 270, NULL, NULL, 'courseDepartments', 557, 558),
(281, 270, NULL, NULL, 'userEvents', 559, 560),
(282, 270, NULL, NULL, 'enrolment', 561, 562),
(283, 270, NULL, NULL, 'add', 563, 564),
(284, 270, NULL, NULL, 'edit', 565, 566),
(285, 270, NULL, NULL, 'index', 567, 568),
(286, 270, NULL, NULL, 'view', 569, 570),
(287, 270, NULL, NULL, 'delete', 571, 572),
(288, 2, NULL, NULL, 'Guard', 574, 591),
(289, 288, NULL, NULL, 'Guard', 575, 590),
(290, 289, NULL, NULL, 'login', 576, 577),
(291, 289, NULL, NULL, 'logout', 578, 579),
(292, 289, NULL, NULL, 'add', 580, 581),
(293, 289, NULL, NULL, 'edit', 582, 583),
(294, 289, NULL, NULL, 'index', 584, 585),
(295, 289, NULL, NULL, 'view', 586, 587),
(296, 289, NULL, NULL, 'delete', 588, 589),
(297, NULL, NULL, NULL, 'functions', 593, 656),
(298, 297, NULL, NULL, 'user', 594, 621),
(299, 298, NULL, NULL, 'superadmin', 595, 596),
(300, 298, NULL, NULL, 'admin', 597, 598),
(301, 298, NULL, NULL, 'instructor', 599, 600),
(302, 298, NULL, NULL, 'tutor', 601, 602),
(303, 298, NULL, NULL, 'student', 603, 604),
(304, 298, NULL, NULL, 'import', 605, 606),
(305, 298, NULL, NULL, 'password_reset', 607, 618),
(306, 305, NULL, NULL, 'superadmin', 608, 609),
(307, 305, NULL, NULL, 'admin', 610, 611),
(308, 305, NULL, NULL, 'instructor', 612, 613),
(309, 305, NULL, NULL, 'tutor', 614, 615),
(310, 305, NULL, NULL, 'student', 616, 617),
(311, 298, NULL, NULL, 'index', 619, 620),
(312, 297, NULL, NULL, 'role', 622, 633),
(313, 312, NULL, NULL, 'superadmin', 623, 624),
(314, 312, NULL, NULL, 'admin', 625, 626),
(315, 312, NULL, NULL, 'instructor', 627, 628),
(316, 312, NULL, NULL, 'tutor', 629, 630),
(317, 312, NULL, NULL, 'student', 631, 632),
(318, 297, NULL, NULL, 'evaluation', 634, 635),
(319, 297, NULL, NULL, 'email', 636, 643),
(320, 319, NULL, NULL, 'allUsers', 637, 638),
(321, 319, NULL, NULL, 'allGroups', 639, 640),
(322, 319, NULL, NULL, 'allCourses', 641, 642),
(323, 297, NULL, NULL, 'emailtemplate', 644, 645),
(324, 297, NULL, NULL, 'viewstudentresults', 646, 647),
(325, 297, NULL, NULL, 'viewemailaddresses', 648, 649),
(326, 297, NULL, NULL, 'superadmin', 650, 651),
(327, 297, NULL, NULL, 'coursemanager', 652, 653),
(328, 297, NULL, NULL, 'viewusername', 654, 655);

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
(2, 1, 297, '1', '1', '1', '1'),
(3, 1, 1, '1', '1', '1', '1'),
(4, 2, 2, '-1', '-1', '-1', '-1'),
(5, 2, 122, '1', '1', '1', '1'),
(6, 2, 16, '1', '1', '1', '1'),
(7, 2, 28, '1', '1', '1', '1'),
(8, 2, 31, '-1', '-1', '-1', '-1'),
(9, 2, 30, '-1', '-1', '-1', '-1'),
(10, 2, 33, '-1', '-1', '-1', '-1'),
(11, 2, 32, '-1', '-1', '-1', '-1'),
(12, 2, 29, '-1', '-1', '-1', '-1'),
(13, 2, 34, '1', '1', '1', '1'),
(14, 2, 48, '1', '1', '1', '1'),
(15, 2, 58, '1', '1', '1', '1'),
(16, 2, 64, '1', '1', '1', '1'),
(17, 2, 84, '1', '1', '1', '1'),
(18, 2, 112, '1', '1', '1', '1'),
(19, 2, 145, '1', '1', '1', '1'),
(20, 2, 173, '1', '1', '1', '1'),
(21, 2, 197, '1', '1', '1', '1'),
(22, 2, 223, '1', '1', '1', '1'),
(23, 2, 207, '1', '1', '1', '1'),
(24, 2, 253, '1', '1', '1', '1'),
(25, 2, 291, '1', '1', '1', '1'),
(26, 2, 297, '-1', '-1', '-1', '-1'),
(27, 2, 323, '1', '1', '1', '1'),
(28, 2, 318, '1', '1', '1', '1'),
(29, 2, 320, '1', '1', '1', '1'),
(30, 2, 298, '1', '1', '1', '1'),
(31, 2, 300, '1', '1', '1', '-1'),
(32, 2, 299, '-1', '-1', '-1', '-1'),
(33, 2, 325, '1', '1', '1', '1'),
(34, 2, 328, '1', '1', '1', '1'),
(35, 2, 327, '1', '1', '1', '1'),
(36, 2, 326, '-1', '-1', '-1', '-1'),
(37, 3, 2, '-1', '-1', '-1', '-1'),
(38, 3, 122, '1', '1', '1', '1'),
(39, 3, 16, '1', '1', '1', '1'),
(40, 3, 34, '1', '1', '1', '1'),
(41, 3, 48, '1', '1', '1', '1'),
(42, 3, 58, '1', '1', '1', '1'),
(43, 3, 64, '1', '1', '1', '1'),
(44, 3, 84, '1', '1', '1', '1'),
(45, 3, 112, '1', '1', '1', '1'),
(46, 3, 145, '1', '1', '1', '1'),
(47, 3, 173, '1', '1', '1', '1'),
(48, 3, 197, '1', '1', '1', '1'),
(49, 3, 223, '1', '1', '1', '1'),
(50, 3, 207, '1', '1', '1', '1'),
(51, 3, 253, '1', '1', '1', '1'),
(52, 3, 291, '1', '1', '1', '1'),
(53, 3, 156, '1', '1', '1', '1'),
(54, 3, 158, '1', '1', '1', '1'),
(55, 3, 162, '1', '1', '1', '1'),
(56, 3, 164, '1', '1', '1', '1'),
(57, 3, 267, '-1', '-1', '-1', '-1'),
(58, 3, 297, '-1', '-1', '-1', '-1'),
(59, 3, 318, '1', '1', '-1', '-1'),
(60, 3, 298, '1', '1', '1', '1'),
(61, 3, 300, '-1', '-1', '-1', '-1'),
(62, 3, 299, '-1', '-1', '-1', '-1'),
(63, 3, 301, '-1', '1', '-1', '-1'),
(64, 3, 311, '-1', '-1', '-1', '-1'),
(65, 3, 325, '-1', '-1', '-1', '-1'),
(66, 3, 326, '-1', '-1', '-1', '-1'),
(67, 3, 327, '1', '1', '1', '1'),
(68, 4, 2, '-1', '-1', '-1', '-1'),
(69, 4, 122, '1', '1', '1', '1'),
(70, 4, 16, '-1', '-1', '-1', '-1'),
(71, 4, 34, '-1', '-1', '-1', '-1'),
(72, 4, 48, '-1', '-1', '-1', '-1'),
(73, 4, 58, '-1', '-1', '-1', '-1'),
(74, 4, 84, '-1', '-1', '-1', '-1'),
(75, 4, 112, '-1', '-1', '-1', '-1'),
(76, 4, 145, '-1', '-1', '-1', '-1'),
(77, 4, 173, '-1', '-1', '-1', '-1'),
(78, 4, 197, '-1', '-1', '-1', '-1'),
(79, 4, 223, '-1', '-1', '-1', '-1'),
(80, 4, 207, '-1', '-1', '-1', '-1'),
(81, 4, 253, '-1', '-1', '-1', '-1'),
(82, 4, 291, '1', '1', '1', '1'),
(83, 4, 70, '1', '1', '1', '1'),
(84, 4, 73, '1', '1', '1', '1'),
(85, 4, 71, '1', '1', '1', '1'),
(86, 4, 262, '1', '1', '1', '1'),
(87, 4, 297, '-1', '-1', '-1', '-1'),
(88, 4, 325, '-1', '-1', '-1', '-1'),
(89, 4, 326, '-1', '-1', '-1', '-1'),
(90, 5, 2, '-1', '-1', '-1', '-1'),
(91, 5, 122, '1', '1', '1', '1'),
(92, 5, 16, '-1', '-1', '-1', '-1'),
(93, 5, 34, '-1', '-1', '-1', '-1'),
(94, 5, 48, '-1', '-1', '-1', '-1'),
(95, 5, 58, '-1', '-1', '-1', '-1'),
(96, 5, 84, '-1', '-1', '-1', '-1'),
(97, 5, 112, '-1', '-1', '-1', '-1'),
(98, 5, 145, '-1', '-1', '-1', '-1'),
(99, 5, 173, '-1', '-1', '-1', '-1'),
(100, 5, 197, '-1', '-1', '-1', '-1'),
(101, 5, 223, '-1', '-1', '-1', '-1'),
(102, 5, 207, '-1', '-1', '-1', '-1'),
(103, 5, 253, '-1', '-1', '-1', '-1'),
(104, 5, 291, '1', '1', '1', '1'),
(105, 5, 70, '1', '1', '1', '1'),
(106, 5, 73, '1', '1', '1', '1'),
(107, 5, 71, '1', '1', '1', '1'),
(108, 5, 262, '1', '1', '1', '1'),
(109, 5, 156, '1', '1', '1', '1'),
(110, 5, 158, '1', '1', '1', '1'),
(111, 5, 162, '1', '1', '1', '1'),
(112, 5, 164, '1', '1', '1', '1'),
(113, 5, 297, '-1', '-1', '-1', '-1'),
(114, 5, 324, '1', '1', '1', '1'),
(115, 5, 325, '-1', '-1', '-1', '-1'),
(116, 5, 326, '-1', '-1', '-1', '-1');

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
