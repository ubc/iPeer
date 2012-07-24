<?php
header('Content-Type: application/json');
    if (null != $user) {
        echo json_encode($user);	
    }
?>
