<?php
App::import('Lib', 'caliper');

class HomeRequestComponent extends CakeObject
{
    public $Sanitize;
    public $uses = [];
    public $components = [ 'JsonHandler', 'JsonResponse', 'EventResource', 'EventCollection' ];
    
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
    
    function pre_r($val)
    {
        echo '<pre>';
        print_r($val);
        echo '</pre>';
    }
    
    /** TBD::TODO */
    public function processInstructorCollectionRequest(array $courseList, string $userId)
    {
        switch ($this->controller->method) {
            case 'GET':
                $this->get();
                break;
            case 'POST':
                $this->create();
                break;
            case 'PUT':
                $this->set();
                break;
            default:
                break;
        }
    }
    
    public function processStudentCollectionRequest(array $events, string $userId)
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
    
    /** private methods */
    
    /**
     * @param $events
     * @param $userId
     */
    private function list($events, $userId): void
    {
        $work = $this->params['url']['work'] ?? null;
        $current = $this->EventCollection->toArray($events['upcoming']);
        $completed = $this->EventCollection->toArray(array_merge($events['submitted'], $events['expired']));
        
        switch ($work) {
            case 'current':
                $this->JsonResponse->setContent($current)->withStatus(200);
                break;
            case 'completed':
                $this->JsonResponse->setContent($completed)->withStatus(200);
                break;
            default:
                $this->JsonResponse->setContent(['current' => $current, 'completed' => $completed])->withStatus(200);
                break;
        }
    }
    
    private function create(): void
    {
        $this->JsonResponse->setContent(['message' => 'process Instructor Request::create'])->withStatus(200);
    }
    
    private function get(): void
    {
        $this->JsonResponse->setContent(['message' => 'Process Instructor Request::get'])->withStatus(200);
    }
    
    private function set(): void
    {
        $this->JsonResponse->setContent(['message' => 'process Instructor Request::set'])->withStatus(200);
    }
    
}
