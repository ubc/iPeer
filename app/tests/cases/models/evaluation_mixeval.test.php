<?php
App::import('Model', 'EvaluationMixeval');

class EvaluationMixevalTestCase extends CakeTestCase
{
    public $name = 'EvaluationMixeval';
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
        echo "Start EvaluationMixeval model test.\n";
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
    }

    function endCase()
    {
    }

    function startTest($method)
    {
    }

    function endTest($method)
    {
    }

    function testCourseInstance()
    {
        $this->assertTrue(is_a($this->EvaluationMixeval, 'EvaluationMixeval'));
    }

    function testGetEvalMixevalByGrpEventIdEvaluatorEvaluatee()
    {
        $result = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(5, 7, 5);
        // Assert the queried data is correct
        $this->assertTrue(!empty($result));
        $this->assertEqual($result['EvaluationMixeval']['grp_event_id'], 5);
        $this->assertEqual($result['EvaluationMixeval']['evaluator'], 7);
        $this->assertEqual($result['EvaluationMixeval']['evaluatee'], 5);
        // Test invalid inputs
        $invalidInputs = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(223, 1, 1);
        $invalidInputs1 = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(2, 1231, 1);
        $invalidInputs2 = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(2, 1, 1231);
        $nullInputs = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(null, null, null);
        $this->assertTrue(empty($invalidInputs));
        $this->assertTrue(empty($invalidInputs1));
        $this->assertTrue(empty($invalidInputs2));
        $this->assertTrue(empty($nullInputs));
    }

    function testGetResultsByEvaluatee()
    {
        $result = $this->EvaluationMixeval->getResultsByEvaluatee(5, 6, array(5, 7));
        // Assert the queried data matches and is correctly ordered according to the fixture data
        $this->assertTrue(!empty($result[0]));
        $this->assertTrue(!empty($result[1]));
        $this->assertEqual($result[0]['EvaluationMixeval']['grp_event_id'], 5);
        $this->assertEqual($result[0]['EvaluationMixeval']['score'], 2.40);
        $this->assertEqual($result[1]['EvaluationMixeval']['grp_event_id'], 5);
        $this->assertEqual($result[1]['EvaluationMixeval']['score'], 2.40);
        // Test invalid inputs
        $invalidInputs = $this->EvaluationMixeval->getResultsByEvaluatee(2, 323, array(323));
        $invalidInputs1 = $this->EvaluationMixeval->getResultsByEvaluatee(232, 1, array(1));
        $nullInput = $this->EvaluationMixeval->getResultsByEvaluatee(null, null, null);
        $this->assertTrue(empty($invalidInputs));
        $this->assertTrue(empty($invalidInputs1));
        $this->assertTrue(empty($nullInputs));
    }

    function testGetResultsDetailByEvaluatee()
    {
        // Set up test data
        $result = $this->EvaluationMixeval->getResultsDetailByEvaluatee(5, 5);
        // Assert the queried data matches with fixture data
        $this->assertTrue(!empty($result));
        $this->assertTrue(!empty($result[3]['EvaluationMixevalDetail']));
        $this->assertTrue(!empty($result[4]['EvaluationMixevalDetail']));
        $evalMixDetail1 = $result[3]['EvaluationMixevalDetail']['question_comment'];
        $evalMixDetail2 = $result[4]['EvaluationMixevalDetail']['question_comment'];
        $this->assertEqual($evalMixDetail1, 'work very efficiently');
        $this->assertEqual($evalMixDetail2, 'Contributed his part');
    }

    function testGetResultsDetailByEvaluator()
    {
        // Set up test data
        $result = $this->EvaluationMixeval->getResultsDetailByEvaluator(5, 7);
        // Assert the queried data matches with fixture data
        $this->assertTrue(!empty($result));
        $this->assertTrue(!empty($result[3]['EvaluationMixevalDetail']));
        $this->assertTrue(!empty($result[4]['EvaluationMixevalDetail']));
        $evalMixDetail1 = $result[3]['EvaluationMixevalDetail']['question_comment'];
        $evalMixDetail2 = $result[4]['EvaluationMixevalDetail']['question_comment'];
        $this->assertEqual($evalMixDetail1, 'work very efficiently');
        $this->assertEqual($evalMixDetail2, 'Contributed his part');
    }

    function testGetReceivedTotalScore()
    {
        // Set up test data
        $result = $this->EvaluationMixeval->getReceivedTotalScore(5, 6);
        // Assert the queried data matches with fixture data
        $this->assertTrue(!empty($result));
        $this->assertEqual($result[0]['received_total_score'], 2.4);
    }

    function testGetReceivedTotalEvaluatorCount()
    {
        // Set up test data
        $grpEvent2 = $this->EvaluationMixeval->getReceivedTotalEvaluatorCount(5, 6);
        // Assert the queried data matches with fixture data
        $this->assertTrue(!empty($grpEvent2));
        $this->assertEqual($grpEvent2, 2);
        $grpEvent1 = $this->EvaluationMixeval->getReceivedTotalEvaluatorCount(5, 5);
        $this->assertTrue(!empty($grpEvent1));
        $this->assertEqual($grpEvent1, 1);
    }

    function testGetOppositeGradeReleaseStatus()
    {
        // check count of grades released
        $ret = $this->EvaluationMixeval->getOppositeGradeReleaseStatus(5, 0);
        $this->assertEqual($ret, 0);
        // check count of grades not released
        $ret = $this->EvaluationMixeval->getOppositeGradeReleaseStatus(5, 1);
        $this->assertEqual($ret, 3);
    }

    function testGetOppositeCommentReleaseStatus()
    {
        // check count of comments released
        $ret = $this->EvaluationMixeval->getOppositeCommentReleaseStatus(5, 0);
        $this->assertEqual($ret, 0);
        // check count of comments not released
        $ret = $this->EvaluationMixeval->getOppositeCommentReleaseStatus(5, 1);
        $this->assertEqual($ret, 3);
    }

    function testSetAllEventGradeRelease()
    {
        // Set up test data
        $this->EvaluationMixeval->setAllEventGradeRelease(3, 1);
        // Assert EvaluationMixeval.event_id is updated
        $searched = $this->EvaluationMixeval->find('all', array('conditions' => array('event_id' => 3)));
        $this->assertTrue(!empty($searched));
        $this->assertEqual($searched[0]['EvaluationMixeval']['grade_release'], 1);
        $this->assertEqual($searched[1]['EvaluationMixeval']['grade_release'], 1);
        $this->assertEqual($searched[2]['EvaluationMixeval']['grade_release'], 1);

        // Revert grade release back to 0, and test again
        $this->EvaluationMixeval->setAllEventGradeRelease(3, 0);
        $searched = $this->EvaluationMixeval->find('all', array('conditions' => array('event_id' => 3)));
        $this->assertTrue(!empty($searched));
        $this->assertEqual($searched[0]['EvaluationMixeval']['grade_release'], 0);
        $this->assertEqual($searched[1]['EvaluationMixeval']['grade_release'], 0);
        $this->assertEqual($searched[2]['EvaluationMixeval']['grade_release'], 0);
    }
    
    function testGetResultsByEvaluateesAndEvaluators()
    {
        // TODO
    }
}
