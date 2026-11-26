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
        $this->controller = null;
        ClassRegistry::flush();
    }

    public function getController()
    {
        return $this->controller;
    }

    function testIndex() {
        $result = $this->testAction('/events/index/1', array('return' => 'vars'));

        $this->assertEqual(count($result["paramsForList"]['data']['entries']),
            17);
        $_event_ids = Set::extract($result["paramsForList"]['data']['entries'], '/Event/id');
        $this->assertEqual(sort($_event_ids), array(1,2,3,6));
        $events = Set::sort($result["paramsForList"]['data']['entries'], '{n}.Event.id', 'asc');

        $this->assertEqual($events[0]['Event']['Title'], 'Term 1 Evaluation');
        $this->assertEqual($events[0]['Event']['event_template_type_id'], 1);
        $this->assertEqual($events[0]['!Custom']['isReleased'], 'Open Now');

        $this->assertEqual($events[1]['Event']['Title'], 'Term Report Evaluation');
        $this->assertEqual($events[1]['Event']['event_template_type_id'], 2);
        $this->assertEqual($events[1]['!Custom']['isReleased'], 'Open Now');

        $this->assertEqual($events[2]['Event']['Title'], 'Project Evaluation');
        $this->assertEqual($events[2]['Event']['event_template_type_id'], 4);
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

    // An event row with an evaluation type that does not exist (old or bad
    // data) shows an error, not a PHP error.
    function testViewEventWithAnUnknownTypeFails() {
        $model = ClassRegistry::init('Event');
        $model->create();
        $model->save(['Event' => [
            'title' => 'event with an unknown type',
            'course_id' => 1,
            'event_template_type_id' => 9,
            'template_id' => 1,
        ]], false);

        $this->testAction("/events/view/{$model->id}", ['return' => 'vars']);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: This event has an unknown evaluation type.');
    }

    function testViewOthersEvent() {
        // instructor2 does not teach MECH 328, the course of event 1
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );

        $this->testAction('/events/view/1', array('fixturize' => true, 'method' => 'get'));
        $this->controller->expectOnce('redirect', array('/home'));

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: That event does not exist or you dont have access to it');
    }

    function testAdd() {
        $result = $this->testAction('/events/add/1', array('return' => 'vars'));

        // make sure the correct course
        $this->assertEqual($result['course_id'], 1);
        // available evaluations: root created the seeded templates
        $this->assertEqual($result['rubrics']['My templates'][1], 'Term Report Evaluation (ID 1)');
        $this->assertEqual($result['simpleEvaluations']['My templates'][1], 'Module 1 Project Evaluation (ID 1)');
        $this->assertEqual($result['mixevals']['My templates'][1], 'Default Mix Evaluation (ID 1)');
        // evauation types
        $this->assertEqual(count($result['eventTemplateTypes']), 4);
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
        $this->testAction(
            '/events/add/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );
        $this->controller->expectOnce('redirect', array('index/1'));
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
        $this->testAction(
            '/events/add/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );
        $this->controller->expectOnce('redirect', array('/home'));
        $model = ClassRegistry::init('Event');
        $event = $model->find('first', array( 'conditions' => array('title' => 'new evaluation'), 'contain' => array('Group', 'GroupEvent', 'EvaluationSubmission')));
        $this->assertFalse($event);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    // A template name that starts or ends with a tab, a line break or a
    // non-breaking space shows without it in the template list (and so in the
    // search box).
    function testAddTrimsTemplateNames() {
        $mixeval = ClassRegistry::init('Mixeval');
        $mixeval->create();
        $mixeval->save(['Mixeval' => [
            'name' => "\tGroup Participation Evaluation \u{00A0}\r\n",
            'availability' => 'public',
            'creator_id' => 1,
        ]], false);
        $id = $mixeval->id;

        $result = $this->testAction('/events/add/1', ['return' => 'vars']);
        $mixeval->delete($id, false);

        // in either group: the test does not depend on who saved it
        $labels = $result['mixevals']['My templates'] + $result['mixevals']['Other templates'];
        $this->assertEqual($labels[$id], "Group Participation Evaluation (ID $id)");
    }

    // The form data names another course, event, groups and penalty; the new
    // event still goes into the course of the URL, and nothing else changes.
    function testAddKeepsTheEventInTheCourseOfTheUrl() {
        // instructor2 teaches course 2 only; the groups 1 and 2, event 8 and
        // penalty 5 (of event 2) belong to course 1
        $this->login = ['User' => ['username' => 'instructor2']];
        $data = [
            'Event' => [
                'id' => 8,
                'course_id' => 1,
                'title' => 'event of course 2',
                'description' => '',
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
            ],
            // groups as a plain list: Cake saves this shape too
            'Group' => [1, 2],
            'Penalty' => [['id' => 5, 'days_late' => 1, 'percent_penalty' => 50]],
            // not part of the form: saveAll() would save it
            'Course' => ['id' => 1, 'title' => 'renamed'],
        ];
        $this->testAction('/events/add/2', ['fixturize' => true, 'data' => $data, 'method' => 'post']);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Add event successful!');
        $model = ClassRegistry::init('Event');
        $event = $model->find('first', [
            'conditions' => ['Event.title' => 'event of course 2'],
            'contain' => ['Group', 'Penalty'],
        ]);
        $this->assertEqual($event['Event']['course_id'], 2);
        $this->assertNotEqual($event['Event']['id'], 8);
        $this->assertEqual(count($event['Group']), 0);
        $this->assertEqual(Set::extract('/Penalty/percent_penalty', $event), [50]);

        $other = $model->find('first', ['conditions' => ['Event.id' => 8], 'contain' => false]);
        $this->assertEqual($other['Event']['title'], 'simple evaluation 4');
        $penalty = ClassRegistry::init('Penalty')->findById(5);
        $this->assertEqual($penalty['Penalty']['event_id'], 2);
        $this->assertEqual($penalty['Penalty']['percent_penalty'], 15);
        $course = ClassRegistry::init('Course')->findById(1);
        $this->assertEqual($course['Course']['title'], 'Mechanical Engineering Design Project');
    }

    // Groups and penalties in the other shapes that Cake saves: one group id
    // instead of a list, and a penalty row wrapped in the model name, with a
    // field the form does not have; a row that is not an array is left out.
    function testAddWithOtherShapesOfGroupsAndPenalties() {
        // instructor2 teaches course 2 only; group 1 and penalty 5 (of
        // event 2) belong to course 1
        $this->login = ['User' => ['username' => 'instructor2']];
        $data = [
            'Event' => [
                'title' => 'event with other shapes',
                'description' => '',
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
            ],
            'Group' => ['Group' => '1'],
            'Penalty' => [
                'x',
                ['Penalty' => ['id' => 5, 'days_late' => 1, 'percent_penalty' => 30, 'event_id' => 2]],
            ],
        ];
        $this->testAction('/events/add/2', ['fixturize' => true, 'data' => $data, 'method' => 'post']);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Add event successful!');
        $event = ClassRegistry::init('Event')->find('first', [
            'conditions' => ['Event.title' => 'event with other shapes'],
            'contain' => ['Group', 'Penalty'],
        ]);
        $this->assertEqual($event['Event']['course_id'], 2);
        $this->assertEqual(count($event['Group']), 0);
        $this->assertEqual(Set::extract('/Penalty/percent_penalty', $event), [30]);
        $this->assertNotEqual($event['Penalty'][0]['id'], 5);

        $penalty = ClassRegistry::init('Penalty')->findById(5);
        $this->assertEqual($penalty['Penalty']['event_id'], 2);
        $this->assertEqual($penalty['Penalty']['percent_penalty'], 15);
    }

    // A course id that is not a whole number does not exist, even when it
    // starts with the id of a course the user can reach.
    function testAddWithACourseIdThatIsNotAWholeNumberFails() {
        $this->testAction('/events/add/1abc', ['return' => 'vars']);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testEditWithAnEventIdThatIsNotAWholeNumberFails() {
        $this->testAction('/events/edit/8abc', ['return' => 'vars']);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: That event does not exist or you dont have access to it');
    }

    // Form data with no event (not an array) is refused before anything is
    // saved or deleted.
    function testAddWithNoEventDataFails() {
        $model = ClassRegistry::init('Event');
        $before = $model->find('count');
        $this->login = ['User' => ['username' => 'instructor2']];
        $this->testAction('/events/add/2', [
            'fixturize' => true,
            'method' => 'post',
            'data' => ['Event' => 'x', 'Group' => ['Group' => '']],
        ]);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Add event failed.');
        $this->assertEqual($model->find('count'), $before);
    }

    function testEditWithNoEventDataFails() {
        // event 8 has one group (group event 10) with a submission (11)
        $this->testAction('/events/edit/8', [
            'fixturize' => true,
            'method' => 'post',
            'data' => ['formLoaded' => 'loaded', 'Event' => 'x', 'Group' => ['Group' => '']],
        ]);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Edit event failed.');
        $event = ClassRegistry::init('Event')->find('first', [
            'conditions' => ['Event.id' => 8],
            'contain' => ['GroupEvent', 'EvaluationSubmission'],
        ]);
        $this->assertEqual($event['Event']['title'], 'simple evaluation 4');
        $this->assertEqual(Set::extract('/GroupEvent/id', $event), [10]);
        $this->assertEqual(Set::extract('/EvaluationSubmission/id', $event), [11]);
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
        $this->testAction(
            '/events/edit/8',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );
        $this->controller->expectOnce('redirect', array('index/1'));

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

    // The form data names another event, course and penalty; the edit still
    // changes only the event of the URL, which stays in its course.
    function testEditKeepsTheEventOfTheUrl() {
        $data = [
            'formLoaded' => true,
            'Event' => [
                'id' => 1,
                'course_id' => 2,
                'title' => 'simple evaluation 4 edited',
                'description' => '',
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
            ],
            'Group' => ['Group' => [1]],
            // penalties 1 and 2 belong to event 1; the second row is wrapped in
            // the model name, which Cake also saves
            'Penalty' => [
                ['id' => 1, 'days_late' => 1, 'percent_penalty' => 50],
                ['Penalty' => ['id' => 2, 'days_late' => 2, 'percent_penalty' => 70]],
            ],
        ];
        $this->testAction('/events/edit/8', ['fixturize' => true, 'data' => $data, 'method' => 'post']);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Edit event successful!');
        $model = ClassRegistry::init('Event');
        $edited = $model->find('first', ['conditions' => ['Event.id' => 8], 'contain' => ['Penalty']]);
        $this->assertEqual($edited['Event']['title'], 'simple evaluation 4 edited');
        $this->assertEqual($edited['Event']['course_id'], 1);
        $this->assertEqual(Set::extract('/Penalty/percent_penalty', $edited), [50, 70]);

        $other = $model->find('first', ['conditions' => ['Event.id' => 1], 'contain' => ['Penalty']]);
        $this->assertEqual($other['Event']['title'], 'Term 1 Evaluation');
        $this->assertEqual(Set::extract('/Penalty/percent_penalty', $other), [20, 40, 60, 100]);
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
        $this->testAction(
            '/events/edit/8',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );
        $this->controller->expectOnce('redirect', array('/home'));
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
        $this->testAction(
            '/events/delete/1',
            array('fixturize' => true, 'method' => 'post', 'data' => array('Event' => array('confirm' => '1')))
        );
        $this->controller->expectOnce('redirect', array('index/1'));

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

        $this->testAction(
            '/events/delete/1',
            array('fixturize' => true, 'method' => 'get')
        );
        $this->controller->expectOnce('redirect', array('/home'));

        $model = ClassRegistry::init('Event');
        $found = $model->find('first', array( 'conditions' => array('id' => 1), 'contain' => false));

        // stil there?
        $this->assertEqual($found['Event']['id'], 1);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: That event does not exist or you dont have access to it');
    }
}
