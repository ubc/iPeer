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
 * @lastmodified $Date: 2006/07/25 16:29:58 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Question
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class SurveyQuestion extends AppModel
{
  var $name = 'SurveyQuestion';

  // returns all the question IDs of a specific survey
  function getQuestionsID($survey_id) {
    $data = $this->find('all', array('conditions'=> array('survey_id' => $survey_id),
                                     'fields' => array('number', 'question_id', 'id')));
    $data['count'] = count($data);

    return $data;
  }

}

?>
