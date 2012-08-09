<?php
class OauthTokensController extends AppController {

    public $name = 'OauthTokens';
    public $components = array('PasswordGenerator');

    function index() {
        $this->set('title_for_layout', 'OAuth Token Credentials');
        $tokenCreds = array();
        $allTokens = $this->OauthToken->find('all');
        foreach ($allTokens as $cred) {
            $enabled = "On";
            $expiry = $cred['OauthToken']['expires'];
            if (strtotime(date("Y-m-d")) > strtotime($expiry)) {
                $enabled = "Expired";
            }
            $tmp = array();
            $tmp['id'] = $cred['OauthToken']['id'];
            $tmp['User'] = $cred['User']['username'];
            $tmp['Key'] = $cred['OauthToken']['key'];
            $tmp['Secret'] = $cred['OauthToken']['secret'];
            $tmp['Expiry Date'] = $expiry;
            $tmp['Status'] = $cred['OauthToken']['enabled'] ? $enabled : 'Off';
            $tmp['Comment'] = $cred['OauthToken']['comment'];
            $tokenCreds[] = $tmp;
        }
        $this->set('tokenCreds', $tokenCreds);
    }

    function add() {
        $this->set('title_for_layout', 'Create New OAuth Token Credential');

        if (!empty($this->data)) {
            $this->OauthToken->create();
            if ($this->OauthToken->save($this->data)) {
                $this->Session->setFlash(__('New OAuth token created!', true), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The OAuth token could not be saved. Please, try again.', true));
            }
        }
        else if (empty($this->data)) {
            // fill key and secret with securely generated random values
            // if no existing data found
            $this->data['OauthToken']['user_id'] = $this->Auth->user('id');
            $this->data['OauthToken']['key'] = 
                $this->PasswordGenerator->generate(8);
            $this->data['OauthToken']['secret'] = 
                $this->PasswordGenerator->generate();
        }
        $users = $this->OauthToken->User->find('list');
        $this->set(compact('users'));
    }

    function edit($id = null) {
        $this->set('title_for_layout', 'Edit OAuth Token Credential');
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid oauth token', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->OauthToken->save($this->data)) {
                $this->Session->setFlash(__('OAuth token saved successfully!', true), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The OAuth token could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->OauthToken->read(null, $id);
        }
        $users = $this->OauthToken->User->find('list');
        $this->set(compact('users'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for oauth token', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->OauthToken->delete($id)) {
            $this->Session->setFlash(__('OAuth token deleted.', true), 'good');
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('OAuth token was not deleted.', true));
        $this->redirect(array('action' => 'index'));
    }
}
