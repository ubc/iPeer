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
 * @lastmodified $Date: 2006/08/28 18:34:28 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * MixevalsQuestion
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class MixevalsQuestionDesc extends AppModel
{
  var $name = 'MixevalsQuestionDesc';

  var $belongsTo = array(
'MixevalsQuestion' => array(
		'className' => 'MixevalsQuestion',
		'foreignKey' => 'question_id'
	)
);

/**
 * Saves Mix evaluation question descriptions to database
 * 
 * @param type_Array $data : Mixeval_question data array
 * @param type_Array $question_ids : Array of question_ids for Mixeval_questions
 */
  function insertQuestionDescriptor($data, $question_ids) {
    foreach ($data as $row){
      if(isset($row['Description'])){
        $descriptors =  $row['Description'];
        foreach($question_ids as $question_id){
          	if($question_id['MixevalsQuestion']['question_num'] == $row['question_num']){
          		$q_id = $question_id['MixevalsQuestion']['id']; 
          	}
          } 
        
        foreach ($descriptors as $index => $value) {
          	$desc = $value;
          	//$desc['mixeval_id'] = $id;     
          	$desc['question_id'] = $q_id;
          	$this->save($desc);
    	  	$this->id = null;
        }
      }
  	}
  }

  // called by mixevals controller during an edit of an
  // existing mixeval question comment(s)
  /* FUNCTION NOT BEING USED
  function updateQuestionDescriptor($id=null, $data) {
  	$this->delete($id);
  	$this->insertQuestionDescriptor($id, $data);
  }*/

//  // called by the delete function in the controller
//  function deleteQuestionDescriptors( $id ){
//
//
//
//  	$this->query('DELETE
//  				  FROM mixevals_question_descs
//  				  WHERE question_id IN
//  				  	(SELECT id
//  				  	FROM mixevals_questions
//  				  	WHERE id='.$id.')');
//  }

  // function to return the question's descriptor
  function getQuestionDescriptor($questionId){
/*		$data = $this->find('all','mixeval_id='.$mixevalId.' AND question_num='.$questionNum, null, 'scale_level ASC');
		return $data;*/
 	/*return $this->find('all', array(
            'conditions' => array('mixeval_id' => $mixevalId, 'question_num' => $questionNum),
            'order' => 'MixevalsQuestionDesc.id ASC'
        ));*/
  	return $this->find('all', array('conditions' => array('question_id' => $questionId), 
  								  	'order' => 'MixevalsQuestionDesc.id ASC'));
  }
}
?>
