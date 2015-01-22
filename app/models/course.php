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
    const FILTER_PERMISSION_ENROLLED = 3;
    
    const IDENTIFIER = 0;

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
            'with' => 'CourseDepartment',
            'joinTable' => 'course_departments',
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
        ),
        'homepage' => array(
            'http' => array(
                'rule' => 'includeHttp'
            ),
            'url' => array(
                'rule' => 'url',
                'allowEmpty' => true,
                'message' => 'The homepage is not a valid URL.'
            )
        )
    );

    /**
     * For some reason, HABTM fields can't be validated with $validate.
     * So this is a workaround, make sure courses have at least 1
     * department selected.
     *
     * Disable this for now as it is ok for a course doesn't have department
     * Only super admin and instructors in the course have access to it
     * */
    /*public function beforeValidate() {
        if (array_key_exists('Department', $this->data) &&
            empty($this->data['Department']['Department'])) {
            // make sure this model fails when saving without department
            $this->invalidate('Department');
            // make the error message appear in the right place
            $this->Department->invalidate('Department',
                'Please select a department.');
        }
    }*/
    
    /**
     * include http to the beginning of the url if it is not already there
     *
     * @param mixed $check homepage field
     *
     * @access public
     * @return void
     */
    function includeHttp($check)
    {
        $prefix = substr($check['homepage'], 0, 4);
        if ($prefix != 'http' && !empty($check['homepage'])) {
            $this->data['Course']['homepage'] = 'http://'.$this->data['Course']['homepage'];
        }
        return true;
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
        // sort courses alphabetically
        $courses = $this->find(
            $type, 
            array(
                'conditions' => $conditions, 
                'contain' => $contain,
                'order' => 'Course.course'
            )
        );

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
     * Get course data by student id
     *
     * @param mixed $studentId  student id
     * @param bool  $type       type
     * @param int   $contain    contained models
     * @param array $conditions conditions for find
     *
     * @return course data
     */
    function getCourseByStudent($studentId, $type = 'all', $contain = array(), $conditions = array())
    {
        $contain = array_merge(array('Enrol'), $contain);
        /*if ($type == 'list') {
            $fields = array('Course.full_name');
        }*/

        // we need two queries to find the courses. becuase if we specifiy the student id condition
        // we can only get one student with the id we specified. If the course has more than one
        // student, we will fail to retrieve them.

        // find course ids first
        $courses = $this->find(
            'all',
            array(
                'conditions' => array('Enrol.id' => $studentId),
                'contain' => array('Enrol')
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
        // find courses with student and other models specified in contain
        $courses = $this->find($type, array('conditions' => $conditions, 'contain' => $contain));

        return $courses;
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
        $options['group'] = 'Course.id'; // prevent dups when a course is in
                                        // multiple faculties
        $options['order'] = 'Course.course'; // sort courses alphabetically
        if ($findType != 'first') {
            $options['conditions']['Department.id'] = $departmentIds;
        }
        if(isset($options['contain'])) { 
            $options['contain'] = array_merge(array('Department'), $options['contain']);
        } else {
            $options['contain'] = array('Department');
        }
        if ($findType == 'list') {
            $courses = $this->find('all', $options);
            return Set::combine($courses, '{n}.'.$this->alias.'.id', '{n}.'.$this->alias.'.'.$this->displayField);
        }
        
        $course = $this->find($findType, $options);
        $xDept = array_intersect(Set::extract('/Department/id', $course), $departmentIds);
        
        if (!empty($xDept)) {
            return $course;
        } else {
            return array();
        }
    }

    /**
     * getCourseList
     *
     * @param mixed $courseIds course id
     *
     * @access public
     * @return void
     */
    function getCourseList($courseIds)
    {
        return $this->find('list', array(
            'conditions' => array('Course.id' => $courseIds)
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
     * getCourseWithInstructorsById
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
        foreach ($course['Enrol'] as &$student) {
            unset($student['UserEnrol']);
        }
        unset($student);

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
     * getCoursesByUserIdFilterPermission
     *
     * @param int    $userId     user id
     * @param mixed  $permission filter permission
     * @param string $type       find type
     * @param array  $options    find options
     *
     * @access protected
     * @return void
     */
    protected function getCoursesByUserIdFilterPermission($userId, $permission, $type = 'all', $options = array())
    {
        switch($permission) {
        case Course::FILTER_PERMISSION_SUPERADMIN:
            // sort courses alphabetically
            $options['order'] = 'Course.course';
            $courses = $this->find($type, $options);
            break;
        case Course::FILTER_PERMISSION_FACULTY:
            $departmentIds = $this->Department->getIdsByUserId($userId);
            $adminCourses = $this->getByDepartmentIds($departmentIds, $type, $options);
            $adminCoursesIds = array();
            for ($i = 0; $i < count($adminCourses); $i++) {
                if(isset($adminCourses[$i])) { // to stop undefined offsets
                    $adminCoursesIds[] = $adminCourses[$i]['Course']['id'];
                }
            }
            $options = array_merge(array('contain' => array(), 'conditions' => array()), $options);
            $instCourses = $this->getCourseByInstructor($userId, $type, $options['contain'], $options['conditions']);
            for ($i = 0; $i < count($instCourses); $i++) { // to remove duplicate courses
                if (isset($instCourses[$i]) && in_array($instCourses[$i]['Course']['id'], $adminCoursesIds)) {  // to stop undefined offsets
                    unset ($instCourses[$i]);
                }
            }
            if (!empty($instCourses)) {
                $courses = array_merge($adminCourses, $instCourses);
                // sort courses alphabetically
                $names = array();
                foreach ($courses as $key => $row) {
                    $names[$key] = $row['Course']['course'];
                }
                array_multisort($names, SORT_ASC, $courses);
            }
            else {
                $courses = $adminCourses;
            }
            break;
        case Course::FILTER_PERMISSION_OWNER:
            $options = array_merge(array('contain' => array(), 'conditions' => array()), $options);
            $courses = $this->getCourseByInstructor($userId, $type, $options['contain'], $options['conditions']);
            break;
        case Course::FILTER_PERMISSION_ENROLLED:
            $options = array_merge(array('contain' => array(), 'conditions' => array()), $options);
            $courses = $this->getCourseByStudent($userId, $type, $options['contain'], $options['conditions']);
            break;
        default:
            return array();
        }

        return $courses;
    }

    /**
     * getAccessibleCourses get all active course that the user has access to
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
        $default = array('conditions' => array('record_status' => 'A'));
        $options = array_merge($default, $options);
        return $this->getCoursesByUserIdFilterPermission($userId, $permission, $type, $options);
    }

    /**
     * getAllAccessibleCourses get all course that the user has access to,
     * including inactive ones
     *
     * @param int    $userId     user id
     * @param mixed  $permission filter permission
     * @param string $type       find type
     * @param array  $options    find options
     *
     * @access public
     * @return void
     */
    function getAllAccessibleCourses($userId, $permission, $type = 'all', $options = array())
    {
        return $this->getCoursesByUserIdFilterPermission($userId, $permission, $type, $options);
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
        return $this->getAccessibleCourses($userId, $permission, 'first', array('conditions' => array($this->alias.'.id' => $courseId), 'contain' => $contain));
    }
    
    /**
     * Get list of users enrolled in a course
     *
     * @param mixed $course_id
     *
     * @return list of user ids
     */
    function getUserListbyCourse($course_id) {
        $users = $this->find('first', array(
            'conditions' => array('Course.id' => $course_id),
            'contain' => array('Enrol')
        ));
        return Set::extract($users, '/Enrol/id');
    }
     
}
