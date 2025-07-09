<?php
// config/saml_settings.php

if (!empty($_ENV['SAML_SETTINGS'])) {
    $modifiedString = str_replace(['{', '}'], ['[', ']'], $_ENV['SAML_SETTINGS']);

    $modifiedArray = json_decode($_ENV['SAML_SETTINGS'], true);

    return $modifiedArray;

} else {

    return [];

}