<?php
// public/saml/auth.php 
require_once '../../vendor/autoload.php';
use OneLogin\Saml2\Auth;

// Initialize SAML authentication
$samlSettings = require '../../config/saml_settings.php';
$auth = new Auth($samlSettings);

// If user is logged in, redirect to dashboard
if ($auth->isAuthenticated()) {
    header('Location: /dashboard');
    exit;
}

// Perform SAML login
$auth->login();
