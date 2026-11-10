-- The browser tests keep a copy of the seed data in this schema and copy it
-- into ipeer_test before each test (see e2e/support/db.ts). MariaDB runs this
-- file only when it creates a new data directory.
CREATE DATABASE IF NOT EXISTS ipeer_seed;
GRANT ALL PRIVILEGES ON ipeer_seed.* TO 'ipeer'@'%';
