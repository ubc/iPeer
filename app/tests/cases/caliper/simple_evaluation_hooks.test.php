<?php
App::import('Lib', 'CaliperAuthTestCase');
# controllers
App::import('Controller', 'Simpleevaluations');
# caliper
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

// mock instead of needing to create a new controller for every test
Mock::generatePartial(
    'SimpleevaluationsController',
    'MockCaliperSimpleevaluationsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);

/**
 * Caliper Hooks test case
 *
 * @uses $CaliperAuthTestCase
 * @package Tests
 */
class CaliperSimpleEvaluationHooksTest extends CaliperAuthTestCase
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
        echo "Start Caliper simple evaluation hook test.\n";
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
        $this->controller = new MockCaliperSimpleevaluationsController();

        $this->SimpleEvaluation = ClassRegistry::init('SimpleEvaluation');

        $this->SimpleEvaluation->id = 1;
        $results = $this->SimpleEvaluation->find('first', array(
            'conditions' => array('id' => 1)
        ));
        $this->simple_evaluation = $results['SimpleEvaluation'];
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

    function test_simple_evaluation_delete()
    {
        $this->setupSession();
        $expected_stored_data = array(
            'SimpleEvaluation' => $this->simple_evaluation,
            'Question' => array(),
        );
        $expected_event = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Deleted',
            'object' => $this->expected_simple_evaluation,
        );

        # check disabled
        # before_delete
        CaliperHooks::simple_evaluation_before_delete($this->SimpleEvaluation);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->SimpleEvaluation->data));

        # after_delete
        CaliperHooks::simple_evaluation_after_delete($this->SimpleEvaluation);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->SimpleEvaluation->data));

        # check enabled
        $this->_enable_caliper();
        # before_delete
        CaliperHooks::simple_evaluation_before_delete($this->SimpleEvaluation);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));
        $this->assertTrue(array_key_exists('caliper_delete', $this->SimpleEvaluation->data));
        $this->assertEqual($expected_stored_data, $this->SimpleEvaluation->data['caliper_delete']);

        # after_delete
        CaliperHooks::simple_evaluation_after_delete($this->SimpleEvaluation);
        $events = $this->_get_caliper_events();
        $this->assertEqual(1, count($events));
        $this->assertFalse(array_key_exists('caliper_delete', $this->SimpleEvaluation->data));
        $actualEvent = $events[0];
        $this->assertEqual($expected_event, $actualEvent);
    }

    function test_simple_evaluation_save()
    {
        $this->setupSession();
        $expected_event = array(
            'type' => 'ResourceManagementEvent',
            'profile' => 'ResourceManagementProfile',
            'action' => 'Created',
            'object' => $this->expected_simple_evaluation,
        );

        # check disabled
        CaliperHooks::simple_evaluation_after_save($this->SimpleEvaluation, TRUE);
        $events = $this->_get_caliper_events();
        $this->assertEqual(0, count($events));

        # check enabled
        $this->_enable_caliper();

        foreach([True, False] as $created) {
            $expected_event['action'] = $created ? 'Created' : 'Modified';

            CaliperHooks::simple_evaluation_after_save($this->SimpleEvaluation, $created);
            $events = $this->_get_caliper_events();
            $this->assertEqual(1, count($events));
            $actualEvent = $events[0];
            $this->assertEqual($expected_event, $actualEvent);
        }
    }
}