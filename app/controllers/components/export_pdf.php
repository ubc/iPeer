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
     * createPdf
     *
     * @param mixed $params params
     * @param mixed $event  event
     *
     * @access public
     * @return void
     */  
    function createPDF($params, $event)
    {
        App::import('Vendor', 'xtcpdf');
        $this->User = ClassRegistry::init('User');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->responseModelName = EvaluationResponseBase::$types[$event['Event']['event_template_type_id']];
        $this->responseModel = ClassRegistry::init($this->responseModelName);
        
        // Rubric PDFs should be landscape in order to reduce PDF generation time and improve readability
        $pdf_orientation = $event['Event']['event_template_type_id'] == 2 ? 'L' : 'P';
        $epdf = new XTCPDF($pdf_orientation);
        $epdf->setFontSubsetting(false);
        $epdf->SetFont('helvetica', '', 10);
        // Construct the filename and extension
        $fileName = !empty($params['file_name']) ? $params['file_name'].'.pdf' : date('m.d.y').'.pdf';
        $epdf->AddPage();
        
        $courseName = $params['include']['course'] ? ': '.$event['Course']['course'] : '';
        $eventName = $params['include']['eval_event_names'] ? ' - '.$event['Event']['title'] : '';
        $headerText = '<h2>Evaluation Event Detail'.$courseName.$eventName.'</h2>';
        $epdf->writeHTML($headerText, true, false, true, false, '');
        
        // fetch this data only once
        switch ($event['Event']['event_template_type_id']) {
            case 1:
                break;
            case 2:
                $this->RubricsCriteria = ClassRegistry::init('RubricsCriteria');
                $rubric_id = $event['Event']['template_id'];
                $rubric_criteria = $this->RubricsCriteria->getCriteria($rubric_id);
                break;
            case 4:
                $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
                
                $mixevalQuestionsForTable = $this->MixevalQuestion->find('all', array(
                    'conditions' => array('mixeval_id' => $event['Event']['template_id'], 
                        'required' => 1, 'self_eval' => 0),
                    'order' => array('question_num')
                ));
                $mixevalQuestionsForResults = $this->MixevalQuestion->find('all', array(
                    'conditions' => array('mixeval_id' => $event['Event']['template_id']),
                    'order' => array('question_num')
                ));
                break;
        }
        foreach ($event['GroupEvent'] as $index => $groupEvent) {
            if($index > 0) {
                $epdf->addPage();  
            }
            $grpEventId = $groupEvent['id'];
            $grpId = $groupEvent['group_id'];
            
            // write group/event details
            $evalDetails = $this->_writeEvalDetails($event, $grpId, $params);
            $epdf->writeHTML($evalDetails, true, false, true, false, '');
            
            // get incomplete/unenrolled members
            $this->responseModel->recursive = -1;
            $submitted = $this->responseModel->findAllByGrpEventId($grpEventId);
            $this->responseModel->recursive = 1;
            
            $members = Set::extract('/GroupsMembers/user_id', $this->GroupsMembers->findAllByGroupId($grpId));
            $evaluators = array_unique(Set::extract('/'.$this->responseModelName.'/evaluator', $submitted));
            $evaluatees = array_unique(Set::extract('/'.$this->responseModelName.'/evaluatee', $submitted));
            $all_group_user_ids = array_unique(array_merge($evaluators, $evaluatees, $members));
            $inComplete = array_diff($members, $evaluators);
            $unEnrolled = array_diff($all_group_user_ids, $members);
            
            $names = $this->User->getFullNames($all_group_user_ids);
            
            $eval_submitted = $this->EvaluationSubmission->findAllByGrpEventId($grpEventId);
            $eval_submitted = Set::extract('/EvaluationSubmission/submitter_id', $eval_submitted);
            $evaluations = $this->responseModel->find('all', array(
                'conditions' => array('grp_event_id' => $grpEventId, 'evaluator' => $eval_submitted),
            ));
        
            $title = (!empty($inComplete) || !empty($unEnrolled)) ? __('Summary', true) : '';
            $epdf->writeHTML('<br><h3>'.$title.'</h3>', true, false, true, false, '');
            $title = (!empty($inComplete)) ? __('Members who have not submitted their evaluations', true) : '';
            $epdf->writeHTML('<p><b>'.$title.'</b></p>', true, false, true, false, '');
            foreach ($inComplete as $incomplete_user_id){
                $name = $names[$incomplete_user_id];
                $epdf->writeHTML($name, true, false, true, false, '');
            }
            $title = (!empty($unEnrolled)) ? __('Left the group, but had submitted or were evaluated', true) : '';
            $epdf->writeHTML('<p><b>'.$title.'</b></p>', true, false, true, false, '');
            foreach ($unEnrolled as $un_enrolled_user_id){
                $name = $names[$un_enrolled_user_id];
                $epdf->writeHTML($name, true, false, true, false, '');
            }
            
            $header = '<h3>'.__('Evaluation Results', true).'</h3><br>';
            
            // broke the summary table and details section into two switch blocks
            // would be more efficient if the user is filtering out sections
            if ($params['include']['grade_tables']) {
                switch ($event['Event']['event_template_type_id']) {
                    case 1:
                        $table = $this->_writeScoresTbl($event, $grpEventId, $grpId, $params, $evaluations, $unEnrolled, $names);
                        break;
                    case 2:
                        $table = $this->_writeRubricResultsTbl($event, $grpEventId, $grpId, $params, $evaluations, $unEnrolled, $names, $rubric_criteria);
                        break;
                    case 4:
                        $table = $this->_writeMixResultsTbl($event, $grpEventId, $grpId, $params, $evaluations, $unEnrolled, $names, $mixevalQuestionsForTable);
                        break;
                }
                if (!empty($table)) {
                    $epdf->writeHTML('<br>', true, false, true, false, '');
                }
                $epdf->writeHTML($table, true, false, true, false, '');
            }
            switch($event['Event']['event_template_type_id']) {
                case 1:
                    $results = $params['include']['comments'] ? $this->_writeComments($evaluations, $names) : array();
                    $header = $params['include']['comments'] ? '<h3>'.__('Comments', true).'</h3><br>' : '';
                    break;
                case 2:
                    $results = $this->_writeRubricEvalResults($event, $grpEventId, $grpId, $params, $evaluations, $unEnrolled, $names, $rubric_criteria);
                    break;
                case 4:
                    $results = $this->_writeMixEvalResults($event, $grpEventId, $grpId, $params, $evaluations, $unEnrolled, $names, $mixevalQuestionsForResults);
                    break;
            }

            $epdf->writeHTML($header, true, false, true, false, '');
            foreach ($results as $result) {
                $epdf->writeHTML($result, true, false, true, false, ''); 
            }
        }
        
        if (ob_get_contents()) {
            ob_clean();
        }
        
        return $epdf->Output($fileName, 'I');
    }

    /**
     * _writeMixEvalResults
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * @param mixed $params
     * @param mixed $evaluations
     * @param mixed $dropped
     * @param mixed $names
     * @param mixed $mixevalQuestions
     * 
     * @return HTML Table containing the results
     */
    function _writeMixEvalResults($event, $grp_event_id, $grp_id, $params, $evaluations, $dropped, $names, $mixevalQuestions)
    {
        $this->Mixeval = ClassRegistry::init('Mixeval');
        
        $skipQues = array(); // for filtering out questions
        $skipQues = $params['include']['grade_tables'] ? $skipQues : array_merge($skipQues, array(1, 4));
        $skipQues = $params['include']['comments'] ? $skipQues : array_merge($skipQues, array(2, 3));
        
        $mixevalQuestions = Set::combine($mixevalQuestions, '{n}.MixevalQuestion.question_num', '{n}');
        $mixeval = $this->Mixeval->findById($event['Event']['template_id']);

        $evaluators = Set::extract('/EvaluationMixeval/evaluator', $evaluations);
        $evaluatees = Set::extract('/EvaluationMixeval/evaluatee', $evaluations);
        $penalties = $this->Mixeval->formatPenaltyArray($event['Event']['id'], $grp_id, $names);
        
        $totalScores = array_fill_keys($evaluatees, 0);
        $evalCount = $totalScores;
        $indivEval = array_fill_keys($evaluatees, array());
        foreach ($evaluations as $eval) {
            $evaluator = $eval['EvaluationMixeval']['evaluator'];
            $evaluatee = $eval['EvaluationMixeval']['evaluatee'];
            if (!$event['Event']['self_eval'] && $evaluator == $evaluatee) {
                continue;
            }
            $totalScores[$evaluatee] += $eval['EvaluationMixeval']['score'];
            $evalCount[$evaluatee]++;
            foreach ($eval['EvaluationMixevalDetail'] as $ques) {
                $type = $mixevalQuestions[$ques['question_number']]['MixevalQuestion']['mixeval_question_type_id'];
                if ($type == 1 || $type == 4) {
                    $indivEval[$evaluatee][$ques['question_number']][$evaluator] = $ques['grade'];
                } else {
                    $indivEval[$evaluatee][$ques['question_number']][$evaluator] = $ques['question_comment'];
                }
            }
        }
        $avgScores = array_fill_keys($evaluatees, array());
        foreach ($totalScores as $userId => $score) {
            $avgScores[$userId]['total'] = ($evalCount[$userId]) ? $score / $evalCount[$userId] : 0;
            $avgScores[$userId]['deduct'] =  $avgScores[$userId]['total'] * $penalties[$userId] / 100;
            $avgScores[$userId]['subtotal'] = $avgScores[$userId]['total'] - $avgScores[$userId]['deduct'];
        }
        $average = Set::extract('/subtotal', $avgScores);
        $avg = count($average) ? array_sum($average) / count($average) : 0;
        
        $results = array();
        
        $questionNums = array_keys($mixevalQuestions);
        foreach ($indivEval as $userId => $questions) {
            $mEval = '';
            $drop = in_array($userId, $dropped) ? ' *' : '';
            $mEval .= '<u><b>'.__('Evaluatee', true).': '.'</b>'.$names[$userId].$drop.'</u>';
            if($avgScores[$userId]['subtotal'] > $avg) {
                $scoreComment = ' - Above Average';
            } else if ($avgScores[$userId]['subtotal'] < $avg) {
                $scoreComment = ' - Below Average';
            } else {
                $scoreComment = ' - Average';
            }
            // only include if total marks is greater than zero
            // doesn't make sense to show it when everyone has zero
            if ($params['include']['final_marks'] && $mixeval['Mixeval']['total_marks']) {
                $penalty = ($penalties[$userId]) ? ' - '.number_format($avgScores[$userId]['deduct'], 2).
                    ' = '.number_format($avgScores[$userId]['subtotal'], 2) : '';
                $mEval .= '<br>'.__('Final Total', true).': '.number_format($avgScores[$userId]['total'], 2).$penalty.
                    ' ('.number_format($avgScores[$userId]['subtotal'] / $mixeval['Mixeval']['total_marks'] * 100, 2).'%)'.$scoreComment;
                $mEval .= ($penalties[$userId]) ? '<br>Note: '.$penalties[$userId].'% Late Penalty' : '';
            }
            $pNum = 0;
            $sNum = 0;
            $pEval = '';
            $sEval = '';
            foreach ($questionNums as $num) {
                $mixQues = $mixevalQuestions[$num]['MixevalQuestion'];
                // filter out questions of question types we don't want
                if ($mixQues['self_eval']) {
                    $sNum++;
                } else {
                    $pNum++;
                }
                if (in_array($mixQues['mixeval_question_type_id'], $skipQues)) {
                    continue;
                }
                $tempEval = '';
                $qNum = $mixQues['self_eval'] ? $sNum : $pNum;
                $tempEval .= '<span nobr="true"><h4>'.$qNum.'. '.$mixQues['title'].'</h4>';
                $tempEval .= '<ul>';
                if (isset($questions[$num])) {
                    foreach ($questions[$num] as $evaluator => $answer) {
                        // Likert questions or ScoreDropdown
                        if (in_array($mixQues['mixeval_question_type_id'], array(1, 4))) {
                            $answer = number_format($answer, 2).' / '.$mixQues['multiplier'];
                        }
                        $drop = in_array($evaluator, $dropped) ? ' *' : '';
                        $tempEval .= '<li><b>'.$names[$evaluator].$drop.': </b>'.$answer.'</li>';
                    }
                } else {
                    $tempEval .= '<li>N/A</li>';
                }
                $tempEval .= '</ul></span>';
                if ($mixQues['self_eval'] && $event['Event']['self_eval']) {
                    $sEval .= $tempEval;
                } else if (!$mixQues['self_eval']) {
                    $pEval .= $tempEval;
                }
            }
            
            // only print if questions exist in the section after filtering if any were done
            $mEval .= ($mixeval['Mixeval']['peer_question'] && $pEval) ? $pEval : '';
            $mEval .= ($mixeval['Mixeval']['self_eval'] && $sEval) ? '<h3><u>'.__('Self-Evaluation', true)."</u></h3>\n".$sEval : '';
            $mEval .= (!$pEval && !$sEval) ? '<br><br>' : ''; // if no questions are printed add some line breaks
            
            $results []= $mEval;
        }

        return $results;
    }
 
    /**
     * _writeMixResultsTbl
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * @param mixed $params
     * @param mixed $evaluations
     * @param mixed $unEnrolled
     * @param mixed $names
     * @param mixed $mixevalQuestions
     * 
     * @return HTML Table containing the results
     */               
    function _writeMixResultsTbl($event, $grp_event_id, $grp_id, $params, $evaluations, $dropped, $names, $mixevalQuestions)
    {
        $this->Mixeval = ClassRegistry::init('Mixeval');
         
        //Write the Table Header
        $mRTBL = '<table border="1" align="center"><tr><th><b>Evaluatee</b></th>';
        $total = 0;
        $num = 1;
        $quesNums = array();
        foreach($mixevalQuestions as $question) {
            if (in_array($question['MixevalQuestion']['mixeval_question_type_id'], array(1, 4))) {
                $question_num = $question['MixevalQuestion']['question_num'];
                $multiplier = $question['MixevalQuestion']['multiplier'];
                $mRTBL .= '<th>'.$num.' (/'.number_format($multiplier, 1).')</th>';
                $total += $multiplier;   
                $quesNums[] = $question_num;     
            }
            $num++;                 
        }
        if (empty($quesNums)) {
            return '';
        }
        if ($params['include']['final_marks']) {
            $mRTBL .= '<th>Total (/'.number_format($total, 2).')</th>';
        }
        $mRTBL .= '</tr>';

        $evaluators = Set::extract('/EvaluationMixeval/evaluator', $evaluations);
        $evaluatees = Set::extract('/EvaluationMixeval/evaluatee', $evaluations);
        $scores = Set::combine($evaluations, '{n}.EvaluationMixeval.evaluator', '{n}.EvaluationMixevalDetail', '{n}.EvaluationMixeval.evaluatee');        

        $grades = array();
        $numEval = array();
        foreach ($scores as $evaluateeId => $evaluators) {
            $grades[$evaluateeId] = array_fill_keys($quesNums, 0);
            $numEval[$evaluateeId] = $grades[$evaluateeId];
            foreach ($evaluators as $evaluator) {
                foreach ($evaluator as $mark) {
                    if (in_array( $mark['question_number'], array_keys($grades[$evaluateeId]))) {
                        $grades[$evaluateeId][$mark['question_number']] += $mark['grade'];
                        $numEval[$evaluateeId][$mark['question_number']]++;
                    }
                }
            }
        }
        
        $quesAvg = array_fill_keys($quesNums, 0);
        $evalNum = $quesAvg;
        $evaluateeAvg = array_fill_keys(array_keys($grades), 0);
        $penalties = $this->Mixeval->formatPenaltyArray($event['Event']['id'], $grp_id, $names);

        foreach ($grades as $evaluateeId => $questions) {
            $suffix = in_array($evaluateeId, $dropped) ? ' *' : '';
            $mRTBL .= '<tr><td>'.$names[$evaluateeId].$suffix.'</td>';
            foreach ($questions as $num => $mark) {
                $avg = ($numEval[$evaluateeId][$num] == 0) ? 0 : $mark / $numEval[$evaluateeId][$num];
                $mRTBL .= '<td>'.number_format($avg, 2).'</td>';
                $quesAvg[$num] += $avg;
                $evalNum[$num]++;
                $evaluateeAvg[$evaluateeId] += $avg;
            }
            if ($params['include']['final_marks']) {
                $deduct = $evaluateeAvg[$evaluateeId] * $penalties[$evaluateeId] / 100;
                $subtotal = $evaluateeAvg[$evaluateeId] - $deduct;
                $percent = ($total == 0) ? 0 : $subtotal / $total * 100;
                $penalty = ($penalties[$evaluateeId]) ? ' - '.number_format($deduct, 2).' = '.number_format($subtotal, 2) : '';
                $mRTBL .= '<td>'.number_format($evaluateeAvg[$evaluateeId], 2).$penalty.' ('.number_format($percent, 2).'%)'.'</td>';
                $evaluateeAvg[$evaluateeId] = $subtotal;
            }
            $mRTBL .= '</tr>';
        }

        $mRTBL .= '<tr><td>'.__('Group Average', true).'</td>';
        foreach ($quesAvg as $num => $sum) {
            $avg = ($evalNum[$num] == 0) ? 0 : $sum / $evalNum[$num];
            $mRTBL .='<td>'.number_format($avg, 2).'</td>';
        }
        $grpAvg = (count($evaluateeAvg) == 0) ? 0 : array_sum($evaluateeAvg) / count($evaluateeAvg);
        $mRTBL .= $params['include']['final_marks'] ? '<td>'.number_format($grpAvg, 2).'</td>' : '';
        $mRTBL .= '</tr></table>';
                   
        return $mRTBL;
    }

    /**
     * _writeRubricResultsTbl
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * @param mixed $params 
     * @param mixed $evaluations
     * @param mixed $dropped
     * @param mixed $names
     * @param mixed $rubric_criteria
     *
     * @access public
     * @return void
     */
    function _writeRubricResultsTbl($event, $grp_event_id, $grp_id, $params, $evaluations, $dropped, $names, $rubric_criteria)
    {
        $this->Rubric = ClassRegistry::init('Rubric');
         
        //Write the Table Header
        $rSTBL = '<table border="1" align="center"><tr><th><b>Evaluatee</b></th>';
        $total = array_sum(Set::extract('/RubricsCriteria/multiplier', $rubric_criteria));
        foreach($rubric_criteria as $criteria) {
            //Get the Rubric Criteria number and the multiplier and write it to the table header
            $criteria_num = $criteria['RubricsCriteria']['criteria_num'];
            $multiplier = $criteria['RubricsCriteria']['multiplier'];
            $rSTBL = $rSTBL.'<th>'.$criteria_num.' (/'.number_format($multiplier, 1).' )</th>';
        }
        $rSTBL .= $params['include']['final_marks'] ? '<th>Total (/'.number_format($total, 2).')</th>' : '';
        $rSTBL .= '</tr>';
        
        $evaluators = Set::extract('/EvaluationRubric/evaluator', $evaluations);
        $evaluatees = Set::extract('/EvaluationRubric/evaluatee', $evaluations);
        $scores = Set::combine($evaluations, '{n}.EvaluationRubric.evaluator', '{n}.EvaluationRubricDetail', '{n}.EvaluationRubric.evaluatee');        
        
        $grades = array();
        $numEval = array();
        foreach ($evaluatees as $evaluatee) {
            $grades[$evaluatee] = array_fill_keys(Set::extract('/RubricsCriteria/criteria_num', $rubric_criteria), 0);
            $numEval[$evaluatee] = $grades[$evaluatee];
            foreach ($scores[$evaluatee] as $evaluator) {
                foreach ($evaluator as $mark) {
                    $grades[$evaluatee][$mark['criteria_number']] += $mark['grade'];
                    $numEval[$evaluatee][$mark['criteria_number']]++;
                }
            }
        }

        // create table
        $quesAvg = array_fill_keys(Set::extract('/RubricsCriteria/criteria_num', $rubric_criteria), 0);
        $evalNum = $quesAvg;
        $evaluateeAvg = array_fill_keys(array_keys($grades), 0);
        $penalties = $this->Rubric->formatPenaltyArray($event['Event']['id'], $grp_id, $names);
        foreach ($grades as $userId => $marks) {
            $suffix = in_array($userId, $dropped) ? ' *' : '';
            $rSTBL .= '<tr><td>'.$names[$userId].$suffix.'</td>';
            foreach ($marks as $num => $mark) {
                $avg = ($numEval[$userId][$num] == 0) ? 0 : $mark / $numEval[$userId][$num];
                $rSTBL .= '<td>'.number_format($avg, 2).'</td>';
                $quesAvg[$num] += $avg;
                $evalNum[$num]++;
                $evaluateeAvg[$userId] += $avg;
            }
            if ($params['include']['final_marks']) {
                $deduct = $evaluateeAvg[$userId] * $penalties[$userId] / 100;
                $subtotal = $evaluateeAvg[$userId] - $deduct;
                $percent = ($total == 0) ? 0 : $subtotal / $total * 100;
                $penalty = ($penalties[$userId]) ? ' - '.number_format($deduct, 2).' = '.number_format($subtotal, 2) : '';
                $rSTBL .= '<td>'.number_format($evaluateeAvg[$userId], 2).$penalty.' ('.number_format($percent, 2).'%)'.'</td>';
                $evaluateeAvg[$userId] = $subtotal;
            }
            $rSTBL .= '</tr>';
        }
        
        $rSTBL .= '<tr><td>'.__('Group Average', true).'</td>';
        foreach ($quesAvg as $num => $sum) {
            $avg = ($evalNum[$num] == 0) ? 0 : $sum / $evalNum[$num];
            $rSTBL .='<td>'.number_format($avg, 2).'</td>';
        }
        $grpAvg = (count($evaluateeAvg) == 0) ? 0 : array_sum($evaluateeAvg) / count($evaluateeAvg);
        $rSTBL .= $params['include']['final_marks'] ? '<td>'.number_format($grpAvg, 2).'</td>' : '';
        $rSTBL .= '</tr></table>';

        return $rSTBL;
    }        
    
    /**
     * _writeRubricEvalResults
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * @param mixed $params
     * @param mixed $evaluations
     * @param mixed $dropped
     * @param mixed $names
     * @param mixed $rubric_criteria
     *
     * @access private
     * @return void
     */               
    function _writeRubricEvalResults($event, $grp_event_id, $grp_id, $params, $evaluations, $dropped, $names, $rubric_criteria)
    {
        $this->Rubric = ClassRegistry::init('Rubric');

        $rubricMark = Set::combine($rubric_criteria, '{n}.RubricsCriteria.criteria_num', '{n}.RubricsCriteria.multiplier');
        $total = array_sum($rubricMark);
        
        $evaluators = Set::extract('/EvaluationRubric/evaluator', $evaluations);
        $evaluatees = Set::extract('/EvaluationRubric/evaluatee', $evaluations);
        $scores = Set::combine($evaluations, '{n}.EvaluationRubric.evaluator', '{n}.EvaluationRubricDetail', '{n}.EvaluationRubric.evaluatee');        
        $comments = Set::combine($evaluations, '{n}.EvaluationRubric.evaluator', '{n}.EvaluationRubric.comment', '{n}.EvaluationRubric.evaluatee');
        $penalties = $this->Rubric->formatPenaltyArray($event['Event']['id'], $grp_id, $names);

        $grades = array();
        $numEval = array();
        foreach ($evaluatees as $evaluatee) {
            $grades[$evaluatee] = array_fill_keys(Set::extract('/RubricsCriteria/criteria_num', $rubric_criteria), 0);
            $numEval[$evaluatee] = $grades[$evaluatee];
            foreach ($scores[$evaluatee] as $evaluator) {
                foreach ($evaluator as $mark) {
                    $grades[$evaluatee][$mark['criteria_number']] += $mark['grade'];
                    $numEval[$evaluatee][$mark['criteria_number']]++;
                }
            }
        }
        
        foreach ($grades as $userId => $grade) {
            foreach ($grade as $num => $mark) {
                $grades[$userId][$num] = $mark / $numEval[$userId][$num];
            }
            $grades[$userId]['total'] = array_sum($grades[$userId]);
            $grades[$userId]['deduct'] = $grades[$userId]['total'] * $penalties[$userId] / 100;
            $grades[$userId]['subtotal'] = $grades[$userId]['total'] - $grades[$userId]['deduct'];
        }
        $grpAvg = count($grades) ? array_sum(Set::extract('/subtotal', $grades)) / count($grades) : 0;
        
        $header = '<br><table border="1" align="center"><tr><th><b>'.__('Evaluator', true).'</b></th>';
        foreach ($rubric_criteria as $criterion) {
            $header .= '<th>('.$criterion['RubricsCriteria']['criteria_num'].') '.$criterion['RubricsCriteria']['criteria'].'</th>';
        }
        $header .= '</tr>';
        
        $results = array();
        
        foreach ($scores as $evaluateeId => $evaluator) {
            $rSTBL = ''; 
            if($grades[$evaluateeId]['subtotal'] == $grpAvg) {
                $totalComment = ' - Group Average';
            } else if($grades[$evaluateeId]['subtotal'] > $grpAvg) {
                $totalComment = ' - Above Group Average';
            } else {
                $totalComment = ' - Below Group Average';
            }
            $drop = in_array($evaluateeId, $dropped) ? ' *' : '';
            $rSTBL .= '<div><b>'.__('Evaluatee', true).': '.$names[$evaluateeId].$drop.'</b><br>';
            if ($params['include']['final_marks']) {
                $rSTBL .= __('Number of Evaluators', true).': '.count($evaluator).'<br>';
                $penalty = ($penalties[$evaluateeId]) ? ' - '.number_format($grades[$evaluateeId]['deduct'], 2).
                ' = '.number_format($grades[$evaluateeId]['subtotal'], 2) : '';
                $rSTBL .= __('Final Total', true).': '.number_format($grades[$evaluateeId]['total'], 2).$penalty.
                    ' ('.number_format($grades[$evaluateeId]['subtotal'] / $total * 100, 2).'%)'.$totalComment.'<br>';
                $rSTBL .= ($penalties[$evaluateeId]) ? 'Note: '.$penalties[$evaluateeId].'% Late Penalty<br>' : '';
            }
            $rSTBL .= $header;
            foreach ($evaluator as $evaluatorId => $details) {
                $drop = in_array($evaluatorId, $dropped) ? ' *' : '';
                $rSTBL .= '<tr><td rowspan="2">'.$names[$evaluatorId].$drop.'</td>';
                foreach ($details as $mark) {
                    $critCom = ($params['include']['comments']) ? $mark['criteria_comment'] : '-';
                    $critGrd = ($params['include']['grade_tables']) ? $mark['grade'].' / '.$rubricMark[$mark['criteria_number']] : '-';
                    $rSTBL .= '<td><b>'.__('Grade', true).'</b>: '.$critGrd;
                    $rSTBL .= '<br><b>'.__('Comment', true).'</b>: '.$critCom.'</td>';
                }
                $rSTBL .= '</tr>';
                $rSTBL .= '<tr><td colspan="'.count($rubricMark).'">';
                $genCom = ($params['include']['comments']) ? $comments[$evaluateeId][$evaluatorId] : '-';
                $rSTBL .= '<b>'.__('General Comment', true).':</b><br>'.$genCom.'</td></tr>'; 
            }
            $rSTBL .= '</table></div>';
            
            $results []= $rSTBL;
        }

        return $results;
    }      

    /**
     * _writeComments
     * 
     * @param mixed $evaluations
     * @param mixed $names
     * 
     * @return HTML string consisting of comments
     */
    function _writeComments($evaluations, $names)
    {
        $comments = Set::combine($evaluations, '{n}.EvaluationSimple.evaluatee', '{n}.EvaluationSimple.comment', '{n}.EvaluationSimple.evaluator');
        
        $results = array();
        //Write the comments if they exist
        foreach ($comments as $userId => $evaluator) {
            $results []= '<br><b>Evaluator: '.$names[$userId].'</b><br>'; // Evaluator
            foreach ($evaluator as $evaluatee => $com) {
                $results []= $names[$evaluatee].': '.$com.'<br>';
            }
        }
        
        return $results;
    }
    
    /**
     * _writeScoresTbl
     * 
     * @param mixed $event
     * @param mixed $grp_event_id
     * @param mixed $grp_id
     * @param mixed $params
     * @param mixed $evaluations
     * @param mixed $dropped
     * @param mixed $names
     * 
     * @return HTML string for ScoresTable
     */
    function _writeScoresTbl($event, $grp_event_id, $grp_id, $params, $evaluations, $dropped, $names)
    {
        $this->SimpleEvaluation = ClassRegistry::init('SimpleEvaluation');
        
        $evaluators = array_unique(Set::extract('/EvaluationSimple/evaluator', $evaluations));
        $evaluatees = array_unique(Set::extract('/EvaluationSimple/evaluatee', $evaluations));
        $penalty = $this->SimpleEvaluation->formatPenaltyArray($event['Event']['id'], $grp_id, $names);
        $colspan = count($evaluatees);
        
        if ($colspan <= 0) {
            return '<table border="1" align="center"><tr><th>No Submissions</th></tr></table><br>';
        }
       
        $tbl = '<table border="1" align="center"><tr><th rowspan="2"><b>Evaluator</b></th>
                <th colspan="'.$colspan.'"><b>Members Evaluated</b></th></tr><tr>';
        //Write the members that have been evaluated
        foreach ($evaluatees as $userId) {
            $drop = in_array($userId, $dropped) ? ' *' : '';
            $tbl .= '<th>'.$names[$userId].$drop.'</th>';
        }
        $tbl .= '</tr>';
        
        $scores = Set::combine($evaluations, '{n}.EvaluationSimple.evaluatee', '{n}.EvaluationSimple.score', '{n}.EvaluationSimple.evaluator');
        $count = array_fill_keys($evaluatees, 0);
        $total = array_fill_keys($evaluatees, 0);
        // Write Scores
        foreach ($evaluators as $userId) {
            $drop = in_array($userId, $dropped) ? ' *' : '';
            $tbl .= '<tr><td>'.$names[$userId].$drop.'</td>';
            foreach ($evaluatees as $key) {
                $scoregiven = isset($scores[$userId][$key]) ? number_format(intval($scores[$userId][$key]), 2) : '-';
                $tbl .= '<td>'.$scoregiven.'</td>';
                $total[$key] += isset($scores[$userId][$key]) ? $scores[$userId][$key] : 0;
                $count[$key] += isset($scores[$userId][$key]) ? 1 : 0;
            }
            $tbl .= '</tr>';
        }
        
        if ($params['include']['final_marks']) {
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
        }

        return ($tbl.'</table>');
    }
    
    /**
     * _writeEvalDetails
     * 
     * @param mixed $event
     * @param mixed $grpId
     * @param mixed $params
     *
     * @access private
     * @return html string
     */
    function _writeEvalDetails($event, $grpId, $params)
    {
        $this->Group = ClassRegistry::init('Group');
        $group = $this->Group->findById($grpId);
        //Write Group name
        $groupName = $params['include']['group_names'] ? $group['Group']['group_name'] : '-';
        $groupName = '<p><b>'.__('Group', true).': </b>'.$groupName.'<br>';
        //Write if self-eval is 'yes' or 'no'
        $selfEval = $event['Event']['self_eval'] ? 'Yes' : 'No';
        $selfEval = '<b>'.__('Self-Evaluation', true).': </b>'.$selfEval.'<br>';
        //Write Event Name
        $eventName = $params['include']['eval_event_names'] ? $event['Event']['title'] : '-';
        $eventName = '<b>'.__('Event Name', true).': </b>'.$eventName.'<br>';
        $eventTemplateType = $params['include']['eval_event_type'] ? 
            ucwords(strtolower($event['EventTemplateType']['type_name'])) : '-';
        $eventTemplateType = '<b>'.__('Evaluation Type', true).': </b>'.$eventTemplateType.'<br>';
        //Write due date and description
        $dueDate = '<b>'.__('Due Date', true).': </b>'.date("D, F j, Y g:i a", strtotime($event['Event']['due_date'])).'<br>';
        $description = '<b>'.__('Description', true).': </b>'.$event['Event']['description'].'</p>';
        
        return ($groupName.$selfEval.$eventName.$eventTemplateType.$dueDate.$description);
    }
}