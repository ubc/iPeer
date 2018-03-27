--
-- Canvas integration
--

INSERT INTO `sys_parameters` (
    `parameter_code`, `parameter_value`,
    `parameter_type`, `description`, `record_status`, `creator_id`, `created`,
    `updater_id`, `modified`)
VALUES (
    'google_tag_manager.container_id', '', 'S',
    'Container ID for Google Tag Manager (GTM-XXXX)', 'A', 0, NOW(), NULL, NOW()
);