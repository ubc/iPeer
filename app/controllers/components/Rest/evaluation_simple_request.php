<?php
App::import('Lib', 'caliper');

class EvaluationSimpleRequestComponent extends CakeObject
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
        $this->Sanitize = new Sanitize;
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
            case 'GET': // Read
                $this->get($event, $groupId, $studentId);
                break;
            case 'POST': // Submit Peer Review
                $this->set($event, $groupId, $studentId, $method);
                break;
            case 'PUT': // Save Draft
                $this->set($event, $groupId, $studentId, $method);
                break;
            case 'PATCH': // Auto Save
                $this->set($event, $groupId, $studentId, $method);
                break;
            default:
                http_response_code(405);
                header('Allow, GET, POST, PUT');
                break;
        }
    }
    
    public function processCollectionRequest($method)
    { // GET, POST, PUT, PATCH, and DELETE
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
     * SimpleEvaluationRequest
     */
    private function list()
    {
        http_response_code(200);
        echo json_encode(['id' => '1', 'method' => 'list', 'process' => 'SimpleEvaluationRequest']);
    }
    
    private function create($request): void
    {
    
    }
    
    private function get($event, $groupId, $studentId = null): void
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
            $this->JsonResponse->withMessage('Error: Evaluation is unavailable')->withStatus(204);
            return;
        }
        
        // students can submit again
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, empty($studentId) ? User::get('id') : $studentId);
        $evaluation = []; // JK:: ADDED
        if (!empty($submission)) {
            // load the submitted values
            $evaluation = $this->controller->EvaluationSimple->getSubmittedResultsByGroupIdEventIdAndEvaluator($groupId, $eventId, empty($studentId) ? User::get('id') : $studentId);
            /** JK:: LOOK into the following:
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
        
        //Setup the courseId to session
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
        
        $dataToJson = [
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
        ];
        $this->JsonHandler->formatSimpleEvaluation($dataToJson);
    }
    
    private function set(array $event, string $groupId, $studentId = null, string $method)
    {
        $data = $this->params['data'];
        $form = $this->params['form'];
        
        $eventId = $form['event_id'];
        $groupId = $form['group_id'];
        $courseId = $form['course_id'];
        $evaluator = $data['Evaluation']['evaluator_id'];
        // check that all points given are not negative numbers
        $minimum = min($form['points']);
        if ($minimum < 0) {
            $this->JsonResponse->setContent([])->withMessage('One or more of your group members have negative points. Please use positive numbers.')->withStatus(404);
            return;
        }
        //Get the target event
        $this->controller->Event->id = $eventId;
        $event = $this->controller->Event->read();
        //Get simple evaluation tool
        $this->controller->SimpleEvaluation->id = $event['Event']['template_id'];
        //Get the target group event
        //
        $groupEvent = $this->controller->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
        //Get the target event submission
        $groupEventId = $groupEvent['GroupEvent']['id'];
        $evaluationSubmission = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
        if ($this->controller->Evaluation->saveSimpleEvaluation($this->params, $groupEvent, $evaluationSubmission)) {
            // CaliperHooks::submit_simple_evaluation($eventId, $evaluator, $groupEvent['GroupEvent']['id'], $groupId);
            // $this->JsonResponse->withMessage('Your Evaluation was submitted successfully.')->withStatus(200);
            
            $msg = [];
            if ($method === 'POST') {
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
                    $msg[] = __('submitted successfully.', true);
                } else {
                    $this->controller->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
                    $evaluationSubmission['EvaluationSubmission']['submitted'] = '1';
                    if (!$this->controller->EvaluationSubmission->save($evaluationSubmission)) {
                        $this->JsonResponse->withMessage('Error: Unable to submit the evaluation. Please try again.')->withStatus(404);
                    }
                    $msg[] = __('submitted.', true);
                }
            } elseif ($method === 'PUT' || $method === 'PATCH') {
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
                    $msg[] = __('saved as draft.', true);
                    $msg[] = __('you still have to submit the evaluation with the Submit Peer Review button', true);
                } else {
                    $this->controller->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
                    if (!$this->controller->EvaluationSubmission->save($evaluationSubmission)) {
                        $this->JsonResponse->withMessage('Error: Unable to submit the evaluation. Please try again.')->withStatus(404);
                    }
                    $msg[] = __('saved successfully.', true);
                }
            }
    
            $this->JsonResponse->withMessage('Your evaluation has been ' . implode($msg))->withStatus(200);
            
        } else {
            // $this->validateErrors($this->controller->Event);
            $this->JsonResponse->withMessage('Save Evaluation failure.')->withStatus(500);
        }
    }
}
