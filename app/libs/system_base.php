<?php
require_once('SystemWebDriver.php');
require_once('SystemWebDriverSession.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriver.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverBy.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverWait.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverKeys.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverSession.php');
require_once(VENDORS.'sausage/src/Sauce/Sausage/SauceConfig.php');
require_once(VENDORS.'sausage/src/Sauce/Sausage/SauceAPI.php');
require_once(VENDORS.'sausage/src/Sauce/Sausage/SauceMethods.php');
require_once(VENDORS.'sausage/src/Sauce/Sausage/SauceTestCommon.php');
require_once('PageFactory.php');

abstract class SystemBaseTestCase extends CakeTestCase
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
        $this->capabilities['name'] = get_class($this);
        $this->capabilities['build'] = getenv('BUILD_NUMBER');
        $this->capabilities['platform'] = getenv('SELENIUM_PLATFORM') ? getenv('SELENIUM_PLATFORM') : 'ANY';
        $this->capabilities['version'] = getenv('SELENIUM_VERSION') ? getenv('SELENIUM_VERSION') : '';

        // get the repo info
        $branch = `git rev-parse --abbrev-ref HEAD`;
        if (!$branch) {
            $branch = "Unknown";
        }
        $commit = `git describe --tags --always`;
        if (!$commit) {
            $commit = "Unknown";
        }
        $this->capabilities['custom-data'] = array('commit' => $commit, 'branch' => $branch);

        $host = getenv('SELENIUM_HOST') ? getenv('SELENIUM_HOST') : 'localhost';
        $port = getenv('SELENIUM_PORT') ? getenv('SELENIUM_PORT') : '4444';
        $username = getenv('SAUCE_USER_NAME') ? getenv('SAUCE_USER_NAME') : '';
        $apikey = getenv('SAUCE_API_KEY') ? ':'.getenv('SAUCE_API_KEY').'@' : '';
        $this->seleniumUrl = "http://".$username.$apikey.$host.':'.$port.'/wd/hub';
        $this->url = getenv('SERVER_TEST') ? getenv('SERVER_TEST') : 'http://localhost:2000/';

        /*echo "SELENIUM_BROWSER = ".$this->browser."\n";
        echo "SELENIUM_VERSION = ".$this->capabilities['version']."\n";
        echo "SELENIUM_PLATFORM = ".$this->capabilities['platform']."\n";
        echo "SELENIUM_URL = ".$this->seleniumUrl."\n";
        echo "SERVER_TEST = ".$this->url."\n";*/

        $this->web_driver = new SystemWebDriver($this->seleniumUrl);
    }

    public function getSession($new = false) {
        if ($this->session == null || $new == true) {
            $this->session = $this->web_driver->session($this->browser, $this->capabilities);
            $this->session->deleteAllCookies();
            return $this->session;
        } else {
            return $this->session;
        }
    }

    public function setSessionName($name)
    {
        $this->capabilities['name'] = $name;
    }

    public function setBuildNumber($build)
    {
        $this->capabilities['build'] = $build;
    }

    public function setTags($tags)
    {
        $this->capabilities['tags'] = $tags;
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

    public function endCase()
    {
        if (getenv('SAUCE_USERNAME')) {
            \Sauce\Sausage\SauceTestCommon::ReportStatus($this->getSession()->getId(), $this->_reporter->getStatus());
        }
        $this->getSession()->deleteAllCookies();
        $this->getSession()->close();
    }
}
