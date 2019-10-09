<?php
/* Departments Test cases generated on: 2012-05-17 17:14:34 : 1337300074*/
App::import('Controller', 'Departments');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('DepartmentsController',
    'MockDepartmentsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class TestDepartmentsController extends DepartmentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DepartmentsControllerTestCase extends ExtendedAuthTestCase {
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

    function startCase()
    {
        echo "Start Departments controller test.\n";
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
        $this->controller = new MockDepartmentsController();
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
        $result = $this->testAction('/departments/index', array( 'return' => 'contents'));
        $this->assertEqual($result['departments'][0]['id'], 1);
        $this->assertEqual($result['departments'][0]['Name'], 'MECH');
        $this->assertEqual($result['departments'][0]['Faculty'], 'Applied Science');
        $this->assertEqual($result['departments'][1]['id'], 2);
        $this->assertEqual($result['departments'][1]['Name'], 'APSC');
        $this->assertEqual($result['departments'][1]['Faculty'], 'Applied Science');
        $this->assertEqual($result['departments'][2]['id'], 3);
        $this->assertEqual($result['departments'][2]['Name'], 'CPSC');
        $this->assertEqual($result['departments'][2]['Faculty'], 'Science');
	}

	function testView() {
        $result = $this->testAction('/departments/view/1', array( 'return' => 'vars'));
        $this->assertEqual($result['title_for_layout'], 'View Department');
        $this->assertEqual($result['department'], 'MECH');
        $this->assertEqual($result['faculty'], 'Applied Science');
        $courses = $result['courses'][0];
        $courses['Department']['created'] = null;
        $expected = array(
            'Course' => array(
                'id' => 1,
                'course' => 'MECH 328',
                'title' => 'Mechanical Engineering Design Project',
                'homepage' => 'http://www.mech.ubc.ca',
                'self_enroll' => 'off',
                'password' => null,
                'record_status' => 'A',
                'creator_id' => 1,
                'created' => '2006-06-20 14:14:45',
                'updater_id' => null,
                'modified' => '2006-06-20 14:14:45',
                'canvas_id' => null,
                'creator' => 'Super Admin',
                'updater' => null,
                'student_count' => 13,
                'full_name' => 'MECH 328 - Mechanical Engineering Design Project',
                'term' => null,
                'course_w_term' => 'MECH 328',
                'title_w_term' => 'Mechanical Engineering Design Project',
            ),
            'CourseDepartment' => array(
                'id' => 2,
                'course_id' => 1,
                'department_id' => 1,
            ),
            'Department' => array(
                'id' => 1,
                'name' => 'MECH',
                'faculty_id' => 1,
                'creator_id' => 0,
                'created' => null,
                'updater_id' => null,
                'modified' => '2012-05-23 11:30:41',
            ),
        );
        $this->assertEqual($courses, $expected);
	}

	function testAdd() {
        $result = $this->testAction('/departments/add', array('return' => 'vars'));
        $this->assertEqual($result['faculties'][1], 'Applied Science');
        $this->assertEqual($result['faculties'][2], 'Science');
        $this->assertEqual($result['title_for_layout'], 'Add Department');
	}

	function testEdit() {
        $result = $this->testAction('/departments/edit/1', array('return' => 'vars'));
        $this->assertEqual($result['faculties'][1], 'Applied Science');
        $this->assertEqual($result['faculties'][2], 'Science');
        $this->assertEqual($result['title_for_layout'], 'Edit Department');
	}

	function testDelete() {
        $this->testAction('/departments/delete/1');
        $result = $this->testAction('/departments/index', array( 'return' => 'contents'));
        $this->assertEqual($result['departments'][0]['id'], 2);
        $this->assertEqual($result['departments'][0]['Name'], 'APSC');
        $this->assertEqual($result['departments'][0]['Faculty'], 'Applied Science');
        $this->assertEqual($result['departments'][1]['id'], 3);
        $this->assertEqual($result['departments'][1]['Name'], 'CPSC');
        $this->assertEqual($result['departments'][1]['Faculty'], 'Science');
	}

}
