DELETE FROM `email_templates` WHERE `id` = '1';

UPDATE `sys_parameters` SET `parameter_value` = '9' WHERE `parameter_code` = 'database.version';