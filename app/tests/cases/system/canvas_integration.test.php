<?php
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverActionChains.php');
require_once(VENDORS.'webdriver/PHPWebDriver/WebDriverKeys.php');
App::import('Lib', 'system_base');

// Assumptions:
// - A Canvas testing environment is available.  If using docker, refer to iPeer readme.md on how
//   to create a bridge to connect the iPeer app and Canvas app containers
// - Check the system parameters in iPeer db and make sure the base URLs are defined properly
// - Modify the following constants accordingly

// TODO: move to environment variable
const ENABLE_CANVAS_TEST = false;
const CANVAS_TEST_BASE_URL = 'http://docker-canvas_app_1';
const CANVAS_ADMIN_LOGIN = 'ipeertest';
const CANVAS_ADMIN_PASSWORD = 'password';

//// testing data

const CANVAS_INSTRUCTOR1_LOGIN = 'instructor1';
const CANVAS_INSTRUCTOR1_FULLNAME = 'Instructor 1';
const CANVAS_INSTRUCTOR1_PASSWORD = 'password';
const CANVAS_INSTRUCTOR1_EMAIL = 'instructor1@test.ubc.ca';

const CANVAS_TA1_LOGIN = 'tutor1';
const CANVAS_TA1_FULLNAME = 'Tutor 1';
const CANVAS_TA1_PASSWORD = 'password';
const CANVAS_TA1_EMAIL = 'tutor1@test.ubc.ca';

const CANVAS_TA2_LOGIN = 'tutor2';
const CANVAS_TA2_FULLNAME = 'Tutor 2';
const CANVAS_TA2_PASSWORD = 'password';
const CANVAS_TA2_EMAIL = 'tutor2@test.ubc.ca';

const CANVAS_STUDENT1_LOGIN = 'redshirt0001';
const CANVAS_STUDENT1_FULLNAME = 'Ed Student';
const CANVAS_STUDENT1_PASSWORD = 'password';
const CANVAS_STUDENT1_EMAIL = 'redshirt0001@test.ubc.ca';

const CANVAS_STUDENT2_LOGIN = 'redshirt0002';
const CANVAS_STUDENT2_FULLNAME = 'Alex Student';
const CANVAS_STUDENT2_PASSWORD = 'password';
const CANVAS_STUDENT2_EMAIL = 'redshirt0002@test.ubc.ca';

const CANVAS_STUDENT3_LOGIN = 'redshirt0003';
const CANVAS_STUDENT3_FULLNAME = 'Matt Student';
const CANVAS_STUDENT3_PASSWORD = 'password';
const CANVAS_STUDENT3_EMAIL = 'redshirt0003@test.ubc.ca';

const CANVAS_NEW_STUDENT1_LOGIN = 'blueshirt0001';
const CANVAS_NEW_STUDENT1_FULLNAME = 'Blue1 Student';
const CANVAS_NEW_STUDENT1_PASSWORD = 'password';
const CANVAS_NEW_STUDENT1_EMAIL = 'blueshirt0001@test.ubc.ca';

const CANVAS_NEW_STUDENT2_LOGIN = 'blueshirt0002';
const CANVAS_NEW_STUDENT2_FULLNAME = 'Blue2 Student';
const CANVAS_NEW_STUDENT2_PASSWORD = 'password';
const CANVAS_NEW_STUDENT2_EMAIL = 'blueshirt0002@test.ubc.ca';

const IPEER_ADMIN_LOGIN = 'root';
const IPEER_ADMIN_PASSWORD = 'ipeeripeer';

const IPEER_INSTRUCTOR1_LOGIN = 'instructor1';
const IPEER_INSTRUCTOR1_PASSWORD = 'ipeeripeer';

const IPEER_TA1_LOGIN = 'tutor1';
const IPEER_TA1_PASSWORD = 'ipeeripeer';

const IPEER_TA2_LOGIN = 'tutor2';
const IPEER_TA2_PASSWORD = 'ipeeripeer';

const IPEER_STUDENT1_LOGIN = 'redshirt0001';
const IPEER_STUDENT1_PASSWORD = 'ipeeripeer';
const IPEER_STUDENT1_STUDENTNO = '65498451';

