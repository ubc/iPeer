<?php

/**
 * LTI Tool Registration Controller
 *
 * @uses      AppController
 * @package   CTLT.iPeer
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 * @link      https://www.imsglobal.org/spec/security/v1p0/#fig_oidcflow
 */
class LtiToolRegistrationsController extends AppController
{
    public $name = 'LtiToolRegistrations';
    public $uses = array('LtiToolRegistration');

    /**
     * Index action
     */
    public function index()
    {
        $this->set('title_for_layout', __('Lti 1.3 Tool Registrations',true));
        $this->set('headings', array('Issuers', 'Settings', 'Actions'));

        $ret = $this->LtiToolRegistration->find('all');

        $registrations = array();
        foreach ($ret as $registration) {
            $tmp = array();
            $tmp['id'] = $registration['LtiToolRegistration']['id'];
            $tmp['iss'] = $registration['LtiToolRegistration']['iss'];
            $tmp['client_id'] = $registration['LtiToolRegistration']['client_id'];
            $registrations[] = $tmp;
        }
        $this->set('registrations', $registrations);
    }

    /**
     * Add action
     */
    public function add()
    {
        $this->set('title_for_layout', __('Add Lti 1.3 Tool Registration',true));

        try {

            // POST request
            if (!empty($this->data)) {
                // Save all
                if ($this->LtiToolRegistration->save($this->data)) {
                    $this->Session->setFlash(__('Tool registration has been created', true), 'good');
                    $this->redirect(array('action' => 'index'));
                }
            }

        } catch (Exception $e) {

            $this->Session->setFlash($e->getMessage());
            $this->redirect(array('action'=>'index'));

        }
    }

    /**
     * Edit action
     *
     * @param mixed $id
     */
    public function edit($id = null)
    {
        $this->set('title_for_layout', __('Edit Lti 1.3 Tool Registration',true));

        try {

            // POST request
            if (!empty($this->data)) {

                // Delete associated deployments from dB
                $id = $this->data['LtiToolRegistration']['id'];
                $conditions = 'lti_tool_registration_id = ' . $id;

                // Save all
                if ($this->LtiToolRegistration->save($this->data)) {
                    $this->Session->setFlash(__('Tool registration has been updated', true), 'good');
                    $this->redirect(array('action' => 'index'));
                }

            } else {

                if (empty($id)) {
                    $this->redirect(array('action' => 'index'));
                }

            }

            $this->data = $this->LtiToolRegistration->findById($id);

        } catch (Exception $e) {

            $this->Session->setFlash($e->getMessage());
            $this->redirect(array('action'=>'index'));

        }
    }

    /**
     * Delete action
     *
     * @param mixed $id
     */
    public function delete($id = null)
    {
        try {

            if ($this->LtiToolRegistration->delete($id)) {
                $this->Session->setFlash(__('Tool registration has been deleted', true), 'good');
                $this->redirect(array('action'=>'index'));
            }
            $this->Session->setFlash(__('Tool registration was not deleted', true));
            $this->redirect(array('action' => 'index'));

        } catch (Exception $e) {

            $this->Session->setFlash($e->getMessage());
            $this->redirect(array('action'=>'index'));

        }
    }
}
