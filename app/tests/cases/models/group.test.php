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
        'app.user_enrol', 'app.groups_member', 'app.survey'
    );
    public $Group = null;

    function startCase()
    {
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

    function testGroupInstance()
    {
        $this->assertTrue(is_a($this->Group, 'Group'));
    }

    function testGetCourseGroupCount()
    {
        $empty = null;

        // Test valid course id
        $group= $this->Group->getCourseGroupCount(1);
        $this->assertEqual($group, 4);

        // Test course with no groups
        $group= $this->Group->getCourseGroupCount(3);
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
        $this->assertEqual($group, 4);

        // Test course with no groups;
        $group = $this->Group->getLastGroupNumByCourseId(3);
        $this->assertEqual($group, $empty);

        // Test course with no groups;
        $group = $this->Group->getLastGroupNumByCourseId(3);
        $this->assertEqual($group, $empty);
    }

    function testGetStudentsNotInGroup()
    {
        $empty = null;

        // Test group with some students in it
        $students = $this->Group->getStudentsNotInGroup(4);
        $this->assertEqual(Set::extract('/Member/username', $students), array('Peterson', 'StudentY'));

        // Test group with all students in it
        $students = $this->Group->getStudentsNotInGroup(2);
        $this->assertEqual(Set::extract('/Member/username', $students), $empty);

        // Test group with no students in it
        $students = $this->Group->getStudentsNotInGroup(3);
        $this->assertEqual(Set::extract('/Member/username', $students), array('Peterson', 'StudentY', 'StudentZ'));

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
        $students = $this->Group->getMembersByGroupId(1);
        $students = Set::extract('/Member/username', $students);
        sort($students);
        $this->assertEqual($students, array('StudentY', 'StudentZ'));

        // Test group with no students in it
        $students = $this->Group->getMembersByGroupId(3);
        $this->assertEqual($students, $empty);

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
        $this->assertEqual($group[0]['Group']['group_name'], 'group1');

        // Test invalid group
        $group = $this->Group->getGroupByGroupId(999);
        $this->assertEqual($group, $empty);
    }

    function testGetGroupsByCouseId()
    {
        $empty=null;

        // Test valid course with groups
        $groups = $this->Group->getGroupsByCouseId(1);
        $this->assertEqual($groups, array(1=>1,2=>2,3=>3,4=>4));

        // Test valid course with no groups
        $groups = $this->Group->getGroupsByCouseId(2);
        $this->assertEqual($groups, $empty);

        // Test invalid course
        $groups = $this->Group->getGroupsByCouseId(999);
        $this->assertEqual($groups, $empty);
    }
}
