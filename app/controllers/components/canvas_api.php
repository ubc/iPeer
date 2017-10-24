<?php
App::import('Model', 'SysParameter');
App::import('Vendor', 'Httpful', array('file' => 'nategood/httpful/bootstrap.php'));

/**
 * CanvasApiComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Clarence Ho <clarence.ho@ubc.ca>
 * @copyright 2017 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CanvasApiComponent extends Object
{
    protected $SysParameter;

    public function __construct($user_id)
    {
        parent::__construct();
        $this->SysParameter = ClassRegistry::init('SysParameter');
    }

    public function getBaseUrl() {
        return $this->SysParameter->get('system.canvas_baseurl');
    }

    public function getVersion() {
        return '/api/v1';
    }

    public function getCanvasData($uri, $params=null, $additionalHeader=null, $refreshTokenAndRetry=True) {

        try {
            // TODO: get proper OAuth2 token
            $response = \Httpful\Request::get($this->getBaseUrl() .
                $this->getVersion() . $uri .
                ($params? '?' . http_build_query($params) : ''))
                    ->expectsJson()
                    ->addHeaders(array('Authorization' => 'Bearer JlKm0lJpGiM9HoeFp7GRLnnJ0trkGKYv5sir2CgxT4IUChgusM9PXPesV0m8ro5p'))
                    ->addHeaders($additionalHeader? $additionalHeader : array())
                    ->send();

            // TODO: check $response->code. if necessary handle token refresh and try again ($refreshTokenAndRetry).
            return $response->body;
        } catch (Exception $e) {
            // TODO: better error handling
            error_log($e->getMessage());
        }


    }

}