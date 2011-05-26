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
  function getQuestionsID($surveyId=null) {
    
  	$data = $this->find('all', array('conditions'=> array('survey_id' => $surveyId),
                                     'fields' => array('number', 'question_id', 'id'),
                                     'order' => 'number'));
    $data['count'] = count($data);
    return $data;
  	
  	/*
  	$surveyId=5;
  	$sql = "SELECT question_id
  			FROM survey_questions
  			WHERE survey_id = $surveyId" ;  	
    $val = $this->query($sql);
    var_dump($val);
    */
  }

}

?>
