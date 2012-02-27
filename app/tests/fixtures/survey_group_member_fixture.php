<?php
/**
 * SurveyGroupMemberFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveyGroupMemberFixture extends CakeTestFixture
{
    public $name = 'SurveyGroupMember';
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'group_set_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
        'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );
    public $records = array(
        array('id' => 1, 'group_set_id' => 1, 'group_id' => 1, 'user_id' => 1),
        array('id' => 2, 'group_set_id' => 1, 'group_id' => 2, 'user_id' => 2)
    );
}
