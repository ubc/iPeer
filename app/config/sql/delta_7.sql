-- This file contains queries that add a single column to two tables.
-- This column is used to hide/show the evaluation marks to the user.

ALTER TABLE mixeval_questions ADD show_marks INT(1) AFTER scale_level;
ALTER TABLE rubrics_criterias ADD show_marks INT(1) AFTER multiplier;

-- Update database version, done as the very last operation as a sign that
-- the update went well.
INSERT INTO `sys_parameters` (`parameter_code`, `parameter_value`, `parameter_type`, `description`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
('database.version', '7', 'I', 'database version', 'A', 0, NOW(), NULL, NOW());

