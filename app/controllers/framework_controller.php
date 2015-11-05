<?php
/**
 * FrameworkController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class FrameworkController extends AppController
{
    /**
     * This controller does not use a model
     *
     * @public $uses
     */
    public $uses =  array('User', 'SysParameter', 'UserCourse', 'UserTutor', 'UserEnrol');
    public $Sanitize;
    public $components = array('Output', 'userPersonalize', 'framework');

    /**
     * __construct
     *
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
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
        parent::beforeFilter();

        $this->set('title_for_layout', __('Framework',true));
    }

    /**
     * calendarDisplay
     *
     * @param string $datetime data time
     * @param string $id       id
     *
     * @access public
     * @return void
     */
    function calendarDisplay($datetime = '', $id='')
    {
        $this->autoRender = false;
        $this->layout = false;
        $redirect = "calendar";
        $this->render($redirect);
    }


    // Deprecated. It's the same as users/view.
    /**
     * userInfoDisplay
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    /*function userInfoDisplay($id)
    {
        $this->AccessControl->check('functions/user', 'read');

        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash(__('Error: You do not have permission to view users', true));
            $this->redirect('/home');
        }

        if (!is_numeric($id) || !($this->data = $this->User->findById($id))) {
            $this->Session->setFlash(__('Error: Invalid user ID.', true));
            $this->redirect($this->referer());
        }

        $role = $this->User->getRoleName($id);
        if (!User::hasPermission('functions/user/'.$role)) {
            $this->Session->setFlash(__('Error: You do not have permission to view this user', true));
            $this->redirect($this->referer());
        }

        if (!User::hasPermission('controllers/departments')) {
            // instructors
            $courses = User::getMyCourseList();
            $models = array('UserCourse', 'UserTutor', 'UserEnrol');
            $accessibleUsers = array();

            // generate a list of instructors, tutors, and students the user has access to
            foreach ($models as $model) {
                $users = $this->$model->find('list', array(
                    'conditions' => array('course_id' => array_keys($courses)),
                    'fields' => array('user_id')
                ));
                $accessibleUsers = array_merge($accessibleUsers, $users);
            }

            if (!in_array($id, $accessibleUsers)) {
                $this->Session->setFlash(__('Error: You do not have permission to view this user', true));
                $this->redirect($this->referer());
            }
        }

        $this->set('title_for_layout', __('View User', true));
        $this->autoRender = false;
        $this->set('user', $this->data);
        $this->render("userinfo");
    }*/


    /**
     * tutIndex
     *
     * @param bool $tut
     *
     * @access public
     * @return void
     */
    function tutIndex($tut=null)
    {
        $this->layout = 'tutorial_pop_up';
        $this->set('tut', $tut);
    }

}
