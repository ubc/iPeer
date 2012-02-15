<?php
  $params = array('controller'=>'evaluations', 'data'=>$members, 'group'=>$group);
  echo $this->element('evaluations/group_submission_details', $params);
?>
