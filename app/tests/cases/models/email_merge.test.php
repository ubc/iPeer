<?php
App::import('Model', 'EmailMerge');

class EmailMergeTestCase extends CakeTestCase
{
    public $name = 'EmailMerge';
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
        echo "Start Email Merge test.\n";
        $this->EmailMerge = ClassRegistry::init('EmailMerge');
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

    function testGetMergeList()
    {
        $mergeList = $this->EmailMerge->getMergeList();
        $expectedResults = array('{{{USERNAME}}}' => 'Username', '{{{FIRSTNAME}}}' => 'First Name', '{{{LASTNAME}}}' => 'Last Name', '{{{Email}}}' => 'Email Address',
            '{{{COURSENAME}}}' => 'Course Name', '{{{EVENTTITLE}}}' => 'Event Title','{{{DUEDATE}}}' => 'Event Due Date', '{{{CLOSEDATE}}}' => 'Event Close Date');

        $this->assertEqual($mergeList, $expectedResults);
    }

    function testGetFieldNameByValue()
    {
        $this->EmailMerge= & ClassRegistry::init('EmailMerge');
        $empty=null;

        //Test on valid input
        $field = $this->EmailMerge->getFieldAndTableNameByValue('{{{USERNAME}}}');
        $this->assertEqual($field, array('table_name'=>'User','field_name'=>'username'));

        //Testing invalid inputs; all tests should return NULL
        //invalid input
        $invalid = $this->EmailMerge->getFieldAndTableNameByValue('<<USERNAME>>');
        $this->assertEqual($invalid, $empty);

        //null input
        $field = $this->EmailMerge->getFieldAndTableNameByValue(null);
        $this->assertEqual($field, $empty);
    }
}
