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
		$this->User =& ClassRegistry::init('User');
		$this->RolesUser =& ClassRegistry::init('RolesUser');
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
        // GET - all users
        $users = $this->_fixtures['app.user']->records;
        $expected = array();

        foreach ($users as $user) {
            $tmp = array();
            $role = array();
            $tmp['id'] = $user['id'];
            $role = $this->RolesUser->find(
                'first',
                array(
                    'conditions' => array('user_id' => $user['id']),
                    'fields' => array('role_id')
                )
            );
            $tmp['role_id'] = $role['RolesUser']['role_id'];
            $tmp['username'] = $user['username'];
            $tmp['last_name'] = $user['last_name'];
            $tmp['first_name'] = $user['first_name'];
            $expected[] = $tmp;
        }
        
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => array('Content-type: application/json')
            )
        );

        $context = stream_context_create($opts);
        $file = file_get_contents('http://localhost/v1/users', false, $context);
        $this->assertEqual($file, json_encode($expected));
        $this->assertEqual(json_decode($file, true), $expected);
        
        // GET - specific user
        $expectedPerson = array(
            'id' => '17',
            'role_id' => '5',
            'username' => '37116036',
            'last_name' => 'Student',
            'first_name' => 'Edna'
        );
        
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => array('Content-type: application/json')
            )
        );

        $context = stream_context_create($opts);
        $file = file_get_contents('http://localhost/v1/users/17', false, $context);
        
        $this->assertEqual($file, json_encode($expectedPerson));
        $this->assertEqual(json_decode($file, true), $expectedPerson);
        
        // POST - add user
        $newUser = array(
            'User' => 
                array('username' => 'coolUser', 'first_name' => 'Jack', 'last_name' => 'Hardy'),
            'Role' =>
                array('RolesUser' => array('role_id' => 5)),
            'Faculty' =>
                array('Faculty' => null),
            'Courses' =>
                array('id' => null),
            'Enrolment' =>
                array()
        );
        $opts = array(
            'http' => array(
                'method' => "POST",
                'header' => array('Content-type: application/json'),
                'content' => json_encode($newUser)
            )
        );

        $context = stream_context_create($opts);
        $file = file_get_contents('http://localhost/v1/users', false, $context);
        $expected = json_decode($file, true);
        $id = $expected['User']['id'];

        $newPerson = array('id' => $id, 'role_id' => '5', 'username' => 'coolUser', 'last_name' => 'Hardy', 'first_name' => 'Jack');
        
        $expectedPerson = array(
            'id' => $expected['User']['id'],
            'role_id' => $expected['Role']['0']['id'],
            'username' => $expected['User']['username'],
            'last_name' => $expected['User']['last_name'],
            'first_name' => $expected['User']['first_name']
        );

        $this->assertEqual($newPerson, $expectedPerson);
        
        // PUT - update user
        $updatedPerson = array(
            'User' => 
                array('id' => $id, 'username' => 'coolUser20', 'first_name' => 'Jane', 'last_name' => 'Hardy')
        );
        
        $expectedPerson = array('id' => $id, 'role_id' => '5', 'username' => 'coolUser20', 'last_name' => 'Hardy', 'first_name' => 'Jane');

        $opts = array(
            'http' => array(
                'method' => "PUT",
                'header' => array('Content-type: application/json'),
                'content' => json_encode($updatedPerson)
            )
        );

        $context = stream_context_create($opts);
        $file = file_get_contents('http://localhost/v1/users/'.$id, false, $context);
        $result = json_decode($file, true);

        $resultPerson = array(
            'id' => $result['User']['id'],
            'role_id' => $result['Role']['0']['id'],
            'username' => $result['User']['username'],
            'last_name' => $result['User']['last_name'],
            'first_name' => $result['User']['first_name']
        );
        
        $this->assertEqual($expectedPerson, $resultPerson);
        
        // DELETE - delete the user
        $opts = array(
            'http' => array(
                'method' => "DELETE",
                'header' => array('Content-type: application/json')
            )
        );

        $context = stream_context_create($opts);
        $file = file_get_contents('http://localhost/v1/users/'.$id, false, $context);
        $result = json_decode($file, true);
        
        $this->assertEqual($result, null);
    }
}
