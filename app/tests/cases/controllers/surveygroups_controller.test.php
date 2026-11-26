<?php
/******************
 * If got Maximum function nesting level of '100' reached
 * Change xdebug.max_nesting_level=200 and max_input_nesting_level=200 in
 * php.ini
 *
 * Details about ExtendedTestCase:
 * http://42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */

App::import('Controller', 'Surveygroups');
App::import('Lib', 'ExtendedAuthTestCase');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('SurveyGroupsController',
    'MockSurveyGroupsController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class SurveygroupsControllerTest extends ExtendedAuthTestCase {
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
        echo "Start SurveyGroup controller test.\n";
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
        $this->controller = new MockSurveyGroupsController();
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
        $result = $this->testAction('/surveygroups/index/1', array('return' => 'contents'));
    }

    private function surveyGroupOf(int $userId)
    {
        return ClassRegistry::init('SurveyGroupMember')->field('group_id', ['user_id' => $userId, 'group_set_id' => 3]);
    }

    // Group set 3 has the survey groups 5, 6 and 7. User 17 is in group 5.
    function testChangeGroupSetMovesAMember()
    {
        $this->testAction('/surveygroups/changegroupset', [
            'fixturize' => true, 'method' => 'post',
            'data' => ['group_set_id' => 3, 'move' => ['17_5_6']],
        ]);

        $this->assertEqual($this->surveyGroupOf(17), 6);
    }

    // A move to a group outside the group set, or a value that is not three
    // ids, changes nothing.
    function testChangeGroupSetRefusesABadMove()
    {
        foreach (['17_5_1', '17_5_x', '17_5', '21_5_6_7'] as $move) {
            $this->controller = new MockSurveyGroupsController();
            $this->testAction('/surveygroups/changegroupset', [
                'fixturize' => true, 'method' => 'post',
                'data' => ['group_set_id' => 3, 'move' => ['17_5_6', $move]],
            ]);

            $message = $this->controller->Session->read('Message.flash');
            $this->assertEqual($message['message'], 'Group set change failed.', $move);
            $this->assertEqual($this->surveyGroupOf(17), 5, $move);
        }
    }
}
