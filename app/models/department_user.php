<?php
class DepartmentUser extends AppModel{

  var $name = 'DepartmentUser';
  
  function getDepartment($user_id=null) {
    return $this->find('first', array('conditions' => 
    			   	array('user_id' => $user_id)));
  }
}
?>