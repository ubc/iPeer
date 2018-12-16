INSERT INTO `sys_parameters` (
  `parameter_code`, `parameter_value`,
  `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
  `updater_id`, `modified`)
VALUES (
         'email.reminder_enabled', 'true', 'B',
         'Enable email reminder feature', 'A', 0, NOW(), NULL, NOW()
       );