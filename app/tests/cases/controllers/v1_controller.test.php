<?php
class V1ControllerTest extends CakeTestCase {
    public $fixtures = array(
        'app.evaluation_mixeval', 'app.evaluation_rubric',
        'app.evaluation_simple', 'app.course', 'app.role', 'app.user', 
        'app.group', 'app.roles_user', 'app.event', 'app.event_template_type', 
        'app.group_event', 'app.evaluation_submission', 'app.survey_group_set', 
        'app.survey_group', 'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.faculty', 'app.department', 'app.course_department',
        'app.user_faculty', 'app.user_tutor', 'app.sys_parameter', 
        'app.penalty', 'app.oauth_client', 'app.oauth_token'
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
        $clients = $this->_fixtures['app.oauth_client']->records;
        $tokens = $this->_fixtures['app.oauth_token']->records;
        $this->clientKey = $clients['0']['key'];
        $this->clientSecret = $clients['0']['secret'];
        $this->tokenKey = $tokens['0']['key'];
        $this->tokenSecret = $tokens['0']['secret'];
    }
    public function endTest($method) {
        echo '<h3>Ending method ' . $method . '</h3>';
        echo '<hr />';
    }

    private function _get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }    

    private function _oauthReq($url, $content = null, $reqType = null, 
        $nonce = null, $timestamp = null) 
    {
        try {
            $oauth = new OAuth(
                $this->clientKey,
                $this->clientSecret,
                OAUTH_SIG_METHOD_HMACSHA1,
                OAUTH_AUTH_TYPE_URI
            );
            $oauth->enableDebug();
            $oauth->setToken(
                $this->tokenKey, 
                $this->tokenSecret
            );
            if (!is_null($nonce)) {
                $oauth->setNonce($nonce);
            }
            if (!is_null($timestamp)) {
                $oauth->setTimestamp($timestamp);
            }
            if (is_null($reqType)) {
                $reqType = OAUTH_HTTP_METHOD_GET;
            }
            $oauth->fetch("$url", $content, $reqType);
            $ret = $oauth->getLastResponse();

            return $ret;
        } catch(OAuthException $e) {
            return $e->lastResponse;
        }
    }

    private function _req($url) {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => array('Content-type: application/json'),
                'ignore_errors' => true
            )
        );

        $context = stream_context_create($opts);
        $ret = @file_get_contents($url, false, $context);
        return $ret;
    }

    public function testCheckRequiredParams() {
        $url = Router::url('v1/oauth', true);
        // Test required parameters checking
        // missing oauth_consumer_key
        $params = "oauth_signature_method=HMAC-SHA1&oauth_nonce=7&oauth_timestamp=1&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"oauthError":"Parameter Absent: oauth_consumer_key"}', 
            $this->_req($url."?$params")
        );
        // missing oauth_signature_method
        $params = "oauth_consumer_key=a&oauth_nonce=7&oauth_timestamp=1&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"oauthError":"Parameter Absent: oauth_signature_method"}', 
            $this->_req($url."?$params")
        );
        // missing oauth_nonce
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-SHA1&oauth_timestamp=1&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"oauthError":"Parameter Absent: oauth_nonce"}', 
            $this->_req($url."?$params")
        );
        // missing oauth_timestamp
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-SHA1&oauth_nonce=7&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"oauthError":"Parameter Absent: oauth_timestamp"}', 
            $this->_req($url."?$params")
        );
        // allow no oauth_version
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-SHA1&oauth_nonce=7&oauth_timestamp=1&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"oauthError":"Invalid Client"}', 
            $this->_req($url."?$params")
        );
        // incorrect oauth_version
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-SHA1&oauth_nonce=7&oauth_timestamp=1&oauth_version=2.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"oauthError":"Parameter Rejected: oauth_version 1.0 only"}', 
            $this->_req($url."?$params")
        );
        // incorrect signature method
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-MD5&oauth_nonce=7&oauth_timestamp=1&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"oauthError":"Parameter Rejected: Only HMAC-SHA1 signatures supported."}', 
            $this->_req($url."?$params")
        );
    }

    public function testCheckSignature() {
        $url = Router::url('v1/oauth', true);

        // No errors thrown
        $this->assertEqual('', $this->_oauthReq($url));
        // The client key couldn't be found in the db
        $original = $this->clientKey;
        $this->clientKey = "a";
        $this->assertEqual(
            '{"oauthError":"Invalid Client"}', $this->_oauthReq($url));
        $this->clientKey = $original;
        // The token key couldn't be found in the db
        $original = $this->tokenKey;
        $this->tokenKey = "a";
        $this->assertEqual(
            '{"oauthError":"Invalid Token"}', $this->_oauthReq($url));
        $this->tokenKey = $original;
        // Incorrect client secret 
        $original = $this->clientSecret;
        $this->clientSecret = "a";
        $this->assertEqual(
            '{"oauthError":"Invalid Signature"}', $this->_oauthReq($url));
        $this->clientSecret = $original;
        // Incorrect token secret 
        $original = $this->tokenSecret;
        $this->tokenSecret = "a";
        $this->assertEqual(
            '{"oauthError":"Invalid Signature"}', $this->_oauthReq($url));
        $this->tokenSecret = $original;
        // No errors thrown
        $this->assertEqual('', $this->_oauthReq($url));
    }

    public function testCheckNonce() {
        $url = Router::url('v1/oauth', true);
        // invalid timestamp 
        $this->assertEqual(
            '{"oauthError":"Timestamp Refused"}', 
            $this->_oauthReq($url, null, null, null, 1344974674)
        );
        // test nonce
        $nonce = rand();
        // first use nonce to make sure the nonce is used
        $this->assertEqual("", $this->_oauthReq($url, null, null, $nonce));
        // then try to reuse the nonce and make sure it is rejected
        $this->assertEqual(
            '{"oauthError":"Nonce Used"}', 
            $this->_oauthReq($url, null, null, $nonce)
        );
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
        
        $ret = $this->_oauthReq($url);
        $this->assertEqual($ret, json_encode($expected));
        $this->assertEqual(json_decode($ret, true), $expected);
        
        // GET - specific user
        $expectedPerson = array(
            'id' => '17',
            'role_id' => '5',
            'username' => '37116036',
            'last_name' => 'Student',
            'first_name' => 'Edna'
        );
        
        $ret = $this->_oauthReq($url.'/17');
        $this->assertEqual($ret, json_encode($expectedPerson));
        $this->assertEqual(json_decode($ret, true), $expectedPerson);
        
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

        $file = $this->_oauthReq(
            $url, json_encode($newUser), OAUTH_HTTP_METHOD_POST);
        $user = json_decode($file, true);
        $userId = $user['id'];

        $expectedPerson = array('id' => $userId, 'role_id' => '5', 'username' => 'coolUser', 'last_name' => 'Hardy', 'first_name' => 'Jack');

        $newPerson = $this->_oauthReq("$url/$userId");

        $this->assertEqual(json_decode($newPerson, true), $expectedPerson);
        
        // PUT - update user
        $updatedPerson = array(
            'User' => 
                array('id' => $userId, 'username' => 'coolUser20', 'first_name' => 'Jane', 'last_name' => 'Hardy')
        );
        
        $expectedPerson = array('id' => $userId, 'role_id' => '5', 'username' => 'coolUser20', 'last_name' => 'Hardy', 'first_name' => 'Jane');

        $file = $this->_oauthReq(
            $url, json_encode($updatedPerson), OAUTH_HTTP_METHOD_PUT);

        $editedPerson = $this->_oauthReq("$url/$userId");
        
        $this->assertEqual(json_decode($editedPerson, true), $expectedPerson);
        
        // DELETE - delete the user
        $file = $this->_oauthReq(
            "$url/$userId", 
            null, 
            OAUTH_HTTP_METHOD_DELETE
        );
        
        $ret = $this->_oauthReq("$url/$userId");
        $this->assertEqual(substr($ret, 0, 8), '"No user');
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
        $actualList = $this->_oauthReq($url);
        // grab data, which should be in json format since it's the view (no $id);
        $actualList = json_decode($actualList, true);
        $this->assertEqual($expectedList, $actualList);
        
        // get a course with id (method: get) and compare to expected
        $actualCourse = $this->_oauthReq("$url/1");
        $actualCourse = json_decode($actualCourse, true);
        $this->assertEqual($expectedCourse, $actualCourse);
        
        // add a course (method: post)
        // see that the proper variables are set for passing to the view (no $id)
        $newCourse = array(
            'Course' => array('course' => 'BLAH 789', 'title' => 'Title for Blah Course'),
            'Department' => array('Department' => array('0' => 2))
        );
        $file = $this->_oauthReq(
            $url, json_encode($newCourse), OAUTH_HTTP_METHOD_POST);
        $course = json_decode($file, true);
        $id = $course['id'];
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json"
            )
        );
        $context = stream_context_create($opts);
        $checkCourse = $this->_oauthReq("$url/$id");
        $checkCourse = json_decode($checkCourse, true);
        $expectedCourse = array('id' => $id, 'course' => 'BLAH 789', 'title' => 'Title for Blah Course');
        $this->assertEqual($expectedCourse, $checkCourse);
        
        // update a course with id (method: put) and compare results to expected
        $updateCourse = array('Course' => array('id' => $id, 'course' => 'BLAH 789', 'title' => 'Updated Title for Blah Course'));
        $file = $this->_oauthReq(
            "$url/$id", json_encode($updateCourse), OAUTH_HTTP_METHOD_PUT);
        $course = json_decode($file, true);
        $id = $course['id'];
        $checkCourse = $this->_oauthReq("$url/$id");
        $checkCourse = json_decode($checkCourse, true);
        // what the fields of updated course should be
        $expectedUpdate = array('id' => $id, 'course' => 'BLAH 789', 'title' => 'Updated Title for Blah Course');
        $this->assertEqual($expectedUpdate, $checkCourse);
        
        // delete a course with id (method: delete) and check to see if it still exists after
        $file = $this->_oauthReq(
            "$url/$id", 
            null,
            OAUTH_HTTP_METHOD_DELETE
        );
        
        $ret = $this->_oauthReq("$url/$id");
        $this->assertEqual(substr($ret, 0, 10), '"No course');
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
        
        $file = $this->_oauthReq("$url/1/groups");
        
        $this->assertEqual($file, json_encode($expected));
        $this->assertEqual(json_decode($file, true), $expected);
        
        // GET - specific group in course
        $expectedGroup = array();
        $expectedGroup = $expected['1'];
        
        $file = $this->_oauthReq("$url/1/groups/2");
        
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

        $file = $this->_oauthReq("$url/2/groups", json_encode($addGroup),
            OAUTH_HTTP_METHOD_POST);

        $expected = json_decode($file, true);
        $id = $expected['id'];
        
        $expectedGroup = array(
            'id' => $id,
            'group_num' => '1',
            'group_name' => 'Best Group Ever',
            'course_id' => 2
        );
        
        $addedGroup = $this->_oauthReq("$url/2/groups/$id");

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
        
        $this->_oauthReq("$url/2/groups/$id", json_encode($editGroup), 
            OAUTH_HTTP_METHOD_PUT);

        $expected = json_decode($file, true);
        $id = $expected['id'];
        
        $expectedGroup = array(
            'id' => $id,
            'group_num' => '1',
            'group_name' => 'Most Amazing Group Ever',
            'course_id' => 2
        );
        
        $editedGroup = $this->_oauthReq("$url/2/groups/$id");
        
        $this->assertEqual(json_decode($editedGroup, true), $expectedGroup);
        
        // DELETE - delete a group
        $this->_oauthReq("$url/2/groups/$id", null, OAUTH_HTTP_METHOD_DELETE);
        $ret = $this->_oauthReq("$url/2/groups/$id");
        $this->assertEqual(substr($ret, 0, 9), '"No group');
    }


    public function testEvents()
    {
        $url = Router::url('v1/courses/1/events', true);
        $events = $this->_fixtures['app.event']->records;
        
        $expectedEvents = array();
        
        foreach ($events as $data) {
            $tmp = array();
            $tmp['title'] = $data['title'];
            $tmp['course_id'] = $data['course_id'];
            $tmp['event_template_type_id'] = $data['event_template_type_id'];
            $tmp['id'] = $data['id'];
            $expectedEvents[] = $tmp;
        }
        
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json"
            )
        );
        
        debug(_oauthReq("$url"));
        //$this->assertEqual($expectedEvents, $actualEvents);

        $actualEvent = file_get_contents($url.'/3', null, $context);
        $actualEvent = json_decode($actualEvent, true);
        $expectedEvent = array("title" => 'Project Evaluation', "course_id" => 1, "event_template_type_id" => 4, "id" => 3);
        $this->assertEqual($expectedEvent, $actualEvent);
    }
    
    /*public function testGrades()
    {
        $url = Router::url('v1/courses/1/events/', true);
        $events = $this->_fixtures['app.event']->records;
        $mixevals = $this->_fixtures['app.evaluation_mixeval']->records;
        $rubrics = $this->_fixtures['app.evaluation_rubric']->records;
        $simples = $this->_fixtures['app.evaluation_simple']->records;
        
        $mixevalList = array();
        $rubricList = array();
        $simpleList = array();
        
        foreach ($mixevals as $data) {
            $tmp = array();
            $tmp['evaluatee'] = $data['evaluatee'];
            $tmp['score'] = $data['score'];
            $tmp['id'] = $data['id'];
            $mixevalList[] = $tmp;
        }
        foreach ($rubrics as $data) {
            $tmp = array();
            $tmp['evaluatee'] = $data['evaluatee'];
            $tmp['score'] = $data['score'];
            $tmp['id'] = $data['id'];
            $rubricList[] = $tmp;
        }
        foreach ($simples as $data) {
            $tmp = array();
            $tmp['evaluatee'] = $data['evaluatee'];
            $tmp['score'] = $data['score'];
            $simpleList[] = $tmp;
        }
        
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Content-Type: application/json"
            )
        );
        
        $context = stream_context_create($opts);
        $simpleGrades = file_get_contents($url.'1/grades', null, $context);
        $simpleGrades = json_decode($simpleGrades, true);
        $this->assertEqual($simpleList, $simpleGrades);
        
        $context = stream_context_create($opts);
        $rubricGrades = file_get_contents($url.'2/grades', null, $context);
        $rubricGrades = json_decode($rubricGrades, true);
        $this->assertEqual($rubricList, $rubricGrades);
        
        $context = stream_context_create($opts);
        $mixevalGrades = file_get_contents($url.'3/grades', null, $context);
        $mixevalGrades = json_decode($mixevalGrades, true);
        $this->assertEqual($mixevalList, $mixevalGrades);

        $studentGrade = file_get_contents($url.'1/grades/33', null, $context);
        $studentGrade = json_decode($studentGrade, true);
        $expectedGrade = array("evaluatee" => 33, "score" => 75);
        $this->assertEqual($expectedGrade, $studentGrade);
        
        $studentGrade = file_get_contents($url.'2/grades/5', null, $context);
        $studentGrade = json_decode($studentGrade, true);
        $expectedGrade = array("evaluatee" => 5, "score" => 14, "id" => 3);
        $this->assertEqual($expectedGrade, $studentGrade);
        
        $studentGrade = file_get_contents($url.'3/grades/6', null, $context);
        $studentGrade = json_decode($studentGrade, true);
        $expectedGrade = array("evaluatee" => 6, "score" => 2.4, "id" => 2);
        $this->assertEqual($expectedGrade, $studentGrade);
    }
     */
    
}
