<?php
require_once('upgrade_base.php');
/**
 * Upgrade332
 *
 * @uses UpgradeBase
 * @package   CTLT.iPeer
 * @author    Clarence Ho
 * @copyright 2018 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.3.2
 */
 class Upgrade332 extends UpgradeBase
{
    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->fromVersions = array(null, '3.3.1');
        $this->toVersion = '3.3.2';
        $this->dbVersion = 15;
    }

    /**
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
        // nothing to patch
        return true;
    }
}
