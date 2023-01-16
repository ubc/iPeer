<?php

class EvaluationReviewsCollectionComponent extends CakeObject
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
    
    public function get(): array
    {
        return ['Collection' => 'EvaluationSubmission'];
    }
}
