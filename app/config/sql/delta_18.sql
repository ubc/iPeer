ALTER TABLE `users` MODIFY `lti_id` varchar(64) NULL DEFAULT NULL;

ALTER TABLE `courses` MODIFY `canvas_id` varchar(64) NULL DEFAULT NULL;
DROP INDEX IF EXISTS `canvas_id` ON `courses`;
ALTER TABLE `courses` ADD INDEX `canvas_id` (`canvas_id`);

DROP TABLE IF EXISTS `lti_platform_deployments`;
CREATE TABLE `lti_platform_deployments` (
  `iss` varchar(255) NOT NULL,
  `deployment` varchar(64) NOT NULL COMMENT 'Platform deployment ID hash. https://purl.imsglobal.org/spec/lti/claim/deployment_id',
  KEY `iss` (`iss`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `lti_platform_deployments` VALUES
('https://lti-ri.imsglobal.org', '1'),
('https://canvas.instructure.com', '1:4dde05e8ca1973bcca9bffc13e1548820eee93a3'),
('https://canvas.instructure.com', '2:f97330a96452fc363a34e0ef6d8d0d3e9e1007d2'),
('https://canvas.instructure.com', '3:d3a2504bba5184799a38f141e8df2335cfa8206d');

DROP TABLE IF EXISTS `lti_tool_registrations`;
CREATE TABLE `lti_tool_registrations` (
  `iss` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `auth_login_url` varchar(255) NOT NULL,
  `auth_token_url` varchar(255) NOT NULL,
  `key_set_url` varchar(255) NOT NULL,
  `private_key_file` varchar(255) NOT NULL,
  PRIMARY KEY `iss` (`iss`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `lti_tool_registrations` VALUES
(
  'https://lti-ri.imsglobal.org',
  'ipeer-lti13-001',
  'https://lti-ri.imsglobal.org/platforms/652/authorizations/new',
  'https://lti-ri.imsglobal.org/platforms/652/access_tokens',
  'https://lti-ri.imsglobal.org/platforms/652/platform_keys/654.json',
  'app/config/lti13/tool.private.key'
),
(
  'https://canvas.instructure.com',
  '10000000000001',
  'http://canvas.docker/api/lti/authorize_redirect',
  'http://canvas.docker/login/oauth2/token',
  'http://canvas.docker/api/lti/security/jwks',
  'app/config/lti13/tool.private.key'
);
