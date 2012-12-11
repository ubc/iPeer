<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Events');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('EventsController',
    'MockEventsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

/**
 * EventsControllerTest
 *
 * @uses ExtendedAuthTestCase
 * @package Tests
 * @author  Pan Luo <pan.luo@ubc.ca>
 */
class EventsControllerTest extends ExtendedAuthTestCase {
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
        'app.rubrics_criteria_comment', 'app.rubrics_lom',
        'app.simple_evaluation', 'app.survey_input', 'app.mixevals_question',
        'app.mixevals_question_desc', 'app.mixeval'
    );

    function startCase() {
        echo "Start Event controller test.\n";
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
        echo $method.TEST_LB;
        $this->controller = new MockEventsController();
    }

    public function endTest($method)
    {
        // defer logout to end of the test as some of the test need check flash
        // message. After logging out, message is destoryed.
        if (isset($this->controller->Auth)) {
            $this->controller->Auth->logout();
        }
        unset($this->controller);
        ClassRegistry::flush();
    }

    public function getController()
    {
        return $this->controller;
    }

    function testIndex() {
        $result = $this->testAction('/events/index', array('return' => 'vars'));

        $this->assertEqual(count($result["paramsForList"]['data']['entries']), 4);
        $this->assertEqual(sort(Set::extract($result["paramsForList"]['data']['entries'], '/Event/id')), array(1,2,3,6));
        $events = Set::sort($result["paramsForList"]['data']['entries'], '{n}.Event.id', 'asc');
        $this->assertEqual($events[0]['Event']['Title'], 'Term 1 Evaluation');
        $this->assertEqual($events[0]['Event']['event_template_type_id'], 1);
        $this->assertEqual($events[0]['Course']['course'], 'MECH 328');
        $this->assertEqual($events[0]['!Custom']['isReleased'], 'Open Now');

        $this->assertEqual($events[1]['Event']['Title'], 'Term Report Evaluation');
        $this->assertEqual($events[1]['Event']['event_template_type_id'], 2);
        $this->assertEqual($events[1]['Course']['course'], 'MECH 328');
        $this->assertEqual($events[1]['!Custom']['isReleased'], 'Open Now');

        $this->assertEqual($events[2]['Event']['Title'], 'Project Evaluation');
        $this->assertEqual($events[2]['Event']['event_template_type_id'], 4);
        $this->assertEqual($events[2]['Course']['course'], 'MECH 328');
        $this->assertEqual($events[2]['!Custom']['isReleased'], 'Open Now');

    }

    function testView() {
        $result = $this->testAction('/events/view/1', array('return' => 'vars'));

        $this->assertEqual($result['event']['Event']['title'], 'Term 1 Evaluation');
        $this->assertEqual($result['event']['Event']['event_template_type_id'], 1);
        $this->assertEqual($result['event']['Group'][0]['group_name'], 'Reapers');
        $this->assertEqual($result['event']['Group'][0]['Member'][0]['first_name'], 'Ed');
        $this->assertEqual($result['event']['Group'][0]['Member'][0]['last_name'], 'Student');

        $result = $this->testAction('/events/view/2', array('return' => 'vars'));

        $this->assertEqual($result['event']['Event']['title'], 'Term Report Evaluation');
        $this->assertEqual($result['event']['Event']['event_template_type_id'], 2);
        $this->assertEqual($result['event']['Group'][0]['group_name'], 'Reapers');
        $this->assertEqual($result['event']['Group'][0]['Member'][0]['first_name'], 'Ed');
        $this->assertEqual($result['event']['Group'][0]['Member'][0]['last_name'], 'Student');
    }

    function testAdd(){
    }

    function testEdit() {
        $result = $this->testAction('/events/edit/1', array('return' => 'vars'));

        $this->assertEqual($result['event']['Event']['title'], 'Term 1 Evaluation');
        $this->assertEqual($result['event']['Event']['event_template_type_id'], 1);
        $this->assertEqual($result['event']['Group'][0]['group_name'], 'Reapers');
        $this->assertEqual($result['event']['Group'][0]['Member'][0]['first_name'], 'Ed');
        $this->assertEqual($result['event']['Group'][0]['Member'][0]['last_name'], 'Student');

        //TODO test saving the data (redirect)

    }

    function testDelete() {
    }

    function testSearch() {
    }

    function testViewGroups() {
        $result = $this->testAction('/events/viewGroups/1', array('return' => 'vars'));

        $this->assertEqual($result['assignedGroups'][0]['group_num'], 1);
        $this->assertEqual($result['assignedGroups'][0]['group_name'], 'Reapers');
        $this->assertEqual($result['assignedGroups'][0]['member_count'], 4);
        $this->assertEqual($result['assignedGroups'][0]['Member'][0]['full_name'], 'Ed Student');
        $this->assertEqual($result['assignedGroups'][0]['Member'][1]['full_name'], 'Alex Student');

        $this->assertEqual($result['assignedGroups'][1]['group_num'], 2);
        $this->assertEqual($result['assignedGroups'][1]['group_name'], 'Lazy Engineers');
        $this->assertEqual($result['assignedGroups'][1]['member_count'], 4);
        $this->assertEqual($result['assignedGroups'][1]['Member'][0]['full_name'], 'Hui Student');
        $this->assertEqual($result['assignedGroups'][1]['Member'][1]['full_name'], 'Bowinn Student');
    }

    function testEditGroups()
    {
    }
}
