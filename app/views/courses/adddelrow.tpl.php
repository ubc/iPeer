<?php
$tmp = $this->controller->allUsers();
$params = array('controller'=>'courses', 'allusers'=>$tmp, 'count'=>$this->params['form']['add']);
echo $this->renderElement('courses/ajax_course_delegates', $params);
?>