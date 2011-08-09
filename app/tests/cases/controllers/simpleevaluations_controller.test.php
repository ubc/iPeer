<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in 
 * php.ini
 *
 * Details about ExtendedTestCase: 
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Simpleevaluations');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
  var $autoRender = false;
}

class SimpleevaluationsControllerTest extends CakeTestCase {
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type', 'app.rubrics_lom',
                        'app.group_event', 'app.evaluation_submission', 'app.rubrics_criteria_comment',
                        'app.survey_group_set', 'app.survey_group', 'app.rubrics_criteria',
                        'app.survey_group_member', 'app.question', 'app.rubric',
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.personalize', 'app.sys_parameter', 'app.simple_evaluation'
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
    $result = $this->testAction('/simpleevaluations/index', array('connection' => 'test_suite', 'return' => 'vars'));
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SimpleEvaluation']['name'], 'SimpleE1');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SimpleEvaluation']['description'], 'descr');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SimpleEvaluation']['point_per_member'], 5);
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SimpleEvaluation']['creator'], 'steveslade');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SimpleEvaluation']['name'], 'SimpleE2');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SimpleEvaluation']['description'], 'descr1');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SimpleEvaluation']['point_per_member'], 10);   
  }

  function testView() {
    $result = $this->testAction('/simpleevaluations/view/1', array('connection' => 'test_suite', 'return' => 'vars'));
    $this->assertEqual($result['data']['SimpleEvaluation']['name'], 'SimpleE1');
    $this->assertEqual($result['data']['SimpleEvaluation']['description'], 'descr');
    $this->assertEqual($result['data']['SimpleEvaluation']['point_per_member'], 5);
    $this->assertEqual($result['data']['SimpleEvaluation']['creator'], 'steveslade');    
  }

  //TODO test redirect
  function testAdd(){
  }
  
//TODO test saving, redirect  
  function testEdit() {
//    $result = $this->testAction('/simpleevaluations/edit/1', array('connection' => 'test_suite', 'return' => 'vars'));

     }

//TODO test redirect
  function testCopy(){
  }
  
//TODO test redirect
  function testDelete(){
  }  
  
}