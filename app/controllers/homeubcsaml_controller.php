<?php

class HomeUBCSamlController extends AppController
{
    public $uses = array('User');
    public $components = array('Saml');

    function beforeFilter()
    {
        $this->Auth->allow('index');
        parent::beforeFilter();
    }

    /**
     * Assertion Consumer Service (ACS) endpoint.
     * Receives and processes SAMLResponse POST from the IdP.
     */
    function index()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            $this->autoRender = false;
            return;
        }

        $result = $this->Saml->processResponse();

        if (!$result) {
            $this->_afterLogout();
            $this->redirect('/login');
            return;
        }

        $username = $result['username'];
        $userId = $this->User->field('id', array('username' => $username));

        if (!$userId) {
            CakeLog::write('info', "SAML ACS: No iPeer account found for username: $username");
            $this->_afterLogout();
            $this->redirect('/login?notice=no_account');
            return;
        }

        if (!$this->Auth->login($userId)) {
            CakeLog::write('error', "SAML ACS: Auth->login() failed for user ID: $userId");
            $this->_afterLogout();
            $this->redirect('/login');
            return;
        }

        CakeLog::write('info', "SAML ACS: User $username (ID: $userId) logged in successfully");
        $this->_afterLogin();
    }
}
