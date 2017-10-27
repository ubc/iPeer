--
-- Canvas integration
--
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
    'system.canvas_client_secret', '', 'S',
    'Canvas Oauth Client Secret', 'E', 0, NOW(), NULL, NOW()
);

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