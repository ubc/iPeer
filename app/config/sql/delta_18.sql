ALTER TABLE `courses` MODIFY `canvas_id` varchar(255) NULL DEFAULT NULL;
ALTER TABLE `courses` ADD INDEX `canvas_id` (`canvas_id`);
ALTER TABLE `users` DROP `lti_id`;

DROP TABLE IF EXISTS `lti_tool_registrations`;
CREATE TABLE `lti_tool_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iss` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `auth_login_url` varchar(255) NOT NULL,
  `auth_token_url` varchar(255) NOT NULL,
  `key_set_url` varchar(255) NOT NULL,
  `tool_private_key` text NOT NULL,
  `tool_public_key` text NOT NULL,
  `user_identifier_field` varchar(255) DEFAULT NULL,
  `student_number_field` varchar(255) DEFAULT NULL,
  `term_field` varchar(255) DEFAULT NULL,
  `canvas_id_field` varchar(255) DEFAULT NULL,
  `faculty_name_field` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `iss` (`iss`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `acos` (id, parent_id, model, foreign_key, alias, lft, rght) VALUES
(338,2,NULL,NULL,'Ltitoolregistrations',664,673),
(339,338,NULL,NULL,'index',665,666),
(340,338,NULL,NULL,'add',667,668),
(341,338,NULL,NULL,'edit',669,670),
(342,338,NULL,NULL,'delete',671,672),
(343,2,NULL,NULL,'Lti',673,674),
(344,343,NULL,NULL,'roster',675,676);

INSERT INTO `aros_acos` (id, aro_id, aco_id, _create, _read, _update, _delete) VALUES
(NULL,1,338,'1','1','1','1'),
(NULL,2,344,'1','1','1','1'),
(NULL,3,344,'1','1','1','1'),
(NULL,4,344,'-1','-1','-1','-1'),
(NULL,5,344,'-1','-1','-1','-1');

DROP TABLE IF EXISTS `lti_nonces`;
CREATE TABLE `lti_nonces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nonce` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nonce` (`nonce`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `lti_contexts`;
CREATE TABLE `lti_contexts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lti_tool_registration_id` int(11) NOT NULL,
  `context_id` varchar(255) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `nrps_context_memberships_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`lti_tool_registration_id`) REFERENCES `lti_tool_registrations` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `lti_tool_registration_id_context_id` (`lti_tool_registration_id`,`context_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `lti_resource_links`;
CREATE TABLE `lti_resource_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lti_context_id` int(11) NOT NULL,
  `resource_link_id` varchar(255) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `lineitems_url` varchar(255) DEFAULT NULL,
  `lineitem_url` varchar(255) DEFAULT NULL,
  `scope_lineitem` INT(1) DEFAULT '0',
  `scope_lineitem_read_only` INT(1) DEFAULT '0',
  `scope_result_readonly` INT(1) DEFAULT '0',
  `scope_result_score` INT(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`lti_context_id`) REFERENCES `lti_contexts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `lti_context_id_resource_link_id` (`lti_context_id`,`resource_link_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lti_users`;
CREATE TABLE `lti_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lti_tool_registration_id` int(11) NOT NULL,
  `lti_user_id` varchar(255) NOT NULL,
  `ipeer_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`lti_tool_registration_id`) REFERENCES `lti_tool_registrations` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`ipeer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `lti_tool_registration_id_lti_user_id` (`lti_tool_registration_id`,`lti_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;