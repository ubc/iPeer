<?php
/**
 * Prevent notices, warnings and errors
 * by assigning $_SERVER['HTTP_X_FORWARDED_PROTO'] and
 * by loading IMSGlobal Composer package and
 * by loading it's dependent package JWT
 * at the top of every LTI13-related file.
 */
$_SERVER['HTTP_X_FORWARDED_PROTO'] = env('HTTP_X_FORWARDED_PROTOCOL');
require_once ROOT.DS.'vendor'.DS.'fproject'.DS.'php-jwt'.DS.'src'.DS.'JWT.php';
require_once ROOT.DS.'vendor'.DS.'imsglobal'.DS.'lti-1p3-tool'.DS.'src'.DS.'lti'.DS.'lti.php';
