<?php
/**
 * SurveyInput
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveyInput extends AppModel
{
    public $name = 'SurveyInput';
    public $belongsTo = array('User');
    public $actsAs = array('Containable');

    /**
     * getByEventIdUserId
     *
     * @param mixed $eventId event id
     * @param mixed $userId  user id
     *
     * @access public
     * @return void
     */
    function getByEventIdUserId($eventId, $userId)
    {
        return $this->find('all', array(
            'conditions' => array(
                'event_id' => $eventId,
                'user_id' => $userId
            ),
            'order' => 'question_id ASC',
            'contain' => false,
        ));
    }


    /**
     * getByEventIdUserIdQuestionId
     *
     * @param mixed $eventId    event id
     * @param mixed $userId     user id
     * @param mixed $questionId question id
     *
     * @access public
     * @return void
     */
    function getByEventIdUserIdQuestionId($eventId, $userId, $questionId)
    {
        return $this->find('all', array(
            'conditions' => array(
                'event_id' => $eventId,
                'user_id' => $userId,
                'question_id' => $questionId
            ),
            'contain' => false,
        ));
    }
}
