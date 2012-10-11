<?php
header('HTTP/1.1 400 Bad Request ');
Debugger::log($oauthError);

$error = array("oauthError" => $oauthError);
echo json_encode($error);
?>
