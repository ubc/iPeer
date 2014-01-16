<?php
/**
 * Department model, stores a list of departments.
 * */
class Department extends AppModel {
    public $name = 'Department';
    public $displayField = 'name';
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => 'notempty',
                'message' => 'Please fill in the name of the Department.'
            ),
        ),
        'faculty_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
    );

    public $belongsTo = array('Faculty');

    /**
     * Given a list of userfaculties, return the departments
     * belonging to those faculties.
     *
     * @param mixed $userfaculties - the list of userfaculties entries to get
     * the departments for.
     *
     * @return departments in the given list of faculties
     * */
    public function getByUserFaculties($userfaculties) {
        $departments = array();
        foreach ($userfaculties as $userfaculty) {
            $fid = $userfaculty['UserFaculty']['faculty_id'];
            $ret = $this->findAllByFacultyId($fid);
            $departments = array_merge($departments, $ret);
        }
        return $departments;
    }

    /**
     * getIdsByUserId
     * 
     * @param mixed $userId user id
     *
     * @access public
     * @return void
     */
    public function getIdsByUserId($userId)
    {
        $faculties = $this->Faculty->find('all', array(
            'conditions' => array('User.id' => $userId),
            'contain' => 'User',
        ));
        $departments = $this->find('all', array(
            'conditions' => array('faculty_id' => Set::extract($faculties, '/Faculty/id')),
            'fields' => array('id'),
            'contain' => false,
        ));

        return Set::extract($departments, '/Department/id');
    }
}
