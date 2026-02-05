<?php

if (empty($_ENV['ENABLE_SAML_METADATA'])) {
    http_response_code(404);
    exit;
}

require_once '../../vendor/autoload.php';
use OneLogin\Saml2\Auth;
$samlSettings = require '../../config/saml_settings.php';
$auth = new Auth($samlSettings);
echo $auth->getMetadata();
