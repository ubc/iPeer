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
 * @lastmodified $Date: 2006/08/28 18:31:49 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Mixeval
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class Mixeval extends AppModel
{
    var $name = 'Mixeval';

	function beforeSave(){
        // Ensure the name is not empty
        if (empty($this->data[$this->name]['name'])) {
            $this->errorMessage = "Please enter a new name for this " . $this->name . ".";
            return false;
        }

        // Remove any signle quotes in the name, so that custom SQL queries are not confused.
        $this->data[$this->name]['name'] =
            str_replace("'", "", $this->data[$this->name]['name']);

		$allowSave = true;
		if (empty($this->data[$this->name]['name'])) {
			//check empty name
			$this->errorMessage='Mixed evaluation name is required.';
			$allowSave = false;
			//check the duplicate mixeval
		} else
			$allowSave = $this->__checkDuplicateMixeval();
		return $allowSave;
	}

	function __checkDuplicateMixeval() {
		$duplicate = false;
		$field = 'name';
		$value = $this->data[$this->name]['name'];
		if ($result = $this->find($field . ' = "' . $value.'"', $field.', id')){
		  if (isset($this->data[$this->name]['id']) &&
            ($this->data[$this->name]['id'] == $result[$this->name]['id'])) {
		    $duplicate = false;
		  } else {
  		  $duplicate = true;
  		}
		 }

		if ($duplicate) {
		  $this->errorMessage='Duplicate Mixed evaluation found. Please change the rubic name.';
		  return false;
		}
		else {
		  return true;
		}
	}
	//sets the current userid and merges the form values into the data array
	function prepData($tmp=null, $userid){

//		$tmp = array_merge($tmp['data']['Mixeval'], $tmp['form']);
    $ttlQuestionNo = $tmp['data']['Mixeval']['total_question'];
    $questions = array();
    for ($i = 1; $i < $ttlQuestionNo+1; $i++) {
      //Format questions for mixed eval
      $question['question_num'] = $i;
      $question['title'] = $tmp['data']['Mixeval']['title'.$i];
      isset($tmp['data']['Mixeval']['text_instruction'.$i])? $question['instructions'] = $tmp['data']['Mixeval']['text_instruction'.$i] : $question['instructions'] = null;
      $question['question_type'] = $tmp['data']['Mixeval']['question_type'.$i];
      isset($tmp['data']['Mixeval']['text_require'.$i])? $question['required'] = $tmp['data']['Mixeval']['text_require'.$i] : $question['required'] = 0;
      isset($tmp['form']['criteria_weight_'.$i])? $question['multiplier'] = $tmp['form']['criteria_weight_'.$i] : $question['multiplier'] = 0;
      $question['scale_level'] = $tmp['data']['Mixeval']['scale_max'];
      isset($tmp['data']['Mixeval']['response_type'.$i])? $question['response_type'] = $tmp['data']['Mixeval']['response_type'.$i] : $question['response_type'] = null;
      $questions[$i]['MixevalsQuestion'] = $question;

      //Format lickert descriptors
      if ($question['question_type'] == 'S') {
        for ($j = 1; $j <= $question['scale_level']; $j++) {
         $desc['question_num'] = $question['question_num'];
         $desc['scale_level'] = $j;

        // Make sure empty strings cause no php errors.
         $descriptor = isset($tmp['data']['Mixeval']['criteria_comment_'.$question['question_num'].'_'.$j]) ?
                             $tmp['data']['Mixeval']['criteria_comment_'.$question['question_num'].'_'.$j] : "";
         $desc['descriptor'] = $descriptor;
         $questions[$i]['MixevalsQuestion']['descriptor'][$j] = $desc;
        }

      }

    }

		return $questions;
	}

    /**
     * Returns the evaluations made by this user, and any other public ones.
     */
    function getBelongingOrPublic($userID) {
        return is_numeric($userID) ?
            $this->query("SELECT * FROM mixevals as Mixeval where availability='public' or Mixeval.creator_id=" . $userID)
            : false;
    }
}

?>
