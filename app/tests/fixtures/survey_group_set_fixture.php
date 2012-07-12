<?php
/**
 * SurveyGroupSetFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveyGroupSetFixture extends CakeTestFixture
{
    public $name = 'SurveyGroupSet';

    public $import = 'SurveyGroupSet';

    public $records = array(
        array('id' => 1, 'survey_id' => 1, 'set_description' => 'HELLO 1', 'num_groups' => 1, 'date' => 0, 'released' => 0)
    );
}

