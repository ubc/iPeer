<?php

require_once '../../vendor/autoload.php';
use OneLogin\Saml2\Auth;

$samlSettings = require '../../config/saml_settings.php';
$auth = new Auth($samlSettings);

if ($auth->isAuthenticated()) {
    header('Location: /dashboard');
    exit;
}

$auth->login();
