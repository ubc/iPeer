<?php
/**
 * RubricsLom
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class RubricsLom extends AppModel
{
    public $name = 'RubricsLom';

    public $belongsTo = array( 'Rubric' => array(
        'className' => 'Rubric'
    ));

    public $hasMany = array( 'RubricsCriteriaComment' => array(
        'className' => 'RubricsCriteriaComment',
        'foreignKey' => 'rubrics_loms_id',
        'dependent' => true,
        'exclusive' => true,
    ));

    public $actsAs = array('Containable');

    public $order = array('RubricsLom.lom_num' => 'ASC', 'RubricsLom.id' => 'ASC');

    /**
     * getLoms
     *
     * @param bool $rubricId rubric id
     * @param bool $lomId    lom id
     *
     * @access public
     * @return void
     */
    function getLoms($rubricId=null, $lomId=null)
    {
        return $this->find('all', array(
            'conditions' => array('RubricsLom.id' => $lomId, 'RubricsLom.rubric_id' => $rubricId),
            'contain' => false,
        ));
    }
}
