<?php
class V1Controller extends Controller {
    public $uses = array('User');
    public $components = array('RequestHandler');
    public $layout = "blank_layout";

    /**
     * Get a list of users in iPeer.
     * */
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
}
