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
    public $components = array('AjaxList', 'ExportBaseNew', 'ExportCsv', 'FileUpload.FileUpload', 'PasswordGenerator');
    private $canvasEnabled;
    private $errorMessages = array();

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

        // get course id from form submission if it's not in url
        if (empty($courseId) && isset($this->data['Group']['Course'])) {
            $courseId = $this->data['Group']['Course'];
        }

        // validate course
        if (!empty($courseId)) {
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
            if ($canvasCourse->term && $canvasCourse->term->name) {
                $canvasCourses[$canvasCourse->id] = $canvasCourse->name . ' (' . $canvasCourse->term->name . ')';
            } else {
                $canvasCourses[$canvasCourse->id] = $canvasCourse->name;
            }
        }
        $this->set('canvasCourses', $canvasCourses);

        if (!empty($courseId)) {

            // first, look to see if this course has a canvas course associated with it
            if (!empty($course['Course']['canvas_id'])) {
                if (!isset($canvasCourses[$course['Course']['canvas_id']])){
                    $this->Session->setFlash(__('Error: Canvas course associated with this course has been deleted or not accessible. Please select a new Canvas course or otherwise ask someone who has access to the Canvas course.', true));
                }
                else {
                    $canvasCourseId = $course['Course']['canvas_id'];
                }
            }

            // if no valid canvas course association, try looking at the url
            if (!empty($canvasCourseId)) {
                if (!isset($canvasCourses[$canvasCourseId])){
                    $this->Session->setFlash(__('Error: Invalid canvas course. Please select a valid canvas course.', true));
                    $canvasCourseId = null;
                }
            }

            // if all else fails, look at the form submit
            if (isset($this->data['Group']['canvasCourse'])) {
                $canvasCourseId = $this->data['Group']['canvasCourse'];
            }

            // save the canvas course association with this course if there's no association (or an invalid association) and there's a course
            if (!empty($canvasCourseId) && (empty($course['Course']['canvas_id']) || !isset($canvasCourses[$course['Course']['canvas_id']]))) {
                $save_data = array('Course' => array('canvas_id' => $canvasCourseId));
                $this->Course->id = $courseId;
                $this->Course->save($save_data);
            }

            $canvasGroupCategories = array();

            if (!empty($canvasCourseId)) {

                // get all accessible canvas course categories (for dropdown) and also validate selected canvas group category (if any)
                $canvasGroupCategories = $canvasCoursesRaw[$canvasCourseId]->getGroupCategories($this, User::get('id'));
                if ($this->data['Group']['syncType'] != 'import') {
                    $canvasGroupCategories[''] = '(Create new groupset)';
                }

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

        }

        $this->breadcrumb->push(__('Sync Canvas Groups', true));
        $this->set('breadcrumb', $this->breadcrumb);

        $formUrl = $this->params['action'];

        if (!empty($courseId) && !empty($canvasCourseId) && !is_null($canvasGroupCategoryId)) {

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

            // We need to show the user a warning if there are duplicate group names
            $canvasDuplicateGroupNames = array();

            // Get the list of groups in this Canvas course and the students in each group
            $canvasGroupsAndUsers = array();
            $canvasGroups = array();
            if (!empty($canvasGroupCategoryId)) {
                $canvasGroups = $canvasCoursesRaw[$canvasCourseId]->getGroups($this, $userId, true, $canvasGroupCategoryId);
                foreach ($canvasGroups as $k => $canvasGroup) {
                    foreach ($canvasGroups as $canvasGroupToCheck) {
                        if ($canvasGroup->id != $canvasGroupToCheck->id && $canvasGroup->name == $canvasGroupToCheck->name) {
                            $canvasDuplicateGroupNames[$canvasGroup->name] = isset($canvasDuplicateGroupNames[$canvasGroup->name]) ? count($canvasDuplicateGroupNames[$canvasGroup->name])+1 : 1;
                        }
                    }
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
                $groupsAndUsers = array_replace_recursive($groupsAndUsers, $canvasGroupsAndUsers);

                list($enableSimplifiedSync, $eligibleCanvasUsersIniPeerCourse) = $this->_shouldEnableSimplifiedSync($groupsAndUsers, $canvasUserKey, $eligibleCanvasUsersIniPeerCourse);
            }

            // Finally, perform sync

            $exportSuccess = false;
            $importSuccess = false;

            if (!empty($canvasDuplicateGroupNames)) {
                $errorMessages = '<p>' . __('We cannot perform a sync because there is a problem with the way your Canvas groups are structured in the selected Group set. In order to continue, please go to this Groupset in Canvas and rename / remove some of your groups so that all your groups have <u>unique names</u>. For your reference:.', true) . '</p><ul>';
                foreach ($canvasDuplicateGroupNames as $duplicateGroupName => $count) {
                    $errorMessages .= '<li>You have ' . $count . ' groups in Canvas with the name "' . $duplicateGroupName . '".</li>';
                }
                $errorMessages .= '</ul>';
                $this->Session->setFlash($errorMessages);
                $this->set('disableSync', true);
            }
            elseif (isset($this->data['Group']['syncType'])) {

                $canvasGroupCategory = array();

                if ($this->data['Group']['syncType'] == 'import') {

                    // do not import if no groups in Canvas
                    $hasGroupsinCanvas = false;
                    foreach ($groupsAndUsers as $group) {
                        if (!empty($group['CanvasGroup'])) {
                            $hasGroupsinCanvas = true;
                            break;
                        }
                    }

                    if (!$hasGroupsinCanvas && !empty($this->data['Group']['selectAllGroups'])) {
                        $this->Session->setFlash(__('You cannot import groups from Canvas because you do not have any groups in the selected group set in Canvas.', true));
                    }
                    else {
                        list($groupsAndUsers, $importSuccess) = $this->_importCanvasGroups(
                            $groupsAndUsers,
                            $courseId,
                            $iPeerUsernamesInCanvasCourse
                        );
                    }
                }
                elseif ($this->data['Group']['syncType'] == 'export') {

                    // do not export if no groups in iPeer
                    $hasGroupsiniPeer = false;
                    foreach ($groupsAndUsers as $group) {
                        if (!empty($group['Group'])) {
                            $hasGroupsiniPeer = true;
                            break;
                        }
                    }

                    if (!$hasGroupsiniPeer && !empty($this->data['Group']['selectAllGroups'])) {
                        $this->Session->setFlash(__('You cannot export groups to Canvas because you do not have any groups in this course.', true));
                    }
                    else {
                        list($groupsAndUsers, $canvasGroupCategory, $exportSuccess) = $this->_exportGroupsToCanvas(
                            $groupsAndUsers,
                            $canvasCourseId,
                            $canvasGroupCategoryId,
                            $canvasCoursesRaw,
                            $canvasStudents,
                            $canvasGroups,
                            $iPeerUsernamesInCanvasCourse
                        );
                    }
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
                            list($groupsAndUsers, $importSuccess) = $this->_importCanvasGroups(
                                $groupsAndUsers,
                                $courseId,
                                $iPeerUsernamesInCanvasCourse
                            );
                        }

                        if (!empty($this->data['iPeerGroup'])){
                            list($groupsAndUsers, $canvasGroupCategory, $exportSuccess) = $this->_exportGroupsToCanvas(
                                $groupsAndUsers,
                                $canvasCourseId,
                                $canvasGroupCategoryId,
                                $canvasCoursesRaw,
                                $canvasStudents,
                                $canvasGroups,
                                $iPeerUsernamesInCanvasCourse
                            );
                        }
                    }
                    else {
                        $this->Session->setFlash(__('Sorry, we are not able to do simplified sync either because you have some members in more than one group or otherwise because you have diffent members in the same Canvas and iPeer groups. Please use advanced mode.', true));
                    }
                }

                // display any error or success messages relating to import / export
                $errorMsgs = '';

                $actionPhrase = 'synchronized with';
                if (!$importSuccess) {
                    $actionPhrase = 'exported to';
                }
                elseif (!$exportSuccess) {
                    $actionPhrase = 'imported from';
                }

                if (empty($this->errorMessages) && ($exportSuccess || $importSuccess)) {
                    if (empty($this->data['Group']['selectAllGroups'])) {
                        $this->Session->setFlash('Success! Groups and users ' . $actionPhrase . ' Canvas are <span class="highlight-green">highlighted</span> below.', 'good');
                    }
                    else {
                        $this->Session->setFlash('Success! All groups and users have been ' . $actionPhrase . ' Canvas.', 'good');
                    }
                }
                elseif (!empty($this->errorMessages)) {
                    if ($exportSuccess || $importSuccess) {
                        if (empty($this->data['Group']['selectAllGroups'])) {
                            $errorTitle = '<p>Only some of the requested groups and users were ' . $actionPhrase .
                                    ' Canvas. Those are <span class="highlight-green">highlighted</span> below.</p>';
                        }
                        else {
                            $errorTitle = '<p>All groups and users were ' . $actionPhrase . ' Canvas except as mentioned below.';
                        }
                    }
                    else {
                        $errorTitle = '<h2>' . ucwords($this->data['Group']['syncType']) . ' failed!</h2>';
                    }
                    $errorTitle .= '<p>The following errors happened: </p>';
                    $this->addErrorMsg('titles', 'general', $errorTitle);
                    $this->Session->setFlash($this->getFullErrorMessage());
                }

                if (!empty($canvasGroupCategory)) {
                    $canvasGroupCategoryId = $canvasGroupCategory['id'];
                    $canvasGroupCategories = array($canvasGroupCategory['id'] => $canvasGroupCategory['name']) + $canvasGroupCategories;
                }

                $enableSimplifiedSync = $this->_shouldEnableSimplifiedSync($groupsAndUsers, $canvasUserKey);
            }
            elseif (!$enableSimplifiedSync) {
                $this->Session->setFlash(__('Simplified sync is disabled either because you have some members in more than one group or otherwise because you have diffent members in the same Canvas and iPeer groups. Please use advanced mode.', true));
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
        elseif (empty($canvasGroupCategories) && $this->data['Group']['syncType'] == 'import') {
            $this->Session->setFlash(__('You cannot import any groups from Canvas because you do not have any group sets in Canvas.', true));
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
    function import($courseId = null, $importFrom = 'file')
    {
        $this->set('importFrom', $importFrom);

        if ($importFrom == 'canvas') {

            App::import('Component', 'CanvasCourse');

            $this->data['Group']['syncType'] = 'import';
            $this->data['Group']['updateGroups'] = true;
            $this->data['Group']['importUsers'] = true;
            $this->data['Group']['selectAllGroups'] = true;

            $this->syncCanvas($courseId);

            $this->set('formUrl', Router::url(DS . $this->params['url']['url'], true));

            // Fix breadcrumb
            $this->breadcrumb->pop();
            $this->breadcrumb->push(__('Import Groups from Canvas', true));
            $this->set('breadcrumb', $this->breadcrumb);

            // some error messages need to be updated to be relevant to this screen
            if (!empty($this->errorMessages['groupImport']['noGroupsSelected'])){
                $this->errorMessages['groupImport']['noGroupsSelected'] = array('There are no groups in this group set in Canvas.');
                $this->Session->setFlash($this->getFullErrorMessage());
            }

            return;
        }

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
    function export($courseId, $exportTo = 'file')
    {
        $this->set('exportTo', $exportTo);

        if ($exportTo == 'canvas') {

            App::import('Component', 'CanvasCourse');

            $this->data['Group']['syncType'] = 'export';
            $this->data['Group']['updateCanvasGroups'] = true;
            $this->data['Group']['selectAllGroups'] = true;

            $this->syncCanvas($courseId);

            $this->set('formUrl', Router::url(DS . $this->params['url']['url'], true));

            // Fix breadcrumb
            $this->breadcrumb->pop();
            $this->breadcrumb->push(__('Export iPeer Groups to Canvas', true));
            $this->set('breadcrumb', $this->breadcrumb);

            // some error messages need to be updated to be relevant to this screen
            if (!empty($this->errorMessages['groupExport']['noGroupsSelected'])){
                $this->errorMessages['groupExport']['noGroupsSelected'] = array('There are no groups in this course to export to Canvas.');
                $this->Session->setFlash($this->getFullErrorMessage());
            }

            return;
        }

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

        $update = !empty($this->data['Group']['updateGroups']);
        $importUsers = !empty($this->data['Group']['importUsers']);
        $importAllGroups = !empty($this->data['Group']['selectAllGroups']);

        $importResults = array('groupSuccess'=>array(), 'groupFailure'=>array());

        if (isset($this->data['canvasGroup']) || $importAllGroups){

            // if importing all groups, we manually create this array to include all groups
            if ($importAllGroups) {
                $this->data['canvasGroup'] = array();

                foreach ($groupsAndUsers as $row) {
                    if (!empty($row['CanvasGroup']['id']) && !empty($row['CanvasGroup']['group_name'])) {
                        $this->data['canvasGroup'][$row['CanvasGroup']['group_name']] = 1;
                    }
                }
            }

            // import users from canvas if requested
            if ($importUsers) {

                App::import('Component', 'CanvasCourse');

                $canvasCourses = array();

                $iPeerCourse = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission());
                $canvasCourses = CanvasCourseComponent::getAllByIPeerUser($this, User::get('id'), true);
                $selectedCanvasCourse = $canvasCourses[$iPeerCourse['Course']['canvas_id']];

                $canvasUsers = $selectedCanvasCourse->getUsers(
                    $this,
                    User::get('id'),
                    array(CanvasCourseUserComponent::ENROLLMENT_QUERY_STUDENT),
                    true,
                    true
                );

                $canvas_user_key = $this->SysParameter->get('system.canvas_user_key');

                foreach ($canvasUsers as $k => $canvasUser) {
                    $users[] = array(
                        User::IMPORT_USERNAME => $canvasUser->$canvas_user_key,
                        User::IMPORT_PASSWORD => $this->PasswordGenerator->generate(),
                        User::IMPORT_FIRSTNAME => $canvasUser->first_name,
                        User::IMPORT_LASTNAME => $canvasUser->last_name,
                        User::IMPORT_EMAIL => isset($canvasUser->email) ? $canvasUser->email : '',
                        User::IMPORT_STUDENT_NO => $canvasUser->sis_user_id,
                    );
                    $iPeerUsernamesInCanvasCourse[] = $canvasUser->$canvas_user_key;
                }

                $result = $this->User->addUserByArray($users, true, $this->User->USER_TYPE_STUDENT);

                $insertedIds = array();
                foreach ($this->User->insertedIds as $new) {
                    $insertedIds[] = $new;
                }

                foreach ($result['updated_users'] as $updated_user) {
                    $insertedIds[] = $updated_user['User']['id'];
                }

                if (isset($result['errors'])) {
                    foreach ($result['errors'] as $error){
                        if (is_array($error)) {
                            foreach ($error as $error_detail) {
                                $this->addErrorMsg('groupImport', 'userAdd', $error_detail);
                            }
                        }
                        else {
                            $this->addErrorMsg('groupImport', 'userAdd', $error);
                        }
                    }
                }

                // enrol the users in this course
                $res = $this->Course->enrolStudents($insertedIds, $courseId);
            }

            foreach( $this->data['canvasGroup'] as $groupName => $groupChecked ) {
                if ($groupChecked && isset($groupsAndUsers[$groupName])) {

                    $canvasGroupEmpty = true;

                    foreach ($groupsAndUsers[$groupName]['CanvasMember'] as $groupMember) {
                        if ($groupMember['isIniPeer']) {
                            $canvasGroupEmpty = false;
                            break;
                        }
                    }

                    // if update flag is on, empty the iPeer group first (if it exists and not already empty)
                    if ($update && isset($groupsAndUsers[$groupName]['Member']) && count($groupsAndUsers[$groupName]['Member'])) {
                        $this->GroupsMembers->deleteAll(array('group_id' => $groupsAndUsers[$groupName]['Group']['id']));
                        $groupsAndUsers[$groupName]['Group']['justAdded'] = true;
                        unset($groupsAndUsers[$groupName]['Member']);
                    }

                    // if there are no members in this canvas group and the iPeer group doesn't exist,
                    // we need to manually create this group since _addGroupByImport wouldn't import groups without members in them
                    if ($canvasGroupEmpty && !isset($groupsAndUsers[$groupName]['Group'])) {
                        $groupNum = $this->Group->getFirstAvailGroupNum($courseId);
                        $tmp = array('Group' => array('group_num' => $groupNum, 'group_name' => $groupName,
                            'course_id' => $courseId, 'creator_id' => $this->Auth->user('id')));
                        $this->Group->id = null;
                        if ($this->Group->save($tmp)) {
                            $importResults['groupSuccess'][] = $groupName;
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

                    // Generate lines to import users into groups
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

            if (count($lines)) {
                $addedGroups = $this->_addGroupByImport($lines, $courseId, $update, 'username', false);
                $importResults = array_merge_recursive($importResults, $addedGroups);
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
                if (!empty($importResults['groupFailure'])) {
                    $this->addErrorMsg('groupImport', 'groupAdd', 'There was a problem importing these groups: ' . implode(', ', $importResults['groupFailure']) . '.');
                }
                if (!empty($importResults['memFailure'])) {
                    foreach ($importResults['memFailure'] as $groupName => $members) {
                        $this->addErrorMsg('groupImport', 'groupMemberAdd', 'There was a problem adding the following members to the ' . $groupName . ' group: ' . implode(', ', $members) . '.');
                    }
                }
            }

            if (empty($importResults['groupSuccess']) &&
                empty($importResults['memSuccess']) &&
                empty($importResults['groupFailure']) &&
                empty($importResults['memFailure']) ) {
                $this->addErrorMsg('groupImport', 'nothingToDo', __('There were no eligible groups or members to import that are not already in iPeer.', true));
            }
        }
        else {
            $this->addErrorMsg('groupImport', 'noGroupsSelected', __('Please select one or more non-empty groups to import.', true));
        }

        $someSuccess = (!empty($importResults['groupSuccess']) || !empty($importResults['memSuccess']));
        return array($groupsAndUsers, $someSuccess);
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
     * @return array    updated array of $groupsAndUsers including the results of the import
     */
    private function _exportGroupsToCanvas($groupsAndUsers, $canvasCourseId, $canvasGroupCategoryId, $canvasCoursesRaw, $canvasStudents, $canvasGroups, $eligibleiPeerUsers)
    {
        $userId = $this->Auth->user('id');
        $canvasUserKey = $this->SysParameter->get('system.canvas_user_key');
        $canvasGroupCategory = array();
        $someSuccess = false;

        $exportAllGroups = !empty($this->data['Group']['selectAllGroups']);

        // if exporting all groups, we manually create this array to include all groups
        if ($exportAllGroups) {
            $this->data['iPeerGroup'] = array();
            foreach ($groupsAndUsers as $row) {
                if (!empty($row['Group']['id']) && !empty($row['Group']['group_name'])) {
                    $this->data['iPeerGroup'][$row['Group']['group_name']] = 1;
                }
            }
        }

        if ( !isset($this->data['iPeerGroup']) ) {
            $this->addErrorMsg('groupExport', 'noGroupsSelected', __('Please select one or more groups to export.', true));
        }
        else {

            // if the update flag is on, let's start by deleting groups in canvas
            if ($this->data['Group']['updateCanvasGroups']) {

                $groupsAndUsers2 = $groupsAndUsers;

                // delete all Canvas groups if exporting all (and update flag is on)
                if ($exportAllGroups) {
                    foreach( $groupsAndUsers2 as $groupName => $groupDetails ) {
                        if ( isset($groupsAndUsers[$groupName]['CanvasGroup']) ) {
                            $groupDeleted = $canvasCoursesRaw[$canvasCourseId]->deleteGroup($this, $userId, false, $groupsAndUsers[$groupName]['CanvasGroup']['id']);
                            if (isset($groupDeleted->errors)){
                                $this->addErrorMsg('groupExport', 'groupExport', 'Error exporting group ' . $groupName . '. Please try again.');
                                continue;
                            }
                            unset($groupsAndUsers[$groupName]['CanvasGroup']);
                            unset($groupsAndUsers[$groupName]['CanvasMember']);
                        }
                    }
                }
                // otherwise just delete the checked groups
                else {
                    foreach( $this->data['iPeerGroup'] as $groupName => $groupChecked ) {
                        if ($groupChecked &&
                            isset($groupsAndUsers[$groupName]) &&
                            isset($groupsAndUsers[$groupName]['CanvasGroup'])
                            ) {
                            $groupDeleted = $canvasCoursesRaw[$canvasCourseId]->deleteGroup($this, $userId, false, $groupsAndUsers[$groupName]['CanvasGroup']['id']);
                            if (isset($groupDeleted->errors)){
                                $this->addErrorMsg('groupExport', 'groupExport', 'Error exporting group ' . $groupName . '. Please try again.');
                                continue;
                            }
                            unset($groupsAndUsers[$groupName]['CanvasGroup']);
                            unset($groupsAndUsers[$groupName]['CanvasMember']);
                        }
                    }
                }

            }

            foreach( $this->data['iPeerGroup'] as $groupName => $groupChecked ) {
                if ($groupChecked && isset($groupsAndUsers[$groupName]) && isset($groupsAndUsers[$groupName]['Member'])) {

                    // create groupset in canvas if necessary
                    if (empty($canvasGroupCategoryId)) {
                        $canvasGroupCategory = $canvasCoursesRaw[$canvasCourseId]->createGroupCategory($this, $userId, false);
                        if ($canvasGroupCategory) {
                            $canvasGroupCategoryId = $canvasGroupCategory['id'];
                        }
                        else {
                            $this->addErrorMsg('groupExport', 'groupSetCreate', 'Error creating new group set for group ' . $groupName . '. Please try again.');
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
                            $this->addErrorMsg('groupExport', 'groupCreate', 'Error creating group ' . $groupName . '. Please try again.');
                            continue;
                        }
                    }
                    else {
                        $canvasGroup = $canvasGroups[$groupsAndUsers[$groupName]['CanvasGroup']['id']];
                    }

                    // add members
                    if ($canvasGroup) {
                        foreach ($groupsAndUsers[$groupName]['Member'] as $user){
                            if (isset($user['username'])){
                                if (in_array($user['username'], $eligibleiPeerUsers)) {

                                    // collect usernames of canvas members in this canvas group ...
                                    $canvasUsernamesInThisGroup = array();
                                    if (isset($groupsAndUsers[$groupName]['CanvasMember'])) {
                                        foreach ($groupsAndUsers[$groupName]['CanvasMember'] as $canvasMember) {
                                            $canvasUsernamesInThisGroup[] = $canvasMember[$canvasUserKey];
                                        }
                                    }
                                    // .. and those in other groups (we need all this for merging purposes)
                                    $canvasUsernamesInOtherGroups = array();
                                    foreach ($groupsAndUsers as $otherGroupName => $otherGroup) {
                                        if ($otherGroupName != $groupName && isset($groupsAndUsers[$otherGroupName]['CanvasMember'])) {
                                            foreach ($groupsAndUsers[$otherGroupName]['CanvasMember'] as $canvasMember) {
                                                $canvasUsernamesInOtherGroups[] = $canvasMember[$canvasUserKey];
                                            }
                                        }
                                    }
                                    if (in_array($user['username'], $canvasUsernamesInOtherGroups) || in_array($user['username'], $canvasUsernamesInThisGroup)) {

                                        // don't show error if the user is just in this group
                                        if (!in_array($user['username'], $canvasUsernamesInThisGroup)) {
                                            $this->addErrorMsg('groupExport', 'userTwoGroups', $user['full_name'] . ' could not be added to group "' .
                                            $groupName . '" because (s)he is already in another group.');
                                        }
                                        continue;
                                    }

                                    $canvasUserAddedId = $canvasStudents[$user['username']]->id;
                                    $userAdded = $canvasGroup->addUser($this, $userId, false, $canvasUserAddedId);
                                    if (isset($userAdded->errors)) {
                                        $errorMsgs = 'Error adding ' . $user['full_name'] . ' to group';
                                        if (is_array($userAdded->errors) && isset($userAdded->errors[0]->message)) {
                                            $errorMsgs .= ': ' . $userAdded->errors[0]->message;
                                        }
                                        elseif (isset($userAdded->errors->group_id[0]->message)) {
                                            $errorMsgs .= ': ' . $userAdded->errors->group_id[0]->message;
                                        }
                                        $this->addErrorMsg('groupExport', 'userAdd', $errorMsgs);
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
                                    }
                                }
                                else {
                                    $this->addErrorMsg('groupExport', 'userAdd', $user['full_name'] . ' was not added to the group in Canvas because they are not enrolled in this Canvas course.');
                                }
                            }
                            else {
                                $this->addErrorMsg('groupExport', 'userAdd', $user['full_name'] . ' was not added to the group in Canvas because their iPeer username is invalid.');
                            }
                        }
                    }
                }
            }
        }

        return array($groupsAndUsers, $canvasGroupCategory, $someSuccess);
    }

    private function addErrorMsg($group, $type, $error) {
        if (!isset($this->errorMessages[$group])) {
            $this->errorMessages[$group] = array();
        }
        if (!isset($this->errorMessages[$group][$type])) {
            $this->errorMessages[$group][$type] = array();
        }
        $this->errorMessages[$group][$type][] = $error;
    }

    private function getFullErrorMessage() {
        $errorOutput = '';
        $errorCount = 0;

        if (isset($this->errorMessages['titles']['general']) && !empty($this->errorMessages['titles']['general'])) {
            foreach ($this->errorMessages['titles']['general'] as $errorMessage) {
                $errorOutput .= $errorMessage;
            }
        }

        if (!empty($this->errorMessages)) {
            $errorOutput .= '<ul>';
            foreach ($this->errorMessages as $groupKey => $errorMessageGroup) {
                if ($groupKey == 'titles') {
                    continue;
                }
                if (isset($this->errorMessages['titles'][$groupKey]) && !empty($this->errorMessages['titles'][$groupKey])) {
                    $errorOutput .= '</ul>';
                    foreach ($this->errorMessages['titles'][$groupKey] as $errorMessage) {
                        $errorOutput .= $errorMessage;
                    }
                    $errorOutput .= '<ul>';
                }
                if (!empty($errorMessageGroup)) {
                    foreach ($errorMessageGroup as $errorMessageType) {
                        foreach ($errorMessageType as $errorMessage) {
                            $errorOutput .= '<li>' . $errorMessage . '</li>';
                        }
                        $errorCount++;
                    }
                }
            }
            $errorOutput .= '</ul>';

            if (isset($this->errorMessages['groupImport']['groupMemberAdd'])) {
                // add additional help
                $errorOutput .= '<p>Possible reasons why some members could not be added: </p><ul>';
                $errorOutput .= '<li>The student identifier does not exist in the system. Please add them first.</li>';
                $errorOutput .= '<li>The student is not enrolled in the course. Please enrol them first.</li>';
                $errorOutput .= '<li>The group was unable to be created or does not exist.</li>';
                $errorOutput .= '</ul>';
                $errorCount--;
            }

            if (isset($this->errorMessages['groupExport']['userTwoGroups'])) {
                // add additional help
                $errorOutput .= '<p>Please note that Canvas allows each member to be in a <u>maximum of 1 group</u> per ' .
                                'group set. Your options are as follows: </p><ul>' .
                                '<li>Change your iPeer groups so that you have each person in a maximum of 1 group.</li>' .
                                '<li>If you really want to add the student to more than one group, consider using different '.
                                'group sets and add each of the groups with the same student into different group sets.</li>';
                $errorOutput .= '</ul>';
                $errorCount--;
            }

            if ($errorCount > 0) {
                $supportEmail = $this->SysParameter->get('display.contact_info');
                $errorOutput .= '<p>If you continue having issues, please <a href="mailto:' . $supportEmail .
                                '?subject=Problem using iPeer Canvas sync feature">contact support</a>.</p>';
            }
        }

        return $errorOutput;
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
