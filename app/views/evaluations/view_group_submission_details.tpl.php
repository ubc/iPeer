<?php
  $params = array('controller'=>'evaluations', 'data'=>$members, 'group'=>$group);
  echo $this->renderElement('evaluations/group_submission_details', $params);
?>