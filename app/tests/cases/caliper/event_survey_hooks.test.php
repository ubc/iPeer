<?php
App::import('Lib', 'CaliperAuthTestCase');
# controllers
App::import('Controller', 'Events');
# caliper
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

// mock instead of needing to create a new controller for every test
Mock::generatePartial(
    'EventsController',
    'MockCaliperSurveyEventsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperEventSurveyHooksTest extends CaliperAuthTestCase
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
        echo "Start Caliper event survey hook test.\n";
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
        $this->controller = new MockCaliperSurveyEventsController();

        $this->Event = ClassRegistry::init('Event');
        $this->Survey = ClassRegistry::init('Survey');

        # survey event
        $this->survey_event_id = 5;
        $results = $this->Event->find('first', array(
            'conditions' => array('Event.id' => $this->survey_event_id),
            'contain' => array('Penalty'),
        ));
        $this->survey_event = $results['Event'];
        $this->survey_penalty = $results['Penalty'];

        $results = $this->Survey->find('first', array(
            'conditions' => array('Survey.id' => $this->survey_event['template_id'])
        ));
        $this->survey = $results['Survey'];

        $this->expected_survey_event = array(
            'id' => 'http://test.ipeer.com/events/view/5',
            'type' => 'Questionnaire',
            'name' => 'Survey, all Q types',
            'extensions' => array(
                'event_template_type_id' => '3',
                'template_id' => '2',
                'self_eval' => '1',
                'com_req' => '1',
                'auto_release' => '0',
                'enable_details' => '1',
                'record_status' => 'A',
                'due_date' => $this->survey_event['due_date'],
                'release_date_begin' => $this->survey_event['release_date_begin'],
                'release_date_end' => $this->survey_event['release_date_end'],
                'type' => 'survey',
                'survey' => array(
                    'availability' => 'public',
                ),
            ),
            'dateCreated' => $this->model_timestamp_to_iso8601($this->survey_event['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->survey_event['modified']),
            'isPartOf' => $this->expected_course,
            'items' => array(
                array(
                    'id' => 'http://test.ipeer.com/events/view/5/questions/3',
                    'type' => 'QuestionnaireItem',
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/5/questions/4',
                    'type' => 'QuestionnaireItem',
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/5/questions/5',
                    'type' => 'QuestionnaireItem',
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/5/questions/6',
                    'type' => 'QuestionnaireItem',
                ),
            ),
        );
        $this->expected_survey_event_without_context = $this->expected_survey_event;
        unset($this->expected_survey_event_without_context['@context']);

        $this->expected_survey_event_question_1 = array(
            'id' => 'http://test.ipeer.com/events/view/5/questions/3?question=true',
            'type' => 'MultiselectQuestion',
            'extensions' => array(
                'type' => 'M',
            ),
            'questionPosed' => 'Testing out MC',
            'points' => 4,
            'itemLabels' => array('A', 'B', 'C', 'D'),
            'itemValues' => array('A', 'B', 'C', 'D'),
        );
        $this->expected_survey_event_questionnaire_item_1 = array(
            'id' => 'http://test.ipeer.com/events/view/5/questions/3',
            'type' => 'QuestionnaireItem',
            'extensions' => array(
                'type' => 'M',
                'master' => 'no',
            ),
            'isPartOf' => $this->expected_survey_event_without_context,
            'question' => $this->expected_survey_event_question_1,
        );
        $this->expected_survey_event_question_2 = array(
            'id' => 'http://test.ipeer.com/events/view/5/questions/4?question=true',
            'type' => 'MultiselectQuestion',
            'extensions' => array(
                'type' => 'C',
            ),
            'questionPosed' => 'Testing out checkboxes',
            'points' => 3,
            'itemLabels' => array('choose me', 'no, me', 'me please'),
            'itemValues' => array('choose me', 'no, me', 'me please'),
        );
        $this->expected_survey_event_questionnaire_item_2 = array(
            'id' => 'http://test.ipeer.com/events/view/5/questions/4',
            'type' => 'QuestionnaireItem',
            'extensions' => array(
                'type' => 'C',
                'master' => 'no',
            ),
            'isPartOf' => $this->expected_survey_event_without_context,
            'question' => $this->expected_survey_event_question_2,
        );
        $this->expected_survey_event_question_3 = array(
            'id' => 'http://test.ipeer.com/events/view/5/questions/5?question=true',
            'type' => 'OpenEndedQuestion',
            'extensions' => array(
                'type' => 'S',
            ),
            'questionPosed' => 'Testing out single line answers',
        );
        $this->expected_survey_event_questionnaire_item_3 = array(
            'id' => 'http://test.ipeer.com/events/view/5/questions/5',
            'type' => 'QuestionnaireItem',
            'extensions' => array(
                'type' => 'S',
                'master' => 'no',
            ),
            'isPartOf' => $this->expected_survey_event_without_context,
            'question' => $this->expected_survey_event_question_3,
        );
        $this->expected_survey_event_question_4 = array(
            'id' => 'http://test.ipeer.com/events/view/5/questions/6?question=true',
            'type' => 'OpenEndedQuestion',
            'extensions' => array(
                'type' => 'L',
            ),
            'questionPosed' => 'Testing out multi-line long answers',
        );
        $this->expected_survey_event_questionnaire_item_4 = array(
            'id' => 'http://test.ipeer.com/events/view/5/questions/6',
            'type' => 'QuestionnaireItem',
            'extensions' => array(
                'type' => 'L',
                'master' => 'no',
            ),
            'isPartOf' => $this->expected_survey_event_without_context,
            'question' => $this->expected_survey_event_question_4,
        );
        $this->expected_survey_event_questionnaire_items = array(
            $this->expected_survey_event_questionnaire_item_1,
            $this->expected_survey_event_questionnaire_item_2,
            $this->expected_survey_event_questionnaire_item_3,
            $this->expected_survey_event_questionnaire_item_4,
        );


        $this->expected_survey = array(
            'id' => 'http://test.ipeer.com/surveys/view/2',
            'type' => 'Questionnaire',
            'name' => 'Survey, all Q types',
            'extensions' => array(
                'availability' => 'public',
            ),
            'dateCreated' => $this->model_timestamp_to_iso8601($this->survey['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->survey['modified']),
            'items' => array(
                array(
                    'id' => 'http://test.ipeer.com/surveys/view/2/questions/3',
                    'type' => 'QuestionnaireItem',
                ),
                array(
                    'id' => 'http://test.ipeer.com/surveys/view/2/questions/4',
                    'type' => 'QuestionnaireItem',
                ),
                array(
                    'id' => 'http://test.ipeer.com/surveys/view/2/questions/5',
                    'type' => 'QuestionnaireItem',
                ),
                array(
                    'id' => 'http://test.ipeer.com/surveys/view/2/questions/6',
                    'type' => 'QuestionnaireItem',
                ),
            ),
        );
        $this->expected_survey_without_context = $this->expected_survey;
        unset($this->expected_survey_without_context['@context']);

        $this->expected_survey_question_1 = array(
            'id' => 'http://test.ipeer.com/surveys/view/2/questions/3?question=true',
            'type' => 'MultiselectQuestion',
            'extensions' => array(
                'type' => 'M',
            ),
            'questionPosed' => 'Testing out MC',
            'points' => 4,
            'itemLabels' => array('A', 'B', 'C', 'D'),
            'itemValues' => array('A', 'B', 'C', 'D'),
        );
        $this->expected_survey_questionnaire_item_1 = array(
            'id' => 'http://test.ipeer.com/surveys/view/2/questions/3',
            'type' => 'QuestionnaireItem',
            'extensions' => array(
                'type' => 'M',
                'master' => 'no',
            ),
            'isPartOf' => $this->expected_survey_without_context,
            'question' => $this->expected_survey_question_1,
        );
        $this->expected_survey_question_2 = array(
            'id' => 'http://test.ipeer.com/surveys/view/2/questions/4?question=true',
            'type' => 'MultiselectQuestion',
            'extensions' => array(
                'type' => 'C',
            ),
            'questionPosed' => 'Testing out checkboxes',
            'points' => 3,
            'itemLabels' => array('choose me', 'no, me', 'me please'),
            'itemValues' => array('choose me', 'no, me', 'me please'),
        );
        $this->expected_survey_questionnaire_item_2 = array(
            'id' => 'http://test.ipeer.com/surveys/view/2/questions/4',
            'type' => 'QuestionnaireItem',
            'extensions' => array(
                'type' => 'C',
                'master' => 'no',
            ),
            'isPartOf' => $this->expected_survey_without_context,
            'question' => $this->expected_survey_question_2,
        );
        $this->expected_survey_question_3 = array(
            'id' => 'http://test.ipeer.com/surveys/view/2/questions/5?question=true',
            'type' => 'OpenEndedQuestion',
            'extensions' => array(
                'type' => 'S',
            ),
            'questionPosed' => 'Testing out single line answers',
        );
        $this->expected_survey_questionnaire_item_3 = array(
            'id' => 'http://test.ipeer.com/surveys/view/2/questions/5',
            'type' => 'QuestionnaireItem',
            'extensions' => array(
                'type' => 'S',
                'master' => 'no',
            ),
            'isPartOf' => $this->expected_survey_without_context,
            'question' => $this->expected_survey_question_3,
        );
        $this->expected_survey_question_4 = array(
            'id' => 'http://test.ipeer.com/surveys/view/2/questions/6?question=true',
            'type' => 'OpenEndedQuestion',
            'extensions' => array(
                'type' => 'L',
            ),
            'questionPosed' => 'Testing out multi-line long answers',
        );
        $this->expected_survey_questionnaire_item_4 = array(
            'id' => 'http://test.ipeer.com/surveys/view/2/questions/6',
            'type' => 'QuestionnaireItem',
            'extensions' => array(
                'type' => 'L',
                'master' => 'no',
            ),
            'isPartOf' => $this->expected_survey_without_context,
            'question' => $this->expected_survey_question_4,
        );

        $this->expected_survey_questionnaire_items = array(
            $this->expected_survey_questionnaire_item_1,
            $this->expected_survey_questionnaire_item_2,
            $this->expected_survey_questionnaire_item_3,
            $this->expected_survey_questionnaire_item_4,
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

    function test_event_delete_survey()
    {
        $this->setupSession();
        $this->Event->id = $this->survey_event_id;

        $expected_stored_data = array(
            'Event' => $this->survey_event,
            'Penalty' => $this->survey_penalty,
            'Course' => $this->course,
        );
        $expected_events = array();
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_survey_event,
            'group' => $this->expected_group,
            'membership' => $this->expected_membership,
        );
        foreach($this->expected_survey_event_questionnaire_items as $expected_survey_event_questionnaire_item) {
            $expected_events[] = array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Deleted',
                'object' => $expected_survey_event_questionnaire_item,
                'group' => $this->expected_group,
                'membership' => $this->expected_membership,
            );
        }

        # check disabled
        # before_delete
        CaliperHooks::event_before_delete($this->Event);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Event->data));

        # after_delete
        CaliperHooks::event_after_delete($this->Event);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Event->data));

        # check enabled
        $this->_enable_caliper();
        # before_delete
        CaliperHooks::event_before_delete($this->Event);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertTrue(array_key_exists('caliper_delete', $this->Event->data));

        # ignore due in for the purpose of this comparison (since it changes over time)
        unset($this->Event->data['caliper_delete']['Event']['due_in']);
        unset($expected_stored_data['Event']['due_in']);
        $this->assertEqual($expected_stored_data, $this->Event->data['caliper_delete']);

        # after_delete
        CaliperHooks::event_after_delete($this->Event);
        $events = $this->_get_caliper_events();
        $this->assertEqual(count($expected_events), count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Event->data));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }

    function test_event_save_survey()
    {
        $this->setupSession();
        $this->Event->id = $this->survey_event_id;

        # check disabled
        CaliperHooks::event_after_save($this->Event, TRUE);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        foreach([True, False] as $created) {
            $expected_events = array();
            if ($created) {
                $expected_events[] = array(
                    'type' => 'ResourceManagementEvent',
                    'profile' => 'ResourceManagementProfile',
                    'action' => 'Copied',
                    'object' => $this->expected_survey,
                    'generated' => $this->expected_survey_event,
                    'group' => $this->expected_group,
                    'membership' => $this->expected_membership,
                );
                foreach($this->expected_survey_event_questionnaire_items as $index => $expected_survey_event_questionnaire_item) {
                    $expected_events[] = array(
                        'type' => 'ResourceManagementEvent',
                        'profile' => 'ResourceManagementProfile',
                        'action' => 'Copied',
                        'object' => $this->expected_survey_questionnaire_items[$index],
                        'generated' => $expected_survey_event_questionnaire_item,
                        'group' => $this->expected_group,
                        'membership' => $this->expected_membership,
                    );
                }
            } else {
                $expected_events[] = array(
                    'type' => 'ResourceManagementEvent',
                    'profile' => 'ResourceManagementProfile',
                    'action' => 'Modified',
                    'object' => $this->expected_survey_event,
                    'group' => $this->expected_group,
                    'membership' => $this->expected_membership,
                );
            }

            CaliperHooks::event_after_save($this->Event, $created);
            $events = $this->_get_caliper_events();
            $this->assertEqual(count($expected_events), count($events));
            foreach($events as $index => $actualEvent) {
                $this->assertEqual($expected_events[$index], $actualEvent);
            }
        }
    }

    function test_event_submit_survey()
    {
        $this->_setup_student('redshirt0013');
        $this->setupSession();

        $expected_events = array();
        foreach($this->expected_survey_event_questionnaire_items as $index => $expected_survey_event_questionnaire_item) {
            unset($expected_survey_event_questionnaire_item['@context']);

            $generated = array();
            if ($index == 0) {
                $generated = array(
                    'id' => 'http://test.ipeer.com/events/view/5/questions/3/responses/5',
                    'type' => 'MultiselectResponse',
                    'selections' => array('B'),
                );
            } else if ($index == 1) {
                $generated = array(
                    'id' => 'http://test.ipeer.com/events/view/5/questions/4/responses/6',
                    'type' => 'MultiselectResponse',
                    'selections' => array('choose me', 'no, me'),
                );
            } else if ($index == 2) {
                $generated = array(
                    'id' => 'http://test.ipeer.com/events/view/5/questions/5/responses/8',
                    'type' => 'OpenEndedResponse',
                    'value' => 'single line rah rah',
                );
            } else if ($index == 3) {
                $generated = array(
                    'id' => 'http://test.ipeer.com/events/view/5/questions/6/responses/9',
                    'type' => 'OpenEndedResponse',
                    'value' => 'long answer what what',
                );
            }

            $expected_events[] = array(
                'type' => 'QuestionnaireItemEvent',
                'profile' => 'SurveyProfile',
                'action' => 'Completed',
                'object' => $expected_survey_event_questionnaire_item,
                'generated' => $generated,
                'group' => $this->expected_group,
                'membership' => $this->expected_membership,
            );
        }
        unset($this->expected_survey_event['@context']);
        $expected_events[] = array(
            'type' => 'QuestionnaireEvent',
            'profile' => 'SurveyProfile',
            'action' => 'Submitted',
            'object' => $this->expected_survey_event,
            'group' => $this->expected_group,
            'membership' => $this->expected_membership,
        );

        # check disabled
        CaliperHooks::submit_survey($this->survey_event_id, $this->user['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        CaliperHooks::submit_survey($this->survey_event_id, $this->user['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }
}