<?php
header('Content-Type: application/json');
header($statusCode);
if ($groupMembers == null) $groupMembers = array();
echo json_encode($groupMembers);	
?>
