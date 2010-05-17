<?php
$data = $this->controller->Rubric->findAll($conditions, $fields=null, $this->controller->order, $this->controller->show, $this->controller->page);

$paging['style'] = 'ajax';
$paging['link'] = '/rubrics/search/?show='.$this->controller->show.'&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';

$paging['count'] = $this->controller->Rubric->findCount($conditions);
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;

$params = array('controller'=>'rubrics', 'data'=>$data, 'paging'=>$paging);
echo $this->renderElement('rubrics/ajax_rubric_list', $params);
?>