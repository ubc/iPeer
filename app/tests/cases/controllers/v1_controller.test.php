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
        'app.penalty', 'app.oauth_client', 'app.oauth_token',
        'app.survey_input', 'app.evaluation_rubric_detail', 'app.evaluation_mixeval_detail'
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
            //return $e->lastResponse;
            return $e;
        }
    }

    private function _req($url) {
        // Initializing curl
        $ch = curl_init( $url );

        // Configuring curl options
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
        );

        // Setting curl options
        curl_setopt_array( $ch, $options );

        // Getting results
        $ret = curl_exec($ch); // Getting jSON result string
        return $ret;
    }

    /**
     * helper function to get test cases running in cli on Jenkins
     **/
    private function _getURL($path) {
        $url = Router::url($path, true);
        // check if we're running the tests from the command line, if so,
        // we have to manually construct a proper URL
        if (substr($url,0,4) != "http") {
            // read the server from environment var first
            // so that this can be configured from outside
            if (!($server = getenv('SERVER_TEST'))) {
                $server = 'http://localhost:2000';
            }
            // since the Router isn't handling relative URLs for us, make sure
            // to convert the path to an absolute URL
            if (substr($path,0,1) != '/') {
                $path = "/$path";
            }
            return $server.$path;
        }
        return $url;
    }

    public function testCheckRequiredParams() {
        $url = $this->_getURL('/v1/oauth');
        // Test required parameters checking
        // missing oauth_consumer_key
        $params = "oauth_signature_method=HMAC-SHA1&oauth_nonce=7&oauth_timestamp=1&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Parameter Absent: oauth_consumer_key"}',
            $this->_req($url."?$params")
        );
        // missing oauth_signature_method
        $params = "oauth_consumer_key=a&oauth_nonce=7&oauth_timestamp=1&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Parameter Absent: oauth_signature_method"}',
            $this->_req($url."?$params")
        );
        // missing oauth_nonce
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-SHA1&oauth_timestamp=1&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Parameter Absent: oauth_nonce"}',
            $this->_req($url."?$params")
        );
        // missing oauth_timestamp
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-SHA1&oauth_nonce=7&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Parameter Absent: oauth_timestamp"}',
            $this->_req($url."?$params")
        );
        // allow no oauth_version
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-SHA1&oauth_nonce=7&oauth_timestamp=1&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Invalid Client"}',
            $this->_req($url."?$params")
        );
        // incorrect oauth_version
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-SHA1&oauth_nonce=7&oauth_timestamp=1&oauth_version=2.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Parameter Rejected: oauth_version 1.0 only"}',
            $this->_req($url."?$params")
        );
        // incorrect signature method
        $params = "oauth_consumer_key=a&oauth_signature_method=HMAC-MD5&oauth_nonce=7&oauth_timestamp=1&oauth_version=1.0&oauth_token=1&oauth_signature=4";
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Parameter Rejected: Only HMAC-SHA1 signatures supported."}',
            $this->_req($url."?$params")
        );
    }

    public function testCheckSignature() {
        $url = $this->_getURL('/v1/oauth');

        // No errors thrown
        $this->assertEqual('', $this->_oauthReq($url));
        // The client key couldn't be found in the db
        $original = $this->clientKey;
        $this->clientKey = "a";
        $oauth = $this->_oauthReq($url);
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Invalid Client"}', $oauth->lastResponse);
        $this->clientKey = $original;
        // The token key couldn't be found in the db
        $original = $this->tokenKey;
        $this->tokenKey = "a";
        $oauth = $this->_oauthReq($url);
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Invalid Token"}', $oauth->lastResponse);
        $this->tokenKey = $original;
        // Incorrect client secret
        $original = $this->clientSecret;
        $this->clientSecret = "a";
        $oauth = $this->_oauthReq($url);
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Invalid Signature"}', $oauth->lastResponse);
        $this->clientSecret = $original;
        // Incorrect token secret
        $original = $this->tokenSecret;
        $this->tokenSecret = "a";
        $oauth = $this->_oauthReq($url);
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Invalid Signature"}', $oauth->lastResponse);
        $this->tokenSecret = $original;
        // No errors thrown
        $this->assertEqual('', $this->_oauthReq($url));
    }

    public function testCheckNonce() {
        $url = $this->_getURL('/v1/oauth');
        // invalid timestamp
        $oauth = $this->_oauthReq($url, null, null, null, 1344974674);
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Timestamp Refused"}', $oauth->lastResponse);
        // test nonce
        $nonce = rand();
        // first use nonce to make sure the nonce is used
        $this->assertEqual("", $this->_oauthReq($url, null, null, $nonce));
        // then try to reuse the nonce and make sure it is rejected
        $oauth = $this->_oauthReq($url, null, null, $nonce);
        $this->assertEqual(
            '{"code":100,"message":"OAuth error: Nonce Used"}', $oauth->lastResponse);
    }

    public function testUsers()
    {
        $url = $this->_getURL('/v1/users');
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
        $this->assertEqual(json_decode($ret, true), $expected);

        // GET - specific user
        $expectedPerson = array(
            'id' => '17',
            'role_id' => '5',
            'username' => 'redshirt0013',
            'last_name' => 'Student',
            'first_name' => 'Edna'
        );

        $ret = $this->_oauthReq($url.'/17');
        $this->assertEqual($ret, json_encode($expectedPerson));
        $this->assertEqual(json_decode($ret, true), $expectedPerson);

        // POST - add user
        $newUser = array('username' => 'coolUser', 'first_name' => 'Jack', 'last_name' => 'Hardy', 'role_id' => 5);
        $file = $this->_oauthReq($url, json_encode($newUser), OAUTH_HTTP_METHOD_POST);
        $user = json_decode($file, true);
        $userId = $user['id'];

        $expectedPerson = array('id' => $userId, 'username' => 'coolUser', 'last_name' => 'Hardy', 'first_name' => 'Jack', 'role_id' => 5);
        $this->assertEqual($user, $expectedPerson);

        // POST - add multiple users - test with 2 users
        $newUsers = array(
            array('username' => 'instructor1', 'first_name' => 'Instructor', 'last_name' => '1', 'role_id' => 3),
            array('username' => 'multipleUser1', 'first_name' => 'multiple1', 'last_name' => 'user', 'role_id' => 4)
        );

        $file = $this->_oauthReq($url, json_encode($newUsers), OAUTH_HTTP_METHOD_POST);
        $users = json_decode($file, true);

        function compare($a, $b)
        {
            return strcmp($a['username'], $b['username']);
        }

        usort($users, 'compare');
        $expectedUsers = array();

        foreach ($newUsers as $key => $nu) {
            $expectedUsers[] = array('id' => $users[$key]['id']) + $nu;
        }

        $importUserId = $expectedUsers['1']['id'];

        $this->assertEqual($users, $expectedUsers);

        // PUT - update user
        $updatedPerson = array('id' => $userId, 'username' => 'coolUser20', 'last_name' => 'Hardy', 'first_name' => 'Jane', 'role_id' => 4);

        $expectedPerson = array('id' => $userId, 'username' => 'coolUser20', 'last_name' => 'Hardy', 'first_name' => 'Jane', 'role_id' => 4);

        $file = $this->_oauthReq($url, json_encode($updatedPerson), OAUTH_HTTP_METHOD_PUT);
        $this->assertEqual(json_decode($file, true), $expectedPerson);

        // DELETE - delete the user
        $ret = $this->_oauthReq("$url/$userId", null, OAUTH_HTTP_METHOD_DELETE);
        // there is no output for delete, so we should just expect empty array
        $this->assertEqual(json_decode($ret), array());
        // make sure that the user is really gone
        $this->_oauthReq("$url/$importUserId", null, OAUTH_HTTP_METHOD_DELETE);
        $ret = $this->_oauthReq("$url/$userId");
        $this->assertEqual(substr($ret->debugInfo['headers_recv'], 0, 22), 'HTTP/1.1 404 Not Found');
    }

    public function testCourses()
    {
        $url = $this->_getURL('/v1/courses');
        $courses = $this->_fixtures['app.course']->records;
        $expectedList = array();

        $enrol = array('13', '15', '2', '0');

        foreach ($courses as $key => $course) {
            $tmp = array();
            $tmp['id'] = $course['id'];
            $tmp['course'] = $course['course'];
            $tmp['title'] = $course['title'];
            $tmp['student_count'] = $enrol[$key];
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
        $file = $this->_oauthReq($url, json_encode($newCourse), OAUTH_HTTP_METHOD_POST);
        $checkCourse = json_decode($file, true);
        $id = $checkCourse['id'];
        $expectedCourse = array('id' => $id, 'course' => 'BLAH 789', 'title' => 'Title for Blah Course');
        $this->assertEqual($expectedCourse, $checkCourse);

        // add a course without department (method: post)
        $newCourse = array(
            'Course' => array('id' => 0, 'course' => 'BLAH 790', 'title' => 'Title for Blah Course 790'),
        );
        $file = $this->_oauthReq($url, json_encode($newCourse), OAUTH_HTTP_METHOD_POST);
        $checkCourse = json_decode($file, true);
        $newCourse['Course']['id'] = $checkCourse['id'];
        $this->assertEqual($newCourse['Course'], $checkCourse);

        // update a course with id (method: put) and compare results to expected
        $updateCourse = array('Course' => array('id' => $id, 'course' => 'BLAH 789', 'title' => 'Updated Title for Blah Course'));
        $file = $this->_oauthReq("$url/$id", json_encode($updateCourse), OAUTH_HTTP_METHOD_PUT);
        $checkCourse = json_decode($file, true);
        $id = $checkCourse['id'];
        // what the fields of updated course should be
        $expectedUpdate = array('id' => $id, 'course' => 'BLAH 789', 'title' => 'Updated Title for Blah Course');
        $this->assertEqual($expectedUpdate, $checkCourse);

        // delete a course with id (method: delete) and check to see if it still exists after
        $file = $this->_oauthReq("$url/$id", null, OAUTH_HTTP_METHOD_DELETE);

        $ret = $this->_oauthReq("$url/$id");
        $this->assertEqual(substr($ret->debugInfo['headers_recv'], 0, 22), 'HTTP/1.1 404 Not Found');
    }

    public function testGroups() {
        $url = $this->_getURL('/v1/courses');
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
                $tmp['member_count'] = "4";
                $expected[] = $tmp;
            }
        }
        $file = $this->_oauthReq("$url/1/groups");
        $this->assertEqual(json_decode($file, true), $expected);

        // GET - specific group in course
        $expectedGroup = array();
        $expectedGroup = $expected['1'];
        $file = $this->_oauthReq("$url/1/groups/2");
        $this->assertEqual($file, json_encode($expectedGroup));
        $this->assertEqual(json_decode($file, true), $expectedGroup);

        // POST - add a group - need to split members from group to be it's own function
        $addGroup = array(
            'course_id' => '2',
            'group_num' => '1',
            'group_name' => 'Best Group Ever',
        );
        $file = $this->_oauthReq("$url/2/groups", json_encode($addGroup), OAUTH_HTTP_METHOD_POST);
        $addedGroup = json_decode($file, true);
        $id = $addedGroup['id'];
        $expectedGroup = array(
            'id' => $id,
            'group_num' => '1',
            'group_name' => 'Best Group Ever',
            'course_id' => 2,
            'member_count' => '0' // newly created group, should have no members
        );
        $this->assertEqual($addedGroup, $expectedGroup);

        // PUT - edit the group - need to split member from group
        $editGroup = array(
            'id' => $id,
            'course_id' => '2',
            'group_num' => '1',
            'group_name' => 'Most Amazing Group Ever'
        );
        $file = $this->_oauthReq("$url/2/groups/$id", json_encode($editGroup), OAUTH_HTTP_METHOD_PUT);
        $updatedGroup = json_decode($file, true);
        $expectedGroup = array(
            'id' => $id,
            'group_num' => '1',
            'group_name' => 'Most Amazing Group Ever',
            'course_id' => 2,
            'member_count' => 0
        );

        // DELETE - delete a group
        $ret = $this->_oauthReq("$url/2/groups/$id", null, OAUTH_HTTP_METHOD_DELETE);
        $this->assertEqual(json_decode($ret), array());
        $ret = $this->_oauthReq("$url/2/groups/$id");
        $this->assertEqual(substr($ret->debugInfo['headers_recv'], 0, 22), 'HTTP/1.1 404 Not Found');
    }

    public function testGroupMembers()
    {
        $url = $this->_getURL('/v1/groups/1/users');

        // HTTP GET, get a list of group members
        $expectedGroup = array(
            array('id' => '5', 'role_id' => '5', 'username' => 'redshirt0001', 'last_name' => 'Student', 'first_name' => 'Ed'),
            array('id' => '6', 'role_id' => '5', 'username' => 'redshirt0002', 'last_name' => 'Student', 'first_name' => 'Alex'),
            array('id' => '7', 'role_id' => '5', 'username' => 'redshirt0003', 'last_name' => 'Student', 'first_name' => 'Matt'),
            array('id' => '35', 'role_id' => '4', 'username' => 'tutor1', 'last_name' => '1', 'first_name' => 'Tutor')
        );

        $actualGroup = $this->_oauthReq("$url");
        $this->assertEqual(json_decode($actualGroup, true), $expectedGroup);

        // HTTP POST, try to assign students to a group
        $toBeAdded = array(
            array('username' => 'redshirt0004'),
            array('username' => 'redshirt0005'),
            array('username' => 'redshirt0006'));
        $addedMembers = $this->_oauthReq("$url", json_encode($toBeAdded), OAUTH_HTTP_METHOD_POST);

        $this->assertEqual(json_decode($addedMembers, true), $toBeAdded);

        // HTTP DELETE, try to remove students from a group
        $ret = $this->_oauthReq("$url/redshirt0004",null,OAUTH_HTTP_METHOD_DELETE);
        $this->assertEqual(json_decode($ret, true), array());
        $ret = $this->_oauthReq("$url/redshirt0005",null,OAUTH_HTTP_METHOD_DELETE);
        $this->assertEqual(json_decode($ret, true), array());
        $ret = $this->_oauthReq("$url/redshirt0006",null,OAUTH_HTTP_METHOD_DELETE);
        $this->assertEqual(json_decode($ret, true), array());
        // confirm that the group is back to what it was before
        $ret = $this->_oauthReq("$url");
        $this->assertEqual(json_decode($ret, true), $expectedGroup);
    }

    public function testEvents()
    {
        $url = $this->_getURL('/v1/courses');
        $events = $this->_fixtures['app.event']->records;

        $expectedEvents = array();

        foreach ($events as $data) {
            $tmp = array();
            $tmp['title'] = $data['title'];
            $tmp['course_id'] = $data['course_id'];
            $tmp['event_template_type_id'] = $data['event_template_type_id'];
            $tmp['due_date'] = $data['due_date'];
            $tmp['id'] = $data['id'];
            $expectedEvents[] = $tmp;
        }

        // test get all events of a course
        $actualEvents = $this->_oauthReq("$url/1/events");
        $this->assertEqual($actualEvents, json_encode($expectedEvents));
        $this->assertEqual(json_decode($actualEvents, true), $expectedEvents);

        // test get specific event from a course
        $actualEvent = $this->_oauthReq("$url/1/events/3");
        $expectedEvent = array("title" => "Project Evaluation", "course_id" => "1", "event_template_type_id" => "4", "due_date" => "2013-07-02 09:00:28", "id" => "3");
        $this->assertEqual($actualEvent, json_encode($expectedEvent));
        $this->assertEqual(json_decode($actualEvent, true), $expectedEvent);
    }

    public function testGrades()
    {
        $url = $this->_getURL('/v1/events');

        // prep expected results
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
            $tmp['username'] = $this->User->field('username',
                array('id' => $data['evaluatee']));
            $tmp['score'] = $data['score'];
            $mixevalList[] = $tmp;
        }
        array_pop($mixevalList);

        foreach ($rubrics as $data) {
            $tmp = array();
            $tmp['evaluatee'] = $data['evaluatee'];
            $tmp['username'] = $this->User->field('username',
                array('id' => $data['evaluatee']));
            $tmp['score'] = $data['score'];
            $rubricList[] = $tmp;
        }
        foreach ($simples as $data) {
            if ($data['event_id'] != 1) continue; # only care about event 1
            $tmp = array();
            $tmp['evaluatee'] = $data['evaluatee'];
            $tmp['username'] = $this->User->field('username',
                array('id' => $data['evaluatee']));
            $tmp['score'] = $data['score'];
            $simpleList[] = $tmp;
        }

        // test get all grades for an event of a course (test multiple types)
        // evaluation simple
        $simpleGrades = $this->_oauthReq("$url/1/grades");
        $simpleGrades = json_decode($simpleGrades, true);
        $this->assertEqual($simpleList, $simpleGrades);
        // evaluation rubric
        $rubricGrades = $this->_oauthReq("$url/2/grades");
        $rubricGrades = json_decode($rubricGrades, true);
        $this->assertEqual($rubricList, $rubricGrades);
        // evaluation mixeval
        $mixevalGrades = $this->_oauthReq("$url/3/grades");
        $mixevalGrades = json_decode($mixevalGrades, true);
        $this->assertEqual($mixevalList, $mixevalGrades);

        // test get specific student's grades for an event of a course (test multiple types)
        // evaluation simple
        $studentGrade = $this->_oauthReq("$url/1/grades/redshirt0029");
        $studentGrade = json_decode($studentGrade, true);
        $expectedGrade = array("evaluatee" => "33", "score" => "75",
            'username' => 'redshirt0029');
        $this->assertEqual($expectedGrade, $studentGrade);
        // evaluation rubric
        $studentGrade = $this->_oauthReq("$url/2/grades/redshirt0001");
        $studentGrade = json_decode($studentGrade, true);
        $expectedGrade = array("evaluatee" => "5", "score" => "14",
            'username' => 'redshirt0001');
        $this->assertEqual($expectedGrade, $studentGrade);
        // evaluation mixeval
        $studentGrade = $this->_oauthReq("$url/3/grades/redshirt0002");
        $studentGrade = json_decode($studentGrade, true);
        $expectedGrade = array("evaluatee" => "6", "score" => "2.4",
            'username' => 'redshirt0002');
        $this->assertEqual($expectedGrade, $studentGrade);
    }


    public function testDepartments() {
        $url = $this->_getURL('/v1/departments');

        $expectedDepartments = array(
            array('id' => '1', 'name' => 'MECH'),
            array('id' => '2', 'name' => 'APSC'),
            array('id' => '3', 'name' => 'CPSC')
        );

        $departments = $this->_oauthReq("$url");
        $this->assertEqual(json_decode($departments, true), $expectedDepartments);

        $expectedCourses = array(array('id' => '1', 'course' => 'MECH 328', 'title' => 'Mechanical Engineering Design Project'));
        $courses = $this->_oauthReq("$url/1");
        $this->assertEqual(json_decode($courses, true), $expectedCourses);
    }

    public function testCourseDepartments()
    {
        $url = $this->_getURL('/v1/courses/1/departments');

        // POST - Add a course to a department
        $file = $this->_oauthReq("$url/2", '', OAUTH_HTTP_METHOD_POST);
        $expected = array('course_id' => '1', 'department_id' => '2');
        $this->assertEqual(json_decode($file, true), $expected);

        // DELETE - Delete a course from a department
        $file = $this->_oauthReq("$url/2", '', OAUTH_HTTP_METHOD_DELETE);
        $this->assertEqual(json_decode($file), array());

        // add test to see if course exist using departments
    }

    function testUsersEvents()
    {
        $expectedEvents = array(
            array(
                'title' => 'Term 1 Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => '2013-07-02 16:34:43',
                'id' => '1',
                'release_date_begin' => "2011-06-16 16:34:49",
                'release_date_end' => "2023-07-22 16:34:53",
                'is_released' => true,
                'is_ended' => false,
            ),
            array(
                'title' => 'Term Report Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '2',
                'due_date' => '2013-06-08 08:59:29',
                'id' => '2',
                'release_date_begin' => "2011-06-06 08:59:35",
                'release_date_end' => "2023-07-02 08:59:41",
                'is_released' => true,
                'is_ended' => false,
            ),
            array(
                'title' => 'Project Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '4',
                'due_date' => '2013-07-02 09:00:28',
                'id' => '3',
                'release_date_begin' => "2011-06-07 09:00:35",
                'release_date_end' => "2023-07-09 09:00:39",
                'is_released' => true,
                'is_ended' => false,
            ),
            array(
                'title' => 'Team Creation Survey',
                'course_id' => '1',
                'event_template_type_id' => '3',
                'due_date' => '2013-07-31 11:20:00',
                'id' => '4',
                'release_date_begin' => "2012-07-01 11:20:00",
                'release_date_end' => "2013-12-31 11:20:00",
                'is_released' => true,
                'is_ended' => false,
            ),
            array(
                'title' => 'Survey, all Q types',
                'course_id' => '1',
                'event_template_type_id' => '3',
                'due_date' => '2013-07-31 11:20:00',
                'id' => '5',
                'release_date_begin' => "2012-07-01 11:20:00",
                'release_date_end' => "2013-12-31 11:20:00",
                'is_released' => true,
                'is_ended' => false,
            )
        );

        $url = $this->_getURL('/v1/users/redshirt0001/events');
        $actualEvents = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($actualEvents, true), '{n}.id', 'asc');
        $this->assertequal($expectedEvents, $events);

        $url = $this->_getURL('/v1/courses/1/users/redshirt0001/events');
        $courseUserEvents = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($courseUserEvents, true), '{n}.id', 'asc');
        $this->assertequal($expectedEvents, $events);
    }
     

    function testEnrolments() {
        // Get the ids of students enrolled in course id 3
        $url = $this->_getURL('/v1/courses/3/users');
        $actual = $this->_oauthReq("$url");
        $expected = array(
            array('id' => '8', 'role_id' => '5', 'username' => 'redshirt0004'),
            array('id' => '33', 'role_id' => '5', 'username' => 'redshirt0029'),
            array('id' => '4', 'role_id' => '3', 'username' => 'instructor3'),
            array('id' => '37', 'role_id' => '4', 'username' => 'tutor3'),
        );
        $this->assertEqual($expected, json_decode($actual, true));

        // Add a student to a course
        $expected = array(array('username' => 'redshirt0005', 'role_id' => 5));
        $actual = $this->_oauthReq(
            $url, json_encode($expected), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // Add a duplicate student to a course
        $expected = array();
        $actual = $this->_oauthReq(
            $url, json_encode($expected), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // Add an instructor to a course
        $expected = array(array('username' => 'instructor2', 'role_id' => 3));
        $actual = $this->_oauthReq(
            $url, json_encode($expected), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // Add an instructor with duplicate usernames to a course
        $expected = array(
            array('username' => 'instructor1', 'role_id' => 3),
        );
        $actual = $this->_oauthReq(
            $url, json_encode(array(
                array('username' => 'instructor1', 'role_id' => 3),
                array('username' => 'INStructor1', 'role_id' => 3)
            )), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // Add a tutor to a course
        $expected = array(array('username' => 'tutor2', 'role_id' => 4));
        $actual = $this->_oauthReq(
            $url, json_encode($expected), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // Remove a student from a course
        $expected = array(array('username' => 'redshirt0005', 'role_id' => 5));
        $actual = $this->_oauthReq(
            $url, json_encode($expected), OAUTH_HTTP_METHOD_DELETE);
        $this->assertEqual($expected, json_decode($actual, true));

        // Remove an instructor from a course
        $expected = array(array('username' => 'instructor2', 'role_id' => 3));
        $actual = $this->_oauthReq(
            $url, json_encode($expected), OAUTH_HTTP_METHOD_DELETE);
        $this->assertEqual($expected, json_decode($actual, true));

        // Remove a tutor from a course
        $expected = array(array('username' => 'tutor2', 'role_id' => 4));
        $actual = $this->_oauthReq(
            $url, json_encode($expected), OAUTH_HTTP_METHOD_DELETE);
        $this->assertEqual($expected, json_decode($actual, true));
    }
}
