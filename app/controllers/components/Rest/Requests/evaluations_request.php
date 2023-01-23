<?php
App::import('Lib', 'caliper');

use caliper\CaliperHooks;

class EvaluationsRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];
    public $components = ['Session', 'EvaluationResource',
        'UserResource', 'EventResource', 'CourseResource', 'PenaltyResource', 'GroupResource',
        'SimpleEvaluationResource',
        'RubricEvaluationResource',
        'MixedEvaluationResource',
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
            case 'POST': // Create
                $this->createSimpleEvaluationSubmit($event, $groupId, $studentId);
                break;
            case 'PUT': // Update
                $this->updateSimpleEvaluationSubmit($event, $groupId, $studentId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET, POST, PUT');
                break;
        }
    }
    
    private function getSimpleEvaluationSubmit(array $event, string $groupId, string $studentId): void
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
        //$penalty = $this->controller->Penalty->getPenaltyByEventId($eventId);
        //$penaltyDays = $this->controller->Penalty->getPenaltyDays($eventId);
        $penaltyFinal = $this->controller->Penalty->getPenaltyFinal($eventId);
        $groupMembers = $this->controller->User->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], empty($studentId) ? $userId : $studentId);
        $numMembers = count($groupMembers);
        $simpleEvaluation = $this->controller->SimpleEvaluation->find('first', array(
            'conditions' => array('id' => $event['Event']['template_id']),
            'contain' => false,
        ));
        $remaining = $simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers;
        //          if ($in['points']) $out['points']=$in['points']; //saves previous points
        //$points_to_ratio = $numMembers==0 ? 0 : 1 / ($simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers);
        //          if ($in['comments']) $out['comments']=$in['comments'];
        
        // $getJson()
        $json = [];
        $json['event'] = $this->EventResource->format($event['Event']);
        $json['course'] = $this->CourseResource->getCourseById($event['Event']['course_id']);
        $json['group'] = $this->GroupResource->format($event['Group']);
        $json['penalty'] = isset($penaltyFinal) ? $this->PenaltyResource->format($penaltyFinal) : [];
        $json['evaluation'] = $this->SimpleEvaluationResource->getSimpleEvaluationQuestionsAndSubmission($simpleEvaluation, $groupMembers, $submission, $evaluation);
        $json['evaluation']['user_id'] = $userId;
        $json['evaluation']['member_count'] = count($groupMembers);
        $json['evaluation']['member_ids'] = implode(',', Set::extract('/User/id', $groupMembers));
        $json['evaluation']['remaining'] = $remaining;
        
        $this->JsonResponse->setContent($json)->withStatus(200);
    }
    
    private function createSimpleEvaluationSubmit($event, $groupId, $studentId): void
    {
        echo "<pre>";
        print_r($this->params);
        die();
        echo "</pre>";
    }
    
    private function updateSimpleEvaluationSubmit($event, $groupId, $studentId): void
    {
        echo "<pre>";
        print_r($this->params);
        die();
        echo "</pre>";
    }
    
    /** Result */
    public function processSimpleEvaluationResultRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        switch ($method) {
            case 'GET': // Read
                $this->getSimpleEvaluationResult($event, $groupEventId, $autoRelease, $userId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET');
                break;
        }
    }
    
    private function getSimpleEvaluationResult($event, $groupEventId, $autoRelease, $userId): void
    {
        $json = [];
        
        $this->JsonResponse->setContent($json)->withStatus(200);
        exit;
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
            case 'POST': // Create
                $this->createRubricEvaluationSubmit($event, $groupId, $studentId);
                break;
            case 'PUT': // Update
                $this->updateRubricEvaluationSubmit($event, $groupId, $studentId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET, POST, PUT');
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
        // template type is not rubric
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
        // $penalty = $this->controller->Penalty->getPenaltyByEventId($eventId);
        // $penaltyDays = $this->controller->Penalty->getPenaltyDays($eventId);
        $penaltyFinal = $this->controller->Penalty->getPenaltyFinal($eventId);
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
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($event['GroupEvent']['id'], $userId);
        
        // $getJson()
        $json = [];
        $json['event'] = $this->EventResource->format($event['Event']);
        $json['course'] = $this->CourseResource->getCourseById($event['Event']['course_id']);
        $json['group'] = $this->GroupResource->format($event['Group']);
        $json['penalty'] = isset($penaltyFinal) ? $this->PenaltyResource->format($penaltyFinal) : [];
        $json['evaluation'] = $this->RubricEvaluationResource->getRubricEvaluationQuestionsAndSubmission($data, $rubricDetail['groupMembers'], $submission);
        $json['evaluation']['user_id'] = $userId;
        $json['evaluation']['member_count'] = $rubricDetail['evaluateeCount'];
        $json['evaluation']['member_ids'] = implode(',', Set::extract('/User/id', $rubricDetail['groupMembers']));
        $json['evaluation']['grp_event_id'] = '';
        $json['evaluation']['com_req'] = $comReq;
        $json['evaluation']['student_id'] = $studentId;
        
        $this->JsonResponse->setContent($json)->withStatus(200);
    }
    
    private function createRubricEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
        echo "<pre>";
        print_r($this->params);
        die();
        echo "</pre>";
    }
    
    private function updateRubricEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
        echo "<pre>";
        print_r($this->params);
        die();
        echo "</pre>";
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
        $json = [];
        
        $this->JsonResponse->setContent($json)->withStatus(200);
        exit;
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
            case 'POST': // Create
                $this->createMixedEvaluationSubmit($event, $groupId, $studentId);
                break;
            case 'PUT': // Update
                $this->updateMixedEvaluationSubmit($event, $groupId, $studentId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET, POST, PUT');
                break;
        }
    }
    
    private function getMixedEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
        $eventId = $event['Event']['id'];
        
        if (!is_numeric($groupId)) {
            $this->JsonResponse->withMessage('Error: Invalid Id')->withStatus(404);
            return;
        }
        
        $courseId = $this->controller->Event->getCourseByEventId($eventId);
        
        $group = array();
        $group_events = $this->controller->GroupEvent->getGroupEventByEventId($eventId);
        $userId = empty($studentId) ? User::get('id') : $studentId;
        
        foreach ($group_events as $events) {
            if ($this->controller->GroupsMembers->checkMembershipInGroup($events['GroupEvent']['group_id'], $userId) !== 0) {
                $group[] = $events['GroupEvent']['group_id'];
            }
        }
        
        // if group id provided does not match the group id the user belongs to
        if (!in_array($groupId, $group)) {
            $this->JsonResponse->withMessage('Error: Invalid Id')->withStatus(404);
            return;
        }
        
        // students can't submit outside of release date range
        $event = $this->controller->Event->getEventByIdGroupId($eventId, $groupId);
        $now = time();
        
        if ($now < strtotime($event['Event']['release_date_begin']) ||
            $now > strtotime($event['Event']['release_date_end'])) {
            $this->JsonResponse->withMessage('Error: Evaluation is unavailable')->withStatus(404);
            return;
        }
        
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $userId);
        $members = $this->controller->GroupsMembers->findAllByGroupId($groupId);
        //$penalty = $this->controller->Penalty->getPenaltyByEventId($eventId);
        //$penaltyDays = $this->controller->Penalty->getPenaltyDays($eventId);
        $penaltyFinal = $this->controller->Penalty->getPenaltyFinal($eventId);
        $enrol = $this->controller->UserEnrol->find('count', array(
            'conditions' => array('user_id' => $userId, 'course_id' => $courseId)
        ));
        $self = $this->controller->EvaluationMixeval->find('first', array(
            'conditions' => array('evaluator' => $userId, 'evaluatee' => $userId, 'event_id' => $eventId)
        ));
        $questions = $this->controller->MixevalQuestion->findAllByMixevalId($event['Event']['template_id']);
        $mixeval = $this->controller->Mixeval->find('first', array(
            'conditions' => array('id' => $event['Event']['template_id']), 'contain' => false, 'recursive' => 2));
        $groupMembers = $this->controller->Evaluation->loadMixEvaluationDetail($event);
        
        // $getJson()
        $json = [];
        $json['event'] = $this->EventResource->format($event['Event']);
        $json['course'] = $this->CourseResource->getCourseById($event['Event']['course_id']);
        $json['group'] = $this->GroupResource->format($event['Group']);
        $json['penalty'] = isset($penaltyFinal) ? $this->PenaltyResource->format($penaltyFinal) : [];
        $json['evaluation'] = $this->MixedEvaluationResource->getMixedEvaluationQuestionsAndSubmission($mixeval, $questions, $groupMembers, $submission);
        $json['evaluation']['user_id'] = $userId;
        $json['evaluation']['self'] = $self;
        $json['evaluation']['enrol'] = $enrol;
        $json['evaluation']['member_count'] = count($members);
        $json['evaluation']['grp_event_id'] = $event['GroupEvent']['id'];
        
        $this->JsonResponse->setContent($json)->withStatus(200);
    }
    
    private function createMixedEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
        echo "<pre>";
        print_r($this->params);
        die();
        echo "</pre>";
    }
    
    private function updateMixedEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
        echo "<pre>";
        print_r($this->params);
        die();
        echo "</pre>";
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
        $json = [];
        
        $this->JsonResponse->setContent($json)->withStatus(200);
        exit;
    }
}
