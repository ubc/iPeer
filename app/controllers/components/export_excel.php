<?php
App::import('Vendor', 'PHPExcel', array('file' => 'excel/PHPExcel.php'));
App::import('Vendor', 'PHPExcelWriter', array('file' => 'excel/PHPExcel/Writer/Excel5.php'));

/**
 * ExportExcelComponent
 *
 * @uses ExportBaseNewComponent
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
Class ExportExcelComponent extends ExportBaseNewComponent
{
    public $sheet;
    public $rowls;
    public $alphaNumeric;
    public $cursor = array();
    public $components = array('ExportHelper2');
    public $EvaluationSubmission, $EvaluationSimple, $EvaluationRubric, $User, $GroupsMembers, $GroupEvent, $EvaluationMixeval, $MixevalQuestion, $Event, $Group;

    /**
     * __construct
     *
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->xls = new PHPExcel();
        $this->sheet = $this->xls->getActiveSheet();
        $this->alphaNumeric = array();
        foreach (range('A', 'Z') as $letters) {
            array_push($this->alphaNumeric, $letters);
        }
        $this->cursor = array('x' => 0, 'y' => 1);
        $this->Group = ClassRegistry::init('Group');
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->User = ClassRegistry::init('User');
        $this->Penalty = ClassRegistry::init('Penalty');
    }

    /**
     * _output
     *
     * @param mixed $fileName
     *
     * @access protected
     * @return void
     */
    function _output($fileName)
    {
        //$starting_pos = ord('C');  //unused
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename='.$fileName.".xls");
        header('Cache-Control: max-age=0');
        $objWriter = new PHPExcel_Writer_Excel5($this->xls);
        $objWriter->setTempDir(TMP);
        $objWriter->save('php://output');
    }

    /**
     * _drawToExcelSheetAtCoordinates
     * converts a block array to xls sheet; by default the function fills the sheet starting at the top left corner.
     *
     * @param bool $grid      grid
     * @param int  $rowOffSet rowOffSet
     * @param int  $colOffSet colOffSet
     *
     * @access protected
     * @return void
     */
    function _drawToExcelSheetAtCoordinates($grid = null, $rowOffSet = 0, $colOffSet = 0)
    {
        $rowSpan = count($grid);
        $colSpan = count($grid[0]);
        for ($row = 0; $row < $rowSpan; $row++) {
            for ($col = 0; $col < $colSpan; $col++) {
                $colAlphabetIndex = $this->_convertToRowAlphabetIndex($row + $rowOffSet);
                $this->sheet->getColumnDimension($colAlphabetIndex)->setColumnIndex($colAlphabetIndex)->setWidth(10);
                $cell = $colAlphabetIndex.($col + $colOffSet);
                if (!empty($grid[$row][$col])) {
                    $this->sheet->setCellValue($cell, $grid[$row][$col]);
                }
            }
        }
    }

    /**
     * _convertToRowAlphabetIndex
     *
     * @param mixed $colNum
     *
     * @access protected
     * @return void
     */
    function _convertToRowAlphabetIndex($colNum)
    {
        if ($colNum < 0 ) {
            throw new EXCEPTION("Column number must be greater than zero.");
        }
        if ($colNum < 26) {
            return $this->alphaNumeric[$colNum];
        } else {
            $letterIndex = $colNum/26;
            $former = $this->alphaNumeric[intval($letterIndex) - 1];
            $latter = $this->alphaNumeric[$colNum % 26];
            return $former.$latter;
        }
    }


    /**
     * _buildSimpleEvalResults
     *
     * @param mixed $grpEventId group event id
     * @param mixed $params     params
     *
     * @access protected
     * @return void
     */
    function _buildSimpleEvalResults($grpEventId, $params)
    {
        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $groupMembersNoTutors = $this->ExportHelper2->getGroupMemberWithoutTutorsHelper($grpEventId);
        // Build grid
        $xRange = 9 + count($groupMembers);
        $yRange = 4 + count($groupMembers);
        $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);
        $evaluatorsArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembers);
        $evaluateesArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembersNoTutors);
        $xPosition = 0;
        // Fill in Evaluatee Rows
        //if (!empty($params['include_student_email'])) {
        //    $this->ExportHelper2->fillGridVertically($grid, 6, $xPosition, $evaluateesArray['email']);
        //}
        $grid[$xPosition+1][4] = "Evaluatees:";
        if (!empty($params['include_student_id'])) {
            $xPosition++;
            $this->ExportHelper2->fillGridVertically($grid, 5, $xPosition, $evaluateesArray['student_no']);
        }
        if (!empty($params['include_student_name'])) {
            $xPosition++;
            $this->ExportHelper2->fillGridVertically($grid, 5, $xPosition, $evaluateesArray['name']);
        }

        $xPosition ++;
        $yPosition = 5;
        // Fill in score table
        $grid[$xPosition + count($groupMembers) + 1][$yPosition - 1] = "Evaluatee Avg Score";
        $grid[$xPosition + count($groupMembers) + 2][$yPosition - 1] = "Penalty";
        $grid[$xPosition + count($groupMembers) + 3][$yPosition - 1] = "Final Mark";
        foreach ($groupMembers as $evaluatee) {
            $resultRowArray = array();
            $totalScore = 0;
            $submitterCount = 0;
            foreach ($groupMembers as $member) {
                $score = $this->EvaluationSimple->find('first', array('conditions' => array('EvaluationSimple.evaluatee' => $evaluatee['id'], 'EvaluationSimple.evaluator' => $member['id'])));
                $mark = '';
                if (!empty($score)) {
                    $totalScore += $score['EvaluationSimple']['score'];
                    $mark = $score['EvaluationSimple']['score'];
                    $submitterCount++;
                }
                array_push($resultRowArray, $mark);
            }
            // Insert Evaluatee Average Mark; if neccessary.
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $resultRowArray);
            if (!empty($params['include_final_marks'])) {
                if ($submitterCount > 0) {
                    $aveScore = $totalScore/$submitterCount;
                    $grid[$xPosition + count($groupMembers) + 1][$yPosition] = number_format($aveScore, 2);
                    $submission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($grpEventId, $evaluatee['id']);
                    $due_date = strtotime($submission['Event']['due_date']);
                    $event_end = strtotime($submission['Event']['release_date_end']);
                    $sub_date = strtotime($submission['EvaluationSubmission']['date_submitted']);
                    $group = $this->Group->find('first', array('conditions' => array('GroupEvent.id' => $grpEventId)));
                    $eventId = $group['GroupEvent']['event_id'];
                    $penalty = null;
                    // no submission - if now is after release date end then - gets final deduction
                    if (empty($submission)) {
                        if (time() > $event_end) {
                            $penalty = $this->Penalty->getPenaltyFinal($eventId);
                        }
                    // there is submission - may be on time or late
                    } else {
                        $late_diff = $sub_date - $due_date;
                        // late
                        if (0 < $late_diff) {
                            $days_late = $late_diff/(24*60*60);
                            $penalty = $this->Penalty->getPenaltyByEventAndDaysLate($eventId, $days_late);
                        }
                    }
                    $penaltyPercent  = $penalty['Penalty']['percent_penalty'];
                    $penaltyPercent > 0 ?  $perPenalty =  '-'.$penaltyPercent.'%' : $perPenalty = '--';
                    $final = number_format($aveScore * (1 - $penaltyPercent / 100), 2);

                    $grid[$xPosition + count($groupMembers) + 2][$yPosition] = $perPenalty;
                    $grid[$xPosition + count($groupMembers) + 3][$yPosition] = $final;
                }
            }
            $yPosition++;
        }
        // Fill in Evaluator Columns
        $yPosition = 5;
        if (!empty($params['include_student_name'])) {
      /*$this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $evaluatorsArray['last_name']);
      $yPosition--;
      $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $evaluatorsArray['first_name']);*/
            $yPosition--;
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $evaluatorsArray['name']);
        }
        if (!empty($params['include_student_id'])) {
            $yPosition--;
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $evaluatorsArray['student_no']);
        }
        $yPosition--;
        $grid[$xPosition][$yPosition] = "Evaluators:";
        //return $this->ExportHelper2->arrayDraw($grid);
        return $grid;
    }

    /**
     * _buildSimpleOrRubricsCommentByEvaluatee
     *
     * @param mixed $grpEventId  group event id
     * @param mixed $evaluateeId evaluatee id
     * @param mixed $params      params
     * @param bool  $eventType   event type
     *
     * @access protected
     * @return void
     */
    function _buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluateeId, $params, $eventType=null)
    {
        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $evaluatee = $this->User->findById($evaluateeId);
        // Build Grid
        $xRange = 5;
        $yRange = count($groupMembers) + 2;
        $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);
        $evalType = "";
        $commentType = "";
        // Insert Evaluatee Header
        $xPosition = 1;
        $evaluateeHeaderArray = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee['User']);
        $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, 0, $evaluateeHeaderArray);
        // Insert evaluators' comment
        $eventType == 'S' ? ($evalResults = $this->EvaluationSimple->getResultsByEvaluatee($grpEventId, $evaluateeId, true)) &&
            ($evalType = 'EvaluationSimple') &&
            ($commentType = 'comment')
            :
            ($evalResults = $this->EvaluationRubric->getResultsByEvaluatee($grpEventId, $evaluateeId, true)) &&
            ($evalType = 'EvaluationRubric') &&
            ($commentType = 'comment');
        $yRowPosition = 2;
        for ($i=0; $i<count($groupMembers); $i++) {
            $evaluator = $groupMembers[$i];
            // Insert evaluator rows, we can utilize the format evaluatee header function with some modifications
            $evaluatorRow = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluator);
            array_shift($evaluatorRow);
            array_pop($evaluatorRow);
            // Push if only evaluator has submitted an evaluation for the current evaluatee
            if (!empty($evalResults[$i][$evalType][$commentType])) {
                array_push($evaluatorRow, $evalResults[$i][$evalType][$commentType]);
            }
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 1, $yRowPosition, $evaluatorRow);
            $yRowPosition++;
        }
        return $grid;
    }


    /**
     * _buildMixevalResultByEvaluatee
     *
     * @param mixed $params      params
     * @param mixed $grpEventId  group event id
     * @param mixed $evaluateeId evaluatee id
     *
     * @access protected
     * @return void
     */
    function _buildMixevalResultByEvaluatee($params, $grpEventId, $evaluateeId)
    {
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->User = ClassRegistry::init('User');

        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $questions = $this->ExportHelper2->getEvaluationQuestions($grpEventId);

        $xRange = count($groupMembers) + 7;
        $yRange = count($questions) + 10;
        $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);

        $evaluatee = $this->User->findUserByidWithFields($evaluateeId);
        $evaluateeHeader = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee);

        $yPosition = 2; $xPosition = 1;
        $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, 0, $evaluateeHeader);
        $grid[$xPosition][1] = "######################################################";

        $evaluatorHeaderArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembers);
        if (!empty($params['include_student_name'])) {
            $grid[$xPosition + 1][$yPosition] = "Evaluators:";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['name']);
        }
        if (!empty($params['include_student_id'])) {
            $yPosition++;
            $grid[$xPosition + 1][$yPosition] = "Evaluator's Student Id:";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['student_no']);
        }
        //if (!empty($params['include_student_email'])) {
        //    $yPosition++;
        //    $grid[$xPosition + 1][$yPosition] = "Evaluator's Email:";
        //    $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['email']);
        //}

        $grid[$xPosition + count($groupMembers) + 4][$yPosition + 1] = "Question Avg Mark";
        $rowNum = 7; $finalMark = 0;
        foreach ($questions as $q) {
            $totalScore = 0;
            $row = array();
            $ques = array();
            $submissionCount = 0;
            array_push($ques, $q['MixevalQuestion']['title'].' (/'.$q['MixevalQuestion']['multiplier'].')'.',');
            foreach ($groupMembers as $evaluator) {
                $evalResult = $this->EvaluationMixeval->getResultDetailByQuestion($grpEventId, $evaluatee['id'],
                    $evaluator['id'], $q['MixevalQuestion']['question_num']-1);
                if (!empty($evalResult)) {
                    $submissionCount++;
                    $result = $evalResult['EvaluationMixevalDetail']['grade'];
                } else {
                    $result = '';
                }
                array_push($row, $result);
                $totalScore += $result;
            }
            array_push($row, ' ');
            $submissionCount > 0 ? $questionAvg = $totalScore/$submissionCount :
                $questionAvg = 0;
            array_push($row, number_format($questionAvg, 2));
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 1, $yPosition+2, $ques);
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition+2, $row);
            $finalMark += $questionAvg;
            $rowNum++;
            $yPosition++;
        }

        if (!empty($params['include_final_marks'])) {
            $grid[$xPosition + count($groupMembers) + 3][$yPosition + 2] = "Final Mark";
            $grid[$xPosition + count($groupMembers) + 4][$yPosition + 2] = number_format($finalMark, 2);
            $submission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($grpEventId, $evaluatee);
            $due_date = strtotime($submission['Event']['due_date']);
            $event_end = strtotime($submission['Event']['release_date_end']);
            $sub_date = strtotime($submission['EvaluationSubmission']['date_submitted']);
            $group = $this->Group->find('first', array('conditions' => array('GroupEvent.id' => $grpEventId)));
            $eventId = $group['GroupEvent']['event_id'];
            $penalty = null;
            // no submission - if now is after release date end then - gets final deduction
            if (empty($submission)) {
                if (time() > $event_end) {
                    $penalty = $this->Penalty->getPenaltyFinal($eventId);
                }
            // there is submission - may be on time or late
            } else {
                $late_diff = $sub_date - $due_date;
                // late
                if (0 < $late_diff) {
                    $days_late = $late_diff/(24*60*60);
                    $penalty = $this->Penalty->getPenaltyByEventAndDaysLate($eventId, $days_late);
                }
            }
            $penaltyPercent  = $penalty['Penalty']['percent_penalty'];
            $penaltyPercent > 0 ?  $perPenalty =  '-'.$penaltyPercent.'%' : $perPenalty = '--';
            $final = number_format($finalMark * (1 - $penaltyPercent / 100), 2);

            $grid[$xPosition + count($groupMembers) + 3][$yPosition + 3] = "Penalty";
            $grid[$xPosition + count($groupMembers) + 4][$yPosition + 3] = $perPenalty;
            $grid[$xPosition + count($groupMembers) + 3][$yPosition + 4] = "Final Mark";
            $grid[$xPosition + count($groupMembers) + 4][$yPosition + 4] = $final;
        }
        return $grid;
    }


    /**
     * _buildRubricsResultByEvalatee
     *
     * @param mixed $params      params
     * @param mixed $grpEventId  group event id
     * @param mixed $evaluateeId evaluatee id
     *
     * @access protected
     * @return void
     */
    function _buildRubricsResultByEvalatee($params, $grpEventId, $evaluateeId)
    {
        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $questions = $this->ExportHelper2->getEvaluationQuestions($grpEventId);

        $xRange = count($groupMembers) + 7;
        $yRange = count($questions) + 10;
        $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);

        $evaluatee = $this->User->findUserByidWithFields($evaluateeId);
        $evaluateeHeader = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee);
        $yPosition = 2; $xPosition = 1;
        $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, 0, $evaluateeHeader);
        $grid[$xPosition][1] = "######################################################";

        $evaluatorHeaderArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembers);
        if (!empty($params['include_student_name'])) {
            $grid[$xPosition+1][$yPosition] = "Evaluators:";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['name']);
        }
        if (!empty($params['include_student_id'])) {
            $yPosition++;
            $grid[$xPosition + 1][$yPosition] = "Evaluator's Student Id:";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['student_no']);
        }
        //if (!empty($params['include_student_email'])) {
        //    $yPosition++;
        //    $grid[$xPosition + 1][$yPosition] = "Evaluator's Email:";
        //    $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['email']);
        //}
        $grid[$xPosition + count($groupMembers) + 4][$yPosition + 1] = "Question Avg Mark";
        $questionArray = array();
        // Insert in question column
        foreach ($questions as $q) {
            array_push($questionArray, $q['RubricsCriteria']['criteria']." ( /".$q['RubricsCriteria']['multiplier'].")");
        }
        $this->ExportHelper2->fillGridVertically($grid, $yPosition + 2, $xPosition + 1, $questionArray);
        $xPosition += 2;
        $questionTotalMarkArray = array_pad(array(), count($questions), 0);
        $countEvaluators = 0;
        foreach ($groupMembers as $evaluator) {
            $evalResult = $this->EvaluationRubric->getRubricsCriteriaResult($grpEventId, $evaluatee['id'], $evaluator['id']);
            $gradesArray = array();
            $questionNum = 0;
            if (!empty($evalResult)) {
                $countEvaluators++;
            }
            foreach ($evalResult as $result) {
                array_push($gradesArray, $result['EvaluationRubricDetail']['grade']);
                $questionTotalMarkArray[$questionNum] += $result['EvaluationRubricDetail']['grade'];
                $questionNum++;
            }
            $this->ExportHelper2->fillGridVertically($grid, $yPosition + 2, $xPosition + 1, $gradesArray);
            $xPosition++;
        }
        // Calculate question average
        $xPosition++; $finalMark = 0;

        foreach ($questionTotalMarkArray as $questionTotal) {
            $countEvaluators > 0 ? $questionAverage = number_format($questionTotal/$countEvaluators, 2) :
                $questionAverage = number_format(0, 2);
            $grid[$xPosition + 1][$yPosition + 2] = $questionAverage;
            $finalMark += $questionAverage;
            $yPosition++;
        }
        // Sum up final mark
        if (!empty($params['include_final_marks'])) {
            $grid[$xPosition][$yPosition + count($questions)] = "Raw Total";
            $grid[$xPosition + 1][$yPosition + count($questions)] = $finalMark;
            $submission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($grpEventId, $evaluatee);
            $due_date = strtotime($submission['Event']['due_date']);
            $event_end = strtotime($submission['Event']['release_date_end']);
            $sub_date = strtotime($submission['EvaluationSubmission']['date_submitted']);
            $group = $this->Group->find('first', array('conditions' => array('GroupEvent.id' => $grpEventId)));
            $eventId = $group['GroupEvent']['event_id'];
            $penalty = null;
            // no submission - if now is after release date end then - gets final deduction
            if (empty($submission)) {
                if (time() > $event_end) {
                    $penalty = $this->Penalty->getPenaltyFinal($eventId);
                }
            // there is submission - may be on time or late
            } else {
                $late_diff = $sub_date - $due_date;
                // late
                if (0 < $late_diff) {
                    $days_late = $late_diff/(24*60*60);
                    $penalty = $this->Penalty->getPenaltyByEventAndDaysLate($eventId, $days_late);
                }
            }
            $penaltyPercent  = $penalty['Penalty']['percent_penalty'];
            $penaltyPercent > 0 ?  $perPenalty =  '-'.$penaltyPercent.'%' : $perPenalty = '--';
            $final = number_format($finalMark * (1 - $penaltyPercent / 100), 2);

            $grid[$xPosition][$yPosition + count($questions) + 1] = "Penalty";
            $grid[$xPosition + 1][$yPosition + count($questions) + 1] = $perPenalty;
            $grid[$xPosition][$yPosition + count($questions) + 2] = "Final Mark";
            $grid[$xPosition + 1][$yPosition + count($questions) + 2] = $final;
        }
        return $grid;
    }


    /**
     * _buildMixEvalQuestionCommentTable
     *
     * @param mixed $params     params
     * @param mixed $grpEventId group event id
     *
     * @access protected
     * @return void
     */
    function _buildMixEvalQuestionCommentTable($params ,$grpEventId)
    {
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->Event = ClassRegistry::init('Event');

        $groupMembersNoTutors = $this->ExportHelper2->getGroupMemberWithoutTutorsHelper($grpEventId);
        $groupCount = count($groupMembersNoTutors);
        $grpEvent = $this->GroupEvent->getGrpEvent($grpEventId);
        $evaluation = $this->Event->getEventById($grpEvent['GroupEvent']['event_id']);
        $questions = $this->MixevalQuestion->getQuestion($evaluation['Event']['template_id'], '2');
        $validQuestionNum = array();
        foreach ($questions as $q) {
            array_push($validQuestionNum, $q['MixevalQuestion']['question_num']-1);
        }
        $qCount = count($questions);
        // Create grid
        $sectionSpacing = 4 + $qCount * ($groupCount+1);
        $gridDimensionY = $sectionSpacing * $groupCount;
        $gridDimensionX = 8;
        $grid = $this->ExportHelper2->buildExporterGrid($gridDimensionX, $gridDimensionY);
        // Fill in the questions
        $questionNum = 1; $questionYPos = 2; $xPosition = 2;

        foreach ($questions as $q) {
            $this->ExportHelper2->repeatDrawByCoordinateVertical($grid, $xPosition, $questionYPos, $sectionSpacing, $groupCount,
                "Question ".$questionNum.": ".$q['MixevalQuestion']['title']);
            $questionNum++;
            $questionYPos += $groupCount+1;
        }
        // Fill in the comments
        $headerYPos = 0; $commentRowYPos = 2;
        foreach ($groupMembersNoTutors as $evaluatee) {
            // First fill in the evaluatee headers
            $evaluateeHeader = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee);
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition - 1, $headerYPos, $evaluateeHeader);

            // Now start filling in mixeval question comments
            $mixedResults = $this->EvaluationMixeval->getResultsDetailByEvaluatee($grpEventId, $evaluatee['id'], true);
            $tmpYPos = $commentRowYPos;
            $rCount = 0;
            foreach ($mixedResults as $evaluator) {
                // Only loop through question comment results
                if (!in_array($evaluator['EvaluationMixevalDetail']['question_number'], $validQuestionNum)) {
                    continue;
                }

                $evaluatorArray = array();
                if (!empty($params['include_student_name'])) {
                    array_push($evaluatorArray, $evaluator['User']['evaluator_last_name']);
                    array_push($evaluatorArray, $evaluator['User']['evaluator_first_name']);
                }
                if (!empty($params['include_student_id'])) {
                    array_push($evaluatorArray, $evaluator['User']['evaluator_student_no']);
                }
                for ($i = 0; $i <= $rCount; $i++) {
                    if ($evaluator['User']['evaluator_last_name'] == $grid[$xPosition][$tmpYPos-$i] && $evaluator['User']['evaluator_first_name'] == $grid[$xPosition+1][$tmpYPos-$i]) {
                        $tmpYPos += $groupCount-$i;
                        $rCount = 0;
                    }
                }
                $tmpYPos++;
                $rCount++;
                array_push($evaluatorArray, $evaluator['EvaluationMixevalDetail']['question_comment']);
                $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $tmpYPos, $evaluatorArray);
            }
            $commentRowYPos += $sectionSpacing;
            $headerYPos += $sectionSpacing;
        }
        return $grid;

    }


    /**
     * _setFontSizeVertically
     *
     * @param mixed $initY    init Y
     * @param mixed $endY     end Y
     * @param mixed $column   column
     * @param mixed $fontSize font size
     *
     * @access protected
     * @return void
     */
    function _setFontSizeVertically($initY, $endY, $column, $fontSize)
    {
        for ($y=$initY; $y<=$endY; $y++) {
            $this->sheet->getStyle($column.$y)->getFont()->setSize($fontSize);
        }
    }


    /**
     * createExcel
     *
     * @param mixed $params params
     * @param mixed $event  event
     *
     * @access public
     * @return void
     */
    function createExcel($params, $event)
    {
        // Prepare header.
        $header = $this->generateHeader2($params, $event, 'fsa');
        $this->_drawToExcelSheetAtCoordinates($header, $this->cursor['x'], $this->cursor['y']);
        $this->_setFontSizeVertically(1, count($header[0])-1, 'A', 16);
        $this->cursor['y'] += (count($header[0]));
        $groupEvents = $event['GroupEvent'];
        switch($event['Event']['event_template_type_id']){
        case 1 :
            // Simple Evaluation
            for ($i=0; $i<count($groupEvents); $i++) {
                $group = $this->Group->getGroupByGroupId($groupEvents[$i]['group_id']);
                $grpEventId = $groupEvents[$i]['id'];
                $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
                $groupMembersNoTutors = $this->ExportHelper2->getGroupMemberWithoutTutorsHelper($grpEventId);
                if (!empty($params['include_group_names'])) {
                    $this->cursor['y'] += 2;
                    $this->sheet->setCellValue('A'.$this->cursor['y'], "Group Name :  ".$group[0]['Group']['group_name']);
                    $this->sheet->getStyle('A'.$this->cursor['y'])->getFont()->setSize(14);
                    $this->cursor['y'] += 2;
                }
                if (!empty($params['include_grade_tables'])) {
                    $this->sheet->setCellValue('A'.$this->cursor['y'], "Simple Evaluation Grades Table");
                    $simpleResults = $this->_buildSimpleEvalResults($groupEvents[$i]['GroupEvent']['id'], $params);
                    $this->_drawToExcelSheetAtCoordinates($simpleResults, $this->cursor['x'], $this->cursor['y']);
                    $this->cursor['y'] += (count($simpleResults[0])+2);
                }
                if (!empty($params['include_comments'])) {
                    $this->sheet->setCellValue('A'.$this->cursor['y'], "Simple Evaluation Comments:");
                    $this->cursor['y']++;
                    //  	      	$CSV .= "Simple Evaluation Comments :\n\n";
                    foreach ($groupMembersNoTutors as $evaluatee) {
                        $simpleEvalComments = $this->_buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluatee['id'], $params, 'S');
                        $this->_drawToExcelSheetAtCoordinates($simpleEvalComments, $this->cursor['x'], $this->cursor['y']);
                        $this->cursor['y'] += (count($simpleEvalComments[0]) + 1);
                    }
                }
            }
            break;

            //Rubrics Evaluation Event
        case 2:
            for ($i=0; $i<count($groupEvents); $i++) {
                $grpEventId = $groupEvents[$i]['GroupEvent']['id'];
                $group = $this->Group->getGroupByGroupId($groupEvents[$i]['GroupEvent']['group_id']);
                $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
                $groupMembersNoTutors = $this->ExportHelper2->getGroupMemberWithoutTutorsHelper($grpEventId);
                if (!empty($params['include_group_names'])) {
                    $this->cursor['y'] += 2;
                    $this->sheet->setCellValue('A'.$this->cursor['y'], "Group Name :  ".$group[0]['Group']['group_name']);
                    $this->sheet->getStyle('A'.$this->cursor['y'])->getFont()->setSize(14);
                    $this->cursor['y'] += 2;
                }
                if (!empty($params['include_grade_tables'])) {
                    $this->sheet->setCellValue('A'.$this->cursor['y'], "Rubrics Evaluation Grade Table");
                    $this->cursor['y'] += 2;
                    foreach ($groupMembersNoTutors as $evaluatee) {
                        $gradeTable = $this->_buildRubricsResultByEvalatee($params, $grpEventId, $evaluatee['id']);
                        $this->_drawToExcelSheetAtCoordinates($gradeTable, 0, $this->cursor['y']);
                        $this->cursor['y'] += (count($gradeTable[0]));
                    }
                }
                if (!empty($params['include_comments'])) {
                    $this->sheet->setCellValue('A'.$this->cursor['y'], "Rubrics General Comments:");
                    $this->cursor['y'] += 2;
                    foreach ($groupMembersNoTutors as $evaluatee) {
                        $rubricGeneralComments = $this->_buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluatee['id'], $params, 'R');
                        $this->_drawToExcelSheetAtCoordinates($rubricGeneralComments, 0, $this->cursor['y']);
                        $this->cursor['y'] += (count($rubricGeneralComments[0]) + 1);
                    }
                }
            }
            //return $CSV;
            break;

            // Mixed Evaluation Event
        case 4 :
            for ($i=0; $i<count($groupEvents); $i++) {
                $grpEventId = $groupEvents[$i]['GroupEvent']['id'];
                $group = $this->Group->getGroupByGroupId($groupEvents[$i]['GroupEvent']['group_id']);
                $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
                $groupMembersNoTutors = $this->ExportHelper2->getGroupMemberWithoutTutorsHelper($grpEventId);
                if (!empty($params['include_group_names'])) {
                    $this->cursor['y'] += 2;
                    $this->sheet->setCellValue('A'.$this->cursor['y'], "Group Name :  ".$group[0]['Group']['group_name']);
                    $this->sheet->getStyle('A'.$this->cursor['y'])->getFont()->setSize(14);
                    $this->cursor['y'] += 2;
                }
                if (!empty($params['include_grade_tables'])) {
                    $this->sheet->setCellValue('A'.$this->cursor['y'], "Mixed Evaluation Grade Table");
                    $this->cursor['y'] += 2;
                    foreach ($groupMembersNoTutors as $evaluatee) {
                        $gradeTable = $this->_buildMixevalResultByEvaluatee($params, $grpEventId, $evaluatee['id']);
                        $this->_drawToExcelSheetAtCoordinates($gradeTable, 0, $this->cursor['y']);
                        $this->cursor['y'] += (count($gradeTable[0]));
                    }
                    $this->cursor['y'] += 2;
                }
                if (!empty($params['include_comments'])) {
                    $this->sheet->setCellValue('A'.$this->cursor['y'], "Mixed Evaluation Comments:");
                    $this->cursor['y'] += 2;
                    $questionComments = $this->_buildMixEvalQuestionCommentTable($params, $grpEventId);
                    $this->_drawToExcelSheetAtCoordinates($questionComments, 0, $this->cursor['y']);
                    $this->cursor['y'] += (count($questionComments[0]) + 1);
                }
            }
            break;

        default:
            throw new Exception("Event id input seems to be invalid!");
        }
        //return $CSV;
        $this->_output($params['file_name']);
    }
}
