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