const IPEER_STUDENT2_LOGIN = 'redshirt0002';
const IPEER_STUDENT2_PASSWORD = 'ipeeripeer';
const IPEER_STUDENT2_STUDENTNO = '65468188';

const IPEER_STUDENT3_LOGIN = 'redshirt0003';
const IPEER_STUDENT3_PASSWORD = 'ipeeripeer';
const IPEER_STUDENT3_STUDENTNO = '98985481';

class CanvasIntegrationTestCase extends SystemBaseTestCase
{
    public function skip()
    {
        $this->skipUnless(ENABLE_CANVAS_TEST, 'Canvas integration test disabled');
    }

    public function startCase()
    {
        parent::startCase();
        echo "Start Canvas integration system test.\n";
        echo "Create test data in Canvas...\n";

        $this->_canvasLoginAdmin();
        
        // create instructors, TAs, and students
        $this->_canvasUserCreate(
            CANVAS_INSTRUCTOR1_LOGIN, CANVAS_INSTRUCTOR1_FULLNAME, CANVAS_INSTRUCTOR1_PASSWORD,
            CANVAS_INSTRUCTOR1_EMAIL, IPEER_INSTRUCTOR1_LOGIN, true);
        for ($i = 1; $i <= 2; $i++) {
            $this->_canvasUserCreate(
                constant('CANVAS_TA'.$i.'_LOGIN'),
                constant('CANVAS_TA'.$i.'_FULLNAME'),
                constant('CANVAS_TA'.$i.'_PASSWORD'),
                constant('CANVAS_TA'.$i.'_EMAIL'),
                constant('IPEER_TA'.$i.'_LOGIN'), true);
        }
        for ($i = 1; $i <= 3; $i++) {
            $this->_canvasUserCreate(
                constant('CANVAS_STUDENT'.$i.'_LOGIN'),
                constant('CANVAS_STUDENT'.$i.'_FULLNAME'),
                constant('CANVAS_STUDENT'.$i.'_PASSWORD'),
                constant('CANVAS_STUDENT'.$i.'_EMAIL'),
                constant('IPEER_STUDENT'.$i.'_LOGIN'), true);
        }
        for ($i = 1; $i <= 2; $i++) {
            $this->_canvasUserCreate(
                constant('CANVAS_NEW_STUDENT'.$i.'_LOGIN'),
                constant('CANVAS_NEW_STUDENT'.$i.'_FULLNAME'),
                constant('CANVAS_NEW_STUDENT'.$i.'_PASSWORD'),
                constant('CANVAS_NEW_STUDENT'.$i.'_EMAIL'),
                constant('CANVAS_NEW_STUDENT'.$i.'_LOGIN'), true);  // students in Canvas only. no integration key (ipeer login) yet
        }

        // create developer key
        echo "Create a developer key for OAuth2...\n";

        $canvasDevKey = $this->_canvasCreateDevKey();   // create a new developer key for ipeer integration
        $this->_canvasLogout();

        $this->_ipeerLoginAdmin();
        $this->_ipeerSetCanvasDevKey($canvasDevKey['id'], $canvasDevKey['key']);
        $this->_ipeerLogout();

    }
    
    //////////////////////////
    // Test cases
    //

