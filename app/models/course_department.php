<?php
/**
 * DepartmentCourse
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CourseDepartment extends AppModel
{
    public $name = 'CourseDepartment';
    public $belongsTo = array('Course', 'Department');
}
