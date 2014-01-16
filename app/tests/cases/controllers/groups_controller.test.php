<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Lib', 'ExtendedAuthTestCase');
App::import('Controller', 'Groups');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('GroupsController',
    'MockGroupsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class GroupsControllerTest extends ExtendedAuthTestCase {
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
        'app.mixeval_question_desc', 'app.mixeval'
    );

    function startCase() {
        echo "Start Group controller test.\n";
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
        $this->controller = new MockGroupsController();
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
        $result = $this->testAction('/groups/index/1', array('return' => 'vars'));

        $this->assertEqual($result['course']['Course']['course'], 'MECH 328');
    }

    function testView() {
        $result = $this->testAction('/groups/view/1', array('return' => 'vars'));

        $this->assertEqual($result['data']['Group']['group_num'], 1);
        $this->assertEqual($result['data']['Group']['group_name'], 'Reapers');
        $this->assertEqual(count($result['data']['Member']), $result['data']['Group']['member_count']);
        $this->assertEqual($result['data']['Member'][0]['full_name'], 'Ed Student');
        $this->assertEqual($result['data']['Member'][1]['full_name'], 'Alex Student');
        $this->assertEqual($result['data']['Member'][2]['full_name'], 'Matt Student');
        $this->assertEqual($result['data']['Member'][3]['full_name'], 'Tutor 1');
    }

    function testAdd() {
        $result = $this->testAction('/groups/add/1', array('return' => 'vars'));

        $this->assertEqual($result['group_num'], 3);
        $this->assertEqual($result['course_id'], 1);
        $this->assertEqual(count($result['user_data']), 15);
        $this->assertEqual($result['user_data'][5], '65498451 Ed Student *');
        $this->assertEqual($result['user_data'][26], '19524032 Bill Student');
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
            'Group' => array(
                'course_id' => 1,
                'group_num' => 3,
                'group_name' => 'Test1',
                'record_status' => 'A',
            ),
            'Member' => array(26, 31, 32),
        );

        $this->controller->expectOnce('redirect', array('index/1'));
        $this->testAction(
            '/groups/add/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $model = ClassRegistry::init('Group');
        $group = $model->find('first', array( 'conditions' => array('group_name' => 'Test1'), 'contain' => array('Member')));
        foreach ($data['Group'] as $key => $expected) {
            $this->assertEqual($group['Group'][$key], $expected);
        }
        $this->assertEqual(count($group['Member']), 3);
        $memberIds = Set::extract($group['Member'], '/id');
        sort($memberIds);
        $this->assertEqual($memberIds, $data['Member']);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The group was added successfully.');
    }

    function testAddWithDataToOthersCourse() {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );
        $data = array(
            'Group' => array(
                'course_id' => 1,
                'group_num' => 3,
                'group_name' => 'Test1',
                'record_status' => 'A',
            ),
            'Member' => array(26, 31, 32),
        );

        $this->controller->expectOnce('redirect', array('/courses'));
        $this->testAction(
            '/groups/add/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $model = ClassRegistry::init('Group');
        $group = $model->find('first', array( 'conditions' => array('group_name' => 'Test1'), 'contain' => array('Member')));
        $this->assertFalse($group);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testEdit() {
        $result = $this->testAction('/groups/edit/1', array('return' => 'vars'));

        $this->assertEqual($result['group_num'], 1);
        $this->assertEqual(count($result['members']), 4);
        $this->assertEqual($result['members'][5], '65498451 Ed Student');

        $this->assertEqual(count($result['user_data']), 11);
        // group member should not be in user_data
        $this->assertFalse(isset($result['user_data'][5]));
        $this->assertEqual($result['user_data'][26], '19524032 Bill Student');
    }

    function testEditWithData() {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor1',
                'password' => md5('ipeeripeer')
            )
        );
        $data = array(
            'Group' => array(
                'id' => 1,
                'course_id' => 1,
                'group_num' => 3,
                'group_name' => 'Test1',
                'record_status' => 'I',
            ),
            'Member' => array(26, 31, 32),
        );

        $this->controller->expectOnce('redirect', array('index/1'));
        $this->testAction(
            '/groups/edit/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $model = ClassRegistry::init('Group');
        $group = $model->find('first', array( 'conditions' => array('id' => 1), 'contain' => array('Member')));
        foreach ($data['Group'] as $key => $expected) {
            $this->assertEqual($group['Group'][$key], $expected);
        }
        $this->assertEqual(count($group['Member']), 3);
        $memberIds = Set::extract($group['Member'], '/id');
        sort($memberIds);
        $this->assertEqual($memberIds, $data['Member']);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The group was updated successfully.');
    }

    function testEditInvalidGroupId() {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor1',
                'password' => md5('ipeeripeer')
            )
        );
        $data = array(
            'Group' => array(
                'id' => 999,
                'course_id' => 1,
                'group_num' => 3,
                'group_name' => 'Test1',
                'record_status' => 'I',
            ),
            'Member' => array(26, 31, 32),
        );

        $this->controller->expectOnce('redirect', array('/courses'));
        $this->testAction(
            '/groups/edit/999',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $model = ClassRegistry::init('Group');
        $group = $model->find('first', array( 'conditions' => array('id' => 999), 'contain' => array('Member')));
        $this->assertFalse($group);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: That group does not exist.');
    }

    function testEditGroupInOthersCourse() {
        // test with instructor account
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );
        $data = array(
            'Group' => array(
                'id' => 1,
                'course_id' => 1,
                'group_num' => 3,
                'group_name' => 'Test1',
                'record_status' => 'I',
            ),
            'Member' => array(26, 31, 32),
        );

        $this->controller->expectOnce('redirect', array('/courses'));
        $this->testAction(
            '/groups/edit/1',
            array('fixturize' => true, 'data' => $data, 'method' => 'post')
        );

        $model = ClassRegistry::init('Group');
        $group = $model->find('first', array( 'conditions' => array('id' => 999), 'contain' => array('Member')));
        $this->assertFalse($group);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testDelete() {
        $this->controller->expectOnce('redirect', array('index/1'));
        $this->testAction(
            '/groups/delete/1',
            array('fixturize' => true, 'method' => 'get')
        );

        $model = ClassRegistry::init('Group');
        $found = $model->find('first', array( 'conditions' => array('id' => 1), 'contain' => false));
        $this->assertFalse($found);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The group was deleted successfully.');
    }

    function testDeleteInvalidGroup() {
        $this->controller->expectOnce('redirect', array('/courses'));
        $this->testAction(
            '/groups/delete/999',
            array('fixturize' => true, 'method' => 'get')
        );

        $model = ClassRegistry::init('Group');
        $found = $model->find('first', array( 'conditions' => array('id' => 999), 'contain' => false));
        $this->assertFalse($found);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: That group does not exist.');
    }

    function testDeleteOthersGroup() {
        $this->login = array(
            'User' => array(
                'username' => 'instructor2',
                'password' => md5('ipeeripeer')
            )
        );
        $this->controller->expectOnce('redirect', array('/courses'));
        $this->testAction(
            '/groups/delete/1',
            array('fixturize' => true, 'method' => 'get')
        );

        $model = ClassRegistry::init('Group');
        $found = $model->find('first', array( 'conditions' => array('id' => 1), 'contain' => false));
        $this->assertTrue($found);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
    }

    function testImport() {
    }

    function testExport() {
    }
}
