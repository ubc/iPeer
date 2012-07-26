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

    public $uses = array('User', 'Group', 'Course', 'Event', 'EvaluationSimple', 'EvaluationRubric', 'EvaluationMixeval');
    public $components = array('RequestHandler');
    public $layout = "blank_layout";

    /**
     * Get a list of users in iPeer.
     *
     * @param mixed $id
     *
     **/
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
                    $data = 'No users can be found';
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
                    $data = 'No user with id '.$id.' can be found';
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
                $this->set('user', 'Error: the user was not added');
            }
        // delete
        } else if ($this->RequestHandler->isDelete()) {
            if ($this->User->delete($id)) {
                $this->set('statusCode', 'HTTP/1.0 204 No Content');
                $this->set('user', null);
            } else {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('user', 'Error: the user was not delete');
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
                $this->set('user', 'Error: the user was not updated');
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
     *
     **/
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
                    $classes = 'No courses can be found';
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
                    $classes = 'No course with id '.$id.' can be found';
                    $statusCode = 'HTTP/1.0 404 Not Found';
                }
            }
            $this->set('courses', $classes);
            $this->set('statusCode', $statusCode);
        } else if ($this->RequestHandler->isPost()) {
            $create = trim(file_get_contents('php://input'), true);
            if (!$this->Course->save(json_decode($create, true))) {
                $message = 'Error: the course was not added';
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('courses', $message);
            } else {
                $temp = $this->Course->read();
                $courseId = array('id' => $temp['Course']['id']);
                $this->set('statusCode', 'HTTP/1.0 201 Created');
                $this->set('courses', $courseId);
            }
        } else if ($this->RequestHandler->isPut()) {   
            $update = trim(file_get_contents('php://input'), true);
            if (!$this->Course->save(json_decode($update, true))) {
                $message = 'Error: the course was not edited';
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('courses', $message);
            } else {
                $temp = $this->Course->read();
                $courseId = array('id' => $temp['Course']['id']);
                $this->set('statusCode', 'HTTP/1.0 200 OK');
                $this->set('courses', $courseId);
            }
        } else if ($this->RequestHandler->isDelete()) {
            if (!$this->Course->delete($id)) {
                $message = 'Error: the course was not deleted';
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('courses', $message);
            } else {
                $temp = $this->Course->read();
                $courseId = $temp['Course']['id'];
                $this->set('statusCode', 'HTTP/1.0 204 No Content');
                $this->set('courses', $courseId);
            }
        } else {
            $this->set('statusCode', 'HTTP/1.0 400 Bad Request');
            $this->set('courses', null);
        }
    }
    
    /**
     * Get a list of groups in iPeer.
     **/
    public function groups () {
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
                    $data = 'No groups are found in the course with the id '.$this->params['course_id'];
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
                    $data = 'No group with id '.$this->params['group_id'].' can be found';
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
                $this->set('group', 'Error: The group was not added');
            }
        // delete
        } else if ($this->RequestHandler->isDelete()) {
            if ($this->Group->delete($this->params['group_id'])) {
                $this->set('statusCode', 'HTTP/1.0 204 No Content');
                $this->set('group', null);
            } else {
                $this->set('statusCode', 'HTTP/1.0 500 Internal Server Error');
                $this->set('group', 'Error: The group was not delete');
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
                $this->set('group', 'Error: The group was not edited');
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
                    $results = 'It appears that there are no events in course with id '.$course_id;
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
                    $results = 'This event with id'.$event_id.'doesn\'t seem to exist';
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
        $course_id = $this->params['course_id'];
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
                        $results = 'No grades for an event with id '.$event_id.' can be found';
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
                        $results = 'No grades for an event with id '.$event_id.' can be found';
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
                        $results = 'No grades for an event with id '.$event_id.' can be found';
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
                        $results = 'No grades can be found for user with id '.$user_id;
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
                        $results = 'No grades can be found for user with id '.$user_id;
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
                        $results = 'No grades can be found for user with id '.$user_id;
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
