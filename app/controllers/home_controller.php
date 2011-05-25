<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/09/08 00:22:00 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Users
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class HomeController extends AppController
{
  /**
   * This controller does not use a model
   *
   * @var $uses
   */
  var $uses =  array('GroupEvent', 'UserEnrol', 'User', 'UserCourse', 'Event', 'Group', 'EvaluationSubmission', 'Course', 'Role');
  var $page;
  var $Sanitize;
  var $functionCode = 'HOME';
  var $componets = array('Acl');

  function __construct()
  {
    $this->Sanitize = new Sanitize;
    $this->set('title_for_layout', 'Home');
    parent::__construct();
  }

  /* temp code */
  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('*');
  }

  function createAro() {
    $this->Role->set('name', 'superadmin');
    $this->Role->save();

    $this->Role->set('id', '');
    $this->Role->set('name', 'admin');
    $this->Role->save();

    $this->Role->set('id', '');
    $this->Role->set('name', 'instructor');
    $this->Role->save();

    $this->Role->set('id', '');
    $this->Role->set('name', 'student');
    $this->Role->save();
  }

  function createPermissions() {
    $role = $this->Role;
    $role->id = 1;  // superadmin
    $this->Acl->allow($role, 'controllers');
    $this->Acl->allow($role, 'functions');

    $role->id = 2;  // admin
    $this->Acl->deny($role, 'controllers');
    $this->Acl->allow($role, 'controllers/Home');
    $this->Acl->allow($role, 'controllers/Courses');
    $this->Acl->allow($role, 'controllers/Users');
    $this->Acl->deny($role, 'functions');
    $this->Acl->allow($role, 'functions/user');
    $this->Acl->deny($role, 'functions/user/admin');
    $this->Acl->deny($role, 'functions/user/superadmin');

    $role->id = 3; // instructor
    $this->Acl->deny($role, 'controllers');
    $this->Acl->allow($role, 'controllers/Home');
    $this->Acl->allow($role, 'controllers/Courses');
    $this->Acl->allow($role, 'controllers/Users');
    $this->Acl->deny($role, 'functions');
    $this->Acl->allow($role, 'functions/user');
    $this->Acl->deny($role, 'functions/user/admin');
    $this->Acl->deny($role, 'functions/user/superadmin');
    $this->Acl->deny($role, 'functions/user/instructor');

    $role->id = 4; // student
    $this->Acl->deny($role, 'controllers');
    $this->Acl->allow($role, 'controllers/Home');
    $this->Acl->allow($role, 'controllers/Courses');
    $this->Acl->deny($role, 'controllers/Users');
    $this->Acl->deny($role, 'functions');
  }

  function createAcos() {
    $this->__buildAcoControllers();
    $this->__buildAcoFunctions();
  }

  function __buildAcoFunctions() {
    $roles = $this->Role->find('all');

    $this->Acl->Aco->create(array('parent_id' => null, 'alias' => 'functions'));
    $root = $this->Acl->Aco->save();
    $root['Aco']['id'] = $this->Acl->Aco->id;

    // functions/user
    $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'user'));
    $aco_user = $this->Acl->Aco->save();
    $aco_user['Aco']['id'] = $this->Acl->Aco->id;

    foreach($roles as $r) {
      $this->Acl->Aco->create(array('parent_id' => $aco_user['Aco']['id'], 'model' => null, 'alias' => $r['Role']['name']));
      $this->Acl->Aco->save();
    }

    $this->Acl->Aco->create(array('parent_id' => $aco_user['Aco']['id'], 'model' => null, 'alias' => 'import'));
    $this->Acl->Aco->save();

    $this->Acl->Aco->create(array('parent_id' => $aco_user['Aco']['id'], 'model' => null, 'alias' => 'password_reset'));
    $pwd_reset = $this->Acl->Aco->save();
    $pwd_reset['Aco']['id'] = $this->Acl->Aco->id;

    foreach($roles as $r) {
      $this->Acl->Aco->create(array('parent_id' => $pwd_reset['Aco']['id'], 'model' => null, 'alias' => $r['Role']['name']));
      $this->Acl->Aco->save();
    }

    // functions/role
    $this->Acl->Aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => 'role'));
    $role = $this->Acl->Aco->save();
    $role['Aco']['id'] = $this->Acl->Aco->id;

    foreach($roles as $r) {
      $this->Acl->Aco->create(array('parent_id' => $role['Aco']['id'], 'model' => null, 'alias' => $r['Role']['name']));
      $this->Acl->Aco->save();
    }
  }

  function __buildAcoControllers() {
    if (!Configure::read('debug')) {
      return $this->_stop();
    }
    $log = array();

    $aco =& $this->Acl->Aco;
    $root = $aco->node('controllers');
    if (!$root) {
      $aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
      $root = $aco->save();
      $root['Aco']['id'] = $aco->id;
      $log[] = 'Created Aco node for controllers';
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
      if ($this->_isPlugin($ctrlName)){
        $pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
        if (!$pluginNode) {
          $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
          $pluginNode = $aco->save();
          $pluginNode['Aco']['id'] = $aco->id;
          $log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
        }
      }
      // find / make controller node
      $controllerNode = $aco->node('controllers/'.$ctrlName);
      if (!$controllerNode) {
        if ($this->_isPlugin($ctrlName)){
          $pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
          $aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
          $controllerNode = $aco->save();
          $controllerNode['Aco']['id'] = $aco->id;
          $log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
        } else {
          $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
          $controllerNode = $aco->save();
          $controllerNode['Aco']['id'] = $aco->id;
          $log[] = 'Created Aco node for ' . $ctrlName;
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
          $log[] = 'Created Aco node for '. $method;
        }
      }
    }
    if(count($log)>0) {
      debug($log);
    }
  }

  function _getClassMethods($ctrlName = null) {
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
    if (array_key_exists('scaffold',$properties)) {
      if($properties['scaffold'] == 'admin') {
        $methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
      } else {
        $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
      }
    }
    return $methods;
  }

  function _isPlugin($ctrlName = null) {
    $arr = String::tokenize($ctrlName, '/');
    if (count($arr) > 1) {
      return true;
    } else {
      return false;
    }
  }

  function _getPluginControllerPath($ctrlName = null) {
    $arr = String::tokenize($ctrlName, '/');
    if (count($arr) == 2) {
      return $arr[0] . '.' . $arr[1];
    } else {
      return $arr[0];
    }
  }

  function _getPluginName($ctrlName = null) {
    $arr = String::tokenize($ctrlName, '/');
    if (count($arr) == 2) {
      return $arr[0];
    } else {
      return false;
    }
  }

  function _getPluginControllerName($ctrlName = null) {
    $arr = String::tokenize($ctrlName, '/');
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
   *
   */
  function _getPluginControllerNames() {
    App::import('Core', 'File', 'Folder');
    $paths = Configure::getInstance();
    $folder =& new Folder();
    $folder->cd(APP . 'plugins');

    // Get the list of plugins
    $Plugins = $folder->read();
    $Plugins = $Plugins[0];
    $arr = array();

    // Loop through the plugins
    foreach($Plugins as $pluginName) {
      // Change directory to the plugin
      $didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
      // Get a list of the files that have a file name that ends
      // with controller.php
      $files = $folder->findRecursive('.*_controller\.php');

      // Loop through the controllers we found in the plugins directory
      foreach($files as $fileName) {
        // Get the base file name
        $file = basename($fileName);

        // Get the controller name
        $file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
        if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
          if (!App::import('Controller', $pluginName.'.'.$file)) {
            debug('Error importing '.$file.' for plugin '.$pluginName);
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

  function index() {
    //Disable the autorender, base the role to render the custom home
    $this->autoRender = false;

    $role = $this->Auth->user('role');
    if (isset ($role)) {
      //General Home Rendering for Admin
      if ($role == $this->User->USER_TYPE_ADMIN)
      {
        
        //var_dump($course_list[0]['Instructor']);

            $inactiveCourseDetail = array();
            $inactiveCourseList = $this->Course->getInactiveCourses();
            $inactiveCourseDetail = $this->formatCourseList($inactiveCourseList);

            $this->set('course_list', $inactiveCourseDetail);
            $this->render('index');
      }////General Home Rendering for Instructor
      else if($role == $this->User->USER_TYPE_INSTRUCTOR){
            $course_list = $this->Course->getCourseByInstructor($this->Auth->user('id'));
            $this->set('course_list', $this->formatCourseList($course_list));
            $this->render('index');
      }//Student Home Rendering
      else if ($role == $this->User->USER_TYPE_STUDENT) {

        $this->set('data', $this->preparePeerEvals());

        //Check if the student has a email in his/her profile
        $email = $this->Auth->user('email');
        if (!empty($email)) {
          $this->render('studentIndex');
        }else{
          $this->redirect('/users/editProfile');
        }
      }
    }
  }

  function preparePeerEvals()
  {
    $curUserId = $this->Auth->user('id');
    $eventAry = array();
    $pos = 0;
    //Get enrolled courses
    $enrolledCourseIds = $this->UserEnrol->getEnrolledCourses($curUserId);
    foreach($enrolledCourseIds as $row) {
      $userEnrol = $row['UserEnrol'];
      $courseId = $userEnrol['course_id'];
      //$courseDetail = $this->Course->find('id='.$courseId);

      //Get Events for this course that are due
      $events = $this->Event->find('all','release_date_begin < NOW() AND NOW() <= release_date_end AND course_id='.$courseId);
      foreach($events as $row) {
        $event = $row['Event'];
        switch ($event['event_template_type_id']) {
          case 3:
            //Survey
            $survey = $this->getSurveyEvaluation($courseId,$event,$curUserId);
            if ($survey!=null) {
              $eventAry[$pos] = $survey;
              $pos++;
            }
            break;
          default:
            //Simple, Rubric and Mixed Evaluation
            $evaluation = $this->getEvaluation($curUserId, $event);
            if ($evaluation!=null) {
              $eventAry[$pos] = $evaluation;
              $pos++;
            }
            break;
        }
      }
    }

    return $eventAry;
  }

  function getEvaluation($userId, $event=null)
  {
    $result = null;
    $groupsEvents = $this->GroupEvent->getGroupEventByUserId($userId, $event['id']);

    foreach($groupsEvents as $row):
    $groupMember = $row['GroupMember'];
    $groupEvent = $row['GroupEvent'];
    //get corresponding group
    $group = $this->Group->find('id='.$groupEvent['group_id']);
    // get corresponding evaluation submission that is not submitted
    $isSubmitted = false;
    $eventSubmit = $this->EvaluationSubmission->find('grp_event_id='.$groupEvent['id'].' AND submitter_id='.$userId);
    if ($eventSubmit['EvaluationSubmission']['submitted']) {
      $isSubmitted = true;
    }

    // get due date of event in days or number of days late
    $diff = $this->framework->getTimeDifference($event['due_date'], $this->framework->getTime());
    $isLate = ($diff < 0);
    $dueIn = abs(floor($diff));

    // if eval submission is not submitted or doesn't exist, output
    if (!$isSubmitted) {
      $result['comingEvent']['Event'] = $event;
      $result['comingEvent']['Event']['is_late'] = $isLate;
      $result['comingEvent']['Event']['days_to_due'] = $dueIn;
      $result['comingEvent']['Event']['group_id'] = $groupEvent['group_id'];
      $result['comingEvent']['Event']['course'] = $this->sysContainer->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
    }
    else {
      $result['eventSubmitted']['Event'] = $event;
      $result['eventSubmitted']['Event']['is_late'] = $isLate;
      $result['eventSubmitted']['Event']['date_submitted'] = $eventSubmit['EvaluationSubmission']['date_submitted'];
      $result['eventSubmitted']['Event']['group_id'] = $groupEvent['group_id'];
      $result['eventSubmitted']['Event']['course'] = $this->sysContainer->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
    }

    endforeach;
    return $result;

  }

  function getSurveyEvaluation($courseId, $event = null, $userId=null) {
    $result = null;
    $surveyEvents = $this->Event->getActiveSurveyEvents($courseId);

    foreach($surveyEvents as $row) {
      // get corresponding evaluation submission that is not submitted
      $isSubmitted = false;
      $eventSubmit = $this->EvaluationSubmission->find('event_id='.$event['id'].' AND submitter_id='.$userId);

      if ($eventSubmit['EvaluationSubmission']['submitted']) {
        $isSubmitted = true;
      }

      // get due date of event in days or number of days late
      $diff = $this->framework->getTimeDifference($event['due_date'], $this->framework->getTime());
      $isLate = ($diff < 0);
      $dueIn = abs(floor($diff));

      // if eval submission is not submitted or doesn't exist, output
      if (!$isSubmitted) {
        $result['comingEvent']['Event'] = $event;
        $result['comingEvent']['Event']['is_late'] = $isLate;
        $result['comingEvent']['Event']['days_to_due'] = $dueIn;
        //   $result['comingEvent']['Event']['group_id'] = $groupEvent['group_id'];
        $result['comingEvent']['Event']['course'] = $this->sysContainer->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
      }
      else {
        $result['eventSubmitted']['Event'] = $event;
        $result['comingEvent']['Event']['is_late'] = $isLate;
        $result['eventSubmitted']['Event']['date_submitted'] = $eventSubmit['EvaluationSubmission']['date_submitted'];
        //   $result['eventSubmitted']['Event']['group_id'] = $groupEvent['group_id'];
        $result['eventSubmitted']['Event']['course'] = $this->sysContainer->getCourseName($event['course_id'], $this->User->USER_TYPE_STUDENT);
      }
    }
    return $result;
  }

  function formatCourseList($course_list)
  {
    $result = array();

    foreach ($course_list as $row) {
      for ($i = 0; $i < count($row['Event']); $i++) {
        //var_dump($row['Event']);
        $event_id = $row['Event'][$i]['id'];
        $row['Event'][$i]['completed_count'] = $this->EvaluationSubmission->numCountInEventCompleted($event_id);
        if ($row['Event'][$i]['event_template_type_id'] == 3) {
              $row['Event'][$i]['student_count'] = $row['Course']['student_count'];
        }
      }
      $result[$row['Course']['record_status']][] = $row;
    }
    return $result;
  }
}

?>
