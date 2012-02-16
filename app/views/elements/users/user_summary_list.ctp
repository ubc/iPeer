<?php $showPasswords = isset($showPasswords) ? $showPasswords : true; ?>
<?php $columns = isset($columns) ? $columns : array('username' => __('Username', true),
                                                    'first_name' => __('First Name', true),
                                                    'last_name' => __('Last Name',true),
                                                    'email' => __('E-mail', true),
                                                    'password' => __('Password', true),
                                                   );
// Summary for single record creation -> convert to multiple user entry 
if (isset($data['id'])) {
  $row['User'] = $data;
  $data = array();
  array_push($data, $row);
}
?>

<table class="user-list-table">
  <tr>
    <?php foreach($columns as $k => $v): ?>
      <th><?php echo $v?></th>
		<?php endforeach;?>
  </tr>
		<!-- Summary for import record creation -->
		<?php foreach($data as $user): ?>
            <?php 
              if($showPasswords) {
                if(isset($user['tmp_password']))
                  $password = $user['tmp_password'];
                else if(isset($user['password']))
                  $password = $user['password'];
                else
                  $password = '';
              } else {
                $password = '******';
              }
            ?>
            <tr>
              <?php foreach($columns as $k => $v): ?>
                <td><?php echo $user[$k]?></td>
              <?php endforeach; ?>
            </tr>
     <?php endforeach;?> 
</table>
