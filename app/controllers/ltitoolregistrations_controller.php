<?php

/**
 * LTI Tool Registration Controller
 *
 * @uses      AppController
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 * @link      https://www.imsglobal.org/spec/security/v1p0/#fig_oidcflow
 */
class LtiToolRegistrationsController extends AppController
{
    public $name = 'LtiToolRegistrations';
    public $uses = array('LtiToolRegistration', 'LtiPlatformDeployment');

    /**
     * Index action
     */
    public function index()
    {
        $this->set('title_for_layout', __('Lti 1.3 Tool Registrations',true));
        $this->set('headings', array('Issuers', 'Settings', 'Actions'));
        $this->set('rows', $this->LtiToolRegistration->findAll());
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

                // Avoid validation errors on foreign key
                unset($this->LtiToolRegistration->LtiPlatformDeployment->validate['lti_tool_registration_id']);

                // Filter out related deployment ID blanks
                $this->data = $this->LtiPlatformDeployment->filterDeploymentRows($this->data);

                // Save all
                if ($this->LtiToolRegistration->saveAll($this->data)) {
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
                $this->LtiToolRegistration->LtiPlatformDeployment->deleteAll($conditions);

                // Filter out related deployment ID blanks
                $this->data = $this->LtiPlatformDeployment->filterDeploymentRows($this->data);

                // Fill related deployment ID rows with foreign key
                $this->data = $this->LtiPlatformDeployment->fillDeploymentRows($this->data, $id);

                // Save all
                if ($this->LtiToolRegistration->saveAll($this->data)) {
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
