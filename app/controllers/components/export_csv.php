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
     * @param mixed $params  params
     * @param mixed $groupId group id
     *
     * @access public
     * @return void
     */
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


    /**
     * _buildGroupExportCsvByUser
     *
     * @param mixed $userId  user id
     * @param mixed $params  params
     * @param mixed $groupId group id
     *
     * @access protected
     * @return void
     */
    function _buildGroupExportCsvByUser($userId, $params, $groupId)
    {
        $this->User = ClassRegistry::init('User');
        $this->Group = ClassRegistry::init('Group');
        $row = '';
        $user = $this->User->findById($userId);
        $group = $this->Group->getGroupByGroupId($groupId);
        if (!empty($params['include_group_numbers'])) {
            $row .= $group['0']['Group']['group_num'].", ";
        }
        if (!empty($params['include_group_names'])) {
            $row .= $group['0']['Group']['group_name'].", ";
        }
        if (!empty($params['include_usernames'])) {
            $row .= $user['User']['username'].", ";
        }
        if (!empty($params['include_student_id'])) {
            $row .= $user['User']['student_no'].", ";
        }
        if (!empty($params['include_student_name'])) {
            $row .= $user['User']['first_name'].", ";
            $row .= $user['User']['last_name'].", ";
        }
        //if (!empty($params['include_student_email'])) {
        //    $row .= $user['User']['email'];
        //}
        return $row;
    }


    /**
     * creteCsvSubHeaderHelper
     *
     * @param mixed $params     params
     * @param mixed &$subHeader sub header
     *
     * @access public
     * @return void
     */
    function creteCsvSubHeaderHelper($params, &$subHeader)
    {
        if (!empty($params['include_group_names'])) {
            $subHeader .= "Group Name, ";
        }
        if (!empty($params['include_student_email'])) {
            $subHeader .= "Email, ";
        }
        if (!empty($params['include_student_name'])) {
            $subHeader .= "Evaluatee, ";
        }
        if (!empty($params['include_student_id'])) {
            $subHeader .= "Evaluatee S#, ";
        }
        if (!empty($params['include_student_name'])) {
            $subHeader .= "Evaluator, ";
        }
        if (!empty($params['include_student_id'])) {
            $subHeader .= "Evaluator S#, ";
        }
    }

    /**
     * createCsvSubHeader
     *
     * @param mixed $params    params
     * @param mixed $questions questions
     *
     * @access public
     * @return void
     */
    function createCsvSubHeader($params, $questions)
    {
        $subHeader = '';
        $this->creteCsvSubHeaderHelper($params, $subHeader);
        foreach ($questions as $key => $question) {
            $subHeader .= "Q".($key+1)." ( /".$question['multiplier']."), ";
        }
        $subHeader .= "Raw Score, Late Penalty, Final Score";
        return $subHeader;
    }


    /**
     * createCsv
     *
     * @param mixed $params  params
     * @param mixed $eventId event id
     *
     * @access public
     * @return void
     */
    function createCsv($params, $event)
    {
        $csv = '';
        $eventHeader = $this->generateHeader($params, $event);
        $csv = $eventHeader."\n";
        $groupEvents = $event['GroupEvent'];
        $groupEventIds = Set::extract($groupEvents, '/id');
        $this->responseModelName = EvaluationResponseBase::$types[$event['Event']['event_template_type_id']];
        $this->responseModel = ClassRegistry::init($this->responseModelName);
        $this->evaluationModelName = EvaluationBase::$types[$event['Event']['event_template_type_id']];
        $this->evaluationModel = ClassRegistry::init($this->evaluationModelName);
        $results = $this->responseModel->getSubmittedResultsByGroupEvent($groupEventIds, $this->detailModel[$event['Event']['event_template_type_id']]);
        $results = Set::combine($results, '{n}.'.$this->responseModelName.'.id', '{n}', '{n}.'.$this->responseModelName.'.grp_event_id');
        $evaluation = $this->evaluationModel->getEvaluation($event['Event']['template_id']);
        $event = array_merge($event, $evaluation);

        $subHeader = $this->createCsvSubHeader($params, $event['Question']);
        $csv .= $subHeader."\n\n";
        $csv .= $this->buildEvaluationScoreTableByEvent($params, $event, $results);

        return $csv;
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

}
