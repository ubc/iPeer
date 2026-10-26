<?php
App::import('Lib', 'caliper');
App::import('Component', 'Session');
use caliper\CaliperUrl;
use caliper\CaliperEntity;

/**
 * Tests for CaliperUrl, which strips user input from URLs sent to the LRS.
 *
 * @package app.tests.cases.caliper
 */
class CaliperUrlTestCase extends CakeTestCase
{
    function testQueryParams()
    {
        $this->assertEqual(
            array('page' => '', 'q' => '', 'ids' => ''),
            CaliperUrl::sanitizeQueryParams(array(
                'page' => '2',
                'q' => 'Jane Student',
                '../x' => '1',
                'ids' => array('1', '2'),
            ))
        );
        $this->assertEqual(array(), CaliperUrl::sanitizeQueryParams(null));
    }

    function testPath()
    {
        $this->assertEqual('/home/index/5', CaliperUrl::sanitizePath('/home/index/5'));
        $this->assertEqual('/files/report.pdf', CaliperUrl::sanitizePath('/files/report.pdf'));
        $this->assertEqual('/users/index/sort:/direction:', CaliperUrl::sanitizePath('/users/index/sort:name/direction:asc'));
        $this->assertEqual('/home/index/_/_/_/_', CaliperUrl::sanitizePath('/home/index/../%2e%2e/<script>/169.254.169.254'));
        $this->assertEqual('/home/index/_', CaliperUrl::sanitizePath('/home/index/Jane Student'));
        $this->assertEqual('', CaliperUrl::sanitizePath(null));
    }

    function testUrl()
    {
        $this->assertEqual(
            'https://ipeer.example.com/login?redirect=',
            CaliperUrl::sanitizeUrl('https://ipeer.example.com/login?redirect=http://169.254.169.254/latest#top')
        );
        $this->assertEqual(
            'https://ipeer.example.com:8443/users/index/sort:/_?q=',
            CaliperUrl::sanitizeUrl('https://user:pass@ipeer.example.com:8443/users/index/sort:name/..%2f?q=Jane&..%2fx=1')
        );
        $this->assertEqual('http://ipeer.example.com/', CaliperUrl::sanitizeUrl('http://ipeer.example.com'));
        $this->assertNull(CaliperUrl::sanitizeUrl(''));
        $this->assertNull(CaliperUrl::sanitizeUrl('not a url'));
        $this->assertNull(CaliperUrl::sanitizeUrl('javascript:alert(1)'));
        $this->assertNull(CaliperUrl::sanitizeUrl('file:///etc/passwd'));
    }

    function testSessionRefererIsSanitized()
    {
        $_SERVER['HTTP_REFERER'] = 'https://ipeer.example.com/users/index?q=Jane+Student';
        $session = CaliperEntity::session();
        unset($_SERVER['HTTP_REFERER']);
        $this->assertEqual(
            array('referer' => 'https://ipeer.example.com/users/index?q='),
            $session->getExtensions()
        );

        $_SERVER['HTTP_REFERER'] = 'not a url';
        $session = CaliperEntity::session();
        unset($_SERVER['HTTP_REFERER']);
        $this->assertFalse(array_key_exists('referer', (array) $session->getExtensions()));
    }
}
