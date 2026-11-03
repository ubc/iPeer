<?php
App::import('Lib', 'Toolkit');
App::import('Core', 'ConnectionManager');
App::import('Component', 'Guard.Guard');

Mock::generatePartial('GuardComponent', 'MockGuardComponentForError', array('_stop'));

/**
 * Tests for code that reports a fatal-looking error with trigger_error()
 * and then carries on (or stops explicitly). Under the test runner, as with
 * Cake's CakeLog handler in production, the error is reported and execution
 * continues, so these pin the message and what happens next.
 *
 * @package app.tests.cases.libs
 */
class UserErrorsTestCase extends CakeTestCase
{
    function testParseCSVMissingFileReportsErrorAndReturnsEmpty()
    {
        $file = TMP . 'no_such_file_' . uniqid() . '.csv';

        // fopen()'s own warning, then Toolkit's error
        $this->expectError(new PatternExpectation('/Failed to open stream/i'));
        $this->expectError(new PatternExpectation('/^Error to open file /'));
        $result = Toolkit::parseCSV($file);

        $this->assertIdentical(array(), $result);
    }

    function testGetDataSourceUnknownNameReportsErrorAndReturnsNull()
    {
        $this->expectError(new PatternExpectation('/Non-existent data source no_such_datasource/'));
        $db = ConnectionManager::getDataSource('no_such_datasource');

        $this->assertNull($db);
    }

    function testGuardErrorReportsErrorAndStops()
    {
        $guard = new MockGuardComponentForError();
        $guard->expectOnce('_stop');

        $this->expectError(new PatternExpectation('/^Guard error message$/'));
        $guard->error('Guard error message');
    }
}
