<?php
$data = $this->controller->Mixeval->findAll($conditions, $fields=null, $this->controller->order, $this->controller->show, $this->controller->page);

$paging['style'] = 'ajax';
$paging['link'] = '/mixevals/search/?show='.$this->controller->show.'&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';

$paging['count'] = $this->controller->Mixeval->findCount($conditions);
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;

$params = array('controller'=>'mixevals', 'data'=>$data, 'paging'=>$paging, 'event'=>$event);
echo $this->renderElement('mixevals/ajax_mixeval_list', $params);
?>