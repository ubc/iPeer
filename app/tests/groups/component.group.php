<?php
class ComponentGroupTest extends TestSuite
{
    public $label = 'Component';
    function __construct()
    {
        TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES . DS . 'components');
    }
}
