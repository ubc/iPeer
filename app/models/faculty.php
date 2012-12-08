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
    public $hasAndBelongsToMany = array(
        'User' => array(
            'joinTable' => 'user_faculties'
        ),
    );

}
