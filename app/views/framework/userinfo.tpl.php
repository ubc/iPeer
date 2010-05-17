<?php
$data = $this->controller->framework->getUser($userId);

$params = array('controller'=>'framework', 'userId'=>$userId, 'data'=>$data);
echo $this->renderElement('framework/view_user_detail', $params);
?>