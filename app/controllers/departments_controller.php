<?php
/**
 * DepartmentsController
 *
 */
class DepartmentsController extends AppController {

    public $name = 'Departments';
    public $uses = array('Department', 'Faculty', 'CourseDepartment', 
        'UserFaculty', 'Course');

    /**
     * Make sure the user has permission to access the departments pages.
     * */
    public function beforeFilter() {
        parent::beforeFilter();
        if (!User::hasPermission('controllers/Departments')) {
            $this->Session->setFlash(__('Permission to access departments '.
                'not found.', true));
            $this->redirect('/home');
        }
    }

    /**
     * Displays the list of departments appropriate to access level.
     * */
    public function index() {
        if (User::hasPermission('functions/user/superadmin')) {
            $ret = $this->Department->find('all');
        } else {
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

    /**
     * View a department entry.
     *
     * @param mixed $id - The department id to be viewed
     *
     * */
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
        foreach ($ret as $val) {
            $course = $this->Course->findById(
                $val['CourseDepartment']['course_id']);
            $tmp = array();
            $tmp['id'] = $course['Course']['id'];
            $tmp['Course'] = $course['Course']['course'];
            $tmp['Title'] = $course['Course']['title'];
            $tmp['Students'] = $course['Course']['student_count'];
            $courses[] = $tmp;
        }
        $this->set('courses', $courses);
    }

    /**
     * Add a new department entry.
     * */
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

    /**
     * Edit an existing department entry.
     *
     * @param mixed $id - The id of the department entry to be edited
     *
     * */
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

    /**
     * Delete an existing department entry.
     *
     * @param mixed $id - The id of the department entry to be edited
     *
     * */
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

    /**
     * Set the necessary variables needed to display the Add and Edit
     * form elements.
     * */
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
