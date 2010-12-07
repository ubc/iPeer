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
            $row['User'] = $data;
            $data = array();
            array_push($data, $row);
        }
	?>
		<!-- Summary for import record creation -->
		<?php foreach($data as $row): ?>
            <?php $user = $row['User'];?>
            <tr class='tablecell'>
            <td><?php echo $user['username']?></td>
            <td><?php echo $user['first_name']?></td>
            <td><?php echo $user['last_name']?></td>
            <td><?php echo !empty($user['password']) ? 
              "<strong style='color:red;letter-spacing:3px'>".$user['password']."</strong></tt>" : 
              "<i>Not availalble for existing users</i>";?></td>
            <?php if(isset($user['error_message'])):?>
                    <td><font color='#FF0000'><?php echo $user['error_message']?></font></td>
            <?php endif;?>
            </tr>
     <?php endforeach;?> 
</table>
