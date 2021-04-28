<?php
App::import('Component', 'Auth');
App::import('Component', 'Acl');
App::import('Component', 'Aco');
require_once (CORE_PATH.'cake/libs/controller/controller.php');

/**
 * CreateAclShell
 *
 * @uses Shell
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CreateAclShell extends Shell
{
    public $uses = array('User', 'Role');
    public $Auth, $Acl;

    /**
     * main
     *
     *
     * @access public
     * @return void
     */
    function main()
    {
        $this->Auth = new AuthComponent(null);
        $this->Acl = new AclComponent(null);

        $this->Role->query('TRUNCATE acos;');
        $this->Role->query('TRUNCATE aros;');
        $this->Role->query('TRUNCATE aros_acos;');
        $this->Role->query('TRUNCATE roles;');

        $this->out('Creating Aros...');
        $this->createAros();

        $this->out('Creating Acos...');
        $this->createAcos();

        $this->out('Creating Permissions...');
        $this->createPermissions();
        $this->hr();

        $this->out('Done');
    }


    /**
     * createAcos
     *
     *
     * @access public
     * @return void
     */
    function createAcos()
    {
        // could make it 'pages/admin' but unfortunately, there is a
        // pages controller brought in somewhere when generating the ACOs
        // for the controller.
        $this->Acl->Aco->create(
            array('parent_id' => null, 'alias' => 'adminpage'));
        $this->Acl->Aco->save();

        $this->__buildAcoControllers();
        $this->__buildAcoFunctions();
    }


    /**
     * __buildAcoFunctions
     *
     *
     * @access protected
     * @return void
     */
    function __buildAcoFunctions()
    {
        $roles = $this->Role->find('all');

        $this->Acl->Aco->create(array('parent_id' => null, 'alias' => 'functions'));
        $root = $this->Acl->Aco->save();
        $root['Aco']['id'] = $this->Acl->Aco->id;

        // functions/user
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'user'));
        $aco_user = $this->Acl->Aco->save();
        $aco_user['Aco']['id'] = $this->Acl->Aco->id;

        foreach ($roles as $r) {
            $this->Acl->Aco->create(array('parent_id' => $aco_user['Aco']['id'], 'model' => null, 'alias' => $r['Role']['name']));
            $this->Acl->Aco->save();
        }

        $this->Acl->Aco->create(array('parent_id' => $aco_user['Aco']['id'], 'model' => null, 'alias' => 'import'));
        $this->Acl->Aco->save();

        $this->Acl->Aco->create(array('parent_id' => $aco_user['Aco']['id'], 'model' => null, 'alias' => 'password_reset'));
        $pwd_reset = $this->Acl->Aco->save();
        $pwd_reset['Aco']['id'] = $this->Acl->Aco->id;

        foreach ($roles as $r) {
            $this->Acl->Aco->create(array('parent_id' => $pwd_reset['Aco']['id'], 'model' => null, 'alias' => $r['Role']['name']));
            $this->Acl->Aco->save();
        }

        $this->Acl->Aco->create(array('parent_id' => $aco_user['Aco']['id'], 'model' => null, 'alias' => 'index'));
        $user_index = $this->Acl->Aco->save();

        // functions/role
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'role'));
        $role = $this->Acl->Aco->save();
        $role['Aco']['id'] = $this->Acl->Aco->id;

        foreach ($roles as $r) {
            $this->Acl->Aco->create(array('parent_id' => $role['Aco']['id'], 'model' => null, 'alias' => $r['Role']['name']));
            $this->Acl->Aco->save();
        }

        // functions/evaluation
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'evaluation'));
        $eval = $this->Acl->Aco->save();
        $eval['Aco']['id'] = $this->Acl->Aco->id;

        // functions/email
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'email'));
        $email = $this->Acl->Aco->save();
        $email['Aco']['id'] = $this->Acl->Aco->id;

        $this->Acl->Aco->create(array('parent_id' => $email['Aco']['id'], 'model' => null, 'alias' => 'allUsers'));
        $this->Acl->Aco->save();

        $this->Acl->Aco->create(array('parent_id' => $email['Aco']['id'], 'model' => null, 'alias' => 'allGroups'));
        $this->Acl->Aco->save();

        $this->Acl->Aco->create(array('parent_id' => $email['Aco']['id'], 'model' => null, 'alias' => 'allCourses'));
        $this->Acl->Aco->save();

        // functions/emailtemplate
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'emailtemplate'));
        $emailtemplate = $this->Acl->Aco->save();
        $emailtemplate['Aco']['id'] = $this->Acl->Aco->id;

        // functions/viewstudentresults
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'viewstudentresults'));
        $viewstudentresults = $this->Acl->Aco->save();
        $viewstudentresults['Aco']['id'] = $this->Acl->Aco->id;

        // functions/viewemailaddresses
        // some users can't explicitly see users' email addresses
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'viewemailaddresses'));
        $viewemailaddresses = $this->Acl->Aco->save();
        $viewemailaddresses['Aco']['id'] = $this->Acl->Aco->id;

        // functions/superadmin
        // for functionalities only super admin can use
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'superadmin'));
        $superadmin = $this->Acl->Aco->save();

        // functions/coursemanager
        // for roles that can view the course manager home page
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'coursemanager'));
        $coursemanager = $this->Acl->Aco->save();

        // functions/viewusername
        // some users can't explicitly see username
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'viewusername'));
        $this->Acl->Aco->save();

        // functions/submitstudenteval
        // allow users to submit evaluation/survey for students in student view
        $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'submitstudenteval'));
        $this->Acl->Aco->save();
    }


    /**
     * __buildAcoControllers
     *
     *
     * @access protected
     * @return void
     */
    function __buildAcoControllers()
    {
        $log = array();

        $aco =& $this->Acl->Aco;
        $root = $aco->node('controllers');
        if (!$root) {
            $aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
            $root = $aco->save();
            $root['Aco']['id'] = $aco->id;
            $log[] = __('Created Aco node for controllers', true);
        } else {
            $root = $root[0];
        }

        App::import('Core', 'File');
        $Controllers = Configure::listObjects('controller');
        $appIndex = array_search('App', $Controllers);
        if ($appIndex !== false ) {
            unset($Controllers[$appIndex]);
        }
        $baseMethods = get_class_methods('Controller');
        $baseMethods[] = 'buildAcl';

        $Plugins = $this->_getPluginControllerNames();
        $Controllers = array_merge($Controllers, $Plugins);

        // look at each controller in app/controllers
        foreach ($Controllers as $ctrlName) {
            $methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

            // Do all Plugins First
            if ($this->_isPlugin($ctrlName)) {
                $pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
                if (!$pluginNode) {
                    $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
                    $pluginNode = $aco->save();
                    $pluginNode['Aco']['id'] = $aco->id;
                    $log[] = __('Created Aco node for ', true) . $this->_getPluginName($ctrlName) . __(' Plugin', true);
                }
            }
            // find / make controller node
            $controllerNode = $aco->node('controllers/'.$ctrlName);
            if (!$controllerNode) {
                if ($this->_isPlugin($ctrlName)) {
                    $pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
                    $aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = __('Created Aco node for ', true) . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . __(' Plugin Controller', true);
                } else {
                    $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = __('Created Aco node for ', true) . $ctrlName;
                }
            } else {
                $controllerNode = $controllerNode[0];
            }

            //clean the methods. to remove those in Controller and private actions.
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
                $methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
                if (!$methodNode) {
                    $aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
                    $methodNode = $aco->save();
                    $log[] = __('Created Aco node for ', true). $method;
                }
            }
        }
        if (count($log)>0) {
            print_r($log);
        }
    }


    /**
     * _getClassMethods
     *
     * @param bool $ctrlName
     *
     * @access protected
     * @return void
     */
    function _getClassMethods($ctrlName = null)
    {
        App::import('Controller', $ctrlName);
        if (strlen(strstr($ctrlName, '.')) > 0) {
            // plugin's controller
            $num = strpos($ctrlName, '.');
            $ctrlName = substr($ctrlName, $num+1);
        }
        $ctrlclass = $ctrlName . 'Controller';
        $methods = get_class_methods($ctrlclass);

        // Add scaffold defaults if scaffolds are being used
        $properties = get_class_vars($ctrlclass);
        if (array_key_exists('scaffold', $properties)) {
            if ($properties['scaffold'] == 'admin') {
                $methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
            } else {
                $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
            }
        }
        return $methods;
    }


    /**
     * _isPlugin
     *
     * @param bool $ctrlName
     *
     * @access protected
     * @return void
     */
    function _isPlugin($ctrlName = null)
    {
        $arr = CakeString::tokenize($ctrlName, '/');
        if (count($arr) > 1) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * _getPluginControllerPath
     *
     * @param bool $ctrlName
     *
     * @access protected
     * @return void
     */
    function _getPluginControllerPath($ctrlName = null)
    {
        $arr = CakeString::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[0] . '.' . $arr[1];
        } else {
            return $arr[0];
        }
    }


    /**
     * _getPluginName
     *
     * @param bool $ctrlName
     *
     * @access protected
     * @return void
     */
    function _getPluginName($ctrlName = null)
    {
        $arr = CakeString::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[0];
        } else {
            return false;
        }
    }


    /**
     * _getPluginControllerName
     *
     * @param bool $ctrlName
     *
     * @access protected
     * @return void
     */
    function _getPluginControllerName($ctrlName = null)
    {
        $arr = CakeString::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[1];
        } else {
            return false;
        }
    }


    /**
     * Get the names of the plugin controllers ...
     *
     * This function will get an array of the plugin controller names, and
     * also makes sure the controllers are available for us to get the
     * method names by doing an App::import for each plugin controller.
     *
     * @return array of plugin names.
     */
    function _getPluginControllerNames()
    {
        App::import('Core', 'File', 'Folder');
        $paths = Configure::getInstance();
        $folder = new Folder();
        $folder->cd(APP . 'plugins');

        // Get the list of plugins
        $Plugins = $folder->read();
        $Plugins = $Plugins[0];
        $arr = array();

        // Loop through the plugins
        foreach ($Plugins as $pluginName) {
            // Change directory to the plugin
            $didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
            if (!$didCD) {
                continue;
            }

            // Get a list of the files that have a file name that ends
            // with controller.php
            $files = $folder->findRecursive('.*_controller\.php');

            // Loop through the controllers we found in the plugins directory
            foreach ($files as $fileName) {
                // Get the base file name
                $file = basename($fileName);

                // Get the controller name
                $file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
                if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
                    if (!App::import('Controller', $pluginName.'.'.$file)) {
                        trigger_error(__('Error importing ', true).$file.__(' for plugin ', true).$pluginName);
                    } else {
                        /// Now prepend the Plugin name ...
                        // This is required to allow us to fetch the method names.
                        $arr[] = Inflector::humanize($pluginName) . "/" . $file;
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * createAros
     *
     *
     * @access public
     * @return void
     */
    function createAros()
    {
        $this->Role->set('name', 'superadmin');
        $this->Role->save();

        $this->Role->set('id', '');
        $this->Role->set('name', 'admin');
        $this->Role->save();

        $this->Role->set('id', '');
        $this->Role->set('name', 'instructor');
        $this->Role->save();

        $this->Role->set('id', '');
        $this->Role->set('name', 'tutor');
        $this->Role->save();

        $this->Role->set('id', '');
        $this->Role->set('name', 'student');
        $this->Role->save();
    }


    /**
     * createPermissions
     *
     *
     * @access public
     * @return void
     */
    function createPermissions()
    {
        $role = $this->Role;

        $role->id = 1;  // superadmin
        $this->Acl->allow($role, 'controllers');
        $this->Acl->allow($role, 'functions');
        $this->Acl->allow($role, 'adminpage');

        $role->id = 2;  // admin
        $this->Acl->deny($role, 'controllers');
        $this->Acl->allow($role, 'controllers/Home');
        $this->Acl->allow($role, 'controllers/Courses');
        $this->Acl->allow($role, 'controllers/Departments');
        $this->Acl->deny($role, 'controllers/Departments/add');
        $this->Acl->deny($role, 'controllers/Departments/view');
        $this->Acl->deny($role, 'controllers/Departments/delete');
        $this->Acl->deny($role, 'controllers/Departments/edit');
        $this->Acl->deny($role, 'controllers/Departments/index');
        $this->Acl->allow($role, 'controllers/Emailer');
        $this->Acl->allow($role, 'controllers/Emailtemplates');
        $this->Acl->allow($role, 'controllers/Evaltools');
        $this->Acl->allow($role, 'controllers/Evaluations');
        $this->Acl->allow($role, 'controllers/Events');
        $this->Acl->allow($role, 'controllers/Groups');
        $this->Acl->allow($role, 'controllers/Mixevals');
        $this->Acl->allow($role, 'controllers/Rubrics');
        $this->Acl->allow($role, 'controllers/Simpleevaluations');
        $this->Acl->allow($role, 'controllers/Surveys');
        $this->Acl->allow($role, 'controllers/Surveygroups');
        $this->Acl->allow($role, 'controllers/Users');
        $this->Acl->deny($role, 'controllers/Users/resetPasswordWithoutEmail');
        $this->Acl->allow($role, 'controllers/Evaluations');
        $this->Acl->allow($role, 'controllers/guard/guard/logout');
        $this->Acl->deny($role, 'functions');
        $this->Acl->allow($role, 'functions/emailtemplate');
        $this->Acl->allow($role, 'functions/evaluation');
        $this->Acl->allow($role, 'functions/email/allUsers');
        $this->Acl->allow($role, 'functions/user');
        $this->Acl->allow($role, 'functions/user/admin');
        $this->Acl->deny($role, 'functions/user/admin', 'delete');
        $this->Acl->deny($role, 'functions/user/superadmin');
        $this->Acl->allow($role, 'functions/viewemailaddresses');
        $this->Acl->allow($role, 'functions/viewusername');
        $this->Acl->allow($role, 'functions/coursemanager');
        $this->Acl->deny($role, 'functions/superadmin');
        $this->Acl->allow($role, 'functions/submitstudenteval');

        $role->id = 3; // instructor
        $this->Acl->deny($role, 'controllers');
        $this->Acl->allow($role, 'controllers/Home');
        $this->Acl->allow($role, 'controllers/Courses');
        $this->Acl->allow($role, 'controllers/Emailer');
        $this->Acl->allow($role, 'controllers/Emailtemplates');
        $this->Acl->allow($role, 'controllers/Evaltools');
        $this->Acl->allow($role, 'controllers/Evaluations');
        $this->Acl->allow($role, 'controllers/Events');
        $this->Acl->allow($role, 'controllers/Groups');
        $this->Acl->allow($role, 'controllers/Mixevals');
        $this->Acl->allow($role, 'controllers/Rubrics');
        $this->Acl->allow($role, 'controllers/Simpleevaluations');
        $this->Acl->allow($role, 'controllers/Surveys');
        $this->Acl->allow($role, 'controllers/Surveygroups');
        $this->Acl->allow($role, 'controllers/Users');
        $this->Acl->allow($role, 'controllers/guard/guard/logout');
        $this->Acl->allow($role, 'controllers/Oauthclients/add');
        $this->Acl->allow($role, 'controllers/Oauthclients/delete');
        $this->Acl->allow($role, 'controllers/Oauthtokens/add');
        $this->Acl->allow($role, 'controllers/Oauthtokens/delete');
        $this->Acl->deny($role, 'controllers/Users/merge');
        $this->Acl->allow($role, 'controllers/Users/showEvents');
        $this->Acl->deny($role, 'controllers/Users/resetPasswordWithoutEmail');
        $this->Acl->deny($role, 'functions');
        $this->Acl->allow($role, 'functions/evaluation');
        $this->Acl->deny($role, 'functions/evaluation', 'update');
        $this->Acl->deny($role, 'functions/evaluation', 'delete');
        $this->Acl->allow($role, 'functions/user');
        $this->Acl->deny($role, 'functions/user/admin');
        $this->Acl->deny($role, 'functions/user/superadmin');
        $this->Acl->allow($role, 'functions/user/instructor');
        $this->Acl->deny($role, 'functions/user/instructor', 'create');
        $this->Acl->deny($role, 'functions/user/instructor', 'update');
        $this->Acl->deny($role, 'functions/user/instructor', 'delete');
        $this->Acl->deny($role, 'functions/user/index');
        $this->Acl->deny($role, 'functions/viewemailaddresses');
        $this->Acl->deny($role, 'functions/superadmin');
        $this->Acl->allow($role, 'functions/coursemanager');
        $this->Acl->deny($role, 'functions/submitstudenteval');

        $role->id = 4; // tutor
        $this->Acl->deny($role, 'controllers');
        $this->Acl->allow($role, 'controllers/Home');
        $this->Acl->deny($role, 'controllers/Courses');
        $this->Acl->deny($role, 'controllers/Emailer');
        $this->Acl->deny($role, 'controllers/Emailtemplates');
        $this->Acl->deny($role, 'controllers/Evaltools');
        $this->Acl->deny($role, 'controllers/Events');
        $this->Acl->deny($role, 'controllers/Groups');
        $this->Acl->deny($role, 'controllers/Mixevals');
        $this->Acl->deny($role, 'controllers/Rubrics');
        $this->Acl->deny($role, 'controllers/Simpleevaluations');
        $this->Acl->deny($role, 'controllers/Surveys');
        $this->Acl->deny($role, 'controllers/Surveygroups');
        $this->Acl->deny($role, 'controllers/Users');
        $this->Acl->allow($role, 'controllers/guard/guard/logout');
        $this->Acl->allow($role, 'controllers/Evaluations/makeEvaluation');
        $this->Acl->allow($role, 'controllers/Evaluations/studentViewEvaluationResult');
        $this->Acl->allow($role, 'controllers/Evaluations/completeEvaluationRubric');
        $this->Acl->allow($role, 'controllers/Users/editProfile');
        $this->Acl->deny($role, 'functions');
        $this->Acl->deny($role, 'functions/viewemailaddresses');
        $this->Acl->deny($role, 'functions/superadmin');

        $role->id = 5; // student
        $this->Acl->deny($role, 'controllers');
        $this->Acl->allow($role, 'controllers/Home');
        $this->Acl->deny($role, 'controllers/Courses');
        $this->Acl->deny($role, 'controllers/Emailer');
        $this->Acl->deny($role, 'controllers/Emailtemplates');
        $this->Acl->deny($role, 'controllers/Evaltools');
        $this->Acl->deny($role, 'controllers/Events');
        $this->Acl->deny($role, 'controllers/Groups');
        $this->Acl->deny($role, 'controllers/Mixevals');
        $this->Acl->deny($role, 'controllers/Rubrics');
        $this->Acl->deny($role, 'controllers/Simpleevaluations');
        $this->Acl->deny($role, 'controllers/Surveys');
        $this->Acl->deny($role, 'controllers/Surveygroups');
        $this->Acl->deny($role, 'controllers/Users');
        $this->Acl->allow($role, 'controllers/guard/guard/logout');
        $this->Acl->allow($role, 'controllers/Evaluations/makeEvaluation');
        $this->Acl->allow($role, 'controllers/Evaluations/studentViewEvaluationResult');
        $this->Acl->allow($role, 'controllers/Evaluations/completeEvaluationRubric');
        $this->Acl->allow($role, 'controllers/Users/editProfile');
        $this->Acl->allow($role, 'controllers/Oauthclients/add');
        $this->Acl->allow($role, 'controllers/Oauthclients/delete');
        $this->Acl->allow($role, 'controllers/Oauthtokens/add');
        $this->Acl->allow($role, 'controllers/Oauthtokens/delete');
        $this->Acl->deny($role, 'functions');
        $this->Acl->allow($role, 'functions/viewstudentresults');
        $this->Acl->deny($role, 'functions/viewemailaddresses');
        $this->Acl->deny($role, 'functions/superadmin');
    }
}
