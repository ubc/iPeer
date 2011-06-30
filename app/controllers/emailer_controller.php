<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailerController extends AppController
{
  var $name = 'Emailer';
  var $uses = array('GroupsMembers', 'UserEnrol', 'User', 'CustomEmail');
  var $components = array('AjaxList', 'Session');

  function __construct(){
    $this->pageTitle = 'Email';
    parent::__construct();
  }

  function setUpAjaxList() {
    $myID = $this->Auth->user('id');

    // Set up Columns
    $columns = array(
            array("CustomEmail.id",   "",       "",        "hidden"),
            array("CustomEmail.name", "Name",   "12em",    "action",   "View Email Template"),
            array("CustomEmail.description", "Description","auto",  "action", "View Email Template"),
            array("CustomEmail.creator_id",           "",            "",     "hidden"),
            array("CustomEmail.creator",     "Creator",  "10em", "action", "View Creator"),
            array("CustomEmail.created", "Creation Date", "10em", "date"));

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
    // put all the joins together
    $joinTables = array();

    $extraFilters = "";

    // Instructors can only edit their own evaluations
    $restrictions = "";
    if ($this->Auth->user('role') != 'A') {
      $restrictions = array(
                            "CustomEmail.creator_id" => array(
                                                  $myID => true,
                                                  "!default" => false));
    }

    // Set up actions
    $warning = "Are you sure you want to delete this email template permanently?";
    $actions = array(
                     array("View Email Template", "", "", "", "view", "CustomEmail.id"),
                     array("Edit Email Template", "", $restrictions, "", "edit", "CustomEmail.id"),
                     array("Delete Email Template", $warning, $restrictions, "", "delete", "CustomEmail.id"),
                     array("View Creator", "",    "", "users", "view", "CustomEmail.creator_id"));

    // No recursion in results
    $recursive = 0;

    // Set up the list itself
    $this->AjaxList->setUp($this->CustomEmail, $columns, $actions,
                           "CustomEmail.name", "CustomEmail.name", $joinTables, $extraFilters, $recursive);
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
    
    if (empty($this->params['data'])) {

    }
    else{
      //Save Data
      if ($this->CustomEmail->save($this->params['data'])) {
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

    $data = $this->CustomEmail->find('first', array(
        'conditions' => array('CustomEmail.id' => $id)
    ));

    if (empty($this->params['data'])) {
        $this->data = $data;
        $this->render('add');
    }
    else{
      //Save Data
      if ($this->CustomEmail->save($this->params['data'])) {$this->log($this->params['data']);
        $this->Session->setFlash('Successful'); 
        $this->redirect('/emailer/index');
      }
      else{
        $this->Session->setFlash('Failed to save');
      }
    }
  }

  function delete ($id) {
    if ($this->CustomEmail->delete($id)) {
      $this->Session->setFlash('The Email Template was deleted successfully.');
    } else {
      $this->Session->setFlash('Email Template delete failed.');
    }
    $this->redirect('index/');
  }

  function view ($id){
    $this->data = $this->CustomEmail->find('first', array(
        'conditions' => array('CustomEmail.id' => $id)
    ));

    $this->set('readonly', true);
    $this->render('add');

  }

}
?>

