<?php
class LibsGroupTest extends TestSuite
{
    public $label = 'Libs';
    function __construct()
    {
        // listed explicitly: libs/extended_test_case is the ExtendedTestCase
        // plugin's own test and doesn't run in this app
        TestManager::addTestFile($this, APP_TEST_CASES . DS . 'libs' . DS . 'toolkit');
        TestManager::addTestFile($this, APP_TEST_CASES . DS . 'libs' . DS . 'ipeer_saml_response');
        TestManager::addTestFile($this, APP_TEST_CASES . DS . 'libs' . DS . 'caliper_sensor');
        TestManager::addTestFile($this, APP_TEST_CASES . DS . 'libs' . DS . 'maintained_dj_worker');
        TestManager::addTestFile($this, APP_TEST_CASES . DS . 'libs' . DS . 'same_site');
        TestManager::addTestFile($this, APP_TEST_CASES . DS . 'libs' . DS . 'controller_paginate');
    }
}
