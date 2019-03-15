<?php
App::import('Model', 'EmailTemplate');

class EmailTemplateTestCase extends CakeTestCase
{
    public $name = 'EmailTemplate';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.email_merge', 'app.email_schedule', 'app.email_template'
    );
    public $Course = null;

    function startCase()
    {
        echo "Start Email Template model test.\n";
        $this->EmailTemplate = ClassRegistry::init('EmailTemplate');
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

    function testGetPermittedEmailTemplate()
    {
        //Test on valid input w/ find all
        $templates = $this->EmailTemplate->getPermittedEmailTemplate('1', 'all');
        $this->assertEqual(count($templates), 6);
        $this->assertEqual($templates[0]['EmailTemplate']['id'], 2);
        $this->assertEqual($templates[1]['EmailTemplate']['id'], 3);
        $this->assertEqual($templates[2]['EmailTemplate']['id'], 4);
        $this->assertEqual($templates[3]['EmailTemplate']['id'], 5);
        $this->assertEqual($templates[4]['EmailTemplate']['id'], 6);
        $this->assertEqual($templates[5]['EmailTemplate']['id'], 7);
        $this->assertEqual($templates[0]['EmailTemplate']['name'], 'Email template example');
        $this->assertEqual($templates[1]['EmailTemplate']['name'], 'Email template example2');
        $this->assertEqual($templates[2]['EmailTemplate']['name'], 'Email template example3');
        $this->assertEqual($templates[3]['EmailTemplate']['name'], 'Evaluation Reminder Template');
        $this->assertEqual($templates[4]['EmailTemplate']['name'], 'MECH 328 Evaluation Reminder Template');
        $this->assertEqual($templates[5]['EmailTemplate']['name'], 'MECH 328 Survey Reminder Template');

        //Test on valid input w/ find list
        $templates = $this->EmailTemplate->getPermittedEmailTemplate('1', 'list');
        $this->assertEqual($templates, array(
            '2' => 'Email template example',
            '3' => 'Email template example2',
            '4' => 'Email template example3',
            '5' => 'Evaluation Reminder Template',
            '6' => 'MECH 328 Evaluation Reminder Template',
            '7' => 'MECH 328 Survey Reminder Template')
        );

        //Test on null input
        $templates = $this->EmailTemplate->getPermittedEmailTemplate(null);
        $this->assertEqual($templates, array());
    }
}
