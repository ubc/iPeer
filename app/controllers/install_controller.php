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
 * @package
 * @subpackage
 * @since
 */
class InstallController extends Controller
{
	var $Sanitize;
  var $uses         = null; 
	var $components   = array('Output', 
                            'framework',
                            'Session',
                            'installHelper',
                            'DbPatcher');

	
	function __construct()
	{
		$this->Sanitize = new Sanitize;
 		$this->set('title_for_layout', __('Install Wizards', true));
		parent::__construct();
	}
		
  function checkDatabaseFile()
  {
    return file_exists('../config/database.php');
  }

	function index()
	{
    $this->Session->write('progress', 'index');
	  $this->autoRender = false;
    if(file_exists('../config/database.php'))
    {
      $this->set('message_content', __('It looks like you already have a instance running. Please install a fresh copy or remove app/config/database.php.', true));
      $this->render(null, null, 'views/pages/message.tpl.php');
    }else{
      return $this->render('install');
    }
	}
	
	function install2()
	{
    if(!$this->Session->check('progress') || 'index' != $this->Session->read('progress'))
    {
      $this->set('message_content', __('You seems to miss some steps. Please start the installation from beginning.', true));
      $this->render(null, null, 'views/pages/message.tpl.php');
    }
    $this->Session->write('progress', 'install2');
	}

	function install3()
	{
    if(!$this->Session->check('progress') || ('install2' != $this->Session->read('progress') && 'install3' != $this->Session->read('progress')))
    {
      $this->render('wrongstep');
    }
    $this->Session->write('progress', 'install3');

    $writable = is_writable("../config");
  
    if(!$writable) $this->set('errmsg', __('"app/config" is not writable. Please check the permission on config directory, e.g., chmod 777 app/config. After installation, please change the permission back.', true));

    if (!empty($this->params['data'])) 
    {
      //setup parameter
      $dbConfig = $this->__createDBConfigFile();
			
			//Retain the data setup option: A - With Sample,  B - Basic, C - Import from iPeer 1.6
			$dbConfig['data_setup_option'] = $this->params['form']['data_setup_option'];
			$insertDataStructure = $this->installHelper->runInsertDataStructure($dbConfig, $this->params);
			
      //Found error
      if (!($dbConfig && $insertDataStructure))
      {
        $this->set('data', $this->params['data']);
        $this->set('errmsg', __('Create Database Configuration Failed', true));
        $this->render('install3');
      }

      // apply the patches
      $dbv = $this->sysContainer->getParamByParamCode('database.version', array('parameter_value' => 0));

      // patch the database
      if(true !== ($ret = $this->DbPatcher->patch($dbv['parameter_value'], $dbConfig)))
      {
        $this->set('message_content', $ret);
        $this->render(null, null, 'views/pages/message.tpl.php');
        exit;
      }
      
      $this->set('data', array());
      $this->redirect('install/install4');  
		}	  
	}

	function install4()
	{
	  $this->autoRender = false;

    if(!$this->Session->check('progress') || ('install3' != $this->Session->read('progress') && 'install4' != $this->Session->read('progress')))
    {
      $this->render('wrongstep');
    }
    $this->Session->write('progress', 'install4');

		if (empty($this->params['data'])) {
      //render default
      $this->render();
  	}else{
      //update parameters
      $username = $this->installHelper->updateSystemParameters($this->params['data']);
      if (!empty($username)) {
				$this->set('superAdmin', $username);

        //create super admin
        $admin = array('User' => array('role'           => 'A',
                                       'username'       => $username,
                                       'password'       => $this->params['data']['Admin']['password'],
                                       'first_name'     => 'Super',
                                       'last_name'      => 'Admin',
                                       'email'          => $this->params['data']['SysParameter']['system.admin_email'],
                                       'record_status'  => 'A',
                                       'creator_id'     => 0));
        $user = new User();
        $user->save($admin); 

        // test if the config directory is still writable by http user
        $this->set('config_writable', $writable = is_writable("../config"));

				$this->render('install5');  
			}	else {
        //Found error
        $this->set('data', $this->params['data']);
        $this->set('errmsg', __('Configuration of iPeer System Parameters Failed.', true));
        $this->render('install4');
      }//end if
		}	  
	}	

  function __createDBConfigFile() 
  {
		//End of line based on OS platform
    $endl = (substr(PHP_OS,0,3)=='WIN')? "\r\n" : "\n"; 
		$dbDriver = '';
		$dbConnect = '';
    $hostName ='';
		$dbUser = '';
		$dbPassword = '';
		$dbName = '';
		$dbConfig = array();
		
		//create and write file 
    if(!$confile = fopen("../config/database.php", "wb")) 
    {
        $errMsg= __("Error creating ../config/database.php; check your permissions<br />", true) ;
        $this->set('errmsg', $errMsg);
        return false;
    }else{
     	if (!empty($this->params['data'])) {
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
      } else {
			  return false; 
			}
			
    }  
    return $dbConfig;
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
}

?>
