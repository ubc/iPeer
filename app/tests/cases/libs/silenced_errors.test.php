<?php
App::import('Core', 'Debugger');

/**
 * Tests that Cake's error handler ignores errors silenced with @.
 *
 * On PHP 8, @ doesn't set error_reporting() to 0: it leaves the fatal
 * levels set (E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR,
 * E_RECOVERABLE_ERROR, E_PARSE). The handler must test the error's own bit.
 *
 * @package app.tests.cases.libs
 */
class SilencedErrorsTestCase extends CakeTestCase
{
    // what error_reporting() returns inside an @ expression on PHP 8
    private $silenced;

    function startTest($method)
    {
        $this->silenced = E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR | E_PARSE;
    }

    private function handleWith($reporting, $code)
    {
        $old = error_reporting($reporting);
        ob_start();
        try {
            $result = Debugger::handleError($code, 'silenced errors test', __FILE__, __LINE__);
        } finally {
            $out = ob_get_clean();
            error_reporting($old);
        }
        return array($result, $out);
    }

    function testSilencedWarningIsIgnored()
    {
        list($result, $out) = $this->handleWith($this->silenced, E_WARNING);
        $this->assertFalse($result);
        $this->assertIdentical('', $out);
    }

    function testSilencedNoticeIsIgnored()
    {
        list($result, $out) = $this->handleWith($this->silenced, E_USER_NOTICE);
        $this->assertFalse($result);
        $this->assertIdentical('', $out);
    }
}
