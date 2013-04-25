<?php
App::import('Model', 'EvaluationMixevalDetail');

class EvaluationMixevalDetailTestCase extends CakeTestCase {
    public $name = 'EvaluationMixevalDetail';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.faculty', 'app.user_faculty', 'app.user_tutor', 'app.department',
        'app.course_department', 'app.penalty',
        'app.evaluation_simple', 'app.survey_input', 'app.oauth_token',
        'app.sys_parameter', 'app.evaluation_rubric', 'app.evaluation_rubric_detail'
    );
    public $Course = null;

    function startCase()
    {
        echo "Start Evaluation Mixeval Detail model test.\n";
        $this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
    }

    function endCase()
    {
    }

    //Run before EVERY test.
    function startTest($method)
    {
        // extra setup stuff here
    }

    function endTest($method)
    {
    }

    function testCourseInstance()
    {
        $this->assertTrue(is_a($this->EvaluationMixevalDetail, 'EvaluationMixevalDetail'));
    }

    function testGetByEvalMixevalIdCriteria()
    {
        // Run test on valid data
        $mixEvalDetail1 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCriteria(1, 4);
        $mixEvalDetail2 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCriteria(1, 5);
        $this->assertEqual($mixEvalDetail1['EvaluationMixevalDetail']['id'], 4);
        $this->assertEqual($mixEvalDetail1['EvaluationMixevalDetail']['question_number'], 4);
        $this->assertEqual($mixEvalDetail1['EvaluationMixevalDetail']['question_comment'], 'work very efficiently');
        $this->assertEqual($mixEvalDetail2['EvaluationMixevalDetail']['id'], 5);
        $this->assertEqual($mixEvalDetail2['EvaluationMixevalDetail']['question_number'], 5);
        $this->assertEqual($mixEvalDetail2['EvaluationMixevalDetail']['question_comment'], 'Contributed his part');
        // Run test on one valid input
        $mixEvalDetail3 = $invalid3 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCriteria(1, null);
        $this->assertTrue(!empty($mixEvalDetail3));
        $this->assertEqual($mixEvalDetail3[0]['EvaluationMixevalDetail']['id'], 1);
        $this->assertEqual($mixEvalDetail3[1]['EvaluationMixevalDetail']['id'], 2);
        $this->assertEqual($mixEvalDetail3[0]['EvaluationMixevalDetail']['question_comment'], null);
        $this->assertEqual($mixEvalDetail3[1]['EvaluationMixevalDetail']['question_comment'], null);
        // Run tests on invalid data
        $invalid1 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCriteria(232, 1);
        $invalid2 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCriteria(1, 1232);
        $invalid3 = $this->EvaluationMixevalDetail->getByEvalMixevalIdCriteria(null, 1);
        $this->assertTrue(empty($invalid1));
        $this->assertTrue(empty($invalid2));
        $this->assertTrue(empty($invalid3));
    }
}
