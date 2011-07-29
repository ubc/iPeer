<?php
App::import('Model', 'EmailSchedule');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EmailScheduleTestCase extends CakeTestCase {
  var $name = 'EmailSchedule';
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question',
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey',
                        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
                        'app.email_merge', 'app.email_schedule', 'app.email_template'
                       );
  var $Course = null;

  function startCase() {
    $this->EmailSchedule = ClassRegistry::init('EmailSchedule');
    $admin = array('User' => array('username' => 'root',
                                   'password' => 'ipeer'));
    $this->controller = new FakeController();
    $this->controller->constructClasses();
    $this->controller->startupProcess();
    $this->controller->Component->startup($this->controller);
    $this->controller->Auth->startup($this->controller);
    ClassRegistry::addObject('view', new View($this->Controller));
    ClassRegistry::addObject('auth_component', $this->controller->Auth);

    $this->controller->Auth->login($admin);
  }

  function endCase() {
    $this->controller->Component->shutdown($this->controller);
    $this->controller->shutdownProcess();
  }

  //Run before EVERY test.
  function startTest($method) {
    // extra setup stuff here
  }

  function endTest($method) {
  }

  function testCourseInstance() {
    $this->assertTrue(is_a($this->EmailSchedule, 'EmailSchedule'));
  }

  function testGetEmailsToSend(){
    ##Run tests
    $expected[0]['EmailSchedule'] = array('id' => 1, 'subject' => 'To send', 'content' => 'This is Test Email', 'date' => '2011-07-10 00:00:00', 'from' => '2', 'to' => '2;3', 'course_id' => null, 'event_id' => null, 'grp_id' => null, 'sent' => 0, 'creator_id' => 1, 'created' => '2011-06-10 00:00:00', 'creator' => null );
    $emails = $this->EmailSchedule->getEmailsToSend();
    $this->assertEqual($emails, $expected);
  }

  function testGetCreatorId(){
    //Test on valid input
    ##Run tests
    $creator_id = $this->EmailSchedule->getCreatorId('1');
    $this->assertEqual($creator_id, '1');

    //Test on invalid input
    $creator_id = $this->EmailSchedule->getCreatorId('10');
    $this->assertEqual($creator_id, null);

    //null input
    $creator_id = $this->EmailSchedule->getCreatorId(null);
    $this->assertEqual($creator_id, null);
  }

  function testGetSent(){
    //Test on valid input
    ##Run tests
    $sent = $this->EmailSchedule->getSent('2');
    $this->assertEqual($sent, '1');

    //Test on invalid input
    $sent = $this->EmailSchedule->getSent('10');
    $this->assertEqual($sent, null);

    //null input
    $sent = $this->EmailSchedule->getSent(null);
    $this->assertEqual($sent, null);
  }
}
?>