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

  // called by mixevals controller during add/edit of mixeval
  // inserts/updates with question comments for each mixeval
  function insertQuestionDescriptor($id, $data){

    foreach ($data as $row) {
      if (isset($row['MixevalsQuestion']['descriptor'])) {
        $descriptors =  $row['MixevalsQuestion']['descriptor'];
        foreach ($descriptors as $index => $value) {
          $desc['MixevalsQuestionDesc'] = $value;
          $desc['MixevalsQuestionDesc']['mixeval_id'] = $id;
    			$this->save($desc);
    			$this->id = null;
        }
      }
  	}
  }

  // called by mixevals controller during an edit of an
  // existing mixeval question comment(s)
  function updateQuestionDescriptor($id=null, $data){
  	$this->deleteQuestionDescriptors( $id );
  	$this->insertQuestionDescriptor($id, $data);
  }

  // called by the delete function in the controller
  function deleteQuestionDescriptors( $id ){
  	$this->query('DELETE FROM mixevals_question_descs WHERE mixeval_id='.$id);
  }

  // function to return the question's descriptor
  function getQuestionDescriptor($question_id){
		$data = $this->findAll('question_id='.$question_id, null, 'scale_level ASC');

  	return $data;
  }
}
?>