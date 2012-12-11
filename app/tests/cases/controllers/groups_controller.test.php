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
App::import('Controller', 'Groups');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('GroupsController',
    'MockGroupsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class GroupsControllerTest extends ExtendedAuthTestCase {
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
        echo "Start Group controller test.\n";
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
        $this->controller = new MockGroupsController();
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
        $result = $this->testAction('/groups/index/1', array('return' => 'vars'));

        $this->assertEqual($result['course_id'], 1);
        /*$this->assertEqual($result['paramsForList']['data']['entries'][0]['Course']['course'], 'Math303');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Group']['group_num'], '1');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Group']['member_count'], '2');
        $this->assertEqual($result['paramsForList']['data']['entries'][1]['Group']['group_num'], '2');
        $this->assertEqual($result['paramsForList']['data']['entries'][1]['Group']['member_count'], '2');
        $this->assertEqual($result['paramsForList']['data']['entries'][2]['Group']['group_num'], '3');
        $this->assertEqual($result['paramsForList']['data']['entries'][2]['Group']['member_count'], '0');*/
    }

    function testView() {
        $result = $this->testAction('/groups/view/1', array('return' => 'vars'));

        $this->assertEqual($result['data']['Group']['group_num'], 1);
        $this->assertEqual($result['data']['Group']['group_name'], 'Reapers');
        $this->assertEqual(count($result['data']['Member']), $result['data']['Group']['member_count']);
        $this->assertEqual($result['data']['Member'][0]['full_name'], 'Ed Student');
        $this->assertEqual($result['data']['Member'][1]['full_name'], 'Alex Student');
        $this->assertEqual($result['data']['Member'][2]['full_name'], 'Matt Student');
        $this->assertEqual($result['data']['Member'][3]['full_name'], 'Tutor 1');
    }

    function testAdd() {
        $result = $this->testAction('/groups/add/1', array('return' => 'vars'));

        $this->assertEqual($result['group_num'], 3);
        $this->assertEqual($result['course_id'], 1);
        $this->assertEqual(count($result['user_data']), 15);
        $this->assertEqual($result['user_data'][5], '65498451 Ed Student *');
        $this->assertEqual($result['user_data'][26], '19524032 Bill Student');

        //TODO test saving, redirect
    }

    function testEdit() {
        $result = $this->testAction('/groups/edit/1', array('return' => 'vars'));

        $this->assertEqual($result['group_num'], 1);
        $this->assertEqual(count($result['members']), 4);
        $this->assertEqual($result['members'][5], '65498451 Ed Student');

        $this->assertEqual(count($result['user_data']), 11);
        // group member should not be in user_data
        $this->assertFalse(isset($result['user_data'][5]));
        $this->assertEqual($result['user_data'][26], '19524032 Bill Student');

        //TODO test saving, redirect
    }

    function testDelete() {
    }
}
