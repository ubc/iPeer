ALTER TABLE `evaluation_submissions` MODIFY COLUMN `grp_event_id` INTEGER  DEFAULT NULL;
ALTER TABLE `evaluation_rubrics` ADD COLUMN `rubric_id` INTEGER DEFAULT 0 NOT NULL;
UPDATE `sys_functions` SET `function_code`='USERS' WHERE `function_code`='USR';
