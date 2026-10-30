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
        $this->Cookie->write('test', 'value', false);
        restore_error_handler();

        $this->assertEqual(array(), preg_grep('/HTTP_USER_AGENT/', $errors));
    }
}
