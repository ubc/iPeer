<?php
class SysFunctionFixture extends CakeTestFixture {
  var $name = 'SysFunction';
  var $import = 'SysFunction';
  
  var $records = array(
  		array('id' => 1, 'function_code' => 'code1', 'function_name' => 'name1',
  			  'parent_id' => 0, 'controller_name' => 'controller1', 'url_link' => 'link1',
  			  'permission_type' => 'I', 'record_status' => 'A', 'creator_id' => 0,
  			  'created' => 0, 'updater_id' => null, 'modified' => null),
  		array('id' => 2, 'function_code' => 'code2', 'function_name' => 'name2',
  			  'parent_id' => 0, 'controller_name' => 'controller2', 'url_link' => 'link2',
  			  'permission_type' => 'I', 'record_status' => 'A', 'creator_id' => 0,
  			  'created' => 0, 'updater_id' => null, 'modified' => null),
  		array('id' => 3, 'function_code' => 'code3', 'function_name' => 'name3',
  			  'parent_id' => 0, 'controller_name' => 'controller3', 'url_link' => 'link3',
  			  'permission_type' => 'A', 'record_status' => 'A', 'creator_id' => 0,
  			  'created' => 0, 'updater_id' => null, 'modified' => null),
  		array('id' => 4, 'function_code' => 'code4', 'function_name' => 'name4',
  			  'parent_id' => 1, 'controller_name' => 'controller4', 'url_link' => 'link4',
  			  'permission_type' => 'A', 'record_status' => 'A', 'creator_id' => 0,
  			  'created' => 0, 'updater_id' => null, 'modified' => null)
  		);
}

