-- This file contains queries that add a single column to two tables.
-- This column is used to hide/show the evaluation marks to the user.

ALTER TABLE rubrics ADD view_mode VARCHAR(10) AFTER criteria;

-- Update database version, done as the very last operation as a sign that
-- the update went well.
INSERT INTO `sys_parameters` (`parameter_code`, `parameter_value`, `parameter_type`, `description`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
('database.version', '8', 'I', 'database version', 'A', 0, NOW(), NULL, NOW());

