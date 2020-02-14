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
    private $log_path = ROOT.'/app/tmp/logs/lti13';

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

            echo $this->Lti13->errorMessage("Error doing OIDC login.");

        }
        $redirect->do_redirect();
    }

    public function launch()
    {
        $this->Lti13->launch();
        $data = $this->Lti13->getLaunchData();

        $this->resetLogs();

        $this->log("LTI 1.3 launch", 'lti13/launch');
        $this->log($data, 'lti13/launch');

        $this->roster();

        $data += array(
            'log_launch' => file_get_contents($this->log_path.'/launch.log'),
            'log_roster' => file_get_contents($this->log_path.'/roster.log'),
            'log_user' => file_get_contents($this->log_path.'/user.log'),
        );
        $this->set($data);
        $this->set('customLogo', null);
        $this->render();
    }

    public function roster()
    {
        $this->Lti13->roster();

        $this->log("LTI 1.3 roster updates", 'lti13/roster');
        $this->log($this->Lti13->ltiRoster, 'lti13/roster');

        try {

            $user = $this->signInUser();

            $this->log("LTI 1.3 user signed in", 'lti13/user');
            $this->log($user, 'lti13/user');

            $this->redirect('/home');

        } catch (LTI_Exception $e) {

            echo $this->Lti13->errorMessage($e->getMessage());

        }
    }

    public function signInUser()
    {
        if (!$user = $this->Lti13->findUserByLtiUserId()) {
            throw new LTI_Exception("User not found.");
            return;
        }
        if (!$this->Auth->login($user['User']['id'])) {
            throw new LTI_Exception("Access denied.");
            return;
        }
        return $user;
    }

    private function resetLogs()
    {
        $filenames = glob($this->log_path.'/*.log');
        foreach ($filenames as $filename) {
            unlink($filename);
        }
    }
}
