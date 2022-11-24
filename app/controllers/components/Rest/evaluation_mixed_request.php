<?php
App::import('Lib', 'caliper');

use caliper\CaliperHooks;

class EvaluationMixedRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];
    public $components = ['RequestHandler', 'Evaluation', 'JsonHandler', 'JsonResponse'];
    
    public $controller;
    public $settings;
    public $params;
    public $data;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
        $this->params = $controller->params;
        $this->data = $controller->data;
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
     * MixedEvaluationRequest
     */
    private function list()
    {
        http_response_code(200);
        echo json_encode(['action' => 'list']);
    }
    
    private function create()
    {
        http_response_code(200);
        echo json_encode(['action' => 'create']);
    }
    
    private function get($event, $groupId, $studentId = null)
    {
        $eventId = $event['Event']['id'];
        
        // invalid group id
        if (!is_numeric($groupId)) {
            $this->JsonResponse->withMessage('Error: Invalid Id')->withStatus(404);
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
        }
        
        // students can't submit outside of release date range
        $event = $this->controller->Event->getEventByIdGroupId($eventId, $groupId);
        $now = time();
        
        if ($now < strtotime($event['Event']['release_date_begin']) ||
            $now > strtotime($event['Event']['release_date_end'])) {
            $this->JsonResponse->withMessage('Error: Evaluation is unavailable')->withStatus(404);
        }
        // NOTE:: included the GroupEventId to get the exact submission record
        // otherwise will pull the first match with 2 conditions which is inaccurate
        // $sub = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId, $userId, $event['GroupEvent']['id']);
        $sub = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $userId);;
        $members = $this->controller->GroupsMembers->findAllByGroupId($groupId);
        $penalty = $this->controller->Penalty->getPenaltyByEventId($eventId);
        $penaltyDays = $this->controller->Penalty->getPenaltyDays($eventId);
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
        
        $data = [
            'event' => $event,
            'penaltyFinal' => $this->controller->Penalty->getPenaltyFinal($eventId),
            'penaltyDays' => $this->controller->Penalty->getPenaltyDays($eventId),
            'penalty' => $this->controller->Penalty->getPenaltyByEventId($eventId),
            //
            'questions' => $questions,
            'submission' => (array)$sub ?? [],
            'evaluation' => [],
            //
            'userId' => empty($studentId) ? $userId : $studentId,
            'groupMembers' => $this->controller->Evaluation->loadMixEvaluationDetail($event),
            'memberIDs' => $members,
            'memberCount' => count($members),
            //
            'evaluateeCount' => count($members),
            'courseId' => $event['Event']['course_id'],
            'allDone' => [],
            'comReq' => [],
            //
            'enrol' => $enrol,
            'self' => $self,
            'mixeval' => $mixeval,
        ];
        $this->JsonHandler->formatMixedEvaluation($data);
    }
    
    private function set(array $event, string $groupId, $studentId=null, string $method)
    {
        $this->autoRender = false;
        $eventId = $event['Event']['id'];
    
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
                $this->processEvaluationSubmission($eventId, $evaluator, $groupEventId, $groupId, $method);
            
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
                        switch ($method) {
                            case 'POST':
                                $this->JsonResponse->withMessage('Your Evaluation was submitted successfully.')->withStatus(200);
                                break;
                            case 'PUT':
                            case 'PATCH':
                                $this->JsonResponse->withMessage('Your Evaluation was saved successfully.')->withStatus(200);
                                break;
                        }
                    }
                } else {
                    switch ($method) {
                        case 'POST':
                            $this->JsonResponse->withMessage('Your Evaluation was submitted successfully.')->withStatus(200);
                            break;
                        case 'PUT':
                        case 'PATCH':
                            $this->JsonResponse->withMessage('Your Evaluation was saved successfully.')->withStatus(200);
                            break;
                    }
                }
            } else {
                $this->processEvaluationSubmission($eventId, $evaluator, $groupEventId, $groupId, $method);
                // Supposed to go here
                $this->JsonResponse->withMessage('Your answers have been saved. Please answer all the required questions before it can be considered submitted.')->withStatus(200);
            }
        } else {
            $failures = $this->controller->User->getFullNames($failures);
            $failures = join(' and ', array_filter(array_merge(array(join(
                ', ', array_slice($failures, 0, -1))), array_slice($failures, -1))));
            $this->JsonResponse->withMessage('Error: It was unsuccessful to save evaluation(s) for '.$failures)->withStatus(400);
        }
    }
    
    
    /**
     * processEvaluationSubmission
     */
    private function processEvaluationSubmission( string$eventId, string $evaluator, string $groupEventId, $groupId, string $method): void
    {
        $evaluationSubmission = $this->controller->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
        if (empty($evaluationSubmission)) {
            $this->controller->EvaluationSubmission->id = null;
            $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
            $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEventId;
            $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
            $evaluationSubmission['EvaluationSubmission']['submitter_id'] = $evaluator;
            switch ($method) {
                case 'POST':
                    $evaluationSubmission['EvaluationSubmission']['submitted'] = '1';
                    break;
                case 'PUT':
                case 'PATCH':
                    $evaluationSubmission['EvaluationSubmission']['submitted'] = '0';
                    break;
            }
            if (!$this->controller->EvaluationSubmission->save($evaluationSubmission)) {
                $this->JsonResponse->withMessage('Error: Unable to submit the evaluation. Please try again.')->withStatus(404);
            }
        } else {
            if($method === 'POST') {
                $this->controller->EvaluationSubmission->id = null;
                $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
                $evaluationSubmission['EvaluationSubmission']['submitted'] = '1';
            }
        }
        CaliperHooks::submit_mixeval($eventId, $evaluator, $groupEventId, $groupId);
    }
}
