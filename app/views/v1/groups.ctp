<?php
header('Content-Type: application/json');
header($statusCode);
// since json_encode returns string if given null
if ($group == null) $group = array();
echo json_encode($group);
?>
