<?php
$params = array('controller'=>'simpleevaluations', 'data'=>$data, 'paging'=>$paging);
echo $this->renderElement('simple_evaluations/ajax_simple_eval_list', $params);
?>
