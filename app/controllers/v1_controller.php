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
                
                $data = array(
                    'id' => $user['User']['id'],
                    'role_id' => $user['Role']['0']['id'],
                    'username' => $user['User']['username'],
                    'last_name' => $user['User']['last_name'],
                    'first_name' => $user['User']['first_name']
                );
            }
            $this->set('user', $data);
        // add
        } else if ($this->RequestHandler->isPost()) {
            $input = trim(file_get_contents('php://input'), true);
            $this->User->save(json_decode($input, true));
            $this->set('user', $this->User->read());
        // delete
        } else if ($this->RequestHandler->isDelete()) {
            $this->User->delete($id);
            $this->set('user', $this->User->read());
        // update
        } else if ($this->RequestHandler->isPut()) {    
            $edit = trim(file_get_contents('php://input'), true);
            $test = $this->User->save(json_decode($edit, true));
            $this->set('user', $this->User->read());
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
