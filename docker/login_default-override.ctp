<?php
// Check if the 'defaultlogin' parameter is NOT set or NOT true in the URL
if (!isset($_GET['defaultlogin']) || $_GET['defaultlogin'] !== 'true') {
    // Perform the redirect
    header("Location: https://ipeer1-stg.apps.ctlt.ubc.ca/");
    exit(); // Ensures no further code is executed after the redirect
}

// Continue with the form if 'defaultlogin=true' is set in the URL
echo $form->create('Guard', array('url' => $login_url));
echo $form->input('username');
echo $form->input('password');
?>

<input type="hidden" name="auth_method" value="default" id="GuardAuthMethod">

<?php
echo $form->end('Login');
?>
