<?php
/**
 * ExportPdfComponent
 *
 * @uses ExportBaseNewComponent
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
Class ExportPdfComponent extends ExportBaseNewComponent
{
      
     /**
     * createPdf
     *
     * @param mixed $params params
     * @param mixed $event  event
     *
     * @access public
     * @return void
     */
     function createPdf($params, $event)
     {
         switch($event['Event']['event_template_type_id']){
             case 1:
                 $this->_createSimpleResultsPdf($params, $event);
                 break;
             case 2:
                 $this->_createRubricResultsPdf($params, $event);
                 break;
            // case 3: We do not need export for Surveys
            //     $this->_createSurveyResultsPdf($params,$event);
            //     break;
             case 4:
                 $this->_createMixResultsPdf($params, $event);
                 break;
         }
     }  
     
     /**
      *_createMixResultsPdf
      * 
      * @param mixed $params
      * @param mixed $event 
      * 
      * @return void
      */
      function _createMixResultsPdf($params,$event){
          App::import('Vendor', 'xtcpdf');
          $mpdf = new XTCPDF();
        
          //Construct the Filename and extension
          $fileName = isset($params['file_name']) && !empty($params['file_name']) ? $params['file_name']:date('m.d.y');
          $fileName = $fileName . '.pdf';
          $mpdf -> AddPage();
          
          //Write header text
          $headertext = '<h2>Evaluation Event Detail for '. $event['Course']['course'].' - '.$event['Event']['title'].'</h2>';
          $mpdf->writeHTML($headertext, true, false, true, false, '');
         
          $this->Group = ClassRegistry::init('Group');
          $page_count = 0;
          foreach($event['GroupEvent'] as $groupevent){
              //Get the groupevent id and the group id for each group in the evaluation
              $grp_event_id = $groupevent['id'];
              $grp_id = $groupevent['group_id'];
            
              //Call writeEvalDetails
              $evalDetails = $this->_writeEvalDetails($event, $grp_id, $params);      
              $mpdf->writeHTML($evalDetails, true, false, true, false, '');
            
              //Write Summary 
              $mpdf->writeHTML('<br>', true, false, true, false, '');
              $mpdf->writeHTML('<h3>Summary</h3>', true, false, true, false, '');
            
              //Get Membersid's and Membernames who have not submitted their evaluations
              $inComplete = $this->_getIncompleteMembers($event['Event']['id'], $grp_id);
              $inComplete = $this->_getMemberNames($inComplete);
              $mpdf->writeHTML('<p><b>Members who have not submitted their evaluations</b></p>', true, false, true, false, '');
              foreach ($inComplete as $name){
                  $mpdf->writeHTML($name, true, false, true, false, '');
              }
              $mpdf->writeHTML('<br>', true, false, true, false, '');
            
              //Get if self evaluation is 'yes' or 'no'
              $event['Event']['self_eval'] == '0'? $selfeval = 'No' : $selfeval = 'Yes';
          
              //Write the Mixeval Scores Table And The Evaluation Results
              if($params['include']['grade_tables'] == '1'){
                  $rtbl = $this->_writeMixResultsTbl($event, $grp_event_id, $grp_id); 
                  $mpdf->writeHTML($rtbl, true, false, true, false, '');
              }            
           
              $mpdf->writeHTML('<h3>Evaluation Results</h3>', true, false, true, false, '');    
              $mEvalResults = $this->_writeMixEvalResults($event, $grp_event_id, $grp_id, $params);  
              $mpdf->writeHTML($mEvalResults, true, false, true, false, '');                   

              $mpdf->lastPage();
              $mpdf->addPage();
              $page_count++;
          }
          $mpdf->deletePage($mpdf->getNumPages());
          
          if(ob_get_contents()){
              ob_clean();
          }
          return $mpdf -> Output($fileName, 'I');     
      }

    /**
     * _writeMixEvalResults
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * @param mixed $params
     * 
     * @return HTML Table containing the results
     */
     function _writeMixEvalResults($event,$grp_event_id,$grp_id,$params){
         //debug($params);
         $evaluators = $this->_getMembers($grp_event_id);
         $evaluatees = $this->_filterTutors($grp_id);
         $evaluatee_names = $this->_getMemberNames($evaluatees); 
         $evaluator_names = $this->_getMemberNames($evaluators);
         $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
         $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
         
         $mEvalResults = '<p>';
         $mixEvalId = $event['Event']['template_id'];
         $mixevalQuestions = $this->MixevalQuestion->getQuestion($mixEvalId, null);         
         //debug($mixevalQuestions);
         
         //Get the final group average
         $totalGroupAverage = 0;
         $count = 0;
         for($i=0; $i<sizeof($evaluatees); $i++){
             $receivedAverageScore = $this->EvaluationMixeval->getReceivedAvgScore($grp_event_id, $evaluatees[$i]);
             $receivedAverageScore = $receivedAverageScore[0][0]['received_avg_score'];             
             if(!isset($receivedAverageScore)){
                 continue;
             }
             $count++;    
             $totalGroupAverage = ($totalGroupAverage + $receivedAverageScore);
         }
        
         $totalGroupAverage = $count > 0 ? $totalGroupAverage/$count : null;
         
         for($i=0; $i<sizeof($evaluatees); $i++){
             $aver = $this->EvaluationMixeval->getReceivedAvgScore($grp_event_id, $evaluatees[$i]);
             $receivedAverageScore = $this->EvaluationMixeval->getReceivedAvgScore($grp_event_id, $evaluatees[$i]);
             $receivedAverageScore = $receivedAverageScore[0][0]['received_avg_score'];
               
             //Get the average score for each evaluatee if the evaluatee has one
             //And also indicate if it is above, below or equal to the average
             $scoreComment = '';
             if(!isset($receivedAverageScore)){
                 $receivedAverageScore = 'N/A';
             }
             else{
                $receivedAverageScore = number_format($receivedAverageScore, 2);
                if($receivedAverageScore > $totalGroupAverage) {
                    $scoreComment = ' -- Above Average --';
                } else if($receivedAverageScore < $totalGroupAverage) {
                    $scoreComment = ' -- Below Average --';
                } else {
                    $scoreComment = ' -- Average --';
                }
             }
             $mEvalResults = $mEvalResults.'<br><u><b>Evaluatee: </b>'.$evaluatee_names[$i].'</u><br>';
             $break = '<br>';
             if($params['include']['final_marks'] == '1'){
                 $mEvalResults = $mEvalResults.'Final Total: '.$receivedAverageScore.$scoreComment.$break;   
             }
 
             
             //Write down the Questions and the responses given by each evaluator
             foreach($mixevalQuestions as $question){
                 //For each question write down the question_num and the title
                 $question_num = $question['MixevalQuestion']['question_num'];
                 $title = $question['MixevalQuestion']['title'];
                                
                 //For each question, write down the response of the evaluator in a single bullet point
                // debug($this->EvaluationMixeval->getResultsDetailByEvaluatee($grp_event_id, $evaluatees[$i], false));
                $question_text = $question_num.'.'.$title;
                $questionWrittenBool = 0;
                   
                for($j=0; $j<sizeof($evaluators); $j++){
                    $result = $this->EvaluationMixeval->getResultDetailByQuestion($grp_event_id, $evaluatees[$i], $evaluators[$j], $question_num);
                    if(empty($result)){
                        continue;
                    }
                    else{
                        if($questionWrittenBool==0){ //Checks if the question has already been written
                            $mEvalResults = $mEvalResults.$question_text.'<br>'; 
                            $questionWrittenBool = 1; //The question has now been written so set Boolean to 1
                        }
                        $grade = $comment = ' --';
                        $mEvalResults = $mEvalResults.'<b>&#160;&#160;&#160;&#160;&#160;&#160;'.$evaluator_names[$j].' : </b>';
                        if($question['MixevalQuestionType']['id'] != '1' && $question['MixevalQuestionType']['id'] != '4'){
                            if($params['include']['comments'] == '1'){
                                $comment = 'Comment : '.$result['EvaluationMixevalDetail']['question_comment'];
                            }
                            $mEvalResults = $mEvalResults.$comment;
                        }
                        else{
                            if($params['include']['grade_tables'] == '1'){
                                $grade = 'Grade : '.$result['EvaluationMixevalDetail']['grade'];
                            }
                            $mEvalResults = $mEvalResults. $grade;
                        }    
                        $mEvalResults = $mEvalResults.'<br>';      
                    }
                }                     
             }
         }
         $mEvalResults = $mEvalResults.'</p>';
         return $mEvalResults;
     }
 
    /**
     * _writeMixResultsTbl
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * 
     * @return HTML Table containing the results
     */
     function _writeMixResultsTbl($event,$grp_event_id,$grp_id){
         $evaluators = $this->_getMembers($grp_event_id);
         $evaluatees = $this->_filterTutors($grp_id);
         $evaluatee_names = $this->_getMemberNames($evaluatees); 
         $evaluator_names = $this->_getMemberNames($evaluators);
         $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
         $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
         
         $mixEvalId = $event['Event']['template_id'];
         $mixevalQuestions = $this->MixevalQuestion->getQuestion($mixEvalId, null);
         //debug($mixevalQuestions);
         
         //Write the Table Header
         $mRTBL = '<table border="1" align="center"><tr><th><b>Evaluatee</b></th>';
         $total = 0;
         $lickertQuestions = array();
         $lickertQuestionsCount=0;
         $scoreDropdownQuestions = array();
         $scoreDropdownCount = 0;
         $finalCumulativeTotal=0;
         foreach($mixevalQuestions as $question){
             //Check if the Question has a multiplier (it should be a likert MixEvalQuestionType.id=1)
             if($question['MixevalQuestionType']['id']!=1 && $question['MixevalQuestionType']['id']!=4){
                 continue;
             }
             $question_num = $question['MixevalQuestion']['question_num'];
             $multiplier = $question['MixevalQuestion']['multiplier'];
             if($question['MixevalQuestionType']['id']==1){
                 $lickertQuestions[$question_num]=0;
             } 
             else{
                 $scoreDropdownQuestions[$question_num]=0;
             }
             $mRTBL = $mRTBL.'<th>'.$question_num.' (/'.number_format($multiplier, 2).')</th>';
             $total = $total + $multiplier;                         
         }
         $mRTBL = $mRTBL.'<th>Total (/'.number_format($total, 2).')</th>';
         $mRTBL = $mRTBL.'</tr>';
         //Table Header End
         
          //Since a question cannot be BOTH lickert and scoreDropdown at the same time, an array_merge on the 2 arrays should work fine.
          $combinedLickertScoreDD = $lickertQuestions + $scoreDropdownQuestions;
         for($i=0; $i<sizeof($evaluatees); $i++){
             $total_grade=0;
             $mRTBL = $mRTBL.'<tr><td>'.$evaluatee_names[$i].'</td>';
             $evalMixevalDetail = $this->EvaluationMixeval->getResultsDetailByEvaluatee($grp_event_id, $evaluatees[$i], false);  
             //debug($evalMixevalDetail);
             if(empty($evalMixevalDetail)){
                 if(count($combinedLickertScoreDD) <= 0){
                     $mRTBL = $mRTBL. '<td> N/A </td>'; //If there are no lickert or score dropdown questions, the total is N/A
                 }
                 else if(count($lickertQuestions) <= 0 && count($scoreDropdownQuestions) > 0){  //If there are no lickert questions and the count of score dropdown is greater than 0 that means all the questions are of the type score dropdown  
                    for($j=0; $j<sizeof($scoreDropdownQuestions); $j++){
                        $mRTBL = $mRTBL.'<td> N/A </td>';
                    }
                    $mRTBL=$mRTBL.'<td> N/A </td>'; //Additional N/A for total column
                 }
                 else if(count($scoreDropdownQuestions) <= 0 && count($lickertQuestions) > 0){ //Opposite of the statement/condition above
                     for($j=0; $j<sizeof($lickertQuestions); $j++){ 
                        $mRTBL = $mRTBL.'<td> N/A </td>';
                     }
                     $mRTBL=$mRTBL.'<td> N/A </td>'; //Additional N/A for total column
                 } else {
                     for($j=0; $j< count($combinedLickertScoreDD); $j++){ //If lickertQuestions and scoreDropdown questions are both greater than 0, that means the number of rows = #lickert + #scoreDropdown
                        $mRTBL=$mRTBL.'<td> N/A </td>';
                     }
                      $mRTBL=$mRTBL.'<td> N/A </td>'; //Additional N/A for total column
                 }    
                 $mRTBL=$mRTBL.'</tr>';
             }
             else{ //The evaluation detail does exist. Need to handle the same cases as above
                 //debug($evalMixevalDetail);
                 $evalMixevalDetail = $evalMixevalDetail['0']['EvaluationMixevalDetail'];
                 //Get the scores for the lickert or scoreDropdown or both type questions and put them in the table
                 
                 //If the count of BOTH lickert type and score dropdown type <= 0, the case is the same as the one handled above, which is as follows
                 if(count($combinedLickertScoreDD) <= 0){
                     $mRTBL = $mRTBL. '<td> N/A </td>'; //If there are no lickert or score dropdown questions, the total is N/A
                 }
                 
                 else if(count($scoreDropdownQuestions) <= 0 && count($lickertQuestions) > 0){ //If there are no score dropdown questions, and the count(lickertQuestions) > 0, that means all the questions are lickert
                     foreach($lickertQuestions as $questionNum => $value){
                         $grade = $evalMixevalDetail[$questionNum-1]['grade']; //$questionNum-1 is necessary since array numbering starts from 0 not 1
                         $lickertQuestions[$questionNum] = $lickertQuestions[$questionNum] + $grade;
                         $total_grade = $total_grade + $grade;  
                         $finalCumulativeTotal = $finalCumulativeTotal + $grade;                  
                         $mRTBL = $mRTBL.'<td>'.$grade.'</td>';
                    }
                 $lickertQuestionsCount = $lickertQuestionsCount + 1;
                 $mRTBL=$mRTBL.'<td>'.$total_grade.'</td>';
                 $mRTBL=$mRTBL.'</tr>';                    
                 }
                 
                 else if(count($scoreDropdownQuestions) > 0 && count($lickertQuestions) <= 0){ //Opposite of the above condition
                     foreach($scoreDropdownQuestions as $questionNum => $value){
                         $grade = $evalMixevalDetail[$questionNum-1]['grade']; //$questionNum-1 is necessary since array numbering starts from 0 not 1
                         $scoreDropdownQuestions[$questionNum] = $scoreDropdownQuestions[$questionNum] + $grade;
                         $total_grade = $total_grade + $grade;  
                         $finalCumulativeTotal = $finalCumulativeTotal + $grade;                  
                         $mRTBL = $mRTBL.'<td>'.$grade.'</td>';
                     }
                 $scoreDropdownCount= $scoreDropdownCount + 1;
                 $mRTBL=$mRTBL.'<td>'.$total_grade.'</td>';
                 $mRTBL=$mRTBL.'</tr>';                    
                 }
                 
                else{ //BOTH count(lickertQuestions) AND count(scoreDropdownQuestions) > 0, so we need to consider them both
                    foreach($combinedLickertScoreDD as $questionNum => $value){
                         $grade = $evalMixevalDetail[$questionNum-1]['grade']; //$questionNum-1 is necessary since array numbering starts from 0 not 1
                         $combinedLickertScoreDD[$questionNum] = $combinedLickertScoreDD[$questionNum] + $grade;
                         $total_grade = $total_grade + $grade;  
                         $finalCumulativeTotal = $finalCumulativeTotal + $grade;                  
                         $mRTBL = $mRTBL.'<td>'.$grade.'</td>';
                    }
                 $mRTBL=$mRTBL.'<td>'.$total_grade.'</td>';
                 $mRTBL=$mRTBL.'</tr>';                      
                }
             }
             
         }
    
         $mRTBL = $mRTBL.'<tr><td><b>Group Average</b></td>';
         $evaluateeCount = number_format(count($evaluatees), 2);
         //Write the Group Average
         if($lickertQuestionsCount > 0 && $scoreDropdownCount <= 0){
             foreach($lickertQuestions as $question){
                $mRTBL = $mRTBL.'<td>'.$question/$evaluateeCount.'</td>';
             }
            $mRTBL = $mRTBL.'<td>'.$finalCumulativeTotal/$evaluateeCount.'</td>';
         }
            
         else if($scoreDropdownCount > 0 && $lickertQuestionsCount <= 0){
             foreach($scoreDropdownQuestions as $question){
                $mRTBL = $mRTBL.'<td>'.$question/$evaluateeCount.'</td>';
             }
            $mRTBL = $mRTBL.'<td>'.$finalCumulativeTotal/$evaluateeCount.'</td>';
         }
         
         else if($combinedLickertScoreDD <= 0){
             $mRTBL = $mRTBL.'<td> N/A </td>';
         }
         else { //BOTH are > 0
            foreach($combinedLickertScoreDD as $question){
                 $mRTBL = $mRTBL.'<td>'.number_format($question/$evaluateeCount, 2).'</td>';
            }
             $mRTBL = $mRTBL.'<td>'.number_format($finalCumulativeTotal/$evaluateeCount, 2).'</td>';
         }
         $mRTBL = $mRTBL.'</tr>';              
         $mRTBL = $mRTBL.'</table>';
         //debug($mRTBL);
         return $mRTBL;
     }
      
     /**
      * _createRubricResultsPdf
      * 
      * @param mixed $params
      * @param mixed $event
      * 
      * @return void 
      */
      function _createRubricResultsPdf($params,$event){
          App::import('Vendor', 'xtcpdf');
          $spdf = new XTCPDF();
          //debug($params);
          //Construct the Filename and extension
          $fileName = isset($params['file_name']) && !empty($params['file_name']) ? $params['file_name']:date('m.d.y');
          $fileName = $fileName . '.pdf';
          $spdf -> AddPage();
          
          //Write header text
          $headertext = '<h2>Evaluation Event Detail for '. $event['Course']['course'].' - '.$event['Event']['title'].'</h2>';
          $spdf->writeHTML($headertext, true, false, true, false, '');
         
          $this->Group = ClassRegistry::init('Group');
          foreach($event['GroupEvent'] as $groupevent){
              //Get the groupevent id and the group id for each group in the evaluation
              $grp_event_id = $groupevent['id'];
              $grp_id = $groupevent['group_id'];
            
              //Call writeEvalDetails
              $evalDetails = $this->_writeEvalDetails($event, $grp_id, $params);      
              $spdf->writeHTML($evalDetails, true, false, true, false, '');
            
              //Write Summary 
              $spdf->writeHTML('<br>', true, false, true, false, '');
              $spdf->writeHTML('<h3>Summary</h3>', true, false, true, false, '');
            
              //Get Membersid's and Membernames who have not submitted their evaluations
              $inComplete = $this->_getIncompleteMembers($event['Event']['id'], $grp_id);
              $inComplete = $this->_getMemberNames($inComplete);
              $spdf->writeHTML('<p><b>Members who have not submitted their evaluations</b></p>', true, false, true, false, '');
              foreach ($inComplete as $name){
                  $spdf->writeHTML($name, true, false, true, false, '');
              }
              $spdf->writeHTML('<br>', true, false, true, false, '');
            
              //Get if self evaluation is 'yes' or 'no'
              $event['Event']['self_eval'] == '0'? $selfeval = 'No' : $selfeval = 'Yes';
          
              //Write the Rubric Scores Table And The Evaluation Results
              if($params['include']['grade_tables'] == '1'){
                 $rtbl = $this->_writeRubricResultsTbl($event, $grp_event_id, $grp_id); 
                 $spdf->writeHTML($rtbl, true, false, true, false, '');
              } 
                        
              $spdf->writeHTML('<h3>Evaluation Results</h3>', true, false, true, false, '');
              $eResultsTbl = $this->_writeRubricEvalResults($event, $grp_event_id, $grp_id, $params); 
              $spdf->writeHTML($eResultsTbl, true, false, true, false, '');

              $spdf->lastPage();
              $spdf->addPage();
          }
          $spdf->deletePage($spdf->getNumPages());

          if(ob_get_contents()){
              ob_clean();
          }
          return $spdf -> Output($fileName, 'I');     
      }    

    /**
     * function _writeRubricResultsTbl
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     *
     * @access public
     * @return void
     */
     function _writeRubricResultsTbl($event,$grp_event_id,$grp_id){
         $this->EvaluationRubric= ClassRegistry::init('EvaluationRubric');
         $this->RubricsCriteria= ClassRegistry::init('RubricsCriteria');
         $this->EvaluationRubricDetail= ClassRegistry::init('EvaluationRubricDetail');
         $rubric = $this->EvaluationRubric->
         find('first', array('conditions' => array('EvaluationRubric.grp_event_id' => $grp_event_id, 'EvaluationRubric.event_id' => $event['Event']['id'])));
         $rubric_id = $rubric['EvaluationRubric']['rubric_id'];
         $rubric_criteria_array = $this->RubricsCriteria->getCriteria($rubric_id);
         
         //Write the Table Header
         $rSTBL = '<table border="1" align="center"><tr><th><b>Evaluatee</b></th>';
         $total = 0;
         foreach($rubric_criteria_array as $rubric_criteria){
             //Get the Rubric Criteria number and the multiplier and write it to the table header
             $criteria_num = $rubric_criteria['RubricsCriteria']['criteria_num'];
             $multiplier = $rubric_criteria['RubricsCriteria']['multiplier'];
             $total = $total + $multiplier;
             $rSTBL = $rSTBL.'<th>'.$criteria_num.' (/'.number_format($multiplier, 1).' )</th>';
         }
         $rSTBL = $rSTBL.'<th>Total (/'.number_format($total, 2).')</th>';
         $rSTBL = $rSTBL.'</tr>';
         
         $evaluators = $this->_getMembers($grp_event_id);
         $evaluatees = $this->_filterTutors($grp_id);
         $evaluatee_names = $this->_getMemberNames($evaluatees); 
         $evaluator_names = $this->_getMemberNames($evaluators);

         //Write Scores For Each Evaluatee
         $groupAvgArray = array();
         $evaluatorCount = 0;
         for($i=0; $i<sizeof($evaluatees); $i++){
             //Get the Score Given to the Evaluatee (By Criteria)
             $rubricScores = $this->EvaluationRubric->getCriteriaResults($grp_event_id, $evaluatees[$i]);
             if(!empty($rubricScores)){
                 $evaluatorCount++;
                 $rSTBL = $rSTBL.'<tr>';
                 //Write The Evaluatee Name
                 $rSTBL = $rSTBL.'<td>'.$evaluatee_names[$i].'</td>';
                 //Write The Scores
                 foreach($rubricScores as $criteria_id => $score){
                     (!isset($groupAvgArray[$criteria_id]))? $groupAvgArray[$criteria_id] = 0 : $groupAvgArray[$criteria_id];
                     $groupAvgArray[$criteria_id] = ($groupAvgArray[$criteria_id] + $score)/($evaluatorCount);
                     $rSTBL = $rSTBL.'<td>'.$score.'</td>';
                 }
                //Write The Total
                $totalScore = $this->EvaluationRubric->getReceivedTotalScore($grp_event_id, $evaluatees[$i]);
                $totalScore = $totalScore['0']['0']['received_total_score'];
                (!isset($groupAvgArray['total_score']))? $groupAvgArray['total_score'] = 0 : $groupAvgArray['total_score'];
                $groupAvgArray['total_score'] = ($groupAvgArray['total_score'] + $totalScore)/($evaluatorCount);
                $rSTBL = $rSTBL.'<td>'.$totalScore.'('.number_format(($totalScore * 100/$total), 2).'%)</td>';
                $rSTBL = $rSTBL.'</tr>';
             } else {
                continue;
             }   
         }
       
       //Write Down The Group Average  
        $rSTBL = $rSTBL.'<tr><td><b>Group Average</b></td>';
        foreach ($groupAvgArray as $criteria => $grpAvg) {
            if($criteria == 'total_score') {
                break;
            }
            $rSTBL = $rSTBL.'<td>'.$grpAvg.'</td>';
        }
        $rSTBL = $rSTBL.'<td>'.$groupAvgArray['total_score'].'</td>';
        $rSTBL = $rSTBL.'</tr></table>';
        return $rSTBL;
     }        
    
    /**
     * function _writeRubricEvalResults
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * @param mixed $params
     *
     * @access private
     * @return void
     */        
    function _writeRubricEvalResults($event,$grp_event_id,$grp_id,$params){
        //debug($params);
        $this->EvaluationRubric= ClassRegistry::init('EvaluationRubric');
        $this->RubricsCriteria= ClassRegistry::init('RubricsCriteria');
        $this->EvaluationRubricDetail= ClassRegistry::init('EvaluationRubricDetail');
        $rubric = $this->EvaluationRubric->find('first', array('conditions' => array('EvaluationRubric.grp_event_id' => $grp_event_id, 'EvaluationRubric.event_id' => $event['Event']['id'])));
        $rubric_id = $rubric['EvaluationRubric']['rubric_id'];
        $rubric_criteria_array = $this->RubricsCriteria->getCriteria($rubric_id);
        $evaluators = $this->_getMembers($grp_event_id);
        $evaluatees = $this->_filterTutors($grp_id);
        $evaluatee_names = $this->_getMemberNames($evaluatees); 
        $evaluator_names = $this->_getMemberNames($evaluators);
        
        //Get the Total
         $total = 0;
         foreach($rubric_criteria_array as $rubric_criteria){
             //Get the Rubric Criteria number and the multiplier and write it to the table header
             $multiplier = $rubric_criteria['RubricsCriteria']['multiplier'];
             $total = $total + $multiplier;
         }
        
         //Scores For Each Evaluatee
         $groupAvgArray = array();
         $evaluatorCount = 0;
         for($i=0; $i<sizeof($evaluatees); $i++){
             //Get the Score Given to the Evaluatee (By Criteria)
             $rubricScores = $this->EvaluationRubric->getCriteriaResults($grp_event_id, $evaluatees[$i]);
             if(!empty($rubricScores)){
                 $evaluatorCount++;
                 //Write The Scores
                 foreach($rubricScores as $criteria_id => $score){
                     (!isset($groupAvgArray[$criteria_id]))? $groupAvgArray[$criteria_id] = 0 : $groupAvgArray[$criteria_id];
                     $groupAvgArray[$criteria_id] = ($groupAvgArray[$criteria_id] + $score)/($evaluatorCount);
                 }
                //Write The Total
                $totalScore = $this->EvaluationRubric->getReceivedTotalScore($grp_event_id, $evaluatees[$i]);
                $totalScore = $totalScore['0']['0']['received_total_score'];
                (!isset($groupAvgArray['total_score']))? $groupAvgArray['total_score'] = 0 : $groupAvgArray['total_score'];
                $groupAvgArray['total_score'] = ($groupAvgArray['total_score'] + $totalScore)/($evaluatorCount);
             } else {
                continue;
             }   
         }
        
        $rSTBL = '';
        
        for($j=0; $j<sizeof($evaluatees); $j++){
            //Get Results By Evaluatee and Check if the array is empty
            $resultsArrayByEvaluatee = $this->EvaluationRubric->getResultsByEvaluatee($grp_event_id, $evaluatees[$j], false);
            if(empty($resultsArrayByEvaluatee)) {
               continue;
            }
            else {
                $num_evaluators = $this->EvaluationRubric->getReceivedTotalEvaluatorCount($grp_event_id, $evaluatees[$j]);
                $totalScore = $this->EvaluationRubric->getReceivedTotalScore($grp_event_id, $evaluatees[$j]);
                $totalScore = $totalScore['0']['0']['received_total_score'];
                
                if($totalScore==$groupAvgArray['total_score']) {
                    $totalComment = '= Group Average';
                } else if($totalScore > $groupAvgArray['total_score']) {
                    $totalComment = 'Above Group Average';
                } else {
                    $totalComment = 'Below Group Average';
                }
                $rSTBL = $rSTBL.'<p><b>Evaluatee : '.$evaluatee_names[$j].'</b><br>';
                $rSTBL = $rSTBL.'Number of Evaluators : '.$num_evaluators.'<br>';
                if($params['include']['final_marks'] == '1'){
                     $rSTBL = $rSTBL. 'Final Total : '.$totalScore.' ('.number_format(($totalScore * 100/$total), 2).'%)<br>';
                }
                $rSTBL = $rSTBL. 'Comment : '.$totalComment.'</p>';
                
                //Write the Table Header
                $rSTBL = $rSTBL. '<table border="1" align="center"><tr><th><b>Evaluator</b></th>';
                $criteria_count = 0;
                $criteria_comments = array();
                foreach($rubric_criteria_array as $rubric_criteria){
                    //Get the Rubric Criteria number and the multiplier and write it to the table header
                    $criteria_num = $rubric_criteria['RubricsCriteria']['criteria_num'];
                    $criteria_comments[$criteria_num] = '--';
                    $criteria_desc = $rubric_criteria['RubricsCriteria']['criteria'];
                    $criteria_count = $criteria_count++;
                    $rSTBL = $rSTBL.'<th><b>('.$criteria_num.') '.$criteria_desc.'</b></th>';
                }
                $rSTBL = $rSTBL.'</tr>';
                
                  foreach ($resultsArrayByEvaluatee as $result) {
                    $eval_rubric_id = $result['EvaluationRubric']['id'];
                    $rSTBL = $rSTBL.'<tr><td>'.$result['EvaluationRubric']['updater'].'</td>';
                    foreach($criteria_comments as $criteria_id => $criteria_comment){
                        $EvalRubricDtl = $this->EvaluationRubricDetail->getByEvalRubricIdCritera($eval_rubric_id, $criteria_id);
                        //debug($EvalRubricDtl);
                        $grade = $comment = '--';
                        if($params['include']['comments'] == 1) {
                            $comment = $EvalRubricDtl['EvaluationRubricDetail']['criteria_comment'];
                        }
                        if($params['include']['grade_tables'] == 1){
                            $grade = $EvalRubricDtl['EvaluationRubricDetail']['grade'];
                        }
                        $rSTBL = $rSTBL.'<td>'.$comment.'<br><b>Grade given : </b>'.$grade.'</td>';
                    }
                    
                    $rSTBL = $rSTBL.'</tr>';
                    //debug($resultsArrayByEvaluatee);
                    if($params['include']['comments'] == '1') {
                        $rSTBL = $rSTBL.'<tr><td colspan="'.$criteria_count.'"><b>General Comment :</b> '.$resultsArrayByEvaluatee['0']['EvaluationRubric']['comment'].'</td></tr>';
                    }            
                  }
            }
            $rSTBL = $rSTBL.'</table>';
        } 
        return $rSTBL;       
    }      

     
     /**
      * _createSimpleResultsPdf
      * 
      * @param mixed $params params
      * @param event $event event
      * 
      * @access public
      * @return void
      */
    function _createSimpleResultsPdf($params, $event) {
        App::import('Vendor', 'xtcpdf');
        $spdf = new XTCPDF();
        
        //Construct the Filename and extension
        $fileName = isset($params['file_name']) && !empty($params['file_name']) ? $params['file_name']:date('m.d.y');
        $fileName = $fileName . '.pdf';
        $spdf -> AddPage();
       
        $coursename ='';
        $eventname ='';
        //Write header text
       // debug($params);
        if($params['include']['course'] == '1'){
            $coursename = ' : '.$event['Course']['course'];
        }
        if($params['include']['eval_event_names'] == '1'){
            $eventname = ' - '.$event['Event']['title'];
        }
        $headertext = '<h2>Evaluation Event Detail'.$coursename.$eventname.'</h2>';
        $spdf->writeHTML($headertext, true, false, true, false, '');
 
        $this->Group = ClassRegistry::init('Group');
        $page_count = 0;
        foreach($event['GroupEvent'] as $groupevent){
            //Get the groupevent id and the group id for each group in the evaluation
            $grp_event_id = $groupevent['id'];
            $grp_id = $groupevent['group_id'];
            
            //Call writeEvalDetails
            $evalDetails = $this->_writeEvalDetails($event, $grp_id, $params);      
            $spdf->writeHTML($evalDetails, true, false, true, false, '');
            
            //Write Summary 
            $spdf->writeHTML('<br>', true, false, true, false, '');
            $spdf->writeHTML('<h3>Summary</h3>', true, false, true, false, '');
            
            //Get Membersid's and Membernames who have not submitted their evaluations
            $inComplete = $this->_getIncompleteMembers($event['Event']['id'], $grp_id);
            $inComplete = $this->_getMemberNames($inComplete);
            $spdf->writeHTML('<p><b>Members who have not submitted their evaluations</b></p>', true, false, true, false, '');
            foreach ($inComplete as $name){
                $spdf->writeHTML($name, true, false, true, false, '');
            }
            $spdf->writeHTML('<br>', true, false, true, false, '');
            
           //Get if self evaluation is 'yes' or 'no'
           $event['Event']['self_eval'] == '0'? $selfeval = 'No' : $selfeval = 'Yes';
           
           //Write the scores table
           if($params['include']['grade_tables'] == '1'){
               $spdf->writeHTML('<h3>Evaluation Results</h3>', true, false, true, false, '');
               $stbl = $this->_writeScoresTbl($event, $grp_event_id, $grp_id, $params); 
               $spdf->writeHTML($stbl, true, false, true, false, '');
           }
                     
           //Write the comments if they are there and if the params ask for them
           if($params['include']['comments'] == '1'){
               $comments_text = '<h3>Comments</h3>';
               $spdf->writeHTML($comments_text, true, false, true, false, '');
               $comments = $this->_writeComments($event, $grp_event_id);
               $spdf->writeHTML($comments, true, false, true, false, '');
           }      

         $spdf->lastPage();
         $spdf->addPage();
         $page_count++;
        }
        $spdf->deletePage($spdf->getNumPages());

        if(ob_get_contents()){
           ob_clean();
        }
        return $spdf -> Output($fileName, 'I');

    }
    
    /**
     * _writeComments 
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * 
     * @return HTML string consisting of comments
     */
    function _writeComments($event,$grp_event_id){
       $evaluators = $this->_getMembers($event['Event']['id'], $grp_event_id);
       $evaluatees = $this->_filterTutors($grp_event_id);
       $evaluatee_names = $this->_getMemberNames($evaluatees); 
       $evaluator_names = $this->_getMemberNames($evaluators);
       
       $comments_html = '';
       //Write the comments if they exist
       for($i=0; $i<sizeof($evaluators); $i++){       
            for($j=0; $j<sizeof($evaluatees); $j++){
                $commentgiven = $this->_getScoreGiven($evaluators[$i], $evaluatees[$j], $grp_event_id);
                if($commentgiven[1]=='-'){
                    continue;
                }
                else{
                    $comments_html = $comments_html.'<b>'.$evaluator_names[$i].'</b><br>'; //Write Evaluator Name
                    $comments_html=$comments_html.'Evaluatee : '.$evaluatee_names[$j].'<br>'.
                                   'Comment : '.$commentgiven[1].'<br><br>';
                }
            }
       }
       return $comments_html;
    }
    
    /**
     * _writeScoresTbl
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * @param mixed $params 
     * 
     * @return HTML string for ScoresTable
     */
    function _writeScoresTbl($event,$grp_event_id,$grp_id,$params){
       $evaluators = $this->_getMembers($grp_event_id);
       $evaluatees = $this->_filterTutors($grp_id);
       $evaluatee_names = $this->_getMemberNames($evaluatees); 
       $evaluator_names = $this->_getMemberNames($evaluators);
       
       $colspan = sizeof($evaluatees); 
       
       $tbl = '<table border="1" align="center">
                <tr>
                    <th rowspan="2"><b>Evaluator</b></th>
                    <th colspan="'.$colspan.'"><b>Members Evaluated</b></th>
                </tr>';
        $tbl = $tbl.'<tr>';
        //Write the members that have been evaluated
        for($i=0; $i<sizeof($evaluatee_names); $i++){
            $tbl = $tbl.'<th>'.$evaluatee_names[$i].'</th>';
        }
        $tbl = $tbl.'</tr>';
        
        $evaluator_count = array();
        
        //Write Scores
        for($i=0; $i<sizeof($evaluators); $i++){
            $tbl = $tbl.'<tr><td>'.$evaluator_names[$i].'</td>'; //Write Evalator Name
            for($j=0; $j<sizeof($evaluatees); $j++){
                $scoregiven = $this->_getScoreGiven($evaluators[$i], $evaluatees[$j], $grp_event_id);
                $tbl=$tbl.'<td>'.$scoregiven[0].'</td>';
            }
            $tbl = $tbl.'</tr>';
        }
        
        $total = null;
        $totals_array = array();
        $tbl = $tbl . '<tr><td><b>Totals</b></td>';
        
        //Write Totals
        for($i=0; $i<sizeof($evaluatees); $i++){
            $evaluator_count[$evaluatees[$i]] = 0;
            for($j=0; $j<sizeof($evaluators); $j++){
                $scoregiven = $this->_getScoreGiven($evaluators[$j], $evaluatees[$i], $grp_event_id);
                $scoregiven[0] == '-'? $total : $total=$total+$scoregiven[0];
                $scoregiven[0]=='-'? $evaluator_count[$evaluatees[$i]] : $evaluator_count[$evaluatees[$i]] = $evaluator_count[$evaluatees[$i]]+1;
            }
            isset($total)? $total=$total : $total='-';
            $totals_array[$evaluatees[$i]] = $total;
            $tbl = $tbl.'<td>'.$total.'</td>';
            $total = null;
        }
        $tbl = $tbl.'</tr>';
        
        //Write Penalties
        $penalty_array = $this->_getPenaltyArray($event, $evaluatees);
         $tbl = $tbl . '<tr><td>Penalty</td>';
         for($i=0; $i<sizeof($evaluatees); $i++){
             $tbl = $tbl.'<td>'.$penalty_array[$evaluatees[$i]].'</td>';
         }
        $tbl = $tbl.'</tr>';      
        
        //Write Final Mark if the params ask for it
        if($params['include']['final_marks'] == '1'){
            $tbl = $tbl . '<tr><td><b>Final Mark</b></td>';
            for($i=0; $i<sizeof($evaluatees); $i++){
                if($penalty_array[$evaluatees[$i]] == '-'){
                    $tbl = $tbl.'<td>'.$totals_array[$evaluatees[$i]].'</td>';
                } else {
                    $final_mark = (100 - $penalty_array[$evaluatees[$i]]) * $totals_array[$evaluatees[$i]];
                    $tbl = $tbl.'<td>'.$final_mark.'</td>';
                }     
            }
         $tbl = $tbl.'</tr>';  
        }      
        
        //Write Evaluator Count
        $tbl = $tbl . '<tr><td># Evaluators</td>';
         for($i=0; $i<sizeof($evaluatees); $i++){
             if($evaluator_count[$evaluatees[$i]] == 0){
                 $tbl = $tbl.'<td> - </td>';
             }
             else{
                 $tbl = $tbl.'<td>'.$evaluator_count[$evaluatees[$i]].'</td>';
             }     
         }
        $tbl = $tbl.'</tr>';  
        
        //Write Average Received
        $tbl = $tbl . '<tr><td><b>Average Received</b></td>';
         for($i=0; $i<sizeof($evaluatees); $i++){
             if($evaluator_count[$evaluatees[$i]] == 0){
                 $tbl = $tbl.'<td>'.$totals_array[$evaluatees[$i]].'</td>';
             }
             else{
                 $tbl = $tbl.'<td>'.($totals_array[$evaluatees[$i]]/$evaluator_count[$evaluatees[$i]]).'</td>';
             }     
         }
        $tbl = $tbl.'</tr>';  
        return ($tbl.'</table>');
    }
    
    /**
     * _getPenaltyArray
     * 
     * @param mixed $event
     * @param mixed $evaluatees 
     * 
     * @return array() - an associative array consisting of the penalties for the evaluatees or '-' if no penalty
     */
    function _getPenaltyArray($event,$evaluatees){
        $this->Penalty= ClassRegistry::init('Penalty');
        $this->EvaluationSubmission= ClassRegistry::init('EvaluationSubmission');
        $penalty_array = array();
        for($i=0; $i<sizeof($evaluatees); $i++){
            $evalsub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($event['Event']['id'], $evaluatees[$i]);
            $days_late = $this->EvaluationSubmission->daysLate($event['Event']['id'], $evalsub['EvaluationSubmission']['date_submitted']);
            $penalty = $this->Penalty->getPenaltyByEventAndDaysLate($event['Event']['id'], $days_late);
            isset($penalty)? $percent_penalty = $penalty['Penalty']['percent_penalty'] : $percent_penalty = '-';
            $penalty_array[$evaluatees[$i]] = $percent_penalty;
        }
        
        return $penalty_array;
    }
    
    /**
     * _filterTutors
     * 
     * @param mixed $grp_id
     * 
     * @return array() consisting of groupmembers no tutors
     */
    function _filterTutors($grp_id){
        $this->User = ClassRegistry::init('User');
        $grpMembersNoTutors = $this->User->getEventGroupMembersNoTutors($grp_id, true, null);
        $evaluatees = array();
        foreach($grpMembersNoTutors as $members){
            array_push($evaluatees, $members['User']['id']);
        }
        return $evaluatees;
    }
    
    /**
     * _writeEvalDetails
     * 
     * @param mixed $event
     * @param mixed $grp_id
     * @param mixed $params
     *
     * @access private
     * @return html string
     */
    function _writeEvalDetails($event,$grp_id,$params){
        //debug($params);
        $groupname = '-';
        $evaleventtype = '-';
        $eventTemplateType = '-';
        $this->Group = ClassRegistry::init('Group');
        $group = $this->Group->getGroupByGroupId($grp_id);
        //Write Group name
        if($params['include']['group_names'] == '1'){
            $groupname = $group['0']['Group']['group_name'];
        }
        $group = '<p>Group : '.$groupname.'<br>';
        //Write if self-eval is 'yes' or 'no'
        $selfeval = null;
        $event['Event']['self_eval'] == '0'? $selfeval = 'No' : $selfeval = 'Yes';
        $selfeval = 'Self-Evaluation : '.$selfeval.'<br>';
        //Write Event Name
        $eventname = $event['Event']['title'];
        $eventname = 'Event Name : '.$eventname.'<br>';
        if($params['include']['eval_event_type'] == '1'){
            $eventTemplateType = ucfirst(strtolower($event['EventTemplateType']['type_name']));
        }
        $eventTemplateType = 'Evaluation Type : '.$eventTemplateType.'<br>';
        //Write duedate and description
        $duedate = $event['Event']['due_date'];      
        $duedate = 'Due Date : '.date("D,F j, Y, g:i a", strtotime($duedate)).'<br>';
        $description = $event['Event']['description'];
        $description = 'Description : '.$description.'</p>';
        
        return ($group.$selfeval.$eventname.$eventTemplateType.$duedate.$description);             
    }
    
    /**
     * _getScoreGiven
     * 
     * @param mixed $evaluator
     * @param mixed $evaluatee
     * @param mixed $grpEventId
     * 
     * @return Integer - Score Given by evaluator to evaluatee or '-' if no score given 
     */
    function _getScoreGiven($evaluator,$evaluatee,$grpEventId){
         $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
         $results = $this->EvaluationSimple->getEvalMarkByGrpEventIdEvaluatorEvaluatee($grpEventId, $evaluator, $evaluatee);
         $scores = array();
         isset($results['EvaluationSimple']['score'])? $mark = $results['EvaluationSimple']['score'] : $mark= '-';
         array_push($scores, $mark);
         isset($results['EvaluationSimple']['comment'])? $comment = $results['EvaluationSimple']['comment'] : $comment= '-';
         array_push($scores, $comment);
         return $scores;
    }

    /**
     * _getIncompleteMembers 
     * 
     * @param EventId $eventId
     * @param GroupId $groupId
     * 
     * @return array() consisting of members who have not completed the evaluation
     */    
    function _getIncompleteMembers($eventId,$groupId){
        //Get the evaluation submissions for the given $eventId
        //Do an array_diff on members in $groupId
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->Group = ClassRegistry::init('Group');
        
        $members = $this->Group->getMembersByGroupId($groupId, 'all');
        $memberslist = array();
        foreach($members as $m){
            array_push($memberslist, $m['Member']['id']);
        }
        $submitter_list = array();
        $submissions = $this->EvaluationSubmission->getEvalSubmissionsByEventId($eventId);
      
        foreach($submissions as $s){
           array_push($submitter_list, $s['EvaluationSubmission']['submitter_id']);
        }
        $inComplete = array_values(array_diff($memberslist, $submitter_list));
        
        return $inComplete;
    }
    
    /**
     * _getMemberNames 
     * 
     * @param mixed $members
     * 
     * @return array() consisting of member's names 
     */
    function _getMemberNames($members){
        $nameslist = array();
         $this->User = ClassRegistry::init('User');
        for($i = 0; $i < sizeof($members); $i++){
            array_push($nameslist, $this->User->getFullNames($members[$i]));
        }
        
        $names = array();
        foreach($nameslist as $name){
            foreach($name as $n){
                array_push($names, $n);
            }
     
        }
        return $names; 
    }
    
    /**
     * _getMembers 
     * @param mixed $grp_event_Id
     * 
     * @return array() consisting of member's belonging to the group
     */
    function _getMembers($grp_event_Id){
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
  
        $members = $this->GroupEvent->getGroupMembers($grp_event_Id);
        $memberslist = array();
        foreach($members as $m){
            array_push($memberslist, $m['GroupsMembers']['user_id']);
        }
        return $memberslist;       
    }
    
}