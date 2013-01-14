<?php
App::import('Model', 'EvaluationBase');

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
            'message' => 'Please select an availability option'
        ),
        'name' => array(
            'rule' => 'isUnique',
            'message' => 'Duplicate name found. Please change the name.'
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
     * @param $id id
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
}
