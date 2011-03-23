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
 * @lastmodified $Date: 2006/10/05 16:47:45 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * RubricCriteria
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class RubricsCriteria extends AppModel
{
  var $name = 'RubricsCriteria';
  var $actsAs = array('Containable');
  
  var $belongsTo = array( 'Rubric' => array(
                     'className' => 'Rubric'                     
                   ));

  var $hasMany = array( 'RubricsCriteriaComment' => array(
                    'className' => 'RubricsCriteriaComment',
                    'foreignKey' => 'criteria_id',
                    'dependent' => true,
                    'exclusive' => true,
                    ));

/*  // called by rubrics controller during add/edit of rubric
  // inserts/updates with criteria comments for each rubric
  function insertCriteria($id=null, $data){
  	for( $i=1; $i<=$data['criteria']; $i++ ){
  		$tmp = array( 'rubric_id'=>$id, 'criteria_num'=>$i, 'criteria'=>$data['criteria'.$i], 'multiplier'=>$data['criteria_weight_'.$i]);
  		$this->save($tmp);
  		$this->id = null;
  	}
  }

   // called by rubrics controller during an edit of an
   // existing rubric criteria(s)
   function updateCriteria($data){
   	$this->query('DELETE FROM rubrics_criterias WHERE rubric_id='.$data['id']);

  	for( $i=1; $i<=$data['criteria']; $i++ ){
  		$this->query('INSERT INTO rubrics_criterias (rubric_id, criteria_num, criteria, multiplier) VALUES ("'.$data['id'].'","'.$i.'","'.$data['criteria'.$i].'","'.$data['criteria_weight_'.$i].'")');
  	}
  }

  // called by the delete function in the controller
  function deleteCriterias( $id ){
    $this->deleteAll(array('rubric_id' => $id));
  	//$this->query('DELETE FROM rubrics_criterias WHERE rubric_id='.$id);
  }

  // function to return the criteria description and weight from the
  // rubrics_loms table
  function getCriteria( $id=null){
    
  	$data = $this->find('all',$conditions = 'rubric_id='.$id, $fields = 'criteria, multiplier');

  	for( $i=0; $i<count($data); $i++ ){
  		if( !empty( $data[$i]['RubricsCriteria']['criteria'] ) )
  			$tmp['criteria'.($i+1)] = $data[$i]['RubricsCriteria']['criteria'];
  		else
  			$tmp['criteria'.($i+1)] = null;

  		if( !empty( $data[$i]['RubricsCriteria']['multiplier'] ) )
  			$tmp['criteria_weight_'.($i+1)] = $data[$i]['RubricsCriteria']['multiplier'];
  		else
  			$tmp['criteria_weight_'.($i+1)] = 1;
  	}

  	return $tmp;
  }*/

}

?>
