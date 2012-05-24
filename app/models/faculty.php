<?php
class Faculty extends AppModel {
    var $name = 'Faculty';
    var $displayField = 'name';
    var $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
    );

}
