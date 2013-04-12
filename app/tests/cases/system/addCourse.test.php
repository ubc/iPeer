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
    
    public function testErrors()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Course')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $error = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($error, 'Add course failed.');
        $error = $this->session->element(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message')->text();
        $this->assertEqual($error, 'The course name is required.');
        
        // did not select an instructor - no instructors should be added
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Instructor')->click();
        $instructor = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'Instructor0FullName');
        $this->assertTrue(empty($instructor));
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseCourse')->sendKeys('APSC 201');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $error = $this->session->element(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message')->text();
        $this->assertEqual($error, 'A course with this name already exists');
    }
    
    public function testDefaultValues()
    {
        $this->waitForLogout();
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('instructor1', 'ipeeripeer');
        
        $this->session->open($this->url.'courses/add');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseCourse')->sendKeys('CPSC 101 101');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'CourseTitle')->sendKeys('Introductory Course to Programming');
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Course created!');

        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'CPSC 101 101')->click();
        $courseId = end(explode('/', $this->session->url()));
        $this->session->open($this->url.'courses/edit/'.$courseId);
        
        // instructor1 is defaulted to be an instructor
        $instructor = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Instructor0FullName')->attribute("value");
        $this->assertEqual($instructor, 'Instructor 1');
        
        // departments - MECH/APSC selected
        $departments = $this->session->elements(PHPWebDRiver_WebDriverBy::CSS_SELECTOR,
            'select[id="DepartmentDepartment"] option[selected="selected"]');
        $this->assertEqual($departments[0]->attribute('value'), '1');
        $this->assertEqual($departments[1]->attribute('value'), '2');
        
        $this->session->open($this->url.'courses/delete/'.$courseId);
        
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The course was deleted successfully.');
    }
    
    public function testInactiveCourse()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'EECE 474 101')->click();
        $courseId = end(explode('/', $this->session->url()));
        $this->session->open($this->url.'courses/edit/'.$courseId);
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="status"] option[value="I"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The course was updated successfully.');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Super Admin')->click();
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::ID, "flashMessage")->text();
        $this->assertEqual($msg, 'Error: You do not have permission to view this user');

        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'EECE 474 101')->click();
        // Students
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Student');
        $this->assertTrue(empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Import Students');
        $this->assertTrue(empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'List Students');
        $this->assertTrue(!empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Email to All Students');
        $this->assertTrue(empty($link));
        
        // Groups
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Create Groups (Manual)');
        $this->assertTrue(empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Create Groups (Import)');
        $this->assertTrue(empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'List Groups');
        $this->assertTrue(!empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Export Groups');
        $this->assertTrue(!empty($link));

        // Evaluation Events
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Event');
        $this->assertTrue(empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'List Evaluation Events');
        $this->assertTrue(!empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Export Evaluation Results');
        $this->assertTrue(!empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Move Students');
        $this->assertTrue(!empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Move Group of Students');
        $this->assertTrue(!empty($link));
        
        // Team Maker
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Create Groups (Auto)');
        $this->assertTrue(empty($link));
        $link = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'List Survey Group Sets');
        $this->assertTrue(!empty($link));
    }
    
    public function testDeleteCourse()
    {
        $this->session->open($this->url.'courses');
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'EECE 474 101')->click();
        $courseId = end(explode('/', $this->session->url()));
        
        $this->session->open($this->url.'courses/delete/'.$courseId);
        
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The course was deleted successfully.');
    }
    
    public function testCourseHomeLinks()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'MECH 328')->click();

        $web = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[target="_blank"]')->attribute('href');
        $this->assertEqual(substr($web, 0, 22), 'http://www.mech.ubc.ca');
        
        $instructor = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Instructor 1');
        $instructor[1]->click();
        $this->assertEqual($this->url.'users/view/2', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Tutor 1')->click();
        $this->assertEqual($this->url.'users/view/35', $this->session->url());
        $this->session->open($this->url.'courses/home/1');

        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Tutor 2')->click();
        $this->assertEqual($this->url.'users/view/36', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $students = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, ".//*[@id='CourseHome']/table/tbody/tr[2]/td[4]")->text();
        $this->assertEqual($students, '13 students');
        
        $groups = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, ".//*[@id='CourseHome']/table/tbody/tr[2]/td[5]")->text();
        $this->assertEqual($groups, '2 groups');
        
        $events = $this->session->element(PHPWebDriver_WebDriverBy::XPATH, ".//*[@id='CourseHome']/table/tbody/tr[2]/td[6]")->text();
        $this->assertEqual($events, '9 events');
    }
    
    public function testStudentLinks()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Student')->click();
        $this->assertEqual($this->url.'users/add/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Import Students')->click();
        $this->assertEqual($this->url.'users/import/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'List Students')->click();
        $this->assertEqual($this->url.'users/goToClassList/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Email to All Students')->click();
        $this->assertEqual($this->url.'emailer/write/C/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
    }
    
    public function testGroupLinks()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Create Groups (Manual)')->click();
        $this->assertEqual($this->url.'groups/add/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Create Groups (Import)')->click();
        $this->assertEqual($this->url.'groups/import/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'List Groups')->click();
        $this->assertEqual($this->url.'groups/index/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Export Groups')->click();
        $this->assertEqual($this->url.'groups/export/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
    }
    
    public function testEventLinks()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Event')->click();
        $this->assertEqual($this->url.'events/add/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'List Evaluation Events')->click();
        $this->assertEqual($this->url.'events/index/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Export Evaluation Results')->click();
        $this->assertEqual($this->url.'evaluations/export/course/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Move Students')->click();
        $this->assertEqual($this->url.'courses/move', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Move Group of Students')->click();
        $this->assertEqual($this->url.'courses/import', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
    }

    public function testTeamMakerLinks()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Create Groups (Auto)')->click();
        $this->assertEqual($this->url.'surveygroups/makegroups/1', $this->session->url());
        $this->session->open($this->url.'courses/home/1');
        
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'List Survey Group Sets')->click();
        $this->assertEqual($this->url.'surveygroups/index/1', $this->session->url());
    }
    
    private function waitForLogout()
    {
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