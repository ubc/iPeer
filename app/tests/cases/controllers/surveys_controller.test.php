<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in 
 * php.ini
 *
 * Details about ExtendedTestCase: 
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Survey');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
  var $autoRender = false;
}

class SurveyControllerTest extends CakeTestCase {
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type', 'app.rubrics_lom',
                        'app.group_event', 'app.evaluation_submission', 'app.rubrics_criteria_comment',
                        'app.survey_group_set', 'app.survey_group', 'app.rubrics_criteria',
                        'app.survey_group_member', 'app.question', 'app.rubric',
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
    $result = $this->testAction('/surveys/index', array('connection' => 'test_suite', 'return' => 'vars'));

    $this->assertEqual($result['paramsForList']['data']['entries'][0]['Survey']['name'], 'Math303 Survey');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['Course']['course'], 'Math303');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['Survey']['name'], 'Math304 Survey');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['Course']['course'], 'Math303');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['Survey']['name'], 'Empty Survey');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['Course']['course'], 'Math303');
  }  
  
  function testView() {
    $result = $this->testAction('/surveys/view/1', array('connection' => 'test_suite', 'return' => 'vars'));
    $this->assertEqual($result['data']['Survey']['name'], 'Math303 Survey');
    $this->assertEqual($result['data']['Survey']['due_date'], '2012-06-16 12:28:00');
    $this->assertEqual($result['data']['Survey']['release_date_begin'], '2011-06-16 12:28:07');
    $this->assertEqual($result['data']['Survey']['release_date_end'], '2013-06-16 12:28:07');    
    $this->assertEqual($result['data']['Course']['course'], 'Math303');
    $this->assertEqual($result['data']['Course']['title'], 'Stochastic Process');
  }   
  
  //TODO redirect
  function testAdd() { 
  }
  //TODO redirect
  function testEdit() {
//    $result = $this->testAction('/surveys/edit/1', array('connection' => 'test_suite', 'return' => 'contents'));
//    var_dump($result);
  }
  
  //TODO redirect
  function testCopy() {
  }
  //TODO redirect
  function testDelete() {
  }
  
  //TODO redirect
  function testReleaseSurvey() {    
    $result = $this->testAction('surveys/questionsSummary/1', array('connection' => 'test_suite', 'return' => 'vars'));
    $this->assertEqual($result['questions'][0]['Question']['prompt'], 'Did you learn a lot from this course ?');
    $this->assertEqual($result['questions'][0]['Response'][0]['response'], 'YES FOR Q1');    
    $this->assertEqual($result['questions'][0]['Response'][1]['response'], 'NO FOR Q1'); 
    $this->assertEqual($result['questions'][1]['Question']['prompt'], 'What was the hardest part ?');
    $this->assertEqual($result['questions'][1]['Response'][0]['response'], 'NO FOR Q2');    
    $this->assertEqual($result['questions'][2]['Question']['prompt'], 'Did u like the prof ?');
    $this->assertEqual($result['questions'][2]['Response'][0]['response'], 'YES FOR Q3');    
    
  }  
  
  //TODO redirect
  function testMoveQuestion() {
  }

  //TODO redirect
  function testAddQuestion() {
    //$result = $this->testAction('surveys/addQuestion/1', array('connection' => 'test_suite', 'return' => 'vars'));
   // var_dump($result);
  }  
  
    function testAddQuestion() {
    //$result = $this->testAction('surveys/editQuestion/1/1', array('connection' => 'test_suite', 'return' => 'vars'));
   // var_dump($result);
  }  
  
}