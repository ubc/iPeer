<?php
$data = $this->controller->Course->field('course', "course = '".mysql_real_escape_string($this->params['form']['course'])."'");
$params = array('controller'=>'courses', 'data'=>$data, 'fieldvalue'=>$this->params['form']['course']);
echo $this->renderElement('courses/ajax_course_validate', $params);
?>