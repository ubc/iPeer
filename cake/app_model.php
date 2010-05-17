<?php
/* SVN FILE: $Id: app_model.php,v 1.6 2006/08/24 22:59:51 rrsantos Exp $ */
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright (c)	2006, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright (c) 2006, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package			cake
 * @subpackage		cake.cake
 * @since			CakePHP v 0.2.9
 * @version			$Revision: 1.6 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2006/08/24 22:59:51 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/app_model.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package		cake
 * @subpackage	cake.cake
 */
class AppModel extends Model{
	
  /**
   * Saves data into model. Similar to save(), but inserts data with an existing id.
   * Used mainly for importing data from ipeer 1.6
   * @param array $data Data to save.
   * @param boolean $validate If set, validation will be done before the save
   * @param array $fieldList List of fields to allow to be written
   * @return boolean success
   */
  function directSave($data=null, $validate = true, $fieldList = array()) {
    $db =& ConnectionManager::getDataSource($this->useDbConfig);

    if ($data) {
      if (countdim($data) == 1) {
        $this->set(array($this->name => $data));
      } else {
        $this->set($data);
      }
    }

    $whitelist = !(empty($fieldList) || count($fieldList) == 0);

    if ($validate && !$this->validates()) {
      return false;
    }

    $fields = $values = array();
    $count = 0;

    if (count($this->data) > 1) {
      $weHaveMulti = true;
      $joined = false;
    } else {
      $weHaveMulti = false;
    }

    $newID = null;

    foreach($this->data as $n => $v) {
      if (isset($weHaveMulti) && isset($v[$n]) && $count > 0 && count($this->hasAndBelongsToMany) > 0) {
        $joined[] = $v;
      } else {
        if ($n === $this->name) {
          foreach($v as $x => $y) {
            if ($this->hasField($x) && ($whitelist && in_array($x, $fieldList) || !$whitelist)) {
              $fields[] = $x;
              $values[] = $y;

              if ($x == $this->primaryKey && !empty($y)) {
                $newID = $y;
              }
            }
          }
        }
      }
      $count++;
    }

    if (empty($this->id) && $this->hasField('created') && !in_array('created', $fields) && ($whitelist && in_array('created', $fieldList) || !$whitelist)) {
      $fields[] = 'created';
      $values[] = date('Y-m-d H:i:s');
    }

    if ($this->hasField('modified') && !in_array('modified', $fields) && ($whitelist && in_array('modified', $fieldList) || !$whitelist)) {
      $fields[] = 'modified';
      $values[] = date('Y-m-d H:i:s');
    }

    if ($this->hasField('updated') && !in_array('updated', $fields) && ($whitelist && in_array('updated', $fieldList) || !$whitelist)) {
      $fields[] = 'updated';
      $values[] = date('Y-m-d H:i:s');
    }

    if (!$this->exists()) {
      $this->id = false;
    }

    if (count($fields)) {
      if ($db->create($this, $fields, $values)) {
        $this->__insertID = $db->lastInsertId($this->tablePrefix . $this->table, $this->primaryKey);

        if (!$this->__insertID && $newID != null) {
          $this->__insertID = $newID;
          $this->id = $newID;
        } else {
          $this->id = $this->__insertID;
        }

        if (!empty($joined)) {
          $this->__saveMulti($joined, $this->id);
        }

        $this->afterSave();
        $this->data = false;
        $this->_clearCache();
        $this->validationErrors = array();
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }  	
  }
}
?>