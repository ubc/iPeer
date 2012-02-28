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
        'app.user_enrol', 'app.groups_member', 'app.survey', 'app.personalize'
    );
    public $Course = null;

    function startCase()
    {
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
        $this->flushDatabase();
    }

    function testPersonalizeInstance()
    {
        $this->assertTrue(is_a($this->Personalize, 'Personalize'));
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

        $this->assertEqual($data['Personalize']['user_id'], null);

        $this->Personalize->updateAttribute(999, 'Code', 'null');
        $data = $this->Personalize->find('first', array(
            'conditions' => array('user_id' => 999, 'attribute_code' => 'Code')
        ));
        $this->assertEqual($data['Personalize']['user_id'], null);
    }


    ##########################################################################################################
    ##################   HELPER FUNCTION USED FOR UNIT TESTING PURPOSES   ####################################
    ##########################################################################################################


    function deleteAllTuples($table)
    {
        $this->Personalize= & ClassRegistry::init('Personalize');
        $sql = "DELETE FROM $table";
        $this->Personalize->query($sql);
    }

    function flushDatabase()
    {
        $this->deleteAllTuples('personalizes');
        $this->deleteAllTuples('courses');
        $this->deleteAllTuples('users');
        $this->deleteAllTuples('user_courses');
        $this->deleteAllTuples('user_enrols');
        $this->deleteAllTuples('roles_users');
        $this->deleteAllTuples('groups');
        $this->deleteAllTuples('groups_members');
    }
}
