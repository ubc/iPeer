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
    public $displayField = 'group_name';

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
    public $validate = array(
        'group_name' => array(
            'empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please insert group name'
            ),
            'duplicate' => array(
                'rule' => 'checkDuplicate',
                'message' => 'A group with the name already exists.'
            )
        )
    );

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
    function beforeSave($options = array())
    { //serverside validation
        // Ensure the name is not empty
        if (empty($this->data[$this->name]['group_name'])) {
            $this->errorMessage = "Please enter a new name for this " . $this->name . ".";
            return false;
        }

        // Remove any single quotes in the name, so that custom SQL queries are not confused.
        $this->data[$this->name]['group_name'] =
            str_replace("'", "", $this->data[$this->name]['group_name']);

        // generate a group number if empty
        if (!isset($this->data[$this->name]['group_num']) || empty($this->data[$this->name]['group_num'])) {
            $max_num = $this->find('first', array(
                'conditions' => array('course_id' => $this->data[$this->name]['course_id']),
                'fields' => array('MAX(group_num) as max_num')));
            $this->data[$this->name]['group_num'] = $max_num[0]['max_num'] + 1;
        }

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
     * Find lowest missing group number in a course
     *
     * @param int $courseId course id
     *
     * @return int n
     */
    function getFirstAvailGroupNum($courseId=null)
    {
        $temp = $this->find('all', array('conditions' => array('course_id' => $courseId)));

        // if at least one group exist in the course
        if (!empty($temp)) {
            $i = 1;
            foreach ($temp as $data) {
                $groupNumAry[] = $data['Group']['group_num'];
                $i++;
            }

            $compare = range(1, max($groupNumAry));
            $avail = array_diff($compare, $groupNumAry);
            sort($avail);
            if (empty($avail)) {
                $avail['0'] = $i;
            }
        // if no groups exist in the course - group num starts with 1
        } else {
            $avail['0'] = 1;
        }

        return $avail['0'];
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
     * findGroupByGroupName Get group by group name
     *
     * @param mixed $course_id
     * @param mixed $group_name
     *
     * @access public
     *
     * @return array of the group
     */
    function findGroupByGroupName($course_id, $group_name)
    {
        return $this->find('first', array('conditions' => array('group_name' => $group_name, 'course_id' => $course_id)));
    }

    /**
     * Get students and tutors not in a group
     *
     * @param int    $group_id group id
     * @param string $type     type of user
     *
     * @return array students and tutors not in the group
     */
    function getStudentsNotInGroup($group_id, $type = 'list')
    {
        // $people includes id of both students and tutors/TAs in group
        $people = $this->getMembersByGroupId($group_id, 'all');
        $people = Set::extract('/Member/id', $people);
        $peopleInGroups = array();

        $course = $this->Course->getCourseByGroupId($group_id);
        if (empty($course)) {
            return array();
        }

        $peopleListinGroups = $this->Member->find($type, array(
            'conditions' => array(
                'NOT' => array('Member.id' => $people),
                'Group.course_id' => $course['Course']['id']
            ),
            'recursive' => 1,
            'fields' => array('Member.student_no_with_full_name'),
            'contain' => array('Group'),
            'order' => 'Member.student_no'
        ));

        foreach ($peopleListinGroups as $key => $student) {
            $peopleListinGroups[$key] = $student.' *';
            $peopleInGroups[] = $key;
        }

        $excludedPeople = array_merge($people, $peopleInGroups);

        $studentsListNotinGroups = $this->Member->find($type, array(
            'conditions' => array(
                'NOT' => array('Member.id' => $excludedPeople),
                'Enrolment.id' => $course['Course']['id'],
            ),
            'recursive' => 1,
            'fields' => array('Member.student_no_with_full_name'),
            'order' => 'Member.student_no'
        ));

        $tutorsListNotinGroups = $this->Member->find($type, array(
            'conditions' => array(
                'NOT' => array('Member.id' => $excludedPeople),
                'Tutor.id' => $course['Course']['id'],
                'Role.id' => 4
            ),
            'recursive' => 1,
            'fields' => array('Member.full_name'),
            'order' => 'Member.last_name'
        ));

        $peopleList = Set::pushDiff($studentsListNotinGroups, $peopleListinGroups);
        $sorted_list = Set::pushDiff($tutorsListNotinGroups, $peopleList);

        return $sorted_list;
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
        $students = $this->Member->find($type, array(
            'conditions' => array('Group.id' => $group_id),
            'recursive' => 1,
            'fields' => array('Member.student_no_with_full_name'),
            'contain' => 'Group')
        );

        return $students;
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
    function getGroupsByCourseId($course_id)
    {
        return $this->find('list', array(
            'conditions' => array('Group.course_id' => $course_id)
        ));
    }

    /**
     * findGroupByid Get group by group id
     *
     * @param mixed $id     group id
     * @param bool  $params search parameters
     *
     * @access public
     * @return array with user data
     * */
    function findGroupByid ($id, $params = array())
    {
        if (null == $id) {
            return null;
        }

        return $this->find(
            'first',
            array_merge(array('conditions' => array($this->name.'.id' => $id,)), $params)
        );
    }

    /**
     * getGroupWithMembersById
     *
     * @param mixed $id id
     * 
     * @access public
     * @return void
     */
    function getGroupWithMembersById($id)
    {
        return $this->find('first', array(
            'conditions' => array($this->alias.'.id' => $id),
            'contain' => 'Member',
        ));
    }

    /**
     * getGroupsByEventId
     *
     * @param mixed $eventId event id
     * @param mixed $contain contain
     * 
     * @access public
     * @return void
     */
    function getGroupsByEventId($eventId, $contain = array())
    {
        return $this->find('all', array(
            'conditions' => array('Event.id' => $eventId),
            'contain' => array_merge(array('Event'), $contain),
        ));
    }

    /**
     * getGroupByGroupIdEventId
     *
     * @param mixed $groupId group id
     * @param mixed $eventId event id
     * 
     * @access public
     * @return void
     */
    function getGroupByGroupIdEventId($groupId, $eventId)
    {
        return $this->getGroupByGroupIdEventIdMemberId($groupId, $eventId, null);
    }

    /**
     * getGroupWithMemberRoleByGroupIdEventId
     *
     * @param mixed $groupId group id
     * @param mixed $eventId event id
     * 
     * @access public
     * @return void
     */
    function getGroupWithMemberRoleByGroupIdEventId($groupId, $eventId)
    {
        $group = $this->getGroupByGroupIdEventIdMemberId($groupId, $eventId, null);
        $roles = $this->Member->Role->find('all', array(
            'fields' => array('Role.*', 'User.id'),
            'conditions' => array('User.id' => Set::extract($group['Member'], '/id')),
        ));

        foreach($roles as $role) {
            foreach($group['Member'] as $key => $member) {
                if ($role['User']['id'] == $member['id']) {
                    $group['Member'][$key]['Role'] = $role['Role'];
                }
            }
        }

        return $group;
    }

    /**
     * getGroupByGroupIdEventIdMemberId get the group by id. If the group is not in the event,
     * return false
     *
     * @param mixed $groupId  group id
     * @param mixed $eventId  event id
     * @param mixed $memberId member id
     *
     * @access public
     * @return void
     */
    function getGroupByGroupIdEventIdMemberId($groupId, $eventId, $memberId)
    {
        $conditions = array(
            $this->alias.'.id' => $groupId,
            'GroupEvent.event_id' => $eventId,
        );
        if ($memberId != null) {
            $conditions['Member.id'] = $memberId;
        }
        $group = $this->find('first', array(
            'fields' => array('Group.*'),
            'conditions' => $conditions,
        ));

        // hack to find the GroupEvent
        if (isset($group['GroupEvent'])) {
            $correct = array();
            foreach ($group['GroupEvent'] as $groupEvent) {
                if ($groupEvent['group_id'] == $groupId && $groupEvent['event_id'] == $eventId) {
                    $correct = $groupEvent;
                }
            }
            $group['GroupEvent'] = $correct;
        }

        return $group;
    }
    
    /**
     * checkDuplicate checks for duplicate group name in the course
     *
     * @access public
     * @return void
     */
    function checkDuplicate()
    {
        $conditions['Group.course_id'] = $this->data['Group']['course_id'];
        // edit groups have group id & doesn't make sense for them not being able to keep their name
        if (isset($this->data['Group']['id'])) {
            $conditions['Group.id !='] = $this->data['Group']['id'];
        }
        $names = Set::extract('/Group/group_name', $this->find('all', array('conditions' => $conditions)));;
        return !in_array($this->data['Group']['group_name'], $names);
    }
}
