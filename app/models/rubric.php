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
 * @lastmodified $Date: 2006/08/30 16:51:39 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Rubric
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class Rubric extends AppModel
{
  var $name = 'Rubric';
  var $hasMany = array(
                        'RubricsCriteria' => array(
                          'className' => 'RubricsCriteria',
                          'dependent' => true
                        ),
                        'RubricsCriteriaComment' => array(
                          'className' => 'RubricsCriteriaComment',
                          'dependent' => true
                        ),
                        'RubricsLom' => array(
                          'className' => 'RubricsLom',
                          'dependent' => true
                        ),
/*                        'EvaluationRubric' => array(
                          'className' => 'EvaluationRubric',
                          'dependent' => true
                        )*/
  );

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
			$this->errorMessage='Rubric name is required.';
			$allowSave = false;
			//check the duplicate rubric
		} else
			$allowSave = $this->__checkDuplicateRubric();
		return $allowSave;
	}

	function __checkDuplicateRubric() {
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
		  $this->errorMessage='Duplicate Rubic found. Please change the rubic name.';
		  return false;
		}
		else {
		  return true;
		}
	}
	//sets the current userid and merges the form values into the data array
	function prepData($tmp=null, $userid=0){
		$tmp['data']['Rubric']['user_id'] = $userid;
		$tmp = array_merge($tmp['data']['Rubric'], $tmp['form']);
		unset($tmp['data']);

		if(empty($tmp['zero_mark']))
			$tmp['zero_mark'] = "off";

		return $tmp;
	}

	    /**
     * Returns the evaluations made by this user, and any other public ones.
     */
    function getBelongingOrPublic($userID) {
        return is_numeric($userID) ?
            $this->query("SELECT * FROM rubrics as Rubric where availability='public' or Rubric.creator_id=" . $userID)
            : false;
    }
}

