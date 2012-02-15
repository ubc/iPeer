<?php

$params = array('controller'=>'events', 'data'=>$assignedGroups, 'event_id' => $event_id, 'popup' => 'y');
echo $this->element('events/event_groups_detail', $params);
  
?>
