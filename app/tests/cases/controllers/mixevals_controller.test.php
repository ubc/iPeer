<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Mixevals');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('MixevalsController',
    'MockMixevalsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class MixevalsControllerTest extends ExtendedAuthTestCase {
    public $controller = null;

    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group', 'app.roles_user', 
        'app.event', 'app.event_template_type', 'app.group_event', 
        'app.evaluation_submission', 'app.survey_group_set', 
        'app.survey_group', 'app.survey_group_member', 'app.question', 
        'app.response', 'app.survey_question', 'app.user_course', 
        'app.user_enrol', 'app.groups_member', 'app.survey', 'app.personalize', 
        'app.penalty', 'app.evaluation_simple', 'app.faculty', 
        'app.user_tutor', 'app.course_department', 'app.evaluation_rubric', 
        'app.evaluation_rubric_detail', 'app.evaluation_mixeval', 
        'app.evaluation_mixeval_detail', 'app.user_faculty', 'app.department', 
        'app.sys_parameter', 'app.oauth_token', 'app.rubric', 
        'app.rubrics_criteria', 'app.rubrics_criteria_comment', 
        'app.rubrics_lom', 'app.simple_evaluation', 'app.survey_input', 
        'app.mixeval_question', 'app.mixeval_question_desc', 'app.mixeval'
    );

    function startCase() {
        echo "Start Mixeval controller test.\n";
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
        $this->controller = new MockMixevalsController();
    }

    function endTest() {
    }

    public function getController()
    {
        return $this->controller;
    }

    function testIndex() {
        $result = $this->testAction('/mixevals/index', array('return' => 'vars'));

        $this->assertEqual(count($result['paramsForList']['data']['entries']), 1);
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['id'], 1);
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['Mixeval']['name'], 'Default Mix Evalution');
        $this->assertEqual($result['paramsForList']['data']['count'], 1);
    }

    function testView() {
        $result = $this->testAction('/mixevals/view/1', array('return' => 'vars'));
        $result = $result['data'];

        $this->assertEqual($result['Mixeval']['id'], 1);
        $this->assertEqual(count($result['Question']), 6);
        $this->assertEqual(count($result['Question'][0]['Description']), 5);
        $this->assertEqual(count($result['Question'][1]['Description']), 5);
        $this->assertEqual(count($result['Question'][2]['Description']), 5);
        $this->assertEqual(count($result['Question'][3]['Description']), 0);
        $this->assertEqual(count($result['Question'][4]['Description']), 0);
        $this->assertEqual(count($result['Question'][5]['Description']), 0);
    }

    function testAdd() {
    }

    function testDeleteQuestion() {
    }

    function testDeleteDescription() {
    }

    function testEdit() {
    }

    function testCopy() {
    }

    function testDelete() {
    }
}
