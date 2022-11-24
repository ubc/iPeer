<?php
App::import('Lib', 'caliper');

class EvaluationRubricRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];
    public $components = ['RequestHandler', 'Evaluation', 'JsonHandler', 'JsonResponse'];
    
    public $controller;
    public $settings;
    public $params;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
        $this->params = $controller->params;
    }
    
    public function __construct()
    {
        parent::__construct();
    }
    
    function pre_r($val)
    {
        echo '<pre>';
        print_r($val);
        echo '</pre>';
    }
    
    public function processResourceRequest($method, $eventId, $groupId, $studentId = null)
    {
        $event = $this->controller->Event->getEventByIdGroupId($eventId, $groupId);
        switch ($method) {
            case 'GET':
                $this->get($event, $groupId, $studentId);
                break;
            case 'POST': // Submit Peer Review
                $this->set($event, $groupId, $studentId, $method);
                break;
            case 'PUT': // Save Draft
                //$this->pre_r($method);$this->pre_r($this->params); die();
                $this->set($event, $groupId, $studentId, $method);
                break;
            case 'PATCH': // maybe Auto Save
                $this->set($event, $groupId, $studentId, $method);
                break;
            default:
                http_response_code(405);
                header('Allow, GET, POST, PUT');
                break;
        }
    }
    
    public function processCollectionRequest($method)
    {
        switch ($method) {
            case 'GET':
                $this->list();
                break;
            case 'POST':
                $this->create();
                break;
            default:
                http_response_code(405);
                header('Allow: GET, POST');
                break;
        }
    }
    
    /**
     * RubricEvaluationRequest
     */
    private function list()
    {
        http_response_code(200);
        echo json_encode(['method' => 'list', 'message' => 'Not available. Instructor view.']);
    }
    
    private function create()
    {
        http_response_code(200);
        echo json_encode(['method' => 'create', 'message' => 'Not available. Instructor view.']);
    }
    
    // TODO::
    private function get($event, $groupId, $studentId=null)
    {
        $now = time();
        $userId = User::get('id');
        $eventId = $event['Event']['id'];
        $rubricId = $event['Event']['template_id'];
        $courseId = $event['Event']['course_id'];
        
        $groupEvents = $this->controller->GroupEvent->findAllByEventId($eventId);
        $groups = Set::extract('/GroupEvent/group_id', $groupEvents);
        
        // if group id provided does not match the group id the user belongs to or
        // template type is not rubric - they are redirected
        if (!is_numeric($groupId) || !in_array($groupId, $groups) ||
            !$this->controller->GroupsMembers->checkMembershipInGroup($groupId, empty($studentId) ? User::get('id') : $studentId)) {
            $this->JsonResponse->withMessage('Error: Invalid Id')->withStatus(404);
        }
        
        // students can't submit outside of release date range
        if ($now < strtotime($event['Event']['release_date_begin']) || $now > strtotime($event['Event']['release_date_end'])) {
            $this->JsonResponse->withMessage('Error: Evaluation is unavailable')->withStatus(404);
        }
        
        // Set up rubric evaluation viewData
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
        // TODO:: Check submissions status and alert students accordingly
        $allDone = ($evaluated === $rubricDetail['evaluateeCount']);
        $comReq = ($commentsNeeded && $event['Event']['com_req']); // part of the evaluation settings [set to false not required]

        // if($commentsNeeded && $event['Event']['com_req']) {
        //   $this->JsonResponse->withMessage('Comments are required.')->withStatus(404);
        // }
        
        $evaluationSubmission = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($event['GroupEvent']['id'], $userId);
        
        $dataToJson = [
            'event' => $event,
            'penaltyFinal' => $this->controller->Penalty->getPenaltyFinal($eventId),
            'penaltyDays' => $this->controller->Penalty->getPenaltyDays($eventId),
            'penalty' => $this->controller->Penalty->getPenaltyByEventId($eventId),
            //
            'questions' => $rubric,
            'answers' => $rubricEvalViewData,
            'submission' => (array)$evaluationSubmission ?? [],
            'evaluation' => (array)$rubricEvalViewData ?? [],
            //
            'userId' => empty($studentId) ? $userId : $studentId,
            'groupMembers' => $rubricDetail['groupMembers'],
            'memberIDs' => implode(',', Set::extract('/User/id', $rubricDetail['groupMembers'])),
            //
            'evaluateeCount' => $rubricDetail['evaluateeCount'],
            'courseId' => $courseId,
            'rubricId' => $rubricId,
            'allDone' => $allDone,
            'comReq' => $comReq,
        ];
        
        $this->JsonHandler->formatRubricEvaluation($dataToJson);
    }
    
    private function set(array $event, string $groupId, $studentId=null, string $method)
    {
        $eventId = $this->params['form']['event_id']??null;
        $groupId = $this->params['form']['group_id']??null;
        $groupEventId = $this->params['form']['group_event_id']??null;
        $evaluator = User::get('id')??null;
        
        if(!isset($eventId) || !isset($groupEventId) || !isset($evaluator)) {
            $this->JsonResponse->withMessage('Internal Error')->withStatus(500);
            exit;
        }
    
        $event = $this->controller->Event->findById($eventId);
        // check if questions were answered
//        if(isset($this->params['form']['5criteria_points_1'])) {
//            $this->JsonResponse->withMessage('Your evaluation was not saved. Please make sure the rubric questions are answered.')->withStatus(404);
//            return;
//        }
        // Student View Mode
        if(isset($this->params['form']['memberIDs'])){
            if (is_string($this->params['form']['memberIDs'])) {
                $this->params['form']['memberIDs'] = array_unique(explode(',', $this->params['form']['memberIDs']));
            }
            
            $suffix='';
            foreach ($this->params['form']['memberIDs'] as $userId) {
                $msg=[];
                if ($this->controller->Evaluation->saveRubricEvaluation($userId, 0, $this->params)) {
                    // check whether comments are given, if not, and it is required, send msg
                    $comments = $this->params['form'][$userId.'comments'];
                    $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
                    if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
                        $msg[] = __('some comments are missing', true);
                    }
                    //
                    $evaluationSubmission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $evaluator);
                    //if (empty($evaluationSubmission)) {
                    //    $msg[] = __('you still have to submit the evaluation with the Submit Peer Review button', true);
                    //}
                    
                    if($method === 'POST') {
                        if (empty($evaluationSubmission)) {
                            $this->controller->EvaluationSubmission->id = null;
                            $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEventId;
                            $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
                            $evaluationSubmission['EvaluationSubmission']['submitter_id'] = empty($studentId) ? $evaluator : null;
                            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
                            $evaluationSubmission['EvaluationSubmission']['submitted'] = '1';
                            if (!$this->controller->EvaluationSubmission->save($evaluationSubmission)) {
                                $this->JsonResponse->withMessage('Error: Unable to save the evaluation. Please try again.')->withStatus(404);
                            }
                            $msg[] = __('Your evaluation has been submitted successfully', true);
                        } else {
                            $this->controller->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
                            $evaluationSubmission['EvaluationSubmission']['submitted'] = '1';
                            if (!$this->controller->EvaluationSubmission->save($evaluationSubmission)) {
                                $this->JsonResponse->withMessage('Error: Unable to submit the evaluation. Please try again.')->withStatus(404);
                            }
                            $msg[] = __('Your evaluation has been submitted', true);
                        }
                    }
                    elseif($method === 'PUT' || $method === 'PATCH') {
                        if (empty($evaluationSubmission)) {
                            $this->controller->EvaluationSubmission->id = null;
                            $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEventId;
                            $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
                            $evaluationSubmission['EvaluationSubmission']['submitter_id'] = empty($studentId) ? $evaluator : null;
                            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
                            $evaluationSubmission['EvaluationSubmission']['submitted'] = '0';
                            if (!$this->controller->EvaluationSubmission->save($evaluationSubmission)) {
                                $this->JsonResponse->withMessage('Error: Unable to save the evaluation. Please try again.')->withStatus(404);
                            }
                            $msg[] = __('Your evaluation has been saved as draft.', true);
                            $msg[] = __('you still have to submit the evaluation with the Submit Peer Review button', true);
                        } else {
                            $this->controller->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
                            if (!$this->controller->EvaluationSubmission->save($evaluationSubmission)) {
                                $this->JsonResponse->withMessage('Error: Unable to submit the evaluation. Please try again.')->withStatus(404);
                            }
                             $msg[] = __('Your evaluation has been saved', true);
                        }
                    }
                    
                    // $suffix = empty($msg) ? '.' : ', but '.implode(' and ', $msg).'.';
                    $suffix = implode(' ', $msg).'.';
                } else {
                    $this->JsonResponse->withMessage('Your evaluation was not saved')->withStatus(404);
                    return;
                }
            }
            // $this->JsonResponse->withMessage('Your evaluation has been '.$suffix)->withStatus(200);
            $this->JsonResponse->withMessage($suffix)->withStatus(200);
        }
        // Criteria View Mode
        elseif(isset($this->params['form']['criteriaIDs'])){
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
            }
            else {
                $viewMode = 0;
            }
        
            // Loop through and save every group member for specified criteria
            foreach ($groupMembers as $groupMember){
                $targetEvaluatee = $groupMember['User']['id'];
    
                $msg = [];
                
                if ($this->controller->Evaluation->saveRubricEvaluation($targetEvaluatee, $viewMode, $this->params, $targetCriteria)) {
                    // check whether comments are given, if not and it is required, send msg
                    $comments = $this->params['form'][$targetEvaluatee.'comments']??'';
                    $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
                    if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
                        $msg[] = __('some comments are missing', true);
                    }
                    //
                    $evaluationSubmission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $evaluator);
                    if (empty($evaluationSubmission)) {
                        $msg[] = __('you still have to submit the evaluation with the Submit button below', true);
                    }
                    $suffix = empty($msg) ? '.' : ', but '.implode(' and ', $msg).'.';
                    $this->JsonResponse->withMessage('Your evaluation has been saved')->withStatus(200);
                } else {
                    $this->JsonResponse->withMessage('Your evaluation was not saved successfully')->withStatus(200);
                    break;
                }
            }
            return;
        }
    }
    
    private function complete(array $event, string $groupId, $studentId = null, string $method): void
    {
    
    }
    
    private function _create($request)
    {
        //$this->pre_r($request); die();
        $eventId = $request['params']['form']['event_id'];
        $groupId = $request['params']['form']['group_id'];
        $event = $this->controller->Event->findById($eventId);
        
        // Student View Mode
        if (isset($request['params']['form']['memberIDs'])) {
            // find out whose evaluation is submitted
            $memberIds = array_unique($request['params']['form']['memberIDs']);
            
            $suffix = '';
            foreach ($memberIds as $targetEvaluatee) {
                if ($this->controller->Evaluation->saveRubricEvaluation($targetEvaluatee, 0, $request['params'])) {
                    // check whether comments are given, if not and it is required, send msg
                    $comments = $request['params']['form'][$targetEvaluatee . 'comments'];
                    $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
                    $msg = array();
                    $sub = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
                    if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
                        $msg[] = 'some comments are missing';
                    }
                    if (empty($sub) || $sub['EvaluationSubmission']['submitted'] === '0') {
                        $msg[] = 'you still have to submit the evaluation with the Submit Peer Review button.';
                    }
                    $suffix = empty($msg) ? '.' : ', but ' . implode(' and ', $msg) . '.';
                    // return;
                } else {
                    //Found error
                    //Validate the error why the Event->save() method returned false
                    // $this->validateErrors($this->controller->Event);
                    $this->JsonResponse->withMessage('Your evaluation was not saved successfully')->withStatus(500);
                    return;
                }
            }
            $this->JsonResponse->withMessage('Your evaluation has been saved' . $suffix)->withStatus(200);
        } // Criteria View Mode
        elseif (isset($request['params']['form']['criteriaIDs'])) {
            $this->JsonResponse->withMessage('Criteria View Mode')->withStatus(200);
            
            // find out the criteria submitted
            // general comments section should be given value of null
            $targetCriteria = null;
            foreach ($request['params']['form']['criteriaIDs'] as $criteriaId) {
                if (isset($request['params']['form'][$criteriaId])) {
                    $targetCriteria = $criteriaId;
                    break;
                }
            }
            
            $evaluator = $request['params']['data']['Evaluation']['evaluator_id'];
            $groupMembers = $this->controller->User->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], $evaluator);
            
            // Criteria will be null if the submitted section was 'General Comments'
            if ($targetCriteria != null) {
                $viewMode = 1;
            } else {
                $viewMode = 0;
            }
            
            // Loop through and save every group member for specified criteria
            $suffix = '';
            foreach ($groupMembers as $groupMember) {
                $targetEvaluatee = $groupMember['User']['id'];
                
                if ($this->controller->Evaluation->saveRubricEvaluation($targetEvaluatee, $viewMode, $request['params'], $targetCriteria)) {
                    // check whether comments are given, if not and it is required, send msg
                    $comments = $request['params']['form'][$targetEvaluatee . 'comments'];
                    $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
                    $msg = array();
                    $sub = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
                    if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
                        $msg[] = 'some comments are missing';
                    }
                    if (empty($sub) || $sub['EvaluationSubmission']['submitted'] === '0') {
                        $msg[] = 'you still have to submit the evaluation with the Submit button below';
                    }
                    $suffix = empty($msg) ? '.' : ', but ' . implode(' and ', $msg) . '.';
                } else {
                    //Found error
                    //Validate the error why the Event->save() method returned false
                    // $this->validateErrors($this->controller->Event);
                    $this->JsonResponse->withMessage('Your evaluation was not saved successfully')->withStatus(500);
                    break;
                }
            }
            $this->JsonResponse->withMessage('Your evaluation has been saved' . $suffix)->withStatus(200);
            return;
        }
        
    }
    
}
