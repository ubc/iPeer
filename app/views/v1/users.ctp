<?php
    header('Content-Type: application/json');
    header($statusCode);
    if ($user) {
        echo json_encode($user);
    }
?>
