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
            $sysparameter->setValue('system.version', $this->toVersion);
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
     * @param array $dbConfig    database config
     *
     * @access public
     * @return void
     */
    public function patchDb($fromVersion, $toVersion = null, $dbConfig = null)
    {
        $ret = $this->connectDb($dbConfig);
        if ($ret) {
            // Unable to connect
           return $ret;
        }

        $toVersion = $toVersion == null ? Configure::read('DATABASE_VERSION') : $toVersion;

        // Apply the delta files
        for ($i = $fromVersion+1; $i <= $toVersion; $i++) {
            // Check that we can read the delta file
            $file = CONFIGS.'sql/delta_'.$i.'.sql';
            if (!is_readable($file)) {
                mysql_close();
                return "Cannot read delta file $file";
            }
            $ret = $this->applyDelta($file);
            if ($ret) {
                mysql_close();
                return 'DB Version '. $fromVersion . ' Failed to apply delta file: '.$file.'. Message = '.$ret;
            }
        }
        mysql_close();

        return false;
    }


    /**
     * connectDb
     *
     * @param mixed $dbConfig
     *
     * @access protected
     * @return void
     */
    protected function connectDb($dbConfig)
    {
        // Read the database configuration from database.php
        $dbConfig = new DATABASE_CONFIG();
        $dbConfig = $dbConfig->default;

        if (null == $dbConfig) {
            $db = new DATABASE_CONFIG();
            $dbConfig = $db->default;
        }
        $mysql = mysql_connect($dbConfig['host'], $dbConfig['login'], $dbConfig['password']);
        if (!$mysql) {
            return 'Could not connect to database!';
        }

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
}
