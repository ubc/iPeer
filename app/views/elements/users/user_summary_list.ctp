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

        if(isset($showPasswords) && $showPasswords) {
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

        if(isset($showFullEmails) && !$showFullEmails) {
            $email_parts = explode('@', $user['User']['email']);
            if (count($email_parts) == 2) {
                $email_parts[0] = str_repeat("*", strlen($email_parts[0]));
                $email_domain_parts = explode('.', $email_parts[1]);
                if (count($email_domain_parts)) {
                    $email_domain_parts[0] = str_repeat("*", strlen($email_domain_parts[0]));
                    $email_parts[1] = implode('.', $email_domain_parts);
                }
                $user['User']['email'] = implode('@', $email_parts);
            }
        }

        echo "<tr>";
        foreach($columns as $k => $v) {
            echo "<td>".$user['User'][$k]."</td>";
        }
        echo "</tr>";
    }
    ?>
</table>
