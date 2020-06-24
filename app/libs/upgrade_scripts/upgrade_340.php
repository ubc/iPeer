<?php
require_once('upgrade_base.php');
/**
 * Upgrade334
 *
 * @uses UpgradeBase
 * @package   CTLT.iPeer
 * @author    Pan Luo
 * @copyright 2018 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.4.0
 */
 class Upgrade340 extends UpgradeBase
{
    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->fromVersions = array(null, '3.3.4', '3.3.5');
        $this->toVersion = '3.4.0';
        $this->dbVersion = 16;
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function isUpgradable()
    {
        return parent::isUpgradable();
    }

    /**
     * up
     *
     * @access public
     * @return boolean
     */
    public function up()
    {
        $sysparameter = ClassRegistry::init('SysParameter');
        $dbv = $sysparameter->get('database.version');

        $ret = $this->patchDb($dbv, $this->dbVersion);
        if ($ret) {
            $this->errors[] = sprintf(__('Database patching failed: %s', true), $ret);
            return false;
        }
        $sysparameter->reload();

        return true;
    }
}
