<?php
App::import('Model', 'EvaluationSubmission');

class EvaluationSubmissionTestCase extends CakeTestCase
{
    public $name = 'EvaluationSimple';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.evaluation_simple', 'app.user_faculty', 'app.course_department',
        'app.department', 'app.faculty', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.survey_input', 'app.oauth_token', 'app.evaluation_rubric',
        'app.evaluation_rubric_detail', 'app.evaluation_mixeval',
        'app.evaluation_mixeval_detail'
    );
    public $Course = null;

    function startCase()
    {
        echo "Start EvaluationSubmission model test.\n";
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
    }

    function endCase()
    {
    }

    function startTest($method)
    {
        echo $method."\n";
    }

    function endTest($method)
    {
    }

    function testGetEvalSubmissionByGrpEventIdSubmitter()
    {
        // valid params
        $result = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(1, 7);
        $this->assertEqual($result['EvaluationSubmission']['id'], 1);
        $this->assertEqual($result['EvaluationSubmission']['grp_event_id'], 1);
        $this->assertEqual($result['EvaluationSubmission']['submitter_id'], 7);

        // invalid params
        $result = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(null, 3);
        $this->assertFalse($result);
        $result = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter(1, null);
        $this->assertFalse($result);
    }

    function testGetEvalSubmissionByEventIdSubmitter()
    {
        // valid params
        $result = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(3, 7);
        $this->assertEqual($result['EvaluationSubmission']['id'], 3);
        $this->assertEqual($result['EvaluationSubmission']['event_id'], 3);
        $this->assertEqual($result['EvaluationSubmission']['submitter_id'], 7);

        // invalid params
        $result = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(null, 3);
        $this->assertFalse($result);
        $result = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(2, null);
        $this->assertFalse($result);
    }

    function testGetEvalSubmissionByEventIdGroupSubmitter()
    {
        // valid params
        $result = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter(6, 1, 5);
        $this->assertEqual($result['EvaluationSubmission']['id'], 10);
        $this->assertEqual($result['EvaluationSubmission']['event_id'], 6);
        $this->assertEqual($result['EvaluationSubmission']['submitter_id'], 5);
        $this->assertEqual($result['EvaluationSubmission']['grp_event_id'], 7);

        // invalid params
        $result = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter(null, null, 3);
        $this->assertFalse($result);
        $result = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter(2, null, null);
        $this->assertFalse($result);
    }

    function testNumCountInGroupCompleted()
    {
        $result = $this->EvaluationSubmission->numCountInGroupCompleted(2, 1);
        $this->assertEqual($result, 1);

        $result = $this->EvaluationSubmission->numCountInGroupCompleted(100);
        $this->assertFalse($result);
    }

    function testDaysLate()
    {
        $result = $this->EvaluationSubmission->daysLate(1, date('Y', strtotime("+1 year")).'-07-03 00:00:01');
        $this->assertEqual($result, 1);
    }

}
