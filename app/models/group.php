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
 * @lastmodified $Date: 2006/10/12 15:42:21 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Group
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class Group extends AppModel
{
  var $name = 'Group';

  var $hasMany = array('GroupEvent' => array('className' => 'GroupEvent',
                                             'dependent' => true
                                            ),
                       'GroupsMember' => array('className' => 'GroupsMembers',
                                               'dependent' => true
                                              )
                      );
  var $validate = array('group_num' => VALID_NOT_EMPTY);

	function beforeSave(){ //serverside validation
        // Ensure the name is not empty
        if (empty($this->data[$this->name]['group_name'])) {
            $this->errorMessage = "Please enter a new name for this " . $this->name . ".";
            return false;
        }

        // Remove any single quotes in the name, so that custom SQL queries are not confused.
        $this->data[$this->name]['group_name'] =
            str_replace("'", "", $this->data[$this->name]['group_name']);

		return true;
	}

  function getCourseGroupCount($courseId=null) {
    return $this->find('course_id='.$courseId, 'COUNT(DISTINCT id) as total');
  }

	// gets all students in database
	function findStudents(){
		return $this->findBySql("SELECT users.id, users.role, users.username, users.first_name, users.last_name, users.student_no, users.title FROM users WHERE role='S'");
	}

	// gets all students in a specific group
	function groupStudents($id=null){
		$tmp = $this->findBySql("SELECT * FROM groups_members WHERE group_id=".$id);
		$count = sizeof( $tmp );
		$result = null;

		for( $i=0; $i<$count; $i++ ){
			$tmp2 = $this->findBySql("SELECT users.id, users.role, users.username, users.first_name, users.last_name, users.student_no, users.title FROM users WHERE id=".$tmp[$i]['groups_members']['user_id']." ORDER BY users.last_name ASC");
			if(!empty($tmp2[0])) {
                $result[$i] = $tmp2[0];
            } else {
                $result[$i] = null;
            }

		}

		//print_r($result);
		return $result;
	}

	// finds all students not in a particular course
	function groupDifference($id=null,$courseId=null){
		return $this->findBySql("SELECT DISTINCT users.id, users.role, users.username, users.first_name, users.last_name, users.student_no, users.title
								 FROM users
								 JOIN user_enrols on users.id=user_enrols.user_id
								 WHERE user_enrols.course_id=".$courseId." AND users.role = 'S' AND users.id NOT IN (SELECT user_id as id from groups_members where group_id=".$id.") ORDER BY users.last_name ASC");
	}

	function prepDataImport($lines=null)
	{
	  // Loop through import file
		for ($i = 1; $i < count($lines); $i++) {
			// Split fields up on line by '
			$line = @split(',', $lines[$i]);

			$data['member'.$i] = trim($line[0]);
		}
		$data['member_count'] = count($lines) - 1;
		//$data['data']['Group']['record_status'] = $data['form']['record_status'];

		return $data;
	}

	// parses the group members id from the hidden field assigned
	function prepData($data=null){
		$tmp = $data['form']['assigned'];
		$member_count=0;

		$tok = strtok($tmp, ":");
		while ($tok !== false) {
		   $member_count++;
		   $data['data']['Group']['member'.$member_count] = $tok;
		   $tok = strtok(":");
		}

		$data['data']['Group']['member_count'] = $member_count;
		$data['data']['Group']['record_status'] = $data['form']['record_status'];

		return $data;
	}


    function findGroup($courseId, $groupNumber, $groupName) {
        $data = $this->find
            ("course_id=$courseId AND group_num=$groupNumber and group_name='$groupName'");
        if (is_array($data)) {
            return $data['Group']['id'];
        } else {
            return false;
        }
    }

	function getLastGroupNumByCourseId($courseId=null) {
        $tmp = $this->find('course_id='.$courseId, 'max(group_num)');
        $maxGroupNum = $tmp[0]['max(group_num)'];
        if (empty($maxGroupNum)) {
            $maxGroupNum = 0;
        }
        return $maxGroupNum;
	}

  function getFilteredStudents($group_id, $filter){
    $course_id = $this->field('course_id',sprintf("id = '%d'", $group_id));
    if($filter)
    {
      return $this->findBySql("SELECT DISTINCT users.id, users.role, users.username, users.first_name, users.last_name, users.student_no, users.title
                              FROM users
                              JOIN user_enrols on users.id=user_enrols.user_id
                              WHERE user_enrols.course_id=".$course_id." AND users.role = 'S' AND users.id NOT IN
                              (SELECT user_id FROM `groups` LEFT JOIN groups_members as gs ON groups.id = gs.group_id WHERE groups.course_id = ".$course_id.")
                              ORDER BY users.last_name ASC");
    } else {
      return $this->groupDifference($group_id,$course_id);
    }
  }

    function countUserSubmissionsInAGroup($user_id, $group_id) {
        $data = $this->findBySql("
            SELECT count(*) AS count FROM users AS User
            JOIN evaluation_submissions AS EvaluationSubmission ON User.id=submitter_id
            JOIN group_events AS GroupEvent ON GroupEvent.id=EvaluationSubmission.grp_event_id
            WHERE User.id=$user_id AND group_id=$group_id");

        return $data[0][0]['count'];
    }
}

?>
