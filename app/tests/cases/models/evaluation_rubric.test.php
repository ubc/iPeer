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
        'app.evaluation_rubric', 'app.evaluation_rubric_detail'
    );
    public $EvaluationRubric = null;

    function startCase()
    {
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

        $this->assertEqual($ret['EvaluationRubric']['general_comment'], 'We work well together.');
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
        $this->assertEqual($ret[0]['EvaluationRubric']['general_comment'], 'He did a great job.');
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
        $evalMixDetail1 = $ret[0]['EvaluationRubricDetail']['criteria_comment'];
        $evalMixDetail2 = $ret[1]['EvaluationRubricDetail']['criteria_comment'];
        $evalMixDetail3 = $ret[2]['EvaluationRubricDetail']['criteria_comment'];
        $evalMixDetail4 = $ret[3]['EvaluationRubricDetail']['criteria_comment'];
        $evalMixDetail5 = $ret[4]['EvaluationRubricDetail']['criteria_comment'];
        $evalMixDetail6 = $ret[5]['EvaluationRubricDetail']['criteria_comment'];
        $this->assertEqual($evalMixDetail1, 'Yes');
        $this->assertEqual($evalMixDetail2, 'attended all of our team meetings');
        $this->assertEqual($evalMixDetail3, 'Absolutely');
        $this->assertEqual($evalMixDetail4, 'very helpful in all parts of the project');
        $this->assertEqual($evalMixDetail5, 'Yes');
        $this->assertEqual($evalMixDetail6, 'Definitely');
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
        $this->assertEqual($ret[0]['EvaluationRubric']['general_comment'], 'Good group member.');
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
        $this->assertEqual($ret, 0);
        $ret = $this->EvaluationRubric->getOppositeGradeReleaseStatus(4, 1);
        $this->assertEqual($ret, 2);
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
        $this->assertEqual($ret, 0);
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

    function testSetAllEventCommentRelease()
    {

        $this->EvaluationRubric->setAllEventCommentRelease(2, 1);
        $result = $this->EvaluationRubric->getTeamReleaseStatus(3);
        $this->assertEqual($result[1]['EvaluationRubric']['comment_release'], 1);

        $this->EvaluationRubric->setAllEventCommentRelease(999, 1);
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
