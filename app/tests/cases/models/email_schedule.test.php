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

    function testCourseInstance()
    {
        $this->assertTrue(is_a($this->EmailSchedule, 'EmailSchedule'));
    }

    function testGetEmailsToSend()
    {
        $expected[0]['EmailSchedule'] = array(
            'id'         => 1,
            'subject'    => 'To send',
            'content'    => 'This is Test Email',
            'date'       => '2011-07-10 00:00:00',
            'from'       => '2',
            'to'         => '2;3',
            'course_id'  => null,
            'event_id'   => null,
            'grp_id'     => null,
            'sent'       => 0,
            'creator_id' => 1,
            'created'    => '2011-06-10 00:00:00',
            'creator'    => 'steve slade'
        );
        $emails = $this->EmailSchedule->getEmailsToSend();
        $this->assertEqual($emails, $expected);
    }

    function testGetCreatorId()
    {
        //Test on valid input
        $creator_id = $this->EmailSchedule->getCreatorId('1');
        $this->assertEqual($creator_id, '1');

        //Test on invalid input
        $creator_id = $this->EmailSchedule->getCreatorId('10');
        $this->assertEqual($creator_id, null);

        //null input
        $creator_id = $this->EmailSchedule->getCreatorId(null);
        $this->assertEqual($creator_id, null);
    }

    function testGetSent()
    {
        //Test on valid input
        $sent = $this->EmailSchedule->getSent('2');
        $this->assertEqual($sent, '1');

        //Test on invalid input
        $sent = $this->EmailSchedule->getSent('10');
        $this->assertEqual($sent, null);

        //null input
        $sent = $this->EmailSchedule->getSent(null);
        $this->assertEqual($sent, null);
    }
}
