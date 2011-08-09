<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in 
 * php.ini
 *
 * Details about ExtendedTestCase: 
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Mixevals');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
  var $autoRender = false;
}

class MixevalsControllerTest extends CakeTestCase {
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type', 'app.rubrics_lom',
                        'app.group_event', 'app.evaluation_submission', 'app.rubrics_criteria_comment',
                        'app.survey_group_set', 'app.survey_group', 'app.rubrics_criteria',
                        'app.survey_group_member', 'app.question', 'app.rubric',
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.personalize', 'app.sys_parameter', 'app.mixeval', 
                        'app.mixevals_question', 'app.mixevals_question_desc'
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
    $result = $this->testAction('/mixevals/index', array('connection' => 'test_suite', 'return' => 'vars'));
 //   foreach($result as $key=>$val){
  //  var_dump($key);}   
 //  var_dump($result["paramsForList"]);
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['name'], 'Rubric');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['creator'], 'steveslade');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['total_question'], 3);        
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['total_marks'], 45);
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['!Custom']['inUse'], 'No');
    
  }
  
 //JavaScript 
  
  function testView() {
  //  $result = $this->testAction('/mixevals/view/1', array('connection' => 'test_suite', 'return' => 'vars'));
  //  var_dump($result);
  }
  
}