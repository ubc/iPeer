<?php
App::import('Lib', 'caliper');

class HomeRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];
    public $components = ['JsonResponse', 'EvaluationCollection'];
    
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
    }
    
    /**
     * @param array $events
     * @param string|null $userId
     */
    public function processStudentCollectionRequest(array $events, string $userId=NULL)
    {
        switch ($this->controller->method) {
            case 'GET':
                $this->list($events, $userId);
                break;
            default:
                http_response_code(405);
                header('Allow: GET, POST');
                break;
        }
    }
    
    /**
     * Private
     */
    private function list($events, $userId): void
    {
        $evaluations = $this->_splitSubmittedEvents($events);
        $currentEvaluations = $this->EvaluationCollection->toArray($evaluations['current']);
        $completedEvaluations = $this->EvaluationCollection->toArray($evaluations['completed']);
    
        $this->JsonResponse
            ->setContent(['current' => $currentEvaluations, 'completed' => $completedEvaluations])
            ->withStatus(200);
    }
    
    private function _splitSubmittedEvents($events)
    {
        $current = $completed = array();
        foreach ($events as $event) {
            if (empty($event['EvaluationSubmission']) && $event['Event']['is_released']) {
                $event['status'] = 'upcoming';
                $current[] = $event;
            } else {
                if(!empty($event['EvaluationSubmission'])) {
                    $event['status'] = 'submitted';
                } else {
                    $event['status'] = 'expired';
                }
                $completed[] = $event;
            }
        }
        return array(
            'current' => $current,
            'completed' => $completed
        );
    }
}
