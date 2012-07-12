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
                'rule' => array('notempty'),
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
}
