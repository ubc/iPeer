<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PHPWebDriver/WebDriverKeys.php');
require_once('PageFactory.php');

class studentSimple extends CakeTestCase
{
    protected $web_driver;
    protected $session;
    protected $url = 'http://ipeerdev.ctlt.ubc.ca/';
    protected $eventId = 0;
    
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
    
    public function testCreateEvent()
    {
        $this->session->open($this->url.'events/add/1');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Simple Evaluation');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('Description for the Eval.');

        //set due date and release date end to next month so that the event is opened.
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '1')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '4')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '5')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="Next"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '28')->click();
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[value="1"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[value="2"]')->click();

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
    
    public function testStudent()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Logout')->click();
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('redshirt0001', 'ipeeripeer');
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Home');
        
        $pending = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="eventSummary pending"]')->text();
        // check that there is at least one pending event
        $this->assertEqual(substr($pending, -20), 'Pending Events Total');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Simple Evaluation')->click();
        // move the sliders
        $this->handleOffset('handle6', 24);
        $this->handleOffset('handle7', -25);
        
        // wait for distribution to finish
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'distr_button')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $six = $session->element(PHPWebDriver_WebDriverBy::ID, 'point6')->attribute('value');
                $seven = $session->element(PHPWebDriver_WebDriverBy::ID, 'point7')->attribute('value');
                return ($six != '' && $seven != '');
            }
        );
        $alex = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'point6')->attribute('value');
        $matt = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'point7')->attribute('value');
        $this->assertEqual($alex, 140);
        $this->assertEqual($matt, 60);
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'comment6')->sendKeys('A very great group member');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'comment7')->sendKeys('Very responsible.');
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit0')->click();       
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');
    }
    
    public function testReSubmit()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Simple Evaluation')->click();
        $delete = new PHPWebDriver_WebDriverKeys('BackspaceKey');
        // edit user's mark - could not use clear(), the javascript will give it a 'NaN'
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'point6')->sendKeys($delete->key.$delete->key.$delete->key.'120');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'comment6')->click();
        // wait for new status message
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $status = $session->element(PHPWebDriver_WebDriverBy::ID, 'statusMsg')->text();
                return ($status == 'Please allocate 20 more points.');
            }
        );
        // distribute the leftover marks
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'point7')->sendKeys($delete->key.$delete->key.'80');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'comment7')->click();

        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit0')->click();       
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Your Evaluation was submitted successfully.');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Logout')->click();
    }
    
    public function testCheckResult()
    {
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
        
        $this->session->open($this->url.'events/index/1');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Simple Evaluation')->click();
        $this->eventId = end(explode('/', $this->session->url()));
        
        $this->session->open($this->url.'evaluations/view/'.$this->eventId);
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'MECH 328 - Mechanical Engineering Design Project > Simple Evaluation > Results');
        
        $this->session->open($this->url.'evaluations/viewEvaluationResults/'.$this->eventId.'/1');
        // check that the other three members have not completed the evaluation
        $notComp1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, 'html/body/div[1]/table[3]/tbody/tr[2]/td')->text();
        $notComp2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, 'html/body/div[1]/table[3]/tbody/tr[3]/td')->text();
        $notComp3 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, 'html/body/div[1]/table[3]/tbody/tr[4]/td')->text();        
    
        $this->assertEqual($notComp1, 'Alex Student (student)');
        $this->assertEqual($notComp2, 'Matt Student (student)');
        $this->assertEqual($notComp3, 'Tutor 1 (TA)');
        
        // check that the individual marks given are correct
        $mark1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, 'html/body/div[1]/table[5]/tbody/tr[3]/td[2]')->text();        
        $mark2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, 'html/body/div[1]/table[5]/tbody/tr[3]/td[3]')->text();
        $this->assertEqual($mark1, '120');
        $this->assertEqual($mark2, '80');
        
        // check that the average marks given are correct - no penalty
        $avg1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, 'html/body/div[1]/table[5]/tbody/tr[12]/td[2]')->text();        
        $avg2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, 'html/body/div[1]/table[5]/tbody/tr[12]/td[3]')->text();
        $this->assertEqual($avg1, '120');
        $this->assertEqual($avg2, '80');
        
        // check the comments are correct
        $com1 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, ".//*[@id='evalForm2']/table/tbody/tr[2]/td[2]")->text();
        $com2 = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, ".//*[@id='evalForm2']/table/tbody/tr[3]/td[2]")->text();
        $this->assertEqual($com1, 'A very great group member');
        $this->assertEqual($com2, 'Very responsible.');
    }
    
    public function testDeleteEvent()
    {
        $this->session->open($this->url.'events/delete/'.$this->eventId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The event has been deleted successfully.');
    }
    
    public function handleOffset($id, $offset)
    {
        $handle = $this->session->element(PHPWebDriver_WebDriverBy::ID, $id);
        $this->session->moveto(array('element' => $handle->getID()));
        $this->session->buttondown();
        $this->session->moveto(array('xoffset' => $offset, 'yoffset' => 0));
        $this->session->buttonup();
    }
}