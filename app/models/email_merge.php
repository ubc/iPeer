<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class EmailMerge extends AppModel
{
  var $name = 'EmailMerge';

  var $actsAs = array();

  /**
   * Get merge fields
   * @return List of merge fields
   */
  function getMergeList(){
    return $this->find('list', array(
        'fields' => array('EmailMerge.value', 'EmailMerge.key')
    ));
  }

  /**
   * Get field name by value
   * @param $value value
   * @return field name
   */
  function getFieldAndTableNameByValue($value = ''){
    $table = $this->find('first', array(
          'conditions' => array('value' => $value),
          'fields' => array('table_name','field_name')
      ));
    return $table['EmailMerge'];
  }

}
?>
