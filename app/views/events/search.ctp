<?php
$data = $this->controller->Event->find('all',$conditions, '*, (NOW() >= release_date_begin AND NOW() <= release_date_end) AS is_released', $this->controller->order, $this->controller->show, $this->controller->page);

$paging['style'] = 'ajax';
$paging['link'] = '/events/search/?show='.$this->controller->show.'&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';

$paging['count'] = $this->controller->Event->find(count,$conditions);
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;

$params = array('controller'=>'events', 'data'=>$data, 'paging'=>$paging);
echo $this->element('events/ajax_event_list', $params);
?>
