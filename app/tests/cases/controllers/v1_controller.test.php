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
        'app.user_faculty', 'app.user_tutor',
        'app.penalty', 'app.oauth_client', 'app.oauth_token',
        'app.survey_input', 'app.evaluation_rubric_detail', 'app.evaluation_mixeval_detail',
        'app.sys_parameter'
    );

    public function startCase() {
        echo "<h1>Starting Test Case</h1>\n";
        $this->User =& ClassRegistry::init('User');
        $this->RolesUser =& ClassRegistry::init('RolesUser');
        $this->Group =& ClassRegistry::init('Group');
    }
    public function endCase() {
        echo "<h1>Ending Test Case</h1>\n";
    }
    public function startTest($method) {
        echo '<h3>Starting method ' . $method . "</h3>\n";
        $clients = $this->_fixtures['app.oauth_client']->records;
        $tokens = $this->_fixtures['app.oauth_token']->records;
        $this->clientKey = $clients['0']['key'];
        $this->clientSecret = $clients['0']['secret'];
        $this->tokenKey = $tokens['0']['key'];
        $this->tokenSecret = $tokens['0']['secret'];
    }
    public function endTest($method) {
        echo '<h3>Ending method ' . $method . '</h3>';
        echo "<hr />\n";
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
            $oauth->fetch($url, $content, $reqType, array('Content-Type' => 'application/json'));
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
        $result = $this->_oauthReq($url);
        print_r($result);
        $this->assertEqual('', $result);
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

        // add user whose role_id will not change
        $newUserSame = array('username' => 's5s5s5s5', 'first_name' => 'Sally', 'last_name' => 'Same', 'role_id' => 3);
        $fileSame = $this->_oauthReq($url, json_encode($newUserSame), OAUTH_HTTP_METHOD_POST);
        $userSame  = json_decode($fileSame, true);
        $userIdSame = $userSame['id'];
        $expectedPersonSame = array('id' => $userIdSame, 'username' => 's5s5s5s5', 'last_name' => 'Same', 'first_name' => 'Sally', 'role_id' => 3);
        $this->assertEqual($userSame, $expectedPersonSame);
        // add same user again, with same role_id and id included (since it will exist)
        $newUserAgain = array('id' => $userIdSame, 'username' => 's5s5s5s5', 'first_name' => 'Sally', 'last_name' => 'Same', 'role_id' => 3);
        $fileAgain = $this->_oauthReq($url, json_encode($newUserAgain), OAUTH_HTTP_METHOD_POST);
        $userAgain = json_decode($fileAgain, true);
        $userIdAgain = $userAgain['id'];
        $expectedPersonAgain = array('id' => $userIdAgain, 'username' => 's5s5s5s5', 'last_name' => 'Same', 'first_name' => 'Sally', 'role_id' => 3);
        $this->assertEqual($userAgain, $expectedPersonAgain);

        // add user whose role_id will not change: external role_id > saved role_id
        $newUserSameL = array('username' => 's0s0s0s0', 'first_name' => 'Also', 'last_name' => 'Same', 'role_id' => 1);
        $fileSameL = $this->_oauthReq($url, json_encode($newUserSameL), OAUTH_HTTP_METHOD_POST);
        $userSameL  = json_decode($fileSameL, true);
        $userIdSameL = $userSameL['id'];
        $expectedPersonSameL = array('id' => $userIdSameL, 'username' => 's0s0s0s0', 'last_name' => 'Same', 'first_name' => 'Also', 'role_id' => 1);
        $this->assertEqual($userSameL, $expectedPersonSameL);
        // add same user again, with higher role_id and id included
        $newUserAgainL = array('id' => $userIdSameL, 'username' => 's0s0s0s0', 'first_name' => 'Also', 'last_name' => 'Same', 'role_id' => 3);
        $fileAgainL = $this->_oauthReq($url, json_encode($newUserAgainL), OAUTH_HTTP_METHOD_POST);
        $userAgainL = json_decode($fileAgainL, true);
        $userIdAgainL = $userAgainL['id'];
        $expectedPersonAgainL = array('id' => $userIdAgainL, 'username' => 's0s0s0s0', 'last_name' => 'Same', 'first_name' => 'Also', 'role_id' => 1);
        $this->assertEqual($userAgainL, $expectedPersonAgainL);

        // add user whose role_id will change: external role_id < saved role_id
        $newUserBefore = array('username' => 'b8b8b8b8', 'first_name' => 'Shifty', 'last_name' => 'Guy', 'role_id' => 5);
        $fileBefore = $this->_oauthReq($url, json_encode($newUserBefore), OAUTH_HTTP_METHOD_POST);
        $userBefore  = json_decode($fileBefore, true);
        $userIdOfChange = $userBefore['id'];
        $expectedPersonBefore = array('id' => $userIdOfChange, 'username' => 'b8b8b8b8', 'last_name' => 'Guy', 'first_name' => 'Shifty', 'role_id' => 5);
        $this->assertEqual($userBefore, $expectedPersonBefore);
        // add same user again, but with lower role_id and id included
        $newUserAfter = array('id' => $userIdOfChange, 'username' => 'b8b8b8b8', 'first_name' => 'Shifty', 'last_name' => 'Guy', 'role_id' => 3);
        $fileAfter = $this->_oauthReq($url, json_encode($newUserAfter), OAUTH_HTTP_METHOD_POST);
        $userAfter  = json_decode($fileAfter, true);
        $userIdAfter = $userAfter['id'];
        $expectedPersonAfter = array('id' => $userIdAfter, 'username' => 'b8b8b8b8', 'last_name' => 'Guy', 'first_name' => 'Shifty', 'role_id' => 3);
        $this->assertEqual($userAfter, $expectedPersonAfter);

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

        // add two users whose roles will not change
        $newUsersSame = array(
            array('username' => 'h4h4h4h4', 'first_name' => 'Susie', 'last_name' => 'Same', 'role_id' => 3),
            array('username' => 'p9p9p9p9', 'first_name' => 'Sammy', 'last_name' => 'Same', 'role_id' => 4)
        );
        $fileSame = $this->_oauthReq($url, json_encode($newUsersSame), OAUTH_HTTP_METHOD_POST);
        $usersSame = json_decode($fileSame, true);
        usort($usersSame, 'compare');
        $expectedUsersSame = array();
        foreach ($newUsersSame as $key => $nu) {
            $expectedUsersSame[] = array('id' => $usersSame[$key]['id']) + $nu;
        }
        $this->assertEqual($usersSame, $expectedUsersSame);

        // add users again with same role_id and id included (since values will exist)
        $usersIdsSame = array();
        foreach ($usersSame as $person) {
            $usersIdsSame[] = $person['id'];
        }
        $newUsersAgain = array(
            array('id' =>  $usersIdsSame[0], 'username' => 'h4h4h4h4', 'first_name' => 'Susie', 'last_name' => 'Same', 'role_id' => 3),
            array('id' =>  $usersIdsSame[1], 'username' => 'p9p9p9p9', 'first_name' => 'Sammy', 'last_name' => 'Same', 'role_id' => 4)
        );
        $fileAgain = $this->_oauthReq($url, json_encode($newUsersAgain), OAUTH_HTTP_METHOD_POST);
        $usersAgain = json_decode($fileAgain, true);
        usort($usersAgain, 'compare');
        $expectedUsersAgain = array();
        foreach ($newUsersAgain as $key => $nu) {
            $expectedUsersAgain[] = array('id' => $usersAgain[$key]['id']) + $nu;
        }
        $this->assertEqual($usersAgain, $expectedUsersAgain);

        // add two users whose roles will not change: external role_id > saved role_id
        $newUsersSameL = array(
            array('username' => 'aaaa1111', 'first_name' => 'Alex', 'last_name' => 'G', 'role_id' => 1),
            array('username' => 'bbbb2222', 'first_name' => 'Tammy', 'last_name' => 'B', 'role_id' => 1)
        );
        $fileSameL = $this->_oauthReq($url, json_encode($newUsersSameL), OAUTH_HTTP_METHOD_POST);
        $usersSameL = json_decode($fileSameL, true);
        usort($usersSameL, 'compare');
        $expectedUsersSameL = array();
        foreach ($newUsersSameL as $key => $nu) {
            $expectedUsersSameL[] = array('id' => $usersSameL[$key]['id']) + $nu;
        }
        $this->assertEqual($usersSameL, $expectedUsersSameL);

        // add users again with same role_id and id included (since values will exist) !!!
        $usersIdsSameL = array();
        foreach ($usersSameL as $person) {
            $usersIdsSameL[] = $person['id'];
        }
        $newUsersAgainL = array(
            array('id' =>  $usersIdsSameL[0], 'username' => 'aaaa1111', 'first_name' => 'Alex', 'last_name' => 'G', 'role_id' => 3),
            array('id' =>  $usersIdsSameL[1], 'username' => 'bbbb2222', 'first_name' => 'Tammy', 'last_name' => 'B', 'role_id' => 3)
        );
        $fileAgainL = $this->_oauthReq($url, json_encode($newUsersAgainL), OAUTH_HTTP_METHOD_POST);
        $usersAgainL = json_decode($fileAgainL, true);
        usort($usersAgainL, 'compare');
        // returned users should be same as original saved users with lower role_id
        $this->assertEqual($usersAgainL, $expectedUsersSameL);

        // add two users whose roles will both change: external role_id < saved role_id
        $newUsersBefore = array(
            array('username' => 'a4a4a4a4', 'first_name' => 'Shifty', 'last_name' => 'Guy', 'role_id' => 5),
            array('username' => 'c2c2c2c2', 'first_name' => 'Shifty', 'last_name' => 'Gal', 'role_id' => 3)
        );
        $fileBefore = $this->_oauthReq($url, json_encode($newUsersBefore), OAUTH_HTTP_METHOD_POST);
        $usersBefore = json_decode($fileBefore, true);
        usort($usersBefore, 'compare');
        $expectedUsersBefore = array();
        foreach ($newUsersBefore as $key => $nu) {
            $expectedUsersBefore[] = array('id' => $usersBefore[$key]['id']) + $nu;
        }
        $this->assertEqual($usersBefore, $expectedUsersBefore);

        // add users again with same role_id and id included (since values will exist)
        $usersIdsBefore = array();
        foreach ($usersBefore as $person) {
            $usersIdsBefore[] = $person['id'];
        }
        $newUsersAfter = array(
            array('id' =>  $usersIdsBefore[0], 'username' => 'a4a4a4a4', 'first_name' => 'Shifty', 'last_name' => 'Guy', 'role_id' => 3),
            array('id' =>  $usersIdsBefore[1], 'username' => 'c2c2c2c2', 'first_name' => 'Shifty', 'last_name' => 'Gal', 'role_id' => 1)
        );
        $fileAfter = $this->_oauthReq($url, json_encode($newUsersAfter), OAUTH_HTTP_METHOD_POST);
        $usersAfter = json_decode($fileAfter, true);
        usort($usersAfter, 'compare');
        $expectedUsersAfter = array();
        foreach ($newUsersAfter as $key => $nu) {
            $expectedUsersAfter[] = array('id' => $usersAfter[$key]['id']) + $nu;
        }
        $this->assertEqual($usersAfter, $expectedUsersAfter);

        // add two users, only one of their roles will change
        $newUsersBefore = array(
            array('username' => 'd6d6d6d6', 'first_name' => 'Steve', 'last_name' => 'Stable', 'role_id' => 3),
            array('username' => 'z2z2z2z2', 'first_name' => 'Sheena', 'last_name' => 'Switch', 'role_id' => 3)
        );
        $fileBefore = $this->_oauthReq($url, json_encode($newUsersBefore), OAUTH_HTTP_METHOD_POST);
        $usersBefore = json_decode($fileBefore, true);
        usort($usersBefore, 'compare');
        $expectedUsersBefore = array();
        foreach ($newUsersBefore as $key => $nu) {
            $expectedUsersBefore[] = array('id' => $usersBefore[$key]['id']) + $nu;
        }
        $this->assertEqual($usersBefore, $expectedUsersBefore);

        // add users again with same role_id and id included (since values will exist)
        $usersIdsBefore = array();
        foreach ($usersBefore as $person) {
            $usersIdsBefore[] = $person['id'];
        }
        $newUsersAfter = array(
            array('id' =>  $usersIdsBefore[0], 'username' => 'd6d6d6d6', 'first_name' => 'Steve', 'last_name' => 'Stable', 'role_id' => 3),
            array('id' =>  $usersIdsBefore[1], 'username' => 'z2z2z2z2', 'first_name' => 'Sheena', 'last_name' => 'Switch', 'role_id' => 1)
        );
        $fileAfter = $this->_oauthReq($url, json_encode($newUsersAfter), OAUTH_HTTP_METHOD_POST);
        $usersAfter = json_decode($fileAfter, true);
        usort($usersAfter, 'compare');
        $expectedUsersAfter = array();
        foreach ($newUsersAfter as $key => $nu) {
            $expectedUsersAfter[] = array('id' => $usersAfter[$key]['id']) + $nu;
        }
        $this->assertEqual($usersAfter, $expectedUsersAfter);

        // POST - special characters in the user data
        $users = "[{\"id\":0,\"username\":\"student1111\",\"email\":\"\\tbademail@example.com\",\"role_id\":5,\"first_name\":\"Yu\",\"last_name\":\"Lee\",\"student_no\":\"67777777\"}]";
        $file = $this->_oauthReq($url, $users, OAUTH_HTTP_METHOD_POST);
        $users = json_decode($file, true);
        // check if the user is correctly inserted
        $this->assertEqual($users[0]['username'], 'student1111');

        // PUT - update user
        $updatedPerson = array('id' => $userId, 'username' => 'coolUser20', 'last_name' => 'Hardy', 'first_name' => 'Jane', 'role_id' => 4);
        $expectedPerson = array('id' => $userId, 'username' => 'coolUser20', 'last_name' => 'Hardy', 'first_name' => 'Jane', 'role_id' => 4);
        $file = $this->_oauthReq($url, json_encode($updatedPerson), OAUTH_HTTP_METHOD_PUT);
        $this->assertEqual(json_decode($file, true), $expectedPerson);

        // update user with no role_id change (see POST test for Sally Same, id s5s5s5s5)
        $updatedUserSame = array('id' => $userIdSame, 'username' => 's5s5s5s5', 'last_name' => 'Same', 'first_name' => 'Sally', 'role_id' => 3);
        $expectedUserSame = array('id' => $userIdSame, 'username' => 's5s5s5s5', 'last_name' => 'Same', 'first_name' => 'Sally', 'role_id' => 3);
        $fileSame = $this->_oauthReq($url, json_encode($updatedUserSame), OAUTH_HTTP_METHOD_PUT);
        $this->assertEqual(json_decode($fileSame, true), $expectedUserSame);

        // update user with higher role_ide (see POST test for Also Same, id s0s0s0s0)
        $updatedUserSameL = array('id' => $userIdSameL,'username' => 's0s0s0s0', 'first_name' => 'Also', 'last_name' => 'Same', 'role_id' => 3);
        $expectedUserSameL = array('id' => $userIdSameL,'username' => 's0s0s0s0', 'first_name' => 'Also', 'last_name' => 'Same', 'role_id' => 1);
        $fileSameL = $this->_oauthReq($url, json_encode($updatedUserSameL), OAUTH_HTTP_METHOD_PUT);
        $this->assertEqual(json_decode($fileSameL, true), $expectedUserSameL);

        // update user with lower role_id (see POST test for Shifty Guy, id b8b8b8b8)
        $updatedUserUp = array('id' => $userIdOfChange, 'username' => 'b8b8b8b8', 'first_name' => 'Shifty', 'last_name' => 'Guy', 'role_id' => 1);
        $expectedUserUp = array('id' => $userIdOfChange, 'username' => 'b8b8b8b8', 'first_name' => 'Shifty', 'last_name' => 'Guy', 'role_id' => 1);
        $fileUp = $this->_oauthReq($url, json_encode($updatedUserUp), OAUTH_HTTP_METHOD_PUT);
        $this->assertEqual(json_decode($fileUp, true), $expectedUserUp);

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

        // delete another course
        $id = $newCourse['Course']['id'];
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
            array('username' => 'redshirt0001'),
            array('username' => 'redshirt0002'),
            array('username' => 'redshirt0003'),
            array('username' => 'tutor1'),
            array('username' => 'redshirt0009'),
            array('username' => 'redshirt0011'),
            array('username' => 'redshirt0013'),
            array('username' => 'redshirt0014'));
        $addedMembers = $this->_oauthReq("$url", json_encode($toBeAdded), OAUTH_HTTP_METHOD_POST);
        $expected = array(
            array('username' => 'redshirt0001'),
            array('username' => 'redshirt0002'),
            array('username' => 'redshirt0003'),
            array('username' => 'tutor1'),
            array('username' => 'redshirt0009'),
            array('username' => 'redshirt0011'),
            array('username' => 'redshirt0013'));

        // redshirt0014 is not in the course therefore would be skipped
        $this->assertEqual(json_decode($addedMembers, true), $expected);

        // test updating class list - removing students not in new list
        $update = array(
            array('username' => 'redshirt0001'),
            array('username' => 'redshirt0002'),
            array('username' => 'redshirt0003'),
            array('username' => 'tutor1'),
            array('username' => 'redshirt0011'),
            array('username' => 'redshirt0013'));
        $updated = $this->_oauthReq("$url", json_encode($update), OAUTH_HTTP_METHOD_POST);
        $updated = $this->_oauthReq("$url");
        $expected = array(
            array('id' => '5', 'role_id' => '5', 'username' => 'redshirt0001', 'last_name' => 'Student', 'first_name' => 'Ed'),
            array('id' => '6', 'role_id' => '5', 'username' => 'redshirt0002', 'last_name' => 'Student', 'first_name' => 'Alex'),
            array('id' => '7', 'role_id' => '5', 'username' => 'redshirt0003', 'last_name' => 'Student', 'first_name' => 'Matt'),
            array('id' => '15', 'role_id' => '5', 'username' => 'redshirt0011', 'last_name' => 'Student', 'first_name' => 'Jennifer'),
            array('id' => '17', 'role_id' => '5', 'username' => 'redshirt0013', 'last_name' => 'Student', 'first_name' => 'Edna'),
            array('id' => '35', 'role_id' => '4', 'username' => 'tutor1', 'last_name' => '1', 'first_name' => 'Tutor'),
        );

        $this->assertEqual(json_decode($updated, true), $expected);

        // test adding non existing user to a group
        $toBeAdded = array(
            array('username' => 'redshirt0001'),
            array('username' => 'redshirt0002'),
            array('username' => 'redshirt0003'),
            array('username' => 'tutor1'),
            array('username' => 'redshirt0011'),
            array('username' => 'redshirt0013'),
            array('username' => 'nonexistinguser'),
        );
        $addedMembers = $this->_oauthReq($url, json_encode($toBeAdded), OAUTH_HTTP_METHOD_POST);

        $this->assertEqual(json_decode($addedMembers, true), $update);
        // make user nothing is added
        $actualGroup = $this->_oauthReq("$url");
        $this->assertEqual(count(json_decode($actualGroup, true)), 6);

        // HTTP DELETE, try to remove students from a group
        $ret = $this->_oauthReq("$url/redshirt0011",null,OAUTH_HTTP_METHOD_DELETE);
        $this->assertEqual(json_decode($ret, true), array());
        $ret = $this->_oauthReq("$url/redshirt0013",null,OAUTH_HTTP_METHOD_DELETE);
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
            $tmp['id'] = $data['id'];
            $tmp['title'] = $data['title'];
            $tmp['course_id'] = $data['course_id'];
            $tmp['event_template_type_id'] = $data['event_template_type_id'];
            $tmp['due_date'] = $data['due_date'];
            $expectedEvents[] = $tmp;
        }

        // test get all events of a course
        $actualEvents = $this->_oauthReq("$url/1/events");
        $this->assertEqual($actualEvents, json_encode($expectedEvents));
        $this->assertEqual(json_decode($actualEvents, true), $expectedEvents);

        // test get specific event from a course
        $actualEvent = $this->_oauthReq("$url/1/events/3");
        $expectedEvent = array("id" => "3", "title" => "Project Evaluation", "course_id" => "1",
            "event_template_type_id" => "4", "due_date" => date('Y', strtotime("+1 year"))."-07-02 09:00:28");
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
                'due_date' => date('Y', strtotime("+1 year")).'-07-02 16:34:43',
                'release_date_begin' => "2011-06-16 16:34:49",
                'release_date_end' => "2023-07-22 16:34:53",
                'result_release_date_begin' => '2024-07-04 16:34:43',
                'result_release_date_end' => '2024-07-30 16:34:43',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '1',
            ),
            array(
                'title' => 'Term Report Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '2',
                'due_date' => date('Y', strtotime("+1 year")).'-06-08 08:59:29',
                'release_date_begin' => "2011-06-06 08:59:35",
                'release_date_end' => "2023-07-02 08:59:41",
                'result_release_date_begin' => '2024-06-09 08:59:29',
                'result_release_date_end' => '2024-07-08 08:59:29',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '2',
            ),
            array(
                'title' => 'Project Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '4',
                'due_date' => date('Y', strtotime("+1 year")).'-07-02 09:00:28',
                'release_date_begin' => "2011-06-07 09:00:35",
                'release_date_end' => "2023-07-09 09:00:39",
                'result_release_date_begin' => '2023-07-04 09:00:28',
                'result_release_date_end' => '2024-07-12 09:00:28',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '3',
            ),
            array(
                'title' => 'Team Creation Survey',
                'course_id' => '1',
                'event_template_type_id' => '3',
                'due_date' => date('Y', strtotime("+1 year")).'-07-31 11:20:00',
                'release_date_begin' => "2012-07-01 11:20:00",
                'release_date_end' => date('Y', strtotime("+1 year"))."-12-31 11:20:00",
                'result_release_date_begin' => '',
                'result_release_date_end' => '',
                'is_released' => true,
                'is_ended' => false,
                'id' => '4',
            ),
            array(
                'title' => 'Survey, all Q types',
                'course_id' => '1',
                'event_template_type_id' => '3',
                'due_date' => date('Y', strtotime("+1 year")).'-07-31 11:20:00',
                'release_date_begin' => "2012-07-01 11:20:00",
                'release_date_end' => date('Y', strtotime("+1 year"))."-12-31 11:20:00",
                'result_release_date_begin' => '',
                'result_release_date_end' => '',
                'is_released' => true,
                'is_ended' => false,
                'id' => '5',
            ),
            array(
                'title' => 'simple evaluation 2',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => '2012-11-28 00:00:00',
                'release_date_begin' => '2012-11-20 00:00:00',
                'release_date_end' => '2022-11-29 00:00:00',
                'result_release_date_begin' => '2022-11-30 00:00:00',
                'result_release_date_end' => '2022-12-12 00:00:00',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '6',
            ),
            array(
                'title' => 'simple evaluation 3',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => '2012-11-28 00:00:00',
                'release_date_begin' => '2012-11-20 00:00:00',
                'release_date_end' => '2012-11-29 00:00:00',
                'result_release_date_begin' => '2022-11-30 00:00:00',
                'result_release_date_end' => '2022-12-12 00:00:00',
                'is_released' => false,
                'is_result_released' => false,
                'is_ended' => true,
                'id' => '7',
            ),
            array(
                'title' => 'simple evaluation 4',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => '2012-11-28 00:00:00',
                'release_date_begin' => '2012-11-20 00:00:00',
                'release_date_end' => '2012-11-29 00:00:00',
                'result_release_date_begin' => '2012-11-30 00:00:00',
                'result_release_date_end' => '2022-12-12 00:00:00',
                'is_released' => false,
                'is_result_released' => true,
                'is_ended' => true,
                'id' => '8',
            ),
            array(
                'title' => 'simple evaluation 5',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => '2012-11-28 00:00:00',
                'release_date_begin' => '2012-11-20 00:00:00',
                'release_date_end' => '2012-11-29 00:00:00',
                'result_release_date_begin' => '2012-11-30 00:00:00',
                'result_release_date_end' => '2022-12-12 00:00:00',
                'is_released' => false,
                'is_result_released' => true,
                'is_ended' => true,
                'id' => '9',
            ),
            array(
                'title' => 'simple evaluation 6',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => date('Y', strtotime("+2 year")).'-07-31 11:20:00',
                'release_date_begin' => date('Y', strtotime("+1 year")).'-07-31 11:20:00',
                'release_date_end' => date('Y', strtotime("+2 year")).'-07-31 11:20:00',
                'result_release_date_begin' => date('Y', strtotime("+2 year")).'-07-31 11:20:00',
                'result_release_date_end' => date('Y', strtotime("+3 year")).'-07-31 11:20:00',
                'is_released' => false,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '10',
            ),
        );
        
        $expectedSubmittableEvents = array(
            array(
                'title' => 'Term 1 Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => date('Y', strtotime("+1 year")).'-07-02 16:34:43',
                'release_date_begin' => "2011-06-16 16:34:49",
                'release_date_end' => "2023-07-22 16:34:53",
                'result_release_date_begin' => '2024-07-04 16:34:43',
                'result_release_date_end' => '2024-07-30 16:34:43',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '1',
            ),
            array(
                'title' => 'Term Report Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '2',
                'due_date' => date('Y', strtotime("+1 year")).'-06-08 08:59:29',
                'release_date_begin' => "2011-06-06 08:59:35",
                'release_date_end' => "2023-07-02 08:59:41",
                'result_release_date_begin' => '2024-06-09 08:59:29',
                'result_release_date_end' => '2024-07-08 08:59:29',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '2',
            ),
            array(
                'title' => 'Project Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '4',
                'due_date' => date('Y', strtotime("+1 year")).'-07-02 09:00:28',
                'release_date_begin' => "2011-06-07 09:00:35",
                'release_date_end' => "2023-07-09 09:00:39",
                'result_release_date_begin' => '2023-07-04 09:00:28',
                'result_release_date_end' => '2024-07-12 09:00:28',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '3',
            ),
            array(
                'title' => 'Team Creation Survey',
                'course_id' => '1',
                'event_template_type_id' => '3',
                'due_date' => date('Y', strtotime("+1 year")).'-07-31 11:20:00',
                'release_date_begin' => "2012-07-01 11:20:00",
                'release_date_end' => date('Y', strtotime("+1 year"))."-12-31 11:20:00",
                'result_release_date_begin' => '',
                'result_release_date_end' => '',
                'is_released' => true,
                'is_ended' => false,
                'id' => '4',
            ),
            array(
                'title' => 'Survey, all Q types',
                'course_id' => '1',
                'event_template_type_id' => '3',
                'due_date' => date('Y', strtotime("+1 year")).'-07-31 11:20:00',
                'release_date_begin' => "2012-07-01 11:20:00",
                'release_date_end' => date('Y', strtotime("+1 year"))."-12-31 11:20:00",
                'result_release_date_begin' => '',
                'result_release_date_end' => '',
                'is_released' => true,
                'is_ended' => false,
                'id' => '5',
            )
        );
        
        $expectedResultReleasedEvents = array(
            array(
                'title' => 'simple evaluation 4',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => '2012-11-28 00:00:00',
                'release_date_begin' => '2012-11-20 00:00:00',
                'release_date_end' => '2012-11-29 00:00:00',
                'result_release_date_begin' => '2012-11-30 00:00:00',
                'result_release_date_end' => '2022-12-12 00:00:00',
                'is_released' => false,
                'is_result_released' => true,
                'is_ended' => true,
                'id' => '8',
            ),
            array(
                'title' => 'simple evaluation 5',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => '2012-11-28 00:00:00',
                'release_date_begin' => '2012-11-20 00:00:00',
                'release_date_end' => '2012-11-29 00:00:00',
                'result_release_date_begin' => '2012-11-30 00:00:00',
                'result_release_date_end' => '2022-12-12 00:00:00',
                'is_released' => false,
                'is_result_released' => true,
                'is_ended' => true,
                'id' => '9',
            )
        );
        
        $expectedFilteredEvents = array(
            array(
                'title' => 'Term 1 Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => date('Y', strtotime("+1 year")).'-07-02 16:34:43',
                'release_date_begin' => "2011-06-16 16:34:49",
                'release_date_end' => "2023-07-22 16:34:53",
                'result_release_date_begin' => '2024-07-04 16:34:43',
                'result_release_date_end' => '2024-07-30 16:34:43',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '1',
            ),
            array(
                'title' => 'Term Report Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '2',
                'due_date' => date('Y', strtotime("+1 year")).'-06-08 08:59:29',
                'release_date_begin' => "2011-06-06 08:59:35",
                'release_date_end' => "2023-07-02 08:59:41",
                'result_release_date_begin' => '2024-06-09 08:59:29',
                'result_release_date_end' => '2024-07-08 08:59:29',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '2',
            ),
            array(
                'title' => 'Project Evaluation',
                'course_id' => '1',
                'event_template_type_id' => '4',
                'due_date' => date('Y', strtotime("+1 year")).'-07-02 09:00:28',
                'release_date_begin' => "2011-06-07 09:00:35",
                'release_date_end' => "2023-07-09 09:00:39",
                'result_release_date_begin' => '2023-07-04 09:00:28',
                'result_release_date_end' => '2024-07-12 09:00:28',
                'is_released' => true,
                'is_result_released' => false,
                'is_ended' => false,
                'id' => '3',
            ),
            array(
                'title' => 'Team Creation Survey',
                'course_id' => '1',
                'event_template_type_id' => '3',
                'due_date' => date('Y', strtotime("+1 year")).'-07-31 11:20:00',
                'release_date_begin' => "2012-07-01 11:20:00",
                'release_date_end' => date('Y', strtotime("+1 year"))."-12-31 11:20:00",
                'result_release_date_begin' => '',
                'result_release_date_end' => '',
                'is_released' => true,
                'is_ended' => false,
                'id' => '4',
            ),
            array(
                'title' => 'Survey, all Q types',
                'course_id' => '1',
                'event_template_type_id' => '3',
                'due_date' => date('Y', strtotime("+1 year")).'-07-31 11:20:00',
                'release_date_begin' => "2012-07-01 11:20:00",
                'release_date_end' => date('Y', strtotime("+1 year"))."-12-31 11:20:00",
                'result_release_date_begin' => '',
                'result_release_date_end' => '',
                'is_released' => true,
                'is_ended' => false,
                'id' => '5',
            ),
            array(
                'title' => 'simple evaluation 4',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => '2012-11-28 00:00:00',
                'release_date_begin' => '2012-11-20 00:00:00',
                'release_date_end' => '2012-11-29 00:00:00',
                'result_release_date_begin' => '2012-11-30 00:00:00',
                'result_release_date_end' => '2022-12-12 00:00:00',
                'is_released' => false,
                'is_result_released' => true,
                'is_ended' => true,
                'id' => '8',
            ),
            array(
                'title' => 'simple evaluation 5',
                'course_id' => '1',
                'event_template_type_id' => '1',
                'due_date' => '2012-11-28 00:00:00',
                'release_date_begin' => '2012-11-20 00:00:00',
                'release_date_end' => '2012-11-29 00:00:00',
                'result_release_date_begin' => '2012-11-30 00:00:00',
                'result_release_date_end' => '2022-12-12 00:00:00',
                'is_released' => false,
                'is_result_released' => true,
                'is_ended' => true,
                'id' => '9',
            )
        );
        
        // get ALL events for redshirt0001
        $url = $this->_getURL('/v1/users/redshirt0001/events/sub/0/results/0');
        $actualEvents = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($actualEvents, true), '{n}.id', 'asc');
        $this->assertEqual($expectedEvents, $events);

        // get ALL events course id 1 for redshirt0001
        $url = $this->_getURL('/v1/courses/1/users/redshirt0001/events/sub/0/results/0');
        $courseUserEvents = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($courseUserEvents, true), '{n}.id', 'asc');
        $this->assertEqual($expectedEvents, $events);
        
        // get events for redshirt0001 available for submissions
        $url = $this->_getURL('/v1/users/redshirt0001/events/sub/1/results/0');
        $eventsSubmittable = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($eventsSubmittable, true), '{n}.id', 'asc');
        $this->assertEqual($expectedSubmittableEvents, $events);
        
        // get events for redshirt0001 that have results released
        $url = $this->_getURL('/v1/users/redshirt0001/events/sub/0/results/1');
        $eventsResult = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($eventsResult, true), '{n}.id', 'asc');
        $this->assertEqual($expectedResultReleasedEvents, $events);
        
        // get events for redshirt0001 available for submissions OR have results released
        $url = $this->_getURL('/v1/users/redshirt0001/events/sub/1/results/1');
        $filteredEvents = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($filteredEvents, true), '{n}.id', 'asc');
        $this->assertEqual($expectedFilteredEvents, $events);

        // get events in course id 1 for redshirt0001 available for submissions
        $url = $this->_getURL('/v1/courses/1/users/redshirt0001/events/sub/1/results/0');
        $eventsSubmittable = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($eventsSubmittable, true), '{n}.id', 'asc');
        $this->assertEqual($expectedSubmittableEvents, $events);
        
        // get events in course id 1 for redshirt0001 that have results released
        $url = $this->_getURL('/v1/courses/1/users/redshirt0001/events/sub/0/results/1');
        $eventsResult = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($eventsResult, true), '{n}.id', 'asc');
        $this->assertEqual($expectedResultReleasedEvents, $events);
        
        // get events in course id 1 for redshirt0001 available for submissions OR have results released
        $url = $this->_getURL('/v1/courses/1/users/redshirt0001/events/sub/1/results/1');
        $filteredEvents = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($filteredEvents, true), '{n}.id', 'asc');
        $this->assertEqual($expectedFilteredEvents, $events);
        
        // get ALL events for redshirt0001 - no filters
        $url = $this->_getURL('/v1/users/redshirt0001/events');
        $events = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($events, true), '{n}.id', 'asc');
        $this->assertEqual($expectedEvents, $events);
        
        // get ALL events in course id 1 for redshirt0001 - no filters
        $url = $this->_getURL('/v1/courses/1/users/redshirt0001/events');
        $events = $this->_oauthReq("$url");
        $events = Set::sort(json_decode($events, true), '{n}.id', 'asc');
        $this->assertEqual($expectedEvents, $events);
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

        // Add a non-existent user
        $input = array(
            array('username' => 'redshirt0004', 'role_id' => 5),
            array('username' => 'redshirt0029', 'role_id' => 5),
            array('username' => 'instructor3', 'role_id' => 3),
            array('username' => 'tutor3', 'role_id' => 4),
            array('username' => 'redshirt0099', 'role_id' => 5)
        );
        $actual = $this->_oauthReq(
            $url, json_encode($input), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual(json_decode($actual, true), array());

        // Add a student to a course
        $input = array(
            array('username' => 'redshirt0004', 'role_id' => 5),
            array('username' => 'redshirt0029', 'role_id' => 5),
            array('username' => 'instructor3', 'role_id' => 3),
            array('username' => 'tutor3', 'role_id' => 4),
            array('username' => 'redshirt0005', 'role_id' => 5)
        );
        $expected = array(array('username' => 'redshirt0005', 'role_id' => 5));
        $actual = $this->_oauthReq(
            $url, json_encode($input), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // Add a duplicate student to a course
        $input = array_merge($input, array(array('username' => 'redshirt0029', 'role_id' => 5)));
        $expected = array();
        $actual = $this->_oauthReq(
            $url, json_encode($input), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // Add an instructor to a course
        $input[] = array('username' => 'instructor2', 'role_id' => 3);
        $expected = array(array('username' => 'instructor2', 'role_id' => 3));
        $actual = $this->_oauthReq(
            $url, json_encode($input), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // Add an instructor with duplicate usernames to a course
        $input = array_merge($input, array(array('username' => 'instructor1', 'role_id' => 3),
            array('username' => 'INStructor1', 'role_id' => 3)));
        $expected = array(array('username' => 'instructor1', 'role_id' => 3));
        $actual = $this->_oauthReq(
            $url, json_encode($input), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // Add a tutor to a course
        $input[] = array('username' => 'tutor2', 'role_id' => 4);
        $expected = array(array('username' => 'tutor2', 'role_id' => 4));
        $actual = $this->_oauthReq(
            $url, json_encode($input), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual($expected, json_decode($actual, true));

        // update course enrolment - remove instructor1
        $input = array(
            array('username' => 'redshirt0004', 'role_id' => 5),
            array('username' => 'redshirt0029', 'role_id' => 5),
            array('username' => 'instructor3', 'role_id' => 3),
            array('username' => 'tutor3', 'role_id' => 4),
            array('username' => 'redshirt0005', 'role_id' => 5),
            array('username' => 'redshirt0029', 'role_id' => 5),
            array('username' => 'instructor2', 'role_id' => 3),
            array('username' => 'tutor2', 'role_id' => 4)
        );
        $actual = $this->_oauthReq(
            $url, json_encode($input), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual(json_decode($actual, true), array());

        // check enrolment
        $actual = $this->_oauthReq("$url");
        $expected = array(
            array('id' => '8', 'role_id' => '5', 'username' => 'redshirt0004'),
            array('id' => '33', 'role_id' => '5', 'username' => 'redshirt0029'),
            array('id' => '9', 'role_id' => '5', 'username' => 'redshirt0005'),
            array('id' => '3', 'role_id' => '3', 'username' => 'instructor2'),
            array('id' => '4', 'role_id' => '3', 'username' => 'instructor3'),
            array('id' => '36', 'role_id' => '4', 'username' => 'tutor2'),
            array('id' => '37', 'role_id' => '4', 'username' => 'tutor3')
        );
        $this->assertEqual(json_decode($actual, true), $expected);

        // update course enrolment - remove instructor1
        $input = array(
            array('username' => 'redshirt0004', 'role_id' => 5),
            array('username' => 'redshirt0029', 'role_id' => 5),
            array('username' => 'instructor3', 'role_id' => 3),
            array('username' => 'tutor3', 'role_id' => 4),
            array('username' => 'redshirt0005', 'role_id' => 5),
            array('username' => 'redshirt0029', 'role_id' => 5),
            array('username' => 'instructor2', 'role_id' => 3),
            array('username' => 'tutor2', 'role_id' => 4)
        );
        $actual = $this->_oauthReq(
            $url, json_encode($input), OAUTH_HTTP_METHOD_POST);
        $this->assertEqual(json_decode($actual, true), array());

        // check enrolment
        $actual = $this->_oauthReq("$url");
        $expected = array(
            array('id' => '8', 'role_id' => '5', 'username' => 'redshirt0004'),
            array('id' => '33', 'role_id' => '5', 'username' => 'redshirt0029'),
            array('id' => '9', 'role_id' => '5', 'username' => 'redshirt0005'),
            array('id' => '3', 'role_id' => '3', 'username' => 'instructor2'),
            array('id' => '4', 'role_id' => '3', 'username' => 'instructor3'),
            array('id' => '36', 'role_id' => '4', 'username' => 'tutor2'),
            array('id' => '37', 'role_id' => '4', 'username' => 'tutor3')
        );
        $this->assertEqual(json_decode($actual, true), $expected);

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
