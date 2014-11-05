<?php
App::import('Model', 'EvaluationRubric');

class EvaluationRubricTestCase extends CakeTestCase
{
    public $name = 'EvaluationRubric';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.faculty', 'app.user_faculty', 'app.user_tutor', 'app.department',
        'app.course_department', 'app.penalty',
        'app.evaluation_simple', 'app.survey_input', 'app.oauth_token',
        'app.sys_parameter', 'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
    );
    public $EvaluationRubric = null;

    function startCase()
    {
        echo "Start EvaluationRubric model test.\n";
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
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


    //getEvalRubricByGrpEventIdEvaluatorEvaluatee($grpEventId=null, $evaluator=null, $evaluatee=null)
    function testGetEvalRubricByGrpEventIdEvaluatorEvaluatee()
    {
        // Set up test data
        $ret = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(4, 31, 32);
        // Assert the queried data is correct
        $this->assertTrue(!empty($ret));

        $this->assertEqual($ret['EvaluationRubric']['comment'], 'We work well together.');
        $this->assertEqual($ret['EvaluationRubric']['score'], 15.00);
        $this->assertEqual($ret['EvaluationRubric']['event_id'], 2);
        // Test invalid inputs
        $invalidInputs = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(999, 3, 4);
        $invalidInputs1 = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(1, 999, 4);
        $invalidInputs2 = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(1, 1, 999);
        $nullInputs = $this->EvaluationRubric->getEvalRubricByGrpEventIdEvaluatorEvaluatee(null, null, null);
        $this->assertTrue(empty($invalidInputs));
        $this->assertTrue(empty($invalidInputs1));
        $this->assertTrue(empty($invalidInputs2));
        $this->assertTrue(empty($nullInputs));
    }

    function testGetResultsByEvaluatee()
    {
        // Set up test data
        $ret = $this->EvaluationRubric->getResultsByEvaluatee(4, 33);
        // Assert the queried data matches and is correctly ordered according to the fixture data
        $this->assertTrue(!empty($ret[0]));
        $this->assertEqual($ret[0]['EvaluationRubric']['comment'], 'He did a great job.');
        $this->assertEqual($ret[0]['EvaluationRubric']['score'], 14.00);
        // Test invalid inputs
        $invalidInputs = $this->EvaluationRubric->getResultsByEvaluatee(1, 999);
        $invalidInputs1 = $this->EvaluationRubric->getResultsByEvaluatee(999, 1);
        $nullInput = $this->EvaluationRubric->getResultsByEvaluatee(null, null);
        $this->assertTrue(empty($invalidInputs));
        $this->assertTrue(empty($invalidInputs1));
        $this->assertTrue(empty($nullInputs));
    }

    function testGetResultsDetailByEvaluatee()
    {
        // Set up test data
        $ret = $this->EvaluationRubric->getResultsDetailByEvaluatee(3, 5);
        // Assert the queried data matches with fixture data
        $this->assertTrue(!empty($ret));
        $this->assertTrue(!empty($ret[0]['EvaluationRubricDetail']));
        $this->assertTrue(!empty($ret[1]['EvaluationRubricDetail']));
        $this->assertTrue(!empty($ret[2]['EvaluationRubricDetail']));
        $evalRubricDetail1 = $ret[0]['EvaluationRubricDetail']['criteria_comment'];
        $evalRubricDetail2 = $ret[1]['EvaluationRubricDetail']['criteria_comment'];
        $evalRubricDetail3 = $ret[2]['EvaluationRubricDetail']['criteria_comment'];
        $this->assertEqual($evalRubricDetail1, 'Yes');
        $this->assertEqual($evalRubricDetail2, 'Absolutely');
        $this->assertEqual($evalRubricDetail3, 'Definitely');
    }

    function testGetResultsDetailByEvaluator()
    {
        // Set up test data
        $ret = $this->EvaluationRubric->getResultsDetailByEvaluator(3, 7);
        // Assert the queried data matches with fixture data
        $this->assertTrue(!empty($ret));
        $this->assertTrue(!empty($ret[0]['EvaluationRubricDetail']));
        $this->assertTrue(!empty($ret[1]['EvaluationRubricDetail']));
        $this->assertTrue(!empty($ret[2]['EvaluationRubricDetail']));
        $this->assertTrue(!empty($ret[3]['EvaluationRubricDetail']));
        $this->assertTrue(!empty($ret[4]['EvaluationRubricDetail']));
        $this->assertTrue(!empty($ret[5]['EvaluationRubricDetail']));
        $comment1 = $ret[0]['EvaluationRubricDetail']['criteria_comment'];
        $comment2 = $ret[1]['EvaluationRubricDetail']['criteria_comment'];
        $comment3 = $ret[2]['EvaluationRubricDetail']['criteria_comment'];
        $comment4 = $ret[3]['EvaluationRubricDetail']['criteria_comment'];
        $comment5 = $ret[4]['EvaluationRubricDetail']['criteria_comment'];
        $comment6 = $ret[5]['EvaluationRubricDetail']['criteria_comment'];

        // Note that the returned comments are not sorted in any particular
        // order and must be tested in an order-agnostic way.
        $actual = array($comment1, $comment2, $comment3, $comment4,
            $comment5, $comment6);
        $expected = array('Yes', 'Yes', 'Absolutely', 'Definitely',
            'attended all of our team meetings',
            'very helpful in all parts of the project'
        );

        $this->assertFalse(array_diff($expected, $actual));
        // For dealing with the two 'Yes' entries, since array_diff will
        // remove both of them even if only one 'Yes' is present
        $this->assertEqual(count($actual), count($expected));

    }


    function testGetCriteriaResults()
    {
        $ret = $this->EvaluationRubric->getCriteriaResults(4, 32);
        $this->assertEqual($ret[1], 5);
        $this->assertEqual($ret[2], 5);
        $this->assertEqual($ret[3], 5);
    }

    function testGetReceivedTotalScore()
    {
        // Set up test data
        $ret = $this->EvaluationRubric->getReceivedTotalScore(4, 33);
        // Assert the queried data matches with fixture data
        $this->assertTrue(!empty($ret));
        $this->assertEqual($ret[0][0]['received_total_score'], 14.00);
    }

    function testGetAllComments()
    {
        $ret = $this->EvaluationRubric->getAllComments(3, 5);
        $this->assertEqual($ret[0]['EvaluationRubric']['comment'], 'Good group member.');
    }

    function testGetReceivedTotalEvaluatorCount()
    {
        // Set up test data
        $grpEvent1 = $this->EvaluationRubric->getReceivedTotalEvaluatorCount(3, 5);
        // Assert the queried data matches with fixture data
        $this->assertTrue(!empty($grpEvent1));
        $this->assertEqual($grpEvent1, 1);
        $grpEvent2 = $this->EvaluationRubric->getReceivedTotalEvaluatorCount(3, 6);
        $this->assertTrue(!empty($grpEvent2));
        $this->assertEqual($grpEvent2, 1);
    }

    function testGetOppositeGradeReleaseStatus()
    {
        $ret = $this->EvaluationRubric->getOppositeGradeReleaseStatus(4, 0);
        $this->assertEqual($ret, 1);
        $ret = $this->EvaluationRubric->getOppositeGradeReleaseStatus(4, 1);
        $this->assertEqual($ret, 1);
        $ret = $this->EvaluationRubric->getOppositeGradeReleaseStatus(3, 0);
        $this->assertEqual($ret, 0);
    }

    function testGetOppositeCommentReleaseStatus()
    {

        $ret = $this->EvaluationRubric->getOppositeCommentReleaseStatus(3, 0);
        $this->assertEqual($ret, 0);
        $ret = $this->EvaluationRubric->getOppositeCommentReleaseStatus(3, 1);
        $this->assertEqual($ret, 2);
        $ret = $this->EvaluationRubric->getOppositeCommentReleaseStatus(4, 0);
        $this->assertEqual($ret, 1);
    }

    function testGetTeamReleaseStatus()
    {

        $ret = $this->EvaluationRubric->getTeamReleaseStatus(3);
        $this->assertEqual($ret[0]['EvaluationRubric']['comment_release'], 0);
        $this->assertEqual($ret[0]['EvaluationRubric']['grade_release'], 0);
        $this->assertEqual($ret[1]['EvaluationRubric']['comment_release'], 0);
        $this->assertEqual($ret[1]['EvaluationRubric']['grade_release'], 0);

        $result = $this->EvaluationRubric->getTeamReleaseStatus(999);
        $this->assertFalse($result);
    }

    function testSetAllEventGradeRelease()
    {

        $this->EvaluationRubric->setAllEventGradeRelease(2, 1);
        $result = $this->EvaluationRubric->getTeamReleaseStatus(4);
        $this->assertEqual($result[1]['EvaluationRubric']['grade_release'], 1);

        $this->EvaluationRubric->setAllEventGradeRelease(999, 1);
        $result = $this->EvaluationRubric->getTeamReleaseStatus(999);
        $this->assertFalse($result);

    }
}
