<?php
class CaliperGroupTest extends TestSuite
{
    public $label = 'Caliper';
    function __construct()
    {
        TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES . DS . 'caliper');
    }
}
