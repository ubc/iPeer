<?php

require_once 'vendor/autoload.php';

use OneLogin\Saml2\Auth;

class HomeUBCSamlController extends AppController
{
    public $uses = array('User');

    function beforeFilter()
    {
        $this->Auth->allow('index');
        parent::beforeFilter();
    }

    function index()
    {
        $this->autoRender = false;

        $settings = $this->getSamlSettings();
        if (empty($settings)) {
            $this->log('SAML_SETTINGS env var is empty or not set', 'error');
            $this->renderError('SAML authentication is not configured.');
            return;
        }

        $auth = new Auth($settings);
        $auth->processResponse();

        $errors = $auth->getErrors();
        if (!empty($errors)) {
            $this->log('SAML response errors: ' . implode(', ', $errors), 'error');
            $reason = $auth->getLastErrorReason();
            if ($reason) {
                $this->log('SAML error reason: ' . $reason, 'error');
            }
            $this->renderError('SAML authentication failed.');
            return;
        }

        if (!$auth->isAuthenticated()) {
            $this->log('SAML response processed but user is not authenticated', 'error');
            $this->renderError('SAML authentication failed.');
            return;
        }

        $attributes = $auth->getAttributes();

        $username = $this->getSamlAttribute($attributes, 'urn:oid:1.3.6.1.4.1.60.6.1.6');

        $this->log('SAML login attempt for username: ' . $username, 'debug');

        $userId = $this->User->field('id', array('username' => $username));

        if (!$userId) {
            $this->_afterLogout();
            $this->redirect('/public/saml/logout.php');
            return;
        }

        $this->Auth->login($userId);
        $this->_afterLogin();
    }

    private function getSamlSettings()
    {
        $samlSettings = getenv('SAML_SETTINGS');
        if (!empty($samlSettings)) {
            return json_decode($samlSettings, true);
        }
        return array();
    }

    private function getSamlAttribute($attributes, $oid)
    {
        if (isset($attributes[$oid]) && !empty($attributes[$oid])) {
            return $attributes[$oid][0];
        }
        return '';
    }

    private function renderError($message)
    {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Login Error</title></head>'
            . '<body style="font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0">'
            . '<p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>'
            . '</body></html>';
    }
}
