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
    'MockCaliperMixevalEventsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperEventMixevalHooksTest extends CaliperAuthTestCase
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
        echo "Start Caliper event mixeval hook test.\n";
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
        $this->controller = new MockCaliperMixevalEventsController();

        $this->Event = ClassRegistry::init('Event');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->Mixeval = ClassRegistry::init('Mixeval');

        # mixeval event
        $this->mixeval_event_id = 3;
        $results = $this->Event->find('first', array(
            'conditions' => array('Event.id' => $this->mixeval_event_id),
            'contain' => array('Penalty'),
        ));
        $this->mixeval_event = $results['Event'];
        $this->mixeval_penalty = $results['Penalty'];

        $results = $this->Mixeval->find('first', array(
            'conditions' => array('Mixeval.id' => $this->mixeval_event['template_id']),
            'contain' => array('MixevalQuestion', 'MixevalQuestion.MixevalQuestionDesc'),
            'recursive' => 2,
        ));
        $this->mixeval = $results['Mixeval'];
        $this->questions = $results['MixevalQuestion'];

        $this->expected_mixeval_event = array(
            'id' => 'http://test.ipeer.com/events/view/3',
            'type' => 'Assessment',
            'name' => 'Project Evaluation',
            'extensions' => array(
                'event_template_type_id' => '4',
                'template_id' => '1',
                'self_eval' => '0',
                'com_req' => '0',
                'auto_release' => '0',
                'enable_details' => '1',
                'record_status' => 'A',
                'due_date' => $this->mixeval_event['due_date'],
                'release_date_begin' => $this->mixeval_event['release_date_begin'],
                'release_date_end' => $this->mixeval_event['release_date_end'],
                'result_release_date_begin' => $this->mixeval_event['result_release_date_begin'],
                'result_release_date_end' => $this->mixeval_event['result_release_date_end'],
                'type' => 'mixeval',
                'mixeval' => array(
                    'zero_mark' => '0',
                    'availability' => 'public',
                ),
            ),
            'dateCreated' => $this->model_timestamp_to_iso8601($this->mixeval_event['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->mixeval_event['modified']),
            'isPartOf' => $this->expected_course,
            'dateToShow' => $this->model_timestamp_to_iso8601($this->mixeval_event['release_date_begin']),
            'dateToStartOn' => $this->model_timestamp_to_iso8601($this->mixeval_event['release_date_begin']),
            'dateToSubmit' => $this->model_timestamp_to_iso8601($this->mixeval_event['due_date']),
            'items' => array(
                array(
                    'id' => 'http://test.ipeer.com/events/view/3/questions/1',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/3/questions/2',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/3/questions/3',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/3/questions/4',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/3/questions/5',
                    'type' => 'AssessmentItem'
                ),
                array(
                    'id' => 'http://test.ipeer.com/events/view/3/questions/6',
                    'type' => 'AssessmentItem'
                ),
            ),
        );

        $this->expected_mixeval_event_question_1_scale = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/1/scale',
            'type' => 'LikertScale',
            'scalePoints' => 5,
            'itemLabels' => array('Lowest', '', 'Middle', '', 'Highest'),
            'itemValues' => array('0.2', '0.4', '0.6', '0.8', '1'),
        );
        $this->expected_mixeval_event_question_1 = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/1',
            'type' => 'AssessmentItem',
            'name' => 'Participated in Team Meetings',
            'extensions' =>  array(
                'question_num' => '1',
                'required' => '1',
                'self_eval' => '0',
                'scale_level' => '5',
                'show_marks' => '1',
                'question_type' => 'Likert',
                'scale' => $this->expected_mixeval_event_question_1_scale,
            ),
            'isPartOf' => $this->expected_mixeval_event,
        );
        $this->expected_mixeval_event_question_1_scale_without_context = $this->expected_mixeval_event_question_1_scale;
        unset($this->expected_mixeval_event_question_1_scale_without_context['@context']);
        $this->expected_mixeval_feedback_question_1 = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/1?feedback=true',
            'type' => 'RatingScaleQuestion',
            'scale' => $this->expected_mixeval_event_question_1_scale_without_context,
            'questionPosed' => 'Participated in Team Meetings',
        );

        $this->expected_mixeval_event_question_2_scale = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/2/scale',
            'type' => 'LikertScale',
            'scalePoints' => 5,
            'itemLabels' => array('Lowest', '', 'Middle', '', 'Highest'),
            'itemValues' => array('0.2', '0.4', '0.6', '0.8', '1'),
        );
        $this->expected_mixeval_event_question_2 = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/2',
            'type' => 'AssessmentItem',
            'name' =>  'Was Helpful and co-operative',
            'extensions' =>  array(
                'question_num' => '2',
                'required' => '1',
                'self_eval' => '0',
                'scale_level' => '5',
                'show_marks' => '0',
                'question_type' => 'Likert',
                'scale' => $this->expected_mixeval_event_question_2_scale,
            ),
            'isPartOf' => $this->expected_mixeval_event,
        );
        $this->expected_mixeval_event_question_2_scale_without_context = $this->expected_mixeval_event_question_2_scale;
        unset($this->expected_mixeval_event_question_2_scale_without_context['@context']);
        $this->expected_mixeval_feedback_question_2 = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/2?feedback=true',
            'type' => 'RatingScaleQuestion',
            'scale' => $this->expected_mixeval_event_question_2_scale_without_context,
            'questionPosed' => 'Was Helpful and co-operative',
        );

        $this->expected_mixeval_event_question_3_scale = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/3/scale',
            'type' => 'LikertScale',
            'scalePoints' => 5,
            'itemLabels' => array('Lowest', '', 'Middle', '', 'Highest'),
            'itemValues' => array('0.2', '0.4', '0.6', '0.8', '1'),
        );
        $this->expected_mixeval_event_question_3 = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/3',
            'type' => 'AssessmentItem',
            'name' => 'Submitted work on time',
            'extensions' =>  array(
                'question_num' => '3',
                'required' => '1',
                'self_eval' => '0',
                'scale_level' => '5',
                'show_marks' => '1',
                'question_type' => 'Likert',
                'scale' => $this->expected_mixeval_event_question_3_scale,
            ),
            'isPartOf' => $this->expected_mixeval_event,
        );
        $this->expected_mixeval_event_question_3_scale_without_context = $this->expected_mixeval_event_question_3_scale;
        unset($this->expected_mixeval_event_question_3_scale_without_context['@context']);
        $this->expected_mixeval_feedback_question_3 = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/3?feedback=true',
            'type' => 'RatingScaleQuestion',
            'scale' => $this->expected_mixeval_event_question_3_scale_without_context,
            'questionPosed' => 'Submitted work on time',
        );

        $this->expected_mixeval_event_question_4 = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/4',
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
            'isPartOf' => $this->expected_mixeval_event,
        );

        $this->expected_mixeval_event_question_5 = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/5',
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
            'isPartOf' => $this->expected_mixeval_event,
        );

        $this->expected_mixeval_event_question_6 = array(
            'id' => 'http://test.ipeer.com/events/view/3/questions/6',
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
            'isPartOf' => $this->expected_mixeval_event,
        );

        $this->expected_mixeval_event_questions = array(
            $this->expected_mixeval_event_question_1,
            $this->expected_mixeval_event_question_2,
            $this->expected_mixeval_event_question_3,
            $this->expected_mixeval_event_question_4,
            $this->expected_mixeval_event_question_5,
            $this->expected_mixeval_event_question_6,
        );

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

    function test_event_delete_mixeval()
    {
        $this->setupSession();
        $this->Event->id = $this->mixeval_event_id;

        $expected_stored_data = array(
            'Event' => $this->mixeval_event,
            'Penalty' => $this->mixeval_penalty,
            'Course' => $this->course,
        );
        $expected_events = array();
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_mixeval_event,
            'group' => $this->expected_group,
            'membership' => $this->expected_membership,
        );
        foreach($this->expected_mixeval_event_questions as $expected_mixeval_event_question) {
            $expected_events[] = array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Deleted',
                'object' => $expected_mixeval_event_question,
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

    function test_event_save_mixeval()
    {
        $this->setupSession();
        $this->Event->id = $this->mixeval_event_id;

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
                    'object' => $this->expected_mixeval,
                    'generated' => $this->expected_mixeval_event,
                    'group' => $this->expected_group,
                    'membership' => $this->expected_membership,
                );
                foreach($this->expected_mixeval_event_questions as $index => $expected_mixeval_event_question) {
                    $expected_events[] = array(
                        'type' => 'ResourceManagementEvent',
                        'profile' => 'ResourceManagementProfile',
                        'action' => 'Copied',
                        'object' => $this->expected_mixeval_questions[$index],
                        'generated' => $expected_mixeval_event_question,
                        'group' => $this->expected_group,
                        'membership' => $this->expected_membership,
                    );
                }
            } else {
                $expected_events[] = array(
                    'type' => 'ResourceManagementEvent',
                    'profile' => 'ResourceManagementProfile',
                    'action' => 'Modified',
                    'object' => $this->expected_mixeval_event,
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

    function test_event_submit_mixeval()
    {
        $this->_setup_student('redshirt0003', 1);
        $this->setupSession();

        $group_event = $this->GroupEvent->getGroupEventByEventIdGroupId(
            $this->mixeval_event_id, $this->group['id'])['GroupEvent'];

        $expected_events = array();
        foreach($this->group_members as $index => $group_member) {
            $user_id = $group_member['User']['id'];

            if (in_array($user_id, array(5, 6)) ) {
                $expected_group_member = $this->expected_group_members[$index];

                foreach($this->questions as $i => $question) {
                    $question_id = $question['id'];
                    $expected_mixeval_event_question = $this->expected_mixeval_event_questions[$i];

                    if (in_array($question_id, array(1, 2, 3))) {
                        $generated = array(
                            'id' => NULL,
                            'type' => 'RatingScaleResponse',
                            'extensions' => array(
                              'evaluatee' => $expected_group_member,
                              'selected_lom' => NULL,
                              'comment_release' => '0',
                              'record_status' => 'A',
                            ),
                            'dateCreated' => NULL,
                            'dateModified' => NULL,
                            'selections' => array(),
                        );
                        if ($user_id == 5) {
                            $generated['dateCreated'] = '2012-07-13T17:38:20.000Z';
                            $generated['dateModified'] = '2012-07-13T17:38:20.000Z';
                            $generated['selections'] = array('1.00');
                            $generated['extensions']['selected_lom'] = '5';
                            if ($question_id == 1) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/1/group/1/user/5/responses/1';
                            } else if ($question_id == 2) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/2/group/1/user/5/responses/2';
                            } else if ($question_id == 3) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/3/group/1/user/5/responses/3';
                            }
                        } else if ($user_id == 6) {
                            $generated['dateCreated'] = '2012-07-13T17:39:28.000Z';
                            $generated['dateModified'] = '2012-07-13T17:39:28.000Z';
                            $generated['selections'] = array('0.80');
                            $generated['extensions']['selected_lom'] = '4';
                            if ($question_id == 1) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/1/group/1/user/6/responses/7';
                            } else if ($question_id == 2) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/2/group/1/user/6/responses/8';
                            } else if ($question_id == 3) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/3/group/1/user/6/responses/9';
                            }
                        }
                    } else if (in_array($question_id, array(4, 5, 6))) {
                        $generated = array(
                            'id' => NULL,
                            'type' => 'OpenEndedResponse',
                            'extensions' => array(
                              'evaluatee' => $expected_group_member,
                              'selected_lom' => '0',
                              'comment_release' => '0',
                              'record_status' => 'A',
                            ),
                            'dateCreated' => NULL,
                            'dateModified' => NULL,
                            'value' => NULL,
                        );
                        if ($user_id == 5) {
                            $generated['dateCreated'] = '2012-07-13T17:38:20.000Z';
                            $generated['dateModified'] = '2012-07-13T17:38:20.000Z';
                            if ($question_id == 4) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/4/group/1/user/5/responses/4';
                                $generated['value'] = 'work very efficiently';
                            } else if ($question_id == 5) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/5/group/1/user/5/responses/5';
                                $generated['value'] = 'Contributed his part';
                            } else if ($question_id == 6) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/6/group/1/user/5/responses/6';
                                $generated['value'] = 'very easy to work with';
                            }
                        } else if ($user_id == 6) {
                            $generated['dateCreated'] = '2012-07-13T17:39:28.000Z';
                            $generated['dateModified'] = '2012-07-13T17:39:28.000Z';
                            if ($question_id == 4) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/4/group/1/user/6/responses/10';
                                $generated['value'] = 'Yes';
                            } else if ($question_id == 5) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/5/group/1/user/6/responses/11';
                                $generated['value'] = 'He contributed in all parts of the project.';
                            } else if ($question_id == 6) {
                                $generated['id'] = 'http://test.ipeer.com/events/view/3/questions/6/group/1/user/6/responses/12';
                                $generated['value'] = 'He is very easy to communicate with.';
                            }
                        }
                    }

                    $expected_events[] = array(
                        'type' => 'AssessmentItemEvent',
                        'profile' => 'AssessmentProfile',
                        'action' => 'Completed',
                        'object' => array(
                            'id' => 'http://test.ipeer.com/events/view/3/questions/'.$question_id.'/group/1/user/'.$user_id,
                            'type' => 'AssessmentItem',
                            'isPartOf' => $expected_mixeval_event_question,
                        ),
                        'generated' => $generated,
                        'group' => $this->expected_group,
                        'membership' => $this->expected_membership,
                    );

                    if (in_array($question_id, array(1, 2, 3))) {
                        $generated = array(
                            // Rating ids are randomly generated
                            //'id' => 'urn:uuid:30844a22-ffcd-44ed-b6e9-6bf01538ee54',
                            'type' => 'Rating',
                            'rater' => $this->expected_actor,
                            'rated' => $expected_group_member,
                            'question' => NULL,
                            'selections' => array(),
                        );
                        if ($question_id == 1) {
                            $generated['question'] = $this->expected_mixeval_feedback_question_1;
                        } else if ($question_id == 2) {
                            $generated['question'] = $this->expected_mixeval_feedback_question_2;
                        } else if ($question_id == 3) {
                            $generated['question'] = $this->expected_mixeval_feedback_question_3;
                        }
                        if ($user_id == 5) {
                            $generated['selections'] = array('1.00');
                        } else if ($user_id == 6) {
                            $generated['selections'] = array('0.80');
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
                    } else if (in_array($question_id, array(4, 5, 6))) {
                        $generated = array(
                            // Rating ids are randomly generated
                            //'id' => 'urn:uuid:30844a22-ffcd-44ed-b6e9-6bf01538ee54',
                            'type' => 'Comment',
                            'commenter' => $this->expected_actor,
                            'commentedOn' => $expected_group_member,
                            'value' => NULL,
                            'extensions' => array(
                                'questionPosed' => NULL,
                            ),
                        );
                        if ($question_id == 4) {
                            $generated['extensions']['questionPosed'] = 'Produced efficient work?';
                        } else if ($question_id == 5) {
                            $generated['extensions']['questionPosed'] = 'Contributed?';
                        } else if ($question_id == 6) {
                            $generated['extensions']['questionPosed'] = 'Easy to work with?';
                        }
                        if ($user_id == 5) {
                            if ($question_id == 4) {
                                $generated['value'] = 'work very efficiently';
                            } else if ($question_id == 5) {
                                $generated['value'] = 'Contributed his part';
                            } else if ($question_id == 6) {
                                $generated['value'] = 'very easy to work with';
                            }
                        } else if ($user_id == 6) {
                            if ($question_id == 4) {
                                $generated['value'] = 'Yes';
                            } else if ($question_id == 5) {
                                $generated['value'] = 'He contributed in all parts of the project.';
                            } else if ($question_id == 6) {
                                $generated['value'] = 'He is very easy to communicate with.';
                            }
                        }
                        $expected_events[] = array(
                            'type' => 'FeedbackEvent',
                            'profile' => 'FeedbackProfile',
                            'action' => 'Commented',
                            'object' => $expected_group_member,
                            'generated' => $generated,
                            'group' => $this->expected_group,
                            'membership' => $this->expected_membership,
                        );
                    }
                }

                $generated = array(
                    // Rating ids are randomly generated
                    //'id' => 'urn:uuid:30844a22-ffcd-44ed-b6e9-6bf01538ee54',
                    'type' => 'Rating',
                    'rater' => $this->expected_actor,
                    'rated' => $expected_group_member,
                    'question' => array(
                        'id' => 'http://test.ipeer.com/events/view/3/questions/overall?feedback=true',
                        'type' => 'RatingScaleQuestion',
                        'scale' => array(
                            'id' => 'http://test.ipeer.com/events/view/3/questions/overall/scale',
                            'type' => 'NumericScale',
                            'minValue' => 0.0,
                            'maxValue' => 3.0,
                        ),
                    ),
                    'selections' => array(),
                );
                if ($user_id == 5) {
                    $generated['selections'] = array('3.00');
                } else if ($user_id == 6) {
                    $generated['selections'] = array('2.40');
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
            'object' => $this->expected_mixeval_event,
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
        CaliperHooks::submit_mixeval(
            $this->mixeval_event_id, $this->user['id'],
            $group_event['id'], $this->group['id']
        );
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        CaliperHooks::submit_mixeval(
            $this->mixeval_event_id, $this->user['id'],
            $group_event['id'], $this->group['id']
        );
        $events = $this->_get_caliper_events();
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }
}