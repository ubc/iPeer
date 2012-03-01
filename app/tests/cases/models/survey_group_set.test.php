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
        'app.rubrics_criteria', 'app.rubrics_criteria_comment'
    );
    public $SurveyGroupSet = null;

    function startCase()
    {
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

    function testSurveyGroupSetInstance()
    {
        $this->assertTrue(is_a($this->SurveyGroupSet, 'SurveyGroupSet'));
    }

    function testSave()
    {
        // Set up test data
        $input = $this->setUpTestInput();
        $this->SurveyGroupSet->save($input);

        // Assert that data has been saved in database
        $searchedAll = $this->SurveyGroupSet->find('first', array('conditions' => array('SurveyGroupSet.id' => 1)));
        $searchedSurveyGroup = $this->SurveyGroup->find('first', array('conditions' => array('group_set_id' => 1)));
        $searchedSurveyGroupMember1 = $this->SurveyGroupMember->find('first', array('conditions' => array('group_set_id' => 1, 'user_id' => 1)));
        $searchedSurveyGroupMember2 = $this->SurveyGroupMember->find('first', array('conditions' => array('group_set_id' => 1, 'user_id' => 2)));
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
        $this->assertEqual($searchedAll['SurveyGroupSet']['id'], 1);
        $this->assertEqual($searchedAll['SurveyGroupSet']['survey_id'], 1);
        $this->assertEqual($searchedAll['SurveyGroupSet']['set_description'], '303 GROUP');
        $this->assertEqual($searchedSurveyGroup['SurveyGroup']['group_set_id'], 1);
        $this->assertEqual($searchedSurveyGroupMember1['SurveyGroupMember']['id'], 1);
        $this->assertEqual($searchedSurveyGroupMember2['SurveyGroupMember']['id'], 2);
    }

    function testRelease()
    {
        // Release surveyGroup 1 in the fixture
        $this->SurveyGroupSet->release(1);
        // Assert that surveyGroup 1 is released
        $released = $this->SurveyGroupSet->find('first', array('conditions' => array('SurveyGroupSet.id' => 1)));
        $this->assertEqual($released['SurveyGroupSet']['released'], 1);

        // Assert that an associated group has been added, as specified by the function
        $expectedGroupName = 'HELLO 1 Team #1';
        $addedGroup = $this->Group->find('first', array('conditions' => array('group_name' => 'HELLO 1 Team #1')));
        $this->assertTrue(!empty($addedGroup));
        $this->assertNotNull($addedGroup);
        $this->assertTrue(isset($addedGroup));
        $this->assertEqual($addedGroup['Group']['group_name'], $expectedGroupName);
    }

    function setUpTestInput()
    {
        $tmp = array(
            'SurveyGroupSet' => array(
                'id' => 1,
                'survey_id' => 1,
                'set_description' => '303 GROUP',
                'num_groups' => 1,
                'date' => 1308770251
            ),
            'SurveyGroup' => array(
                '0' => array(
                    'SurveyGroup' => array(
                        'group_number' => 1
                    ),
                    'SurveyGroupMember' => array(
                        '0' => array(
                            'user_id' => 10
                        ),
                        '1' => array(
                            'user_id' => 11
                        )
                    )
                )
            )
        );
        return $tmp;
    }
}
