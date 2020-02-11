<?php
App::import('Lib', 'Lti13Bootstrap');
App::import('Model', 'Lti13');

use IMSGlobal\LTI\LTI_Exception;
use IMSGlobal\LTI\LTI_OIDC_Login;
use IMSGlobal\LTI\OIDC_Exception;

/**
 * LTI 1.3 Controller
 *
 * @uses      AppController
 * @package   CTLT.iPeer
 * @since     3.4.5
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
        $this->Lti13->launch();
        $data = $this->Lti13->getLaunchData();
        $this->set($data);
        $this->set('customLogo', null);
        $this->render();
    }

    public function update()
    {
        $this->Lti13->update();
        try {
            $this->signInUser();
            $this->redirect('/home');
        } catch (LTI_Exception $e) {
            echo $e->getMessage();
        }
    }

    public function signInUser()
    {
        if (!$userId = $this->Lti13->findUserByLtiUserId()) {
            throw new LTI_Exception("User not found.");
            return;
        }
        if (!$this->Auth->login($userId)) {
            throw new LTI_Exception("Access denied.");
            return;
        }
    }
}
