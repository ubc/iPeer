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
 * @uses ExtendedAuthTestCase
 * @package Tests
 * @author  Pan Luo <pan.luo@ubc.ca>
 */
class CoursesControllerTest extends ExtendedAuthTestCase
{
    public $controller = null;

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
        'app.oauth_token', 'app.survey_input', 'app.user_oauths'
    );

    //public $testMethods = array('testIndexNoPermission');

    private $fixtureIndex = array(
        array(
            'Course' => array(
                'id' => 1,
                'course' => 'MECH 328',
                'title' => 'Mechanical Engineering Design Project',
                'term' => null,
                'creator_id' => 1,
                'record_status' => 'A',
                'creator' => 'Super Admin'
            ),
        ),
        array(
            'Course' => array(
                'id' => 2,
                'course' => 'APSC 201',
                'title' => 'Technical Communication',
                'term' => null,
                'creator_id' => 1,
                'record_status' => 'A',
                'creator' => 'Super Admin'
            ),
        ),
        array(
            'Course' => array(
                'id' => 3,
                'course' => 'CPSC 101',
                'title' => 'Connecting with Computer Science',
                'term' => null,
                'creator_id' => 1,
                'record_status' => 'I',
                'creator' => 'Super Admin'
            ),
        ),
        array(
            'Course' => array(
                'id' => 4,
                'course' => 'CPSC 404',
                'title' => 'Advanced Software Engineering',
                'term' => null,
                'creator_id' => 1,
                'record_status' => 'A',
                'creator' => 'Super Admin'
            ),
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
                'student_count' => 13,
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
                'student_count' => 15,
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
        echo "Start Course controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
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
    public function startTest($method)
    {
        echo $method.TEST_LB;
        $this->controller = new MockCoursesController();
    }

    /**
     * endTest clean up
     *
     * @access public
     * @return void
     */
    public function endTest($method)
    {
        // defer logout to end of the test as some of the test need check flash
        // message. After logging out, message is destoryed.
        $this->controller->Auth->logout();
        unset($this->controller);
        ClassRegistry::flush();
    }

    public function getController()
    {
        return $this->controller;
    }

    function testIndex()
    {
        $result = $this->testAction('/courses/index', array('return' => 'vars'));
        $expected = array(
            $this->fixtureIndex[1],
            $this->fixtureIndex[2],
            $this->fixtureIndex[3],
            $this->fixtureIndex[0],
        );
        $this->assertEqual($result['paramsForList']['data']['entries'],
            $expected);
    }

    function testIndexNoPermission()
    {
        // test with student account
        $this->login = array(
            'User' => array(
                'username' => 'redshirt0001',
                'password' => md5('ipeeripeer')
            )
        );
        $this->controller->expectOnce('redirect', array('/home'));
        $this->testAction('/courses/index', array('return' => 'result', 'fixturize' => false));
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: You do not have permission to access the page.');
    }

    function testIndexFacultyAdminInstructorRareCase() {
        // test courses admin'd (Fac of AppSci) or taught by admin3
        $this->login = array(
            'User' => array(
                'username' => 'admin3',
                'password' => md5('ipeeripeer')
            )
        );
        $result = $this->testAction('/courses/index', array('return' => 'vars'));
        $expected = array(
            $this->fixtureIndex[1],
            $this->fixtureIndex[2],
            $this->fixtureIndex[3],
            $this->fixtureIndex[0],
        );
        $this->assertEqual($result['paramsForList']['data']['entries'],
            $expected);

        // test courses admin'd (Fac of Sci) or taught by admin4
        $this->login = array(
            'User' => array(
                'username' => 'admin4',
                'password' => md5('ipeeripeer')
            )
        );
        $result = $this->testAction('/courses/index', array('return' => 'vars'));
        $expected = array(
            $this->fixtureIndex[1],
            $this->fixtureIndex[2],
            $this->fixtureIndex[0],
        );
        $this->assertEqual($result['paramsForList']['data']['entries'],
            $expected);
    }

    function testView()
    {
        $result1 = $this->testAction('/courses/view/1', array('return' => 'vars'));
        $result2 = $this->testAction('/courses/view/2', array('return' => 'vars'));

        $result = $result1['data'];
        $this->assertEqual($result['Course']['id'], 1);
        $this->assertEqual($result['Course']['course'], $this->fixtureView['Course'][0]['course']);
        $this->assertEqual($result['Course']['student_count'], $this->fixtureView['Course'][0]['student_count']);
        $this->assertEqual(count($result['Instructor']), 1);
        $this->assertEqual($result['Instructor'][0]['id'], $this->fixtureView['Instructor'][0]['id']);

        $result = $result2['data'];
        $this->assertEqual($result['Course']['id'], 2);
        $this->assertEqual($result['Course']['course'], $this->fixtureView['Course'][1]['course']);
        $this->assertEqual($result['Course']['student_count'], $this->fixtureView['Course'][1]['student_count']);
        $this->assertEqual(count($result['Instructor']), 3);
        $this->assertEqual($result['Instructor'][0]['id'], $this->fixtureView['Instructor'][1]['id']);
        $this->assertEqual($result['Instructor'][1]['id'], $this->fixtureView['Instructor'][2]['id']);
    }

    function testViewInvalidId()
    {
        // test invalid course id
        $this->controller->expectOnce('redirect', array('index'));
        $this->testAction('/courses/view/9999', array('return' => 'vars'));
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testViewNotMyCourse()
    {
        // test with student account
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );
        $this->controller->expectOnce('redirect', array('index'));
        $this->testAction('/courses/view/1', array('return' => 'vars'));
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testHome()
    {
        $result = $this->testAction('/courses/home/1', array('return' => 'vars'));

        $this->assertEqual($result['data']['Course']['student_count'], 13);
        $this->assertEqual($result['data']['Course']['id'], $this->fixtureView['Course'][0]['id']);
        $this->assertEqual($result['data']['Course']['id'], $this->fixtureView['Course'][0]['id']);
        $this->assertEqual($result['data']['Course']['course'], $this->fixtureView['Course'][0]['course']);
        $this->assertEqual($result['data']['Course']['title'], $this->fixtureView['Course'][0]['title']);
        $this->assertEqual(count($result['data']['Group']), 2);
        $this->assertEqual(count($result['data']['Event']), 17);
        $this->assertEqual($result['title_for_layout'], $this->fixtureView['Course'][0]['course'].' - '.$this->fixtureView['Course'][0]['title']);
    }

    function testHomeInvalidId()
    {
        // test invalid course id
        $this->controller->expectOnce('redirect', array('index'));
        $this->testAction('/courses/home/9999', array('return' => 'result'));
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testHomeNotMyCourse()
    {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );
        $this->controller->expectOnce('redirect', array('index'));
        $this->testAction('/courses/home/1', array('return' => 'vars'));
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testAdd()
    {
        $result = $this->testAction('/courses/add', array('return' => 'vars'));

        $this->assertEqual(count($result['departments']), 3);
        $this->assertEqual($result['statusOptions'], array( 'A' => 'Active', 'I' => 'Inactive'));
        $this->assertEqual(count($result['instructors']), 3);
        $this->assertEqual($result['title_for_layout'], 'Add Course');
    }

    function testAddWithData()
    {
        $data = array(
            'Course' => array(
                'course' => 'test',
                'title' => 'Some Course',
                'record_status' => 'A',
                'homepage' => 'http://www.ubc.ca'
            ),
            'Instructor' => array(
                'Instructor' => array(2)
            ),
            'Department' => array(
                'Department' => array(2)
            ),
        );
        $this->controller->expectOnce('redirect', array('index'));

        $this->testAction(
            '/courses/add',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $courseModel = ClassRegistry::init('Course');
        $found = $courseModel->find('first', array( 'conditions' => array('course' => $data['Course']['course'])));
        $this->assertNotNull($found);
        $this->assertEqual($found['Course']['title'], $data['Course']['title']);
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Course created!');
    }

    function testAddWithExistingCourse()
    {
        $data = array(
            'Course' => array(
                'course' => 'MECH 328',
                'title' => 'Some Course',
                'record_status' => 'A',
                'homepage' => 'http://www.ubc.ca'
            ),
            'Instructor' => array(
                'Instructor' => array(2)
            ),
            'Department' => array(
                'Department' => array(2)
            ),
        );

        $this->testAction(
            '/courses/add',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $courseModel = ClassRegistry::init('Course');
        $count = $courseModel->find('count', array( 'conditions' => array('course' => $data['Course']['course'])));
        $this->assertEqual($count, 1);
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Add course failed.');
    }

    function testEdit()
    {
        $result = $this->testAction('/courses/edit/1', array('return' => 'vars'));

        $this->assertEqual(count($result['departments']), 3);
        $this->assertEqual($result['statusOptions'], array( 'A' => 'Active', 'I' => 'Inactive'));
        $this->assertEqual(count($result['instructors']), 3);
        $this->assertEqual($this->controller->data['Course']['course'], $this->fixtureView['Course'][0]['course']);
        $this->assertEqual($this->controller->data['Course']['course'], $this->fixtureView['Course'][0]['course']);
        $this->assertEqual(count($this->controller->data['Instructor']['Instructor']), 1);
        $this->assertEqual($this->controller->data['Instructor'][0]['id'], 2);
    }

    function testEditWithData()
    {
        $data = array(
            'Course' => array(
                'id' => 1,
                'course' => 'test',
                'title' => 'Some Course',
                'record_status' => 'A',
                'homepage' => 'http://www.ubc.ca'
            ),
            'Instructor' => array(
                'Instructor' => array(2)
            ),
            'Department' => array(
                'Department' => array(3)
            ),
        );

        $this->controller->expectOnce('redirect', array('index'));
        $result = $this->testAction(
            '/courses/edit/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $courseModel = ClassRegistry::init('Course');
        $course = $courseModel->find('all', array( 'conditions' => array('course' => $data['Course']['course']), 'contain' => array('Instructor', 'Department')));
        $this->assertEqual(count($course), 1);
        $course = $course[0];
        $this->assertEqual($course['Course']['id'], $data['Course']['id']);
        $this->assertEqual($course['Course']['title'], $data['Course']['title']);
        $this->assertEqual($course['Course']['record_status'], $data['Course']['record_status']);
        $this->assertEqual($course['Course']['homepage'], $data['Course']['homepage']);

        $this->assertEqual(count($course['Department']), 1);
        $this->assertEqual($course['Department'][0]['id'], 3);
        $this->assertEqual(count($course['Instructor']), 1);
        $this->assertEqual($course['Instructor'][0]['id'], 2);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The course was updated successfully.');
    }

    function testEditSavingToExistingCourse()
    {
        $data = array(
            'Course' => array(
                'id' => 1,
                'course' => 'CPSC 101',
                'title' => 'Some Course',
                'record_status' => 'A',
                'homepage' => 'http://www.ubc.ca'
            ),
            'Instructor' => array(
                'Instructor' => array(2)
            ),
            'Department' => array(
                'Department' => array(3)
            ),
        );
        $result = $this->testAction(
            '/courses/edit/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course edits could not be saved.');
    }

    function testEditOthersCourse()
    {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );
        $data = array(
            'Course' => array(
                'id' => 1,
                'course' => 'test',
                'title' => 'Some Course',
                'record_status' => 'A',
                'homepage' => 'http://www.ubc.ca'
            ),
            'Instructor' => array(
                'Instructor' => array(2)
            ),
            'Department' => array(
                'Department' => array(3)
            ),
        );
        $this->controller->expectAt(0, 'redirect', array('index'));
        $result = $this->testAction(
            '/courses/edit/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testDelete()
    {
        $result = $this->testAction(
            '/courses/delete/1',
            array('fixturize' => true, 'method' => 'get')
        );

        $courseModel = ClassRegistry::init('Course');
        $found = $courseModel->find('first', array( 'conditions' => array('id' => 1)));
        $this->assertFalse($found);
        $instructors = $courseModel->UserCourse->find('all', array('conditions' => array('course_id' => 1)));
        $this->assertFalse($instructors);
        $students = $courseModel->UserEnrol->find('all', array('conditions' => array('course_id' => 1)));
        $this->assertFalse($students);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The course was deleted successfully.');
    }

    /*function testMove()
    {
        //TODO
    }*/

    function testDeleteNonExistingCourse()
    {
        $this->controller->expectOnce('redirect', array('index'));
        $result = $this->testAction(
            '/courses/delete/999',
            array('fixturize' => true, 'method' => 'get')
        );

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testDeleteOthersCourse()
    {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );
        $this->controller->expectOnce('redirect', array('index'));
        $this->testAction(
            '/courses/delete/1',
            array('fixturize' => true, 'method' => 'get')
        );

        // course should not be deleted
        $courseModel = ClassRegistry::init('Course');
        $found = $courseModel->find('first', array( 'conditions' => array('id' => 1)));
        $this->assertTrue(array_key_exists('Course', $found));

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testLogout()
    {
        $this->testAction('/courses/index', array('return' => 'vars'));
        $this->controller->Auth->logout();

        $this->assertFalse($this->controller->Auth->isAuthorized());
        $this->assertFalse(array_key_exists('Auth', $_SESSION));
        $this->assertFalse(array_key_exists('ipeerSession', $_SESSION));
    }


/*    function testAddInstructor() {
        $this->Course = ClassRegistry::init('Course');
        $data = array('instructor_id'=> 2, 'course_id' => 1);
        //$result = $this->testAction('/courses/addInstructor', array('connection' => 'test', 'data' => $data, 'return' => 'vars'));
    }

    function testDeleteInstructor() {
        $data = array('instructor_id'=> 2, 'course_id' => 1);
        // $result = $this->testAction('/courses/deleteInstructor', array('connection' => 'test', 'data' => $data, 'return' => 'vars'));
        //  $search = $this->UserCourse->find('all', array( 'conditions' => array('user_id' => 1)));
    }
 */
}
