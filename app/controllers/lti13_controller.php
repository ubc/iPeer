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
        $this->Auth->allow();
        $this->set('customLogo', null);
        $this->set('title_for_layout', "LTI 1.3");
    }

    public function index()
    {
        $json = $this->Lti13->getRegistrationJson();
        $this->set('json', $json);
    }

    public function login()
    {
        $login = LTI_OIDC_Login::new($this->Lti13->db);

        try {

            $url = Router::url('/lti13/launch', true);
            $redirect = $login->do_oidc_login_redirect($url);
            $redirect->do_redirect();

        } catch (OIDC_Exception $e) {

            echo $this->Lti13->errorMessage(sprintf("Error doing OIDC login: %s", $e->getMessage()));

        }
    }

    public function launch()
    {
        $launch = $this->Lti13->launch();
        $data['referer'] = $this->referer();
        $data += $this->Lti13->getData($launch->get_launch_id());

        $this->Lti13->resetLogs();
        $this->log("LTI 1.3 launch", 'lti13/launch');
        $this->log(json_encode($data, 448), 'lti13/launch');

        if ($this->referer() != '/') {
            $this->redirect($this->referer(array('action' => 'roster')));
        }
        $this->set($data);
    }

    public function roster()
    {
        $this->Lti13->roster($launch_id);

        $this->log("LTI 1.3 roster updates", 'lti13/roster');
        $this->log($this->Lti13->rosterUpdatesLog, 'lti13/roster');

        $this->redirect($this->referer(array('action' => 'signin')));
    }

    public function signin()
    {
        try {

            if (!$user = $this->Lti13->findUserByLtiUserId()) {
                throw new LTI_Exception("LTI user ID not found.");
                return;
            }

            $id = $user['User']['id'];
            if (!$this->Auth->login($id)) {
                throw new LTI_Exception(sprintf("Access denied to user ID %s.", $id));
                return;
            }

            $this->log("LTI 1.3 user signed in", 'lti13/user');
            $this->log($user, 'lti13/user');

            return;

        } catch (LTI_Exception $e) {

            echo $this->Lti13->errorMessage($e->getMessage());

        }
    }
}
