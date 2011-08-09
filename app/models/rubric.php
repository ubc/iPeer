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
App::import('Model', 'EvaluationBase');

class Rubric extends EvaluationBase
{
  const TEMPLATE_TYPE_ID = 2;
  var $name = 'Rubric';
  // use default table
  var $useTable = null;
  var $actsAs = array('Containable', 'Traceable');
  var $hasMany = array(
                        'RubricsCriteria' => array(
                          'className' => 'RubricsCriteria',
                          'dependent' => true,
                          'order'     => array('criteria_num' => 'ASC', 'id' => 'ASC'),
                        ),
                        'RubricsLom' => array(
                          'className' => 'RubricsLom',
                          'dependent' => true,
                          'order'     => array('lom_num' => 'ASC', 'id' => 'ASC'),
                        ),
/*                        'EvaluationRubric' => array(
                          'className' => 'EvaluationRubric',
                          'dependent' => true
                        )*/
                        'Event' =>
                        array('className'   => 'Event',
                              'conditions'  => array('Event.event_template_type_id' => self::TEMPLATE_TYPE_ID),
                              'order'       => '',
                              'foreignKey'  => 'template_id',
                              'dependent'   => true,
                              'exclusive'   => false,
                              'finderSql'   => ''
                             ),
                        );

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->virtualFields['total_marks'] = sprintf('SELECT sum(multiplier) as sum FROM rubrics_criterias as rc WHERE rc.rubric_id = %s.id', $this->alias);
  }
	//sets the current userid and merges the form values into the data array