    public function testCreateNewCourseAndImportStudents()
    {
        $courseCode = 'CPSC567';
        $courseTitle = 'Integration Test with Canvas';
        
        // create a new course in Canvas. also generate new developer key
        $this->_canvasLoginAdmin();
        $this->_canvasCourseCreate($courseCode, $courseTitle, true);
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_INSTRUCTOR1_LOGIN, CANVAS_INSTRUCTOR1_PASSWORD, 'Teacher');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_STUDENT1_LOGIN, CANVAS_STUDENT1_PASSWORD, 'Student');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_STUDENT2_LOGIN, CANVAS_STUDENT2_PASSWORD, 'Student');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_NEW_STUDENT1_LOGIN, CANVAS_NEW_STUDENT1_PASSWORD, 'Student');
        $this->_canvasLogout();
        sleep(1);
        
        // create a new course in iPeer
        $this->_ipeerLogin(IPEER_INSTRUCTOR1_LOGIN, IPEER_INSTRUCTOR1_PASSWORD);
        $this->_ipeerCourseCreate($courseCode, $courseTitle);
        // try to import students from Canvas. that should trigger OAuth
        $this->_ipeerImportStudentFromCanvas(
            CANVAS_INSTRUCTOR1_LOGIN, CANVAS_INSTRUCTOR1_PASSWORD,
            $courseCode, $courseTitle, $courseTitle,
            array(CANVAS_NEW_STUDENT1_LOGIN),       // imported new students that were in Canvas only
            array(CANVAS_STUDENT1_LOGIN, CANVAS_STUDENT2_LOGIN));
        $this->_ipeerLogout();
    }

    public function testCreateCourseBasedOnCanvas()
    {
        $courseCode = 'CPSC568';
        $courseTitle = 'Integration Test with Canvas 2';
        
        // create a new course in Canvas and add instructors / TAs
        $this->_canvasLoginAdmin();
        $this->_canvasCourseCreate($courseCode, $courseTitle, true);
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_INSTRUCTOR1_LOGIN, CANVAS_INSTRUCTOR1_PASSWORD, 'Teacher');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_TA1_LOGIN, CANVAS_TA1_PASSWORD, 'TA');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_TA2_LOGIN, CANVAS_TA2_PASSWORD, 'TA');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_STUDENT1_LOGIN, CANVAS_STUDENT1_PASSWORD, 'Student');
        $this->_canvasLogout();
        sleep(1);
        
        // For TAs in Canvas to be mapped as instructors in iPeer, change the ipeer default role
        $this->_ipeerLoginAdmin();
        for ($i = 1; $i <= 2; $i++) {
            $this->_ipeerChangeUserDefaultRole(constant('IPEER_TA'.$i.'_LOGIN'), 'instructor');
        }
        $this->_ipeerLogout();

        // create a new course in iPeer based on the Canvas course
        $this->_ipeerLogin(IPEER_INSTRUCTOR1_LOGIN, IPEER_INSTRUCTOR1_PASSWORD);
        $this->_ipeerCourseCreateBasedOnCanvas($courseCode, $courseTitle);
        $this->_ipeerLogout();
    }
