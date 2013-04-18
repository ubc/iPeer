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

    public $validate = array(
        'prompt' => array(
            'rule' => 'notEmpty',
            'message' => 'Please enter a prompt.',
            'required' => true
        ),
        'type' => array(
            'rule' => array('inList', array('M', 'C', 'S', 'L')),
            'message' => 'Please select a valid type.',
            'required' => true
        ),
        'master' => array(
            'rule' => array('inList', array('yes', 'no')),
            'message' => 'Please select yes or no for master.',
            'required' => true
        )
    );

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
    
    /**
     * copyQuestions
     * copies the questions and responses to a new survey
     *
     * @param mixed $quesIds
     * @param mixed $surveyId
     *
     * @access public
     * @return void
     */
    function copyQuestions($quesIds, $surveyId)
    {
        $ids = Set::extract('/SurveyQuestion/question_id', $quesIds);
        $order = Set::combine($quesIds, '{n}.SurveyQuestion.question_id', '{n}.SurveyQuestion.number');
        $questions = $this->findAllById($ids);
        foreach ($questions as $ques) {
            $quesId = $ques['Question']['id'];
            //$quesNo['SurveyQuestion']['number'] = $order[$ques['Question']['id']];
            unset($ques['Survey'], $ques['Question']['id']);
            $ques['Question']['master'] = 'no';
            foreach ($ques['Response'] as &$resp) {
                unset($resp['id'], $resp['question_id']);
            }
            unset($resp);
            if (empty($ques['Response'])) {
                unset($ques['Response']);
            }
            $ques['Survey']['id'] = $surveyId;
            $this->saveAll($ques);
            $quesNo[$this->getInsertId()] = $order[$quesId];
        }
        return $quesNo;
    }
}
