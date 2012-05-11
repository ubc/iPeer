<?php
/**
 * Role Model
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Role extends AppModel
{
    //The model name
    public $name = 'Role';
    public $test = 'a';
    public $displayField = 'name';

    public $hasAndBelongsToMany = array(
        'User' => array(
            'className'             => 'User',
            'joinTable'             => 'roles_users',
            'foreignKey'            => 'role_id',
            'associationForeignKey' => 'user_id',
            'unique'                => true,
            'dependent'             => true,
        )
    );

    public $actsAs = array('Acl' => array('type' => 'requester'), 'ExtendAssociations', 'Containable', 'Habtamable');

    /**
     * parentNode
     *
     * @access public
     * @return void
     */
    function parentNode()
    {
        return null;
    }


    /**
     * getRoleByRoleNumber
     *
     * @param string $roleNum
     *
     * @access public
     * @return void
     */
    function getRoleByRoleNumber($roleNum = '')
    {
        if ($roleNum==1) {
            return 'SA';
        }

        if ($roleNum==2) {
            return 'A';
        }
        if ($roleNum==3) {
            return 'I';
        }
        if ($roleNum==4) {
            return 'S';
        } else {
            return null;
        }
    }

}
