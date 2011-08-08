<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

App::import('Lib', 'neat_string');

class EmailerController extends AppController
{
  var $name = 'Emailer';
  var $uses = array('GroupsMembers', 'UserEnrol', 'User', 'EmailTemplate', 'EmailMerge','Personalize', 'SysParameter', 'SysFunction');
  var $components = array('AjaxList', 'Session', 'RequestHandler', 'Email');
  var $helpers = array('Html', 'Ajax', 'Javascript', 'Time', 'Pagination');
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
      $this->set('to', $emailAddress);
      $this->set('from', $this->Auth->user('email'));
      $this->set('templatesList', $this->EmailTemplate->getPermittedEmailTemplate($this->Auth->user('id'),'list'));
      $this->set('templates', $this->EmailTemplate->getPermittedEmailTemplate($this->Auth->user('id')));
    }
    else{
      $this->set('data', $this->data);
      $this->render('confirmation');      
    }    

    //_sendEmail($template,$subject,$from,$to)
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
      if ($this->EmailTemplate->save($this->params['data'])) {$this->log($this->params['data']);
        $this->Session->setFlash('Successful');
        $this->redirect('/emailer/index');
      }
      else{
        $this->Session->setFlash('Failed to save');
      }
    }

  }

  function edit ($id){
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
      if ($this->EmailTemplate->save($this->params['data'])) {$this->log($this->params['data']);
        $this->Session->setFlash('Successful'); 
        $this->redirect('/emailer/index');
      }
      else{
        $this->Session->setFlash('Failed to save');
      }
    }
  }

  function delete ($id) {
    if ($this->EmailTemplate->delete($id)) {
      $this->Session->setFlash('The Email Template was deleted successfully.');
    } else {
      $this->Session->setFlash('Email Template delete failed.');
    }
    $this->redirect('index/');
  }

  function view ($id){
    $this->data = $this->EmailTemplate->find('first', array(
        'conditions' => array('EmailTemplate.id' => $id)
    ));

    $this->set('readonly', true);
    $this->render('add');

  }

  function emailTemplate($templateId = null) {
      $this->layout = 'ajax';
      $template = $this->EmailTemplate->find('first', array(
          'conditions' => array('EmailTemplate.id' => $templateId)
      ));      
      $this->set('template', $template);
  }

}
?>