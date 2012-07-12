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

    public $import = 'GroupEvent';

    public $records = array(
        array('id' => 1, 'group_id' => 1, 'event_id' => 1, 'record_status'=>'A', 'marked' => 'reviewed'),
        array('id' => 2, 'group_id' => 2, 'event_id' => 1, 'record_status'=>'A', 'marked' => 'not reviewed'),
        array('id' => 3, 'group_id' => 1, 'event_id' => 2, 'record_status'=>'A', 'marked' => 'reviewed'),
        array('id' => 4, 'group_id' => 2, 'event_id' => 2, 'record_status'=>'I'),
    );
}
