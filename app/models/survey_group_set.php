<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
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
class SurveyGroupSet extends AppModel
{
    var $name = 'SurveyGroupSet';
    var $belongsTo = array('Survey' => 
                           array('className'    => 'Survey',
                                 'condition'    => '',
                                 'order'        => '',
                                 'foreignKey'   => 'survey_id'
                                )
                          );

    function getIdBySurveyIdSetDescription($surveyId=null,$setDescription=null) {
      $tmp = $this->find('survey_id='.$surveyId.' AND set_description=\''.$setDescription.'\'');
      return $tmp['SurveyGroupSet']['id'];
    }

    function getSurveyIdById($id=null) {
      $tmp = $this->find('id='.$id);
      return $tmp['SurveyGroupSet']['survey_id'];
    }
}

?>
