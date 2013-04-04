<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class addEventTestCase extends CakeTestCase
{
    protected $web_driver;
    protected $session;
    protected $url = "http://ipeerdev.ctlt.ubc.ca/";
    
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
    
    public function testAddEvent()
    {
        $this->session->open($this->url.'events/add/1');
        $this->fillInEventAddForm();
        
        // search that all the email schedules have been created
        $this->session->open($this->url.'emailer');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->sendKeys('mech');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $result = $session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="total-result"]')->text();
                return ($result == 'Total Results: 5');
            }
        );
        
        // check out each scheduled email
        for ($i = 0; $i < 5; $i++) {
            $this->session->element(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'MECH 328')->click();
            $students = count($this->session->elements(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'Student'));
            $this->assertEqual($students, 6);
            $tutors = count($this->session->elements(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'Tutor'));
            $this->assertEqual($tutors, 1);
            $id = end(explode('/', $this->session->url()));
            $this->session->open($this->url.'emailer/cancel/'.$id);
            $w->until(
                function($session) {
                    return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
                }
            );
            $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
            $this->assertEqual($msg, 'The Email was canceled successfully.');
        }
        
        // delete the event
        $this->session->open($this->url.'events/index/1');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Peer Evaluation')->click();
        $eventId = end(explode('/', $this->session->url()));
        $this->session->open($this->url.'events/delete/'.$eventId);
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The event has been deleted successfully.');
    }
    
    public function fillInEventAddForm()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Final Project Peer Evaluation');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('Peer evaluation for the final project');
        
        // fill in the dates
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '12')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '4')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '13')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '14')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '28')->click();
        
        // set email reminder frequency to 2 days
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventEmailSchedule2')->click();
        // select all groups
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[id="selectAll"]')->click();
        
        // submit form
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Add event successful!');     
    }
}