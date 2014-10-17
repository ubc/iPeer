<?php
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriver.php');
require_once('SystemWebDriverSession.php');

/**
 * SystemWebDriver This class is a duplication of WebDriver class in order to return a custom session object, which extends a few methods based on WebDriverSession class
 * This class also stores session id in SystemWebDriverSession
 *
 * @uses PHPWebDriver_WebDriver
 * @package tests
 * @version //autogen//
 * @copyright Copyright (c) 2013 All rights reserved.
 * @author
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class SystemWebDriver extends PHPWebDriver_WebDriver {
    function __construct($executor = null) {
        if (! is_null($executor)) {
            parent::__construct($executor);
        } else {
            parent::__construct();
        }
    }

    public function session($browser = 'firefox',
                            $additional_capabilities = array(),
                            $curl_opts = array(),
                            $browser_profile = null) {
        $capabilities = new PHPWebDriver_WebDriverDesiredCapabilities();
        $desired_capabilities = array_merge($capabilities->$browser, $additional_capabilities);
        if ($browser == 'firefox' && $browser_profile) {
            $desired_capabilities['firefox_profile'] = $browser_profile->encoded();
        }

        $curl_opts = $curl_opts + array(CURLOPT_FOLLOWLOCATION => true);

        $results = $this->curl(
            'POST',
            '/session',
            array('desiredCapabilities' => $desired_capabilities),
                $curl_opts);

        $session = new SystemWebDriverSession($results['info']['url'].$results['sessionId']);
        $session->setId($results['sessionId']);

        return $session;
    }
}
