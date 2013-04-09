<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class addRubric extends CakeTestCase
{
    protected $web_driver;
    protected $session;
    protected $url = 'http://ipeerdev.ctlt.ubc.ca/';
    protected $rubricId = 0;
    
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
    
    public function testAddRubricStepOne()
    {
        $this->session->open($this->url.'rubrics');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Rubric')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'name')->sendKeys('Midterm Evaluation');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="LOM"] option[value="3"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="criteria"] option[value="2"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'AvailabilityPrivate')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'zero_mark')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
    }
    
    public function testAddRubricStepTwo()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsLom0LomComment')->sendKeys('Bad');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsLom1LomComment')->sendKeys('Average');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsLom2LomComment')->sendKeys('Good');
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteria0Criteria')->sendKeys('Effort');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteria1Criteria')->sendKeys('Participation');
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment0CriteriaComment')->sendKeys('Does a sloppy job.');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment1CriteriaComment')->sendKeys('Does the minimum work');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment2CriteriaComment')->sendKeys('Exceeds my expectation');

        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment0CriteriaComment')->sendKeys('Does not attend meetings regularly');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment1CriteriaComment')->sendKeys('Attends all meetings');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment2CriteriaComment')->sendKeys('Very active during discussions');

        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria0Multiplier"] option[value="4"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria1Multiplier"] option[value="3"]')->click();
        
        $crit1mk1 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark00')->attribute("value");
        $this->assertEqual($crit1mk1, 0);
        $crit1mk2 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark01')->attribute("value");
        $this->assertEqual($crit1mk2, 2);
        $crit1mk3 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark02')->attribute("value");
        $this->assertEqual($crit1mk3, 4);
        
        $crit2mk1 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark10')->attribute("value");
        $this->assertEqual($crit2mk1, 0);
        $crit2mk2 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark11')->attribute("value");
        $this->assertEqual($crit2mk2, 1.5);
        $crit2mk3 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark12')->attribute("value");
        $this->assertEqual($crit2mk3, 3);
        
        $total = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'total')->attribute("value");
        $this->assertEqual($total, 7); 
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit-rubric')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The rubric was added successfully.');
    }
    
    public function testRubricEdit()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Midterm Evaluation')->click();
        $url = $this->session->url();
        $this->rubricId = end(explode('/', $url));
        $this->session->open(str_replace('view', 'edit', $url));
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'name')->clear();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'name')->sendKeys('Final Project Evaluation');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="LOM"] option[value="4"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="criteria"] option[value="3"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::NAME, 'preview')->click();
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsLom3LomComment')->sendKeys('Excellent');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria0RubricsCriteriaComment3CriteriaComment')->sendKeys('Willing to help others as well');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria1RubricsCriteriaComment3CriteriaComment')->sendKeys('Continually led the group in the right direction');

        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteria2Criteria')->sendKeys('Punctuality');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment0CriteriaComment')->sendKeys('Always late');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment1CriteriaComment')->sendKeys('On time most of the time');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment2CriteriaComment')->sendKeys('On time all of the time');
        $this->session->element(PHPWebDriver_WebDriverBy::ID,
            'RubricsCriteria2RubricsCriteriaComment3CriteriaComment')->sendKeys('Always early');
            
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria0Multiplier"] option[value="5"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria1Multiplier"] option[value="4"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="RubricsCriteria2Multiplier"] option[value="7"]')->click();
            
        $crit1mk1 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark00')->attribute("value");
        $this->assertEqual($crit1mk1, 0);
        $crit1mk2 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark01')->attribute("value");
        $this->assertEqual($crit1mk2, 1.67);
        $crit1mk3 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark02')->attribute("value");
        $this->assertEqual($crit1mk3, 3.33);
        $crit1mk4 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark03')->attribute("value");
        $this->assertEqual($crit1mk4, 5);
        
        $crit2mk1 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark10')->attribute("value");
        $this->assertEqual($crit2mk1, 0);
        $crit2mk2 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark11')->attribute("value");
        $this->assertEqual($crit2mk2, 1.33);
        $crit2mk3 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark12')->attribute("value");
        $this->assertEqual($crit2mk3, 2.67);
        $crit2mk4 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark13')->attribute("value");
        $this->assertEqual($crit2mk4, 4);
        
        $crit3mk1 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark20')->attribute("value");
        $this->assertEqual($crit3mk1, 0);
        $crit3mk2 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark21')->attribute("value");
        $this->assertEqual($crit3mk2, 2.33);
        $crit3mk3 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark22')->attribute("value");
        $this->assertEqual($crit3mk3, 4.67);
        $crit3mk4 = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'RubricsCriteriaMark23')->attribute("value");
        $this->assertEqual($crit3mk4, 7);
        
        $total = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'total')->attribute("value");
        $this->assertEqual($total, 16);
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'submit-rubric')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The rubric evaluation was updated successfully');
    }
    
    public function testDeleteRubric()
    {
        $this->session->open($this->url.'rubrics/delete/'.$this->rubricId);
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The rubric was deleted successfully.');
    }
}