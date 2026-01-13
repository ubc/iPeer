<?php
// $Id: adapter_test.php 1505 2007-04-30 23:39:59Z lastcraft $
require_once(dirname(__FILE__) . '/../autorun.php');
require_once(dirname(__FILE__) . '/../extensions/pear_test_case.php');
require_once(dirname(__FILE__) . '/../extensions/phpunit_test_case.php');

class SameTestClass
{
}

class TestOfPearAdapter extends PHPUnit_TestCase
{
    public function testBoolean()
    {
        $this->assertTrue(true, "PEAR true");
        $this->assertFalse(false, "PEAR false");
    }
    
    public function testName()
    {
        $this->assertTrue($this->getName() == get_class($this));
    }
    
    public function testPass()
    {
        $this->pass("PEAR pass");
    }
    
    public function testNulls()
    {
        $value = null;
        $this->assertNull($value, "PEAR null");
        $value = 0;
        $this->assertNotNull($value, "PEAR not null");
    }
    
    public function testType()
    {
        $this->assertType("Hello", "string", "PEAR type");
    }
    
    public function testEquals()
    {
        $this->assertEquals(12, 12, "PEAR identity");
        $this->setLooselyTyped(true);
        $this->assertEquals("12", 12, "PEAR equality");
    }
    
    public function testSame()
    {
        $same = new SameTestClass();
        $this->assertSame($same, $same, "PEAR same");
    }
    
    public function testRegExp()
    {
        $this->assertRegExp('/hello/', "A big hello from me", "PEAR regex");
    }
}

class TestOfPhpUnitAdapter extends TestCase
{
    public function __construct()
    {
        parent::__construct('TestOfPhpUnitAdapter');
    }
    
    public function testBoolean()
    {
        $this->assert(true, 'PHP Unit true');
    }
    
    public function testName()
    {
        $this->assert($this->name() == 'TestOfPhpUnitAdapter');
    }
    
    public function testEquals()
    {
        $this->assertEquals(12, 12, 'PHP Unit equality');
    }
    
    public function testMultilineEquals()
    {
        $this->assertEquals("a\nb\n", "a\nb\n", 'PHP Unit equality');
    }
    
    public function testRegExp()
    {
        $this->assertRegexp('/hello/', 'A big hello from me', 'PHPUnit regex');
    }
}
