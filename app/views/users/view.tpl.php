<?php
    $data = $this->controller->framework->getUser($userId);
    $params = array('controller'=>'framework', 'data'=>$data);
    echo $this->renderElement('framework/view_user_detail', $params);
?>

