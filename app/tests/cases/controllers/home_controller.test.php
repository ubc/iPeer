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
        'app.rubrics_criteria_comment', 'rubrics_lom'
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
        $this->assertEqual(count($result['course_list']['A']), 2);
        $this->assertEqual(count($result['course_list']['I']), 1);
        $activeCourses = Set::sort($result['course_list']['A'], '{n}.Course.id', 'asc');
        $inactiveCourses = $result['course_list']['I'];
        $this->assertEqual(count($activeCourses[0]['Instructor']), 1);
        $this->assertEqual(count($activeCourses[0]['Event']), 9);
        $this->assertEqual(count($activeCourses[1]['Instructor']), 2);
        $this->assertEqual(count($activeCourses[1]['Event']), 0);
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
        $this->assertEqual(count($activeCourses[0]['Event']), 9);
    }

    function testIndexStudent()
    {
        $this->login = array(
            'User' => array(
                'username' => '65498451',
                'password' => md5('ipeeripeer')
            )
        );
        $result = $this->testAction('/home/index', array('return' => 'vars'));
        $upcoming = Set::sort($result['upcoming'], '{n}.Event.id', 'asc');
        $submitted = Set::sort($result['submitted'], '{n}.Event.id', 'asc');
        $this->assertEqual(count($upcoming), 5);
        $this->assertEqual(count($submitted), 4);

        $this->assertEqual(Set::extract('/Event/id', $upcoming), array(1,2,3,4,5));
        $this->assertEqual(Set::extract('/Event/id', $submitted), array(6,7,8,9));
    }
}
