<?php
App::import('Model', 'GroupsMembers');

class GroupsMembersTestCase extends CakeTestCase
{
    public $name = 'GroupsMembers';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.survey_input',
    );
    public $GroupsMembers = null;

    function startCase()
    {
        echo "Start GroupsMembers model test.\n";
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
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

    function testInsertMembers()
    {
        // set up test input
        $data = array();
        $data['member_count'] = 2;
        $data['member1'] = 3;
        $data['member2'] = 4;

        $this->GroupsMembers->insertMembers(5, $data);
        // Assert the data was saved in database
        $searched = $this->GroupsMembers->find('all', array('conditions' => array('group_id' => 5)));
        $this->assertEqual($searched[0]['GroupsMembers']['group_id'], 5);
        $this->assertEqual($searched[1]['GroupsMembers']['group_id'], 5);
        $this->assertEqual($searched[0]['GroupsMembers']['user_id'], 3);
        $this->assertEqual($searched[1]['GroupsMembers']['user_id'], 4);

        // Test for incorrect inputs

        $incorrectResult = $this->GroupsMembers->insertMembers(5, null);
        $this->assertFalse($incorrectResult);

    }

    function testUpdateMembers()
    {
        $data = array();
        $data['member_count'] = 1;
        $data['member1'] = 2;

        $this->GroupsMembers->updateMembers(2, $data);
        $searched = $this->GroupsMembers->find('all', array('conditions' => array('group_id' => 2)));
        $this->assertEqual($searched[0]['GroupsMembers']['group_id'], 2);
        $this->assertEqual($searched[0]['GroupsMembers']['user_id'], 2);
        $this->assertEqual(sizeof($searched), 1);

        $incorrectData = $this->GroupsMembers->updateMembers(2, null);
        $this->assertFalse($incorrectData);
    }

    function testGetMembers()
    {
        //Test valid group with members
        $members = $this->GroupsMembers->getMembers(1);
        $this->assertEqual($members, array(1=>5,2=>6,3=>7,4=>35));

        //Test valid group with no members
        $members = $this->GroupsMembers->getMembers(3);
        $this->assertEqual($members, null);

        //Test invalid group
        $members = $this->GroupsMembers->getMembers(999);
        $this->assertEqual($members, null);
    }

    function testCountMembers()
    {
        //Test valid group with members
        $members = $this->GroupsMembers->countMembers(1);
        $this->assertEqual($members, 4);

        //Test invalid group
        $members = $this->GroupsMembers->countMembers(999);
        $this->assertEqual($members, null);
    }

    function testGetEventGroupMembers ()
    {
        //Test group, selfeval
        $members = $this->GroupsMembers->getEventGroupMembers(1, true, 5);
        $this->assertEqual(Set::extract('/User/username', $members), array('redshirt0001', 'redshirt0002', 'redshirt0003', 'tutor1'));

        //Test group, no selfeval, valid used id
        $members = $this->GroupsMembers->getEventGroupMembers(1, false, 6);
        $this->assertEqual(Set::extract('/User/username', $members), array('redshirt0001', 'redshirt0003', 'tutor1'));

        //Test group, no selfeval, invalid used id
        $members = $this->GroupsMembers->getEventGroupMembers(1, false, 999);
        $this->assertEqual(Set::extract('/User/username', $members), array('redshirt0001', 'redshirt0002', 'redshirt0003', 'tutor1'));

        //Test invalid group
        $members = $this->GroupsMembers->getEventGroupMembers(999, false, 3);
        $this->assertEqual(Set::extract('/User/username', $members), null);
    }

    function testCheckMembershipInGroup()
    {
        //Test student in existing group
        $inGroup = $this->GroupsMembers->checkMembershipInGroup(1, 7);
        $this->assertTrue($inGroup);

        //Test student not in existing group
        $inGroup = $this->GroupsMembers->checkMembershipInGroup(2, 5);
        $this->assertFalse($inGroup);

        //Test invalid student in existing group
        $inGroup = $this->GroupsMembers->checkMembershipInGroup(1, 999);
        $this->assertFalse($inGroup);

        //Test student in invalid existing group
        $inGroup = $this->GroupsMembers->checkMembershipInGroup(999, 3);
        $this->assertFalse($inGroup);

        //Test invalid student in invalid existing group
        $inGroup = $this->GroupsMembers->checkMembershipInGroup(999, 999);
        $this->assertFalse($inGroup);
    }

    function testGetUserListInGroups()
    {
        //Test valid group with members
        $users = $this->GroupsMembers->getUserListInGroups(1);
        $this->assertEqual($users, array(1=>5, 2=>6, 3=>7, 4=>35));

        //Test valid group with no members
        $users = $this->GroupsMembers->getUserListInGroups(3);
        $this->assertEqual($users, null);

        //Test invalid group
        $users = $this->GroupsMembers->getUserListInGroups(999);
        $this->assertEqual($users, null);
    }

}
