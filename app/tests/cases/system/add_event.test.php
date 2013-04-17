<?php
require_once('system_base.php');

class addEventTestCase extends SystemBaseTestCase
{    
    public function startCase()
    {
        $this->getUrl();
        $wd_host = 'http://localhost:4444/wd/hub';
        $this->web_driver = new PHPWebDriver_WebDriver($wd_host);
        $this->session = $this->web_driver->session('firefox');
        $this->session->open($this->url);
        
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $this->session->deleteAllCookies();
        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('instructor1', 'ipeeripeer');
    }
    
    public function endCase()
    {
        $this->session->deleteAllCookies();
        $this->session->close();
    }
    
    public function testAddEvent()
    {
        $this->session->open($this->url.'events/add/1');
        $this->eventsFormError();
        $this->session->open($this->url.'events/add/1');
        $this->fillInEventAddForm();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Peer Evaluation')->click();
        $eventId = end(explode('/', $this->session->url()));
        $this->session->open($this->url.'events/edit/'.$eventId);
        $this->eventsEdit();
        
        // search that all the email schedules have been created
        $this->session->open($this->url.'emailer');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->sendKeys('mech');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $result = $session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="total-result"]')->text();
                return ($result == 'Total Results: 5');
            }
        );
        
        // check out each scheduled email
        for ($i = 0; $i < 5; $i++) {
            $this->session->element(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'MECH 328')->click();
            $students = count($this->session->elements(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'Student'));
            $this->assertEqual($students, 6);
            $tutors = count($this->session->elements(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'Tutor'));
            $this->assertEqual($tutors, 1);
            $id = end(explode('/', $this->session->url()));
            $this->session->open($this->url.'emailer/cancel/'.$id);
            $w->until(
                function($session) {
                    return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
                }
            );
            $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
            $this->assertEqual($msg, 'The Email was canceled successfully.');
        }
        
        // delete the event
        $this->session->open($this->url.'events/delete/'.$eventId);
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The event has been deleted successfully.');
    }
    
    public function eventsFormError()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Add event failed.');
        
        $validate = $this->session->elements(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message');
        $this->assertEqual($validate[0]->text(), 'Title is required.');
        $this->assertEqual($validate[1]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');
        $this->assertEqual($validate[2]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');
        $this->assertEqual($validate[3]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');
        $this->assertEqual($validate[4]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');
        $this->assertEqual($validate[5]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');
        
        $courseId = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventCourseId"] option');
        $this->assertEqual($courseId[0]->attribute('value'), 1);
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('simple evaluation 2');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                $warning = $session->element(PHPWebDriver_WebDriverBy::ID, 'titleWarning')->text();
                return ($warning == 'Event title "simple evaluation 2" already exists in this course.');
            }
        );
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->clear();
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="3"]')->click();
        $preview = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'prevV')->attribute('href');;
        $this->assertTrue(strpos($preview, 'surveys/view/1'));
        
        // check dates toggle
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'EventDueDate');
        $this->assertTrue(!empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin');
        $this->assertTrue(!empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd');
        $this->assertTrue(!empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'ResultReleaseDateBeginDiv');
        $this->assertTrue(empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'ResultReleaseDateEndDiv');
        $this->assertTrue(empty($date));
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="2"]')->click();
        // check boolean options
        $boolean = $this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked="checked"]');
        $this->assertEqual($boolean[0]->attribute('id'), 'EventSelfEval0');
        $this->assertEqual($boolean[1]->attribute('id'), 'EventComReq0');
        $this->assertEqual($boolean[2]->attribute('id'), 'EventAutoRelease0');
        $this->assertEqual($boolean[3]->attribute('id'), 'EventEnableDetails1');
        $this->assertEqual($boolean[4]->attribute('id'), 'EventEmailSchedule0');

        // check dates toggle
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'EventDueDate');
        $this->assertTrue(!empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin');
        $this->assertTrue(!empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd');
        $this->assertTrue(!empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'ResultReleaseBeginDiv');
        $this->assertTrue(!empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'ResultReleaseEndDiv');
        $this->assertTrue(!empty($date));
        
        // penalty
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $dayOne = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Penalty0DaysLate')->attribute('value');
        $this->assertEqual($dayOne, 1);
        $dayTwo = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Penalty1DaysLate')->attribute('value');
        $this->assertEqual($dayTwo, 2);
        $dayThree = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Penalty2DaysLate')->attribute('value');
        $this->assertEqual($dayThree, 3);
        $percent = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="Penalty1PercentPenalty"] option'));
        $this->assertEqual($percent, 100);
        
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="rmPenaltyInputs(0); return false;"]')->click();
        $penalties = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="penaltyInputs"] div[class="penaltyInput"]'));
        $this->assertEqual($penalties, 2);
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $dayFour = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Penalty3DaysLate')->attribute('value');
        $this->assertEqual($dayFour, 4);
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="rmPenaltyInputs(2); return false;"]')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $dayFive = $this->session->element(PHPWebDriver_WebDriverBy::ID, 'Penalty4DaysLate')->attribute('value');
        $this->assertEqual($dayFive, 5);
    }
    
    public function fillInEventAddForm()
    {
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Final Project Peer Evaluation');
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('Peer evaluation for the final project');
        
        // fill in the dates
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '12')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '4')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '13')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateBegin')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '14')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventResultReleaseDateEnd')->click();
        $this->session->element(PHPWebDriver_WebDriverBy::LINK_TEXT, '28')->click();
        
        // set email reminder frequency to 2 days
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'EventEmailSchedule2')->click();
        // select all groups
        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[id="selectAll"]')->click();
        
        // submit form
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
    
    public function eventsEdit()
    {
        $courseId = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'EventCourseId');
        $this->assertTrue(empty($courseId));

        $eventId = end(explode('/', $this->session->url()));
        $groups = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[selected="selected"]'));
        $this->assertEqual($groups, 2);
        
        $this->session->element(PHPWebDriver_WebDriverBy::ID, 'unselectAll')->click();

        $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $session = $this->session;
        $w->until(
            function($session) {
                return count($session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->element(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Edit event successful!');
        
        $this->session->open($this->url.'events/edit/'.$eventId);
        $groups = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[selected="selected"]'));
        $this->assertEqual($groups, 0);
    }
}