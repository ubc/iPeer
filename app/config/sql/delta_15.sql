--
-- Canvas Grade sync
--

-- add page permissions --
INSERT INTO `acos`
(`parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`)
	select id, NULL, NULL, 'exportCanvas', NULL, NULL
	from `acos`
	where BINARY `alias`='Evaluations' LIMIT 1;

-- store canvas course id
ALTER TABLE `events` ADD COLUMN `canvas_assignment_id` VARCHAR(25) NULL DEFAULT NULL;
