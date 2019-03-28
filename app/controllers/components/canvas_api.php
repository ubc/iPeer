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
class CanvasApiComponent extends CakeObject
{
    protected $apiTimeout;                  // RESTful call timeout in seconds
    protected $paginationDefaultPerPage;    // default items to retrieve per each call to Canvas API
    protected $paginationMaxRetrieveAll;    // max how many items to retrieve when auto-looping pagination
    protected $paginationMaxCall;           // limit the number of Canvas API call when auto-looping pagination

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

        $this->apiTimeout = (int)$this->SysParameter->get('system.canvas_api_timeout', '10');
        $this->paginationDefaultPerPage = (int)$this->SysParameter->get('system.canvas_api_default_per_page', '500');
        $this->paginationMaxRetrieveAll = (int)$this->SysParameter->get('system.canvas_api_max_retrieve_all', '10000');
        $this->paginationMaxCall = (int)$this->SysParameter->get('system.canvas_api_max_call', '20');
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

    // use the url set in the settings first. The deploy behind load
    // balancer with SSL off loading may cause problem if using the URL
    // directly with Router::url (missing https)
    private function _getCurrentUrl() {
        $appUrl = $this->SysParameter->get('system.absolute_url');
        if (empty($appUrl)) {
            $appUrl = Router::url(null, true);
        } else {
            $appUrl .= Router::url(null, false);
        }
        return $appUrl;
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
                    if (isset($apiToken['accessToken'])){
                        $accessToken = $apiToken['accessToken'];
                    }
                    else {
                        return false;
                    }
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
     * get requested data from canvas api
     *
     * @param object    $_controller the controller that initiated this request
     * @param boolean   $force_auth redirects the user to give auhtorization through Canvas if not authorized yet
     * @param string    $uri canvas api uri
     * @param array     $params canvas api parameters
     * @param string    $additionalHeader
     * @param boolean   $refreshTokenAndRetry if set to true (default), uses the refresh token to retry if the access token is expired
     * @param string    $method 'get' (default) or 'post' to do a post request instead
     * @param boolean   $retrieveAll Canvas API uses pagination to limit items returned per call.  Set to true (default) to retrieve all (max limited by system parameter "system.canvas_api_max_retrieve_all")
     * @param integer   $perPage Limit how many items to retrieve on each Canvas API call. Default to system parameter "system.canvas_api_default_per_page" or 500
     *
     * @access public
     * @return mixed return requested data, otherwise void
     */
    public function getCanvasData($_controller, $force_auth, $uri, $params=null, $additionalHeader=null, $refreshTokenAndRetry=true, $method='get', $retrieveAll=true, $perPage=null)
    {
        if (is_null($perPage)) {
            $perPage = $this->paginationDefaultPerPage;
        }

        // first check to see if we have a valid access token
        $accessToken = $this->getAccessToken();

        // if auth token exists, attempt to call api
        if ($accessToken) {
            $data = $this->_getPostCanvasData($_controller, $accessToken, $uri, $params, $additionalHeader, $refreshTokenAndRetry, $method, $retrieveAll, $perPage);
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
                    $_controller->Session->setFlash('You have successfully connected to Canvas.', 'good');
                    $_controller->redirect($this->_getCurrentUrl());
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
            $this->_getNewOauth($_controller);
        }
    }

    /**
     * post requested data to canvas api
     *
     * @param object    $_controller the controller that initiated this request
     * @param boolean   $force_auth redirects the user to give auhtorization through Canvas if not authorized yet
     * @param string    $uri canvas api uri
     * @param array     $params canvas api parameters
     * @param string    $additionalHeader
     * @param boolean   $refreshTokenAndRetry if set to true (default), uses the refresh token to retry if the access token is expired
     *
     * @access public
     * @return mixed return response, otherwise void
     */
    public function postCanvasData($_controller, $force_auth, $uri, $params=null, $additionalHeader=null, $refreshTokenAndRetry=true)
    {
        return $this->getCanvasData($_controller, $force_auth, $uri, $params, $additionalHeader, $refreshTokenAndRetry, 'post');
    }

    /**
     * delete requested data from canvas api
     *
     * @param object    $_controller the controller that initiated this request
     * @param boolean   $force_auth redirects the user to give auhtorization through Canvas if not authorized yet
     * @param string    $uri canvas api uri
     * @param array     $params canvas api parameters
     * @param string    $additionalHeader
     * @param boolean   $refreshTokenAndRetry if set to true (default), uses the refresh token to retry if the access token is expired
     *
     * @access public
     * @return mixed return response, otherwise void
     */
    public function deleteCanvasData($_controller, $force_auth, $uri, $params=null, $additionalHeader=null, $refreshTokenAndRetry=true)
    {
        return $this->getCanvasData($_controller, $force_auth, $uri, $params, $additionalHeader, $refreshTokenAndRetry, 'delete');
    }

    /**
     * forward the user to canvas to give authorization to ipeer
     *
     * @param object    $_controller the controller that initiated this request
     *
     * @access private
     * @return void
     */
    private function _getNewOauth($_controller)
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
        $_split_current_url = explode('?', $this->_getCurrentUrl());

        $canvasOauthUrl = $this->getBaseUrl(true) . '/login/oauth2/auth' .
                            '?client_id=' . $this->SysParameter->get('system.canvas_client_id') .
                            '&response_type=code' .
                            '&state=' . $state .
                            ($forceLogin ? '&force_login=1' : '') .
                            '&purpose=iPeer' .
                            '&redirect_uri=' . array_shift($_split_current_url);

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
        $request->timeoutIn($this->apiTimeout);
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
     * @param mixed     $accessToken access token for Canvas API calls
     * @param string    $uri canvas api uri
     * @param array     $params canvas api parameters
     * @param string    $additionalHeader
     * @param boolean   $refreshTokenAndRetry if set to true (default), uses the refresh token to retry if the access token is expired
     * @param string    $method 'get' (default) or 'post' to do a post request instead
     * @param boolean   $retrieveAll Canvas API uses pagination to limit items returned per call.  Set to true (default) to retrieve all (max limited by system parameter "system.canvas_api_max_retrieve_all")
     * @param integer   $perPage Limit how many items to retrieve on each Canvas API call. Default to system parameter "system.canvas_api_default_per_page" or 500
     *
     * @access private
     * @return mixed either the response body from the api, or false if not successful
     */
    private function _getPostCanvasData($_controller, $accessToken, $uri, $params=null, $additionalHeader=null, $refreshTokenAndRetry=true, $method='get', $retrieveAll=true, $perPage=null)
    {
        if (is_null($perPage)) {
            $perPage = $this->paginationDefaultPerPage;
        }
        if ($method != 'get' && $method != 'post' && $method != 'delete') {
            return false;
        }

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

            if ($method == 'get' && !isset($params['per_page'])) {
                $params_expanded = $params_expanded . '&'. http_build_query(array('per_page' => $perPage));
            }

            $result = array();
            $nextPageUrl = false;
            $callCount = 0;
            do {
                if ($method == 'post') {
                    if ($params) {
                        $paramsFixed = array();
                        foreach ($params as $key => $value) {
                            $paramsFixed[str_replace('[]','',$key)] = $value;
                        }
                        $params = $paramsFixed;
                    }
                    $response = \Httpful\Request::post($this->getApiUrl() . $uri)
                        ->sendsJson()
                        ->body(json_encode($params))
                        ->expectsJson()
                        ->addHeaders(array('Authorization' => 'Bearer ' . $accessToken))
                        ->addHeaders($additionalHeader? $additionalHeader : array())
                        ->timeoutIn($this->apiTimeout)
                        ->send();
                }
                elseif ($method == 'get' && $nextPageUrl) {
                    $response = \Httpful\Request::get($nextPageUrl)
                        // no need to append parameters here.
                        // the Canvas pagination url is absolute and contains all necessary params
                        ->expectsJson()
                        ->addHeaders(array('Authorization' => 'Bearer ' . $accessToken))
                        ->addHeaders($additionalHeader? $additionalHeader : array())
                        ->timeoutIn($this->apiTimeout)
                        ->send();
                } else {
                    $response = \Httpful\Request::$method($this->getApiUrl() . $uri .
                        ($params? '?' . $params_expanded : ''))
                            ->expectsJson()
                            ->addHeaders(array('Authorization' => 'Bearer ' . $accessToken))
                            ->addHeaders($additionalHeader? $additionalHeader : array())
                            ->timeoutIn($this->apiTimeout)
                            ->send();
                }

                $callCount += 1;
                // ignore "not found" errors
                if (is_object($response->body) && isset($response->code) && $response->code == 404) {
                    continue;
                }
                // only merge result if there is no error
                elseif (is_object($response->body) && isset($response->body->errors)) {
                    $_controller->Session->setFlash('There was an error sending / receiving Canvas data:' .
                                                    $this->_getErrorsAsString($response->body->errors));
                    break;
                }
                elseif (is_array($response->body)) {
                    $result = array_merge($result, $response->body);
                }
                else {
                    $result = $response->body;
                }
                $nextPageUrl = $this->_hasMore($response);
            } while (
                $method == 'get' &&     // only do pagination loop for GET
                $retrieveAll &&
                $callCount < $this->paginationMaxCall &&
                $nextPageUrl !== false &&
                sizeof($result) < $this->paginationMaxRetrieveAll);

            if (isset($response->body->errors) && $refreshTokenAndRetry) {
                // most likely, the access token is no longer valid (expired), so use refresh token to get it
                $apiToken = $this->getApiTokenUsingRefreshToken();
                if (isset($apiToken['accessToken'])) {
                    return $this->_getPostCanvasData($_controller, $apiToken['accessToken'], $uri, $params, $additionalHeader, false, $method, $retrieveAll, $perPage);
                }
                // if not able to get new access token, we need to re-authenticate with canvas
                else {
                    $this->UserOauth->deleteToken($this->userId, $this->provider);
                    $this->_getNewOauth($_controller);
                }
            }
            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Turns the Canvas API error object into a string for display
     *
     * @param object $errors
     * @param string $separator if not set or null, it will return an unordered list
     * @param integer $num if set, it will limit the errors to a certain number
     *
     * @access private
     * @return string
     */
    private function _getErrorsAsString($errors, $separator=null, $num=null) {
        $ret = array();

        if (is_object($errors)) {
            foreach ($errors as $param => $errs) {
                foreach ($errs as $err) {
                    $ret[] = $err->message;
                }
            }
        }

        if (is_array($errors) && isset($errors[0]) && is_object($errors[0]) && isset($errors[0]->message)) {
            foreach ($errors as $err) {
                $ret[] = $err->message;
            }
        }

        if ($num !== null) {
            $num += 0;
            array_splice($ret, 0, $num);
        }

        if ($separator === null) {
            return '<ul><li>' . implode('</li><li>', $ret) . '</li></ul>';
        }
        else {
            return implode($separator, $ret);
        }
    }

    /**
     * checks if there are more items to retrieve from Canvas API pagination
     *
     * @param Httpful\Response    $response the response from Canvas API
     *
     * @access private
     * @return mixed false if no more items to retrieve.  Otherwise, the URL to retrieve the next page
     */
    private function _hasMore($response) {
        if (!$response->headers->offsetExists('link')) {
            return false;
        }
        $links = explode(',', trim($response->headers->offsetGet('link')));
        foreach ($links as $link) {
            $elements = explode(';', $link);
            $url = trim($elements[0], '<> ');
            $relArray = array();
            foreach ($elements as $elm) {
                if (strncmp(trim($elm), 'rel=', 4) == 0) {
                    $relArray = explode(' ', trim(substr(trim($elm), 4), '"\''));
                    break;
                }
            }

            if (in_array('next', $relArray)) {
                return $url;
            }
        }
        return false;
    }
}
