<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

App::import('Lib', 'neat_string');

class EmailerController extends AppController
{
  var $name = 'Emailer';
  var $uses = array('GroupsMembers', 'UserEnrol', 'User', 'EmailTemplate', 'EmailMerge', 'EmailSchedule', 'Personalize', 'SysParameter', 'SysFunction');
  var $components = array('AjaxList', 'Session', 'RequestHandler', 'Email');
  var $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'Pagination', 'Js' => array('Prototype'));
  var $show;
  var $sortBy;
  var $direction;
  var $page;
  var $order;
  var $NeatString;
  var $Sanitize;

  function __construct(){
    $this->Sanitize = new Sanitize;
    $this->NeatString = new NeatString;
    $this->show = empty($_GET['show'])? 'null':$this->Sanitize->paranoid($_GET['show']);
    if ($this->show == 'all') $this->show = 99999999;
    $this->sortBy = empty($_GET['sort'])? 'EmailTemplate.description': $_GET['sort'];
    $this->direction = empty($_GET['direction'])? 'asc': $this->Sanitize->paranoid($_GET['direction']);
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
            array("EmailTemplate.id",   "",       "",        "hidden"),
            array("EmailTemplate.name", "Name",   "12em",    "action",   "View Email Template"),
            array("EmailTemplate.description", "Description","auto",  "action", "View Email Template"),
            array("EmailTemplate.creator_id",           "",            "",     "hidden"),
            array("EmailTemplate.creator",     "Creator",  "10em", "action", "View Creator"),
            array("EmailTemplate.created", "Creation Date", "10em", "date"));

    $userList = array($myID => "My Email Template");

    // Join with Users
    $jointTableCreator = 
      array("id"         => "Creator.id",
            "localKey"   => "creator_id",
            "description" => "Email Template to show:",
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
          "EmailTemplate.creator_id" => array($myID => true, "!default" => false)
      );
      $extraFilters = "(EmailTemplate.creator_id=$myID or EmailTemplate.availability='0')";
    }

    // Set up actions
    $warning = "Are you sure you want to delete this email template permanently?";
    $actions = array(
                     array("View Email Template", "", "", "", "view", "EmailTemplate.id"),
                     array("Edit Email Template", "", $restrictions, "", "edit", "EmailTemplate.id"),
                     array("Delete Email Template", $warning, $restrictions, "", "delete", "EmailTemplate.id"),
                     array("View Creator", "",    "", "users", "view", "EmailTemplate.creator_id"));

    // Set up the list itself
    $this->AjaxList->setUp($this->EmailTemplate, $columns, $actions,
                           "EmailTemplate.id", "EmailTemplate.name", $joinTables, $extraFilters);
  }

  function ajaxList() {
    // Set up the list
    $this->setUpAjaxList();
    // Process the request for data
    $this->AjaxList->asyncGet();
  }

  function index(){
    // Set up the basic static ajax list variables
    $this->setUpAjaxList();
    // Set the display list
    $this->set('paramsForList', $this->AjaxList->getParamsForList());
  }

  function write($to = ' '){
    if(!isset($this->data)){
      $emailAddress = $this->getEmailAddress($to);
      if(is_array($emailAddress))
        $emailAddress = implode('; ', $emailAddress);

      $recipients = $this->getRecipient($to);
      $this->set('recipients', $recipients);
      $this->Session->write('email_recipients', $recipients);
      $this->set('recipients_rest', $this->User->find('list', array(
          'conditions'=>array('NOT' => array('User.id' => array_flip($this->getRecipient($to, 'list')))))));
      $this->set('to', $emailAddress);      
      $this->set('from', $this->Auth->user('email'));
      $this->set('templatesList', $this->EmailTemplate->getPermittedEmailTemplate($this->Auth->user('id'),'list'));
      $this->set('templates', $this->EmailTemplate->getPermittedEmailTemplate($this->Auth->user('id')));
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

      //Set current date if no schedule
      if(!$data['schedule'])
        $data['date'] = date("Y-m-d H:i:s", time());

      $this->EmailSchedule->save($data);

      //Display for testing
      $this->set('data', $data);
      $this->render('confirmation');      
    }        
  }

  function add(){
    //Set up user info
    $currentUser = $this->User->getCurrentLoggedInUser();
    $this->set('currentUser', $currentUser);
    $this->set('mergeList', $this->EmailMerge->getMergeList());
    if (empty($this->params['data'])) {

    }
    else{
      //Save Data
      if ($this->EmailTemplate->save($this->params['data'])) {
        $this->Session->setFlash('Successful');
        $this->redirect('/emailer/index');
      }
      else{
        $this->Session->setFlash('Failed to save');
      }
    }

  }

  function edit ($id){
    $creator_id = $this->EmailTemplate->getCreatorId($id);
    $user_id = $this->Auth->user('id');
    if($creator_id == $user_id){
      //Set up user info
      $currentUser = $this->User->getCurrentLoggedInUser();
      $this->set('currentUser', $currentUser);
      $this->set('mergeList', $this->EmailMerge->getMergeList());

      $data = $this->EmailTemplate->find('first', array(
          'conditions' => array('EmailTemplate.id' => $id)
      ));

      if (empty($this->params['data'])) {
          $this->data = $data;
          $this->render('add');
      }
      else{
        //Save Data
        if ($this->EmailTemplate->save($this->params['data'])) {
          $this->Session->setFlash('Successful');
          $this->redirect('/emailer/index');
        }
        else{
          $this->Session->setFlash('Failed to save');
        }
      }
    }
    else{
      $this->Session->setFlash('No Permission');
      $this->redirect('/emailer/index');
    }
  }

  function delete ($id) {
    $creator_id = $this->EmailTemplate->getCreatorId($id);
    $user_id = $this->Auth->user('id');
    if($creator_id == $user_id){
      if ($this->EmailTemplate->delete($id)) {
        $this->Session->setFlash('The Email Template was deleted successfully.');
      } else {
        $this->Session->setFlash('Email Template delete failed.');
      }
      $this->redirect('index/');
    }
    else{
      $this->Session->setFlash('No Permission');
      $this->redirect('/emailer/index');
    }
  }

  function view ($id){
    $this->data = $this->EmailTemplate->find('first', array(
        'conditions' => array('EmailTemplate.id' => $id)
    ));
    $this->set('readonly', true);
    $this->render('add');

  }

  function displayTemplate($templateId = null) {
      $this->layout = 'ajax';
      $template = $this->EmailTemplate->find('first', array(
          'conditions' => array('EmailTemplate.id' => $templateId)
      ));      
      $this->set('template', $template);
  }

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
    $this->Session->write('email_recipients', $tmp_recipients);
    $this->set('recipient', $recipient['User']);
    $this->render('/elements/emailer/edit_recipient');

  }

  function deleteRecipient() {
    if(!isset($this->passedArgs['recipient_id'])) {
      $this->cakeError('error404');
    }
    $tmp_index = $this->searchByUserId($this->Session->read('email_recipients'),'id', $this->passedArgs['recipient_id']);
    $tmp_recipients = $this->Session->read('email_recipients');
    unset($tmp_recipients[$tmp_index]);
    $this->Session->write('email_recipients', $tmp_recipients);
    $this->autoRender = false;
    $this->ajax = true;
  }

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

  function getRecipient($to, $s_type = 'all'){
    $this->User->recursive = -1;
    $type = $to[0];
    $id = substr($to,1);
    switch($type){
      case ' ':
        return array();
        break;
      case 'C': //Email addresses for all in Course
        return $this->User->find($s_type, array(
            //'fields' => array('email'),
            'conditions' => array('User.id' => $this->UserEnrol->getUserListByCourse($id))
        ));
        break;
      case 'G': //Email addresses for all in group
        return $this->User->find($s_type, array(
            //'fields' => array('email'),
            'conditions' => array('User.id' => $this->GroupsMembers->getMembers($id))
        ));
        break;
      default: //Email address for a user
        return $this->User->find($s_type, array(
            //'fields' => array('email'),
            'conditions' => array('User.id' => $to)
        ));

    }
  }

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

  function doMerge($string, $start, $end, $user_id = null){
    //Return array $matches that contains all tags
    preg_match_all('/'.$start.'(.*?)'.$end.'/', $string, $matches, PREG_OFFSET_CAPTURE);
    $patterns = array();
    $replacements = array();
    $merge_count = 0;
    $patterns = $matches[0];
    foreach($matches[0] as $key => $match){      
      $patterns[$key] = '/'.$match[0].'/';
      
      $table = $this->EmailMerge->getFieldNameByValue($match[0]);
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
          
          var_dump($this->Session->read('Message.email'));
        }

      }
    }
  }


}
?>

