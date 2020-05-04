<?php
App::import('Lib', 'CaliperAuthTestCase');
# controllers
App::import('Controller', 'Surveys');
# caliper
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

// mock instead of needing to create a new controller for every test
Mock::generatePartial(
    'SurveysController',
    'MockCaliperSurveysController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperSurveyHooksTest extends CaliperAuthTestCase
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
        echo "Start Caliper survey hook test.\n";
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
        $this->controller = new MockCaliperSurveysController();

        $this->Survey = ClassRegistry::init('Survey');

        $this->Survey->id = 2;

        $results = $this->Survey->find('first', array(
            'conditions' => array('Survey.id' => $this->Survey->id),
            'contains' => array(
                'Question' => array(
                    'order' => 'SurveyQuestion.number ASC',
                    'Response',
                ),
            ),
            'recursive' => 2,
        ));
        $this->survey = $results['Survey'];
        $this->questions = $results['Question'];

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

    function test_survey_delete()
    {
        $this->setupSession();
        $expected_stored_data = array(
            'Survey' => $this->survey,
            'Question' => $this->questions,
        );
        $expected_events = array();
        foreach($this->expected_survey_questionnaire_items as $expected_survey_questionnaire_item) {
            $expected_events[] = array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Deleted',
                'object' => $expected_survey_questionnaire_item,
            );
        }
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_survey,
        );

        # check disabled
        # before_delete
        CaliperHooks::survey_before_delete($this->Survey);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Survey->data));

        # after_delete
        CaliperHooks::survey_after_delete($this->Survey);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->Survey->data));

        # check enabled
        $this->_enable_caliper();
        # before_delete
        CaliperHooks::survey_before_delete($this->Survey);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertTrue(array_key_exists('caliper_delete', $this->Survey->data));
        $this->assertEqual($expected_stored_data, $this->Survey->data['caliper_delete']);

        # after_delete
        CaliperHooks::survey_after_delete($this->Survey);
        $events = $this->_get_caliper_events();
        $this->assertFalse(array_key_exists('caliper_delete', $this->Survey->data));
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }

    function test_survey_edit()
    {
        $this->setupSession();
        $expected_event = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Modified',
            'object' => $this->expected_survey,
        );

        # check disabled
        CaliperHooks::survey_edit($this->survey['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        CaliperHooks::survey_edit($this->survey['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(1, count($events));
        $actualEvent = $events[0];
        $this->assertEqual($expected_event, $actualEvent);
    }

    function test_survey_create()
    {
        $this->setupSession();

        # check disabled
        CaliperHooks::survey_create($this->survey['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        $expected_events = array();

        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Created',
            'object' => $this->expected_survey,
        );
        foreach($this->expected_survey_questionnaire_items as $expected_survey_questionnaire_item) {
            $expected_events[] = array(
                'type' => 'ResourceManagementEvent',
                'profile' => 'ResourceManagementProfile',
                'action' => 'Created',
                'object' => $expected_survey_questionnaire_item,
            );
        }

        CaliperHooks::survey_create($this->survey['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }

    function test_survey_edit_question()
    {
        $this->setupSession();
        $expected_event = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Modified',
            'object' => $this->expected_survey_questionnaire_items[0],
        );

        # check disabled
        CaliperHooks::survey_edit_question($this->survey['id'], $this->questions[0]['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        CaliperHooks::survey_edit_question($this->survey['id'], $this->questions[0]['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(1, count($events));
        $actualEvent = $events[0];
        $this->assertEqual($expected_event, $actualEvent);
    }

    function test_survey_remove_question()
    {
        $this->setupSession();
        $expected_events = array();
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_survey_questionnaire_items[0],
        );
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Modified',
            'object' => $this->expected_survey,
        );

        # check disabled
        CaliperHooks::survey_remove_question($this->survey['id'], $this->questions[0]['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        CaliperHooks::survey_remove_question($this->survey['id'], $this->questions[0]['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }

    function test_survey_create_question()
    {
        $this->setupSession();
        $expected_events = array();
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Created',
            'object' => $this->expected_survey_questionnaire_items[0],
        );
        $expected_events[] = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Modified',
            'object' => $this->expected_survey,
        );

        # check disabled
        CaliperHooks::survey_create_question($this->survey['id'], $this->questions[0]['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        CaliperHooks::survey_create_question($this->survey['id'], $this->questions[0]['id']);
        $events = $this->_get_caliper_events();
        $this->assertEqual(count($expected_events), count($events));
        foreach($events as $index => $actualEvent) {
            $this->assertEqual($expected_events[$index], $actualEvent);
        }
    }
}