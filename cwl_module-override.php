<?php
echo $form->create('Guard', array('url' => $login_url));
echo $form->input('username');
echo $form->input('password');
?>

<table id="cwl-login" class="standardtable">
<tr>
    <td>Active iPeer with CWL</td>
    <td>
    <a href="https://ipeer1-stg.apps.ctlt.ubc.ca/" style="display:inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-align: center; text-decoration: none; border-radius: 5px;">ACTIVATE</a>
    </td>
</tr>

</table>

<input type="hidden" name="auth_method" value="default" id="GuardAuthMethod">
<?php echo $form->end('Login');
