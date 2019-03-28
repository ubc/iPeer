<?php
App::import('Lib', 'system_base');

class MoveStudentTestCase extends SystemBaseTestCase
{
    protected $courseId;

    public function startCase()
    {
        parent::startCase();
        echo "Start MoveStudent system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('admin1', 'ipeeripeer');
    }

    public function testAddSurveyEvent()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'Courses');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'APSC 201')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'APSC 201 - Technical Communication');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Event')->click();
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'APSC 201 - Technical Communication > Add Event');

        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle');
        $title->sendKeys('Group Making Survey');

        $desc = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDescription');
        $desc->sendKeys('This survey is for creating groups.');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="EventEventTemplateTypeId"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR,
            'select[id="EventSurvey"] option[value="1"]')->click();

        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin')->click();
        // $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->click();
        $this->selectDayOnCalendar('EventDueDate', date('j'), false);
        $this->selectDayOnCalendar('EventReleaseDateBegin', date('j'), false);
        $this->selectDayOnCalendar('EventReleaseDateEnd', date('j'), false);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Add event successful!');
    }

    public function testCopyStudent()
    {
        $this->session->open($this->url.'courses/move');

        $submit = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit');
        $this->assertTrue($submit->attribute('disabled'));

        $sourceCourse = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSourceCourses"] option[value="1"]');
        $sourceCourse->click();
        $this->assertEqual($sourceCourse->text(), 'MECH 328 - Mechanical Engineering Design Project');
        // need to wait for the next drop down menu to populate
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSourceSurveys"] option')) - 1;
            }
        );

        $sourceSurvey = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSourceSurveys"] option[value="4"]');
        $sourceSurvey->click();
        $this->assertEqual($sourceSurvey->text(), 'Team Creation Survey');
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSubmitters"] option')) - 1;
            }
        );

        $submitter = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSubmitters"] option[value="31"]');
        $submitter->click();
        $this->assertEqual($submitter->text(), 'Hui Student');
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestCourses"] option')) - 1;
            }
        );

        $destCourse = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestCourses"] option');
        $this->assertEqual($destCourse[0]->text(), '-- Pick a course --');
        $this->assertEqual($destCourse[1]->text(), 'APSC 201 - Technical Communication');
        $courseId = $destCourse[1]->attribute('value');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestCourses"] option[value="'.$courseId.'"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestSurveys"] option')) - 1;
            }
        );

        $destSurvey = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestSurveys"] option');
        $this->assertEqual($destSurvey[0]->text(), '-- Pick a survey --');
        $this->assertEqual($destSurvey[1]->text(), 'Group Making Survey');
        $eventId = $destSurvey[1]->attribute('value');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestSurveys"] option[value="'.$eventId.'"]')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'CourseAction0')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Hui Student was successfully copied to APSC 201 - Technical Communication.');

        $this->deleteEvent($eventId);
    }

    public function testMoveStudent()
    {
        $this->edgeCasesSetup();
        $this->session->open($this->url.'courses/move');
        $this->moveStudent('8');

        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Chris Student was successfully moved to EECE 375 101 - Project Course.');
    }

    public function testAlreadySubmitted()
    {
        $this->session->open($this->url.'courses/move');
        $this->moveStudent('9');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage")->text();
        $this->assertEqual($msg, 'The student has already submitted to the destination survey');
        $this->session->open($this->url.'users/goToClassList/1');
        // drop Johnny Student that could not be moved.
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]')->sendKeys('johnny');
        $w->until(
            function($session) {
                $count = count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Drop')->click();
        $this->session->accept_alert();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Student is successfully unenrolled!');
    }

    public function testAlreadyEnrolled()
    {
        $this->session->open($this->url.'courses/move');
        $this->moveStudent('10');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Travis Student was successfully moved to EECE 375 101 - Project Course.');

        $this->session->open($this->url.'users/goToClassList/1');
        $total = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'table_id_info')->text();
        $this->assertEqual($total, 'Showing 1 to 10 of 13 entries');
        $this->session->open($this->url.'courses/delete/'.$this->courseId);
    }

    public function deleteEvent($eventId)
    {
        $this->session->open($this->url.'evaluations/viewSurveySummary/'.$eventId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="individualResponses"]')->sendKeys('Hui');
        $result = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Result');
        $this->session->open($result->attribute('href')); // instead of trying to go to the new window or tab
        $title = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "h1.title")->text();
        $this->assertEqual($title, 'APSC 201 - Technical Communication > Group Making Survey > Results');

        // unenrol Hui Student
        $this->session->open($this->url.'users/goToClassList/2');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[aria-controls="table_id"]')->sendKeys('Hui');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $count = count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'tr[class="odd"]'));
                return ($count == 1);
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="  sorting_1"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Drop')->click();
        $this->session->accept_alert();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Student is successfully unenrolled!');

        // delete Group Making Survey Event
        $this->session->open($this->url.'events/delete/'.$eventId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The event has been deleted successfully.');
    }

    public function enrolStudent($username, $courseId)
    {
        $this->session->open($this->url.'users/add/'.$courseId);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'UserUsername')->sendKeys($username);
        // wait for "username already exist" warning
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return $session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="usernameErr"]')->text();
            }
        );

        $warning = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="usernameErr"]')->text();
        $this->assertEqual(substr($warning, 0, 39), 'Username "'.$username.'" already exists.');

        // click here to enrol
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'here')->click();
        // wait for the student to be enrolled
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'User is successfully enrolled.');
    }

    public function edgeCasesSetup()
    {
        $this->enrolStudent('redshirt0004', '1');
        $this->enrolStudent('redshirt0005', '1');
        $this->enrolStudent('redshirt0006', '1');

        $this->waitForLogoutLogin('redshirt0004');
        $this->fillInSurvey('1', '5', 'Team Creation Survey');
        $this->waitForLogoutLogin('redshirt0005');
        $this->fillInSurvey('1', '6', 'Team Creation Survey');
        $this->waitForLogoutLogin('redshirt0006');
        $this->fillInSurvey('2', '5', 'Team Creation Survey');

        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'courses/add');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'CourseCourse')->sendKeys('EECE 375 101');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'CourseTitle')->sendKeys('Project Course');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Save"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'EECE 375 101')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Event')->click();
        $_temp_url = explode('/', $this->session->url());
        $this->courseId = end($_temp_url);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Test Survey');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventSurvey"] option[value="1"]')->click();
        //set due date and release date end to next month so that the event is opened.
        $this->selectDayOnCalendar('EventDueDate', '1');
        $this->selectDayOnCalendar('EventReleaseDateBegin', date('j'), false);
        $this->selectDayOnCalendar('EventReleaseDateEnd', '4');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();

        $this->enrolStudent('redshirt0005', $this->courseId);
        $this->enrolStudent('redshirt0006', $this->courseId);

        $this->waitForLogoutLogin('redshirt0005');
        $this->fillInSurvey('1', '5', 'Test Survey');
        $this->waitForLogoutLogin('root');
    }

    public function fillInSurvey($first, $snd, $survey)
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, $survey)->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SurveyInput0ResponseId'.$first)->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SurveyInput1ResponseId'.$snd)->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }

    public function moveStudent($userId)
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSourceCourses"] option[value="1"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSourceSurveys"] option')) - 1;
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSourceSurveys"] option[value="4"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSubmitters"] option')) - 1;
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseSubmitters"] option[value="'.$userId.'"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestCourses"] option')) - 1;
            }
        );
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestCourses"] option[value="'.$this->courseId.'"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestSurveys"] option')) - 1;
            }
        );
        $surveys = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="CourseDestSurveys"] option');
        $surveys[1]->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit')->click();
    }
}
