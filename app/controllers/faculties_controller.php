<?php
/**
 * Faculties Controller
 */
class FacultiesController extends AppController {
    public $name = 'Faculties';
    public $uses = array('Faculty', 'Department', 'UserFaculty');

    /**
     * Make sure the user has permission to access the faculties pages.
     * */
    public function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * Display all faculties.
     * */
    public function index() {
        $ret = $this->Faculty->find('all');
        $faculties = array();
        foreach ($ret as $faculty) {
            $tmp = array();
            $tmp['id'] = $faculty['Faculty']['id'];
            $tmp['Name'] = $faculty['Faculty']['name'];
            $faculties[] = $tmp;
        }
        $this->set('faculties', $faculties);
    }

    /**
     * Display a specific faculty.
     *
     * @param mixed $id - the id of the faculty to be displayed
     */
    function view($id)
    {
        $this->set('title_for_layout', __('View Faculty',true));

        if (!$id) {
            $this->Session->setFlash(__('Invalid faculty', true));
            $this->redirect(array('action' => 'index'));
        }

        // there's pretty much only one interesting field in faculty
        $this->set(
            'faculty',
            $this->Faculty->field('name', array('id' => $id))
        );
        // Get this faculty's departments
        $ret = $this->Department->findAllByFacultyId($id);
        $departments = array();
        foreach ($ret as $department) {
            $tmp = array();
            $tmp['id'] = $department['Department']['id'];
            $tmp['Name'] = $department['Department']['name'];
            $departments[] = $tmp;
        }
        $this->set('departments', $departments);
        // Get this faculty's admins
        // for now, we assume that only admins will have a
        // UserFaculty entry
        $ret = $this->UserFaculty->findAllByFacultyId($id);
        $userfaculty = array();

        foreach ($ret as $user) {
            $tmp = array();
            $prof = $this->User->findById($user['UserFaculty']['user_id']);
            $tmp['id'] = $prof['User']['id'];
            $tmp['Username'] = $prof['User']['username'];
            $tmp['Full Name'] = $prof['User']['full_name'];
            $tmp['Email'] = $prof['User']['email'];
            $tmp['Role'] = $prof['Role'][0]['name'];
            $userfaculty[] = $tmp;
        }
        $this->set('userfaculty', $userfaculty);
    }

    /**
     * Add a new faculty.
     * */
    function add() {
        $this->set('title_for_layout', 'Add Faculty');
        $this->RolesUser = Classregistry::init('RolesUser');

        $superadmins = $this->RolesUser->find('all', array('conditions' => array('role_id' => 1)));
        $userfac = array();

        if (!empty($this->data)) {
            $this->Faculty->create();
            $this->data['Faculty']['name'] = trim($this->data['Faculty']['name']);
            if ($this->Faculty->save($this->data)) {
                $facultyId = $this->Faculty->getLastInsertID();
                foreach ($superadmins as $sa) {
                    $userfac[] = array('user_id' => $sa['RolesUser']['user_id'], 'faculty_id' => $facultyId);
                }
                $this->UserFaculty->saveAll($userfac);
                $this->Session->setFlash(
                    __('Faculty added!', true), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The faculty could not be saved. Please, try again.', true));
            }
        }
    }

    /**
     * Edit an existing faculty.
     *
     * @param mixed $id - the id of the faculty to be edited
     */
    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid faculty', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->data['Faculty']['name'] = trim($this->data['Faculty']['name']);
            if ($this->Faculty->save($this->data)) {
                $this->Session->setFlash(__('Faculty saved.', true), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The faculty could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Faculty->read(null, $id);
        }
    }

    /**
     * Delete a faculty
     *
     * @param mixed $id - the id of the faculty to be deleted
     */
    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for faculty', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Faculty->delete($id)) {
            $this->Session->setFlash(__('Faculty deleted', true), 'good');
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('Faculty was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
}
