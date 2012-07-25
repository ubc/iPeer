<?php
/**
 * EventTemplateType
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EventTemplateType extends AppModel
{
    public $name = 'EventTemplateType';
    public $displayField = 'type_name';
    public $actsAs = array('Traceable');

    public $hasMany = array(
        'Event' => array(
            'className' => 'Event'
        )
    );

    /**
     * Return a list of the event templates
     *
     * @param type_BOOLEAN $onlyDisplayForSelection : TRUE returns entries only for display_for_selection==1
     * FALSE returns ALL entries
     *
     * @access public
     * @return array
     */
    function getEventTemplateTypeList($onlyDisplayForSelection = true)
    {
        $onlyDisplayForSelection? $conditions['EventTemplateType.display_for_selection'] = '1': $conditions = array();
        return $this->find('list', array(
            'conditions'=> $conditions,
            'order' => 'EventTemplateType.id'
        ));
    }
}
