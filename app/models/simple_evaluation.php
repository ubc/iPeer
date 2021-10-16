<?php
App::import('Model', 'EvaluationBase');
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

/**
 * SimpleEvaluation
 *
 * @uses EvaluationBase
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SimpleEvaluation extends EvaluationBase
{
    const TEMPLATE_TYPE_ID = 1;
    public $name = 'SimpleEvaluation';
    public $actsAs = array('Containable', 'Traceable');
    // use default table
    public $useTable = null;

    public $hasMany = array(
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
        'point_per_member' => array(
            'valid' => array(
                'rule' => array('comparison', '>', 0),
                'message' => 'Please enter a positive integer.'
            ),
        ),
    );



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
        $eval = $this->find('first', array('conditions' => array($this->alias.'.id' => $id), 'contain' => false));
        if ($eval) {
            $eval['Question'] = array();
        }

        return $eval;
    }

    /**
     * Called after each successful save operation.
     *
     * @param boolean $created True if this save created a new record
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#afterSave-1053
     */
    function afterSave($created) {
        parent::afterSave($created);
        CaliperHooks::simple_evaluation_after_save($this, $created);
    }


    /**
     * Called after every deletion operation.
     *
     * @access public
     * @link http://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Models.html#Callback-Methods#afterDelete-1055
     */
	function afterDelete() {
        parent::afterDelete();
        CaliperHooks::simple_evaluation_after_delete($this);
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
        CaliperHooks::simple_evaluation_before_delete($this);
        return parent::beforeDelete($cascade);
	}
}
