--
-- Canvas integration
--
INSERT INTO `ipeer`.`sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_baseurl', 'http://dockercanvas_app_1:80', 'S',
    'Base URL for Canvas API', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `ipeer`.`sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_enabled', 'true', 'B',
    'Enable Canvas integration', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `ipeer`.`sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_user_key', 'integration_id', 'S',
    'Key used to map a Canvas user to iPeer username', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `ipeer`.`acos`
(`parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`)
	select id, NULL, NULL, 'syncCanvasEnrollment', NULL, NULL
	from `ipeer`.`acos`
	where BINARY `alias`='Courses' LIMIT 1;


-- store canvas course id
ALTER TABLE `ipeer`.`courses` ADD COLUMN `canvas_id` VARCHAR(25) NULL DEFAULT NULL;