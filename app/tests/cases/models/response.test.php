<?php
App::import('Model', 'Response');

class ResponseTestCase extends CakeTestCase
{
    public $name = 'Response';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question', 'app.survey',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment'
    );
    public $Response = null;

    function startCase()
    {
        $this->Response = ClassRegistry::init('Response');
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

    function testResponseInstance()
    {
        $this->assertTrue(is_a($this->Response, 'Response'));
    }

    function testFillQuestion()
    {
        $input = $this->setupTestInput();
        // Assert reponse index didn't exist in the input prior to calling fillResponse
        $this->assertTrue(empty($input[0]['Question']['Responses']['response_0']['response']));
        $this->assertTrue(empty($input[1]['Question']['Responses']['response_0']['response']));
        $this->assertTrue(empty($input[2]['Question']['Responses']['response_0']['response']));
        $result = $this->Response->fillResponse($input);
        // Assert that responses index are merged with array
        $this->assertEqual($result[0]['Question']['Responses']['response_0']['response'], 'YES FOR Q1');
        $this->assertEqual($result[0]['Question']['Responses']['response_0']['id'], 1);
        $this->assertEqual($result[1]['Question']['Responses']['response_0']['response'], 'NO FOR Q2');
        $this->assertEqual($result[1]['Question']['Responses']['response_0']['id'], 2);
        $this->assertEqual($result[2]['Question']['Responses']['response_0']['response'], 'YES FOR Q3');
        $this->assertEqual($result[2]['Question']['Responses']['response_0']['id'], 3);
    }

    function testGetResponseByQuestionId()
    {
        // Assert that response index are merged with array
        $question1 = $this->Response->getResponseByQuestionId(1);
        $question2 = $this->Response->getResponseByQuestionId(2);
        $question6 = $this->Response->getResponseByQuestionId(6);
        $question4 = $this->Response->getResponseByQuestionId(4);
        $this->assertEqual($question1['Responses']['response_0']['response'], 'YES FOR Q1');
        $this->assertEqual($question2['Responses']['response_0']['response'], 'NO FOR Q2');
        $this->assertEqual($question6['Responses']['response_0']['response'], 'YES FOR Q3');
        $this->assertEqual($question4['Responses']['response_0']['response'], 'YES FOR Q4');
    }

    function testCountResponses()
    {
        // Assert the correct question count
        $Q1ResponseCount = $this->Response->countResponses(1);
        $Q2ResponseCount = $this->Response->countResponses(2);
        $Q5ResponseCount = $this->Response->countResponses(5);
        $this->assertEqual($Q1ResponseCount, 2);
        $this->assertEqual($Q2ResponseCount, 1);
        $this->assertEqual($Q5ResponseCount, 0);
    }

    function testGetResponseId()
    {
        $respose1 = $this->Response->getResponseId(1, 'YES FOR Q1');
        $respose2 = $this->Response->getResponseId(2, 'NO FOR Q2');
        $this->assertEqual($respose1, 1);
        $this->assertEqual($respose2, 2);
    }

    function setupTestInput()
    {
        $data = $this->SurveyQuestion->find('all', array('conditions'=> array('survey_id' => 1),
            'fields' => array('number', 'question_id', 'id'),
            'order' => 'number'));
        $data['count'] = count($data);
        $result = $this->Question->fillQuestion($data);
        return $result;
    }
}
