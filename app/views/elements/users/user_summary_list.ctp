<?php 
$columns = isset($columns) ? 
    $columns : 
    array(
        'username' => __('Username', true),
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

<table class='standardtable'>
    <tr>
    <?php 
    foreach($columns as $k => $v) {
        echo "<th>$v</th>";
    }
    ?>
    </tr>
    <!-- Summary for import record creation -->
    <?php 
    foreach($data as $user) {
        if(isset($showPasswords)) {
            if(!empty($user['User']['tmp_password']))
                $password = $user['User']['tmp_password'];
            else if(!empty($user['User']['import_password']))
                $password = $user['User']['import_password'];
            else
                $password = '';
        } else {
            $password = '******';
        }
        $user['User']['password'] = $password;
        echo "<tr>";
        foreach($columns as $k => $v) {
            echo "<td>".$user['User'][$k]."</td>";
        }
        echo "</tr>";
    }
    ?>
</table>
