-- add table to store delayed jobs
CREATE TABLE IF NOT EXISTS `jobs` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `handler` TEXT NOT NULL,
    `queue` VARCHAR(255) NOT NULL DEFAULT 'default',
    `attempts` INT UNSIGNED NOT NULL DEFAULT 0,
    `run_at` DATETIME NULL,
    `locked_at` DATETIME NULL,
    `locked_by` VARCHAR(255) NULL,
    `failed_at` DATETIME NULL,
    `error` TEXT NULL,
    `created_at` DATETIME NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

-- store course term
ALTER TABLE `courses` ADD COLUMN `term` VARCHAR(50) NULL DEFAULT NULL;