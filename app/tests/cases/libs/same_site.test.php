<?php

require_once(LIBS . 'same_site.php');

use Skorp\Dissua\SameSite;

/**
 * Tests for the SameSite=None user agent compatibility check used by
 * CakeSession and CookieComponent.
 *
 * @package app.tests.cases.libs
 */
class SameSiteTestCase extends CakeTestCase
{
    function testNullUserAgentSendsNone()
    {
        // CakeSession passes env('HTTP_USER_AGENT'), which is null when the
        // request has no User-Agent header
        $this->assertTrue(SameSite::handle(null));
    }

    function testNoArgumentSendsNone()
    {
        $this->assertTrue(SameSite::handle());
    }

    function testEmptyUserAgentSendsNone()
    {
        $this->assertTrue(SameSite::handle(''));
    }

    function testModernBrowsersSendNone()
    {
        $this->assertTrue(SameSite::handle(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'));
        $this->assertTrue(SameSite::handle(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0'));
        $this->assertTrue(SameSite::handle(
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Safari/605.1.15'));
    }

    function testChromeVersionsThatDropSameSiteNone()
    {
        $this->assertFalse(SameSite::handle(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'));
        $this->assertFalse(SameSite::handle(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36'));
        $this->assertTrue(SameSite::handle(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36'));
    }

    function testIos12HasWebKitBug()
    {
        $this->assertFalse(SameSite::handle(
            'Mozilla/5.0 (iPhone; CPU iPhone OS 12_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0 Mobile/15E148 Safari/604.1'));
        $this->assertTrue(SameSite::handle(
            'Mozilla/5.0 (iPhone; CPU iPhone OS 13_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0 Mobile/15E148 Safari/604.1'));
    }

    function testMacos1014SafariHasWebKitBug()
    {
        $this->assertFalse(SameSite::handle(
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.1.2 Safari/605.1.15'));
    }

    function testOldUcBrowserDropsSameSiteNone()
    {
        $this->assertFalse(SameSite::handle(
            'Mozilla/5.0 (Linux; U; Android 8.1.0; en-US; Nexus 6P Build/OPM7.181205.001) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.108 UCBrowser/12.13.1.1208 Mobile Safari/537.36'));
        $this->assertTrue(SameSite::handle(
            'Mozilla/5.0 (Linux; U; Android 8.1.0; en-US; Nexus 6P Build/OPM7.181205.001) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.108 UCBrowser/12.13.2.1208 Mobile Safari/537.36'));
    }
}
