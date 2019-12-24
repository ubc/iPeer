<?php
App::import('Model', 'Lti13');

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
        $json = $this->Lti13->get_registration_json();
        $this->set('json', $json);
        $this->set('customLogo', null);
        $this->render();
    }

    public function update()
    {
        $this->Lti13->update();
        $this->redirect('/home');
    }

    public function login()
    {
        $login = LTI_OIDC_Login::new($this->Lti13->ltidb);
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
        $this->Lti13->launch();
        $data = $this->Lti13->get_launch_data();
        $this->set($data);
        $this->set('customLogo', null);
        $this->render();
    }
}
