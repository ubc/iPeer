<?php
App::import('Model', 'EmailTemplate');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EmailTemplateTestCase extends CakeTestCase {
  var $name = 'EmailTemplate';
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
    $this->EmailTemplate = ClassRegistry::init('EmailTemplate');
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
    $this->assertTrue(is_a($this->EmailTemplate, 'EmailTemplate'));
  }

  function testGetMyEmailTemplate(){
    //Test on valid input w/ find all
    ##Run tests
    $expected[0]['EmailTemplate'] = array('id' => 1, 'name' => 'Test Template for 1', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '0', 'creator_id' => '1', 'created' => '2011-06-10 00:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 00:00:00', 'creator'=>null, 'updater'=>null );
    $templates = $this->EmailTemplate->getMyEmailTemplate('1', 'all');
    $this->assertEqual($templates, $expected);

    //Test on valid input w/ find list
    ##Run tests
    $templates = $this->EmailTemplate->getMyEmailTemplate('1', 'list');
    $this->assertEqual($templates, array('1' => 'Test Template for 1'));

    //Test on null input
    ##Run tests
    $templates = $this->EmailTemplate->getMyEmailTemplate(null);
    $this->assertEqual($templates, null);
  }

  function testGetPermittedEmailTemplate(){
        //Test on valid input w/ find all
    ##Run tests
    $expected = array();
    $expected[0]['EmailTemplate'] = array('id' => 1, 'name' => 'Test Template for 1', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '0', 'creator_id' => '1', 'created' => '2011-06-10 00:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 00:00:00', 'creator'=>null, 'updater'=>null );
    $expected[1]['EmailTemplate'] = array('id' => 3, 'name' => 'Test Template public', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '1', 'creator_id' => '2', 'created' => '2011-06-10 00:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 00:00:00', 'creator'=>"TaehyunYou", 'updater'=>null );
    $templates = $this->EmailTemplate->getPermittedEmailTemplate('1', 'all');
    $this->assertEqual($templates, $expected);

    //Test on valid input w/ find list
    ##Run tests
    $templates = $this->EmailTemplate->getPermittedEmailTemplate('1', 'list');
    $this->assertEqual($templates, array('1' => 'Test Template for 1', '3' => 'Test Template public'));

    //Test on null input
    ##Run tests
    $expected = array();
    $expected[0]['EmailTemplate'] = array('id' => 3, 'name' => 'Test Template public', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '1', 'creator_id' => '2', 'created' => '2011-06-10 00:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 00:00:00', 'creator'=>"TaehyunYou", 'updater'=>null );
    $templates = $this->EmailTemplate->getPermittedEmailTemplate(null);
    $this->assertEqual($templates, $expected);
  }

  function testGetCreatorId(){
    //Test on valid input
    ##Run tests
    $creator_id = $this->EmailTemplate->getCreatorId('2');
    $this->assertEqual($creator_id, '2');

    //Test on invalid input
    $creator_id = $this->EmailTemplate->getCreatorId('10');
    $this->assertEqual($creator_id, null);

    //null input
    $creator_id = $this->EmailTemplate->getCreatorId(null);
    $this->assertEqual($creator_id, null);
  }
}
?>