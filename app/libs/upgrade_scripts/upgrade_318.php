<?php
require_once('upgrade_base.php');
/**
 * Upgrade318
 *
 * @uses UpgradeBase
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2015 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.1
 */
 class Upgrade318 extends UpgradeBase
{
	/**
	 * __construct
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->fromVersions = array(null, '3.1.6', '3.1.7');
		$this->toVersion = '3.1.8';
		$this->dbVersion = Configure::read('DATABASE_VERSION');
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