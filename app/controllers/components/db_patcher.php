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
    $mysql = $this->connectDb($dbConfig);

    // apply the delta files
    for($i = $from_version+1; $i <= Configure::read('DATABASE_VERSION'); $i++)
    {
      $file = '../config/sql/delta_'.$i.'.sql';
      if(!(is_readable($file) && true === ($ret = $this->applyDelta($file))))
      {
        mysql_query("ROLLBACK");
        mysql_close($mysql);
        return __('Failed to apply delta file: ', true).$file.'. '.__('Message', true).' = '.$ret;
      }
    }
    $this->disconnectDb($mysql);

    // apply the other changes
    $this->updateDatabaseVersion();

    return true;
  }

  function connectDb($dbConfig)
  {
    if(null == $dbConfig)
    {
      $db = new DATABASE_CONFIG();
      $dbConfig = $db->default;
    }
    $mysql = mysql_connect($dbConfig['host'], $dbConfig['login'], $dbConfig['password']);
    if(!$mysql) {
      $this->set('message_content', __('Could not connect to database!', true));
      $this->render(null, null, 'views/pages/message.tpl.php');
      exit;
    } 

    //Open the database
    $mysqldb = mysql_select_db($dbConfig['database']);
    if (!$mysqldb) {
      $this->set('message_content', __('Could not find database ', true).$dbConfig['database'].'!');
      $this->render(null, null, 'views/pages/message.tpl.php');
      exit;
    }	  
  
    mysql_query('BEGIN');

    return $mysql;
  }

  function disconnectDb($mysql)
  {
    mysql_query("COMMIT");
    mysql_close($mysql);
  }

  function applyDelta($file)
  {
    $fp = fopen( $file, "r" );
    if ( false === $fp ) {
      return __("Could not open ", true).$fname;
    }

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
        //echo $cmd . ";<br /><br /><br />";
        $result = mysql_query($cmd);
        if (!$result)
        {
          $error = __("Cannot run query", true);
          return $error;
        }
        //if ($this->execute($cmd)) {
        //	return false;
        //}
        $cmd = "";
        $done = false;
      }
    }
    fclose( $fp );
    return true;
  }

  function updateDatabaseVersion()
  {
    if(!class_exists('DATABASE_CONFIG'))
    {
      include_once('../config/database.php');
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

