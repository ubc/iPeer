<table width="95%" border="0" cellspacing="2" cellpadding="4" bgcolor="#FFFFFF">
  <tr class="tableheader">
    <th><?php __('Username')?></th>
    <th><?php __('First Name')?></th>
    <th><?php __('Last Name')?></th>
    <th><?php __('Password')?></th>
	  <?php	 if ( isset($data[0]['User']['error_message'])) : ?>
		<th><?php __('Message')?></th>
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
              __("<i>Not availalble for existing users</i>", true);?></td>
            <?php if(isset($user['error_message'])):?>
                    <td><font color='#FF0000'><?php echo $user['error_message']?></font></td>
            <?php endif;?>
            </tr>
     <?php endforeach;?> 
</table>
