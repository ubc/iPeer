<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Lib', 'ExtendedAuthTestCase');
App::import('Controller', 'Courses');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('CoursesController',
    'MockCoursesController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

/**
 * CoursesControllerTest Course controller test case
 *
 * @uses ExtendedTestCase
 * @package Tests
 * @author  Pan Luo <pan.luo@ubc.ca>
 */
class CoursesControllerTest extends ExtendedAuthTestCase
{
    private $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.personalize',
    );

    var $testMethods = array('testIndex', 'testView');

    private $fixtureIndex = array(
        array(
            'Course' => array(
                'id' => 1, 'homepage' => 'http://www.mech.ubc.ca', 'course' => 'MECH 328', 'title' => 'Mechanical Engineering Design Project',
                'creator_id' => 1, 'record_status' => 'A', 'creator' => 'Super Admin'),
        ),
        array(
            'Course' => array(
                'id' => 2, 'homepage' => 'http://www.apsc.ubc.ca', 'course' => 'APSC 201', 'title' => 'Technical Communication',
                'creator_id' => 1, 'record_status' => 'A', 'creator' => 'Super Admin'),
        ),
        array(
            'Course' => array(
                'id' => 3, 'homepage' => 'http://www.ugrad.cs.ubc.ca/~cs101/', 'course' => 'CPSC 101', 'title' => 'Connecting with Computer Science',
                'creator_id' => 0, 'record_status' => 'I', 'creator' => null),
        ),
    );

    private $fixtureView = array(
        'Course' => array(
            array(
                'id' => '1',
                'course' => 'MECH 328',
                'title' => 'Mechanical Engineering Design Project',
                'record_status' => 'A',
                'homepage' => 'http://www.mech.ubc.ca',
                'created' => '2006-06-20 14:14:45',
                'modified' => '2006-06-20 14:14:45',
                'creator' => 'Super Admin',
                'updater' => null,
            ),
            array (
                'id' => '2',
                'course' => 'APSC 201',
                'title' => 'Technical Communication',
                'record_status' => 'A',
                'homepage' => 'http://www.apsc.ubc.ca',
                'created' => '2006-06-20 14:15:38',
                'modified' => '2006-06-20 14:39:31',
                'creator' => 'Super Admin',
                'updater' => null,
            ),
        ),
        'Instructor' => array(
            array(
                'id' => '2',
                'username' => 'instructor1',
                'password' => 'b17c3f638781ecd22648b509e138c00f',
                'first_name' => 'Instructor',
                'last_name' => '1',
                'student_no' => null,
                'title' => 'Instructor',
                'email' => 'instructor1@email',
                'last_login' => null,
                'last_logout' => null,
                'last_accessed' => null,
                'record_status' => 'A',
                'creator_id' => '1',
                'created' => '2006-06-19 16:25:24',
                'updater_id' => null,
                'modified' => '2006-06-19 16:25:24',
                'lti_id' => null,
                'full_name' => 'Instructor 1',
                'student_no_with_full_name' => 'Instructor 1',
                'creator' => 'Super Admin',
                'updater' => null,
                'UserCourse' => array (
                    'id' => '1',
                    'user_id' => '2',
                    'course_id' => '1',
                    'access_right' => 'A',
                    'record_status' => 'A',
                    'creator_id' => '0',
                    'created' => '2006-06-20 14:14:45',
                    'updater_id' => null,
                    'modified' => '2006-06-20 14:14:45',
                ),
            ),
            array (
                'id' => '3',
                'username' => 'instructor2',
                'password' => 'b17c3f638781ecd22648b509e138c00f',
                'first_name' => 'Instructor',
                'last_name' => '2',
                'student_no' => null,
                'title' => 'Professor',
                'email' => '',
                'last_login' => null,
                'last_logout' => null,
                'last_accessed' => null,
                'record_status' => 'A',
                'creator_id' => '1',
                'created' => '2006-06-20 14:17:02',
                'updater_id' => null,
                'modified' => '2006-06-20 14:17:02',
                'lti_id' => null,
                'full_name' => 'Instructor 2',
                'student_no_with_full_name' => 'Instructor 2',
                'creator' => 'Super Admin',
                'updater' => null,
                'UserCourse' =>
                array (
                    'id' => '2',
                    'user_id' => '3',
                    'course_id' => '2',
                    'access_right' => 'A',
                    'record_status' => 'A',
                    'creator_id' => '0',
                    'created' => '2006-06-20 14:39:31',
                    'updater_id' => null,
                    'modified' => '2006-06-20 14:39:31',
                ),
            ),
            array (
                'id' => '4',
                'username' => 'instructor3',
                'password' => 'b17c3f638781ecd22648b509e138c00f',
                'first_name' => 'Instructor',
                'last_name' => '3',
                'student_no' => null,
                'title' => 'Assistant Professor',
                'email' => '',
                'last_login' => null,
                'last_logout' => null,
                'last_accessed' => null,
                'record_status' => 'A',
                'creator_id' => '1',
                'created' => '2006-06-20 14:17:53',
                'updater_id' => null,
                'modified' => '2006-06-20 14:17:53',
                'lti_id' => null,
                'full_name' => 'Instructor 3',
                'student_no_with_full_name' => 'Instructor 3',
                'creator' => 'Super Admin',
                'updater' => null,
                'UserCourse' =>
                array (
                    'id' => '3',
                    'user_id' => '4',
                    'course_id' => '2',
                    'access_right' => 'A',
                    'record_status' => 'A',
                    'creator_id' => '0',
                    'created' => '2006-06-20 14:39:31',
                    'updater_id' => null,
                    'modified' => '2006-06-20 14:39:31',
                ),
            ),
        ),
    );

    /**
     * startCase case startup
     *
     * @access public
     * @return void
     */
    public function startCase()
    {
    }

    /**
     * endCase case ending
     *
     * @access public
     * @return void
     */
    public function endCase()
    {
    }

    /**
     * startTest prepare tests
     *
     * @access public
     * @return void
     */
    public function startTest()
    {
        $this->testController = new MockCoursesController();
        $this->testController->params['action'] = 'test';
        $this->testController->__construct();
        $this->testController->constructClasses();
    }

    /**
     * endTest clean up
     *
     * @access public
     * @return void
     */
    public function endTest()
    {
        unset($this->testController);
        ClassRegistry::flush();
    }

    function _startController(&$controller, $params = array()) {
        $admin = array('User' => array('username' => 'root',
            'password' => md5('ipeeripeer')));
        $controller->Auth->login($admin);
        User::getInstance($controller->Auth->user());
        $controller->AccessControl->getPermissions();
        $controller->User->loadRoles(User::get('id'));
    }

    function testIndex() {
        $result = $this->testAction('/courses/index', array('return' => 'vars'));
        $expected = array(
            $this->fixtureIndex[1],
            $this->fixtureIndex[2],
            $this->fixtureIndex[0],
        );
        $this->assertTrue($result['paramsForList']['data']['entries'] == $expected);
    }

    function testView() {
        $result1 = $this->testAction('/courses/view/1', array('return' => 'vars'));
        $result2 = $this->testAction('/courses/view/2', array('return' => 'vars'));
        $expect1 = array('data' => array('Course' => $this->fixtureView['Course'][0], 'Instructor' => array($this->fixtureView['Instructor'][0])));
        $expect2 = array('data' => array('Course' => $this->fixtureView['Course'][1], 'Instructor' => array($this->fixtureView['Instructor'][1], $this->fixtureView['Instructor'][2])));
        $this->assertEqual($result1, $expect1);
        $this->assertEqual($result2, $expect2);
    }

    function testHome() {
        $this->Course = ClassRegistry::init('Course');
        $result = $this->testAction('/courses/home/1', array('connection' => 'test', 'return' => 'vars'));
        $result1 = $this->testAction('/courses/home/2', array('connection' => 'test', 'return' => 'vars'));

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
        //$result = $this->testAction('/courses/add', array('connection' => 'test', 'data' => $data, 'return' => 'vars'));
        //  $search = $this->Course->find('all', array('conditions' => array ('course' => 10)));
        //var_dump($search);
        //   $result1 = $this->testAction('/courses/home/2', array('connection' => 'test', 'return' => 'vars'));

    }

    //TODO redirect
    function testEdit () {
    }

    function testDelete() {
    }

    function testAddInstructor() {
        $this->Course = ClassRegistry::init('Course');
        $data = array('instructor_id'=> 2, 'course_id' => 1);
        //$result = $this->testAction('/courses/addInstructor', array('connection' => 'test', 'data' => $data, 'return' => 'vars'));
    }

    function testDeleteInstructor() {
        $data = array('instructor_id'=> 2, 'course_id' => 1);
        // $result = $this->testAction('/courses/deleteInstructor', array('connection' => 'test', 'data' => $data, 'return' => 'vars'));
        //  $search = $this->UserCourse->find('all', array( 'conditions' => array('user_id' => 1)));
    }

    function testCheckDuplicateName(){
        $data['Course']['course'] = 'Course';
        // $result = $this->testAction('/courses/deleteInstructor', array('connection' => 'test', 'data' => $data, 'return' => 'vars'));
    }

    //TODO uses Auth
    function testUpdate(){
    }
}
