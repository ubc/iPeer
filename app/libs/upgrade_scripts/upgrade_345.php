<?php
require_once('upgrade_base.php');

/**
 * Upgrade345
 *
 * @uses UpgradeBase
 * @package   CTLT.iPeer
 * @author    Steven Marshall
 * @copyright 2020 All rights reserved.
 * @license   PHP Version 3.01 {@link https://www.php.net/license/3_01.txt}
 * @version   Release: 3.4.5
 */
 class Upgrade345 extends UpgradeBase
{
    public function __construct()
    {
        $this->fromVersions = array(null, '3.4.0', '3.4.1', '3.4.2', '3.4.3', '3.4.4');
        $this->toVersion = '3.4.5';
        $this->dbVersion = Configure::read('DATABASE_VERSION');
    }

    /**
     * up
     *
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