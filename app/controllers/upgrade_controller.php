<?php
/**
 * UpgradeController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UpgradeController extends AppController
{
    public $name = "Upgrade";
    public $uses         = array();
    public $components   = array('Output',
        'framework',
        'Session',
        'Guard.Guard',
        'DbPatcher'
    );

    /**
     * __construct
     *
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->set('title_for_layout', __('Upgrade Database', true));
        parent::__construct();
    }


    /**
     * index
     *
     *
     * @access public
     * @return void
     */
    function index()
    {
        if ($this->checkPermission()) {
            $this->set('isadmin', false); // tell view user doesn't have access
        } else {
            $this->set('isadmin', true);
        }
    }


    /**
     * step2
     *
     *
     * @access public
     * @return void
     */
    function step2()
    {
        if ($this->checkPermission()) {
            $this->set('isadmin', false);
        } else {
            $this->set('isadmin', true);
            $this->set('upgradefailed', false); // tell view the upgrade worked fine
            $dbv = $this->SysParameter->getDatabaseVersion();
            $ret = $this->DbPatcher->patch($dbv);
            if ($ret) {
                $this->set('upgradefailed', $ret);
            }
        }
    }


    /**
     * checkPermission
     *
     *
     * @access public
     * @return void
     */
    function checkPermission()
    {
        if (User::hasPermission('controllers/upgrade')) {
            $this->Session->setFlash(__('Sorry, you do not have access to this page. Only administrators can perform a database upgrade. If you are an administrator, please login and then go to this page to perform the upgrade.', true));
            return true;
        }
        return false;
    }
}
