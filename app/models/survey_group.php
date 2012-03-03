<?php
/**
 * SurveyGroup
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveyGroup extends AppModel
{
    public $name = 'SurveyGroup';
/*    public $hasMany = array('SurveyGroupMember' =>
                         array('className'   => 'SurveyGroupMember',
                               'conditions'  => '',
                               'order'       => '',
                               'foreignKey'  => 'group_id',
                               'dependent'   => true,
                               'exclusive'   => false,
                               'finderSql'   => ''
));*/


    public $hasAndBelongsToMany = array('Member' =>
        array('className'    =>  'User',
            'joinTable'    =>  'survey_group_members',
            'foreignKey'   =>  'group_id',
            'associationForeignKey'    =>  'user_id',
            'conditions'   =>  '',
            'order'        =>  '',
            'limit'        => '',
            'unique'       => true,
            'finderQuery'  => '',
            'deleteQuery'  => '',
            'dependent'    => false,
        ),
    );

    public $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

    //Function obsolete
   /* function getIdByGroupSetIdGroupNumber($groupSetId=null, $groupNumber=null)
{
      //$tmp = $this->find('group_set_id='.$groupSetId.' AND group_number='.$groupNumber);
        $tmp = $this->find('first', array(
            'conditions' => array('group_set_id' => $groupSetId, 'group_number' => $groupNumber)
        ));
      return $tmp['SurveyGroup']['id'];
   }*/

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
            'fields' => array('SurveyGroup.id')
        ));
    }
}
