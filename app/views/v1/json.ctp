<?php
    header('Content-Type: application/json');
    header($statusCode);
    if ($result == null) {
        $result = array();
    }
    $json = json_encode($result);
    $this->log("Return: $json", 'api');
    echo $json;

