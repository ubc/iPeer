<?php

require_once 'vendor/autoload.php';

use OneLogin\Saml2\Auth;

class SamlController extends AppController
{
    const USERNAME_OID = 'urn:oid:1.3.6.1.4.1.60.6.1.6';

    public $uses = array('User');

    /**
     * @var OneLogin\Saml2\Auth
     */
    private $samlAuth;

    function beforeFilter()
    {
        $this->Auth->allow('singleSignOn', 'assertionConsumerService', 'singleLogout', 'metadata');
        $this->autoRender = false;
        parent::beforeFilter();

        // For ACS endpoint, use the separate key to decrypt encrypted assertions due to the
        // configuration we set up with IdP administrators (see function docblock for more context).
        $useSeparateDecryptionKey = ($this->params['action'] === 'assertionConsumerService');
        if (!($this->samlAuth = $this->createSamlAuth($useSeparateDecryptionKey))) {
            $this->setErrorResponse(500);
            exit;
        }
    }

    public function singleSignOn()
    {
        if ($this->samlAuth->isAuthenticated()) {
            $this->redirect('/dashboard');
            return;
        }

        $this->samlAuth->login();
    }

    public function assertionConsumerService()
    {
        try {
            $this->samlAuth->processResponse();
        } catch (Throwable $e) {
            $this->log('SAML response error: ' . $e->getMessage(), 'error');
            $this->setErrorResponse(401);
            return;
        }

        $errors = $this->samlAuth->getErrors();
        if (!empty($errors)) {
            $this->log('SAML response errors: ' . implode(', ', $errors), 'error');
            $reason = $this->samlAuth->getLastErrorReason();
            if ($reason) {
                $this->log('SAML error reason: ' . $reason, 'error');
            }
            $this->setErrorResponse(401);
            return;
        }

        if (!$this->samlAuth->isAuthenticated()) {
            $this->log('SAML response processed but user is not authenticated', 'error');
            $this->setErrorResponse(401);
            return;
        }

        $attributes = $this->samlAuth->getAttributes();
        $username = $attributes[self::USERNAME_OID][0] ?? null;

        $userId = $username ? $this->User->field('id', array('username' => $username)) : null;

        if (!$userId) {
            $this->log("No user found for $username after valid SAML login", 'info');
            $this->_afterLogout();
            $this->redirect('/public/saml/logout.php');
            return;
        }

        $this->Auth->login($userId);
        $this->_afterLogin();
    }

    public function singleLogout()
    {
        $this->_afterLogout();

        if (isset($_COOKIE['IPEER'])) {
            setcookie('IPEER', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 3600, '/');
        }

        if ($this->samlAuth->isAuthenticated()) {
            try {
                $this->samlAuth->logout();
            } catch (Throwable $e) {
                $this->log('SAML logout failed (but session was destroyed): ' . $e->getMessage(), 'error');
            }
            return;
        }

        $this->redirect($this->getSamlLogoutUrl());
    }

    /**
     * This endpoint should normally be exposed, but we've been sending customized metadata
     * configuration to our IdP administrators that does not align with how OneLogin\Saml2
     * generates it. So we hide this endpoint by default to prevent confusion (or possible
     * invalid auto-updates of IdP configuration from scraping this endpoint).
     *
     * If we can find a way to reconfigure the library to output metadata with separate
     * keys for signing and encryption, we could re-enable this endpoint by default.
     *
     * @see createSamlAuth for more background
     */
    public function metadata()
    {
        if (!getenv('ENABLE_SAML_METADATA')) {
            $this->setErrorResponse(403);
            return;
        }

        header('Content-Type: application/xml');
        echo $this->samlAuth->getSettings()->getSPMetadata();
    }

    /**
     * Creates a OneLogin SAML2 Auth instance with optional private key override.
     *
     * In a SAML setup, it is possible for the Service Provider (SP) to have two key pairs:
     * 1. **Signing key pair**: SP signs outgoing AuthnRequests so the IdP can verify authenticity
     *    - This is advertised in the metadata as `<KeyDescriptor use="signing">`
     * 2. **Decryption key pair**: IdP encrypts assertions; SP decrypts them with the private key
     *    - This is advertised in the metadata as `<KeyDescriptor use="encryption">`
     *
     * OneLogin\Saml2 only cleanly supports using a single key for both cases.
     *
     * In the past, iPeer was using two separate keys, and had a hand-rolled ACS endpoint,
     * which avoided this library limitation. However, when we wanted to consolidate the SAML
     * code into a single place, plus drop the custom ACS implementation in favour of a library,
     * we added this `$useSeparateKeyForDecryption` workaround.
     *
     * In the future, we could stop using 2 separate keys. Then we could drop this workaround
     * and start publishing the metadata endpoint as normal.
     *
     * @param bool $useSeparateKeyForDecryption If true, override `sp.privateKey` with the separate
     *                                          `sp.privateKeyForDecryption`, if present.
     *                                          Normally set to true for ACS endpoint only.
     *
     * @return Auth|null Returns configured Auth instance, or null on failure
     */
    private function createSamlAuth($useSeparateKeyForDecryption = false)
    {
        $samlSettings = getenv('SAML_SETTINGS');
        if (empty($samlSettings)) {
            $this->log('SAML_SETTINGS env var is empty or not set', 'critical');
            return null;
        }

        try {
            $settings = json_decode($samlSettings, true);
            if ($useSeparateKeyForDecryption && isset($settings['sp']['privateKeyForDecryption'])) {
                $settings['sp']['privateKey'] = $settings['sp']['privateKeyForDecryption'];
            }
            return new Auth($settings);
        } catch (Throwable $e) {
            $this->log('Failed to initialize SAML Auth: ' . $e->getMessage(), 'critical');
            return null;
        }
    }

    private function getSamlLogoutUrl()
    {
        $url = getenv('SAML_LOGOUT_URL');
        if (empty($url)) {
            $this->log('SAML_LOGOUT_URL env var is not set, falling back to "/"', 'warning');
            return '/';
        }
        return $url;
    }

    private function setErrorResponse($httpCode)
    {
        if (headers_sent()) {
            return;
        }

        http_response_code($httpCode);
        $supportEmail = getenv('IPEER_SUPPORT_EMAIL');
        $message = 'We are unable to sign you in.';
        if (!empty($supportEmail)) {
            $message .= " Please contact $supportEmail for help.";
        }
        echo $message;
    }
}
