<?php
if (!class_exists('DJJob')) {
    App::import('Vendor', 'Djjob.DJJob', array(
        'file' => 'DJJob.php',
    ));
}

/**
 * DJWorker that periodically cleans up the jobs table while running.
 *
 * Jobs keep their lock forever if the worker dies mid-job (pod killed, fatal
 * error), since locks are only released by the worker that owns them.
 */
class MaintainedDJWorker extends DJWorker {
    var $maintenanceInterval = 3600;  // seconds between cleanups
    var $staleLockAge = 3600;         // seconds before a lock is considered abandoned
    var $retentionDays = 30;          // days to keep failed jobs (for replay)
    var $lastMaintenance = 0;

    // The abandoned run counts as an attempt so a job that kills the worker
    // eventually stops being picked up, and is marked failed once out of
    // attempts (same as DJJob::finishWithError) so it gets cleaned up.
    // Params: max_attempts, max_attempts
    const RELEASE_LOCK_SET = "
        SET locked_at = NULL, locked_by = NULL, attempts = attempts + 1,
            failed_at = IF(attempts >= ?, NOW(), NULL),
            error = IF(attempts >= ?, 'Lock abandoned by worker', NULL)";

    public function start() {
        // a freshly started worker holds no locks, so any under its name were
        // left by a dead predecessor with the same host and pid (e.g. container
        // restarted in the same pod). Without this the worker would reclaim the
        // lock and re-run a job that crashed it without counting the attempt.
        try {
            $released = $this->releaseLocksHeldBy($this->name);
            $this->log("[WORKER] released {$released} locks left by a previous worker with this name", self::INFO);
        } catch (Exception $e) {
            $this->log("[WORKER] releasing previous locks failed: {$e->getMessage()}", self::ERROR);
        }
        parent::start();
    }

    public function getNewJob() {
        if (time() - $this->lastMaintenance >= $this->maintenanceInterval) {
            $this->lastMaintenance = time();
            $this->maintain();
        }
        return parent::getNewJob();
    }

    public function maintain() {
        try {
            $unlocked = $this->releaseLocksOlderThan($this->staleLockAge);
            // only failed jobs are deleted; pending and running jobs are never removed
            $deleted = self::runUpdate("
                DELETE FROM " . self::$jobsTable . "
                WHERE failed_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
                array($this->retentionDays)
            );
            $this->log("[WORKER] maintenance: released {$unlocked} stale locks, deleted {$deleted} old failed jobs", self::INFO);
        } catch (Exception $e) {
            $this->log("[WORKER] maintenance failed: {$e->getMessage()}", self::ERROR);
        }
    }

    /**
     * Releases locks held by the given worker name as abandoned attempts.
     *
     * @return int number of jobs released
     */
    protected function releaseLocksHeldBy($workerName) {
        return self::runUpdate("
            UPDATE " . self::$jobsTable . self::RELEASE_LOCK_SET . "
            WHERE locked_at IS NOT NULL AND locked_by = ?",
            array($this->max_attempts, $this->max_attempts, (string) $workerName)
        );
    }

    /**
     * Releases locks older than the given number of seconds as abandoned attempts.
     *
     * @return int number of jobs released
     */
    protected function releaseLocksOlderThan($seconds) {
        return self::runUpdate("
            UPDATE " . self::$jobsTable . self::RELEASE_LOCK_SET . "
            WHERE locked_at < DATE_SUB(NOW(), INTERVAL ? SECOND)",
            array($this->max_attempts, $this->max_attempts, (int) $seconds)
        );
    }
}
