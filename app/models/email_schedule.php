<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class EmailSchedule extends AppModel
{
  var $name = 'EmailSchedule';

  var $actsAs = array('Traceable');

  /**
   * Get emails to send
   * @return Emails to send
   */
  function getEmailsToSend(){
    $this->recursive = -1;
    return $this->find('all', array(
        'conditions' => array('now() >= EmailSchedule.date', 'EmailSchedule.sent' => '0'),
    ));
  }

  /**
   * Get creator's id
   * @param $id id
   * @return Creator id
   */
  function getCreatorId($id){
    $tmp = $this->find('first', array(
        'conditions' => array('EmailSchedule.id' => $id),
        'fields' => array('EmailSchedule.creator_id')
    ));
    return $tmp['EmailSchedule']['creator_id'];
  }

  /**
   * Get whether email is sent or not
   * @param $id id
   * @return Sent field
   */
  function getSent($id){
    $tmp = $this->find('first', array(
        'conditions' => array('EmailSchedule.id' => $id),
        'fields' => array('EmailSchedule.sent')
    ));
    return $tmp['EmailSchedule']['sent'];
  }
}
?>
