-- There are differences between upgrading from a iPeer v2.x installation
-- and upgrading from an iPeer v3.0.x installation. 

-- This file contains queries specific to upgrading from a iPeer v2.x 
-- installation. It should run after the full delta_6 patch file.
ALTER TABLE mixeval_question_descs DROP FOREIGN KEY mixeval_question_descs_ibfk_1;

ALTER TABLE simple_evaluations DROP point_low_limit;
ALTER TABLE simple_evaluations DROP point_high_limit;

INSERT INTO `sys_parameters` (`parameter_code`, `parameter_value`, `parameter_type`, `description`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
('email.port', '', 'S', 'port number for email smtp option', 'A', 0, '2013-03-06 17:08:52', NULL, '2013-03-06 17:09:04'),
('email.host', '', 'S', 'host address for email smtp option', 'A', 0, '2013-03-06 17:08:52', NULL, '2013-03-06 17:09:04'),
('email.username', '', 'S', 'username for email smtp option', 'A', 0, '2013-03-06 17:08:52', NULL, '2013-03-06 17:09:04'),
('email.password', '', 'S', 'password for email smtp option', 'A', 0, '2013-03-06 17:08:52', NULL, '2013-03-06 17:09:04');

ALTER TABLE rubrics_criteria_comments DROP FOREIGN KEY rubrics_criteria_comments_ibfk_1;
ALTER TABLE rubrics_criteria_comments DROP FOREIGN KEY rubrics_criteria_comments_ibfk_2;

ALTER TABLE `user_enrols` DROP INDEX `course_id`;
ALTER TABLE `user_enrols` DROP INDEX `user_id_index`;

----------------------------------------------------------------

-- Update database version, done as the very last operation as a sign that
-- the update went well.
INSERT INTO `sys_parameters` (`parameter_code`, `parameter_value`, `parameter_type`, `description`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
('database.version', '6', 'I', 'database version', 'A', 0, NOW(), NULL, NOW());
