<?php
class V1Controller extends Controller {
    public $uses = array('User');
    public $layout = "blank_layout";

    /**
     * Get a list of users in iPeer.
     * */
    public function users() {
        $users = $this->User->find('all', 
            array(
                'fields' => array('id', 'username', 'last_name', 'first_name'),
                'recursive' => 0
            )
        );

        $people = array();
        foreach ($users as $user) {
            $people[] = $user['User'];
        }

        $this->set('users', $people);
    }
}
