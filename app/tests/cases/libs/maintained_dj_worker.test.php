<?php

App::import('Lib', 'CakeDjjob.maintained_dj_worker', array(
    'file' => 'maintained_dj_worker.php',
));

/**
 * Tests for the jobs table cleanup done by MaintainedDJWorker.
 *
 * Uses its own table so it doesn't touch the real jobs table.
 *
 * @package app.tests.cases.libs
 */
class MaintainedDJWorkerTestCase extends CakeTestCase
{
    private $table = 'test_maintained_dj_worker_jobs';

    function startTest($method)
    {
        $this->configure($this->table);
        DJJob::setLogLevel(DJJob::CRITICAL);
        DJJob::runUpdate("DROP TABLE IF EXISTS {$this->table}");
        DJJob::runUpdate("
            CREATE TABLE {$this->table} (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `handler` LONGTEXT NOT NULL,
                `queue` VARCHAR(255) NOT NULL DEFAULT 'default',
                `attempts` INT UNSIGNED NOT NULL DEFAULT 0,
                `run_at` DATETIME NULL,
                `locked_at` DATETIME NULL,
                `locked_by` VARCHAR(255) NULL,
                `failed_at` DATETIME NULL,
                `error` TEXT NULL,
                `created_at` DATETIME NOT NULL
            ) ENGINE = InnoDB DEFAULT CHARSET=utf8");
    }

    function endTest($method)
    {
        DJJob::runUpdate("DROP TABLE IF EXISTS {$this->table}");
        DJJob::setLogLevel(DJJob::DEBUG);
        $this->configure('jobs');
    }

    private function configure($table)
    {
        $config = ConnectionManager::getDataSource('default')->config;
        DJJob::configure(array(
            'driver' => 'mysql',
            'host' => $config['host'],
            'dbname' => $config['database'],
            'port' => $config['port'],
            'user' => $config['login'],
            'password' => $config['password'],
        ), $table);
    }

    // $columns are SQL expressions, e.g. 'NOW() - INTERVAL 2 HOUR'
    private function insertJob($id, $columns)
    {
        $columns = array_merge(array(
            'handler' => "'x'",
            'created_at' => 'NOW()',
            // keep released jobs from being run by start()
            'run_at' => 'NOW() + INTERVAL 1 DAY',
        ), $columns);
        DJJob::runUpdate(sprintf("INSERT INTO {$this->table} (id, %s) VALUES (%d, %s)",
            implode(', ', array_keys($columns)), $id, implode(', ', $columns)));
    }

    private function getJobs()
    {
        $jobs = array();
        $rows = DJJob::runQuery("SELECT id, attempts, locked_by, failed_at, error FROM {$this->table}");
        foreach ($rows as $row) {
            $jobs[$row['id']] = $row;
        }
        return $jobs;
    }

    function testMaintain()
    {
        $this->insertJob(1, array('created_at' => 'NOW() - INTERVAL 40 DAY'));
        $this->insertJob(2, array('attempts' => 4, 'locked_at' => 'NOW() - INTERVAL 2 HOUR', 'locked_by' => "'host::dead pid::1'"));
        $this->insertJob(3, array('locked_at' => 'NOW() - INTERVAL 2 HOUR', 'locked_by' => "'host::dead pid::2'"));
        $this->insertJob(4, array('attempts' => 5, 'failed_at' => 'NOW() - INTERVAL 40 DAY', 'error' => "'old'"));
        $this->insertJob(5, array('attempts' => 5, 'failed_at' => 'NOW() - INTERVAL 5 DAY', 'error' => "'recent'"));
        $this->insertJob(6, array('locked_at' => 'NOW() - INTERVAL 5 MINUTE', 'locked_by' => "'host::busy pid::3'"));

        $worker = new MaintainedDJWorker(array('max_attempts' => 5));
        $worker->maintain();
        $jobs = $this->getJobs();

        $this->assertEqual(array(1, 2, 3, 5, 6), array_keys($jobs), 'only the old failed job is deleted');
        // pending job is never deleted, however old
        $this->assertEqual(0, $jobs[1]['attempts']);
        // stale lock on the last attempt is released and marked failed
        $this->assertEqual(5, $jobs[2]['attempts']);
        $this->assertNull($jobs[2]['locked_by']);
        $this->assertNotNull($jobs[2]['failed_at']);
        $this->assertEqual('Lock abandoned by worker', $jobs[2]['error']);
        // stale lock with attempts left is released and stays pending
        $this->assertEqual(1, $jobs[3]['attempts']);
        $this->assertNull($jobs[3]['locked_by']);
        $this->assertNull($jobs[3]['failed_at']);
        // recent failed job is kept for replay
        $this->assertEqual('recent', $jobs[5]['error']);
        // fresh lock is left alone
        $this->assertEqual('host::busy pid::3', $jobs[6]['locked_by']);
        $this->assertEqual(0, $jobs[6]['attempts']);
    }

    function testStartReleasesLocksWithOwnName()
    {
        $worker = new MaintainedDJWorker(array('max_attempts' => 5, 'count' => 1, 'sleep' => 0));
        // recent locks under this worker's name, as left by a crashed
        // predecessor with the same host and pid
        $this->insertJob(1, array('locked_at' => 'NOW()', 'locked_by' => "'{$worker->name}'"));
        $this->insertJob(2, array('attempts' => 4, 'locked_at' => 'NOW()', 'locked_by' => "'{$worker->name}'"));
        $this->insertJob(3, array('locked_at' => 'NOW()', 'locked_by' => "'host::other pid::1'"));

        $worker->start();
        $jobs = $this->getJobs();

        $this->assertEqual(1, $jobs[1]['attempts']);
        $this->assertNull($jobs[1]['locked_by']);
        $this->assertNull($jobs[1]['failed_at']);
        $this->assertEqual(5, $jobs[2]['attempts']);
        $this->assertNotNull($jobs[2]['failed_at']);
        // another worker's lock is left alone
        $this->assertEqual('host::other pid::1', $jobs[3]['locked_by']);
        $this->assertEqual(0, $jobs[3]['attempts']);
    }
}
