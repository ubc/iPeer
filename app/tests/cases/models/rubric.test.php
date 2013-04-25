<?php
App::import('Model', 'rubric');

class RubricTestCase extends CakeTestCase
{
    public $name = 'Rubric';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input',
        'app.oauth_token', 'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail'
    );
    public $Rubric = null;

    function startCase()
    {
        echo "Start Rubric model test.\n";
        $this->Rubric = ClassRegistry::init('Rubric');
        $this->RubricsLom = ClassRegistry::init('RubricsLom');
        $this->RubricsCriteria = ClassRegistry::init('RubricsCriteria');
        $this->RubricsCriteriaComment = ClassRegistry::init('RubricsCriteriaComment');
    }

    function endCase()
    {
    }

    //Run before EVERY test.
    function startTest($method)
    {
        // extra setup stuff here
    }

    function endTest($method)
    {
    }

    function testSaveAllWithCriteriaComment()
    {
        //Set up test data
        $tmp = $this->setUpRubricsArray();
        // Run the test function
        $this->Rubric->SaveAllWithCriteriaComment($tmp);

        // Assert that Rubrics was saved correctly
        $rubric = $this->Rubric->find('first', array('conditions' => array('Rubric.id' => 50)));

        $this->assertEqual($rubric['Rubric']['id'], $tmp['Rubric']['id']);
        $this->assertEqual($rubric['Rubric']['name'], $tmp['Rubric']['name']);

        // Assert that RubricsLom was saved correctly
        $rubricsLom1 = $this->RubricsLom->find('first', array('conditions' => array('RubricsLom.id' => 50)));
        $rubricsLom2 = $this->RubricsLom->find('first', array('conditions' => array('RubricsLom.id' => 51)));
        $this->assertEqual($rubricsLom1['RubricsLom']['lom_comment'], $tmp['RubricsLom'][0]['lom_comment']);
        $this->assertEqual($rubricsLom2['RubricsLom']['lom_comment'], $tmp['RubricsLom'][1]['lom_comment']);

        // Assert that RubricsCriteria was saved correctly
        $rubricsCriteria = $this->RubricsCriteria->find('first', array('conditions' => array('RubricsCriteria.id' => 50)));
        $this->assertEqual($rubricsCriteria['RubricsCriteria']['criteria'], $tmp['RubricsCriteria'][0]['criteria']);

        // Assert that RubricsCriteriaComment was saved correctly
        $rubricsCriteriaComment0 = $this->RubricsCriteriaComment->find('first', array('conditions' => array('criteria_num' => 50)));
        $this->assertEqual(
            $rubricsCriteriaComment0['RubricsCriteriaComment']['criteria_comment'],
            $tmp['RubricsCriteria'][0]['RubricsCriteriaComment'][0]['criteria_comment']);
    }

    function testAfterFind()
    {
        // Set up test data
        $rubric = $this->Rubric->find('first', array(
            'conditions' => array('id' => 1),
            'contain' => array('RubricsCriteria.RubricsCriteriaComment', 'RubricsLom'),
        ));
        $this->assertEqual($rubric['RubricsCriteria'][0]['RubricsCriteriaComment'][0]['criteria_comment'], 'No participation.');
        $this->assertEqual($rubric['RubricsCriteria'][0]['RubricsCriteriaComment'][1]['criteria_comment'], 'Little participation.');
        $this->assertEqual($rubric['RubricsCriteria'][0]['RubricsCriteriaComment'][2]['criteria_comment'], 'Some participation.');
    }

    function testCopy()
    {
        $copyRubric = $this->Rubric->copy(1);

        // Assert the Rubric name is copied
        $this->assertEqual($copyRubric['Rubric']['name'],
            'Copy of Term Report Evaluation');
        // Assert that the Rubric and all of its associated id's are delete
        $this->assertTrue(!isset($copyRubric['Rubric']['id']));
        $this->assertTrue(!isset($copyRubric['RubricsCriteria'][0]['id']));
        $this->assertTrue(!isset($copyRubric['RubricsCriteria'][1]['id']));
        $this->assertTrue(!isset($copyRubric['RubricsLom'][0]['id']));
        $this->assertTrue(!isset($copyRubric['RubricsLom'][1]['id']));
        $this->assertTrue(!isset($copyRubric['RubricsCriteria'][0]['RubricsCriteriaComment'][0]['id']));
        $this->assertTrue(!isset($copyRubric['RubricsCriteria'][0]['RubricsCriteriaComment'][1]['id']));
        $this->assertTrue(!isset($copyRubric['RubricsCriteria'][1]['RubricsCriteriaComment'][0]['id']));
        $this->assertTrue(!isset($copyRubric['RubricsCriteria'][1]['RubricsCriteriaComment'][1]['id']));
    }

    function testGetRubricById()
    {

        $rubric = $this->Rubric->getRubricById(1);
        $this->assertEqual(1, $rubric['Rubric']['id']);
        $this->assertTrue(isset($rubric['RubricsCriteria']));
        $this->assertTrue(isset($rubric['RubricsLom']));
    }



    ### Helper Functions ###

    function setUpRubricsArray()
    {
        $tmp = array(
            'Rubric' => array(
                'id' => 50,
                'template' => 'horizontal',
                'name' => 'Some Rubric',
                'lom_max' => 2,
                'criteria' => 50,
                'availability' => 1,
                'zero_mark' => 0,
                'criteria_mark_0_0' => 0.5,
                'criteria_mark_0_1' => 1
            ),
            'RubricsLom' => Array(
                '0' => Array(
                    'lom_comment' => 'LOM 1',
                    'id' => 50,
                    'lom_num' => 1
                ),
                '1' => Array(
                    'lom_comment' => 'LOM 2',
                    'id' => 51,
                    'lom_num' => 2
                )
            ),
            'RubricsCriteria' => Array(
                '0' => Array(
                    'criteria' => 'Criteria 1',
                    'id' => 50,
                    'criteria_num' => 50,
                    'RubricsCriteriaComment' => Array(
                        '0' => Array(
                            'criteria_comment' => 'HELLO 11',
                            'id' => 1,
                        ),
                        '1' => Array(
                            'criteria_comment' => 'HELLO 21',
                            'id' => 2,
                        )
                    ),
                    'multiplier' => 1
                )
            )
        );
        return $tmp;
    }
}
