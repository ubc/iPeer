--
-- Canvas integration
--

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
    'system.canvas_baseurl', 'http://dockercanvas_app_1:80', 'S',
    'Base URL for Canvas API', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_baseurl_ext', 'http://localhost:8900', 'S',
    'External Base URL for Canvas API (if not set, will default to canvas_baseurl)', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_user_key', 'integration_id', 'S',
    'Key used to map a Canvas user to iPeer username', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`, `parameter_type`, 
    `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_client_id', '', 'S',
    'Canvas Oauth Client ID', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`, `parameter_type`, 
    `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_client_secret', '', 'E',
    'Canvas Oauth Client Secret', 'A', 0, NOW(), NULL, NOW()
);

INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_force_login', 'false', 'B',
    'Force the user to enter their Canvas credentials when connecting for the first time', 'A', 0, NOW(), NULL, NOW()
);

-- add page permissions --
INSERT INTO `acos`
(`parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`)
	select id, NULL, NULL, 'syncCanvasEnrollment', NULL, NULL
	from `acos`
	where BINARY `alias`='Courses' LIMIT 1;

INSERT INTO `acos`
(`parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`)
	select id, NULL, NULL, 'syncCanvas', NULL, NULL
	from `acos`
	where BINARY `alias`='Groups' LIMIT 1;

-- store canvas course id
ALTER TABLE `courses` ADD COLUMN `canvas_id` VARCHAR(25) NULL DEFAULT NULL;

-- add table to store oauth access/refresh tokens and expiry timestamp of the access token
CREATE TABLE IF NOT EXISTS `user_oauths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `provider` varchar(255) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_duplicates` (`user_id`, `provider`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_oauths_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
AUTO_INCREMENT=1
COLLATE = utf8_general_ci;

-- new parameters to define the behaviour of Canvas API calls
INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_api_timeout', '10', 'I',
    'Canvas API call timeout in seconds', 'A', 0, NOW(), NULL, NOW()
);
INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_api_default_per_page', '500', 'I',
    'Default number of items to retrieve per Canvas API call', 'A', 0, NOW(), NULL, NOW()
);
INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_api_max_retrieve_all', '10000', 'I',
    'Max number of item to retrieve when auto-looping Canvas API pagination to retrieve all records', 'A', 0, NOW(), NULL, NOW()
);
INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'system.canvas_api_max_call', '20', 'I',
    'Max number of API calls when auto-looping Canvas API pagination to retrieve all records', 'A', 0, NOW(), NULL, NOW()
);