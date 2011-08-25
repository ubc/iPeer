<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailerController extends AppController
{
  var $name = 'Emailer';
  var $uses = array('GroupsMembers', 'UserEnrol', 'User', 'EmailTemplate', 'EmailMerge', 
      'EmailSchedule', 'Personalize', 'SysParameter', 'SysFunction', 'Group', 'Course');
  var $components = array('AjaxList', 'Session', 'RequestHandler', 'Email');
  var $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'Pagination', 'Js' => array('Prototype'));
  var $show;
  var $sortBy;
  var $direction;
  var $page;
  var $order;
  var $Sanitize;

  function __construct(){
    $this->Sanitize = new Sanitize;
    $this->show = empty($_GET['show'])? 'null':$this->Sanitize->paranoid($_GET['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->sortBy = empty($_GET['sort'])? 'EmailSchedule.date': $_GET['sort'];
    $this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
    $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
    $this->order = $this->sortBy . ' ' . strtoupper($this->direction);
    $this->pageTitle = 'Email';
    $this->mergeStart = '{{{';
    $this->mergeEnd = '}}}';
    parent::__construct();
  }

  function setUpAjaxList() {
    $myID = $this->Auth->user('id');

    // Set up Columns
    $columns = array(
            array("EmailSchedule.id",   "",       "",        "hidden"),
            array("EmailSchedule.subject", __("Subject", true),   "auto",    "action",   "View Email"),
            array("EmailSchedule.date", __("Scheduled On", true),"15em",  "date"),
            array("EmailSchedule.sent",       __("Sent", true),         "5em",   "map",
                     array(  "0" => __("Not Yet", true),  "1" => __("Sent", true))),
            array("EmailSchedule.creator_id",           "",            "",     "hidden"),
            array("EmailSchedule.created", __("Creation Date", true), "15em", "date"));

    $userList = array($myID => "My Email");
    
    // Join with Users
    $jointTableCreator =
      array("id"         => "Creator.id",
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
    if ($this->Auth->user('role') != 'A') {
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

  function ajaxList() {
    // Set up the list
    $this->setUpAjaxList();
    // Process the request for data
    $this->AjaxList->asyncGet();
  }

  /**
   * Index
   */
  function index(){
    // Set up the basic static ajax list variables
    $this->setUpAjaxList();
    // Set the display list
    $this->set('paramsForList', $this->AjaxList->getParamsForList());
  }

  /**
   * Write an email
   * @param $to parameter for recipients
   */
  function write($to = ' '){
    //TODO: preview function
    if(isset($this->params['form']['preview'])){
      $this->render('preview');
    }
    else{
      if(!isset($this->data)){
        //Get recipients' email address
        $emailAddress = $this->getEmailAddress($to);
        if(is_array($emailAddress))
          $emailAddress = implode('; ', $emailAddress);

        $recipients = $this->getRecipient($to);
        $this->set('recipients', $recipients['Display']);
        //Write current recipients into session
        $this->Session->write('email_recipients', $recipients['Users']);
        //Users who are not in recipients of the email
        $this->set('recipients_rest', $this->User->find('list', array(
            'conditions'=>array('NOT' => array('User.id' => array_flip($this->getRecipient($to, 'list')))))));
        $this->set('from', $this->Auth->user('email'));
        $this->set('templatesList', $this->EmailTemplate->getPermittedEmailTemplate($this->Auth->user('id'),'list'));
        $this->set('templates', $this->EmailTemplate->getPermittedEmailTemplate($this->Auth->user('id')));
        $this->set('mergeList', $this->EmailMerge->getMergeList());
      }
      else{
        $recipients = $this->Session->read('email_recipients');
        $data = $this->data;
        $data = $data['Email'];

        $data['from'] = $this->Auth->user('id');
        $to = array();
        foreach($recipients as $key => $r){
          $to[$key] = $r['User']['id'];
        }
        $to = implode(';', $to);
        $data['to'] = $to;
        $date = $data['date'];

        //Set current date if no schedule
        if(!$data['schedule']){
          $data['date'] = date("Y-m-d H:i:s");
        }
        else{
          $tmp_data = array();
          for($i=1; $i<=$data['times']; $i++){
            $tmp_data[$i] = $data;
            $tmp_data[$i]['date'] = date("Y-m-d H:i:s", strtotime($date) + ($i-1)*$data['interval_type']*$data['interval_num']);
          }
          $data = $tmp_data;
        }        

        //Push an email into email_schedules table
        $this->EmailSchedule->saveAll($data);        

        $this->Session->setFlash(__('The Email was saved successfully', true));
        $this->redirect('index/');
      }
    }
  }

  /**
   * Cancel(Delete) an email from email_schedules table
   * @param <type> $id email_schedule id
   */
  function cancel ($id) {
    $creator_id = $this->EmailSchedule->getCreatorId($id);
    $user_id = $this->Auth->user('id');
    if($creator_id == $user_id){
      if(!$this->EmailSchedule->getSent($id)){
        if ($this->EmailSchedule->delete($id)) {
          $this->Session->setFlash(__('The Email was canceled successfully.', true));
        } else {
          $this->Session->setFlash(__('Email cancellation failed.', true));
        }
        $this->redirect('index/');
      }
      else{//If email is already sent
        $this->Session->setFlash(__('Cannot cancel: Email is already sent.', true));
        $this->redirect('index/');
      }
    }
    else{//If user is not creator of the email
      $this->Session->setFlash(__('No Permission', true));
      $this->redirect('/emailer/index');
    }
  }

  /**
   * View an email
   * @param <type> $id email_schedule id
   */
  function view ($id){
    $email = $this->EmailSchedule->find('first', array(
        'conditions' => array('EmailSchedule.id' => $id)
    ));
    $email['EmailSchedule']['to'] = explode(';', $email['EmailSchedule']['to']);
    $this->User->recursive = -1;
    $email['User'] = $this->User->find('all', array(
        'conditions' => array('User.id'=>$email['EmailSchedule']['to'])
    ));
    $this->set('data', $email);
  }

  /**
   * Add a recipient
   */
  function addRecipient() {
    if((!isset($this->passedArgs['recipient_id'])) &&
       (!isset($this->params['form']['recipient_id']))) {
      $this->cakeError('error404');
    }

    $recipient_id = isset($this->passedArgs['recipient_id']) ? $this->passedArgs['recipient_id'] : $this->params['form']['recipient_id'];
    $this->User->recursive = -1;
    if(!($recipient = $this->User->find('first', array('conditions' => array('User.id' => $recipient_id))))) {
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

  //Delete a recipient
  function deleteRecipient() {
    if(!isset($this->passedArgs['recipient_id'])) {
      $this->cakeError('error404');
    }
    $tmp_index = $this->searchByUserId($this->Session->read('email_recipients'),'id', $this->passedArgs['recipient_id']);
    //Unset the recipient from the session
    $tmp_recipients = $this->Session->read('email_recipients');
    unset($tmp_recipients[$tmp_index]);
    $this->Session->write('email_recipients', $tmp_recipients);
    $this->autoRender = false;
    $this->ajax = true;
  }

  /**
   * Get email addresses
   * @param $to recipients
   * @return List of users' email address
   */
  function getEmailAddress($to){

    $type = $to[0];
    $id = substr($to,1);
    switch($type){
      case ' ':
        return '';
        break;
      case 'C': //Email addresses for all in Course
        return $this->User->find('list', array(
            'fields' => array('email'),
            'conditions' => array('User.id' => $this->UserEnrol->getUserListByCourse($id))
        ));
        break;
      case 'G': //Email addresses for all in group
        return $this->User->find('list', array(
            'fields' => array('email'),
            'conditions' => array('User.id' => $this->GroupsMembers->getMembers($id))
        ));
        break;
      default: //Email address for a user
        return $this->User->find('list', array(
            'fields' => array('email'),
            'conditions' => array('User.id' => $to)
        ));

    }
  }

  /**
   * Get recipients
   * @param <type> $to recipients
   * @param <type> $s_type find type
   * @return array of recipients and info
   */
  function getRecipient($to, $s_type = 'all'){
    $result = array();
    $this->User->recursive = -1;
    $type = $to[0];
    $id = substr($to,1);
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
            'fields' => array('id','course'),
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
            'fields' => array('id','group_name'),
            'conditions' => array('Group.id' => $id)
        ));
        $display['name'] = __('All students in  group: ', true).$group['Group']['group_name'];
        $display['link'] = '/groups/view/'.$group['Group']['id'];
        break;
      default: //Email address for a user
        $users = $this->User->find($s_type, array(
            //'fields' => array('email'),
            'conditions' => array('User.id' => $to)
        ));
        $user = $this->User->find('first', array(
            //'fields' => array('email'),
            'conditions' => array('User.id' => $to)
        ));
        $display['name'] = $user['User']['full_name'];
        $display['link'] = '/users/view/'.$user['User']['id'];

    }
    $result['Users'] = $users;
    $result['Display'] = $display;
    if($s_type == 'list')
      return $users;
    else
      return $result;
  }

  /**
   * Get index of user in array
   * @param <type> $array array
   * @param <type> $key key
   * @param <type> $value value
   * @return index of searched user
   */
  function searchByUserId($array, $key, $value)
  {
      $i = 0;
      if (is_array($array))
      {
          foreach ($array as $subarray){
            if($subarray['User'][$key]==$value)
              return $i;
            $i++;
          }
      }
  }

  /**
   * Do merge
   * @param $string string with merge fields
   * @param $start start of merge field
   * @param $end end of merge field
   * @param $user_id user id
   * @return merged string
   */
  function doMerge($string, $start, $end, $user_id = null){
    //Return array $matches that contains all tags
    preg_match_all('/'.$start.'(.*?)'.$end.'/', $string, $matches, PREG_OFFSET_CAPTURE);
    $patterns = array();
    $replacements = array();
    $merge_count = 0;
    $patterns = $matches[0];
    foreach($matches[0] as $key => $match){      
      $patterns[$key] = '/'.$match[0].'/';
      
      $table = $this->EmailMerge->getFieldAndTableNameByValue($match[0]);
      $table_name = $table['table_name'];
      $field_name = $table['field_name'];
      $this->$table_name->recursive = -1;
      $value = $this->$table_name->find('first',array(
          'conditions' => array($table_name.'.id' => $user_id),
          'fields' => $field_name
      )); 

      $replacements[$key] = $value[$table_name][$field_name];
    }
    return preg_replace($patterns, $replacements, $string);
  }

  /**
   * Send emails 
   */
  function send(){
    $this->layout = 'ajax';
    $emails = $this->EmailSchedule->getEmailsToSend();

    foreach($emails as $e){
      $e = $e['EmailSchedule'];

      $from_id = $e['from'];
      $from = $this->User->getEmailById($from_id);

      $to_ids = explode(';', $e['to']);
      foreach($to_ids as $to_id){        
        $to = $this->User->getEmailById($to_id);
        $subject = $e['subject'];
        $content = $this->doMerge($e['content'], $this->mergeStart, $this->mergeEnd, $to_id);
        if($this->_sendEmail($content,$subject,$from,$to)){
          $tmp = array('id' => $e['id'],'sent' => '1');
          $this->EmailSchedule->save($tmp);          
        }
        else{
          echo __("Failed", true);
        }

      }
    }
  }


}
?>