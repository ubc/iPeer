<?php
/**
 * UserGradePenalty
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class UserGradePenalty extends AppModel
{
    public $name = 'UserGradePenalty';

    /**
     * getUserGradePenaltyById
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function getUserGradePenaltyById($id)
    {
        return $this->find('first', array('conditions' => array('UserGradePenalty.id' => $id)));
    }


    /**
     * getByUserIdGrpEventId
     *
     * @param mixed $grpEventId group event id
     * @param mixed $userId     user id
     *
     * @access public
     * @return void
     */
    function getByUserIdGrpEventId($grpEventId, $userId)
    {
        return $this->find('first', array('conditions' =>
            array('grp_event_id' => $grpEventId, 'user_id' => $userId)));
    }
}
