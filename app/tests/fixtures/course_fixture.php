<?php
/**
 * CourseFixture
 *
 * @uses CakeTestFixture
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CourseFixture extends CakeTestFixture
{
    public $name = 'Course';

    public $import = array('model' => 'Course', 'records' => true);
}
