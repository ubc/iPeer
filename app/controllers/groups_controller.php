<?php
define('IMPORT_GROUP_IDENTIFIER', 0);
define('IMPORT_GROUP_GROUP_NAME', 1);
define('IMPORT_GROUP_GROUP_NUMBER', 2);

/**
 * GroupsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class GroupsController extends AppController
{
    public $name = 'Groups';
    public $uses =  array('Group', 'GroupsMembers', 'User', 'Personalize', 'GroupEvent', 'Course', 'EvaluationSubmission');
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time');
    public $components = array('AjaxList', 'ExportBaseNew', 'ExportCsv');

    /**
     * _postProcess
     *
     * @param mixed $data
     *
     * @access public
     * @return void
     */
    function _postProcess($data)
    {
        // Creates the custom in use column
        if ($data) {
            foreach ($data as $key => $entry) {
                $plural = ($entry['Group']['member_count']> 1) ? "s" : "";
                $data[$key]['Group']['group_name'] .= " <span style='color:#404080'>".
                    '('.$entry['Group']['member_count'].' member'.$plural.')</span>';
            }
        }
        // Return the processed data back
        return $data;
    }

    // =-=-=-=-=-== New list routines =-=-=-=-=-===-=-
    /**
     * setUpAjaxList
     *
     *
     * @access public
     * @return void
     */
    function setUpAjaxList ()
    {
        // Set up the ajax list component

        // The columns to show
        $columns = array(
            array("Group.id",        "",         "",     "hidden"),
            array("Group.member_count",       "",         "",     "hidden"),
            array("Course.course", "", "", "hidden"),
            array("Group.group_num", __("Group #", true),  "6em",  "number"),
            array("Group.group_name",__("Group Name", true), "auto", "action", "View Group"),
            array("Group.creator_id",      "",         "",     "hidden"),
            array("Group.creator", __("Creator", true),  "10em", "action", "View Creator"),
            array("Group.created",  __("Date", true),     "10em", "date"),
            array("Group.course_id",        "",         "",     "hidden"),
            array("Course.id",        "",         "",     "hidden"),
        );

        $conditions = array('Group.course_id' => $this->Session->read('ipeerSession.courseId'));

        // The course to list for is the extra filter in this case
        $joinTables = array();
            /*array(
                array(  // Define the GUI aspecs
                    "id"            => "Group.course_id",
                    "description"   => "for Course:",
                    // What are the choises and the default values?
                    "list"  => $coursesList,
                    "default" => $this->Session->read('iPeerSession.courseId'),
                    // What table do we join to get these
                ),
                array(  "joinTable" => "users",
                "joinModel" => "Creator",
                "localKey" => "creator_id")
            );*/

        $extraFilters = "";

        // Define Actions
        $deleteUserWarning = __("Delete this group?\n", true).
            __("(The students themselves will be unaffected).\n", true).
            __("Proceed?", true);

        $recursive = 0;

        $actions = array(
            //   parameters to cakePHP controller:,
            //   display name, (warning shown), fixed parameters or Column ids
            array(__("View Group", true),  "", "", "",  "view", "Group.id"),
            array(__("Edit Group", true),  "", "", "",  "edit", "Group.id"),
            array(__("Course Home", true),  "", "", "courses", "home", "Group.course_id"),
            array(__("View Course", true),  "", "", "courses", "view", "Group.course_id"),
            array(__("View Creator", true),  "", "", "users", "view", "Group.creator_id"),
            array(__("Delete Group", true),    $deleteUserWarning, "", "", "delete",       "Group.id")
        );

        $this->AjaxList->setUp($this->Group, $columns, $actions, "Group.group_num", "Group.group_name",
            $joinTables, $extraFilters, $recursive, "_postProcess", null, null, null, $conditions);
    }

    /**
     * index
     *
     * @param mixed $courseId
     *
     * @access public
     * @return void
     */
    function index($courseId)
    {
        if (empty($courseId) || !$course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission())) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
            return;
        }

        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))->push(__('Groups', true)));
        $this->set('course', $course);
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }


    /**
     * ajaxList
     *
     *
     * @access public
     * @return void
     */
    function ajaxList()
    {
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }

    /**
     * view
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function view($id)
    {
        // Check whether the group exists
        $group = $this->Group->getGroupWithMembersById($id);
        if (empty($group)) {
            $this->Session->setFlash(__('Error: That group does not exist.', true));
            $this->redirect('/courses');
            return;
        }

        $course = $this->Course->getAccessibleCourseById($group['Group']['course_id'], User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
            return;
        }

        $this->set('data', $group);
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))
            ->push(array('groups' => array('course_id' => $course['Course']['id'])))
            ->push(__('View', true)));
    }


    /**
     * add
     *
     * @param mixed $course_id
     *
     * @access public
     * @return void
     */
    function add ($course_id)
    {
        $course = $this->Course->getAccessibleCourseById($course_id, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
            return;
        }

        if (!empty($this->data)) {
            $this->data['Group']['group_name'] = trim($this->data['Group']['group_name']);
            if ($this->Group->save($this->data)) {
                $this->Session->setFlash(__('The group was added successfully.', true), 'good');
                $this->redirect('index/'.$course_id);
                return;
            }
        }

        $user_data1 = $this->User->getEnrolledStudentsForList($course_id);
        $user_data2 = $this->User->getCourseTutorsForList($course_id);
        $user_data = Set::pushDiff($user_data1, $user_data2);

        //Check if student is already assigned in a group
        $groups = $this->Group->getGroupsByCourseId($course_id);
        $assigned_users = $this->GroupsMembers->getUserListInGroups(
            array_keys($groups));
        foreach ($assigned_users as $assigned_user) {
            $user_data[$assigned_user] = $user_data[$assigned_user].' *';
        }

        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))->push(__('Add Group', true)));
        $this->data['Group']['course_id'] = $course_id;
        // gets all the students in db for the unfiltered students list
        $this->set('user_data', $user_data);
        $this->set('course_id', $course_id);
        $this->set('group_num', $this->Group->getFirstAvailGroupNum(($course_id)));
        $this->render('edit');
    }


    /**
     * edit
     *
     * @param mixed $group_id
     *
     * @access public
     * @return void
     */
    function edit ($group_id = null)
    {
        // Check whether the course exists
        $group = $this->Group->find('first', array('conditions' => array('Group.id' => $group_id), 'recursive' => 1));
        if (empty($group)) {
            $this->Session->setFlash(__('Error: That group does not exist.', true));
            $this->redirect('/courses');
            return;
        }

        $course = $this->Course->getAccessibleCourseById($group['Group']['course_id'], User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
            return;
        }

        if (!empty($this->data)) {
            $this->data['Group']['group_name'] = trim($this->data['Group']['group_name']);
            if ($this->Group->save($this->data)) {
                $this->Session->setFlash(__('The group was updated successfully.', true), 'good');
            } else {
                $this->Session->setFlash(__('Error updating that group.', true));
            }
            $this->redirect('index/'.$this->data['Group']['course_id']);
            return;
        }

        $this->data = $group;
        $groupEvent = $this->GroupEvent->find('list',
            array(
                'conditions' => array('group_id' => $group_id),
                'fields' => array('GroupEvent.id')
            ));
        $submissions = empty($groupEvent) ? false : $this->EvaluationSubmission->numCountInGroupCompleted($groupEvent);

        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))
            ->push(array('groups' => array('course_id' => $course['Course']['id'])))
            ->push(__('Edit', true)));

        // gets all students not listed in the group for unfiltered box
        $this->set('user_data', $this->Group->getStudentsNotInGroup($group_id, 'list'));
        //gets all students in the group
        $this->set('members', $this->Group->getMembersByGroupId($group_id, 'list'));
        $this->set('group_id', $group_id);
        $this->set('group_num', $this->data['Group']['group_num']);
        $this->set('course_id', $this->data['Group']['course_id']);
        $this->set('submissions', $submissions);
    }

    /**
     * delete
     *
     * @param mixed $groupId
     *
     * @access public
     * @return void
     */
    function delete ($groupId = null)
    {
        // Check whether the course exists
        $group = $this->Group->find('first', array('conditions' => array('id' => $groupId), 'contain' => false));
        if (empty($group)) {
            $this->Session->setFlash(__('Error: That group does not exist.', true));
            $this->redirect('/courses');
            return;
        }

        $course = $this->Course->getAccessibleCourseById($group['Group']['course_id'], User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
            return;
        }

        if ($this->Group->delete($groupId)) {
            $this->Session->setFlash(__('The group was deleted successfully.', true), 'good');
        } else {
            $this->Session->setFlash(__('Group delete failed.', true));
        }
        $this->redirect('index/'.$course['Course']['id']);
    }


    /**
     * import
     *
     * @param int $courseId
     *
     * @access public
     * @return void
     */
    function import($courseId = null)
    {
        if (empty($courseId) || !$course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission())) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
            return;
        }
        $this->breadcrumb->push(array('course' => $course['Course']));

        // Just render :-)
        if (!empty($this->params['form'])) {
            $courseId = $this->params['data']['Group']['Course'];
            $this->params['data']['Group']['course_id'] = $courseId;
            $filename = $this->params['form']['file']['name'];
            $tmpFile = $this->params['form']['file']['tmp_name'];
            $update = ($this->params['data']['Group']['update_groups']) ? 
                true : false;
            $identifier = $this->params['data']['Group']['identifiers'];

            $uploadDir = "../tmp/";
            $uploadFile = $uploadDir.$filename;

            //check that a file is attached
            if (trim($filename) == "") {
                $this->Session->setFlash(__('Please select a file to upload.', true));
                $this->redirect('import/'.$courseId);
                return;
            }
            //Return true if valid, else error msg
            $validUploads = $this->framework->validateUploadFile($tmpFile, $filename, $uploadFile);
            if ($validUploads === true) {
                // Get file into an array.
                $lines = file($uploadFile, FILE_SKIP_EMPTY_LINES);
                // Delete the uploaded file
                unlink($uploadFile);
                //Mass create groups
                $this->_addGroupByImport($lines, $courseId, $update, $identifier);
            } else {
                $this->Session->setFlash(__('Error: File was not successfully processed.', true));
                $this->redirect('import/'.$courseId);
                return;
            }
        }


        $coursesList = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');

        $this->set('breadcrumb', $this->breadcrumb->push(__('Import Groups From Text (.txt) or CSV File (.csv)', true)));
        $this->set("courses", $coursesList);
        $this->set("courseId", $courseId);
    }


    /**
     * _addGroupByImport
     * Takes an array of imported file lines, and creates groups from them
     *
     * @param mixed $lines      lines
     * @param mixed $courseId   course id
     * @param mixed $update     update
     * @param mixed $identifier identifier
     *
     * @access public
     * @return void
     */
    function _addGroupByImport($lines, $courseId, $update, $identifier)
    {   
        // Check for parameters
        if (empty($lines) || empty($courseId)) {
            return array();
        }

        // Remove duplicate lines
        $lines = array_unique($lines);
        
        // pre-process the lines in the file first
        foreach ($lines as $key => $line) {
            // Trim this line's white space
            $lines[$key] = str_getcsv(trim($lines[$key]));
        }

        // Process the array into groups
        $users = array();
        foreach ($lines as $split) {
            $entry = array();
            $entry['line'] =  join(', ', $split);
            // To start, mark all entries as invalid
            $entry['status'] = "Unchecked Entry";
            $entry['valid'] = false;
            $entry['added'] = false;
            // If the count is not 2 or 3, there's probably a formatting error,
            //  so ignore this entry.
            if (count($split) < 2 ) {
                $entry['status'] = __("Too few columns in this line (", true) . count($split). "), " .
                    __(" expected minimum 2.", true);
            } else if (count($split) > 3 ) {
                $entry['status'] = __("Too many columns in this line (", true) . count($split). "), " .
                    __(" expected maximum 3.", true);
            }

            // assign the parts into their appropriate places
            $entry['identifier'] = trim($split[IMPORT_GROUP_IDENTIFIER]);
            $entry['group_name'] = trim($split[IMPORT_GROUP_GROUP_NAME]);
            if (count($split) > 2) {
                $entry['group_num'] = trim($split[IMPORT_GROUP_GROUP_NUMBER]);
            }

            // Check the entries for empty usernames/student no's or group_names fields
            if (empty($entry['identifier'])) {
                $entry['status'] = __("Username/Student No column is empty.", true);
            } else if (empty($entry['group_name'])) {
                $entry['status'] = __("Group Name column is empty.", true);
            }

            $userData = ($identifier == 'username') ? $this->User->findByUsername($entry['identifier']) :
                $this->User->findByStudentNo($entry['identifier']);

            if (!is_array($userData)) {
                $entry['status'] = __("User ", true). $entry['identifier'].__(" is unknown. Please add this user first.", true);
                $entry['status'] .= __(' Did you choose the wrong student identifier?', true);
            } else {
                $entry['id'] = $userData['User']['id'];
                $courses = array_merge(Set::extract('/Enrolment/id', $userData),
                    Set::extract('/Tutor/id', $userData));
                if (!in_array($courseId, array_unique($courses))) {
                    $entry['status'] = __("User ", true). $entry['identifier'].__(" is not enrolled in your selected course. ", true);
                    $entry['status'] .= __("Please enrol them first.", true);
                } else {
                    // So, the user exists, and is enrolled in the course - they pass validation
                    $entry['status'] = __("Validated Entry", true);
                    $entry['valid'] = true;
                }
            }
            // Add this checked entry into the list
            array_push($users, $entry);
        }

        // Now, generate a list of groups to create
        $groups = array();
        $groupNames = array();
        $groupMembers = array();
        $groupNum = $this->Group->getFirstAvailGroupNum($courseId);
        foreach ($users as $key => $entry) {
            if ($entry['valid']) {
                // If we have a new group, record it.
                if (!in_array($entry['group_name'], $groupNames)) {
                    $group = array();
                    $group['number'] = (isset($entry['group_num'])) ? $entry['group_num'] : $groupNum;
                    $group['name'] = $entry['group_name'];
                    $groupNames[] = $entry['group_name'];
                    $group['id'] = false;
                    $group['created'] = false;
                    $group['present'] = false;
                    $group['reason'] = __("Unchecked groups", true);
                    array_push($groups, $group);
                    $groupNum++;
                }
                $groupMembers[$entry['group_name']]['User'][$key] = $entry;
            } else {
                continue;
            }
        }

        // Check the groups' existance, and create them if they're missing.
        foreach ($groups as $key => $group) {
            $groupAry = array();
            $groupAry = $this->Group->findGroupByGroupName($courseId, $group['name']);
            $groupId = $groupAry['Group']['id'];
            if (is_numeric($groupId)) {
                $groups[$key]['present'] = true;
                $groups[$key]['id'] = $groupId;
                $groups[$key]['reason'] = __("The group already exists. Students will be added to it.", true);
                $groupMembers[$group['name']]['Group']['id'] = $groupId;
            } else {
                // Create the group's database array for storage
                $groupData['Group'] = array();
                $groupData['Group']['id'] = null;
                $groupData['Group']['group_num'] = $group['number'];
                $groupData['Group']['group_name'] = $group['name'];
                $groupData['Group']['creator_id'] = $this->Auth->user('id');
                $groupData['Group']['course_id'] = $courseId;

                // Save the group to the database
                if ($this->Group->save($groupData)) {
                    $groups[$key]['present'] = true;
                    $groups[$key]['created'] = true;
                    $groups[$key]['id'] = $this->Group->id;
                    $groups[$key]['reason'] = __("This is a new group; it was created successfully.", true);
                    $groupMembers[$group['name']]['Group']['id'] = $this->Group->id;
                } else {
                    $groups[$key]['reason'] = __("The group could not be created in the database!", true);
                }
            }
        }

        // Then, add the users to the created groups
        foreach ($groupMembers as $groupName => $group) {
            $groupId = $group['Group']['id'];
            $oldMembers = $this->GroupsMembers->findAllByGroupId($groupId);
            $newMembers = Set::extract('/User/id', $group);
            // remove old group members
            if ($update) {
                foreach ($oldMembers as $old) {
                    if (!in_array($old['GroupsMembers']['user_id'], $newMembers)) {
                        $this->GroupsMembers->delete($old['GroupsMembers']['id']);
                    }
                }
            }
            
            foreach($group['User'] as $key => $user) {
                if (in_array($user['id'], Set::extract('/GroupsMembers/user_id', $oldMembers))) {
                    $users[$key]['status'] = __("User ", true).$user['identifier'];
                    $users[$key]['status'] .= __(" is already in group ", true).$groupName;
                } else {
                    $groupMemberData = array();
                    $groupMemberData['id'] = null;
                    $groupMemberData['user_id'] = $user['id'];
                    $groupMemberData['group_id'] = $groupId;
                    if ($this->GroupsMembers->save($groupMemberData)) {
                        $users[$key]['status'] = __("User added successfully to group ", true).$groupName;
                        $users[$key]['added'] = true;
                    } else {
                        $users[$key]['status'] = __("User ", true).$user['identifier'];
                        $users[$key]['status'] .= __(" could not be added to group ", true). $groupName;
                        $users[$key]['status'] .= __("- the entry could not be created in the database.", true);
                    }
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
        $this->set("courseId", $courseId);
        $this->render('import_results');
    }

    /**
     * export
     *
     * @param mixed $courseId
     *
     * @access public
     * @return void
     */
    function export($courseId)
    {
        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('/courses');
            return;
        }

        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))->push(__('Export Groups', true)));
        $this->set('courseId', $courseId);
        if (isset($this->params['form']) && !empty($this->params['form'])) {
            // check that filename field is not empty
            if (empty($this->params['form']['file_name'])) {
                $this->Session->setFlash("Please enter a valid filename.");
                $this->redirect('');
                return;
            }
            // check that at least one group has been selected
            if (empty($this->data['Member']['Member'])) {
                $this->Session->setFlash("Please select at least one group to export.");
                $this->redirect('');
                return;
            }
            $this->autoRender = false;
            $fileContent = '';
            $groups = $this->data['Member']['Member'];
            if (!empty($this->params['form']['include_group_numbers'])) {
                $fileContent .= "Group Number, ";
            }
            if (!empty($this->params['form']['include_group_names'])) {
                $fileContent .= "Group Name, ";
            }
            if (!empty($this->params['form']['include_usernames'])) {
                $fileContent .= "Username, ";
            }
            if (!empty($this->params['form']['include_student_id'])) {
                $fileContent .= "Student #, ";
            }
            if (!empty($this->params['form']['include_student_name'])) {
                $fileContent .= "First Name, Last Name";
            }
            //if (!empty($this->params['form']['include_student_email'])) {
            //    $fileContent .= "Email Address";
            //}
            // check that at least one export field has been selected
            if (empty($this->params['form']['include_group_numbers']) && empty($this->params['form']['include_group_names'])
              && empty($this->params['form']['include_usernames']) && empty($this->params['form']['include_student_id'])
              && empty($this->params['form']['include_student_name']) && empty($this->params['form']['include_student_email'])) {
                $this->Session->setFlash("Please select at least one field to export.");
                $this->redirect('');
                return;
            }

            $fileContent .= "\n";

            foreach ($groups as $groupId) {
                $fileContent .= $this->ExportCsv->buildGroupExportCsvByGroup($this->params['form'], $groupId);
            }
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' .$this->params['form']['file_name']. '.csv');
            echo $fileContent;
        } else {
            // format data
            $unassignedGroups = $this->Group->find('list', array('conditions'=> array('course_id'=>$courseId), 'fields'=>array('group_name')));
            $this->set('unassignedGroups', $unassignedGroups);
        }
    }
}
