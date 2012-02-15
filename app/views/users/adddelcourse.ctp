<?php
  $params = array('controller'  =>'users', 
                  'all_courses' =>$courses, 
                  'count'       =>$this->params['form']['add']);
  echo $this->element('users/ajax_user_courses', $params);
?>
