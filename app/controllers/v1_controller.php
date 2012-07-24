<?php
class V1Controller extends Controller {

    public $uses = array('User', 'Course', 'Event', 'EvaluationSimple', 'EvaluationRubric', 'EvaluationMixeval');
    public $components = array('RequestHandler');
    public $layout = "blank_layout";

    /**
     * Get a list of users in iPeer.
     **/
    public function users($id = null) {
        // view
        if ($this->RequestHandler->isGet()) {
            $data = array();
            // all users
            if (null == $id) {
                $users = $this->User->find('all');
                foreach ($users as $user) {
                    $tmp = array();
                    $tmp['id'] = $user['User']['id'];
                    $tmp['role_id'] = $user['Role']['0']['id'];
                    $tmp['username'] = $user['User']['username'];
                    $tmp['last_name'] = $user['User']['last_name'];
                    $tmp['first_name'] = $user['User']['first_name'];
                    $data[] = $tmp;
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
                } else {
                    $data = null;
                }
            }
            $this->set('user', $data);
        // add
        } else if ($this->RequestHandler->isPost()) {
            $input = trim(file_get_contents('php://input'), true);
            if ($this->User->save(json_decode($input, true))) {
                $user = $this->User->read('id');
                $userId = array('id' => $user['User']['id']);
                $this->set('user', $userId);
            } else {
                $this->set('user', 'Error: the user was not saved');
            }
        // delete
        } else if ($this->RequestHandler->isDelete()) {
            if ($this->User->delete($id)) {
                $this->set('user', null);
            } else {
                $this->set('user', 'Error: the user was not delete');
            }
        // update
        } else if ($this->RequestHandler->isPut()) {    
            if ($this->User->save(json_decode($edit, true))) {
                $user = $this->User->read('id');
                $userId = array('id' => $user['User']['id']);
                $this->set('user', $userId);
            } else {
                $this->set('user', 'Error: the user was not updated');
            }
        }
    }

    /**
     * Get a list of courses in iPeer.
     **/
    public function courses($id = null) {
        $classes = array();

        if ($this->RequestHandler->isGet()) {
            if (null == $id) {
                $courses = $this->Course->find('all',
                    array('fields' => array('id', 'course', 'title'))
                );
                foreach ($courses as $course) {
                    $classes[] = $course['Course'];
                }
            } else {
            // specific course
                $course = $this->Course->find('first', 
                    array(
                        'conditions' => array('id' => $id),
                        'fields' => array('id', 'course', 'title'),
                    )
                );
                $classes = $course['Course'];
            }
            $this->set('courses', $classes);
        } else {
            if ($this->RequestHandler->isPost()) {
                $create = trim(file_get_contents('php://input'), true);
                if (!$this->Course->save(json_decode($create ,true))) {
                    $temp = 'Error';
                    $this->set('courses', $temp);
                } else {
                    $temp = $this->Course->read();
                    $temp = $temp['Course']['id'];
                    $this->set('courses', $temp);
                }
            } else {
                if ($this->RequestHandler->isPut()) {    
                    $update = trim(file_get_contents('php://input'), true);
                    if (!$this->Course->save(json_decode($update ,true))) {
                        $temp = 'Error';
                        $this->set('courses', $temp);
                    } else {
                        $temp = $this->Course->read();
                        $temp = $temp['Course']['id'];
                        $this->set('courses', $temp);
                    }
                } else {
                    if ($this->RequestHandler->isDelete()) {
                        if(!$this->Course->delete($id)) {
                            $temp = 'Error';
                            $this->set('courses', $temp);
                        } else {
                            $temp = $this->Course->read();
                            $temp = $temp['Course']['id'];
                            $this->set('courses', $temp);
                        }
                    } else {
                        debug('unknown request method');
                    }
                }
            }
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
                
                foreach ($groups as $group) {
                    $data[] = $group['Group'];
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
                
                $data = $group['Group'];
            }
            $this->set('group', $data);
        // add
        } else if ($this->RequestHandler->isPost()) {
            $add = trim(file_get_contents('php://input'), true);
            if ($this->Group->save(json_decode($add, true))) {
                $group = $this->Group->read('id');
                $groupId = $group['Group']['id'];
                $this->set('group', array('id' => $groupId));
            } else {
                $this->set('group', 'Error: The group was not added');
            }
        // delete
        } else if ($this->RequestHandler->isDelete()) {
            if ($this->Group->delete($this->params['group_id'])) {
                $this->set('group', null);
            } else {
                $this->set('group', 'Error: The group was not delete');
            }
        // update
        } else if ($this->RequestHandler->isPut()) {
            $edit = trim(file_get_contents('php://input'), true);
            if ($this->Group->save(json_decode($edit, true))) {
                $group = $this->Group->read('id');
                $groupId = $group['Group']['id'];
                $this->set('group', array('id' => $groupId));
            } else {
                $this->set('group', 'Error: The group was not edited');
            }
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
                    foreach ($list as $data) {
                        $results[] = $data['EvaluationSimple'];
                    }
                } else {
                    if (2 == $eventType) {
                        $list = $this->EvaluationRubric->find('all',
                            array('fields' => array('evaluatee', 'score'),
                                'conditions' => array('event_id' => $event_id)
                            )
                        );
                        foreach ($list as $data) {
                            $results[] = $data['EvaluationRubric'];
                        }
                    } else {
                        if (4 == $eventType) {
                            $list = $this->EvaluationMixeval->find('all',
                                array('fields' => array('evaluatee', 'score'),
                                    'conditions' => array('event_id' => $event_id)
                                )
                            );
                            foreach ($list as $data) {
                                $results[] = $data['EvaluationMixeval'];
                            }
                        }
                    }
                }
            } else {
                if (1 == $eventType) {
                    $list = $this->EvaluationSimple->find('first',
                        array('fields' => array('evaluatee', 'score'),
                            'conditions' => array('event_id' => $event_id, 'evaluatee' => $user_id)
                        )
                    );
                    $results = $data['EvaluationSimple'];
                } else {
                    if (2 == $eventType) {
                        $list = $this->EvaluationRubric->find('first',
                            array('fields' => array('evaluatee', 'score'),
                                'conditions' => array('event_id' => $event_id, 'evaluatee' => $user_id)
                            )
                        );
                        $results = $data['EvaluationRubric'];
                    } else {
                        if (4 == $eventType) {
                            $list = $this->EvaluationMixeval->find('first',
                                array('fields' => array('evaluatee', 'score'),
                                    'conditions' => array('event_id' => $event_id, 'evaluatee' => $user_id)
                                )
                            );
                            $results = $list['EvaluationMixeval'];
                        }
                    }
                }
            }
            $this->set('grades', $results);
        } else {
            debug('Error: not working as intended');
        }
    }
    
}
