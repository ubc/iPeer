<?php
header('HTTP/1.1 400 Bad Request');

$error = array("oauthError" => $oauthError);
echo json_encode($error);
?>
