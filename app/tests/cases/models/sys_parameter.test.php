<?php
App::import('Model', 'SysParameter');

class SysParameterTestCase extends CakeTestCase
{
    public $name = 'EvaluationSimple';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 'app.sys_parameter',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.evaluation_simple'
    );
    public $Course = null;

    function startCase()
    {
        $this->SysParameter = ClassRegistry::init('SysParameter');
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

    function testSysParameterInstance()
    {
        $this->assertTrue(is_a($this->SysParameter, 'SysParameter'));
    }

    function testFindParameter ()
    {

        $result = $this->SysParameter->findParameter('code2');
        $this->assertEqual($result['SysParameter']['id'], 2);
        $this->assertEqual($result['SysParameter']['parameter_code'], 'code2');
        $this->assertEqual($result['SysParameter']['parameter_value'], 'value2');

        $result = $this->SysParameter->findParameter('invalid');
        $this->assertFalse($result);

        $result = $this->SysParameter->findParameter(null);
        $this->assertFalse($result);
    }
}
