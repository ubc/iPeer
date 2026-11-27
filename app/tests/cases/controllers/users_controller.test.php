<?php
App::import('Controller', 'Users');
App::import('Lib', 'ExtendedAuthTestCase');

Mock::generatePartial('UsersController',
    'MockUsersController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class UsersControllerTestCase extends ExtendedAuthTestCase
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
        'app.rubric', 'app.rubrics_criteria',
        'app.rubrics_criteria_comment', 'app.rubrics_lom',
        'app.simple_evaluation', 'app.survey_input', 'app.mixeval_question',
        'app.mixeval_question_desc', 'app.mixeval', 'app.mixeval_question_type',
        'app.user_oauths', 'app.email_schedule',
        'app.email_template',
    );

    public function startCase()
    {
        echo "Start Users controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer'),
            )
        );
    }

    public function endCase()
    {
    }

    public function startTest($method)
    {
        echo $method . TEST_LB;
        $this->controller = new MockUsersController();
    }

    public function endTest($method)
    {
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

    /**
     * A student who is also a tutor in another course must not be enrolled
     * again in a course they are already in.
     */
    public function testEnrolRefusesStudentAlreadyEnrolledWhenAlsoTutor()
    {
        $userModel = ClassRegistry::init('User');
        $userModel->addTutor(7, 4);
        $user = $userModel->getByUsername('redshirt0003');
        // The tutor course and this course both have index 0 in their lists.
        $courseId = Set::extract('/Enrolment/id', $user)[0];

        $this->testAction("/users/enrol/redshirt0003/$courseId", ['fixturize' => true]);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: The student is already enrolled.');
        $enrolModel = ClassRegistry::init('UserEnrol');
        $this->assertEqual($enrolModel->find('count', [
            'conditions' => ['UserEnrol.user_id' => 7, 'UserEnrol.course_id' => $courseId],
        ]), 1);
    }

    /**
     * An instructor must not enrol users in a course they do not teach.
     */
    public function testEnrolRefusesCourseTheInstructorCannotAccess()
    {
        // instructor1 teaches course 1 only; redshirt0001 (user 5) is not in course 2.
        $this->login = ['User' => ['username' => 'instructor1']];

        $this->testAction('/users/enrol/redshirt0001/2', ['fixturize' => true]);

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: Course does not exist or you do not have permission to view this course.');
        $enrolModel = ClassRegistry::init('UserEnrol');
        $this->assertEqual($enrolModel->find('count', [
            'conditions' => ['UserEnrol.user_id' => 5, 'UserEnrol.course_id' => 2],
        ]), 0);
    }

    /**
     * Happy path: merging two students with no shared courses or submissions
     * results in the secondary account being deleted and a success flash message.
     *
     * Uses user id=9 (course 2 only) as primary and user id=13 (course 1 only) as secondary —
     * both are role 5 (student) with no overlapping enrollments, groups, or evaluations.
     */
    public function testMergeHappyPath()
    {
        $userModel = ClassRegistry::init('User');

        $this->testAction('/users/merge', array(
            'fixturize' => true,
            'method' => 'post',
            'data' => array(
                'User' => array(
                    'primaryAccount' => '9',
                    'secondaryAccount' => '13',
                )
            )
        ));

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The two accounts have successfully merged.');

        $secondary = $userModel->find('first', array('conditions' => array('User.id' => 13)));
        $this->assertFalse($secondary, 'Secondary account should have been deleted after merge.');
    }

    /**
     * Single-conflict path: users 5 and 6 are both enrolled in course 1.
     * Verifies that a single shared enrollment (which previously produced
     * invalid SQL via the CakePHP 1.3 'field NOT' => array(one_value) bug)
     * is handled correctly and the merge still succeeds.
     */
    public function testMergeSingleConflictEnrollment()
    {
        $userModel = ClassRegistry::init('User');

        $this->testAction('/users/merge', array(
            'fixturize' => true,
            'method' => 'post',
            'data' => array(
                'User' => array(
                    'primaryAccount' => '5',
                    'secondaryAccount' => '6',
                )
            )
        ));

        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'The two accounts have successfully merged.');

        $secondary = $userModel->find('first', array('conditions' => array('User.id' => 6)));
        $this->assertFalse($secondary, 'Secondary account should have been deleted after merge.');
    }

    private function mergeAccountSearch(string $field, string $value): array
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $_GET = ['action' => 'account', 'field' => $field, 'value' => $value];
        $result = $this->testAction('/users/ajax_merge', ['fixturize' => true, 'return' => 'vars']);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $_GET = [];
        return $result['options'];
    }

    public function testMergeAccountSearchFindsBySearchField()
    {
        $options = $this->mergeAccountSearch('username', 'redshirt0001');
        $this->assertEqual(array_values($options), ['redshirt0001']);
    }

    public function testMergeAccountSearchIgnoresOtherFields()
    {
        $this->assertEqual($this->mergeAccountSearch('password', 'a'), []);
    }

    /**
     * The profile form saves only the profile fields of the signed-in user.
     */
    public function testEditProfileSavesOnlyTheProfileFields()
    {
        $this->login = ['User' => ['username' => 'redshirt0001']];

        $this->testAction('/users/editProfile', ['fixturize' => true, 'data' => [
            'User' => ['first_name' => 'Edd', 'username' => 'renamed',
                'student_no' => '1', 'password' => 'x'],
            'Role' => ['Role' => [1]],
            'Enrolment' => ['Enrolment' => [2]],
        ]]);

        $user = ClassRegistry::init('User')->find('first', [
            'conditions' => ['User.id' => 5], 'recursive' => -1]);
        $this->assertEqual($user['User']['first_name'], 'Edd');
        $this->assertEqual($user['User']['username'], 'redshirt0001');
        $this->assertEqual($user['User']['student_no'], '65498451');
        $this->assertNotEqual($user['User']['password'], 'x');
        $roles = ClassRegistry::init('RolesUser')->find('list', [
            'conditions' => ['RolesUser.user_id' => 5], 'fields' => ['RolesUser.role_id']]);
        $this->assertEqual(array_values($roles), [5]);
        $this->assertEqual(ClassRegistry::init('UserEnrol')->find('count', [
            'conditions' => ['UserEnrol.user_id' => 5, 'UserEnrol.course_id' => 2]]), 0);
    }
}
