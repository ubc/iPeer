<?php
/**
 * Question
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Question extends AppModel
{
    public $name = 'Question';
    public $displayField = 'prompt';

    public $hasMany = array('Response' =>
        array('className' => 'Response',
            'foreignKey' => 'question_id',
            'dependent' => true)
        );

    public $hasAndBelongsToMany = array('Survey' =>
        array('className'    =>  'Survey',
            'joinTable'    =>  'survey_questions',
            'foreignKey'   =>  'question_id',
            'associationForeignKey'    =>  'survey_id',
            'conditions'   =>  '',
            'order'        =>  '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => false,
        ),
    );

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

    /**
     * getTypeById
     *
     * @param bool $id
     *
     * @access public
     * @return string question type, null if id = null
     */
    function getTypeById($id = null)
    {
        if (null == $id) {
            return null;
        }

        $type = $this->find('first', array(
            'conditions' => array('Question.id' => $id),
            'fields' => array('type')
        ));
        return $type['Question']['type'];
    }

    /**
     * getQuestionsForMakingGroupBySurveyId
     * get all the questions that are available for makeing groups
     * E.g. select/checkbox questions
     * The result of questions are ordered
     *
     * @param mixed $surveyId survey id
     *
     * @access public
     * @return array of the questions
     */
    function getQuestionsForMakingGroupBySurveyId($surveyId)
    {
        $questions = $this->find('all', array(
            'fields' => array($this->alias.'.*'),
            'conditions' => array(
                'type' => array('C', 'M'),
                'Survey.id' => $surveyId,
            ),
            'order' => 'number ASC',
            'contain' => 'Survey',
        ));

        return Set::classicExtract($questions, '{n}.Question');
    }

    /**
     * getQuestionsListBySurveyId
     * return the list of questions for select
     *
     * @param mixed $surveyId survey id
     *
     * @access public
     * @return void
     */
    function getQuestionsListBySurveyId($surveyId)
    {
        $questions = $this->getQuestionsForMakingGroupBySurveyId($surveyId);

        return Set::combine($questions, '{n}.id', '{n}.prompt');
    }
}
