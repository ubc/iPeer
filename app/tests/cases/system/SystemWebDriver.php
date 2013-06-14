<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('SystemWebDriverSession.php');

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

        return new SystemWebDriverSession($results['info']['url']);
    }
}