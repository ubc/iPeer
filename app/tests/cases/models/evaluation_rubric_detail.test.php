<?php
App::import('Model', 'EvaluationRubricDetail');

class EvaluationRubricDetailTestCase extends CakeTestCase
{
    public $name = 'EvaluationRubricDetail';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail'
    );
    public $Course = null;

    function startCase()
    {
        $this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');
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
        $this->assertTrue(is_a($this->EvaluationRubricDetail, 'EvaluationRubricDetail'));
    }

    function testGetByEvalRubricIdCritera()
    {
        // Run test on valid data
        $rubricEvalDetail1 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(2, 1);
        $rubricEvalDetail2 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(2, 2);
        $this->assertEqual($rubricEvalDetail1['EvaluationRubricDetail']['id'], 1);
        $this->assertEqual($rubricEvalDetail1['EvaluationRubricDetail']['criteria_number'], 1);
        $this->assertEqual($rubricEvalDetail1['EvaluationRubricDetail']['criteria_comment'], 'criteria comment1');
        $this->assertEqual($rubricEvalDetail2['EvaluationRubricDetail']['id'], 2);
        $this->assertEqual($rubricEvalDetail2['EvaluationRubricDetail']['criteria_number'], 2);
        $this->assertEqual($rubricEvalDetail2['EvaluationRubricDetail']['criteria_comment'], 'criteria comment2');
        // Run test on one valid input
        $rubricEvalDetail3 = $invalid3 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(2, null);
        $this->assertFalse($rubricEvalDetail3);
        // Run tests on invalid data
        $invalid1 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(232, 1);
        $invalid2 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(1, 1232);
        $invalid3 = $this->EvaluationRubricDetail->getByEvalRubricIdCritera(null, 1);
        $this->assertTrue(empty($invalid1));
        $this->assertTrue(empty($invalid2));
        $this->assertTrue(empty($invalid3));
    }


    function testGetAllByEvalRubricId()
    {

        $rubricEvalDetail  = $this->EvaluationRubricDetail->getAllByEvalRubricId(2);
        $this->assertTrue(!empty($rubricEvalDetail));
        $this->assertEqual($rubricEvalDetail[0]['EvaluationRubricDetail']['id'], 1);
        $this->assertEqual($rubricEvalDetail[1]['EvaluationRubricDetail']['id'], 2);
        $this->assertEqual($rubricEvalDetail[0]['EvaluationRubricDetail']['criteria_comment'], 'criteria comment1');
        $this->assertEqual($rubricEvalDetail[1]['EvaluationRubricDetail']['criteria_comment'], 'criteria comment2');
        // Run tests on invalid data
        $invalid1 = $this->EvaluationRubricDetail->getAllByEvalRubricId(232);
        $invalid2 = $this->EvaluationRubricDetail->getAllByEvalRubricId(null);
        $this->assertTrue(empty($invalid1));
        $this->assertTrue(empty($invalid2));
    }
}
