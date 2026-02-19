<?php

require_once 'vendor/autoload.php';

use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Settings;
use caliper\CaliperHooks;

class SamlController extends Controller {
    var $uses = array();
    var $components = array('Session');

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

    public function auth() {
        $this->autoRender = false;

        $auth = new Auth($this->getSamlSettings());

        if ($auth->isAuthenticated()) {
            $this->redirect('/dashboard');
            return;
        }

        $auth->login();
    }

    public function logout() {
        $this->autoRender = false;

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
        $this->autoRender = false;
        $settings = new Settings($this->getSamlSettings());
        header('Content-Type: application/xml');
        echo $settings->getSPMetadata();
    }
}
