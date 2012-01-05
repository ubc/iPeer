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
 * Controller :: Installs
 *
 * Note that the InstallController inherits from Controller instead of
 * AppController. AppController brings in dependencies on the currently
 * non-existing database.
 *
 * @package
 * @subpackage
 * @since
 */
class InstallController extends Controller
{
  var $uses         = null; 
  var $components   = array(
                      'Output', 
                      'Session',
                      'installHelper',
                      'DbPatcher'
                      );
  var $helpers = array('Session','Html','Js');

	
  function __construct()
  {
    $this->set('title_for_layout', __('Install Wizard', true));
    parent::__construct();
  }
		
  /**
   * Check prereqs for installing iPeer
   * */
  function index()
  {    
    if(file_exists(CONFIGS.'installed.txt'))
    {
      $this->Session->setFlash(__('WARNING: It looks like you already have a instance running. Proceed at your own risk. Remove app/config/installed.txt if you do not want to see this warning.', true));
    }
  }
	
  /***
   * Ask the user if they agree to the GPL license.
   * */
  function install2()
  {
  }

  /**
   * Ask the user for how to access the database
   * */
  function install3()
  {
    if(!is_writable(CONFIGS))
    { 
      $this->Session->setFlash(__('The config directory is not writable. Please change the permission on config directory, e.g., "chmod 777 app/config". After installation, change the permission back.', true));
    }
    else if (!is_writable(CONFIGS.'database.php'))
    {
      $this->Session->setFlash(__('The database config file is not writable. Please change the permission on config directory, e.g., "chmod 777 app/config/database.php". After installation, change the permission back.', true));
    }
  }

  /**
   * This is an unrenderable page (no associated ctp file). Its only purpose
   * is to create the database configuration file. Unfortunately, this page
   * is needed because the database patcher in the next step needs to read
   * the database. If the DB patching and writing of the configuration file
   * takes place in the same step, CakePHP does not reread the DB config file,
   * so uses the wrong credentials to try to access the database.
   * */
  function configdb()
  {
    if (empty($this->data))
    { // Can't configure database if no data was entered on the prev page
      $this->Session->setFlash(__('No data entered.',true));
      $this->redirect(array('action' => 'install3'));
      return;
    }

    // Try to configure the database, configureDatabase() will setFlash
    // with error msg so don't need to do it again
    $ret = $this->configureDatabase();
    if ($ret)
    { // Failed to configure database
      $this->Session->setFlash(__('Database config failed - '.$ret, true));
      $this->redirect(array('action' => 'install3'));
      return;
    }

    // Success
    $this->redirect(array('action' => 'install4'));
  }

  /**
   * iPeer configuration page, enter the administrator user's name, password,
   * email, site info, etc.
   * */
  function install4()
  { 
    // Try to patch the database if needed, note that this database patching
    // is only for small changes, not for large version changes
    $ret = $this->patchDB();
    if ($ret)
    {
      $this->Session->setFlash(__('Database patching failed - '.$ret, true));
      $this->redirect(array('action' => 'install3'));
      return;
    }

    // Set variables needed to render the page
    $this->set('absolute_url', str_replace($this->here,'',
      Router::url($this->here, true)));
    $this->set('domain_name', $_SERVER['HTTP_HOST']);
  }	

