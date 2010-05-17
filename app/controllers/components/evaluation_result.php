<?php
/* SVN FILE: $Id: evaluation_result.php,v 1.2 2006/06/30 23:15:28 zoeshum Exp $ */
/*
 * To use your Model’s inside of your components, you can create a new instance like this:
 *  $this->foo = new Foo;
 *
 * @author      
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class EvaluationResultComponent extends Object
{ 

 	function formatSimpleEvaluationResultsMatrix($event, $groupMembers, $evalResult) {
		//
		// results matrix format:
		// Matrix[evaluatee_id][evaluator_id] = score
		//
		$matrix = array(); 

//$this->User = new User;
//print_r($this->User->findUserByStudentNo('36241032'));
	      	
	  foreach($evalResult AS $index => $value){
	    $evalMarkArray = $value;
	    $evalTotal = 0;
      if ($evalMarkArray!=null) {
        $grade_release = 1;
        //Get total score of each memeber
        //$receivedTotalScoreAry = isset($evalMarkArray[-1]['received_total_score'])? $evalMarkArray[-1]['received_total_score']: 0;
        //foreach($receivedTotalScoreAry AS $totalScore ){
          //$receivedTotalScore = $totalScore['received_total_score'];
        //}
  	    foreach($evalMarkArray AS $row ){
  	      $evalMark = isset($row['EvaluationSimple'])? $row['EvaluationSimple']: null;

          if ($evalMark!=null) {     
    				$grade_release = $evalMark['grade_release'];
    				//$ave_score= $receivedTotalScore / count($evalMarkArray);
        	  $matrix[$index][$evalMark['evaluatee']] = $evalMark['score'];
        	  //$matrix[$index]['received_ave_score'] =$ave_score;
						/*if ($index == $evalMark['evaluatee'] ) {
							$matrix[$index]['grade_released'] =$grade_release;
							$matrix[$index]['evaluatee'] =$evalMark['evaluatee'];
							
						}*/
      	  } else{
      	    $matrix[$index][$evalMark['evaluatee']] = 'n/a';
      	  }
    	  }
    	}	else {
				foreach ($groupMembers as $user) {
					$matrix[$index][$user['User']['id']] = 'n/a';
				}
			}
      //if(!$event['Event']['self_eval']) $matrix[$member->getId()][$member->getId()] = '--';			
	  }
		return $matrix;
	}
	

	
} 
?>