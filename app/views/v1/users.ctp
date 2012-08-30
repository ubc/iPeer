<?php
    header('Content-Type: application/json');
    header($statusCode);
    if (null != $user) {
        echo json_encode($user);	
    }
?>
