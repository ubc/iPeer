<?php
/**
 * OauthClientsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class OauthclientsController extends AppController {

    public $name = 'OauthClients';
    public $components = array('PasswordGenerator');

    /**
     * index
     *
     * @access public
     * @return void
     */
    public function index() {
        if (!User::hasPermission('controllers/oauthclients')) {
            $this->redirect('/users/editProfile');
        }
        $this->set('title_for_layout', __('OAuth Client Credentials',true));
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

    /**
     * add
     *
     * @access public
     * @return void
     */
    public function add() {
        $this->set('title_for_layout', 'Create New OAuth Client Credential');

        if (!empty($this->data)) {
            $this->OauthClient->create();
            if ($this->OauthClient->save($this->data)) {
                $this->Session->setFlash(__('A new OAuth client has been created', true), 'good');
                if (User::hasPermission('controllers/oauthclients')) {
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->redirect('/users/editProfile');
                }
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
        if (!User::hasPermission('controllers/oauthclients')) {
            $this->set('hideUser', true);
            $clients = $this->OauthClient->find(
                'count',
                array(
                    'conditions' => array('OauthClient.user_id' => $this->Auth->user('id'))
                )
            );
            // only super admins can create more than one client credential for a user
            if ($clients > 0) {
                $this->Session->setFlash(__('Error: You do not have permission to create more than one OAuth Client Credential', true));
                $this->redirect('/users/editProfile');
            }
        }
        $users = $this->OauthClient->User->find('list',
            array('fields' => array('User.username')));
        $this->set(compact('users'));
    }

    /**
     * edit
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    public function edit($id = null) {
        $this->set('title_for_layout', 'Edit OAuth Client Credential');
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid OAuth client', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!User::hasPermission('controllers/oauthclients')) {
            $this->redirect('/users/editProfile');
        }
        if (!empty($this->data)) {
            if ($this->OauthClient->save($this->data)) {
                $this->Session->setFlash(__('The OAuth client has been saved', true), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The OAuth client could not be saved. Please, try again.', true));
            }
        } else if (empty($this->data)) {
            $this->data = $this->OauthClient->read(null, $id);
            if (empty($this->data)) {
                $this->redirect('index');
            }
        }
        $users = $this->OauthClient->User->find('list',
            array('fields' => array('User.username')));
        $this->set(compact('users'));
    }

    /**
     * delete
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    public function delete($id = null) {
        $client = $this->OauthClient->find('first', array('conditions' => array('OauthClient.id' => $id)));
        if (empty($client)) {
            $this->Session->setFlash(__('Invalid id for OAuth client', true));
        } else if ($client['OauthClient']['user_id'] != $this->Auth->user('id') &&
            !User::hasPermission('controllers/oauthclients')) {
            $this->Session->setFlash(__('Invalid id for OAuth client', true));
        } else if ($this->OauthClient->delete($id)) {
            $this->Session->setFlash(__('OAuth client deleted', true), 'good');
        } else {
            $this->Session->setFlash(__('OAuth client was not deleted', true));
        }
        $this->redirect($this->referer());
    }
}
