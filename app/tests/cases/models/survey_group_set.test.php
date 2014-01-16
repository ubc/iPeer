<?php
App::import('Model', 'SurveyGroupSet');

class SurveyGroupSetTestCase extends CakeTestCase
{
    public $name = 'SurveyGroupSet';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question', 'app.survey',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input',
        'app.oauth_token', 'app.evaluation_mixeval', 'app.evaluation_rubric', 
        'app.evaluation_mixeval_detail', 'app.evaluation_rubric_detail'
    );
    public $SurveyGroupSet = null;

    function startCase()
    {
        echo "Start SurveyGroupSet model test.\n";
        $this->SurveyGroupSet = ClassRegistry::init('SurveyGroupSet');
        $this->SurveyGroup = ClassRegistry::init('SurveyGroup');
        $this->SurveyGroupMember = ClassRegistry::init('SurveyGroupMember');
        $this->Survey = ClassRegistry::init('Survey');
        $this->Question = ClassRegistry::init('Question');
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

    function testSave()
    {
        // Set up test data
        $input = $this->setUpTestInput();
        $this->SurveyGroupSet->save($input);

        // Assert that data has been saved in database
        $searchedAll = $this->SurveyGroupSet->find('first', array('conditions' => array('SurveyGroupSet.id' => 50), 'contain' => false));
        $searchedSurveyGroup = $this->SurveyGroup->find('first', array('conditions' => array('group_set_id' => 50), 'contain' => false));
        $searchedSurveyGroupMember1 = $this->SurveyGroupMember->find('first', array('conditions' => array('group_set_id' => 50, 'user_id' => 10)));
        $searchedSurveyGroupMember2 = $this->SurveyGroupMember->find('first', array('conditions' => array('group_set_id' => 50, 'user_id' => 11)));
        $this->assertTrue(!empty($searchedAll));
        $this->assertTrue(!empty($searchedSurveyGroup));
        $this->assertTrue(!empty($searchedSurveyGroupMember1));
        $this->assertTrue(!empty($searchedSurveyGroupMember2));
        $this->assertNotNull($searchedAll);
        $this->assertNotNull($searchedSurveyGroup);
        $this->assertNotNull($searchedSurveyGroupMember1);
        $this->assertNotNull($searchedSurveyGroupMember2);
        $this->assertTrue(isset($searchedAll));
        $this->assertTrue(isset($searchedSurveyGroup));
        $this->assertTrue(isset($searchedSurveyGroupMember1));
        $this->assertTrue(isset($searchedSurveyGroupMember2));

        // Assert the data is saved correctly
        $this->assertEqual($searchedAll['SurveyGroupSet']['id'], 50);
        $this->assertEqual($searchedAll['SurveyGroupSet']['survey_id'], 1);
        $this->assertEqual($searchedAll['SurveyGroupSet']['set_description'], 'Test Group');
        $this->assertEqual($searchedSurveyGroup['SurveyGroup']['group_set_id'], 50);
        $this->assertEqual($searchedSurveyGroupMember1['SurveyGroupMember']['id'], 37);
        $this->assertEqual($searchedSurveyGroupMember2['SurveyGroupMember']['id'], 38);
    }

    function testRelease()
    {
        // Release surveyGroup in the fixture
        $this->SurveyGroupSet->release(3);
        // Assert that surveyGroup is released
        $released = $this->SurveyGroupSet->find('first', array('conditions' => array('SurveyGroupSet.id' => 3), 'contain' => false));
        $this->assertEqual($released['SurveyGroupSet']['released'], 1);

        // Assert that an associated group has been added, as specified by the function
        $groupName = 'test groupset Team #1';
        $addedGroup = $this->Group->find(
            'first',
            array('conditions' =>
                array('group_name' => $groupName)
            )
        );
        $this->assertTrue(!empty($addedGroup));
        $this->assertNotNull($addedGroup);
        $this->assertTrue(isset($addedGroup));
        $this->assertEqual($addedGroup['Group']['group_name'], $groupName);
    }

    function setUpTestInput()
    {
        $tmp = array(
            'SurveyGroupSet' => array(
                'id' => 50,
                'survey_id' => 1,
                'set_description' => 'Test Group',
                'num_groups' => 1,
                'date' => 1308770251
            ),
            'SurveyGroup' => array(
                '0' => array(
                    'SurveyGroupMember' => array(10, 11)
                )
            )
        );
        return $tmp;
    }
}
