<?php
/**
 * UpgraderComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UpgraderComponent extends Object
{
    public $scripts = array();

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $scriptNames = glob(APP.DS.'libs'.DS.'upgrade_scripts/*.php');
        sort($scriptNames);
        foreach ($scriptNames as $script) {
            if (basename($script) == 'upgrade_base.php') {
                continue;
            }
            require_once($script);
            $name = Inflector::classify(basename($script, '.php'));
            $this->scripts[] = new $name();
        }
    }

    public function startup($controller)
    {
        $this->controller = $controller;
    }

    /**
     * isUpgradable check if the current instance is upgradable
     *
     * @access public
     * @return void
     */
    public function isUpgradable()
    {
        foreach ($this->scripts as $script) {
            if ($script->isUpgradable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * upgrade
     *
     * @access public
     * @return void
     */
    public function upgrade()
    {
        foreach ($this->scripts as $script) {
            if ($script->isUpgradable()) {
                if (!$script->upgrade()) {
                    $this->controller->Session->setFlash(join(';', $script->errors));
                    return false;
                }
            }
        }

        return true;
    }
}

