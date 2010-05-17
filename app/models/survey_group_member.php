<?php
/* SVN FILE: $Id: survey_group_member.php,v 1.1 2006/06/20 18:44:19 zoeshum Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.1 $
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
      return $this->findAll('group_set_id='.$groupSetId,'id');
    }
}

?>