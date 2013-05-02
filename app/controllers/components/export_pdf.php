<?php
/**
 * ExportPdfComponent
 *
 * @uses ExportBaseNewComponent
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 * being refactored - rubrics, mixeval
 */
Class ExportPdfComponent extends ExportBaseNewComponent
{
      
    /**
     * createPdf // MT
     *
     * @param mixed $params params
     * @param mixed $event  event
     *
     * @access public
     * @return void
     */
    function createPdf($params, $event)
    {
        switch($event['Event']['event_template_type_id']) {
            case 1:
                $this->_createSimpleResultsPdf($params, $event);
                break;
            case 2:
                $this->_createRubricResultsPdf($params, $event);
                break;
            /*case 3: We do not need export for Surveys
                $this->_createSurveyResultsPdf($params,$event);
                break;*/
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
              // MT
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
         $evaluators = $this->_getMembers($grp_event_id);
         $evaluatees = $this->_filterTutors($grp_id);
         $evaluatee_names = $this->_getMemberNames($evaluatees); 
         $evaluator_names = $this->_getMemberNames($evaluators);
         $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
         $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
         
         $mEvalResults = '<p>';
         $mixEvalId = $event['Event']['template_id'];
         $mixevalQuestions = $this->MixevalQuestion->getQuestion($mixEvalId, null);         
         
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
         return $mRTBL;
     }
      
     /**
      * _createRubricResultsPdf // MT
      * 
      * @param mixed $params
      * @param mixed $event
      * 
      * @return void 
      */
    function _createRubricResultsPdf($params,$event)
    {
        App::import('Vendor', 'xtcpdf');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->User = ClassRegistry::init('User');
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
        $spdf = new XTCPDF();
        //Construct the Filename and extension
        $fileName = isset($params['file_name']) && !empty($params['file_name']) ? $params['file_name'] : date('m.d.y');
        $fileName = $fileName . '.pdf';
        $spdf->AddPage();
          
        $courseName = '';
        $eventName = '';
        //Write header text
        if ($params['include']['course'] == '1') {
            $courseName = ' : '.$event['Course']['course'];
        }
        if ($params['include']['eval_event_names'] == '1') {
            $eventName = ' - '.$event['Event']['title'];
        }
        $headertext = '<h2>Evaluation Event Detail'.$courseName.$eventName.'</h2>';
        $spdf->writeHTML($headertext, true, false, true, false, '');
         
        foreach($event['GroupEvent'] as $groupEvent) {
            //Get the groupevent id and the group id for each group in the evaluation
            $grp_event_id = $groupEvent['id'];
            $grp_id = $groupEvent['group_id'];
            
            //Call writeEvalDetails
            $evalDetails = $this->_writeEvalDetails($event, $grp_id, $params);      
            $spdf->writeHTML($evalDetails, true, false, true, false, '');
            
            //Get incomplete/unenrolled members
            $submitted = $this->EvaluationRubric->findAllByGrpEventId($grp_event_id);
            $members = Set::extract('/GroupsMembers/user_id', $this->GroupsMembers->findAllByGroupId($grp_id));
            $evaluators = Set::extract('/EvaluationRubric/evaluator', $submitted);
            $evaluatees = Set::extract('/EvaluationRubric/evaluatee', $submitted);
            $inComplete = $this->User->getFullNames(array_diff($members, $evaluators));
            $unEnrolled = $this->User->getFullNames(array_diff(array_unique(array_merge($evaluators, $evaluatees)), $members));

            //Write Summary 
            $title = (!empty($inComplete) || !empty($unEnrolled)) ? __('Summary', true) : '';
            $spdf->writeHTML('<br><h3>'.$title.'</h3>', true, false, true, false, '');
            $title = (!empty($inComplete)) ? __('Members who have not submitted their evaluations', true) : '';
            $spdf->writeHTML('<p><b>'.$title.'</b></p>', true, false, true, false, '');
            foreach ($inComplete as $name){
                $spdf->writeHTML($name, true, false, true, false, '');
            }
            $title = (!empty($unEnrolled)) ? __('Left the group, but had submitted or were evaluated', true) : '';
            $spdf->writeHTML('<p><b>'.$title.'</b></p>', true, false, true, false, '');
            foreach ($unEnrolled as $name){
                $spdf->writeHTML($name, true, false, true, false, '');
            }
            $spdf->writeHTML('<br>', true, false, true, false, '');
          
            //Write the Rubric Scores Table And The Evaluation Results
            if($params['include']['grade_tables'] == '1'){
                $rtbl = $this->_writeRubricResultsTbl($event, $grp_event_id, $grp_id); 
                $spdf->writeHTML($rtbl, true, false, true, false, '');
            } 
                        
            /*$spdf->writeHTML('<h3>Evaluation Results</h3>', true, false, true, false, '');
            $eResultsTbl = $this->_writeRubricEvalResults($event, $grp_event_id, $grp_id, $params); 
            $spdf->writeHTML($eResultsTbl, true, false, true, false, '');*/

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
     * function _writeRubricResultsTbl // MT
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     *
     * @access public
     * @return void
     */
    function _writeRubricResultsTbl($event,$grp_event_id,$grp_id)
    {
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
        $this->RubricsCriteria = ClassRegistry::init('RubricsCriteria');
        $this->EvaluationRubricDetail = ClassRegistry::init('EvaluationRubricDetail');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->Rubric = ClassRegistry::init('Rubric');
        $this->User = ClassRegistry::init('User');
         //$rubric = $this->EvaluationRubric->
         //find('first', array('conditions' => array('EvaluationRubric.grp_event_id' => $grp_event_id, 'EvaluationRubric.event_id' => $event['Event']['id'])));
        $rubric_id = $event['Event']['template_id'];
        $rubric_criteria_array = $this->RubricsCriteria->getCriteria($rubric_id);
        //debug($rubric_criteria_array);
         
        //Write the Table Header
        $rSTBL = '<table border="1" align="center"><tr><th><b>Evaluatee</b></th>';
        $total = array_sum(Set::extract('/RubricsCriteria/multiplier', $rubric_criteria_array));
        //debug(Set::extract('/RubricsCriteria/multiplier/', $rubric_criteria_array));
        foreach($rubric_criteria_array as $rubric_criteria) {
            //Get the Rubric Criteria number and the multiplier and write it to the table header
            $criteria_num = $rubric_criteria['RubricsCriteria']['criteria_num'];
            $multiplier = $rubric_criteria['RubricsCriteria']['multiplier'];
            $rSTBL = $rSTBL.'<th>'.$criteria_num.' (/'.$multiplier.' )</th>';
        }
        $rSTBL .= '<th>Total (/'.$total.')</th></tr>';

        $groupMembers = $this->GroupsMembers->find('list',
            array('conditions' => array('group_id' => $grp_id),'fields' => array('user_id', 'user_id')));
        $eval = $this->EvaluationRubric->findAllByGrpEventId($grp_event_id);
        $evaluators = Set::extract('/EvaluationRubric/evaluator', $eval);
        $evaluatees = Set::extract('/EvaluationRubric/evaluatee', $eval);
        $dropped = array_diff(array_unique(array_merge($evaluators, $evaluatees)), $groupMembers);
        $evaluators = array_merge($groupMembers, $dropped);
        foreach ($evaluators as $userid) {
            $userinfo = $this->User->find('first',
                array(
                    'conditions' => array('User.id' => $userid),
                    'contain' => array('Role')
                )
            );
            $temp[$userid] = $userinfo;
        }
        $penalty = $this->Rubric->formatPenaltyArray($temp, $event['Event']['id'], $grp_id);
        $temp = Set::extract('/Role[id='.$this->User->USER_TYPE_TA.']/RolesUser/user_id', $temp);
        $evaluatees = array_diff($evaluators, $temp);
        $evaluatee_names = $this->User->getFullNames($evaluatees); 
        $evaluator_names = $this->User->getFullNames($evaluators);

        $scores = Set::combine($eval, '{n}.EvaluationRubric.evaluator', '{n}.EvaluationRubricDetail', '{n}.EvaluationRubric.evaluatee');        
        debug($scores);
        //debug($rubric_criteria_array);
        
        foreach ($scores as $userId => $evaluatee) {
            $grades[$userId] = array_combine(Set::extract('/RubricsCriteria/criteria_num', $rubric_criteria_array), array_fill(0, count($rubric_criteria_array), 0));
            foreach ($evaluatee as $key => $evaluator) {
                
            }
        }
        // rubric score summary table
         //Write Scores For Each Evaluatee
         /*$groupAvgArray = array();
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
        $rSTBL = $rSTBL.'<td>'.$groupAvgArray['total_score'].'</td>';*/
        //$rSTBL = $rSTBL.'</tr></table>';
        $rSTBL .= '</table>';
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
      * _createSimpleResultsPdf // MT
      * 
      * @param mixed $params params
      * @param event $event event
      * 
      * @access public
      * @return void
      */
    function _createSimpleResultsPdf($params, $event)
    {
        App::import('Vendor', 'xtcpdf');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->User = ClassRegistry::init('User');
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
        $spdf = new XTCPDF();
        
        //Construct the Filename and extension
        $fileName = isset($params['file_name']) && !empty($params['file_name']) ? $params['file_name'] : date('m.d.y');
        $fileName = $fileName . '.pdf';
        $spdf->AddPage();
       
        $courseName = '';
        $eventName = '';
        //Write header text
        if ($params['include']['course'] == '1') {
            $courseName = ' : '.$event['Course']['course'];
        }
        if ($params['include']['eval_event_names'] == '1') {
            $eventName = ' - '.$event['Event']['title'];
        }
        $headertext = '<h2>Evaluation Event Detail'.$courseName.$eventName.'</h2>';
        $spdf->writeHTML($headertext, true, false, true, false, '');
 
        $page_count = 0;
        foreach($event['GroupEvent'] as $groupEvent) {
            //Get the groupevent id and the group id for each group in the evaluation
            $grp_event_id = $groupEvent['id'];
            $grp_id = $groupEvent['group_id'];
            
            //Call writeEvalDetails
            $evalDetails = $this->_writeEvalDetails($event, $grp_id, $params);      
            $spdf->writeHTML($evalDetails, true, false, true, false, '');
            
            //Get incomplete/unenrolled members
            $submitted = $this->EvaluationSimple->findAllByGrpEventId($grp_event_id);
            $members = Set::extract('/GroupsMembers/user_id', $this->GroupsMembers->findAllByGroupId($grp_id));
            $evaluators = Set::extract('/EvaluationSimple/evaluator', $submitted);
            $evaluatees = Set::extract('/EvaluationSimple/evaluatee', $submitted);
            $inComplete = $this->User->getFullNames(array_diff($members, $evaluators));
            $unEnrolled = $this->User->getFullNames(array_diff(array_unique(array_merge($evaluators, $evaluatees)), $members));
            
            //Write Summary 
            $title = (!empty($inComplete) || !empty($unEnrolled)) ? __('Summary', true) : '';
            $spdf->writeHTML('<br><h3>'.$title.'</h3>', true, false, true, false, '');
            $title = (!empty($inComplete)) ? __('Members who have not submitted their evaluations', true) : '';
            $spdf->writeHTML('<p><b>'.$title.'</b></p>', true, false, true, false, '');
            foreach ($inComplete as $name){
                $spdf->writeHTML($name, true, false, true, false, '');
            }
            $title = (!empty($unEnrolled)) ? __('Left the group, but had submitted or were evaluated', true) : '';
            $spdf->writeHTML('<p><b>'.$title.'</b></p>', true, false, true, false, '');
            foreach ($unEnrolled as $name){
                $spdf->writeHTML($name, true, false, true, false, '');
            }
            $spdf->writeHTML('<br>', true, false, true, false, '');

            //Write the scores table
            if ($params['include']['grade_tables'] == '1') {
                $spdf->writeHTML('<h3>Evaluation Results</h3>', true, false, true, false, '');
                $stbl = $this->_writeScoresTbl($event, $grp_event_id, $grp_id);
                $spdf->writeHTML($stbl, true, false, true, false, '');
            }
         
            //Write the comments if they are there and if the params ask for them
            if ($params['include']['comments'] == '1') {
                $comments_text = '<h3>Comments</h3>';
                $spdf->writeHTML($comments_text, true, false, true, false, '');
                $comments = $this->_writeComments($grp_event_id);
                $spdf->writeHTML($comments, true, false, true, false, '');
            }      

            $spdf->lastPage();
            $spdf->addPage();
            $page_count++;
        }
        $spdf->deletePage($spdf->getNumPages());

        if (ob_get_contents()) {
            ob_clean();
        }
        return $spdf->Output($fileName, 'I');
    }
    
    /**
     * _writeComments // MT
     * 
     * @param mixed $grp_event_id
     * 
     * @return HTML string consisting of comments
     */
    function _writeComments($grp_event_id)
    {
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
        $this->User = ClassRegistry::init('User');
        $eval = $this->EvaluationSimple->findAllByGrpEventId($grp_event_id);
        $comments = Set::combine($eval, '{n}.EvaluationSimple.evaluatee', '{n}.EvaluationSimple.comment', '{n}.EvaluationSimple.evaluator');
        $users = array_merge(Set::extract('/EvaluationSimple/evaluatee', $eval), Set::extract('/EvaluationSimple/evaluator', $eval));
        $names = $this->User->getFullNames($users);
        
        $comments_html = '';
        //Write the comments if they exist
        foreach ($comments as $userId => $evaluator) {
            $comments_html .= '<br><b>Evaluator: '.$names[$userId].'</b><br>'; // Evaluator
            foreach ($evaluator as $evaluatee => $com) {
                $comments_html .= $names[$evaluatee].': '.$com.'<br>';
            }
        }
        
        return $comments_html;
    }
    
    /**
     * _writeScoresTbl // MT
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * 
     * @return HTML string for ScoresTable
     */
    function _writeScoresTbl($event, $grp_event_id, $grp_id)
    {
        $this->User = ClassRegistry::init('User');
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->SimpleEvaluation = ClassRegistry::init('SimpleEvaluation');

        $groupMembers = $this->GroupsMembers->find('list',
            array('conditions' => array('group_id' => $grp_id),'fields' => array('user_id', 'user_id')));
        $eval = $this->EvaluationSimple->findAllByGrpEventId($grp_event_id);
        $evaluators = Set::extract('/EvaluationSimple/evaluator', $eval);
        $evaluatees = Set::extract('/EvaluationSimple/evaluatee', $eval);
        $dropped = array_diff(array_unique(array_merge($evaluators, $evaluatees)), $groupMembers);
        $evaluators = array_merge($groupMembers, $dropped);
        foreach ($evaluators as $userid) {
            $userinfo = $this->User->find('first',
                array(
                    'conditions' => array('User.id' => $userid),
                    'contain' => array('Role')
                )
            );
            $temp[$userid] = $userinfo;
        }
        $penalty = $this->SimpleEvaluation->formatPenaltyArray($temp, $event['Event']['id'], $grp_id);
        $temp = Set::extract('/Role[id='.$this->User->USER_TYPE_TA.']/RolesUser/user_id', $temp);
        $evaluatees = array_diff($evaluators, $temp);
        $evaluatee_names = $this->User->getFullNames($evaluatees); 
        $evaluator_names = $this->User->getFullNames($evaluators);       
        $colspan = count($evaluatees); 
       
        $tbl = '<table border="1" align="center"><tr><th rowspan="2"><b>Evaluator</b></th>
                <th colspan="'.$colspan.'"><b>Members Evaluated</b></th></tr><tr>';
        //Write the members that have been evaluated
        foreach ($evaluatee_names as $userId => $name) {
            $drop = in_array($userId, $dropped) ? ' *' : '';
            $tbl .= '<th>'.$name.$drop.'</th>';
        }
        $tbl .= '</tr>';
        
        $scores = Set::combine($eval, '{n}.EvaluationSimple.evaluatee', '{n}.EvaluationSimple.score', '{n}.EvaluationSimple.evaluator');
        $count = array_combine($evaluatees, array_fill(0, count($evaluatees), 0));
        $total = array_combine($evaluatees, array_fill(0, count($evaluatees), 0));
        // Write Scores
        foreach ($evaluator_names as $userId => $evaluator) {
            $drop = in_array($userId, $dropped) ? ' *' : '';
            $tbl .= '<tr><td>'.$evaluator.$drop.'</td>';
            foreach (array_keys($evaluatee_names) as $key) {
                $scoregiven = isset($scores[$userId][$key]) ? number_format(intval($scores[$userId][$key]), 2) : '-';
                $tbl .= '<td>'.$scoregiven.'</td>';
                $total[$key] += isset($scores[$userId][$key]) ? $scores[$userId][$key] : 0;
                $count[$key] += isset($scores[$userId][$key]) ? 1 : 0;
            }
            $tbl .= '</tr>';
        }
        
        $tbl .= '<tr><td><b>Totals</b></td>';
        foreach ($evaluatees as $evaluatee) {
            $tbl .= '<td>'.number_format($total[$evaluatee], 2).'</td>';
        }
        $tbl .= '</tr><tr><td><b>Penalty</b></td>';
        foreach ($evaluatees as $evaluatee) {
            $minus = $total[$evaluatee] * $penalty[$evaluatee] / 100;
            $tbl .= '<td>'.number_format($minus, 2).' ('.$penalty[$evaluatee].'%)</td>';
        }
        $tbl .= '</tr><tr><td><b>Final Mark</b></td>';
        foreach ($evaluatees as $evaluatee) {
            $final[$evaluatee] = $total[$evaluatee] * (1 - $penalty[$evaluatee] / 100);
            $tbl .= '<td>'.number_format($final[$evaluatee], 2).'</td>';
        }
        $tbl .= '</tr><tr><td><b># of Evaluator(s)</b></td>';
        foreach ($evaluatees as $evaluatee) {
            $tbl .= '<td>'.$count[$evaluatee].'</td>';
        }
        $tbl .= '</tr><tr><td><b>Average Received</b></td>';
        foreach ($evaluatees as $evaluatee) {
            $avg = ($count[$evaluatee] > 0) ? $final[$evaluatee] / $count[$evaluatee] : 0;
            $tbl .= '<td>'.number_format($avg, 2).'</td>';
        }
        $tbl .= '</tr>';
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
     * _writeEvalDetails // MT
     * 
     * @param mixed $event
     * @param mixed $grp_id
     * @param mixed $params
     *
     * @access private
     * @return html string
     */
    function _writeEvalDetails($event, $grp_id, $params)
    {
        $this->Group = ClassRegistry::init('Group');
        $groupName = '-';
        $evalEventType = '-';
        $eventTemplateType = '-';
        $group = $this->Group->findById($grp_id);
        //Write Group name
        if ($params['include']['group_names'] == '1') {
            $groupName = $group['Group']['group_name'];
        }
        $group = '<p>Group: '.$groupName.'<br>';
        //Write if self-eval is 'yes' or 'no'
        $selfEval = $event['Event']['self_eval'] == '0' ? 'No' : 'Yes';
        $selfEval = 'Self-Evaluation: '.$selfEval.'<br>';
        //Write Event Name
        $eventName = $event['Event']['title'];
        $eventName = 'Event Name: '.$eventName.'<br>';
        if ($params['include']['eval_event_type'] == '1') {
            $eventTemplateType = ucfirst(strtolower($event['EventTemplateType']['type_name']));
        }
        $eventTemplateType = 'Evaluation Type: '.$eventTemplateType.'<br>';
        //Write due date and description
        $dueDate = $event['Event']['due_date'];      
        $dueDate = 'Due Date: '.date("D, F j, Y g:i a", strtotime($dueDate)).'<br>';
        $description = $event['Event']['description'];
        $description = 'Description: '.$description.'</p>';
        
        return ($group.$selfEval.$eventName.$eventTemplateType.$dueDate.$description);             
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
     * _getIncompleteMembers // MT
     * 
     * @param mixed $eventId
     * @param mixed $groupId
     * 
     * @return array() consisting of members who have not completed the evaluation
     */    
    function _getIncompleteMembers($eventId, $groupId)
    {
        //Get the evaluation submissions for the given $eventId
        //Do an array_diff on members in $groupId
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->Group = ClassRegistry::init('Group');
        
        $members = $this->Group->getMembersByGroupId($groupId, 'all');
        $memberslist = Set::extract('/Member/id', $members);
        $submissions = $this->EvaluationSubmission->getEvalSubmissionsByEventId($eventId);
        $submitter_list = Set::extract('/EvaluationSubmission/submitter_id', $submissions);
        $inComplete = array_diff($memberslist, $submitter_list);
        
        return $inComplete;
    }
    
    /**
     * _getUnenrolledMembers // MT
     *
     * @param mixed $eventId
     * @param mixed $groupId
     *
     * @return array() consisting of members who have left
     */
    function _getUnenrolledMembers($eventId, $groupId)
    {
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->Group = ClassRegistry::init('Group');

        $members = $this->Group->getMembersByGroupId($groupId, 'all');
        $memberslist = Set::extract('/Member/id', $members);
        $submissions = $this->EvaluationSubmission->getEvalSubmissionsByEventId($eventId);

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