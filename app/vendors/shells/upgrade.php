<?php
App::import('Component', 'Upgrader');

/**
 * UpgradeShell
 *
 * @uses Shell
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2013 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UpgradeShell extends Shell
{
    public $uses = array('SysParameter');

    /**
     * main
     *
     *
     * @access public
     * @return void
     */
    function main()
    {
        $upgrader = new UpgraderComponent(null);

        if (!$upgrader->isUpgradable()) {
            return;
        }

        $this->out('Upgrading...');

        if ($upgrader->upgrade()) {
            $this->out('Upgrade successful.');
        } else {
            $this->out('Upgrade failed: '.join(';', $upgrader->errors));
        }

        $this->out('Done');
    }
}

