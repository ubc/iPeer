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

    public function __construct()
    {
        parent::__construct();
        $this->set('customLogo', null);
    }

    public function beforeFilter()
    {
        $this->Auth->allow();
    }

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
                // To avoid validation errors
                unset($this->LtiToolRegistration->LtiPlatformDeployment->validate['lti_tool_registration_id']);

                $this->_filterDeployments();
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

            if (empty($id)) {
                $this->redirect(array('action' => 'index'));
            }

            // PUT request
            if (!empty($this->data)) {
                $this->_filterDeployments();
                $this->_replaceDeployments();
                if ($this->LtiToolRegistration->saveAll($this->data)) {
                    $this->Session->setFlash(__('Tool registration has been updated', true), 'good');
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
     * Filter out related deployment ID blanks
     *
     * @return array
     */
    private function _filterDeployments()
    {
        if (!empty($this->data['LtiPlatformDeployment'])) {
            foreach ($this->data['LtiPlatformDeployment'] as $i => $row) {
                if (isset($row['deployment'])) {
                    if (empty(trim($row['deployment']))) {
                        unset($this->data['LtiPlatformDeployment'][$i]);
                    }
                }
            }
        }
        if (empty($this->data['LtiPlatformDeployment'])) {
            unset($this->data['LtiPlatformDeployment']);
        }
    }

    /**
     * Replace related deployment ID rows
     *
     * @return array
     */
    private function _replaceDeployments()
    {
        $id = $this->data['LtiToolRegistration']['id'];

        // Delete from dB
        $conditions = 'lti_tool_registration_id = ' . $id;
        if ($this->LtiToolRegistration->LtiPlatformDeployment->deleteAll($conditions)) {
            $this->Session->setFlash(__('Tool registration deployments have been deleted.', true), 'good');
        }

        // Fill with foreign key
        if (!empty($this->data['LtiPlatformDeployment'])) {
            foreach ($this->data['LtiPlatformDeployment'] as $i => $row) {
                $this->data['LtiPlatformDeployment'][$i]['lti_tool_registration_id'] = $id;
            }
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
