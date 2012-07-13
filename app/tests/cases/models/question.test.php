<?php
App::import('Model', 'Question');

class QuestionTestCase extends CakeTestCase
{
    public $name = 'Question';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question', 'app.survey',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment', 'app.faculty',
        'app.user_faculty', 'app.department', 'app.course_department',
        'app.user_tutor', 'app.sys_parameter'
    );
    public $Question = null;

    function startCase()
    {
        $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
        $this->Question = ClassRegistry::init('Question');
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

    function testFillQuestion()
    {
        $data = $this->SurveyQuestion->find('all', array('conditions'=> array('survey_id' => 1),
            'fields' => array('number', 'question_id', 'id'),
            'order' => 'number'));
        $ret = $this->Question->fillQuestion($data);
        // Compare the result with fixture data
        $firstQuestion = $ret[0]['Question'];
        $this->assertEqual($firstQuestion['prompt'], 'What was your GPA last term?');
        $this->assertEqual($firstQuestion['id'], 1);
        $this->assertEqual($firstQuestion['number'], 1);
        $secondQuestion = $ret[1]['Question'];
        $this->assertEqual($secondQuestion['prompt'], 'Do you own a laptop?');
        $this->assertEqual($secondQuestion['id'], 2);
        $this->assertEqual($secondQuestion['number'], 2);
        // Check that Survey Question has been unset
        $this->assertFalse(isset($firstQuestion['SurveyQuestion']));
        $this->assertFalse(isset($secondQuestion['SurveyQuestion']));
    }

    function testGetTypeById()
    {
        // Set up test data
        $Q1Type = $this->Question->getTypeById(1);
        $Q2Type = $this->Question->getTypeById(2);
        // Compare with fixture data
        $this->assertEqual($Q1Type, 'M');
        $this->assertEqual($Q2Type, 'M');
        // Check for faulty inputs
        $faultyId = $this->Question->getTypeById(332);
        $nullId = $this->Question->getTypeById(null);

        $this->assertNull($faultyId);
        $this->assertNull($nullId);
    }

}
