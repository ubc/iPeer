<?php
header('HTTP/1.1 400 Bad Request ');

$error = array('code' => 100, "message" => 'OAuth error: '.$oauthError);
echo json_encode($error);
?>
