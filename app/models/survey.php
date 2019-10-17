<?php
App::import('Model', 'EvaluationBase');
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

/**
 * Survey
 *
 * @uses EvaluationBase
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Survey extends EvaluationBase
{
    public $name = 'Survey';
    public $useTable = null;
    const TEMPLATE_TYPE_ID = 3;

    public $hasMany = array('SurveyGroupSet' =>
        array('className'    =>  'SurveyGroupSet',
            'conditions'    => '',
            'order'         => '',
            'limit'         => '',
            'foreignKey'    => 'survey_id',
            'dependent'     => true,
            'exclusive'     => false,
            'finderQuery'   => '',
            'fields'        => '',
            'offset'        => '',
            'counterQuery'  => ''
        ),
        'Event' =>
        array('className'    => 'Event',
            'conditions'   => 'Event.event_template_type_id = 3',
            'order'         => '',
            'limit'         => '',
            'foreignKey'    => 'template_id',
            'dependent'     => true,
            'exclusive'     => false,
            'finderQuery'   => '',
            'fields'        => '',
            'offset'        => '',
            'counterQuery'  => ''),
    );

    public $hasAndBelongsToMany = array(
        'Question' => array(
            'className'    =>  'Question',
            'joinTable'    =>  'survey_questions',
            'foreignKey'   =>  'survey_id',
            'associationForeignKey'    =>  'question_id',
            'conditions'   =>  '',
            'order'        =>  'number',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => false,
        ),
    );

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Copyable', 'Traceable');

    public $contain = array('Question');

    public $validate = array(
        'name'  => array(
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Duplicate name found. Please select another.'
            ),
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please fill in the name of the survey template.'
            ),
        ),
        'availability' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select an availability option.'
        )
    );

    /**
     * __construct
     *
     * @param bool $id    id
     * @param bool $table table
     * @param bool $ds    data source
     *
     * @access protected
     * @return void
     */
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['question_count'] = sprintf('SELECT count(*) as question_count FROM survey_questions as q WHERE q.survey_id = %s.id', $this->alias);
    }

    /**
     * getSurveyWithQuestionsById
     *
     * @param mixed $surveyId
     *
     * @access public
     * @return survey with questions and responses
     */
    public function getSurveyWithQuestionsById($surveyId)
    {
        return $this->find('first', array(
            'conditions' => array('id' => $surveyId),
            'contain' => array('Question' => array('order' => 'SurveyQuestion.number ASC', 'Response'))
        ));
    }

    /**
     * Get the questions and responses for a given survey. This method will
     * format the responses into a format that's easier for cakephp's form
     * helper to use.
     *
     * @param int $surveyId - The id of the survey to get questions for
     *
     * @return An array of the questions, responses, and response options
     */
    public function getQuestions($surveyId)
    {
        // Get all required data from each table for every question
        $questions = $this->Question->find('all', 
            array(
                'conditions' => array('Survey.id' => $surveyId),
                'order' => 'SurveyQuestion.number'
            )
        );

        // Convert the response array into a flat options array for
        // the form input helper
        foreach ($questions as &$q) {
            if (isset($q['Response'])) {
                $options = array();
                foreach ($q['Response'] as $resp) {
                    $options[$resp['id']] = $resp['response'];
                }
                $q['ResponseOptions'] = $options;
            }
        }

        return $questions;
    }

    /**
     * Called after every deletion operation.
     *
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#afterDelete-1055
     */
	function afterDelete() {
        parent::afterDelete();
        CaliperHooks::survey_after_delete($this);
	}


    /**
     * Called before every deletion operation.
     *
     * @param boolean $cascade If true records that depend on this record will also be deleted
     * @return boolean True if the operation should continue, false if it should abort
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#beforeDelete-1054
     */
	function beforeDelete($cascade = true) {
        CaliperHooks::survey_before_delete($this);
        return parent::beforeDelete($cascade);
	}
}
