<?php
/**
 * CoursesController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class CoursesController extends AppController
{
    public $name = 'Courses';
    public $uses =  array('GroupEvent', 'Course', 'Personalize', 'UserCourse',
        'UserEnrol', 'Group', 'Event', 'User', 'UserFaculty', 'Department',
        'CourseDepartment');
    public $helpers = array('Html', 'Ajax', 'excel', 'Javascript', 'Time', 'Js' => array('Prototype'));
    public $components = array('ExportBaseNew', 'AjaxList', 'ExportCsv', 'ExportExcel');

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->set('title_for_layout', 'Courses');
        parent::__construct();
    }

    /**
     * _setUpAjaxList
     *
     * @access public
     * @return void
     */
    function _setUpAjaxList()
    {
        // Set up Columns
        $columns = array(
            array("Course.id",            "",            "",      "hidden"),
            array("Course.course",        __("Course", true),      "15em",  "action", "Course Home"),
            array("Course.title",         __("Title", true),       "auto", "action", "Course Home"),
            array("Course.creator_id",           "",            "",     "hidden"),
            array("Course.record_status", __("Status", true),      "5em",  "map",     array("A" => __("Active", true), "I" => __("Inactive", true))),
            array("Course.creator",     __("Created by", true),  "10em", "action", "View Creator"));


        // put all the joins together
        $joinTables = array();

        // super admins
        if (User::hasPermission('functions/superadmin')) {
            $extraFilters = '';
        // faculty admins
        } else if (User::hasPermission('controllers/departments')) {
            $courseList = User::getMyDepartmentsCourseList('list');
            $extraFilters = array('Course.id' => array_keys($courseList));
        // instructors
        } else {
            $extraFilters = array('Instructor.id' => $this->Auth->user('id'));
        }

        // Set up actions
        $warning = __("Are you sure you want to delete this course permanently?", true);

        $actions = array(
            array(__("Course Home", true), "", "", "", "home", "Course.id"),
            array(__("View Record", true), "", "", "", "view", "Course.id"),
            array(__("Edit Course", true), "", "", "", "edit", "Course.id"),
            array(__("Delete Course", true), $warning, "", "", "delete", "Course.id"),
            array(__("View Creator", true), "",    "", "users", "view", "Course.creator_id"));

        $recursive = 0;

        $this->AjaxList->setUp($this->Course, $columns, $actions,
            'Course.course', 'Course.course', $joinTables, $extraFilters, $recursive);
    }


    /**
     * daysLate
     *
     * @param mixed $event          event
     * @param mixed $submissionDate submission date
     *
     * @access public
     * @return void
     */
    function daysLate($event, $submissionDate)
    {
        $days = 0;
        $dueDate = $this->Event->find('first', array('conditions' => array('Event.id' => $event), 'fields' => array('Event.due_date')));
        $dueDate = new DateTime($dueDate['Event']['due_date']);
        $submissionDate = new DateTime($submissionDate);
        $dateDiff = $dueDate->diff($submissionDate);
        if (!$dateDiff->format('%r')) {
            $days = $dateDiff->format('%d');
            if ($dateDiff->format('%i') || $dateDiff->format('%s')) {
                $days++;
            }
        }
        return $days;
    }

    /**
     * index
     *
     * @access public
     * @return void
     */
    function index()
    {
        // Set up the basic static ajax list variables
        $this->_setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }

    /**
     * ajaxList
     *
     * @access public
     * @return void
     */
    function ajaxList()
    {
        // Set up the list
        $this->_setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }

    /**
     * view
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function view($id)
    {
        $course = $this->Course->getAccessibleCourseById($id, User::get('id'), User::getCourseFilterPermission(), array('Instructor'));
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }

        $this->set('data', $course);
    }

    /**
     * home
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function home($id)
    {
        $course = $this->Course->getAccessibleCourseById($id, User::get('id'), User::getCourseFilterPermission(), array('Instructor', 'Tutor', 'Event', 'Group'));
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }

        $this->set('data', $course);
        $this->set('title_for_layout', $course['Course']['full_name']);

        //Setup the courseId to session
        $this->Session->write('ipeerSession.courseId', $id);

        $this->render('home');
    }

    /**
     * Set all the necessary variables for the Add and Edit form elements.
     *
     * @return void
     * */
    public function _initFormEnv() {
        // set the list of departments
        if (User::hasPermission('functions/user/superadmin')) {
            // superadmin permission means you see all departments regardless
            $departments = $this->Course->Department->find('list');
            $instructorList = $this->User->getInstructors('list', array());
        } else {
            // need to limit the departments this user can see
            // get the user's faculties
            $uf = $this->UserFaculty->findAllByUserId($this->Auth->user('id'));
            // get the departments of those faculties
            $ret = $this->Department->getByUserFaculties($uf);
            $departments = array();
            foreach ($ret as $department) {
                $departments[$department['Department']['id']] =
                    $department['Department']['name'];
            }
            // a hack for transition from 2.x
            // exisintg instructors may not get assigned to any department,
            // they have no way to assign course to department. So showing all
            // deparments for those who don't get any deparment
            if (empty($departments)) {
                $departments = $this->Course->Department->find('list');
            }
            $facultyIds = Set::extract($uf, '/UserFaculty/faculty_id');
            $instructorList = $this->User->getInstructorListByFaculty($facultyIds);
        }
        // set the list available statuses
        $statusOptions = array( 'A' => 'Active', 'I' => 'Inactive');
        $this->set('statusOptions', $statusOptions);

        $this->set('departments', $departments);

        // set the list of instructors
        $this->set('instructors', $instructorList);
    }

    /**
     * add
     *
     * @access public
     * @return void
     */
    public function add()
    {
        $this->set('title_for_layout', 'Add Course');
        $this->_initFormEnv();
        $this->set('instructorSelected', User::get('id'));

        if (!empty($this->data)) {
            if ($this->Course->save($this->data)) {
                // add current user to the new course if the user is not an admin
                if (!User::hasPermission('controllers/departments')) {
                    $this->Course->addInstructor($this->Course->id,
                        $this->Auth->user('id'));
                }
                // assign departments to the course if none were selected
                // based on the faculties the instructor(s)' are in
                if (empty($this->data['Department']['Department'])) {
                    $instructors = $this->UserCourse->findAllByCourseId($this->Course->id);
                    $faculty = $this->UserFaculty->findAllByUserId(Set::extract('/UserCourse/user_id', $instructors));
                    $department = $this->Department->findAllByFacultyId(Set::extract('/UserFaculty/faculty_id', $faculty));
                    $this->CourseDepartment->insertCourses($this->Course->id, Set::extract('/Department/id', $department));
                }
                $this->Session->setFlash('Course created!', 'good');
                $this->redirect('index');
                return;
            } else {
                $this->Session->setFlash('Add course failed.');
            }
        }
        $this->render('edit');
    }

    /**
     * edit
     *
     * @param int $courseId
     *
     * @access public
     * @return void
     */
    public function edit($courseId)
    {
        $this->_initFormEnv();

        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission(), array('Instructor', 'Department'));
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }

        if (!empty($this->data)) {
            $success = $this->Course->save($this->data);
            if ($success) {
                $this->Session->setFlash(__('The course was updated successfully.', true), 'good');
                $this->redirect('index');
                return;
            } else if (!$success) {
                $this->Session->setFlash(__('Error: Course edits could not be saved.', true));
            }
        }

        $this->data = $course;
        $this->set('breadcrumb', $this->breadcrumb->push(array('course' => $course['Course']))->push(__('Edit Course', true)));
    }


    /**
     * delete
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function delete($id)
    {
        $course = $this->Course->getAccessibleCourseById($id, User::get('id'), User::getCourseFilterPermission());
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }

        if ($this->Course->delete($id)) {
            //Delete all corresponding data start here
            //Instructors: Instructor record will remain in database, but the join table records will be deleted
            $this->Course->UserCourse->deleteAll(array('UserCourse.course_id' => $id));

            // same for students
            $this->Course->UserEnrol->deleteAll(array('UserEnrol.course_id' => $id));
            //Events: TODO
            $this->Session->setFlash(__('The course was deleted successfully.', true), 'good');
        } else {
            $this->Session->setFlash('Cannot delete the course. Check errors below');
        }
        $this->redirect('index');
    }

    /**
     * addInstructor
     *
     * @deprecated deprecated since version 3.0
     * @access public
     * @return void
     */
