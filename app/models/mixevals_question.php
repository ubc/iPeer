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
 * @lastmodified $Date: 2006/06/20 18:44:18 $
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
class MixevalsQuestion extends AppModel
{
  var $name = 'MixevalsQuestion';
  var $hasMany = array(
                  'Description' =>
                     array('className'   => 'MixevalsQuestionDesc',
                           'order'       => '',
                           'foreignKey'  => 'question_id',
                           'dependent'   => true,
                           'exclusive'   => true,
                           'finderSql'   => ''
                          ),
                     );

  // called by mixevals controller during add of mixeval
  // inserts with question comments for each mixeval
  function insertQuestion($id, $data){
    foreach ($data as $index => $value) {
   		$value['mixeval_id'] = $id;
   		$this->save($value);
  		$this->id = null;
  	}
	
   }
  
   // called by mixevals controller during an edit of an
   // existing mixeval question(s)
  function updateQuestion($id, $data){
    $this->deleteQuestions($id);
    $this->insertQuestion($id, $data);
  }
  
  // called by the delete function in the controller
  function deleteQuestions( $id ){
  	$this->query('DELETE FROM mixevals_questions WHERE mixeval_id='.$id);
  }
  
  // function to return the question description and weight from the
  // mixevals_loms table
  function getQuestion( $id=null){
//  	$data = $this->find('all','mixeval_id='.$id, null, 'question_num ASC');
  	return $this->find('all', array(
            'conditions' => array('mixeval_id' => $id),
            'order' => 'question_num ASC'
        ));
  }  
}
?>
