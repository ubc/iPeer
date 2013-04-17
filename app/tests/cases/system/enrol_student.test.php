<?php
require_once('system_base.php');

class EnrolStudentTestCase extends SystemBaseTestCase
{    
    public function startCase()
    {
        $this->getUrl();
        $wd_host = 'http://localhost:4444/wd/hub';
        $this->web_driver = new PHPWebDriver_WebDriver($wd_host);
        $this->session = $this->web_driver->session('firefox');
        $this->session->open($this->url);
        
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $this->session->deleteAllCookies();
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }
    
    public function endCase()
    {
        $this->session->deleteAllCookies();
        $this->session->close();
    }
    
    public function testEnrolStudent()
    {
        $this->session->open($this->url.'users/add/2');
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'APSC 201 - Technical Communication > Add User');
        
        // enter username: redshirt0004
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'UserUsername')->sendKeys('redshirt0004');
        // wait for "username already exist" warning
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return $session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="usernameErr"]')->text();
            }
        );
        
        $warning = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="usernameErr"]')->text();
        $this->assertEqual(substr($warning, 0, 39), 'Username "redshirt0004" already exists.');
        
        // click here to enrol
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'here')->click();
        
        // wait for the student to be enrolled
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Student is successfully enrolled.');  
    }
    
    public function testUnenrolStudent()
    {
        // unenrol Chris Student
        $this->session->open($this->url.'users/goToClassList/2');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]')->sendKeys('Chris');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $count = count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Drop')->click();
        $this->session->accept_alert();
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Student is successfully unenrolled!');
    }
}