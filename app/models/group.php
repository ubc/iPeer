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

  var $schema = array('id' => array('type' => 'integer',
                                     'null' => false,
                                     'default' => null,
                                     'length' => 11,
                                     'key' => 'primary'),
                       'group_num' => array('type' => 'integer',
                                     'null' => false,
                                     'default' => 0,
                                     'length' => 4),
                       'group_name' => array('type' => 'string',
                                     'null' => true,
                                     'default' => null,
                                     'length' => 80,
                                     'collate' => 'latin1_swedish_ci',
                                     'charset' => 'latin1'),
                       'course_id' => array('type' => 'integer',
                                     'null' => true,
                                     'default' => null,
                                     'length' => 11),
                       'record_status' => array('type' => 'enum("A", "I")',
                                     'null' => false,
                                     'default' => 'A',
                                     'length' => 1,
                                     'collate' => 'latin1_swedish_ci',
                                     'charset' => 'latin1'),
                       'creator_id' => array('type' => 'integer',
                                     'null' => false,
                                     'default' => 0,
                                     'length' => 11),
                       'updater_id' => array('type' => 'integer',
                                     'null' => true,
                                     'default' => null,
                                     'length' => 11),
                       'created' => array('type' => 'datetime',
                                     'null' => false,
                                     'default' => '0000-00-00 00:00:00',
                                     'length' => null),
                       'modified' => array('type' => 'datetime',
                                     'null' => true,
                                     'default' => null,
                                     'length' => null),
                       );

  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');

  var $belongsTo = array('Course' => array('className' => 'Course',
                                           'foreignKey' => 'course_id'));

  var $hasAndBelongsToMany = array('Member' =>
                                   array('className'    =>  'User',
                                         'joinTable'    =>  'groups_members',
                                         'foreignKey'   =>  'group_id',
                                         'associationForeignKey'    =>  'user_id',
                                         'conditions'   =>  '',
                                         'order'        =>  '',
                                         'limit'        => '',
                                         'unique'       => true,
                                         'finderQuery'  => '',
                                         'deleteQuery'  => '',
                                         'dependent'    => false,
                                         ),
                                   'Event' =>
                                   array('className'    =>  'Event',
                                         'joinTable'    =>  'group_events',
                                         'foreignKey'   =>  'group_id',
                                         'associationForeignKey'    =>  'event_id',
                                         'conditions'   =>  '',
                                         'order'        =>  '',
                                         'limit'        => '',
                                         'unique'       => true,
                                         'finderQuery'  => '',
                                         'deleteQuery'  => '',
                                         'dependent'    => false,
                                        ),);
  var $validate = array('group_num' => 'notEmpty');

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);

    $this->virtualFields['member_count'] = sprintf('SELECT count(*) as count FROM groups_members as gm WHERE gm.group_id = %s.id', $this->alias);
  }

  function schema($field = false) {
    $this->_schema = $this->schema;
    return $this->schema;
  }

  function beforeSave(){ //serverside validation
    // Ensure the name is not empty
    if (empty($this->data[$this->name]['group_name'])) {
      $this->errorMessage = "Please enter a new name for this " . $this->name . ".";
      return false;
    }

    // Remove any single quotes in the name, so that custom SQL queries are not confused.
    $this->data[$this->name]['group_name'] =
      str_replace("'", "", $this->data[$this->name]['group_name']);

    return parent::beforeSave();
  }

  /**
   * Count groups in a course
   * 
   *  @param int $courseId course id
   *  
   *  @return int count of courses
   * */
  
  function getCourseGroupCount($courseId=null) {
      return $this->find('count', array(
          'conditions' => array('course_id' => $courseId)
      ));
  }

	/**
	 * 
	 * Prepares group member data in an array
	 *
	 * @param  $lines members
	 * 
	 * @return $data data in an array
	 */
  
  function prepDataImport($lines=null)
	{
	  // Loop through import file
		for ($i = 1; $i < count($lines); $i++) {
			// Split fields up on line by '
			$line = @split(',', $lines[$i]);

			$data['member'.$i] = trim($line[0]);
		}
		$data['member_count'] = count($lines) - 1;

		return $data;
	}
	
	/**
	 * Gets last group number in a course 
	 * @param $course_id course id
	 * @return $maxGroupNumber  last group number
	 */

  function getLastGroupNumByCourseId($course_id) {
    $tmp = $this->find('first', array('conditions' => array('course_id' => $course_id), 
                                      'fields' => array('max(group_num)'),
                                      'contain' => false));
    $maxGroupNum = $tmp[0]['max(group_num)'];
    if (empty($maxGroupNum)) {
      $maxGroupNum = 0;
    }
    return $maxGroupNum;
  }

  /**
   * Get students not in a group 
   * @param $group_id group id
   * @param $type type of user
   * @return array students not in the group
   */
  function getStudentsNotInGroup($group_id, $type = 'all') {
    return $this->Member->getStudentsNotInGroup($group_id, $type);
  }

  /**
   * Get members in a group by id 
   * @param $group_id group id
   * @param $type type of user
   * @return Array of students in the group
   */  
  
  function getMembersByGroupId($group_id, $type = 'all') {
    return $this->Member->getMembersByGroupId($group_id, $type);
  }

  /**
   * 
   * Get group by group Id
   * @param $groupId group id
   * @param $fields fields to return
   * @return Group array
   */
  function getGroupByGroupId($groupId, $fields=null){
  	return $this->find('all',array('conditions'=>array('Group.id'=>$groupId), 'fields' => $fields));
  }

  /**
   * 
   * Get groups in a course
   * @param $course_id course id
   * @return groups in a course
   */
  
  function getGroupsByCouseId($course_id){
    return $this->find('list', array(
        'conditions' => array('Group.course_id' => $course_id)
    ));    
  }
  
  function getGroupById($groupId=null){
  	return $this->find('first', array('conditions' => array('Group.id' => $groupId)));
  }
}
?>
