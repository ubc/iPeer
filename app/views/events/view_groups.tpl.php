<?php
  $params = array('controller'=>'events', 'data'=>$assignedGroups);
  echo $this->renderElement('events/event_groups_detail', $params);
?>