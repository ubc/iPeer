<?php
switch ($display)
{
  case 'evaluation':
  $params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'sticky'=>$sticky);
  echo $this->renderElement('searchs/evaluation_search_panel', $params);
    break;

  case 'eval_result':
  $params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'sticky'=>$sticky);
  echo $this->renderElement('searchs/evaluation_result_search_panel', $params);
    break;

  case 'instructor':
  $params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'sticky'=>$sticky);
  echo $this->renderElement('searchs/instructor_search_panel', $params);
    break;

  default:
  $params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging);
  echo $this->renderElement('searchs/evaluation_search_panel', $params);

}

?>