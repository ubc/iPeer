<?php
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

class EvaluationRubricRequestComponent extends CakeObject
{
  public $Sanitize;
  public $uses = [];  //['Evaluation','GroupEvent', 'GroupsMembers', 'Event', 'Rubric', 'Penalty', 'EvaluationSubmission'];
  public $components = ['RequestHandler', 'Evaluation', 'JsonHandler', 'RestResponseHandler'];
  
  /**
   * @var bool|object
   */
  private $Event;
  private $Rubric;
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
    parent::__construct();
  
    $this->Event = ClassRegistry::init('Event');
    $this->Rubric = ClassRegistry::init('Rubric');
    $this->Penalty = ClassRegistry::init('Penalty');
    $this->GroupEvent = ClassRegistry::init('GroupEvent');
    $this->GroupsMembers = ClassRegistry::init('GroupsMembers');
    $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
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
          // $this->set($event, $groupId, $studentId, '1');
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
  private function get($event, $groupId, $studentId)
  {
    $now          = time();
    $userId       = User::get('id');
    $eventId      = $event['Event']['id'];
    $rubricId     = $event['Event']['template_id'];
    $courseId     = $event['Event']['course_id'];

    $groupEvents  = $this->GroupEvent->findAllByEventId($eventId);
    $groups       = Set::extract('/GroupEvent/group_id', $groupEvents);
    
    // if group id provided does not match the group id the user belongs to or
    // template type is not rubric - they are redirected
    if (!is_numeric($groupId) || !in_array($groupId, $groups) ||
      !$this->GroupsMembers->checkMembershipInGroup($groupId, empty($studentId) ? User::get('id') : $studentId)) {
      $this->RestResponseHandler->toJson('Error: Invalid Id', 404);
    }
  
    // students can't submit outside of release date range
    if ($now < strtotime($event['Event']['release_date_begin']) || $now > strtotime($event['Event']['release_date_end'])) {
      $this->RestResponseHandler->toJson('Error: Evaluation is unavailable', 404);
    }
    
    // Set up rubric evaluation viewData
    $rubric             = $this->Rubric->getRubricById($rubricId);
    $rubricEvalViewData = $this->Rubric->compileViewData($rubric);
    $rubricDetail       = $this->Evaluation->loadRubricEvaluationDetail($event, $studentId);
    
    $evaluated          = 0; // # of group members evaluated
    $commentsNeeded     = false;
    
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
        if (count($user['Evaluation']['EvaluationRubricDetail']) == count($rubricDetail['rubric']['RubricsCriteria'])){
          $evaluated++;
        }
      } else {
        $commentsNeeded = true; // not evaluated = comments needed
      }
    }
    
    // $this->pre_r($evaluated); // (int) returns 3
    // $this->pre_r($rubricDetail['evaluateeCount']); die(); // (int) returns 3
    
    // $this->pre_r($commentsNeeded);                   // NOTE:: (bool) returns true
    // $this->pre_r($event['Event']['com_req']); die(); // NOTE:: Part of the evaluation settings [set to false not required]
    
    // TODO:: Check submissions status and alert students accordingly
    $allDone    = ($evaluated === $rubricDetail['evaluateeCount']);
    $comReq     = ($commentsNeeded && $event['Event']['com_req']); // part of the evaluation settings [set to false not required]
    
