<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
  <tr class="tableheader">
    <th>Username</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Password</th>
	  <?php	 if ( isset($data[0]['User']['error_message'])) : ?>
		<th>Message</th>
		<?php endif;?>
  </tr>
	<!-- Summary for single record creation -> convert to multiple user entry -->
	<?php
        if (isset($data['id'])) {
            $thisUser = $data;
            if (isset($tmpPassword)) {
                $thisUser['tmp_password'] = $tmpPassword;
            }
            $data = array();
            $row = array();
            $row['User'] = $thisUser;
            array_push($data, $row);
        }
	?>
		<!-- Summary for import record creation -->
		<?php
            foreach($data as $row) {
                $user = $row['User'];
                echo "<tr class='tablecell'>";
                echo "<td>$user[username]</td>";
                echo "<td>$user[last_name]</td>";
                echo "<td>$user[first_name]</td>";
				echo "<td>";
				echo !empty($user['tmp_password']) ?
                    "<strong style='color:red;letter-spacing:3px'>$user[tmp_password]</strong></tt>" :
                    "<i>Not availalble for existing users</i>";
                echo "</td>";
                echo isset($user['error_message']) ?
                    "<td><font color='#FF0000'>$user[error_message]</font></td>"  :
                    "";
                }
                echo "</tr>";
        ?>
</table>