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
        $this->Auth->autoRedirect = false;
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

            // LTI 1.3 launch and log
            $launch = $this->Lti13->launch();
            $data = $this->Lti13->getData($launch->get_launch_id()); // Needs launch cache
            $this->Lti13->resetLogs();
            $this->log(json_encode($data, 448), 'lti13/launch');

            $this->Session->setFlash(__('LTI 1.3 launch success', true), 'good');

            // Automatic user login and log
            $user = $this->Lti13->getPuidUser(); // Needs launch cache
            $this->Auth->login($user);
            $this->log($user, 'lti13/user');

            // Redirect to course page
            $courseId = @$this->Lti13->getCourseId(); // Needs launch cache
            $course_home_courseId_url = Router::url(array('controller'=>'courses', 'action'=>'home', $courseId));
            $course_index_url = Router::url(array('controller'=>'courses', 'action'=>'index'));
            $home_index_url = Router::url(array('controller'=>'home', 'action'=>'index'));
            if ($this->Lti13->isAdminOrInstructor($user)) {
                if ($courseId) {
                    $this->Auth->redirect($course_home_courseId_url);
                } else {
                    $this->Auth->redirect($course_index_url);
                }
            } else {
                $this->Auth->redirect($home_index_url);
            }
            $this->redirect($this->Auth->redirect());


            // ####################################################################################
            // TEST page app/views/lti13/launch.ctp
            // ####################################################################################
