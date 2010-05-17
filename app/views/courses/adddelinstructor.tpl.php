<?php
$params = array('controller'=>'courses', 'instructor'=>(($this->controller->uniqueInstructors($this->controller->rdAuth->courseId)) ? $this->controller->uniqueInstructors($this->controller->rdAuth->courseId):$this->controller->allInstructors()), 'count'=>$this->params['form']['add']);
echo $this->renderElement('courses/ajax_course_instructors', $params);
?>