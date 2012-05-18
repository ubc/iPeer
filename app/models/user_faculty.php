<?php
/**
 * UserFaculty
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserFaculty extends AppModel
{
    public $name = 'UserFaculty';
    public $belongsTo = array('User', 'Faculty');

    /**
     * getFaculty
     *
     * @param bool $user_id
     *
     * @access public
     * @return void
     */
    function getFaculty($user_id=null)
    {
        return $this->find('first', array('conditions' =>
            array('user_id' => $user_id)));
    }

}
