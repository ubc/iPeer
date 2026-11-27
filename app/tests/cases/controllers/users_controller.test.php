<?php
App::import('Controller', 'Users');
App::import('Lib', 'ExtendedAuthTestCase');

Mock::generatePartial('UsersController',
    'MockUsersController',
    // _renewSession sets a cookie, which fails after the test output starts.
    array('isAuthorized', 'render', 'redirect', '_stop', 'header', '_renewSession'));

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
        putenv('SAML_SETTINGS'); // clear any SAML flags at the end of any test
        putenv('FORCE_SAML_LOGIN');
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

    private function forceSamlLogin()
    {
        putenv('SAML_SETTINGS={}');
        putenv('FORCE_SAML_LOGIN=true');
    }

    private function loginAs($username)
    {
        $this->login = ['User' => ['username' => $username, 'password' => md5('ipeeripeer')]];
    }

    private function postProfile(array $user)
    {
        $this->testAction('/users/editProfile', [
            'fixturize' => true, 'method' => 'post', 'data' => ['User' => $user],
        ]);
    }

    private function storedUser($id)
    {
        return ClassRegistry::init('User')->find('first', [
            'conditions' => ['User.id' => $id], 'recursive' => -1,
        ])['User'];
    }

    /**
     * Hash a password the way the app does. AppController::beforeFilter()
     * sets the hash type and salt, so a test must not depend on an earlier
     * request for them.
     */
    private function hashPassword($password)
    {
        $salt = getenv('IPEER_SECURITY_SALT');
        Security::setHash($salt ? 'sha256' : 'md5');
        Configure::write('Security.salt', $salt ?: '');
        return Security::hash($password, null, true);
    }

    private function setPassword($id, $password)
    {
        ClassRegistry::init('User')->updateAll(
            ['User.password' => "'" . $this->hashPassword($password) . "'"],
            ['User.id' => $id]
        );
    }

    /**
     * A profile save changes only the allowed fields of the logged-in user.
     */
    public function testEditProfileSavesOnlyAllowedFields()
    {
        $this->loginAs('redshirt0001');
        $this->testAction('/users/editProfile', [
            'fixturize' => true, 'method' => 'post',
            'data' => [
                'User' => [
                    'id' => 1, 'first_name' => 'Edna', 'username' => 'root2',
                    'record_status' => 'I', 'title' => 'Dr', 'student_no' => '111',
                ],
                'Role' => ['Role' => [1]],
                'Enrolment' => ['Enrolment' => [1, 2]],
            ],
        ]);

        $student = $this->storedUser(5);
        $this->assertEqual($student['first_name'], 'Edna');
        $this->assertEqual($student['username'], 'redshirt0001');
        $this->assertEqual($student['record_status'], 'A');
        $this->assertNull($student['title']);
        // system.student_number is true in the fixtures.
        $this->assertEqual($student['student_no'], '111');
        $this->assertEqual(ClassRegistry::init('User')->getRoleName(5), 'student');
        $this->assertEqual(ClassRegistry::init('RolesUser')->find('count', [
            'conditions' => ['RolesUser.user_id' => 5],
        ]), 1);
        $this->assertEqual($this->storedUser(1)['username'], 'root');
        $this->assertEqual($this->controller->Session->read('Auth.User.first_name'), 'Edna');
        // The header of the same page reads the stored user.
        $this->assertEqual(User::get('full_name'), 'Edna Student');
    }

    public function testEditProfileKeepsStudentNumberWhenParameterIsOff()
    {
        $param = ClassRegistry::init('SysParameter');
        $param->updateAll(['SysParameter.parameter_value' => "'false'"],
            ['SysParameter.parameter_code' => 'system.student_number']);
        Cache::delete('system.student_number', 'configuration');

        $this->loginAs('redshirt0001');
        $this->postProfile(['first_name' => 'Ed', 'student_no' => '111']);

        $this->assertEqual($this->storedUser(5)['student_no'], '65498451');
        $param->updateAll(['SysParameter.parameter_value' => "'true'"],
            ['SysParameter.parameter_code' => 'system.student_number']);
        Cache::delete('system.student_number', 'configuration');
    }

    public function testEditProfileChangesPasswordWithOldPassword()
    {
        $this->setPassword(2, 'oldsecret');
        $this->loginAs('instructor1');
        // The form posts every field. The stored email fails validation,
        // but it is unchanged, so it does not block the password change.
        $this->postProfile([
            'first_name' => 'Instructor', 'last_name' => '1', 'title' => 'Instructor',
            'email' => 'instructor1@email', 'old_password' => 'oldsecret',
            'temp_password' => 'newsecret', 'confirm_password' => 'newsecret',
        ]);

        $this->assertEqual($this->storedUser(2)['password'], $this->hashPassword('newsecret'));
    }

    public function testEditProfileRefusesBadPasswordChanges()
    {
        $this->setPassword(2, 'oldsecret');
        $before = $this->storedUser(2)['password'];
        $attempts = [
            'Old password is incorrect' =>
                ['old_password' => 'wrong', 'temp_password' => 'newsecret', 'confirm_password' => 'newsecret'],
            'New passwords do not match' =>
                ['old_password' => 'oldsecret', 'temp_password' => 'newsecret', 'confirm_password' => 'other1'],
            'The new password must have at least 6 characters.' =>
                ['old_password' => 'oldsecret', 'temp_password' => 'short', 'confirm_password' => 'short'],
        ];
        foreach ($attempts as $expected => $attempt) {
            $this->loginAs('instructor1');
            $this->postProfile(['first_name' => 'Changed'] + $attempt);
            $message = $this->controller->Session->read('Message.flash');
            $this->assertEqual($message['message'], $expected);
            $user = $this->storedUser(2);
            $this->assertEqual($user['password'], $before);
            $this->assertEqual($user['first_name'], 'Instructor');
        }
    }

    /**
     * With SAML-only sign-in, a student cannot change their profile.
     */
    public function testEditProfileIsReadOnlyForStudentsWithSaml()
    {
        $this->forceSamlLogin();
        $this->loginAs('redshirt0001');
        $this->postProfile(['first_name' => 'Edna', 'email' => 'x@example.com']);

        $student = $this->storedUser(5);
        $this->assertEqual($student['first_name'], 'Ed');
        $this->assertEqual($student['email'], '');
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'You cannot change your profile.');
    }

    /**
     * With SAML-only sign-in, staff can change their profile but not their
     * password.
     */
    public function testEditProfileIgnoresPasswordWithSaml()
    {
        $this->forceSamlLogin();
        $this->setPassword(2, 'oldsecret');
        $before = $this->storedUser(2)['password'];
        $this->loginAs('instructor1');
        $this->postProfile([
            'first_name' => 'Ina', 'old_password' => 'oldsecret',
            'temp_password' => 'newsecret', 'confirm_password' => 'newsecret',
        ]);

        $user = $this->storedUser(2);
        $this->assertEqual($user['first_name'], 'Ina');
        $this->assertEqual($user['password'], $before);
    }

    public function testResetPasswordIsRefusedWithSaml()
    {
        $this->forceSamlLogin();
        $before = $this->storedUser(5)['password'];
        $this->testAction('/users/resetPasswordWithoutEmail/5', ['fixturize' => true]);
        $this->testAction('/users/resetPassword/5', ['fixturize' => true]);

        $this->assertEqual($this->storedUser(5)['password'], $before);
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Passwords are not used: users sign in with SAML.');
    }

    public function testViewHidesPasswordResetWithSaml()
    {
        $this->forceSamlLogin();
        $result = $this->testAction('/users/view/5', ['fixturize' => true, 'return' => 'vars']);

        $urls = array_column($result['bottomActionButtons'], 'url');
        $this->assertFalse(in_array('/users/resetPassword/5', $urls));
        $this->assertFalse(in_array('/users/resetPasswordWithoutEmail/5', $urls));
    }
}
