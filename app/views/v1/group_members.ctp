<?php
    header('Content-Type: application/json');
    header($statusCode);
    if (null != $groupMembers) {
        echo json_encode($groupMembers);	
    }
?>
