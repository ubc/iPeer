<?php
//$tmpusername=$this->params['form']['newuser'];
$data = $this->controller->User->field('username', "username = '".mysql_real_escape_string($this->params['form']['newuser'])."'");
$params = array('controller'=>'users', 'data'=>$data, 'fieldvalue'=>$this->params['form']['newuser'], 'role'=>$role);
echo $this->renderElement('users/ajax_username_validate', $params);
?>