<?php
/* SVN FILE: $Id: question.php,v 1.1 2006/06/20 18:44:18 zoeshum Exp $ */

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
 * @lastmodified $Date: 2006/06/20 18:44:18 $
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
class Question extends AppModel
{
  var $name = 'Question';

  // prepares the data by moving varibles in the form to the data question sub array
  function prepData($data)
  {
  	$data['data']['Question']['master'] = $data['form']['master'];
	$data['data']['Question']['type'] = $data['form']['type'];
	$data['data']['Question']['count'] = $data['form']['add'];

	return $data;
  }

  // sets the data variable up with proper formating in the array for display
  function fillQuestion($data)
  {
	for( $i=0; $i<$data['count']; $i++ ){
		$data[$i]['Question'] = $this->findAll($conditions='id='.$data[$i]['SurveyQuestion']['question_id'], $fields="prompt, type");
		$data[$i]['Question'] = $data[$i]['Question'][0]['Question'];
		$data[$i]['Question']['number'] = $data[$i]['SurveyQuestion']['number'];
		$data[$i]['Question']['id'] = $data[$i]['SurveyQuestion']['question_id'];
		$data[$i]['Question']['sq_id'] = $data[$i]['SurveyQuestion']['id'];
		unset($data[$i]['SurveyQuestion']);
	}

	return $data;
  }

  // delete old question references in each table
  function editCleanUp($question_id)
  {
  		$this->query('DELETE FROM questions WHERE id='.$question_id);
		$this->query('DELETE FROM responses WHERE question_id='.$question_id);
		$this->query('DELETE FROM survey_questions WHERE question_id='.$question_id);
  }

  function getTypeById($id=null) {
    $type = $this->find('id='.$id,'type');
    return $type['Question']['type'];
  }
}

?>