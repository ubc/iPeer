<?php
class EvaluationMixevalDetail extends AppModel
{
  var $name = 'EvaluationMixevalDetail';
  
  var $belongsTo = array(
									 			'EvaluationMixeval' =>
									 				array(
									 					'className' => 'EvaluationMixeval',
									 					'foreignKey' => 'evaluation_mixeval_id'
									 				)
									 );
  
	function getByEvalMixevalIdCritera($MixevalId=null, $question=null){
//	  $sql = 'evaluation_mixeval_id='.$MixevalId;
//	  if ($question != null) {
//	    $sql .= ' AND question_number='.$question;
//	  }
//		return $this->find($sql);
            $conditions = array('evaluation_mixeval_id' => $MixevalId);
            if ($question != null)
                $conditions = array('question_number' => $question);
            return $this->find('first', array(
                'conditions' => $conditions
            ));
	}
	
	function getAllByEvalMixevalId($MixevalId=null, $question=null){
//	  $sql = 'evaluation_mixeval_id='.$MixevalId;
//	  if ($question != null) {
//	    $sql .= ' AND question_number='.$question;
//	  }
//		return $this->find('all',$sql);
          $conditions = array('evaluation_mixeval_id' => $MixevalId);
            if ($question != null)
                $conditions = array('question_number' => $question);
            return $this->find('all', array(
                'conditions' => $conditions
            ));
	}	
}

?>
