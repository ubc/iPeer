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
    function buildGroupExportCsvByGroup($params, $groupId)
    {
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $groupMemberId = $this->GroupsMembers->getMembers($groupId);
        $CSV = '';
        foreach ($groupMemberId as $userId) {
            $CSV .= $this->_buildGroupExportCsvByUser($userId, $params, $groupId)."\n";
        }

        return $CSV;
    }

    function _buildGroupExportCsvByUser($userId, $params, $groupId)
    {
        $this->User = ClassRegistry::init('User');
        $this->Group = ClassRegistry::init('Group');
        $row = '';
        $user = $this->User->findUserByid($userId);
        $group = $this->Group->getGroupByGroupId($groupId);
        if(!empty($params['include_group_names'])) {
            $row .= $group['Group']['group_name'].",";
        }
        if(!empty($params['include_student_id'])) {
            $row .= $user['User']['student_no'].",";
        }
        if(!empty($params['include_student_name'])) {
            $row .= $user['User']['first_name'].",";
            $row .= $user['User']['last_name'].",";
        }
        if(!empty($params['include_student_email'])) {
            $row .= $user['User']['email'];
        }
        return $row;
    }

    function creteCsvSubHeaderHelper($params, &$subHeader)
    {
        if(!empty($params['include_group_names'])) {
            $subHeader .= "Group Name,";
        }
        if(!empty($params['include_student_email'])) {
            $subHeader .= "Email,";
        }
        if(!empty($params['include_student_name'])) {
            $subHeader .= "Evaluatee,";
        }
        if(!empty($params['include_student_id'])) {
            $subHeader .= "Evaluatee S#,";
        }
        if(!empty($params['include_student_name'])) {
            $subHeader .= "Evaluator,";
        }
        if(!empty($params['include_student_id'])) {
            $subHeader .= "Evaluator S#,";
        }
    }

    function createMixEvalCsvSubHeader($params, $eventId)
    {
        $this->Event = ClassRegistry::init('Event');
        $this->MixevalsQuestion = ClassRegistry::init('MixevalsQuestion');
        $event = $this->Event->getEventById($eventId);
        $questions = $this->MixevalsQuestion->getQuestion($event['Event']['template_id'], 'S');

        $subHeader = '';
        $this->creteCsvSubHeaderHelper($params, $subHeader);
        for($i=0; $i<count($questions); $i++) {
            $subHeader .= "Q".($i+1)." (/".$questions[$i]['MixevalsQuestion']['multiplier']."),";
        }
        $subHeader .= "Raw Score, Late Penalty, Final Score";
        return $subHeader;
    }

    function createRubricsMixEvalCsvSubHeader($params, $eventId)
    {
        $this->Event = ClassRegistry::init('Event');
        $this->Rubric = ClassRegistry::init('Rubric');
        $this->RubricsCriteria = ClassRegistry::init('RubricsCriteria');

        $event = $this->Event->getEventById($eventId);
        $rubric = $this->Rubric->getRubricById($event['Event']['template_id']);
        $rubricCriterias = $this->RubricsCriteria->getCriteria($rubric['Rubric']['id']);

        $subHeader = '';
        $this->creteCsvSubHeaderHelper($params, $subHeader);
        for ($i=1; $i<=$rubric['Rubric']['criteria']; $i++) {
            $rubricCriteriaMark = $rubricCriterias[$i-1]['RubricsCriteria']['multiplier'];
            $subHeader .= "Q".$i." ( /".$rubricCriteriaMark."),";
        }
        $subHeader .= "Raw Score, Late Penalty, Final Score";
        return $subHeader;
    }

    function createSimpleCsvSubHeader($params)
    {
        $subHeader = '';
        $this->creteCsvSubHeaderHelper($params, $subHeader);
        $subHeader .= "Raw Grade";
        $subHeader .= ",Mark Penalty";
        $subHeader .= ",Final Grade";
        return $subHeader;
    }

    function createCsv($params, $eventId)
    {
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->Event = ClassRegistry::init('Event');
        //    var_dump($this->GroupEvent);
        $event = $this->Event->getEventById($eventId);

        $groupEvents = $this->GroupEvent->getGroupsByEventId($eventId);
        $csv = '';
        $eventHeader = $this->generateHeader($params, $eventId);
        $csv = $eventHeader."\n";

        switch($event['Event']['event_template_type_id']) {
        case 1:
            $subHeader = $this->createSimpleCsvSubHeader($params);
            $csv .= $subHeader."\n\n";
            $resultTable = $this->buildSimpleEvaluationScoreTableByEvent($params, $eventId);
            $csv .= $resultTable;
            break;

        case 2:
            $subHeader = $this->createRubricsMixEvalCsvSubHeader($params, $eventId);
            $csv .= $subHeader."\n\n";
            $resultTable = $this->buildRubricsEvalTableByEventId($params, $eventId);
            $csv .= $resultTable;
            break;

        case 4:
            $subHeader = $this->createMixEvalCsvSubHeader($params, $eventId);
            $csv .= $subHeader."\n\n";
            foreach ($groupEvents as $ge) {
                $resultTable = $this->buildMixedEvalScoreTableByGroupEvent($params ,$ge['GroupEvent']['id']);
                $csv .= $resultTable;
            }
            break;
        default: throw new Exception("Invalid event_id");
        }
        return $csv;
    }

    function createExcel($params, $eventId)
    {
        $this->Event = ClassRegistry::init('Event');
        $this->Group = ClassRegistry::init('Group');
        // Prepare header.
        $CSV = '';
        $header = $this->generateHeader($params, $eventId);
        $CSV .= $header."\n";
        $groupEvents = $this->GroupEvent->getGroupEventByEventId($eventId);
        $event = $this->Event->getEventById($eventId);
        switch($event['Event']['event_template_type_id']){
            // Simple Evaluation
        case 1 :
            for ($i=0; $i<count($groupEvents); $i++) {
                $group = $this->Group->getGroupByGroupId($groupEvents[$i]['GroupEvent']['group_id']);
                $grpEventId = $groupEvents[$i]['GroupEvent']['id'];
                $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
                if(!empty($params['include_group_names'])) {
                    $CSV .= "Group Name : ".$group[0]['Group']['group_name']."\n";
                }
                if(!empty($params['simple_eval_grade_table'])){
                    $simpleResults = $this->buildSimpleEvalResults($groupEvents[$i]['GroupEvent']['id'], $params);
                    $CSV .= $simpleResults."\n\n";
                }
                if(!empty($params['simple_evaluator_comment'])) {
                    $CSV .= "Simple Evaluation Comments :\n\n";
                    foreach ($groupMembers as $evaluatee) {
                        $simpleEvalComments = $this->buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluatee['GroupsMembers']['user_id'], $params, 'S');
                        $CSV .= $simpleEvalComments."\n";
                    }
                }
            }
            break;

            //Rubrics Evaluation Event
        case 2:
            for($i=0; $i<count($groupEvents); $i++) {
                $grpEventId = $groupEvents[$i]['GroupEvent']['id'];
                $group = $this->Group->getGroupByGroupId($groupEvents[$i]['GroupEvent']['group_id']);
                $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
                if(!empty($params['include_group_names'])) {
                    $CSV .= "Group Name : ".$group[0]['Group']['group_name']."\n\n";
                }
                if(!empty($params['rubric_criteria_marks'])) {
                    $CSV .= "Rubrics Evaluation Grade Tables:\n\n";
                    $gradeTable = $this->buildRubricsResultTable($params, $grpEventId);
                    $CSV .= $gradeTable."\n";
                }
                if(!empty($params['rubric_general_comments'])) {
                    $CSV .= "Rubrics General Comments\n\n";
                    foreach ($groupMembers as $evaluatee) {
                        $rubricGeneralComments = $this->buildSimpleOrRubricsCommentByEvaluatee($grpEventId, $evaluatee['GroupsMembers']['user_id'], $params, 'R');
                        $CSV .= $rubricGeneralComments."\n";
                    }
                }
            }
            return $CSV;
            break;

            // Mixed Evaluation Event
        case 4 :
            for ($i=0; $i<count($groupEvents); $i++) {
                $grpEventId = $groupEvents[$i]['GroupEvent']['id'];
                $group = $this->Group->getGroupByGroupId($groupEvents[$i]['GroupEvent']['group_id']);
                $groupMembers = $this->GroupEvent->getGroupMembers($grpEventId);
                if(!empty($params['include_group_names'])) {
                    $CSV .= "Group Name : ".$group[0]['Group']['group_name']."\n\n\n";
                }
                if(!empty($params['include_mixeval_grades'])) {
                    $CSV .= "Part 1: Licket Scale Questions Grade Table :\n\n";
                    $gradeTable = $this->buildMixevalResult($params, $groupEvents[$i]['GroupEvent']['id'], $groupMembers[$i]['GroupsMembers']['id']);
                    $CSV .= $gradeTable."\n\n";
                }
                if (!empty($params['include_mixeval_question_comment'])) {
                    $CSV .= "Part 2: Comment Question Results :\n\n";
                    $questionComments = $this->buildMixEvalQuestionCommentTable($params, $grpEventId);
                    $CSV .= $questionComments."\n\n";
                }
            }
            break;

        default: throw new Exception("Event id input seems to be invalid!");
        }
        return $CSV;
    }

    function checkAll($params, $eventId)
    {
        $this->Event = ClassRegistry::init('Event');
        $event = $this->Event->getEventById($eventId);
        $eventType = $event['Event']['event_template_type_id'];

        switch($eventType) {
            // Simple Evaluation
        case 1:
            if(!$this->checkSimpleEvaluationParams($params)) {
                return false;
            }
            else return true;
            break;
            //Rubrics Evaluation
        case 2:
            if(!$this->checkRubricsEvaluationParams($params)) {
                return false;
            }
            else return true;
            break;
            // Mixed Evaluation
        case 4:
            if(!$this->checkMixedEvaluationParams($params)) {
                return false;
            }
            else return true;
            break;

        default:
            throw new Exception("Invalid Event Id");
        }

    }

    function checkSimpleEvaluationParams($params)
    {
        // Check that the basic param requirements are met
        if(!$this->checkBasicParamsRequirement($params)) {
            return false;
        }
        else if(empty($params['simple_evaluator_comment']) && empty($params['simple_eval_grade_table'])) {
            return false;
        }
        else return true;
    }

    function checkRubricsEvaluationParams($params)
    {
        // Check that the basic param requirements are met
        if(!$this->checkBasicParamsRequirement($params)) {
            return false;
        }
        else if(empty($params['rubric_criteria_marks']) && empty($params['rubric_general_comments'])) {
            return false;
        }
        else return true;
    }

    function checkMixedEvaluationParams($params)
    {
        // Check that the basic param requirements are met
        if (!$this->checkBasicParamsRequirement($params)) {
            return false;
        }
        else if(empty($params['include_mixeval_question_comment']) && empty($params['include_mixeval_grades'])) {
            return false;
        }
        else return true;
    }

    function checkBasicParamsRequirement($params)
    {
        if(empty($params['include_course']) && empty($params['include_eval_event_names'])){
            return false;
        }
        else if(empty($params['include_student_name']) && empty($params['include_student_id'])) {
            return false;
        }
        else return true;
    }
}
