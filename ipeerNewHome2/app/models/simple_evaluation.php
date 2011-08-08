<?php
/* SVN FILE: $Id: simple_evaluation.php 517 2011-05-31 17:50:52Z compass $ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision: 517 $
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/09/25 17:31:54 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * SimpleEvaluation
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
App::import('Model', 'EvaluationBase');

class SimpleEvaluation extends EvaluationBase
{
  const TEMPLATE_TYPE_ID = 1;
  var $name = 'SimpleEvaluation';
  // use default table
  var $useTable = null;
  /*var $validate = array(
      'name' => array('rule' => 'notEmpty',
                      'required' => true,
                      'allowEmpty' => false),
      'point_per_member' => 'numeric',
  );*/

/*  var $hasMany = array(
                       'EvaluationSimple' => array(
                        'className' => 'EvaluationSimple',
                        'dependent' => true
                       )
  );*/
  var $hasMany = array(
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
}

?>
