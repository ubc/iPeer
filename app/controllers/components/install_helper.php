<?php
/* SVN FILE: $Id$ */
/*
 * InstallHelper Component 
 *
 * @author      gwoo <gwoo@rd11.com>
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */

class InstallHelperComponent
{
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
    }		 
    else if ($dataOption == 'B') {
      //Install database with basic structure
      $dbConfig['filename'] = $basicSQLFile;
      $runQuery = $this->dbSource($dbConfig);
    }

    return $runQuery; 
  }

  /**
   * Reads an SQL template file and executes the queries inside.
   *
   * @param $dbConfig - login configuration for the mysql db and the sql
   * template filename
   * @return false if everything executed successfully, an error message
   * otherwise.
   */
  function dbSource($dbConfig) 
  {
    // Connect to the server
    $mysql = mysql_connect($dbConfig['host'], 
      $dbConfig['login'], 
      $dbConfig['password']);
    if(!$mysql) 
    {
      return('Could not connect: ' . mysql_error());
    } 

    // Create the database if not exists
    $ret = mysql_query("CREATE DATABASE IF NOT EXISTS `".$dbConfig['database'].
      "` CHARACTER SET `utf8` COLLATE `utf8_general_ci`;", $mysql);
    if (!$ret) 
    {
      return 'Error creating database: ' . mysql_error();
    }

    // Open the database
    $mysqldb = mysql_select_db($dbConfig['database']);
    if (!$mysqldb) 
    {
      return 'Could not open the database: '. mysql_error();
    }

    // Read the SQL template file
    $template = file($dbConfig['filename'], FILE_SKIP_EMPTY_LINES);
    if ( false === $template ) 
    {
      return 'Unable to open SQL template "' . $dbConfig['filename'] . '"';
    }

    // Do the queries
    mysql_query('START TRANSACTION');
    $query = ''; // Query variable
    foreach ($template as $line)
    {
      // Skip comments
      if (substr($line, 0, 2) == '--')
        continue;

      // Add this line to the current query
      $query .= $line;
      // Semicolon indicates end of query
      if (substr(trim($line), -1, 1) == ';')
      { // Perform query to queue
        $ret = mysql_query($query);
        if (!$ret)
        { // Query failed, undo everything and cry
          $err = mysql_error();
          mysql_query('ROLLBACK');
          mysql_close($mysql);
          return 'Query failed: ' . $err;
        }
        // Reset query variable to empty
        $query = '';
      }
    }

    // Commit all queries 
    $ret = mysql_query("COMMIT");
    if (!$ret)
    {
      return "Unable to commit queries: " . mysql_error();
    }
    mysql_close($mysql);
    return false;
  }    	
	
  function updateSystemParameters($data) 
  {
    App::import('Model', 'SysParameter');
    $this->SysParameter = new SysParameter;
    $superAdmin = null;

    if (!empty($data)) {
            foreach($data['SysParameter'] as $key => $value){
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
