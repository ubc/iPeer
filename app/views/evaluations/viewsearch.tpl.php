<?php
$params = array('controller'=>'evaluations', 'data'=>$data, 'paging'=>!empty($paging)? $paging: null);
echo $this->renderElement('evaluations/ajax_evaluation_result_list', $params);
?>