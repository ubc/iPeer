<?php
/**
 * EmailMerge
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class EmailMerge extends AppModel
{
    const MERGE_START = '{{{';
    const MERGE_END   = '}}}';

    public $name = 'EmailMerge';

    public $actsAs = array();

    /**
     * Get merge fields
     *
     * @return List of merge fields
     */
    function getMergeList()
    {
        return $this->find('list', array(
            'fields' => array('EmailMerge.value', 'EmailMerge.key')
        ));
    }

    /**
     * Get field name by value
     *
     * @param string $value value
     *
     * @return field name
     */
    function getFieldAndTableNameByValue($value = '')
    {
        $table = $this->find('first', array(
            'conditions' => array('value' => $value),
            'fields' => array('table_name', 'field_name')
        ));
        return $table['EmailMerge'];
    }

    /**
     * getAllMergesWithKey
     *
     * @access public
     * @return void
     */
    function getAllMergesWithKey()
    {
        $return = array();
        $merges = $this->find('all', array('fields' => array('key', 'value', 'table_name', 'field_name')));
        foreach ($merges as $merge) {
            $return[$merge['EmailMerge']['value']] = $merge['EmailMerge'];
        }

        return $return;
    }
}
