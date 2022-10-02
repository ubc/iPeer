<?php
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

class EventsRequestComponent extends CakeObject
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
  
  
  public function processResourceRequest($method, $events, $userId, $params=null)
  {
  
  }
  public function processCollectionRequest($method, $events, $userId, $params=null)
  {
    switch ($method) {
      case 'GET':
        $this->list($events, $userId, $params);
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
  private function list($events, $userId, $params=null): void
  {
//    http_response_code(200);
//    echo json_encode(['message' => 'processCollectionRequest::list']);
    
//    $this->JsonHandler->formatEvents($events, $userId, $params);
  
    $encoded = $this->JsonHandler->formatEvents($events, $userId);
    
    $this->RestResponseHandler->toJson('Current Work To Do', 200, $encoded);
    exit;
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
