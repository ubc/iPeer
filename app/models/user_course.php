<?php
/**
 * UserCourse
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserCourse extends AppModel
{
    public $name = 'UserCourse';

    public $belongsTo = array('User');
    public $actsAs = array('Traceable');


    /**
     * Saves all the instructors associated with a course to the user_courses table
     *
     * @param int   $course_id course id
     * @param array $data      instructors' data
     *
     * @access public
     * @return void
     */
    function insertInstructors($course_id, $data)
    {
        $instructorIDs = '';
        for ($i=1; $i<=$data['count']; $i++) {
            $pos = 0;
            if (!empty($data['instructor_id'.$i]) && $data['instructor_id'.$i] > 0) {
                //$pos = strpos($instructorIDs, $data['instructor_id'.$i]);
                $pos = strpos($instructorIDs, $data['instructor_id'.$i]);
                if (!(false!== $pos)) {
                    $newInstructor = array();
                    $newInstructor['UserCourse']['user_id'] = $data['instructor_id'.$i];
                    $newInstructor['UserCourse']['course_id'] = $course_id;
                    $newInstructor['UserCourse']['access_right'] = 'A';
                    $newInstructor['UserCourse']['record_status'] = 'A';
                    $this->save($newInstructor);
                    $this->id = null;
                    $instructorIDs .= $data['instructor_id'.$i].';';
                }
            }
        }
    }

}
