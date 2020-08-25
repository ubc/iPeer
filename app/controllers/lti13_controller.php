<?php
App::import('Lib', 'Lti13Bootstrap');
App::import('Model', 'Lti13');

use IMSGlobal\LTI\LTI_Exception;
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
        $this->Auth->autoRedirect = false;
    }

    /**
     * OIDC login action called by platform.
     */
    public function login()
    {
        try {

            $login = $this->Lti13->login();
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

            // LTI 1.3 launch and log
            $launch = $this->Lti13->launch();
            $data = $this->Lti13->getData($launch->get_launch_id()); // Needs launch cache
            $this->Lti13->resetLogs();
            $this->log(json_encode($data, 448), 'lti13/launch');

            $this->Session->setFlash(__('LTI 1.3 launch success', true), 'good');

            // Automatic user login and log
            $user = $this->Lti13->getPuidUser(); // Needs launch cache
            $this->Auth->login($user);
            $this->_afterLogin(false);
            $this->log($user, 'lti13/user');

            // Redirect to course page
            if ($this->Lti13->isAdminOrInstructor($user)) {
                if ($courseId = @$this->Lti13->getCourseId()) {
                    $url = Router::url(array('controller'=>'courses', 'action'=>'home', $courseId));
                } else {
                    $url = Router::url(array('controller'=>'courses', 'action'=>'index'));
                }
            } else {
                $url = Router::url(array('controller'=>'home', 'action'=>'index'));
            }
            $this->Auth->redirect($url);
            $this->redirect($this->Auth->redirect());

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
}
