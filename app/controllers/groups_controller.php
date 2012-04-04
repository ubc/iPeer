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


define('IMPORT_USERNAME', 0);
define('IMPORT_GROUP_NUMBER', 1);
define('IMPORT_GROUP_NAME', 2);


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
    var $uses =  array('Group','GroupsMembers', 'User','UserEnrol','Personalize','GroupEvent');
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
        if ($this->show == 'all') {
            $this->show = 99999999;
        }
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

        $groupData = $this->Group->findById($id);
        if(empty($groupData)) {
            $this->redirect('/groups/index/Group Not Found.');
        }

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
        $students = $this->Group->groupStudents($id);

        // groupHasSubmittedEvaluation checks if anyone in the group submited an evaluation
        $groupHasSubmittedEvaluation = false;
        foreach ($students as $user) {
            $groupHasSubmittedEvaluation =
                $this->Group->countUserSubmissionsInAGroup($user['users']['id'], $id) > 0;
            if($groupHasSubmittedEvaluation) {
                break;
            }
        }

        $this->set('group_data', $students);
        $this->set('group_id', $id);
        $this->set('groupHasSubmittedEvaluation', $groupHasSubmittedEvaluation);

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
            $lines = file($uploadFile, FILE_SKIP_EMPTY_LINES);
            // Delete the uploaded file
            unlink($uploadFile);

            //Mass create groups
            $this->addGroupByImport($this->params['data'], $lines, $courseId);
        } else {
            $this->set('errmsg', $$validUploads);
            $this->set('user_data', $this->User->getEnrolledStudents($courseId));
            $this->set('import_again',"true");
            $this->render('add');
        }
    }

    // Takes an array of imported file lines, and creates groups from them
    function addGroupByImport($data, $lines, $courseId) {

        // Check for parameters
        if (empty($lines) || empty($courseId)) {
            return array();
        }

        // pre-process the lines in the file first
        for ($i = 0; $i < count($lines); $i++) {
            // Trim this line's white space
            $lines[$i] = trim($lines[$i]);
            // Clear all quotes - we rely on separators instead
            $lines[$i] = str_replace('"', '', $lines[$i]);
            // Replace tabs with commas - in case of different CSV formatting
            $lines[$i] = str_replace("\t", "", $lines[$i]);
            // Put a space between the commas, and their following column
            $lines[$i] = str_replace(",", ", ", $lines[$i]);
        }

        // Remove duplicate lines
        $lines = array_unique($lines);


        // Process the array into groups
        $users = array();
        for ($i = 0; $i < count($lines); $i++) {
            $entry = array();
            $entry['line'] =  $lines[$i];
            // To start, mark all entries as invalid and
            $entry['status'] = "Unchecked Entry";
            $entry['valid'] = false;
            $entry['added'] = false;
            // Split the line up by command
            $split = @split (',', $entry['line']);
            // If the count is not 3, there's probably a formatting error,
            //  so ignore this entry.
            if (count($split) < 3 ) {
                $entry['status'] = "Too few columns in this line (" . count($split). ")," .
                    " expected 3.";
            } else if (count($split) > 3 ) {
                $entry['status'] = "Too many columns in this line (" . count($split). ")," .
                    " expected 3.";
            } else {
                // assign the parts into their appropriate places
                $entry['username'] = trim($split[IMPORT_USERNAME]);
                $entry['group_num'] = trim($split[IMPORT_GROUP_NUMBER]);
                $entry['group_name'] = trim($split[IMPORT_GROUP_NAME]);

                // Check the entries for empty spots
                if (empty($entry['username'])) {
                    $entry['status'] = "Username column is empty.";
                } else if (empty($entry['group_num'])) {
                    $entry['status'] = "Group Number column is empty.";
                } else if (empty($entry['group_name'])) {
                    $entry['status'] = "Group Name column is empty.";
                } else {
                    $userData = $this->User->findByUsername($entry['username']);
                    if (!is_array($userData)) {
                        $entry['status'] = "User $entry[username] is unknown. Please add this user first.";
                    } else {
                        $entry['id'] = $userData['User']['id'];
                        if (!$this->UserEnrol->isEnrolledInByUsername($entry['username'], $courseId)) {
                            $entry['status'] = "User $entry[username] is not enrolled in your selected course. ";
                            $entry['status'] .= "Please entroll them first.";
                        } else {
                            // So, the user exists, and is enrolled in the course - they pass validation
                            $entry['status'] = "Validated Entry";
                            $entry['valid'] = true;
                        }
                    }
                }
            }
            // Add this checked entry into the list
            array_push($users, $entry);
        }

        // Now, generate a list of groups to create
        $groups = array();
        foreach ($users as $key => $entry) {
            if ($entry['valid']) {
                // Check to see if this group is already in the group array.
                $newGroup = true;
                foreach ($groups as $group) {
                    if ($group['name'] == $entry['group_name'] &&
                        $group['number'] == $entry['group_num']) {
                        $newGroup = false;
                        break;
                    }
                }
                // If we have a new group, record it.
                if ($newGroup) {
                    $group = array();
                    $group['number'] = $entry['group_num'];
                    $group['name'] = $entry['group_name'];
                    $group['id'] = false;
                    $group['created'] = false;
                    $group['present'] = false;
                    $group['reason'] = "Unchecked groups";
                    array_push($groups, $group);
                }
            } else {
                continue;
            }
        }

        // Check the groups' existance, and create them if they're missing.
        // Here I assume that the above code will not add a group without users.
        foreach ($groups as $key=>$group) {
            $groupId = $this->Group->findGroup($courseId, $group['number'], $group['name']);
            if (is_numeric($groupId)) {
                $groups[$key]['present'] = true;
                $groups[$key]['id'] = $groupId;
                $groups[$key]['reason'] = "The group already exists. Students will be added to it.";
            } else {
                // DOESN'T WORK FOR SOME REASON...
                // Create the group's database array for storage
                $groupData = array();
                $groupData['Group'] = array();
                $groupData['Group']['id'] = null;
                $groupData['Group']['group_num'] = $group['number'];
                $groupData['Group']['group_name'] = $group['name'];
                $groupData['Group']['creator_id'] = $this->rdAuth->id;
                $groupData['Group']['course_id'] = $courseId;

                // Save the group to the database
                if ($this->Group->save($groupData)) {
                    $groups[$key]['present'] = true;
                    $groups[$key]['created'] = true;
                    $groups[$key]['id'] = $this->Group->id;
                    $groups[$key]['reason'] = "This is a new group; it was created sucessfully.";
                } else {
                    $groups[$key]['reason'] = "The group could not be created in the database!";
                }
            }
        }

        // Then, add the users to the created groups
        foreach ($users as $key => $user) {
            // For all valid users
            if ($user['valid']) {
                // Find the group we'd like to add this person to
                $groupId = false;
                foreach ($groups as $group) {
                    if ($group['number'] == $user['group_num'] &&
                        $group['name'] == $user['group_name']) {
                        $groupId = $group['id'];
                    }
                }
                if (is_numeric($groupId)) {
                    $alreadyAdded = $this->GroupsMembers->isMemberOf($user['id'], $groupId);
                    if ($alreadyAdded) {
                        // User Already in group
                        $users[$key]['status'] = "User $user[username] is already in group ";
                        $users[$key]['status'].= "$user[group_num] - $user[group_name]";
                    } else {
                        $groupMemberData = array();
                        $groupMemberData['id']= null;
                        $groupMemberData['user_id'] = $user['id'];
                        $groupMemberData['group_id'] = $groupId;
                        if ($this->GroupsMembers->save($groupMemberData)) {
                            $users[$key]['status'] = "User added sucessfully to group ";
                            $users[$key]['status'].= "$user[group_num] - $user[group_name]";
                            $users[$key]['added'] = true;
                        } else {
                            $users[$key]['status'] = "User $user[username] could not be added to group ";
                            $users[$key]['status'].= "$user[group_num] - $user[group_name]";
                            $users[$key]['status'].= "- the entry could not be created in the database.";
                        }
                    }
                } else {
                    // A group should have either existed, or was just created.
                    $users[$key]['status'] = "Can't find the group for user $user[username]. ";
                    $users[$key]['status'] = "This should never occur!";
                }

            }
        }

        // Set up the data for the view
        $results = array();
        $results['users_added'] = 0;
        $results['users_skipped'] = 0;
        $results['users_error'] = 0;

        $results['groups_added'] = 0;
        $results['groups_skipped'] = 0;
        $results['groups_error'] = 0;

        // Add up statistics of import
        foreach ($users as $key => $user) {

            // Look up extra user info:
            if (!empty($user['id'])) {
                 $users[$key]['data'] = $this->User->findById($user['id']);
            } else {
                $users[$key]['data'] = false;
            }

            // Add the user to statistics
            if ($user['valid']) {
                if ($user['added']) {
                    $results['users_added']++;
                } else {
                    $results['users_skipped']++;
                }
            } else {
                $results['users_error']++;
            }

        }

        foreach ($groups as $key => $group) {
            if ($group['present']) {
                if ($group['created']) {
                    $results['groups_added']++;
                } else {
                    $results['groups_skipped']++;
                }
            } else {
                $results['groups_error']++;
            }
        }

        $results['groups'] = $groups;
        $results['users'] = $users;

        $this->set("results", $results);
        $this->render('import_results');
        exit;
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

