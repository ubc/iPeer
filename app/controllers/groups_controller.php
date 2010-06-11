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
 * @lastmodified $Date: 2006/10/11 17:27:15 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controller :: Groups
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class GroupsController extends AppController
{
  var $name = 'Groups';
	var $uses =  array('Group','GroupsMembers', 'User','Personalize','GroupEvent');
  var $show;
	var $sortBy;
	var $direction;
	var $page;
	var $order;
	var $helpers = array('Html','Ajax','Javascript','Time','Pagination');
	var $Sanitize;

	function __construct()
	{
		$this->Sanitize = new Sanitize;
		$this->show = empty($_GET['show'])? 'null': $this->Sanitize->paranoid($_GET['show']);
		if ($this->show == 'all') $this->show = 99999999;
		$this->sortBy = empty($_GET['sort'])? 'created': $_GET['sort'];
		$this->direction = empty($_GET['direction'])? 'desc': $this->Sanitize->paranoid($_GET['direction']);
		$this->page = empty($_GET['page'])? '1': $this->Sanitize->paranoid($_GET['page']);
		$this->order = $this->sortBy.' '.strtoupper($this->direction);
 		$this->pageTitle = 'Groups';
		parent::__construct();
	}

  function index($msg='') {	
    $courseId = $this->rdAuth->courseId;
   	$this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Groups';

		$queryAttributes = $this->getQueryAttribute($courseId);
		$fields = $queryAttributes['fields'];
		$condition = $queryAttributes['condition'];
		$joinTable = $queryAttributes['joinTable'];

		$personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
    $this->userPersonalize->setPersonalizeList($personalizeData);
  	if ($personalizeData && $this->userPersonalize->inPersonalizeList('Group.ListMenu.Limit.Show')) {
       $this->show = $this->userPersonalize->getPersonalizeValue('Group.ListMenu.Limit.Show');
       $this->set('userPersonalize', $this->userPersonalize);
  	} else {
  	  $this->show = '10';
      $this->update($attributeCode = 'Group.ListMenu.Limit.Show',$attributeValue = $this->show);
  	}

		$data = $this->Group->findAll($condition, $fields, $this->order, $this->show, $this->page, null, $joinTable);
		
		for ($i=0; $i < count($data); $i++) 
		{
		  $data[$i]['Group']['member_count'] = $data[$i][$i]['mc'];
		}

		$paging['style'] = 'ajax';
		$paging['link'] = '/groups/search/?show='.$this->show.'&sort='.$this->sortBy.'&direction='.$this->direction.'&page=';

		$paging['count'] = $this->Group->findCount($condition, 0, $joinTable);
		$paging['show'] = array('10','25','50','all');
		$paging['page'] = $this->page;
		$paging['limit'] = $this->show;
		$paging['direction'] = $this->direction;

		$this->set('paging',$paging);
		$this->set('data',$data);
		$this->set('message', $msg);
	}

  function view ($id)
  {
		$this->set('group_data', $this->Group->groupStudents($id));
		$this->set('data', $this->Group->read());
  }

  function add ()
  {
    $courseId = $this->rdAuth->courseId;
   	$this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Groups';


    // gets all the students in db for the unfiltered students list
		$this->set('user_data', $this->User->getEnrolledStudents($courseId));

		if (empty($this->params['data']))
		{
			$this->render('add');
		}
		else
		{
		  $this->params['data']['Group']['course_id']=$courseId;
			$this->params = $this->Group->prepData($this->params);

			if ($this->Group->save($this->params['data']))
			{
				// add members into the groups_members table
				$this->GroupsMembers->insertMembers($this->Group->id, $this->params['data']['Group']);

				$this->redirect('/groups/index/The groups were added successfully.');

			}
			else
			{
				$this->set('data', $this->params['data']);
				$this->render('edit');
			}
		}
  }

  function edit ($id=null)
  {
    $courseId = $this->rdAuth->courseId;
   	$this->pageTitle = $this->sysContainer->getCourseName($courseId).' > Groups';

		// gets all students not listed in the group for unfiltered box
		$this->set('user_data', $this->Group->groupDifference($id,$courseId));

		// gets all students in the group
		$this->set('group_data', $this->Group->groupStudents($id));

		if (empty($this->params['data']))
		{
			$this->Group->setId($id);
			$this->params['data'] = $this->Group->read();
			$this->render('edit');
		}
		else
		{
			$this->params = $this->Group->prepData($this->params);

			if ( $this->Group->save($this->params['data']))
			{
				$this->GroupsMembers->updateMembers($this->Group->id, $this->params['data']['Group']);

				$this->redirect('/groups/index/The groups were updated successfully.');
			}
			else
			{
				$this->set('data', $this->params['data']);
				$this->render();
			}
		}
  }

  function delete ($id)
  {
    if ($this->Group->del($id)) {
      $groupEvents = $this->GroupEvent->findAll('group_id='.$id);
      $groupMembers = $this->GroupsMembers->findAll('group_id='.$id);
      foreach ($groupEvents as $groupEvent) {
        $this->GroupEvent->del($groupEvent['GroupEvent']['id']);
      }
      foreach ($groupMembers as $groupMember) {
        $this->GroupsMembers->del($groupMember['GroupsMembers']['id']);
      }
			$this->set('data', $this->Group->findAll(null, null, 'id'));
			$this->redirect('/groups/index/The group was deleted successfully.');
		} else {
		  $this->set('data', $this->Group->findAll(null, null, 'id'));
			$this->redirect('/groups/index/Group delete failed.');
		}
  }

	function search()
  {
    $this->layout = 'ajax';

    if ($this->show == 'null') { //check for initial page load, if true, load record limit from db
      $personalizeData = $this->Personalize->findAll('user_id = '.$this->rdAuth->id);
      if ($personalizeData) {
        $this->userPersonalize->setPersonalizeList($personalizeData);
        $this->show = $this->userPersonalize->getPersonalizeValue('Group.ListMenu.Limit.Show');
        $this->set('userPersonalize', $this->userPersonalize);
      }
    }

    $condition = "";
    $courseId = isset($this->params['form']['course_id'])? $this->params['form']['course_id']: $this->rdAuth->courseId;;
    $this->set('courseId', $courseId);

    $queryAttributes = $this->getQueryAttribute($courseId);
    $fields = $queryAttributes['fields'];
		$condition = $queryAttributes['condition'];
		$joinTable = $queryAttributes['joinTable'];
    
    if ('' != $this->params['form']['livesearch2'] && !empty($this->params['form']['select']))
    {
      $pagination->loadingId = 'loading';
      //parse the parameters
      $searchField=$this->params['form']['select'];
      $searchValue=$this->params['form']['livesearch2'];

      if (!empty($condition))
      {
        $condition .= " AND ";
      }
      $condition .= $searchField." LIKE '%".mysql_real_escape_string($searchValue)."%'";
    }
    $this->update($attributeCode = 'Group.ListMenu.Limit.Show',$attributeValue = $this->show);
    $this->set('conditions',$condition);
    $this->set('fields',$fields);
    $this->set('joinTable',$joinTable);
  }

  function checkDuplicateName()
  {
      $this->layout = 'ajax';
      $this->set('course_id', $this->rdAuth->courseId);
      $this->render('checkDuplicateName');
  }

  function getQueryAttribute($courseId = null)
  {	
    $attributes = array('fields'=>'', 'condition'=>'', 'joinTable'=>array());
    $attributes['fields'] = 'DISTINCT Group.id, Group.group_num, Group.group_name, Group.course_id, Group.created, Group.creator_id, Group.modified, Group.updater_id, count(GroupsMembers.group_id) AS mc';
	$joinTable = array('INNER JOIN groups_members AS GroupsMembers ON Group.id = GroupsMembers.group_id');
	 
    if (!empty($courseId)) {
      $attributes['condition'] .= ' Group.course_id = '.$courseId;
  	}
  	$attributes['joinTable']=$joinTable;

  	return $attributes;
  }

  function import() {
    $this->autoRender = false;
    $courseId = $this->params['form']['course_id'];
    $this->params['data']['Group']['course_id'] = $courseId;
		$filename = $this->params['form']['file']['name'];
		$tmpFile = $this->params['form']['file']['tmp_name'];

		//$uploadDir = $this->sysContainer->getParamByParamCode('system.upload_dir');
		$uploadDir="/var/www/ipeer.apsc.ubc.ca/htdocs/prod/app/uploads/";
		//$uploadFile = APP.$uploadDir['parameter_value'] . $filename;
		$uploadFile=$uploadDir.$filename;

		//check for blank filename
		if (trim($filename) == "") {
			$this->set('errmsg','File required.');
			$this->render('add');
			return false;
		}
	  //Return true if valid, else error msg
    $validUploads = $this->framework->validateUploadFile($tmpFile, $filename, $uploadFile);
		if ($validUploads) {
			// Get file into an array.
			$lines = file($uploadFile);
			// Delete the uploaded file
			unlink($uploadFile);

			//Mess create students
			$resultAry = $this->addGroupByImport($this->params['data'], $lines);
  		$this->set('data', $resultAry);

  		$this->redirect('/groups/index/The groups were added successfully.');
		}
		else {
		  $this->set('errmsg', $$validUploads);
		  $this->render('add');
		}
	}

  function addGroupByImport($data=null, $lines=null)
	{
	  $groupNo = '';
		for ($i = 1; $i < count($lines); $i++) {
			// Split fields up on line by '
			$line = split(',', $lines[$i]);

  		$data['Group']['id'] = null;
  		//$data['Group']['student_no'] = trim($line[0]);
$data['Group']['username'] = trim($line[0]);
  		$data['Group']['group_num'] = trim($line[1]);
  		$data['Group']['group_name'] = trim($line[2]);
      $data['Group']['creator_id'] = $this->rdAuth->id;
			if ($groupNo != $data['Group']['group_num']) {
			  $this->Group->save($data);
			}

			// add members into the groups_members table
			$groupMember['GroupsMembers']['group_id'] = $this->Group->id;
			//$user = $this->User->find('student_no = '.$data['Group']['student_num']);
$user = $this->User->find('username = '.$data['Group']['username']);
			$groupMember['GroupsMembers']['user_id'] = $user['User']['id'];
			$this->GroupsMembers->save($groupMember);
			$this->GroupsMembers->id = null;

			$groupNo = $data['Group']['group_num'];
    }
	}

	function update($attributeCode='',$attributeValue='') {
		if ($attributeCode != '' && $attributeValue != '') //check for empty params
		{
			$this->params['data'] = $this->Personalize->updateAttribute($this->rdAuth->id, $attributeCode, $attributeValue);
		}
	}
}

?>
