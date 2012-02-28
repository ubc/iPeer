<?php
class ModelGroupTest extends TestSuite
{
    public $label = 'Model';
    function __construct()
    {
        TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES . DS . 'models');
    }
}
