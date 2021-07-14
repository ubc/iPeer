<?php
/**
 * Prevent notices, warnings and errors
 * by assigning $_SERVER keys and
 * by loading Firebase\JWT\JWT & Firebase\JWT\JWK first,
 * then by loading IMSGlobal\LTI Composer package
 * at the top of every LTI-related file.
 */
if (!isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $_SERVER['HTTP_X_FORWARDED_PROTO'] = env('HTTP_X_FORWARDED_PROTOCOL');
}
if (!isset($_SERVER['REQUEST_SCHEME'])) {
    $_SERVER['REQUEST_SCHEME'] = env('REQUEST_SCHEME');
}
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = env('HTTP_HOST');
}
require_once ROOT.DS.'vendor'.DS.'fproject'.DS.'php-jwt'.DS.'src'.DS.'JWT.php';
require_once ROOT.DS.'vendor'.DS.'fproject'.DS.'php-jwt'.DS.'src'.DS.'JWK.php';
require_once ROOT.DS.'vendor'.DS.'imsglobal'.DS.'lti-1p3-tool'.DS.'src'.DS.'lti'.DS.'lti.php';
$scriptNames = glob(APP.DS.'libs'.DS.'lti/*.php');
foreach ($scriptNames as $script) {
    require_once($script);
}