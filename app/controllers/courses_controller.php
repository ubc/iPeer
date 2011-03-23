<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/07/20 18:10:32 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Courses
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */

class CoursesController extends AppController
{
	var $name = 'Courses';
	var $uses =  array('Course', 'Personalize', 'UserCourse', 'UserEnrol', 'Group', 'Event', 'User');
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $Sanitize;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination', 'Js' => array('Prototype'));
	var $components = array("AjaxList");

	function __construct() {
		$this->Sanitize = new Sanitize;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'created': $this->Sanitize->paranoid($_GET['sort']);
		$this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->set('title_for_layout', 'Courses');
		parent::__construct();
	}

  function setUpAjaxList() {
    // Set up Columns
    $columns = array(
        array("Course.id",            "",            "",      "hidden"),
        array("Course.homepage",      "Web",         "4em",  "link",   "home.gif"),
        array("Course.course",        "Course",      "15em",  "action", "Course Home"),
        array("Course.title",         "Title",       "auto", "action", "Course Home"),
        array("Creator.id",           "",            "",     "hidden"),
#        array("Instructor.full_name",  "Instructor *","10em", "action", "View Instructor"),
#        array("Instructor.last_name",  "Instructor *","10em", "action", "View Instructor"),
        array("Course.record_status", "Status",      "5em",  "map",
            array("A" => "Active", "I" => "Inactive")),
        array("Course.creator",     "Created by",  "10em", "action", "View Creator"),
        array("Instructor.id",        "",            "",     "hidden"));

    // Join with
    $jointTableCreator =
        array("localKey"   => "creator_id",
              "joinTable"  => "users",
              "joinModel"  => "Creator");
    // Join with user courses for instructor ID's
    $joinTableInstructorsIDs =
        array("joinTable" => "user_courses",
              "joinModel" => "UserCourse",
              "foreignKey" => "course_id");
    // Join with users to translate instructor ID's into usernames
    $joinTableInsturctorUserName =
        array("localModel" => "UserCourse",
              "localKey" => "user_id",
              "joinTable" => "users",
              "joinModel" => "Instructor");

    // put all the joins together
    $joinTables = array($jointTableCreator,
                        $joinTableInstructorsIDs,
                        $joinTableInsturctorUserName);

    // For instructors: only list their own courses
    $extraFilters = $this->Auth->user('role') != 'A' ?
        array("Instructor.id" => $this->Auth->user('id')) :
        null;

    // Set up actions
    $warning = "Are you sure you want to delete this course permanently?";
    $actions = array(
        array("Course Home", "", "", "", "home", "Course.id"),
        array("View Record", "", "", "", "view", "Course.id"),
        array("Edit Course", "", "", "", "edit", "Course.id"),
        array("Delete Course", $warning, "", "", "delete", "Course.id"),
        array("View Creator", "",    "", "users", "view", "Creator.id"),
        array("View Instructor", "", "", "users", "view", "Instructor.id"));

    $recursive = 0;

    $this->AjaxList->setUp($this->Course, $columns, $actions,
        "Course.course", "Course.course", $joinTables, $extraFilters, $recursive);
  }

  function index() {
    // Set up the basic static ajax list variables
    $this->setUpAjaxList();
    // Set the display list
    $this->set('paramsForList', $this->AjaxList->getParamsForList());
  }

  function ajaxList() {
    // Set up the list
    $this->setUpAjaxList();
    // Process the request for data
    $this->AjaxList->asyncGet();
  }

	function view($id) {
		$this->set('data', $this->Course->read(null, $id));
	}