/*	function prepData($tmp=null, $userid=0){
		$tmp['data']['Rubric']['user_id'] = $userid;
		$tmp = array_merge($tmp['data']['Rubric'], $tmp['form']);
		unset($tmp['data']);

		if(empty($tmp['zero_mark']))
			$tmp['zero_mark'] = "off";

		return $tmp;
	}*/

  /**
   * saveAllWithCriteriaComment save all data passed in including criteria 
   * comment. This function also remove the associations that exists in database 
   * but not passed through parameter. 
   * 
   * @param array $data data to be saved 
   * @access public
   * @return void
   */
  function saveAllWithCriteriaComment($data) {
    // check if the we should remove some of the association records
    if(isset($data['Rubric']['id']) && !empty($data['Rubric']['id'])) {
      $rubric = $this->find('first', array('conditions' => array('id' => $data['Rubric']['id']),
                                           'contain' => array('RubricsCriteria.RubricsCriteriaComment',
                                                              'RubricsLom')));
      // check level of mastery and criteria if they should be removed
      if(null != $rubric) {
      $result = array('RubricsLom' => array(), 
                      'RubricsCriteria' => array());
      foreach(array_keys($result) as $model) {
        foreach($rubric[$model] as $in_db) {
          $found = false;
          foreach($data[$model] as $d) {
            if(isset($d['id']) && !empty($d['id']) && $in_db['id'] == $d['id']) {
              $found = true;
            }
          }

          if(!$found) {
            $result[$model][] = $in_db['id'];
          }
        }

        if(!empty($result[$model])) {
          // this should also remove related comments
          $this->{$model}->deleteAll(array($model.'.id' => $result[$model]), true);
        }
      }
    }
      // clean up LOM for criteria comments
      /*if(!empty($result['RubricsLom']['nums'])) {
        $ids = array();
        foreach($rubric['RubricsCriteria'] as $c) {
          foreach($c['RubricsCriteriaComment'] as $comment) {
            if(in_array($comment['lom_num'], $result['RubricsLom']['nums'])) {
              $ids[] = $comment['id'];
            }
          }
        }
        $this->RubricsCriteriaComment->deleteAll(array('id' => $ids), true);
      }*/
    }

    // saving the data. Because saveAll only saves direct associations. So we 
    // have to save criteria and criteria comment separately.
    
    // remove criteria array. We only save rubric and RubricsLom first.  
    
    $criterias = $data['RubricsCriteria'];
    unset($data['RubricsCriteria']);
    if(!$this->saveAll($data)) {
      //$this->errorMessage = __('Failed to save Rubric with Level of Mastery!', true);
      return false;
    }
	
    // get LOM Ids
    $loms = $this->RubricsLom->find('all', array('conditions' => array('rubric_id' => $this->id),
                                                 'fields' => array('id'),
                                                 'contain' => false));
	
    // now save the criteria with comments one by one
    foreach($criterias as $c) {
      // link the rubric with criteria
      $c['rubric_id'] = $this->id;

      // link LOM id to comments, criteria id is linked by saveAll
      for($i = 0; $i < count($c['RubricsCriteriaComment']); $i++) {
        $c['RubricsCriteriaComment'][$i]['rubrics_loms_id'] = $loms[$i]['RubricsLom']['id'];
      }

      // prepare the data format for saveAll
      $criteria_data['RubricsCriteriaComment'] = $c['RubricsCriteriaComment'];
      unset($c['RubricsCriteriaComment']);
      $criteria_data['RubricsCriteria'] = $c;

      // save 
      if(!$this->RubricsCriteria->saveAll($criteria_data)) {
        //$this->errorMessage = __('Failed to save rubric criterias!', true);
        return false;
      }
    }
    
    return true;
  }

  /**
   * Executes everytime after find is called on Rubircs, and rearranges the resulting criteria comments array
   * such that the criteria comments from the rubrics grid is ordered from left to right, top to bottom.
   * 
   * @param array $results: The result of the find->Rubric operation
   * @param boolean $primary Whether this model is being queried directly (vs. being queried as an association)
   */
  function afterFind(array $results, $primary) {
  	$return = array();
  	
    foreach($results as $r) {
      if(isset($r['RubricsCriteria']) && isset($r['RubricsLom']) &&
         isset($r['RubricsCriteria'][0]['RubricsCriteriaComment'])) {
        // order  comments
        for($i = 0; $i < count($r['RubricsCriteria']); $i++) {
          $comments = array();
          foreach($r['RubricsLom'] as $lom) {
            foreach($r['RubricsCriteria'][$i]['RubricsCriteriaComment'] as $c) {
              if($c['rubrics_loms_id'] == $lom['id']) {
                $comments[] = $c;
              }
            }
          }
          
          $r['RubricsCriteria'][$i]['RubricsCriteriaComment'] = $comments;
        }
      }
      $return[] = $r;
    }
    return $return;
  }

  /**
   * copy generate a copy of rubric with specific ID. The generated copy is 
   * clean up with out any ID in it. 
   * 
   * @param mixed $id source rubric ID
   * @access public
   * @return array copy of rubric
   */
  function copy($id) {
    $data = $this->find('first', array('conditions' => array('id' => $id),
                                       'contain' => array('RubricsCriteria.RubricsCriteriaComment',
                                                          'RubricsLom')));
    
    if(null != $data) {
      // set a new name
      $data['Rubric']['name'] = __('Copy of ', true).$data['Rubric']['name'];

      		
      // remove rubric id and other stuff
      unset($data['Rubric']['id'],
            $data['Rubric']['creator_id'],
            $data['Rubric']['created'],
            $data['Rubric']['updater_id'],
            $data['Rubric']['modified']);

      // remove lom ids
      for($i = 0; $i < count($data['RubricsLom']); $i++) {
        unset($data['RubricsLom'][$i]['rubric_id']);
        unset($data['RubricsLom'][$i]['id']);
      }
      
      // remove criteria and criteria comment ids
      for($i = 0; $i < count($data['RubricsCriteria']); $i++) {
        unset($data['RubricsCriteria'][$i]['rubric_id']);
        unset($data['RubricsCriteria'][$i]['id']);
        for($j = 0; $j < count($data['RubricsCriteria'][$i]['RubricsCriteriaComment']); $j++) {
          unset($data['RubricsCriteria'][$i]['RubricsCriteriaComment'][$j]['id']);
          unset($data['RubricsCriteria'][$i]['RubricsCriteriaComment'][$j]['criteria_id']);
          unset($data['RubricsCriteria'][$i]['RubricsCriteriaComment'][$j]['rubrics_loms_id']);
        }
      }
    }
    return $data;
  }
  
  /**
   * @param Integer $id : Rubric id
   * @return returns Rubric and all of its associated models
   */
  function getRubricById($id=null){
  	return $this->find('first', array('conditions' => array('id' => $id),
                                       'contain' => array('RubricsCriteria.RubricsCriteriaComment',
                                                     'RubricsLom')));
  }

  function compileViewData($tmp) {
    $this->RubricsLom = new RubricsLom;
    $this->RubricsCriteria = new RubricsCriteria;
    $this->RubricsCriteriaComment = new RubricsCriteriaComment;

    $data = $this->RubricsLom->getLoms($tmp['Rubric']['id'], $tmp['RubricsLom'][0]['id']);
    $tmp1 = array_merge($tmp['Rubric'],$data);

    $data = $this->RubricsCriteria->getCriteria($tmp['Rubric']['id']);
    $tmp2 = array_merge($tmp1,$data);

    // add some empty ones if needed
    $criteria_count = count($data)/2;

    for(;$criteria_count < $tmp['Rubric']['criteria'];$criteria_count++) {
        $tmp2['criteria'.($criteria_count+1)] = '';
        $tmp2['criteria_weight_'.($criteria_count+1)] = 1;
    }

    $data = $this->RubricsCriteriaComment->getCriteriaComment($tmp);
    $tmp3 = array_merge($tmp2,$data);

    /* Now, replace all the elements of this database array with the submitted array,
       unless there are missing */
    $submitedRubric = $tmp['Rubric'];
    foreach ($tmp3 as $key => $value) {

        if (!empty($submitedRubric[$key])) {
            //echo "<b>$key Replacing $tmp3[$key] with $submitedRubric[$key]</b><br />";
            $tmp3[$key] = $submitedRubric[$key]; // Copy the submited value over, overwriting the one got from the database.
        }
    }
    return $tmp3;
  } 	
}
?>
