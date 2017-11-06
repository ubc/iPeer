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
App::import('Controller', 'Home');

Mock::generatePartial(
    'HomeController',
    'MockHomeController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

class HomeControllerTest extends ExtendedAuthTestCase
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
        'app.oauth_token', 'app.rubric', 'app.rubrics_criteria',
        'app.rubrics_criteria_comment', 'rubrics_lom',
        'app.survey_input',
    );

    public function getController()
    {
        return $this->controller;
    }

    function startCase()
    {
        echo "Start Home controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
    }

    function endCase()
    {
    }

    function startTest($method)
    {
        echo $method.TEST_LB;
        $this->controller = new MockHomeController();
    }

    function endTest($method)
    {
        // defer logout to end of the test as some of the test need check flash
        // message. After logging out, message is destoryed.
        $this->controller->Auth->logout();
        unset($this->controller);
        ClassRegistry::flush();
    }

    function testIndex()
    {
        $result = $this->testAction('/home/index', array('return' => 'vars'));
        // courses are sorted by creation date in reverse order
        $this->assertTrue(
            strtotime($result['course_list']['A'][0]['Course']['created']) >
            strtotime($result['course_list']['A'][1]['Course']['created']));
        
        $this->assertEqual(
            $result['course_list']['A'][1]['Course']['course'], 'APSC 201');
        $this->assertEqual(
            $result['course_list']['A'][2]['Course']['course'], 'MECH 328');
        // test that there are no duplicate courses listed
        $this->assertEqual(count($result['course_list']['A']), 3);
        $this->assertEqual(count($result['course_list']['I']), 1);
        // test that course information is correct
        $activeCourses = $result['course_list']['A'];
        $inactiveCourses = $result['course_list']['I'];
        $this->assertEqual(count($activeCourses[1]['Instructor']), 3);
        $this->assertEqual(count($activeCourses[1]['Event']), 0);
        $this->assertEqual(count($activeCourses[2]['Instructor']), 1);
        $this->assertEqual(count($activeCourses[2]['Event']), 17);
        $this->assertEqual(count($inactiveCourses[0]['Instructor']), 1);
        $this->assertEqual(count($inactiveCourses[0]['Event']), 0);
    }

    function testIndexInstructor()
    {
        $this->login = array(
            'User' => array(
                'username' => 'instructor1',
                'password' => md5('ipeeripeer')
            )
        );
        $result = $this->testAction('/home/index', array('return' => 'vars'));
        $this->assertEqual(count($result['course_list']['A']), 1);
        $this->assertFalse(isset($result['course_list']['I']));
        $activeCourses = $result['course_list']['A'];
        $this->assertEqual(count($activeCourses[0]['Instructor']), 1);
        $this->assertEqual(count($activeCourses[0]['Event']), 17);

        // make sure the inactive courses are listed correctly
        $this->login = array(
            'User' => array(
                'username' => 'instructor3',
                'password' => md5('ipeeripeer')
            )
        );
        $result = $this->testAction('/home/index', array('return' => 'vars'));
        $this->assertEqual(count($result['course_list']['A']), 1);
        $this->assertEqual(count($result['course_list']['I']), 1);
        $inactiveCourses = $result['course_list']['I'];
        $this->assertEqual(count($inactiveCourses[0]['Instructor']), 1);
    }

    function testIndexFacultyAdmin()
    {
        $this->login = array(
            'User' => array(
                'username' => 'admin1',
                'password' => md5('ipeeripeer')
            )
        );
        // test that courses are in alphabetical order
        $result = $this->testAction('/home/index', array('return' => 'vars'));
        $this->assertEqual(
            $result['course_list']['A'][0]['Course']['course'], 'APSC 201');
        $this->assertEqual(
            $result['course_list']['A'][1]['Course']['course'], 'MECH 328');
        // test that there are no duplicate courses listed
        $this->assertEqual(count($result['course_list']['A']), 2);
        $this->assertEqual(count($result['course_list']['I']), 1);
        // test that course information is correct for MECH 328
        $activeCourses = $result['course_list']['A'];
        $this->assertEqual(count($activeCourses[1]['Instructor']), 1);
        $this->assertEqual(count($activeCourses[1]['Event']), 17);
    }

    function testIndexStudent()
    {
        $this->login = array(
            'User' => array(
                'username' => 'redshirt0001',
                'password' => md5('ipeeripeer')
            )
        );
        $result = $this->testAction('/home/index', array('return' => 'vars'));

        // summary statistics
        $this->assertEqual($result['numOverdue'], 0);
        $this->assertEqual($result['numDue'], 5);

        // evaluations
        $upcoming = Set::sort($result['evals']['upcoming'], '{n}.Event.id',
            'asc');
        $submitted = Set::sort($result['evals']['submitted'], '{n}.Event.id',
            'asc');
        $expired = Set::sort($result['evals']['expired'], '{n}.Event.id',
            'asc');
        $this->assertEqual(count($upcoming), 3);
        $this->assertEqual(count($submitted), 2);
        $this->assertEqual(count($expired), 2);

        $this->assertEqual(Set::extract('/Event/id', $upcoming),
            array(1,2,3));
        $this->assertEqual(Set::extract('/Event/id', $submitted),
            array(6,8));
        $this->assertEqual(Set::extract('/Event/id', $expired),
            array(7,9));

        // surveys
        $upcoming = Set::sort($result['surveys']['upcoming'], '{n}.Event.id',
            'asc');
        $submitted = Set::sort($result['surveys']['submitted'], '{n}.Event.id',
            'asc');
        $this->assertEqual(count($upcoming), 2);
        $this->assertEqual(count($submitted), 0);

        $this->assertEqual(Set::extract('/Event/id', $upcoming),
            array(4,5));
        $this->assertEqual(Set::extract('/Event/id', $submitted),
            array());
    }

    function testIndexFacultyAdminInstructorRareCase()
    {
        $this->login = array(
            'User' => array(
                'username' => 'admin3',
                'password' => md5('ipeeripeer')
            )
        );
        // test that combined admin'd/taught active courses are in alphabetical order
        $result = $this->testAction('/home/index', array('return' => 'vars'));
        $this->assertEqual(
            $result['course_list']['A'][0]['Course']['course'], 'APSC 201');
        $this->assertEqual(
            $result['course_list']['A'][1]['Course']['course'], 'CPSC 404');
        // test that there are no duplicate courses listed
        $this->assertEqual(count($result['course_list']['A']), 3);
        $this->assertEqual(count($result['course_list']['I']), 1);
        // test that course information is correct for MECH 328
        $activeCourses = $result['course_list']['A'];
        $this->assertEqual(count($activeCourses[2]['Instructor']), 1);
        $this->assertEqual(count($activeCourses[2]['Event']), 17);
        // test that course information is correct for CPSC 404
        $activeCourses = $result['course_list']['A'];
        $this->assertEqual(count($activeCourses[1]['Instructor']), 1);
        $this->assertEqual(count($activeCourses[1]['Event']), 0);

        $this->login = array(
            'User' => array(
                'username' => 'admin4',
                'password' => md5('ipeeripeer')
            )
        );
        // test that courses are in alphabetical order
        $result = $this->testAction('/home/index', array('return' => 'vars'));
        $this->assertEqual(
            $result['course_list']['A'][0]['Course']['course'], 'APSC 201');
        $this->assertEqual(
            $result['course_list']['A'][1]['Course']['course'], 'MECH 328');
        // test that there are no duplicate courses listed
        $this->assertEqual(count($result['course_list']['A']), 2);
        $this->assertEqual(count($result['course_list']['I']), 1);
    }
}
