<?php
/* SVN FILE: $Id$ */
/*
 * dbPatcher Component 
 */
class DbPatcherComponent extends Object
{
  var $controller = true;

  public function patch($from_version, $dbConfig = null)
  {
    $ret = $this->connectDb($dbConfig);
    if ($ret)
    { // Unable to connect
      return $ret;
    }

    // Apply the delta files
    for($i = $from_version+1; $i <= Configure::read('DATABASE_VERSION'); $i++)
    {
      // Check that we can read the delta file 
      $file = CONFIGS.'sql/delta_'.$i.'.sql';
      if (!is_readable($file))
      {
        mysql_close();
        return "Cannot read delta file $file";
      }
      $ret = $this->applyDelta($file);
      if ($ret)
      {
        mysql_close();
        return 'DB Version '. $from_version . ' Failed to apply delta file: '.$file.'. Message = '.$ret;
      }
    }

    // apply the other changes
    $ret = $this->updateDatabaseVersion();
    if ($ret)
    {
      mysql_close();
      return "Database upgrade successful, however, failed to increment database version counter, please do this manually: " . $ret;
    }
    mysql_close();

    return false;
  }

  private function connectDb($dbConfig)
  {
    // Read the database configuration from database.php
    $dbConfig = new DATABASE_CONFIG();
    $dbConfig = $dbConfig->default;

    if(null == $dbConfig)
    {
      $db = new DATABASE_CONFIG();
      $dbConfig = $db->default;
    }
    $mysql = mysql_connect($dbConfig['host'], $dbConfig['login'], $dbConfig['password']);
    if(!$mysql) {
      return 'Could not connect to database!';
    } 

    //Open the database
    $mysqldb = mysql_select_db($dbConfig['database']);
    if (!$mysqldb) {
      return 'Could not find database '.$dbConfig['database'].'!';
    }	  
  
    return false;
  }

  private function applyDelta($file)
  {
    $fp = fopen( $file, "r" );
    if ( false === $fp ) {
      return "Could not open ".$file;
    }
    mysql_query('BEGIN');

    $cmd = "";
    $done = false;

    while ( ! feof( $fp ) ) {
      $line = trim( fgets( $fp, 1024 ) );
      $sl = strlen( $line ) - 1;

      if ( $sl < 0 ) { continue; }
      if ( "-" == $line{0} && "-" == $line{1} ) { continue; }

      if ( ";" == $line{$sl} ) {
        $done = true;
        $line = substr( $line, 0, $sl );
      }

      if ( "" != $cmd ) { $cmd .= " "; }
      $cmd .= $line;

      if ( $done ) {
        $result = mysql_query($cmd);
        if (!$result)
        {
          $err = mysql_error();
          mysql_query("ROLLBACK");
          return "Cannot run query from $file - $cmd - $err";
        }
        $cmd = "";
        $done = false;
      }
    }
    fclose( $fp );
    mysql_query("COMMIT");
    return false;
  }

  private function updateDatabaseVersion()
  {
    // it should be safe to assume that we have a database.version entry
    // since we're starting from version 3
    $ret = mysql_query("update `sys_parameters` set `parameter_value` = ".
      Configure::read('DATABASE_VERSION')." where `parameter_code` = 'database.version';");
    if ($ret == false)
    {
      return mysql_error();
    }
    return false;
  }
}

