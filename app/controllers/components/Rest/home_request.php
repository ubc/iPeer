<?php
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

class HomeRequestComponent extends CakeObject
{
  public $Sanitize;
  public $uses = [];
  public $components = ['RequestHandler', 'Evaluation', 'JsonHandler', 'RestResponseHandler'];
  
  /**
   * @var bool|object
   */
  private $Event;
  
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
    
    $this->Event = ClassRegistry::init('Event');
  }
  
  function pre_r($val)
  {
    echo '<pre>';
    print_r($val);
    echo '</pre>';
  }
  
  // header('Content-Type: application/json');
  public function processInstructorCollectionRequest(array $courseList, string $userId)
  {
    switch ($this->controller->method) {
      case 'GET':
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode($courseList['I'][0]); exit; // ['I'] for Instructor list
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
      case 'POST':
        $this->create();
        break;
      default:
        http_response_code(405);
        header('Allow: GET, POST');
        break;
    }
  }
  
  /** private */
  private function list($events, $userId): void
  {
    $work = $this->params['url']['work'] ?? null;
    switch($work) {
      case 'current':
        // $this->EventsRequest->processCollectionRequest($this->method, $evals['upcoming'], $userId, $this->params);
        $current = $this->JsonHandler->formatEvents($events, $userId);
        $this->RestResponseHandler->toJson('Current Work To Do', 200, $current);
        break;
      case 'completed':
        // $this->EventsRequest->processCollectionRequest($this->method, array_merge($evals['submitted'], $evals['expired']), $userId, $this->params);
        $completed = $this->JsonHandler->formatEvents($events, $userId);
        $this->RestResponseHandler->toJson('Closed or Completed Work', 200, $completed);
        break;
      default:
        // $this->EventsRequest->processCollectionRequest($this->method, $evals, $userId, $this->params);
        $current = $this->JsonHandler->formatEvents($events['upcoming'], $userId);
        $completed = $this->JsonHandler->formatEvents(array_merge($events['submitted'], $events['expired']), $userId);
        $this->RestResponseHandler->toJson('Events', 200, [
          'current'   => $current,
          'completed' => $completed
        ]);
        break;
    }
  }
  
  
  private function create(): void
  {
    http_response_code(200);
    echo json_encode(['message' => 'processCollectionRequest::create']);
  }
  
  private function get($events): void
  {
    http_response_code(200);
    echo json_encode(['message' => 'processResourceRequest::get', '$events' => $events]);
  }
  
  private function set($events): void
  {
    http_response_code(200);
    echo json_encode(['message' => 'processResourceRequest::set', '$events' => $events]);
  }
  
}
