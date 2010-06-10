<?php
  $params = array('controller'=>'events', 'data'=>$assignedGroups, 'event_id' => $event_id);
  echo $this->renderElement('events/event_groups_detail', $params);
?>
