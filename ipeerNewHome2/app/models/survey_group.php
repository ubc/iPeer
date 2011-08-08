<?php
/* SVN FILE: $Id: survey_group.php 596 2011-06-22 22:33:26Z TonyChiu $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 596 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/06/20 18:44:18 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Survey
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class SurveyGroup extends AppModel
{
    var $name = 'SurveyGroup';
/*    var $hasMany = array('SurveyGroupMember' =>
                         array('className'   => 'SurveyGroupMember',
                               'conditions'  => '',
                               'order'       => '',
                               'foreignKey'  => 'group_id',
                               'dependent'   => true,
                               'exclusive'   => false,
                               'finderSql'   => ''
                              ));*/


    var $hasAndBelongsToMany = array('Member' =>
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

  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

	//Function obsolete
   /* function getIdByGroupSetIdGroupNumber($groupSetId=null,$groupNumber=null) {
      //$tmp = $this->find('group_set_id='.$groupSetId.' AND group_number='.$groupNumber);
        $tmp = $this->find('first', array(
            'conditions' => array('group_set_id' => $groupSetId, 'group_number' => $groupNumber)
        ));
      return $tmp['SurveyGroup']['id'];
    }*/

    function getIdsByGroupSetId($groupSetId=null) {
      //return $this->find('all','group_set_id='.$groupSetId,'id');
        return $this->find('all', array(
            'conditions' => array('group_set_id' => $groupSetId),
            'fields' => array('SurveyGroup.id')
        ));
    }
}

?>
