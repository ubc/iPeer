<?php
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

class UsersRequestComponent extends CakeObject
{
  public $Sanitize;
  public $uses = [];
  public $components = ['Session', 'RequestHandler', 'Evaluation', 'JsonHandler', 'RestResponseHandler'];
  
  /**
   * @var bool|object
   */
  private $User;
  
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
    
    $this->User = ClassRegistry::init('User');
  }
  
  function pre_r($val)
  {
    echo '<pre>';
    print_r($val);
    echo '</pre>';
  }
  
  public function processResourceRequest($method, $userId, $params)
  {
    switch ($method) {
      case 'GET':
        $this->get($userId);
        break;
      case 'PUT':
        $this->set($userId, $params['data']);
        break;
      case 'DELETE':
        http_response_code(200);
        echo json_encode(['message' => 'TBD']);
        break;
      default:
        http_response_code(405);
        header('Allow, GET, POST, PUT');
        break;
    }
  }
  
  public function processCollectionRequest($method)
  {
    switch ($method) {
      case 'GET':
        $this->list($method);
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
  private function list($method): void
  {
    http_response_code(200);
    echo json_encode(['process_collection' => []]);
  }
  
  
  private function create(): void
  {
    http_response_code(200);
    echo json_encode(['message' => 'processCollectionRequest::create']);
  }
  
  
  private function get($id): void
  {
    $user = $this->User->read(null, $id);
    if(empty($user)) exit;
    $profile = [
      'id' => $user['User']['id'],
      'role_id' => $user['Role'][0]['id'],
      'username' => $user['User']['username'],
      'first_name' => $user['User']['first_name'],
      'last_name' => $user['User']['last_name'],
      'student_no' => $user['User']['student_no'],
      'title' => $user['User']['title'],
      'email' => $user['User']['email'],
      // 'role_name' => $user['Role'][0]['name']
    ];
    //$submission = $user['Submission'];
    
    http_response_code(200);
    echo json_encode($profile);
  }
  
  
  private function set($id, $data): void
  {
    if (!empty($data)) {
        $data['User']['id'] = $id;
      
        if (!empty($data['User']['temp_password'])) {
            $user = $this->User->findUserByidWithFields($id, array('password'));
            if (md5($data['User']['old_password'])==$user['password']) {
                if ($data['User']['temp_password']==$data['User']['confirm_password']) {
                    $data['User']['password'] = md5($data['User']['temp_password']);
                } else {
                    $this->RestResponseHandler->toJson('New passwords do not match', 404);
                }
            } else {
                $this->RestResponseHandler->toJson('Old password is incorrect', 404);
            }
        } else {
            unset($data['User']['temp_password']);
            unset($data['User']['old_password']);
            unset($data['User']['confirm_password']);
        }
      
        if ($this->processForm($data)) {
            $this->setSessionData($data['User']);
            $this->RestResponseHandler->toJson('Your Profile Has Been Updated Successfully.', 200);
        }
      }
  }
  
    private function processForm($data): bool
    {
      if (!empty($data)) {
        // NOTE:: LOOK
        // $this->Output->filter($data);//always filter
        // Save Data
        if ($data = $this->User->save($data)) {
          $data['User']['id'] = $this->User->id;
        } else {
          $validationErrors = $this->User->invalidFields();
          $errorMsg = '';
          foreach ($validationErrors as $error) {
            $errorMsg = $errorMsg."\n".$error;
          }
          $this->RestResponseHandler->toJson('Failed to save. '.$errorMsg, 404);
        }
      
        if (isset($data['OauthClient'])) {
          if (!($this->OauthClient->saveAll($data['OauthClient']))) {
            $this->RestResponseHandler->toJson('Failed to save.', 404);
          }
        }
      
        if (isset($data['OauthToken'])) {
          if (!($this->OauthToken->saveAll($data['OauthToken']))) {
            $this->RestResponseHandler->toJson('Failed to save.', 404);
          }
        }
      }
    
      return true;
    }
    
    
    private function setSessionData($userData): void
    {
      $this->Session->write('ipeerSession.id', $userData['id']);
      $this->Session->write('ipeerSession.username', $userData['username']);
      $this->Session->write('ipeerSession.fullname', $userData['first_name'].' '.$userData['last_name']);
      $this->Session->write('ipeerSession.email', $userData['email']);
    }
}
