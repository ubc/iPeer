<?php
require_once('upgrade_base.php');
/**
 * Upgrade300
 *
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.0
 */
class Upgrade300 extends UpgradeBase
{
    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->fromVersions = array(null, '2.3.1');
        $this->toVersion = '3.0.0';
        $this->dbVersion = 5;
    }

    /**
     * isUpgradable
     *
     * @access public
     * @return void
     */
    public function isUpgradable()
    {
        $sysparameter = ClassRegistry::init('SysParameter');
        $dbv = $sysparameter->get('database.version');
        $sysv = $sysparameter->get('system.version');

        // If system.version doesn't exist, but we get a database version of 4,
        // then that means we're upgrading from a prior iPeer v3 installation
        // and don't need to run this upgrader.
#        if (empty($sysv) && $dbv == 4) {
#            return false;
#        }

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
        if (!is_writable(CONFIGS)) {
            $this->errors[] = sprintf(__('Cannot write to the configuration directory. Please change the permission on %s so that it is writable.', true), CONFIGS);
            return false;
        }

        if (!file_exists(CONFIGS.'guard.php')) {
            if (!copy(APP.DS.'plugins'.DS.'guard'.DS.'config'.DS.'guard_default.php', CONFIGS.'guard.php')) {
                $this->errors[] = __('Cannot copy the guard configuration (app/plugins/guard/config/guard_default.php) to the configuration directory as guard.php.', true);
                return false;
            }
        }

        // when upgrading from v2, will run through all the delta_*.sql files
        // up to $this->dbVersion because the database version wasn't stored in
        // the database, so PHP get a null value back, which is then treated
        // as if we're starting from version 0
        $sysparameter = ClassRegistry::init('SysParameter');
        $dbv = $sysparameter->getDatabaseVersion();
        $ret = $this->patchDb($dbv, $this->dbVersion);
        if ($ret) {
            $this->errors[] = sprintf(__('Database patching failed: %s', true), $ret);
            return false;
        }
        $sysparameter->reload();

        if (!file_exists(TMP.'installed.txt')) {
            $f = fopen(TMP.'installed.txt', 'w');
            if (!$f) {
                $this->errors[] = sprintf(__('Installation failed, unable to write to %s dir', true), CONFIGS);
                return false;
            }
            fclose($f);
        }

        return true;
    }
}
