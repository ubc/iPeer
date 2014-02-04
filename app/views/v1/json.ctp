<?php
    header('Content-Type: application/json');
    header($statusCode);
    if ($result == null) {
        $result = array();
    }
    $json = json_encode($result);
    header("Content-length: ".strlen($json));
    $this->log("Return: $json", 'api');
    echo $json;

