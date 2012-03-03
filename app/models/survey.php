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

    public $hasAndBelongsToMany = array('Question' =>
        array('className'    =>  'Question',
            'joinTable'    =>  'survey_questions',
            'foreignKey'   =>  'survey_id',
            'associationForeignKey'    =>  'question_id',
            'conditions'   =>  '',
            'order'        =>  '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => false,
        ),
    );

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Copyable', 'Traceable');

    public $contain = array('Question');

  /*function __checkDuplicateSurvey()
{
    // Set up query condition for dusplicates
    $conditions = array();
    $conditions['name'] = $this->data[$this->alias]['name'];
    if (!empty($this->data[$this->alias]['id'])) {
      $conditions[$this->alias.'.id <>']= $this->data[$this->name]['id'];
    }

    $count = $this->find('count', array('conditions' =>$conditions));
    $duplicate = $count > 0;

    if ($duplicate) {
      $this->errorMessage='Duplicate Survey found. Please change the Survey name and try again';
      return false;
    } else {
      return true;
    }
  }*/


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



    ///*  function beforeDelete()
    //{
    //    $event = new Event();
    //    return $event->removeEventsBySurveyId($this->id);
    //  }*/
    //
    //  function getSurveyResult($courseId=null)
    //{
    //    $condition = 'Survey.course_id='.$courseId;
    //    $fields = 'Survey.id,Survey.name,User.id,User.first_name,User.last_name,User.student_no,EvaluationSubmission.id,EvaluationSubmission.submitted,EvaluationSubmission.date_submitted';
    //    $joinTable = array(' LEFT JOIN (users as Users CROSS JOIN evaluation_submissions as EvaluationSubmission) ON (User.id=EvaluationSubmission.submitter_id');
    //
    //    return $this->find('all', $condition, $fields, null, null, null, null, $joinTable );
    //    return $this->find('all', array(
    //        'conditions' => array('Survey.course_id' => $courseId),
    //        'fields' => array('Survey.id', 'Survey.name', 'User.id', 'User.first_name', 'User.last_name', 'User.student_no', 'EvaluationSubmission.id', 'EvaluationSubmission.submitted', 'EvaluationSubmission.date_submitted'),
    //        'joins' => array(
    //            array(
    //                'table' => 'evaluation_submissions',
    //                'alias' => 'EvaluationSubmission',
    //                'type' => 'CROSS'
    //            ),
    //            array(
    //                'table' => 'EvaluationSubmission',
    //                'alias' => 'CrossJoined',
    //                'type' => 'LEFT',
    //                'conditions' => array('User.id = EvaluationSubmission.submitter_id')
    //            )
    //        )
    //    ));
    //  }

    /**
     * getSurveyWithSubmissionById
     *
     * @param mixed $survey_id  survey id
     * @param bool  $conditions conditions
     *
     * @access public
     * @return void
     */
    function getSurveyWithSubmissionById($survey_id, $conditions = array())
    {
        $evaluation_submission = Classregistry::init('EvaluationSubmission');

        $survey = $this->find('first', array('conditions' => array('Survey.id' => $survey_id),
            'contain' => array('Course' => array('Enrol' => array('conditions' => $conditions)),
            'Event')));
        if (empty($survey)) {
            return false;
        }

        $user_ids = Set::extract('/Course/Enrol/id', $survey);
        $submissions = $evaluation_submission->find('list', array('conditions' => array('event_id' => $survey['Event'][0]['id'],
            'submitter_id' => $user_ids),
        'fields' => array('submitter_id', 'date_submitted')));
        foreach ($survey['Course']['Enrol'] as $key => $student) {
            $survey['Course']['Enrol'][$key]['date_submitted'] = isset($submissions[$student['id']]) ? $submissions[$student['id']] : '';
        }
        return $survey;
    }

}
