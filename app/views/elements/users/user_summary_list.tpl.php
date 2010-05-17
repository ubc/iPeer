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
	<!-- Summary for single record creation -->
	<?php	 if (isset($data['id'])) : ?>
	  <tr class="tablecell">
    <td>
      <!--?php echo $user['username'] ?-->
      <?php echo $data['username']; ?>
    </td>
    <td>
      <!--?php echo $user['last_name'] ?-->
      <?php echo $data['first_name']; ?>
    </td>
    <td>
      <!--?php echo $user['first_name'] ?-->
      <?php echo $data['last_name']; ?>
    </td>
    <td>
      <?php echo empty($tmpPassword)?'':$tmpPassword; ?>
    </td>
  </tr>
	<?php else: ?>
		<!-- Summary for import record creation -->
		<?php foreach($data as $row): $user = $row['User']; ?>
		<tr class="tablecell">
			<td>
				<?php echo $user['username'] ?>
			</td>
			<td>
				<?php echo $user['last_name'] ?>
			</td>
			<td>
				<?php echo $user['first_name'] ?>
			</td>
			<td>
				<?php echo $user['tmp_password']; ?>
			</td>
				<?php if ( isset($data[0]['User']['error_message'])){
							echo '<td><font color="#FF0000">'.$user['error_message'].'</font></td>';
							}?>
		</tr>
		<?php endforeach; ?>
	<?php endif;?>
</table>