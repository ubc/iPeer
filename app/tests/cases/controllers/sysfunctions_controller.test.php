<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in 
 * php.ini
 *
 * Details about ExtendedTestCase: 
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Sysfunctions');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
  var $autoRender = false;
}

class SysfunctionsControllerTest extends CakeTestCase {
  var $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 
                        'app.roles_user', 'app.event', 'app.event_template_type', 'app.rubrics_lom',
                        'app.group_event', 'app.evaluation_submission', 'app.rubrics_criteria_comment',
                        'app.survey_group_set', 'app.survey_group', 'app.rubrics_criteria',
                        'app.survey_group_member', 'app.question', 'app.rubric',
                        'app.response', 'app.survey_question', 'app.user_course',
                        'app.user_enrol', 'app.groups_member', 'app.survey', 
                        'app.personalize', 'app.sys_parameter', 'app.sys_function',
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
    $result = $this->testAction('/sysfunctions/index', array('connection' => 'test_suite', 'return' => 'vars'));
//var_dump($result);
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysFunction']['function_code'], 'code1');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysFunction']['function_name'], 'name1');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysFunction']['controller_name'], 'controller1');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysFunction']['url_link'], 'link1');    
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysFunction']['permission_type'], 'I');     
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysFunction']['record_status'], 'A');     
    
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysFunction']['function_code'], 'code2');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysFunction']['function_name'], 'name2');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysFunction']['controller_name'], 'controller2');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysFunction']['url_link'], 'link2');    
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysFunction']['permission_type'], 'I');     
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysFunction']['record_status'], 'A');     
    
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysFunction']['function_code'], 'code3');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysFunction']['function_name'], 'name3');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysFunction']['controller_name'], 'controller3');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysFunction']['url_link'], 'link3');    
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysFunction']['permission_type'], 'A');     
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysFunction']['record_status'], 'A');     
    
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysFunction']['function_code'], 'code4');
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysFunction']['function_name'], 'name4');
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysFunction']['controller_name'], 'controller4');
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysFunction']['url_link'], 'link4');    
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysFunction']['permission_type'], 'A');     
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysFunction']['record_status'], 'A');     
        
  }  
  

  function testView() {
    $result = $this->testAction('/sysfunctions/view/1', array('connection' => 'test_suite', 'return' => 'vars'));
//var_dump($result);
    $this->assertEqual($result['data']['SysFunction']['function_code'], 'code1');
    $this->assertEqual($result['data']['SysFunction']['function_name'], 'name1');
    $this->assertEqual($result['data']['SysFunction']['controller_name'], 'controller1');
    $this->assertEqual($result['data']['SysFunction']['url_link'], 'link1');
    $this->assertEqual($result['data']['SysFunction']['permission_type'], 'I');    
    $this->assertEqual($result['data']['SysFunction']['record_status'], 'A');
  }

  //TODO test saving
    function testEdit() {
    $result = $this->testAction('/sysfunctions/edit/1', array('connection' => 'test_suite', 'return' => 'vars'));
//var_dump($result);
    $this->assertEqual($result['data']['SysFunction']['function_code'], 'code1');
    $this->assertEqual($result['data']['SysFunction']['function_name'], 'name1');
    $this->assertEqual($result['data']['SysFunction']['controller_name'], 'controller1');
    $this->assertEqual($result['data']['SysFunction']['url_link'], 'link1');
    $this->assertEqual($result['data']['SysFunction']['permission_type'], 'I');    
    $this->assertEqual($result['data']['SysFunction']['record_status'], 'A');
 
    }
  
   //TODO test redirect
    function testDelete() {
//    $result = $this->testAction('/sysfunctions/delete/1', array('connection' => 'test_suite', 'return' => 'vars'));
//var_dump($result); 
      }

}