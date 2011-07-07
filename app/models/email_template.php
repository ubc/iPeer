<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class EmailTemplate extends AppModel
{
  var $name = 'EmailTemplate';

  var $actsAs = array('Traceable');

  function getMyEmailTemplate($user_id, $type = 'all'){
    return $this->find($type, array(
        'conditions' => array('creator_id' => $user_id)
    ));
  }

  function getPermittedEmailTemplate($user_id, $type= 'all'){
    return $this->find($type, array(
        'conditions' => array('OR' => array(
            array('creator_id' => $user_id),
            array('availability' => '1')
        ))
    ));
  }

  function getCreatorId($template_id){
    $tmp = $this->find('first', array(
        'conditions' => array('EmailTemplate.id' => $template_id),
        'fields' => array('EmailTemplate.creator_id')
    ));
    return $tmp['EmailTemplate']['creator_id'];
  }

}
?>
