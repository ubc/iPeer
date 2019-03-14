<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Evaltools');

class FakeController extends Controller {
  var $name = 'FakeController';
  var $components = array('Auth');
  var $uses = null;
  var $params = array('action' => 'test');
  var $autoRender = false;
}

class EvaltoolsControllerTest extends CakeTestCase {
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.personalize', 'app.penalty', 'app.evaluation_simple',
        'app.faculty', 'app.user_tutor', 'app.course_department',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.user_faculty', 'app.department', 'app.sys_parameter',
        'app.oauth_token', 'app.rubric', 'app.rubrics_criteria',
        'app.rubrics_criteria_comment', 'app.rubrics_lom',
        'app.simple_evaluation', 'app.survey_input', 'app.mixeval_question',
        'app.mixeval_question_desc', 'app.mixeval'
    );

    function startCase() {
        echo "Start Evaltool controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
    }

    function endCase() {
    }

    function startTest($method) {
        $controller = new FakeController();
        $controller->constructClasses();
        $controller->startupProcess();
        $controller->Component->startup($controller);
        $controller->Auth->startup($controller);
        $admin = array('User' => array('username' => 'Admin',
            'password' => 'passwordA'));
        $controller->Auth->login($admin);
    }

    function endTest($method) {
    }

    function testIndex() {
        //$result = $this->testAction('/evaltools/index', array('connection' => 'test_suite', 'return' => 'contents'));
/*    $expected = array(
      array('Course' => array('id' => 3, 'homepage' => null, 'course' => 'Math100', 'title' => 'Math',
                               'creator_id' => 0, 'record_status' => '', 'creator' => null),
            'Instructor' => array('id' => 2),
            'CreatorId' => array(),
            'UpdaterId' => array()),
      array('Course' => array('id' => 1, 'course' => 'Math303', 'title' => 'Stochastic Process',
                              'homepage' => null, 'creator_id' => 0, 'record_status' => '', 'creator' => null),
            'Instructor' => array('id' => 1),
            'CreatorId' => array(),
            'UpdaterId' => array()),
      array('Course' => array('id' => 2, 'course' => 'Math321', 'title' => 'Analysis II',
                              'homepage' => null, 'creator_id' => 0, 'record_status' => '', 'creator' => null),
            'Instructor' => array('id' => 1),
            'CreatorId' => array(),
            'UpdaterId' => array()),
      );
$this->assertEqual($result['paramsForList']['data']['entries'], $expected);*/
    }

/*  function testView() {
    $result1 = $this->testAction('/courses/view/1', array('connection' => 'test_suite', 'return' => 'vars'));
    $result2 = $this->testAction('/courses/view/2', array('connection' => 'test_suite', 'return' => 'vars'));
    $expect1 = array('id' => 1, 'course' => 'Math303', 'title' => 'Stochastic Process', 'homepage' => null,
                     'self_enroll' => null, 'password' => null, 'record_status' => null,
                     'creator_id' => 0, 'created' => "0000-00-00 00:00:00", 'updater_id' => null,
                     'modified' => null, 'instructor_id' => 0,
             'creator'=> null, 'updater'=> null, 'student_count'=> 4	 );
    $expect2 = array('id' => 2, 'course' => 'Math321', 'title' => 'Analysis II',
                     'homepage' => null, 'self_enroll' => null, 'password' => null,'homepage' => null,
                     'self_enroll' => null, 'password' => null, 'record_status' => null,
                     'creator_id' => 0, 'created' => "0000-00-00 00:00:00", 'updater_id' => null,
                     'modified' => null, 'instructor_id' => 0,
            'creator'=> null, 'updater'=> null, 'student_count'=> 1);

    $this->assertEqual($result1['data']['Course'], $expect1);
    $this->assertEqual($result2['data']['Course'], $expect2);

  }

  function testHome() {
    $this->Course = ClassRegistry::init('Course');
    $result = $this->testAction('/courses/home/1', array('connection' => 'test_suite', 'return' => 'vars'));
    $result1 = $this->testAction('/courses/home/2', array('connection' => 'test_suite', 'return' => 'vars'));

    $this->assertEqual($result['studentCount'], 4);
    $this->assertEqual($result['course_id'], 1);
    $this->assertEqual($result['data']['Course']['id'], 1);
    $this->assertEqual($result['data']['Course']['course'], 'Math303');
    $this->assertEqual($result['data']['Course']['title'], 'Stochastic Process');
    $this->assertEqual($result['data']['Group'][0]['group_name'], 'group1');

    $this->assertEqual($result1['studentCount'], 1);
    $this->assertEqual($result1['course_id'], 2);
    $this->assertEqual($result1['data']['Course']['id'], 2);
    $this->assertEqual($result1['data']['Course']['course'], 'Math321');
    $this->assertEqual($result1['data']['Course']['title'], 'Analysis II');
  }


//TODO (redirect)

  function testAdd() {
    $this->Course = ClassRegistry::init('Course');
    $data = array('course' => 10, 'title' => 'Some Course');
    //$result = $this->testAction('/courses/add', array('connection' => 'test_suite', 'data' => $data, 'return' => 'vars'));
  //  $search = $this->Course->find('all', array('conditions' => array ('course' => 10)));
    //var_dump($search);
    //   $result1 = $this->testAction('/courses/home/2', array('connection' => 'test_suite', 'return' => 'vars'));

  }

//TODO redirect
 function testEdit () {
 }

 function testDelete() {
 }

 function testAddInstructor() {
    $this->Course = ClassRegistry::init('Course');
    $data = array('instructor_id'=> 2, 'course_id' => 1);
    //$result = $this->testAction('/courses/addInstructor', array('connection' => 'test_suite', 'data' => $data, 'return' => 'vars'));
 }

 function testDeleteInstructor() {
   $data = array('instructor_id'=> 2, 'course_id' => 1);
 // $result = $this->testAction('/courses/deleteInstructor', array('connection' => 'test_suite', 'data' => $data, 'return' => 'vars'));
 //  $search = $this->UserCourse->find('all', array( 'conditions' => array('user_id' => 1)));
 }

 function testCheckDuplicateName(){
   $data['Course']['course'] = 'Course';
  // $result = $this->testAction('/courses/deleteInstructor', array('connection' => 'test_suite', 'data' => $data, 'return' => 'vars'));


 }

 //TODO uses Auth
 function testUpdate(){
 }
 */
}
