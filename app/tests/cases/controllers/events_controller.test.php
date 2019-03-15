<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Events');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('EventsController',
    'MockEventsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

/**
 * EventsControllerTest
 *
 * @uses ExtendedAuthTestCase
 * @package Tests
 * @author  Pan Luo <pan.luo@ubc.ca>
 */
class EventsControllerTest extends ExtendedAuthTestCase {
    public $controller = null;

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

    function startCase() {
        echo "Start Event controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
    }

    function endCase() {
    }

    function startTest($method) {
        echo $method.TEST_LB;
        $this->controller = new MockEventsController();
    }

    public function endTest($method)
    {
        // defer logout to end of the test as some of the test need check flash
        // message. After logging out, message is destoryed.
        if (isset($this->controller->Auth)) {
            $this->controller->Auth->logout();
        }
        unset($this->controller);
        ClassRegistry::flush();
    }

    public function getController()
    {
        return $this->controller;
    }

    function testIndex() {
        $result = $this->testAction('/events/index', array('return' => 'vars'));

        $this->assertEqual(count($result["paramsForList"]['data']['entries']),
            17);
        $_event_ids = Set::extract($result["paramsForList"]['data']['entries'], '/Event/id');
        $this->assertEqual(sort($_event_ids), array(1,2,3,6));
        $events = Set::sort($result["paramsForList"]['data']['entries'], '{n}.Event.id', 'asc');

        $this->assertEqual($events[0]['Event']['Title'], 'Term 1 Evaluation');
        $this->assertEqual($events[0]['Event']['event_template_type_id'], 1);
        $this->assertEqual($events[0]['Course']['course'], 'MECH 328');
        $this->assertEqual($events[0]['!Custom']['isReleased'], 'Open Now');

        $this->assertEqual($events[1]['Event']['Title'], 'Term Report Evaluation');
        $this->assertEqual($events[1]['Event']['event_template_type_id'], 2);
        $this->assertEqual($events[1]['Course']['course'], 'MECH 328');
        $this->assertEqual($events[1]['!Custom']['isReleased'], 'Open Now');

        $this->assertEqual($events[2]['Event']['Title'], 'Project Evaluation');
        $this->assertEqual($events[2]['Event']['event_template_type_id'], 4);
        $this->assertEqual($events[2]['Course']['course'], 'MECH 328');
        $this->assertEqual($events[2]['!Custom']['isReleased'], 'Open Now');

    }

    function testView() {
        $result = $this->testAction('/events/view/1', array('return' => 'vars'));

        $this->assertEqual($result['event']['Course']['full_name'], 'MECH 328 - Mechanical Engineering Design Project');
        $this->assertEqual($result['event']['Event']['title'], 'Term 1 Evaluation');
        $this->assertEqual($result['event']['Event']['event_template_type_id'], 1);
        $this->assertEqual(count($result['event']['Group']), 2);
        $groups = Set::sort($result['event']['Group'], '{n}.id', 'asc');
        $this->assertEqual($groups[0]['id'], 1);
        $this->assertEqual($groups[0]['group_name'], 'Reapers');
        $this->assertEqual(count($groups[0]['Member']), 4);
        $this->assertEqual($groups[1]['id'], 2);
        $this->assertEqual($groups[1]['group_name'], 'Lazy Engineers');
        $this->assertEqual(count($groups[1]['Member']), 4);
        $this->assertEqual($result['event']['SimpleEvaluation']['id'], 1);
        $this->assertEqual($result['event']['SimpleEvaluation']['name'], 'Module 1 Project Evaluation');
        $penalties = Set::sort($result['event']['Penalty'], '{n}.id', 'asc');
        $this->assertEqual(Set::extract($penalties, '/id'), array(1,2,3,4));
        $this->assertEqual(Set::extract($penalties, '/days_late'), array(1,2,3,4));
        $this->assertEqual($result['event']['EventTemplateType']['id'], 1);
        $this->assertEqual($result['event']['EventTemplateType']['type_name'], 'SIMPLE');

        $result = $this->testAction('/events/view/2', array('return' => 'vars'));

        $this->assertEqual($result['event']['Event']['title'], 'Term Report Evaluation');
        $this->assertEqual($result['event']['Event']['event_template_type_id'], 2);
        $this->assertEqual($result['event']['Group'][0]['group_name'], 'Reapers');
        $this->assertEqual($result['event']['Group'][0]['Member'][0]['first_name'], 'Ed');
        $this->assertEqual($result['event']['Group'][0]['Member'][0]['last_name'], 'Student');
    }

