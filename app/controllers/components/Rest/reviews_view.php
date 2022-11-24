<?php

class ReviewsViewComponent extends CakeObject
{
    public $name = 'ReviewsView';
    public $Sanitize;
    public $uses = [];
    public $components = ['ReviewsSimpleRequest', 'ReviewsRubricRequest', 'ReviewsMixedRequest'];
    
    public function __construct() {
        parent::__construct();
    }
    
    function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function processSimpleReviewsRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        if($groupEventId) {
            $this->ReviewsSimpleRequest->processResourceRequest($event, $groupEventId, $autoRelease, $userId, $method);
        } else {
            $this->ReviewsSimpleRequest->processCollectionRequest($method);
        }
    }
    
    public function processRubricReviewsRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        if($groupEventId) {
            $this->ReviewsRubricRequest->processResourceRequest($event, $groupEventId, $autoRelease, $userId, $method);
        } else {
            $this->ReviewsRubricRequest->processCollectionRequest($method);
        }
    }
    
    public function processMixedReviewsRequest($event, $groupEventId, $autoRelease, $userId, $method): void
    {
        if($groupEventId) {
            $this->ReviewsMixedRequest->processResourceRequest($event, $groupEventId, $autoRelease, $userId, $method);
        } else {
            $this->ReviewsMixedRequest->processCollectionRequest($method);
        }
    }
}
