<?php
/**
 * EventFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EventFixture extends CakeTestFixture
{
    public $name = 'Event';

    public $import = 'Event';

    public $records = array(
        array('id' => 1, 'title' => 'Event1', 'course_id' => 1, 'event_template_type_id' => 2, 'due_date' => '2011-06-10 00:00:00' ),
        array('id' => 2, 'title' => 'Event2', 'course_id' => 1, 'event_template_type_id' => 1, 'due_date' => '2022-06-10 00:00:00'),
        array('id' => 3, 'title' => 'Event3', 'course_id' => 2, 'event_template_type_id' => 1),
        array('id' => 4, 'title' => 'Event4', 'course_id' => 2, 'event_template_type_id' => 3, 'template_id' =>2),
        array('id' => 5, 'title' => 'Event5', 'course_id' => 3, 'event_template_type_id' => 3, 'record_status'=>'A', 'template_id' =>3),
        array('id' => 6, 'title' => 'Event6', 'course_id' => 3, 'event_template_type_id' => 3, 'record_status'=>'I'),
        array('id' => 7, 'title' => 'Event7', 'course_id' => 3, 'event_template_type_id' => 4, 'record_status'=>'I'),
    );

}


