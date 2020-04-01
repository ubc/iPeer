<?php
App::import('Lib', 'CaliperAuthTestCase');
# controllers
App::import('Controller', 'Home');
# caliper
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

// mock instead of needing to create a new controller for every test
Mock::generatePartial(
    'HomeController',
    'MockCaliperHomeController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperAppControllerHooksTest extends CaliperAuthTestCase
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
        echo "Start Caliper app controller hook test.\n";
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
        $this->controller = new MockCaliperHomeController();
    }

    /**
     * @access public
     * @return void
     */
    public function endTest($method)
    {
        parent::endTest($method);
    }

    function test_app_controller_before_render()
    {
        $this->setupSession('/home/index');
        $expected_event = array(
            'type' => 'NavigationEvent',
            'profile' => 'ReadingProfile',
            'action' => 'NavigatedTo',
            'object' => array(
                'id' => 'http://test.ipeer.com/',
                'type' => 'WebPage',
            ),
            'extensions' => array(
                'relativePath' => '',
                'queryString' => '',
                'absolutePath' => '',
                'absoluteUrl' => '/',
            )
        );

        # check disabled
        CaliperHooks::app_controller_before_render($this->controller);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        CaliperHooks::app_controller_before_render($this->controller);
        $events = $this->_get_caliper_events();
        $this->assertEqual(1, count($events));
        $actualEvent = $events[0];
        $this->assertEqual($expected_event, $actualEvent);
    }

    function test_app_controller_after_login_and_out()
    {
        $this->setupSession();
        $expected_event = array(
            'type' => 'SessionEvent',
            'profile' => 'SessionProfile',
            'action' => 'LoggedIn',
            'object' => $this->expected_ed_app
        );

        # check disabled
        # login
        CaliperHooks::app_controller_after_login($this->controller);
        $events = $this->_get_caliper_events(TRUE, FALSE);
        $this->assertEqual(0, count($events));

        # logout
        CaliperHooks::app_controller_after_logout($this->controller);
        $events = $this->_get_caliper_events(TRUE, TRUE);
        $this->assertEqual(0, count($events));


        # check enabled
        $this->_enable_caliper();
        # login
        CaliperHooks::app_controller_after_login($this->controller);
        $events = $this->_get_caliper_events(TRUE, FALSE);
        $this->assertEqual(1, count($events));
        $actualEvent = $events[0];
        $this->assertEqual($expected_event, $actualEvent);

        # logout
        $expected_event['action'] = 'LoggedOut';
        CaliperHooks::app_controller_after_logout($this->controller);
        $events = $this->_get_caliper_events(TRUE, TRUE);
        $this->assertEqual(1, count($events));
        $actualEvent = $events[0];
        $this->assertEqual($expected_event, $actualEvent);
    }
}