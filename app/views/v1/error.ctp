<?php
header('HTTP/1.1 500 Internal Server Error');
$error_str = json_encode($error);
header("Content-length: ".strlen($error_str));
$this->log($error_str);
echo $error_str;