    function testAdd() {
        $result = $this->testAction('/events/add/1', array('return' => 'vars'));

        // make sure the correct course
        $this->assertEqual($result['course_id'], 1);
        // available evaluations
        $this->assertEqual($result['rubrics'][1], 'Term Report Evaluation');
        $this->assertEqual($result['simpleEvaluations'][1], 'Module 1 Project Evaluation');
        $this->assertEqual($result['mixevals'][1], 'Default Mix Evaluation');
        // evauation types
        $this->assertEqual(count($result['eventTemplateTypes']), 4);
        // course list
        ksort($result['courses']);
        $this->assertEqual($result['courses'], array(
            1 => 'MECH 328 - Mechanical Engineering Design Project',
            2 => 'APSC 201 - Technical Communication',
            4 => 'CPSC 404 - Advanced Software Engineering',
        ));
        // group list
        ksort($result['groups']);
        $this->assertEqual($result['groups'], array(
            1 => 'Reapers',
            2 => 'Lazy Engineers',
        ));
    }

    function testAddWithData() {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor1',
                'password' => md5('ipeeripeer')
            )
        );
        $data = array(
            'Event' => array(
                'title' => 'new evaluation',
                'description' => 'result released with submissiona',
                'event_template_type_id' => 1,
                'SimpleEvaluation' => 1,
                'self_eval' => 0,
                'com_req' => 0,
                'due_date' => '2012-11-28 00:00:01',
                'release_date_begin' => '2012-11-20 00:00:01',
                'release_date_end' => '2012-11-29 00:00:01',
                'result_release_date_begin' => '2012-11-30 00:00:01',
                'result_release_date_end' => '2022-12-12 00:00:01',
                'email_schedule' => 0,
                'EmailTemplate' => 2,
            ),
            'Group' => array(
                'Group' => array(1,2)
            ),
        );
        $this->controller->expectOnce('redirect', array('index/1'));
        $this->testAction(
            '/events/add/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );
        $model = ClassRegistry::init('Event');
        $event = $model->find('first', array( 'conditions' => array('title' => 'new evaluation'), 'contain' => array('Group', 'GroupEvent', 'EvaluationSubmission')));
        unset($data['Event']['SimpleEvaluation'], $data['Event']['email_schedule'], $data['Event']['EmailTemplate']);
        $data['Event']['template_id'] = 1;
        foreach ($data['Event'] as $key => $expected) {
            $this->assertEqual($event['Event'][$key], $expected);
        }
        $this->assertEqual(count($event['Group']), 2);

        // make sure the GroupEvents are added
        $this->assertEqual(count($event['GroupEvent']), 2);

        // make sure no submission
        $this->assertEqual(count($event['EvaluationSubmission']), 0);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Add event successful!');
    }

    function testAddToOthersCourse() {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );
        $data = array(
            'Event' => array(
                'title' => 'new evaluation1',
                'description' => 'result released with submissiona',
                'event_template_type_id' => 1,
                'SimpleEvaluation' => 1,
                'self_eval' => 0,
                'com_req' => 0,
                'due_date' => '2012-11-28 00:00:01',
                'release_date_begin' => '2012-11-20 00:00:01',
                'release_date_end' => '2012-11-29 00:00:01',
                'result_release_date_begin' => '2012-11-30 00:00:01',
                'result_release_date_end' => '2022-12-12 00:00:01',
            ),
            'Group' => array(
                'Group' => array(1,2)
            ),
        );
        $this->controller->expectOnce('redirect', array('/home'));
        $this->testAction(
            '/events/add/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );
        $model = ClassRegistry::init('Event');
        $event = $model->find('first', array( 'conditions' => array('title' => 'new evaluation'), 'contain' => array('Group', 'GroupEvent', 'EvaluationSubmission')));
        $this->assertFalse($event);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testEdit() {
        $result = $this->testAction('/events/edit/1', array('return' => 'vars'));
        $this->assertEqual($result['eventTemplateTypes'][1], 'SIMPLE');
        $this->assertEqual($result['eventTemplateTypes'][2], 'RUBRIC');
        $this->assertEqual($result['eventTemplateTypes'][4], 'MIX EVALUATION');
        $this->assertEqual($result['event']['Event']['title'], 'Term 1 Evaluation');
        $this->assertEqual($result['event']['Event']['event_template_type_id'], 1);
        $this->assertEqual($result['event']['Course']['id'], 1);
        $this->assertEqual(count($result['event']['Penalty']), 4);
        $this->assertEqual(count($result['event']['Group']), 2);
        $this->assertEqual($result['event']['Group'][0]['group_name'], 'Reapers');
        $this->assertEqual($result['event']['Group'][1]['group_name'], 'Lazy Engineers');
    }

    function testEditWithData() {
        $data = array(
            'formLoaded' => true,
            'Event' => array(
                'id' => 8,
                'title' => 'simple evaluation 4a',
                'description' => 'result released with submissiona',
                'event_template_type_id' => 1,
                'SimpleEvaluation' => 1,
                'self_eval' => 0,
                'com_req' => 0,
                'enable_details' => 0,
                'auto_release' => 0,
                'due_date' => '2012-11-28 00:00:01',
                'release_date_begin' => '2012-11-20 00:00:01',
                'release_date_end' => '2012-11-29 00:00:01',
                'result_release_date_begin' => '2012-11-30 00:00:01',
                'result_release_date_end' => '2022-12-12 00:00:01',
                'email_schedule' => 0,
                'EmailTemplate' => 2,
            ),
            'Group' => array(
                'Group' => array(1,2)
            ),
        );
        $this->controller->expectOnce('redirect', array('index/1'));
        $this->testAction(
            '/events/edit/8',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Edit event successful!');

        $model = ClassRegistry::init('Event');
        $event = $model->find('first', array( 'conditions' => array('id' => $data['Event']['id']), 'contain' => array('Group', 'GroupEvent', 'EvaluationSubmission')));
        unset($data['Event']['SimpleEvaluation'], $data['Event']['email_schedule'], $data['Event']['EmailTemplate']);
        $data['Event']['template_id'] = 1;
        foreach ($data['Event'] as $key => $expected) {
            $this->assertEqual($event['Event'][$key], $expected);
        }
        $this->assertEqual(count($event['Group']), 2);

        // make sure the GroupEvent id is not shifted
        $groupEvents = Set::sort($event['GroupEvent'], '{n}.id', 'asc');
        $this->assertEqual($groupEvents[0]['id'], 10);

        // make sure the GroupEvent id is not shifted
        $submissions = Set::sort($event['EvaluationSubmission'], '{n}.id', 'asc');
        $this->assertEqual($submissions[0]['id'], 11);
    }

    function testEditOthersEvent() {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );

        $data = array(
            'Event' => array(
                'id' => 8,
                'title' => 'simple evaluation 4a',
                'description' => 'result released with submissiona',
                'event_template_type_id' => 1,
                'SimpleEvaluation' => 1,
                'self_eval' => 0,
                'com_req' => 0,
                'enable_details' => 1,
                'due_date' => '2012-11-28 00:00:01',
                'release_date_begin' => '2012-11-20 00:00:01',
                'release_date_end' => '2012-11-29 00:00:01',
                'result_release_date_begin' => '2012-11-30 00:00:01',
                'result_release_date_end' => '2022-12-12 00:00:01',
            ),
            'Group' => array(
                'Group' => array(1,2)
            ),
        );
        $this->controller->expectOnce('redirect', array('index'));
        $this->testAction(
            '/events/edit/8',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );
        $model = ClassRegistry::init('Event');
        $event = $model->find('first', array( 'conditions' => array('id' => $data['Event']['id']), 'contain' => array('Group', 'GroupEvent', 'EvaluationSubmission')));
        // data should not be changed
        $this->assertEqual($event['Event']['title'], 'simple evaluation 4');
        $this->assertEqual(count($event['Group']), 1);

        // make sure the GroupEvent id is not shifted
        $this->assertEqual(count($event['GroupEvent']), 1);
        $groupEvents = Set::sort($event['GroupEvent'], '{n}.id', 'asc');
        $this->assertEqual($groupEvents[0]['id'], 10);

        // make sure the submission id is not shifted
        $submissions = Set::sort($event['EvaluationSubmission'], '{n}.id', 'asc');
        $this->assertEqual($submissions[0]['id'], 11);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: That event does not exist or you dont have access to it');
    }

    function testDelete() {
        $this->controller->expectOnce('redirect', array('index/1'));
        $this->testAction(
            '/events/delete/1',
            array('fixturize' => true, 'method' => 'get')
        );

        $model = ClassRegistry::init('Event');
        $found = $model->find('first', array( 'conditions' => array('id' => 1), 'contain' => false));
        $this->assertFalse($found);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The event has been deleted successfully.');
    }

    function testDeletOthers() {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );

        $this->controller->expectOnce('redirect', array('/home'));
        $this->testAction(
            '/events/delete/1',
            array('fixturize' => true, 'method' => 'get')
        );

        $model = ClassRegistry::init('Event');
        $found = $model->find('first', array( 'conditions' => array('id' => 1), 'contain' => false));

        // stil there?
        $this->assertEqual($found['Event']['id'], 1);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: That event does not exist or you dont have access to it');
    }
}
