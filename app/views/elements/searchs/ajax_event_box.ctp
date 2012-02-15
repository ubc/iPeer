<?php

if ($view) {
  //For view page; display the name only
  echo $eventList[$eventId]['event'];
}
else {
  //For Edit or Add pages; shows the selection box
  echo '<select name="event_id" id="event_id" '.$disabled.'>';

  if ($defaultOpt == '1') {
    echo '<option value="-1" SELECTED >'.__('No event Selected', true).'</option>';
  } else if ($defaultOpt == 'A') {
    echo '<option value="A" SELECTED >'.__(' --- All events --- ').'</option>';
  }

  foreach($eventList as $index => $value) {
    if ($eventId == null) {
      echo '<option value="'.$eventList[$index]['id'].'">'.$value['event'].'</option>';
    } else {
      if ($eventId == $index){
        echo '<option value="'.$index.'">'.$eventList['event'].'</option>';
      }
    }
  }
  echo '</select>';
}?>