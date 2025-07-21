<?php
// public/saml/auth.php
require_once '../../vendor/autoload.php';
use OneLogin\Saml2\Auth;

    // Initialize SAML authentication
    $samlSettings = require '../../config/saml_settings.php';
    $auth = new Auth($samlSettings);

    session_start();
    session_unset(); // Clear session data
    session_destroy(); // Destroy the session

    if (isset($_COOKIE['SAMLRequest'])) {
        setcookie('SAMLRequest', '', time() - 3600, '/'); // Clear the SAMLRequest cookie
    }
    if (isset($_COOKIE['SAMLResponse'])) {
        setcookie('SAMLResponse', '', time() - 3600, '/'); // Clear the SAMLResponse cookie
    }
    if (isset($_COOKIE['PHPSESSID'])) {
        setcookie('PHPSESSID', '', time() - 3600, '/'); // Clear the PHP session cookie
    }

        
    if ($auth->isAuthenticated()) {
        // Perform SAML logout
        $auth->logout();

        // Optionally, redirect to a post-logout page (e.g., homepage)
        //header('Location: /logout-success');
        exit;
    } else {

        if (!empty($_ENV['SAML_LOGOUT_URL'])) {
            header("Location: " . $_ENV['SAML_LOGOUT_URL']);
            exit;
        } else {
        // Optional: handle missing environment variable
            http_response_code(500);
            echo "SAML_LOGOUT_URL is not set in the environment.";
            exit;
        }



        exit;
    }
