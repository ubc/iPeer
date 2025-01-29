<?php

class GuardController extends AppController {
    var $name = "Guard";
    var $uses = array();

    /**
     * login login action
     *
     * @access public
     * @return void
     */
    function login() {
        $this->set('login_url', $this->Auth->getLoginUrl());
        $this->set('is_logged_in', $this->Auth->isLoggedIn());
        $this->set('params', $this->Auth->getParameters());

        // check if the auth module needs a login form or just a button
        $this->set('auth_module_name', $this->Auth->getAuthModuleName());


        // Get all object properties as an associative array
        $properties = get_object_vars($this);
        $this->log('MMMMMMMMMMMMMMMM11111:'.json_encode($properties));

        // this redirect only happens when autoRedirect is set to false
        // so that application can do some stuff after user login
        if( !(empty($this->data)) && $this->Auth->user() ){
            //$this->redirect('/login');
            $this->redirect('https://ubc.ca');
        }
    }

    function loginUBCCWL() {

        $this->redirect('https://ubc-ubccwl.ca');
    }
    /**
     * logout logout action
     *
     * @access public
     * @return void
     */
    function logout() {
        $this->redirect($this->Auth->logout());
    }
}
