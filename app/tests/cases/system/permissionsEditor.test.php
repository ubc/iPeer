<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class PermissionsEditorTestCase extends cakeTestCase
{
    protected $web_driver;
    protected $session;
    protected $url = 'http://ipeerdev.ctlt.ubc.ca/';
    
    public function startCase()
    {
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
    
    public function testAllowAccess()
    {
        // access permission editor
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Home');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Admin')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Admin');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Permissions Editor')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Permissions Editor > superadmin');
        
        // change the role from super admin to instructor
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="role"] option[value="3"]')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Permissions Editor > instructor');
        
        // search for functions/user/index
        $this->findPermissions();
        
        // allow access
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Allow All')->click();
        $this->updatePermissions();
    }
    
    public function testAccess()
    {
        $this->waitForLogout();
        $this->assertEqual($this->session->url(), 'http://ipeerdev.ctlt.ubc.ca/login');

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('instructor1', 'ipeeripeer');
        
        $this->session->open('http://ipeerdev.ctlt.ubc.ca/accesses/view/5');
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Permissions Editor > student');
        
        $this->waitForLogout();
    }
    
    public function testDenyAccess()
    {
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');

        $this->session->open('http://ipeerdev.ctlt.ubc.ca/accesses/view/3');
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Permissions Editor > instructor');

        // search for functions/user/index
        $this->findPermissions();
        
        // deny access
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Deny All')->click();
        $this->updatePermissions();
    }
    
    public function findPermissions()
    {
        // search for the permission to access users/index
        $search = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]');
        $search->sendKeys('controllers/accesses/view');
        
        // open the table row for options
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $count = count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
    }
    
    public function updatePermissions()
    {
        $this->session->accept_alert();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Permissions have been updated');
    }
    
    public function waitForLogout()
    {
        //set the url back to homepage before logging out
        //so that when we login again, we're at the home page.
        $this->session->open('http://ipeerdev.ctlt.ubc.ca/');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Logout')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $title = $session->title();
                return ($title == 'iPeer - Guard');
            }
        );
    }
}