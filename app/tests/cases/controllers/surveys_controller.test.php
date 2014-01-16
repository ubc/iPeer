<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Surveys');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('SurveysController',
    'MockSurveysController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class SurveyControllerTest extends ExtendedAuthTestCase {
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
        echo "Start Survey controller test.\n";
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
        $this->controller = new MockSurveysController();
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
        $result = $this->testAction('/surveys/index', array('return' => 'vars'));
        $surveys = Set::sort($result['paramsForList']['data']['entries'], '{n}.Survey.id', 'asc');
        $this->assertEqual($surveys[0]['Survey']['name'], 'Team Creation Survey');
        $this->assertEqual($surveys[0]['Survey']['question_count'], 2);
        $this->assertEqual($surveys[1]['Survey']['name'], 'Survey, all Q types');
        $this->assertEqual($surveys[1]['Survey']['question_count'], 4);
    }

    function testView() {
        $result = $this->testAction('/surveys/view/1', array('return' => 'vars'));
        $this->assertEqual($result['survey']['Survey']['name'], 'Team Creation Survey');
        $this->assertEqual($result['questions'][0]['Question']['prompt'], 'What was your GPA last term?');
        $this->assertEqual($result['questions'][0]['Response'][0]['response'], '4+');
        $this->assertEqual($result['questions'][0]['Response'][1]['response'], '3-4');
        $this->assertEqual($result['questions'][1]['Question']['prompt'], 'Do you own a laptop?');
        $this->assertEqual($result['questions'][1]['Response'][0]['response'], 'yes');
    }

    //TODO redirect
    function testAdd() {
    }
    //TODO redirect
    function testEdit() {
        //    $result = $this->testAction('/surveys/edit/1', array('connection' => 'test_suite', 'return' => 'contents'));
        //    var_dump($result);
    }

    //TODO redirect
    function testCopy() {
    }
    //TODO redirect
    function testDelete() {
    }

    //TODO redirect
    function testQuestionSummary() {
    }

    //TODO redirect
    function testMoveQuestion() {
    }

    //TODO redirect
    function testAddQuestion() {
        //$result = $this->testAction('surveys/addQuestion/1', array('connection' => 'test_suite', 'return' => 'vars'));
        // var_dump($result);
    }

    function testEditQuestion() {
        //$result = $this->testAction('surveys/editQuestion/1/1', array('connection' => 'test_suite', 'return' => 'vars'));
        // var_dump($result);
    }

}
