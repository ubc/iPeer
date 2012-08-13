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
        )
    );

}
