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
	var $uses =  array('Course', 'Personalize', 'UserCourse', 'UserEnrol', 'Group', 'Event');
	var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $Sanitize;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $components = array("AjaxList");

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'created': $this->Sanitize->paranoid($_GET['sort']);
		$this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->pageTitle = 'Courses';
		parent::__construct();
	}

    function setUpAjaxList() {
        // Set up Columns
        $columns = array(
            array("Course.id",            "ID",          "4em",  "number"),
            array("Course.homepage",      "Web",         "4em",  "link",   "home.gif"),
            array("Course.course",        "Course",      "15em",  "action", "Course Home"),
            array("Course.title",         "Title",       "auto", "action", "Course Home"),
            array("Creator.id",           "",            "",     "hidden"),
            array("Instructor.username",  "Instructor *","10em", "action", "View Instructor"),
            array("Course.record_status", "Status",      "5em",  "map",
                array("A" => "Active", "I" => "Inactive")),
            array("Creator.username",     "Created by",  "10em", "action", "View Creator"),
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
        $extraFilters = $this->rdAuth->role != 'A' ?
            array("Instructor.id" => $this->rdAuth->id) :
            null;

        // Set up actions
        $warning = "Are you sure you want to delete this course permanently?";
        $actions = array(
            array("Course Home", "", "", "home", "Course.id"),
            array("View Record", "", "", "view", "Course.id"),
            array("Edit Record", "", "", "edit", "Course.id"),
            array("Delete Course", $warning, "", "home", "Course.id"),
            array("View Creator", "",    "users", "view", "Creator.id"),
            array("View Instructor", "", "users", "view", "Instructor.id"));

        $recursive = 0;

        $this->AjaxList->setUp($this->Course, $columns, $actions,
            "Course.id", "Course.title", $joinTables, $extraFilters, $recursive);
    }


    function index($message='') {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
        // Set the top message
        $this->set('message', $message);
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }

    function ajaxList() {
        // Make sure the present user is not a student
        $this->rdAuth->noStudentsAllowed();
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }

	function view($id)
	{
		$this->set('data', $this->Course->read());
		$tmp = $this->Course->read();
		$this->set('instructor_data', $this->UserCourse->getInstructors($tmp['Course']['id']));
	}

	function home($id)
	{
        $course = $this->Course->findById($id);
        $this->set('data', $course);
        $students = $this->Course->getEnrolledStudentCount($id);
        $this->set('studentCount', $students);;

        $number_of_groups=count($this->Group->findAll('course_id = '.$id));
        $this->set('groupCount', $number_of_groups);
        $events = $this->Event->getCourseEventCount($id);
        $this->set('eventCount', $events[0]['total']);

        $this->pageTitle = $this->sysContainer->getCourseName($id);

        //Setup the Personalization list
        if (empty($this->userPersonalize->personalizeList)) {
            $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
            $this->userPersonalize->setPersonalizeList($personalizeData);
        }
        $this->set('userPersonalize', $this->userPersonalize);

        //Setup the courseId to session
        $this->rdAuth->setCourseId($id);

        $this->render('home');
	}

	function add() {
		$this->set('instructor', $this->Course->findInstructors());
		$this->set('instructor_count', count($this->Course->findInstructors()));

		if (empty($this->params['data']))
		{
			$this->render('add');
		} else {
            if (!stristr($this->params['data']['Course']['homepage'], "http://") || !stristr($this->params['data']['Course']['homepage'], "https://") ) {
                $this->params['data']['Course']['homepage'] = "http://" . $this->params['data']['Course']['homepage'];
            }
			$this->params = $this->Course->prepData($this->params);
			if ($this->Course->save($this->params['data'])) {
				$this->UserCourse->insertAdmin($this->Course->id);
				$this->UserCourse->insertInstructors($this->Course->id,$this->params['data']['Course']);
				//refresh my accessible courses on session
				$myCourses = $this->Course->findAccessibleCoursesListByUserIdRole($this->rdAuth->id, $this->rdAuth->role);
				$this->sysContainer->setMyCourseList($myCourses);

				$this->redirect('/courses/index/The course was added successfully.');
			} else {
				$this->set('data', $this->params['data']);
				$this->set('errmsg', $this->Course->errorMessage);
				$this->render('add');
			}
		}
	}

	function edit($id=null) {
		$this->set('instructor', $this->Course->findInstructors());
		$this->set('allusers', $this->Course->findUsers());
		//$tmp = $this->Course->read();
		$instructorList = $this->UserCourse->getInstructors($id); //$tmp['Course']['id']);
		//Setup the courseId to session
  		$this->rdAuth->setCourseId($id);

		$this->set('instructor_count', count($this->Course->findUniqueInstructors($id)));
		$this->set('instructor_data', $instructorList);

		if (empty($this->params['data']))
		{
			$this->Course->setId($id);
			$this->params['data'] = $this->Course->read();
			$this->set('data', $this->Course->read());
		} else {
            if (!stristr($this->params['data']['Course']['homepage'], "http://")) {
                $this->params['data']['Course']['homepage'] = "http://" . $this->params['data']['Course']['homepage'];
            }
			$this->params = $this->Course->prepData($this->params);
			if ( $this->Course->save($this->params['data'])) {
        $this->UserCourse->insertInstructors($this->Course->id,$this->params['data']['Course']);
				$this->redirect('courses/index/The course was updated successfully.');
			} else	{
				$this->set('data', $this->params['data']);
				$this->set('errmsg', $this->Course->errorMessage);
			}
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
		  // Finished all deletion of course related data

			$this->redirect('/courses/index/The course was deleted successfully.');
		} else {
		  $this->set('errmsg', $this->Course->errorMessage);
		  $this->redirect('/courses/index');
		}
	}

	function deleteInstructor($user_id, $course_id)
	{
		$this->Course->deleteInstructor($user_id, $course_id);

		$this->set('instructor_count', count($this->Course->findUniqueInstructors($course_id)));
		$this->set('instructor', $this->Course->findInstructors());
		$this->set('instructor_data', $this->UserCourse->getInstructors($course_id));

		$this->set('message', 'The instructor was removed from the course.');
		//$this->Course->setId($course_id);
		//$this->params['data'] = $this->Course->read();

		$this->edit($course_id);
		$this->render('edit');

		//$this->redirect('/courses/edit/'.$course_id);
	}

  function adddelrow()
  {
      $this->layout = 'ajax';
  }

  function adddelinstructor()
  {
      $this->layout = 'ajax';
  }

  function allUsers()
  {
  	return $this->Course->findUsers();
  }

  function allInstructors()
  {
  	return $this->Course->findInstructors();
  }

  function uniqueInstructors($course_id) {
  	return $this->Course->findUniqueInstructors($course_id);
  }

  function checkDuplicateName()
  {
      $this->layout = 'ajax';
      $this->render('checkDuplicateName');
  }

	function update($attributeCode='',$attributeValue='')
	{
		$this->layout = false;

		if ($attributeCode != '') {
            $this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode,$attributeValue);
            $this->set('attributeCode',$attributeCode);

            $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
            $this->userPersonalize->setPersonalizeList($personalizeData);

            $this->set('userPersonalize', $this->userPersonalize);
            if ($attributeValue == '') {
                $this->render('update');
            }
		}
	}
}
?>
