-- There are differences between upgrading from a iPeer v2.x installation
-- and upgrading from an iPeer v3.0.x installation. 

-- This file contains queries specific to upgrading from a iPeer v3.0.x 
-- installation. It should run after the full delta_6 patch file.

ALTER TABLE mixeval_question_descs DROP FOREIGN KEY mixeval_question_descs_ibfk_1;
ALTER TABLE mixeval_question_descs DROP KEY question_num;

-- Remove the orphan records before adding the foreign key so that it will not fail.
DELETE FROM mixeval_question_descs WHERE question_id NOT IN (SELECT id FROM mixeval_questions);
ALTER TABLE mixeval_question_descs ADD FOREIGN KEY (`question_id`) REFERENCES `mixeval_questions` (`id`) ON DELETE CASCADE;

ALTER TABLE simple_evaluations ADD UNIQUE KEY `name` (`name`);

-- Add the system.version that wasn't included in the sql template, this should
-- be the very last operation to indicate that the update was successful

INSERT INTO `sys_parameters` (`parameter_code`, `parameter_value`, `parameter_type`, `description`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) VALUES
('system.version', '3.1.0', 'S', NULL, 'A', 0, NOW(), NULL, NOW());

