<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Simpleevaluations');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('SimpleevaluationsController',
    'MockSimpleevaluationsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class SimpleevaluationsControllerTest extends ExtendedAuthTestCase {
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
        'app.simple_evaluation', 'app.survey_input', 'app.mixeval_question',
        'app.mixeval_question_desc', 'app.mixeval'
    );

    function startCase() {
        echo "Start Simple Evaluation controller test.\n";
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
        $this->controller = new MockSimpleevaluationsController();
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
        $result = $this->testAction('/simpleevaluations/index', array('return' => 'vars'));
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['SimpleEvaluation']['name'], 'Module 1 Project Evaluation');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['SimpleEvaluation']['description'], '');
        $this->assertEqual($result['paramsForList']['data']['entries'][0]['SimpleEvaluation']['point_per_member'], 100);
    }

    function testView() {
        $result = $this->testAction('/simpleevaluations/view/1', array('return' => 'vars'));
        $this->assertEqual($result['data']['SimpleEvaluation']['name'], 'Module 1 Project Evaluation');
        $this->assertEqual($result['data']['SimpleEvaluation']['description'], '');
        $this->assertEqual($result['data']['SimpleEvaluation']['point_per_member'], 100);
    }

    //TODO test redirect
    function testAdd(){
    }

    //TODO test saving, redirect
    function testEdit() {
        //    $result = $this->testAction('/simpleevaluations/edit/1', array('connection' => 'test_suite', 'return' => 'vars'));

    }

    //TODO test redirect
    function testCopy(){
    }

    //TODO test redirect
    function testDelete(){
    }

}
