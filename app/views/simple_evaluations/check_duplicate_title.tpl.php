<?php
//$tmptitle=$this->params['form']['newtitle'];
$data = $this->controller->SimpleEvaluation->field('name', "name = '".mysql_real_escape_string($this->params['form']['newtitle'])."'");
$params = array('controller'=>'simpleevaluations', 'data'=>$data, 'fieldvalue'=>$this->params['form']['newtitle']);
echo $this->renderElement('simple_evaluations/ajax_title_validate', $params);
?>