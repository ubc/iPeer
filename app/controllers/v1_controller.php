<?php
/**
 * V1Controller
 *
 * @uses Controller
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class V1Controller extends Controller {

    public $name = 'V1';
    public $uses = array(
        'User', 'RolesUser',
        'Group', 'Course', 'Event', 'EvaluationSimple', 'EvaluationRubric',
        'EvaluationMixeval', 'OauthClient', 'OauthNonce', 'OauthToken',
        'GroupsMembers', 'GroupEvent', 'Department', 'Role', 'CourseDepartment',
        'UserCourse', 'UserTutor', 'UserEnrol', 'Penalty'
    );
    public $helpers = array('Session');
    public $components = array('RequestHandler', 'Session');
    public $layout = "blank_layout";

    /**
     * body the request body
     *
     * @var string
     * @access private
     */
    private $body = "";

    /**
     * oauth function for use in test?
     */
    public function oauth() {
    }

    /**
     * Checks to see if required parameters are present?
     *
     * @return bool - false if something missing
     */
    private function _checkRequiredParams() {
        if (!isset($_REQUEST['oauth_consumer_key'])) {
            $this->set('oauthError', "Parameter Absent: oauth_consumer_key");
            $this->output = $this->render('oauth_error');
            return false;
        }
        if (!isset($_REQUEST['oauth_token'])) {
            $this->set('oauthError', "Parameter Absent: oauth_token");
            $this->output = $this->render('oauth_error');
            return false;
        }
        if (!isset($_REQUEST['oauth_signature_method'])) {
            $this->set('oauthError', "Parameter Absent: oauth_signature_method");
            $this->output = $this->render('oauth_error');
            return false;
        }
        if (!isset($_REQUEST['oauth_timestamp'])) {
            $this->set('oauthError', "Parameter Absent: oauth_timestamp");
            $this->output = $this->render('oauth_error');
            return false;
        }
        if (!isset($_REQUEST['oauth_nonce'])) {
            $this->set('oauthError', "Parameter Absent: oauth_nonce");
            $this->output = $this->render('oauth_error');
            return false;
        }
        // oauth_version is optional, but must be set to 1.0
        if (isset($_REQUEST['oauth_version']) &&
            $_REQUEST['oauth_version'] != "1.0"
        ) {
            $this->set('oauthError',
                "Parameter Rejected: oauth_version 1.0 only");
            $this->output = $this->render('oauth_error');
            return false;
        }
        if ($_REQUEST['oauth_signature_method'] != "HMAC-SHA1") {
            $this->set('oauthError',
                "Parameter Rejected: Only HMAC-SHA1 signatures supported.");
            $this->output = $this->render('oauth_error');
            return false;
        }
        return true;
    }

    /**
     * Recalculate the oauth signature and check it against the given signature
     * to make sure that they match.
     *
     * @return bool - true if signatures match
     */
    private function _checkSignature()
    {
        // Calculate the signature, note, going to assume that all incoming
        // parameters are already UTF-8 encoded since it'll be impossible
        // to convert encodings blindly
        $tmp = $_REQUEST;
        unset($tmp['oauth_signature']);
        unset($tmp['url']); // can ignore, mod_rewrite added, not sent by client
        // percent-encode the keys and values
        foreach ($tmp as $key => $val) {
            // change the value
            $val = rawurlencode($val);
            $tmp[$key] = $val;
            // change the key if needed
            $encodedKey = rawurlencode($key);
            if ($encodedKey != $key) {
                $tmp[$encodedKey] = $val;
                unset($tmp[$key]);
            }
        }
        // sort by keys into byte order, technically should have another
        // layer that sorts by value if keys are equal, but that shouldn't
        // happen with our api
        ksort($tmp);
        // construct the data string used in hmac calculation
        $params = "";
        foreach ($tmp as $key => $val) {
           $params .= $key . "=" . $val . "&";
        }
        $params = substr($params, 0, -1);
        $reqType = "GET";
        if ($this->RequestHandler->isPost()) {
            $reqType = "POST";
        } else if ($this->RequestHandler->isPut()) {
            $reqType = "PUT";
        } else if ($this->RequestHandler->isDelete()) {
            $reqType = "DELETE";
        }
        $params = "$reqType&" . rawurlencode(Router::url(null, true))
            . "&" . rawurlencode($params);
        // construct the key used for hmac calculation
        $clientSecret = $this->_getClientSecret($_REQUEST['oauth_consumer_key']);
        if (is_null($clientSecret)) {
            $this->log('Got invalid client ["'.(isset($_REQUEST['oauth_consumer_key']) ? $_REQUEST['oauth_consumer_key'] : "").'"]!');
            $this->set('oauthError', "Invalid Client");
            $this->output = $this->render('oauth_error');
            return false;
        }
        $clientSecret = rawurlencode($clientSecret);
        $tokenSecret = $this->OauthToken->getTokenSecret($_REQUEST['oauth_token']);
        if (is_null($tokenSecret)) {
            $this->log('Got invalid token ["'.(isset($_REQUEST['oauth_token']) ? $_REQUEST['oauth_token'] : "").'"]!');
            $this->set('oauthError', "Invalid Token");
            $this->output = $this->render('oauth_error');
            return false;
        }
        $tokenSecret = rawurlencode($tokenSecret);
        $secrets = $clientSecret . "&" . $tokenSecret;

        // get the binary result of the hmac calculation
        $hmac = hash_hmac('sha1', $params, $secrets, true);
        // need to encode it in base64
        $expected = base64_encode($hmac);
        // check to see if we got the signature we expected
        $actual = $_REQUEST['oauth_signature'];
        if ($expected != $actual) {
            $this->log('Got invalid signature. expecting: '.$expected.', actual: '.$actual.'.');
            $this->set('oauthError', "Invalid Signature");
            $this->output = $this->render('oauth_error');
            return false;
        }
        return true;
    }

    /**
     * Confirm that the nonce is valid. The nonce is valid if we have never
     * seen that nonce used before. Since we can't store every single nonce
     * ever used in a request, we limit the nonce storage to only 15 minutes.
     * This necessitates checking that the timestamp given by the client is
     * relatively similar to the server's. If a request comes in that is beyond
     * our 15 minute time frame for nonce storage, we can't be sure that the
     * nonce hasn't been used before.
     *
     * @return bool - true/false depending on nonce validity
     */
    private function _checkNonce() {
        // timestamp must be this many seconds within server time
        $validTimeWindow = 15 * 60; // 15 minutes
        $now = time();
        $then = $_REQUEST['oauth_timestamp'];
        $diff = abs($now - $then);
        // we should reject timestamps that are 15 minutes off from ours
        if ($diff > $validTimeWindow) {
            // more than 15 minutes of difference between the two times
            $this->set('oauthError', "Timestamp Refused");
            $this->output = $this->render('oauth_error');
            return false;
        }

        // delete nonces that we don't need to keep anymore.
        // Note that we do this before checking for nonce uniqueness since
        // we assume that all stored nonces are not expired. There is an edge
        // case where if a request reuses a nonce immediately after it expires,
        // we would reject the nonce since it hasn't been removed from the db.
        $this->OauthNonce->deleteAll(
            array('expires <' => date("Y-m-d H:i:s", $now - $validTimeWindow)));

        // check nonce uniqueness
        $nonce = $_REQUEST['oauth_nonce'];
        $ret = $this->OauthNonce->findByNonce($nonce);
        if ($ret) {
            // we've seen this nonce already
            $this->set('oauthError', "Nonce Used");
            $this->output = $this->render('oauth_error');
            return false;
        } else {
            // store nonce we haven't encountered before
            $this->OauthNonce->save(
                array(
                    'nonce' => $nonce,
                    'expires' => date("Y-m-d H:i:s", $now + $validTimeWindow)
                )
            );
        }

        return true;
    }

    /**
     * Retrieve the client credential secret based on the key. The client
     * credential identifies the client program acting on behalf of the
            iebug($input);
     * resource owner.
     *
     * @param mixed $key - identifier for the secret.
     *
     * @return The secret if found, null if not.
     */
    private function _getClientSecret($key) {
        $ret = $this->OauthClient->findByKey($key);
        if (!empty($ret)) {
            if ($ret['OauthClient']['enabled']) {
                return $ret['OauthClient']['secret'];
            }
        }
        return null;
    }


    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    public function beforeFilter() {
        // expecting a body except get request
        if (!$this->RequestHandler->isGet()) {
            $this->body = trim(file_get_contents('php://input'), true);
        }
        $this->log('Got API request: '. $this->getRequestInfo($_REQUEST, $this->body), 'api');

        // use oauth=0 paramter in url to bypass oauth checking
        if (!(Configure::read('debug') != 0 && isset($_REQUEST['oauth'])) &&
            !($this->_checkRequiredParams() && $this->_checkSignature() && $this->_checkNonce())) {

            $this->log('Parameter checking failed: '.$this->output);
            echo $this->output;
            $this->_stop();
        }
    }

    /**
     * Empty controller action for displaying the oauth error page.
     */
    public function oauth_error() {
    }

    /**
     * Get a list of users in iPeer.
     *
     * @param mixed $id
     */
    public function users($id = null) {
        // view
        if ($this->RequestHandler->isGet()) {
            $data = array();
            // all users
            if (null == $id) {
                $users = $this->User->find('all');
                if (!empty($users)) {
                    foreach ($users as $user) {
                        $tmp = array();
                        $tmp['id'] = $user['User']['id'];
                        $tmp['role_id'] = $user['Role']['0']['id'];
                        $tmp['username'] = $user['User']['username'];
                        $tmp['last_name'] = $user['User']['last_name'];
                        $tmp['first_name'] = $user['User']['first_name'];
                        $data[] = $tmp;
                    }
                    $statusCode = 'HTTP/1.1 200 OK';
                } else {
                    $statusCode = 'HTTP/1.1 404 Not Found';
                    $data = null;
                }
            // specific user
            } else {
                $user = $this->User->find(
                    'first',
                    array('conditions' => array('User.id' => $id))
                );
                if (!empty($user)) {
                    $data = array(
                        'id' => $user['User']['id'],
                        'role_id' => $user['Role']['0']['id'],
                        'username' => $user['User']['username'],
                        'last_name' => $user['User']['last_name'],
                        'first_name' => $user['User']['first_name']
                    );
                    $statusCode = 'HTTP/1.1 200 OK';
                } else {
                    $statusCode = 'HTTP/1.1 404 Not Found';
                    $data = null;
                }
            }
            $this->set('result', $data);
            $this->set('statusCode', $statusCode);
        // add
        } else if ($this->RequestHandler->isPost()) {
            $input = $this->body;
            $decode = json_decode($input, true);
            // adding one user
            if (isset($decode['username'])) {
                $role = array('Role' => array('RolesUser' => array('role_id' => $decode['role_id'])));
                unset($decode['role_id']);
                // do some clean up before we insert the values
                array_walk($decode, create_function('&$val', '$val = trim($val);'));
                $user = array('User' => $decode);
                $user = $user + $role;

                // does not save role in RolesUser - need to fix
                if ($this->User->save($user)) {
                    $user = $this->User->read(array('id','username','last_name','first_name'));
                    $role = $this->RolesUser->read('role_id');
                    $combine = $user['User'] + array('role_id' => $role['RolesUser']['role_id']);
                    $statusCode = 'HTTP/1.1 201 Created';
                    $body = $combine;
                } else {
                    $statusCode = 'HTTP/1.1 500 Internal Server Error';
                    $body = null;
                }
            // adding multiple users from import (expected input: array)
            } else if (isset($decode['0'])) {
                $data = array();
                // rearrange the data
                foreach ($decode as $person) {
                    $pRole = array('Role' => array('RolesUser' => array('role_id' => $person['role_id'])));
                    unset($person['role_id']);
                    // do some clean up before we insert the values
                    array_walk($person, create_function('&$val', '$val = trim($val);'));
                    $pUser = array('User' => $person);
                    $data[] = $pUser + $pRole;
                }
                $sUser = array();
                $uUser = array();
                $result = $this->User->saveAll($data, array('atomic' => false));
                $statusCode = 'HTTP/1.1 500 Internal Server Error';
                foreach ($result as $key => $ret) {
                    if ($ret) {
                        $statusCode = 'HTTP/1.1 201 Created';
                        $sUser[] = $decode[$key]['username'];
                        $this->log('User created successful: '. $decode[$key]['username'], 'api');
                    } else {
                        $temp = array();
                        $temp['username'] = $decode[$key]['username'];
                        $temp['first_name'] = $decode[$key]['first_name'];
                        $temp['last_name'] = $decode[$key]['last_name'];
                        $uUser[] = $temp;
                        $this->log('User created failed: '. $decode[$key]['username'], 'api');
                    }
                }
                $sbody = $this->User->find('all', array(
                        'conditions' => array('username' => $sUser),
                        'fields' => array('User.id', 'username', 'last_name', 'first_name')
                    ));
                foreach ($sbody as $sb) {
                    // at the moment assuming one role per user
                    $body[] = $sb['User'] + array('role_id' => $sb['Role']['0']['id']);
                }

                foreach ($uUser as $check) {
                    $verify = $this->User->find('first', array(
                        'conditions' => array('username' => $check['username'], 'last_name' => $check['last_name'], 'first_name' => $check['first_name']),
                        'fields' => array('User.id', 'username', 'first_name', 'last_name')
                    ));
                    if (!empty($verify)) {
                        $statusCode = 'HTTP/1.1 201 Created';
                        $body[] = $verify['User'] + array('role_id' => $verify['Role']['0']['id']);
                    }
                }
            // incorrect format
            } else {
                $statusCode = 'HTTP/1.1 400 Bad Request';
                $body = null;
            }
            $this->set('statusCode', $statusCode);
            $this->set('result', $body);
        // delete
        } else if ($this->RequestHandler->isDelete()) {
            if ($this->User->delete($id)) {
                $this->set('statusCode', 'HTTP/1.1 204 No Content');
                $this->set('result', null);
            } else {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', null);
            }
        // update
        } else if ($this->RequestHandler->isPut()) {
            $edit = $this->body;
            $decode = json_decode($edit, true);
            // at the moment each user only has one role
            $role = array('Role' => array('RolesUser' => array('role_id' => $decode['role_id'])));
            unset($decode['role_id']);
            $user = array('User' => $decode);
            $user = $user + $role;
            // does not save role in RolesUser - need to fix
            if ($this->User->save($user)) {
                $user = $this->User->read(array('id','username','last_name','first_name'));
                $role = $this->RolesUser->find('first', array('conditions' => array('user_id' => $user['User']['id']), 'fields' => 'role_id'));
                $combine = $user['User'] + array('role_id' => $role['RolesUser']['role_id']);
                $this->set('statusCode', 'HTTP/1.1 200 OK');
                $this->set('result', $combine);
            } else {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', null);
            }
        } else {
            $this->set('statusCode', 'HTTP/1.1 400 Bad Request');
            $this->set('result', null);
        }
        $this->render('json');
    }

    /**
     * Get a list of courses in iPeer.
     *
     * @param mixed $id
     */
    public function courses($id = null) {
        $classes = array();
        if ($this->RequestHandler->isGet()) {
            if (null == $id) {
                $courses = $this->Course->find('all',
                    array('fields' => array('id', 'course', 'title', 'student_count'))
                );
                if (!empty($courses)) {
                    foreach ($courses as $course) {
                        $classes[] = $course['Course'];
                    }
                }
                $statusCode = 'HTTP/1.1 200 OK';
            } else {
                // specific course
                $course = $this->Course->find('first',
                    array(
                        'conditions' => array('id' => $id),
                        'fields' => array('id', 'course', 'title', 'student_count'),
                    )
                );
                if (!empty($course)) {
                    $classes = $course['Course'];
                    $statusCode = 'HTTP/1.1 200 OK';
                } else {
                    $classes = array('code' => 2, 'message' => 'Course does not exists.');
                    $statusCode = 'HTTP/1.1 404 Not Found';
                }
            }
            $this->set('result', $classes);
            $this->set('statusCode', $statusCode);
        } else if ($this->RequestHandler->isPost()) {
            $create = $this->body;
            if (!$this->Course->save(json_decode($create, true))) {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', array('code' => 1, 'message' => 'course already exists.'));
            } else {
                $temp = $this->Course->read(array('id','course','title'));
                $course = $temp['Course'];
                $this->set('statusCode', 'HTTP/1.1 201 Created');
                $this->set('result', $course);
            }
        } else if ($this->RequestHandler->isPut()) {
            $update = $this->body;
            if (!$this->Course->save(json_decode($update, true))) {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', null);
            } else {
                $temp = $this->Course->read(array('id','course','title'));
                $course = $temp['Course'];
                $this->set('statusCode', 'HTTP/1.1 200 OK');
                $this->set('result', $course);
            }
        } else if ($this->RequestHandler->isDelete()) {
            if (!$this->Course->delete($id)) {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', null);
            } else {
                $this->set('statusCode', 'HTTP/1.1 204 No Content');
                $this->set('result', null);
            }
        } else {
            $this->set('statusCode', 'HTTP/1.1 400 Bad Request');
            $this->set('result', null);
        }
        $this->render('json');
    }

    /**
     * Get a list of groups in iPeer.
     **/
    public function groups()
    {
        $fields = array('id', 'group_num', 'group_name', 'course_id', 'member_count');
        // view
        if ($this->RequestHandler->isGet()) {
            $data = array();
            if (!isset($this->params['group_id']) || null == $this->params['group_id']) {
                $groups = $this->Group->find(
                    'all',
                    array(
                        'conditions' => array('course_id' => $this->params['course_id']),
                        'fields' => $fields,
                        'recursive' => 0
                    )
                );

                if (!empty($groups)) {
                    foreach ($groups as $group) {
                        $data[] = $group['Group'];
                    }
                } else {
                    $data = array();
                }
                $statusCode = 'HTTP/1.1 200 OK';
            } else {
                $group = $this->Group->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Group.id' => $this->params['group_id'],
                            'course_id' => $this->params['course_id']
                        ),
                        'fields' => $fields,
                        'recursive' => 0
                    )
                );
                if (!empty($group)) {
                    $data = $group['Group'];
                    $statusCode = 'HTTP/1.1 200 OK';
                } else {
                    $data = array();
                    $statusCode = 'HTTP/1.1 404 Not Found';
                }
            }
            $this->set('result', $data);
            $this->set('statusCode', $statusCode);
        // add
        } else if ($this->RequestHandler->isPost()) {
            $add = $this->body;
            $decode = array('Group' => json_decode($add, true));
            $decode['Group']['course_id'] = $this->params['course_id'];

            if ($this->Group->save($decode)) {
                $tempGroup = $this->Group->read($fields);
                $group = $tempGroup['Group'];
                $this->set('statusCode', 'HTTP/1.1 201 Created');
                $this->set('result', $group);
            } else {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', null);
            }
        // delete
        } else if ($this->RequestHandler->isDelete()) {
            if ($this->Group->delete($this->params['group_id'])) {
                $this->set('statusCode', 'HTTP/1.1 204 No Content');
                $this->set('result', null);
            } else {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', null);
            }
        // update
        } else if ($this->RequestHandler->isPut()) {
            $edit = $this->body;
            $decode = array('Group' => json_decode($edit, true));
            if ($this->Group->save($decode)) {
                $temp = $this->Group->read($fields);
                $group = $temp['Group'];
                $this->set('statusCode', 'HTTP/1.1 200 OK');
                $this->set('result', $group);
            } else {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', null);
            }
        } else {
            $this->set('statusCode', 'HTTP/1.1 400 Bad Request');
            $this->set('result', null);
        }

        $this->render('json');
    }

    /**
     * get, add, and delete group members from a group
    **/
    public function groupMembers()
    {
        $status = 'HTTP/1.1 400 Bad Request';
        $groupMembers = array();

        $groupId = $this->params['group_id'];
        $username = $this->params['username'];

        if ($this->RequestHandler->isGet()) {
            // retrieve a list of users in the given group
            $users = $this->User->getMembersByGroupId($groupId);
            foreach ($users as $user) {
                $tmp = array();
                $tmp['id'] = $user['User']['id'];
                $tmp['role_id'] = $user['Role']['0']['id'];
                $tmp['username'] = $user['User']['username'];
                $tmp['last_name'] = $user['User']['last_name'];
                $tmp['first_name'] = $user['User']['first_name'];
                $groupMembers[] = $tmp;
            }

            $status = 'HTTP/1.1 200 OK';
        } else if ($this->RequestHandler->isPost()) {
            // add the list of users to the given group
            $ret = $this->body;
            $users = json_decode($ret, true);
            $group = $this->Group->findById($groupId);
            $courseId = $group['Group']['course_id'];
            $students = $this->UserEnrol->find('list', array('conditions' => array('course_id' => $courseId), 'fields' => array('user_id')));
            $tutors = $this->UserTutor->find('list', array('conditions' => array('course_id' => $courseId), 'fields' => array('user_id')));
            $members = array_merge($students, $tutors);
            $inClass = $this->User->find('list', array('conditions' => array('User.id' => $members), 'fields' => array('User.username')));
            $userIds = array();
            $status = 'HTTP/1.1 200 OK';
            foreach ($users as $user) {
                if (!in_array($user['username'], $inClass)) {
                    $this->log('User '.$user['username'].' is not in the course '.$courseId, 'debug');
                    continue;
                }
                $userId = $this->User->field('id',
                    array('username' => $user['username']));
                // skip the non-existing users
                if (!$userId) {
                    continue;
                }
                $userIds[] = $userId;
                $tmp = array('group_id' => $groupId, 'user_id' => $userId);
                // try to add this user to group
                $this->GroupsMembers->create();
                if ($this->GroupsMembers->save($tmp)) {
                    $userId = $this->GroupsMembers->read('user_id');
                    $this->GroupsMembers->id = null;
                    $groupMembers[] = $user;
                } else {
                    $status = 'HTTP/1.1 500 Internal Server Error';
                    break;
                }
            }
            $origMembers = Set::extract('/Member/id', $group);
            $toRemove = array_diff($origMembers, $userIds);
            if (!empty($toRemove)) {
                if ($this->GroupsMembers->deleteAll(array('user_id' => $toRemove, 'group_id' => $groupId))) {
                    $this->log('Removed users '.implode(', ', $toRemove).' from group '.$groupId, 'debug');
                } else {
                    $this->log('Failed to remove users '.implode(', ', $toRemove).' from group '.$groupId, 'debug');
                }
            }
        } else if ($this->RequestHandler->isDelete()) {
            // delete a user from the given group
            $userId = $this->User->field('id', array('username' => $username));
            $gmId = $this->GroupsMembers->field('id',
                array('user_id' => $userId, 'group_id' => $groupId));
            if ($this->GroupsMembers->delete($gmId)) {
                $status = 'HTTP/1.1 204 No Content';
            } else {
                $status = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        $this->set('statusCode', $status);
        $this->set('result', $groupMembers);
        $this->render('json');
    }

    /**
     * Get a list of events in iPeer.
     **/
    public function events()
    {
        $course_id = $this->params['course_id'];
        $results = array();
        $fields = array('title', 'course_id', 'event_template_type_id', 'due_date');

        if ($this->RequestHandler->isGet()) {
            if (!isset($this->params['event_id']) || empty($this->params['event_id'])) {
                $list = $this->Event->getEventFieldsByCourseId($course_id, $fields);

                if (!empty($list)) {
                    foreach ($list as $data) {
                        $results[] = $data['Event'];
                    }
                }
                $statusCode = 'HTTP/1.1 200 OK';
            } else {
                $list = $this->Event->getEventFieldsByEventId($this->params['event_id'], $fields);

                if (!empty($list)) {
                    $results = $list['Event'];
                }
                $statusCode = 'HTTP/1.1 200 OK';
            }
            $this->set('statusCode', $statusCode);
            $this->set('result', $results);
        } else {
            $this->set('statusCode', 'HTTP/1.1 400 Bad Request');
            $this->set('result', null);
        }
        $this->render('json');
    }

    /**
     * Get a list of grades in iPeer.
     **/
    public function grades()
    {
        $event_id = $this->params['event_id'];
        $username = $this->params['username']; // if set, only want 1 user
        $user_id = $this->User->field('id',
            array('username' => $username));
        $type = $this->Event->getEventTemplateTypeId($event_id);

        // assume failure initially
        $results = array();
        $statusCode = 'HTTP/1.1 400 Bad Request'; // unrecognized request type

        // initialize find parameters
        $fields = array('id', 'evaluatee', 'score');
        $conditions = array('event_id' => $event_id);
        // add additional conditions if they only want 1 user
        if ($user_id) {
            $conditions['evaluatee'] = $user_id;
        }

        if ($this->RequestHandler->isGet()) {
            $res = array();
            $key = ""; // name of the table we're querying
            if ($type == 1) {
                $res = $this->EvaluationSimple->simpleEvalScore($event_id,
                    $fields, $conditions);
                $key = "EvaluationSimple";
            } else if ($type == 2) {
                $res = $this->EvaluationRubric->rubricEvalScore($event_id,
                    $fields, $conditions);
                $key = "EvaluationRubric";
            } else if ($type == 4) {
                $res = $this->EvaluationMixeval->mixedEvalScore($event_id,
                    $fields, $conditions);
                $key = "EvaluationMixeval";
            }
            foreach ($res as $val) {
                unset($val[$key]['id']);
                $results[] = $val[$key];
            }
            $statusCode = 'HTTP/1.1 200 OK';
        }

        // add in username
        if ($user_id && !empty($results)) {
            // remove from array if they wanted only 1 user
            $results = $results[0];
            $results['username'] = $username;
        } else {
            foreach ($results as &$result) {
                $username = $this->User->field('username',
                    array('id' => $result['evaluatee']));
                $result['username'] = $username;
            }
        }

        $this->set('result', $results);
        $this->set('statusCode', $statusCode);
        $this->render('json');
    }

    /**
     * Get a list of departments in iPeer
     *
     * @param bool $departmentId
     *
     * @access public
     * @return void
     */
    public function departments($departmentId = null)
    {
        if ($this->RequestHandler->isGet()) {
            if (is_null($departmentId)) {
                $departments = array();
                $dps = $this->Department->find('all',
                    array('fields' => array('id', 'name'))
                );
                if (!empty($dps)) {
                    foreach ($dps as $dp) {
                        $departments[] = $dp['Department'];
                    }
                    $statusCode = 'HTTP/1.1 200 OK';
                } else {
                    $departments = null;
                    $statusCode = 'HTTP/1.1 404 Not Found';
                }
            } else {
                $courseDepts = $this->CourseDepartment->find('list',
                    array('conditions' => array('department_id' => $departmentId),
                        'fields' => array('course_id')));
                $courses = $this->Course->find('all',
                    array('conditions' => array('Course.id' => $courseDepts),
                        'fields' => array('Course.id', 'course', 'title')));
                if (!empty($courses)) {
                    $departments = array();
                    foreach ($courses as $course) {
                        $departments[] = $course['Course'];
                    }
                    $statusCode = 'HTTP/1.1 200 OK';
                } else {
                    $departments = null;
                    $statusCode = 'HTTP/1.1 404 Not Found';
                }
            }
            $this->set('result', $departments);
            $this->set('statusCode', $statusCode);
        } else {
            $this->set('result', null);
            $this->set('statusCode', 'HTTP/1.0 400 Bad Request');
        }

        $this->render('json');
    }

    /**
     * Add or Delete departments in iPeer
     *
     * @access public
     * @return void
     */
    public function courseDepartments()
    {
        $department_id = $this->params['department_id'];
        $course_id = $this->params['course_id'];
        //POST: array{'course_id', 'faculty_id'} ; assume 1 for now
        if ($this->RequestHandler->isPost()) {
            if ($this->Course->habtmAdd('Department', $course_id, $department_id)) {
                $this->set('statusCode', 'HTTP/1.1 201 Created');
                $departments = $this->CourseDepartment->find('first',
                    array('conditions' => array('course_id' => $course_id, 'department_id' => $department_id)));
                $departments = $departments['CourseDepartment'];
                unset($departments['id']);
                $this->set('result', $departments);
            } else {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', array());
            }
        } else if ($this->RequestHandler->isDelete()) {
            if ($this->Course->habtmDelete('Department', $course_id, $department_id)) {
                $this->set('statusCode', 'HTTP:/1.1 204 No Content');
                $this->set('result', array());
            } else {
                $this->set('statusCode', 'HTTP/1.1 500 Internal Server Error');
                $this->set('result', array());
            }
        } else {
            $this->set('statusCode', 'HTTP/1.0 400 Bad Request');
            $this->set('result', array());
        }

        $this->render('json');
    }

    /**
     * retrieving events a user has access to (eg. student)
    **/
    public function userEvents()
    {
        $username = $this->params['username'];

        if ($this->RequestHandler->isGet()) {
            $user = $this->User->find('first', array('conditions' => array('User.username' => $username)));
            $user_id = $user['User']['id'];

            $fields = array('title', 'course_id', 'event_template_type_id', 'due_date', 'release_date_begin', 'release_date_end', 'id');
            $events = $this->Event->getPendingEventsByUserId($user_id, $fields);

            $this->set('statusCode', 'HTTP/1.1 200 OK');
            $this->set('result', $events);
        } else {
            $this->set('statusCode', 'HTTP/1.1 400 Bad Request');
            $this->set('result', null);
        }

        $this->render('json');
    }

    /**
     * Enrol users in courses
     */
    public function enrolment() {
        $this->set('statusCode', 'HTTP/1.1 400 Unrecognizable Request');
        $this->set('result', null);
        $courseId = $this->params['course_id'];

        // Get request, just return a list of users
        if ($this->RequestHandler->isGet()) {
            $students = $this->User->getEnrolledStudents($courseId);
            $instructors = $this->User->getInstructorsByCourse($courseId);
            $tutors = $this->User->getTutorsByCourse($courseId);

            $ret = array_merge($students, $instructors, $tutors);
            $users = array();
            foreach ($ret as $entry) {
                $user = array(
                    'id' => $entry['User']['id'],
                    'role_id' => $entry['Role']['0']['id'],
                    'username' => $entry['User']['username']
                );
                $users[] = $user;
            }
            $this->set('statusCode', 'HTTP/1.1 200 OK');
            $this->set('result', $users);
        } else if ($this->RequestHandler->isPost()) {
        // Post request, add user to course
        // if user already in course, count as successful
        // if user failed save, stop execution and return an error
        //
            $this->set('statusCode', 'HTTP/1.1 200 OK');
            $input = $this->body;
            $users = json_decode($input, true);

            $students = $this->UserEnrol->find('list', array('conditions' => array('course_id' => $courseId), 'fields' => array('user_id')));
            $tutors = $this->UserTutor->find('list', array('conditions' => array('course_id' => $courseId), 'fields' => array('user_id')));
            $instructors = $this->UserCourse->find('list', array('conditions' => array('course_id' => $courseId), 'fields' => array('user_id')));
            $members = $students + $tutors + $instructors;
            $inClass = array();
            if (!empty($members)) {
                $inClass = $this->User->find('list', array('conditions' => array('User.id' => $members), 'fields' => array('User.username')));
            }
            $result = array();

            foreach ($users as $user) {
                $this->log('processing user '.$user['username'].' to course '.$courseId, 'api');
                // check if the user is already in the course using case
                // insensitive username
                if (count(preg_grep("/^".$user['username']."$/i", $inClass)) == 0) {
                    $userId = $this->User->field('id',
                        array('username' => $user['username']));
                    // skip users not in the system
                    if (empty($userId)) {
                        $this->log('Username '.$user['username'].' does not exist in the system.', 'debug');
                        continue;
                    }
                    $role = $this->Role->getRoleName($user['role_id']);
                    $table = null;
                    if ($role == 'student') {
                        $ret = $this->User->addStudent($userId, $courseId);
                        $this->log('Adding student '.$user['username'].' to course '.$courseId, 'api');
                    } else if ($role == 'instructor') {
                        $ret = $this->User->addInstructor($userId, $courseId);
                        $this->log('Adding instructor '.$user['username'].' to course '.$courseId, 'api');
                    } else if ($role == 'tutor') {
                        $ret = $this->User->addTutor($userId, $courseId);
                        $this->log('Adding tutor '.$user['username'].' to course '.$courseId, 'api');
                    } else {
                        $this->set('error', array('code' => 400, 'message' => 'Unsupported role for '.$user['username']));
                        $this->render('error');
                        return;
                    }
                    if (!$ret) {
                        $this->set('error', array('code' => 401, 'message' => 'Fail to enrol ' . $user['username']));
                        $this->render('error');
                        return;
                    }

                    // add the new user to our checklist to prevent duplication
                    // in the request
                    $inClass[] = $user['username'];

                    $result[] = $user;
                }
            }
            // unenrol students that are no longer in the class, this will become a problem if
            // only a fraction of the class list is given because the rest of the class will
            // be unenrolled. One example is the api being used to only enrol one user.
            $users = Set::extract('/username', $users);
            $unEnrol = array_diff($inClass, $users);
            foreach ($unEnrol as $user) {
                $userId = $this->User->field('id', array('username' => $user));
                $role = $this->User->getRoleName($userId);
                if ($role == 'student') {
                    $ret = $this->User->removeStudent($userId, $courseId);
                    $this->log('Removing student '.$user['username'].' from course '.$courseId, 'debug');
                } else if ($role == 'instructor') {
                    $ret = $this->User->removeInstructor($userId, $courseId);
                    $this->log('Removing instructor '.$user['username'].' from course '.$courseId, 'debug');
                } else if ($role == 'tutor') {
                    $ret = $this->User->removeTutor($userId, $courseId);
                    $this->log('Removing tutor '.$user['username'].' from course '.$courseId, 'debug');
                } else {
                    $this->set('error', array('code' => 400, 'message' => 'Unsupported role for '.$user['username'].'. Could not unenrol.'));
                    $this->render('error');
                    return;
                }
                if (!$ret) {
                    $this->set('error', array('code' => 401, 'message' => 'Fail to unenrol ' . $user['username']));
                    $this->render('error');
                    return;
                }
            }

            $this->set('result', $result);
        } else if ($this->RequestHandler->isDelete()) {
            $this->set('statusCode', 'HTTP/1.1 200 OK');
            $input = $this->body;
            $users = json_decode($input, true);
            foreach ($users as $user) {
                $userId = $this->User->field('id',
                    array('username' => $user['username']));
                $role = $this->Role->getRoleName($user['role_id']);
                $table = null;
                if ($role == 'student') {
                    $ret = $this->User->removeStudent($userId, $courseId);
                } else if ($role == 'instructor') {
                    $ret = $this->User->removeInstructor($userId, $courseId);
                } else if ($role == 'tutor') {
                    $ret = $this->User->removeTutor($userId, $courseId);
                } else {
                    $this->set('error', array('code' => 400, 'message' => 'Unsupported role for '.$user['username']));
                    $this->render('error');
                    return;
                }
                if (!$ret) {
                    $this->set('error', array('code' => 401, 'message' => 'Fail to enrol ' . $user['username']));
                    $this->render('error');
                    return;
                }
            }
            $this->set('result', $users);
        }
        $this->render('json');
    }

    protected function getRequestInfo($request, $body)
    {
        $ret = '';
        $ret .= $_SERVER['REQUEST_METHOD'] . ' ' . $request['url']. "\n";
        $ret .= "Params: \n";
        foreach ($request as $key => $value) {
            if ($key == 'url') {
                continue;
            }
            $ret .= "    ".$key.": ".$value."\n";
        }
        $ret .= "Body: ".$body;

        return $ret;
    }
}
