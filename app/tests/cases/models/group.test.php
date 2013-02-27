<?php
App::import('Model', 'Group');

class GroupTestCase extends CakeTestCase
{
    public $name = 'Group';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.user_faculty', 'app.faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input',
    );
    public $Group = null;

    function startCase()
    {
        echo "Start Group model test.\n";
        $this->Group = ClassRegistry::init('Group');
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

    function testGetCourseGroupCount()
    {
        $empty = null;

        // Test valid course id
        $group= $this->Group->getCourseGroupCount(1);
        $this->assertEqual($group, 2);

        // Test course with no groups
        $group= $this->Group->getCourseGroupCount(2);
        $this->assertEqual($group, 0);

        // Test invalid course id
        $group= $this->Group->getCourseGroupCount(999);
        $this->assertEqual($group, $empty);
    }

    // Function is not used anywhere
    function testPrepDataImport()
    {
    }

    function testGetLastGroupNumByCourseId()
    {
        $empty = null;

        // Test valid course
        $group = $this->Group->getLastGroupNumByCourseId(1);
        $this->assertEqual($group, 2);

        // Test course with no groups;
        $group = $this->Group->getLastGroupNumByCourseId(2);
        $this->assertEqual($group, $empty);
    }

    function testGetStudentsNotInGroup()
    {
        $empty = null;

        // Test group with some students in it
        $students = $this->Group->getStudentsNotInGroup(2);
        $this->assertEqual($students, array(
            '36' => 'Tutor 2',
            '26' => 'redshirt0022 Bill Student',
            '21' => 'redshirt0017 Nicole Student',
            '17' => 'redshirt0013 Edna Student',
            '28' => 'redshirt0024 Michael Student',
            '15' => 'redshirt0011 Jennifer Student',
            '13' => 'redshirt0009 Damien Student',
            '19' => 'redshirt0015 Jonathan Student',
            '35' => ' Tutor 1 *',
            '6' => 'redshirt0002 Alex Student *',
            '5' => 'redshirt0001 Ed Student *',
        ));

        // Test invalid group
        $students = $this->Group->getStudentsNotInGroup(999);
        $this->assertEqual($students, $empty);

        //Test function for null course_id input
        $studentNotInGroup = $this->Group->getStudentsNotInGroup(null);
        $this->assertEqual($studentNotInGroup, $empty);
    }

    function testGetMembersByGroupId()
    {
        $empty = null;

        // Test group with students in it
        $students = $this->Group->getMembersByGroupId(2);
        $students = Set::extract('/Member/student_no_with_full_name', $students);
        sort($students);
        $this->assertEqual($students, array(
            'redshirt0027 Hui Student',
            'redshirt0028 Bowinn Student',
            'redshirt0029 Joe Student',
            'redshirt0003 Matt Student',
        ));

        // Test invalid group
        $students = $this->Group->getMembersByGroupId(999);
        $this->assertEqual($students, $empty);

        //Run test on NULL group_id input
        $nullGroupId = $this->Group->getMembersByGroupId(null);
        $this->assertEqual($nullGroupId, $empty);
    }

    function testGetGroupByGroupId()
    {
        $empty = null;

        // Test valid group
        $group = $this->Group->getGroupByGroupId(1);
        $this->assertEqual($group[0]['Group']['group_name'], 'Reapers');

        // Test invalid group
        $group = $this->Group->getGroupByGroupId(999);
        $this->assertEqual($group, $empty);
    }

    function testGetGroupsByCourseId()
    {
        $empty=null;

        // Test valid course with groups
        $groups = $this->Group->getGroupsByCourseId(1);
        $this->assertEqual($groups, array(1 =>'Reapers', 2 =>'Lazy Engineers'));

        // Test valid course with no groups
        $groups = $this->Group->getGroupsByCourseId(2);
        $this->assertEqual($groups, $empty);

        // Test invalid course
        $groups = $this->Group->getGroupsByCourseId(999);
        $this->assertEqual($groups, $empty);
    }

    function testGetFirstAvailGroupNum()
    {
        // Test valid course with groups
        $group_num = $this->Group->getFirstAvailGroupNum(1);
        $this->assertEqual($group_num, 3);

        // Test valid course with no groups
        $group_num = $this->Group->getFirstAvailGroupNum(2);
        $this->assertEqual($group_num, 1);

        // Test invalid course
        $group_num = $this->Group->getFirstAvailGroupNum(999);
        $this->assertEqual($group_num, 1);
    }

    function testGetGroupByGroupIdEventId()
    {
        // group is attached to event
        $group = $this->Group->getGroupByGroupIdEventId(1, 1);
        $this->assertFalse(empty($group));
        $this->assertEqual($group['Group']['id'], 1);

        // group is not attached to event
        $group = $this->Group->getGroupByGroupIdEventId(2, 8);
        $this->assertFalse($group);

    }

    function testGetGroupByGroupIdEventIdMemberId()
    {
        // student within group, and group is attached to event
        $group = $this->Group->getGroupByGroupIdEventIdMemberId(1, 1, 5);
        $this->assertFalse(empty($group));
        $this->assertEqual($group['Group']['id'], 1);

        // student not in the group
        $group = $this->Group->getGroupByGroupIdEventIdMemberId(1, 1, 31);
        $this->assertFalse($group);

        // student in the group, but group is not attached to event
        $group = $this->Group->getGroupByGroupIdEventIdMemberId(2, 8, 31);
        $this->assertFalse($group);

    }
}
