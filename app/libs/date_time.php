<?php

App::import('Model', 'SysParameter');

class DateTimeTool {

  function format($timestamp) {
    $this->SysParameter = new SysParameter;
    $data = $this->SysParameter->findParameter('display.date_format');
    $dateFormat = $data['SysParameter']['parameter_value'];

    if (is_string($timestamp)) {
      return date($dateFormat,strtotime($timestamp));
    } else if(is_numeric($timestamp)){
      return date($dateFormat, $timestamp);
    } else {
      return "";
    }
  }
}
