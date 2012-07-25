<?php
class V1ControllerTest extends CakeTestCase {
    public $fixtures = array(
        'app.evaluation_mixeval', 'app.evaluation_rubric',
        'app.evaluation_simple', 'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.faculty', 'app.department', 'app.course_department',
        'app.user_faculty', 'app.user_tutor',
        
    );

    public function startCase() {
        echo '<h1>Starting Test Case</h1>';
		$this->User =& ClassRegistry::init('User');
		$this->RolesUser =& ClassRegistry::init('RolesUser');
		$this->Group =& ClassRegistry::init('Group');
    }
    public function endCase() {
        echo '<h1>Ending Test Case</h1>';
    }
    public function startTest($method) {
        echo '<h3>Starting method ' . $method . '</h3>';
    }
    public function endTest($method) {
        echo '<h3>Ending method ' . $method . '</h3>';
        echo '<hr />';
    }

    public function testUsers()
    {
        $url = Router::url('v1/users', true);
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
        $file = file_get_contents($url, false, $context);
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
        $file = file_get_contents($url.'/17', false, $context);
        
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

        $file = file_get_contents($url, false, $context);
        $user = json_decode($file, true);
        $userId = $user['id'];

        $expectedPerson = array('id' => $userId, 'role_id' => '5', 'username' => 'coolUser', 'last_name' => 'Hardy', 'first_name' => 'Jack');

        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => array('Content-type: application/json'),
            )
        );

        $context = stream_context_create($opts);
        $newPerson = file_get_contents($url.'/'.$userId, false, $context);

        $this->assertEqual(json_decode($newPerson, true), $expectedPerson);
        
        // PUT - update user
        $updatedPerson = array(
            'User' => 
                array('id' => $userId, 'username' => 'coolUser20', 'first_name' => 'Jane', 'last_name' => 'Hardy')
        );
        
        $expectedPerson = array('id' => $userId, 'role_id' => '5', 'username' => 'coolUser20', 'last_name' => 'Hardy', 'first_name' => 'Jane');

        $opts = array(
            'http' => array(
                'method' => "PUT",
                'header' => array('Content-type: application/json'),
                'content' => json_encode($updatedPerson)
            )
        );

        $context = stream_context_create($opts);

        $file = file_get_contents($url.'/'.$userId, false, $context);

        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => array('Content-type: application/json')
            )
        );
        
        $context = stream_context_create($opts);
        $editedPerson = file_get_contents($url.'/'.$userId, false, $context);
        
        $this->assertEqual(json_decode($editedPerson, true), $expectedPerson);
        
        // DELETE - delete the user
        $opts = array(
            'http' => array(
                'method' => "DELETE",
                'header' => array('Content-type: application/json')
            )
        );

        $context = stream_context_create($opts);

        $file = file_get_contents($url.'/'.$userId, false, $context);
        
        if ($this->get_http_response_code($url.'/'.$userId) == 404) {
            $deleteTest = 'successful';
        } else {
            $deleteTest = 'failed';
        }
        
        $this->assertEqual($deleteTest, 'successful');

    }
    
    public function testCourses()
    {
        $url = Router::url('v1/courses', true);
        $courses = $this->_fixtures['app.course']->records;
        $expectedList = array();

        foreach ($courses as $course) {
            $tmp = array();
            $tmp['id'] = $course['id'];
            $tmp['course'] = $course['course'];
            $tmp['title'] = $course['title'];
            $expectedList[] = $tmp;
        }
        $expectedCourse = $expectedList[0];

        // get a list of courses (method: get) and compare to expected
        // see that the proper variables are set for passing to the view (no $id)
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json"
            )
        );
        $context = stream_context_create($opts);
        $actualList = file_get_contents($url, false, $context);
        // grab data, which should be in json format since it's the view (no $id);
        $actualList = json_decode($actualList, true);
        $this->assertEqual($expectedList, $actualList);
        
        // get a course with id (method: get) and compare to expected
        $actualCourse = file_get_contents($url.'/1', false, $context);
        $actualCourse = json_decode($actualCourse, true);
        $this->assertEqual($expectedCourse, $actualCourse);
        
        // add a course (method: post)
        // see that the proper variables are set for passing to the view (no $id)
        $newCourse = array(
            'Course' => array('course' => 'BLAH 789', 'title' => 'Title for Blah Course'),
            'Department' => array('Department' => array('0' => 2))
        );
        $opts = array(
            'http' => array(
                'method' => "POST",
                'header' => "Content-Type: application/json",
                'content' => json_encode($newCourse)
            )
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($url, null, $context);
        $course = json_decode($file, true);
        $id = $course['id'];
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json"
            )
        );
        $context = stream_context_create($opts);
        $checkCourse = file_get_contents($url.'/'.$id, null, $context);
        $checkCourse = json_decode($checkCourse, true);
        $expectedCourse = array('id' => $id, 'course' => 'BLAH 789', 'title' => 'Title for Blah Course');
        $this->assertEqual($expectedCourse, $checkCourse);
        
        // update a course with id (method: put) and compare results to expected
        $updateCourse = array('Course' => array('id' => $id, 'course' => 'BLAH 789', 'title' => 'Updated Title for Blah Course'));
        $opts = array(
            'http' => array(
                'method' => "PUT",
                'header' => "Content-Type: application/json",
                'content' => json_encode($updateCourse)
            )
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($url.'/'.$id, null, $context);
        $course = json_decode($file, true);
        $id = $course['id'];
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json"
            )
        );
        $context = stream_context_create($opts);
        $checkCourse = file_get_contents($url.'/'.$id, null, $context);
        $checkCourse = json_decode($checkCourse, true);
        // what the fields of updated course should be
        $expectedUpdate = array('id' => $id, 'course' => 'BLAH 789', 'title' => 'Updated Title for Blah Course');
        $this->assertEqual($expectedUpdate, $checkCourse);
        
        // delete a course with id (method: delete) and check to see if it still exists after
        $opts = array(
            'http' => array(
                'method' => "DELETE",
                'header' => "Content-Type: application/json"
            )
        );
        $context = stream_context_create($opts);
        file_get_contents($url.'/'.$id, null, $context);
        
        if ($this->get_http_response_code($url.'/'.$id) == 404) {
            $deleteTest = 'successful';
        } else {
            $deleteTest = 'failed';
        }
        
        $this->assertEqual($deleteTest, 'successful');
    }
    
    public function testGroups() {
        $url = Router::url('v1/courses', true);
        // GET - list of groups in course
        $expected = array();
        $groups = $this->_fixtures['app.group']->records;
        
        foreach ($groups as $group) {
            if ('1' == $group['course_id']) {
                $tmp = array();
                $tmp['id'] = $group['id'];
                $tmp['group_num'] = $group['group_num'];
                $tmp['group_name'] = $group['group_name'];
                $tmp['course_id'] = $group['course_id'];
                $expected[] = $tmp;
            }
        }
        
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => array('Content-type: application/json')
            )
        );

        $context = stream_context_create($opts);
        $file = file_get_contents($url.'/1/groups', false, $context);
        
        $this->assertEqual($file, json_encode($expected));
        $this->assertEqual(json_decode($file, true), $expected);
        
        // GET - specific group in course
        $expectedGroup = array();
        $expectedGroup = $expected['1'];
        
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => array('Content-type: application/json')
            )
        );
        
        $context = stream_context_create($opts);
        $file = file_get_contents($url.'/1/groups/2', false, $context);
        
        $this->assertEqual($file, json_encode($expectedGroup));
        $this->assertEqual(json_decode($file, true), $expectedGroup);
        
        // POST - add a group
        
        $addGroup = array(
            'Group' => array(
                'course_id' => '2',
                'group_num' => '1',
                'group_name' => 'Best Group Ever',
                'record_status' =>  'A',
                'source' => null
            ),
            'Member' => array(
                'Member' => array(
                    '0' => '16',
                    '1' => '30',
                    '2' => '25'
                )
            )
        );

        $opts = array(
            'http' => array(
                'method' => "POST",
                'header' => array('Content-type: application/json'),
                'content' => json_encode($addGroup)
            )
        );
        
        $context = stream_context_create($opts);
        $file = file_get_contents($url.'/2/groups', false, $context);

        $expected = json_decode($file, true);
        $id = $expected['id'];
        
        $expectedGroup = array(
            'id' => $id,
            'group_num' => '1',
            'group_name' => 'Best Group Ever',
            'course_id' => 2
        );
        
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => array('Content-type: application/json')
            )
        );
        
        $context = stream_context_create($opts);
        $addedGroup = file_get_contents($url.'/2/groups/'.$id, false, $context);

        $this->assertEqual(json_decode($addedGroup, true), $expectedGroup);
        
        // PUT - edit the group
        
        $editGroup = array(
            'Group' => array(
                'id' => $id,
                'course_id' => '2',
                'group_num' => '1',
                'group_name' => 'Most Amazing Group Ever',
                'record_status' =>  'A',
                'source' => null
            ),
            'Member' => array(
                'Member' => array(
                    '0' => '16',
                    '1' => '30',
                    '2' => '25'
                )
            )
        );
        
        $opts = array(
            'http' => array(
                'method' => "PUT",
                'header' => array('Content-type:application/json'),
                'content' => json_encode($editGroup)
            )
        );
        
        $context = stream_context_create($opts);
        file_get_contents($url.'/2/groups/'.$id, false, $context);

        $expected = json_decode($file, true);
        $id = $expected['id'];
        
        $expectedGroup = array(
            'id' => $id,
            'group_num' => '1',
            'group_name' => 'Most Amazing Group Ever',
            'course_id' => 2
        );
        
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => array('Content-type:application/json'),
            )
        );
        
        $context = stream_context_create($opts);
        $editedGroup = file_get_contents($url.'/2/groups/'.$id, false, $context);
        
        $this->assertEqual(json_decode($editedGroup, true), $expectedGroup);
        
        // DELETE - delete a group
        
        $opts = array(
            'http' => array(
                'method' => "DELETE",
                'header' => array('Content-type:application/json')
            )
        );
        
        $context = stream_context_create($opts);
        file_get_contents($url.'/2/groups/'.$id, false, $context);
        
        if ($this->get_http_response_code($url.'/'.$id) == 404) {
            $deleteTest = 'successful';
        } else {
            $deleteTest = 'failed';
        }
        
        $this->assertEqual($deleteTest, 'successful');
    }
    
    public function testGrades()
    {
        $url = Router::url('v1/courses/1/events/3/grades', true);
        $events = $this->_fixtures['app.event']->records;
        $mixevals = $this->_fixtures['app.evaluation_mixeval']->records;
        $rubrics = $this->_fixtures['app.evaluation_rubric']->records;
        $simples = $this->_fixtures['app.evaluation_simple']->records;
        
        $mixevalList = array();
        $rubricList = array();
        $simpleList = array();
        $listAll = array();
        
        foreach ($mixevals as $data) {
            $tmp = array();
            $tmp['evaluatee'] = $data['evaluatee'];
            $tmp['score'] = $data['score'];
            $tmp['id'] = $data['id'];
            $mixevalList[] = $tmp;
            $listAll[] = $tmp;
        }
        foreach ($rubrics as $data) {
            $tmp = array();
            $tmp['evaluatee'] = $data['evaluatee'];
            $tmp['score'] = $data['score'];
            $tmp['id'] = $data['id'];
            $rubricList[] = $tmp;
            $listAll[] = $tmp;
        }
        foreach ($simples as $data) {
            $tmp = array();
            $tmp['evaluatee'] = $data['evaluatee'];
            $tmp['score'] = $data['score'];
            $tmp['id'] = $data['id'];
            $simpleList[] = $tmp;
            $listAll[] = $tmp;
        }
        
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json"
            )
        );
        $context = stream_context_create($opts);
        $mixevalGrades = file_get_contents($url, null, $context);
        $mixevalGrades = json_decode($mixevalGrades, true);
        $this->assertEqual($mixevalList, $mixevalGrades);

        $studentGrade = file_get_contents($url.'/6', null, $context);
        $studentGrade = json_decode($studentGrade, true);
        $expectedGrade = array("evaluatee" => 6, "score" => 2.4, "id" => 2);
        $this->assertEqual($expectedGrade, $studentGrade);
    }
    
    function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }    
}
