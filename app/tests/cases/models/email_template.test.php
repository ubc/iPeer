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
        $expected[0]['EmailTemplate'] = array('id' => 1, 'name' => 'Email template example','description' => 'This is an email template example', 'subject' => 'Email Template', 'content' => 'Hello, {{{USERNAME}}}', 'availability' => '1', 'creator_id' => '1', 'created' => '0000-00-00 00:00:00', 'updater_id' => null, 'updated' => null, 'creator' => 'Super Admin', 'updater' => null);
        $templates = $this->EmailTemplate->getMyEmailTemplate('1', 'all');
        $this->assertEqual($templates, $expected);

        //Test on valid input w/ find list
        $templates = $this->EmailTemplate->getMyEmailTemplate('1', 'list');
        $this->assertEqual($templates, array('1' => 'Email template example'));

        //Test on null input
        $templates = $this->EmailTemplate->getMyEmailTemplate(null);
        $this->assertEqual($templates, null);
    }

    function testGetPermittedEmailTemplate()
    {
        //Test on valid input w/ find all
        $expected = array();
        $expected[0]['EmailTemplate'] = array('id' => 1, 'name' => 'Email template example','description' => 'This is an email template example', 'subject' => 'Email Template', 'content' => 'Hello, {{{USERNAME}}}', 'availability' => '1', 'creator_id' => '1', 'created' => '0000-00-00 00:00:00', 'updater_id' => null, 'updated' => null, 'creator' => 'Super Admin', 'updater' => null);
        $expected[1]['EmailTemplate'] = array('id' => 2, 'name' => 'Email template example2','description' => 'email template ex2', 'subject' => 'Email Template2', 'content' => 'Hello, {{{FIRSTNAME}}}', 'availability' => '1', 'creator_id' => '2', 'created' => '0000-00-00 00:00:00', 'updater_id' => null, 'updated' => null, 'creator' => 'Instructor 1', 'updater' => null);
        $expected[2]['EmailTemplate'] = array('id' => 3, 'name' => 'Email template example3','description' => 'email temp example3', 'subject' => 'Email Template3', 'content' => 'Hello,', 'availability' => '1', 'creator_id' => '3', 'created' => '0000-00-00 00:00:00', 'updater_id' => null, 'updated' => null, 'creator' => 'Instructor 2', 'updater' => null);
        $templates = $this->EmailTemplate->getPermittedEmailTemplate('1', 'all');
        $this->assertEqual($templates, $expected);

        //Test on valid input w/ find list
        $templates = $this->EmailTemplate->getPermittedEmailTemplate('1', 'list');
        $this->assertEqual($templates, array('1' => 'Email template example', '2' => 'Email template example2', '3' => 'Email template example3'));

        //Test on null input
        $expected = array();
        $expected[0]['EmailTemplate'] = array('id' => 1, 'name' => 'Email template example','description' => 'This is an email template example', 'subject' => 'Email Template', 'content' => 'Hello, {{{USERNAME}}}', 'availability' => '1', 'creator_id' => '1', 'created' => '0000-00-00 00:00:00', 'updater_id' => null, 'updated' => null, 'creator' => 'Super Admin', 'updater' => null);
        $expected[1]['EmailTemplate'] = array('id' => 2, 'name' => 'Email template example2','description' => 'email template ex2', 'subject' => 'Email Template2', 'content' => 'Hello, {{{FIRSTNAME}}}', 'availability' => '1', 'creator_id' => '2', 'created' => '0000-00-00 00:00:00', 'updater_id' => null, 'updated' => null, 'creator' => 'Instructor 1', 'updater' => null);
        $expected[2]['EmailTemplate'] = array('id' => 3, 'name' => 'Email template example3','description' => 'email temp example3', 'subject' => 'Email Template3', 'content' => 'Hello,', 'availability' => '1', 'creator_id' => '3', 'created' => '0000-00-00 00:00:00', 'updater_id' => null, 'updated' => null, 'creator' => 'Instructor 2', 'updater' => null);
        $templates = $this->EmailTemplate->getPermittedEmailTemplate(null);
        $this->assertEqual($templates, $expected);
    }

    function testGetCreatorId()
    {
        //Test on valid input
        $creator_id = $this->EmailTemplate->getCreatorId('2');
        $this->assertEqual($creator_id, '2');

        //Test on invalid input
        $creator_id = $this->EmailTemplate->getCreatorId('10');
        $this->assertEqual($creator_id, null);

        //null input
        $creator_id = $this->EmailTemplate->getCreatorId(null);
        $this->assertEqual($creator_id, null);
    }
}
