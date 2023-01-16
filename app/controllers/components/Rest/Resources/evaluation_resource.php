<?php

class EvaluationResourceComponent extends CakeObject
{
    public $components = [
        'EventResource', 'CourseResource', 'GroupResource', 'PenaltyResource',
        'EvaluationSubmissionResource', 'EvaluationReviewsCollection',
        'JsonResponse'];
    public $controller;
    public $settings;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
    }
    
    function simpleEvaluation($data): void
    {
        // $json = $this->EventResource->toArray($data);
        $json['event'] = $this->EventResource->format($data['event']['Event']);
        $json['course'] = $this->CourseResource->courseById($data['event']['Event']['course_id']);
        $json['group'] = $this->GroupResource->groupByIdEventId($data['event']['Group']['id'], $data['event']['Event']['id']);
        // $json['members'] = $this->UserCollection->eventGroupMembersNoTutors($data['event']['Group']['id'], $data['event']['Event']['self_eval']);
        $json['penalty'] = $this->PenaltyResource->getPenaltyFinalByEventId($data['event']['Event']['id']);
        //
        $json['member_count'] = $data['evaluateeCount'];
        $json['member_ids'] = $data['memberIDs'];
        //
        $json['submission'] = $this->EvaluationSubmissionResource->get($data);
        $json['reviews'] = $this->EvaluationReviewsCollection->get();
        // $json['simple'] = [];
        // $json['simple'] = $this->EvaluationCollection->simpleEvaluation($data);
        
        //$json['response']       = $this->EvaluationCollection->simpleSubmission($data['submission'], $data['evaluation']);
        //$json['simple']         = $this->getSimpleEvaluationSettings($data);
        //$json['response']       = $this->getSimpleEvaluationSubmission($data['submission'], $data['evaluation']);
        //
        
        $this->JsonResponse->setContent($json)->withStatus(200);
        exit;
    }
    
    function rubricEvaluation(array $data): array
    {
        
        return [];
    }
    
    function mixedEvaluation(array $data): array
    {
        
        return [];
    }
}
