<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in 
 * php.ini
 *
 * Details about ExtendedTestCase: 
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Events');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
  var $autoRender = false;
}

class EventsControllerTest extends CakeTestCase {
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',   'app.mixeval', 'app.mixevals_question',
                        'app.roles_user', 'app.event', 'app.event_template_type',  'app.rubrics_lom', 'app.mixevals_question_desc',
                        'app.group_event', 'app.evaluation_submission',  'app.rubrics_criteria_comment',
                        'app.survey_group_set', 'app.survey_group', 'app.rubric', 'app.rubrics_criteria',
                        'app.survey_group_member', 'app.question', 'app.simple_evaluation', 'app.email_template',
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.personalize', 'app.sys_parameter',
                       );

  function startCase() {
    echo '<h1>Starting Test Case</h1>';
  }

  function endCase() {      
     echo '<h1>Ending Test Case</h1>';    
  } 

  function startTest() {
    $controller = new FakeController();
    $controller->constructClasses();
    $controller->startupProcess();
    $controller->Component->startup($controller);
    $controller->Auth->startup($controller);
    $admin = array('User' => array('username' => 'Admin',
                                   'password' => 'passwordA'));
    $controller->Auth->login($admin);
  }

  function endTest() {
    echo '<hr />';
  }

  function testIndex() {
    $result = $this->testAction('/events/index', array('connection' => 'test_suite', 'return' => 'vars'));
 //   var_dump($result);

    $this->assertEqual($result["paramsForList"]['data']['entries'][0]['Event']['Title'], 'Event7');
    $this->assertEqual($result["paramsForList"]['data']['entries'][0]['Event']['event_template_type_id'], 4);    
    $this->assertEqual($result["paramsForList"]['data']['entries'][0]['Course']['course'], 'Math100');
    $this->assertEqual($result["paramsForList"]['data']['entries'][0]['!Custom']['isReleased'], 'Already Closed');

    $this->assertEqual($result["paramsForList"]['data']['entries'][1]['Event']['Title'], 'Event1');
    $this->assertEqual($result["paramsForList"]['data']['entries'][1]['Event']['event_template_type_id'], 2);    
    $this->assertEqual($result["paramsForList"]['data']['entries'][1]['Course']['course'], 'Math303');
    $this->assertEqual($result["paramsForList"]['data']['entries'][1]['!Custom']['isReleased'], 'Already Closed');
    
    $this->assertEqual($result["paramsForList"]['data']['entries'][2]['Event']['Title'], 'Event2');
    $this->assertEqual($result["paramsForList"]['data']['entries'][2]['Event']['event_template_type_id'], 1);    
    $this->assertEqual($result["paramsForList"]['data']['entries'][2]['Course']['course'], 'Math303');
    $this->assertEqual($result["paramsForList"]['data']['entries'][2]['!Custom']['isReleased'], 'Already Closed');
    
  }

  //TODO redirect
  
  function testGoToClassList() {
//    $result = $this->testAction('/events/goToClassList/1', array('connection' => 'test_suite', 'return' => 'contents'));
  
  }
  function testView() {
    $result = $this->testAction('/events/view/1', array('connection' => 'test_suite', 'return' => 'vars'));
   // var_dump($result);
    $this->assertEqual($result['event']['Event']['title'], 'Event1');
    $this->assertEqual($result['event']['Event']['event_template_type_id'], 2);
    $this->assertEqual($result['event']['Group'][0]['group_name'], 'group1');    
    $this->assertEqual($result['event']['Group'][0]['Member'][0]['first_name'], 'TestName'); 
    $this->assertEqual($result['event']['Group'][0]['Member'][0]['last_name'], 'TestLastname'); 
  
   $result = $this->testAction('/events/view/2', array('connection' => 'test_suite', 'return' => 'vars'));
   // var_dump($result);
    $this->assertEqual($result['event']['Event']['title'], 'Event2');
    $this->assertEqual($result['event']['Event']['event_template_type_id'], 1);
    $this->assertEqual($result['event']['Group'][0]['group_name'], 'group1');    
    $this->assertEqual($result['event']['Group'][0]['Member'][0]['first_name'], 'TestName'); 
    $this->assertEqual($result['event']['Group'][0]['Member'][0]['last_name'], 'TestLastname'); 
  }

  //TODO redirect
  function testAdd(){
  }
  
  function testEdit() {
   $result = $this->testAction('/events/edit/1', array('connection' => 'test_suite', 'return' => 'vars'));
   
   $this->assertEqual($result['event']['Event']['title'], 'Event1');
    $this->assertEqual($result['event']['Event']['event_template_type_id'], 2);
    $this->assertEqual($result['event']['Group'][0]['group_name'], 'group1');    
    $this->assertEqual($result['event']['Group'][0]['Member'][0]['first_name'], 'TestName'); 
    $this->assertEqual($result['event']['Group'][0]['Member'][0]['last_name'], 'TestLastname'); 
  
 //TODO test saving the data (redirect)
    
  }
  
  function testDelete() {
  }

  //TODO uses rdAuth
  function testSearch() {
  } 
  
  function testViewGroups() {
    $result = $this->testAction('/events/viewGroups/1', array('connection' => 'test_suite', 'return' => 'vars'));
      
    $this->assertEqual($result['assignedGroups'][0]['group_num'], 1);
    $this->assertEqual($result['assignedGroups'][0]['group_name'], 'group1');
    $this->assertEqual($result['assignedGroups'][0]['member_count'], 2); 
    $this->assertEqual($result['assignedGroups'][0]['Member'][0]['first_name'], 'TestName'); 
    $this->assertEqual($result['assignedGroups'][0]['Member'][0]['last_name'], 'TestLastname');       
    $this->assertEqual($result['assignedGroups'][0]['Member'][1]['first_name'], 'name'); 
    $this->assertEqual($result['assignedGroups'][0]['Member'][1]['last_name'], 'lastname');       
  
    $this->assertEqual($result['assignedGroups'][1]['group_num'], 2);
    $this->assertEqual($result['assignedGroups'][1]['group_name'], 'group2');
    $this->assertEqual($result['assignedGroups'][1]['member_count'], 2); 
    $this->assertEqual($result['assignedGroups'][1]['Member'][0]['first_name'], 'TestName'); 
    $this->assertEqual($result['assignedGroups'][1]['Member'][0]['last_name'], 'TestLastname');       
    $this->assertEqual($result['assignedGroups'][1]['Member'][1]['first_name'], 'name'); 
    $this->assertEqual($result['assignedGroups'][1]['Member'][1]['last_name'], 'lastname');   
  }
  
}