/*
            if ($cached_launch = @$this->Lti13->launchFromCache()) {
                $cached_launch_id = $cached_launch->get_launch_id();
            }
            $this->set('test', json_encode(array(

                // New launch object

                'new_launch_class'                 => get_class($launch),
                'new_launch_id'                    => $launch->get_launch_id(),

                // User

                'current_user'                     => $this->Auth->user(),
                'current_user_roles'               => array_column($user['Role'], 'name'),
                'current_user_isAuthorized_bool'   => $this->Auth->isAuthorized() ? 'true' : 'false',
                'isAdminOrInstructor_bool'         => $this->Lti13->isAdminOrInstructor($user) ? 'true' : 'false',

                // Course

                'courseId'                         => $courseId,

                // Redirect URLs

                'course_home_courseId_url'         => $course_home_courseId_url,
                'course_index_url'                 => $course_index_url,
                'home_index_url'                   => $home_index_url,
                'Auth_redirect_url'                => $this->Auth->redirect(),

                // Cache

                'cached_launch_class'              => get_class($cached_launch),
                'cached_launch_id'                 => $cached_launch_id,
                'sys_get_temp_dir'                 => sys_get_temp_dir(),
                'lti_cache_txt' => array(
                    'file_exists_bool'   => file_exists(sys_get_temp_dir() . '/lti_cache.txt') ? 'true' : 'false',
                    'is_file_bool'       => is_file(sys_get_temp_dir() . '/lti_cache.txt') ? 'true' : 'false',
                    'chown'              => posix_getpwuid(fileowner(sys_get_temp_dir() . '/lti_cache.txt'))['name'],
                    'chgrp'              => posix_getgrgid(filegroup(sys_get_temp_dir() . '/lti_cache.txt'))['name'],
                    'chmod'              => substr(sprintf('%o', fileperms(sys_get_temp_dir() . '/lti_cache.txt')), -4),
                    'is_readable_bool'   => is_readable(sys_get_temp_dir() . '/lti_cache.txt') ? 'true' : 'false',
                    'is_writable_bool'   => is_writable(sys_get_temp_dir() . '/lti_cache.txt') ? 'true' : 'false',
                    'is_executable_bool' => is_executable(sys_get_temp_dir() . '/lti_cache.txt') ? 'true' : 'false',
                    'file_get_contents'  => json_decode(file_get_contents(sys_get_temp_dir() . '/lti_cache.txt')),
                ),

                // Logging

                'log_path' => array(
                    'uri'                => $this->Lti13->log_path,
                    'is_dir_bool'        => is_dir($this->Lti13->log_path) ? 'true' : 'false',
                    'chown'              => posix_getpwuid(fileowner($this->Lti13->log_path))['name'],
                    'chgrp'              => posix_getgrgid(filegroup($this->Lti13->log_path))['name'],
                    'chmod'              => substr(sprintf('%o', fileperms($this->Lti13->log_path)), -4),
                    'is_readable_bool'   => is_readable($this->Lti13->log_path) ? 'true' : 'false',
                    'is_writable_bool'   => is_writable($this->Lti13->log_path) ? 'true' : 'false',
                    'is_executable_bool' => is_executable($this->Lti13->log_path) ? 'true' : 'false',
                ),

                'launch_file' => array(
                    'file_exists_bool'   => file_exists($this->Lti13->log_path . '/launch.log') ? 'true' : 'false',
                    'is_file_bool'       => is_file($this->Lti13->log_path . '/launch.log') ? 'true' : 'false',
                    'chown'              => posix_getpwuid(fileowner($this->Lti13->log_path . '/launch.log'))['name'],
                    'chgrp'              => posix_getgrgid(filegroup($this->Lti13->log_path . '/launch.log'))['name'],
                    'chmod'              => substr(sprintf('%o', fileperms($this->Lti13->log_path . '/launch.log')), -4),
                    'is_readable_bool'   => is_readable($this->Lti13->log_path . '/launch.log') ? 'true' : 'false',
                    'is_writable_bool'   => is_writable($this->Lti13->log_path . '/launch.log') ? 'true' : 'false',
                    'is_executable_bool' => is_executable($this->Lti13->log_path . '/launch.log') ? 'true' : 'false',
                ),

                'user_file' => array(
                    'file_exists_bool'   => file_exists($this->Lti13->log_path . '/user.log') ? 'true' : 'false',
                    'is_file_bool'       => is_file($this->Lti13->log_path . '/user.log') ? 'true' : 'false',
                    'chown'              => posix_getpwuid(fileowner($this->Lti13->log_path . '/user.log'))['name'],
                    'chgrp'              => posix_getgrgid(filegroup($this->Lti13->log_path . '/user.log'))['name'],
                    'chmod'              => substr(sprintf('%o', fileperms($this->Lti13->log_path . '/user.log')), -4),
                    'is_readable_bool'   => is_readable($this->Lti13->log_path . '/user.log') ? 'true' : 'false',
                    'is_writable_bool'   => is_writable($this->Lti13->log_path . '/user.log') ? 'true' : 'false',
                    'is_executable_bool' => is_executable($this->Lti13->log_path . '/user.log') ? 'true' : 'false',
                ),

                'test_file' => array(
                    'file_put_contents_int' => (int)file_put_contents($this->Lti13->log_path . '/test.log', uniqid('test log ')), // => number of bytes or 0
                    'file_exists_bool'      => file_exists($this->Lti13->log_path . '/test.log') ? 'true' : 'false',
                    'is_file_bool'          => is_file($this->Lti13->log_path . '/test.log') ? 'true' : 'false',
                    'chown'                 => posix_getpwuid(fileowner($this->Lti13->log_path . '/test.log'))['name'],
                    'chgrp'                 => posix_getgrgid(filegroup($this->Lti13->log_path . '/test.log'))['name'],
                    'chmod'                 => substr(sprintf('%o', fileperms($this->Lti13->log_path . '/test.log')), -4),
                    'is_readable_bool'      => is_readable($this->Lti13->log_path . '/test.log') ? 'true' : 'false',
                    'is_writable_bool'      => is_writable($this->Lti13->log_path . '/test.log') ? 'true' : 'false',
                    'is_executable_bool'    => is_executable($this->Lti13->log_path . '/test.log') ? 'true' : 'false',
                    'file_get_contents'     => file_get_contents($this->Lti13->log_path . '/test.log'),
                ),

            ), 448));
*/
            // ####################################################################################


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
