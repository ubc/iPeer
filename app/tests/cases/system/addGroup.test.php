<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class AddGroupTestCase extends CakeTestCase
{
    protected $web_driver;
    protected $session;
    protected $url = 'http://ipeerdev.ctlt.ubc.ca/';
    protected $groupId = 0;
    
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
    
    public function testAddGroup()
    {
        $this->session->open($this->url.'courses/home/2');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Create Groups (Manual)')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'APSC 201 - Technical Communication > Add Group');
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'GroupGroupName')->sendKeys('Amazing Group');
        
        // adding Geoff Student
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="30"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();
        
        // adding Van Hong Student
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="27"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();
        
        // adding Denny Student
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="18"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();
        
        // adding Trevor Student
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="23"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();
        
        $count = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option'));
        $this->assertEqual($count, 4);
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The group was added successfully.');  
    }
    
    public function testEditGroup()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Amazing Group (4 members)')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'APSC 201 - Technical Communication > Groups > View');
        $this->groupId = end(explode('/', $this->session->url()));
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Edit this Group')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'GroupGroupName')->sendKeys(' Two');
        
        // removing Van Hong Student
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="30"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="18"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="23"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="<< Remove "]')->click();

        $count = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option'));
        $this->assertEqual($count, 3);
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The group was updated successfully.');
    }
    
    public function testDeleteGroup()
    {
        $this->session->open($this->url.'groups/delete/'.$this->groupId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The group was deleted successfully.');
    }
}