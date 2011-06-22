<?php 
App::import('Lib', 'Toolkit');

class AppModel extends Model {
  var $errorMessage = 'Failed to save.';

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
  }

  function save($data = null, $validate = true, $fieldList = array()) {
    //clear modified field value before each save
    if (isset($this->data) && isset($this->data[$this->alias]))
      unset($this->data[$this->alias]['modified']);
    if (isset($data) && isset($data[$this->alias]))
      unset($data[$this->alias]['modified']);

    return parent::save($data, $validate, $fieldList);
  }
}
