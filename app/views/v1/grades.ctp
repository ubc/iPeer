<?php
    header('Content-Type: application/json');
    header($statusCode);
    if (null != $grades) {
        echo json_encode($grades);
    }
?>