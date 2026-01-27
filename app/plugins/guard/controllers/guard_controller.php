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

        // this redirect only happens when autoRedirect is set to false
        // so that application can do some stuff after user login
        if( !(empty($this->data)) && $this->Auth->user() ){
            $this->redirect('/login');
        }
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
