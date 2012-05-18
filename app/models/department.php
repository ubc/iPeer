<?php
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
    public $hasMany = array('CourseDepartment');
}