//    if($commentsNeeded && $event['Event']['com_req']) {
//      $this->RestResponseHandler->toJson('Comments are required.', 404);
//    }
    
    $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($event['GroupEvent']['id'], $userId);
  
    $dataToJson = [
      'event'             => $event,
      'penaltyFinal'      => $this->Penalty->getPenaltyFinal($eventId),
      'penaltyDays'       => $this->Penalty->getPenaltyDays($eventId),
      'penalty'           => $this->Penalty->getPenaltyByEventId($eventId),
      //
      'questions'         => $rubric,
      'answers'           => $rubricEvalViewData,
      'submission'        => (array) $evaluationSubmission ?? [],
      'evaluation'        => (array) $rubricEvalViewData ?? [],
      //
      'userId'            => empty($studentId) ? $userId : $studentId,
      'groupMembers'      => $rubricDetail['groupMembers'],
      'memberIDs'         => implode(',', Set::extract('/User/id', $rubricDetail['groupMembers'])),
      //
      'evaluateeCount'    => $rubricDetail['evaluateeCount'],
      'courseId'          => $courseId,
      'rubricId'          => $rubricId,
      'allDone'           => $allDone,
      'comReq'            => $comReq,
    ];
  
    $this->JsonHandler->formatRubricEvaluation($dataToJson);
  }
  
  private function set(array $event, string $groupId, $studentId=null, string $submitted)
  {
    //$this->pre_r($this->params); die();
    $form = $this->params['form'];
    //unset($this->params['form']);
    $data = $this->params['data'];
    //unset($this->params['data']);
  
    $rubrics      = $this->Rubric->findById($form['rubric_id']);
    
    // check peer evaluation questions
    if ($rubrics['RubricsCriteria'] > 0) {
  
      $eventId        = $form['event_id'];
      $groupId        = $form['group_id'];
      $groupEventId   = $form['grp_event_id'];
      $evaluator      = empty($studentId) ? $data['Evaluation']['submitter_id'] : $studentId; //  User::get('id');
      $com_required   = $event['Event']['com_req'];
      $failures       = [];
      
      // Student View Mode
      if(isset($form['memberIDs'])) {
        // find out whose evaluation is submitted
        $memberIds = array_unique($form['memberIDs']);
        $suffix = '';
        
        foreach ($memberIds as $userId) {
          if (isset($data[$userId])) {
            if ($this->Evaluation->_saveRubricEvaluation($userId, 0, $this->params)) {
  
              $comments = $data[$userId]['comments'];
              $filter = array_filter(array_map('trim', $comments));
              $msg = [];
              
              
              
              
            } else {
              // Internal error
              $this->RestResponseHandler->toJson('Error: Unable to save the evaluation. Please try again.', 500);
            }
          } else {
            // empty data
            $this->RestResponseHandler->toJson('Error: Unable to save the evaluation. Please make sure your evaluation fields are not empty.', 404);
          }
        }
  
        $this->RestResponseHandler->toJson('Success.', 200);
      }
      
      
    }
    
    
    //die();
  }
  
  
  /**private function set(array $event, string $groupId, $studentId=null, string $submitted)
  {
    // $this->pre_r($this->params); die();
    $form = $this->params['form'];
    $data = $this->params['data'];
    
    $eventId  = $form['event_id'];
    $groupId  = $form['group_id'];
    // $event    = $this->Event->findById($eventId);
  
    $groupEventId   = $form['grp_event_id'];
    $evaluator      = User::get('id');
    
    // Student View Mode
    if(isset($form['memberIDs'])){
      //$this->pre_r('in members');die();
      // find out whose evaluation is submitted
      $memberIds = array_unique($form['memberIDs']);
      //$this->pre_r($memberIds);die();
      $suffix = '';
      foreach ($memberIds as $userId) {
        // $this->pre_r('loop');die();
        // $this->pre_r($data);die();
        //$this->pre_r($this->params);die();
        if (isset($data[$userId])) {
          // $this->pre_r(isset($data[$userId]));die();
          if ($this->Evaluation->_saveRubricEvaluation($userId, 0, $this->params)) {
            // check whether comments are given, if not, and it is required, send msg
            // JK:: $comments = $form[$userId.'comments'];
            // JK:: $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
  
            $comments = $data[$userId]['comments'];
            $filter = array_filter(array_map('trim', $comments));
            $msg = [];
            
            $evaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $evaluator);
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
            }
            else {
              //$updateEvaluationSubmission = $this->EvaluationSubmission->getEvalSubmissionByGrpEventIdSubmitter($groupEventId, $evaluator);
              $this->EvaluationSubmission->id = $evaluationSubmission['EvaluationSubmission']['id'];
              $evaluationSubmission['EvaluationSubmission']['submitted'] = $submitted;
              if (!$this->EvaluationSubmission->save($evaluationSubmission)) {
                $this->RestResponseHandler->toJson('Error: Unable to submit the evaluation. Please try again.', 404);
              }
            }
  
            // NOTE::
            if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
              $msg[] = 'some comments are missing';
            }
  
            // NOTE::
            $suffix = empty($msg) ? '.' : ', but '.implode(' and ', $msg).'.';

//            $this->pre_r($event['Event']['com_req']);
//            $this->pre_r(count($filter) < count($comments));
//            $this->pre_r(count($filter));
//            $this->pre_r($msg);
//            echo '<hr />';
//            $this->pre_r(count($comments));die();
            
            
            
            
            
            // return;
          } else {
            //Found error
            //Validate the error why the Event->save() method returned false
            $this->controller->validateErrors($this->Event);
    
            $this->RestResponseHandler->toJson('Your evaluation was not saved successfully', 500);
            return;
          }
          
          
          
        }
        
      }
      if($submitted) {
        $this->RestResponseHandler->toJson('Your Evaluation was submitted successfully.', 200);
      } else {
        $this->RestResponseHandler->toJson('Your Evaluation was saved successfully' . $suffix, 200);
      }
      // $this->RestResponseHandler->toJson('Your evaluation has been saved' . $suffix, 200);
    }
    // Criteria View Mode
    elseif(isset($form['criteriaIDs'])) {
      // $this->RestResponseHandler->toJson('Criteria View Mode', 200);
      
      // find out the criteria submitted
      // general comments section should be given value of null
      $targetCriteria = null;
      foreach ($form['criteriaIDs'] as $criteriaId) {
        if (isset($form[$criteriaId])) {
          $targetCriteria = $criteriaId;
          break;
        }
      }
      
      $evaluator = $data['Evaluation']['evaluator_id'];
      $groupMembers = $this->User->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], $evaluator);
      
      // Criteria will be null if the submitted section was 'General Comments'
      if ($targetCriteria != null) {
        $viewMode = 1;
      }
      else {
        $viewMode = 0;
      }
      
      // Loop through and save every group member for specified criteria
      $suffix = '';
      foreach ($groupMembers as $groupMember){
        $targetEvaluatee = $groupMember['User']['id'];
        
        if ($this->Evaluation->saveRubricEvaluation($targetEvaluatee, $viewMode, $this->params, $targetCriteria)) {
          // check whether comments are given, if not and it is required, send msg
          $comments = $form[$targetEvaluatee.'comments'];
          $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
          $msg = array();
          $sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
          if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
            $msg[] = 'some comments are missing';
          }
          if (empty($sub) || $sub['EvaluationSubmission']['submitted'] === '0') {
            $msg[] = 'you still have to submit the evaluation with the Submit button below';
          }
          $suffix = empty($msg) ? '.' : ', but '.implode(' and ', $msg).'.';
        } else {
          //Found error
          //Validate the error why the Event->save() method returned false
          $this->validateErrors($this->Event);
          $this->RestResponseHandler->toJson('Your evaluation was not saved successfully', 500);
          break;
        }
      }
      $this->RestResponseHandler->toJson('Your evaluation has been saved' . $suffix, 200);
      return;
    }
    
  }*/
  
  private function complete(array $event, string $groupId, $studentId=null, string $submitted): void
  {
  
  }
  
  private function _create($request)
  {
    //$this->pre_r($request); die();
    $eventId  = $request['params']['form']['event_id'];
    $groupId  = $request['params']['form']['group_id'];
    $event    = $this->Event->findById($eventId);
    
    // Student View Mode
    if(isset($request['params']['form']['memberIDs'])){
      // find out whose evaluation is submitted
      $memberIds = array_unique($request['params']['form']['memberIDs']);
      
      $suffix = '';
      foreach ($memberIds as $targetEvaluatee) {
        if ($this->Evaluation->saveRubricEvaluation($targetEvaluatee, 0, $request['params'])) {
          // check whether comments are given, if not and it is required, send msg
          $comments = $request['params']['form'][$targetEvaluatee.'comments'];
          $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
          $msg = array();
          $sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
          if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
            $msg[] = 'some comments are missing';
          }
          if (empty($sub) || $sub['EvaluationSubmission']['submitted'] === '0') {
            $msg[] = 'you still have to submit the evaluation with the Submit Peer Review button.';
          }
          $suffix = empty($msg) ? '.' : ', but '.implode(' and ', $msg).'.';
          // return;
        } else {
          //Found error
          //Validate the error why the Event->save() method returned false
          $this->validateErrors($this->Event);
          
          $this->RestResponseHandler->toJson('Your evaluation was not saved successfully', 500);
          return;
        }
      }
      $this->RestResponseHandler->toJson('Your evaluation has been saved' . $suffix, 200);
    }
    // Criteria View Mode
    elseif(isset($request['params']['form']['criteriaIDs'])) {
      $this->RestResponseHandler->toJson('Criteria View Mode', 200);
      
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
      $groupMembers = $this->User->getEventGroupMembersNoTutors($groupId, $event['Event']['self_eval'], $evaluator);
    
      // Criteria will be null if the submitted section was 'General Comments'
      if ($targetCriteria != null) {
        $viewMode = 1;
      }
      else {
        $viewMode = 0;
      }
    
      // Loop through and save every group member for specified criteria
      $suffix = '';
      foreach ($groupMembers as $groupMember){
        $targetEvaluatee = $groupMember['User']['id'];
      
        if ($this->Evaluation->saveRubricEvaluation($targetEvaluatee, $viewMode, $request['params'], $targetCriteria)) {
          // check whether comments are given, if not and it is required, send msg
          $comments = $request['params']['form'][$targetEvaluatee.'comments'];
          $filter = array_filter(array_map('trim', $comments)); // filter out blank comments
          $msg = array();
          $sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, User::get('id'));
          if ($event['Event']['com_req'] && (count($filter) < count($comments))) {
            $msg[] = 'some comments are missing';
          }
          if (empty($sub) || $sub['EvaluationSubmission']['submitted'] === '0') {
            $msg[] = 'you still have to submit the evaluation with the Submit button below';
          }
          $suffix = empty($msg) ? '.' : ', but '.implode(' and ', $msg).'.';
        } else {
          //Found error
          //Validate the error why the Event->save() method returned false
          $this->validateErrors($this->Event);
          $this->RestResponseHandler->toJson('Your evaluation was not saved successfully', 500);
          break;
        }
      }
      $this->RestResponseHandler->toJson('Your evaluation has been saved' . $suffix, 200);
      return;
    }
    
  }
  
}
