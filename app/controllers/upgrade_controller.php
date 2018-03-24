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
class UpgradeController extends Controller
{
    public $name       = "Upgrade";
    public $uses       = array('SysParameter');
    public $components = array('Session', 'Upgrader');
    public $layout = 'installer';

    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        Cache::clear(false, 'default');
        Cache::clear(false, 'configuration');
        $this->set('title_for_layout', __('Upgrade', true));
        if (!class_exists('DATABASE_CONFIG')) {
            // not a valid installation
            $this->Session->setFlash(__('It seems you do not have a installation of iPeer. Please install it first!', true));
            $this->redirect('/install');
            return;
        } elseif (IS_INSTALLED) {
            // 2.x upgrade
            $sysp = ClassRegistry::init('SysParameter');
            if (null == $sysp->get('system.version', null)) {
                return;
            }
        }

        // 3.x and above upgrade, enable permission
        // Workaround to avoid loading the User model with an iPeer2 database.
        // If we tried loading iPeer3's User model with an iPeer2 database,
        // we'll get missing table errors.
        App::import('Model', 'User');
        $permission = array_filter(array('controllers', ucwords($this->params['plugin']), ucwords($this->params['controller']), $this->params['action']));
        if (!User::hasPermission(join('/', $permission))) {
            $this->Session->setFlash('Error: You do not have permission to access the page.');
            $this->redirect('/home');
        }
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
        $sysv = $this->SysParameter->get('system.version');
        $dbv = $this->SysParameter->get('database.version');
        // workaround for a mistake in a sql templates in v3.0.x where
        // we forgot to add the system.version entry into sys_parameters
        if (empty($sysv) && $dbv == 4 && $this->SysParameter->get('display.vocabulary.department')) {
            // upgrading from iPeer v3.0
            $sysv = '3.0.x';
            $dbv = 5;
        } else {
            $sysv = $this->SysParameter->get('system.version', '2.x');
        }
        $this->set('is_upgradable', $this->Upgrader->isUpgradable());
        $this->set('currentVersion', $sysv);
        $this->set('currentDbVersion', $dbv);
        $this->set('dbVersion', Configure::read('DATABASE_VERSION'));
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
        $result = $this->Upgrader->upgrade();
        if ($result) {
            $this->Session->destroy();
        }
        $this->set('upgrade_success', $result);
    }
}
