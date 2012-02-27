<?php
/**
 * SurveyQuestionFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveyQuestionFixture extends CakeTestFixture
{
    public $name = 'SurveyQuestion';
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'survey_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
        'number' => array('type' => 'integer', 'null' => false, 'default' => '9999'),
        'question_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'question_id' => array('column' => 'question_id', 'unique' => 0), 'survey_id' => array('column' => 'survey_id', 'unique' => 0)),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );
    public $records = array(
        array('id' => 1, 'survey_id' => 1, 'number' => 1, 'question_id' => 1),
        array('id' => 2, 'survey_id' => 1, 'number' => 2, 'question_id' => 2),
        array('id' => 6, 'survey_id' => 1, 'number' => 3, 'question_id' => 6),
        array('id' => 3, 'survey_id' => 2, 'number' => 9999, 'question_id' => 3),
        array('id' => 4, 'survey_id' => 2, 'number' => 9999, 'question_id' => 4),
        array('id' => 5, 'survey_id' => 2, 'number' => 9999, 'question_id' => 5)
    );
}
