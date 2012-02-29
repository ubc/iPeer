<?php
/**
 * EmailMergeFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EmailMergeFixture extends CakeTestFixture
{
    public $name = 'EmailMerge';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'key' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'value' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'table_name' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'field_name' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
    );

    public $records = array(
        array('id' => 1, 'key' => 'Username', 'value' => '{{{USERNAME}}}', 'table_name' => 'User', 'field_name' => 'username', 'created' => '2011-06-10 00:00:00', 'modified' => '2011-06-10 00:00:00' ),
        array('id' => 2, 'key' => 'First Name', 'value' => '{{{FIRSTNAME}}}', 'table_name' => 'User', 'field_name' => 'first_name', 'created' => '2011-06-10 00:00:00', 'modified' => '2011-06-10 00:00:00' ),
        array('id' => 3, 'key' => 'Last Name', 'value' => '{{{LASTNAME}}}', 'table_name' => 'User', 'field_name' => 'last_name', 'created' => '2011-06-10 00:00:00', 'modified' => '2011-06-10 00:00:00' ),
        array('id' => 4, 'key' => 'Email Address', 'value' => '{{{EMAIL}}}', 'table_name' => 'User', 'field_name' => 'email', 'created' => '2011-06-10 00:00:00', 'modified' => '2011-06-10 00:00:00' ),
    );

}
