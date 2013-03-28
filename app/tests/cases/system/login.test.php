<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class LoginTestCase extends CakeTestCase
{
    protected $web_driver;
    protected $session;
    protected $ini;
    protected $browser;
    protected $url = "http://ipeerdev.ctlt.ubc.ca/";

    public function startCase()
    {
        $wd_host = 'http://localhost:4444/wd/hub';
        $this->web_driver = new PHPWebDriver_WebDriver($wd_host);
        //$this->session = $this->web_driver->session('ie', array('version' => '8'));
        $this->session = $this->web_driver->session('firefox');
        $this->session->open($this->url);
    }

    public function endCase()
    {
        $this->session->deleteAllCookies();
        $this->session->close();
    }

    public function captureScreen($screenshot)
    {
        $filename = date('ymdHisu').'.png';
        $imgData = base64_decode($screenshot);
        return file_put_contents($filename, $imgData);
    }

    public function assertWaitForElementToAppear($type, $locator, $waitTimeInSeconds)
    {
        $timerUp = time() + $waitTimeInSeconds;
        $lastException = '';

        while ($timerUp > time()) {
            try {
                if ($this->session->element($type, $locator)->displayed()) {
                    return;
                }
            }
            catch (Exception $e) {
                $lastException = $e->getMessage();
                usleep(50000);
            }
        }

        $this->fail('The element of type "'.$type.'" located by "'.$locator.'" is not visible. '.$lastException);

    }

    public function getElementFromKey($key)
    {
        $location = $this->ini[$key];
        if (preg_match('/.*\.xpath/',$key)) {
            return $this->getElementFrom("xpath", $location, null);
        }
        else if (preg_match('/.*\.css_selector/',$key)) {
            return $this->getElementFrom("css selector", $location, null);
        }
        else {
            throw new RuntimeException("Unable to extract the selector type from the key '".$key."'");
        }
    }

    private function getElementsFrom($selectorType, $location, $session)
    {
        if (is_null($session)) {
            $session = $this->session;
        }

        $returnValue = null;
        try {
            $returnValue = $session->elements($selectorType, $location);
        } catch (NoSuchElementWebDriverError $e) {
            $returnValue = null;
        }

        return $returnValue;
    }

    public function testLogin()
    {
       $w = new PHPWebDriver_WebDriverWait($this->session);
       $this->session->deleteAllCookies();
       $login = PageFactory::initElements($this->session, 'Login');
       $home = $login->login('root', 'ipeeripeer');
       $this->assertEqual($this->session->url(), $this->url);
       // make sure we are landed on home page
       $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
       $this->assertEqual($title, 'Home');
    }
}
