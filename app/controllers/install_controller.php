<?php
/**
 * InstallController
 * Note that the InstallController inherits from Controller instead of
 * AppController. AppController brings in dependencies on the currently
 * non-existing database.
 *
 * @uses Controller
 * @package   CTLT.iPeer
 * @author    John Hsu <john.hsu@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class InstallController extends Controller
{
    public $uses         = array(
        'InstallValidationStep3',
        'InstallValidationStep4'
    );
    public $components   = array(
        'Session',
        'installHelper',
        'Upgrader'
    );
    public $helpers = array('Session', 'Html', 'Js');
    public $layout = 'installer';


    /**
     * __construct
     *
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * beforeFilter function called before filter
     *
     * @access public
     * @return void
     */
    public function beforeFilter()
    {
        $this->set('title_for_layout', __('Installation Wizard',true));

        $timezone = ini_get('date.timezone') ? ini_get('date.timezone') : 'UTC';
        date_default_timezone_set($timezone); // set the default time zone
    }

    /**
     * Check prereqs for installing iPeer
     * */
    function index()
    {
        if (IS_INSTALLED) {
            $this->Session->setFlash(__('WARNING: It looks like you already have a instance running. Please remove the existing database tables before proceeding.', true));
        }
    }

    /**
     * install2
     * Ask the user if they agree to the GPL license.
     *
     *
     * @access public
     * @return void
     */
    function install2()
    {
    }


    /**
     * install3
     * Ask the user for how to access the database
     *
     *
     * @access public
     * @return void
     */
    function install3()
    {
        /*if ($this->data['InstallValidationStep3']['data_setup_option'] == 'C') {
            $this->redirect(array('controller' => 'upgrade'));
            return;
        }

        if (!DB_PREDEFINED && !is_writable(CONFIGS.'database.php')) {
            $this->Session->setFlash(sprintf(__('Cannot write to the database configuration file. Please change the permission on %s so that it is writable.', true), CONFIGS.'/database.php'));
        }*/


        if ($this->data) {
            // we have data submitted
            $this->InstallValidationStep3->set($this->data);
            if (!$this->InstallValidationStep3->validates()) {
                // fails validation
                $this->Session->setFlash(__('Please fill in all fields.', true));
                return;
            }

            // Try to configure the database, configureDatabase() will setFlash
            // with error msg so don't need to do it again
            $this->Session->write('data_setup_option', $this->data['InstallValidationStep3']['data_setup_option']);

            // Success
            $this->redirect(array('action' => 'install4'));
        }
    }


    /**
     * iPeer configuration page, enter the administrator user's name, password,
     * email, site info, etc.
     * */
    function install4()
    {
        if (!is_writable(TMP)) {
            $this->Session->setFlash(sprintf(__('Cannot write to the configuration directory. Please change the permission on %s so that it is writable.', true), TMP));
            return;
        }

        if (!file_exists(CONFIGS.'guard.php')) {
            if (!copy(APP.DS.'plugins'.DS.'guard'.DS.'config'.DS.'guard_default.php', CONFIGS.'guard.php')) {
                $this->Session->setFlash(__('Cannot copy the guard configuration (app/plugins/guard/config/guard_default.php) to the configuration directory as guard.php.', true));
                return;
            }
        }

        $timezones = DateTimeZone::listIdentifiers();
        $this->set('timezones', array_combine($timezones, $timezones));

        if ($this->data) {
            // we have data submitted
            $this->InstallValidationStep4->set($this->data);
            if (!$this->InstallValidationStep4->validates()) {
                // fails validation
                $this->Session->setFlash(__('Please fill in all fields.', true));
                return;
            }

            // validation successful

            // create tables
            $ret = $this->configureDatabase($this->Session->read('data_setup_option'));
            if ($ret) {
                // Failed to configure database
                $this->Session->setFlash(sprintf(__('Database config failed - %s', true), $ret));
                return;
            }

            // force cake to reload the source list (tables) from database
            // so that the query below will have most up-to-date tables
            $db = ConnectionManager::getDataSource('default');
            $db->cacheSources = false;

            // Try to patch the database if needed, note that this database patching
            // is only for small changes, not for large version changes
            $ret = $this->Upgrader->upgrade();
            if (!$ret) {
                $this->Session->setFlash(__('Database patching failed - '.$ret, true));
                $this->redirect(array('action' => 'install4'));
                return;
            }

            $this->createSuperAdmin(
                $this->data['InstallValidationStep4']['super_admin'],
                $this->data['InstallValidationStep4']['password'],
                $this->data['InstallValidationStep4']['admin_email']
            );

            //update parameters
            $sysparams = array(
                'SysParameter' => array(
                    'system.super_admin' => $this->data['InstallValidationStep4']['super_admin'],
                    'system.admin_email' => $this->data['InstallValidationStep4']['admin_email'],
                    'email.host' => $this->data['InstallValidationStep4']['email_host'],
                    'email.port' => $this->data['InstallValidationStep4']['email_port'],
                    'email.username' => $this->data['InstallValidationStep4']['email_username'],
                    'email.password' => $this->data['InstallValidationStep4']['email_password'],
                    'system.absolute_url' => Router::url('/', true),
                    'system.timezone' => $this->data['InstallValidationStep4']['time_zone'],
                )
            );
            $this->installHelper->updateSystemParameters($sysparams);

            // congratulate the user for a successful install
            $this->redirect(array('action' => 'install5'));
        }
    }

    /**
     * install5
     * Installation done page
     *
     * @access public
     * @return void
     */
    function install5()
    {
    }


    /**
     * gpl
     *
     *
     * @access public
     * @return void
     */
    function gpl()
    {
        $this->layout = false;
        $this->render('gpl');
    }

    /**
     * configureDatabase
     *
     *
     * @access private
     * @return void
     */
    private function configureDatabase($option)
    {
        // We have user credentials for the database, write it to conf file
        //$dbConfig = $this->createDBConfigFile();
        $db = new DATABASE_CONFIG();
        $dbConfig = $db->default;

        if (!$dbConfig) {
            // Writing to config file failed
            return "Unable to write to ".CONFIGS."database.php";
        }

        // Get the data setup option: A-With Sample,B-Basic,C-Import from iPeer 1.6
        $dbConfig['data_setup_option'] = $option;
        $ret = $this->installHelper->runInsertDataStructure($dbConfig);

        //Found error
        if ($ret) {
            return 'Create Database Failed - ' . $ret;
        }

        return false;
    }


    /**
     * createDBConfigFile
     *
     *
     * @access private
     * @return void
     */
    /*private function createDBConfigFile()
    {
        // Workaround for Windows portability
        $endl = (substr(PHP_OS, 0, 3)=='WIN')? "\r\n" : "\n";

        $dbConfig = array();
        //Setup the database config parameters
        $dbConfig['host'] = $this->data['InstallValidationStep3']['host'];
        $dbConfig['login'] = $this->data['InstallValidationStep3']['login'];
        $dbConfig['password'] = $this->data['InstallValidationStep3']['password'];
        $dbConfig['database'] = $this->data['InstallValidationStep3']['database'];

        if (!DB_PREDEFINED) {
            //create and write file
            $confile = fopen(CONFIGS.'database.php', 'wb');
            if (!$confile) {
                return false;
            }

            //Write Config file
            fwrite($confile, "<?php" . $endl);
            fwrite($confile, "class DATABASE_CONFIG
            {".$endl);
            fwrite($confile, "public \$default = array(".$endl);
            fwrite($confile, "    'driver' => 'mysql', ". $endl);
            fwrite($confile, "    'connect' => 'mysql_pconnect', ".$endl);
            foreach ($dbConfig as $k => $v) {
                fwrite($confile, "    '".$k."'   => '".$v."', ".$endl);
            }
            fwrite($confile, "    'prefix'   => ''" . $endl);
            fwrite($confile, "  );" . $endl . "}".$endl);
            fwrite($confile, "?>" . $endl);
            fflush($confile);
            fclose($confile);
        }

        return $dbConfig;
    }*/


    /**
     * patchDB
     * Our base SQL file might not have the most recent database changes.
     * Updates a newly created database from the base to the current version.
     *
     *
     * @access private
     * @return void
     */
    private function patchDB()
    {
        $ret = $this->loadModel('SysParameter');
        if ($ret !== true) {
            // failed to load model
            return false;
        }
        $dbv = $this->SysParameter->getDatabaseVersion();

        // Run the patcher
        $ret = $this->DbPatcher->patch($dbv);
        if ($ret) {
            // Patcher failed
            return $ret;
        }

        return false;
    }


    /**
     * createSuperAdmin
     *
     * @param mixed $username username
     * @param mixed $password password
     * @param mixed $email    email
     *
     * @access private
     * @return void
     */
    private function createSuperAdmin($username, $password, $email)
    {
        // modify the superadmin to user specs
        $this->loadModel('User');
        $root = $this->User->findById(1);
        $root['User']['username'] = $username;
        $root['User']['password'] = $password;
        $root = $this->User->hashPasswords($root);
        $root['User']['email'] = $email;
        $ret = $this->User->save($root);
    }
}
