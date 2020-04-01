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
    'MockCaliperRubricEventsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperEventRubricHooksTest extends CaliperAuthTestCase
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
        echo "Start Caliper event rubric hook test.\n";
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
        $this->controller = new MockCaliperRubricEventsController();

        $this->Event = ClassRegistry::init('Event');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->Rubric = ClassRegistry::init('Rubric');

        # rubric event
        $this->rubric_event_id = 2;
        $results = $this->Event->find('first', array(
            'conditions' => array('Event.id' => $this->rubric_event_id),
            'contain' => array('Penalty'),
        ));
        $this->rubric_event = $results['Event'];
        $this->rubric_penalty = $results['Penalty'];

        $results = $this->Rubric->find('first', array(
            'conditions' => array('Rubric.id' => $this->rubric_event['template_id']),
            'contain' => array(
                'RubricsCriteria',
                'RubricsCriteria.RubricsCriteriaComment',
                'RubricsLom',
            )
        ));
        $this->rubric = $results['Rubric'];
        $this->questions = $results['RubricsCriteria'];
        $this->lom = $results['RubricsLom'];

        $this->expected_rubric_event = array(
            'id' => 'http://test.ipeer.com/events/view/2',
            'type' => 'Assessment',
            'name' => 'Term Report Evaluation',
            'extensions' => array(
                'event_template_type_id' => '2',
                'template_id' => '1',
                'self_eval' => '0',
                'com_req' => '0',
                'auto_release' => '0',
                'enable_details' => '1',
                'record_status' => 'A',
                'due_date' => $this->rubric_event['due_date'],
                'release_date_begin' => $this->rubric_event['release_date_begin'],
                'release_date_end' => $this->rubric_event['release_date_end'],
                'result_release_date_begin' => $this->rubric_event['result_release_date_begin'],
                'result_release_date_end' => $this->rubric_event['result_release_date_end'],
                'penalties' => array(
                    array('days_late' => '1', 'percent_penalty' => '15'),
                    array('days_late' => '2', 'percent_penalty' => '30'),
                    array('days_late' => '3', 'percent_penalty' => '45'),
                    array('days_late' => '4', 'percent_penalty' => '60'),
                ),
                'type' => 'rubric',
                'rubric' => array(
                    'zero_mark' => '0',
                    'lom_max' => '5',
                    'criteria' => '3',
                    'view_mode' => 'student',
                    'availability' => 'public',
                    'template' => 'horizontal',
                ),
            ),
            'dateCreated' => $this->model_timestamp_to_iso8601($this->rubric_event['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->rubric_event['modified']),
            'isPartOf' => $this->expected_course,
            'dateToShow' => $this->model_timestamp_to_iso8601($this->rubric_event['release_date_begin']),
            'dateToStartOn' => $this->model_timestamp_to_iso8601($this->rubric_event['release_date_begin']),
            'dateToSubmit' => $this->model_timestamp_to_iso8601($this->rubric_event['due_date']),
            'items' => array(
                array(
                    'id' => 'http://test.ipeer.com/events/view/2/questions/1',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/2/questions/2',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/2/questions/3',
                    'type' => 'AssessmentItem'
                ),
            ),
        );

        $this->expected_rubric_event_question_1_scale = array(
            'id' => 'http://test.ipeer.com/events/view/2/questions/1/scale',
            'type' => 'LikertScale',
            'extensions' => array(
                'comments' => array('No participation.', 'Little participation.', 'Some participation.', 'Good participation.', 'Great participation.'),
            ),
            'scalePoints' => 5,
            'itemLabels' => array('Poor', 'Below Average', 'Average', 'Above Average', 'Excellent'),
            'itemValues' => array('1', '2', '3', '4', '5'),
        );
        $this->expected_rubric_event_question_1 = array(
            'id' => 'http://test.ipeer.com/events/view/2/questions/1',
            'type' => 'AssessmentItem',
            'name' => 'Participated in Team Meetings',
            'extensions' => array(
                'criteria_num' => '1',
                'multiplier' => '5',
                'show_marks' => '0',
                'scale' => $this->expected_rubric_event_question_1_scale
            ),
            'isPartOf' => $this->expected_rubric_event,
        );
        $this->expected_rubric_event_question_1_scale_without_context = $this->expected_rubric_event_question_1_scale;
        unset($this->expected_rubric_event_question_1_scale_without_context['@context']);
        $this->expected_rubric_feedback_question_1 = array(
            'id' => 'http://test.ipeer.com/events/view/2/questions/1?feedback=true',
            'type' => 'RatingScaleQuestion',
            'scale' => $this->expected_rubric_event_question_1_scale_without_context,
            'questionPosed' => 'Participated in Team Meetings',
        );

        $this->expected_rubric_event_question_2_scale = array(
            'id' => 'http://test.ipeer.com/events/view/2/questions/2/scale',
            'type' => 'LikertScale',
            'extensions' => array(
                'comments' => array(),
            ),
            'scalePoints' => 5,
            'itemLabels' => array('Poor', 'Below Average', 'Average', 'Above Average', 'Excellent'),
            'itemValues' => array('1', '2', '3', '4', '5'),
        );
        $this->expected_rubric_event_question_2 = array(
            'id' => 'http://test.ipeer.com/events/view/2/questions/2',
            'type' => 'AssessmentItem',
            'name' => 'Was Helpful and Co-operative',
            'extensions' => array(
                'criteria_num' => '2',
                'multiplier' => '5',
                'show_marks' => '1',
                'scale' => $this->expected_rubric_event_question_2_scale
            ),
            'isPartOf' => $this->expected_rubric_event,
        );
        $this->expected_rubric_event_question_2_scale_without_context = $this->expected_rubric_event_question_2_scale;
        unset($this->expected_rubric_event_question_2_scale_without_context['@context']);
        $this->expected_rubric_feedback_question_2 = array(
            'id' => 'http://test.ipeer.com/events/view/2/questions/2?feedback=true',
            'type' => 'RatingScaleQuestion',
            'scale' => $this->expected_rubric_event_question_2_scale_without_context,
            'questionPosed' => 'Was Helpful and Co-operative',
        );

        $this->expected_rubric_event_question_3_scale = array(
            'id' => 'http://test.ipeer.com/events/view/2/questions/3/scale',
            'type' => 'LikertScale',
            'extensions' => array(
                'comments' => array(),
            ),
            'scalePoints' => 5,
            'itemLabels' => array('Poor', 'Below Average', 'Average', 'Above Average', 'Excellent'),
            'itemValues' => array('1', '2', '3', '4', '5'),
        );
        $this->expected_rubric_event_question_3 = array(
            'id' => 'http://test.ipeer.com/events/view/2/questions/3',
            'type' => 'AssessmentItem',
            'name' => 'Submitted Work on Time',
            'extensions' => array(
                'criteria_num' => '3',
                'multiplier' => '5',
                'show_marks' => '1',
                'scale' => $this->expected_rubric_event_question_3_scale
            ),
            'isPartOf' => $this->expected_rubric_event,
        );
        $this->expected_rubric_event_question_3_scale_without_context = $this->expected_rubric_event_question_3_scale;
        unset($this->expected_rubric_event_question_3_scale_without_context['@context']);
        $this->expected_rubric_feedback_question_3 = array(
            'id' => 'http://test.ipeer.com/events/view/2/questions/3?feedback=true',
            'type' => 'RatingScaleQuestion',
            'scale' => $this->expected_rubric_event_question_3_scale_without_context,
            'questionPosed' => 'Submitted Work on Time',
        );

        $this->expected_rubric_event_questions = array(
            $this->expected_rubric_event_question_1,
            $this->expected_rubric_event_question_2,
            $this->expected_rubric_event_question_3,
        );


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

    function test_event_delete_rubric()
    {
        $this->setupSession();
        $this->Event->id = $this->rubric_event_id;

        $expected_stored_data = array(
            'Event' => $this->rubric_event,
            'Penalty' => $this->rubric_penalty,
            'Course' => $this->course,
        );
        $expected_events = array();
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_rubric_event,
            'group' => $this->expected_group,
            'membership' => $this->expected_membership,
        );
        foreach($this->expected_rubric_event_questions as $expected_rubric_event_question) {
            $expected_events[] = array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Deleted',
                'object' => $expected_rubric_event_question,
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

    function test_event_save_rubric()
    {
        $this->setupSession();
        $this->Event->id = $this->rubric_event_id;

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
                    'object' => $this->expected_rubric,
                    'generated' => $this->expected_rubric_event,
                    'group' => $this->expected_group,
                    'membership' => $this->expected_membership,
                );
                foreach($this->expected_rubric_event_questions as $index => $expected_rubric_event_question) {
                    $expected_events[] = array(
                        'type' => 'ResourceManagementEvent',
                        'profile' => 'ResourceManagementProfile',
                        'action' => 'Copied',
                        'object' => $this->expected_rubric_questions[$index],
                        'generated' => $expected_rubric_event_question,
                        'group' => $this->expected_group,
                        'membership' => $this->expected_membership,
                    );
                }
            } else {
                $expected_events[] = array(
                    'type' => 'ResourceManagementEvent',
                    'profile' => 'ResourceManagementProfile',
                    'action' => 'Modified',
                    'object' => $this->expected_rubric_event,
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

    function test_event_submit_rubric()
    {
        $this->_setup_student('redshirt0003', 1);
        $this->setupSession();

        $group_event = $this->GroupEvent->getGroupEventByEventIdGroupId(
            $this->rubric_event_id, $this->group['id'])['GroupEvent'];

        $expected_events = array();
        foreach($this->group_members as $index => $group_member) {
            $user_id = $group_member['User']['id'];

            if (in_array($user_id, array(5, 6)) ) {
                $expected_group_member = $this->expected_group_members[$index];

                foreach($this->questions as $i => $question) {
                    $question_id = $question['id'];
                    $expected_rubric_event_question = $this->expected_rubric_event_questions[$i];

                    $generated = array(
                        'id' => NULL,
                        'type' => 'RatingScaleResponse',
                        'extensions' => array(
                            'evaluatee' => $expected_group_member,
                            'selected_lom' => '5',
                            'criteria_comment' => NULL,
                            'comment_release' => '0',
                            'record_status' => 'A',
                        ),
                        'dateCreated' => NULL,
                        'dateModified' => NULL,
                        'selections' => array('5.00'),
                    );
                    if ($user_id == 5) {
                        $generated['dateCreated'] = '2012-07-13T17:30:29.000Z';
                        $generated['dateModified'] = '2012-07-13T17:30:29.000Z';
                        if ($question_id == 1) {
                            $generated['id'] = 'http://test.ipeer.com/events/view/2/questions/1/group/1/user/5/responses/7';
                            $generated['extensions']['criteria_comment'] = 'Yes';
                        } else if ($question_id == 2) {
                            $generated['id'] = 'http://test.ipeer.com/events/view/2/questions/2/group/1/user/5/responses/8';
                            $generated['selections'] = array('4.00');
                            $generated['extensions']['selected_lom'] = '4';
                            $generated['extensions']['criteria_comment'] = 'Absolutely';
                        } else if ($question_id == 3) {
                            $generated['id'] = 'http://test.ipeer.com/events/view/2/questions/3/group/1/user/5/responses/9';
                            $generated['extensions']['criteria_comment'] = 'Definitely';
                        }
                    } else if ($user_id == 6) {
                        $generated['dateCreated'] = '2012-07-13T17:31:19.000Z';
                        $generated['dateModified'] = '2012-07-13T17:31:19.000Z';
                        if ($question_id == 1) {
                            $generated['id'] = 'http://test.ipeer.com/events/view/2/questions/1/group/1/user/6/responses/10';
                            $generated['extensions']['criteria_comment'] = 'attended all of our team meetings';
                        } else if ($question_id == 2) {
                            $generated['id'] = 'http://test.ipeer.com/events/view/2/questions/2/group/1/user/6/responses/11';
                            $generated['extensions']['criteria_comment'] = 'very helpful in all parts of the project';
                        } else if ($question_id == 3) {
                            $generated['id'] = 'http://test.ipeer.com/events/view/2/questions/3/group/1/user/6/responses/12';
                            $generated['extensions']['criteria_comment'] = 'Yes';
                        }
                    }

                    $expected_events[] = array(
                        'type' => 'AssessmentItemEvent',
                        'profile' => 'AssessmentProfile',
                        'action' => 'Completed',
                        'object' => array(
                            'id' => 'http://test.ipeer.com/events/view/2/questions/'.$question_id.'/group/1/user/'.$user_id,
                            'type' => 'AssessmentItem',
                            'isPartOf' => $expected_rubric_event_question,
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
                        'question' => NULL,
                        'selections' => array('5.00'),
                        'ratingComment' => array(
                            // Comment ids are randomly generated
                            //'id' => 'urn:uuid:381bd430-429f-407e-b45a-1da9b080ced2',
                            'type' => 'Comment',
                            'commenter' => $this->expected_actor,
                            'commentedOn' => $expected_group_member,
                            'value' => NULL,
                        ),
                    );
                    if ($question_id == 1) {
                        $generated['question'] = $this->expected_rubric_feedback_question_1;
                    } else if ($question_id == 2) {
                        $generated['question'] = $this->expected_rubric_feedback_question_2;
                    } else if ($question_id == 3) {
                        $generated['question'] = $this->expected_rubric_feedback_question_3;
                    }
                    if ($user_id == 5) {
                        if ($question_id == 1) {
                            $generated['ratingComment']['value'] = 'Yes';
                        } else if ($question_id == 2) {
                            $generated['selections'] = array('4.00');
                            $generated['ratingComment']['value'] = 'Absolutely';
                        } else if ($question_id == 3) {
                            $generated['ratingComment']['value'] = 'Definitely';
                        }
                    } else if ($user_id == 6) {
                        if ($question_id == 1) {
                            $generated['ratingComment']['value'] = 'attended all of our team meetings';
                        } else if ($question_id == 2) {
                            $generated['ratingComment']['value'] = 'very helpful in all parts of the project';
                        } else if ($question_id == 3) {
                            $generated['ratingComment']['value'] = 'Yes';
                        }
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

                $generated = array(
                    // Rating ids are randomly generated
                    //'id' => 'urn:uuid:30844a22-ffcd-44ed-b6e9-6bf01538ee54',
                    'type' => 'Rating',
                    'rater' => $this->expected_actor,
                    'rated' => $expected_group_member,
                    'question' => array(
                        'id' => 'http://test.ipeer.com/events/view/2/questions/overall?feedback=true',
                        'type' => 'RatingScaleQuestion',
                        'scale' => array(
                            'id' => 'http://test.ipeer.com/events/view/2/questions/overall/scale',
                            'type' => 'NumericScale',
                            'minValue' => 3.0,
                            'maxValue' => 15.0,
                        ),
                    ),
                    'selections' => array(),
                    'ratingComment' => array(
                        // Comment ids are randomly generated
                        //'id' => 'urn:uuid:381bd430-429f-407e-b45a-1da9b080ced2',
                        'type' => 'Comment',
                        'commenter' => $this->expected_actor,
                        'commentedOn' => $expected_group_member,
                        'value' => NULL,
                    ),
                );
                if ($user_id == 5) {
                    $generated['selections'] = array('14.00');
                    $generated['ratingComment']['value'] = 'Good group member.';
                } else if ($user_id == 6) {
                    $generated['selections'] = array('15.00');
                    $generated['ratingComment']['value'] = 'Good job.';
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
            'object' => $this->expected_rubric_event,
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
        CaliperHooks::submit_rubric(
            $this->rubric_event_id, $this->user['id'],
            $group_event['id'], $this->group['id']
        );
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        CaliperHooks::submit_rubric(
            $this->rubric_event_id, $this->user['id'],
            $group_event['id'], $this->group['id']
        );
        $events = $this->_get_caliper_events();
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }
}