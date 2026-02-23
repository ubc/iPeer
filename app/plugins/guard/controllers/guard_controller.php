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
        $this->set('title_for_layout', 'Log In');
        $this->set('login_url', $this->Auth->getLoginUrl());
        $this->set('is_logged_in', $this->Auth->isLoggedIn());
        $this->set('params', $this->Auth->getParameters());

        // check if the auth module needs a login form or just a button
        $this->set('auth_module_name', $this->Auth->getAuthModuleName());

        if ($this->Auth->isLoggedIn()) {
            $this->redirect('/');
            return;
        }

        // this redirect only happens when autoRedirect is set to false
        // so that application can do some stuff after user login
        if( !(empty($this->data)) && $this->Auth->user() ){
            $this->redirect('/login');
        }

        $allowed_notices = array('inactive', 'no_enrollment', 'no_account');
        $notice = $this->params['url']['notice'] ?? false;
        $this->set('notice', in_array($notice, $allowed_notices, true) ? $notice : null);

        $forceGuardAuth = ($_GET['defaultlogin'] ?? '') === "true";
        if ($forceGuardAuth) {
            // continue loading/rendering Guard plugin and views as normal
            // (mostly meant for fallback password-based auth)
            return;
        }

        if (getenv('SAML_SETTINGS')) {
            $this->set('saml_logout_notice', ClassRegistry::init('SysParameter')->get(
                'display.saml_logout_notice',
                __('You are still logged in with your institution. If you wish, you can log out everywhere by clicking the button below.', true)
            ));
            $this->render('/guard/saml_login');
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
