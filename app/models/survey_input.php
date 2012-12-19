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

    /**
     * getBySurveyIdUserId
     *
     * @param mixed $surveyId survey id
     * @param mixed $userId   user id
     *
     * @access public
     * @return void
     */
    function getByEventIdUserId($eventId, $userId)
    {
        return $this->find(
            'all', 
            array(
                'conditions' => array(
                    'event_id' => $eventId,
                    'user_id' => $userId
                ),
                'order' => 'question_id'
            )
        );
    }


    /**
     * getBySurveyIdUserIdQuestionId
     *
     * @param mixed $surveyId   survey id
     * @param mixed $userId     user id
     * @param mixed $questionId question id
     *
     * @access public
     * @return void
     */
    function getBySurveyIdUserIdQuestionId($eventId, $userId, $questionId)
    {
        return $this->find(
            'all', 
            array(
                'conditions' => array(
                    'event_id' => $eventId,
                    'user_id' => $userId,
                    'question_id' => $questionId
                )
            )
        );
    }

    /**
     * beforeSave
     *
     *
     * @access public
     * @return void
     */
    function beforeSave()
    {
        //check for duplicate submission
        return true;
    }


    /**
     * checkDuplicate
     *
     *
     * @access public
     * @return void
     */
    function checkDuplicate()
    {
        //check duplicate
    }

}
