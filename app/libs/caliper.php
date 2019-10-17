<?php
App::import('Vendor', 'IMSGlobal\\Caliper', array('file' => 'imsglobal'.DS.'caliper'.DS.'autoload.php'));
$scriptNames = glob(APP.DS.'libs'.DS.'caliper/*.php');
foreach ($scriptNames as $script) {
    require_once($script);
}