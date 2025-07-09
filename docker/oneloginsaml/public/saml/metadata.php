<?php
// public/saml/metadata.php
require_once '../../vendor/autoload.php';
use OneLogin\Saml2\Auth;

// Initialize SAML authenticationn
$samlSettings = require '../../config/saml_settings.php';
$auth = new Auth($samlSettings);

// Generate SAML metadata
echo $auth->getMetadata();
