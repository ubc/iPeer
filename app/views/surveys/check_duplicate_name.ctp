<?php
$data = $this->controller->Survey->field('name', "name = '".mysql_real_escape_string($this->params['form']['name'])."' AND course_id = ".$course_id);
$params = array('controller'=>'surveys', 'data'=>$data, 'fieldvalue'=>$this->params['form']['name']);
echo $this->element('surveys/ajax_survey_validate', $params);
?>
