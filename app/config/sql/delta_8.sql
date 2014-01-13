-- This file contains queries that add a single column to two tables.
-- This column is used to hide/show the evaluation marks to the user.

ALTER TABLE rubrics ADD view_mode VARCHAR(10) AFTER criteria;

-- Update database version, done as the very last operation as a sign that
-- the update went well.
UPDATE `sys_parameters` SET `parameter_value` = '8' WHERE `parameter_code` = 'database.version';

