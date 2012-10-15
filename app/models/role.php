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
     * getRoleName - return the name of the role given an role id
     *
     * @param string $roleId
     *
     * @access public
     * @return string name of the role
     */
    public function getRoleName($roleId)
    {
        return $this->field('name', array('id' => $roleId));
    }

    /**
     * Returns the default role for users. This is usually the lowest level
     * of access, in this case, it is the student role.
     *
     * @return The id of role with the lowest level of access
     * */
    public function getDefaultId() {
        return $this->field('id', array('name' => 'student'));
    }

}
