<?php
/**
 * UserGradePenaltyFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserGradePenaltyFixture extends CakeTestFixture
{
    public $name = 'UserGradePenalty';
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'penalty_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
        'grp_event_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
    );
    public $records = array(
    );
}