	function home($id) {
    $course = $this->Course->find('first', array('conditions' => array('id' => $id), 'recursive' => 2));
    $this->set('data', $course);
    $this->set('course_id', $id);

    $students = $this->Course->getEnrolledStudentCount($id);
    $this->set('studentCount', $students);;

    $this->set('groupCount', count($course['Group']));
    $this->set('eventCount', count($course['Event']));

    $this->set('title_for_layout', $this->sysContainer->getCourseName($id));

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

	function add() {
		if (!empty($this->data)) {
			if ($this->data = $this->Course->save($this->data)) {
        // add current user to the new course
        $this->Course->addInstructor($this->Course->id, $this->Auth->user('id'));
        $this->Session->setFlash('The course has been created.');
        //$this->sysContainer->setMyCourseList($myCourses);
				$this->redirect(array('action' => 'edit', $this->Course->id));
			}
    }
    $this->set('course_id', 0);
    $this->set('data', $this->data);
    $this->render('edit');
	}

	function edit($id) {
    if(!is_numeric($id)) {
      $this->Session->setFlash('Invalid course ID.');
      $this->redirect('index');
    }

    $this->data['Course']['id'] = $id;

		if (!empty($this->data) && $this->Course->save($this->data)) {
      $this->Session->setFlash('The course was updated successfully.');
      $this->redirect('index');
    } else {
      $this->data = $this->Course->read(null, $id);

      $this->set('instructors_rest', 
                 $this->Course->getAllInstructors('list', array('excludes' => $this->data['Instructor'])));
      $this->set('data', $this->data);
      $this->set('course_id', $this->data['Course']['id']);
      //$this->set('errmsg', $this->Course->errorMessage);
		} 
  }

	function delete($id)
	{
		if ($this->Course->del($id))
		{
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
            //refresh my accessible courses on session
            $myCourses = $this->Course->findAccessibleCoursesListByUserIdRole($this->Auth->user('id'), $this->Auth->user('role'));
            $this->sysContainer->setMyCourseList($myCourses);
            // Finished all deletion of course related data
            $this->redirect('/courses/index/The course was deleted successfully.');
		} else {
		  $this->set('errmsg', $this->Course->errorMessage);
		  $this->redirect('/courses/index');
		}
	}

  function addInstructor() {
    if((!isset($this->passedArgs['instructor_id']) || !isset($this->passedArgs['course_id'])) &&
       (!isset($this->params['form']['instructor_id']) || !isset($this->params['form']['course_id']))) {
      $this->cakeError('error404');
    }

    $instructor_id = isset($this->passedArgs['instructor_id']) ? $this->passedArgs['instructor_id'] : $this->params['form']['instructor_id'];
    $course_id = isset($this->passedArgs['course_id']) ? $this->passedArgs['course_id'] : $this->params['form']['course_id'];

    if(!($instructor = $this->Course->Instructor->find('first', array('conditions' => array('Instructor.id' => $instructor_id))))) {
        $this->cakeError('error404');
    }

    if(!($course = $this->Course->find('first', array('conditions' => array('Course.id' => $course_id))))) {
        $this->cakeError('error404');
    }

    //$this->autoRender = false;
    $this->layout = false;
    $this->ajax = true;
		if($this->Course->addInstructor($course_id, $instructor_id)) {
      $this->set('instructor', $instructor['Instructor']);
      $this->set('course_id', $course_id);
      $this->render('/elements/courses/edit_instructor');
    } else {
      return 'Unknown error';
    }

  }

	function deleteInstructor() {
    if(!isset($this->passedArgs['instructor_id']) || !isset($this->passedArgs['course_id'])) {
      $this->cakeError('error404');
    }

    $this->autoRender = false;
    $this->ajax = true;
		if($this->Course->deleteInstructor($this->passedArgs['course_id'], $this->passedArgs['instructor_id'])) {
      return '';
    } else {
      return 'Unknown error';
    }
	}

  function checkDuplicateName()
  {
      $this->layout = 'ajax';
      $this->autoRender = false;

      $course = $this->Course->getCourseByCourse($this->data['Course']['course'], array('contain' => false));

      // check if the course is unique or the name is unchanged.
      return (empty($course) || (1 == count($course) && $this->params['named']['course_id'] == $course[0]['Course']['id'])) ?
        '' : 'Duplicated course.';
  }

	function update($attributeCode='',$attributeValue='')
	{
		$this->layout = false;
    $this->set('course_id', $this->Session->read('ipeerSession.courseId'));

		if ($attributeCode != '') {
      $this->params['data'] = $this->Personalize->updateAttribute($this->Auth->user('id'), $attributeCode,$attributeValue);
      $this->set('attributeCode',$attributeCode);

      $personalizeData = $this->Personalize->find('all', array('conditions' => array('user_id' => $this->Auth->user('id'))));
      $this->userPersonalize->setPersonalizeList($personalizeData);

      $this->set('userPersonalize', $this->userPersonalize);
      if ($attributeValue == '') {
        $this->render('update');
      }
		}
	}
}
?>
