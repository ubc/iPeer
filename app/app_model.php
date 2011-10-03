<?php 
App::import('Lib', 'Toolkit');

class AppModel extends Model {
  var $errorMessage = array();
  var $insertedIds = array();

  function afterSave($created) {
    if($created) {
      $this->insertedIds[] = $this->getInsertID();
    }

    return true;
  }

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


  /**
    * showErrors itterates through the errors array
    * and returns a concatinated string of errors sepearated by
    * the $sep
    *
    * @param string $sep A seperated defaults to <br />
    * @return string
    * @access public
    */
  function showErrors($sep = "<br />"){
    $retval = "";
    foreach($this->errorMessage as $key => $error){
      if(!is_numeric($key)) {
        $error = $key.': '.$error;
      }
      $retval .= "$error $sep";
    }

    return $retval;
  }
}
