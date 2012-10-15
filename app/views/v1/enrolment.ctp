<?php
    header('Content-Type: application/json');
    header($statusCode);
    if (null != $enrolment) {
        echo json_encode($enrolment);	
    }
?>
