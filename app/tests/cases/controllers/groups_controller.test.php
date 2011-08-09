<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in 
 * php.ini
 *
 * Details about ExtendedTestCase: 
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Groups');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
  var $autoRender = false;
}

class GroupsControllerTest extends CakeTestCase {
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type',
                        'app.group_event', 'app.evaluation_submission',
                        'app.survey_group_set', 'app.survey_group',
                        'app.survey_group_member', 'app.question', 
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
    $result = $this->testAction('/groups/index', array('connection' => 'test_suite', 'return' => 'vars'));
    
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['Course']['course'], 'Math303');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['Group']['group_num'], '1');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['Group']['member_count'], '2');        
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['Group']['group_num'], '2');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['Group']['member_count'], '2'); 
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['Group']['group_num'], '3');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['Group']['member_count'], '0');     
  }
  
  function testView() {
    $result = $this->testAction('/groups/view/1', array('connection' => 'test_suite', 'return' => 'vars'));
    $this->assertEqual($result['data']['Group']['group_num'], 1);
    $this->assertEqual($result['data']['Group']['group_name'], 'group1');
    $this->assertEqual($result['data']['Member'][0]['first_name'], 'TestName');    
    $this->assertEqual($result['data']['Member'][0]['last_name'], 'TestLastname');    
    $this->assertEqual($result['data']['Member'][1]['first_name'], 'name');    
    $this->assertEqual($result['data']['Member'][1]['last_name'], 'lastname');
  }
    
  function testAdd() {  
    $result = $this->testAction('/groups/add/1', array('connection' => 'test_suite', 'return' => 'vars'));
    $this->assertEqual($result['group_num'], 5);
    $this->assertEqual($result['course_id'], 1);
    $this->assertEqual($result['user_data'][4], '100 - name lastname*');
    $this->assertEqual($result['user_data'][3], '123 - TestName TestLastname*');
    
//TODO test saving, redirect    
  }

  function testEdit() {
    $result = $this->testAction('/groups/edit/4', array('connection' => 'test_suite', 'return' => 'vars'));
    $this->assertEqual($result['group_num'], 4);
    $this->assertEqual($result['members'][4], '100 - name lastname');
    $this->assertEqual($result['user_data'][3], '123 - TestName TestLastname'); 

//TODO test saving, redirect
  }
  
//TODO redirect
  function testDelete() {  
  }
  
//Not used anywhere  
  function testGetQueryAttribute(){
  }
}