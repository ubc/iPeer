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
        'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor'
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

    function testFillQuestion()
    {
        $data = $this->SurveyQuestion->find('all', array('conditions'=> array('survey_id' => 1),
            'fields' => array('number', 'question_id', 'id'),
            'order' => 'number'));
        $ret = $this->Question->fillQuestion($data);
        // Assert reponse index didn't exist in the input prior to calling fillResponse
        $this->assertTrue(empty($ret[0]['Question']['Responses']['response_0']['response']));
        $this->assertTrue(empty($ret[1]['Question']['Responses']['response_0']['response']));
        $result = $this->Response->fillResponse($ret);
        // Assert that responses index are merged with array
        $this->assertEqual($result[0]['Question']['Responses']['response_0']['response'], '4+');
        $this->assertEqual($result[0]['Question']['Responses']['response_0']['id'], 1);
        $this->assertEqual($result[0]['Question']['Responses']['response_1']['response'], '3-4');
        $this->assertEqual($result[0]['Question']['Responses']['response_1']['id'], 2);
        $this->assertEqual($result[0]['Question']['Responses']['response_2']['response'], '2-3');
        $this->assertEqual($result[0]['Question']['Responses']['response_2']['id'], 3);
        $this->assertEqual($result[0]['Question']['Responses']['response_3']['response'], '< 2');
        $this->assertEqual($result[0]['Question']['Responses']['response_3']['id'], 4);
        $this->assertEqual($result[1]['Question']['Responses']['response_0']['response'], 'yes');
        $this->assertEqual($result[1]['Question']['Responses']['response_0']['id'], 5);
        $this->assertEqual($result[1]['Question']['Responses']['response_1']['response'], 'no');
        $this->assertEqual($result[1]['Question']['Responses']['response_1']['id'], 6);
    }

    function testGetResponseByQuestionId()
    {
        // Assert that response index are merged with array
        $question1 = $this->Response->getResponseByQuestionId(1);
        $question2 = $this->Response->getResponseByQuestionId(2);
        $this->assertEqual($question1['Responses']['response_0']['response'], '4+');
        $this->assertEqual($question1['Responses']['response_1']['response'], '3-4');
        $this->assertEqual($question1['Responses']['response_2']['response'], '2-3');
        $this->assertEqual($question1['Responses']['response_3']['response'], '< 2');
        $this->assertEqual($question2['Responses']['response_0']['response'], 'yes');
        $this->assertEqual($question2['Responses']['response_1']['response'], 'no');
    }

    function testGetResponseId()
    {
        $respose1 = $this->Response->getResponseId(1, '4+');
        $respose2 = $this->Response->getResponseId(1, '3-4');
        $respose3 = $this->Response->getResponseId(1, '2-3');
        $respose4 = $this->Response->getResponseId(1, '< 2');
        $respose5 = $this->Response->getResponseId(2, 'yes');
        $respose6 = $this->Response->getResponseId(2, 'no');
        $this->assertEqual($respose1, 1);
        $this->assertEqual($respose2, 2);
        $this->assertEqual($respose3, 3);
        $this->assertEqual($respose4, 4);
        $this->assertEqual($respose5, 5);
        $this->assertEqual($respose6, 6);
    }

}
