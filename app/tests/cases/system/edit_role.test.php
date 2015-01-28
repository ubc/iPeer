<?php
App::import('Lib', 'system_base');

class EnrolStudentTestCase extends SystemBaseTestCase
{
    public function startCase()
    {
        parent::startCase();
        echo "Start Role system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testUnenrolEnrolStudent()
    {
        return;
        // unenroll Student from his course
        $this->session->open($this->url.'users/edit/6');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "course_1")->sendKeys('none');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="message good-message green"]')->text();
        $this->assertEqual($msg,'User successfully updated!');
        
        // log in as ex-student and check for 0 courses
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->open($this->url.'home');
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="eventSummary alldone"]')->text();
        $this->assertEqual($msg,'No Event(s) Pending');
        
        // put student back in
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'users/edit/6');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "course_1")->sendKeys('student');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="message good-message green"]')->text();
        $this->assertEqual($msg,'User successfully updated!');
        
        // log in as student and check for some events
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->open($this->url.'home');
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="eventSummary pending"]')->text();
        $this->assertTrue(!empty($msg));
    }
    
    public function testStudentInstructorDuality()
    {
        // set Student as instructor for one course (primary role: Instructor)
        // still a student in other course
        $this->session->open($this->url.'users/edit/6');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "RoleRolesUserRoleId")->sendKeys('instructor');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "course_2")->sendKeys('instructor');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="FacultyFaculty"][class="enabled"]')->sendKeys('Applied Science');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="message good-message green"]')->text();
        $this->assertEqual($msg,'User successfully updated!');

        // log in as Student and check Instructor View
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->open($this->url.'home');
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="instructorView"] h1[class="title"]')->text();
        $this->assertEqual($title, 'Instructor View');
        $courseName = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="course"] h3')->text();
        $this->assertEqual($courseName, 'APSC 201');

        // check Student View
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="StudentHome"] div[class="eventSummary pending"]')->text();
        $this->assertEqual($msg,'7 Pending Event(s) Total');

        // check Courses tab
        $tab = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="navigationOuter"] ul li a[href="/courses"]')->text();
        $this->assertEqual($tab, 'Courses');

        // check Evaluation tab
        $tab = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="navigationOuter"] ul li a[href="/evaltools"]')->text();
        $this->assertEqual($tab, 'Evaluation');

        // reset: remove Student as instructor for the course (primary role: Student)
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'users/edit/6');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "RoleRolesUserRoleId")->sendKeys('student');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "course_2")->sendKeys('none');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="message good-message green"]')->text();
        $this->assertEqual($msg,'User successfully updated!');

        // log in as Student and check that no longer instructor
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->open($this->url.'home');

        // check that instructorView (and student home title) does not exist by counting number of h1 elements
        $allHeaders = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'h1');
        $this->assertEqual(count($allHeaders), 1);
    }

    
    public function testSingleCourseDualityPrevention() {
        // set Student as instructor for one course (primary role: Instructor)
        // still a student in other course
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'users/edit/6');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "RoleRolesUserRoleId")->sendKeys('instructor');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "course_2")->sendKeys('instructor');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="FacultyFaculty"][class="enabled"]')->sendKeys('Applied Science');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="message good-message green"]')->text();
        $this->assertEqual($msg,'User successfully updated!');

        // edit other course where Student is student, while their system role is Instructor
        $this->session->open($this->url.'courses/edit/1');
        // make sure Student in not an option for instructor to add
        $options = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseInstructors"] option');
        $this->assertEqual(count($options), 3);


        // reset: remove Student as instructor for the course (primary role: Student)
        $this->session->open($this->url.'users/edit/6');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "RoleRolesUserRoleId")->sendKeys('student');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "course_2")->sendKeys('none');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[class="message good-message green"]')->text();
        $this->assertEqual($msg,'User successfully updated!');
    }

}
