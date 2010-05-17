<?php

for ( $i=0; $i<$count; $i++){
	echo '<select name="instructor_id'.($i+1).'" style="width:250px;margin: 2px;">';
	foreach($instructor as $row): $user = $row['users'];
    echo '<option value='.$user['id'].'>'.$user['last_name'].", ".$user['first_name']."</option>";
	endforeach;
	echo "</select><br>";
}
echo '<input type="hidden" name="data[Course][count]"  value="'.$count.'" />';
?>