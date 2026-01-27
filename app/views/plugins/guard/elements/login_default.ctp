<?php

echo $form->create('Guard', array('url' => $login_url));
echo $form->input('username');
echo $form->input('password');
?>
<input type="hidden" name="auth_method" value="default" id="GuardAuthMethod">
<?php echo $form->end('Login');
