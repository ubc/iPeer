<?php
App::import('Lib', 'caliper');

use caliper\CaliperHooks;

class EvaluationsRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];
    public $components = ['Session', 'EvaluationResource',
        'EventResource', 'CourseResource', 'PenaltyResource', 'GroupResource',
        'JsonResponse'];
    
    public $controller;
    public $settings;
    public $params;
    public $data;
    
    public function __construct()
    {
        $this->Sanitize = new Sanitize;
        parent::__construct();
    }
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
        $this->params = $controller->params;
        $this->data = $controller->data;
    }
    
    /** Submit */
    
    public function processSimpleEvaluationSubmitRequest($method, $event, $groupId, $studentId): void
    {
        $studentId = $studentId ?? $this->controller->Auth->user('id');
        switch ($method) {
            case 'GET': // Read
                $this->getSimpleEvaluationSubmit($event, $groupId, $studentId);
                break;
            case 'PUT': // Update
                $this->setSimpleEvaluationSubmit($event, $groupId, $studentId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET, PUT');
                break;
        }
    }
    
    private function getSimpleEvaluationSubmit(array $event, string $groupId, string $studentId)
    {
        $eventId = $event['Event']['id'];
        
        $userId = User::get('id');
        
        $grpMem = $this->controller->GroupsMembers->find('first', array(
            'conditions' => array('GroupsMembers.user_id' => empty($studentId) ? $userId : $studentId,
                'GroupsMembers.group_id' => $groupId)));
        
        // filter out users that don't have access to this eval, invalid ids
        if (empty($grpMem)) {
            $this->JsonResponse->withMessage('Error: Invalid Id')->withStatus(404);
            return;
        }
        
        $now = time();
        // students can't submit outside of release date range
        if ($now < strtotime($event['Event']['release_date_begin']) ||
            $now > strtotime($event['Event']['release_date_end'])) {
            $this->JsonResponse->withMessage('Error: Evaluation is unavailable (students can\'t submit outside of release date range)')->withStatus(404);
            return;
        }
        
        // students can submit again
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, empty($studentId) ? User::get('id') : $studentId);
        $evaluation = []; // JK::N
        if (!empty($submission)) {
            // load the submitted values
            $evaluation = $this->controller->EvaluationSimple->getSubmittedResultsByGroupIdEventIdAndEvaluator($groupId, $eventId, empty($studentId) ? User::get('id') : $studentId);
            /** JK::L
             * foreach ($evaluation as $eval) {
             * $this->data['Evaluation']['point'.$eval['EvaluationSimple']['evaluatee']] = $eval['EvaluationSimple']['score'];
             * $this->data['Evaluation']['comment_'.$eval['EvaluationSimple']['evaluatee']] = $eval['EvaluationSimple']['comment'];
             * }*/
        }
        
        //Get the target event
        $eventId = $this->Sanitize->paranoid($eventId);
        $event = $this->controller->Event->getEventByIdGroupId($eventId, $groupId);
        $penalty = $this->controller->Penalty->getPenaltyByEventId($eventId);
        $penaltyDays = $this->controller->Penalty->getPenaltyDays($eventId);
        $penaltyFinal = $this->controller->Penalty->getPenaltyFinal($eventId);
        
        //Set up the courseId to session
        $courseId = $event['Event']['course_id'];
        
        //Get Members for this evaluation
        $groupMembers = $this->controller->User->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], empty($studentId) ? $userId : $studentId);
        
        // enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
        $numMembers = count($groupMembers);
        $simpleEvaluation = $this->controller->SimpleEvaluation->find('first', array(
            'conditions' => array('id' => $event['Event']['template_id']),
            'contain' => false,
        ));
        $remaining = $simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers;
        //          if ($in['points']) $out['points']=$in['points']; //saves previous points
        //$points_to_ratio = $numMembers==0 ? 0 : 1 / ($simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers);
        //          if ($in['comments']) $out['comments']=$in['comments'];
        
        // render simple evaluation
        $this->EvaluationResource->simpleEvaluation([
            'event' => $event,
            'penaltyFinal' => $penaltyFinal,
            'penaltyDays' => $penaltyDays,
            'penalty' => $penalty,
            //
            'questions' => $simpleEvaluation['SimpleEvaluation'],
            'submission' => (array)$submission ?? [],
            'evaluation' => (array)$evaluation ?? [],
            //
            'userId' => empty($studentId) ? $userId : $studentId,
            'groupMembers' => $groupMembers,
            'memberIDs' => implode(',', Set::extract('/User/id', $groupMembers)),
            //
            'evaluateeCount' => count($groupMembers),
            'remaining' => $remaining,
            'courseId' => $courseId,
            //
            'allDone' => [],
            'comReq' => [],
        ]);
    }
    
    private function setSimpleEvaluationSubmit($event, $groupId, $studentId): void
    {
    }
    
    /** Result */
    public function processSimpleEvaluationResultRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        /** $studentId = $studentId ?? $this->controller->Auth->user('id'); */
        switch ($method) {
            case 'GET': // Read
                $this->getSimpleEvaluationResult($event, $groupEventId, $autoRelease, $userId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET');
                break;
        }
        /**
         * if ($groupEventId) {
         * $this->ReviewsSimpleRequest->processResourceRequest($event, $groupEventId, $autoRelease, $userId, $method);
         * } else {
         * $this->ReviewsSimpleRequest->processCollectionRequest($method);
         * }*/
    }
    
    private function getSimpleEvaluationResult($event, $groupEventId, $autoRelease, $userId): void
    {
        $studentResult = $this->controller->EvaluationSimple->formatStudentViewOfSimpleEvaluationResult($event, $userId);
        //$this->set('studentResult', $studentResult);
        //$this->set('gradeReleased', $studentResult['gradeReleased']);
        //$this->set('commentReleased', $studentResult['commentReleased']);
        //$this->render('student_view_simple_evaluation_results');
        
        $json = [
            'studentResult' => $studentResult,
            'gradeReleased' => $studentResult['gradeReleased'],
            'commentReleased' => $studentResult['commentReleased'],
        ];
        
        $this->JsonResponse->setContent($json)->withStatus(200);
        exit;

//        $this->pre_r($event, $groupEventId, $autoRelease, $userId);
//        die();
    }
    
    
    /**
     * Rubric
     * @param $method
     * @param $event
     * @param $groupId
     * @param $studentId
     */
    /** Submit */
    public function processRubricEvaluationSubmitRequest($method, $event, $groupId, $studentId): void
    {
        $studentId = $studentId ?? $this->controller->Auth->user('id');
        switch ($method) {
            case 'GET': // Read
                $this->getRubricEvaluationSubmit($event, $groupId, $studentId);
                break;
            case 'PUT': // Update
                $this->setRubricEvaluationSubmit($event, $groupId, $studentId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET, PUT');
                break;
        }
    }
    
    private function getRubricEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
        $userId = User::get('id');
        $eventId = $event['Event']['id'];
        //
        $rubricId = $event['Event']['template_id'];
        $courseId = $event['Event']['course_id'];
        
        $groupEvents = $this->controller->GroupEvent->findAllByEventId($eventId);
        $groups = Set::extract('/GroupEvent/group_id', $groupEvents);
        
        // if group id provided does not match the group id the user belongs to or
        // template type is not rubric - they are redirected
        if (!is_numeric($groupId) || !in_array($groupId, $groups) ||
            !$this->controller->GroupsMembers->checkMembershipInGroup($groupId, empty($studentId) ? User::get('id') : $studentId)) {
            $this->JsonResponse->withMessage('Error: Invalid Id')->withStatus(404);
            return;
        }
        
        // students can't submit outside of release date range
        $now = time();
        
        if ($now < strtotime($event['Event']['release_date_begin']) ||
            $now > strtotime($event['Event']['release_date_end'])) {
            $this->JsonResponse->withMessage('Error: Evaluation is unavailable')->withStatus(404);
            return;
        }
        
        $event = $this->controller->Event->getEventByIdGroupId($eventId, $groupId);
        
        $data = $this->controller->Rubric->getRubricById($rubricId);
        
        $penalty = $this->controller->Penalty->getPenaltyByEventId($eventId);
        $penaltyDays = $this->controller->Penalty->getPenaltyDays($eventId);
        $penaltyFinal = $this->controller->Penalty->getPenaltyFinal($eventId);
        
        //Setup the viewData
        $rubricId = $event['Event']['template_id'];
        $rubric = $this->controller->Rubric->getRubricById($rubricId);
        $rubricEvalViewData = $this->controller->Rubric->compileViewData($rubric);
        
        $rubricDetail = $this->controller->Evaluation->loadRubricEvaluationDetail($event, $studentId);
        
        $evaluated = 0; // # of group members evaluated
        $commentsNeeded = false;
        foreach ($rubricDetail['groupMembers'] as $row) {
            $user = $row['User'];
            if (isset($user['Evaluation'])) {
                foreach ($user['Evaluation']['EvaluationRubricDetail'] as $eval) {
                    if (!$commentsNeeded && empty($eval['criteria_comment'])) {
                        $commentsNeeded = true;
                    }
                }
                // only check if $commentsNeeded is false
                if (!$commentsNeeded && empty($user['Evaluation']['EvaluationRubric']['comment'])) {
                    $commentsNeeded = true;
                }
                if (count($user['Evaluation']['EvaluationRubricDetail']) == count($rubricDetail['rubric']['RubricsCriteria'])) {
                    $evaluated++;
                }
            } else {
                $commentsNeeded = true; // not evaluated = comments needed
            }
        }
        $allDone = ($evaluated == $rubricDetail['evaluateeCount']);
        $comReq = ($commentsNeeded && $event['Event']['com_req']);
        
        $evaluationSubmission = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($event['GroupEvent']['id'], $userId);
        
        $submission = [];
        foreach ($rubricDetail['groupMembers'] as $member) {
            $tmp = [];
            $tmp['id'] = $member['User']['id'];
            $tmp['first_name'] = $member['User']['first_name'];
            $tmp['last_name'] = $member['User']['last_name'];
            isset($member['User']['Evaluation']) ? $tmp['evaluation'] = [
                'id' => $member['User']['Evaluation']['EvaluationRubric']['id'],
                'comment' => $member['User']['Evaluation']['EvaluationRubric']['comment'],
                'comment_release' => $member['User']['Evaluation']['EvaluationRubric']['comment_release'],
                'evaluatee' => $member['User']['Evaluation']['EvaluationRubric']['evaluatee'],
                'evaluator' => $member['User']['Evaluation']['EvaluationRubric']['evaluator'],
                'grade_release' => $member['User']['Evaluation']['EvaluationRubric']['grade_release'],
                'score' => $member['User']['Evaluation']['EvaluationRubric']['score'],
            ] : null;
            isset($member['User']['Evaluation']) ? $tmp['evaluation']['detail'] = $member['User']['Evaluation']['EvaluationRubricDetail'] : null;
            $submission[] = $tmp;
        }
        
        $criterias = [];
        foreach ($data['RubricsCriteria'] as $criteria) {
            $tmp = [];
            $tmp['id'] = $criteria['id'];
            $tmp['criteria'] = $criteria['criteria'];
            $tmp['criteria_num'] = $criteria['criteria_num'];
            $tmp['multiplier'] = $criteria['multiplier'];
            $tmp['rubric_id'] = $criteria['rubric_id'];
            $tmp['show_marks'] = $criteria['show_marks'];
            $tmp['comments'] = $criteria['RubricsCriteriaComment'];
            $tmp['loms'] = $data['RubricsLom'];
            $criterias[] = $tmp;
        }
        
        $json = [
            'event' => $this->EventResource->format($event['Event']),
            'course' => $this->CourseResource->courseById($event['Event']['course_id']),
            'penalty' => $this->PenaltyResource->format($this->controller->Penalty->getPenaltyFinal($eventId)),
            'group' => $this->GroupResource->format($event['Group']),
            'rubric' => [
                'id' => $data['Rubric']['id'],
                'availability' => $data['Rubric']['availability'],
                'criteria' => $data['Rubric']['criteria'],
                'event_count' => $data['Rubric']['event_count'],
                'lom_max' => $data['Rubric']['lom_max'],
                'name' => $data['Rubric']['name'],
                'template' => $data['Rubric']['template'],
                'total_marks' => $data['Rubric']['total_marks'],
                'view_mode' => $data['Rubric']['view_mode'],
                'zero_mark' => $data['Rubric']['zero_mark'],
                'criterias' => $criterias,
            ],
            'submission' => [
                "id" => "",
                "name" => "",
                "description" => "",
                "record_status" => "",
                "availability" => "",
                "submitted" => "",
                "submitter_id" => "",
                "date_submitted" => "",
                'data' => $submission,
            ],
            'member_count' => $rubricDetail['evaluateeCount'],
            'member_ids' => implode(',', Set::extract('/User/id', $rubricDetail['groupMembers'])),
            'student_id' => $studentId,
            'com_req' => $comReq,
        ];
        
        $this->JsonResponse->setContent($json)->withStatus(200);
    }
    
    private function setRubricEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
    }
    
    /** Result */
    public function processRubricEvaluationResultRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        switch ($method) {
            case 'GET': // Read
                $this->getRubricEvaluationResult($event, $groupEventId, $autoRelease, $userId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET');
                break;
        }
    }
    
    private function getRubricEvaluationResult($event, $groupEventId, $autoRelease, $userId): void
    {
        $this->pre_r($event, $groupEventId, $autoRelease, $userId);
        die();
    }
    
    
    /**
     * Mixed
     * @param $method
     * @param $event
     * @param $groupId
     * @param $studentId
     */
    /** Submit */
    public function processMixedEvaluationSubmitRequest($method, $event, $groupId, $studentId): void
    {
        $studentId = $studentId ?? $this->controller->Auth->user('id');
        switch ($method) {
            case 'GET': // Read
                $this->getMixedEvaluationSubmit($event, $groupId, $studentId);
                break;
            case 'PUT': // Update
                $this->setMixedEvaluationSubmit($event, $groupId, $studentId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET, PUT');
                break;
        }
    }
    
    private function getMixedEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
        $json = [
            'event' => $this->EventResource->format($event['Event']),
            'course' => $this->CourseResource->format($event['Course']),
            'group' => $this->GroupResource->groupByIdEventId($groupId, $event['Event']['id']),
            'penalty' => isset($event['Penalty']) ? $this->PenaltyResource->format($event['Penalty']) : [],
            'questions' => [],
            'submission' => [
                'data' => []
            ],
        ];
        $this->JsonResponse->setContent($json)->withStatus(200);
    }
    
    private function setMixedEvaluationSubmit(string $eventId, string $objectId, string $studentId): void
    {
    }
    
    /** Result */
    public function processMixedEvaluationResultRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        switch ($method) {
            case 'GET': // Read
                $this->getMixedEvaluationResult($event, $groupEventId, $autoRelease, $userId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET');
                break;
        }
    }
    
    private function getMixedEvaluationResult($event, $groupEventId, $autoRelease, $userId): void
    {
        $this->pre_r($event, $groupEventId, $autoRelease, $userId);
        die();
    }
}
