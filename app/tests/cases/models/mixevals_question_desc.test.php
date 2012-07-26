<?php
App::import('Model', 'MixevalsQuestionDesc');

class MixevalsQuestionDescTestCase extends CakeTestCase
{
    public $name = 'MixevalsQuestionDesc';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.mixeval', 'app.mixevals_question',
        'app.mixevals_question_desc'
    );
    public $MixevalsQuestionDesc = null;

    function startCase()
    {
        echo "Start MixevalsQuestionDesc model test.\n";
        $this->MixevalsQuestionDesc = ClassRegistry::init('MixevalsQuestionDesc');
        $this->MixevalsQuestion = ClassRegistry::init('MixevalsQuestion');
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

    function testMixevalsQuestionDescInstance()
    {
        $this->assertTrue(is_a($this->MixevalsQuestionDesc, 'MixevalsQuestionDesc'));
    }

    function testInsertQuestionDescriptor()
    {
        // Set up test inputs
        $question_ids = $this->MixevalsQuestion->find('all', array('conditions' => array('mixeval_id'=> 1), 'fields' => array('MixevalsQuestion.id, question_num')));
        $data = $this->setUpTestData();
        // Set up test
        $this->MixevalsQuestionDesc->insertQuestionDescriptor($data, $question_ids);
        // Assert Question Descriptors have been successfully added to MixevalsQuestionDesc
        $searched1 = $this->MixevalsQuestionDesc->find('all', array('conditions' => array('descriptor' => 'ONLY ENTRY 1')));
        $searched2 = $this->MixevalsQuestionDesc->find('all', array('conditions' => array('descriptor' => 'ONLY ENTRY 2')));
        $this->assertEqual($searched1[0]['MixevalsQuestionDesc']['descriptor'], 'ONLY ENTRY 1');
        $this->assertEqual($searched2[0]['MixevalsQuestionDesc']['descriptor'], 'ONLY ENTRY 2');
    }

    function testGetQuestionDescriptor()
    {
        // Data comes from fixture tables
        $result = $this->MixevalsQuestionDesc->getQuestionDescriptor(1);
        $this->assertEqual($result[0]['MixevalsQuestionDesc']['id'], 1);
        $this->assertEqual($result[0]['MixevalsQuestionDesc']['descriptor'], 
            'Lowest');
        $this->assertEqual($result[2]['MixevalsQuestionDesc']['id'], 3);
        $this->assertEqual($result[2]['MixevalsQuestionDesc']['descriptor'], 
            'Middle');
    }

    function setUpTestData()
    {
        $tmp = array(
            '0' => array(
                'id' => 1, 
                'question_type' => 'S',
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
