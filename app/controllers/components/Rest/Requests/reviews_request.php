<?php
App::import('Lib', 'caliper');

use caliper\CaliperHooks;

class ReviewsRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];
    public $components = ['Session', 'JsonResponse'];
    
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
    
    public function processSimpleReviewsRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        /** $studentId = $studentId ?? $this->controller->Auth->user('id'); */
        switch ($method) {
            case 'GET': // Read
                $this->getSimpleReviews($event, $groupEventId, $autoRelease, $userId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET');
                break;
        }
        /**
         * if ($groupEventId) {
         * $this->ReviewsSimpleRequest->processResourceRequest($event, $groupEventId, $autoRelease, $userId, $method);
         * } else {
         * $this->ReviewsSimpleRequest->processCollectionRequest($method);
         * }*/
    }
    
    /**
     * Private
     */
    
    
    private function getSimpleReviews($event, $groupEventId, $autoRelease, $userId): void
    {
        $this->pre_r($event, $groupEventId, $autoRelease, $userId);
    }
    
    public function processRubricReviewsRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        /** $studentId = $studentId ?? $this->controller->Auth->user('id'); */
        switch ($method) {
            case 'GET': // Read
                $this->getRubricReviews($event, $groupEventId, $autoRelease, $userId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET');
                break;
        }
        /**
         * if ($groupEventId) {
         * $this->ReviewsRubricRequest->processResourceRequest($event, $groupEventId, $autoRelease, $userId, $method);
         * } else {
         * $this->ReviewsRubricRequest->processCollectionRequest($method);
         * }*/
    }
    
    private function getRubricReviews($event, $groupEventId, $autoRelease, $userId): void
    {
        $this->pre_r($event, $groupEventId, $autoRelease, $userId);
    }
    
    public function processMixedReviewsRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        /** $studentId = $studentId ?? $this->controller->Auth->user('id'); */
        switch ($method) {
            case 'GET': // Read
                $this->getMixedReviews($event, $groupEventId, $autoRelease, $userId);
                break;
            default:
                http_response_code(405);
                header('Allow, GET');
                break;
        }
        /**
         * if ($groupEventId) {
         * $this->ReviewsMixedRequest->processResourceRequest($event, $groupEventId, $autoRelease, $userId, $method);
         * } else {
         * $this->ReviewsMixedRequest->processCollectionRequest($method);
         * }*/
    }
    
    private function getMixedReviews($event, $groupEventId, $autoRelease, $userId): void
    {
        $this->pre_r($event, $groupEventId, $autoRelease, $userId);
    }
}
