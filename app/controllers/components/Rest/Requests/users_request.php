<?php
App::import('Lib', 'caliper');

class UsersRequestComponent extends CakeObject
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
        parent::__construct();
    }
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
        $this->params = $controller->params;
        $this->data = $controller->data;
    }
    
    public function processResourceRequest($method, $userId): void
    {
        switch ($method) {
            case 'GET': // Read
                $this->get($userId); //TODO:: rename get to something else
                break;
            case 'PUT': // Update
                $this->set($userId); //TODO:: rename set to something else
                break;
            default:
                http_response_code(405);
                header('Allow, GET, PUT');
                break;
        }
    }
    
    /**
     * @param $userId
     */
    private function get($userId): void
    {
        $user = $this->controller->User->read(null, $userId);
        if (!isset($user)) exit;
        $profile = [
            'id' => $user['User']['id'],
            'role_id' => $user['Role'][0]['id'],
            'username' => $user['User']['username'],
            'first_name' => $user['User']['first_name'],
            'last_name' => $user['User']['last_name'],
            'email' => $user['User']['email'],
            'student_no' => $user['User']['student_no'],
        ];
        $this->JsonResponse->setContent($profile)->withStatus(200);
    }
    
    /**
     * @param $userId
     */
    private function set($userId): void
    {
        // No security checks here, since we're editing the logged-in user
        $id = $userId; // $this->controller->Auth->user('id');
        if (!empty($this->data)) {
            $this->data['User']['id'] = $id;
            if (!empty($this->data['User']['temp_password'])) {
                $user = $this->controller->User->findUserByidWithFields($id, array('password'));
                if (md5($this->data['User']['old_password']) === $user['password']) {
                    if ($this->data['User']['temp_password'] === $this->data['User']['confirm_password']) {
                        $this->data['User']['password'] = md5($this->data['User']['temp_password']);
                    } else {
                        $this->JsonResponse->withMessage('New passwords do not match')->withStatus(404);
                        return;
                    }
                } else {
                    $this->JsonResponse->withMessage('Old password is incorrect')->withStatus(404);
                    return;
                }
            } else {
                unset($this->data['User']['temp_password']);
            }
            
            if ($this->__processForm()) {
                $this->__setSessionData($this->data['User']);
                $this->JsonResponse->withMessage('Your Profile Has Been Updated Successfully.')->withStatus(200);
                return;
            }
        }
        
        /** N/A */
        if (in_array($this->controller->User->getRoleName($id), array("student", "tutor"))) {
            $isStudent = true;
        } else {
            $isStudent = false;
        }
        $oAuthClient = $this->controller->OauthClient->find('all', array('conditions' => array('OauthClient.user_id' => $id)));
        $oAuthToken = $this->controller->OauthToken->find('all', array('conditions' => array('OauthToken.user_id' => $id)));
        
        $enabled = array('0' => 'Disabled', '1' => 'Enabled');
        $this->data = $this->controller->User->read(null, $id);
        // $this->Output->br2nl($this->data);
        
        return;
    }
    
    /**
     * @return bool|void
     */
    private function __processForm()
    {
        if (!empty($this->data)) {
            // $this->Output->filter($this->data);//always filter
            //Save Data
            if ($this->data = $this->controller->User->save($this->data)) {
                $this->data['User']['id'] = $this->controller->User->id;
            } else {
                $validationErrors = $this->controller->User->invalidFields();
                $errorMsg = '';
                foreach ($validationErrors as $error) {
                    $errorMsg = $errorMsg . "\n" . $error;
                }
                $this->JsonResponse->withMessage('Failed to save. ' . $errorMsg)->withStatus(400);
                return;
            }
            // not set (staging env)
            if (isset($this->data['OauthClient'])) {
                if (!($this->controller->OauthClient->saveAll($this->data['OauthClient']))) {
                    $this->JsonResponse->withMessage('Failed to save. ')->withStatus(400);
                    return;
                }
            }
            // not set (staging env)
            if (isset($this->data['OauthToken'])) {
                if (!($this->controller->OauthToken->saveAll($this->data['OauthToken']))) {
                    $this->JsonResponse->withMessage('Failed to save. ')->withStatus(200);
                    return;
                }
            }
        }
        return true;
    }
    
    /**
     * @param $userData
     */
    private function __setSessionData($userData): void
    {
        $this->Session->write('ipeerSession.id', $userData['id']);
        $this->Session->write('ipeerSession.username', $userData['username']);
        $this->Session->write('ipeerSession.fullname', $userData['first_name'] . ' ' . $userData['last_name']);
        $this->Session->write('ipeerSession.email', $userData['email']);
    }
    
    function pre_r($val)
    {
        echo '<pre>';
        print_r($val);
        echo '</pre>';
    }
    
    public function processCollectionRequest($method): void
    {
    }
    
}
