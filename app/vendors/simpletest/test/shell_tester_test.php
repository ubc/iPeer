<?php
// $Id: shell_tester_test.php 1505 2007-04-30 23:39:59Z lastcraft $
require_once(dirname(__FILE__) . '/../autorun.php');
require_once(dirname(__FILE__) . '/../shell_tester.php');
Mock::generate('SimpleShell');

class TestOfShellTestCase extends ShellTestCase
{
    public $_mock_shell = false;
    
    public function &_getShell()
    {
        return $this->_mock_shell;
    }
    
    public function testGenericEquality()
    {
        $this->assertEqual('a', 'a');
        $this->assertNotEqual('a', 'A');
    }
    
    public function testExitCode()
    {
        $this->_mock_shell = new MockSimpleShell();
        $this->_mock_shell->setReturnValue('execute', 0);
        $this->_mock_shell->expectOnce('execute', array('ls'));
        $this->assertTrue($this->execute('ls'));
        $this->assertExitCode(0);
    }
    
    public function testOutput()
    {
        $this->_mock_shell = new MockSimpleShell();
        $this->_mock_shell->setReturnValue('execute', 0);
        $this->_mock_shell->setReturnValue('getOutput', "Line 1\nLine 2\n");
        $this->assertOutput("Line 1\nLine 2\n");
    }
    
    public function testOutputPatterns()
    {
        $this->_mock_shell = new MockSimpleShell();
        $this->_mock_shell->setReturnValue('execute', 0);
        $this->_mock_shell->setReturnValue('getOutput', "Line 1\nLine 2\n");
        $this->assertOutputPattern('/line/i');
        $this->assertNoOutputPattern('/line 2/');
    }
}
