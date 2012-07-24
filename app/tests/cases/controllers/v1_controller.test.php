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
        $file = file_get_contents('http://localhost:800/iPeer/v1/users', false, $context);
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
        $file = file_get_contents('http://localhost:800/iPeer/v1/users/17', false, $context);
        
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
        $file = file_get_contents('http://localhost:800/iPeer/v1/users', false, $context);
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
        $file = file_get_contents('http://localhost:800/iPeer/v1/users/'.$id, false, $context);
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
        $file = file_get_contents('http://localhost:800/iPeer/v1/users/'.$id, false, $context);
        $result = json_decode($file, true);
        
        $this->assertEqual($result, null);

    }
    
    public function testCourses()
    {
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
        $actualList = file_get_contents('http://localhost:800/iPeer/v1/courses', null, $context);
        // grab data, which should be in json format since it's the view (no $id);
        $actualList = json_decode($actualList, true);
        $this->assertEqual($expectedList, $actualList);
        
        // get a course with id (method: get) and compare to expected
        $actualCourse = file_get_contents('http://localhost:800/iPeer/v1/courses/1', null, $context);
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
        $id = file_get_contents('http://localhost:800/iPeer/v1/courses', null, $context);
        $id = json_decode($id, true);
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json"
            )
        );
        $context = stream_context_create($opts);
        $checkCourse = file_get_contents('http://localhost:800/iPeer/v1/courses/'.$id, null, $context);
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
        $id = file_get_contents('http://localhost:800/iPeer/v1/courses/'.$id, null, $context);
        $id = json_decode($id, true);
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json"
            )
        );
        $context = stream_context_create($opts);
        $checkCourse = file_get_contents('http://localhost:800/iPeer/v1/courses/'.$id, null, $context);
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
        $id = file_get_contents('http://localhost:800/iPeer/v1/courses/'.$id, null, $context);
        $id = json_decode($id, true);
        
        $this->assertEqual(null, $id);
    }
    
    public function testGrades()
    {
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
        $mixevalGrades = file_get_contents('http://localhost:800/iPeer/v1/courses/1/events/3/grades', null, $context);
        $mixevalGrades = json_decode($mixevalGrades, true);
        $this->assertEqual($mixevalList, $mixevalGrades);

        $studentGrade = file_get_contents('http://localhost:800/iPeer/v1/courses/1/events/3/grades/6', null, $context);
        $studentGrade = json_decode($studentGrade, true);
        $expectedGrade = array("evaluatee" => 6, "score" => 2.4, "id" => 2);
        $this->assertEqual($expectedGrade, $studentGrade);
    }
}
