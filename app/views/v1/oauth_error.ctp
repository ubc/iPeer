<?php
header('HTTP/1.1 400 Bad Request ');

$error = array('code' => 100, "message" => 'OAuth error: '.$oauthError);
$error_str = json_encode($error);
header("Content-length: ".strlen($error_str));
$this->log($error_str);
echo $error_str;
