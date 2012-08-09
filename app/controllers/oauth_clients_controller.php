<?php
class OauthClientsController extends AppController {

    public $name = 'OauthClients';
    public $components = array('PasswordGenerator');

    public function index() {
        $this->set('title_for_layout', 'OAuth Client Credentials');
        $clientCreds = array();
        $allClients = $this->OauthClient->find('all');
        foreach ($allClients as $cred) {
            $tmp = array();
            $tmp['id'] = $cred['OauthClient']['id'];
            $tmp['User'] = $cred['User']['username'];
            $tmp['Key'] = $cred['OauthClient']['key'];
            $tmp['Secret'] = $cred['OauthClient']['secret'];
            $tmp['Status'] = $cred['OauthClient']['enabled'] ? 'On' : 'Off';
            $tmp['Comment'] = $cred['OauthClient']['comment'];
            $clientCreds[] = $tmp;
        }
        $this->set('clientCreds', $clientCreds); 
    }

    public function add() {
        $this->set('title_for_layout', 'Create New OAuth Client Credential');

        if (!empty($this->data)) {
            debug($this->data);
            $this->OauthClient->create();
            if ($this->OauthClient->save($this->data)) {
                $this->Session->setFlash(__('A new OAuth client has been created', true), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The OAuth client could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            // fill key and secret with securely generated random values
            // if no existing data found
            $this->data['OauthClient']['user_id'] = $this->Auth->user('id');
            $this->data['OauthClient']['key'] = 
                $this->PasswordGenerator->generate(8);
            $this->data['OauthClient']['secret'] = 
                $this->PasswordGenerator->generate();
        }
        $users = $this->OauthClient->User->find('list',
            array('fields' => array('User.username')));
        $this->set(compact('users'));
    }

    public function edit($id = null) {
        $this->set('title_for_layout', 'Edit OAuth Client Credential');
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid OAuth client', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->OauthClient->save($this->data)) {
                $this->Session->setFlash(__('The OAuth client has been saved', true), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The OAuth client could not be saved. Please, try again.', true));
            }
        }
        else if (empty($this->data)) {
            $this->data = $this->OauthClient->read(null, $id);
        }
        $users = $this->OauthClient->User->find('list',
            array('fields' => array('User.username')));
        $this->set(compact('users'));
    }

    public function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for OAuth client', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->OauthClient->delete($id)) {
            $this->Session->setFlash(__('OAuth client deleted', true), 'good');
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('OAuth client was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
