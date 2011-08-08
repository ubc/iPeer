<?php
/* SVN FILE: $Id: rubrics_lom.php 600 2011-06-23 17:49:48Z tonychiu $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 600 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/08/30 16:51:39 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * RubricLom
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class RubricsLom extends AppModel
{
  var $name = 'RubricsLom';

  var $belongsTo = array( 'Rubric' => array(
                     'className' => 'Rubric'
                   ));

  var $hasMany = array( 'RubricsCriteriaComment' => array(
                    'className' => 'RubricsCriteriaComment',
                    'foreignKey' => 'rubrics_loms_id',
                    'dependent' => true,
                    'exclusive' => true,
                    ));

  var $actsAs = array('Containable');

  var $order = array('RubricsLom.lom_num' => 'ASC', 'RubricsLom.id' => 'ASC');
  
  function getLoms($rubricId=null, $lomId=null){
    return $this->find('all', array(
        'conditions' => array('RubricsLom.id' => $lomId, 'RubricsLom.rubric_id' => $rubricId)
    ));
  }
}
?>