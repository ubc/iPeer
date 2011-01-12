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

  // used to copy questions to a new survey when a template is used
  function copyQuestions($survey_id, $id)
  {
  	$tmp = $this->findAll($conditions='survey_id='.$survey_id);

	for( $i=0; $i<sizeof($tmp); $i++ ){
		$data['survey_id'] = $id;
		$data['question_id'] = $tmp[$i]['SurveyQuestion']['question_id'];
		$data['number'] = $tmp[$i]['SurveyQuestion']['number'];

		$this->save($data);
		$this->id=null;
	}
  }

  // saves rows into table survey_questions for a survey and its
  // corresponding questions
  function linkQuestions($survey_id, $question_id)
  {
  	$data['survey_id'] = $survey_id;
	$data['question_id'] = $question_id;
	$data['number'] = ($this->findCount($conditions='survey_id ='.$survey_id)) +1;

	$this->save($data);
  }

  // returns all the question IDs of a specific survey
  function getQuestionsID($survey_id)
  {
	$data = $this->findAll($conditions='survey_id='.$survey_id, $fields="number, question_id, id", 'number ASC');
	$data['count'] = count($data);

	return $data;
  }

}

?>
