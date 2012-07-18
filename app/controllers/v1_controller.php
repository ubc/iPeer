<?php
class V1Controller extends Controller {
    public $uses = array('User');
    public $layout = "blank_layout";

    /**
     * Get a list of users in iPeer.
     * */
    public function users($id = null) {
        $people = array();
        
        // all users
        if (null == $id) {
            $users = $this->User->find('all', 
                array(
                    'fields' => array('id', 'username', 'last_name', 'first_name'),
                    'recursive' => 0
                )
            );
    
            foreach ($users as $user) {
                $people[] = $user['User'];
            }
        // specific user
        } else {
            $user = $this->User->find('first', 
                array(
                    'conditions' => array('id' => $id),
                    'fields' => array('id', 'username', 'last_name', 'first_name'),
                    'recursive' => 0
                )
            );
            $people = $user['User'];
        }

        $this->set('users', $people);
    }
}
