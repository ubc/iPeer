<?php
/**
 * ExportBaseNewComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class ExportBaseNewComponent extends CakeObject
{
    public $eventType = array('1' => 'Simple Evaluation', '2' => 'Rubrics Evaluation', '4' => 'Mixed Evaluation');
    public $detailModel = array(1 => false, 2 => 'EvaluationRubricDetail', 4 => 'EvaluationMixevalDetail');
    public $components = array('ExportHelper2', 'Penalty');
    public $responseModelName = null;
    public $responseModel = null;
    public $evaluationModelName = null;
    public $evaluationModel = null;

    /**
     * generateHeader2
     *
     * @param mixed $params params
     * @param mixed $event  event
     * @param mixed $type   type
     *
     * @access public
     * @return void
     */
    function generateHeader2($params, $event, $type)
    {

        $grid = $this->ExportHelper2->buildExporterGrid(8, 8);
        $grid[0][0] = "********************************************";
        $yIndex = 1;

        if (!empty($params['include_course'])) {
            $grid[0][$yIndex] = "Course Name : ,,".$event['Course']['title'];
        }
        if (!empty($params['include_eval_event_names'])) {
            $yIndex++;
            $grid[0][$yIndex] = "Event : ,,".$event['Event']['title'];
        }
        if (!empty($params['include_eval_event_type'])) {
            $yIndex++;
            $grid[0][$yIndex] = "Evaluation Type : ,,".$this->eventType[$event['Event']['event_template_type_id']];
        }
        if (!empty($params['include_instructors'])) {
            $yIndex++;
            $instructors = array();
            foreach ($event['Course']['Instructor'] as $instructor) {
                array_push($instructors, $instructor['full_name']);
            }
            if (!empty($instructors)) {
                $listInstructors = implode(", ", $instructors);
            } else {
                $listInstructors = $instructors;
            }
            $grid[0][$yIndex] = "Instructors : ,,".$listInstructors;
        }
        if (!empty($params['include_date'])) {
            $yIndex += 2;
            $grid[0][$yIndex] = "Date : ,,".date("F j Y g:i a");
        }
        $yIndex++;
        $grid[0][$yIndex] = "********************************************";
        if ($type == 'CSV') {
            return $this->ExportHelper2->arrayDraw($grid);
        } else {
            for ($y=1; $y<count($grid); $y++) {
                $grid[0][$y] = str_replace(array(","), "", $grid[0][$y]);
            }
            return $grid;
        }
    }

    /**
     * buildEvaluationScoreTableByGroup
     *
     * @param mixed $params     params
     * @param mixed $groupEvent group event
     * @param mixed $event      event
     * @param mixed $results    results
     * @param mixed $peerEval   boolean for self/peer eval
     *
     * @access public
     * @return void
     */
    function buildEvaluationScoreTableByGroup($params, $groupEvent, $event, $results, $peerEval)
    {
        $this->Group = ClassRegistry::init('Group');
        $this->User = ClassRegistry::init('User');

        $group = $this->Group->getGroupWithMemberRoleByGroupIdEventId($groupEvent['group_id'], $event['Event']['id']);
        $dropped = $this->User->getDroppedStudentsWithRole($this->responseModelName, $results, $group);

        $grid = array();
        $subDate = Set::combine($results, '{n}.EvaluationSubmission.submitter_id', '{n}.EvaluationSubmission.date_submitted');
        $responsesByEvaluatee = Set::combine($results, '{n}.'.$this->responseModelName.'.evaluator', '{n}', '{n}.'.$this->responseModelName.'.evaluatee');
        $group['Member'] = array_merge($group['Member'], $dropped);
        foreach ($group['Member'] as $member) {
            $grid = array_merge($grid, $this->buildScoreTableByEvaluatee($params, $group, $member, $event, $responsesByEvaluatee, $subDate, $peerEval));
        }

        return $grid;
    }


    /**
     * buildEvaluationScoreTableByEvent
     *
     * @param mixed $params   params
     * @param mixed $event    event
     * @param mixed $results  results
     * @param mixed $peerEval boolean for self/peer eval
     *
     * @access public
     * @return void
     */
    function buildEvaluationScoreTableByEvent($params, $event, $results, $peerEval = 1)
    {
        $grid = array();
        if (empty($event['GroupEvent'])) {
            return $grid;
        }
        foreach ($event['GroupEvent'] as $ge) {
            if (isset($results[$ge['id']])) {
                $grid = array_merge($grid, $this->buildEvaluationScoreTableByGroup($params, $ge, $event, $results[$ge['id']], $peerEval));
            }
        }
        return $grid;
    }


    /**
     * buildScoreTableByEvaluatee
     *
     * @param mixed $params    params
     * @param mixed $group     group
     * @param mixed $evaluatee evaluatee
     * @param mixed $event     event
     * @param mixed $responses responses
     * @param mixed $subDate   submission dates
     * @param mixed $peerEval  boolean for self/peer eval
     *
     * @access public
     * @return void
     */
    function buildScoreTableByEvaluatee($params, $group, $evaluatee, $event, $responses, $subDate, $peerEval)
    {
        // Build grid
        //$xPosition = 0;
        //$yPosition = 0;
        // Fill in grid Results
        $yInc = 0;

        $xDimension = $this->calcDimensionX($params, $event);
        //$yDimensions = count($group['Member']);
        $grid = array();

        foreach ($group['Member'] as $evaluator) {
            if (!$peerEval && $evaluator['id'] != $evaluatee['id']) {
                continue; // skip peer evaluations for self-evaluation section
            }

            if (!$event['Event']['self_eval'] && $evaluator['id'] == $evaluatee['id']) {
                continue; // skip self-eval when self-evaluation is not set in event
            }

            //TODO: change the condition to not depend on Role's name, which can change
            if ($evaluatee['Role']['name'] == 'tutor') {
                continue; // skip evaluations where the tutor is the evaluatee
            }

            $row = array();
            if ($params['include']['course']) {
                array_push($row, $event['Course']['course']);
            }
            if ($params['include']['eval_event_names']) {
                array_push($row, $event['Event']['title']);
            }
            if ($peerEval && $params['include']['eval_event_type']) {
                array_push($row, $this->eventType[$event['Event']['event_template_type_id']]);
            }
            if ($params['include']['group_names']) {
                array_push($row, $group['Group']['group_name']);
            }
            /*if ($params['include']['student_email']) {
                array_push($row, $evaluatee['email']);
            }*/
            if ($params['include']['student_name']) {
                $dropped = isset($evaluatee['GroupsMember']) ? '' : '*';
                array_push($row, $dropped.$evaluatee['full_name']);
            }
            if ($params['include']['student_id']) {
                array_push($row, $evaluatee['student_no']);
            }
            if ($peerEval && $params['include']['student_name']) {
                $dropped = isset($evaluator['GroupsMember']) ? '' : '*';
                array_push($row, $dropped.$evaluator['full_name']);
            }
            if ($peerEval && $params['include']['student_id']) {
                array_push($row, $evaluator['student_no']);
            }

            /* Export all determines whether or not to export all results
            including those that don't have values. */
            if (!isset($responses[$evaluatee['id']]) || !array_key_exists($evaluator['id'], $responses[$evaluatee['id']])) {
                if ($params['include']['export_all']) {
                    $row += array_fill(count($row), $xDimension - count($row), '');
                    $grid[] = $row;
                    $yInc++;
                }
                continue;
            }

            $response = $responses[$evaluatee['id']][$evaluator['id']];
                        
            if ($event['Event']['event_template_type_id'] == 2) {
                foreach ($response[$this->detailModel[$event['Event']['event_template_type_id']]] as $result) {
                    array_push($row, $result['criteria_comment']);
                }
            }

            // comments for Rubric and Simple Evaluation
            if ($event['Event']['event_template_type_id'] != 4 && $params['include']['comments']) {
                array_push($row, $response[$this->responseModelName]['comment']);
            }

            if ($this->detailModel[$event['Event']['event_template_type_id']] && array_key_exists($this->detailModel[$event['Event']['event_template_type_id']], $response)) {
                if (in_array($event['Event']['event_template_type_id'], array(1, 2))) {
                    if ($params['include']['grade_tables']) {
                        foreach ($response[$this->detailModel[$event['Event']['event_template_type_id']]] as $result) {
                            array_push($row, $result['grade']);
                        }
                    }
                } else {
                    // mixed evaluation
                    $results = $response[$this->detailModel[$event['Event']['event_template_type_id']]];
                    $results = Set::combine($results, '{n}.question_number', '{n}');
                    
                    $missing_required_question = false;
                    
                    foreach ($event['Question'] as $question) {
                        if ($question['self_eval'] == $peerEval) {
                            continue; // skip questions that don't belong in the desired section
                        }
                        if ($question['required'] && !isset($results[$question['question_num']])) {
                            $missing_required_question = true;
                        }
                    }
                    
                    if ($missing_required_question) {
                        //skip the rest of output
                        $grid[] = $row;
                        $yInc++;
                        continue;
                    }
                    
                    foreach ($event['Question'] as $question) {
                        if ($question['self_eval'] == $peerEval) {
                            continue; // skip questions that don't belong in the desired section
                        }
                        if (!isset($results[$question['question_num']])) {
                            array_push($row, '');
                        } elseif ($params['include']['grade_tables'] && in_array($question['mixeval_question_type_id'], array(1, 4))) {
                            array_push($row, $results[$question['question_num']]['grade']);
                        } elseif ($params['include']['comments'] && in_array($question['mixeval_question_type_id'], array(2, 3))) {
                            array_push($row, $results[$question['question_num']]['question_comment']);
                        } else {
                            array_push($row, 'N/A');
                        }
                    }
                }
            }

            if (!$peerEval) {
                $grid[] = $row;
                $yInc++;
                continue; // skip final marks/penalty for self-evaluation
            }

            array_push($row, $response[$this->responseModelName]['score']);
            $date = isset($subDate[$evaluatee['id']]) ? $subDate[$evaluatee['id']] : false;

            $penalty = $this->Penalty->calculate(
                $event['Event']['due_date'],
                $event['Event']['release_date_end'],
                $date,
                $event['Penalty']
            );

            if (is_numeric($penalty)) {
                array_push($row, $penalty."%");
                $finalGrade = $response[$this->responseModelName]['score'] * (1 - ($penalty/100));
            } else {
                array_push($row, "-");
                $finalGrade = $response[$this->responseModelName]['score'];
            }

            if ($params['include']['final_marks']) {
                array_push($row, $finalGrade);
            }
            $grid[] = $row;
            $yInc++;
        }

        return $grid;
    }

    /**
     * calcDimensionX
     *
     * @param mixed $params params
     * @param mixed $event  event
     *
     * @access public
     * @return void
     */
    public function calcDimensionX($params, $event) {
        $total = 2 + count($params['include']);
        if (4 == $event['Event']['event_template_type_id']) {
            $commentQuestions = Set::extract($event, '/Question[mixeval_question_type_id=2]');
            if (isset($params['include']['grade_tables'])) {
                // question number - 1 as one is counted as grade_tables in include
                $total += count($event['Question']) - count($commentQuestions) - 1;
            }
            if (isset($params['include']['comments'])) {
                // question number - 1 as one is counted as comments in include
                $total += count($commentQuestions) - 1;
            }
        } elseif (2 == $event['Event']['event_template_type_id']) {
            if (isset($params['include']['grade_tables'])) {
                // question number - 1 as one is counted as grade_tables in include
                $total += count($event['Question']) - 1;
            }
        } else {
            if (isset($params['include']['grade_tables'])) {
                $total--;
            }
        }

        if (isset($params['include']['student_name'])) {
            $total++;
        }
        if (isset($params['include']['student_id'])) {
            $total++;
        }

        return $total;
    }

    /*
     * UNDER CONSTRUCTION !!!
     function buildMixEvalCommentTableByEvaluatee($params, $grpEventId, $evaluateeId)
     {
         $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
         $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
         $this->GroupEvent = ClassRegistry::init('GroupEvent');
         $this->Event = ClassRegistry::init('Event');

         $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
         $questions = $this->MixevalQuestion->getQuestion($evaluation['Event']['template_id'], 'T');
         // Create grid
         $gridYDim = count($questions)*(count($groupMembers) + 1);
         $gridXDim = 8;
         $grid = $this->ExportHelper2->buildExporterGrid($gridDimensionX, $gridDimensionY);
         $xPosition = 0; $yPosition = 0;
         // Fill in questions
         $qCount = 1;
         $qSpacing = count($groupMembers) + 1;
         $qIndexing = 0;
         foreach ($question as $q) {
             $grid[$xPosition + 2][$yPosition + $qIndexing + 2] = "Question ".$qCount.":".$q['MixevalQuestion']['title'];
             $qIndexing += $qSpacing;
}
// Save question_comment's quesion num; only way to identify question comments via question num
$validQuestionNum = array();
foreach ($questions as $q) {
    array_push($validQuestionNum, $q['MixevalQuestion']['question_num']);
}
// Setup evaluator's info
$grpMembersBlock = $this->ExportHelper2->createGroupMemberArrayBlock($groupMembers, $params);
$questionInitialYPos = 3;
for ($inc=0; $inc<$count($groupMembers); $inc++) {
    $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 2, $questionInitialYPos + $i, $grpMembersBlock[$i]);
}

}
     */

    /**
     * buildMixEvalQuestionCommentTable
     *
     * @param mixed $params     params
     * @param mixed $grpEventId group event id
     *
     * @access public
     * @return void
     */
    function buildMixEvalQuestionCommentTable($params ,$grpEventId)
    {
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->Event = ClassRegistry::init('Event');

        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $groupCount = count($groupMembers);
        $grpEvent = $this->GroupEvent->getGrpEvent($grpEventId);
        $evaluation = $this->Event->getEventById($grpEvent['GroupEvent']['event_id']);
        $questions = $this->MixevalQuestion->getQuestion($evaluation['Event']['template_id'], '2');
        $validQuestionNum = array();
        foreach ($questions as $q) {
            array_push($validQuestionNum, $q['MixevalQuestion']['question_num']);
        }
        $qCount = count($questions);
        // Create grid
        $sectionSpacing = 4 + $qCount * ($groupCount+1);
        $gridDimensionY = $sectionSpacing * $groupCount;
        $gridDimensionX = 8;
        $grid = $this->ExportHelper2->buildExporterGrid($gridDimensionX, $gridDimensionY);
        // Fill in the questions
        $questionNum = 1; $questionYPos = 2; $xPosition = 2;
        $submissionCount = $this->EvaluationSubmission->countSubmissions($grpEventId);
        foreach ($questions as $q) {
            $this->ExportHelper2->repeatDrawByCoordinateVertical($grid, $xPosition, $questionYPos, $sectionSpacing, $groupCount,
                "Question ".$questionNum.": ".$q['MixevalQuestion']['title']);
            $questionNum++;
            $questionYPos += $submissionCount + 1;
        }
        // Fill in the comments
        $headerYPos = 0; $commentRowYPos = 3;
        foreach ($groupMembers as $evaluatee) {
            // First fill in the evaluatee headers
            $evaluateeHeader = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee);
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition - 1, $headerYPos, $evaluateeHeader);

            // Now start filling in mixeval question comments
            $mixedResults = $this->EvaluationMixeval->getResultsDetailByEvaluatee($grpEventId, $evaluatee['id'], true);
            $tmpYPos = $commentRowYPos;
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
                array_push($evaluatorArray, $evaluator['EvaluationMixevalDetail']['question_comment']);
                empty($grid[$xPosition][$tmpYPos]) ? $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $tmpYPos, $evaluatorArray) :
                    $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $tmpYPos+1, $evaluatorArray);
                $tmpYPos++;
            }
            $commentRowYPos += $sectionSpacing;
            $headerYPos += $sectionSpacing;
        }
        $csv = $this->ExportHelper2->arrayDraw($grid);
        return $csv;
    }


    /**
     * buildRubricsResultByEvalatee
     *
     * @param mixed $params      params
     * @param mixed $grpEventId  group event id
     * @param mixed $evaluateeId evaluatee id
     *
     * @access public
     * @return void
     */
    function buildRubricsResultByEvalatee($params, $grpEventId, $evaluateeId)
    {
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
        $this->User = ClassRegistry::init('User');

        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $questions = $this->ExportHelper2->getEvaluationQuestions($grpEventId);

        $xRange = count($groupMembers) + 7;
        $yRange = count($questions) + 8;
        $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);

        $evaluatee = $this->User->findUserByidWithFields($evaluateeId);
        $evaluateeHeader = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee);
        $yPosition = 2; $xPosition = 1;
        $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, 0, $evaluateeHeader);
        $grid[$xPosition][1] = "######################################################";

        $evaluatorHeaderArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembers);
        if (!empty($params['include_student_name'])) {
            $grid[$xPosition+1][$yPosition] = "Evaluators :";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['name']);
        }
        if (!empty($params['include_student_id'])) {
            $yPosition++;
            $grid[$xPosition + 1][$yPosition] = "Evaluator's Student Id :";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['student_no']);
        }
        if (!empty($params['include_student_email'])) {
            $yPosition++;
            $grid[$xPosition + 1][$yPosition] = "Evaluator's Email :";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['email']);
        }
        $grid[$xPosition + 7][$yPosition + 1] = "Question Avg Mark";
        $questionArray = array();
        // Insert in question column
        foreach ($questions as $q) {
            array_push($questionArray, $q['RubricsCriteria']['criteria']." ( /".$q['RubricsCriteria']['multiplier'].")");
        }
        $this->ExportHelper2->fillGridVertically($grid, $yPosition + 2, $xPosition + 1, $questionArray);
        $xPosition += 2;
        $questionTotalMarkArray = array_pad(array(), count($questions), 0);
        foreach ($groupMembers as $evaluator) {
            $evalResult = $this->EvaluationRubric->getRubricsCriteriaResult($grpEventId, $evaluatee['id'], $evaluator['id']);
            $gradesArray = array();
            $questionNum = 0;
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
        $submissionCount = $this->EvaluationSubmission->countSubmissions($grpEventId);
        foreach ($questionTotalMarkArray as $questionTotal) {
            $questionAverage = $questionTotal/count($submissionCount);
            $grid[$xPosition + 1][$yPosition + 2] = $questionAverage;
            $finalMark += $questionAverage;
            $yPosition++;
        }
        // Sum up final mark
        if (!empty($params['include_final_marks'])) {
            $grid[$xPosition][$yPosition + count($questions)] = "Total";
            $grid[$xPosition + 1][$yPosition + count($questions)] = $finalMark;
        }
        return $this->ExportHelper2->arrayDraw($grid);
    }


    /**
     * buildMixevalResultByEvaluatee
     *
     * @param mixed $params      params
     * @param mixed $grpEventId  group event id
     * @param mixed $evaluateeId evaluatee id
     *
     * @access public
     * @return void
     */
    function buildMixevalResultByEvaluatee($params, $grpEventId, $evaluateeId)
    {
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
        $this->User = ClassRegistry::init('User');

        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $questions = $this->ExportHelper2->getEvaluationQuestions($grpEventId);

        $xRange = count($groupMembers) + 7;
        $yRange = count($questions) + 8;
        $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);

        $evaluatee = $this->User->findUserByidWithFields($evaluateeId);
        $evaluateeHeader = $this->ExportHelper2->formatEvaluateeHeaderArray($params, $evaluatee);

        $yPosition = 2; $xPosition = 1;
        $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, 0, $evaluateeHeader);
        $grid[$xPosition][1] = "######################################################";

        $evaluatorHeaderArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembers);
        if (!empty($params['include_student_name'])) {
            $grid[$xPosition + 1][$yPosition] = "Evaluators :";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['name']);
        }
        if (!empty($params['include_student_id'])) {
            $yPosition++;
            $grid[$xPosition + 1][$yPosition] = "Evaluator's Student Id :";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['student_no']);
        }
        if (!empty($params['include_student_email'])) {
            $yPosition++;
            $grid[$xPosition + 1][$yPosition] = "Evaluator's Email :";
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 3, $yPosition, $evaluatorHeaderArray['email']);
        }

        $grid[$xPosition + count($groupMembers) + 4][$yPosition + 1] = "Question Avg Mark";
        $rowNum = 7; $finalMark = 0;
        foreach ($questions as $q) {
            $totalScore = 0;
            $row = array();
            array_push($row, $q['MixevalQuestion']['title'].' (/'.$q['MixevalQuestion']['multiplier'].')'.',');
            foreach ($groupMembers as $evaluator) {
                $evalResult = $this->EvaluationMixeval->getResultDetailByQuestion($grpEventId, $evaluatee['id'],
                    $evaluator['id'], $q['MixevalQuestion']['question_num']-1);
                array_push($row, $evalResult['EvaluationMixevalDetail']['grade']);
                $totalScore += $evalResult['EvaluationMixevalDetail']['grade'];
            }
            array_push($row, ' ');
            $sumbissionCount = $this->EvaluationSubmission->countSubmissions($grpEventId);
            $questionAvg = $totalScore/$sumbissionCount;
            array_push($row, $questionAvg);
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition + 1, $yPosition+2, $row);
            $finalMark += $questionAvg;
            $rowNum++;
            $yPosition++;
        }

        if (!empty($params['include_final_marks'])) {
            $grid[$xPosition + count($groupMembers) + 3][$yPosition + 2] = "Final Mark";
            $grid[$xPosition + count($groupMembers) + 4][$yPosition + 2] = $finalMark;
        }
        return $this->ExportHelper2->arrayDraw($grid);
    }


    /**
     * buildMixevalResult
     *
     * @param mixed $params     params
     * @param mixed $grpEventId group event id
     *
     * @access public
     * @return void
     */
    function buildMixevalResult($params, $grpEventId)
    {
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');

        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $resultCSV = '';
        foreach ($groupMembers as $evaluatee) {
            $resultCSV .= $this->buildMixevalResultByEvaluatee($params, $grpEventId, $evaluatee['id']);
            $resultCSV .= "\n";
        }
        return $resultCSV;
    }


    /**
     * buildRubricsResultTable
     *
     * @param mixed $params     params
     * @param mixed $grpEventId group event id
     *
     * @access public
     * @return void
     */
    function buildRubricsResultTable($params, $grpEventId)
    {
        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $resultCSV = '';
        foreach ($groupMembers as $evaluatee) {
            $resultCSV .= $this->buildRubricsResultByEvalatee($params, $grpEventId, $evaluatee['id']);
            $resultCSV .= "\n";
        }
        return $resultCSV;
    }


    /**
     * buildSimpleEvalResults
     *
     * @param mixed $grpEventId group event id
     * @param mixed $params     params
     *
     * @access public
     * @return void
     */
    function buildSimpleEvalResults($grpEventId, $params)
    {
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');

        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        // Build grid
        $xRange = 9 + count($groupMembers);
        $yRange = 6 + count($groupMembers);
        $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);
        $grid[0][1] = "Simple Evaluation Grades Table :";
        $evaluatorsArray = $this->ExportHelper2->formatEvaluatorsHeaderArray($groupMembers);
        $xPosition = 0;
        // Fill in Evaluatee Rows
        if (!empty($params['include_student_email'])) {
            $this->ExportHelper2->fillGridVertically($grid, 6, $xPosition, $evaluatorsArray['email']);
        }
        if (!empty($params['include_student_id'])) {
            $xPosition++;
            $this->ExportHelper2->fillGridVertically($grid, 6, $xPosition, $evaluatorsArray['student_no']);
        }
        if (!empty($params['include_student_name'])) {
            $xPosition++;
            $this->ExportHelper2->fillGridVertically($grid, 6, $xPosition, $evaluatorsArray['name']);
        }

        $xPosition ++;
        $yPosition = 6;
        // Fill in score table
        $grid[$xPosition + count($groupMembers) + 1][$yPosition - 1] = "Evaluatee Ave Score";
        foreach ($groupMembers as $evaluatee) {
            $evalResults = $this->EvaluationSimple->getResultsByEvaluatee($grpEventId, $evaluatee['id'], true);
            // Format marks input array
            $resultRowArray = array();
            $totalScore = 0;
            foreach ($evalResults as $evaluator) {
                $totalScore += $evaluator['EvaluationSimple']['score'];
                array_push($resultRowArray, $evaluator['EvaluationSimple']['score']);
            }
            // Insert Evaluatee Average Mark; if neccessary.
            $this->ExportHelper2->fillGridHorizonally($grid, $xPosition, $yPosition, $resultRowArray);
            if (!empty($params['include_final_marks'])) {
                $submissionCount = $this->EvaluationSubmission->countSubmissions($grpEventId);
                $grid[$xPosition + count($groupMembers) + 1][$yPosition] = $totalScore/$submissionCount;
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
        return $this->ExportHelper2->arrayDraw($grid);
    }


    /**
     * buildSimpleOrRubricsCommentByEvaluatee
     *
     * @param mixed $grpEventId  group event id
     * @param mixed $evaluateeId evaluatee id
     * @param mixed $params      params
     * @param bool  $eventType   event type
     *
     * @access public
     * @return void
     */
    function buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluateeId, $params, $eventType=null)
    {
        $this->User = ClassRegistry::init('User');
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');

        $groupMembers = $this->ExportHelper2->getGroupMemberHelper($grpEventId);
        $evaluatee = $this->User->findById($evaluateeId);
        // Build Grid
        $xRange = 9;
        $yRange = count($groupMembers) + 2;
        $grid = $this->ExportHelper2->buildExporterGrid($xRange, $yRange);
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
        return $this->ExportHelper2->arrayDraw($grid);
    }

}
