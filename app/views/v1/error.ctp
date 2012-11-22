<?php
header('HTTP/1.1 500 Internal Server Error');
$this->log('Error: '.json_encode($error));
echo json_encode($error);
