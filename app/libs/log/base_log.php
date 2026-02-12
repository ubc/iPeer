<?php
require_once LIBS . 'file.php';

/**
 * Our custom iPeer abstract logger.
 * Based on `cake/libs/log/file_log.php`
 */
abstract class BaseLog
{

    var $_path = null;

    // RFC 5424 severity levels (0 = most severe), also used by ECS schema
    var $_levelSeverity = array(
        'emergency' => 0,
        'alert' => 1,
        'critical' => 2,
        'error' => 3,
        'warning' => 4,
        'notice' => 5,
        'info' => 6,
        'debug' => 7,
    );
    var $_errorLevelCutoff = 4; // as in <=4 will be sent to the error log file
    var $_minSeverity = null;

    function __construct($options = array())
    {
        $options += array('path' => LOGS);
        $this->_path = $options['path'];

        $envLevel = getenv('IPEER_LOG_LEVEL');
        if ($envLevel && isset($this->_levelSeverity[strtolower($envLevel)])) {
            $this->_minSeverity = $this->_levelSeverity[strtolower($envLevel)];
        } elseif (Configure::read('debug') >= 2) {
            $this->_minSeverity = $this->_levelSeverity['debug'];
        } else {
            $this->_minSeverity = $this->_levelSeverity['info'];
        }
    }

    function write($type, $message)
    {
        if (empty($message)) {
            return true;  // No log to write, so consider it a "success"
        }

        if (!is_string($message)) {
            $message = print_r($message, true);
        }

        $type = isset($this->_levelSeverity[$type]) ? $type : 'info';
        $levelNumber = $this->_levelSeverity[$type];

        if ($levelNumber > $this->_minSeverity) {
            return true; // No log to write, so consider it a "success"
        }

        // These two files / FIFOs (named pipes) are defined in the docker entrypoint script
        if ($levelNumber <= $this->_errorLevelCutoff) {
            $filename = $this->_path . 'error.log';
        } else {
            $filename = $this->_path . 'debug.log';
        }

        try {
            $output = $this->format($type, $message);
            if (!str_ends_with($output, "\n")) {
                $output .= "\n";
            }
        } catch (Throwable) {
            $output = str_replace("\n", " ", $message) . "\n";
        }
        $log = new File($filename, true);
        if ($log->writable()) {
            return $log->append($output);
        }
        return false;
    }

    abstract function format($type, $message);
}
