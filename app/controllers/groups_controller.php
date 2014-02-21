<?php
define('IMPORT_GROUP_IDENTIFIER', 0);
define('IMPORT_GROUP_GROUP_NAME', 1);

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
    public $uses =  array('Group', 'GroupsMembers', 'User', 'Personalize', 'GroupEvent', 'Course', 'EvaluationSubmission',
        'UserEnrol', 'UserTutor');
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'FileUpload.FileUpload');
    public $components = array('AjaxList', 'ExportBaseNew', 'ExportCsv', 'FileUpload.FileUpload');

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

    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();

        $allowTypes = array(
            'text/plain', 'text/csv', 'application/csv',
            'application/csv.ms-excel', 'application/octet-stream',
            'text/comma-separated-values', 'text/anytext');
        $this->FileUpload->allowedTypes(array(
            'txt' => null,
            'csv' => null,
        ));
        $this->FileUpload->uploadDir(TMP);
        $this->FileUpload->fileModel(null);
        $this->FileUpload->attr('required', true);
        $this->FileUpload->attr('forceWebroot', false);
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
            $update = ($this->params['data']['Group']['update_groups']) ?
                true : false;
            $identifier = $this->params['data']['Group']['identifiers'];

            //check that a file is attached
            if (trim($filename) == "") {
                $this->Session->setFlash(__('Please select a file to upload.', true));
                $this->redirect('import/'.$courseId);
                return;
            }

            if ($this->FileUpload->success) {
                $uploadFile = $this->FileUpload->uploadDir.DS.$this->FileUpload->finalFile;
                // Get file into an array.
                $lines = Toolkit::parseCSV($uploadFile);
                //Mass create groups
                $this->_addGroupByImport($lines, $courseId, $update, $identifier);
                // Delete the uploaded file
                $this->FileUpload->removeFile($uploadFile);
            } else {
                $this->Session->setFlash($this->FileUpload->showErrors());
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

        // Remove duplicate entries
        $lines = array_map("unserialize", array_unique(array_map("serialize", $lines)));

        // pre-process the lines in the file first
        $filter = 'return (count(array_filter($user)) != 2);';
        $invalid = array_filter($lines, create_function('$user', $filter));
        $valid = array_diff_key($lines, $invalid);
        $users = Set::combine($valid, '{n}.'.IMPORT_GROUP_IDENTIFIER, '', '{n}.'.IMPORT_GROUP_GROUP_NAME);
        $names = array_unique(Set::extract($valid, '{n}.'.IMPORT_GROUP_GROUP_NAME));

        $groupSuccess = array();
        $groupFailure = array();
        foreach ($names as $name) {
            $inGroup = $this->Group->field('id', array('group_name' => $name, 'course_id' => $courseId));
            if($inGroup) {
                $groupSuccess[$name] = $inGroup;
            } else {
                $groupNum = $this->Group->getFirstAvailGroupNum($courseId);
                $tmp = array('Group' => array('group_num' => $groupNum, 'group_name' => $name,
                    'course_id' => $courseId, 'creator_id' => $this->Auth->user('id')));
                $this->Group->id = null;
                if ($this->Group->save($tmp)) {
                    $groupSuccess[$name] = $this->Group->id;
                } else {
                    $groupFailure[] = $name;
                }
            }
        }

        $memSuccess = array();
        $memFailure = array();
        $tutors = $this->UserTutor->find('list', array(
            'conditions' => array('course_id' => $courseId), 'fields' => 'user_id'));
        $students = $this->UserEnrol->find('list', array(
            'conditions' => array('course_id' => $courseId), 'fields' => 'user_id'));
        $enrolled = array_merge($tutors, $students);
        foreach ($users as $groupName => $members) {
            $identifiers = array_keys($members);
            $members = $this->User->find('list', array(
                'conditions' => array('User.'.$identifier => $identifiers),
                'fields' => array('User.'.$identifier)
            ));
            $notExist = array_diff($identifiers, $members);

            if (!isset($groupSuccess[$groupName])) {
                $stu = array_keys($members);
                foreach ($stu as $userId) {
                    $memFailure[$groupName][] = $members[$userId];
                }
                continue;
            }

            $groupId = $groupSuccess[$groupName];
            $old = $this->GroupsMembers->find('list', array(
                'conditions' => array('group_id' => $groupId), 'fields' => 'user_id'));

            if ($update) {
                $diff = array_diff($old, array_keys($members));
                $this->GroupsMembers->deleteAll(array('user_id' => $diff));
            }

            $stu = array_keys($members);
            foreach ($stu as $userId) {
                if (in_array($userId, $old)) {
                    $memSuccess[$groupName][] = $members[$userId];
                    continue;
                } else if (!in_array($userId, $enrolled)) {
                    $memFailure[$groupName][] = $members[$userId];
                    continue;
                }
                $tmp = array('GroupsMembers' => array('user_id' => $userId, 'group_id' => $groupId));
                $this->GroupsMembers->id = null;
                if ($this->GroupsMembers->save($tmp)) {
                    $memSuccess[$groupName][] = $members[$userId];
                } else {
                    $memFailure[$groupName][] = $members[$userId];
                }
            }
            foreach ($notExist as $user) {
                $memFailure[$groupName][] = $user;
            }
        }
        $this->set('groupSuccess', array_keys($groupSuccess));
        $this->set('groupFailure', $groupFailure);
        $this->set('memSuccess', $memSuccess);
        $this->set('memFailure', $memFailure);
        $this->set('invalid', $invalid);
        $this->set('courseId', $courseId);
        $this->set('breadcrumb', $this->breadcrumb->push(__('Import Groups Results', true)));
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
            $fileContent = array();
            $groups = $this->data['Member']['Member'];
            $GroupColumns = array(
                'Group.group_num' => array('form' => 'include_group_numbers', 'title' => __('Group Number', true)),
                'Group.group_name' => array('form' => 'include_group_names', 'title' => __('Group Name', true)),
            );
            // took out emails
            $UserColumns = array(
                'Member.username' => array('form' => 'include_usernames', 'title' => __('Username', true)),
                'Member.student_no' => array('form' => 'include_student_id', 'title' => __('Student No', true)),
                'Member.first_name' => array('form' => 'include_student_name', 'title' => __('First Name', true)),
                'Member.last_name' => array('form' => 'include_student_name', 'title' => __('Last Name', true)),
            );
            $titles = array();
            $gFields = array();
            $uFields = array();
            $grp = false;
            foreach ($GroupColumns as $key => $field) {
                if (isset($this->params['form'][$field['form']])) {
                    $grp = true;
                    $titles[] = $field['title'];
                    $gFields[] = $key;
                }
            }
            foreach ($UserColumns as $key => $field) {
                if (isset($this->params['form'][$field['form']])) {
                    $titles[] = $field['title'];
                    $uFields[] = $key;
                }
            }
            $fileContent[] = implode(',', $titles);
            // check that at least one export field has been selected
            $fields = $this->params['form'];
            unset($fields['file_name']);
            $fields = array_filter($fields);
            if (empty($fields)) {
                $this->Session->setFlash("Please select at least one field to export.");
                $this->redirect('');
                return;
            }

            foreach ($groups as $groupId) {
                $group = $this->ExportCsv->buildGroupExportCsvByGroup($gFields, $uFields, $groupId);
                $fileContent = array_merge($fileContent, $group);
            }
            $fileContent = implode("\n", $fileContent);
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
