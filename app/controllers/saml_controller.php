<?php

require_once 'vendor/autoload.php';

use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Settings;
use caliper\CaliperHooks;

class SamlController extends AppController {
    public $uses = array('User');
    public $components = array('Saml', 'Session');
    private $loginActions = array('acs', 'auth');

    function beforeFilter()
    {
        $this->autoRender = false;

        if ($this->Auth->user() && in_array($this->params['action'], $this->loginActions)) {
            // user is already logged in, so redirect to home page
            $this->redirect('/');
            return;
        }

        $this->Auth->allow('acs', 'auth', 'logout', 'metadata');

        if ($this->params['action'] === 'acs') {
            // acs is the only endpoint that requires our special AppController setup
            parent::beforeFilter();
        } else {
            // all other endpoints don't have dependencies on our AppController
            // in this case, we only do logging that AppController::beforeFilter normally does
            AppController::logControllerAction($this);
        }
    }

    private function getSamlSettings() {
        $samlSettings = getenv('SAML_SETTINGS');
        if (!empty($samlSettings)) {
            return json_decode($samlSettings, true);
        }
        return array();
    }

    private function getSamlLogoutUrl() {
        return getenv('SAML_LOGOUT_URL') ?: '/';
    }

    public function acs() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
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

    public function auth() {
        $auth = new Auth($this->getSamlSettings());
        $auth->login();
    }

    public function logout() {
        $auth = new Auth($this->getSamlSettings());

        CaliperHooks::app_controller_after_logout($this);
        $this->Session->destroy();

        if (isset($_COOKIE['SAMLRequest'])) {
            setcookie('SAMLRequest', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['SAMLResponse'])) {
            setcookie('SAMLResponse', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['IPEER'])) {
            setcookie('IPEER', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 3600, '/');
        }

        if ($auth->isAuthenticated()) {
            $auth->logout();
            return;
        }

        header("Location: " . $this->getSamlLogoutUrl());
        exit;
    }

    public function metadata() {
        if (!getenv('ENABLE_SAML_METADATA')) {
            // Due to a limitation in the `onelogin/php-saml` library,
            // we don't expose this by default in case we configured
            // our IdP to use separate private keys for signing requests
            // and response decryption. In that case, we need to manually
            // prep the metadata file.
            http_response_code(403);
            return;
        }

        $settings = new Settings($this->getSamlSettings());
        header('Content-Type: application/xml');
        echo $settings->getSPMetadata();
    }
}
