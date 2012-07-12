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
        'EmailSchedule', 'Personalize', 'SysParameter', 'SysFunction', 'Group', 'Course');
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
        $this->show = empty($_GET['show'])? 'null':$this->Sanitize->paranoid($_GET['show']);
        if ($this->show == 'all') {
            $this->show = 99999999;
        }
        $this->sortBy = empty($_GET['sort'])? 'EmailSchedule.date': $_GET['sort'];
        $this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
        $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
        $this->order = $this->sortBy . ' ' . strtoupper($this->direction);
        $this->pageTitle = 'Email';
        $this->mergeStart = '{{{';
        $this->mergeEnd = '}}}';
        parent::__construct();
    }

    /**
     * Need this to allow the send page to be accessed by unloggedin users.
     * The send page needs this free access in order to accomdate cron jobs
     * which enable the scheduled email delivery feature.
     * */
    function beforeFilter() {
        parent::beforeFilter();
        // Need to be able to cron job send, so should allow unauthed access
        $this->Auth->allow('send');
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

        $extraFilters = "";

        // Restriction for Instructor
        $restrictions = "";
        if (!User::hasRole('superadmin') && !User::hasRole('admin')) {
            $restrictions = array(
                "EmailSchedule.creator_id" => array($myID => true, "!default" => false)
            );
            $extraFilters = "(EmailSchedule.creator_id=$myID)";
        }

        // Set up actions
        $warning = __("Are you sure you want to cancel this email?", true);
        $actions = array(
            array(__("View Email", true), "", "", "", "view", "EmailSchedule.id"),
            array(__("Cancel Email", true), $warning, $restrictions, "", "cancel", "EmailSchedule.id"),
            array(__("View Creator", true), "",    "", "users", "view", "EmailSchedule.creator_id"));

        // Set up the list itself
        $this->AjaxList->setUp($this->EmailSchedule, $columns, $actions,
            "EmailSchedule.date", "EmailSchedule.id", $joinTables, $extraFilters);
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
        if (!User::hasPermission('controllers/emailer')) {
            $this->Session->setFlash(__('You do not have permission to use the emailer.', true));
            $this->redirect('/home');
        }

        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
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
        
        if (!User::hasPermission('controllers/emailer')) {
            $this->Session->setFlash(__('You do not have permission to write emails.', true));
            $this->redirect('/home');
        }

        // class, group, user
        if ('C' == $type || 'G' == $type || null != $id) {
            if ('C' == $type) {
                $group = $this->Course->find('first', array('conditions' => array('Course.id' => $id)));
            } else if ('G' == $type) {
                $group = $this->Group->find('first', array('conditions' => array('Group.id' => $id)));
            } else if (null != $id) {
                $group = $this->User->find('first', array('conditions' => array('User.id' => $id)));
            }
        
            // check for valid id
            if (empty($group)) {
                $this->Session->setFlash(__('Invalid Id', true));
                $this->redirect('index');
            }
        }
        
        // list of courses the user has access to
        $courseList = array();
        $user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));
        foreach ($user['Course'] as $course) {
            $courseList[] = $course['id'];
        }

        //for checking if the user can email to class with $id
        if ('C' == $type && !User::hasPermission('functions/email/allcourses')) {
             // check if they have access to class with $id
             $course = $this->User->find(
                 'first', 
                 array(
                    'conditions' => array(
                        'User.id' => $this->Auth->user('id'), 
                        'Course.id' => $id
                    )
                 )
            );
            if (empty($course)) {
                $this->Session->setFlash(__('You do not have permission to write emails to this class.', true));
                $this->redirect('index');
            }
        // for checking if the user can email to group with $id
        } else if ('G' == $type && !User::hasPermission('functions/email/allgroups')) {
            // check if they have access to group with $id           
            $group = $this->Group->find(
                'first', 
                array(
                    'conditions' => array(
                        'Group.id' => $id, 
                        'Course.id' => $courseList
                    )
                )
            );
            if (empty($group)) {
                $this->Session->setFlash(__('You do not have permission to write emails to this group.', true));
                $this->redirect('index');
            }
        // for checking if the user can email to user with $id
        } else if (!User::hasPermission('functions/email/allusers') && null != $id) {
            // check if they have access to user with $id
            $user = $this->User->find(
                'all', 
                array(
                    'conditions' => array(
                        'User.id' => $id, 
                        'Enrolment.id' => $courseList
                    )
                )
            );
            if (empty($user)) {
                $this->Session->setFlash(__('You do not have permission to write emails to this user.', true));
                $this->redirect('index');
            }
        }
        
    
        $this->set('title_for_layout', 'Write Email');

        if (!isset($this->data)) {
            //Get recipients' email address
            $recipients = $this->getRecipient($type, $id);
            $this->set('recipients', $recipients['Display']);
            //Write current recipients into session
            $this->Session->write('email_recipients', $recipients['Users']);
            //Users who are not in recipients of the email
            $this->set('recipients_rest', $this->User->find('list', array(
                'conditions'=>array('NOT' => array('User.id' => array_flip($this->getRecipient($type, $id, 'list')))))));
            $this->set('from', $this->Auth->user('email'));
            $this->set('templatesList', $this->EmailTemplate->getPermittedEmailTemplate($this->Auth->user('id'), 'list'));
            $this->set('templates', $this->EmailTemplate->getPermittedEmailTemplate($this->Auth->user('id')));
            $this->set('mergeList', $this->EmailMerge->getMergeList());
        } else {
            $recipients = $this->Session->read('email_recipients');
            $data = $this->data;
            $data = $data['Email'];

            $data['from'] = $this->Auth->user('id');
            $to = array();
            foreach ($recipients as $key => $r) {
                $to[$key] = $r['User']['id'];
            }
            $to = implode(';', $to);
            $data['to'] = $to;
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
        if (!User::hasPermission('controllers/emailer')) {
            $this->Session->setFlash(__('You do not have permission to cancel email schedules', true));
            $this->redirect('/home');
        }

        // retrieving the requested email schedule
        $email = $this->EmailSchedule->find(
            'first', 
            array(
                'conditions' => array('id' => $id)
            )
        );

        // check to see if $id is valid - numeric & is a email schedule
        if (!is_numeric($id) || empty($email)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
        }

        // check to see if the user is the creator, admin, or superadmin
        if (!($email['EmailSchedule']['creator_id'] == $this->Auth->user('id') || User::hasPermission('functions/email/allusers') ||
            User::hasPermission('functions/email/allgroups') || User::hasPermission('functions/email/allcourses'))) {
            $this->Session->setFlash(__('You do not have permission to cancel this email schedule.', true));
            $this->redirect('index');
        }
        
        $creator_id = $this->EmailSchedule->getCreatorId($id);
        $user_id = $this->Auth->user('id');
        if ($creator_id == $user_id) {
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
        } else {
            //If user is not creator of the email
            $this->Session->setFlash(__('No Permission', true));
            $this->redirect('/emailer/index');
        }
    }

    /**
     * View an email
     * @param <type> $id email_schedule id
     */
    function view ($id)
    {
        if (!User::hasPermission('controllers/emailer')) {
            $this->Session->setFlash(__('You do not have permission to view email schedules.', true));
            $this->redirect('/home');
        }
        
        // retrieving the requested email schedule
        $email = $this->EmailSchedule->find(
            'first', 
            array(
                'conditions' => array('id' => $id)
            )
        );

        // check to see if $id is valid - numeric & is a email schedule
        if (!is_numeric($id) || empty($email)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
        }

        if ($email['EmailSchedule']['creator_id'] !=  $this->Auth->user('id') && !(User::hasPermission('functions/email/allusers') && 
            User::hasPermission('functions/email/allgroups') && User::hasPermission('functions/email/allcourses'))) {
            $this->Session->setFlash(__('You do not have permission to view this email schedule.', true));
            $this->redirect('index');
        }

        $email['EmailSchedule']['to'] = explode(';', $email['EmailSchedule']['to']);
        $this->User->recursive = -1;
        $email['User'] = $this->User->find('all', array(
            'conditions' => array('User.id'=>$email['EmailSchedule']['to'])
        ));
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
            $users = $this->User->find($s_type, array(
                //'fields' => array('email'),
                'conditions' => array('User.id' => $this->UserEnrol->getUserListByCourse($id))
            ));
            $course = $this->Course->find('first', array(
                'fields' => array('id', 'course'),
                'conditions' => array('Course.id' => $id)
            ));
            $display['name'] = __('All students in course: ', true).$course['Course']['course'];
            $display['link'] = '/users/goToClassList/'.$course['Course']['id'];
            break;
        case 'G': //Email addresses for all in group
            $users = $this->User->find($s_type, array(
                //'fields' => array('email'),
                'conditions' => array('User.id' => $this->GroupsMembers->getMembers($id))
            ));
            $group = $this->Group->find('first', array(
                'fields' => array('id', 'group_name'),
                'conditions' => array('Group.id' => $id)
            ));
            $display['name'] = __('All students in  group: ', true).$group['Group']['group_name'];
            $display['link'] = '/groups/view/'.$group['Group']['id'];
            break;
        default: //Email address for a user
            $users = $this->User->find($s_type, array(
                //'fields' => array('email'),
                'conditions' => array('User.id' => $id)
            ));
            $user = $this->User->find('first', array(
                //'fields' => array('email'),
                'conditions' => array('User.id' => $id)
            ));
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
     * Do merge
     *
     * @param string $string  string with merge fields
     * @param int    $start   start of merge field
     * @param int    $end     end of merge field
     * @param int    $user_id user id
     *
     * @return merged string
     */
    function doMerge($string, $start, $end, $user_id = null)
    {
        //Return array $matches that contains all tags
        preg_match_all('/'.$start.'(.*?)'.$end.'/', $string, $matches, PREG_OFFSET_CAPTURE);
        $patterns = array();
        $replacements = array();
        $patterns = $matches[0];
        foreach ($matches[0] as $key => $match) {
            $patterns[$key] = '/'.$match[0].'/';

            $table = $this->EmailMerge->getFieldAndTableNameByValue($match[0]);
            $table_name = $table['table_name'];
            $field_name = $table['field_name'];
            $this->$table_name->recursive = -1;
            $value = $this->$table_name->find('first', array(
                'conditions' => array($table_name.'.id' => $user_id),
                'fields' => $field_name
            ));

            $replacements[$key] = $value[$table_name][$field_name];
        }
        return preg_replace($patterns, $replacements, $string);
    }

    /**
     * Goes through scheduled emails that have not yet been sent,
     * send them if they're due and mark them them as sent.
     */
    public function send() {
        $this->layout = 'ajax';
        $emails = $this->EmailSchedule->getEmailsToSend();

        foreach ($emails as $e) {
            $e = $e['EmailSchedule'];

            $from_id = $e['from'];
            $from = $this->getEmailAddress($from_id);
            // TODO what to do if no from address?

            $to_ids = explode(';', $e['to']);
            foreach ($to_ids as $to_id) {
                $to = $this->getEmailAddress($to_id);
                $subject = $e['subject'];
                $content = $this->doMerge($e['content'], $this->mergeStart, $this->mergeEnd, $to_id);
                if ($this->_sendEmail($content, $subject, $from, $to)) {
                    $tmp = array('id' => $e['id'], 'sent' => '1');
                    $this->EmailSchedule->save($tmp);
                } else {
                    echo __("Failed", true);
                }

            }
        }
    }

    /**
     * Given a user id, get the email address associated with that id, if any.
     *
     * @param int $id - the user id
     *
     * @return The user's email address, if it exists, empty string if not
     */
    private function getEmailAddress($id) {
        return $this->User->field('email', array('User.id' => $id));
    }
}
