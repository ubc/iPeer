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
 * RubricCriteriaComment
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class RubricsCriteriaComment extends AppModel
{
  var $name = 'RubricsCriteriaComment';
  var $actsAs = array('Containable');
  
  var $belongsTo = array( 'RubricsCriteria' => array(
                     'className' => 'RubricsCriteria',
                     'foreignKey' => 'criteria_id'
                     ));
  
  // called by rubrics controller during add/edit of rubric
  // inserts/updates with criteria comments for each rubric
  function insertCriteriaComm($id=null, $data){
  	for( $i=1; $i<=$data['criteria']; $i++ ){
  		for( $j=1; $j<=$data['lom_max']; $j++ ){
  			if( !empty($data['criteria_comment_'.$i.'_'.$j]) )
  				$tmp = array( 'rubric_id'=>$id, 'criteria_num'=>$i, 'lom_num'=>$j, 'criteria_comment'=>$data['criteria_comment_'.$i.'_'.$j]);
  			else
  				$tmp = array( 'rubric_id'=>$id, 'criteria_num'=>$i, 'lom_num'=>$j, 'criteria_comment'=>null);
  				
  			$this->save($tmp);
  			$this->id = null;
  		}
  	}
  }
  
  // called by rubrics controller during an edit of an
  // existing rubric criteria comment(s)
  function updateCriteriaComm($data){
  	$this->query('DELETE FROM rubrics_criteria_comments WHERE rubric_id='.$data['id']);
  	
  	for( $i=1; $i<=$data['criteria']; $i++ ){
  		for( $j=1; $j<=$data['lom_max']; $j++ ){
  			if( !empty($data['criteria_comment_'.$i.'_'.$j]) )
  				$this->query('INSERT INTO rubrics_criteria_comments (rubric_id, criteria_num, lom_num, criteria_comment) VALUES ("'.$data['id'].'","'.$i.'","'.$j.'","'.$data['criteria_comment_'.$i.'_'.$j].'")');
  			else
  				$this->query('INSERT INTO rubrics_criteria_comments (rubric_id, criteria_num, lom_num, criteria_comment) VALUES ("'.$data['id'].'","'.$i.'","'.$j.'",null)');
  		}
  	}
  }
  
  // called by the delete function in the controller
  function deleteCriteriaComments( $id ){
  	$this->query('DELETE FROM rubrics_criteria_comments WHERE rubric_id='.$id);
  }
  
  // function to return the criteria x lom comments
  /*function getCriteriaComment( $id=null, $criteria=null, $lom=null ){	
  	for( $i=0; $i<$criteria; $i++ ){
  		for( $j=0; $j<$lom; $j++ ){
  			$data = $this->find('all',$conditions = 'rubric_id='.$id." AND ".'criteria_num='.($i+1)." AND ".'lom_num='.($j+1), $fields = 'criteria_comment');
  			if( !empty($data) )
  				$tmp['criteria_comment_'.($i+1).'_'.($j+1)] = $data[0]['RubricsCriteriaComment']['criteria_comment'];
  			else
  				$tmp['criteria_comment_'.($i+1).'_'.($j+1)] = null;
  		}
  	}
  	return $tmp;
  }*/
}

?>
