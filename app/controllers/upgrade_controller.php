<?php
/* SVN FILE: $Id$ */

/**
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Upgrade
 *
 * @package
 * @subpackage
 * @since
 */
class UpgradeController extends Controller
{
	var $Sanitize;
  var $uses         = array(); 
	var $components   = array('Output', 
                            'framework',
                            'Session',
                            'rdAuth'
                            );
	var $beforeFilter =	 array('preExecute');

  function preExecute()
  {
 		$this->rdAuth->loadFromSession();
    $this->set('rdAuth',$this->rdAuth);
  }

  function index()
  {
    $this->checkPermission();
    $this->set('message_content', 'You are about to upgrade your iPeer instance. Please make sure you have backed up your database and files before proceeding!<br /><a href="/upgrade/step2">Confirm</a>');
    $this->render(null, null, 'views/pages/message.tpl.php');
  }

  function step2()
  {
    $this->checkPermission();
    // apply the delta files
    $mysql = $this->connectDb();
    $dbv = $this->sysContainer->getParamByParamCode('database.version', array('parameter_value' => 0));

    for($i = $dbv['parameter_value']+1; $i <= DATABASE_VERSION; $i++)
    {
      $file = '../config/sql/delta_'.$i.'.sql';
      if(is_readable($file))
      {
        /*if(true !== ($ret = $this->applyDelta($file)))
        {
          $this->set('message_content', 'Failed to apply delta file: '.$file.'. Message = '.$ret);
          $this->render(null, null, 'views/pages/message.tpl.php');
          return;
        }*/
      }else{
        mysql_query("ROLLBACK");
        mysql_close($mysql);
        $this->set('message_content', 'The file '.$file.' is missing. Please make sure you have a complete copy of iPeer.');
        $this->render(null, null, 'views/pages/message.tpl.php');
        return;
      }
    }
    $this->disconnectDb($mysql);

    // apply the other changes

    // update database version
    $sysParam = new SysParameter();
    if($dbv = $sysParam->findByParameterCode('database.version1'))
    {
      $sysParam->id = $dbv['SysParameter']['id'];
      $sysParam->saveField('parameter_code', DATABASE_VERSION);
    }else{
      $dbv = array('SysParameter' => array('parameter_code' => 'database.version',
                                           'parameter_value'=> DATABASE_VERSION,
                                           'parameter_type' => 'I',
                                           'description'    => 'database version',
                                           'record_status'  => 'A',
                                           'creator_id'     => 0,
                                           'created'        => 'NOW()',
                                           'updater_id'     => 0,
                                           'modified'       => 'NOW()'
                                           ));
      $sysParam->save($dbv);
    }

    // logout the user
		$this->rdAuth->logout();
		$this->Session->del('URL');
		$this->Session->del('AccessErr');
		$this->Session->del('Message');
		$this->Session->del('CWLErr');

    $this->set('message_content', 'Your iPeer instance has been upgraded. Please login again.<br /><a href="/loginout/login">Login</a>');
    $this->render(null, null, 'views/pages/message.tpl.php');
  }

  function connectDb()
  {
    $db = new DATABASE_CONFIG();
    $dbConfig = $db->default;
    $mysql = mysql_connect($dbConfig['host'], $dbConfig['login'], $dbConfig['password']);
    if(!$mysql) {
      $this->set('message_content', 'Could not connect to database!');
      $this->render(null, null, 'views/pages/message.tpl.php');
      exit;
    } 

    //Open the database
    $mysqldb = mysql_select_db($dbConfig['database']);
    if (!$mysqldb) {
      $this->set('message_content', 'Could not find database '.$dbConfig['database'].'!');
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
      return "Could not open ".$fname;
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
        echo $cmd . ";<br /><br /><br />";
        $result = mysql_query($cmd);
        if (!$result)
        {
          $error = "Cannot run query";
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

  function checkPermission()
  {
    if('A' != $this->rdAuth->role)
    {
      $this->set('message_content', 'Sorry, you do not have access to this page. Only administrator can perform a upgrade. If you are an administrator, please login and then go to this page to perform the upgrade.');
      $this->render(null, null, 'views/pages/message.tpl.php');
      exit;
    }
  }
}
