<?php
/* SVN FILE: $Id$ */
/*
 * dbPatcher Component 
 */
class DbPatcherComponent extends Object
{
  var $controller = true;

  function patch($from_version, $dbConfig = null)
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
        return 'Failed to apply delta file: '.$file.'. Message = '.$ret;
      }
    }
    mysql_close();

    // apply the other changes
    $this->updateDatabaseVersion();

    return false;
  }

  function connectDb($dbConfig)
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

  function applyDelta($file)
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
          mysql_query("ROLLBACK");
          return "Cannot run query from $file - $cmd";
        }
        $cmd = "";
        $done = false;
      }
    }
    fclose( $fp );
    mysql_query("COMMIT");
    return false;
  }

  function updateDatabaseVersion()
  {
    if(!class_exists('DATABASE_CONFIG'))
    {
      include_once(CONFIGS.'database.php');
    }

    // update database version
    $sysParam = new SysParameter();
    if($dbv = $sysParam->find("parameter_code = 'database.version'"))
    {
      $sysParam->id = $dbv['SysParameter']['id'];
      $sysParam->saveField('parameter_value', Configure::read('DATABASE_VERSION'));
    }else{
      $dbv = array('SysParameter' => array('parameter_code' => 'database.version',
                                           'parameter_value'=> Configure::read('DATABASE_VERSION'),
                                           'parameter_type' => 'I',
                                           'description'    => 'database version',
                                           'record_status'  => 'A',
                                           'creator_id'     => 0,
                                           'created'        => date('Y-m-d H:i:s'),
                                           'updater_id'     => 0
                                           ));
      $sysParam->save($dbv);
    }
  }
}

