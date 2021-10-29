<?php
/**
 * ExportCsvComponent
 *
 * @uses ExportBaseNewComponent
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
Class ExportCsvComponent extends ExportBaseNewComponent
{
    /**
     * buildGroupExportCsvByGroup
     *
     * @param mixed $grp     group fields
     * @param mixed $user    user fields
     * @param mixed $groupId group id
     *
     * @access public
     * @return void
     */
    function buildGroupExportCsvByGroup($grp, $user, $groupId)
    {
        $this->Group = ClassRegistry::init('Group');
      
        $group = $this->Group->find('first', array(
            'conditions' => array('Group.id' => $groupId),
            'fields' => $grp,
            'contain' => array('Member' => array('fields' => $user)),
        ));
        $usr = (empty($user)) ? true : false;
        unset($group['Group']['id']);
        $export = array();
        foreach ($group['Member'] as $member) {
            unset($member['GroupsMember']);
            if ($usr) {
                $member = array();
            }
            if ($grp) {
                $member = $group['Group'] + $member;
            }
            $export[] = implode(',', $member);
        }

        return $export;
    }


    /**
     * createCsvSubHeader
     *
     * @param mixed $params    params
     * @param mixed $questions questions
     * @param mixed $evalType  evaluation type
     *
     * @access public
     * @return void
     */
    /*function createCsvSubHeader($params, $questions, $evalType)
    {
    }*/


    /**
     * createCsv
     *
     * @param mixed $params params
     * @param mixed $event  event
     *
     * @access public
     * @return void
     */
    function createCsv($params, $event)
    {
        $grid = array();
        $groupEvents = $event['GroupEvent'];
        $groupEventIds = Set::extract($groupEvents, '/id');
        $this->responseModelName = EvaluationResponseBase::$types[$event['Event']['event_template_type_id']];
        $this->responseModel = ClassRegistry::init($this->responseModelName);
        $this->evaluationModelName = EvaluationBase::$types[$event['Event']['event_template_type_id']];
        $this->evaluationModel = ClassRegistry::init($this->evaluationModelName);
        $results = $this->responseModel->getSubmittedResultsByGroupEvent($groupEventIds, true, $this->detailModel[$event['Event']['event_template_type_id']]);
        $results = Set::combine($results, '{n}.'.$this->responseModelName.'.id', '{n}', '{n}.'.$this->responseModelName.'.grp_event_id');
        $evaluation = $this->evaluationModel->getEvaluation($event['Event']['template_id']);
        unset($evaluation['Event']);
        if (isset($evaluation['MixevalQuestion'])) {
            $evaluation['Question'] = $evaluation['MixevalQuestion'];
            unset($evaluation['MixevalQuestion']);
        }
        // if it is a mixed evaluation
        if ($event['Event']['event_template_type_id'] == 4) {
            $this->Mixeval = ClassRegistry::init('Mixeval');
            $mixeval = $this->Mixeval->findById($event['Event']['template_id']);
        }
        $peerEval = isset($mixeval) ? $mixeval['Mixeval']['peer_question'] : true;
        $selfEval = isset($mixeval) ? $mixeval['Mixeval']['self_eval'] && $event['Event']['self_eval'] : false;
        $event = array_merge($event, $evaluation);

        $grid = array();
        if ($peerEval) {
            $grid[] = $this->generateHeader($params, $event);
            $table = $this->buildEvaluationScoreTableByEvent($params, $event, $results);
            $grid = array_merge($grid, $table);
            if ($selfEval) {
                $grid[] = array(); // newline
            }
        }
        if ($selfEval) {
            $selfHeader = $this->generateHeader($params, $event, 0);
            $selfTable = $this->buildEvaluationScoreTableByEvent($params, $event, $results, 0);
            $grid[] = array('Self-Evaluation');
            $grid[] = $selfHeader;
            $grid = array_merge($grid, $selfTable);
        }

        $this->render($grid);
    }

    /**
     * generateHeader
     *
     * @param mixed $params   params
     * @param mixed $event    event
     * @param mixed $peerEval boolean for self/peer eval
     *
     * @access public
     * @return void
     */
    function generateHeader($params, $event, $peerEval = 1)
    {
        $header = array();

        if ($params['include']['course']) {
            $header[] = "Course Name";
        }
        if ($params['include']['eval_event_names']) {
            $header[] = "Event";
        }
        if ($peerEval && $params['include']['eval_event_type']) {
            $header[] = "Evaluation Type";
        }

        if ($params['include']['group_names']) {
            $header[] = "Group Name";
        }
        /*if ($params['include']['student_email']) {
            $header[] = "Email";
        }*/
        if ($params['include']['student_name']) {
            $header[] = "Evaluatee";
        }
        if ($params['include']['student_id']) {
            $header[] = "Evaluatee S#";
        }
        if ($peerEval && $params['include']['student_name']) {
            $header[] = "Evaluator";
        }
        if ($peerEval && $params['include']['student_id']) {
            $header[] = "Evaluator S#";
        }

        // add columns for individual criterion comments (Rubrics only)
        if (isset($event['Rubric'])) {
        	foreach ($event['Question'] as $key => $question) {
        		$header[] = "Q".($key+1)." Comment";
        	}
        }

        // comment header
        if ($event['Event']['event_template_type_id'] != 4 && $params['include']['comments']) {
            $header[] = 'Comment';
        }

        $num = 1;
        foreach ($event['Question'] as $key => $question) {
            if (isset($question['mixeval_question_type_id'])) {
                if ($question['self_eval']) {
                    continue; // skip self-evaluation questions
                }
                if (in_array($question['mixeval_question_type_id'], array(1, 4))) {
                   if ($peerEval && $params['include']['question_title']) {
                     $header[] = "Q".$num." ( /".$question['multiplier'].")"." ".$question['title'];
                   } else {
                     $header[] = "Q" . $num . " ( /" . $question['multiplier'] . ")";
                   }
                } else if (in_array($question['mixeval_question_type_id'], array(2, 3))){
                   if ($peerEval && $params['include']['question_title']) {
                     $header[] = "Q".$num." ".$question['title'];
                   } else {
                     $header[] = "Q".$num;
                   }
                }
                $num++;
            } else {
                if ($params['include']['grade_tables']) {
                    $header[] = "Q".($key+1)." ( /".$question['multiplier'].")";
                }
            }
        }
        
        if ($peerEval) {
            $header[] = "Raw Score";
            $header[] = "Late Penalty";

            if ($params['include']['final_marks']) {
                $header[] = "Final Score";
            }
        }

        return $header;
    }


    /**
     * checkAll
     *
     * @param mixed $params  params
     * @param mixed $eventId event id
     *
     * @access public
     * @return void
     */
    function checkAll($params, $eventId)
    {
        $this->Event = ClassRegistry::init('Event');
        $event = $this->Event->getEventById($eventId);
        $eventType = $event['Event']['event_template_type_id'];

        switch($eventType) {
            // Simple Evaluation
        case 1:
            if (!$this->checkSimpleEvaluationParams($params)) {
                return false;
            } else {
                return true;
            }
            break;
            //Rubrics Evaluation
        case 2:
            if (!$this->checkRubricsEvaluationParams($params)) {
                return false;
            } else {
                return true;
            }
            break;
            // Mixed Evaluation
        case 4:
            if (!$this->checkMixedEvaluationParams($params)) {
                return false;
            } else {
                return true;
            }
            break;

        default:
            throw new Exception("Invalid Event Id");
        }

    }


    /**
     * checkSimpleEvaluationParams
     *
     * @param mixed $params
     *
     * @access public
     * @return void
     */
    function checkSimpleEvaluationParams($params)
    {
        // Check that the basic param requirements are met
        if (!$this->checkBasicParamsRequirement($params)) {
            return false;
        } else if (empty($params['simple_evaluator_comment']) && empty($params['simple_eval_grade_table'])) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * checkRubricsEvaluationParams
     *
     * @param mixed $params
     *
     * @access public
     * @return void
     */
    function checkRubricsEvaluationParams($params)
    {
        // Check that the basic param requirements are met
        if (!$this->checkBasicParamsRequirement($params)) {
            return false;
        } else if (empty($params['rubric_criteria_marks']) && empty($params['rubric_general_comments'])) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * checkMixedEvaluationParams
     *
     * @param mixed $params
     *
     * @access public
     * @return void
     */
    function checkMixedEvaluationParams($params)
    {
        // Check that the basic param requirements are met
        if (!$this->checkBasicParamsRequirement($params)) {
            return false;
        } else if (empty($params['include_mixeval_question_comment']) && empty($params['include_mixeval_grades'])) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * checkBasicParamsRequirement
     *
     * @param mixed $params
     *
     * @access public
     * @return void
     */
    function checkBasicParamsRequirement($params)
    {
        if (empty($params['include_course']) && empty($params['include_eval_event_names'])) {
            return false;
        } else if (empty($params['include_student_name']) && empty($params['include_student_id'])) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * buildSurveyGroupSet
     *
     * @param mixed $surveyGrps
     * @param mixed $fields
     * @param mixed $groupNo
     *
     * @access public
     * @return void
     */
    function buildSurveyGroupSet($surveyGrps, $fields, $groupNo)
    {
        $members = array();
        foreach ($surveyGrps as $surveyGrp) {
            $num = $surveyGrp['SurveyGroup']['group_number'];
            foreach ($surveyGrp['Member'] as $member) {
                $mem = array();
                unset($member['SurveyGroupMember']);
                if ($groupNo) {
                    $mem[] = $num;
                }
                $member = empty($fields) ? array() : $member;
                $mem += $member;
                $members[] = implode(',', $mem);
            }
        }
        return $members;
    }

    /**
     * render
     *
     * @param mixed $grid data grid
     *
     * @access public
     * @return void
     */
    function render($grid)
    {
        $resource = fopen('php://output', 'a');
        foreach ($grid as $row) {
            fputcsv($resource, $row);
        }
        fclose($resource);
    }
    
    /**
     * exportCSV
     *
     * Used for generic CSV file export
     *
     * @param Array $rows array of rows
     * @param String $filename data name (with or without csv)
     *
     * @access public
     * @return boolean (success or failure)
     */
    function exportCSV($rows, $filename=false)
    {
        if (!empty($rows))
        {
            if($filename !== false) {
                $name = (strpos($filename, '.csv') === false) ? $filename . ".csv" : $filename;
            } else {
                $name =  "export.csv";
            }
            // Set the HTTP headers
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $name);

            # Start the ouput
            $output = fopen('php://output', 'w');
            
            # Then loop through the rows
            foreach($rows as $row)
            {
                # Add the rows to the body
                fputcsv($output, $row);
            }
            
            return true;
        }
        
        return false;
    }
}
