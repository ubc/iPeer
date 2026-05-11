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
}
