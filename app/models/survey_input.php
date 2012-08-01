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
     * getAllSurveyInputBySurveyIdUserId
     *
     * @param mixed $surveyId survey id
     * @param mixed $userId   user id
     *
     * @access public
     * @return void
     */
    function getAllSurveyInputBySurveyIdUserId($surveyId, $userId)
    {
        return $this->find('all', array('conditions' => array('survey_id' => $surveyId,
            'user_id' => $userId),
        'order' => 'question_id'));
    }


    /**
     * getAllSurveyInputBySurveyIdUserIdQuestionId
     *
     * @param mixed $surveyId   survey id
     * @param mixed $userId     user id
     * @param mixed $questionId question id
     *
     * @access public
     * @return void
     */
    function getAllSurveyInputBySurveyIdUserIdQuestionId($surveyId, $userId, $questionId)
    {
        return $this->find('all', array('conditions' => array('survey_id' => $surveyId,
            'user_id' => $userId,
            'question_id' => $questionId)));
    }

    /**
     * delAllSurveyInputBySurveyIdUserIdQuestionId
     *
     * @param mixed $surveyId   survey id
     * @param mixed $userId     user id
     * @param mixed $questionId question id
     *
     * @access public
     * @return void
     */
    function delAllSurveyInputBySurveyIdUserIdQuestionId($surveyId, $userId, $questionId)
    {
        return $this->deleteAll(array('survey_id' => $surveyId,
            'user_id' => $userId,
            'question_id' => $questionId));
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


    /**
     * findCountInSurveyGroup
     * SELECT user_id
     * FROM survey_inputs
     * WHERE survey_id='.$surveyId.'
     *     AND question_id='.$questionId.'
     *     AND response_id='.$responseId.'
     *     AND user_id IN (SELECT user_id FROM survey_group_members WHERE group_id='.$surveyGroupId.');
     *
     * @param bool $surveyId      survey id
     * @param bool $questionId    question id
     * @param bool $responseId    response id
     * @param bool $surveyGroupId survey group id
     *
     * @access public
     * @return void
     */
    function findCountInSurveyGroup($surveyId, $questionId, $responseId, $surveyGroupId)
    {
        $this->SurveyGroupMembers = ClassRegistry::init('SurveyGroupMembers');
        $inputs = $this->find(
            'all', 
            array('conditions' => array(
                    'survey_id' => $surveyId,
                    'question_id' => $questionId,
                    'response_id' => $responseId
                )
            )
        );
        $groupMembers = $this->SurveyGroupMembers->find(
            'all', 
            array('conditions' => array('group_id' => $surveyGroupId))
        );

        $count = 0;
        foreach ($groupMembers as $member) {
            foreach ($inputs as $input) {
                if ($member['SurveyGroupMembers']['user_id'] == 
                    $input['SurveyInput']['user_id']) {
                    $count++;
                    break;
                }
            }
        } 
        return $count;
    }


    /**
     * findResponseInSurveyGroup
     * SELECT user_id,response_text
     *     FROM survey_inputs
     *     WHERE survey_id='.$surveyId.'
     *         AND question_id='.$questionId.'
     *         AND user_id IN (SELECT user_id FROM survey_group_members WHERE group_id='.$surveyGroupId.')
     *
     * @param bool $surveyId      survey id
     * @param bool $questionId    question id
     * @param bool $surveyGroupId survey group id
     *
     * @access public
     * @return void
     */
    function findResponseInSurveyGroup($surveyId, $questionId, $surveyGroupId)
    {
        $this->SurveyGroupMembers = ClassRegistry::init('SurveyGroupMembers');

        $conditionSubQuery['`SurveyGroupMembers2`.`group_id`'] = $surveyGroupId;
        $dbo = $this->SurveyGroupMembers->getDataSource();
        $subQuery = $dbo->buildStatement(
            array(
                'fields' => array('`SurveyGroupMembers2`.`user_id`'),
                'table'  => $dbo->fullTableName($this->SurveyGroupMembers),
                'alias'  => 'SurveyGroupMembers2',
                'limit'  => null,
                'offset' => null,
                'joins'  => array(),
                'conditions' => $conditionSubQuery,
                'order'  => null,
                'group'  => null,
            ),
            $this->SurveyGroupMembers
        );
        $subQuery = ' user_id IN (' . $subQuery . ') ';
        $subQueryExpression = $dbo->expression($subQuery);
        $conditions = array(
            'survey_id' => $surveyId,
            'question_id' => $questionId,
        );
        $conditions[] = $subQueryExpression;
        return $this->find('all',
            array(
                'conditions' => $conditions,
                'fields' => array('user_id', 'response_text'),
            )
        );
    }
}
