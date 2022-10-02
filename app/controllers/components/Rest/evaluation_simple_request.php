<?php
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

class EvaluationSimpleRequestComponent extends CakeObject
{
  public $Sanitize;
  public $uses = [];
  public $components = ['RequestHandler', 'Evaluation', 'JsonHandler', 'RestResponseHandler'];
  
  /**
   * @var bool|object
   */
  private $User;
  private $Event;
  private $EvaluationSimple;
  private $SimpleEvaluation;
  private $Penalty;
  private $GroupEvent;
  private $GroupsMembers;
  private $EvaluationSubmission;
  
  public $controller;
  public $settings;
  public $params;
  
  public function initialize($controller, $settings) {
    $this->controller = $controller;
    $this->settings = $settings;
    $this->params = $controller->params;
  }
  
  public function __construct()
  {
    $this->Sanitize = new Sanitize;
    $this->User = ClassRegistry::init('User');
    $this->Event = ClassRegistry::init('Event');
    $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
    $this->SimpleEvaluation = ClassRegistry::init('SimpleEvaluation');
    $this->Penalty = ClassRegistry::init('Penalty');
    $this->GroupEvent = ClassRegistry::init('GroupEvent');
    $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
    $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
    parent::__construct();
  }
  
  function pre_r($val) {
    echo '<pre>';
    print_r($val);
    echo  '</pre>';
  }
  
