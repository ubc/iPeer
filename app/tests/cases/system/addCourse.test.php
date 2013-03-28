<?php
require_once('PHPWebDriver/WebDriver.php');
require_once('PHPWebDriver/WebDriverBy.php');
require_once('PHPWebDriver/WebDriverWait.php');
require_once('PageFactory.php');

class AddCourseTestCase extends CakeTestCase
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
    
    public function testAddCourse()
    {
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Home');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Courses');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Course')->click();
        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Add Course');
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseCourse')->sendKeys('EECE 474 101');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseTitle')->sendKeys('Project Course');

        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseInstructors"] option[value="2"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Instructor')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseInstructors"] option[value="4"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Instructor')->click();

        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="DepartmentDepartment"] option[value="2"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="DepartmentDepartment"] option[value="3"]')->click();
    
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseHomepage')->sendKeys('http://www.ece.ubc.ca/course/eece-474');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Course created!');        
    }
    
    public function testDeleteCourse()
    {       
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'EECE 474 101')->click();

        $title = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'EECE 474 101 - Project Course');

        $courseId = end(explode('/', $this->session->url()));
        
        $this->session->open($this->url.'courses/delete/'.$courseId);
        
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The course was deleted successfully.');
    }
}