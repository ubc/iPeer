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
    
    /**
     * put course in departments
     *
     * @param mixed $courseId      course id
     * @param mixed $departmentIds department id
     *
     * @access public
     * @return void
     */
    function insertCourses($courseId, $departmentIds)
    {
        if (!is_array($departmentIds) || empty($departmentIds) || $courseId <= 0) {
            return;
        }
        
        foreach (array_unique($departmentIds) as $id) {
            $temp = array();
            $temp['CourseDepartment']['course_id'] = $courseId;
            $temp['CourseDepartment']['department_id'] = $id;
            $this->save($temp);
            $this->id = null;
        }
    }
}
