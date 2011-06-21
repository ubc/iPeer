<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in 
 * php.ini
 *
 * Details about ExtendedTestCase: 
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Courses');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
}

class CoursesControllerTest extends CakeTestCase {
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
    $result = $this->testAction('/courses/index', array('connection' => 'test_suite', 'return' => 'vars'));
    $expected = array(
      array('Course' => array('id' => 3, 'course' => 'Math100', 'title' => 'Math', 
                              'homepage' => null, 'creator_id' => 0, 'record_status' => '', 'creator' => null),
            'Instructor' => array('id' => 8)),
      array('Course' => array('id' => 1, 'course' => 'Math303', 'title' => 'Stochastic Process',
                              'homepage' => null, 'creator_id' => 0, 'record_status' => '', 'creator' => null),
            'Instructor' => array('id' => 1)),
      array('Course' => array('id' => 2, 'course' => 'Math321', 'title' => 'Analysis II',
                              'homepage' => null, 'creator_id' => 0, 'record_status' => '', 'creator' => null),
            'Instructor' => array('id' => 1)),
      );
    $this->assertEqual($result['paramsForList']['data']['entries'], $expected);
  }
}

