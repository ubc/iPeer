<?php
App::import('Model', 'Lti13');

use IMSGlobal\LTI\LTI_Message_Launch;
use IMSGlobal\LTI\LTI_OIDC_Login;
use IMSGlobal\LTI\OIDC_Exception;

/**
 * LTI 1.3 Controller
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Lti13Controller extends AppController
{
    public $uses = array('Lti13');

    public function __construct()
    {
        parent::__construct();
    }

    public function beforeFilter()
    {
        $this->Auth->allow('index');
    }

    public function index()
    {
        $json = $this->Lti13->getRegistrationJson();
        $this->set('json', $json);
        $this->set('customLogo', null);
        $this->render();
    }

    public function login()
    {
        $login = LTI_OIDC_Login::new($this->Lti13->db);
        try {
            $url = Router::url('/lti13/launch', true);
            $redirect = $login->do_oidc_login_redirect($url);
        } catch (OIDC_Exception $e) {
            echo "Error doing OIDC login.";
        }
        $redirect->do_redirect();
    }

    public function launch()
    {
        $launch = LTI_Message_Launch::new($this->Lti13->db);
        try {
            $launch->validate();
        } catch (\Exception $e) {
            echo "Launch validation failed.";
        }
        $this->Lti13->launchId = $launch->get_launch_id();

        // $data = $this->Lti13->getLaunchData();
        // $this->set($data);
        // $this->set('customLogo', null);
        // $this->render();
    }

    public function update()
    {
        $launch = LTI_Message_Launch::from_cache($this->Lti13->launchId, $this->Lti13->db);
        $this->Lti13->jwtPayload = json_decode($launch->get_launch_data(), true);
        $this->Lti13->update();
        // $this->log_in();                // TO BE DETERMINED (from legacy code)
        $this->redirect('/home');
    }
}
