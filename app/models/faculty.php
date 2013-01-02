<?php
/**
 * Faculty model, stores a list of faculties.
 * */
class Faculty extends AppModel
{
    public $name = 'Faculty';
    public $displayField = 'name';
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
    );

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

    public $hasAndBelongsToMany = array(
        'User' => array(
            'className'             => 'User',
            'joinTable'             => 'user_faculties',
            'foreignKey'            => 'faculty_id',
            'associationForeignKey' => 'user_id',
            'unique'                => true,
            'dependent'             => true,
        ),
    );

}
