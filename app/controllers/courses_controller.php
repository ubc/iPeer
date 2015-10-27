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
        'CourseDepartment', 'EvaluationSubmission', 'SurveyInput', 'UserTutor');
    public $helpers = array('Html', 'Ajax', 'excel', 'Javascript', 'Time',
        'Js' => array('Prototype'), 'FileUpload.FileUpload');
    public $components = array('ExportBaseNew', 'AjaxList', 'ExportCsv', 'ExportExcel',
        'FileUpload.FileUpload', 'RequestHandler');

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
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();

        $allowTypes = array(
            'text/plain', 'text/csv', 'application/csv',
            'application/csv.ms-excel', 'application/octet-stream',
            'text/comma-separated-values', 'text/anytext');
        $this->FileUpload->allowedTypes(array(
            'txt' => null,
            'csv' => null,
        ));
        $this->FileUpload->uploadDir(TMP);
        $this->FileUpload->fileModel(null);
        $this->FileUpload->attr('required', true);
        $this->FileUpload->attr('forceWebroot', false);
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
            // includes both FacultyAdmin'd and Instructed courses (outside admin fac)
            $adminList = User::getMyDepartmentsCourseList('list');
            $adminKeys = array_keys($adminList);
            $instrList = $this->Course->getCourseByInstructor($this->Auth->user('id'));
            $instrKeys = (Set::extract('/Course/id', $instrList));
            $extraFilters = array('Course.id' => array_merge($adminKeys, $instrKeys));
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
     * @param mixed $courseId courseId - default null (eg. add)
     *
     * @return void
     * */
    public function _initFormEnv($courseId = 0) {
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
            $departments = Set::combine($ret, '{n}.Department.id', '{n}.Department.name');
            $facultyIds = Set::extract($uf, '/UserFaculty/faculty_id');
            $instructorList = $this->User->getInstructorListByFaculty($facultyIds);
            // a hack for transition from 2.x
            // existing instructors may not get assigned to any department,
            // they have no way to assign course to department. So showing all
            // deparments for those who don't get any deparment
            if (empty($departments)) {
                $departments = $this->Course->Department->find('list');
                $instructorList = $this->User->getInstructors('list', array());
            }
        }
        // set the list available statuses
        $statusOptions = array( 'A' => 'Active', 'I' => 'Inactive');
        $this->set('statusOptions', $statusOptions);

        $this->set('departments', $departments);

        $currentStudents = Set::combine($this->User->getEnrolledStudents($courseId), '{n}.User.id', '{n}.User.full_name');

        $currentProf = $this->User->getInstructorsByCourse($courseId);
        $currentProf = Set::combine($currentProf, '{n}.User.id', '{n}.User.full_name');
        //prevent system-wide instructors that are a student in this course from being listed
        $instructorList = array_diff_key($instructorList,$currentStudents);
        $instructorList = $currentProf + array_diff($instructorList, $currentProf);

        $tutorList = $this->User->getTutors();
        //prevent system-wide tutors that are a student in this course from being listed
        $tutorList = array_diff_key($tutorList,$currentStudents);
        // since there could be users who are tutors in this course, but are not system wide tutors
        $existingTutors = Set::combine($this->User->getTutorsByCourse($courseId), '{n}.User.id', '{n}.User.full_name');
        $tutorList = $existingTutors + $tutorList;

        // set the list of instructors/tutors
        $this->set('instructors', $instructorList);
        $this->set('tutors', $tutorList);
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
            $this->data['Course'] = array_map('trim', $this->data['Course']);
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
        $this->_initFormEnv($courseId);

        $course = $this->Course->getAccessibleCourseById($courseId, User::get('id'), User::getCourseFilterPermission(), array('Instructor', 'Department'));
        if (!$course) {
            $this->Session->setFlash(__('Error: Course does not exist or you do not have permission to view this course.', true));
            $this->redirect('index');
            return;
        }

        if (!empty($this->data)) {
            $this->data['Course'] = array_map('trim', $this->data['Course']);
            $newInstructors = (!isset($this->data['Instructor'])) ? array() :
                $this->data['Instructor']['Instructor'];
            // delete instructors from the course if they are not in the new list
            $instructors = $this->UserCourse->findAllByCourseId($courseId);
            foreach ($instructors as $instructor) {
                if (!in_array($instructor['UserCourse']['user_id'], $newInstructors)) {
                    $this->UserCourse->delete($instructor['UserCourse']['id']);
                }
            }
            $success = $this->Course->save($this->data);
            if ($success) {
                $this->Session->setFlash(__('The course was updated successfully.', true), 'good');
                $this->redirect('index');
                return;
            } else if (!$success) {
                $this->Session->setFlash(__('Error: Course edits could not be saved.', true));
            }
        }

        $course['Instructor']['Instructor'] = Set::extract('/Instructor/id', $course);
        $tutors = $this->UserTutor->findAllByCourseId($courseId);
        $course['Tutor']['Tutor'] = Set::extract('/UserTutor/user_id', $tutors);

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
     * move
     *
     * @access public
     * @return void
     */
    function move()
    {
        if (!empty($this->data)) {
            $data = $this->data['Course'];
            $move = $data['action'];

            $destSub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(
                $data['destSurveys'], $data['submitters']);
            if (!empty($destSub)) {
                $this->Session->setFlash(__('The student has already submitted to the destination survey', true));
                $this->redirect('move');
                return;
            }
            // making copies of the submission and survey inputs
            $sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(
                $data['sourceSurveys'], $data['submitters']);
            $inputs = $this->SurveyInput->getByEventIdUserId(
                $data['sourceSurveys'], $data['submitters']);

            // if choose to copy set id to null
            if (!$move) {
                $sub['EvaluationSubmission']['id'] = null;
            }
            $sub['EvaluationSubmission']['event_id'] = $data['destSurveys'];
            $sInputs = array();
            foreach ($inputs as $input) {
                $tmp = $input['SurveyInput'];
                if (!$move) {
                    $tmp['id'] = null;
                }
                $tmp['event_id'] = $data['destSurveys'];
                $sInputs[] = $tmp;
            }
            $action = ($move) ? 'moved' : 'copied';
            $this->User->id = $data['submitters'];
            $student = $this->User->field('full_name');
            $this->Course->id = $data['destCourses'];
            $to = $this->Course->field('full_name');

            $msg = '';
            if ($this->EvaluationSubmission->save($sub) && $this->SurveyInput->saveAll($sInputs)) {
                $msg = sprintf(__('%s was successfully %s to %s.', true), $student, $action, $to);
            } else {
                $this->Session->setFlash(sprintf(__('%s was not successfully %s to %s.', true), $student, $action, $from));
                $this->redirect('move');
                return;
            }

            // if student is not enrolled in destination course - enrol them
            $enrol = $this->Course->getAccessibleCourseById($data['destCourses'], $data['submitters'], Course::FILTER_PERMISSION_ENROLLED);
            if (!$enrol) {
                if (!$this->User->addStudent($data['submitters'], $data['destCourses'])) {
                    $msg .= sprintf(__(' %s was unsuccessfully enrolled to %s.', true), $student, $to);
                }
            }

            if ($move) {
                $this->Course->id = $data['sourceCourses'];
                $from = $this->Course->field('full_name');
                if (!$this->User->removeStudent($data['submitters'], $data['sourceCourses'])) {
                    $msg .= sprintf(__(' %s was unsuccessfully unenrolled from %s.', true), $student, $from);
                }
            }
            $this->Session->setFlash($msg, 'good');
        }
        // clear data when user is redirected back to this page
        $this->data = null;

        $sourceCourses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        $sourceEvents = $this->Event->getActiveSurveyEvents(array_keys($sourceCourses));
        $courseIds = array_unique(Set::extract('/Event/course_id', $sourceEvents));
        $sourceCourses = $this->Course->getCourseList($courseIds);
        asort($sourceCourses);

        $this->set('sourceCourses', $sourceCourses);
        $this->set('sourceSurveys', array());
        $this->set('submitters', array());
        $this->set('destCourses', array());
        $this->set('destSurveys', array());
        $this->set('title_for_layout', __('Move Students', true));
    }

    /**
     * ajax_options
     *
     * @access public
     * @return void
     */
    function ajax_options()
    {
        if (!$this->RequestHandler->isAjax()) {
            $this->cakeError('error404');
        }

        switch($_GET['field']) {
            case 'sCourses':
                $options = $this->Event->getActiveSurveyEvents($_GET['courseId'], 'list');
                break;
            case 'sSurveys':
                $sub = $this->EvaluationSubmission->findAllByEventId($_GET['surveyId']);
                $options = $this->User->getFullNames(Set::extract('/EvaluationSubmission/submitter_id', $sub));
                break;
            case 'submitters':
                $event = $this->Event->findById($_GET['surveyId']);
                $destCourses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
                $destEvents = $this->Event->getSurveyByCourseIdTemplateId(array_keys($destCourses),
                    $event['Event']['template_id'], 'all');
                $options = $this->Course->getCourseList(array_unique(Set::extract('/Event/course_id', $destEvents)));
                unset($options[$_GET['courseId']]); //remove source course
                break;
            case 'dCourses':
                $event = $this->Event->findById($_GET['surveyId']);
                $options = $this->Event->getSurveyByCourseIdTemplateId(
                    $_GET['courseId'], $event['Event']['template_id']);
                break;
            case 'importDestCourses':
                $options = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
                unset($options[$_GET['courseId']]); // remove source course
                break;
        }

        asort($options);
        $this->set('options', $options);
    }

    /**
     * import
     *
     * @access public
     * @return void
     */
    function import()
    {
        if (!empty($this->data)) {
            // check that file upload worked
            if ($this->FileUpload->success) {
                $uploadFile = $this->FileUpload->uploadDir.DS.$this->FileUpload->finalFile;
            } else {
                $this->Session->setFlash($this->FileUpload->showErrors());
                return;
            }

            $students = Toolkit::parseCSV($uploadFile);
            $identifiers = Set::extract('/'.Course::IDENTIFIER, $students);
            $data = $this->data['Course'];
            $move = $data['action'];
            $field = $data['identifiers'];
            $fieldText = ($field == 'student_no') ? __('student number', true) : __('username', true);

            // create a copy of the source survey else grab destSurveys id
            if (isset($data['sourceSurveys'])) {
                if ($data['surveyChoices']) {
                    $event = $this->Event->findById($data['sourceSurveys']);
                    $event['Event']['id'] = null;
                    $event['Event']['course_id'] = $data['destCourses'];
                    if (!$this->Event->save($event['Event'])) {
                        $this->Session->setFlash(__('Error: Event was unable to be created.', true));
                        $this->redirect('import');
                        return;
                    }
                    $destEventId = $this->Event->getLastInsertID();
                } else {
                    $destEventId = $data['destSurveys'];
                }
            }

            $error = array();
            $success = array();
            $users = array();
            if (!empty($identifiers)) {
                $users = $this->User->find('all', array(
                    'conditions' => array('User.'.$field => $identifiers, 'Role.id' => $this->User->USER_TYPE_STUDENT),
                    'contain' => array('Role')
                ));
            }

            $invalid = array_diff($identifiers, Set::extract('/User/'.$field, $users));
            foreach ($invalid as $inv) {
                $error[$inv] = sprintf(__('No student with %s %s exists.', true), $fieldText, $inv);
            }
            $enrolled = $this->UserEnrol->findAllByCourseId($data['sourceCourses']);
            $enrolled = Set::extract('/UserEnrol/user_id', $enrolled);
            $destEnrolled = $this->UserEnrol->findAllByCourseId($data['destCourses']);
            $destEnrolled = Set::extract('/UserEnrol/user_id', $destEnrolled);
            if($move) {
                $event = $this->Event->findAllByCourseId($data['sourceCourses']);
                $eventIds = Set::extract('/Event/id', $event);
                $sub = $this->EvaluationSubmission->find('all', array(
                    'conditions' => array('grp_event_id !=' => null, 'submitter_id' => $enrolled,
                        'EvaluationSubmission.event_id' => $eventIds)));
                $submission = Set::combine($sub, '{n}.EvaluationSubmission.id', '{n}.EvaluationSubmission', '{n}.EvaluationSubmission.submitter_id');
            }
            // enrol student
            foreach ($users as $user) {
                $identifier = $user['User'][$field];
                // enrol student to destination course if not already enrolled
                if (!in_array($user['User']['id'], $destEnrolled)) {
                    if (!$this->User->addStudent($user['User']['id'], $data['destCourses'])) {
                        $error[$identifier] = __('The student could not be enrolled to the destination course.', true);
                        continue;
                    }
                }
                // move or copy survey submission
                if (isset($data['sourceSurveys'])) {
                    $sub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(
                        $data['sourceSurveys'], $user['User']['id']);
                    $destSub = $this->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter(
                        $data['destSurveys'], $user['User']['id']);
                    if ($sub && empty($destSub)) {
                        $inputs = $this->SurveyInput->getByEventIdUserId(
                            $data['sourceSurveys'], $user['User']['id']);
                        // if choose to copy set id to null
                        if (!$move) {
                            $sub['EvaluationSubmission']['id'] = null;
                        }
                        $sub['EvaluationSubmission']['event_id'] = $destEventId;
                        $sInputs = array();
                        foreach ($inputs as $input) {
                            $tmp = $input['SurveyInput'];
                            if (!$move) {
                                $tmp['id'] = null;
                            }
                            $tmp['event_id'] = $destEventId;
                            $sInputs[] = $tmp;
                        }
                        if (!($this->EvaluationSubmission->save($sub) && $this->SurveyInput->saveAll($sInputs))) {
                            $error[$identifier] = __("The student's survey submission could not be transferred, however they are enrolled in the destination course.", true);
                            continue;
                        }
                    }
                }
                if (!isset($error[$identifier])) {
                    $success[$identifier] = __('Success.', true);
                }

                if ($move && in_array($user['User']['id'], $enrolled)) {
                    if (!$this->User->removeStudent($user['User']['id'], $data['sourceCourses'])) {
                        $success[$identifier] .= __(' However they were unsuccessfully unenrolled from the source course.', true);
                    }
                } else if (!in_array($user['User']['id'], $enrolled)) {
                    $success[$identifier] .= sprintf(__(' However no student with %s %s was enrolled in the source course.', true), $fieldText, $identifier);
                }

                if ($move && isset($submission[$user['User']['id']])) {
                    $success[$identifier] .= "\n".__("The student has already submitted a peer evaluation in the source course.", true);
                }

                if (isset($data['sourceSurveys']) && !empty($destSub) && $sub) {
                    $success[$identifier] .= "\n".__("The student has already submitted to the
                        destination survey, therefore the survey submission from the source survey was not transferred.", true);
                }

            }
            $this->set('errors', $error);
            $this->set('success', $success);
            $this->set('courseId', $data['destCourses']);
            $this->set('identifier', ucwords($fieldText));
            $this->FileUpload->removeFile($uploadFile);
            $this->render('import_summary');
        }

        $destCourses = $this->Course->getAccessibleCourses(User::get('id'), User::getCourseFilterPermission(), 'list');
        //$sourceEvents = $this->Event->getActiveSurveyEvents(array_keys($destCourses));
        //$courseIds = array_unique(Set::extract('/Event/course_id', $sourceEvents));
        $courseIds = array_keys($destCourses);
        $sourceCourses = $this->Course->getCourseList($courseIds);
        asort($sourceCourses);
        $this->set('sourceCourses', $sourceCourses);
        $this->set('sourceSurveys', array());
        $this->set('destCourses', $destCourses);
        $this->set('destSurveys', array());
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
