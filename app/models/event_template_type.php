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
 * @lastmodified $Date: 2006/06/20 18:44:17 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * SysParameter
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class EventTemplateType extends AppModel
{
  var $name = 'EventTemplateType';
  var $displayField = 'type_name';
  var $actsAs = array('Traceable');

  /**
   * Return a list of the event templates
   * @param type_BOOLEAN $onlyDisplayForSelection : TRUE returns entries only for display_for_selection==1
   * 												FALSE returns ALL entries
   */
  function getEventTemplateTypeList($onlyDisplayForSelection = true) {
    $onlyDisplayForSelection? $conditions['EventTemplateType.display_for_selection'] = '1': $conditions = array();
    return $this->find('list', array(
        'conditions'=> $conditions,
        'order' => 'EventTemplateType.id'
    ));
  }

  // Function is obsolete.
  /*function getEventType($eventTemplateTypeId, $field='type_name') {
    $eventTemplate = $this->find('first', array(
        'conditions' => array('EventTemplateType.id' => $eventTemplateTypeId)
    ));
    return $eventTemplate['EventTemplateType'][$field];
  }*/
}

?>