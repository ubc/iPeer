<?php
/**
 * EmailScheduleFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EmailScheduleFixture extends CakeTestFixture {
    public $name = 'EmailSchedule';

    public $import = 'EmailSchedule';

    public $records = array(
        array('id' => 1, 'subject' => 'To send', 'content' => 'This is Test Email', 'date' => '2011-07-10 00:00:00', 'from' => '2', 'to' => '2;3', 'course_id' => null, 'event_id' => null, 'grp_id' => null, 'sent' => 0, 'creator_id' => 1, 'created' => '2011-06-10 00:00:00' ),
        array('id' => 2, 'subject' => 'Sent', 'content' => 'This is Test Email', 'date' => '2011-07-10 00:00:00', 'from' => '2', 'to' => '2;3', 'course_id' => null, 'event_id' => null, 'grp_id' => null, 'sent' => 1, 'creator_id' => 2, 'created' => '2011-06-10 00:00:00' ),
        array('id' => 3, 'subject' => 'Not Yet', 'content' => 'This is Test Email', 'date' => '2015-07-10 00:00:00', 'from' => '2', 'to' => '2;3', 'course_id' => null, 'event_id' => null, 'grp_id' => null, 'sent' => 0, 'creator_id' => 3, 'created' => '2011-06-10 00:00:00' ),
    );
}
