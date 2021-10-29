<?php
require_once('upgrade_base.php');
/**
 * Upgrade310
 *
 * @uses UpgradeBase
 * @package   CTLT.iPeer
 * @author    Michael Tang <michael.tang@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.1
 */
class UpgradeDefault extends UpgradeBase
{
	/**
	 * __construct
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->dbVersion = Configure::read('DATABASE_VERSION');
	}

	/**
	 *
	 * @access public
	 * @return void
	 */
	public function isUpgradable()
	{
		$sysparameter = ClassRegistry::init('SysParameter');
		$sysv = $sysparameter->get('system.version');
		return empty($sysv) || version_compare(IPEER_VERSION,$sysv) > 0;
	}

	/**
	 * up
	 *
	 * @access public
	 * @return boolean
	 */
	public function up()
	{
		$this->toVersion = IPEER_VERSION;

		return true;
	}
}
