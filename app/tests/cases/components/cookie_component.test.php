<?php
App::import('Component', 'Cookie');

/**
 * Tests for CookieComponent's SameSite handling.
 *
 * @package app.tests.cases.components
 */
class CookieComponentTestCase extends CakeTestCase
{
    private $server;

    function startTest($method)
    {
        $this->server = $_SERVER;
        $this->Cookie = new CookieComponent();
        $this->Cookie->samesite = 'None';
    }

    function endTest($method)
    {
        $_SERVER = $this->server;
        unset($this->Cookie);
    }

    function testWriteSameSiteNoneWithoutUserAgent()
    {
        unset($_SERVER['HTTP_USER_AGENT']);

        // setcookie() itself may warn that headers were already sent by the
        // test runner, so only look for errors about the user agent
        $errors = array();
        set_error_handler(function ($severity, $message) use (&$errors) {
            $errors[] = $message;
            return true;
        });
        try {
            $this->Cookie->write('test', 'value', false);
        } finally {
            restore_error_handler();
        }

        // Requests without a User-Agent header (e.g. curl, health checks) have
        // no HTTP_USER_AGENT key. Reading $_SERVER['HTTP_USER_AGENT'] directly
        // raised an "Undefined array key" warning on PHP 8; the Cookie component now
        // reads it with env(), which returns null instead. (part of PHP upgrade work)
        // We leave this test as a regression test (ensure warning doesn't get emitted).
        $this->assertEqual(array(), preg_grep('/HTTP_USER_AGENT/', $errors));
    }
}
