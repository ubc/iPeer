<?php
class DepartmentsController extends AppController {

    public $name = 'Departments';
    public $uses = array('Department', 'Faculty', 'CourseDepartment', 
        'UserFaculty');

    public function index() {
        if (User::hasPermission('functions/user/superadmin')) {
            $ret = $this->Department->find('all');
        }
        else {
            $uf = $this->UserFaculty->findAllByUserId($this->Auth->user('id'));
            $ret = $this->Department->getByUserFaculties($uf);
        }
        $departments = array();
        foreach ($ret as $department) {
            $tmp = array(); 
            $tmp['id'] = $department['Department']['id'];
            $tmp['Name'] = $department['Department']['name'];
            $tmp['Faculty'] = $department['Faculty']['name'];
            $departments[] = $tmp;
        }
        $this->set('departments', $departments);
    }

    public function view($id = null) {
        $this->set('title_for_layout', 'View Department');
        if (!$id) {
            $this->Session->setFlash(__('Invalid department', true));
            $this->redirect(array('action' => 'index'));
        }
        $ret = $this->Department->read(null, $id);
        $this->set('department', $ret['Department']['name']);
        $this->set('faculty', $ret['Faculty']['name']);

        $ret = $this->CourseDepartment->findAllByDepartmentId($id);
        $courses = array();
        foreach ($ret as $course) {
            $tmp = array();
            $tmp['id'] = $course['Course']['id'];
            $tmp['Course'] = $course['Course']['course'];
            $tmp['Title'] = $course['Course']['title'];
            $tmp['Students'] = $course['Course']['student_count'];
            $courses[] = $tmp;
        }
        $this->set('courses', $courses);
    }

    public function add() {
        $this->set('title_for_layout', 'Add Department');
        $this->_initFormEnv();

        if (!empty($this->data)) {
            $this->Department->create();
            if ($this->Department->save($this->data)) {
                $this->Session->setFlash(
                    __('The department has been saved', true), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The department could not be saved. Please, try again.', true));
            }
            return;
        }
    }

    public function edit($id = null) {
        $this->set('title_for_layout', 'Edit Department');
        $this->_initFormEnv();

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid department', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Department->save($this->data)) {
                $this->Session->setFlash(
                    __('The department has been saved', true), 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The department could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Department->read(null, $id);
        }
    }

    public function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for department', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Department->delete($id)) {
            $this->Session->setFlash(__('Department deleted', true), 'good');
            $this->redirect(array('action'=>'index'));
        }
        $this->Session->setFlash(__('Department was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    private function _initFormEnv() {
        // prepare the faculty drop down options 
        $ret = $this->Faculty->find('all');
        $faculties = array();
        foreach ($ret as $faculty) {
            $faculties[$faculty['Faculty']['id']] = $faculty['Faculty']['name'];
        }
        $this->set('faculties', $faculties);
    }
}
