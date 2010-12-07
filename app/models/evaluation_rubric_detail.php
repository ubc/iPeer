<?php
class EvaluationRubricDetail extends AppModel
{
  var $name = 'EvaluationRubricDetail';
  
  var $belongsTo = array(
									 			'EvaluationRubric' =>
									 				array(
									 					'className' => 'EvaluationRubric',
									 					'foreignKey' => 'evaluation_rubric_id'
									 				)
									 );
  
	function getByEvalRubricIdCritera($rubricId=null, $criteria=null){
	  $sql = 'evaluation_rubric_id='.$rubricId;
	  if ($criteria != null) {
	    $sql .= ' AND criteria_number='.$criteria;
	  }
		return $this->find($sql);
	}
	
	function getAllByEvalRubricId($rubricId=null, $criteria=null){
	  $sql = 'evaluation_rubric_id='.$rubricId;
	  if ($criteria != null) {
	    $sql .= ' AND criteria_number='.$criteria;
	  }
		return $this->find('all',$sql);
	}	
}

?>
