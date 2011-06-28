<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailerController extends AppController
{
  var $name = 'Emailer';
  var $uses = array('GroupsMembers', 'UserEnrol', 'User');
  var $components = array('AjaxList');

  function __construct(){
    $this->pageTitle = 'Email';
    parent::__construct();
  }

  function write($to = ' '){

    if(!isset($this->data)){
//      if(isset($this->params['named'])){
//        $params = $this->params['named'];
//        foreach($params as $key => $value){
//          $this->set($key, $value);
//        }
//      }
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

  function getEmailAddress($to){
    $type = $to[0];
    $id = substr($to,1);
    switch($type){
      case ' ':
        return '';
        break;
      case 'C':
        return $this->User->find('list', array(
            'fields' => array('email'),
            'conditions' => array('User.id' => $this->UserEnrol->getUserListByCourse($id))
        ));
        break;
      case 'G':
        return $this->User->find('list', array(
            'fields' => array('email'),
            'conditions' => array('User.id' => $this->GroupsMembers->getMembers($id))
        ));
        break;
      default:
        return $this->User->find('list', array(
            'fields' => array('email'),
            'conditions' => array('User.id' => $to)
        ));
      
    }
  }

}


?>

