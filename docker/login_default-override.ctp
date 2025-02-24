<?php
// Function to check if the URL is reachable
function isUrlWorking($url) {
    // Get the headers from the URL
    $headers = @get_headers($url);

    // Check if the headers were fetched successfully and the response code is 200 (OK)
    if ($headers && (strpos($headers[0], '200') || strpos($headers[0], '302'))) {
        return true;
    }

    echo $headers[0];

    return false;
}

//echo($_ENV['IPEER_AUTH_SHIBB_URL']);

$authShibbUrl = $_ENV['IPEER_AUTH_SHIBB_URL'] ?? 'https://ipeer.elearning.ubc.ca/login?defaultlogin=true';

// Define the URL to check using the value of $authShibbUrl
$url_to_check = $authShibbUrl;

// Check if the 'defaultlogin' parameter is NOT set or NOT true in the URL
if (!isset($_GET['defaultlogin']) || $_GET['defaultlogin'] !== 'true') {
    // Check if the URL is working
    if (isUrlWorking($url_to_check)) {
        // If the URL is working, perform the redirect
        header("Location: $url_to_check");
        exit(); // Ensures no further code is executed after the redirect
    } else {
        // If the URL is not working, show the login form
        echo '<h2>Login Form 222</h2>';
        echo $form->create('Guard', array('url' => $login_url));
        echo $form->input('username');
        echo $form->input('password');
        echo '<input type="hidden" name="auth_method" value="default" id="GuardAuthMethod">';
        echo $form->end('Login');
    }
} else {
    ///$this->Auth->logout();
    // Continue with the form if 'defaultlogin=true' is set in the URL
    echo '<h2>Login Form 111</h2>';
    echo $form->create('Guard', array('url' => $login_url));
    echo $form->input('username');
    echo $form->input('password');
    echo '<input type="hidden" name="auth_method" value="default" id="GuardAuthMethod">';
    echo $form->end('Login');
}
?>
