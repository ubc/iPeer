<?php
App::import('Model', 'EmailMerge');
App::import('Component', 'Auth');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class EmailMergeTestCase extends CakeTestCase {
  var $name = 'EmailMerge';
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
    $this->EmailMerge = ClassRegistry::init('EmailMerge');
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
    $this->assertTrue(is_a($this->EmailMerge, 'EmailMerge'));
  }

  function testGetMergeList() {
    $mergeList = $this->EmailMerge->getMergeList();
    $expectedResults = array('{{{USERNAME}}}' => 'Username', '{{{FIRSTNAME}}}' => 'First Name', '{{{LASTNAME}}}' => 'Last Name', '{{{EMAIL}}}' => 'Email Address');
    ##Run tests
    $this->assertEqual($mergeList, $expectedResults);
  }

  function testGetFieldNameByValue(){
    $this->EmailMerge= & ClassRegistry::init('EmailMerge');
    $empty=null;

    //Test on valid input
    ##Run tests
    $field = $this->EmailMerge->getFieldAndTableNameByValue('{{{USERNAME}}}');
    $this->assertEqual($field, array('table_name'=>'User','field_name'=>'username'));

    //Testing invalid inputs; all tests should return NULL
    ##invalid input
    $invalid = $this->EmailMerge->getFieldAndTableNameByValue('<<USERNAME>>');
    $this->assertEqual($invalid, $empty);

    ##null input
    $field = $this->EmailMerge->getFieldAndTableNameByValue(null);
    $this->assertEqual($field, $empty);
  }
}
?>