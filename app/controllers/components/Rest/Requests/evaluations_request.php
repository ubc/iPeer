<?php
App::import('Lib', 'caliper');

use caliper\CaliperHooks;

class EvaluationsRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];
    public $components = ['Session', 'EvaluationResource',
        'UserResource', 'EventResource', 'CourseResource', 'PenaltyResource', 'GroupResource', 'GroupEventResource',
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
                $this->setRubricEvaluationSubmit($event, $groupId, $studentId);
                break;
            case 'PUT': // Update
                $this->setRubricEvaluationSubmit($event, $groupId, $studentId);
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
        $json['evaluation']['rubric_id'] = $rubricId;
        $json['evaluation']['student_id'] = $studentId;
        // $groupEventId
        // $this->GroupEventResource->getGroupEventByEventIdGroupId($event['Event']['id'], $event['Group']['id']);
        $json['evaluation']['grp_event_id'] = $event['GroupEvent']['id'];
        $json['evaluation']['member_ids'] = implode(',', Set::extract('/User/id', $rubricDetail['groupMembers']));
        $json['evaluation']['member_count'] = $rubricDetail['evaluateeCount'];
        $json['evaluation']['com_req'] = $comReq;
        
        $this->JsonResponse->setContent($json)->withStatus(200);
    }
    
    private function submitRubricEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
        $status = true;
        
        $eventId = $this->params['form']['event_id'];
        $groupId = $this->params['form']['group_id'];
        $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
        $evaluators = $this->controller->GroupsMembers->findAllByGroupId($groupId);
        $evaluators = Set::extract('/GroupsMembers/user_id', $evaluators);
        
        $groupEventId = $this->params['form']['group_event_id'];
        //Get the target group event
        $groupEvent = $this->controller->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
        $this->controller->GroupEvent->id = $groupEvent['GroupEvent']['group_id'];
        
        // if no submission exists, create one
        //Get the target event submission
        $evaluationSubmission = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
        if (empty($evaluationSubmission)) {
            $this->controller->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
            $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEventId;
            $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
            $evaluationSubmission['EvaluationSubmission']['submitter_id'] = $evaluator;
            // save evaluation submission
            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
            $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
            if (!$this->controller->EvaluationSubmission->save($evaluationSubmission)) {
                $status = false;
            }
        }
        
        //checks if all members in the group have submitted
        //the number of submission equals the number of members
        //means that this group is ready to review
        $memberCompletedNo = $this->controller->EvaluationSubmission->find('count', array(
            'conditions' => array('grp_event_id' => $groupEventId, 'submitter_id' => $evaluators)
        ));
        $evaluators = count($evaluators);
        //Check to see if all members are completed this evaluation
        if ($memberCompletedNo == $evaluators) {
            $groupEvent['GroupEvent']['marked'] = 'to review';
            if (!$this->controller->GroupEvent->save($groupEvent)) {
                $status = false;
            }
        }
        
        if ($status) {
            CaliperHooks::submit_rubric($eventId, $evaluator, $groupEvent['GroupEvent']['id'], $groupId);
            $this->JsonResponse->withMessage('Your Evaluation was submitted successfully.')->withStatus(200);
            return;
        } else {
            $this->JsonResponse->withMessage('Your Evaluation was not submitted successfully.')->withStatus(404);
            return;
        }
    }
    
    private function setRubricEvaluationSubmit(array $event, string $groupId, string $studentId): void
    {
        $eventId = $this->params['form']['event_id'];
        $groupId = $this->params['form']['group_id'];
        
        $event = $this->controller->Event->findById($eventId);
        
        // Student View Mode
        if (isset($this->params['form']['memberIDs'])) {
            if (gettype($this->params['form']['memberIDs']) === 'string') {
                $this->params['form']['memberIDs'] = array_unique(explode(',', $this->params['form']['memberIDs']));
            }
            $this->params['form']['memberIDs'] = array_unique($this->params['form']['memberIDs']);
            // find out whose evaluation is submitted
            $msg = array();
            foreach ($this->params['form']['memberIDs'] as $targetEvaluatee) { // $userId
                if ($this->controller->Evaluation->saveRubricEvaluation($targetEvaluatee, 0, $this->params)) {
                    // check whether comments are given, if not and it is required, send msg
                    $comments = $this->params['form'][$targetEvaluatee . 'comments'];
                    $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
                    $sub = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
                    if ((bool)$event['Event']['com_req'] && (count($filter) < count($comments))) {
                        // $msg[] = __('some comments are missing', true);
                        $msg[] = 'some comments are missing';
                    }
                    if (empty($sub)) {
                        $msg[] = __('you still have to submit the evaluation with the Submit button below', true);
                    }
                    $suffix = empty($msg) ? '.' : ', but ' . implode(' and ', $msg) . '.';
                    $this->JsonResponse->withMessage('Your evaluation has been saved' . $suffix)->withStatus(200);
                } else {
                    //Found error. Validate the error why the Event->save() method returned false
                    //TBD:: $this->validateErrors($this->Event);
                    $this->JsonResponse->withMessage('Your evaluation was not saved successfully')->withStatus(404);
                    return;
                }
            }
        } // Criteria View Mode
        elseif (isset($this->params['form']['criteriaIDs'])) {
            // find out the criteria submitted
            // general comments section should be given value of null
            $targetCriteria = null;
            foreach ($this->params['form']['criteriaIDs'] as $criteriaId) {
                if (isset($this->params['form'][$criteriaId])) {
                    $targetCriteria = $criteriaId;
                    break;
                }
            }
            
            $evaluator = $this->params['data']['Evaluation']['evaluator_id'];
            $groupMembers = $this->controller->User->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], $evaluator);
            
            // Criteria will be null if the submitted section was 'General Comments'
            if ($targetCriteria != null) {
                $viewMode = 1;
            } else {
                $viewMode = 0;
            }
            
            // Loop through and save every group member for specified criteria
            foreach ($groupMembers as $groupMember) {
                $targetEvaluatee = $groupMember['User']['id'];
                
                if ($this->controller->Evaluation->saveRubricEvaluation($targetEvaluatee, $viewMode, $this->params, $targetCriteria)) {
                    // check whether comments are given, if not and it is required, send msg
                    $comments = $this->params['form'][$targetEvaluatee . 'comments'];
                    $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
                    $msg = array();
                    $sub = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
                    if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
                        $msg[] = __('some comments are missing', true);
                    }
                    if (empty($sub)) {
                        $msg[] = __('you still have to submit the evaluation with the Submit button below', true);
                    }
                    $suffix = empty($msg) ? '.' : ', but ' . implode(' and ', $msg) . '.';
                    $this->JsonResponse->withMessage('Your evaluation has been saved' . $suffix)->withStatus(200);
                } else {
                    //Found error. Validate the error why the Event->save() method returned false
                    // TBD: $this->validateErrors($this->Event);
                    $this->JsonResponse->withMessage('Your evaluation was not saved successfully')->withStatus(404);
                    break;
                }
            }
            return;
        }
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
                $this->setMixedEvaluationSubmit($event, $groupId, $studentId, $method);
                break;
            case 'PUT': // Update
                $this->setMixedEvaluationSubmit($event, $groupId, $studentId, $method);
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
        
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId, $userId);
        // $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $userId);
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
        // temp
        $json['submission'] = $submission;
        
        $this->JsonResponse->setContent($json)->withStatus(200);
    }
    
    private function setMixedEvaluationSubmit(array $event, string $groupId, string $studentId, string $method): void
    {
        $data = $this->data['data'];
        unset($this->data['data']);
        $mixeval = $this->controller->Mixeval->findById($data['template_id']);
        $groupEventId = $data['grp_event_id'];
        $evaluator = empty($studentId) ? $data['submitter_id'] : $studentId;
        $required = true;
        $failures = array();
        
        // check peer evaluation questions
        if ($mixeval['Mixeval']['peer_question'] > 0) {
            foreach ($this->data as $userId => $eval) {
                if (!isset($eval['Evaluation'])) {
                    continue; // only has self-evaluation so skip
                }
                if (!empty($studentId)) {
                    $eval['Evaluation']['evaluator_id'] = $studentId;
                }
                $eventId = $eval['Evaluation']['event_id'];
                $groupId = $eval['Evaluation']['group_id'];
                $evaluatee = $eval['Evaluation']['evaluatee_id'];
                /*if (!$this->validMixevalEvalComplete($this->params['form'])) {
                    $this->redirect('/evaluations/makeEvaluation/'.$eventId.'/'.$groupId);
                    return;
                }*/
                if (!$this->controller->Evaluation->saveMixevalEvaluation($eval)) {
                    $failures[] = $userId;
                }
                $evalMixeval = $this->controller->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
                    $groupEventId, $evaluator, $evaluatee);
                $evaluation = !empty($evalMixeval['EvaluationMixevalDetail']) ? $evalMixeval['EvaluationMixevalDetail'] : null;
                $details = Set::combine($evaluation, '{n}.question_number', '{n}');
                foreach ($mixeval['MixevalQuestion'] as $ques) {
                    if ($ques['required'] && !$ques['self_eval'] && !isset($details[$ques['question_num']])) {
                        $required = false;
                    }
                }
            }
        }
        // check self evaluation questions
        // second condition to exclude tutors
        if ($mixeval['Mixeval']['self_eval'] > 0 && isset($this->data[$evaluator]['Self-Evaluation'])) {
            $evaluatee = empty($studentId) ? User::get('id') : $studentId;
            $eventId = $this->data[$evaluatee]['Self-Evaluation']['event_id'];
            $groupId = $this->data[$evaluatee]['Self-Evaluation']['group_id'];
            $this->data[$evaluatee]['Evaluation'] = $this->data[$evaluatee]['Self-Evaluation'];
            if (!$this->controller->Evaluation->saveMixevalEvaluation($this->data[$evaluatee])) {
                $failures[] = $evaluatee;
            }
            $evalMixeval = $this->controller->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
                $groupEventId, $evaluator, $evaluatee);
            $evaluation = !empty($evalMixeval['EvaluationMixevalDetail']) ? $evalMixeval['EvaluationMixevalDetail'] : null;
            $details = Set::combine($evaluation, '{n}.question_number', '{n}');
            foreach ($mixeval['MixevalQuestion'] as $ques) {
                if ($ques['required'] && $ques['self_eval'] && !isset($details[$ques['question_num']])) {
                    $required = false;
                }
            }
        }
        // success
        if (empty($failures)) {
            if ($required) {
                $evaluationSubmission = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
                if (empty($evaluationSubmission)) {
                    $this->controller->EvaluationSubmission->id = null;
                    $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEventId;
                    $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
                    $evaluationSubmission['EvaluationSubmission']['submitter_id'] = empty($studentId) ? $evaluator : $studentId;
                    $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
                    $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
                    if (!$this->controller->EvaluationSubmission->save($evaluationSubmission)) {
                        $this->JsonResponse->withMessage('Error: Unable to submit the evaluation. Please try again.')->withStatus(404);
                    }
                }
                CaliperHooks::submit_mixeval($eventId, $evaluator, $groupEventId, $groupId);
                
                //checks if all members in the group have submitted the number of
                //submission equals the number of members means that this group is ready to review
                $evaluators = $this->controller->GroupsMembers->findAllByGroupId($groupId);
                $evaluators = Set::extract('/GroupsMembers/user_id', $evaluators);
                $memberCompletedNo = $this->controller->EvaluationSubmission->find('count', array(
                    'conditions' => array('grp_event_id' => $groupEventId, 'submitter_id' => $evaluators)
                ));
                $evaluators = count($evaluators);
                //Check to see if all members are completed this evaluation
                if ($memberCompletedNo == $evaluators) {
                    $this->controller->GroupEvent->id = $groupEventId;
                    $groupEvent['GroupEvent']['marked'] = 'to review';
                    if (!$this->controller->GroupEvent->save($groupEvent)) {
                        $this->JsonResponse->withMessage('Error')->withStatus(404);
                    } else {
                        $this->JsonResponse->withMessage('Your Evaluation was submitted successfully.')->withStatus(201);
                        exit;
                    }
                } else {
                    if ($method === 'POST') {
                        $this->JsonResponse->withMessage('Your Evaluation was submitted successfully.')->withStatus(201);
                    } elseif ($method === 'PUT') {
                        $this->JsonResponse->withMessage('Your Evaluation was saved successfully.')->withStatus(200);
                    }
                    exit;
                }
            } else {
                // Supposed to go here
                $this->JsonResponse->withMessage('Your answers have been saved. Please answer all the required questions before it can be considered submitted.')->withStatus(200);
            }
        } else {
            $failures = $this->controller->User->getFullNames($failures);
            $failures = join(' and ', array_filter(array_merge(array(join(
                ', ', array_slice($failures, 0, -1))), array_slice($failures, -1))));
            $this->JsonResponse->withMessage('Error: It was unsuccessful to save evaluation(s) for ' . $failures)->withStatus(404);
        }
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
