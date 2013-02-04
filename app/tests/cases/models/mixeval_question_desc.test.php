<?php
App::import('Model', 'MixevalQuestionDesc');

class MixevalQuestionDescTestCase extends CakeTestCase
{
    public $name = 'MixevalQuestionDesc';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission', 'app.oauth_token',
        'app.survey_group_set', 'app.survey_group', 'app.sys_parameter',
        'app.survey_group_member', 'app.question', 'app.evaluation_simple',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.mixeval', 
        'app.mixeval_question', 'app.user_faculty', 'app.user_tutor',
        'app.mixeval_question_desc', 'app.survey_input', 'app.faculty', 
        'app.department', 'app.course_department', 'app.penalty',
        'app.mixeval_question_type'
    );
    public $MixevalQuestionDesc = null;

    function startCase()
    {
        echo "Start MixevalQuestionDesc model test.\n";
        $this->MixevalQuestionDesc = ClassRegistry::init('MixevalQuestionDesc');
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
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

    function testMixevalQuestionDescInstance()
    {
        $this->assertTrue(is_a($this->MixevalQuestionDesc, 'MixevalQuestionDesc'));
    }

    function testInsertQuestionDescriptor()
    {
        // Set up test inputs
        $question_ids = $this->MixevalQuestion->find('all', array('conditions' => array('mixeval_id'=> 1), 'fields' => array('MixevalQuestion.id, question_num')));
        $data = $this->setUpTestData();
        // Set up test
        $this->MixevalQuestionDesc->insertQuestionDescriptor($data, $question_ids);
        // Assert Question Descriptors have been successfully added to MixevalQuestionDesc
        $searched1 = $this->MixevalQuestionDesc->find('all', array('conditions' => array('descriptor' => 'ONLY ENTRY 1')));
        $searched2 = $this->MixevalQuestionDesc->find('all', array('conditions' => array('descriptor' => 'ONLY ENTRY 2')));
        $this->assertEqual($searched1[0]['MixevalQuestionDesc']['descriptor'], 'ONLY ENTRY 1');
        $this->assertEqual($searched2[0]['MixevalQuestionDesc']['descriptor'], 'ONLY ENTRY 2');
    }

    function testGetQuestionDescriptor()
    {
        // Data comes from fixture tables
        $result = $this->MixevalQuestionDesc->getQuestionDescriptor(1);
        $this->assertEqual($result[0]['MixevalQuestionDesc']['id'], 1);
        $this->assertEqual($result[0]['MixevalQuestionDesc']['descriptor'],
            'Lowest');
        $this->assertEqual($result[2]['MixevalQuestionDesc']['id'], 3);
        $this->assertEqual($result[2]['MixevalQuestionDesc']['descriptor'],
            'Middle');
    }

    function setUpTestData()
    {
        $tmp = array(
            '0' => array(
                'id' => 1,
                'mixeval_question_type_id' => '1',
                'question_num' => 1,
                'title' => 'Participated in Team Meetings',
                'multiplier' => 1,
                'Description' => array(
                    '0' => array(
                        'id' => '',
                        'descriptor' => 'ONLY ENTRY 1'
                    ),
                    '1' => array(
                        'id' => '',
                        'descriptor' => 'ONLY ENTRY 2'
                    )
                )
            )
        );
        return $tmp;
    }
}
