<?php
/**
 * Group
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Group extends AppModel
{
    public $name = 'Group';

/*    public $schema = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'group_num' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 4),
        'group_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 80, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'course_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'record_status' => array('type' => 'string', 'null' => false, 'default' => 'A', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'creator_id' => array('type' => 'integer', 'null' => false, 'default' => 0),
        'updater_id' => array('type' => 'integer', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
    );*/

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');

    public $belongsTo = array(
        'Course' => array(
            'className' => 'Course',
            'foreignKey' => 'course_id'
        )
    );

    public $hasMany = array(
        'GroupEvent' =>
        array(
            'className' => 'GroupEvent',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'group_id'
        ),
    );

    public $hasAndBelongsToMany = array(
        'Member' => array(
            'className'    =>  'User',
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
        'Event' => array(
            'className'    =>  'Event',
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
        ),
    );
    public $validate = array('group_num' => 'notEmpty');

    /**
     * __construct
     *
     * @param bool $id    id
     * @param bool $table table
     * @param bool $ds    data source
     *
     * @access protected
     * @return void
     */
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);

        $this->virtualFields['member_count'] = sprintf('SELECT count(*) as count FROM groups_members as gm WHERE gm.group_id = %s.id', $this->alias);
    }

    /**
     * schema
     * NOTE: forgot why this function is here, causing trouble in unit test
     *
     * @param bool $field field
     *
     * @access public
     * @return void
     */
    /*function schema($field = false)
    {
        $this->_schema = $this->schema;
        return $this->schema;
    }*/

    /**
     * beforeSave
     *
     * @access public
     * @return void
     */
    function beforeSave()
    { //serverside validation
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
     * @param int $courseId course id
     *
     * @return int count of courses
     */
    function getCourseGroupCount($courseId=null)
    {
        return $this->find('count', array(
            'conditions' => array('course_id' => $courseId)
        ));
    }

    /**
     * Prepares group member data in an array
     *
     * @param mixed $lines members
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
     *
     * @param int $course_id course id
     *
     * @return $maxGroupNumber  last group number
     */
    function getLastGroupNumByCourseId($course_id)
    {
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
     *
     * @param int    $group_id group id
     * @param string $type     type of user
     *
     * @return array students not in the group
     */
    function getStudentsNotInGroup($group_id, $type = 'all')
    {
        $students = $this->getMembersByGroupId($group_id, 'all');
        $students = Set::extract('/Member/id', $students);

        $course = $this->Course->getCourseByGroupId($group_id);
        if (empty($course)) {
            return array();
        }

        return $this->Member->find($type, array(
            'conditions' => array(
                'NOT' => array('Member.id' => $students),
                'Enrolment.id' => $course['Course']['id'],
            ),
            'contain' => array('Enrolment'),
        ));
    }

    /**
     * Get members in a group by id
     *
     * @param int    $group_id group id
     * @param string $type     type of user
     *
     * @return Array of students in the group
     */
    function getMembersByGroupId($group_id, $type = 'all')
    {
        return $this->Member->find($type, array(
            'conditions' => array('Group.id' => $group_id),
            'contain' => 'Group')
        );
    }

    /**
     * Get group by group Id
     *
     * @param int   $groupId group id
     * @param array $fields  fields to return
     *
     * @return Group array
     */
    function getGroupByGroupId($groupId, $fields = null)
    {
        return $this->find('all', array('conditions' => array('Group.id' => $groupId), 'fields' => $fields));
    }

    /**
     * Get groups in a course
     *
     * @param int $course_id course id
     *
     * @return groups in a course
     */
    function getGroupsByCouseId($course_id)
    {
        return $this->find('list', array(
            'conditions' => array('Group.course_id' => $course_id)
        ));
    }
}
