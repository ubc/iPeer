<?php
/**
 * Load the seed data (app/config/sql/ipeer_samples_data.sql) into the test
 * database. Run it with `composer test-db`; `composer test` runs it first.
 *
 * The connection comes from app/config/database.php, which reads the
 * IPEER_DB_* environment variables. The database is ipeer_test unless
 * IPEER_DB_NAME is set, so a test run does not overwrite the development
 * database.
 */

$root = dirname(__DIR__);
require $root . '/app/config/database.php';

$config = (new DATABASE_CONFIG())->default;
$database = getenv('IPEER_DB_NAME') ?: 'ipeer_test';
$seedFile = $root . '/app/config/sql/ipeer_samples_data.sql';

// The seed file has lines such as "--- START" that MariaDB does not accept as
// comments, so remove all lines that start with "--" (e2e/support/db.ts does
// the same).
$sql = preg_replace('/^--.*$/m', '', file_get_contents($seedFile));

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$port = isset($config['port']) ? (int) $config['port'] : null;
$db = new mysqli($config['host'], $config['login'], $config['password'], $database, $port);

// multi_query() runs the first statement; each next_result() runs the next one
// and throws when it fails.
$db->multi_query($sql);
$statements = 0;
do {
    $statements++;
    $result = $db->store_result();
    if ($result) {
        $result->free();
    }
} while ($db->more_results() && $db->next_result());
$db->close();

echo "Loaded the seed data into $database ($statements statements).\n";