  /**
   * Installation done page
   * */
  function install5()
  {
    if (empty($this->params['data'])) 
    {
      $this->Session->setFlash(__('No iPeer configuration entered'), true);
      $this->redirect(array('action' => 'install4'));
      return;
    }

    //update parameters
    $username = $this->installHelper->updateSystemParameters($this->params['data']);
    // TODO Replace raw SQL queries with CakePHP Model calls once the database
    // conflicts where both the users and roles_users table stores role data
    // has been fixed
    $this->set('superAdmin', $username);

    //Create Super Admin
    $my_db =& ConnectionManager::getDataSource('default');
    $my_db->query("INSERT INTO `users` (`id`, `role`, `username`, `password`, `first_name`, `last_name`, `student_no`, `title`, `email`, `last_login`, `last_logout`, `last_accessed`, `record_status`, `creator_id`, `created`, `updater_id`, `modified`) 
      VALUES ('1', 'A', '".$username."', '".md5($this->params['data']['Admin']['password'])."', 'Super', 'Admin', NULL, NULL, '".$this->params['data']['SysParameter']['system.admin_email']."', NULL, NULL, NULL, 'A', '0', '".date("Y-m-d H:i:s")."', NULL, NULL)
      ON DUPLICATE KEY UPDATE password = '".md5($this->params['data']['Admin']['password'])."', email = '".$this->params['data']['SysParameter']['system.admin_email']."';");

    //Get Super Admin's id and insert into roles_users table
    $user_id = $my_db->query("SELECT id FROM users WHERE username = '".$username."'");
    $my_db->query("INSERT INTO `roles_users` (`id`, `role_id`, `user_id`, `created`, `modified`)
      VALUES (NULL, '1', '".$user_id[0]['users']['id']."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."');");

    // test if the config directory is still writable by http user
    $this->set('config_writable', $writable = is_writable("../config"));

    $f = fopen(CONFIGS.'installed.txt', 'w');
    if (!$f)
    {
      $this->Session->setFlash(__('Installation failed, unable to write to '. CONFIGS .' dir'), true);
      $this->redirect(array('action' => 'install4'));
      return;
    }
    fclose($f);
  }

  function gpl()
  {
    $this->layout = false;
    $this->render('gpl');
  }

  function manualdoc()
  {
    $this->render('manualdoc');
  }

  private function configureDatabase()
  {
    // We have user credentials for the database, write it to conf file
    $dbConfig = $this->createDBConfigFile();
    if (!$dbConfig)
    { // Writing to config file failed
      return "Unable to write to ".CONFIGS."database.php";
    }

    // Get the data setup option: A-With Sample,B-Basic,C-Import from iPeer 1.6
    $dbConfig['data_setup_option'] = $this->params['form']['data_setup_option'];
    $ret = $this->installHelper->runInsertDataStructure($dbConfig, $this->params);

    //Found error
    if ($ret)
    {
      return 'Create Database Failed - ' . $ret;
    }

    return false;
  }

  private function createDBConfigFile() 
  {
		// Workaround for Windows portability
    $endl = (substr(PHP_OS,0,3)=='WIN')? "\r\n" : "\n"; 
		
		//create and write file 
    $confile = fopen(CONFIGS.'database.php', 'wb');
    if (!$confile)
    {
      return false;
    }
    $dbConfig = array();
    //Setup the database config parameters
    $dbConfig = $this->params['data']['DBConfig'];

    //Write Config file
    fwrite($confile, "<?php" . $endl);
    fwrite($confile, "class DATABASE_CONFIG {".$endl);
    fwrite($confile, "var \$default = array(".$endl);
    foreach($dbConfig as $k => $v)
    {
      fwrite($confile, "                     '".$k."'   => '".$v."',".$endl);
    }
    fwrite($confile,"                     'prefix'   => '');  }".$endl);
    fwrite($confile,"?>" . $endl);
    fflush($confile);
    fclose($confile);
    return $dbConfig;
  }

  /**
   * Our base SQL file might not have the most recent database changes.
   * Updates a newly created database from the base to the current version.
   * */
  private function patchDB()
  {
    // There is an intermittent error with importing sysContainer
    // so instead of using that to get the database.version
    // we have to use this workaround
    $my_db =& ConnectionManager::getDataSource('default');    
    $ret = $my_db->query("SELECT `parameter_value` FROM `sys_parameters` WHERE `parameter_code` = 'database.version';");
    if ($ret == false)
    { // Unable to retrieve the db version
      return "Unable to retrieve the DB version, please try again.";
    }
    $dbv = $ret[0]['sys_parameters']['parameter_value'];

    // Run the patcher
    $ret = $this->DbPatcher->patch($dbv);
    if ($ret)
    { // Patcher failed
      return $ret;
    }

    return false;
  }
}

?>
