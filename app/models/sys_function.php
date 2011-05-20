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
 * @lastmodified $Date: 2006/06/20 18:44:19 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * SysFunction
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */
class SysFunction extends AppModel
{
  var $name = 'SysFunction';

  function getAllAccessibleFunction ($role='') {
   	//return $this->find('all',"permission_type LIKE '%".$role."%' ", array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link'));
        return $this->find('all', array(
            'conditions' => array('permission_type LIKE' => '%'.$role.'%'),
            'fields' => array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link')
        ));
  }

  function getTopAccessibleFunction ($role='') {
   	//return $this->find('all',"permission_type LIKE '%".$role."%' AND parent_id = 0 ", array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link'));
      return $this->find('all', array(
          'conditions' => array('permission_type LIKE' => '%'.$role.'%', 'parent_id' => '0'),
          'fields' => array('id', 'function_code', 'function_name', 'parent_id', 'controller_name', 'url_link')
      ));
  }

  function getCountAccessibleFunction ($role='') {
   	//return $this->find(count,"permission_type LIKE '%".$role."%' ");
      return $this->find('count', array(
          'conditions' => array('permission_type LIKE' => '%'.$role.'%')
      ));
  }
}

?>
