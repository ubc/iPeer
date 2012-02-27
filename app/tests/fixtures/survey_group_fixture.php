<?php
/**
 * SurveyGroupFixture
 *
 * @uses CakeTestFixture
 * @package Tests
 * @author  Pan Luo <pan.luo@ubc.ca>
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class SurveyGroupFixture extends CakeTestFixture
{
    public $name = 'SurveyGroup';
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'group_set_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'group_number' => array('type' => 'integer', 'null' => false, 'default' => '0'),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );
    public $records = array(
        array('id' => 1, 'group_set_id' => 1, 'group_number' => 1)
    );
}
