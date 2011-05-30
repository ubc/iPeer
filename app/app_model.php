<?php 
App::import('Lib', 'Toolkit');

class AppModel extends Model {
  var $errorMessage = 'Failed to save.';

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);

    // auto grab the creator and updater name for those tables that have 
    // creator_id and updater_id
    $local_alias = $this->alias.'1';
    if($this->hasField('creator_id')) {
      $f1 = sprintf('SELECT Creator.first_name FROM users as Creator JOIN %s as %s ON Creator.id = %s.creator_id WHERE %s.id = %s.id', $this->table, $local_alias, $local_alias, $local_alias, $this->alias);
      $f2 = sprintf('SELECT Creator.last_name FROM users as Creator JOIN %s as %s ON Creator.id = %s.creator_id WHERE %s.id = %s.id', $this->table, $local_alias, $local_alias, $local_alias, $this->alias);
      $this->virtualFields['creator'] = sprintf('CONCAT((%s)," ",(%s))', $f1, $f2);
    }

    if($this->hasField('updater_id')) {
      $f3 = sprintf('SELECT Updater.first_name FROM users as Updater JOIN %s as %s ON Updater.id = %s.updater_id WHERE %s.id = %s.id', $this->table, $local_alias, $local_alias, $local_alias, $this->alias);
      $f4 = sprintf('SELECT Updater.last_name FROM users as Updater JOIN %s as %s ON Updater.id = %s.updater_id WHERE %s.id = %s.id', $this->table, $local_alias, $local_alias, $local_alias, $this->alias);
      $this->virtualFields['updater'] = sprintf('CONCAT((%s)," ",(%s))', $f3, $f4);
    }
  }

  function beforeSave($options = array()) {
    $exists = $this->exists();
    if ( !$exists && $this->hasField('creator_id') && empty($this->data[$this->alias]['creator_id']) ) {
      $this->data[$this->alias]['creator_id'] = Toolkit::getUserId();
    }
    if ( $this->hasField('updater_id') && empty($this->data[$this->alias]['updater_id']) ) {
      $this->data[$this->alias]['updater_id'] = Toolkit::getUserId();
    }
    return parent::beforeSave($options);
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
