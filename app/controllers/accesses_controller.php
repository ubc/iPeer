<?php
/**
 * AccessesController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2013 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class AccessesController extends AppController {
    public $name = 'Accesses';
    public $uses = array('Access', 'Role');

    /**
     * view
     *
     * @param mixed $roleId role id
     *
     * @access public
     * @return void
     */
    public function view($roleId=1)
    {
        $roles = $this->Role->find('list');
        if (!in_array($roleId, array_keys($roles))) {
            $this->Session->setFlash(__('Error: Invalid Id.', true));
            $this->redirect('view');
        }

        $acos = $this->Acl->Aco->find('threaded');
        $group_aro = $this->Acl->Aro->find('threaded', array('conditions'=>array('Aro.foreign_key'=>$roleId, 'Aro.model'=>'Role')));
        $permissions = $this->Access->loadPermissions($acos, $group_aro);

        $this->set('roles', $roles);
        $this->set('roleId', $roleId);
        $this->set('permissions', $permissions);
        $this->set('title_for_layout', __('Permissions Editor',true).' > '.$roles[$roleId]);
    }

    /**
     * edit
     *
     * @param mixed $access allow or deny
     * @param mixed $acoId  aco id
     * @param mixed $aroId  role id
     * @param mixed $action action
     *
     * @access public
     * @return void
     */
    public function edit($access, $acoId, $aroId, $action='all')
    {
        $permission = $this->Access->find('first', array(
            'conditions' => array('aro_id' => $aroId, 'aco_id' => $acoId)
        ));

        $aco = $this->Acl->Aco->findById($acoId);
        $aro = $this->Acl->Aro->findById($aroId);
        $accesses = array('allow', 'deny');
        if (!$aco || !$aro || !in_array($access, $accesses)) {
            $this->Session->setFlash(__('Error: Updating Permissions failed.', true));
            $this->redirect('view/'.$aroId);
        }

        // check acoId and aroId exists, check $access is valid

        $newEntry = empty($permission) ? true : false;
        $actions = array('create', 'read', 'update', 'delete');
        $access = ($access == 'deny') ? -1 : 1;

        // create a new entry that deny or allow all actions
        if ($newEntry && $action == 'all') {
            $permission['aro_id'] = $aroId;
            $permission['aco_id'] = $acoId;
            foreach ($actions as $act) {
                $permission['_'.$act] = $access;
            }
        // update an entry that deny or allow all actions
        } else if (!$newEntry && $action == 'all') {
            $permission = $permission['Access'];
            foreach ($actions as $act) {
                $permission['_'.$act] = $access;
            }
        } else if ($newEntry && in_array($action, $actions)) {
            $alias = strtolower($aco['Aco']['alias']);
            while (isset($aco['Aco']['parent_id'])) {
                $aco = $this->Acl->Aco->findById($aco['Aco']['parent_id']);
                $alias = strtolower($aco['Aco']['alias'].'/'.$alias);
            }
            $acos = $this->Acl->Aco->find('threaded');
            $group_aro = $this->Acl->Aro->find('threaded', array(
                'conditions'=>array('Aro.foreign_key'=>$aroId, 'Aro.model'=>'Role')));
            $permissions = $this->Access->loadPermissions($acos, $group_aro);
            $permissions = $permissions[$alias];
            $permission['aro_id'] = $aroId;
            $permission['aco_id'] = $acoId;
            foreach ($actions as $act) {
                if ($act == $action) {
                    $permission['_'.$act] = $access;
                } else {
                    $permission['_'.$act] = $permissions[$act];
                }
            }
        } else if (!$newEntry && in_array($action, $actions)) {
            $permission = $permission['Access'];
            $permission['_'.$action] = $access;
        }
        $permission = array('Access' => $permission);

        if (!empty($permission) && $this->Access->save($permission)) {
            $this->Session->setFlash(__('Permissions have been updated', true), 'good');
        } else {
            $this->Session->setFlash(__('Error: Updating Permissions failed.', true));
        }
        $this->redirect('view/'.$aroId);
    }
}
