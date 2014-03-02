<?php
class SystemGroupTest extends TestSuite
{
    public $label = 'System';
    function __construct()
    {
        TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES . DS . 'system');
    }
}

