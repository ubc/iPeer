<?php
App::import('Core', 'Debugger');

/**
 * Log stream that keeps messages in memory.
 */
class SilencedErrorsLog
{
    public $messages = array();

    function write($type, $message)
    {
        $this->messages[] = array($type, $message);
    }
}

/**
 * Tests that Cake's error handlers ignore errors silenced with @ and
 * still handle other errors.
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
    private $silenced = E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR | E_PARSE;

    private function handleWith($handler, $reporting, $code)
    {
        $log =& CakeLog::getInstance();
        $streams = $log->_streams;
        $capture = new SilencedErrorsLog();
        $log->_streams = array('capture' => $capture);
        $old = error_reporting($reporting);
        ob_start();
        try {
            $result = call_user_func(array($handler, 'handleError'), $code, 'silenced errors test', __FILE__, __LINE__);
        } finally {
            $out = ob_get_clean();
            error_reporting($old);
            $log->_streams = $streams;
        }
        return array($result, $out, $capture->messages);
    }

    function testDebuggerIgnoresSilencedWarning()
    {
        list($result, $out) = $this->handleWith('Debugger', $this->silenced, E_WARNING);
        $this->assertFalse($result);
        $this->assertIdentical('', $out);
    }

    function testDebuggerIgnoresSilencedNotice()
    {
        list($result, $out) = $this->handleWith('Debugger', $this->silenced, E_USER_NOTICE);
        $this->assertFalse($result);
        $this->assertIdentical('', $out);
    }

    function testDebuggerHandlesEnabledWarning()
    {
        // the handler's output formatting raises E_DEPRECATED, which Cake ignores but SimpleTest doesn't
        list($result) = $this->handleWith('Debugger', E_ALL & ~E_DEPRECATED, E_WARNING);
        $this->assertTrue($result);
    }

    function testCakeLogIgnoresSilencedWarning()
    {
        list(, , $messages) = $this->handleWith('CakeLog', $this->silenced, E_WARNING);
        $this->assertIdentical(array(), $messages);
    }

    function testCakeLogIgnoresSilencedNotice()
    {
        list(, , $messages) = $this->handleWith('CakeLog', $this->silenced, E_USER_NOTICE);
        $this->assertIdentical(array(), $messages);
    }

    function testCakeLogRecordsEnabledWarning()
    {
        list(, , $messages) = $this->handleWith('CakeLog', E_ALL, E_WARNING);
        $this->assertEqual(1, count($messages));
        $this->assertEqual('warning', $messages[0][0]);
        $this->assertPattern('/^Warning \(2\): silenced errors test/', $messages[0][1]);
    }
}
