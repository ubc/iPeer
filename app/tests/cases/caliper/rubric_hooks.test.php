<?php
App::import('Lib', 'CaliperAuthTestCase');
# controllers
App::import('Controller', 'Rubrics');
# caliper
App::import('Lib', 'caliper');
use caliper\CaliperHooks;
use caliper\CaliperSensor;

// mock instead of needing to create a new controller for every test
Mock::generatePartial(
    'RubricsController',
    'MockCaliperRubricsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperRubricHooksTest extends CaliperAuthTestCase
{
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.personalize', 'app.penalty', 'app.evaluation_simple',
        'app.faculty', 'app.user_tutor', 'app.course_department',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.user_faculty', 'app.department', 'app.sys_parameter',
        'app.oauth_token', 'app.rubric', 'app.rubrics_criteria',
        'app.rubrics_criteria_comment', 'app.rubrics_lom',
        'app.simple_evaluation', 'app.survey_input', 'app.mixeval_question',
        'app.mixeval_question_desc', 'app.mixeval', 'app.mixeval_question_type',
        'app.email_schedule', 'app.email_template'
    );

    /**
     * startCase case startup
     *
     * @access public
     * @return void
     */
    public function startCase()
    {
        echo "Start Caliper rubric hook test.\n";
    }

    /**
     * endCase case ending
     *
     * @access public
     * @return void
     */
    public function endCase()
    {
    }

    /**
     * startTest prepare tests
     * @access public
     * @return void
     */
    public function startTest($method)
    {
        echo $method.TEST_LB;
        parent::startTest($method);
        $this->controller = new MockCaliperRubricsController();

        $this->Rubric = ClassRegistry::init('Rubric');

        $this->Rubric->id = 1;
        $results = $this->Rubric->find('first', array(
            'conditions' => array('id' => 1),
            'contain' => array(
                'RubricsCriteria',
                'RubricsCriteria.RubricsCriteriaComment',
                'RubricsLom',
            )
        ));
        $this->rubric = $results['Rubric'];
        $this->questions = $results['RubricsCriteria'];
        $this->loms = $results['RubricsLom'];

        $this->expected_rubric = array(
            'id' => 'http://test.ipeer.com/rubrics/view/1',
            'type' => 'Assessment',
            'name' => 'Term Report Evaluation',
            'extensions' => array(
                'zero_mark' => '0',
                'lom_max' => '5',
                'criteria' => '3',
                'view_mode' => 'student',
                'availability' => 'public',
                'template' => 'horizontal',
            ),
            'dateCreated' => $this->model_timestamp_to_iso8601($this->rubric['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->rubric['modified']),
            'items' => array(
                array(
                    'id' => 'http://test.ipeer.com/rubrics/view/1/questions/1',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/rubrics/view/1/questions/2',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/rubrics/view/1/questions/3',
                    'type' => 'AssessmentItem'
                ),
            ),
        );

        $this->expected_rubric_question_1_scale = array(
            'id' => 'http://test.ipeer.com/rubrics/view/1/questions/1/scale',
            'type' => 'LikertScale',
            'extensions' => array(
                'comments' => array('No participation.', 'Little participation.', 'Some participation.', 'Good participation.', 'Great participation.'),
            ),
            'scalePoints' => 5,
            'itemLabels' => array('Poor', 'Below Average', 'Average', 'Above Average', 'Excellent'),
            'itemValues' => array('1', '2', '3', '4', '5'),
        );
        $this->expected_rubric_question_1 = array(
            'id' => 'http://test.ipeer.com/rubrics/view/1/questions/1',
            'type' => 'AssessmentItem',
            'name' => 'Participated in Team Meetings',
            'extensions' => array(
                'criteria_num' => '1',
                'multiplier' => '5',
                'show_marks' => '0',
                'scale' => $this->expected_rubric_question_1_scale
            ),
            'isPartOf' => $this->expected_rubric,
        );
        $this->expected_rubric_question_2_scale = array(
            'id' => 'http://test.ipeer.com/rubrics/view/1/questions/2/scale',
            'type' => 'LikertScale',
            'extensions' => array(
                'comments' => array(),
            ),
            'scalePoints' => 5,
            'itemLabels' => array('Poor', 'Below Average', 'Average', 'Above Average', 'Excellent'),
            'itemValues' => array('1', '2', '3', '4', '5'),
        );
        $this->expected_rubric_question_2 = array(
            'id' => 'http://test.ipeer.com/rubrics/view/1/questions/2',
            'type' => 'AssessmentItem',
            'name' => 'Was Helpful and Co-operative',
            'extensions' => array(
                'criteria_num' => '2',
                'multiplier' => '5',
                'show_marks' => '1',
                'scale' => $this->expected_rubric_question_2_scale
            ),
            'isPartOf' => $this->expected_rubric,
        );
        $this->expected_rubric_question_3_scale = array(
            'id' => 'http://test.ipeer.com/rubrics/view/1/questions/3/scale',
            'type' => 'LikertScale',
            'extensions' => array(
                'comments' => array(),
            ),
            'scalePoints' => 5,
            'itemLabels' => array('Poor', 'Below Average', 'Average', 'Above Average', 'Excellent'),
            'itemValues' => array('1', '2', '3', '4', '5'),
        );
        $this->expected_rubric_question_3 = array(
            'id' => 'http://test.ipeer.com/rubrics/view/1/questions/3',
            'type' => 'AssessmentItem',
            'name' => 'Submitted Work on Time',
            'extensions' => array(
                'criteria_num' => '3',
                'multiplier' => '5',
                'show_marks' => '1',
                'scale' => $this->expected_rubric_question_3_scale
            ),
            'isPartOf' => $this->expected_rubric,
        );
        $this->expected_rubric_questions = array(
            $this->expected_rubric_question_1,
            $this->expected_rubric_question_2,
            $this->expected_rubric_question_3,
        );
    }

    /**
     * @access public
     * @return void
     */
    public function endTest($method)
    {
        parent::endTest($method);
    }

    function test_rubric_delete()
    {
        $this->setupSession();
        $expected_stored_data = array(
            'Rubric' => $this->rubric,
            'RubricsCriteria' => $this->questions,
            'RubricsLom' => $this->loms,
        );
        $expected_events = array();
        foreach($this->expected_rubric_questions as $expected_rubric_question) {
            $expected_events[] = array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Deleted',
                'object' => $expected_rubric_question,
            );
        }
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_rubric,
        );

        # check disabled
        # before_delete
        CaliperHooks::rubric_before_delete($this->Rubric);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Rubric->data));

        # after_delete
        CaliperHooks::rubric_after_delete($this->Rubric);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Rubric->data));

        # check enabled
        $this->_enable_caliper();
        # before_delete
        CaliperHooks::rubric_before_delete($this->Rubric);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertTrue(array_key_exists('caliper_delete', $this->Rubric->data));
        $this->assertEqual($expected_stored_data, $this->Rubric->data['caliper_delete']);

        # after_delete
        CaliperHooks::rubric_after_delete($this->Rubric);
        $events = $this->_get_caliper_events();
        $this->assertFalse(array_key_exists('caliper_delete', $this->Rubric->data));
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }
    function test_rubric_delete_criteria_partial()
    {
        $this->setupSession();
        $expected_event = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_rubric_questions[2],
        );
        $rubric_results = array(
            'Rubric' => $this->rubric,
            'RubricsCriteria' => $this->questions,
            'RubricsLom' => $this->loms,
        );

        # check disabled
        $event = CaliperHooks::rubric_delete_criteria_partial($rubric_results, $this->questions[2]);
        $this->assertNull($event);
        # nothing should be send
        $e = $this->_get_caliper_events();
        $this->assertEqual(0, count($e));

        # check enabled
        $this->_enable_caliper();
        $event = CaliperHooks::rubric_delete_criteria_partial($rubric_results, $this->questions[2]);
        $this->assertNotNull($event);
        # nothing should be send
        $e = $this->_get_caliper_events();
        $this->assertEqual(0, count($e));

        // fake send the event to get a cleaned up event from _get_caliper_events
        CaliperSensor::sendEvent($event);
        $actualEvent = $this->_get_caliper_events()[0];
        $this->assertEqual($expected_event, $actualEvent);
    }

    function test_rubric_save_with_criteria()
    {
        //Note: needs to use rubric_delete_criteria_partial
        //there is actually doubling of the removed events because they are not actually
        //removed from the db between rubric_delete_criteria_partial and rubric_save_with_criteria
        $this->setupSession();

        $rubric_results = array(
            'Rubric' => $this->rubric,
            'RubricsCriteria' => $this->questions,
            'RubricsLom' => $this->loms,
        );

        $existing_question_ids = array($this->questions[0]['id'], $this->questions[1]['id']);
        $fake_removed_question_events = array(
            array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Deleted',
                'object' => $this->expected_rubric_questions[2],
            ),
        );


        # check disabled
        $removed_events = array(
            CaliperHooks::rubric_delete_criteria_partial($rubric_results, $this->questions[2])
        );
        CaliperHooks::rubric_save_with_criteria($this->Rubric, $removed_events, $existing_question_ids, TRUE);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        foreach([True, False] as $is_new) {
            $expected_events = $fake_removed_question_events;
            foreach($this->questions as $index => $question) {
                $expected_rubric_question = $this->expected_rubric_questions[$index];

                $action = in_array($question['id'], $existing_question_ids) ? 'Modified' : 'Created';
                $expected_events[] = array(
                    'type' => 'ResourceManagementEvent',
                    'profile' => 'ResourceManagementProfile',
                    'action' => $action,
                    'object' => $expected_rubric_question,
                );
            }
            $action = $is_new ? 'Created' : 'Modified';
            $expected_events[] = array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => $action,
                'object' => $this->expected_rubric,
            );

            $removed_events = array(
                CaliperHooks::rubric_delete_criteria_partial($rubric_results, $this->questions[2])
            );
            CaliperHooks::rubric_save_with_criteria($this->Rubric, $removed_events, $existing_question_ids, $is_new);
            $events = $this->_get_caliper_events();
            $this->assertEqual(count($expected_events), count($events));
            foreach($events as $index => $actualEvent) {
                $this->assertEqual($expected_events[$index], $actualEvent);
            }
        }
    }

    /*
    print_r("\n**********\n");
    var_export($actualEvent);
    print_r("\n**********\n");
    */

    /*
    print_r("\n**********\n");
    var_export($actualEvent);
    print_r("\n");
    var_export($expected_event);
    print_r("\n**********\n");
    */
}