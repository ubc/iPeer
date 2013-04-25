<?php
App::import('Model', 'Personalize');

class PersonalizeTestCase extends CakeTestCase
{
    public $name = 'Personalize';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey', 'app.personalize',
        'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input',
        'app.oauth_token', 'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
    );
    public $Course = null;

    function startCase()
    {
        echo "Start Personalize model test.\n";
        $this->Personalize = ClassRegistry::init('Personalize');
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

    function testUpdateAttribute()
    {
        $this->Personalize->updateAttribute(1, 'code', 'newValue');
        $data = $this->Personalize->find('first', array(
            'conditions' => array('user_id' => 1, 'attribute_code' => 'code')
        ));

        $this->assertEqual($data['Personalize']['user_id'], 1);
        $this->assertEqual($data['Personalize']['attribute_code'], 'code');
        $this->assertEqual($data['Personalize']['attribute_value'], 'newValue');

        $this->Personalize->updateAttribute(1, 'invalidCode', 'null');
        $data = $this->Personalize->find('first', array(
            'conditions' => array('user_id' => 1, 'attribute_code' => 'invalidCode')
        ));

        $this->assertEqual($data['Personalize']['user_id'], 1);

        // invalid user id
        $this->Personalize->updateAttribute(999, 'Code', 'value');
        $data = $this->Personalize->find('first', array(
            'conditions' => array('user_id' => 999, 'attribute_code' => 'Code')
        ));
        $this->assertEqual($data, null);
    }
}
