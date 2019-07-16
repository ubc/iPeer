<?php
/**
 * EmailtemplatesController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EmailtemplatesController extends AppController
{
    public $name = 'EmailTemplates';
    public $uses = array('GroupsMembers', 'UserEnrol', 'User', 'EmailTemplate',
        'EmailMerge', 'EmailSchedule', 'Personalize', 'SysParameter', 'UserCourse',
        'Course');
    public $components = array('AjaxList', 'Session', 'RequestHandler', 'Email');
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'Js' => array('Prototype'));
    public $show;
    public $sortBy;
    public $direction;
    public $page;
    public $order;
    public $Sanitize;

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
        $this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
        if ($this->show == 'all') {
            $this->show = 99999999;
        }
        $this->sortBy = empty($_GET['sort'])? 'EmailTemplate.description': $this->Sanitize->paranoid($_GET['sort']);
        $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
        $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
        $this->order = $this->sortBy.' '.strtoupper($this->direction);
        parent::__construct();
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

        $this->set('title_for_layout', __('Email',true));
    }

        /**
     * setUpAjaxList
     *
     * @access public
     * @return void
     */
    function setUpAjaxList()
    {
        $myID = $this->Auth->user('id');

        // Set up Columns
        $columns = array(
            array("EmailTemplate.id",   "",       "",        "hidden"),
            array("EmailTemplate.name", __("Name", true),   "12em",    "action",   "View Email Template"),
            array("EmailTemplate.availability", __("Availability", true), "6em", "map", array("1" => __("public", true), "0" => __("private", true))),
            array("EmailTemplate.subject", __("Subject", true),   "12em",    "string"),
            array("EmailTemplate.description", __("Description", true), "auto",  "string"),
            array("EmailTemplate.creator_id",           "",            "",     "hidden"),
            array("EmailTemplate.creator",     __("Creator", true),  "10em", "action", "View Creator"),
            array("EmailTemplate.created", __("Creation Date", true), "10em", "date"));

        $userList = array($myID => "My Email Template");

        // Join with Users
        $jointTableCreator =
            array("id"         => "Creator_id",
                "localKey"   => "creator_id",
                "description" => __("Email Templates to show:", true),
                "default" => $myID,
                "list" => $userList,
                "joinTable"  => "users",
                "joinModel"  => "Creator");
        //put all the joins together
        $joinTables = array($jointTableCreator);

        if (User::hasPermission('functions/superadmin')) {
            $extraFilters = "";
        } else {
            $creators = array();
            // grab course ids of the courses admin/instructor has access to
            $courseIds = User::getAccessibleCourses();
            // grab all instructors that have access to the courses above
            $instructors = $this->UserCourse->findAllByCourseId($courseIds);
            $extraFilters = "(";
            // only admins will go through this loop
            foreach ($instructors as $instructor) {
                $id = $instructor['UserCourse']['user_id'];
                $creators[] = $id;
                $extraFilters .= "creator_id = $id or ";
            }
            // allow instructors/admins to see their own email templates
            $extraFilters .= "creator_id = $myID or ";
            $extraFilters .= "availability = '1')";
        }

        // Instructors can only edit their own email templates
        $restrictions = array();
        // instructors
        $basicRestrictions = array(
            $myID => true,
            "!default" => false);
        // super admins
        if (User::hasPermission('functions/superadmin')) {
            $basicRestrictions = "";
        // faculty admins
        } else if (User::hasPermission('controllers/departments')) {
            foreach ($creators as $creator) {
                $basicRestrictions = $basicRestrictions + array($creator => true);
            }
        }

        empty($basicRestrictions) ? $restrictions = $basicRestrictions :
            $restrictions['EmailTemplate.creator_id'] = $basicRestrictions;

        // Set up actions
        $warning = __("Are you sure you want to delete this email template permanently?", true);

        $actions = array(
            array(__("View Email Template", true), "", "", "", "view", "EmailTemplate.id"),
            array(__("Edit Email Template", true), "", $restrictions, "", "edit", "EmailTemplate.id"),
            array(__("Delete Email Template", true), $warning, $restrictions, "", "delete", "EmailTemplate.id"),
            array(__("View Creator", true), "",    "", "users", "view", "EmailTemplate.creator_id"));

        // Set up the list itself
        $this->AjaxList->setUp($this->EmailTemplate, $columns, $actions,
            "EmailTemplate.id", "EmailTemplate.subject", $joinTables, $extraFilters);
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
     * index
     *
     *
     * @access public
     * @return void
     */
    function index()
    {
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }

    /**
     * add
     *
     * Add an email template
     *
     * @access public
     * @return void
     */
    function add()
    {
        $this->set('title_for_layout', __('Add Email Template', true));
        // Set up user info
        $currentUser = $this->User->getCurrentLoggedInUser();
        $this->set('currentUser', $currentUser);
        $this->set('mergeList', $this->EmailMerge->getMergeList());
        if (empty($this->params['data'])) {

        } else {
            //Save Data
            $this->data['EmailTemplate'] = array_map('trim', $this->data['EmailTemplate']);
            if ($this->EmailTemplate->save($this->params['data'])) {
                $this->Session->setFlash(__('Save Successful!', true), 'good');
                $this->redirect('index');
            } else {
                $this->Session->setFlash(__('Save failed.', true));
            }
        }
    }


    /**
     * edit
     * Edit an email template
     *
     * @param mixed $id template id
     *
     * @access public
     * @return void
     */
    function edit ($id = null)
    {
        if ($this->data) {
            //Save Data
            $this->data['EmailTemplate'] = array_map('trim', $this->data['EmailTemplate']);
            if ($this->EmailTemplate->save($this->data)) {
                $this->Session->setFlash(__('Save Successful', true), 'good');
                $this->redirect('index');
            } else {
                $this->Session->setFlash(__('Failed to save', true));
            }
            return;
        }

        // retrieving the requested email template
        $template = $this->EmailTemplate->findById($id);

        $this->set('title_for_layout', __('Edit Email Template', true));
        // check to see if $id is valid - is an email template
        if (empty($template)) {
            $this->Session->setFlash(__('Error:Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        if (!User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id be in the array of accessible user ids
            if (!(in_array($template['EmailTemplate']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to edit this email template', true));
                $this->redirect('index');
            }
        }

        //Set up user info
        $currentUser = $this->User->getCurrentLoggedInUser();
        $this->set('currentUser', $currentUser);
        $this->set('mergeList', $this->EmailMerge->getMergeList());

        $this->data = $template;
    }


    /**
     * Delete an email template
     * @param <type> $id template id
     */
    function delete ($id)
    {
        // retrieving the requested email template
        $template = $this->EmailTemplate->findById($id);

        // check to see if $id is valid - numeric & is a email template
        if (empty($template)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        if (!User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id be in the array of accessible user ids
            if (!(in_array($template['EmailTemplate']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to delete this email template', true));
                $this->redirect('index');
                return;
            }
        }

        if ($this->EmailTemplate->delete($id)) {
            $this->Session->setFlash(__('The Email Template was deleted successfully.', true), 'good');
        } else {
            $this->Session->setFlash(__('Failed to delete the Email Template.', true));
        }
        $this->redirect('index');
    }

    /**
     * View an email template
     * @param <type> $id template id
     */
    function view($id)
    {
        $this->set('title_for_layout', __('View Email Template', true));   //title for view

        // retrieving the requested email template
        $template = $this->EmailTemplate->findById($id);

        // check to see if $id is valid - numeric & is a email template
        if (!is_numeric($id) || empty($template)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        // check for permissions if the email template is not public
        if ($template['EmailTemplate']['availability'] != '1' && !User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id be in the array of accessible user ids
            if (!(in_array($template['EmailTemplate']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to vie this email template', true));
                $this->redirect('index');
            }
        }

        $this->data = $this->EmailTemplate->findById($id);
        $this->set('readonly', true);
    }

    /**
     * display template content for updating field by selecting a template
     * @param <type> $templateId template id
     */
    function displayTemplateContent($templateId = null)
    {
        $this->layout = 'ajax';
        $template = $this->EmailTemplate->findById($templateId);
        $this->set('template', $template);
    }

    /**
     * displayTemplateSubject
     * display template subject for updating field by selecting a template
     *
     * @param int $templateId template id
     *
     * @access public
     * @return void
     */
    function displayTemplateSubject($templateId = null)
    {
        $this->layout = 'ajax';
        $template = $this->EmailTemplate->findById($templateId);
        $this->set('template', $template);
    }

}
