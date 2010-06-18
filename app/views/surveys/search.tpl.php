<?php

$data = $this->controller->Survey->findAll($conditions,'' , $this->controller->order, $this->controller->show, $this->controller->page,null,null);//array('JOIN courses AS Course ON Survey.course_id = Course.id'));'Survey.*,Course.course'

$paging['style'] = 'ajax';
$paging['link'] = '/surveys/search/?show='.$this->controller->show.'&sort='.$this->controller->sortBy.'&direction='.$this->controller->direction.'&page=';

$paging['count'] = count($data);
$paging['show'] = array('10','25','50','all');
$paging['page'] = $this->controller->page;
$paging['limit'] = $this->controller->show;
$paging['direction'] = $this->controller->direction;

$params = array('controller'=>'surveys', 'data'=>$data, 'paging'=>$paging);
echo $this->renderElement('surveys/ajax_survey_list', $params);
?>