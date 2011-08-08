<?php
/* SVN FILE: $Id: survey_group_member.php 484 2011-05-20 23:22:42Z tae $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 484 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/06/20 18:44:19 $
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
class SurveyGroupMember extends AppModel
{
    var $name = 'SurveyGroupMember';

    function getIdsByGroupSetId($groupSetId=null) {
      //return $this->find('all','group_set_id='.$groupSetId,'id');
        return $this->find('all', array(
            'conditions' => array('group_set_id' => $groupSetId),
            'fields' => array('SurveyGroupMember.id')
        ));
    }
}

?>
