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
App::import('Controller', 'Evaluations');

Mock::generatePartial(
    'EvaluationsController',
    'MockEvaluationsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

class EvaluationControllerTest extends ExtendedAuthTestCase
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
        'app.rubrics_criteria_comment', 'app.rubrics_lom',
        'app.simple_evaluation', 'app.survey_input', 'app.mixeval_question',
        'app.mixeval_question_desc', 'app.mixeval', 'app.mixeval_question_type',
    );

    public function getController()
    {
        return $this->controller;
    }

    function startCase()
    {
        echo "Start Evaluation controller test.\n";
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
        $this->controller = new MockEvaluationsController();
    }

    function endTest($method)
    {
        // defer logout to end of the test as some of the test need check flash
        // message. After logging out, message is destoryed.
        $this->controller->Auth->logout();
        unset($this->controller);
        ClassRegistry::flush();
    }

    function testMakeEvaluationSimpleForStudent()
    {
        $this->login = array(
            'User' => array(
                'username' => 'redshirt0001',
                'password' => md5('ipeeripeer')
            )
        );

        // simple evaluation
        $result = $this->testAction('/evaluations/makeEvaluation/1/1', array('return' => 'vars'));
        $this->assertEqual($result['courseId'], 1);
        $this->assertEqual($result['evaluateeCount'], 2);
        $this->assertEqual($result['remaining'], 200);
        $this->assertEqual(count($result['groupMembers']), 2);
        $this->assertEqual($result['fullName'], 'Ed Student');
        $this->assertEqual($result['userId'], 5);
        $this->assertEqual(count($result['penalty']), 4);
        $this->assertEqual(Set::extract($result['penalty'], '/Penalty/id'), array(1,2,3,4));
        $this->assertEqual($result['penaltyDays'], 3);
        $this->assertEqual($result['penaltyFinal']['Penalty']['id'], 4);
        $this->assertEqual($result['event']['Event']['id'], 1);
        $this->assertEqual($result['event']['GroupEvent']['id'], 1);
        $this->assertEqual($result['event']['Group']['id'], 1);
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], '');
    }

    function testMakeEvaluationRubricForStudent()
    {
        $this->login = array(
            'User' => array(
                'username' => 'redshirt0001',
                'password' => md5('ipeeripeer')
            )
        );

        // rubric evaluation
        $result = $this->testAction('/evaluations/makeEvaluation/2/1', array('return' => 'vars'));
        $this->assertEqual($result['evaluateeCount'], 2);
        $this->assertEqual(count($result['groupMembers']), 2);
        $this->assertEqual($result['viewData']['id'], 1);
        $this->assertEqual(count($result['penalty']), 4);
        $this->assertEqual(Set::extract($result['penalty'], '/Penalty/id'), array(5,6,7,8));
        $this->assertEqual($result['penaltyDays'], 3);
        $this->assertEqual($result['penaltyFinal']['Penalty']['id'], 8);
        $this->assertEqual($result['event']['Event']['id'], 2);
        $this->assertEqual($result['event']['GroupEvent']['id'], 3);
        $this->assertEqual($result['event']['Group']['id'], 1);
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], '');
    }

    function testMakeEvaluationMixevalForStudent()
    {
        $this->login = array(
            'User' => array(
                'username' => 'redshirt0001',
                'password' => md5('ipeeripeer')
            )
        );

        // rubric evaluation
        $result = $this->testAction('/evaluations/makeEvaluation/3/1', array('return' => 'vars'));
        $this->assertEqual(count($result['groupMembers']), 2);
        $this->assertEqual(count($result['groupMembers']), 2);
        $this->assertEqual($result['mixeval']['Mixeval']['id'], 1);
        $this->assertEqual(count($result['penalty']), 0);
        $this->assertEqual(Set::extract($result['penalty'], '/Penalty/id'), array());
        $this->assertEqual($result['penaltyDays'], 0);
        $this->assertEqual($result['event']['Event']['id'], 3);
        $this->assertEqual($result['event']['GroupEvent']['id'], 5);
        $this->assertEqual($result['event']['Group']['id'], 1);
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], '');
    }

    function testMakeEvaluationSurveyForStudent()
    {
        $this->login = array(
            'User' => array(
                'username' => 'redshirt0001',
                'password' => md5('ipeeripeer')
            )
        );

        $result = $this->testAction('/evaluations/makeEvaluation/4', array('return' => 'vars'));
        $this->assertEqual($result['eventId'], 4);
        $this->assertEqual(count($result['questions']), 2);
        $this->assertEqual($result['questions'][0]['Question']['prompt'], 
            'What was your GPA last term?');
        $this->assertEqual($result['questions'][1]['Question']['prompt'], 
            'Do you own a laptop?');
        $this->assertEqual(count($result['questions'][0]['ResponseOptions']), 
            4);
        $this->assertEqual(count($result['questions'][1]['ResponseOptions']), 
            2);
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], '');
    }
}
