<?php
class EvaluationRubricDetail extends AppModel
{
  var $name = 'EvaluationRubricDetail';
  var $actsAs = array('Traceable');
  
  var $belongsTo = array('EvaluationRubric' =>array(
										'className' => 'EvaluationRubric',
									 	'foreignKey' => 'evaluation_rubric_id'
									 			   )
						);
  
	function getByEvalRubricIdCritera($rubricId=null, $criteriaNum=null){
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
		if($criteriaNum != null)
			return $this->find('first', array('conditions'=>array('evaluation_rubric_id'=>$rubricId , 'criteria_number'=>$criteriaNum)));
		else $this->find('first', array('conditions'=>array('evaluation_rubric_id'=>$rubricId)));	
	}
	
	function getAllByEvalRubricId($rubricId=null, $criteriaNum=null){
//	  $sql = 'evaluation_rubric_id='.$rubricId;
//	  if ($criteria != null) {
//	    $sql .= ' AND criteria_number='.$criteria;
//	  }
//		return $this->find('all',$sql);
            /*$conditions = array('evaluation_rubric_id' => $rubricId);
            if ($criteriaNum != null)
                $conditions = array('criteria_number' => $criteriaNum);
            return $this->find('all', array(
                'conditions' => $condtions*/
			if($criteriaNum != null)
				return $this->find('all', array('conditions'=>array('evaluation_rubric_id'=>$rubricId, 'criteria_number'=>$criteriaNum)));
			else return $this->find('all', array('conditions'=>array('evaluation_rubric_id'=> $rubricId)));
	}	
}

?>
