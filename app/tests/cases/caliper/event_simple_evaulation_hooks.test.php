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
    'MockCaliperSimpleEvaluationEventsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperEventSimpleEvaluationHooksTest extends CaliperAuthTestCase
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
        echo "Start Caliper event simple evaluation hook test.\n";
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
        $this->controller = new MockCaliperSimpleEvaluationEventsController();

        $this->Event = ClassRegistry::init('Event');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->SimpleEvaluation = ClassRegistry::init('SimpleEvaluation');

        # simple evaluation event
        $this->simple_evaluation_event_id = 1;
        $results = $this->Event->find('first', array(
            'conditions' => array('Event.id' => $this->simple_evaluation_event_id),
            'contain' => array('Penalty'),
        ));
        $this->simple_evaluation_event = $results['Event'];
        $this->simple_evaluation_penalty = $results['Penalty'];

        $results = $this->SimpleEvaluation->find('first', array(
            'conditions' => array('SimpleEvaluation.id' => $this->simple_evaluation_event['template_id'])
        ));
        $this->simple_evaluation = $results['SimpleEvaluation'];

        $this->expected_simple_evaluation_event = array(
            'id' => 'http://test.ipeer.com/events/view/1',
            'type' => 'Assessment',
            'name' => 'Term 1 Evaluation',
            'extensions' => array(
                'event_template_type_id' => '1',
                'template_id' => '1',
                'self_eval' => '0',
                'com_req' => '0',
                'auto_release' => '0',
                'enable_details' => '1',
                'record_status' => 'A',
                'due_date' => $this->simple_evaluation_event['due_date'],
                'release_date_begin' => $this->simple_evaluation_event['release_date_begin'],
                'release_date_end' => $this->simple_evaluation_event['release_date_end'],
                'result_release_date_begin' => $this->simple_evaluation_event['result_release_date_begin'],
                'result_release_date_end' => $this->simple_evaluation_event['result_release_date_end'],
                'penalties' => array(
                    array('days_late' => '1', 'percent_penalty' => '20'),
                    array('days_late' => '2', 'percent_penalty' => '40'),
                    array('days_late' => '3', 'percent_penalty' => '60'),
                    array('days_late' => '4', 'percent_penalty' => '100'),
                ),
                'type' => 'simple_evaluation',
                'simple_evaluation' => array(
                    'point_per_member' => '100',
                    'record_status' => 'A',
                    'availability' => 'public',
                ),
            ),
            'dateCreated' => $this->model_timestamp_to_iso8601($this->simple_evaluation_event['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->simple_evaluation_event['modified']),
            'isPartOf' => $this->expected_course,
            'dateToShow' => $this->model_timestamp_to_iso8601($this->simple_evaluation_event['release_date_begin']),
            'dateToStartOn' => $this->model_timestamp_to_iso8601($this->simple_evaluation_event['release_date_begin']),
            'dateToSubmit' => $this->model_timestamp_to_iso8601($this->simple_evaluation_event['due_date']),
            'items' => array(
                array(
                    'id' => 'http://test.ipeer.com/events/view/1/questions/1',
                    'type' => 'AssessmentItem'
                ),
            ),
        );
        $this->expected_simple_evaluation_event_question_scale = array(
            'id' => 'http://test.ipeer.com/events/view/1/questions/1/scale',
            'type' => 'NumericScale',
            'extensions' => array(
                'point_per_member' => '100',
            ),
            'minValue' => 0.0,
            'maxValue' => 200.0,
            'step' => 1.0,
        );
        $this->expected_simple_evaluation_event_question = array(
            'id' => 'http://test.ipeer.com/events/view/1/questions/1',
            'type' => 'AssessmentItem',
            'isPartOf' => $this->expected_simple_evaluation_event,
            'extensions' => array(
                'scale' => $this->expected_simple_evaluation_event_question_scale
            ),
        );
        $this->expected_simple_evaluation_event_question_scale_without_context = $this->expected_simple_evaluation_event_question_scale;
        unset($this->expected_simple_evaluation_event_question_scale_without_context['@context']);
        $this->expected_simple_evaluation_feedback_question = array(
            'id' => 'http://test.ipeer.com/events/view/1/questions/1?feedback=true',
            'type' => 'RatingScaleQuestion',
            'scale' => $this->expected_simple_evaluation_event_question_scale_without_context,
        );

        $this->expected_simple_evaluation = array(
            'id' => 'http://test.ipeer.com/simpleevaluations/view/1',
            'type' => 'Assessment',
            'name' => 'Module 1 Project Evaluation',
            'extensions' => array(
                'point_per_member' => '100',
                'record_status' => 'A',
                'availability' => 'public',
            ),
            'dateCreated' => $this->model_timestamp_to_iso8601($this->simple_evaluation['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->simple_evaluation['modified']),
            'items' => array(
                array(
                    'id' => 'http://test.ipeer.com/simpleevaluations/view/1/questions/1',
                    'type' => 'AssessmentItem'
                ),
            ),
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

    function test_event_delete_simple_evaluation()
    {
        $this->setupSession();
        $this->Event->id = $this->simple_evaluation_event_id;

        $expected_stored_data = array(
            'Event' => $this->simple_evaluation_event,
            'Penalty' => $this->simple_evaluation_penalty,
            'Course' => $this->course,
        );
        $expected_events = array();
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_simple_evaluation_event,
            'group' => $this->expected_group,
            'membership' => $this->expected_membership,
        );

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

    function test_event_save_simple_evaluation()
    {
        $this->setupSession();
        $this->Event->id = $this->simple_evaluation_event_id;

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
                    'object' => $this->expected_simple_evaluation,
                    'generated' => $this->expected_simple_evaluation_event,
                    'group' => $this->expected_group,
                    'membership' => $this->expected_membership,
                );
            } else {
                $expected_events[] = array(
                    'type' => 'ResourceManagementEvent',
                    'profile' => 'ResourceManagementProfile',
                    'action' => 'Modified',
                    'object' => $this->expected_simple_evaluation_event,
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

    function test_event_submit_simple_evaluation()
    {
        $this->_setup_student('redshirt0003', 1);
        $this->setupSession();

        $group_event = $this->GroupEvent->getGroupEventByEventIdGroupId(
            $this->simple_evaluation_event_id, $this->group['id'])['GroupEvent'];

        $expected_events = array();
        foreach($this->group_members as $index => $group_member) {
            $user_id = $group_member['User']['id'];

            if (in_array($user_id, array(5, 6)) ) {
                $expected_group_member = $this->expected_group_members[$index];

                $generated = array();
                if ($user_id == 5) {
                    $generated = array(
                        'id' => 'http://test.ipeer.com/events/view/1/questions/1/group/1/user/5/responses/1',
                        'type' => 'RatingScaleResponse',
                        'extensions' =>  array(
                          'evaluatee' => $expected_group_member,
                          'comment' => 'very hard working',
                          'release_status' => '0',
                          'grade_release' => '0',
                          'record_status' => 'A',
                        ),
                        'dateCreated' => '2012-07-13T17:21:57.000Z',
                        'dateModified' => '2012-07-13T17:21:57.000Z',
                        'endedAtTime' => '2012-07-13T17:21:57.000Z',
                        'selections' => array('95.0'),
                    );
                } else if ($user_id == 6) {
                    $generated = array(
                        'id' => 'http://test.ipeer.com/events/view/1/questions/1/group/1/user/6/responses/2',
                        'type' => 'RatingScaleResponse',
                        'extensions' =>  array(
                          'evaluatee' => $expected_group_member,
                          'comment' => 'did a decent job',
                          'release_status' => '0',
                          'grade_release' => '0',
                          'record_status' => 'A',
                        ),
                        'dateCreated' => '2012-07-13T17:21:57.000Z',
                        'dateModified' => '2012-07-13T17:21:57.000Z',
                        'endedAtTime' => '2012-07-13T17:21:57.000Z',
                        'selections' => array('105.0'),
                    );
                }

                $expected_events[] = array(
                    'type' => 'AssessmentItemEvent',
                    'profile' => 'AssessmentProfile',
                    'action' => 'Completed',
                    'object' => array(
                        'id' => 'http://test.ipeer.com/events/view/1/questions/1/group/1/user/'.$user_id,
                        'type' => 'AssessmentItem',
                        'isPartOf' => $this->expected_simple_evaluation_event_question,
                    ),
                    'generated' => $generated,
                    'group' => $this->expected_group,
                    'membership' => $this->expected_membership,
                );

                $generated = array(
                    // Rating ids are randomly generated
                    //'id' => 'urn:uuid:30844a22-ffcd-44ed-b6e9-6bf01538ee54',
                    'type' => 'Rating',
                    'rater' => $this->expected_actor,
                    'rated' => $expected_group_member,
                    'question' => $this->expected_simple_evaluation_feedback_question,
                    'selections' => array(),
                    'ratingComment' => array(
                        // Comment ids are randomly generated
                        //'id' => 'urn:uuid:381bd430-429f-407e-b45a-1da9b080ced2',
                        'type' => 'Comment',
                        'commenter' => $this->expected_actor,
                        'commentedOn' => $expected_group_member,
                        'value' => '',
                    ),
                );
                if ($user_id == 5) {
                    $generated['selections'] = array('95.0');
                    $generated['ratingComment']['value'] = 'very hard working';
                } else if ($user_id == 6) {
                    $generated['selections'] = array('105.0');
                    $generated['ratingComment']['value'] = 'did a decent job';
                }
                $expected_events[] = array(
                    'type' => 'FeedbackEvent',
                    'profile' => 'FeedbackProfile',
                    'action' => 'Ranked',
                    'object' => $expected_group_member,
                    'generated' => $generated,
                    'group' => $this->expected_group,
                    'membership' => $this->expected_membership,
                );
            }

        }
        $expected_events[] = array(
            'type' => 'AssessmentEvent',
            'profile' => 'AssessmentProfile',
            'action' => 'Submitted',
            'object' => $this->expected_simple_evaluation_event,
            'group' => $this->expected_group,
            'membership' => $this->expected_membership,
        );
        $expected_events[] = array(
            'type' => 'ToolUseEvent',
            'profile' => 'ToolUseProfile',
            'action' => 'Used',
            'object' => $this->expected_ed_app,
            'group' => $this->expected_group,
            'membership' => $this->expected_membership,
        );

        # check disabled
        CaliperHooks::submit_simple_evaluation(
            $this->simple_evaluation_event_id, $this->user['id'],
            $group_event['id'], $this->group['id']
        );
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        CaliperHooks::submit_simple_evaluation(
            $this->simple_evaluation_event_id, $this->user['id'],
            $group_event['id'], $this->group['id']
        );
        $events = $this->_get_caliper_events();
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }
}