<?php
App::import('Lib', 'CaliperAuthTestCase');
# controllers
App::import('Controller', 'Courses');
# caliper
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

// mock instead of needing to create a new controller for every test
Mock::generatePartial(
    'CoursesController',
    'MockCaliperCoursesController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperCourseHooksTest extends CaliperAuthTestCase
{
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

    /**
     * startCase case startup
     *
     * @access public
     * @return void
     */
    public function startCase()
    {
        echo "Start Caliper course hook test.\n";
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
     * @access public
     * @return void
     */
    public function startTest($method)
    {
        echo $method.TEST_LB;
        parent::startTest($method);
        $this->controller = new MockCaliperCoursesController();
        $this->Course->id = $this->course['id'];
    }

    /**
     * @access public
     * @return void
     */
    public function endTest($method)
    {
        parent::endTest($method);
    }

    function test_course_delete()
    {
        $this->setupSession();
        $expected_stored_data = array(
            'Course' => $this->course,
        );
        $expected_event = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_course,
            'group' => $this->expected_group,
            'membership' => $this->expected_membership,
        );

        # check disabled
        # before_delete
        CaliperHooks::course_before_delete($this->Course);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Course->data));

        # after_delete
        CaliperHooks::course_after_delete($this->Course);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Course->data));

        # check enabled
        $this->_enable_caliper();
        # before_delete
        CaliperHooks::course_before_delete($this->Course);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertTrue(array_key_exists('caliper_delete', $this->Course->data));
        $this->assertEqual($expected_stored_data, $this->Course->data['caliper_delete']);

        # after_delete
        CaliperHooks::course_after_delete($this->Course);
        $events = $this->_get_caliper_events();
        $this->assertEqual(1, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Course->data));
        $actualEvent = $events[0];
        $this->assertEqual($expected_event, $actualEvent);
    }

    function test_course_save()
    {
        $this->setupSession();
        $expected_event = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Created',
            'object' => $this->expected_course,
            'group' => $this->expected_group,
            'membership' => $this->expected_membership,
        );

        # check disabled
        CaliperHooks::course_after_save($this->Course, TRUE);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        foreach([True, False] as $created) {
            $expected_event['action'] = $created ? 'Created' : 'Modified';

            CaliperHooks::course_after_save($this->Course, $created);
            $events = $this->_get_caliper_events();
            $this->assertEqual(1, count($events));
            $actualEvent = $events[0];
            $this->assertEqual($expected_event, $actualEvent);
        }
    }
}