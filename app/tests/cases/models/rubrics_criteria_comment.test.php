<?php
App::import('Model', 'Rubric');
App::import('Model', 'RubricsCriteriaComment');

class RubricsCriteriaCommentTestCase extends CakeTestCase
{
    public $name = 'RubricsCriteriaComment';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group', 'app.survey',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input',
        'app.oauth_token', 'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail'
    );
    public $RubricsCriteriaComment = null;

    function startCase()
    {
        echo "Start RubricsCriteriaComment model test.\n";
        $this->RubricsCriteriaComment = ClassRegistry::init('RubricsCriteriaComment');
        $this->Rubric = ClassRegistry::init('Rubric');
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

    function testGetCriteriaComment()
    {
        // Set up test data by querying from fixture
        $rubric = $this->Rubric->find('first', array('conditions' => array('Rubric.id' => 1)));
        $result = $this->RubricsCriteriaComment->getCriteriaComment($rubric);
        // Assert that the query was successful
        $this->assertTrue(!empty($result));
        $this->assertNotNull($result);
        // Assert the queried result matches with the fixture data
        $this->assertEqual($result['criteria_comment_1_1'], 'No participation.');
        $this->assertEqual($result['criteria_comment_1_2'], 'Little participation.');
        $this->assertEqual($result['criteria_comment_1_3'], 'Some participation.');
        $this->assertEqual($result['criteria_comment_1_4'], 'Good participation.');
        $this->assertEqual($result['criteria_comment_1_5'], 'Great participation.');
        $this->assertEqual($result['criteria_comment_2_1'], '');
        $this->assertEqual($result['criteria_comment_2_5'], '');
        $this->assertEqual($result['criteria_comment_3_1'], '');
        $this->assertEqual($result['criteria_comment_3_5'], '');
    }

    function testDeleteCriteriaComments()
    {
        // Assert data was initially in fixtures
        $criteria1 = $this->RubricsCriteriaComment->find('all', array(
            'conditions' => array('criteria_id' => 1)));
        $criteria2 = $this->RubricsCriteriaComment->find('all', array(
            'conditions' => array('criteria_id' => 2)));
        $this->assertTrue(!empty($criteria1));
        $this->assertTrue(!empty($criteria2));
        // Set up test data
        $this->RubricsCriteriaComment->deleteCriteriaComments(1);
        $criteria1 = $this->RubricsCriteriaComment->find('all', array(
            'conditions' => array('criteria_id' => 1)));
        $criteria2 = $this->RubricsCriteriaComment->find('all', array(
            'conditions' => array('criteria_id' => 2)));
        // Assert the queried tuples have been deleted
        $this->assertTrue(empty($criteria1));
        $this->assertTrue(empty($criteria2));
    }
}
