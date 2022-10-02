<?php

class EvaluationMakeComponent extends CakeObject
{
  public $name = 'MakeEvaluation';
  public $uses = [];
  public $components = ['EvaluationSimpleRequest', 'EvaluationRubricRequest', 'EvaluationMixedRequest'];
  
  public function __construct() {
    parent::__construct();
  }
  
  function beforeFilter() {
    parent::beforeFilter();
  }
  
  public function processSimpleEvaluationRequest($method, $eventId, $groupId, $studentId): void
  {
    if($eventId) {
      $this->EvaluationSimpleRequest->processResourceRequest($method, $eventId, $groupId, $studentId);
    } else {
      $this->EvaluationSimpleRequest->processCollectionRequest($method);
    }
  }
  
  public function processRubricEvaluationRequest($method, $eventId, $groupId, $studentId): void
  {
    if($eventId) {
      $this->EvaluationRubricRequest->processResourceRequest($method, $eventId, $groupId, $studentId);
    } else {
      $this->EvaluationRubricRequest->processCollectionRequest($method);
    }
  }
  
  public function processMixedEvaluationRequest($method, $eventId, $groupId, $studentId): void
  {
    if($eventId) {
      $this->EvaluationMixedRequest->processResourceRequest($method, $eventId, $groupId, $studentId);
    } else {
      $this->EvaluationMixedRequest->processCollectionRequest($method);
    }
  }
}
