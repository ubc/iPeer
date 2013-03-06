<?php
/**
 * EvaluationMixevalDetail
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationMixevalDetail extends AppModel
{
    public $name = 'EvaluationMixevalDetail';
    public $actsAs = array('Traceable', 'Containable');

    public $belongsTo = array('EvaluationMixeval' =>
        array(
            'className' => 'EvaluationMixeval',
            'foreignKey' => 'evaluation_mixeval_id'
        )
    );

    /**
     * getLickertScaleQuestionResults
     *
     * @param bool $mixedEvalId
     *
     * @access public
     * @return void
     */
    function getLickertScaleQuestionResults($mixedEvalId=null)
    {
        return $this->find('all', array(
            'conditions' => array('evaluation_mixeval_id' => $mixedEvalId, 'grade >' => 0),
            'order' => array('question_number' => 'ASC')
        ));
    }


    /**
     * getByEvalMixevalIdCriteria
     *
     * @param bool $MixevalId   mixeval id
     * @param bool $questionNum question number
     *
     * @access public
     * @return void
     */
    function getByEvalMixevalIdCriteria($MixevalId=null, $questionNum=null)
    {
        //	  $sql = 'evaluation_mixeval_id='.$MixevalId;
        //	  if ($questionNum != null) {
        //	    $sql .= ' AND question_number='.$questionNum;
        //	  }
        //		return $this->find($sql);
        $conditions = array('evaluation_mixeval_id' => $MixevalId);
        if ($questionNum != null) {
            $conditions['question_number'] = $questionNum;
        }

        $type=null;
        if (!is_numeric($questionNum)) {
            $type = 'all';
        } else {
            $type = 'first';
        }

        return $this->find($type, array(
            'conditions' => $conditions, 'order'=>'question_number',
            'contain' => false,
        ));
    }
}
