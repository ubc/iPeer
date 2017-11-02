<?php
App::import('Model', 'SysParameter');
App::import('Model', 'UserOauth');
App::import('Vendor', 'Httpful', array('file' => 'nategood'.DS.'httpful'.DS.'bootstrap.php'));

/**
 * CanvasApiComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Clarence Ho <clarence.ho@ubc.ca>
 * @author    Aidin Niavarani <aidin.niavarani@ubc.ca>
 * @copyright 2017 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CanvasApiComponent extends Object
{
    protected $SysParameter;
    protected $userId;
    protected $provider;

    public function __construct($userId)
    {
        parent::__construct();
        $this->userId = $userId;
        $this->provider = 'canvas';
        $this->SysParameter = ClassRegistry::init('SysParameter');
        $this->UserOauth = ClassRegistry::init('UserOauth');
    }

    public function getBaseUrl()
    {
        return $this->SysParameter->get('system.canvas_baseurl');
    }

    public function getVersion()
    {
        return '/api/v1';
    }
    
    /**
      * get user access token
      *
      * @access public
      * @return mixed
      */
    public function getAccessToken()
    {
        $oauth = $this->UserOauth->getAll($this->userId, $this->provider);

        if (isset($oauth['UserOauth']['access_token'])) {
            if (isset($oauth['UserOauth']['expires'])) {
                $currentDateTime = new DateTime();
                $expiresInSeconds = strtotime($oauth['UserOauth']['expires']) - $currentDateTime->getTimeStamp();
                if ($expiresInSeconds < 300) {
                    // Refresh access token
                    $apiToken = $this->getApiTokenUsingRefreshToken($oauth['UserOauth']['refresh_token']);
                    $accessToken = $apiToken['accessToken'];
                }
                else {
                    $accessToken = $oauth['UserOauth']['access_token'];
                }
            }
            else {
                $accessToken = $oauth['UserOauth']['access_token'];
            }
            return $accessToken;
        }
        return false;
    }

    public function getApiTokenUsingRefreshToken($refreshToken = null)
    {
        if (!$refreshToken) {
            $oauth = $this->UserOauth->getAll($this->userId, $this->provider);
            $refreshToken = $oauth['UserOauth']['refresh_token'];
        }
        return $this->_getAccessTokensFromCanvas('refresh_token', $refreshToken);
    }
            
    public function getApiTokenUsingCode($code)
    {
        return $this->_getAccessTokensFromCanvas('authorization_code', $code);
    }

    public function getCanvasData($_controller, $redirect_uri, $force_auth, $uri, $params=null, $additionalHeader=null, $refreshTokenAndRetry=True)
    {   
        // first check to see if we have a valid access token
        $accessToken = $this->getAccessToken();

        // if auth token exists, attempt to call api
        if ($accessToken) {
            return $this->_getCanvasData($_controller, $redirect_uri, $accessToken, $uri, $params, $additionalHeader, $refreshTokenAndRetry);
        }

        // returning from canvas with auth code
        if (isset($_controller->params['url']['code'])){
            $apiToken = $this->getApiTokenUsingCode($_controller->params['url']['code']);
            if (isset($apiToken['accessToken'])) {
                $_controller->Session->setFlash('You have successfully connected to Canvas.', 'flash_success');
                $_controller->redirect($redirect_uri);
            }
            elseif (isset($apiToken['err'])) {
                $_controller->Session->setFlash($apiToken['err']);
            }
            else {
                $_controller->Session->setFlash('There was an error connecting to Canvas. Please try again.');
            }
        }
        // if no access token, get a new access token by forwarding the user to the canvas auth page
        elseif ($force_auth) {
            $this->_getNewOauth($_controller, $redirect_uri);
        }        
    }

    private function _getNewOauth($_controller, $redirect_uri)
    {
        $canvasOauthUrl = 'http://localhost:8900' . '/login/oauth2/auth' . 
                            '?client_id=' . $this->SysParameter->get('system.canvas_client_id') .
                            '&response_type=code' . 
                            '&state=new' . 
                            '&redirect_uri=' . array_shift(explode('?', $redirect_uri));
        $_controller->redirect($canvasOauthUrl);
    }

    private function _getAccessTokensFromCanvas($grantType, $codeOrToken)
    {
        $params = array('grant_type' => $grantType,
                        'client_id' => $this->SysParameter->get('system.canvas_client_id'),
                        'client_secret' => $this->SysParameter->get('system.canvas_client_secret'));
                        
        if ($grantType == 'refresh_token') {
            $params['refresh_token'] = $codeOrToken;
        }
        elseif ($grantType == 'authorization_code') {
            $params['code'] = $codeOrToken;
        }
        
        $request = \Httpful\Request::post($this->getBaseUrl() . "/login/oauth2/token", http_build_query($params))->expectsJson();

        $error = array();
        
        try {
            $response = $request->sendIt()->body;
            
            if (isset($response->error)) {
                switch ($response->error) {
                    case 'invalid_client':
                        $auth_error = 'The client ID for Canvas Oauth is invalid. Please contact an administrator.';
                    case 'invalid_grant':
                        $auth_error = 'This ' . $grantType . ' was not found. This is sometimes caused by refreshing the page. If you continue having this issue, please contact an administrator.';
                    default:
                        $auth_error = ucfirst($response->error_description);
                }
                $error_description = sprintf(__('Error: Authentication failed. %s', true), $auth_error);
                $error = $response->error;
            } elseif (!isset($response->access_token)) {
                $error_description = __('Error: Authentication failed. Canvas did not send back an access token, and additionally did not provide an error message, either.', true);
            } else {
                $saveData = array('access_token' => $response->access_token);

                if (isset($response->expires_in)) {
                    $saveData['expires'] = $response->expires_in;
                }

                if (isset($response->refresh_token)) {
                    $saveData['refresh_token'] = $response->refresh_token;
                }
                
                $this->UserOauth->saveTokens($this->userId, $this->provider, $saveData);

                return array('accessToken' => $response->access_token);
            }
        }
        catch (Exception $e) {
            error_log($e->getMessage());
            $error_description = sprintf(__('Error: Authentication failed. Connection error: %s', true), $e->getMessage());
        }
        
        return array('err' => $error_description, 'err_code' => $error);
    }

    private function _getCanvasData($_controller, $redirect_uri, $accessToken, $uri, $params=null, $additionalHeader=null, $refreshTokenAndRetry=True)
    {
        try {
            // For Canvas API, multiple parameters can have the same key.
            // In this case, the value of the passed in $params will be an array
            $params_expanded = '';
            if ($params) {
                foreach ($params as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $val) {
                            $params_expanded = $params_expanded . '&' . http_build_query(array($key => $val));
                        }
                    } else {
                        $params_expanded = $params_expanded . '&' . http_build_query(array($key => $value));
                    }
                }
            }
            
            $response = \Httpful\Request::get($this->getBaseUrl() .
                $this->getVersion() . $uri .
                ($params? '?' . $params_expanded : ''))
                    ->expectsJson()
                    ->addHeaders(array('Authorization' => 'Bearer ' . $accessToken))
                    ->addHeaders($additionalHeader? $additionalHeader : array())
                    ->send();

            if (isset($response->body->errors) && $refreshTokenAndRetry) {
                // most likely, the access token is no longer valid (expired), so use refresh token to get it
                $apiToken = $this->getApiTokenUsingRefreshToken();
                if (isset($apiToken['accessToken'])) {
                    return $this->_getCanvasData($_controller, $redirect_uri, $apiToken['accessToken'], $uri, $params, $additionalHeader, false);
                }
                // if not able to get new access token, we need to re-authenticate with canvas
                else {
                    $this->UserOauth->deleteToken($this->userId, $this->provider);
                    $this->_getNewOauth($_controller, $redirect_uri);
                }
            }
            return $response->body;
        } catch (Exception $e) {
            // TODO: better error handling
            error_log($e->getMessage());
        }
    }
}