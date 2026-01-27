<?php
require_once('XML/RPC.php');

/**
 * UBC Campus Wide Login authentication module.
 *
 * @uses AuthModule
 * @package Plugins.Guard
 * @version $id$
 * @copyright Copyright (C) 2010 CTLT
 * @author Compass
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class CwlModule extends AuthModule {

    /**
     * name the name of the authentication module
     *
     * @var string
     * @access public
     */
    var $name = 'Cwl';

    /**
     * hasLoginForm this module uses external login page
     *
     * @var mixed
     * @access public
     */
    var $hasLoginForm = false;

    /**
     * sessionHeaders the headers to check against to see if there is an active
     * shibboleth session
     *
     * @var string
     * @access public
     */
    var $sessionHeaders = array('Shib-Session-ID', 'HTTP_SHIB_IDENTITY_PROVIDER');

    /**
     * hasLoginData test if it got login data from SP (if there is a active
     * session)
     *
     * @access public
     * @return boolean true, if it got login data. false, if not
     */
    function hasLoginData() {
        return isset($_GET['ticket']);
    }

    /**
     * Check if a Shibboleth session is active.
     *
     * @access public
     * @return boolean if session is active
     */
    function isSessionActive() {
        $active = false;

        foreach ($this->sessionHeaders as $header) {
            if ( array_key_exists($header, $_SERVER) && !empty($_SERVER[$header]) ) {
                $active = true;
                break;
            }
        }
        return $active;
    }

    /**
     * Generate the URL to initiate CWL login.
     *
     * @param string $redirect the final URL to redirect the user to after all login is complete
     * @return the URL to direct the user to in order to initiate Shibboleth login
     */
    function sessionInitiatorUrl($redirect = null) {
        $initiator_url = self::urlNormalize($this->sessionInitiatorURL) .
            '?serviceName=' . $this->applicationID.
            '&serviceURL=' . Router::url(array('plugin' => 'guard', 'controller' => 'guard', 'action' => 'login'), true);

        return $initiator_url;
    }

    /**
     * authenticate authenticate the user and generate the user session
     *
     * @param mixed $username
     * @access public
     * @return void
     */
    function authenticate($username = null) {
        $this->_mapFields();

        $ticket = $_GET['ticket'];
        $username = '';

        // the parameters passed to the RPC interface.  the ticket is the
        // first parameter for all functions
        // we try PUID first
        $puid = $this->sendRPCRequest('getPuid', array($ticket));

        if ($this->identify($puid)) {
            $username = $puid;
        } else {
            // try employee number or student number
            $identities = $this->sendRPCRequest($this->functionName, array($ticket));
            if (!empty($identities['employee_number']) && $this->identify($identities['employee_number'])) {
                $username = $identities['employee_number'];
            } elseif (!empty($identities['student_number']) && $this->identify($identities['student_number'])) {
                $username = $identities['student_number'];
            } elseif (!empty($identities['guest_id']) && $this->identify($identities['guest_id'])) {
                $username = $identities['guest_id'];
            } else {
                CakeLog::write('error', 'No student number or guest id found.');
                return false;
            }
        }

        $this->data[$this->guard->fields['username']] = $username;

        return true;
    }

    /**
     * getLoginUrl return the shibboleth login URL
     *
     * @access public
     * @return string the shibboleth login URL
     */
    function getLoginUrl() {
        return $this->sessionInitiatorUrl();
    }

    /**
     * logout logout shibboleth session. User will be redirected to shibboleth
     * logout URL after the internal logout. Then redirected to the final logout
     * page.
     *
     * @access public
     * @return void
     */
    /*function logout() {
        if ( $this->isSessionActive() ) {
            $this->guard->logoutRedirect = self::urlNormalize($this->logoutURL) .
                '?return=' . Router::url($this->guard->logoutRedirect, true);
        }
    }*/

    function sendRPCRequest($method, $params) {
        $rpcParams = array();
        foreach($params as $param) {
            $rpcParams[] = new XML_RPC_Value($param, 'string');
        }

        // note that the function name is prepended with the string 'session.'
        $msg = new XML_RPC_Message("session.$method", $rpcParams);

        $cli = new XML_RPC_Client($this->RPCPath, $this->RPCURL);
        $cli->setCredentials($this->applicationID, $this->applicationPassword);

        $resp = $cli->send($msg);
        if (!$resp) {
            CakeLog::write('error', 'Communication error: ' . $cli->errstr);
            return false;
        }

        if ($resp->faultCode()) {
            // error
            CakeLog::write('error', 'Fault Code: ' . $resp->faultCode() . "," . 'Fault Reason: ' . $resp->faultString());
            return false;
        }

        // an encoded response value
        $val = $resp->value();
        //CakeLog::write('debug', print_r($val, true));

        // the actual data we requested
        $data = XML_RPC_decode($val);
        //CakeLog::write('debug', print_r($data, true));

        return $data;
    }
}