/*    function addInstructor()
    {
        if ((!isset($this->passedArgs['instructor_id']) || !isset($this->passedArgs['course_id'])) &&
            (!isset($this->params['form']['instructor_id']) || !isset($this->params['form']['course_id']))) {
                $this->cakeError('error404');
        }

        $instructor_id = isset($this->passedArgs['instructor_id']) ? $this->passedArgs['instructor_id'] : $this->params['form']['instructor_id'];
        $course_id = isset($this->passedArgs['course_id']) ? $this->passedArgs['course_id'] : $this->params['form']['course_id'];

        if (!($instructor = $this->Course->Instructor->find('first', array('conditions' => array('Instructor.id' => $instructor_id))))) {
            $this->cakeError('error404');
        }

        if (!($this->Course->find('first', array('conditions' => array('Course.id' => $course_id))))) {
            $this->cakeError('error404');
        }

        //$this->autoRender = false;
        $this->layout = false;
        $this->ajax = true;
        if ($this->Course->addInstructor($course_id, $instructor_id)) {
            $this->set('instructor', $instructor['Instructor']);
            $this->set('course_id', $course_id);
            $this->render('/elements/courses/edit_instructor');
        } else {
            return __('Unknown error', true);
        }

    }

    /**
     * deleteInstructor
     *
     * @deprecated deprecated since version 3.0
     * @access public
     * @return void
     */
    /*function deleteInstructor()
    {
        if (!isset($this->passedArgs['instructor_id']) || !isset($this->passedArgs['course_id'])) {
            $this->cakeError('error404');
        }

        $this->autoRender = false;
        $this->ajax = true;
        if ($this->Course->deleteInstructor($this->passedArgs['course_id'], $this->passedArgs['instructor_id'])) {
            return '';
        } else {
            return __('Unknown error', true);
        }
    }*/
}
