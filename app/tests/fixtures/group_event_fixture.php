<?php
/**
 * GroupEventFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class GroupEventFixture extends CakeTestFixture
{
    public $name = 'GroupEvent';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'event_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
        'marked' => array('type' => 'string', 'null' => false, 'default' => 'not reviewed'),
        'grade' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '12,2'),
        'grade_release_status' => array('type' => 'string', 'null' => false, 'default' => 'None', 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'comment_release_status' => array('type' => 'string', 'null' => false, 'default' => 'None', 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );

    public $records = array(
        array('id' => 1, 'group_id' => 1, 'event_id' => 1, 'record_status'=>'A', 'marked' => 'reviewed'),
        array('id' => 2, 'group_id' => 2, 'event_id' => 1, 'record_status'=>'A', 'marked' => 'not reviewed'),
        array('id' => 3, 'group_id' => 1, 'event_id' => 2, 'record_status'=>'A', 'marked' => 'reviewed'),
        array('id' => 4, 'group_id' => 2, 'event_id' => 2, 'record_status'=>'I'),
    );
}
