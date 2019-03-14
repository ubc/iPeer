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
class UpgraderComponent extends CakeObject
{
    public $scripts = array();
    public $errors = array();
    protected $controller = null;

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

    /**
     * startup
     *
     * @param mixed $controller
     *
     * @access public
     * @return void
     */
    public function startup($controller)
    {
        $this->controller = $controller;
    }

    /**
     * isUpgradable check if the current instance is upgradable
     *
     * @access public
     * @return bool
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
     * @return bool
     */
    public function upgrade()
    {
        foreach ($this->scripts as $script) {
            if ($script->isUpgradable()) {
                if (!$script->upgrade()) {
                    if ($this->controller != null) {
                        $this->controller->Session->setFlash(join(';', $script->errors));
                    } else {
                        // if no controller (may be called from CLI)
                        $this->errors = array_merge($this->errors, $script->errors);
                    }
                    return false;
                }
            }
        }

        foreach(array('css', 'js', 'views', 'models', 'persistent') as $dir) {
            clearCache(null, $dir, null);
        }

        return true;
    }
}

