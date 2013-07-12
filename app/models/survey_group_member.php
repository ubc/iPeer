<?php
/**
 * SurveyGroupMember
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveyGroupMember extends AppModel
{
    public $name = 'SurveyGroupMember';

    public $belongsTo = array(
        'User' => array(
            'className'    => 'User',
            'condition'    => '',
            'order'        => '',
            'foreignKey'   => 'user_id'
        ),
        'SurveyGroupSet' => array(
            'className'    => 'SurveyGroupSet',
            'condition'    => '',
            'order'        => '',
            'foreignKey'   => 'group_set_id'
        ),
    );

    /**
     * getIdsByGroupSetId
     *
     * @param bool $groupSetId
     *
     * @access public
     * @return void
     */
    function getIdsByGroupSetId($groupSetId=null)
    {
        //return $this->find('all', 'group_set_id='.$groupSetId, 'id');
        return $this->find('all', array(
            'conditions' => array('group_set_id' => $groupSetId),
            'fields' => array('SurveyGroupMember.id')
        ));
    }
}
