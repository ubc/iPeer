<?php
    header('Content-Type: application/json');
    header($statusCode);
    if ($group) {
        echo json_encode($group);
    }
?>
