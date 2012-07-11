<?php
class V1ControllerTest extends CakeTestCase {
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.faculty', 'app.department', 'app.course_department',
        'app.user_faculty', 'app.user_tutor'
    );

    public function startCase() {
        echo '<h1>Starting Test Case</h1>';
    }
    public function endCase() {
        echo '<h1>Ending Test Case</h1>';
    }
    public function startTest($method) {
        echo '<h3>Starting method ' . $method . '</h3>';
    }
    public function endTest($method) {
        echo '<hr />';
    }

    public function testUsers()
    {
        $result = $this->testAction('/v1/users', array('return' => 'vars'));
        debug($result);
        $this->assertEqual($result['test'], 'test');
    }
}
