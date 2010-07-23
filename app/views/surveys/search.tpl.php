<?php
$params = array('controller'=>'surveys', 'data'=>$data, 'paging'=>$paging);
echo $this->renderElement('surveys/ajax_survey_list', $params);
?>
