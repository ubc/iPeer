<?php
/* Department Test cases generated on: 2012-05-17 12:27:41 : 1337282861*/
App::import('Model', 'Department');

class DepartmentTestCase extends CakeTestCase {
    public $name = 'Department';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input',
        'app.oauth_token', 'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
    );

    function startTest($method) {
        echo "Start Department model test.";
        $this->Department =& ClassRegistry::init('Department');
    }

    function endTest($method) {
        unset($this->Department);
        ClassRegistry::flush();
    }

    public function testGetIdsByUserId() {
        // superadmin
        /*$result = $this->Department->getIdsByUserId(1);
        sort($result);
        $this->assertEqual($result, array(1,2,3));*/

        // admin
        $result = $this->Department->getIdsByUserId(34);
        sort($result);
        $this->assertEqual($result, array(1,2));

        $result = $this->Department->getIdsByUserId(38);
        sort($result);
        $this->assertEqual($result, array(3));

        // instructor
        $result = $this->Department->getIdsByUserId(2);
        sort($result);
        $this->assertEqual($result, array(1,2));
    }
}
