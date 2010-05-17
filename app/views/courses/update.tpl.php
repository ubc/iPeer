<?php 

$params = array('controller'=>'courses', 'userPersonalize'=>$userPersonalize);
echo $this->renderElement('courses/ajax_personalize_'.$attributeCode, $params); 

?>