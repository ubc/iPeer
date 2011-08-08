<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class EmailMerge extends AppModel
{
  var $name = 'EmailMerge';

  var $actsAs = array();

  function getMergeList(){
    return $this->find('list', array(
        'fields' => array('EmailMerge.value', 'EmailMerge.key')
    ));
  }

  function getFieldNameByValue($value){
    $result = $this->find('first', array(
        'conditions' => array('EmailMerge.value' => $value),
        'fields' => array('EmailMerge.field_name')
    ));

    return $result['EmailMerge']['field_name'];
  }

}
?>
