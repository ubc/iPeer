<?php
App::import('Lib', 'caliper');

use caliper\CaliperHooks;

class EvaluationMixedRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];  //['Evaluation','GroupEvent', 'GroupsMembers', 'Event', 'Rubric', 'Penalty', 'EvaluationSubmission'];
    public $components = ['RequestHandler', 'Evaluation', 'JsonHandler', 'RestResponseHandler'];
    
    /**
     * @var bool|object
     */
    // private $User;
    // private $Group;
    
    private $Event;
    private $Mixeval;
    private $UserEnrol;
    private $EvaluationMixeval;
    private $MixevalQuestion;
    private $Penalty;
    private $GroupEvent;
    private $GroupsMembers;
    private $EvaluationSubmission;
    
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
        
        $this->User = ClassRegistry::init('User');
        // $this->Group = ClassRegistry::init('Group');
        $this->Event = ClassRegistry::init('Event');
        $this->Mixeval = ClassRegistry::init('Mixeval');
        $this->UserEnrol = ClassRegistry::init('UserEnrol');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->MixevalQuestion = ClassRegistry::init('MixevalQuestion');
        $this->Penalty = ClassRegistry::init('Penalty');
        $this->GroupEvent = ClassRegistry::init('GroupEvent');
        $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
    }
    
    
    function pre_r($val)
    {
        echo '<pre>';
        print_r($val);
        echo '</pre>';
    }
    
    public function processResourceRequest($method, $eventId, $groupId, $studentId = null)
    {
        $event = $this->Event->getEventByIdGroupId($eventId, $groupId);
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
            $this->RestResponseHandler->toJson('Error: Invalid Id', 404);
        }
        
        $courseId = $this->Event->getCourseByEventId($eventId);
        
        $group = array();
        $group_events = $this->GroupEvent->getGroupEventByEventId($eventId);
        $userId = empty($studentId) ? User::get('id') : $studentId;
        foreach ($group_events as $events) {
            if ($this->GroupsMembers->checkMembershipInGroup($events['GroupEvent']['group_id'], $userId) !== 0) {
                $group[] = $events['GroupEvent']['group_id'];
            }
        }
        
        // if group id provided does not match the group id the user belongs to
        if (!in_array($groupId, $group)) {
            $this->RestResponseHandler->toJson('Error: Invalid Id', 404);
        }
        
        // students can't submit outside of release date range
        $event = $this->Event->getEventByIdGroupId($eventId, $groupId);
        $now = time();
        
        if ($now < strtotime($event['Event']['release_date_begin']) ||
            $now > strtotime($event['Event']['release_date_end'])) {
            $this->RestResponseHandler->toJson('Error: Evaluation is unavailable', 404);
        }
        // NOTE:: had to include the GroupEventId to get the exact submission record
        // NOTE:: otherwise will pull the first match with 2 conditions which is inaccurate
        $sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId, $userId, $event['GroupEvent']['id']);
        //$sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId, $userId);
        $members = $this->GroupsMembers->findAllByGroupId($groupId);
        
        $penalty = $this->Penalty->getPenaltyByEventId($eventId);
        $penaltyDays = $this->Penalty->getPenaltyDays($eventId);
        $penaltyFinal = $this->Penalty->getPenaltyFinal($eventId);
        $enrol = $this->UserEnrol->find('count', array(
            'conditions' => array('user_id' => $userId, 'course_id' => $courseId)
        ));
        $self = $this->EvaluationMixeval->find('first', array(
            'conditions' => array('evaluator' => $userId, 'evaluatee' => $userId, 'event_id' => $eventId)
        ));
        $questions = $this->MixevalQuestion->findAllByMixevalId($event['Event']['template_id']);
        $mixeval = $this->Mixeval->find('first', array(
            'conditions' => array('id' => $event['Event']['template_id']), 'contain' => false, 'recursive' => 2));
        
        $dataToJson = [
            'event' => $event,
            'penaltyFinal' => $this->Penalty->getPenaltyFinal($eventId),
            'penaltyDays' => $this->Penalty->getPenaltyDays($eventId),
            'penalty' => $this->Penalty->getPenaltyByEventId($eventId),
            //
            'questions' => $questions,
            'submission' => (array)$sub ?? [],
            'evaluation' => [],
            //
            'userId' => empty($studentId) ? $userId : $studentId,
            'groupMembers' => $this->Evaluation->loadMixEvaluationDetail($event),
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
        $this->JsonHandler->formatMixedEvaluation($dataToJson);
    }
    
    private function set(array $event, string $groupId, $studentId = null, string $method)
    {
        if(!isset($this->params['data'])) return;
        
        $data = $this->params['data'];
        unset($this->params['data']);
        $mixeval = $this->Mixeval->findById($data['data']['template_id']);
        $groupEventId = $data['data']['grp_event_id'];
        $evaluator = empty($studentId) ? $data['data']['submitter_id'] : $studentId;
        $required = true;
        $failures = array();
    
        // check peer evaluation questions
        if ($mixeval['Mixeval']['peer_question'] > 0) {
            foreach ($data as $userId => $eval) {
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
                if (!$this->Evaluation->saveMixevalEvaluation($eval)) {
                    $failures[] = $userId;
                }
                $evalMixeval = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee($groupEventId, $evaluator, $evaluatee);
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
        if ($mixeval['Mixeval']['self_eval'] > 0 && isset($data[$evaluator]['Self-Evaluation'])) {
            $evaluatee = empty($studentId) ? User::get('id') : $studentId;
            $eventId = $data[$evaluatee]['Self-Evaluation']['event_id'];
            $groupId = $data[$evaluatee]['Self-Evaluation']['group_id'];
            $data[$evaluatee]['Evaluation'] = $data[$evaluatee]['Self-Evaluation'];
            if (!$this->Evaluation->saveMixevalEvaluation($data[$evaluatee])) {
                $failures[] = $evaluatee;
            }
            $evalMixeval = $this->EvaluationMixeval->getEvalMixevalByGrpEventIdEvaluatorEvaluatee(
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
                $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
                if (empty($evaluationSubmission)) {
                    $this->EvaluationSubmission->id = null;
                    $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEventId;
                    $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
                    $evaluationSubmission['EvaluationSubmission']['submitter_id'] = empty($studentId) ? $evaluator : $studentId;
                    $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
                    $evaluationSubmission['EvaluationSubmission']['submitted'] = 1;
                    if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
                        $this->RestResponseHandler->toJson('Error: Unable to submit the evaluation. Please try again.', 404);
                    }
                }
                CaliperHooks::submit_mixeval($eventId, $evaluator, $groupEventId, $groupId);
            
                //checks if all members in the group have submitted the number of
                //submission equals the number of members means that this group is ready to review
                $evaluators = $this->GroupsMembers->findAllByGroupId($groupId);
                $evaluators = Set::extract('/GroupsMembers/user_id', $evaluators);
                $memberCompletedNo = $this->EvaluationSubmission->find('count', array(
                    'conditions' => array('grp_event_id' => $groupEventId, 'submitter_id' => $evaluators)
                ));
                $evaluators = count($evaluators);
                //Check to see if all members are completed this evaluation
                if ($memberCompletedNo == $evaluators) {
                    $this->GroupEvent->id = $groupEventId;
                    $groupEvent['GroupEvent']['marked'] = 'to review';
                    if (!$this->GroupEvent->save($groupEvent)) {
                        $this->RestResponseHandler->toJson('Error', 404);
                    } else {
                        $this->RestResponseHandler->toJson('Your Evaluation was submitted successfully.', 200);
                    }
                } else {
                    $this->RestResponseHandler->toJson('Your Evaluation was submitted successfully.', 200);
                }
            } else {
                // Supposed to go here
                $this->RestResponseHandler->toJson('Your answers have been saved. Please answer all the required questions before it can be considered submitted.', 200);
            }
        } else {
            $failures = $this->User->getFullNames($failures);
            $failures = join(' and ', array_filter(array_merge(array(join(
                ', ', array_slice($failures, 0, -1))), array_slice($failures, -1))));
            $this->RestResponseHandler->toJson('It was unsuccessful to save evaluation(s) for '.$failures, 404);
        }
    }
}
