<?php
App::import('Lib', 'system_base');

class LoginTestCase extends SystemBaseTestCase
{
    protected $ini;
    protected $browser;

    public function startCase()
    {
        parent::startCase();
        echo "Start Login system test.\n";
        $this->getSession()->open($this->url);
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
                if ($this->session->elementWithWait($type, $locator)->displayed()) {
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
            $returnValue = $session->elementsWithWait($selectorType, $location);
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
       $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
       $this->assertEqual($title, 'Home');
    }
}
