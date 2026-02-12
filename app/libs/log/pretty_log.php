<?php
require_once dirname(__FILE__) . '/base_log.php';
require_once dirname(__FILE__) . '/../request_context.php';

define('ANSI_RED', "\033[1;31m");
define('ANSI_YELLOW', "\033[1;33m");
define('ANSI_BLUE', "\033[1;34m");
define('ANSI_GRAY', "\033[1;90m");
define('ANSI_RESET', "\033[0m");

/**
 * Intended for local development.
 */
class PrettyLog extends BaseLog
{

    var $_colors = array(
        'emergency' => ANSI_RED,
        'alert' => ANSI_RED,
        'critical' => ANSI_RED,
        'error' => ANSI_RED,
        'warning' => ANSI_YELLOW,
        'notice' => ANSI_BLUE,
        'info' => ANSI_BLUE,
        'debug' => ANSI_GRAY,
    );

    function format($type, $message)
    {
        $color = $this->_colors[$type] ?? '';
        $level = strtoupper($type);
        $tz = date_default_timezone_get() !== 'UTC' ? date_default_timezone_get() : 'America/Vancouver';
        $timestamp = (new DateTimeImmutable('now', new DateTimeZone($tz)))->format('Y-m-d H:i:s');

        $user = '';
        $username = RequestContext::username();
        $userId = RequestContext::userId();
        if ($username !== null) {
            $user = " [$username id#$userId]";
        }

        $message = str_replace("\n", "\n\t", rtrim($message));
        return "[$timestamp]$user $color$level" . ANSI_RESET . " \n\t$message\n";
    }
}
