<?php
App::import('Lib', 'CaliperAuthTestCase');
# controllers
App::import('Controller', 'Mixevals');
# caliper
App::import('Lib', 'caliper');
use caliper\CaliperHooks;
use caliper\CaliperSensor;

// mock instead of needing to create a new controller for every test
Mock::generatePartial(
    'MixevalsController',
    'MockCaliperMixevalsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperMixevalHooksTest extends CaliperAuthTestCase
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
        echo "Start Caliper mixeval hook test.\n";
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
        $this->controller = new MockCaliperMixevalsController();

        $this->Mixeval = ClassRegistry::init('Mixeval');

        $this->Mixeval->id = 1;
        $results = $this->Mixeval->find('first', array(
            'conditions' => array('id' => 1),
            'contain' => array('MixevalQuestion', 'MixevalQuestion.MixevalQuestionDesc'),
            'recursive' => 2,
        ));
        $this->mixeval = $results['Mixeval'];
        $this->questions = $results['MixevalQuestion'];

        $this->expected_mixeval = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1',
            'type' => 'Assessment',
            'name' => 'Default Mix Evaluation',
            'extensions' => array(
                'zero_mark' => '0',
                'availability' => 'public',
            ),
            'dateCreated' => $this->model_timestamp_to_iso8601($this->mixeval['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->mixeval['modified']),
            'items' => array(
                array(
                    'id' => 'http://test.ipeer.com/mixevals/view/1/questions/1',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/mixevals/view/1/questions/2',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/mixevals/view/1/questions/3',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/mixevals/view/1/questions/4',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/mixevals/view/1/questions/5',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/mixevals/view/1/questions/6',
                    'type' => 'AssessmentItem'
                ),
            ),
        );

        $this->expected_mixeval_question_1_scale = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1/questions/1/scale',
            'type' => 'LikertScale',
            'scalePoints' => 5,
            'itemLabels' => array('Lowest', '', 'Middle', '', 'Highest'),
            'itemValues' => array('0.2', '0.4', '0.6', '0.8', '1'),
        );
        $this->expected_mixeval_question_1 = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1/questions/1',
            'type' => 'AssessmentItem',
            'name' => 'Participated in Team Meetings',
            'extensions' =>  array(
                'question_num' => '1',
                'required' => '1',
                'self_eval' => '0',
                'scale_level' => '5',
                'show_marks' => '1',
                'question_type' => 'Likert',
                'scale' => $this->expected_mixeval_question_1_scale,
            ),
            'isPartOf' => $this->expected_mixeval,
        );
        $this->expected_mixeval_question_2_scale = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1/questions/2/scale',
            'type' => 'LikertScale',
            'scalePoints' => 5,
            'itemLabels' => array('Lowest', '', 'Middle', '', 'Highest'),
            'itemValues' => array('0.2', '0.4', '0.6', '0.8', '1'),
        );
        $this->expected_mixeval_question_2 = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1/questions/2',
            'type' => 'AssessmentItem',
            'name' =>  'Was Helpful and co-operative',
            'extensions' =>  array(
                'question_num' => '2',
                'required' => '1',
                'self_eval' => '0',
                'scale_level' => '5',
                'show_marks' => '0',
                'question_type' => 'Likert',
                'scale' => $this->expected_mixeval_question_2_scale,
            ),
            'isPartOf' => $this->expected_mixeval,
        );
        $this->expected_mixeval_question_3_scale = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1/questions/3/scale',
            'type' => 'LikertScale',
            'scalePoints' => 5,
            'itemLabels' => array('Lowest', '', 'Middle', '', 'Highest'),
            'itemValues' => array('0.2', '0.4', '0.6', '0.8', '1'),
        );
        $this->expected_mixeval_question_3 = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1/questions/3',
            'type' => 'AssessmentItem',
            'name' => 'Submitted work on time',
            'extensions' =>  array(
                'question_num' => '3',
                'required' => '1',
                'self_eval' => '0',
                'scale_level' => '5',
                'show_marks' => '1',
                'question_type' => 'Likert',
                'scale' => $this->expected_mixeval_question_3_scale,
            ),
            'isPartOf' => $this->expected_mixeval,
        );
        $this->expected_mixeval_question_4 = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1/questions/4',
            'type' => 'AssessmentItem',
            'name' => 'Produced efficient work?',
            'extensions' =>  array(
                'question_num' => '4',
                'required' => '1',
                'self_eval' => '0',
                'scale_level' => '5',
                'show_marks' => '0',
                'question_type' => 'Sentence',
            ),
            'isPartOf' => $this->expected_mixeval,
        );
        $this->expected_mixeval_question_5 = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1/questions/5',
            'type' => 'AssessmentItem',
            'name' => 'Contributed?',
            'extensions' =>  array(
                'question_num' => '5',
                'required' => '1',
                'self_eval' => '0',
                'scale_level' => '5',
                'show_marks' => '0',
                'question_type' => 'Paragraph',
            ),
            'isPartOf' => $this->expected_mixeval,
        );
        $this->expected_mixeval_question_6 = array(
            'id' => 'http://test.ipeer.com/mixevals/view/1/questions/6',
            'type' => 'AssessmentItem',
            'name' => 'Easy to work with?',
            'extensions' =>  array(
                'question_num' => '6',
                'required' => '0',
                'self_eval' => '0',
                'scale_level' => '5',
                'show_marks' => '0',
                'question_type' => 'Sentence',
            ),
            'isPartOf' => $this->expected_mixeval,
        );
        $this->expected_mixeval_questions = array(
            $this->expected_mixeval_question_1,
            $this->expected_mixeval_question_2,
            $this->expected_mixeval_question_3,
            $this->expected_mixeval_question_4,
            $this->expected_mixeval_question_5,
            $this->expected_mixeval_question_6,
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

    function test_mixeval_delete()
    {
        $this->setupSession();
        $expected_stored_data = array(
            'Mixeval' => $this->mixeval,
            'MixevalQuestion' => $this->questions,
        );
        $expected_events = array();
        foreach($this->expected_mixeval_questions as $expected_mixeval_question) {
            $expected_events[] = array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Deleted',
                'object' => $expected_mixeval_question,
            );
        }
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_mixeval,
        );

        # check disabled
        # before_delete
        CaliperHooks::mixeval_before_delete($this->Mixeval);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Mixeval->data));

        # after_delete
        CaliperHooks::mixeval_after_delete($this->Mixeval);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Mixeval->data));

        # check enabled
        $this->_enable_caliper();
        # before_delete
        CaliperHooks::mixeval_before_delete($this->Mixeval);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertTrue(array_key_exists('caliper_delete', $this->Mixeval->data));
        $this->assertEqual($expected_stored_data, $this->Mixeval->data['caliper_delete']);

        # after_delete
        CaliperHooks::mixeval_after_delete($this->Mixeval);
        $events = $this->_get_caliper_events();
        $this->assertFalse(array_key_exists('caliper_delete', $this->Mixeval->data));
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }

    function test_mixeval_save_deleted_question_partial()
    {
        $this->setupSession();
        $removed_question_ids = array($this->questions[2]['id'], $this->questions[3]['id']);

        $expected_events = array();
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_mixeval_questions[2],
        );
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_mixeval_questions[3],
        );

        # check disabled
        $events = CaliperHooks::mixeval_save_deleted_question_partial($this->mixeval['id'], $removed_question_ids);
        $this->assertEqual(0, count($events));
        # nothing should be send
        $e = $this->_get_caliper_events();
        $this->assertEqual(0, count($e));

        # check enabled
        $this->_enable_caliper();
        $events = CaliperHooks::mixeval_save_deleted_question_partial($this->mixeval['id'], $removed_question_ids);
        $this->assertEqual(count($expected_events), count($events));
        # nothing should be send
        $e = $this->_get_caliper_events();
        $this->assertEqual(0, count($e));

        // fake send the event to get a cleaned up event from _get_caliper_events
        CaliperSensor::sendEvent($events);
        $events = $this->_get_caliper_events();
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }

    function test_mixeval_save_with_questions()
    {
        //Note: needs to use mixeval_save_deleted_question_partial
        //there is actually doubling of the removed events because they are not actually
        //removed from the db between mixeval_save_deleted_question_partial and mixeval_save_with_questions
        $this->setupSession();

        $existing_question_ids = array($this->questions[0]['id'], $this->questions[1]['id']);
        $removed_question_ids = array($this->questions[2]['id'], $this->questions[3]['id']);
        $fake_removed_question_events = array(
            array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Deleted',
                'object' => $this->expected_mixeval_questions[2],
            ),
            array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Deleted',
                'object' => $this->expected_mixeval_questions[3],
            ),
        );

        # check disabled
        $events = CaliperHooks::mixeval_save_deleted_question_partial($this->mixeval['id'], $removed_question_ids);
        CaliperHooks::mixeval_save_with_questions($events, $this->mixeval['id'], $existing_question_ids, TRUE);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        foreach([True, False] as $is_new) {
            $expected_events = $fake_removed_question_events;
            foreach($this->questions as $index => $question) {
                $expected_mixeval_question = $this->expected_mixeval_questions[$index];

                $action = in_array($question['id'], $existing_question_ids) ? 'Modified' : 'Created';
                $expected_events[] = array(
                    'type' => 'ResourceManagementEvent',
                    'profile' => 'ResourceManagementProfile',
                    'action' => $action,
                    'object' => $expected_mixeval_question,
                );
            }
            $action = $is_new ? 'Created' : 'Modified';
            $expected_events[] = array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => $action,
                'object' => $this->expected_mixeval,
            );

            $events = CaliperHooks::mixeval_save_deleted_question_partial($this->mixeval['id'], $removed_question_ids);
            CaliperHooks::mixeval_save_with_questions($events, $this->mixeval['id'], $existing_question_ids, $is_new);
            $events = $this->_get_caliper_events();
            $this->assertEqual(count($expected_events), count($events));
            foreach($events as $index => $actualEvent) {
                $this->assertEqual($expected_events[$index], $actualEvent);
            }
        }
    }
}