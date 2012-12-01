<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Sysparameters');

class SysparametersControllerTest extends CakeTestCase {
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

  // This code may be copied from SysParameter, need to be changed
  function testIndex() {
    $result = $this->testAction('/sysparameters/index', array('connection' => 'test_suite', 'return' => 'vars'));
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysParameter']['function_code'], 'code1');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysParameter']['function_name'], 'name1');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysParameter']['controller_name'], 'controller1');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysParameter']['url_link'], 'link1');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysParameter']['permission_type'], 'I');
    $this->assertEqual($result['paramsForList']['data']['entries'][0]['SysParameter']['record_status'], 'A');

    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysParameter']['function_code'], 'code2');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysParameter']['function_name'], 'name2');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysParameter']['controller_name'], 'controller2');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysParameter']['url_link'], 'link2');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysParameter']['permission_type'], 'I');
    $this->assertEqual($result['paramsForList']['data']['entries'][1]['SysParameter']['record_status'], 'A');

    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysParameter']['function_code'], 'code3');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysParameter']['function_name'], 'name3');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysParameter']['controller_name'], 'controller3');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysParameter']['url_link'], 'link3');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysParameter']['permission_type'], 'A');
    $this->assertEqual($result['paramsForList']['data']['entries'][2]['SysParameter']['record_status'], 'A');

    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysParameter']['function_code'], 'code4');
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysParameter']['function_name'], 'name4');
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysParameter']['controller_name'], 'controller4');
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysParameter']['url_link'], 'link4');
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysParameter']['permission_type'], 'A');
    $this->assertEqual($result['paramsForList']['data']['entries'][3]['SysParameter']['record_status'], 'A');

  }


  function testView() {
    $result = $this->testAction('/sysparameters/view/1', array('connection' => 'test_suite', 'return' => 'vars'));
//var_dump($result);
    $this->assertEqual($result['data']['SysParameter']['function_code'], 'code1');
    $this->assertEqual($result['data']['SysParameter']['function_name'], 'name1');
    $this->assertEqual($result['data']['SysParameter']['controller_name'], 'controller1');
    $this->assertEqual($result['data']['SysParameter']['url_link'], 'link1');
    $this->assertEqual($result['data']['SysParameter']['permission_type'], 'I');
    $this->assertEqual($result['data']['SysParameter']['record_status'], 'A');
  }

  //TODO test saving
    function testEdit() {
    $result = $this->testAction('/sysparameters/edit/1', array('connection' => 'test_suite', 'return' => 'vars'));
//var_dump($result);
    $this->assertEqual($result['data']['SysParameter']['function_code'], 'code1');
    $this->assertEqual($result['data']['SysParameter']['function_name'], 'name1');
    $this->assertEqual($result['data']['SysParameter']['controller_name'], 'controller1');
    $this->assertEqual($result['data']['SysParameter']['url_link'], 'link1');
    $this->assertEqual($result['data']['SysParameter']['permission_type'], 'I');
    $this->assertEqual($result['data']['SysParameter']['record_status'], 'A');

    }

   //TODO test redirect
    function testDelete() {
//    $result = $this->testAction('/sysparameters/delete/1', array('connection' => 'test_suite', 'return' => 'vars'));
//var_dump($result);
      }

}
