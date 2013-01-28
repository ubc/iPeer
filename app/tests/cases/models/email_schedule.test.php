<?php
App::import('Model', 'EmailSchedule');

class EmailScheduleTestCase extends CakeTestCase
{
    public $name = 'EmailSchedule';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
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
        echo "Start Email Schedule test.\n";
        $this->EmailSchedule = ClassRegistry::init('EmailSchedule');
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

    function testGetEmailsToSend()
    {
        $expected[0]['EmailSchedule'] = array(
            'id'         => 2,
            'subject'    => 'Email Template',
            'content'    => 'Hello, {{{USERNAME}}}',
            'date'       => '2011-07-18 16:52:31',
            'from'       => '1',
            'to'         => '5;6;7;13;15;17;19;21;26;28;31;32;33',
            'course_id'  => null,
            'event_id'   => null,
            'grp_id'     => null,
            'sent'       => 0,
            'creator_id' => 1,
            'created'    => '2010-07-16 16:57:50',
            'creator'    => 'Super Admin'
        );
        $emails = $this->EmailSchedule->getEmailsToSend();
        $this->assertEqual($emails, $expected);
    }

    function testGetSent()
    {
        //Test on valid input
        $sent = $this->EmailSchedule->getSent('3');
        $this->assertEqual($sent, '1');

        //Test on invalid input
        $sent = $this->EmailSchedule->getSent('10');
        $this->assertEqual($sent, null);

        //null input
        $sent = $this->EmailSchedule->getSent(null);
        $this->assertEqual($sent, null);
    }
}
