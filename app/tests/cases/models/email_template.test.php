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

    function testGetMyEmailTemplate()
    {
        //Test on valid input w/ find all
        $templates = $this->EmailTemplate->getMyEmailTemplate('1', 'all');
        $this->assertEqual(count($templates), 2);
        $this->assertEqual($templates[0]['EmailTemplate']['id'], 1);
        $this->assertEqual($templates[1]['EmailTemplate']['id'], 2);
        $this->assertEqual($templates[0]['EmailTemplate']['name'], 'Submission Confirmation');
        $this->assertEqual($templates[1]['EmailTemplate']['name'], 'Email template example');

        //Test on valid input w/ find list
        $templates = $this->EmailTemplate->getMyEmailTemplate('1', 'list');
        $this->assertEqual($templates, array('1' => 'Submission Confirmation', '2' => 'Email template example'));

        //Test on null input
        $templates = $this->EmailTemplate->getMyEmailTemplate(null);
        $this->assertEqual($templates, null);
    }

    function testGetPermittedEmailTemplate()
    {
        //Test on valid input w/ find all
        $templates = $this->EmailTemplate->getPermittedEmailTemplate('1', 'all');
        $this->assertEqual(count($templates), 4);
        $this->assertEqual($templates[0]['EmailTemplate']['id'], 1);
        $this->assertEqual($templates[1]['EmailTemplate']['id'], 2);
        $this->assertEqual($templates[2]['EmailTemplate']['id'], 3);
        $this->assertEqual($templates[3]['EmailTemplate']['id'], 4);
        $this->assertEqual($templates[0]['EmailTemplate']['name'], 'Submission Confirmation');
        $this->assertEqual($templates[1]['EmailTemplate']['name'], 'Email template example');
        $this->assertEqual($templates[2]['EmailTemplate']['name'], 'Email template example2');
        $this->assertEqual($templates[3]['EmailTemplate']['name'], 'Email template example3');

        //Test on valid input w/ find list
        $templates = $this->EmailTemplate->getPermittedEmailTemplate('1', 'list');
        $this->assertEqual($templates, array(
            '1' => 'Submission Confirmation',
            '2' => 'Email template example',
            '3' => 'Email template example2',
            '4' => 'Email template example3')
        );

        //Test on null input
        $templates = $this->EmailTemplate->getPermittedEmailTemplate(null);
        $this->assertEqual($templates, array());
    }

    function testGetCreatorId()
    {
        //Test on valid input
        $creator_id = $this->EmailTemplate->getCreatorId('3');
        $this->assertEqual($creator_id, '2');

        //Test on invalid input
        $creator_id = $this->EmailTemplate->getCreatorId('10');
        $this->assertEqual($creator_id, null);

        //null input
        $creator_id = $this->EmailTemplate->getCreatorId(null);
        $this->assertEqual($creator_id, null);
    }
}
