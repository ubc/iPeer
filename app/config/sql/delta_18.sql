ALTER TABLE `users` MODIFY `lti_id` VARCHAR(64) NULL DEFAULT NULL;

INSERT INTO `acos` (`parent_id`, `alias`)
    SELECT id, 'updateRosterFromCanvas'
    FROM `acos`
    WHERE BINARY `alias`='Users' LIMIT 1;

