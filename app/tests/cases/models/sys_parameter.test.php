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
        echo "Start SysParameter model test.\n";
        $this->SysParameter = ClassRegistry::init('SysParameter');
    }

    function endCase()
    {
    }

    function startTest($method)
    {
        echo $method."\n";
    }

    function endTest($method)
    {
    }

    function testFindParameter ()
    {
        $result = $this->SysParameter->findParameter('system.super_admin');
        $this->assertEqual($result['SysParameter']['parameter_code'], 'system.super_admin');
        $this->assertEqual($result['SysParameter']['parameter_value'], 'root');

        $result = $this->SysParameter->findParameter('invalid');
        $this->assertFalse($result);

        $result = $this->SysParameter->findParameter(null);
        $this->assertFalse($result);
    }

    function testGet()
    {
        $result = $this->SysParameter->get('system.super_admin');
        $this->assertEqual($result, 'root');

        // test cached version
        $result = $this->SysParameter->get('system.super_admin');
        $this->assertEqual($result, 'root');

        // test default
        $result = $this->SysParameter->get('non.existing.key', 'default');
        $this->assertEqual($result, 'default');
    }
    
    function testNumberSysParam()
    {
        $result = $this->SysParameter->find('count');
        $this->assertEqual($result, 19);
        
        $result = $this->SysParameter->find('list', array('fields' => array('SysParameter.parameter_code')));
        $expected = array(
            'system.super_admin', 'system.admin_email', 'display.date_format', 'system.version',
            'database.version', 'email.port', 'email.host', 'email.username', 'email.password',
            'display.contact_info', 'display.login.header', 'display.login.footer', 'system.absolute_url',
            'google_analytics.tracking_id', 'google_analytics.domain', 'banner.custom_logo', 'system.timezone', 
            'system.student_number', 'course.creation.instructions');
        $this->assertEqual(array_values($result), $expected);
    }
}
