<?php
/**
 * RubricsCriteria
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class RubricsCriteria extends AppModel
{
    public $name = 'RubricsCriteria';
    public $actsAs = array('Containable');

    public $belongsTo = array( 'Rubric' => array(
        'className' => 'Rubric'
    ));

    public $hasMany = array( 'RubricsCriteriaComment' => array(
        'className' => 'RubricsCriteriaComment',
        'foreignKey' => 'criteria_id',
        'dependent' => true,
        'exclusive' => true,
    ));

    /**
     * getCriteria
     *
     * @param mixed $rubric_id
     *
     * @access public
     * @return array the criteria array
     */
    function getCriteria($rubric_id)
    {
        //          $sql = "SELECT criteria
        //                          FROM rubrics_criterias
        //                          WHERE rubric_id=$id";
        //          return $this->query($sql);
        return $this->find('all', array(
            'conditions' => array('RubricsCriteria.rubric_id' => $rubric_id),
            'order' => array('criteria_num ASC')
        ));
    }

/*  // called by rubrics controller during add/edit of rubric
  // inserts/updates with criteria comments for each rubric
  function insertCriteria($id=null, $data)
{
    for ( $i=1; $i<=$data['criteria']; $i++ ){
        $tmp = array( 'rubric_id'=>$id, 'criteria_num'=>$i, 'criteria'=>$data['criteria'.$i], 'multiplier'=>$data['criteria_weight_'.$i]);
        $this->save($tmp);
        $this->id = null;
    }
  }

   // called by rubrics controller during an edit of an
   // existing rubric criteria(s)
   function updateCriteria($data)
{
    $this->query('DELETE FROM rubrics_criterias WHERE rubric_id='.$data['id']);

    for ( $i=1; $i<=$data['criteria']; $i++ ){
        $this->query('INSERT INTO rubrics_criterias (rubric_id, criteria_num, criteria, multiplier) VALUES ("'.$data['id'].'","'.$i.'","'.$data['criteria'.$i].'","'.$data['criteria_weight_'.$i].'")');
    }
  }

  // called by the delete function in the controller
  function deleteCriterias( $id )
{
    $this->deleteAll(array('rubric_id' => $id));
    //$this->query('DELETE FROM rubrics_criterias WHERE rubric_id='.$id);
  }

  // function to return the criteria description and weight from the
  // rubrics_loms table
  function getCriteria( $id=null)
{

    $data = $this->find('all', $conditions = 'rubric_id='.$id, $fields = 'criteria, multiplier');

    for ( $i=0; $i<count($data); $i++ ){
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
