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
    public $uses = array('User', 'Group', 'Course', 'Event', 'EvaluationSimple', 'EvaluationRubric', 'EvaluationMixeval', 'OauthClient', 'OauthNonce', 'OauthToken');
    public $helpers = array('Session');
    public $components = array('RequestHandler', 'Session');
    public $layout = "blank_layout";

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
            $this->render('oauth_error');
            return false;
        }
        if (!isset($_REQUEST['oauth_token'])) {
            $this->set('oauthError', "Parameter Absent: oauth_token");
            $this->render('oauth_error');
            return false;
        }
        if (!isset($_REQUEST['oauth_signature_method'])) {
            $this->set('oauthError', "Parameter Absent: oauth_signature_method");
            $this->render('oauth_error');
            return false;
        }
        if (!isset($_REQUEST['oauth_timestamp'])) {
            $this->set('oauthError', "Parameter Absent: oauth_timestamp");
            $this->render('oauth_error');
            return false;
        }
        if (!isset($_REQUEST['oauth_nonce'])) {
            $this->set('oauthError', "Parameter Absent: oauth_nonce");
            $this->render('oauth_error');
            return false;
        }
        // oauth_version is optional, but must be set to 1.0
        if (isset($_REQUEST['oauth_version']) && 
            $_REQUEST['oauth_version'] != "1.0"
        ) {
            $this->set('oauthError',
                "Parameter Rejected: oauth_version 1.0 only");
            $this->render('oauth_error');
            return false;
        }
        if ($_REQUEST['oauth_signature_method'] != "HMAC-SHA1") {
            $this->set('oauthError',
                "Parameter Rejected: Only HMAC-SHA1 signatures supported.");
            $this->render('oauth_error');
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
    private function _checkSignature() {
        // Calculate the signature, note, going to assume that all incoming
        // parameters are already UTF-8 encoded since it'll be impossible
        // to convert encodings wit
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
        $params = "$reqType&" . rawurlencode(Router::url($this->here, true)) 
            . "&" . rawurlencode($params);
        // construct the key used for hmac calculation
        $clientSecret = $this->_getClientSecret($_REQUEST['oauth_consumer_key']);
        if (is_null($clientSecret)) {
            $this->set('oauthError', "Invalid Client");
            $this->render('oauth_error');
            return false;
        }
        $clientSecret = rawurlencode($clientSecret);
        $tokenSecret = $this->_getTokenSecret($_REQUEST['oauth_token']);
        if (is_null($tokenSecret)) {
            $this->set('oauthError', "Invalid Token");
            $this->render('oauth_error');
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
            $this->set('oauthError', "Invalid Signature");
            $this->render('oauth_error');
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
            $this->render('oauth_error');
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
            $this->render('oauth_error');
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
     * Retrieve the token credential secret based on the key. The token
     * credential identifies the resource owner (user).
     *
     * @param mixed $key - identifier for the secret.
     *
     * @return The secret if found, null if not.
     */
    private function _getTokenSecret($key) {
        $ret = $this->OauthToken->findByKey($key);
        if (!empty($ret)) {
            if ($ret['OauthToken']['enabled'] &&
                strtotime($ret['OauthToken']['expires']) > time()
            ) {
                return $ret['OauthToken']['secret'];
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
        return $this->_checkRequiredParams() &&
        $this->_checkSignature() &&
        $this->_checkNonce();
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
                    $statusCode = 'HTTP/1.0 200 OK';
                } else {
                    $statusCode = 'HTTP/1.0 404 Not Found';
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
                    $statusCode = 'HTTP/1.0 200 OK';
                } else {
                    $statusCode = 'HTTP/1.0 404 Not Found';
                    $data = null;
                }
            }
            $this->set('user', $data);
            $this->set('statusCode', $statusCode);
        // add
        } else if ($this->RequestHandler->isPost()) {
            $input = trim(file_get_contents('php://input'), true);
            if ($this->User->save(json_decode($input, true))) {
                $user = $this->User->read('id');
                $userId = array('id' => $user['User']['id']);
                $this->set('statusCode', 'HTTP/1.0 201 Created');
                $this->set('user', $userId);
            } else {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error'); 
                $this->set('user', null);
            }
        // delete
        } else if ($this->RequestHandler->isDelete()) {
            if ($this->User->delete($id)) {
                $this->set('statusCode', 'HTTP/1.0 204 No Content');
                $this->set('user', null);
            } else {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('user', null);
            }
        // update
        } else if ($this->RequestHandler->isPut()) {
            $edit = trim(file_get_contents('php://input'), true);
            if ($this->User->save(json_decode($edit, true))) {
                $user = $this->User->read('id');
                $userId = array('id' => $user['User']['id']);
                $this->set('statusCode', 'HTTP/1.0 200 OK');
                $this->set('user', $userId);
            } else {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('user', null);
            }
        } else {
            $this->set('statusCode', 'HTTP/1.0 400 Bad Request');
            $this->set('user', null);
        }
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
                    array('fields' => array('id', 'course', 'title'))
                );
                if (!empty($courses)) {
                    foreach ($courses as $course) {
                        $classes[] = $course['Course'];
                    }
                    $statusCode = 'HTTP/1.0 200 OK';
                } else {
                    $classes = null;
                    $statusCode = 'HTTP/1.0 404 Not Found';
                }
            } else {
            // specific course
                $course = $this->Course->find('first', 
                    array(
                        'conditions' => array('id' => $id),
                        'fields' => array('id', 'course', 'title'),
                    )
                );
                if (!empty($course)) {
                    $classes = $course['Course'];
                    $statusCode = 'HTTP/1.0 200 OK';
                } else {
                    $classes = null;
                    $statusCode = 'HTTP/1.0 404 Not Found';
                }
            }
            $this->set('courses', $classes);
            $this->set('statusCode', $statusCode);
        } else if ($this->RequestHandler->isPost()) {
            $create = trim(file_get_contents('php://input'), true);
            if (!$this->Course->save(json_decode($create, true))) {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('courses', null);
            } else {
                $temp = $this->Course->read(array('id','course','title'));
                $course = $temp['Course'];
                $this->set('statusCode', 'HTTP/1.0 201 Created');
                $this->set('courses', $course);
            }
        } else if ($this->RequestHandler->isPut()) {   
            $update = trim(file_get_contents('php://input'), true);
            if (!$this->Course->save(json_decode($update, true))) {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('courses', null);
            } else {
                $temp = $this->Course->read(array('id','course','title'));
                $course = $temp['Course'];
                $this->set('statusCode', 'HTTP/1.0 200 OK');
                $this->set('courses', $course);
            }
        } else if ($this->RequestHandler->isDelete()) {
            if (!$this->Course->delete($id)) {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('courses', null);
            } else {
                $this->set('statusCode', 'HTTP/1.0 204 No Content');
                $this->set('courses', null);
            }
        } else {
            $this->set('statusCode', 'HTTP/1.0 400 Bad Request');
            $this->set('courses', null);
        }
    }
    
    /**
     * Get a list of groups in iPeer.
     **/
    public function groups() {
        // view
        if ($this->RequestHandler->isGet()) {
            $data = array();
            if (null == $this->params['group_id']) {
                $groups = $this->Group->find(
                    'all',
                    array(
                        'conditions' => array('course_id' => $this->params['course_id']),
                        'fields' => array('id', 'group_num', 'group_name', 'course_id'),
                        'recursive' => 0
                    )
                );
                
                if (!empty($groups)) {
                    foreach ($groups as $group) {
                        $data[] = $group['Group'];
                    }
                    $statusCode = 'HTTP/1.0 200 OK';
                } else {
                    $data = null;
                    $statusCode = 'HTTP/1.0 404 Not Found';
                }
            } else {
                $group = $this->Group->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Group.id' => $this->params['group_id'],
                            'course_id' => $this->params['course_id']
                        ),
                        'fields' => array('id', 'group_num', 'group_name', 'course_id'),
                        'recursive' => 0
                    )
                );
                if (!empty($group)) {
                    $data = $group['Group'];
                    $statusCode = 'HTTP/1.0 200 OK';
                } else {
                    $data = null;
                    $statusCode = 'HTTP/1.0 404 Not Found';
                }
            }
            $this->set('group', $data);
            $this->set('statusCode', $statusCode);
        // add
        } else if ($this->RequestHandler->isPost()) {
            $add = trim(file_get_contents('php://input'), true);
            if ($this->Group->save(json_decode($add, true))) {
                $group = $this->Group->read('id');
                $groupId = $group['Group']['id'];
                $this->set('statusCode', 'HTTP/1.0 201 Created');
                $this->set('group', array('id' => $groupId));
            } else {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('group', null);
            }
        // delete
        } else if ($this->RequestHandler->isDelete()) {
            if ($this->Group->delete($this->params['group_id'])) {
                $this->set('statusCode', 'HTTP/1.0 204 No Content');
                $this->set('group', null);
            } else {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('group', null);
            }
        // update
        } else if ($this->RequestHandler->isPut()) {
            $edit = trim(file_get_contents('php://input'), true);
            if ($this->Group->save(json_decode($edit, true))) {
                $group = $this->Group->read('id');
                $groupId = $group['Group']['id'];
                $this->set('statusCode', 'HTTP/1.0 200 OK');
                $this->set('group', array('id' => $groupId));
            } else {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('group', null);
            }
        } else {
            $this->set('statusCode', 'HTTP/1.0 400 Bad Request');
            $this->set('group', null);
        }
        
    }

    /**
     * Get a list of events in iPeer.
     **/
    public function events() {
        $course_id = $this->params['course_id'];
        $event_id = $this->params['event_id'];

        if ($this->RequestHandler->isGet()) {
            if (null == $event_id) {
                $list = $this->Event->find('all', array('fields' => array('title', 'course_id', 'event_template_type_id')));

                if (!empty($list)) {
                    foreach ($list as $data) {
                        $results[] = $data['Event'];
                    }
                    $statusCode = 'HTTP/1.0 200 OK';
                } else {
                    $results = null;
                    $statusCode = 'HTTP/1.0 404 Not Found';
                }
            } else {
                $list = $this->Event->find('first',
                    array('fields' => array('title', 'course_id', 'event_template_type_id'),
                        'conditions' => array('Event.id' => $event_id))
                );
                
                if (!empty($list)) {
                    $results = $list['Event'];
                    $statusCode = 'HTTP/1.0 200 OK';
                } else {
                    $results = null;
                    $statusCode = 'HTTP/1.0 404 Not Found';
                }
            }
            $this->set('statusCode', $statusCode);
            $this->set('events', $results);
        } else {
            $this->set('statusCode', 'HTTP/1.0 400 Bad Request');
            $this->set('events', null);
        }
    }
    
    /**
     * Get a list of grades in iPeer.
     **/
    public function grades() {
        $event_id = $this->params['event_id'];
        $user_id = $this->params['user_id'];

        if ($this->RequestHandler->isGet()) {
            $eventType = $this->Event->getEventTemplateTypeId($event_id);
            if (null == $user_id) {
                if (1 == $eventType) {
                    $list = $this->EvaluationSimple->find('all',
                        array('fields' => array('evaluatee', 'score'),
                            'conditions' => array('event_id' => $event_id)
                        )
                    );
                    
                    if (!empty($list)) {
                        foreach ($list as $data) {
                            $results[] = $data['EvaluationSimple'];
                        }
                        $statusCode = 'HTTP/1.0 200 OK';
                    } else {
                        $results = null;
                        $statusCode = 'HTTP/1.0 404 Not Found';
                    }
                } else if (2 == $eventType) {
                    $list = $this->EvaluationRubric->find('all',
                        array('fields' => array('evaluatee', 'score'),
                            'conditions' => array('event_id' => $event_id)
                        )
                    );
                    
                    if (!empty($list)) {
                        foreach ($list as $data) {
                            $results[] = $data['EvaluationRubric'];
                        }
                        $statusCode = 'HTTP/1.0 200 OK';
                    } else {
                        $results = null;
                        $statusCode = 'HTTP/1.0 404 Not Found';
                    }
                } else if (4 == $eventType) {
                    $list = $this->EvaluationMixeval->find('all',
                        array('fields' => array('evaluatee', 'score'),
                            'conditions' => array('event_id' => $event_id)
                        )
                    );
                    if (!empty($list)) {
                        foreach ($list as $data) {
                            $results[] = $data['EvaluationMixeval'];
                        }
                        $statusCode = 'HTTP/1.0 200 OK';
                    } else {
                        $results = null;
                        $statusCode = 'HTTP/1.0 404 Not Found';
                    }
                }
            } else {
                if (1 == $eventType) {
                    $list = $this->EvaluationSimple->find('first',
                        array('fields' => array('evaluatee', 'score'),
                            'conditions' => array('event_id' => $event_id, 'evaluatee' => $user_id)
                        )
                    );
                    
                    if (!empty($list)) {
                        $results = $list['EvaluationSimple'];
                        $statusCode = 'HTTP/1.0 200 OK';
                    } else {
                        $results = null;
                        $statusCode = 'HTTP/1.0 404 Not Found';
                    }
                } else if (2 == $eventType) {
                    $list = $this->EvaluationRubric->find('first',
                        array('fields' => array('evaluatee', 'score'),
                            'conditions' => array('event_id' => $event_id, 'evaluatee' => $user_id)
                        )
                    );
                    
                    if (!empty($list)) {
                        $results = $list['EvaluationRubric'];
                        $statusCode = 'HTTP/1.0 200 OK';
                    } else {
                        $results = null;
                        $statusCode = 'HTTP/1.0 404 Not Found';
                    }
                } else if (4 == $eventType) {
                    $list = $this->EvaluationMixeval->find('first',
                        array('fields' => array('evaluatee', 'score'),
                            'conditions' => array('event_id' => $event_id, 'evaluatee' => $user_id)
                        )
                    );
                    
                    if (!empty($list)) {
                        $results = $list['EvaluationMixeval'];
                        $statusCode = 'HTTP/1.0 200 OK';
                    } else {
                        $results = null;
                        $statusCode = 'HTTP/1.0 404 Not Found';
                    }
                }
            }
            $this->set('statusCode', $statusCode);
            $this->set('grades', $results);
        } else {
            $this->set('statusCode', 'HTTP/1.0 400 Bad Request');
            $this->set('grades', null);
        }
    }

}
