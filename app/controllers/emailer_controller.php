<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailerController extends AppController
{
  var $name = 'Emailer';
  var $uses = array();
  var $components = array();

  function __construct(){
    $this->pageTitle = 'Email';
    parent::__construct();
  }

  function write($from='', $to=''){

    if(!isset($this->data)){
      $this->set('from', $from);
      $this->set('to',$to);
    }
    else{
      $this->set('data', $this->data);
      $this->render('confirmation');      
    }
    

    //_sendEmail($template,$subject,$from,$to)
  }

}


?>

