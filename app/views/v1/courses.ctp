<?php
    header('Content-Type: application/json');
    header($statusCode);
    if (null != $courses) {
        echo json_encode($courses);
    }
?>