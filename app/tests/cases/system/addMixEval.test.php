<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class AddMixEvalTestCase extends CakeTestCase
{
    protected $wd_driver;
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
    
    public function testAddMixEval()
    {
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Home');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Evaluation')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Evaluation Tools');

        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Mixed Evaluations')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Mixed Evaluations');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Mix Evaluation')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Mixed Evaluations > Add');
        
        // template info
        $name = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalName');
        $name->sendKeys('Final Project Evaluation');
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalAvailabilityPublic')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalZeroMark')->click();
        
        // likert question
        $this->addLikert();
        
        // sentence question
        $this->addSentence();
        
        // paragraph question
        $this->addParagraph();
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $session = $this->session;
        // wait for creation of template to finish
        $w = new PHPWebDriver_WebDriverWait($session);
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The mixed evaluation was saved successfully.');
        
        // delete the template
        $this->deleteTemplate();
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The Mixed Evaluation was removed successfully.');
    }
    
    public function addLikert() 
    {
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="button"]')->click();
        
        $question = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Title');
        $question->sendKeys('In your opinion, how is their work ethics?');
        
        $instructions = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion0Instructions');
        $instructions->sendKeys('Please be honest.');
        
        $marks = $this->session->element(PHPWebDriver_WebDriverBy::ID, "MixevalQuestion0Multiplier");
        $marks->sendKeys('8');
        
        $add = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="addDesc(0);"]');
        for ($i=0; $i<5; $i++) {
            $add->click();
        }
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return (count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="DescsDiv0"] div')) - 4);
            }
        );
        for ($i=0; $i<5; $i++) {
            $desc = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestionDesc'.$i.'Descriptor');
            $mark = $i * 2;
            $desc->sendKeys($mark.' marks');
        }
    }
    
    public function addSentence()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionType"] option[value="3"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();
        
        $question = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Title');
        $question->sendKeys('Which part of the project was their greatest contribution?');
        
        $instructions = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Instructions');
        $instructions->sendKeys('Choose one of the following: Research, Report, Presentation.');
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion1Required')->click();
    }
    
    public function addParagraph()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="MixevalMixevalQuestionType"] option[value="2"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[onclick="insertQ();"]')->click();
        
        $question = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Title');
        $question->sendKeys('What have they done well? How can they improve?');
        
        $instructions = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'MixevalQuestion2Instructions');
        $instructions->sendKeys('Please give constructive comments.');
    }
    
    public function deleteTemplate()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Evaluation')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Mixed Evaluations > View > Final Project Evaluation');
        
        $templateId = end(explode('/', $this->session->url()));
        $this->session->open($this->url.'mixevals/delete/'.$templateId);
    }
}