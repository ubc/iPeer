<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class EmailTemplate extends AppModel
{
  var $name = 'EmailTemplate';

  var $actsAs = array('Traceable');

  /**
   * Get my email templates
   * @param $user_id user id
   * @param $type find type
   * @return List of my email templates
   */
  function getMyEmailTemplate($user_id, $type = 'all'){
    $this->recursive = -1;
    return $this->find($type, array(
        'conditions' => array('creator_id' => $user_id)
    ));
  }

  /**
   * Get email templates that available for the user
   * @param $user_id user id
   * @param $type find type
   * @return List of available email templates
   */
  function getPermittedEmailTemplate($user_id, $type= 'all'){
    $this->recursive = -1;
    return $this->find($type, array(
        'conditions' => array('OR' => array(
            array('creator_id' => $user_id),
            array('availability' => '1')
        ))
    ));
  }

  /**
   * Get creator id
   * @param $template_id template id
   * @return Creator Id
   */
  function getCreatorId($template_id){
    $tmp = $this->find('first', array(
        'conditions' => array('EmailTemplate.id' => $template_id),
        'fields' => array('EmailTemplate.creator_id')
    ));
    return $tmp['EmailTemplate']['creator_id'];
  }

}
?>
