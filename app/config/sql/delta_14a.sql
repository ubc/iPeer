--
-- Canvas integration
--
INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_baseurl', 'http://dockercanvas_app_1:80', 'S',
    'Base URL for Canvas API', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_enabled', 'true', 'B',
    'Enable Canvas integration', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_user_key', 'integration_id', 'S',
    'Key used to map a Canvas user to iPeer username', 'A', 0, NOW(), NULL, NOW()
);

-- store canvas course id
ALTER TABLE `courses` ADD COLUMN `canvas_id` VARCHAR(25) NULL DEFAULT NULL;