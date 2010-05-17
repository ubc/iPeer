<?php
$data = $this->controller->Survey->field('name', "name = '".mysql_real_escape_string($this->params['form']['name'])."'");
$params = array('controller'=>'surveys', 'data'=>$data, 'fieldvalue'=>$this->params['form']['name']);
echo $this->renderElement('surveys/ajax_survey_validate', $params);
?>