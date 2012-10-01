<?php
    header('Content-Type: application/json');
    header($statusCode);
    if (!is_null($departments)) {
	    echo json_encode($departments);
	}
?>