<?php

App::import('Lib', 'Toolkit');

/**
 * Tests for Toolkit utility methods.
 *
 * @package app.tests.cases.libs
 */
class ToolkitTestCase extends CakeTestCase
{
    private $tmpFile;

    function tearDown()
    {
        if ($this->tmpFile && file_exists($this->tmpFile)) {
            unlink($this->tmpFile);
        }
    }

    private function writeTmp($content)
    {
        $this->tmpFile = tempnam(sys_get_temp_dir(), 'toolkit_test_');
        file_put_contents($this->tmpFile, $content);
        return $this->tmpFile;
    }

    function testParseCSVNoBom()
    {
        $file = $this->writeTmp("TESTUSER001,Jane,Doe,10000001\n");
        $result = Toolkit::parseCSV($file);
        $this->assertEqual('TESTUSER001', $result[0][0]);
    }

    function testParseCSVWithBom()
    {
        $file = $this->writeTmp("\xEF\xBB\xBFTESTUSER001,Jane,Doe,10000001\n");
        $result = Toolkit::parseCSV($file);
        $this->assertEqual('TESTUSER001', $result[0][0],
            'BOM should be stripped from first field');
    }

    function testParseCSVBomDoesNotAffectOtherFields()
    {
        $file = $this->writeTmp("\xEF\xBB\xBFuser1,First,Last,12345\nuser2,A,B,67890\n");
        $result = Toolkit::parseCSV($file);
        $this->assertEqual('user1',  $result[0][0]);
        $this->assertEqual('First',  $result[0][1]);
        $this->assertEqual('user2',  $result[1][0]);
    }

    function testParseCSVShortFile()
    {
        // File shorter than BOM (3 bytes) — rewind path should still work
        $file = $this->writeTmp("a\n");
        $result = Toolkit::parseCSV($file);
        $this->assertEqual('a', $result[0][0]);
    }

    function testParseCSVBackslashBeforeQuote()
    {
        // fgetcsv() treats a backslash as an escape character: the quote
        // after it doesn't end the field, and the backslash is kept
        $file = $this->writeTmp("\"a\\\"b\",c\n");
        $result = Toolkit::parseCSV($file);
        $this->assertIdentical(array(array('a\\"b', 'c')), $result);
    }

    function testParseCSVWindowsLineEndings()
    {
        $file = $this->writeTmp("TESTUSER001,Jane\r\nTESTUSER002,John\r\n");
        $result = Toolkit::parseCSV($file);
        $this->assertIdentical(
            array(array('TESTUSER001', 'Jane'), array('TESTUSER002', 'John')),
            $result
        );
    }

    function testParseCSVCarriageReturnLineEndings()
    {
        // A file that uses only CR needs auto_detect_line_endings, which
        // app_controller.php turns on for every request. That file is not
        // loaded here and the default is off, so this test sets it. PHP 8.1
        // deprecated the setting: when PHP removes it, this test fails and
        // tells us that CR files no longer parse.
        $old = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        try {
            $file = $this->writeTmp("TESTUSER001,Jane\rTESTUSER002,John\r");
            $result = Toolkit::parseCSV($file);
        } finally {
            // Restore even on a failure, so the value does not leak into the
            // tests that run after this one in the same process.
            ini_set('auto_detect_line_endings', $old);
        }

        $this->assertIdentical(
            array(array('TESTUSER001', 'Jane'), array('TESTUSER002', 'John')),
            $result
        );
    }
}
