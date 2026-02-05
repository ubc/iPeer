<?php

if (!empty($_ENV['SAML_SETTINGS'])) {
    return json_decode($_ENV['SAML_SETTINGS'], true);
}

return [];
