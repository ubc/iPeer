<?php
/**
 * SAML Assertion Consumer Service Controller
 *
 * Handles SAML responses from the IdP using the SamlComponent
 * for secure processing and validation.
 */

class HomeUBCSamlController extends AppController
{
    public $uses = array('User');
    public $components = array('Saml');

    /**
     * Index - ACS endpoint
     *
     * Receives and processes SAML responses from the IdP.
     */
    function index()
    {
        $samlResponse = $_POST['SAMLResponse'] ?? null;

        if (empty($samlResponse)) {
            CakeLog::write('error', 'SAML ACS: No SAMLResponse in POST data');
            $this->redirect('/login?defaultlogin=true');
            return;
        }

        // Process the SAML response using the component
        $result = $this->Saml->processResponse($samlResponse);

        if (!$result) {
            CakeLog::write('error', 'SAML ACS: Response processing failed');
            $this->redirect('/login?defaultlogin=true');
            return;
        }

        $username = $result['username'];
        $attributes = $result['attributes'];

        // Look up user in database
        $userId = $this->User->field('id', array('username' => $username));

        if (!$userId) {
            CakeLog::write('info', "SAML ACS: No user found for username: $username");
            $this->_afterLogout();
            $this->redirect('/public/saml/logout.php');
            return;
        }

        // Log the user in
        if (!$this->Auth->login($userId)) {
            CakeLog::write('error', "SAML ACS: Auth->login() failed for user ID: $userId");
            $this->redirect('/login?defaultlogin=true');
            return;
        }

        CakeLog::write('info', "SAML ACS: User $username (ID: $userId) logged in successfully");
        $this->_afterLogin();
    }
}
