<?php
require_once('upgrade_base.php');
/**
 * Upgrade310
 *
 * @uses UpgradeBase
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.0
 */
class Upgrade310 extends UpgradeBase
{
    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->fromVersions = array(null, '3.0.0');
        // determines whether the upgrader will run
        $this->toVersion = '3.1.0';
        // determines which of the delta_*.sql files will be applied
        $this->dbVersion = Configure::read('DATABASE_VERSION');
    }

    /**
     * isUpgradable
     *
     * @access public
     * @return void
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
        // apply the delta_*.sql files
        $sysparameter = ClassRegistry::init('SysParameter');
        $dbv = $sysparameter->get('database.version');
        $sysv = $sysparameter->get('system.version');
        
        // apply additional delta files depending on which iPeer version we're
        // upgrading from
        $vDepDeltaFiles = array(); // version dependent delta file
        // If Upgrading from iPeer v2
        //
        // unfortunately, the previous upgrade script forgot to set the database
        // version during upgrade, but it did update the version number, so
        // we're going to guess that we're coming from the previous version if
        // we have 3.0.0 system version and empty for the db version.
        if ($sysv == "3.0.0" && $dbv == '') {
            // should be safe to assume that we're on db version 5
            array_push($vDepDeltaFiles, CONFIGS.'sql/delta_6a.sql');
            $dbv = 5;
        }
        // If upgrading from a prior iPeer v3 installation
        // 
        // apparently, we forgot to add the system.version entry in v3, so...
        // and also forgot to increment the database version...
        // It should be safe to assume these characteristics means we're 
        // upgrading from v3.0.x
        else if (empty($sysv) && $dbv == 4) {
            array_push($vDepDeltaFiles, CONFIGS.'sql/delta_6b.sql');
            $dbv = 5;
        }

        $ret = $this->patchDb($dbv, $this->dbVersion, $vDepDeltaFiles);
        if ($ret) {
            $this->errors[] = sprintf(__('Database patching failed: %s', true), $ret);
            return false;
        }
        $sysparameter->reload();

        return true;
    }
}
