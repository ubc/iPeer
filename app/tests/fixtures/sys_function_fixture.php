<?php
/**
 * SysFunctionFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SysFunctionFixture extends CakeTestFixture
{
    public $name = 'SysFunction';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'primary'),
        'function_code' => array('type' => 'string', 'null' => false, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'function_name' => array('type' => 'string', 'null' => false, 'length' => 200, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'parent_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'controller_name' => array('type' => 'string', 'null' => false, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'url_link' => array('type' => 'string', 'null' => false, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'permission_type' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
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
