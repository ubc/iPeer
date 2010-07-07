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
 * RubricLom
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class RubricsLom extends AppModel
{
  var $name = 'RubricsLom';

  var $belongsTo = array( 'Rubric' => array(
                     'className' => 'Rubric'
                   ));

  // called by rubrics controller during add/edit of rubric
  // inserts/updates with LOM comments for each rubric
  function insertLOM( $id=null, $data ){
  	for( $i=1; $i<=$data['lom_max']; $i++ ){
  		$tmp = array( 'rubric_id'=>$id, 'lom_num'=>$i, 'lom_comment'=>$data['lom_comment'.$i]);
  		$this->save($tmp);
  		$this->id = null;
  	}
  }

  // called by rubrics controller during an edit of an
  // existing rubric lom(s)
  function updateLOM( $data ){
  	$this->query('DELETE FROM rubrics_loms WHERE rubric_id='.$data['id']);

	  for( $i=1; $i<=$data['lom_max']; $i++ ){
		  $this->query('INSERT INTO rubrics_loms (rubric_id, lom_num, lom_comment) VALUES ("'.$data['id'].'","'.$i.'","'.$data['lom_comment'.$i].'")');
	  }
  }

  // called by the delete function in the controller
  function deleteLOM( $id ){
  	$this->query('DELETE FROM rubrics_loms WHERE rubric_id='.$id);
  }

  // function to return the LOM general statements from the
  // rubrics_loms table
  function getLOM( $id=null, $lom_num=null ){
  	$data = $this->findAll($conditions = 'rubric_id='.$id, $fields = 'lom_comment');

  	for( $i=0; $i<$lom_num; $i++ ){
  		if( !empty( $data[$i]['RubricsLom']['lom_comment'] ) )
  			$tmp['lom_comment'.($i+1)] = $data[$i]['RubricsLom']['lom_comment'];
  		else
  			$tmp['lom_comment'.($i+1)] = null;
  	}
  	return $tmp;
  }
}

?>