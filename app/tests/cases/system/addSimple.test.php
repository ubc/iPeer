<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class addSimple extends CakeTestCase
{
    protected $web_driver;
    protected $session;
    protected $url = 'http://ipeerdev.ctlt.ubc.ca/';
    protected $simpleId = 0;
    
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
    
    public function testAdd()
    {
        $this->session->open($this->url.'simpleevaluations');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Simple Evaluation')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Simple Evaluations > Add Template');

        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationName')->sendKeys('Project Eval Part One');
        $desc = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationDescription');
        $desc->sendKeys('Please evaluate your group members for the first part of the project.');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationPointPerMember')->sendKeys('50');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPublic')->click();
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The evaluation was added successfully.');
    }
    
    public function testEdit()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Project Eval Part One')->click();
        $url = $this->session->url();
        $this->simpleId = end(explode('/', $url));
        $this->session->open(str_replace('view', 'edit', $url));
        $points = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'SimpleEvaluationPointPerMember');
        $this->assertEqual($points->attribute('value'), 50);
        $points->clear();
        $points->sendKeys('72');

        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The simple evaluation was updated successfully.');
    }
    
    public function testDelete()
    {
        $this->session->open($this->url.'simpleevaluations/delete/'.$this->simpleId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The evaluation was deleted successfully.');
    }
}