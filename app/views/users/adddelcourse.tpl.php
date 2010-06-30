<?php
  $params = array('controller'  =>'users', 
                  'all_courses' =>$this->controller->nonRegisteredCourses($user_id), 
                  'count'       =>$this->params['form']['add']);
  echo $this->renderElement('users/ajax_user_courses', $params);
?>
