<?php
/* Faculties Test cases generated on: 2012-05-17 14:49:40 : 1337291380*/
App::import('Lib', 'ExtendedAuthTestCase');
App::import('Controller', 'Faculties');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('FacultiesController',
    'MockFacultiesController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));


class FacultiesControllerTestCase extends ExtendedAuthTestCase {
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
        echo "Start Faculties controller test.\n";
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
        $this->controller = new MockFacultiesController();
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
        $return = $this->testAction('/faculties/index', array('return' => 'contents'));
        $this->assertEqual($return['faculties'][0]['id'], 1);
        $this->assertEqual($return['faculties'][0]['Name'], 'Applied Science');
        $this->assertEqual($return['faculties'][1]['id'], 2);
        $this->assertEqual($return['faculties'][1]['Name'], 'Science');
    }

    function testView() {
        $return = $this->testAction('/faculties/view/1', array('return' => 'vars'));
        $this->assertEqual($return['faculty'], 'Applied Science');
        $this->assertEqual($return['departments'][0]['id'], 1);
        $this->assertEqual($return['departments'][0]['Name'], 'MECH');
        $this->assertEqual($return['departments'][1]['id'], 2);
        $this->assertEqual($return['departments'][1]['Name'], 'APSC');
        $this->assertEqual($return['userfaculty'][1]['id'], 2);
        $this->assertEqual($return['userfaculty'][1]['Username'], 'instructor1');
        $this->assertEqual($return['userfaculty'][1]['Full Name'], 'Instructor 1');
        $this->assertEqual($return['userfaculty'][1]['Email'], 'instructor1@email');
        $this->assertEqual($return['userfaculty'][1]['Role'], 'instructor');
        $this->assertEqual($return['userfaculty'][2]['id'], 3);
        $this->assertEqual($return['userfaculty'][2]['Username'], 'instructor2');
        $this->assertEqual($return['userfaculty'][2]['Full Name'], 'Instructor 2');
        $this->assertEqual($return['userfaculty'][2]['Email'], '');
        $this->assertEqual($return['userfaculty'][2]['Role'], 'instructor');
        $this->assertEqual($return['userfaculty'][3]['id'], 4);
        $this->assertEqual($return['userfaculty'][3]['Username'], 'instructor3');
        $this->assertEqual($return['userfaculty'][3]['Full Name'], 'Instructor 3');
        $this->assertEqual($return['userfaculty'][3]['Email'], '');
        $this->assertEqual($return['userfaculty'][3]['Role'], 'instructor');
        $this->assertEqual($return['userfaculty'][4]['id'], 34);
        $this->assertEqual($return['userfaculty'][4]['Username'], 'admin1');
        $this->assertEqual($return['userfaculty'][4]['Full Name'], 'admin1');
        $this->assertEqual($return['userfaculty'][4]['Email'], '');
        $this->assertEqual($return['userfaculty'][4]['Role'], 'admin');
    }

    function testAdd() {

    }

    function testEdit() {
    
    }

    function testDelete() {
        $this->testAction('faculties/delete/1');
        $return = $this->testAction('faculties/index', array('return' => 'contents'));
        $this->assertEqual($return['faculties'][0]['id'], 2);
        $this->assertEqual($return['faculties'][0]['Name'], 'Science');
    }

}
