<?php
/**
 * EvaluationRubricDetail
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EvaluationRubricDetail extends AppModel
{
    public $name = 'EvaluationRubricDetail';
    public $actsAs = array('Traceable');

    public $belongsTo = array(
        'EvaluationRubric' =>array(
            'className' => 'EvaluationRubric',
            'foreignKey' => 'evaluation_rubric_id'
        )
    );

    /**
     * getByEvalRubricIdCritera
     *
     * @param bool $rubricId    rubric id
     * @param bool $criteriaNum criter id num
     *
     * @access public
     * @return void
     */
    function getByEvalRubricIdCritera($rubricId=null, $criteriaNum=null)
    {
        //	  $sql = 'evaluation_rubric_id='.$rubricId;
        //	  if ($criteriaNum != null) {
        //	    $sql .= ' AND criteria_number='.$criteriaNum;
        //	  }
        //		return $this->find($sql);
            /*$conditions = array('evaluation_rubric_id' => $rubricId);
            if ($criteriaNum != null)
                $conditions = array('criteria_number' => $criteriaNum);
            return $this->find('first', array(
                'conditions' => $condtions
            ));*/
        return $this->find('first', array('conditions'=>array('evaluation_rubric_id'=>$rubricId , 'criteria_number'=>$criteriaNum)));
    }


    /**
     * getAllByEvalRubricId
     *
     * @param bool $rubricId
     *
     * @access public
     * @return void
     */
    function getAllByEvalRubricId($rubricId=null)
    {
        //	  $sql = 'evaluation_rubric_id='.$rubricId;
        //	  if ($criteria != null) {
        //	    $sql .= ' AND criteria_number='.$criteria;
        //	  }
        //		return $this->find('all', $sql);
            /*$conditions = array('evaluation_rubric_id' => $rubricId);
            if ($criteriaNum != null)
                $conditions = array('criteria_number' => $criteriaNum);
            return $this->find('all', array(
            'conditions' => $condtions*/
        $condition = array('evaluation_rubric_id'=> $rubricId);
        return $this->find('all', array('conditions' => $condition,
            'order' => array('criteria_number ASC')));
    }
}
