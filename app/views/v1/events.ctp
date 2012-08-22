<?php
header('Content-Type: application/json');
header($statusCode);
    if (null != $events) {
        echo json_encode($events);	
    }	
?>