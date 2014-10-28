<?php
/**
 * UpgradeBase
 *
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.0
 */

// mysql_set_charset is only available PHP 5 >= 5.2.3
if (!function_exists('mysql_set_charset')) {
    function mysql_set_charset($charset,$dbh)
    {
        return mysql_query("set names $charset",$dbh);
    }
}

class UpgradeBase
{
    public $errors = array();
    public $currentVersion;
    public $fromVersions;
    public $toVersion;
    public $dbVersion;
    /**
     * isUpgradable
     *
     * @access public
     * @return void
     */
    public function isUpgradable()
    {
        $sysparameter = ClassRegistry::init('SysParameter');
        $this->currentVersion = $sysparameter->get('system.version');
        return in_array($this->currentVersion, $this->fromVersions);
    }

    /**
     * up the actually upgrade function
     *
     * @access public
     * @return void
     */
    public function up()
    {
        trigger_error(__('You need to implemented up() function in the upgrade script.', true));
    }

    /**
     * down the actually downgrade function
     *
     * @access public
     * @return void
     */
    public function down()
    {
        trigger_error(__('You need to implemented down() function in the upgrade script.', true));
    }

    /**
     * upgrade
     *
     * @access public
     * @return void
     */
    public function upgrade() {
        if ($this->up()) {
            $sysparameter = ClassRegistry::init('SysParameter');
            // note that upgrade_300 will run this but it won't do anything
            // because there's no pre-existing database.version entry,
            // upgrade_310 should properly add this back
            $sysparameter->setValue('database.version', $this->dbVersion);
            $sysparameter->setValue('system.absolute_url', Router::url('/', true));
            $this->refreshCache();
        } else {
            return false;
        }

        return true;
    }

    /**
     * patchDb
     *
     * @param int   $fromVersion from version
     * @param int   $toVersion   to version
     * @param array $additionalDeltas additional delta files to be applied after
     *              the major patches.
     *
     * @access public
     * @return void
     */
    public function patchDb($fromVersion, $toVersion = null,
        $additionalDeltas = array())
    {
        $ret = $this->connectDb();
        if ($ret) {
            // Unable to connect
           return $ret;
        }

        $toVersion = $toVersion == null ? Configure::read('DATABASE_VERSION') : $toVersion;

        $deltaFiles = array();
        for ($i = $fromVersion + 1; $i <= $toVersion; $i++) {
            array_push($deltaFiles, CONFIGS.'sql/delta_'.$i.'.sql');
        }
        $deltaFiles = array_merge($deltaFiles, $additionalDeltas);

        // Apply the delta files
        foreach ($deltaFiles as $file) {
            // Check that we can read the delta file
            if (!is_readable($file)) {
                mysql_close();
                return "Cannot read delta file $file";
            }
            $ret = $this->applyDelta($file);
            if ($ret) {
                mysql_close();
                return 'Failed to apply delta file: '.$file.'. Message = '.$ret;
            }
        }
        mysql_close();

        return false;
    }


    /**
     * connectDb
     *
     * @access protected
     * @return void
     */
    protected function connectDb()
    {
        // Read the database configuration from database.php
        $dbConfig = new DATABASE_CONFIG();
        $dbConfig = $dbConfig->default;

        $mysql = mysql_connect($dbConfig['host'], $dbConfig['login'], $dbConfig['password']);
        if (!$mysql) {
            return 'Could not connect to database!';
        }

        mysql_set_charset('utf8', $mysql);

        //Open the database
        $mysqldb = mysql_select_db($dbConfig['database']);
        if (!$mysqldb) {
            return 'Could not find database '.$dbConfig['database'].'!';
        }

        return false;
    }


    /**
     * applyDelta
     *
     * @param mixed $file
     *
     * @access protected
     * @return void
     */
    protected function applyDelta($file)
    {
        $fp = fopen($file, "r");
        if (false === $fp) {
            return "Could not open ".$file;
        }
        mysql_query('BEGIN');

        $cmd = "";
        $done = false;

        while (!feof($fp)) {
            $line = trim(fgets($fp));
            $sl = strlen($line) - 1;

            if ($sl < 0) {
                continue;
            }
            if ("-" == $line{0} && "-" == $line{1}) {
                continue;
            }

            if (";" == $line{$sl}) {
                $done = true;
                $line = substr($line, 0, $sl);
            }

            if ("" != $cmd) {
                $cmd .= " ";
            }
            $cmd .= $line;

            if ($done) {
                $result = mysql_query($cmd);
                if (!$result) {
                    $err = mysql_error();
                    mysql_query("ROLLBACK");
                    return "Cannot run query from $file - $cmd - $err";
                }
                $cmd = "";
                $done = false;
            }
        }
        fclose($fp);
        // update database version
        mysql_query('UPDATE `sys_parameters` SET `parameter_value` = '.$this->dbVersion.' Where `parameter_code` = "database.version";');
        mysql_query("COMMIT");
        return false;
    }

    /**
     * refreshCache
     *
     * @access protected
     * @return void
     */
    protected function refreshCache()
    {
        // refresh cache directory
        exec('rm -rf '.dirname(__FILE__).'/../../../app/tmp/cache/cake*');
        exec('rm -rf '.dirname(__FILE__).'/../../../app/tmp/persistent/cake*');
    }
}
