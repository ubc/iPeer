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


    /**
     * returns all the instructor names and ids for display on the view page
     * Deprecated: Use the same function in User model instead
     *
     * @param int $id course id
     *
     * @access public
     * @return UserCourse data
     */
    /*
    function getInstructors($id)
    {
        //$result = $this->query('SELECT User.id, User.first_name, User.last_name, User.email FROM user_courses JOIN users as User ON User.id=user_courses.user_id AND User.id <> 1 WHERE course_id='.$id);
        //return $result;
        return $this->find('all', array(
            'conditions' => array('course_id' => $id),
            'fields' => array('User.id', 'User.first_name', 'User.last_name', 'User.email'),
            'joins' => array(
                array(
                    'table' => 'user',
                    'alias' => 'User',
                    'type' => 'LEFT',
                    'conditions' => array('User.id = UserCourse.user_id')
                ))
            ));
    }*/


    /**
     * Returns all the instructor id list
     * Deprecated: Use the function in User model instead
     *
     * @param int $course_id course id
     *
     * @return list of instuctors' ids
     */
    /*
    function getInstructorsId($course_id = null)
    {
        return $this->find('list', array(
            'conditions' => array('course_id' => $course_id),
            'fields' => array('user_id')
        ));
    }*/

    /**
     * function called for every newly added course to place root account as admin
     * @param int $id user id
     *
     * @access public
     * @return void
     */
    function insertAdmin($id=null)
    {
        $tmp = array( 'user_id'=>1, 'course_id'=>$id, 'access_right'=>'A', 'record_status'=>'A' );
        $this->save($tmp);
    }

}
