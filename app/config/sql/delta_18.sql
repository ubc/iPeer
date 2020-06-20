ALTER TABLE `users` MODIFY `lti_id` varchar(64) NULL DEFAULT NULL;

ALTER TABLE `courses` MODIFY `canvas_id` varchar(64) NULL DEFAULT NULL;
DROP INDEX IF EXISTS `canvas_id` ON `courses`;
ALTER TABLE `courses` ADD INDEX `canvas_id` (`canvas_id`);

DROP TABLE IF EXISTS `lti_platform_deployments`;
CREATE TABLE `lti_platform_deployments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lti_tool_registration_id` int(11) NOT NULL,
  `deployment` varchar(64) NOT NULL COMMENT 'Platform deployment ID hash. https://purl.imsglobal.org/spec/lti/claim/deployment_id',
  PRIMARY KEY (`id`),
  KEY `lti_tool_registration_id` (`lti_tool_registration_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `lti_tool_registrations`;
CREATE TABLE `lti_tool_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iss` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `auth_login_url` varchar(255) NOT NULL,
  `auth_token_url` varchar(255) NOT NULL,
  `key_set_url` varchar(255) NOT NULL,
  `tool_private_key_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `iss_client` (`iss`,`client_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `acos` VALUES
(NULL,2,NULL,NULL,'LtiToolRegistrations',NULL,NULL),
(NULL,338,NULL,NULL,'index',NULL,NULL),
(NULL,338,NULL,NULL,'add',NULL,NULL),
(NULL,338,NULL,NULL,'edit',NULL,NULL),
(NULL,338,NULL,NULL,'delete',NULL,NULL);
