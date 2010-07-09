<?php
$data = $this->controller->SimpleEvaluation->findAll($conditions, $fields=null, $this->controller->order, $this->controller->show, $this->controller->page);

$paging['style'] = 'ajax';
$paging['link'] = '/simpleevaluations/search/?show='.$this->controller->show.'&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';

$paging['count'] = $this->controller->SimpleEvaluation->findCount($conditions);
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;
$paging['mine'] = $this->controller->mine_only;

$params = array('controller'=>'simpleevaluations', 'data'=>$data, 'paging'=>$paging);
echo $this->renderElement('simple_evaluations/ajax_simple_eval_list', $params);
?>