/*
    public function testSyncGroup()
    {
        $courseCode = 'CPSC569';
        $courseTitle = 'Integration Test with Canvas 3';

        // create a new course in Canvas and add instructors / TAs
        $this->_canvasLoginAdmin();
        $this->_canvasCourseCreate($courseCode, $courseTitle, true);
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_INSTRUCTOR1_LOGIN, CANVAS_INSTRUCTOR1_PASSWORD, 'Teacher');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_TA1_LOGIN, CANVAS_TA1_PASSWORD, 'TA');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_STUDENT1_LOGIN, CANVAS_STUDENT1_PASSWORD, 'Student');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_STUDENT2_LOGIN, CANVAS_STUDENT2_PASSWORD, 'Student');
        $this->_canvasEnrollmentAdd($courseTitle, CANVAS_STUDENT3_LOGIN, CANVAS_STUDENT3_PASSWORD, 'Student');
        $this->_canvasLogout();
        sleep(1);

        // create a new course in iPeer based on the Canvas course
        $this->_ipeerLogin(IPEER_INSTRUCTOR1_LOGIN, IPEER_INSTRUCTOR1_PASSWORD);
        $this->_ipeerCourseCreate($courseCode, $courseTitle);
        $this->_ipeerAddStudent($courseCode, $courseTitle,
            array(IPEER_STUDENT1_LOGIN, IPEER_STUDENT2_LOGIN, IPEER_STUDENT3_LOGIN));
        $this->_ipeerCreateGroup($courseCode, $courseTitle,
            array(
                array(),    // empty group
                array(IPEER_STUDENT1_STUDENTNO, IPEER_STUDENT2_STUDENTNO),
                array(IPEER_STUDENT3_STUDENTNO)
            ));
        $this->_ipeerSyncCanvasGroup(CANVAS_INSTRUCTOR1_LOGIN, CANVAS_INSTRUCTOR1_PASSWORD,
            $courseCode, $courseTitle, $courseTitle);
        $this->_ipeerLogout();
        $this->_canvasLogout();
        
        // TODO: verify groups created in Canvas
    }
*/

    //////////////////////////
    // Helper functions for iPeer flow
    //

    private function _ipeerCourseCreate($courseCode, $courseTsitle)
    {
        $this->getSession()->open($this->url);
        
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Home');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Courses');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Course')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Add Course');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'CourseCourse')->sendKeys($courseCode);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'CourseTitle')->sendKeys($courseTsitle);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseInstructors"] option[value="2"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Instructor')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseTutors"] option[value="35"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Tutor')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="DepartmentDepartment"] option[value="1"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="DepartmentDepartment"] option[value="2"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Course created!');
    }
    
    private function _ipeerLogin($user, $password)
    {
        $this->getSession()->open($this->url);
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login($user, $password);
    }

    private function _ipeerLoginAdmin()
    {
        $this->_ipeerLogin(IPEER_ADMIN_LOGIN, IPEER_ADMIN_PASSWORD);
    }
    
    private function _ipeerLogout()
    {
        $this->getSession()->open($this->url . 'logout');
    }
    
    private function _ipeerSetCanvasDevKey($id, $key) {
        $this->getSession()->open($this->url . 'sysparameters');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->sendKeys('system.canvas_client_id');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//input[@type="submit" and @value="Search"]')->click();
        sleep(2);
        $paramId = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[1]/div')->text();
        $this->getSession()->open($this->url . 'sysparameters/edit/'.$paramId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SysParameterParameterValue')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SysParameterParameterValue')->sendKeys($id);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//div[@class="submit"]/input[@type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The record is edited successfully.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->sendKeys('system.canvas_client_secret');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//input[@type="submit" and @value="Search"]')->click();
        sleep(2);
        $paramId = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[1]/div')->text();
        $this->getSession()->open($this->url . 'sysparameters/edit/'.$paramId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SysParameterParameterValue')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SysParameterParameterValue')->sendKeys($key);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//div[@class="submit"]/input[@type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The record is edited successfully.');
    }
    
    private function _ipeerCourseHome($courseTitle)
    {
        $this->getSession()->open($this->url . 'courses');
        $course = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[text()="'.$courseTitle.'"]')->click();
    }
    
    private function _ipeerImportStudentFromCanvas($canvasUser, $canvasPassword, $courseCode, $courseTitle, $canvasCourseTitle, $createdStudent, $updatedStudent)
    {
        $this->_ipeerCourseHome($courseTitle);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[text()="Import Users from Canvas"]')->click();
        $this->_handleOAuth($canvasUser, $canvasPassword);
        
        // the iPeer course should be selecte by default
        // $selected = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
        //     '//*[@id="CourseCourse"]/option[@selected="selected"]');
        // $this->assertEqual($courseCode . ' - ' . $courseTitle, $selected->text());

        // select the canvas course
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//*[@id="UserCanvasCourse"]/option[starts-with(normalize-space(text()), "'.$canvasCourseTitle.'")]')->click();
            
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//div[@class="submit"]/input[@type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Import successful! See below for import details.');

        // make sure the students are added
        if (isset($createdStudent)) {
            for ($i = 0; $i < sizeof($createdStudent); $i++) {
                $student = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    "/html/body/div[1]/table[1]/tbody/tr[".($i+2)."]/td[1]")->text();
                $this->assertEqual($student, $createdStudent[$i]);
            }
        }
        if (isset($updatedStudent)) {
            for ($i = 0; $i < sizeof($updatedStudent); $i++) {
                $student = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    "/html/body/div[1]/table[2]/tbody/tr[".($i+2)."]/td[1]")->text();
                $this->assertEqual($student, $updatedStudent[$i]);
            }
        }
    }
    
    private function _handleOAuth($canvasUser, $canvasPassword)
    {
        sleep(2);
        $buttons = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//input[@type="submit" and @value="Authorize"]');
        $login = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="pseudonym_session_unique_id"]');

        if (sizeof($buttons) > 0) {
            // user already logged into Canvas. simply click the authorize button
            // $this->assertTrue(!$expectLogin);
            $buttons[0]->click();
        } else if (sizeof($login) > 0) {
            // user not logged in yet
            // $this->assertTrue($expectLogin);
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'pseudonym_session_unique_id')->sendKeys($canvasUser);
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'pseudonym_session_password')->sendKeys($canvasPassword);
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
            
            // authorize
            $buttons = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//input[@type="submit" and @value="Authorize"]');
            $buttons[0]->click();
        } else {
            // already authorized. nothing to do
            //$this->assertTrue(!$expectLogin);
            return;
        }
        sleep(2);
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class*='message']")->text();
        $this->assertEqual($msg, 'You have successfully connected to Canvas.');
    }

    private function _ipeerCourseCreateBasedOnCanvas($canvasCourseCode, $canvasCourseTitle)
    {
        $this->getSession()->open($this->url . 'courses');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//a[normalize-space(text())="Add Course Based on Canvas"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//select[@id="CanvasCourses"]/option[starts-with(normalize-space(text()), "'.$canvasCourseTitle.'")]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//a[normalize-space(text())="Next"]')->click();

        // check the course code and name
        $prefillCourseCode = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'CourseCourse');
        $this->assertEqual($prefillCourseCode->attribute('value'), $canvasCourseCode);
        $prefillCourseTitle = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'CourseTitle');
        $this->assertEqual($prefillCourseTitle->attribute('value'), $canvasCourseTitle);
        // make sure instructor1 is added
        $addedInstructor = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Instructor0FullName');
        $this->assertEqual($addedInstructor->attribute('value'), 'Instructor 1');
        // make sure the two tutors are added
        $addedTutor1 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Instructor1FullName');
        $this->assertEqual($addedTutor1->attribute('value'), 'Tutor 1');
        $addedTutor2 = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Instructor2FullName');
        $this->assertEqual($addedTutor2->attribute('value'), 'Tutor 2');
        
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="DepartmentDepartment"] option[value="1"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Course created!');
    }
    
    private function _ipeerAddStudent($courseCode, $courseTitle, $students)
    {
        $this->_ipeerCourseHome($courseTitle);
        foreach ($students as $student) {
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[text()="Add Student"]')->click();
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserUsername')->sendKeys($student);
            sleep(2);
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[text()=" here"]')->click();
            $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
            $this->assertEqual($msg, 'User is successfully enrolled.');
        }
    }
    
    private function _ipeerCreateGroup($courseCode, $courseTitle, $groups)
    {
        $this->_ipeerCourseHome($courseTitle);
        $groupCount = 1;
        foreach ($groups as $group) {
            $memberCount = 0;
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[text()="Add Group"]')->click();
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'GroupGroupName')->sendKeys('Group '.$groupCount);

            $action = new PHPWebDriver_WebDriverActionChains($this->session);
            $action->keyDown(new PHPWebDriver_WebDriverKeys('ControlKey'));
            foreach ($group as $member) {
                $handle = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH,
                    '//*[@id="all_groups"]/option[contains(text(), "'.$member.'")]');
                if (sizeof($handle) > 0) {
                    $action->click($handle[0]);
                    $memberCount +=1;
                }
            }
            $action->keyUp(new PHPWebDriver_WebDriverKeys('ControlKey'));

            if ($memberCount > 0) {
                $action->perform();
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    '//input[@type="button" and @value="Assign >>"]')->click();
            }

            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                '//input[@type="submit" and @value="Add Group"]')->click();
            $groupCount += 1;
            
            $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
            $this->assertEqual($msg, 'The group was added successfully.');
        }
    }
    
    private function _ipeerSyncCanvasGroup($canvasUser, $canvasPassword, $courseCode, $courseTitle, $canvasCourseTitle)
    {
        $this->_ipeerCourseHome($courseTitle);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[text()="Sync Canvas Groups"]')->click();
        $this->_handleOAuth($canvasUser, $canvasPassword);
        
        // the iPeer course should be selecte by default
        // $selected = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
        //     '//*[@id="GroupCourse"]/option[@selected="selected"]');
        // $this->assertEqual($courseCode . ' - ' . $courseTitle, $selected->text());
        // select the canvas course
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//*[@id="GroupCanvasCourse"]/option[starts-with(normalize-space(text()), "'.$canvasCourseTitle.'")]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        sleep(2);
        //$this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();  // create new group set

        // should be in simplified mode
        $simplified = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//input[@id="GroupFormTypeSimplified"]');
        //$this->assertTrue($simplified->isSelected());

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Success! Groups and users exported to Canvas are highlighted below.');
    }

    private function _ipeerChangeUserDefaultRole($username, $role) {
        $this->getSession()->open($this->url . 'users');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->sendKeys($username);
        sleep(2);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="ajaxListDiv"]/div/table/tbody/tr[2]/td[2]/div')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//div[text()="Edit User"]')->click();
        sleep(1);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="RoleRolesUserRoleId"] option[value="3"]')->click();
        if ($role == 'instructor') {
            sleep(1);
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="FacultyFaculty"] option[value="1"]')->click();
        }
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'User successfully updated!');
    }

    //////////////////////////
    // Helper functions for Canvas flow
    //
    
    private function _canvasLogin($user, $password)
    {
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/login/canvas');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'pseudonym_session_unique_id')->sendKeys($user);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'pseudonym_session_password')->sendKeys($password);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        
        // if it is the first time, ack the agreement
        sleep(2);
        $agreement = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//input[@type="checkbox" and @name="user[terms_of_use]"]');
        if (sizeof($agreement) > 0) {
            $agreement[0]->click();
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();            
        }
    }
    
    private function _canvasLoginAdmin()
    {
        $this->_canvasLogin(CANVAS_ADMIN_LOGIN, CANVAS_ADMIN_PASSWORD);
    }

    private function _canvasLogout()
    {
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/logout');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Button--logout-confirm')->click();
        sleep(2);
    }
    
    private function _canvasCreateDevKey($forceNew = true)
    {
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/accounts/site_admin/developer_keys');
        // see if the latest key is a suitable key for integration test
        $defined = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="keys"]/tbody/tr[1]/td[1]');
        if (!(sizeof($defined) > 0 and $defined[0]->text() == 'iPeer integration test')) {
            $forceNew = true;
        }

        if ($forceNew) {
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//button[@title="Add Developer Key"]')->click();
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'key_name')->sendKeys('iPeer integration test');
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'redirect_uri')->sendKeys($this->url);
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'redirect_uris')->sendKeys($this->url);
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        }
        sleep(5);

        // simulate mouse drag over the key so the key is displayed and we can copy it
        $handle = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//*[@id="keys"]/tbody/tr[1]/td[3]/div/div[2]');
        $action = new PHPWebDriver_WebDriverActionChains($this->session);
        $action->clickAndHold($handle)->moveByOffset(1, 0)->release()->perform();

        return array(
            'id' => substr($this->session->elementWithWait(
                PHPWebDriver_WebDriverBy::XPATH, '//*[@id="keys"]/tbody/tr[1]/td[3]/div/div[1]')->text(), 4),
            'key' => substr($this->session->elementWithWait(
                PHPWebDriver_WebDriverBy::XPATH, '//*[@id="keys"]/tbody/tr[1]/td[3]/div/div[2]')->text(), 5)
            );
    }
    
    private function _canvasCourseCreate($code, $title, $deleteIfExist=true)
    {
        if ($deleteIfExist) {
            $this->_deleteCanvasCourseFromHome($title);
        }
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/courses');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'start_new_course')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'course_name')->sendKeys($title);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="course_license"] option[value="public_domain"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'course_is_public')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//button[span="Create course"]')->click();
        sleep(1);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[class*="btn-publish"]')->click();
        sleep(1);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//span[text()="Course Activity Stream"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//button[span="Choose and Publish"]')->click();
        sleep(5);   // AJAX call... don't continue too quick
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[text()="Settings"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'course_course_code')->clear();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'course_course_code')->sendKeys($code);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//button[@type="submit"]')->click();
        sleep(5);
    }
    
    private function _deleteCanvasCourseFromHome($title)
    {
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/courses');
        $courses = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[title="'.$title.'"]');
        if (sizeof($courses) > 0) {
            $this->getSession()->open($courses[0]->attribute('href') . '/confirm_action?event=delete');
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[type="submit"]')->click();
        }
    }
    
    /**
     * _canvasUserCreate
     *
     * Create new Canvas user.  If $forceCreateNew is true, existing logins that match
     * $login or $email will be deleted. And, the logic will delete any
     * integration id associated with the user before deleting.
     *
     * @access protected
     * @return void
     */
    private function _canvasUserCreate($login, $fullname, $password, $email, $integrationId, $forceCreateNew=true)
    {
        if ($forceCreateNew) {
            // In Canvas, integration IDs are unique and can't be duplicated.
            // However, deleting a login won't clear the integration ID for a new login to use.
            // The only way to do it is to loop all logins and clear the integration ID before doing a/c user deletion.
            // Otherwise, will need to manually patch the table "pseudonyms".
            //$this->_clearIntegrationId($integrationId);
            $this->_clearIntegrationId($login);
            $this->_clearIntegrationId($email);
            $this->_clearIntegrationId($this->_fullname_to_display_name($fullname));

            $this->_canvasDeleteUserByLogin($login);
            $this->_canvasDeleteUserByLogin($email);
            $this->_canvasDeleteUserByLogin($this->_fullname_to_display_name($fullname));
        } else {
            
            $this->getSession()->open(CANVAS_TEST_BASE_URL . '/accounts/site_admin/users');
            $users = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//ul[contains(@class, "users")]//a[starts-with(@href, "/accounts/")]');
            $foundUser = false;
            foreach ($users as $user) {
                if ($user->text() == $email || $user->text() == $login) {
                    return;
                }
            }
        }
        
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/accounts/site_admin/users');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[contains(@class, "add_user_link")]/span')->click();
        sleep(1);  // allow ajax pop-up
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//table[@class="formtable"]//input[@id="user_name"]')->sendKeys($fullname);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//table[@class="formtable"]//input[@id="pseudonym_unique_id"]')->sendKeys($email);
        sleep(2); // allow auto-fillin to complete
        // set display name and sortable name
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
        //     '//table[@class="formtable"]//input[@id="user_short_name"]')->clear();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
        //     '//table[@class="formtable"]//input[@id="user_short_name"]')->sendKeys($login);
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
        //     '//table[@class="formtable"]//input[@id="user_sortable_name"]')->clear();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
        //     '//table[@class="formtable"]//input[@id="user_sortable_name"]')->sendKeys($login.'_last, '.$login.'_first');
            
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//span[starts-with(text(), "Add User")]')->click();
        sleep(2);

        // reload the page and see if new user created
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/accounts/site_admin/users');
        $users = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//ul[contains(@class, "users")]//a[starts-with(@href, "/accounts/")]');
        $foundUser = false;
        foreach ($users as $user) {
            if ($user->text() == $this->_fullname_to_display_name($fullname)) {
                $foundUser = $user;
                break;
            }
        }
        $this->assertTrue($foundUser);
        
        // create a new login using 
        $foundUser->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[starts-with(text(), "Add Login")]')->click();
        sleep(1);  // allow ajax pop-up
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//table[@class="formtable"]//input[@id="pseudonym_unique_id"]')->sendKeys($login);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//select[@id="pseudonym_account_id"]/option[normalize-space(text())="Site Admin"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//input[@id="pseudonym_password"]')->sendKeys($password);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
            '//input[@id="pseudonym_password_confirmation"]')->sendKeys($password);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//button[starts-with(text(), "Add Login")]')->click();
        sleep(2);

        // before we can assign the integration id, login as the new user and ack the agreement
        $this->_canvasLogout();
        $this->_canvasLogin($login, $password);
        $this->_canvasLogout();
        $this->_canvasLoginAdmin();
        
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/accounts/site_admin/users');
        $users = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//ul[contains(@class, "users")]//a[starts-with(@href, "/accounts/")]');
        $foundUser = false;
        foreach ($users as $user) {
            if ($user->text() == $this->_fullname_to_display_name($fullname)) {
                $foundUser = $user;
                break;
            }
        }
        $this->assertTrue($foundUser);
        $foundUser->click();
        sleep(1);
        $logins = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//a[@class="edit_pseudonym_link"]');
        $this->assertTrue(sizeof($logins) > 1);
        if (sizeof($logins) > 1) {
            $logins[1]->click();
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                '//table[@class="formtable"]//input[@id="pseudonym_integration_id"]')->sendKeys($login);
            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//button[starts-with(text(), "Update Login")]')->click();
        }
    }
    
    // loop through all login and clear the given save but slow.
    /*
    private function _clearIntegrationId($integrationId)
    {
        $userLinks = array();

        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/accounts/site_admin/users');
        $users = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//ul[contains(@class, "users")]//a[starts-with(@href, "/accounts/")]');
        foreach ($users as $user) {
            array_push($userLinks, $user->attribute('href'));
        }
        foreach ($userLinks as $link) {
            $this->getSession()->open($link);

            $logins = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//a[@class="edit_pseudonym_link"]');
            foreach ($logins as $loginEdit) {$login
                if ($loginEdit->displayed()) {
                    $loginEdit->click();
                    $curentId = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH,
                        '//table[@class="formtable"]//input[@id="pseudonym_integration_id"]');
                    if (sizeof($curentId) > 0 && $curentId[0]->attribute('value') == $integrationId) {
                        $curentId[0]->clear();
                        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//div[contains(@class, "ui-dialog")]//div[@class="form-controls"]//button[starts-with(text(), "Update Login")]')->click();
                        sleep(1);
                        return;
                    } else {
                        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//div[contains(@class, "ui-dialog")]//div[@class="form-controls"]//button[starts-with(text(), "Cancel")]')->click();
                    }
                    sleep(1);
                }
            }
        }
    }
    */
    // assume a user always assigned the same integration id.  so just
    // clear the integration id of given user.
    private function _clearIntegrationId($identifier)
    {
        $userLinks = array();

        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/accounts/site_admin/users');
        $users = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//ul[contains(@class, "users")]//a[starts-with(@href, "/accounts/")]');
        foreach ($users as $user) {
            if (trim($user->text()) == $identifier) {
                $user->click();

                $logins = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//a[@class="edit_pseudonym_link"]');
                foreach ($logins as $loginEdit) {
                    if ($loginEdit->displayed()) {
                        $loginEdit->click();
                        $curentId = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH,
                            '//table[@class="formtable"]//input[@id="pseudonym_integration_id"]');
                        if (sizeof($curentId) > 0) {
                            $curentId[0]->clear();
                            $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//div[contains(@class, "ui-dialog")]//div[@class="form-controls"]//button[starts-with(text(), "Update Login")]')->click();
                            sleep(1);
                        }
                        sleep(1);
                    }
                }
                break;
            }
        }
    }
    
    
    
    private function _canvasDeleteUserByLogin($login)
    {
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/accounts/site_admin/users');
        $users = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH, '//ul[contains(@class, "users")]//a[starts-with(@href, "/accounts/")]');
        foreach ($users as $user) {
            if (trim($user->text()) == $login) {
                $user->click();
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//a[starts-with(text(), "Delete from")]')->click();
                sleep(2);
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH, '//button[starts-with(text(), "Delete ")]')->click();
                break;
            }
        }
    }
    
    private function _canvasEnrollmentAdd($courseTitle, $login, $password, $role)
    {
        $this->getSession()->open(CANVAS_TEST_BASE_URL . '/courses');
        $courses = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH,
            '//table[@id="my_courses_table"]//a[starts-with(@href, "/courses/")]');
        foreach ($courses as $course) {
            if ($course->attribute('title') == $courseTitle) {
                $course->click();
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    '//nav[@role="navigation"]//a[normalize-space(text())="People"]')->click();
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    '//a[contains(@class, "btn") and @id="addUsers"]')->click();
                sleep(1);   // wait for ajax pop-up
                // search by login
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    '//*[@id="add_people_modal"]/div[2]/div/div/div/fieldset[1]/span/span/span[2]/span/span/span/span[2]/label/span/span[1]')->click();
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    '//fieldset//textarea')->sendKeys($login);
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    '//select[@id="peoplesearch_select_role"]//option[normalize-space(text())="'.$role.'"]')->click();
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    '//button[@id="addpeople_next"]')->click();
                sleep(1);
                $this->session->elementWithWait(PHPWebDriver_WebDriverBy::XPATH,
                    '//button[@id="addpeople_next"]')->click();
                break;
            }
        }
        
        // login as user to accept the enrollment
        $this->_canvasLogout();
        $this->_canvasLogin($login, $password);
        $enrollments = $this->session->elements(PHPWebDriver_WebDriverBy::XPATH,
            '//div[contains(@class, "ic-notification__actions")]//button[@type="submit" and @name="accept"]');
        $this->assertTrue(sizeof($enrollments) > 0);
        if (sizeof($enrollments) > 0) {
            $enrollments[0]->click();
        }
        $this->_canvasLogout();
        $this->_canvasLoginAdmin();
    }

    private function _fullname_to_display_name($fullname) {
        $token = explode(' ', $fullname);
        if (!$token or count($token) != 2) {
            return $fullname;
        }
        return $token[1].', '.$token[0];
    }
}
