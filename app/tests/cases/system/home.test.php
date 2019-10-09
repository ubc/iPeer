<?php
App::import('Lib', 'system_base');

class homeTestCase extends SystemBaseTestCase
{
    public function startCase()
    {
        parent::startCase();
        echo "Start HomeTest system test.\n";
        $this->getSession()->open($this->url);

        $login = PageFactory::initElements($this->session, 'Login');
        $home = $login->login('root', 'ipeeripeer');
    }

    public function testSuperAdmin()
    {
        $headers = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h2');
        $this->assertEqual($headers[0]->text(), 'My Courses');
        $this->assertEqual($headers[1]->text(), 'Inactive Courses');

        $courses = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h3');
        $this->assertEqual(count($courses), 4);
        $this->assertEqual($courses[0]->text(), 'CPSC 404 - Advanced Software Engineering');
        $this->assertEqual($courses[1]->text(), 'APSC 201 - Technical Communication');
        $this->assertEqual($courses[2]->text(), 'MECH 328 - Mechanical Engineering Design Project');
        $this->assertEqual($courses[3]->text(), 'CPSC 101 - Connecting with Computer Science');

        // navigation
        $home = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Home');
        $this->assertEqual(count($home), 1);
        $courses = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses');
        $this->assertEqual(count($courses), 1);
        $users = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Users');
        $this->assertEqual(count($users), 1);
        $evaluation = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Evaluation');
        $this->assertEqual(count($evaluation), 1);
        $admin = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Admin');
        $this->assertEqual(count($admin), 1);
    }

    public function testAdmin()
    {
        $this->waitForLogoutLogin('admin2');
        $headers = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h2');
        $this->assertEqual(count($headers), 2);
        $this->assertEqual($headers[0]->text(), 'My Courses');
        $this->assertEqual($headers[1]->text(), 'Inactive Courses');
        $courses = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h3');
        $this->assertEqual(count($courses), 2);
        $this->assertEqual($courses[0]->text(), 'CPSC 404 - Advanced Software Engineering');
        $this->assertEqual($courses[1]->text(), 'CPSC 101 - Connecting with Computer Science');

        // navigation
        $home = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Home');
        $this->assertEqual(count($home), 1);
        $courses = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses');
        $this->assertEqual(count($courses), 1);
        $users = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Users');
        $this->assertEqual(count($users), 1);
        $evaluation = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Evaluation');
        $this->assertEqual(count($evaluation), 1);
        $admin = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Admin');
        $this->assertEqual(count($admin), 0);
    }

    public function testInstructor()
    {
        $this->waitForLogoutLogin('instructor2');
        $headers = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h2');
        $this->assertEqual(count($headers), 1);
        $this->assertEqual($headers[0]->text(), 'My Courses');
        $courses = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h3');
        $this->assertEqual(count($courses), 1);
        $this->assertEqual($courses[0]->text(), 'APSC 201 - Technical Communication');

        // navigation
        $home = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Home');
        $this->assertEqual(count($home), 1);
        $courses = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses');
        $this->assertEqual(count($courses), 1);
        $users = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Users');
        $this->assertEqual(count($users), 0);
        $evaluation = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Evaluation');
        $this->assertEqual(count($evaluation), 1);
        $admin = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Admin');
        $this->assertEqual(count($admin), 0);
    }

    public function testTutor()
    {
        $this->waitForLogoutLogin('tutor2');
        $header = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h2');
        $this->assertEqual($header[0]->text(), 'Peer Evaluations');
        $this->assertEqual($header[1]->text(), 'Surveys');

        $titles = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h3');
        $this->assertEqual($titles[0]->text(), 'Due');
        $this->assertEqual($titles[1]->text(), 'Submitted');
        $this->assertEqual($titles[2]->text(), 'Due');
        $this->assertEqual($titles[3]->text(), 'Submitted');

        // navigation
        $home = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Home');
        $this->assertEqual(count($home), 1);
        $courses = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses');
        $this->assertEqual(count($courses), 0);
        $users = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Users');
        $this->assertEqual(count($users), 0);
        $evaluation = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Evaluation');
        $this->assertEqual(count($evaluation), 0);
        $admin = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Admin');
        $this->assertEqual(count($admin), 0);
    }

    public function testStudent()
    {
        $this->waitForLogoutLogin('redshirt0001');
        $header = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h2');
        $this->assertEqual($header[0]->text(), 'Peer Evaluations');
        $this->assertEqual($header[1]->text(), 'Surveys');

        $titles = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h3');
        $this->assertEqual($titles[0]->text(), 'Due');
        $this->assertEqual($titles[1]->text(), 'Submitted');
        $this->assertEqual($titles[2]->text(), 'Expired With No Submission');
        $this->assertEqual($titles[3]->text(), 'Due');
        $this->assertEqual($titles[4]->text(), 'Submitted');

        // navigation
        $home = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Home');
        $this->assertEqual(count($home), 1);
        $courses = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Courses');
        $this->assertEqual(count($courses), 0);
        $users = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Users');
        $this->assertEqual(count($users), 0);
        $evaluation = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Evaluation');
        $this->assertEqual(count($evaluation), 0);
        $admin = $this->session->elements(PHPWebDriver_WebDriverBy::LINK_TEXT, 'Admin');
        $this->assertEqual(count($admin), 0);
    }

    public function studentTutorHome()
    {
        $header = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h2');
        $this->assertEqual($header[0]->text(), 'Peer Evaluations');
        $this->assertEqual($header[1]->text(), 'Surveys');

        $titles = $this->session->elementsWithWait(PHPWebDriver_WebDriverBy::TAG_NAME, 'h3');
        $this->assertEqual($titles[0]->text(), 'Due');
        $this->assertEqual($titles[1]->text(), 'Submitted');
        $this->assertEqual($titles[2]->text(), 'Expired With No Submission');
        $this->assertEqual($titles[3]->text(), 'Due');
        $this->assertEqual($titles[4]->text(), 'Submitted');
    }
}
