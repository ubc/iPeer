<?php
/**
 * EmailerController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EmailerController extends AppController
{
    public $name = 'Emailer';
    public $uses = array('GroupsMembers', 'UserEnrol', 'User', 'EmailTemplate', 'EmailMerge',
        'EmailSchedule', 'Personalize', 'SysParameter', 'Group', 'Course', 'UserCourse',
        'UserTutor', 'Event', 'Penalty');
    public $components = array('AjaxList', 'Session', 'RequestHandler', 'Email');
    public $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'Js' => array('Prototype'));

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
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

        $this->pageTitle = __('Email',true);
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
            array("EmailSchedule.id",   "",       "",        "hidden"),
            array("EmailSchedule.subject", __("Subject", true),   "auto",    "action",   "View Email"),
            array("EmailSchedule.date", __("Scheduled On", true), "15em",  "date"),
            array("EmailSchedule.sent",       __("Sent", true),         "5em",   "map",
            array(  "0" => __("Not Yet", true),  "1" => __("Sent", true))),
            array("EmailSchedule.creator_id",           "",            "",     "hidden"),
            array("EmailSchedule.created", __("Creation Date", true), "15em", "date"));

        $userList = array($myID => "My Email");

        // Join with Users
        $jointTableCreator =
            array("id"         => "Creator_id",
                "localKey"   => "creator_id",
                "description" => __("Email to show:", true),
                "default" => $myID,
                "list" => $userList,
                "joinTable"  => "users",
                "joinModel"  => "Creator");
        //put all the joins together
        $joinTables = array($jointTableCreator);

        // super admin
        if (User::hasPermission('functions/superadmin')) {
            $extraFilters = '';
            $restrictions = '';
        } else {
            $courseIds = User::getAccessibleCourses();
            $restrictions['EmailSchedule.creator_id'] = array($myID => true, "!default" => false);
            // instructor - only their own and emails for one of their accessible courses
            $extraFilters = "(";
            foreach ($courseIds as $courseId) {
                $extraFilters .= "course_id = $courseId or ";
            }
            // admin - their own and emails from instructors and courses in their departments
            if (User::hasPermission('controllers/departments')) {
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $creators = Set::extract('/UserCourse/user_id', $instructors);
                foreach ($creators as $creator) {
                    $extraFilters .= "creator_id = $creator or ";
                    $restrictions['EmailSchedule.creator_id'][$creator] = true;
                }
            }
            $extraFilters .= "creator_id = $myID)";
        }

        // Set up actions
        $warning = __("Are you sure you want to cancel this email?", true);
        $actions = array(
            array(__("View Email", true), "", "", "", "view", "EmailSchedule.id"),
            array(__("Cancel Email", true), $warning, $restrictions, "", "cancel", "EmailSchedule.id"),
            array(__("View Creator", true), "",    "", "users", "view", "EmailSchedule.creator_id"));

        // Set up the list itself
        $this->AjaxList->setUp($this->EmailSchedule, $columns, $actions,
            "EmailSchedule.date", "EmailSchedule.subject", $joinTables, $extraFilters);
    }

    /**
     * ajaxList
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
    public function index()
    {
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        $this->set('reminder_enabled', $this->SysParameter->get('email.reminder_enabled', true));
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }

    /**
     * Write an email to either a class, group, or user.
     *
     * There are 3 possible character values for type:
     * 'C' - for class
     * 'G' - for group
     * ' ' - no clue what this is used for, but it returns an empty list
     *
     * All others characters indicate single user.
     *
     * @param string $type - a class, group, or user
     * @param int $id - the id of the class, group, or user we're writing to
     *
     * @access public
     * @return void
     */
    public function write($type=null, $id=null) {
        // class, group, user
        if ('C' == $type || 'G' == $type || null != $id) {
            if ('C' == $type) {
                $group = $this->Course->findById($id);
                $this->breadcrumb->push(array('course' => $group['Course']));
                $courseId = $id;
            } else if ('G' == $type) {
                $group = $this->Group->findById($id);
                $courseId = $group['Group']['course_id'];
                $groupId = $id;
            } else if (null != $id) {
                $group = $this->User->findById($id);
            }

            // check for valid id
            if (empty($group)) {
                $this->Session->setFlash(__('Invalid Id', true));
                $this->redirect('index');
            }
        }

        $courseList = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');

        //for checking if the user can email to class with $id
        if ('C' == $type && !User::hasPermission('functions/email/allcourses')) {
            if (!in_array($id, array_keys($courseList))) {
                $this->Session->setFlash(__('Error: You do not have permission to write emails to this course', true));
                $this->redirect('index');
            }
        // for checking if the user can email to group with $id
        } else if ('G' == $type && !User::hasPermission('functions/email/allgroups')) {
            // check if they have access to group with $id
            $group = $this->Group->findById($id);
            if (!in_array($group['Course']['id'], array_keys($courseList))) {
                $this->Session->setFlash(__('Error: You do not have permission to write emails to this group', true));
                $this->redirect('index');
            }
        }


        $this->set('breadcrumb', $this->breadcrumb->push(__('Write Email', true)));

        if (!isset($this->data)) {
            //Get recipients' email address
            $courseId = isset($courseId) ? $courseId : null;
            $grpId = isset($groupId) ? $groupId : null;
            $recipients = $this->getRecipient($type, $id);
            $this->set('recipients', $recipients['Display']);
            //Write current recipients into session
            $this->Session->write('email_recipients', $recipients['Users']);
            // Write the course id (and group id)
            $this->Session->write('grp_id', $grpId);
            $this->Session->write('course_id', $courseId);
            //Users who are not in recipients of the email
            $this->set('recipients_rest', $this->User->find('list', array(
                'conditions'=>array('NOT' => array('User.id' => Set::extract('/User/id', $recipients['Users']))))));
            $this->set('templatesList', $this->EmailTemplate->getPermittedEmailTemplate($this->Auth->user('id'), 'list'));
            $this->set('mergeList', $this->EmailMerge->getMergeList());
        } else {
            $recipients = $this->Session->read('email_recipients');
            $data = $this->data;
            $data = $data['Email'];

            $data['from'] = $this->Auth->user('id');
            $to = Set::extract('/User/id', $recipients);
            $to = implode(';', $to);
            $data['to'] = $to;
            $data['course_id'] = $this->Session->read('course_id');
            $data['grp_id'] = $this->Session->read('grp_id');
            $date = $data['date'];

            //Set current date if no schedule
            if ("" == $data['date']) {
                $data['date'] = date("Y-m-d H:i:s");
            } else {
                $tmp_data = array();
                for ($i=1; $i<=$data['times']; $i++) {
                    $tmp_data[$i] = $data;
                    $tmp_data[$i]['date'] = date("Y-m-d H:i:s", strtotime($date) + ($i-1)*$data['interval_type']*$data['interval_num']);
                }
                $data = $tmp_data;
            }

            //Push an email into email_schedules table
            $data = $this->_multiMap($data);
            $this->EmailSchedule->saveAll($data);

            $this->Session->setFlash(__('The Email was saved successfully', true), 'good');
            $this->redirect('index/');
        }
    }


    /**
     * Cancel(Delete) an email from email_schedules table
     * @param <type> $id email_schedule id
     */
    function cancel ($id)
    {
        // retrieving the requested email schedule
        $email = $this->EmailSchedule->findById($id);

        // check to see if $id is valid - numeric & is a email schedule
        if (!is_numeric($id) || empty($email)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
        }

        if (!User::hasPermission('functions/superadmin')) {
            $courseIds = User::getAccessibleCourses();
            $instructorIds = array();
            if (User::hasPermission('controllers/departments')) {
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract('/UserCourse/user_id', $instructors);
            }
            // add the user's id
            array_push($instructorIds, $this->Auth->user('id'));

            // creator's id must be in the array of accessible user ids OR
            // they have access to the course
            if (!(in_array($email['EmailSchedule']['creator_id'], $instructorIds) ||
                in_array($email['EmailSchedule']['course_id'], $courseIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to cancel this email schedule', true));
                $this->redirect('index');
            }
        }

        if (!$this->EmailSchedule->getSent($id)) {
            if ($this->EmailSchedule->delete($id)) {
                $this->Session->setFlash(__('The Email was canceled successfully.', true), 'good');
            } else {
                $this->Session->setFlash(__('Email cancellation failed.', true));
            }
            $this->redirect('index/');
        } else {
            //If email is already sent
            $this->Session->setFlash(__('Cannot cancel: Email is already sent.', true));
            $this->redirect('index/');
        }
    }

    /**
     * View an email
     * @param <type> $id email_schedule id
     */
    function view ($id)
    {
        // retrieving the requested email schedule
        $email = $this->EmailSchedule->findById($id);

        // check to see if $id is valid - numeric & is a email schedule
        if (!is_numeric($id) || empty($email)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
        }

        if (!User::hasPermission('functions/superadmin')) {
            $courseIds = User::getAccessibleCourses();
            $instructorIds = array();
            if (User::hasPermission('controllers/departments')) {
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract('/UserCourse/user_id', $instructors);
            }
            // add the user's id
            array_push($instructorIds, $this->Auth->user('id'));

            // creator's id must be in the array of accessible user ids OR
            // they have access to the course
            if (!(in_array($email['EmailSchedule']['creator_id'], $instructorIds) ||
                in_array($email['EmailSchedule']['course_id'], $courseIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to view this email schedule', true));
                $this->redirect('index');
            }
        }
        
        // event reminders - grab email template's content
        if (isset($email['EmailSchedule']['event_id'])) {
            $template = $this->EmailTemplate->findById($email['EmailSchedule']['content']);
            $email['EmailSchedule']['content'] = $template['EmailTemplate']['content'] ? $template['EmailTemplate']['content'] : "";
        }

        $email['EmailSchedule']['to'] = explode(';', $email['EmailSchedule']['to']);
        $email['EmailSchedule']['content'] = str_replace("\n", '<br/>', $email['EmailSchedule']['content']);
        $this->User->recursive = -1;
        $email['User'] = $this->User->findAllById($email['EmailSchedule']['to']);
        $this->set('data', $email);
    }

    /**
     * addRecipient
     * Add a recipient
     *
     * @access public
     * @return void
     */
    function addRecipient()
    {
        if ((!isset($this->passedArgs['recipient_id'])) && (!isset($this->params['form']['recipient_id']))) {
                $this->cakeError('error404');
        }

        $recipient_id = isset($this->passedArgs['recipient_id']) ? $this->passedArgs['recipient_id'] : $this->params['form']['recipient_id'];
        $this->User->recursive = -1;
        if (!($recipient = $this->User->find('first', array('conditions' => array('User.id' => $recipient_id))))) {
            $this->cakeError('error404');
        }

        //$this->autoRender = false;
        $this->layout = false;
        $this->ajax = true;

        $tmp_recipients = $this->Session->read('email_recipients');
        array_push($tmp_recipients, $recipient);
        //Store added recipient to session
        $this->Session->write('email_recipients', $tmp_recipients);
        $this->set('recipient', $recipient['User']);
        $this->render('/elements/emailer/edit_recipient');

    }

    /**
     * deleteRecipient
     * Delete a recipient
     *
     * @access public
     * @return void
     */
    function deleteRecipient()
    {
        if (!isset($this->passedArgs['recipient_id'])) {
            $this->cakeError('error404');
        }
        $tmp_index = $this->searchByUserId($this->Session->read('email_recipients'), 'id', $this->passedArgs['recipient_id']);
        //Unset the recipient from the session
        $tmp_recipients = $this->Session->read('email_recipients');
        unset($tmp_recipients[$tmp_index]);
        $this->Session->write('email_recipients', $tmp_recipients);
        $this->ajax = true;
    }

    /**
     * Get a list of email addresses from the id. If type is a class, then
     * we get the email address of all the students in the class. Same if
     * it's a group. If it's a single user, then we only get a single address.
     *
     * There are 3 possible character values for type:
     * 'C' - for class
     * 'G' - for group
     * ' ' - no clue what this is used for, but it returns an empty list
     *
     * All others characters indicate single user.
     *
     * @param string $type - a class, group, or user
     * @param int $id - the id of the class, group, or user we're writing to
     * @param string $s_type - first parameter of a model's find method
     *
     * @return array of recipients and info
     */
    function getRecipient($type, $id, $s_type = 'all')
    {
        $result = array();
        $this->User->recursive = -1;
        switch($type){
        case ' ':
            $display = array();
            $users = array();
            break;
        case 'C': //Email addresses for all in Course
            $users = $this->UserEnrol->find($s_type, array(
                'conditions' => array('UserEnrol.course_id' => $id),
                'contain' => array('User')
            ));
            $users = Set::extract('/User', $users);
            $course = $this->Course->findById($id);
            $display['name'] = __('All students in course: ', true).$course['Course']['course'];
            $display['link'] = '/users/goToClassList/'.$course['Course']['id'];
            break;
        case 'G': //Email addresses for all in group
            $users = $this->User->find($s_type, array(
                'conditions' => array('User.id' => $this->GroupsMembers->getMembers($id))
            ));
            $group = $this->Group->findById($id);
            $display['name'] = __('All students in  group: ', true).$group['Group']['group_name'];
            $display['link'] = '/groups/view/'.$group['Group']['id'];
            break;
        default: //Email address for a user
            $users = $this->User->find($s_type, array(
                'conditions' => array('User.id' => $id)
            ));
            $user = $this->User->findById($id);
            $display['name'] = $user['User']['full_name'];
            $display['link'] = '/users/view/'.$user['User']['id'];

        }
        $result['Users'] = $users;
        $result['Display'] = $display;
        if ($s_type == 'list') {
            return $users;
        } else {
            return $result;
        }
    }

    /**
     * Get index of user in array
     *
     * @param array  $array array
     * @param string $key   key
     * @param mixed  $value value
     *
     * @return index of searched user
     */
    function searchByUserId($array, $key, $value)
    {
        $i = 0;
        if (is_array($array)) {
            foreach ($array as $subarray) {
                if ($subarray['User'][$key]==$value) {
                    return $i;
                }
                $i++;
            }
        }
    }
    
    /**
     * _multiMap
     *
     * @param mixed $data data
     *
     * @access private
     * @return void
     */
    function _multiMap($data)
    {
        $ret = array();
        foreach($data as $key => $value) {
            $ret[$key] = is_array($value) ? $this->_multiMap($value) : trim($value);
        }
        return $ret;
    }
}
