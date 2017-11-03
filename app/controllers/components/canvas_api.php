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
    const API_CALL_TIMEOUT = 10;   // RESTful call timeout in seconds
    
    protected $SysParameter;
    protected $userId;
    protected $provider;
    protected $apiPath;

    /**
     * overriden constructor to initialize private variables
     *
     * @param integer $userId required for most nonstatic functionalities
     * 
     * @access public
     */
    public function __construct($userId)
    {
        parent::__construct();
        $this->userId = $userId;
        $this->provider = 'canvas';
        $this->apiPath = '/api/v1';
        $this->SysParameter = ClassRegistry::init('SysParameter');
        $this->UserOauth = ClassRegistry::init('UserOauth');
    }

    /**
     * gets the base URL for canvas (without appending slash)
     *
     * @param boolean $ext pass true if making a frontend request to canvas
     * 
     * @access public
     * @return void
     * 
     */
    public function getBaseUrl($ext=false)
    {
        if ($ext) {
            $extBaseUrl = $this->SysParameter->get('system.canvas_baseurl_ext');
            if ($extBaseUrl) {
                return $extBaseUrl;
            }
        }
        
        return $this->SysParameter->get('system.canvas_baseurl');
    }

    /**
     * get full URL to api (without appending slash). Append API endpoint URI to make a request
     *
     * @access public
     * @return void
     */
    public function getApiUrl()
    {
        return $this->getBaseUrl() . $this->apiPath;
    }
    
    /**
      * get user access token
      *
      * @access public
      * @return mixed return access token if successful, boolean false otherwise
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

    /**
     * get access token and other information using a refresh token. if successful, also saves this information to the database
     *
     * @param string $refreshToken if not provided, fetches it from the database
     * 
     * @access public
     * @return array with key 'access_token' if successful, otherwise the array will have 'err' element with description of error
     */
    public function getApiTokenUsingRefreshToken($refreshToken = null)
    {
        if (!$refreshToken) {
            $oauth = $this->UserOauth->getAll($this->userId, $this->provider);
            if (isset($oauth['UserOauth']['refresh_token'])){
                $refreshToken = $oauth['UserOauth']['refresh_token'];
            }
            else {
                err_log('Canvas API: Tried to fetch access tokens using a non-existent refresh token');
                return array('err'=>'Could not retrieve refresh token. Please contact the administrator.');
            }
        }
        return $this->_getAccessTokensFromCanvas('refresh_token', $refreshToken);
    }

    /**
     * get access token and other information using an authorization code (this is the step after user authorizes iPeer in Canvas)
     * if successful, also saves this information to the database
     *
     * @param string $code
     * 
     * @access public
     * @return array with key 'access_token' if successful, otherwise the array will have 'err' element with description of error
     */
    public function getApiTokenUsingCode($code)
    {
        return $this->_getAccessTokensFromCanvas('authorization_code', $code);
    }

    /**
     * retrieves requested data from canvas api
     *
     * @param object    $_controller the controller that initiated this request
     * @param string    $redirect_uri the page to end up on after this request is done (only used if oauth needed)
     * @param boolean   $force_auth redirects the user to give auhtorization through Canvas if not authorized yet
     * @param string    $uri canvas api uri
     * @param array     $params canvas api parameters
     * @param string    $additionalHeader
     * @param boolean   $refreshTokenAndRetry if set to true (default), uses the refresh token to retry if the access token is expired
     * 
     * @access public
     * @return mixed return requested data, otherwise void
     */
    public function getCanvasData($_controller, $redirect_uri, $force_auth, $uri, $params=null, $additionalHeader=null, $refreshTokenAndRetry=true)
    {   
        // first check to see if we have a valid access token
        $accessToken = $this->getAccessToken();

        // if auth token exists, attempt to call api
        if ($accessToken) {
            $data = $this->_getCanvasData($_controller, $redirect_uri, $accessToken, $uri, $params, $additionalHeader, $refreshTokenAndRetry);
            if ($data === false) {
                $_controller->Session->setFlash('There was an error retrieving data from Canvas. Please try again.');
            }
            else {
                return $data;
            }
        }

        // returning from canvas with auth code
        if (isset($_controller->params['url']['code'])){
            // ensure state is the same for security, then get tokens
            if ($_controller->params['url']['state'] == $_controller->Session->read('oauth_'.$this->provider.'_state')){
                $_controller->Session->delete('oauth_'.$this->provider.'_state');
                $apiToken = $this->getApiTokenUsingCode($_controller->params['url']['code']);
                if (isset($apiToken['accessToken'])) {
                    $_controller->Session->setFlash('You have successfully connected to Canvas.', 'flash_success');
                    $_controller->redirect($redirect_uri);
                }
                elseif (isset($apiToken['err'])){
                    $_controller->Session->setFlash($apiToken['err']);
                }
                else {
                    $_controller->Session->setFlash('There was an error connecting to Canvas. Please try again.');
                }
            }
            else {
                $_controller->Session->setFlash('There was an authentication error while trying to connect to Canvas. Please try again.');
            }
        }
        // if no access token, get a new access token by forwarding the user to the canvas auth page
        elseif ($force_auth) {
            $this->_getNewOauth($_controller, $redirect_uri);
        }        
    }

    /**
     * forward the user to canvas to give authorization to ipeer
     *
     * @param object    $_controller the controller that initiated this request
     * @param string    $redirect_uri the page to end up on after this request is done (only used if oauth needed)
     * 
     * @access private
     * @return void
     */
    private function _getNewOauth($_controller, $redirect_uri)
    {
        // handle the case of the user having cancelled the authorization
        if (isset($_controller->params['url']['error']) && $_controller->params['url']['error']=='access_denied'){
            $_controller->Session->setFlash(__('Canvas authorization was cancelled. You need to authorize iPeer '
                                              .'in order to use Canvas functionalities.', true));
            $_controller->redirect('/');
        }

        // this will be passed back by Canvas along with the code, so we save it in session and check it at that point to ensure security
        $state = uniqid('st');
        $_controller->Session->write('oauth_'.$this->provider.'_state', $state);

        // if true, it will force the user to enter their credentials, even if they're already logged into Canvas. By default, if 
        // a user already has an active Canvas web session, they will not be asked to re-enter their credentials.
        $forceLogin = in_array($this->SysParameter->get('system.canvas_force_login', 'false'), array('1', 'true', 'yes'));

        $canvasOauthUrl = $this->getBaseUrl(true) . '/login/oauth2/auth' . 
                            '?client_id=' . $this->SysParameter->get('system.canvas_client_id') .
                            '&response_type=code' . 
                            '&state=' . $state .
                            ($forceLogin ? '&force_login=1' : '') .
                            '&redirect_uri=' . array_shift(explode('?', $redirect_uri));
                            
        $_controller->redirect($canvasOauthUrl);
    }

    /**
     * get access token and other information. if successful, also saves this information to the database
     *
     * @param string $grantType either 'refresh_token' or 'authorization_code'
     * @param string $tokenOrCode the actual refresh token or authorization code
     * 
     * @access private
     * @return array with key 'access_token' if successful, otherwise the array will have 'err' element with description of error
     */
    private function _getAccessTokensFromCanvas($grantType, $tokenOrCode)
    {
        $params = array('grant_type' => $grantType,
                        'client_id' => $this->SysParameter->get('system.canvas_client_id'),
                        'client_secret' => $this->SysParameter->get('system.canvas_client_secret'));
                        
        if ($grantType == 'refresh_token') {
            $params['refresh_token'] = $tokenOrCode;
        }
        elseif ($grantType == 'authorization_code') {
            $params['code'] = $tokenOrCode;
        }
        
        $request = \Httpful\Request::post($this->getBaseUrl() . "/login/oauth2/token", http_build_query($params))->expectsJson();
        $request->timeoutIn(CanvasApiComponent::API_CALL_TIMEOUT);
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

    /**
     * retrieves requested data from canvas api
     *
     * @param object    $_controller the controller that initiated this request
     * @param string    $redirect_uri the page to end up on after this request is done (only used if oauth needed)
     * @param string    $uri canvas api uri
     * @param array     $params canvas api parameters
     * @param string    $additionalHeader
     * @param boolean   $refreshTokenAndRetry if set to true (default), uses the refresh token to retry if the access token is expired
     * 
     * @access private
     * @return mixed either the response body from the api, or false if not successful
     */
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
            
            $response = \Httpful\Request::get($this->getApiUrl() . $uri .
                ($params? '?' . $params_expanded : ''))
                    ->expectsJson()
                    ->addHeaders(array('Authorization' => 'Bearer ' . $accessToken))
                    ->addHeaders($additionalHeader? $additionalHeader : array())
                    ->timeoutIn(CanvasApiComponent::API_CALL_TIMEOUT)
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
            error_log($e->getMessage());
            return false;
        }
    }
}