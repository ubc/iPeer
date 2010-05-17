<?php
for ( $i=0; $i<$count; $i++){
	echo "
	<table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
	  <tr>
		<td width=\"161\">
		  <select name=\"delegate".$i."\" style=\"width:150px;\">
			<option value=\"\" selected></option>";
			foreach($allusers as $row): $user = $row['users'];
			echo "<option value=\"" .$user['id'] ."\">" .$user['last_name'] .", " .$user['first_name'] ."</option>";
		   endforeach;
	echo "
		</select></td>
		<td width=\"20\"><input name=\"delType".$i."\" type=\"radio\" value=\"I\"></td>
		<td width=\"110\">Instructor</td>
		<td width=\"20\"><input name=\"delType".$i."\" type=\"radio\" value=\"T\"></td>
		<td width=\"3\">T.A. </td>
	  </tr>
	</table>";
}
?>