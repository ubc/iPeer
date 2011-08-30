<?php
$params = array('controller'=>'events', 'eventList'=>$eventList, 'eventId'=>null, 'defaultOpt'=>'A', 'view'=>0, 'disabled'=>0);
echo $this->element('events/event_selection_box', $params);
?>
