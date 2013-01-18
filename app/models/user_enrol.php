<?php
/**
 * UserEnrol
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserEnrol extends AppModel
{
    public $name = 'UserEnrol';
    public $actsAs = array('Traceable');

    public $belongsTo = array('User');

    
    /**
     * Enroll user in courses
     *
     * @param int $user_id    user id
     * @param int $course_ids course id
     *
     * @access public
     * @return void
     */
    function insertCourses ($user_id, $course_ids)
    {

        if (!is_array($course_ids) || empty($course_ids) || $user_id <= 0) {
            return;
        }

        $course_ids = array_unique($course_ids);

        foreach ($course_ids as $id) {
            $c = array();
            $c['UserEnrol']['course_id']  = $id;
            $c['UserEnrol']['user_id']    = $user_id;
            $c['UserEnrol']['record_status'] = 'A';
            $this->save($c);
            $this->id = null;
        }
    }
}
