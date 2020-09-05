<?php
require_once('upgrade_base.php');
/**
 * Upgrade346
 *
 * @uses UpgradeBase
 * @package   CTLT.iPeer
 * @author    Pan Luo
 * @copyright 2020 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.4.6
 */
 class Upgrade346 extends UpgradeBase
{
    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->fromVersions = array(null, '3.4.4', '3.4.5');
        $this->toVersion = '3.4.6';
        $this->dbVersion = 17;
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
        return true;
    }
}
