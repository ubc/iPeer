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
 * @lastmodified $Date: 2006/10/11 17:27:15 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Groups
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class GroupsController extends AppController
{
  var $name = 'Groups';
	var $uses =  array('Group','GroupsMembers', 'User','Personalize','GroupEvent');
  var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $Sanitize;
    var $components = array('AjaxList');

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'created': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->pageTitle = 'Groups';
		parent::__construct();
	}

   function postProcess($data) {
        // Creates the custom in use column
        if ($data) {
            foreach ($data as $key => $entry) {
                $memberCount = $this->GroupsMembers->countMembers($entry['Group']['id']);
                $plural = ($memberCount > 1) ? "s" : "";
                $data[$key]['Group']['group_name'] .= " <span style='color:#404080'>".
                    "($memberCount member$plural)</span>";
            }
        }
        // Return the processed data back
        return $data;
    }

       // =-=-=-=-=-== New list routines =-=-=-=-=-===-=-
    function setUpAjaxList () {
        // Set up the ajax list component

        // Get the course data
        $userCourseList = $this->sysContainer->getMyCourseList();
        $coursesList = array();

        foreach ($userCourseList as $id => $course) {
            $coursesList{$id} = $course['course'];
        }

        // The columns to show
        $columns = array(
            array("Group.id",        "",         "",     "hidden"),
            array("Course.id",       "",         "",     "hidden"),
            array("Course.course",   "Course",   "12em", "action", "Course Home"),
            array("Group.group_num", "Group #",  "6em",  "number"),
            array("Group.group_name","Group Name","auto", "action",
                    "View Group"),
            array("Creator.id",      "",         "",     "hidden"),
            array("Creator.username","Creator",  "10em", "action", "View Creator"),
            array("Course.created",  "Date",     "10em", "date"),
        );

        // The course to list for is the extra filter in this case
        $joinTables =
            array(
                array(  // Define the GUI aspecs
                    "id"            => "Group.course_id",
                    "description"   => "for Course:",
                    // What are the choises and the default values?
                    "list"  => $coursesList,
                    "default" => $this->rdAuth->courseId,
                    // What table do we join to get these
                    "joinTable"     => "courses",
                    "joinModel"     => "Course",
                    "localKey"      => "course_id"
                ),
                array(  "joinTable" => "users",
                        "joinModel" => "Creator",
                        "localKey" => "creator_id")
            );

        // For instructors: only list their own course events
        $extraFilters = "";
        if ($this->rdAuth->role != 'A') {
            $extraFilters = " ( ";
            foreach ($coursesList as $id => $course) {
                $extraFilters .= "course_id=$id or ";
            }
            $extraFilters .= "1=0 ) "; // just terminates the or condition chain with "false"
        }

        // Define Actions
        $deleteUserWarning = "Delete this group?\n";
        $deleteUserWarning.= "(The students themselves will be unaffected).\n";
        $deleteUserWarning.= "Proceed?";

        $recursive = (-1);

        $actions = array(
            //   parameters to cakePHP controller:,
            //   display name, (warning shown), fixed parameters or Column ids
            array("View Group",  "", "", "",  "view", "Group.id"),
            array("Edit Group",  "", "", "",  "edit", "Group.id"),
            array("Course Home",  "", "", "courses", "home", "Course.id"),
            array("View Course",  "", "", "courses", "view", "Course.id"),
            array("View Creator",  "", "", "users","view", "Creator.id"),
            array("Delete Group",    $deleteUserWarning, "", "", "delete",       "Group.id")
        );

        $this->AjaxList->setUp($this->Group, $columns, $actions, "Group.group_num", "Group.group_name",
            $joinTables, $extraFilters, $recursive, "postProcess");
    }

    function index($message='') {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
        // Set the top message
        $this->set('message', $message);
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }

    function ajaxList() {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }

    // Show a class list of groups
    function goToClassList($course) {
        if (is_numeric($course)) {
            $courses = $this->sysContainer->getMyCourseList();
            if (!empty($courses[$course])) {
                // We need to change the session state to point to this
                // course:
                // Initialize a basic non-funcional AjaxList
                $this->AjaxList->quickSetUp();
                // Clear the state first, we don't want any previous searches/selections.
                $this->AjaxList->clearState();
                // Set and update session state Variable
                $joinFilterSelections->{"Group.course_id"} = $course;
                $this->AjaxList->setStateVariable("joinFilterSelections", $joinFilterSelections);
            }
        }
        // Redirect to list after state modifications (or in case of error)
        $this->redirect("/groups/index");
    }

  function view ($id)
  {
		$this->set('group_data', $this->Group->groupStudents($id));
		$this->set('data', $this->Group->read());
  }

  function add ()
  {
    $courseId = $this->rdAuth->courseId;
   	$this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Groups';


    // gets all the students in db for the unfiltered students list
		$this->set('user_data', $this->User->getEnrolledStudents($courseId));

		if (empty($this->params['data'])) {
			$this->render('add');
		} else {
            $this->params['data']['Group']['course_id']=$courseId;
			$this->params = $this->Group->prepData($this->params);

			if ($this->Group->save($this->params['data'])) {
				// add members into the groups_members table
				$this->GroupsMembers->insertMembers($this->Group->id, $this->params['data']['Group']);

				$this->redirect('/groups/index/The groups were added successfully.');

			} else {
                // Error occured
                $this->Session->setFlash('Please correct the error below.');
				$this->render();
			}
		}
  }

    function edit ($id=null)
    {
        $courseId = $this->rdAuth->courseId;
        $this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Groups';

        if (!empty($this->params['data'])) {
            $id = $this->params['data']['Group']['id'];
            $data2save = $this->Group->prepData($this->params);
            if ( $this->Group->save($data2save['data'])) {
                $this->GroupsMembers->updateMembers($this->Group->id, $data2save['data']['Group']);
                $this->redirect('/groups/index/The group was updated successfully.');
            } else {
                // Error occurs:
                // todo: display error message
                $this->redirect('/groups/index/Error saving that group.');

            }
        }

        // gets all students not listed in the group for unfiltered box
        $this->set('user_data', $this->Group->groupDifference($id,$courseId));

        //gets all students in the group
        $this->set('group_data', $this->Group->groupStudents($id));
        $this->set('group_id', $id);

        $groupData = $this->Group->findById($id);
        if(empty($groupData)) {
            $this->redirect('/groups/index/Group Not Found.');
        }

        $this->params['data'] = $groupData;
  }

  function delete ($id)
  {
    if ($this->Group->del($id)) {
      $groupEvents = $this->GroupEvent->findAll('group_id='.$id);
      $groupMembers = $this->GroupsMembers->findAll('group_id='.$id);
      foreach ($groupEvents as $groupEvent) {
        $this->GroupEvent->del($groupEvent['GroupEvent']['id']);
      }
      foreach ($groupMembers as $groupMember) {
        $this->GroupsMembers->del($groupMember['GroupsMembers']['id']);
      }
			$this->set('data', $this->Group->findAll(null, null, 'id'));
			$this->redirect('/groups/index/The group was deleted successfully.');
		} else {
		  $this->set('data', $this->Group->findAll(null, null, 'id'));
			$this->redirect('/groups/index/Group delete failed.');
		}
  }

  function checkDuplicateName()
  {
      $this->layout = 'ajax';
      $this->set('course_id', $this->rdAuth->courseId);
      $this->render('checkDuplicateName');
  }

  function getQueryAttribute($courseId = null)
  {
    $attributes = array('fields'=>'', 'condition'=>'', 'joinTable'=>array());
    $attributes['fields'] = 'Group.id, Group.group_num, Group.group_name, Group.course_id, Group.created, Group.creator_id, Group.modified, Group.updater_id';
    $joinTable = array();//array('INNER JOIN groups_members AS GroupsMembers ON Group.id = GroupsMembers.group_id');

    if (!empty($courseId)) {
      $attributes['condition'] .= ' Group.course_id = '.$courseId;
  	}
  	$attributes['joinTable']=$joinTable;

  	return $attributes;
  }

    function import() {
        $this->autoRender = false;
        $courseId = $this->params['form']['course_id'];
        $this->params['data']['Group']['course_id'] = $courseId;
        $filename = $this->params['form']['file']['name'];
        $tmpFile = $this->params['form']['file']['tmp_name'];

        //$uploadDir = $this->sysContainer->getParamByParamCode('system.upload_dir');
        $uploadDir = "../tmp/";
        //$uploadFile = APP.$uploadDir['parameter_value'] . $filename;
        $uploadFile = $uploadDir.$filename;

        //check for blank filename
        if (trim($filename) == "") {
            $this->set('errmsg','File required.');
            $this->set('user_data', $this->User->getEnrolledStudents($courseId));
            $this->set('import_again',"true");
            $this->render('add');
            return false;
        }
        //Return true if valid, else error msg
        $validUploads = $this->framework->validateUploadFile($tmpFile, $filename, $uploadFile);
        if ($validUploads) {
            // Get file into an array.
            $lines = file($uploadFile);
            // Delete the uploaded file
            unlink($uploadFile);

            //Mess create students
            $resultAry = $this->addGroupByImport($this->params['data'], $lines);
            $this->set('data', $resultAry);

            $this->redirect('/groups/index/The group was added successfully.');
        } else {
            $this->set('errmsg', $$validUploads);
            $this->set('user_data', $this->User->getEnrolledStudents($courseId));
            $this->set('import_again',"true");
            $this->render('add');
        }
    }

    function addGroupByImport($data=null, $lines=null)
    {
        $groupNo = '';
        for ($i = 0; $i < count($lines); $i++) {
            // Get rid of '"', it just  confuses iPeer in CSV Files
            $filteredLine = $lines[$i];
            $filteredLine = str_replace('"','', $filteredLine);

            // Split fields up on line by ','
            $line = @split(',', $filteredLine);
            $data['Group']['id'] = null;
            //$data['Group']['student_no'] = trim($line[0]);
            $data['Group']['username'] = trim($line[0]);
            $data['Group']['group_num'] = trim($line[1]);
            $data['Group']['group_name'] = trim($line[2]);
            $data['Group']['creator_id'] = $this->rdAuth->id;
            if ($groupNo != $data['Group']['group_num']) {
                $this->Group->save($data);
            }

            // add members into the groups_members table
            $groupMember['GroupsMembers']['group_id'] = $this->Group->id;
            //$user = $this->User->find('student_no = '.$data['Group']['student_num']);
            $user = $this->User->find('username = '.$data['Group']['username']);
            $groupMember['GroupsMembers']['user_id'] = $user['User']['id'];
            $this->GroupsMembers->save($groupMember);
            $this->GroupsMembers->id = null;

            $groupNo = $data['Group']['group_num'];
        }
    }

    function update($attributeCode='',$attributeValue='') {
        if ($attributeCode != '' && $attributeValue != '') //check for empty params
        {
            $this->params['data'] =
                $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
        }
    }

    function getFilteredStudent()
    {
        $this->layout = 'ajax';
        $this->set('course_id', $this->rdAuth->courseId);
        $group_id = $this->params['form']['group_id'];
        $filter = 'filter' == $this->params['form']['filter'];
        $this->set('students', $this->Group->getFilteredStudents($group_id, $filter));
        $this->render('filteredStudents');
    }

    function sendGroupEmail ($courseId) {
        if (is_numeric($courseId)) {

        } else {

        }
    }
}

?>
