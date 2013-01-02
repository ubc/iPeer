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
        return $this->find('all', array(
            'conditions' => array('RubricsCriteria.rubric_id' => $rubric_id),
            'order' => array('criteria_num ASC'),
            'contain' => false,
        ));
    }
}
