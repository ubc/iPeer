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
         switch($event['Event']['template_id']){
             case 1:
                 $this->_createSimpleResultsPdf($params,$event);
                 break;
             case 2:
                 $this->_createRubricResultsPdf($params,$event);
                 break;
             case 3:
                 $this->_createSurveyResultsPdf($params,$event);
                 break;
             case 4:
                 $this->_createMixResultsPdf($params,$event);
                 break;
         }
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
       
        //Write header text
        $headertext = '<h2>Evaluation Event Detail for '. $event['Course']['course'].' - '.$event['Event']['title'].'</h2>';
        $spdf->writeHTML($headertext, true, FALSE, true, FALSE, '');
 
        $this->Group = ClassRegistry::init('Group');
        $page_count = 0;
        foreach($event['GroupEvent'] as $groupevent){
            //Get the groupevent id and the group id for each group in the evaluation
            $grp_event_id = $groupevent['id'];
            $grp_id = $groupevent['group_id'];
            
            //Call writeEvalDetails
            $evalDetails = $this->_writeEvalDetails($event,$grp_id);      
            $spdf->writeHTML($evalDetails, true, false, true, false, '');
            
            //Write Summary 
            $spdf->writeHTML('<br>', true, false, true, false, '');
            $spdf->writeHTML('<h3>Summary</h3>', true, false, true, false, '');
            
            //Get Membersid's and Membernames who have not submitted their evaluations
            $inComplete = $this->_getIncompleteMembers($event['Event']['id'],$grp_id);
            $inComplete = $this->_getMemberNames($inComplete);
            $spdf->writeHTML('<p><b>Members who have not submitted their evaluations</b></p>',true, false, true, false, '');
            foreach ($inComplete as $name){
                $spdf->writeHTML($name,true, false, true, false, '');
            }
            $spdf->writeHTML('<br>', true, false, true, false, '');
            
           //Get if self evaluation is 'yes' or 'no'
           $event['Event']['self_eval'] == '0'? $selfeval = 'No' : $selfeval = 'Yes';
           
           //Write the scores table
           $spdf->writeHTML('<h3>Evaluation Results</h3>',true, false, true, false, '');
           $stbl = $this->_writeScoresTbl($event,$grp_event_id,$grp_id); 
           $spdf->writeHTML($stbl, true, false, true, false, '');
           
           //Write the comments if they are there
           $comments_text = '<h3>Comments</h3>';
           $spdf->writeHTML($comments_text, true, false, true, false, '');
           $comments = $this->_writeComments($event,$grp_event_id);
           $spdf->writeHTML($comments, true, false, true, false, '');

         $spdf->lastPage();
         $spdf->addPage();
         $page_count++;
        }
        $spdf->deletePage($page_count+1);

        if(ob_get_contents()){
           ob_clean();
        }
        return $spdf -> Output($fileName,'D');

    }
    
    /*
     * _writeComments 
     * 
     * @param mixed $event
     * @param GroupEventId $grp_event_id
     * 
     * @return HTML string consisting of comments
     * */
    function _writeComments($event,$grp_event_id){
       $evaluators = $this->_getMembers($event['Event']['id'],$grp_event_id);
       $evaluatees = $this->_filterTutors($grp_event_id);
       $evaluatee_names = $this->_getMemberNames($evaluatees); 
       $evaluator_names = $this->_getMemberNames($evaluators);
       
       $comments_html = '';
       //Write the comments if they exist
       for($i=0;$i<sizeof($evaluators);$i++){       
            for($j=0;$j<sizeof($evaluatees);$j++){
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
    
    /*
     * _writeScoresTbl
     * 
     * @param mixed $event
     * @param GroupEventId $grp_event_id
     * @param GroupId $grp_id
     * 
     * @return HTML string for ScoresTable
     * */
    function _writeScoresTbl($event,$grp_event_id,$grp_id){
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
        for($i=0;$i<sizeof($evaluatee_names);$i++){
            $tbl = $tbl.'<th>'.$evaluatee_names[$i].'</th>';
        }
        $tbl = $tbl.'</tr>';
        
        $evaluator_count = array();
        
        //Write Scores
        for($i=0;$i<sizeof($evaluators);$i++){
            $tbl = $tbl.'<tr><td>'.$evaluator_names[$i].'</td>'; //Write Evalator Name
            for($j=0;$j<sizeof($evaluatees);$j++){
                $scoregiven = $this->_getScoreGiven($evaluators[$i], $evaluatees[$j], $grp_event_id);
                $tbl=$tbl.'<td>'.$scoregiven[0].'</td>';
            }
            $tbl = $tbl.'</tr>';
        }
        
        $total = null;
        $totals_array = array();
        $tbl = $tbl . '<tr><td><b>Totals</b></td>';
        
        //Write Totals
        for($i=0;$i<sizeof($evaluatees);$i++){
            $evaluator_count[$evaluatees[$i]] = 0;
            for($j=0;$j<sizeof($evaluators);$j++){
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
        $penalty_array = $this->_getPenaltyArray($event,$evaluatees);
         $tbl = $tbl . '<tr><td>Penalty</td>';
         for($i=0;$i<sizeof($evaluatees);$i++){
             $tbl = $tbl.'<td>'.$penalty_array[$evaluatees[$i]].'</td>';
         }
        $tbl = $tbl.'</tr>';      
        
        //Write Final Mark  
        $tbl = $tbl . '<tr><td><b>Final Mark</b></td>';
         for($i=0;$i<sizeof($evaluatees);$i++){
             if($penalty_array[$evaluatees[$i]] == '-'){
                 $tbl = $tbl.'<td>'.$totals_array[$evaluatees[$i]].'</td>';
             }
             else{
                 $final_mark = (100 - $penalty_array[$evaluatees[$i]]) * $totals_array[$evaluatees[$i]];
                 $tbl = $tbl.'<td>'.$final_mark.'</td>';
             }     
         }
        $tbl = $tbl.'</tr>';  
        
        //Write Evaluator Count
        $tbl = $tbl . '<tr><td># Evaluators</td>';
         for($i=0;$i<sizeof($evaluatees);$i++){
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
         for($i=0;$i<sizeof($evaluatees);$i++){
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
    
    /*
     * _getPenaltyArray
     * 
     * @param mixed $event
     * @param array() - $evaluatees 
     * 
     * @return array() - an associative array consisting of the penalties for the evaluatees or '-' if no penalty
     * */
    function _getPenaltyArray($event,$evaluatees){
        $this->Penalty= ClassRegistry::init('Penalty');
        $this->EvaluationSubmission= ClassRegistry::init('EvaluationSubmission');
        $penalty_array = array();
        for($i=0;$i<sizeof($evaluatees);$i++){
            $evalsub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($event['Event']['id'], $evaluatees[$i]);
            $days_late = $this->EvaluationSubmission->daysLate($event['Event']['id'], $evalsub['EvaluationSubmission']['date_submitted']);
            $penalty = $this->Penalty->getPenaltyByEventAndDaysLate($event['Event']['id'], $days_late);
            isset($penalty)? $percent_penalty = $penalty['Penalty']['percent_penalty'] : $percent_penalty = '-';
            $penalty_array[$evaluatees[$i]] = $percent_penalty;
        }
        
        return $penalty_array;
    }
    
    /*_filterTutors
     * 
     * @param GroupId $grp_id
     * 
     * @return array() consisting of groupmembers no tutors
     * */
    function _filterTutors($grp_id){
        $this->User = ClassRegistry::init('User');
        $grpMembersNoTutors = $this->User->getEventGroupMembersNoTutors($grp_id, TRUE, null);
        $evaluatees = array();
        foreach($grpMembersNoTutors as $members){
            array_push($evaluatees,$members['User']['id']);
        }
        return $evaluatees;
    }
    
    /*
     * _writeEvalDetails
     * 
     * @param mixed $event
     * @param Group Id $group_id
     * 
     * @return html string
     */
    function _writeEvalDetails($event,$grp_id){
        $this->Group = ClassRegistry::init('Group');
        $group = $this->Group->getGroupByGroupId($grp_id);
        //Write Group name
        $groupname = $group['0']['Group']['group_name'];
        $group = '<p>Group : '.$groupname.'<br>';
        //Write if self-eval is 'yes' or 'no'
        $selfeval = null;
        $event['Event']['self_eval'] == '0'? $selfeval = 'No' : $selfeval = 'Yes';
        $selfeval = 'Self-Evaluation : '.$selfeval.'<br>';
        //Write Event Name
        $eventname = $event['Event']['title'];
        $eventname = 'Event Name : '.$eventname.'<br>';
        $eventTemplateType = ucfirst(strtolower($event['EventTemplateType']['type_name']));
        $eventTemplateType = 'Evaluation Type : '.$eventTemplateType.'<br>';
        //Write duedate and description
        $duedate = $event['Event']['due_date'];      
        $duedate = 'Due Date : '.date("D,F j, Y, g:i a", strtotime($duedate)).'<br>';
        $description = $event['Event']['description'];
        $description = 'Description : '.$description.'</p>';
        
        return ($group.$selfeval.$eventname.$eventTemplateType.$duedate.$description);             
    }
    
    /*
     * _getScoreGiven
     * 
     * @param Evaluator Id - $evaluator
     * @param Evaluatee Id - $evaluatee
     * @param GroupEventId - $grpEventId
     * 
     * @return Integer - Score Given by evaluator to evaluatee or '-' if no score given 
     * */
    function _getScoreGiven($evaluator,$evaluatee,$grpEventId){
         $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
         $results = $this->EvaluationSimple->getEvalMarkByGrpEventIdEvaluatorEvaluatee($grpEventId, $evaluator, $evaluatee);
         $scores = array();
         isset($results['EvaluationSimple']['score'])? $mark = $results['EvaluationSimple']['score'] : $mark= '-';
         array_push($scores,$mark);
         isset($results['EvaluationSimple']['comment'])? $comment = $results['EvaluationSimple']['comment'] : $comment= '-';
         array_push($scores,$comment);
         return $scores;
    }

    /*
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
        
        $members = $this->Group->getMembersByGroupId($groupId,'all');
        $memberslist = array();
        foreach($members as $m){
            array_push($memberslist,$m['Member']['id']);
        }
        $submitter_list = array();
        $submissions = $this->EvaluationSubmission->getEvalSubmissionsByEventId($eventId);
      
        foreach($submissions as $s){
           array_push($submitter_list,$s['EvaluationSubmission']['submitter_id']);
         }
        $inComplete = array_values(array_diff($memberslist, $submitter_list));
        
        return $inComplete;
    }
    
     /*
     * _getMemberNames 
     * 
     * @param array() $members
     * 
     * @return array() consisting of member's names 
     */
    
    function _getMemberNames($members){
        $nameslist = array();
         $this->User = ClassRegistry::init('User');
        for($i = 0;$i < sizeof($members);$i++){
            array_push($nameslist,$this->User->getFullNames($members[$i]));
        }
        
        $names = array();
        foreach($nameslist as $name){
            foreach($name as $n){
                array_push($names,$n);
            }
     
        }
        return $names; 
    }
    
    /*
     * _getMembers 
     * @param GroupEventId $grp_event_Id
     * 
     * @return array() consisting of member's belonging to the group
     */
    function _getMembers($grp_event_Id){
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
  
        $members = $this->GroupEvent->getGroupMembers($grp_event_Id);
        $memberslist = array();
        foreach($members as $m){
            array_push($memberslist,$m['GroupsMembers']['user_id']);
        }
        return $memberslist;       
    }
    
}
?>