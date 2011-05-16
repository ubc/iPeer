<?php
switch ($display)
{

  case 'evaluation':
  $params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'sticky'=>$sticky);
  echo $this->element('searchs/evaluation_search_panel', $params);
    break;

  case 'eval_result':
  $params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'sticky'=>$sticky);
  echo $this->element('searchs/evaluation_result_search_panel', $params);
    break;

  case 'instructor':
  $params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging, 'sticky'=>$sticky);
  echo $this->element('searchs/instructor_search_panel', $params);
    break;

  default:
 // 	var_dump('three');
  $params = array('controller'=>'searchs', 'data'=>$data, 'paging'=>$paging);
  echo $this->element('searchs/evaluation_search_panel', $params);

}

?>
