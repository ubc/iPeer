<?php
/**
 * InstallHelperComponent
 *
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class InstallHelperComponent
{
    /**
     * runInsertDataStructure
     *
     * @param mixed $dbConfig
     *
     * @access public
     * @return void
     */
    function runInsertDataStructure($dbConfig)
    {
        $basicSQLFile = CONFIGS . "sql/ipeer.sql";
        $samplesFile = CONFIGS . "sql/ipeer_samples_data.sql";

        //Install database with sample data
        $dataOption = $dbConfig['data_setup_option'];
        if ($dataOption == 'A') {
            //Install database with sample data structure
            $dbConfig['filename'] = $samplesFile;
            $runQuery = $this->dbSource($dbConfig);
        } else if ($dataOption == 'B') {
            //Install database with basic structure
            $dbConfig['filename'] = $basicSQLFile;
            $runQuery = $this->dbSource($dbConfig);
        } else {
            return "Invalid database data setup option.";
        }

        return $runQuery;
    }


    /**
     * Reads an SQL template file and executes the queries inside.
     *
     * @param array $dbConfig - login configuration for the mysql db and the sql
     * template filename
     *
     * @return false if everything executed successfully, an error message
     * otherwise.
     */
    function dbSource($dbConfig)
    {
        // Connect to the server
        $mysql = mysqli_connect($dbConfig['host'],
            $dbConfig['login'],
            $dbConfig['password']);
        if (!$mysql) {
            return('Could not connect to database');
        }

        // Create the database if not exists
        $ret = mysqli_query($mysql, "CREATE DATABASE IF NOT EXISTS `".$dbConfig['database'].
            "` CHARACTER SET `utf8` COLLATE `utf8_general_ci`;");
        if (!$ret) {
            return 'Error creating database: ' . mysqli_error($mysql);
        }

        // Open the database
        $mysqldb = mysqli_select_db($mysql, $dbConfig['database']);
        if (!$mysqldb) {
            return 'Could not open the database: '. mysqli_error($mysql);
        }

        // Read the SQL template file
        $template = file($dbConfig['filename'], FILE_SKIP_EMPTY_LINES);
        if (false === $template ) {
            return 'Unable to open SQL template "' . $dbConfig['filename'] . '"';
        }

        // Do the queries
        mysqli_query($mysql, 'START TRANSACTION');
        $query = ''; // Query variable
        foreach ($template as $line) {
            // Skip comments
            if (substr($line, 0, 2) == '--') {
                continue;
            }

            // Add this line to the current query
            $query .= $line;
            // Semicolon indicates end of query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform query to queue
                $ret = mysqli_query($mysql, $query);
                if (!$ret) {
                    // Query failed, undo everything and cry
                    $err = mysqli_error($mysql);
                    mysqli_query($mysql, 'ROLLBACK');
                    mysqli_close($mysql);
                    return 'Query failed: ' . $err;
                }
                // Reset query variable to empty
                $query = '';
            }
        }

        // Commit all queries
        $ret = mysqli_query($mysql, "COMMIT");
        if (!$ret) {
            return "Unable to commit queries: " . mysql_error();
        }
        mysqli_close($mysql);
        return false;
    }

    /**
     * updateSystemParameters
     *
     * @param mixed $data
     *
     * @access public
     * @return void
     */
    function updateSystemParameters($data)
    {
        App::import('Model', 'SysParameter');
        $this->SysParameter = new SysParameter;
        $superAdmin = null;

        if (!empty($data)) {
            foreach ($data['SysParameter'] as $key => $value) {
                $tmpSysParam = $this->SysParameter->findParameter($key);
                $tmpSysParam['SysParameter']['parameter_value'] = $value;
                $this->SysParameter->save($tmpSysParam);

                if ($key == 'system.super_admin') {
                    $superAdmin = $value;
                }
            }
        }
        return $superAdmin;
    }
}
