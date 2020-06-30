ALTER TABLE `users` MODIFY `lti_id` varchar(64) NULL DEFAULT NULL;

ALTER TABLE `courses` MODIFY `canvas_id` varchar(64) NULL DEFAULT NULL;
-- DROP INDEX IF EXISTS `canvas_id` ON `courses`;
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

INSERT INTO `acos` (id, parent_id, model, foreign_key, alias, lft, rght) VALUES
(338,2,NULL,NULL,'Ltitoolregistrations',664,673),
(339,338,NULL,NULL,'index',665,666),
(340,338,NULL,NULL,'add',667,668),
(341,338,NULL,NULL,'edit',669,670),
(342,338,NULL,NULL,'delete',671,672);

INSERT INTO `aros_acos` (id, aro_id, aco_id, _create, _read, _update, _delete) VALUES
(NULL,1,338,'1','1','1','1');
