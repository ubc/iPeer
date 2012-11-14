<?php
header('Content-Type: application/json');
header($statusCode);
// json_encode will return the string "null" if you give it a null value
if ($user == null) $user = array(); 
echo json_encode($user);
?>
