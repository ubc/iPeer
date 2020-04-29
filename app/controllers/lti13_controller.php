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
 * @link      https://www.imsglobal.org/spec/security/v1p0/#fig_oidcflow
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
    }

    /**
     * OIDC login action called by platform.
     */
    public function login()
    {
        try {

            $login = LTI_OIDC_Login::new($this->Lti13->db);
            $url = Router::url('/lti13/launch', true);
            $redirect = $login->do_oidc_login_redirect($url);
            $redirect->do_redirect();

        } catch (OIDC_Exception $e) {

            $this->Session->setFlash(sprintf("Error doing OIDC login: %s", $e->getMessage()));
            $this->redirect(array('controller'=>'home', 'action'=>'index'));

        }
    }

    /**
     * Launch action called by platform.
     */
    public function launch()
    {
        try {

            $launch = $this->Lti13->launch();
            $data = $this->Lti13->getData($launch->get_launch_id());
            $this->Lti13->resetLogs();
            $this->log(json_encode($data, 448), 'lti13/launch');

            $this->Session->setFlash(__('LTI 1.3 launch success', true), 'good');

            if (!$this->Auth->isLoggedIn()) {
                $this->redirect('/');
            }

            $user = $this->checkUser();

            if ($this->isAdminOrInstructor($user)) {
                if ($courseId = @$this->Lti13->getCourseId()) {
                    $this->redirect(array('controller'=>'courses', 'action'=>'home', $courseId));
                }
                $this->redirect(array('controller'=>'courses', 'action'=>'index'));
            }

            $this->redirect(array('controller'=>'home', 'action'=>'index'));

        } catch (LTI_Exception $e) {

            $this->Session->setFlash($e->getMessage());
            $this->redirect('/logout');

        }
    }

    /**
     * Update roster by course ID from platform.
     *
     * Called by tool, not platform.
     * @param string $courseId
     */
    public function roster($courseId)
    {
        try {

            $this->Lti13->updateRoster($courseId);
            $this->log($this->Lti13->rosterUpdatesLog, 'lti13/roster');

            $this->Session->setFlash(__('Updated roster from Canvas', true), 'good');
            $this->redirect($this->referer(array('controller'=>'home', 'action'=>'index')));

        } catch (LTI_Exception $e) {

            $this->Session->setFlash($e->getMessage());
            $this->redirect($this->referer(array('controller'=>'home', 'action'=>'index')));

        }
    }

    /**
     * Check if current user has LTI user ID in dB.
     *
     * @return array
     */
    private function checkUser()
    {
        if (!$user = $this->Lti13->findUserByLtiUserId()) {
            throw new LTI_Exception("LTI user ID not found.");
            return;
        }

        if ($user['User']['id'] != $this->Auth->user('id')) {
            throw new LTI_Exception("Mismatched user logged in.");
            return;
        }

        $this->log($user, 'lti13/user');

        return $user;
    }

    /**
     * Check if current user is in ['superadmin', 'admin', 'instructor']
     *
     * @param array $user
     * @return bool
     */
    private function isAdminOrInstructor($user)
    {
        $roles = array_column($user['Role'], 'name');
        return (bool)preg_grep('/superadmin|admin|instructor/i', $roles);
    }

}
