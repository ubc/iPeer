<?php
/**
 * MixevalFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class MixevalFixture extends CakeTestFixture
{
    public $name = 'Mixeval';

    public $import = 'Mixeval';

    public $records = array(
        array('id' => 1, 'name' => 'Mixeval', 'zero_mark' => 0,
        'scale_max' => 0, 'availability' => 1, 'creator_id' => 1, 'created' => '0000-00-00 00:00:00',
        'updater_id' => null, 'modified' => '0000-00-00 00:00:00')
    );
}
