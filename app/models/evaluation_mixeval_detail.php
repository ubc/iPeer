<?php
class EvaluationMixevalDetail extends AppModel
{
  var $name = 'EvaluationMixevalDetail';
  var $actsAs = array('Traceable');
  
  var $belongsTo = array('EvaluationMixeval' => 
  											array(
									 			'className' => 'EvaluationMixeval',
									 			'foreignKey' => 'evaluation_mixeval_id'
									 			 )
						);
  
	function getByEvalMixevalIdCritera($MixevalId=null, $questionNum=null){
//	  $sql = 'evaluation_mixeval_id='.$MixevalId;
//	  if ($questionNum != null) {
//	    $sql .= ' AND question_number='.$questionNum;
//	  }
//		return $this->find($sql);
            $conditions['EvaluationMixevalDetail.evaluation_mixeval_id'] = $MixevalId;
            if(!is_null($questionNum))
                $conditions['EvaluationMixevalDetail.question_number'] = $questionNum;
            
            $type = is_null($questionNum)? 'all':'first';
            $result = $this->find($type, array(
                'conditions' => $conditions, 
                'recursive' => -1,
                'order'=>'EvaluationMixevalDetail.question_number'
            ));
            return $result;
	}
}
?>