<?php
App::import('Model', 'EvaluationBase');

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

    public $belongsTo = array('Course' =>
        array('className'  =>  'Course',
            'conditions' =>  '',
            'order'      =>  '',
            'foreignKey' =>  'course_id'),
    );

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
            )
        ),
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
     * getSurveyIdByCourseIdTitle
     *
     * @param bool $courseId course id
     * @param bool $title    title
     *
     * @access public
     * @return void
     */
    function getSurveyIdByCourseIdTitle($courseId=null, $title=null)
    {
        //$tmp = $this->find('course_id='.$courseId.' AND name=\''.$title.'\'', 'id');
        $tmp = $this->find('first', array(
            'conditions' => array('course_id' => $courseId, 'name' => $title),
            'fields' => array('id')
        ));
        return $tmp['Survey']['id'];
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
}
