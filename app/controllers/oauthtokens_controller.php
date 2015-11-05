<?php
/**
 * OauthTokensController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class OauthtokensController extends AppController {

    public $name = 'OauthTokens';
    public $components = array('PasswordGenerator');

    /**
     * index
     *
     * @access public
     * @return void
     */
    function index() {
        if (!User::hasPermission('controllers/oauthtokens')) {
            $this->redirect('/users/editProfile');
        }
        $this->set('title_for_layout', __('OAuth Token Credentials',true));
        $tokenCreds = array();
        $allTokens = $this->OauthToken->find('all');
        foreach ($allTokens as $cred) {
            $enabled = "On";
            $expiry = $cred['OauthToken']['expires'];
            if (time() > strtotime($expiry)) {
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


    /**
     * add
     *
     * @access public
     * @return void
     */
    function add() {
        $this->set('title_for_layout', 'Create New OAuth Token Credential');

        if (!empty($this->data)) {
            $this->OauthToken->create();
            if ($this->OauthToken->save($this->data)) {
                $this->Session->setFlash(__('New OAuth token created!', true), 'good');
                if (User::hasPermission('controllers/oauthtokens')) {
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->redirect('/users/editProfile');
                }
            } else {
                $this->Session->setFlash(__('The OAuth token could not be saved. Please, try again.', true));
            }
        } else if (empty($this->data)) {
            // fill key and secret with securely generated random values
            // if no existing data found
            $this->data['OauthToken']['user_id'] = $this->Auth->user('id');
            $this->data['OauthToken']['key'] = 
                $this->PasswordGenerator->generate(8);
            $this->data['OauthToken']['secret'] = 
                $this->PasswordGenerator->generate();
        }
        if (!User::hasPermission('controllers/oauthtokens')) {
            $this->set('hideUser', true);
        }
        $users = $this->OauthToken->User->find('list');
        asort($users);
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
    function edit($id = null) {
        $this->set('title_for_layout', 'Edit OAuth Token Credential');
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid oauth token', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!User::hasPermission('controllers/oauthtokens')) {
            $this->redirect('/users/editProfile');
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
            if (empty($this->data)) {
                $this->redirect('index');
            }
        }
        $users = $this->OauthToken->User->find('list');
        asort($users);
        $this->set(compact('users'));
    }

    /**
     * delete
     *
     * @param mixed $id
     *
     * @access public
     * @access void
     */
    function delete($id = null) {
        $token = $this->OauthToken->find('first', array('conditions' => array('OauthToken.id' => $id)));
        if (empty($token)) {
            $this->Session->setFlash(__('Invalid id for OAuth token', true));
        } else if ($token['OauthToken']['user_id'] != $this->Auth->user('id') &&
            !User::hasPermission('controllers/oauthtokens')) {
            $this->Session->setFlash(__('Invalid id for OAuth token', true));
        } else if ($this->OauthToken->delete($id)) {
            $this->Session->setFlash(__('OAuth token deleted.', true), 'good');
            if(User::hasPermission('controllers/oauthtokens')) {
                $this->redirect(array('action'=>'index'));
            } else {
                $this->redirect('/users/editProfile');
            }
        }
        $this->Session->setFlash(__('OAuth token was not deleted.', true));
        $this->redirect(array('action' => 'index'));
    }
}
