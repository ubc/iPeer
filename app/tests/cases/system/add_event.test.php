<?php
App::import('Lib', 'system_base');

class addEventTestCase extends SystemBaseTestCase
{
    public function startCase()
    {
        parent::startCase();
        echo "Start AddEvent system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('instructor1', 'ipeeripeer');
    }

    public function testAddEvent()
    {
        $this->session->open($this->url.'events/add/1');
        $this->eventsFormError();
        $this->session->open($this->url.'events/add/1');
        $this->fillInEventAddForm();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Final Project Peer Evaluation')->click();
        $_temp_url = explode('/', $this->session->url());
        $eventId = end($_temp_url);

        // search that all the email schedules have been created
        $this->session->open($this->url.'emailer');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->sendKeys('iPeer');
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $result = $session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'td[class="total-result"]')->text();
                return ($result == 'Total Results: 4');
            }
        );

        // check email recipients
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'iPeer Evaluation Reminder')->click();
        $students = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'Student'));
        $this->assertEqual($students, 6);
        $tutors = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::PARTIAL_LINK_TEXT, 'Tutor'));
        $this->assertEqual($tutors, 1);

        // edit event
        $this->session->open($this->url.'events/edit/'.$eventId);
        $this->eventsEdit();

        // delete the event
        $this->session->open($this->url.'events/delete/'.$eventId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'The event has been deleted successfully.');
    }

    public function eventsFormError()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage')->text();
        $this->assertEqual($msg, 'Add event failed.');

        $validate = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CLASS_NAME, 'error-message');
        $this->assertEqual($validate[0]->text(), 'Title is required.');
        $this->assertEqual($validate[1]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');
        $this->assertEqual($validate[2]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');
        $this->assertEqual($validate[3]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');
        $this->assertEqual($validate[4]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');
        $this->assertEqual($validate[5]->text(), 'Must be in Year-Month-Day Hour:Minute:Second format.');

        $courseId = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventCourseId"] option');
        $this->assertEqual($courseId[0]->attribute('value'), 1);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('simple evaluation 2');
        // duplicate event title is allowed now
        /*$w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                $warning = $session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'titleWarning')->text();
                return ($warning == 'Event title "simple evaluation 2" already exists in this course.');
            }
        );*/
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->clear();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="3"]')->click();
        $preview = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'prevV')->attribute('href');;
        $this->assertTrue(strpos($preview, 'surveys/view'));

        // check dates toggle
        $date = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDueDate');
        $this->assertTrue(!empty($date));
        $date = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin');
        $this->assertTrue(!empty($date));
        $date = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd');
        $this->assertTrue(!empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'ResultReleaseDateBeginDiv');
        $this->assertTrue(empty($date));
        $date = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'ResultReleaseDateEndDiv');
        $this->assertTrue(empty($date));

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="2"]')->click();
        // check boolean options
        $boolean = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[checked="checked"]');
        $this->assertEqual($boolean[0]->attribute('id'), 'EventSelfEval0');
        $this->assertEqual($boolean[1]->attribute('id'), 'EventComReq0');
        $this->assertEqual($boolean[2]->attribute('id'), 'EventAutoRelease0');
        $this->assertEqual($boolean[3]->attribute('id'), 'EventEnableDetails1');

        // check dates toggle
        $date = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDueDate');
        $this->assertTrue(!empty($date));
        $date = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateBegin');
        $this->assertTrue(!empty($date));
        $date = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd');
        $this->assertTrue(!empty($date));
        $date = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'ResultReleaseBeginDiv');
        $this->assertTrue(!empty($date));
        $date = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, 'ResultReleaseEndDiv');
        $this->assertTrue(!empty($date));

        // penalty
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $dayOne = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Penalty0DaysLate')->attribute('value');
        $this->assertEqual($dayOne, 1);
        $dayTwo = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Penalty1DaysLate')->attribute('value');
        $this->assertEqual($dayTwo, 2);
        $dayThree = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Penalty2DaysLate')->attribute('value');
        $this->assertEqual($dayThree, 3);
        $percent = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="Penalty1PercentPenalty"] option'));
        $this->assertEqual($percent, 100);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="rmPenaltyInputs(0); return false;"]')->click();
        $penalties = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'div[id="penaltyInputs"] div[class="penaltyInput"]'));
        $this->assertEqual($penalties, 2);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $dayFour = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Penalty3DaysLate')->attribute('value');
        $this->assertEqual($dayFour, 4);
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'a[onclick="rmPenaltyInputs(2); return false;"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Add Penalty')->click();
        $dayFive = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'Penalty4DaysLate')->attribute('value');
        $this->assertEqual($dayFive, 5);
    }

    public function fillInEventAddForm()
    {
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Final Project Peer Evaluation');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('Peer evaluation for the final project');

        // set email reminder frequency to 2 days
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[value="2"]')->click();
        // select Evaluation Reminder Template
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailTemplate"] option[value="5"]')->click();
        // select all groups
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[id="selectAll"]')->click();

        // fill in the dates
        $this->selectDayOnCalendar('EventDueDate', '12');
        $this->selectDayOnCalendar('EventReleaseDateBegin', '4');
        $this->selectDayOnCalendar('EventReleaseDateEnd', '13');
        $this->selectDayOnCalendar('EventResultReleaseDateBegin', '14');
        $this->selectDayOnCalendar('EventResultReleaseDateEnd', '28');

        // submit form
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

    public function eventsEdit()
    {
        $courseId = $this->session->elements(PHPWebDriver_WebDriverBy::ID, 'EventCourseId');
        $this->assertTrue(empty($courseId));

        // test that email frequency is 2 days
        $email = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[selected="selected"]');
        $this->assertEqual($email->attribute('value'), 2);

        $_temp_url = explode('/', $this->session->url());
        $eventId = end($_temp_url);
        $groups = count($this->session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[selected="selected"]'));
        $this->assertEqual($groups, 2);

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'unselectAll')->click();

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();

        $warning = $this->session->switch_to_alert();
        $warning->accept();

        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Edit event successful!');

        $this->session->open($this->url.'events/edit/'.$eventId);
        $groups = count($this->session->elements(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="GroupGroup"] option[selected="selected"]'));
        $this->assertEqual($groups, 0);
    }

    public function testEmailRemindersForEvals()
    {
        // evaluations -> simple evaluations
        $this->session->open($this->url.'events/add/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Simple Evaluation with Reminders');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('w/ reminders');

        // fill in the dates
        $this->selectDayOnCalendar('EventDueDate', '12');
        $this->selectDayOnCalendar('EventReleaseDateBegin', '1', false);  // relaese at the beginning of current month so student can see it
        $this->selectDayOnCalendar('EventReleaseDateEnd', '28');
        $this->selectDayOnCalendar('EventResultReleaseDateBegin', '14');
        $this->selectDayOnCalendar('EventResultReleaseDateEnd', '28');

        $dueDate = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->attribute('value');
        $releaseEnd = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventReleaseDateEnd')->attribute('value');

        // set email reminder frequency to 5 days
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[value="5"]')->click();
        // select email template
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailTemplate"] option[value="6"]')->click();
        // select all groups
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'button[id="selectAll"]')->click();

        // submit form
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Add event successful!');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Simple Evaluation with Reminders')->click();
        $_temp_url = explode('/', $this->session->url());
        $eventId = end($_temp_url);

        // Alex Student completes the evaluations
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Simple Evaluation with Reminders')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'distr_button')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'submit0')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        // remove a student from a group
        $this->waitForLogoutLogin('root');
        $this->removeFromGroup();
        // send emails
        exec('cd '.dirname(__FILE__).'/../../../ && ../cake/console/cake send_emails');

        // check recipients list
        $this->session->open($this->url.'emailer');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'MECH 328 - iPeer Evaluation Reminder')->click();
        $ed = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Ed Student');
        $this->assertTrue(empty($ed)); // Ed is not listed because he was removed from the group
        $alex = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Alex Student');
        $this->assertTrue(empty($alex)); // Alex is not listed because he has submitted already
        // email content
        $content = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'emailContent')->text();
        // $expected = "Hello Name,\nA peer evaluation for MECH 328 is made available to you in iPeer, which".
        //     " has yet to be completed.\nName: Simple Evaluation with Reminders\nDue Date: ".date('l, F j, Y g:i a', strtotime($dueDate)).
        //     "\nClose Date: ".date('l, F j, Y g:i a', strtotime($releaseEnd))."\nYou can login here to complete ".
        //     "the peer evaluation before it closes.\nThank you";
        $expected = "Hello {{{FIRSTNAME}}},\n\n".
                    "A evaluation for {{{COURSENAME}}} is made available to you in iPeer, which has yet to be completed.\n\n".
                    "Name: {{{EVENTTITLE}}}\n".
                    "Due Date: {{{DUEDATE}}}\n".
                    "Close Date: {{{CLOSEDATE}}}\n\n".
                    "Thank You";
        $this->assertEqual($content, $expected);
        // $link = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'here')->attribute('href');
        // $this->assertEqual($link, $this->url);

        // frequency calculations
        $this->session->open($this->url.'events/edit/'.$eventId);
        $frequency = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[selected="selected"]');
        $this->assertEqual($frequency->attribute('value'), 5);

        // update frequency
        $dueDate = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->attribute('value');
        $scheduled = ceil((strtotime($dueDate) - time()) / (60*60*24*4)); // number of emails scheduled
        // set email reminder frequency to 4 days
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[value="4"]')->click();
        // submit form
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $this->session->open($this->url.'emailer');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'searchInputField')->sendKeys('MECH 328 - iPeer Evaluation Reminder');
        $emails = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'MECH 328 - iPeer Evaluation Reminder');
        $scheduled++;
        //includes the one that was sent and can't be deleted; test that all unsent emails are deleted
        $this->assertEqual(count($emails), $scheduled);

        // delete unsent emails - by disabling email reminders
        $this->session->open($this->url.'events/edit/'.$eventId);
        $frequency = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[selected="selected"]');
        $this->assertEqual($frequency->attribute('value'), 4); // the frequency was updated
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->session->open($this->url.'emailer');
        $emails = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'MECH 328 - iPeer Evaluation Reminder');
        $this->assertEqual(count($emails), 1);
        $emails[0]->click();
        // try deleting the sent email
        $this->session->open(str_replace('view', 'cancel', $this->session->url()));
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage"));
            }
        );
        $message = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage');
        $this->assertEqual($message->text(), 'Cannot cancel: Email is already sent.');

        $this->session->open($this->url.'events/delete/'.$eventId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $this->assignToGroup();
    }

    public function testEmailRemindersForSurveys()
    {
        $this->session->open($this->url.'events/add/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Survey with Email Reminders');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('Email Reminders are included');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventSurvey"] option[value="1"]')->click();

        // fill in the dates
        $this->selectDayOnCalendar('EventDueDate', '12');
        $this->selectDayOnCalendar('EventReleaseDateBegin', '1', false); # release at the begining of current month
        $this->selectDayOnCalendar('EventReleaseDateEnd', '13');

        // set email reminder frequency to 5 days
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[value="5"]')->click();
        // select email template
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailTemplate"] option[value="7"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $msg = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']")->text();
        $this->assertEqual($msg, 'Add event successful!');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Survey with Email Reminders')->click();
        $_temp_url = explode('/', $this->session->url());
        $eventId = end($_temp_url);

        // Alex Student completes the survey
        $this->waitForLogoutLogin('redshirt0002');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Survey with Email Reminders')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SurveyInput0ResponseId1')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'SurveyInput1ResponseId5')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        // send emails
        exec('cd '.dirname(__FILE__).'/../../../ && ../cake/console/cake send_emails'); // send emails

        // check recipients list
        $this->waitForLogoutLogin('root');
        $this->session->open($this->url.'emailer');

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'MECH 328 - iPeer Survey Reminder')->click();
        $alex = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Alex Student');
        $this->assertTrue(empty($alex)); // Alex is not listed because he has submitted already

        // frequency calculations
        $this->session->open($this->url.'events/edit/'.$eventId);
        $frequency = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[selected="selected"]');
        $this->assertEqual($frequency->attribute('value'), 5);

        // update frequency
        $dueDate = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDueDate')->attribute('value');
        $scheduled = ceil((strtotime($dueDate) - time()) / (60*60*24*4)); // number of emails scheduled
        // set email reminder frequency to 4 days
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[value="4"]')->click();
        // submit form
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
        $this->session->open($this->url.'emailer');
        $emails = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'MECH 328 - iPeer Survey Reminder');
        $scheduled++;
        //includes the one that was sent and can't be deleted; test that all unsent emails are deleted
        $this->assertEqual(count($emails), $scheduled);

        // delete unsent emails - by disabling email reminders
        $this->session->open($this->url.'events/edit/'.$eventId);
        $frequency = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[selected="selected"]');
        $this->assertEqual($frequency->attribute('value'), 4); // the frequency was updated
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->session->open($this->url.'emailer');
        $emails = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'MECH 328 - iPeer Survey Reminder');
        $this->assertEqual(count($emails), 1);
        $emails[0]->click();
        // try deleting the sent email
        $this->session->open(str_replace('view', 'cancel', $this->session->url()));
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::ID, "flashMessage"));
            }
        );
        $message = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'flashMessage');
        $this->assertEqual($message->text(), 'Cannot cancel: Email is already sent.');

        $this->session->open($this->url.'events/delete/'.$eventId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }

    public function testCornerCasesForFrequencies()
    {
        // one reminder - interval too big - shows up as 7 days
        $this->session->open($this->url.'events/add/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventTitle')->sendKeys('Survey with Email Reminders');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::ID, 'EventDescription')->sendKeys('Email Reminders are included');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEventTemplateTypeId"] option[value="3"]')->click();
        // fill in the dates - interval is 2 days
        $this->selectDayOnCalendar('EventDueDate', '12');
        $this->selectDayOnCalendar('EventReleaseDateBegin', '10');
        $this->selectDayOnCalendar('EventReleaseDateEnd', '13');
        // set email reminder frequency to 3 days
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[value="3"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Survey with Email Reminders')->click();
        $_temp_url = explode('/', $this->session->url());
        $eventId = end($_temp_url);
        $this->session->open($this->url.'events/edit/'.$eventId);
        $selected = $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option[selected="selected"]');
        $this->assertEqual($selected->attribute('value'), 7);
        // disable email reminders
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="EventEmailSchedule"] option')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[type="submit"]')->click();
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );

        $this->session->open($this->url.'events/delete/'.$eventId);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }

    public function removeFromGroup()
    {
        $this->session->open($this->url.'groups/edit/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="35"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="6"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="selected_groups"] option[value="7"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="<< Remove "]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Edit Group"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }

    public function assignToGroup()
    {
        $this->session->open($this->url.'groups/edit/1');
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'select[id="all_groups"] option[value="5"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Assign >>"]')->click();
        $this->session->elementWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, 'input[value="Edit Group"]')->click();
        $w = new PHPWebDriver_WebDriverWait($this->session);
        $w->until(
            function($session) {
                return count($session->elementsWithWait(PHPWebDriver_WebDriverBy::CSS_SELECTOR, "div[class='message good-message green']"));
            }
        );
    }
}
