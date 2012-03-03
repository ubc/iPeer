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
    public $uses =  array('User', 'SysFunction', 'SysParameter');
    public $Sanitize;
    public $components = array('rdAuth', 'Output', 'sysContainer', 'userPersonalize', 'framework');

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
        $this->set('title_for_layout', __('Framework', true));
        parent::__construct();
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


    /**
     * userInfoDisplay
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function userInfoDisplay($id)
    {
        $this->AccessControl->check('functions/user', 'read');

        if (!is_numeric($id) || !($this->data = $this->User->findUserByid($id))) {
            $this->Session->setFlash(__('Invalid user ID.', true));
            $this->redirect('index');
        }

        $roles = $this->User->getRoles($id);
        if (!$this->AccessControl->hasPermissionDoActionOnUserWithRoles('ViewUser', $roles)) {
            $this->Session->setFlash(__('You do not have permission to view this user.', true));
            $this->redirect('index');
        }

        $this->autoRender = false;
        $this->layout = 'pop_up';
        $this->set('data', $this->data);
        $this->render("userinfo");
    }


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
