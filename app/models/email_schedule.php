<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class EmailSchedule extends AppModel
{
  var $name = 'EmailSchedule';

  var $actsAs = array('Traceable');

  function getEmailsToSend(){
    return $this->find('all', array(
        'conditions' => array('now() >= EmailSchedule.date', 'EmailSchedule.sent' => '0')
    ));
  }
}
?>
