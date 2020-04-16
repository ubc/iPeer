ALTER TABLE `users` MODIFY `lti_id` VARCHAR(64) NULL DEFAULT NULL;

ALTER TABLE `courses` MODIFY `canvas_id` VARCHAR(64) NULL DEFAULT NULL;
DROP INDEX IF EXISTS `canvas_id` ON `courses`;
ALTER TABLE `courses` ADD INDEX `canvas_id` (`canvas_id`);

DROP TABLE IF EXISTS `courses_lti_platform_deployments`;
CREATE TABLE `courses_lti_platform_deployments` (
  `deployment_id` varchar(64) NOT NULL DEFAULT '' COMMENT 'Not a foreign key! Platform deployment ID hash. https://purl.imsglobal.org/spec/lti/claim/deployment_id',
  `course_id` int(11) NOT NULL,
  UNIQUE KEY `deployment_id` (`deployment_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `courses_lti_platform_deployments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
