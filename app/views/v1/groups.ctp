<?php
header('Content-Type: application/json');
    if (null != $group) {
        echo json_encode($group);	
    }	
?>