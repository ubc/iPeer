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
  
	function getLickertScaleQuestionResults($mixedEvalId=null) {
	  return $this->find('all', array(
	  				'conditions' => array('evaluation_mixeval_id' => $mixedEvalId, 'grade >' => 0),
	  				'order' => array('question_number' => 'ASC')
	  		));
	} 						
						
	function getByEvalMixevalIdCritera($MixevalId=null, $questionNum=null){
//	  $sql = 'evaluation_mixeval_id='.$MixevalId;
//	  if ($questionNum != null) {
//	    $sql .= ' AND question_number='.$questionNum;
//	  }
//		return $this->find($sql);
            $conditions = array('evaluation_mixeval_id' => $MixevalId);
            if($questionNum != null)
                $conditions['question_number'] = $questionNum;
            
            $type=null;
            if(null == $questionNum) $type = 'all';
            	else $type = 'first';
            return $this->find($type, array(
                'conditions' => $conditions, 'order'=>'question_number'
            ));  
	}
	
}
?>