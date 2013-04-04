<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class addSurveyTestCase extends CakeTestCase
{
    protected $web_driver;
    protected $session;
    protected $url = 'http://ipeerdev.ctlt.ubc.ca/';
    
    public function startCase() {
        $wd_host = 'http://localhost:4444/wd/hub';
        $this->web_driver = new PHPWebDriver_WebDriver($wd_host);
        $this->session = $this->web_driver->session('firefox');
        $this->session->open($this->url);
        
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $this->session->deleteAllCookies();
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }
    
    public function endCase() {
        $this->session->deleteAllCookies();
        $this->session->close();
    }
    
    public function testAddSurvey() {
        $this->session->open($this->url.'surveys/add');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'SurveyName')->sendKeys('Group Making Survey');
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
        $this->assertEqual($msg, 'Survey is saved!');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Group Making Survey')->click();
        $surveyId = end(explode('/', $this->session->url()));
        $this->session->open($this->url.'surveys/questionsSummary/'.$surveyId);
        $this->addMC($surveyId);
        $this->addMultipleAnswers($surveyId);
        $this->addTextQues($surveyId);
        
        // check that the questions have been added correctly
        $this->session->open($this->url.'surveys/view/'.$surveyId);
        $radio = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="radio"]'));
        $this->assertEqual($radio, 5);
        $check = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="checkbox"]'));
        $this->assertEqual($check, 4);
        $text = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="text"]'));
        $this->assertEqual($text, 1);
        $textarea = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'textarea'));
        $this->assertEqual($textarea, 1);
        
        // delete survey template
        $this->session->open($this->url.'surveys/delete/'.$surveyId);
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The survey was deleted successfully.');
    }
    
    public function addMC($surveyId) {
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('What year are you in your program?');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Response0Response')->sendKeys('1st');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Response1Response')->sendKeys('2nd');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Response2Response')->sendKeys('3rd');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Response3Response')->sendKeys('4th');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Response4Response')->sendKeys('5th +');
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was added successfully.');
    }
    
    public function addMultipleAnswers($surveyId)
    {
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('Which operating systems have you used before?');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionType"] option[value="C"]')->click();
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Response0Response')->sendKeys('Windows');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Response1Response')->sendKeys('Mac');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Response2Response')->sendKeys('Linux');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Response')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Response3Response')->sendKeys('Chrome');
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                    return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was added successfully.');
    }
    
    public function addTextQues($surveyId)
    {
        // short answer
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('What is your favourite course this term?');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionType"] option[value="S"]')->click();
 
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                    return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was added successfully.');
        
        // long answer
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'QuestionPrompt')->sendKeys('Tell me a little bit about yourself.');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="QuestionType"] option[value="L"]')->click();
 
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save Question"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                    return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The question was added successfully.');
    }
}