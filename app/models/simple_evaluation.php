<?php
/* SVN FILE: $Id: simple_evaluation.php,v 1.3 2006/09/25 17:31:54 kamilon Exp $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 1.3 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/09/25 17:31:54 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * SimpleEvaluation
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class SimpleEvaluation extends AppModel
{
  var $name = 'SimpleEvaluation';
  var $validate = array(
      'name' => VALID_NOT_EMPTY,
      'point_per_member' => VALID_NUMBER
  );
  
  var $hasMany = array(
                       'EvaluationSimple' => array(
                        'className' => 'EvaluationSimple',
                        'dependent' => true
                       )
  );
	//Overwriting Function - will be called before save operation 
	function beforeSave(){              
	  $allowSave = true;
	  if (empty($this->data[$this->name]['id'])) {
      //check the duplicate username
      $allowSave = $this->__checkDuplicateTitle();
    }
    return $allowSave;
	}    
  
  //Validation check on duplication of username	
	function __checkDuplicateTitle() {
	  $duplicate = false;
    $field = 'name';
    $value = $this->data[$this->name]['name'];  
    if ($result = $this->find($field . ' = "' . $value.'"', $field)){
      $duplicate = true;
     }
           
    if ($duplicate == true) {
      $this->errorMessage='Duplicate name found.  Please change the name of this Simple Evaluation';
      return false;
    } 
    else {
      return true;
    }	  
	}
	

}

?>