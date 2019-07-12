<?php
$scriptNames = glob(APP.DS.'libs'.DS.'jobs/*.php');
foreach ($scriptNames as $script) {
    require_once($script);
}