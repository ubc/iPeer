<?php
App::import('Model', 'EvaluationBase');
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

/**
 * Rubric
 *
 * @uses EvaluationBase
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Rubric extends EvaluationBase
{
    const TEMPLATE_TYPE_ID = 2;
    public $name = 'Rubric';
    // use default table
    public $useTable = null;
    public $actsAs = array('Containable', 'Traceable');
    public $hasMany = array(
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
    
    public $validate = array(
        'availability' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select an availability option.'
        ),
        'name' => array(
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Duplicate name found. Please change the name.'
            ),
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please give the evaluation template a name.'
            )
        ),
        'lom_max' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select a Level of Mastery.'
        ),
        'criteria' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select the number of Criteria.'
        ),
        'view_mode' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select a view mode.'
        ),
    );

    /**
     * __construct
     *
     * @param bool $id    id
     * @param bool $table table
     * @param bool $ds    data source
     *
     * @access protected
     * @return void
     */
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['total_marks'] = sprintf('SELECT sum(multiplier) as sum FROM rubrics_criterias as rc WHERE rc.rubric_id = %s.id', $this->alias);
    }

    //sets the current userid and merges the form values into the data array
/*	function prepData($tmp=null, $userid=0)
{
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
     *
     * @access public
     * @return void
     */
    function saveAllWithCriteriaComment($data)
    {
        $isNewRubric = !isset($data['Rubric']['id']) || empty($data['Rubric']['id']);

        # events and modified_criteria_ids are needed for caliper
        $events = array();
        $modified_criteria_ids = array();

        // check if the we should remove some of the association records
        if (isset($data['Rubric']['id']) && !empty($data['Rubric']['id'])) {
            $rubric = $this->find('first', array('conditions' => array('id' => $data['Rubric']['id']),
                'contain' => array('RubricsCriteria.RubricsCriteriaComment',
                'RubricsLom')));
            // check level of mastery and criteria if they should be removed
            if (null != $rubric) {
                $result = array('RubricsCriteria' => array(), 'RubricsLom' => array());
                foreach (array_keys($result) as $model) {
                    foreach ($rubric[$model] as $in_db) {
                        $found = false;
                        foreach ($data[$model] as $d) {
                            if (isset($d['id']) && !empty($d['id']) && $in_db['id'] == $d['id']) {
                                $found = true;
                            }
                        }

                        if (!$found) {
                            $result[$model][] = $in_db['id'];
                        }

                        if ($model == 'RubricsCriteria') {
                            if ($found) {
                                $modified_criteria_ids[]=$in_db['id'];
                            } else {
                                $event = CaliperHooks::rubric_delete_criteria_partial($rubric, $in_db);
                                if ($event) {
                                    $events[] = $event;
                                }
                            }
                        }
                    }
                }

                foreach (array_keys($result) as $model) {
                    if (!empty($result[$model])) {
                        // this should also remove related comments
                        $this->{$model}->deleteAll(array($model.'.id' => $result[$model]), true);
                    }
                }
            }
            // clean up LOM for criteria comments
      /*if(!empty($result['RubricsLom']['nums'])) {
        $ids = array();
        foreach ($rubric['RubricsCriteria'] as $c) {
          foreach ($c['RubricsCriteriaComment'] as $comment) {
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
        if (!$this->saveAll($data)) {
            //$this->errorMessage = __('Failed to save Rubric with Level of Mastery!', true);
            return false;
        }

        // get LOM Ids
        $loms = $this->RubricsLom->find('all', array('conditions' => array('rubric_id' => $this->id),
            'fields' => array('id'),
            'contain' => false));

        // now save the criteria with comments one by one
        foreach ($criterias as $c) {
            // link the rubric with criteria
            $c['rubric_id'] = $this->id;

            // link LOM id to comments, criteria id is linked by saveAll
            for ($i = 0; $i < count($c['RubricsCriteriaComment']); $i++) {
                $c['RubricsCriteriaComment'][$i]['rubrics_loms_id'] = $loms[$i]['RubricsLom']['id'];
            }

            // prepare the data format for saveAll
            $criteria_data['RubricsCriteriaComment'] = $c['RubricsCriteriaComment'];
            unset($c['RubricsCriteriaComment']);
            $criteria_data['RubricsCriteria'] = $c;

            // save
            if (!$this->RubricsCriteria->saveAll($criteria_data)) {
                //$this->errorMessage = __('Failed to save rubric criterias!', true);
                return false;
            }
        }

        // can't use after save hook due to the way the criteria are stored
        CaliperHooks::rubric_save_with_criteria($this, $events, $modified_criteria_ids, $isNewRubric);

        return true;
    }

    /**
     * Executes everytime after find is called on Rubircs, and rearranges the resulting criteria comments array
     * such that the criteria comments from the rubrics grid is ordered from left to right, top to bottom.
     *
     * @param array $results The result of the find->Rubric operation
     // * @param boolean $primary Whether this model is being queried directly (vs. being queried as an association)
     *
     * @access public
     * @return void
     */
    function afterFind($results, $primary = false)
    {
        $return = array();

        foreach ($results as $r) {
            if (isset($r['RubricsCriteria']) && isset($r['RubricsLom']) &&
                isset($r['RubricsCriteria'][0]['RubricsCriteriaComment'])) {
                    // order  comments
                    for ($i = 0; $i < count($r['RubricsCriteria']); $i++) {
                        $comments = array();
                        foreach ($r['RubricsLom'] as $lom) {
                            foreach ($r['RubricsCriteria'][$i]['RubricsCriteriaComment'] as $c) {
                                if ($c['rubrics_loms_id'] == $lom['id']) {
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
     *
     * @access public
     * @return array copy of rubric
     */
    function copy($id)
    {
        $data = $this->find('first', array('conditions' => array('id' => $id),
            'contain' => array('RubricsCriteria.RubricsCriteriaComment',
            'RubricsLom')));

        if (null != $data) {
            // set a new name
            $data['Rubric']['name'] = __('Copy of ', true).$data['Rubric']['name'];


            // remove rubric id and other stuff
            unset($data['Rubric']['id'],
                $data['Rubric']['creator_id'],
                $data['Rubric']['created'],
                $data['Rubric']['updater_id'],
                $data['Rubric']['modified']);

            // remove lom ids
            for ($i = 0; $i < count($data['RubricsLom']); $i++) {
                unset($data['RubricsLom'][$i]['rubric_id']);
                unset($data['RubricsLom'][$i]['id']);
            }

            // remove criteria and criteria comment ids
            for ($i = 0; $i < count($data['RubricsCriteria']); $i++) {
                unset($data['RubricsCriteria'][$i]['rubric_id']);
                unset($data['RubricsCriteria'][$i]['id']);
                for ($j = 0; $j < count($data['RubricsCriteria'][$i]['RubricsCriteriaComment']); $j++) {
                    unset($data['RubricsCriteria'][$i]['RubricsCriteriaComment'][$j]['id']);
                    unset($data['RubricsCriteria'][$i]['RubricsCriteriaComment'][$j]['criteria_id']);
                    unset($data['RubricsCriteria'][$i]['RubricsCriteriaComment'][$j]['rubrics_loms_id']);
                }
            }
        }
        return $data;
    }

    /**
     * getRubricById
     *
     * @param mixed $id rubric id
     *
     * @access public
     * @return returns Rubric and all of its associated models
     */
    function getRubricById($id=null)
    {
        return $this->find('first', array('conditions' => array('id' => $id),
            'contain' => array('RubricsCriteria.RubricsCriteriaComment',
            'RubricsLom')));
    }


    /**
     * compileViewData
     *
     * @param mixed $tmp
     *
     * @access public
     * @return void
     */
    function compileViewData($tmp)
    {
        $this->RubricsLom = new RubricsLom;
        $this->RubricsCriteria = new RubricsCriteria;
        $this->RubricsCriteriaComment = new RubricsCriteriaComment;

        $data = $this->RubricsLom->getLoms($tmp['Rubric']['id'], $tmp['RubricsLom'][0]['id']);
        $tmp1 = array_merge($tmp['Rubric'], $data);

        $data = $this->RubricsCriteria->getCriteria($tmp['Rubric']['id']);
        $tmp2 = array_merge($tmp1, $data);

        // add some empty ones if needed
        $criteria_count = count($data)/2;

        for (; $criteria_count < $tmp['Rubric']['criteria']; $criteria_count++) {
            $tmp2['criteria'.($criteria_count+1)] = '';
            $tmp2['criteria_weight_'.($criteria_count+1)] = 1;
        }

        $data = $this->RubricsCriteriaComment->getCriteriaComment($tmp);
        $tmp3 = array_merge($tmp2, $data);

    /* Now, replace all the elements of this database array with the submitted array, unless there are missing */
        $submittedRubric = $tmp['Rubric'];
        $keys = array_keys($tmp3);
        foreach ($keys as $key) {

            if (!empty($submittedRubric[$key])) {
                //echo "<b>$key Replacing $tmp3[$key] with $submittedRubric[$key]</b><br />";
                $tmp3[$key] = $submittedRubric[$key]; // Copy the submitted value over, overwriting the one got from the database.
            }
        }
        return $tmp3;
    }

    /**
     * getEvaluation
     *
     * @param mixed $id id
     * 
     * @access public
     * @return void
     */
    public function getEvaluation($id)
    {
        $eval = $this->find('first', array('conditions' => array($this->alias.'.id' => $id), 'contain' => 'RubricsCriteria'));
        if ($eval) {
            $eval['Question'] = $eval['RubricsCriteria'];
            unset($eval['RubricsCriteria']);
        }

        return $eval;
    }

    /**
     * Called after every deletion operation.
     *
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#afterDelete-1055
     */
	function afterDelete() {
        parent::afterDelete();

        CaliperHooks::rubric_after_delete($this);
	}


    /**
     * Called before every deletion operation.
     *
     * @param boolean $cascade If true records that depend on this record will also be deleted
     * @return boolean True if the operation should continue, false if it should abort
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#beforeDelete-1054
     */
	function beforeDelete($cascade = true) {
        CaliperHooks::rubric_before_delete($this);
        return parent::beforeDelete($cascade);
	}
}
