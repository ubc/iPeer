<?php
App::import('Model', 'SysParameter');
App::import('Model', 'UserOauth');
App::import('Component', 'CanvasApi');
App::import('Vendor', 'Httpful', array('file' => 'nategood'.DS.'httpful'.DS.'bootstrap.php'));

/**
 * CanvasGraphQlComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Clarence Ho <clarence.ho@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CanvasGraphQlComponent extends CanvasApiComponent
{
    protected $graphQlPath;

    /**
     * overriden constructor to initialize private variables
     *
     * @param integer $userId required for most nonstatic functionalities
     *
     * @access public
     */
    public function __construct($userId)
    {
        parent::__construct($userId);
        $this->graphQlPath = '/api/graphql';
    }

    /**
     * get full URL to GraphQL (without appending slash). Append API endpoint URI to make a request
     *
     * @access public
     * @return void
     */
    public function getGraphQlUrl()
    {
        return $this->getBaseUrl() . $this->graphQlPath;
    }

    /**
     * Set query via GraphQL. This is a simple implementation that doesn't handle oauth token and pagniation etc
     *
     * @param object    $_controller the controller that initiated this request
     * @param string    $query GraphQL query
     *
     * @access private
     * @return mixed either the response body from the api, or false if not successful
     */
    public function postGraphQl($_controller, $query=null)
    {
        // TODO handle pagination if we are going to use GraphQL to retrieve data

        $result = false;
        try {
            $accessToken = $this->getAccessToken();
            if (! $accessToken) {
                return $result;
            }
            $response = \Httpful\Request::post($this->getGraphQlUrl())
                ->sendsJson()
                ->body($query)
                ->expectsJson()
                ->addHeaders(array('Authorization' => 'Bearer ' . $accessToken))
                ->timeoutIn($this->apiTimeout)
                ->send();

            if (is_object($response->body)) {
                $result = $response->body;
            }
            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
