<?php
/* SVN FILE: $Id: install_controller.php,v 1.3 2006/08/11 17:22:15 rrsantos Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.3 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/08/11 17:22:15 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Installs
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class InstallController extends AppController
{
  //var $uses =  array('SysParameter');
  var $uses = array();
	var $Sanitize;
	var $superAdmin = '';
	var $components = array('rdAuth','Output','sysContainer', 'globalConstant', 'userPersonalize', 'framework',
	                        'installHelper');

	
	function __construct()
	{
		$this->Sanitize = &new Sanitize;
 		$this->pageTitle = 'Install Wizards';		
		parent::__construct();
	}
		
	function index()
	{
	  $this->autoRender = false;
    $this->render('install');
	}
	
	function install2()
	{
	}

	function install3()
	{
		if (empty($this->params['data'])) {
      //render default
  	}
		else {
      //setup parameter
      $dbConfig = $this->__createDBConfigFile();
			
			//Retain the data setup option: A - With Sample,  B - Basic, C - Import from iPeer 1.6
			$dbConfig['data_setup_option'] = $this->params['form']['data_setup_option'];
			$insertDataStructure = $this->installHelper->runInsertDataStructure($dbConfig, $this->params);
			
      //Save Data
			//if ($this->SysParameter->save($this->params['data'])) {
      if ($dbConfig && $insertDataStructure) {
				$this->params['data'] = array(); 
				$this->set('data', $this->params['data']);
				$this->redirect('install/install4');  
			}	
      //Found error
      else {
        $this->set('data', $this->params['data']);
        $this->set('errmsg', 'Create Database Configuration Failed');
        $this->render('install3');
      
      }//end if
		}	  
	}

	function install4()
	{
	  $this->autoRender = false;
		if (empty($this->params['data'])) {
      //render default
      $this->render();
  	}
		else {
      //update parameters
      $this->superAdmin = $this->installHelper->updateSystemParameters($this->params['data']);
      
      if (!empty($this->superAdmin)) {
				$this->set('superAdmin', $this->superAdmin);
				$this->render('install5');  
			}	
      //Found error
      else {
        $this->set('data', $this->params['data']);
        $this->set('errmsg', 'Configuration of iPeer System Parameters Failed.');
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
        $errMsg= "Error creating ../config/database.php; check your permissions<br />" ;
        $this->set('errmsg', $errMsg);
        return false;
    }
    if($confile){
     	if (!empty($this->params['data'])) {
				//Setup the database config parameters
				foreach($this->params['data']['DBConfig'] as $key => $value){
					switch ($key) {
						case 'driver':
							 $dbDriver = $value;
							 $dbConfig['db_driver'] = $dbDriver;
							 break;
						case 'connect':
							 $dbConnect = $value;
							 $dbConfig['db_connect'] = $dbConnect;
							 break;
						case 'host_name':
							 $hostName = $value;
							 $dbConfig['host_name'] = $hostName;
							 break;
						case 'db_user':
							 $dbUser = $value;
							 $dbConfig['db_user'] = $dbUser;
							 break;
						case 'db_password':
							 $dbPassword = $value;
							 $dbConfig['db_password'] = $dbPassword;
							 break;
						case 'db_name':
							 $dbName = $value;
							 $dbConfig['db_name'] = $dbName;
							 break;
					}					
				}
				//Write Config file
				fwrite($confile,"<?php" . $endl);
				fwrite($confile,"class DATABASE_CONFIG {".$endl);
    		fwrite($confile,"var \$default = array('driver'   => '".$dbDriver."',".$endl);
        fwrite($confile,"                     'connect'  => '".$dbConnect."',".$endl);
        fwrite($confile,"                     'host'     => '".$hostName."',".$endl);
        fwrite($confile,"                     'login'    => '".$dbUser."',".$endl);
        fwrite($confile,"                     'password' => '".$dbPassword."',".$endl);
        fwrite($confile,"                     'database' => '".$dbName."',".$endl);
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