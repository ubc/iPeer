<?php
App::import('Vendor', 'PHPExcel', array('file' => 'excel/PHPExcel.php'));
App::import('Vendor', 'PHPExcelWriter', array('file' => 'excel/PHPExcel/Writer/Excel5.php'));
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
        'UserEnrol', 'Group', 'Event', 'User', 'UserFaculty', 'Department');
    public $show;
    public $sortBy;
    public $direction;
    public $page;
    public $order;
    public $Sanitize;
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
        $this->Sanitize = new Sanitize;
        $this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
        if ($this->show == 'all') {
            $this->show = 99999999;
        }
        $this->sortBy = empty($_GET['sort'])? 'created': $this->Sanitize->paranoid($_GET['sort']);
        $this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
        $this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
        $this->order = $this->sortBy.' '.strtoupper($this->direction);
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
            array("Course.homepage",      __("Web", true),         "4em",  "link",   "home.gif"),
            array("Course.course",        __("Course", true),      "15em",  "action", "Course Home"),
            array("Course.title",         __("Title", true),       "auto", "action", "Course Home"),
            array("Course.creator_id",           "",            "",     "hidden"),
            array("Course.record_status", __("Status", true),      "5em",  "map",     array("A" => __("Active", true), "I" => __("Inactive", true))),
            array("Course.creator",     __("Created by", true),  "10em", "action", "View Creator"));


        // put all the joins together
        $joinTables = array();

        // For instructors: only list their own courses
        $extraFilters = (User::hasRole('superadmin') || User::hasRole('admin')) ?
            array() :
            array('Instructor.id' => $this->Auth->user('id'));

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
        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash('You do not have permission to view courses.');
            $this->redirect('/home');
        }

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
        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash('You do not have permission to view courses.');
            $this->redirect('/home');
        }
        $course = $this->Course->find('first', array('conditions' => array('id' => $id), 'recursive' => 1));
        if (empty($course)) {
            $this->Session->setFlash(__('Error: That course does not exist.', true));
            $this->redirect('index');
        }

        $this->set('data', $this->Course->read(null, $id));
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
        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash('You do not have permission to view courses.');
            $this->redirect('/home');
        }

        $course = $this->Course->find('first', array('conditions' => array('id' => $id), 'recursive' => 1));
        if (empty($course)) {
            $this->Session->setFlash(__('Error: That course does not exist.', true));
            $this->redirect('index');
        }
        $this->set('data', $course);
        $this->set('course_id', $id);
        $this->set('export_eval_link', 'courses/export/'.$id);

        $students = $course['Course']['student_count'];
        $this->set('studentCount', $students);

        $this->set('groupCount', count($course['Group']));
        $this->set('eventCount', count($course['Event']));

        $this->set('title_for_layout',
            $course['Course']['course'].' - '.$course['Course']['title']);

        //Setup the Personalization list
        if (empty($this->userPersonalize->personalizeList)) {
            $personalizeData = $this->Personalize->find('all', array('conditions' => array('user_id' => $this->Auth->user('id'))));
            $this->userPersonalize->setPersonalizeList($personalizeData);
        }
        $this->set('userPersonalize', $this->userPersonalize);

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
            $this->set('departments', $departments);
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
            $this->set('departments', $departments);
        }

        // set the list available statuses
        $statusOptions = array( 'A' => 'Active', 'I' => 'Inactive');
        $this->set('statusOptions', $statusOptions);

        // set the list of instructors
        $instructors = $this->User->getInstructors('list', 
            array('User.username'));
        $this->set('instructors', $instructors);

    }

    /**
     * add
     *
     * @access public
     * @return void
     */
    public function add()
    {
        if (!User::hasPermission('controllers/courses/add')) {
            $this->Session->setFlash('You do not have permission to add courses');
            $this->redirect('/home');
        }
        $this->set('title_for_layout', 'Add Course');

        $this->_initFormEnv();

        if (!empty($this->data)) {
            if ($this->Course->save($this->data)) {
                // add current user to the new course
                $this->Course->addInstructor($this->Course->id, 
                    $this->Auth->user('id'));
                $this->Session->setFlash('Course created!', 'good');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Add course failed.');
            }
        }

    }

    /**
     * edit
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    public function edit($id)
    {
        if (!User::hasPermission('controllers/courses/edit')) {
            $this->Session->setFlash(__('You do not have permission to edit courses.', true));
            $this->redirect('/home');
        }
        $course = $this->Course->find('first', array('conditions' => array('id' => $id), 'recursive' => 1));
        if (empty($course)) {
            $this->Session->setFlash(__('Error: That course does not exist.', true));
            $this->redirect('index');
        }
        $this->set('title_for_layout', 'Edit Course');
        $this->_initFormEnv();

        if (!empty($this->data)) {
            $success = $this->Course->save($this->data);
            if ($success) {
                $this->Session->setFlash(__('The course was updated successfully.', true), 'good');
                $this->redirect('index');
            } else if (!$success) {
                $this->Session->setFlash(__('Error: Course edits could not be saved', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Course->read(null, $id);
        }
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
        if (!User::hasPermission('functions/user')) {
            $this->Session->setFlash('You do not have permission to delete courses');
            $this->redirect('/home');
        }

        if ($this->Course->delete($id)) {
            //Delete all corresponding data start here
            $course = $this->Course->findById($id);

            //Instructors: Instructor record will remain in database, but the join table records will be deleted
            $instructors = $course['UserCourse'];
            if (!empty($instructors)) {
                foreach ($instructors as $index -> $value) {
                    $this->UserCourse-del($value['id']);
                }
            }

            //Students: Students who enrolled in other courses will not be deleted;
            //          Else, Student records will be deleted
            $students = $course['UserEnrol'];
            if (!empty($students)) {
                foreach ($students as $index -> $value) {
                    $this->UserCourse-del($value['id']);

                    //Check whether there is other enrolled courses existed
                    $otherCourse = $this->UserCourse->getById($value['user_id']);
                    if (empty($otherCourse)) {
                        $this->User->del($value['user_id']);
                    }
                }
            }

            //Events: TODO
            $events = $course['Event'];
            if (!empty($events)) {

            }

            $this->Session->setFlash(
                __('The course was deleted successfully.', true), 'good');
        } else {
            $this->Session->setFlash('Cannot delete the course. Check errors below');
        }
        $this->redirect('index');
    }


    /**
     * addInstructor
     *
     * @access public
     * @return void
     */
    function addInstructor()
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
     * @access public
     * @return void
     */
    function deleteInstructor()
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
    }

    /**
     * checkDuplicateName
     *
     * @access public
     * @return void
     */
    function checkDuplicateName()
    {
        $this->layout = 'ajax';
        $this->autoRender = false;

        $course = $this->Course->getCourseByCourse($this->data['Course']['course'], array('contain' => false));

        // check if the course is unique or the name is unchanged.
        return (empty($course) || (1 == count($course) && $this->params['named']['course_id'] == $course[0]['Course']['id'])) ?
            '' : __('Duplicated course.', true);
    }


    /**
     * update
     *
     * @param string $attributeCode  attribute code
     * @param string $attributeValue attribute value
     *
     * @access public
     * @return void
     */
    function update($attributeCode='',$attributeValue='')
    {
        $this->layout = false;
        $this->set('course_id', $this->Session->read('ipeerSession.courseId'));

        if ($attributeCode != '') {
            $this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode, $attributeValue);
            $this->set('attributeCode', $attributeCode);

            $personalizeData = $this->Personalize->find('all', array('conditions' => array('user_id' => $this->Auth->user('id'))));
            $this->userPersonalize->setPersonalizeList($personalizeData);

            $this->set('userPersonalize', $this->userPersonalize);
            if ($attributeValue == '') {
                $this->render('update');
            }
        }
    }
}
