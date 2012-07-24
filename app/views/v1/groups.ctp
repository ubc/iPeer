<?php
header('Content-Type: application/json');
header($statusCode);
    if (null != $group) {
        echo json_encode($group);	
    }	
?>