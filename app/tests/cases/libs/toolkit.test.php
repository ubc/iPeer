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

    function testScaleMarkGoesFromNoneToAllTheMarks()
    {
        // Zero Mark off: the lowest of 4 levels gives a quarter.
        $this->assertIdentical(2.0, Toolkit::scaleMark(8, 1, 4, false));
        $this->assertIdentical(8.0, Toolkit::scaleMark(8, 4, 4, false));
        // Zero Mark on: the lowest level gives 0.
        $this->assertIdentical(0.0, Toolkit::scaleMark(8, 1, 5, true));
        $this->assertIdentical(4.0, Toolkit::scaleMark(8, 3, 5, true));
        $this->assertIdentical(8.0, Toolkit::scaleMark(8, 5, 5, true));
    }

    function testScaleMarkOfAOneLevelScale()
    {
        $this->assertIdentical(8.0, Toolkit::scaleMark(8, 1, 1, false));
        // With Zero Mark on, the only level is the lowest.
        $this->assertIdentical(0.0, Toolkit::scaleMark(8, 1, 1, true));
        $this->assertIdentical(0.0, Toolkit::scaleMark(8, 1, 0, false));
    }
}
