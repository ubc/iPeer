<?php

$params = array('controller'=>'events', 'data'=>$assignedGroups, 'event_id' => $event_id, 'popup' => 'y');
echo $this->element('events/event_groups_detail', $params);

if(isset($popup) && $popup == 'y'): ?>
    <tr><td colspan="3" align="center">
        <input type="button" value="<?php __('Close Window')?>" onclick="window.close()">
    </td></tr>
<?php endif;
  
?>
