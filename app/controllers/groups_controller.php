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
    private $canvasEnabled;

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

        $this->set('title_for_layout', __('Groups',true));

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

        $this->canvasEnabled = in_array($this->SysParameter->get('system.canvas_enabled', 'false'), array('1', 'true', 'yes'));
        $this->set('canvasEnabled', $this->canvasEnabled);
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
     * sync groups with canvas (import or export)
     *
     * @param mixed $task 'import' or 'export'
     * @param int   $courseId
     * @param mixed $canvasCourseId
     *
     * @access public
     * @return void
     */
    function syncCanvas($courseId = null, $canvasCourseId = null, $canvasGroupCategoryId = null)
    {
        if (!$this->canvasEnabled){
            $this->Session->setFlash(__('Error: Canvas integration not enabled.', true));
            $this->redirect('index');
        }
        
        App::import('Component', 'CanvasCourse');
        App::import('Component', 'CanvasCourseUser');
        
        $userId = $this->Auth->user('id');

        // get all accessible courses (for dropdown) and also validate selected course (if any)
        $courses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        $this->set('courses', $courses);

        if (isset($this->data['Group']['Course'])) {
            $courseId = $this->data['Group']['Course'];
        }

        if (!is_null($courseId)) {
            $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
            if (empty($course)) {
                $this->Session->setFlash(__('Error: Invalid course. Please select a valid course.', true));
                $courseId = null;
            }
            else {
                $this->breadcrumb->push(array('course' => $course['Course']));
            }
        }
        
        // get all accessible canvas courses (for dropdown) and also validate selected canvas course (if any)
        $canvasCoursesRaw = CanvasCourseComponent::getAllByIPeerUser($this, $userId, true);

        $canvasCourses = array();
        foreach ($canvasCoursesRaw as $canvasCourse) {
            $canvasCourses[$canvasCourse->id] = $canvasCourse->name;
        }
        $this->set('canvasCourses', $canvasCourses);

        if (isset($this->data['Group']['canvasCourse'])) {
            $canvasCourseId = $this->data['Group']['canvasCourse'];
        }

        if (!is_null($canvasCourseId)) {
            if (!isset($canvasCourses[$canvasCourseId])){
                $this->Session->setFlash(__('Error: Invalid canvas course. Please select a valid canvas course.', true));
                $canvasCourseId = null;
            }
        }
        elseif (!empty($course) && isset($course['Course']['canvas_id'])) {
            if (!isset($canvasCourses[$course['Course']['canvas_id']])){
                $this->Session->setFlash(__('Error: Canvas course associated with this course has been deleted. Please select a new canvas course.', true));
            }
        }

        if (empty($canvasCourseId) && !empty($canvasCoursesRaw)) {
            reset($canvasCoursesRaw);
            $canvasCourseId = current($canvasCoursesRaw)->id;
        }

        $canvasGroupCategories = array();

        if (!empty($canvasCourseId)) {

            // get all accessible canvas course categories (for dropdown) and also validate selected canvas group category (if any)
            $canvasGroupCategories = $canvasCoursesRaw[$canvasCourseId]->getGroupCategories($this, User::get('id'));
            $canvasGroupCategories[''] = '(Create new groupset)';

            if (isset($this->data['Group']['canvasGroupCategory'])) {
                $canvasGroupCategoryId = $this->data['Group']['canvasGroupCategory'];
            }
    
            if (!is_null($canvasGroupCategoryId)) {
                if ($canvasGroupCategoryId == 'new') {
                    $canvasGroupCategoryId = '';
                }
                elseif (!isset($canvasGroupCategories[$canvasGroupCategoryId])){
                    $this->Session->setFlash(__('Error: Invalid canvas group set. Please select a valid canvas group set.', true));
                    $canvasGroupCategoryId = null;
                }
            }
        }

        $formUrl = $this->params['action'];

        if (!empty($courseId) && !empty($canvasCourseId) && !is_null($canvasGroupCategoryId)) {

            $this->set('breadcrumb', $this->breadcrumb->push(__('Sync Canvas Groups', true)));

            $canvasUserKey = $this->SysParameter->get('system.canvas_user_key');
            $this->set('canvasBaseUrl', $this->SysParameter->get('system.canvas_baseurl_ext'));

            $canvasStudents = $canvasCoursesRaw[$canvasCourseId]->getUsers(
                $this,
                User::get('id'),
                array(CanvasCourseUserComponent::ENROLLMENT_QUERY_STUDENT)
            );
            
            // Get the list of groups in this iPeer course and the students in each group
            $groupsAndUsers = array();
            $groups = $this->Group->getGroupsByCourseId($courseId);
            foreach ($groups as $k => $group) {
                $groupsAndUsers[$group] = $this->Group->getGroupWithMembersById($k);
                foreach ($groupsAndUsers[$group]['Member'] as $k2 => $user) {
                    // This is needed to determine if we can export this user
                    $groupsAndUsers[$group]['Member'][$k2]['isInCanvasCourse'] = isset($canvasStudents[$user['username']]);
                }
            }

            // Get list of iPeer users that are enrolled in the course in Canvas (may or may not be in this iPeer course)
            $iPeerUsernameBasedOnCanvas = array_values(array_map(
                function ($student) {
                    $key = $student->canvas_user_key;
                    return $student->$key;
                },
                $canvasStudents)
            );
            $iPeerUsernamesInCanvasCourse = array_map(
                function ($p) { return $p['User']['username']; },
                $this->User->getByUsernames($iPeerUsernameBasedOnCanvas)
            );

            $enableSimplifiedSync = true;
            $eligibleCanvasUsersIniPeerCourse = array();

            // Get the list of groups in this Canvas course and the students in each group
            $canvasGroupsAndUsers = array();
            $canvasGroups = array();
            if (!empty($canvasGroupCategoryId)) {
                $canvasGroups = $canvasCoursesRaw[$canvasCourseId]->getGroups($this, $userId, true, $canvasGroupCategoryId);
                foreach ($canvasGroups as $k => $canvasGroup) {
                    $canvasGroupsAndUsers[$canvasGroup->name] = array('CanvasMember'=>array());
                    $canvasGroupsAndUsers[$canvasGroup->name]['CanvasGroup'] = array( 
                        'id' => $canvasGroup->id,
                        'group_name' => $canvasGroup->name
                    );
    
                    // use id as $key so we get all users, not just the importable ones
                    $groupUsers = $canvasGroup->getUsers($this, $userId, false, 'id');
                    foreach ($groupUsers as $k2 => $user){
                        $canvasGroupsAndUsers[$canvasGroup->name]['CanvasMember'][$k2] = array(
                            'id' => $user->id,
                            'full_name' => $user->name,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            $canvasUserKey => $user->$canvasUserKey,
                            'isIniPeer' => (!empty($user->$canvasUserKey) && in_array($user->$canvasUserKey, $iPeerUsernamesInCanvasCourse))
                        );
                    }
                }
    
                // Merge into a single array of groups, indexed by group name
                $groupsAndUsers = array_merge_recursive($groupsAndUsers, $canvasGroupsAndUsers);

                list($enableSimplifiedSync, $eligibleCanvasUsersIniPeerCourse) = $this->_shouldEnableSimplifiedSync($groupsAndUsers, $canvasUserKey, $eligibleCanvasUsersIniPeerCourse);
            }

            // Finally, perform sync

            $exportSuccess = false;
            $importSuccess = false;

            if (isset($this->data['Group']['syncType'])) {

                $canvasGroupCategory = array();

                if ($this->data['Group']['syncType'] == 'import') {
                    $groupsAndUsers = $this->_importCanvasGroups(
                        $groupsAndUsers,
                        $courseId,
                        $iPeerUsernamesInCanvasCourse
                    );
                    $importSuccess = true;
                }
                elseif ($this->data['Group']['syncType'] == 'export') {
                    list($groupsAndUsers, $canvasGroupCategory) = $this->_exportGroupsToCanvas(
                        $groupsAndUsers, 
                        $canvasCourseId, 
                        $canvasGroupCategoryId,
                        $canvasCoursesRaw, 
                        $canvasStudents, 
                        $canvasGroups, 
                        $iPeerUsernamesInCanvasCourse,
                        $eligibleCanvasUsersIniPeerCourse
                    );
                    $exportSuccess = true;
                }
                elseif ($this->data['Group']['syncType'] == 'sync') {
                    if ($enableSimplifiedSync) {

                        if (isset($this->data['iPeerGroupAll'])) {
                            $this->data['iPeerGroup'] = $this->data['iPeerGroupAll'];
                        }
                        if (isset($this->data['canvasGroupAll'])) {
                            $this->data['canvasGroup'] = $this->data['canvasGroupAll'];
                        }

                        if (!empty($this->data['canvasGroup'])){
                            $groupsAndUsers = $this->_importCanvasGroups(
                                $groupsAndUsers,
                                $courseId,
                                $iPeerUsernamesInCanvasCourse
                            );
                            $importSuccess = true;
                        }

                        if (!empty($this->data['iPeerGroup'])){
                            list($groupsAndUsers, $canvasGroupCategory) = $this->_exportGroupsToCanvas(
                                $groupsAndUsers, 
                                $canvasCourseId, 
                                $canvasGroupCategoryId,
                                $canvasCoursesRaw, 
                                $canvasStudents, 
                                $canvasGroups, 
                                $iPeerUsernamesInCanvasCourse,
                                $eligibleCanvasUsersIniPeerCourse
                            );
                            $exportSuccess = true;
                        }
                    }
                    else {
                        $this->Session->setFlash(__('Sorry, we are not able to do simplified sync due to inconsistencies between Canvas and iPeer groups. Please use advanced mode.', true));
                    }
                }

                if (!empty($canvasGroupCategory)) {
                    $canvasGroupCategoryId = $canvasGroupCategory['id'];
                    $canvasGroupCategories = array($canvasGroupCategory['id'] => $canvasGroupCategory['name']) + $canvasGroupCategories;
                }

                $enableSimplifiedSync = $this->_shouldEnableSimplifiedSync($groupsAndUsers, $canvasUserKey);
            }
            elseif (!$enableSimplifiedSync) {
                $this->Session->setFlash(__('Simplified sync is disabled because there are inconsistencies between Canvas and iPeer groups. Please use advanced mode instead.', true));
            }

            if (!empty($courseId)) {
                $formUrl .= DS . $courseId;
                if (!empty($canvasCourseId)) {
                    $formUrl .= DS . $canvasCourseId;
                    if (!empty($canvasGroupCategoryId)) {
                        $formUrl .= DS . $canvasGroupCategoryId;
                    }
                    else {
                        $formUrl .= DS . 'new';
                    }
                }
            }

            // Get ready for view
            $this->set('enableSimplifiedSync', $enableSimplifiedSync);
            $this->set('exportSuccess', $exportSuccess);
            $this->set('importSuccess', $importSuccess);
            $this->set('numMembersToShow', 10); // determines how many members are displayed at minimum per group
            $this->set('groupsAndUsers', $groupsAndUsers);
            $this->set('canvasUserKey', $canvasUserKey);
            $this->set('canvasCourseName', $canvasCourses[$canvasCourseId]);
            $this->set('canvasGroupCategoryName', $canvasGroupCategories[$canvasGroupCategoryId]);
        }

        $this->set('formUrl', Router::url($formUrl, true));
        $this->set('courseId', $courseId);
        $this->set('canvasCourseId', $canvasCourseId);
        $this->set('canvasGroupCategoryId', $canvasGroupCategoryId);
        $this->set('canvasGroupCategories', $canvasGroupCategories);
    }

    /**
     * import using a CSV file
     *
     * @param int $courseId
     *
     * @access public
     * @return void
     */
    function import($courseId = null)
    {
        if (!is_null($courseId)) {
            $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
            if (empty($course)) {
                $this->Session->setFlash(__('Error: That course does not exist.', true));
                $this->redirect('/courses');
            }
            $this->breadcrumb->push(array('course' => $course['Course']));
        }
        
        $courses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        $this->set('courses', $courses);

        // make sure we know the course we're importing groups for
        if ($courseId == null && !empty($this->data)) {
            $courseId = $this->data['Group']['Course'];
        }
        $this->set('courseId', $courseId);
        $this->set('formUrl', Router::url(null, true));
        
        $this->set('breadcrumb', $this->breadcrumb->push(__('Import Groups From CSV File (.csv or .txt)', true)));
        $this->set('isFileImport', true);

        if (!empty($this->params['form'])) {
            $courseId = $this->params['data']['Group']['Course'];
            $this->params['data']['Group']['course_id'] = $courseId;
            $filename = $this->params['form']['file']['name'];
            $update = ($this->params['data']['Group']['update_groups']) ? true : false;
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
    function _addGroupByImport($lines, $courseId, $update, $identifier, $render=true)
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

        if ($render) {
            $this->set('groupSuccess', array_keys($groupSuccess));
            $this->set('groupFailure', $groupFailure);
            $this->set('memSuccess', $memSuccess);
            $this->set('memFailure', $memFailure);
            $this->set('invalid', $invalid);
            $this->set('courseId', $courseId);
            $this->set('breadcrumb', $this->breadcrumb->push(__('Import Groups Results', true)));
            $this->render('import_results');
        }
        else {
            return array(
                'groupSuccess' => array_keys($groupSuccess),
                'groupFailure' => $groupFailure,
                'memSuccess' => $memSuccess,
                'memFailure' => $memFailure
            );
        }
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

    /**
     * Handles importing of Canvas groups
     *
     * @param array     $groupsAndUsers array of existing groups and users in both iPeer and Canvas
     * @param integer   $courseId course ID to import into
     * @param array     $iPeerUsernamesInCanvasCourse array of usernames of users that are enrolled in this canvas course
     * @return array    updated array of $groupsAndUsers including the results of the import
     */
    private function _importCanvasGroups($groupsAndUsers, $courseId, $iPeerUsernamesInCanvasCourse)
    {
        $canvasUserKey = $this->SysParameter->get('system.canvas_user_key');

        $lines = array();

        $usersToImport = array();
        
        $update = ($this->data['Group']['updateGroups']) ? true : false;

        $importResults = array('groupSuccess'=>array(), 'groupFailure'=>array());

        if (isset($this->data['canvasGroup'])){
            foreach( $this->data['canvasGroup'] as $groupName => $groupChecked ) {
                if ($groupChecked && isset($groupsAndUsers[$groupName])) {

                    // if there are no members in this canvas group and the update flag is on or the iPeer group doesn't exist, 
                    // we need to import manually since _addGroupByImport wouldn't import groups without members in them
                    if (empty($groupsAndUsers[$groupName]['CanvasMember']) && ($update || !isset($groupsAndUsers[$groupName]['Group']))) {
                        // just empty the group if the group already exists
                        if (isset($groupsAndUsers[$groupName]['Group'])) {
                            $this->GroupsMembers->deleteAll(array('group_id' => $groupsAndUsers[$groupName]['Group']['id']));
                            $groupsAndUsers[$groupName]['Group']['justAdded'] = true;
                            unset($groupsAndUsers[$groupName]['Member']);
                        }
                        // otherwise, add this empty group
                        else {
                            $groupNum = $this->Group->getFirstAvailGroupNum($courseId);
                            $tmp = array('Group' => array('group_num' => $groupNum, 'group_name' => $groupName,
                                'course_id' => $courseId, 'creator_id' => $this->Auth->user('id')));
                            $this->Group->id = null;
                            if ($this->Group->save($tmp)) {
                                $importResults['groupSuccess'][$groupName] = $this->Group->id;
                                $groupsAndUsers[$groupName]['Group'] = array(
                                    'id' => $this->Group->id,
                                    'group_name' => $groupName,
                                    'group_num' => $groupNum,
                                    'course_id' => $courseId,
                                    'record_status' => 'A',
                                    'justAdded' => true
                                );
                            } else {
                                $importResults['groupFailure'][] = $groupName;
                            }
                        }
                    }
                    elseif (isset($groupsAndUsers[$groupName]['CanvasMember'])) {
                        foreach ($groupsAndUsers[$groupName]['CanvasMember'] as $canvasUser){
                            if (isset($canvasUser[$canvasUserKey]) && in_array($canvasUser[$canvasUserKey], $iPeerUsernamesInCanvasCourse)) {
                                // ensure this user is not already in this group
                                $userNotInGroup = true;
                                if (!empty($groupsAndUsers[$groupName]['Member'])){
                                    foreach ($groupsAndUsers[$groupName]['Member'] as $user) {
                                        if ($user['username'] == $canvasUser[$canvasUserKey]) {
                                            $userNotInGroup = false;
                                            break;
                                        }
                                    }
                                }
                                if ($userNotInGroup) {
                                    $lines[] = array($canvasUser[$canvasUserKey], $groupName);
                                    $usersToImport[$canvasUser[$canvasUserKey]] = $canvasUser;
                                }
                            }
                        }
                    }
                }
            }

            if (count($lines)) {
                $importResults = array_merge_recursive($importResults, $this->_addGroupByImport($lines, $courseId, $update, 'username', false));
                foreach ($importResults['groupSuccess'] as $groupName) {
                    $groupsAndUsers[$groupName]['Group'] = $groupsAndUsers[$groupName]['CanvasGroup'];
                    $groupsAndUsers[$groupName]['Group']['justAdded'] = true;
                }
                foreach ($importResults['memSuccess'] as $groupName => $importedUsers) {
                    if (!isset($groupsAndUsers[$groupName]['Member']) || $update) {
                        $groupsAndUsers[$groupName]['Member'] = array();
                    }
                    foreach ($importedUsers as $username) {
                        $importedUser = array(
                            'full_name' => $usersToImport[$username]['full_name'],
                            'first_name' => $usersToImport[$username]['first_name'],
                            'last_name' => $usersToImport[$username]['last_name'],
                            'username' => $username,
                            'justAdded' => true,
                            'isInCanvasCourse' => true
                        );
                        $groupsAndUsers[$groupName]['Member'][] = $importedUser;
                    }
                }
            }

            if (!empty($importResults['groupFailure']) || !empty($importResults['memFailure'])) {
                $errorMsgs = '';
                if (!empty($importResults['groupSuccess']) || !empty($importResults['memSuccess'])){
                    $errorMsgs .= '<p>Only some of the requested groups and users were imported. Those are <span class="highlight-green">highlighted</span> below.</p>';
                }
                $errorMsgs .= '<p>The following errors happened:</p><ul>';
                if (!empty($importResults['groupFailure'])) {
                    $errorMsgs .= '<li>There was a problem importing these groups: ' . implode(', ', $importResults['groupFailure']) . '</li>';
                }
                if (!empty($importResults['memFailure'])) {
                    foreach ($importResults['memFailure'] as $groupName => $members) {
                        $errorMsgs .= '<li>There was a problem adding the following members to the ' . $groupName . ' group: ' . implode(', ', $members) . '</li>';
                    }

                    $errorMsgs .= '</ul><p>Possible reasons: </p><ul>';
                    $errorMsgs .= '<li>The student identifier does not exist in the system. Please add them first.</li>';
                    $errorMsgs .= '<li>The student is not enrolled in the course. Please enrol them first.</li>';
                    $errorMsgs .= '<li>The group was unable to be created or does not exist.</li>';
                }
                $this->Session->setFlash($errorMsgs . '</ul>');                       
            }
            elseif ((!empty($importResults['groupSuccess']) || !empty($importResults['memSuccess'])) || $update) {
                $this->Session->setFlash('Success! Imported groups and members are <span class="highlight-green">highlighted</span> below.', 'good');                                
            }
            else {
                $this->Session->setFlash('There were no eligible groups or members to import that are not already in iPeer.');
            }
        }
        else {
            $this->Session->setFlash(__('Please select one or more non-empty groups to import.', true));
        }

        return $groupsAndUsers;
    }

    /**
     * Handles exporting of groups to Canvas
     *
     * @param array     $groupsAndUsers array of existing groups and users in both iPeer and Canvas
     * @param integer   $canvasCourseId of the course to export to
     * @param integer   $canvasGroupCategoryId of the course to export to
     * @param array     $canvasCoursesRaw list of Canvas courses with all details (fetched from canvas API)
     * @param array     $canvasStudents list of Canvas students in this course
     * @param array     $canvasGroups list of Canvas groups (component objects) in this course with details
     * @param array     $eligibleiPeerUsers array of usernames of users that are enrolled in this canvas course
     * @param array     $eligibleCanvasUsers array of strings representing integration_id (e.g. username) in Canvas 
     *                    that are enrolled in this iPeer course
     * @return array    updated array of $groupsAndUsers including the results of the import
     */
    private function _exportGroupsToCanvas($groupsAndUsers, $canvasCourseId, $canvasGroupCategoryId, $canvasCoursesRaw, $canvasStudents, $canvasGroups, $eligibleiPeerUsers, $eligibleCanvasUsers)
    {
        $userId = $this->Auth->user('id');
        $canvasUserKey = $this->SysParameter->get('system.canvas_user_key');
        $canvasGroupCategory = array();

        if ( !isset($this->data['iPeerGroup']) ) {
            $this->Session->setFlash(__('Please select one or more groups to export.', true));
        }
        else {
            $errorMsgs = '';
            $someSuccess = false;

            // reset this since all groups are deleted
            if ($this->data['Group']['updateCanvasGroups']) {
                $eligibleCanvasUsers = array();
            }

            foreach( $this->data['iPeerGroup'] as $groupName => $groupChecked ) {
                if ($groupChecked && isset($groupsAndUsers[$groupName]) && isset($groupsAndUsers[$groupName]['Member'])) {

                    // delete group in canvas if we need to create a new group
                    if ($this->data['Group']['updateCanvasGroups'] && isset($groupsAndUsers[$groupName]['CanvasGroup'])) {
                        $groupDeleted = $canvasCoursesRaw[$canvasCourseId]->deleteGroup($this, $userId, false, $groupsAndUsers[$groupName]['CanvasGroup']['id']);  
                        if (isset($groupDeleted->errors)){
                            $errorMsgs .= '<li>Error exporting group ' . $groupName . '</li>';
                            continue;                                            
                        }
                        unset($groupsAndUsers[$groupName]['CanvasGroup']);
                        unset($groupsAndUsers[$groupName]['CanvasMember']);
                    }

                    // create groupset in canvas if necessary
                    if (empty($canvasGroupCategoryId)) {
                        $canvasGroupCategory = $canvasCoursesRaw[$canvasCourseId]->createGroupCategory($this, $userId, false);
                        if ($canvasGroupCategory) {
                            $canvasGroupCategoryId = $canvasGroupCategory['id'];
                        }
                        else {
                            $errorMsgs .= '<li>Error creating new group set for group ' . $groupName . '</li>';
                            continue;                                            
                        }
                    }

                    // create group in canvas if necessary
                    if (!isset($groupsAndUsers[$groupName]['CanvasGroup'])) {
                        $canvasGroup = $canvasCoursesRaw[$canvasCourseId]->createGroup($this, $userId, false, $groupName, $canvasGroupCategoryId);
                        if ($canvasGroup) {
                            $someSuccess = true;
                            $groupsAndUsers[$groupName]['CanvasGroup'] = array('group_name'=>$groupName, 'id'=>$canvasGroup->id, 'justAdded'=>true);
                        }
                        else {
                            $errorMsgs .= '<li>Error creating group ' . $groupName . '</li>';
                            continue;                                            
                        }
                    }
                    else {
                        $canvasGroup = $canvasGroups[$groupsAndUsers[$groupName]['CanvasGroup']['id']];
                    }

                    // add members
                    if ($canvasGroup) {
                        foreach ($groupsAndUsers[$groupName]['Member'] as $user){
                            if (isset($user['username']) && in_array($user['username'], $eligibleiPeerUsers)) {
                                if (in_array($user['username'], $eligibleCanvasUsers)) {
                                    $errorMsgs .= '<li>' . $user['full_name'] . ' could not be added to group "' . $groupName . '" because (s)he is already in a group.</li>';
                                    continue;
                                }
                                $canvasUserAddedId = $canvasStudents[$user['username']]->id;
                                $userAdded = $canvasGroup->addUser($this, $userId, false, $canvasUserAddedId);
                                if (isset($userAdded->errors)) {
                                    $errorMsgs .= '<li>Error adding ' . $user['full_name'] . ' to group';
                                    if (is_array($userAdded->errors) && isset($userAdded->errors[0]->message)) {
                                        $errorMsgs .= ': ' . $userAdded->errors[0]->message;
                                    }
                                    elseif (isset($userAdded->errors->group_id[0]->message)) {
                                        $errorMsgs .= ': ' . $userAdded->errors->group_id[0]->message;
                                    }
                                    $errorMsgs .= '</li>';
                                }
                                else {
                                    $someSuccess = true;
                                    if (!isset($groupsAndUsers[$groupName]['CanvasMember'])){
                                        $groupsAndUsers[$groupName]['CanvasMember'] = array();
                                    }
                                    $groupsAndUsers[$groupName]['CanvasMember'][$canvasUserAddedId] = array(
                                        'id' => $canvasUserAddedId,
                                        'full_name' => $user['full_name'],
                                        'first_name' => $user['first_name'],
                                        'last_name' => $user['last_name'],
                                        $canvasUserKey => $canvasStudents[$user['username']]->$canvasUserKey,
                                        'isIniPeer' => true,
                                        'justAdded'=>true
                                    );
                                    $eligibleCanvasUsers[] = $user['username'];
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($errorMsgs)) {
                if ($someSuccess){
                    $errorMsgs = '<p>Only some of the requested groups and users were exported to Canvas. ' .
                                 'Those are <span class="highlight-green">highlighted</span> below.</p>' .
                                 '<p>The following errors happened: </p><ul>' . $errorMsgs .'</ul>';
                }
                else {
                    $errorMsgs = '<p>Export failed. The following errors happened:</p><ul>' . $errorMsgs .'</ul>';
                }
                $this->Session->setFlash($errorMsgs);
            }
            elseif ($someSuccess) {
                $this->Session->setFlash('Success! Groups and users exported to Canvas are <span class="highlight-green">highlighted</span> below.', 'good');                                
            }
        }

        return array($groupsAndUsers, $canvasGroupCategory);
    }

    /**
     * Determine whether to enable simplified sync for canvas
     * We can do a simplified sync if: 
     * - iPeer does not have the same eligible member in more than 1 group
     * - Groups either exist only on one side, or have the same eligible members on both sides
     *
     * @param array $groupsAndUsers
     * @param string $canvasUserKey
     * @param array $eligibleCanvasUsers
     * 
     * @access private
     * @return mixed if 3rd arg is not provided or null, will return either true or false, otherwise an array
     */
    private function _shouldEnableSimplifiedSync($groupsAndUsers, $canvasUserKey, $eligibleCanvasUsers=null) 
    {

        $eligibleiPeerUsers = array();
        $enableSimplifiedSync = true;

        foreach ($groupsAndUsers as $group) {

            // Check that iPeer does not have the same eligible member in more than 1 group
            $eligibleiPeerGroupUsers = array();
            if (isset($group['Member'])) {
                foreach ($group['Member'] as $iPeerUser) {
                    if ($iPeerUser['isInCanvasCourse']) { // eligible
                        if (in_array($iPeerUser['username'], $eligibleiPeerUsers)) {
                            $enableSimplifiedSync = false;
                            break;
                        }
                        $eligibleiPeerUsers[] = $iPeerUser['username'];
                        $eligibleiPeerGroupUsers[] = $iPeerUser['username'];
                    }
                }
            }

            // Check that if a group exists on both sides they have the same eligible members
            $eligibleCanvasGroupUsers = array();
            if (isset($group['CanvasGroup']) && isset($group['Group'])) {
                if (isset($group['CanvasMember'])) {
                    foreach ($group['CanvasMember'] as $canvasUser) {
                        if ($canvasUser['isIniPeer']) { // eligible
                            $eligibleCanvasGroupUsers[] = $canvasUser[$canvasUserKey];
                            if (is_array($eligibleCanvasUsers)) {
                                $eligibleCanvasUsers[] = $canvasUser[$canvasUserKey];
                            }
                        }
                    }
                }
                if (  count($eligibleCanvasGroupUsers) != count($eligibleiPeerGroupUsers) ||
                        count(array_intersect($eligibleiPeerGroupUsers, $eligibleCanvasGroupUsers)) != count($eligibleiPeerGroupUsers) ) 
                {
                    $enableSimplifiedSync = false;
                    break;
                }
            }

            if (!$enableSimplifiedSync) {
                break;
            }
        }

        if (!is_null($eligibleCanvasUsers)) {
            return array($enableSimplifiedSync, $eligibleCanvasUsers);
        }
        else {
            return $enableSimplifiedSync;
        }
    }
}
