<?php
class ControllerGroupTest extends TestSuite
{
    public $label = 'Controller';
    function __construct()
    {
        TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES . DS . 'controllers');
    }
}

