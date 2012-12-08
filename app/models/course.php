<?php
/**
 * Course
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Course extends AppModel
{
    const FILTER_PERMISSION_SUPERADMIN = 0;
    const FILTER_PERMISSION_FACULTY = 1;
    const FILTER_PERMISSION_OWNER = 2;

    public $name = 'Course';
    public $displayField = 'full_name';

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable', 'Traceable');

    public $hasMany = array(
        'Group' => array(
            'className'   => 'Group',
            'conditions'  => array('Group.record_status = "A"'),
            'order'       => 'Group.created DESC',
            'foreignKey'  => 'course_id',
            'dependent'   => true,
            'exclusive'   => false,
            'finderSql'   => ''
        ),
        'Event' => array(
            'className'   => 'Event',
            'conditions'  => 'Event.record_status = "A"',
            'order'       => 'Event.created DESC',
            'foreignKey'  => 'course_id',
            'dependent'   => true,
            'exclusive'   => false,
            'finderSql'   => ''
        ),
        'Survey' => array(
            'className'   => 'Survey',
            'conditions'  => '',
            'order'       => '',
            'foreignKey'  => 'course_id',
            'dependent'   => true,
            'exclusive'   => false,
            'finderSql'   => ''
        )
    );

    public $hasAndBelongsToMany = array(
        'Instructor' => array(
            'className'    =>  'User',
            'joinTable'    =>  'user_courses',
            'foreignKey'   =>  'course_id',
            'associationForeignKey'    =>  'user_id',
            'conditions'   =>  '',
            'order'        =>  '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => false,
        ),
        'Tutor' => array(
            'className'    =>  'User',
            'joinTable'    =>  'user_tutors',
            'foreignKey'   =>  'course_id',
            'associationForeignKey'    =>  'user_id',
            'conditions'   =>  '',
            'order'        =>  '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => false,
        ),
        'Enrol' => array(
            'className'    =>  'User',
            'joinTable'    =>  'user_enrols',
            'foreignKey'   =>  'course_id',
            'associationForeignKey'    =>  'user_id',
            'conditions'   =>  /*'Enrol.role = "S"'*/'',
            'order'        =>  '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => false,
        ),
        'Department' => array(
            'joinTable' => 'course_departments'
        ),
    );

    /* Record Status - Active, Inactive */
    const STATUS_ACTIVE = 'A';
    const STATUS_INACTIVE = 'I';
    public $STATUS = array(
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive'
    );

    public $validate = array (
        'course' => array (
            'courseRule1' => array(
                'rule' => 'isUnique',
                'message' => 'A course with this name already exists',
            ),
            'courseRule2' => array(
                'rule' => 'notEmpty',
                'message' => 'The course name is required.'
            )
        )
    );

    /**
     * For some reason, HABTM fields can't be validated with $validate.
     * So this is a workaround, make sure courses have at least 1
     * department selected.
     * */
    public function beforeValidate() {
        if (array_key_exists('Department', $this->data) &&
            empty($this->data['Department']['Department'])) {
            // make sure this model fails when saving without department
            $this->invalidate('Department');
            // make the error message appear in the right place
            $this->Department->invalidate('Department',
                'Please select a department.');
        }
    }

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
        $this->virtualFields['student_count'] = sprintf('SELECT count(*) as count FROM user_enrols as enrol WHERE enrol.course_id = %s.id', $this->alias);
        $this->virtualFields['full_name'] = sprintf('CONCAT(%s.course, " - ", %s.title)', $this->alias, $this->alias);

    }

    /**
     * Delete instructor from a course
     *
     * @param unknown_type $course_id course id
     * @param unknown_type $user_id   user id
     *
     * @access public
     * @return void
     */
    function deleteInstructor($course_id, $user_id)
    {
        $this->habtmDelete('Instructor', $course_id, $user_id);
    }

    /**
     * Add instructor to a course
     *
     * @param unknown_type $course_id course id
     * @param unknown_type $user_id   user i/
     *
     * @access public
     * @return void
     */
    function addInstructor($course_id, $user_id)
    {
        $this->habtmAdd('Instructor', $course_id, $user_id);
    }

    /**
     * Get inactive courses
     *
     * @return list of inactive courses
     */
    function getInactiveCourses()
    {
        return $this->find('all', array('conditions' => array('record_status' => 'I')));
    }

    /**
     * prepData
     *
     * @param bool $data data
     *
     * @access public
     * @return void
     */
    function prepData($data=null)
    {
        if (empty($data['data']['Course']['record_status'])) {
            $data['data']['Course']['record_status'] = $data['form']['record_status'];
        }

        if (!empty($data['form']['self_enroll'])) {
            $data['data']['Course']['self_enroll'] = "on";
        } else {
            $data['data']['Course']['self_enroll'] = "off";
        }

        for ($i=1; $i<=$data['data']['Course']['count']; $i++) {
            $data['data']['Course']['instructor_id'.$i] = isset($data['form']['instructor_id'.$i])? $data['form']['instructor_id'.$i] : '';
        }

        return $data;
    }

    /**
     * Get course name by id
     *
     * @param unknown_type $id course id
     *
     * @return course name
     */
    function getCourseName($id)
    {
        $tmp = $this->read(null, $id);
        return $tmp['Course']['course'];
    }

    /**
     * Delete course and all related items
     *
     * @param int $id id
     *
     * @access public
     * @return void
     */
    function deleteAll($id = null)
    {
        //delete self
        if ($this->delete($id)) {
            //delete user course,user enrol handled by hasMany
            //$events = $this->Event->find('all', array('conditions' => array('course_id' => $id)));
            $this->Event->deleteAll(array('course_id' => $id));
        }
    }

    /**
     * Get course data by course name
     *
     * @param unknown_type $course course name
     * @param unknown_type $params search params
     *
     * @return course data
     */
    function getCourseByCourse($course, $params =null)
    {
        return $this->find('all', array_merge(array('conditions' => array('course' => $course)), $params));
    }

    /**
     * Get course data by instructor id
     *
     * @param mixed $instructorId instructor id
     * @param bool  $type         type
     * @param int   $contain      contained models
     * @param array $conditions   conditions for find
     *
     * @return course data
     */
    function getCourseByInstructor($instructorId, $type = 'all', $contain = array(), $conditions = array())
    {
        $contain = array_merge(array('Instructor'), $contain);
        /*if ($type == 'list') {
            $fields = array('Course.full_name');
        }*/

        // we need two queries to find the courses. becuase if we specifiy the instructor id condition
        // we can only get one instructor with the id we specified. If the course has more than one
        // instructor, we will fail to retrieve them.

        // find course ids first
        $courses = $this->find(
            'all',
            array(
                'conditions' => array('Instructor.id' => $instructorId),
                'contain' => array('Instructor')
            )
        );

        $courseIds = Set::extract('/Course/id', $courses);
        if (array_key_exists('id', $conditions)) {
            if (is_array($conditions['id'])) {
                $conditions['id'] = array_intersect($conditions['id'], $courseIds);
            } else {
                if (!in_array($conditions['id'], $courseIds)) {
                    return false;
                }
            }
        } else {
            $conditions['id'] = $courseIds;
        }
        // find courses with instructor and other models specified in contain
        $courses = $this->find($type, array('conditions' => $conditions, 'contain' => $contain));

        return $courses;
    }

    /**
     * Get course data by instructor id
     *
     * @param unknown_type $instructorId instructor id
     *
     * @return course data
     */
    function getListByInstructor($instructorId)
    {
        return $this->getCourseByInstructor($instructorId, 'list');
    }

    /**
     * enrolStudents enrol student to a course
     *
     * @param mixed $ids      id array of the students
     * @param mixed $courseId the course the students are enrolled into. If null,
     * read the current id in the course object
     *
     * @access public
     * @return boolean true for success, false for failed.
     */
    function enrolStudents($ids, $courseId = null)
    {
        if (null == $courseId) {
            $courseId = $this->id;
        }

        if (null == $courseId) {
            return false;
        }

        return $this->habtmAdd('Enrol', $courseId, $ids);
    }

    /**
     * Get course data by departments
     *
     * @param array  $departments array of departments
     * @param string $findType    find type
     *
     * @return course data
     */
    function getByDepartments($departments, $findType)
    {
        $this->CourseDepartment = Classregistry::init('CourseDepartment');

        $courses = array();

        foreach ($departments as $department) {
            $dp_id = $department['Department']['id'];
            $cd = $this->CourseDepartment->find('all', array('conditions' => array('department_id' => $dp_id)));
            foreach ($cd as $course) {
                array_push($courses, $course['CourseDepartment']['course_id']);
            }
        }
        $ret = $this->find($findType, array('conditions' => array('Course.id' => $courses)));

        return $ret;
    }

    /**
     * getByDepartmentIds get course belongs to departments
     *
     * @param mixed  $departmentIds id or array of ids
     * @param string $findType      find type
     * @param mixed  $options       options for find
     *
     * @access public
     * @return void
     */
    function getByDepartmentIds($departmentIds, $findType = "all", $options = array())
    {
        $options = array_merge(array('conditions' => array('Department.id' => $departmentIds)), $options);
        if ($findType == 'list') {
            $courses = $this->find('all', $options);
            return Set::combine($courses, '{n}.'.$this->alias.'.id', '{n}.'.$this->alias.'.'.$this->displayField);
        }
        return $this->find($findType, $options);
    }
    /* }}} */

    /**
     * getCourseList
     *
     * @access public
     * @return void
     */
    function getCourseList()
    {
        $this->displayField = 'course';
        return $this->find('list', array(
            'conditions' => array()
        ));
    }

    /**
     * getCourseById
     *
     * @param mixed $courseId course id
     *
     * @access public
     * @return void
     */
    function getCourseById($courseId)
    {
        return $this->find('first', array(
            'conditions' => array('Course.id' => $courseId),
            'contain' => false
        ));
    }

    /**
     * getCourseById
     *
     * @param mixed $courseId course id
     *
     * @access public
     * @return void
     */
    function getCourseWithInstructorsById($courseId)
    {
        return $this->find('first', array(
            'conditions' => array('Course.id' => $courseId),
            'contain' => 'Instructor'
        ));
    }

    /**
     * getCourseWithEnrolmentById
     *
     * @param mixed $courseId
     *
     * @access public
     * @return void
     */
    function getCourseWithEnrolmentById($courseId)
    {
        $course = $this->find('first', array(
            'conditions' => array('Course.id' => $courseId),
            'contain' => array(
                'Enrol' => array(
                    'fields' => array('id', 'username', 'full_name', 'email', 'student_no')
                )
            )
        ));

        // some clean up
        foreach ($course['Enrol'] as $key => $student) {
            unset($course['Enrol'][$key]['UserEnrol']);
        }

        return $course;
    }

    /**
     * getCourseIdByGroup get the course by group id
     *
     * @param mixed $groupId
     *
     * @access public
     * @return object course object
     */
    function getCourseByGroupId($groupId)
    {
        $courseId = $this->Group->field('course_id', array('id' => $groupId));
        if (!$courseId) {
            return array();
        }
        return $this->find('first', array('conditions' => array('Course.id' => $courseId)));
    }


    /**
     * getAccessibleCourses get all course that the user has access to
     *
     * @param int    $userId     user id
     * @param mixed  $permission filter permission
     * @param string $type       find type
     * @param array  $options    find options
     *
     * @access public
     * @return void
     */
    function getAccessibleCourses($userId, $permission, $type = 'all', $options = array())
    {
        switch($permission) {
        case Course::FILTER_PERMISSION_SUPERADMIN:
            $courses = $this->find($type, $options);
            break;
        case Course::FILTER_PERMISSION_FACULTY:
            $department = Classregistry::init('Department');
            $departments = $department->find('all', array(
                'fields' => array('id'),
                'contain' => array(
                    'Faculty' => 'User.id = '.$userId
                ),
            ));
            $departmentIds = Set::extract($departments, '/Department/id');
            $courses = $this->getByDepartmentIds($departmentIds, $type, $options);
            break;
        case Course::FILTER_PERMISSION_OWNER:
            $options = array_merge(array('contain' => array(), 'conditions' => array()), $options);
            $courses = $this->getCourseByInstructor($userId, $type, $options['contain'], $options['conditions']);
            break;
        default:
            return array();
        }

        return $courses;
    }

    /**
     * getAccessibleCourseById get one course by course id, if user do not have
     * access to the course, return false.
     *
     * @param int   $courseId   course id
     * @param int   $userId     user id
     * @param mixed $permission filter permission
     * @param array $contain    contain relationship
     *
     * @access public
     * @return void
     */
    function getAccessibleCourseById($courseId, $userId,  $permission, $contain = array())
    {
        return $this->getAccessibleCourses($userId, $permission, 'first', array('conditions' => array('id' => $courseId), 'contain' => $contain));
    }
}
