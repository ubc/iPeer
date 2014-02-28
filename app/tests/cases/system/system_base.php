<?php
require_once('SystemWebDriver.php');
require_once('SystemWebDriverSession.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriver.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverBy.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverWait.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverKeys.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverSession.php');
require_once('PageFactory.php');

class SystemBaseTestCase extends CakeTestCase
{
    protected $web_driver;
    protected $session = null;
    protected $url;
    protected $browser;
    protected $capabilities;
    protected $seleniumUrl;

    public function __construct()
    {
        // give it a default timezone
        $timezone = ini_get('date.timezone') ? ini_get('date.timezone') : 'UTC';
        date_default_timezone_set($timezone); // set the default time zone

        // set up the test environment according to the environment values
        $this->browser = getenv('SELENIUM_BROWSER') ? getenv('SELENIUM_BROWSER') : 'firefox';
        $this->capabilities['platform'] = getenv('SELENIUM_PLATFORM') ? getenv('SELENIUM_PLATFORM') : 'ANY';
        $this->capabilities['version'] = getenv('SELENIUM_VERSION') ? getenv('SELENIUM_VERSION') : '';
        $this->seleniumUrl = getenv('SELENIUM_URL') ? getenv('SELENIUM_URL') : 'http://localhost:4444/wd/hub';
        $this->url = getenv('SERVER_TEST') ? getenv('SERVER_TEST') : 'http://localhost:2000/';

        echo "SELENIUM_BROWSER = ".$this->browser."\n";
        echo "SELENIUM_VERSION = ".$this->capabilities['version']."\n";
        echo "SELENIUM_PLATFORM = ".$this->capabilities['platform']."\n";
        echo "SELENIUM_URL = ".$this->seleniumUrl."\n";
        echo "SERVER_TEST = ".$this->url."\n";

        $this->web_driver = new SystemWebDriver($this->seleniumUrl);
        $this->session = $this->getSession();
        $this->session->deleteAllCookies();
    }

    public function getSession($new = false) {
        if ($this->session == null || $new == true) {
            return $this->web_driver->session($this->browser, $this->capabilities);
        } else {
            return $this->session;
        }
    }

    public function waitForLogoutLogin($username)
    {
        $this->session->open($this->url);
        $this->logoutLogin($username);
    }

    public function logoutLogin($username)
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Logout')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $title = $session->title();
                return ($title == 'iPeer - Guard');
            }
        );
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login($username, 'ipeeripeer');
    }

    public function startTest($method) {
        echo 'Starting method ' . $method . "\n";
    }

    public function endTest($method) {
        echo 'Ending method ' . $method . "\n";
    }
}
