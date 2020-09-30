<?php
App::import('Lib', 'lti');

use lti\LTIDatabase;
use lti\LTICache;
use lti\LTICookie;
use lti\LaunchDataParser;
use lti\NamesAndRolesService;
use IMSGlobal\LTI\LTI_Exception;
use IMSGlobal\LTI\LTI_Message_Launch;
use IMSGlobal\LTI\LTI_OIDC_Login;
use IMSGlobal\LTI\OIDC_Exception;

/**
 * LTI 1.3 Controller
 *
 * @uses      AppController
 * @package   CTLT.iPeer
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 * @link      https://www.imsglobal.org/spec/security/v1p0/#fig_oidcflow
 */
class LtiController extends AppController
{
    public $uses = array('LtiContext', 'LtiResourceLink', 'LtiUser',
        'User', 'Course');

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        //ensure the user is logged out of iPeer when starting the login/launch actions
        if (!empty($this->params['action']) && in_array($this->params['action'], ['login', 'launch'])) {
            if ($this->Auth->isAuthorized()) {
                $this->Auth->logout();
            }
        }
        $this->Auth->allow('login', 'launch');
        parent::beforeFilter();
    }

    /**
     * OIDC login action called by platform.
     */
    public function login()
    {
        try {
            $login = LTI_OIDC_Login::new(
                new LTIDatabase(),
                new LTICache(),
                new LTICookie()
            );
            $url = Router::url('/lti/launch', true);
            $redirect = $login->do_oidc_login_redirect($url);
            // fix `die` call issue for testing by redirecting manually using framework
            $this->redirect($redirect->get_redirect_url());
        } catch (OIDC_Exception $e) {
            $this->Session->setFlash(sprintf("Error doing OIDC login: %s", $e->getMessage()));
            $this->redirect('/home/index');
        }
    }

    /**
     * Launch action called by platform.
     */
    public function launch()
    {
        // fixes potential warning when state/id_token param is missing
        // (will still properly error out in validation)
        if (empty($_POST['state'])) {
            $_POST['state'] = null;
        }
        if (empty($_POST['id_token'])) {
            $_POST['id_token'] = null;
        }

        $launch = LTI_Message_Launch::new(
            new LTIDatabase(),
            new LTICache(),
            new LTICookie()
        );
        try {
            $launch->validate();
        } catch (LTI_Exception $e) {
            $this->Session->setFlash($e->getMessage());
            $this->redirect('/logout');
            return;
        }

        if (!$launch->is_resource_launch()) {
            $this->Session->setFlash("Not an LTI Launch.");
            $this->redirect('/logout');
            return;
        }
        $launch_data = $launch->get_launch_data();
        $launch_data_parser = new LaunchDataParser($launch_data);

        $this->log(json_encode($launch_data, 448), 'lti/launch');

        // automatically create Faculty if needed
        $faculty = $this->LtiContext->syncFaculty($launch_data_parser);
        $faculty_id = $faculty['Faculty']['id'];

        // automatically create/update lti context + course
        $lti_context = $this->LtiContext->syncLaunchContext($launch_data_parser);
        $lti_context_id = $lti_context['LtiContext']['id'];
        $course_id = $lti_context['Course']['id'];

        // automatically create/update lti resource link + assignment (skipping assignment for now)
        $lti_resource_link = $this->LtiResourceLink->syncLaunchResourceLink($launch_data_parser, $lti_context_id);

        // automatically create/update lti user + user
        $lti_user = $this->LtiUser->syncUser(
            $launch_data_parser->lti_tool_registration['id'],
            $launch_data_parser->getParam('sub'),
            $launch_data_parser->getUserData()
        );
        $user_id = $lti_user['User']['id'];
        $role_id = $launch_data_parser->getCourseRole();

        // automatically update user enrollment
        $this->LtiUser->syncUserEnrollment($course_id, $user_id, $faculty_id, $role_id);

        // Automatic user login and log
        $this->Auth->login($user_id);
        if (method_exists($this, '_afterLogin')) {
            $this->_afterLogin(false);
        }

        $this->log($lti_user['User'], 'lti/user');

        $this->Session->setFlash(__('LTI launch success', true), 'good');
        if (in_array($role_id, [$this->User->USER_TYPE_ADMIN, $this->User->USER_TYPE_INSTRUCTOR, $this->User->USER_TYPE_TA])) {
            // Redirect to course page is instructor/admin
            $this->redirect("/courses/home/$course_id");
        } else {
            // else redirect to home page for students
            $this->redirect('/home/index');
        }
    }

    /**
     * Update roster by course ID from platform.
     *
     * Called by tool, not platform.
     * @param string $course_id
     */
    public function roster($course_id)
    {
        if (!User::hasPermission('controllers/Lti/roster')) {
            return;
        }
        $names_and_roles_service = new NamesAndRolesService($course_id);
        try
        {
            $names_and_roles_service->sync_membership();

            $this->Session->setFlash(__('Imported Users from LMS', true), 'good');
            $this->redirect("/courses/home/$course_id");

        } catch (LTI_Exception $e) {
            $this->Session->setFlash($e->getMessage());
            $this->redirect("/courses/home/$course_id");
        }
    }
}