  public function processResourceRequest($method, $eventId, $groupId, $studentId=null)
  {
    $event = $this->Event->getEventByIdGroupId($eventId, $groupId);
    switch ($method) {
      case 'GET':
        $this->get($event, $groupId, $studentId);
        break;
      case 'POST':
        if($this->params['form']['action'] === 'Save') {
          $this->set($event, $groupId, $studentId, '0');
        }
        elseif($this->params['form']['action'] === 'Submit') {
          $this->set($event, $groupId, $studentId, '1');
        }
        elseif($this->params['form']['action'] === 'Auto') {
          $this->set($event, $groupId, $studentId, '0');
        }
        break;
      default:
        http_response_code(405);
        header('Allow, GET, POST');
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
  
    // if (empty($this->params['data'])) { // REMOVE it already empty since its a GET
      $userId = User::get('id');
    
      $grpMem = $this->GroupsMembers->find('first', array(
        'conditions' => array('GroupsMembers.user_id' => empty($studentId) ? $userId : $studentId,
          'GroupsMembers.group_id' => $groupId)));
    
      // filter out users that don't have access to this eval, invalid ids
      if (empty($grpMem)) {
        $this->RestResponseHandler->toJson('Error: Invalid Id', 404);
        return;
      }
    
      $now = time();
      // students can't submit outside of release date range
      if ($now < strtotime($event['Event']['release_date_begin']) ||
        $now > strtotime($event['Event']['release_date_end'])) {
        $this->RestResponseHandler->toJson('Error: Evaluation is unavailable', 204);
        return;
      }
    
      // students can submit again
      $submission = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, empty($studentId) ? User::get('id') : $studentId);
      $evaluation = []; // JK:: ADDED
      if (!empty($submission)) {
        // load the submitted values
        $evaluation =  $this->EvaluationSimple->getSubmittedResultsByGroupIdEventIdAndEvaluator($groupId, $eventId, empty($studentId) ? User::get('id') : $studentId);
        /** JK:: LOOK into the following:
         * foreach ($evaluation as $eval) {
          $this->data['Evaluation']['point'.$eval['EvaluationSimple']['evaluatee']] = $eval['EvaluationSimple']['score'];
          $this->data['Evaluation']['comment_'.$eval['EvaluationSimple']['evaluatee']] = $eval['EvaluationSimple']['comment'];
        }*/
      }
    
      //Get the target event
      $eventId = $this->Sanitize->paranoid($eventId);
      $event = $this->Event->getEventByIdGroupId($eventId, $groupId);
      // REMOVE $this->set('event', $event);
    
    
      $penalty = $this->Penalty->getPenaltyByEventId($eventId);
      $penaltyDays = $this->Penalty->getPenaltyDays($eventId);
      $penaltyFinal = $this->Penalty->getPenaltyFinal($eventId);
      // REMOVE $this->set('penaltyFinal', $penaltyFinal);
      // REMOVE $this->set('penaltyDays', $penaltyDays);
      // REMOVE $this->set('penalty', $penalty);
    
    
      //Setup the courseId to session
      // REMOVE $this->set('courseId', $event['Event']['course_id']);
      $courseId = $event['Event']['course_id'];
      // REMOVE $this->set('title_for_layout', $this->Course->getCourseName($courseId, 'S').__(' > Evaluate Peers', true));
    
      //Set userId, first_name, last_name
      // REMOVE $this->set('userId', empty($studentId) ? $userId : $studentId);
      // REMOVE $this->set('fullName', $this->Auth->user('full_name'));
    
    
      //Get Members for this evaluation
      $groupMembers = $this->User->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], empty($studentId) ? $userId : $studentId);
      // REMOVE $this->set('groupMembers', $groupMembers);
    
      // enough points to distribute amongst number of members - 1 (evaluator does not evaluate him or herself)
      $numMembers = count($groupMembers);
      $simpleEvaluation = $this->SimpleEvaluation->find('first', array(
        'conditions' => array('id' => $event['Event']['template_id']),
        'contain' => false,
      ));
      $remaining = $simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers;
      //          if ($in['points']) $out['points']=$in['points']; //saves previous points
      //$points_to_ratio = $numMembers==0 ? 0 : 1 / ($simpleEvaluation['SimpleEvaluation']['point_per_member'] * $numMembers);
      //          if ($in['comments']) $out['comments']=$in['comments'];
  
      // REMOVE $this->set('remaining', $remaining);
      // REMOVE $this->set('evaluateeCount', $numMembers);
      // REMOVE $this->set('courseId', $courseId);
      // REMOVE $this->render('simple_eval_form');
  
    $dataToJson = [
      'event'             => $event,
      'penaltyFinal'      => $penaltyFinal,
      'penaltyDays'       => $penaltyDays,
      'penalty'           => $penalty,
      //
      'questions'         => $simpleEvaluation['SimpleEvaluation'],
      'submission'        => (array) $submission ?? [],
      'evaluation'        => (array) $evaluation ?? [],
      //
      'userId'            => empty($studentId) ? $userId : $studentId,
      'groupMembers'      => $groupMembers,
      'memberIDs'         => implode(',', Set::extract('/User/id', $groupMembers)),
      //
      'evaluateeCount'    => count($groupMembers),
      'remaining'         => $remaining,
      'courseId'          => $courseId,
      //
      'allDone'           => [],
      'comReq'            => [],
    ];
    $this->JsonHandler->formatSimpleEvaluation($dataToJson);
    // REMOVE closing if statement }
  }
  private function set(array $event, string $groupId, $studentId=null, string $submitted)
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
      $this->RestResponseHandler->toJson('One or more of your group members have negative points. Please use positive numbers.', 404, []);
      return;
    }
  
    //Get the target event
    $this->Event->id = $eventId;
    $event = $this->Event->read();
  
    //Get simple evaluation tool
    $this->SimpleEvaluation->id = $event['Event']['template_id'];
  
    //Get the target group event
    $groupEvent = $this->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
  
    //Get the target event submission
    // NOTE:: $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEvent['GroupEvent']['id'], $evaluator);
    // NOTE:: $this->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
    $groupEventId = $groupEvent['GroupEvent']['id'];
    $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
    if (empty($evaluationSubmission)) {
      $this->EvaluationSubmission->id = null;
      $evaluationSubmission['EvaluationSubmission']['grp_event_id'] = $groupEventId;
      $evaluationSubmission['EvaluationSubmission']['event_id'] = $eventId;
      $evaluationSubmission['EvaluationSubmission']['submitter_id'] = empty($studentId) ? $evaluator : null;
      $evaluationSubmission['EvaluationSubmission']['date_submitted'] = date('Y-m-d H:i:s');
      $evaluationSubmission['EvaluationSubmission']['submitted'] = $submitted;
      if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
        $this->RestResponseHandler->toJson('Error: Unable to save the evaluation. Please try again.', 404);
      }
    } else {
      // $updateEvaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
      $this->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
      $evaluationSubmission['EvaluationSubmission']['submitted'] = $submitted;
      if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
        $this->RestResponseHandler->toJson('Error: Unable to submit the evaluation. Please try again.', 404);
      }
    }
    // NOTE::END
    
  
    if ($this->Evaluation->saveSimpleEvaluation($this->params, $groupEvent, $evaluationSubmission)) {
      CaliperHooks::submit_simple_evaluation($eventId, $evaluator, $groupEvent['GroupEvent']['id'], $groupId);
      $this->RestResponseHandler->toJson('Your Evaluation was submitted successfully.', 200);
    } else {
      // $this->validateErrors($this->Event);
      $this->RestResponseHandler->toJson('Save Evaluation failure.', 500);
    }
  }
}
