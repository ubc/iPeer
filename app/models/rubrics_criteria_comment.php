<?php
/**
 * RubricsCriteriaComment
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class RubricsCriteriaComment extends AppModel
{
    public $name = 'RubricsCriteriaComment';
    public $actsAs = array('Containable');

    public $belongsTo = array( 'RubricsCriteria' => array(
        'className' => 'RubricsCriteria',
        'foreignKey' => 'criteria_id'
    ));

    // Function Obsolete
    // called by rubrics controller during add/edit of rubric
    // inserts/updates with criteria comments for each rubric
  /*function insertCriteriaComm($id=null, $data)
{

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
  }*/

    // called by rubrics controller during an edit of an
    // existing rubric criteria comment(s)
    //  function updateCriteriaComm($data)
    //  {
    //  	//$this->query('DELETE FROM rubrics_criteria_comments WHERE rubric_id='.$data['id']);
    //    $tmp = array();
    //    $this->deleteAll(array('RubricsCriteriaComment.rubric_id' => $data['id']));
    //    for( $i=1; $i<=$data['criteria']; $i++ ){
    //      for( $j=1; $j<=$data['lom_max']; $j++ ){
    ////          if( !empty($data['criteria_comment_'.$i.'_'.$j]) )
    ////                  $this->query('INSERT INTO rubrics_criteria_comments (rubric_id, criteria_num, lom_num, criteria_comment) VALUES ("'.$data['id'].'","'.$i.'","'.$j.'","'.$data['criteria_comment_'.$i.'_'.$j].'")');
    ////          else
    ////                  $this->query('INSERT INTO rubrics_criteria_comments (rubric_id, criteria_num, lom_num, criteria_comment) VALUES ("'.$data['id'].'","'.$i.'","'.$j.'",null)');
    //        $tmp['RubricsCriteriaComment'] = array('rubric_id' => $data['id'], 'criteria_num' => $i, 'lom_num' => $j);
    //        if( !empty($data['criteria_comment_'.$i.'_'.$j]) )
    //          $tmp['RubricsCriteriaComment'] =  $tmp['RubricsCriteriaComment'] + array('criteria_comment' => $data['criteria_comment_'.$i.'_'.$j]);
    //      }
    //    }
    //  }

    /**
     * Called by the delete function in the controller; deletes all criteria
     * comments associated with given rubric.
     *
     * @param type_INT $rubricId : rubric's id.
     */
    function deleteCriteriaComments($rubricId)
    {
        $this->RubricsCriteria = ClassRegistry::init('RubricsCriteria');
        $rubricsCriteriaId = $this->RubricsCriteria->find('all',
            array('conditions' => array('rubric_id' => $rubricId),
            'fields' => array('id')));

        for ($i=0; $i<count($rubricsCriteriaId); $i++) {
            $criteriaId = $rubricsCriteriaId[$i]['RubricsCriteria']['id'];
            $this->deleteAll(array('RubricsCriteriaComment.criteria_id' => $criteriaId));
        }
        //$this->deleteAll(array('RubricsCriteriaComment.rubric_id' => $rubridId));
    }

    /**
     * Function to return the criteria comments of a rubric.
     *
     * @param ARRAY $rubric $rubric obtained from Rubric->find(...)
     *
     * @access public
     * @return void
     */
    function getCriteriaComment($rubric = null)
    {
        $id = $rubric['Rubric']['id'];
        $criteria = $rubric['RubricsCriteria'];
        $lom = $rubric['RubricsLom'];
        $tmp = null;

        for ($i = 0; $i<count($criteria); $i++) {
            $criteria_Id = $criteria[$i]['id'];
            for ($j = 0; $j<count($lom); $j++) {
                $lomId = $lom[$j]['id'];
                $data = $this->find('all', array('conditions' => array('criteria_id' => $criteria_Id, 'rubrics_loms_id' => $lomId),
                    'fields' => array('criteria_comment')));
                if (!empty($data)) {
                    $tmp['criteria_comment_'.($i+1).'_'.($j+1)] = $data[0]['RubricsCriteriaComment']['criteria_comment'];
                } else {
                    $tmp['criteria_comment_'.($i+1).'_'.($j+1)] = null;
                }
            }
        }

        return $tmp;
    }
}
