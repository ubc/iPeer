<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class massMoveTestCase extends CakeTestCase
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
    
    public function testImportUsers()
    {
        $courseId = $this->addTestCourse();
        $this->session->open($this->url.'courses/import');
        $file = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseFile');
        $file->sendKeys('../tests/cases/system/massMove.csv');
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseIdentifiersUsername')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 
            'select[id="CourseSourceCourses"] option[value="1"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSourceSurveys"] option')) - 1;  
            }
        );
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 
            'select[id="CourseSourceSurveys"] option[value="4"]')->click();
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestCourses"] option')) - 1;  
            }
        );
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="CourseDestCourses"] option[value="'.$courseId.'"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseAction0')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        
        $header = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'h3')->text();
        $this->assertEqual($header, 'User(s) successfully transferred:');
        
        // check that 7 students have been copied over to the new course
        $this->session->open($this->url.'users/goToClassList/'.$courseId);
        $classList = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'table_id_info')->text();
        $this->assertEqual($classList, 'Showing 1 to 7 of 7 entries');
        
        // check the event has been duplicated
        $this->session->open($this->url.'events/index/'.$courseId);
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Team Creation Survey')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'TEST 101 101 - Demo Course > Team Creation Survey > View');
        $eventId = end(explode('/', $this->session->url()));
        
        // check that the two submissions have been copied over
        $this->session->open($this->url.'evaluations/viewSurveySummary/'.$eventId);
        $submitted = count($this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Result'));
        $this->assertEqual($submitted, 2);
        
        $this->session->open($this->url.'courses/delete/'.$courseId);
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The course was deleted successfully.');
    }
    
    public function addTestCourse()
    {
        $this->session->open($this->url.'courses/add');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseCourse')->sendKeys('TEST 101 101');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseTitle')->sendKeys('Demo Course');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'TEST 101 101')->click();
        
        return end(explode('/', $this->session->url()));
    }
}