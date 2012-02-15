<?php
$params = array('controller'=>'events', 'eventTemplates'=>$eventTemplates, 'default'=>$default);
echo $this->element('events/ajax_event_template_list', $params);
?>
